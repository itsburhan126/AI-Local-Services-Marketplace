<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $booking->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            background: #fff;
        }
        .header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 2px solid #6366f1;
            padding-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            vertical-align: top;
            width: 60%;
        }
        .header-right {
            display: table-cell;
            vertical-align: top;
            width: 40%;
            text-align: right;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            margin: 0;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #6366f1;
            text-transform: uppercase;
            margin: 0;
        }
        .invoice-details {
            margin-top: 10px;
            color: #64748b;
        }
        .info-table {
            width: 100%;
            margin-bottom: 40px;
        }
        .info-col {
            width: 50%;
            vertical-align: top;
        }
        .info-label {
            font-size: 11px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .info-content {
            font-size: 15px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 2px;
        }
        .info-sub {
            font-size: 13px;
            color: #64748b;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            padding: 12px 15px;
            background: #f8fafc;
            color: #475569;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .items-table .item-name {
            font-weight: bold;
            color: #1e293b;
        }
        .items-table .item-desc {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .total-section {
            width: 100%;
            display: table;
        }
        .total-right {
            display: table-cell;
            width: 40%;
            float: right;
        }
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .total-label {
            display: table-cell;
            text-align: right;
            padding-right: 20px;
            color: #64748b;
        }
        .total-value {
            display: table-cell;
            text-align: right;
            width: 120px;
            font-weight: bold;
            color: #1e293b;
        }
        .grand-total {
            border-top: 2px solid #6366f1;
            padding-top: 15px;
            margin-top: 10px;
        }
        .grand-total .total-label {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
        }
        .grand-total .total-value {
            font-size: 20px;
            color: #6366f1;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background: #f1f5f9;
            color: #475569;
            margin-top: 10px;
        }
        .status-paid { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef9c3; color: #854d0e; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="header-left">
                <h1 class="company-name">{{ \App\Models\Setting::get('company_name', config('app.name')) }}</h1>
                <div class="invoice-details">
                    {{ \App\Models\Setting::get('company_address', 'Marketplace Platform') }}<br>
                    {{ \App\Models\Setting::get('company_email', 'support@example.com') }}<br>
                    {{ \App\Models\Setting::get('company_phone', '') }}
                </div>
            </div>
            <div class="header-right">
                <h2 class="invoice-title">Invoice</h2>
                <div class="invoice-details">
                    <strong>Invoice #:</strong> INV-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}<br>
                    <strong>Date:</strong> {{ $booking->created_at->format('M d, Y') }}<br>
                    <span class="status-badge {{ $booking->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                        {{ ucfirst($booking->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <table class="info-table">
            <tr>
                <td class="info-col">
                    <div class="info-label">Bill To (Customer)</div>
                    <div class="info-content">{{ $booking->user->name }}</div>
                    <div class="info-sub">{{ $booking->user->email }}</div>
                    @if($booking->user->phone)
                    <div class="info-sub">{{ $booking->user->phone }}</div>
                    @endif
                    <div class="info-sub" style="margin-top: 5px;">{{ $booking->address }}</div>
                </td>
                <td class="info-col">
                    <div class="info-label">Service Provider</div>
                    <div class="info-content">{{ $booking->provider->name }}</div>
                    <div class="info-sub">{{ $booking->provider->providerProfile->company_name ?? 'Professional Service Provider' }}</div>
                    <div class="info-sub">{{ $booking->provider->email }}</div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">Description</th>
                    <th width="20%">Date</th>
                    <th width="15%">Duration</th>
                    <th width="15%" style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="item-name">{{ $booking->service->name }}</div>
                        <div class="item-desc">{{ Str::limit($booking->service->description, 100) }}</div>
                    </td>
                    <td>{{ $booking->scheduled_at->format('M d, Y') }}<br><span style="font-size: 11px; color: #999;">{{ $booking->scheduled_at->format('h:i A') }}</span></td>
                    <td>{{ $booking->service->duration_minutes }} mins</td>
                    <td style="text-align: right;">{{ \App\Models\Setting::get('currency_symbol', '$') }}{{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-right">
                <div class="total-row">
                    <div class="total-label">Subtotal</div>
                    <div class="total-value">{{ \App\Models\Setting::get('currency_symbol', '$') }}{{ number_format($booking->total_amount, 2) }}</div>
                </div>
                <!-- Add Tax or other fees here if available in the model -->
                <div class="total-row grand-total">
                    <div class="total-label">Total</div>
                    <div class="total-value">{{ \App\Models\Setting::get('currency_symbol', '$') }}{{ number_format($booking->total_amount, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for choosing {{ \App\Models\Setting::get('company_name', config('app.name')) }}!</p>
            <p>If you have any questions about this invoice, please contact support.</p>
        </div>
    </div>
</body>
</html>