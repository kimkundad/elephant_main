<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Booking;
use Illuminate\Http\Request;

class AgentReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $paymentFilter = $request->query('payment_status');

        $dateFilter = function ($q) use ($start, $end, $paymentFilter) {
            if ($start) {
                $q->whereDate('date', '>=', $start);
            }
            if ($end) {
                $q->whereDate('date', '<=', $end);
            }
            if ($paymentFilter === 'paid') {
                $q->where('payment_status', 'paid');
            } elseif ($paymentFilter === 'unpaid') {
                $q->where('payment_status', '!=', 'paid');
            }
        };

        $agents = Agent::orderBy('name')->get();

        $summary = $agents->map(function (Agent $agent) use ($dateFilter) {
            $bookings = Booking::where('agent_id', $agent->id)
                ->where($dateFilter)
                ->get();
            $paid = $bookings->where('payment_status', 'paid');
            $unpaid = $bookings->where('payment_status', '!=', 'paid');
            return [
                'agent' => $agent,
                'total_sales' => $paid->sum('grand_total'),
                'total_discount' => $paid->sum('discount_amount'),
                'booking_count' => $bookings->count(),
                'paid_count' => $paid->count(),
                'unpaid_count' => $unpaid->count(),
            ];
        });

        $bookingsWithDiscount = Booking::with(['tour', 'session', 'agent'])
            ->whereNotNull('discount_code')
            ->where($dateFilter)
            ->orderByDesc('id')
            ->get();

        return view('admin.reports.agents', compact('summary', 'bookingsWithDiscount', 'start', 'end', 'paymentFilter'));
    }

    public function exportCsv(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $paymentFilter = $request->query('payment_status');

        $query = Booking::with(['tour', 'session', 'agent'])
            ->whereNotNull('discount_code');

        if ($start) {
            $query->whereDate('date', '>=', $start);
        }
        if ($end) {
            $query->whereDate('date', '<=', $end);
        }
        if ($paymentFilter === 'paid') {
            $query->where('payment_status', 'paid');
        } elseif ($paymentFilter === 'unpaid') {
            $query->where('payment_status', '!=', 'paid');
        }

        $rows = $query->orderByDesc('id')->get();

        $filename = 'agent-discount-report.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'Booking ID',
                'Tour Date',
                'Tour',
                'Customer',
                'Discount Code',
                'Discount Amount',
                'Agent',
                'Total',
                'Payment Status',
            ]);

            foreach ($rows as $b) {
                fputcsv($out, [
                    $b->id,
                    $b->date,
                    $b->tour?->name,
                    $b->customer_name ?? $b->customer?->full_name,
                    $b->discount_code,
                    $b->discount_amount,
                    $b->agent?->name,
                    $b->grand_total,
                    $b->payment_status,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
