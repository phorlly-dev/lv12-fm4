<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <link rel="stylesheet" href="{{ public_path('css/invoice.css') }}">

</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">Your Company Name</div>
            <div>123 Business Street, City, State 12345</div>
            <div>Phone: (555) 123-4567 | Email: info@company.com</div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <table>
                <tr>
                    <td class="invoice-left">
                        <h2 style="margin: 0 0 10px 0;">INVOICE</h2>
                        <strong>#{{ $invoice->invoice_number }}</strong><br>
                        <strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}<br>
                        <strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}<br>
                        <strong>Status:</strong> {{ ucfirst($invoice->status) }}
                    </td>
                    <td class="invoice-right">
                        <!-- Company logo space -->
                    </td>
                </tr>
            </table>
        </div>

        <!-- Customer Details -->
        <div class="customer-details">
            <strong>Bill To:</strong><br>
            <strong>{{ $invoice->customer_name }}</strong><br>
            {{ $invoice->customer_email }}<br>
            {!! nl2br(e($invoice->customer_address)) !!}
        </div>

        <!-- Invoice Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">№</th> <!-- New column for index -->
                    <th style="width: 50%">Description</th>
                    <th style="width: 15%" class="text-center">Quantity</th>
                    <th style="width: 17.5%" class="text-right">Unit Price</th>
                    <th style="width: 17.5%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td> <!-- Index -->
                        <td>{{ $item->description }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">${{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                @if ($invoice->tax_rate > 0)
                    <tr>
                        <td class="label">Tax ({{ $invoice->tax_rate }}%):</td>
                        <td class="amount">${{ number_format($invoice->tax_amount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td class="label">TOTAL:</td>
                    <td class="amount">${{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Generated on {{ now()->format('M d, Y \a\t g:i A') }}</p>
        </div>
    </div>
</body>

</html>
