<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 30px 40px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .top-section {
            margin-bottom: 25px;
        }

        .row {
            width: 100%;
            display: table;
        }

        .col-6 {
            width: 50%;
            display: table-cell;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            margin-top: 20px;
        }

        .invoice-header {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .invoice-header strong {
            display: inline-block;
            width: 130px;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 25px;
        }

        .item-table th {
            text-align: left;
            border-bottom: 2px solid #999;
            padding-bottom: 6px;
            font-weight: bold;
        }

        .item-table td {
            padding: 6px 0;
            border-bottom: 1px solid #ddd;
        }

        .summary-table {
            width: 250px;
            float: right;
            margin-top: 10px;
        }

        .summary-table td {
            padding: 5px 0;
        }

        .summary-table tr.total-row td {
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
        }

        .amount-due {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .pay-link {
            margin-top: 10px;
        }

        .pay-link a {
            color: #0066ff;
            text-decoration: none;
        }

        .footer-text {
            margin-top: 40px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
<div class="container">

    <h1>Invoice</h1>

    {{-- INVOICE HEADER --}}
    <div class="invoice-header">
        <strong>Invoice number:</strong> INV-{{ $booking->id }}<br>
        <strong>Date of issue:</strong> {{ date('F d, Y') }}<br>
        <strong>Date due:</strong> {{ date('F d, Y', strtotime($booking->date)) }}
    </div>

    <div class="row">
        {{-- FROM --}}
        <div class="col-6">
            <div class="label">From</div>
            <p>
                Elephant Sanctuary Co., Ltd.<br>
                Chiang Mai, Thailand<br>
                info@elephant.com<br>
                Phone: 090-000-0000
            </p>
        </div>

        {{-- BILL TO --}}
        <div class="col-6">
            <div class="label">Bill to</div>
            <p>
                {{ $booking->customer->full_name }}<br>
                {{ $booking->customer->email }}<br>
                {{ $booking->customer->phone }}<br>
                Thailand
            </p>
        </div>
    </div>

    {{-- AMOUNT DUE --}}
    <div class="amount-due">
        THB {{ number_format($booking->grand_total, 2) }} due {{ $booking->date }}
    </div>

    <div class="pay-link">
        <a href="#">Pay online</a>
    </div>

    {{-- ITEMS --}}
    <table class="item-table">
        <tr>
            <th>Description</th>
            <th style="width:60px;">Qty</th>
            <th style="width:80px;">Unit price</th>
            <th style="width:80px;">Amount</th>
        </tr>

        <tr>
            <td>
                {{ $booking->tour->name }}<br>
                {{ $booking->session->title }} ({{ $booking->session->start_time }} - {{ $booking->session->end_time }})<br>
                Date: {{ $booking->date }}
            </td>
            <td>1</td>
            <td>THB {{ number_format($booking->subtotal, 2) }}</td>
            <td>THB {{ number_format($booking->subtotal, 2) }}</td>
        </tr>
    </table>

    {{-- SUMMARY --}}
    <table class="summary-table">
        <tr>
            <td>Subtotal</td>
            <td style="text-align:right;">THB {{ number_format($booking->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>VAT (7%)</td>
            <td style="text-align:right;">THB {{ number_format($booking->vat_amount, 2) }}</td>
        </tr>
        <tr>
            <td>Fees (5%)</td>
            <td style="text-align:right;">THB {{ number_format($booking->fee_amount, 2) }}</td>
        </tr>

        <tr class="total-row">
            <td>Total</td>
            <td style="text-align:right;">THB {{ number_format($booking->grand_total, 2) }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    {{-- FOOTER --}}
    <div class="footer-text">
        Thank you for your booking.
        For questions, contact us at support@example.com
    </div>
</div>
</body>
</html>
