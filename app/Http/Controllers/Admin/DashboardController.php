<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\IntegrationLog;
use App\Models\Review;
use App\Models\TourAvailability;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /** Bookings that were cancelled never count towards any number here. */
    private const LIVE_STATUSES = ['pending', 'confirmed'];

    public function index()
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $lastMonthStart = $monthStart->copy()->subMonth();

        return view('admin.dashboard', [
            'kpi' => $this->kpis($today, $monthStart, $lastMonthStart),
            'chart' => $this->monthlyChart($today),
            'upcoming' => $this->upcomingDepartures($today),
            'recentBookings' => Booking::with(['tour', 'session'])
                ->orderByDesc('id')
                ->limit(8)
                ->get(),
            'topTours' => $this->topTours($monthStart),
            'channels' => $this->splitBy('payment_channel', $monthStart),
            'provinces' => $this->provinceSplit($monthStart),
            'attention' => $this->attention($today),
        ]);
    }

    /** @return array<string, mixed> */
    private function kpis(Carbon $today, Carbon $monthStart, Carbon $lastMonthStart): array
    {
        $revenueThisMonth = $this->revenueBetween($monthStart, $today->copy()->endOfDay());
        $revenueLastMonth = $this->revenueBetween($lastMonthStart, $monthStart->copy()->subSecond());

        $bookingsThisMonth = $this->liveBookings()->where('created_at', '>=', $monthStart)->count();
        $bookingsLastMonth = $this->liveBookings()
            ->whereBetween('created_at', [$lastMonthStart, $monthStart->copy()->subSecond()])
            ->count();

        return [
            'revenue' => $revenueThisMonth,
            'revenue_change' => $this->percentChange($revenueThisMonth, $revenueLastMonth),
            'bookings' => $bookingsThisMonth,
            'bookings_change' => $this->percentChange($bookingsThisMonth, $bookingsLastMonth),
            'guests_today' => (int) $this->liveBookings()->whereDate('date', $today)->sum('total_guests'),
            'departures_today' => $this->liveBookings()->whereDate('date', $today)->count(),
            'checked_in_today' => $this->liveBookings()->whereDate('date', $today)->whereNotNull('checked_in_at')->count(),
            'unpaid' => $this->liveBookings()->whereIn('payment_status', ['pending', 'awaiting_qr'])->count(),
            'unpaid_amount' => (float) $this->liveBookings()
                ->whereIn('payment_status', ['pending', 'awaiting_qr'])
                ->sum('grand_total'),
        ];
    }

    /**
     * Revenue and booking counts for the last 12 months, grouped in PHP so the
     * query works the same on MySQL and on the sqlite test database.
     *
     * @return array<string, mixed>
     */
    private function monthlyChart(Carbon $today): array
    {
        $start = $today->copy()->startOfMonth()->subMonths(11);

        $months = collect(range(0, 11))
            ->map(fn (int $i) => $start->copy()->addMonths($i))
            ->mapWithKeys(fn (Carbon $month) => [$month->format('Y-m') => [
                'label' => $month->locale('th')->isoFormat('MMM'),
                'revenue' => 0.0,
                'bookings' => 0,
            ]]);

        Booking::query()
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', $start)
            ->get(['paid_at', 'grand_total'])
            ->each(function (Booking $booking) use (&$months) {
                $key = Carbon::parse($booking->paid_at)->format('Y-m');
                if ($months->has($key)) {
                    $months[$key] = array_replace($months[$key], [
                        'revenue' => $months[$key]['revenue'] + (float) $booking->grand_total,
                    ]);
                }
            });

        $this->liveBookings()
            ->where('created_at', '>=', $start)
            ->get(['created_at'])
            ->each(function (Booking $booking) use (&$months) {
                $key = $booking->created_at->format('Y-m');
                if ($months->has($key)) {
                    $months[$key] = array_replace($months[$key], [
                        'bookings' => $months[$key]['bookings'] + 1,
                    ]);
                }
            });

        $rows = $months->values();

        return [
            'rows' => $rows,
            'max_revenue' => max(1, (float) $rows->max('revenue')),
            'max_bookings' => max(1, (int) $rows->max('bookings')),
        ];
    }

    /** Departures for today and tomorrow, with how full each session is. */
    private function upcomingDepartures(Carbon $today): Collection
    {
        $dates = [$today->toDateString(), $today->copy()->addDay()->toDateString()];

        return $this->liveBookings()
            ->with(['tour', 'session'])
            ->whereIn('date', $dates)
            ->get()
            ->groupBy(fn (Booking $booking) => $booking->date . '|' . $booking->session_id)
            ->map(function (Collection $bookings) {
                $first = $bookings->first();
                $date = (string) $first->date;
                $capacity = $this->capacityFor($first, $date);
                $guests = (int) $bookings->sum('total_guests');

                return [
                    'date' => $date,
                    'tour' => $first->tour?->name ?? '-',
                    'session' => $first->session?->title ?? $first->session?->name ?? '-',
                    'time' => $first->session?->time_range ?? '-',
                    'guests' => $guests,
                    'capacity' => $capacity,
                    'percent' => $capacity > 0 ? min(100, (int) round($guests / $capacity * 100)) : 0,
                    'bookings' => $bookings->count(),
                    'checked_in' => $bookings->whereNotNull('checked_in_at')->count(),
                ];
            })
            ->sortBy([['date', 'asc'], ['time', 'asc']])
            ->values();
    }

    private function capacityFor(Booking $booking, string $date): int
    {
        $session = $booking->session;
        if (!$session) {
            return 0;
        }

        $availability = TourAvailability::where('tour_id', $booking->tour_id)
            ->where('session_id', $session->id)
            ->whereDate('date', $date)
            ->first();

        if ($availability && $availability->capacity_override !== null) {
            return (int) $availability->capacity_override;
        }

        return (int) ($session->capacity ?? $session->default_capacity ?? 0);
    }

    private function topTours(Carbon $monthStart): Collection
    {
        return $this->liveBookings()
            ->with('tour.province')
            ->where('created_at', '>=', $monthStart)
            ->get()
            ->groupBy('tour_id')
            ->map(fn (Collection $bookings) => [
                'name' => $bookings->first()->tour?->name ?? '-',
                'province' => $bookings->first()->tour?->province?->name_th,
                'bookings' => $bookings->count(),
                'guests' => (int) $bookings->sum('total_guests'),
                'revenue' => (float) $bookings->where('payment_status', 'paid')->sum('grand_total'),
            ])
            ->sortByDesc('bookings')
            ->take(5)
            ->values();
    }

    /** Share of this month's bookings per column value, biggest first. */
    private function splitBy(string $column, Carbon $monthStart): Collection
    {
        $bookings = $this->liveBookings()->where('created_at', '>=', $monthStart)->get([$column]);
        $total = max(1, $bookings->count());

        return $bookings
            ->groupBy(fn (Booking $booking) => $booking->{$column} ?: 'unknown')
            ->map(fn (Collection $rows, string $key) => [
                'label' => $key,
                'count' => $rows->count(),
                'percent' => (int) round($rows->count() / $total * 100),
            ])
            ->sortByDesc('count')
            ->values();
    }

    private function provinceSplit(Carbon $monthStart): Collection
    {
        $bookings = $this->liveBookings()
            ->with('tour.province')
            ->where('created_at', '>=', $monthStart)
            ->get();
        $total = max(1, $bookings->count());

        return $bookings
            ->groupBy(fn (Booking $booking) => $booking->tour?->province?->name_th ?: 'ไม่ระบุ')
            ->map(fn (Collection $rows, string $key) => [
                'label' => $key,
                'count' => $rows->count(),
                'percent' => (int) round($rows->count() / $total * 100),
            ])
            ->sortByDesc('count')
            ->values();
    }

    /** @return array<string, mixed> */
    private function attention(Carbon $today): array
    {
        return [
            'reviews_pending' => Review::where('is_active', false)->count(),
            'contacts_new' => Contact::where('created_at', '>=', $today->copy()->subDays(7))->count(),
            'errors' => IntegrationLog::where('level', 'error')
                ->where('created_at', '>=', $today->copy()->subDays(7))
                ->orderByDesc('id')
                ->limit(5)
                ->get(['id', 'channel', 'event', 'message', 'created_at']),
        ];
    }

    private function liveBookings()
    {
        return Booking::whereIn('status', self::LIVE_STATUSES);
    }

    private function revenueBetween(Carbon $from, Carbon $to): float
    {
        return (float) Booking::where('payment_status', 'paid')
            ->whereBetween('paid_at', [$from, $to])
            ->sum('grand_total');
    }

    private function percentChange(float $current, float $previous): ?float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : null;
        }

        return round(($current - $previous) / $previous * 100, 1);
    }
}
