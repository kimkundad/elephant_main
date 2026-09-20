<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Sales are money that actually arrived, so every sales figure counts only
 * bookings with payment_status = paid and is dated by paid_at, not by the
 * tour date. Booking counts use the date the booking was made.
 */
class AgentReportController extends Controller
{
    public function index(Request $request)
    {
        [$start, $end, $paymentFilter] = $this->filters($request);

        $summary = Agent::orderBy('name')->get()->map(function (Agent $agent) use ($start, $end) {
            $paid = $this->paidSales($start, $end)->where('agent_id', $agent->id)->get();
            $booked = $this->bookingsMade($start, $end)->where('agent_id', $agent->id)->get();

            return [
                'agent' => $agent,
                'total_sales' => (float) $paid->sum('grand_total'),
                'total_discount' => (float) $paid->sum('discount_amount'),
                'paid_count' => $paid->count(),
                'booking_count' => $booked->count(),
                'unpaid_count' => $booked->where('payment_status', '!=', 'paid')->count(),
            ];
        });

        $bookingsWithDiscount = $this->discountList($start, $end, $paymentFilter)->get();

        return view('admin.reports.agents', compact('summary', 'bookingsWithDiscount', 'start', 'end', 'paymentFilter'));
    }

    public function exportCsv(Request $request)
    {
        [$start, $end, $paymentFilter] = $this->filters($request);

        $rows = $this->discountList($start, $end, $paymentFilter)->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, [
                'Booking ID',
                'Booked At',
                'Paid At',
                'Tour Date',
                'Tour',
                'Customer',
                'Discount Code',
                'Discount Amount',
                'Agent',
                'Total',
                'Payment Status',
                'Booking Status',
            ]);

            foreach ($rows as $b) {
                fputcsv($out, [
                    $b->id,
                    $b->created_at?->format('Y-m-d H:i'),
                    $b->paid_at ? $b->paid_at->format('Y-m-d H:i') : '',
                    $b->date,
                    $b->tour?->name,
                    $b->customer_name ?? $b->customer?->full_name,
                    $b->discount_code,
                    $b->discount_amount,
                    $b->agent?->name,
                    $b->grand_total,
                    $b->payment_status,
                    $b->status,
                ]);
            }

            fclose($out);
        }, 'agent-discount-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** @return array{0: ?string, 1: ?string, 2: ?string} */
    private function filters(Request $request): array
    {
        return [
            $request->query('start_date'),
            $request->query('end_date'),
            $request->query('payment_status'),
        ];
    }

    /** Money received in the period: paid bookings, dated by payment. */
    private function paidSales(?string $start, ?string $end): Builder
    {
        return Booking::query()
            ->where('payment_status', 'paid')
            ->whereNotNull('paid_at')
            ->when($start, fn (Builder $q) => $q->whereDate('paid_at', '>=', $start))
            ->when($end, fn (Builder $q) => $q->whereDate('paid_at', '<=', $end));
    }

    /** Bookings made in the period, whatever their payment state. */
    private function bookingsMade(?string $start, ?string $end): Builder
    {
        return Booking::query()
            ->when($start, fn (Builder $q) => $q->whereDate('created_at', '>=', $start))
            ->when($end, fn (Builder $q) => $q->whereDate('created_at', '<=', $end));
    }

    private function discountList(?string $start, ?string $end, ?string $paymentFilter): Builder
    {
        $query = $paymentFilter === 'paid'
            ? $this->paidSales($start, $end)
            : $this->bookingsMade($start, $end);

        if ($paymentFilter === 'unpaid') {
            $query->where('payment_status', '!=', 'paid');
        }

        return $query
            ->with(['tour', 'session', 'agent', 'customer'])
            ->whereNotNull('discount_code')
            ->orderByDesc('id');
    }
}
