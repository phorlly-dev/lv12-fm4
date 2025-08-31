<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    @vite('resources/css/app.css') {{-- ✅ load Tailwind --}}
</head>

<body class="font-sans text-gray-800 p-8">
    <h1 class="text-2xl font-bold mb-4">Invoice #{{ $invoice->invoice_number }}</h1>

    <div class="mb-6">
        <p><span class="font-semibold">Bill To:</span> {{ $invoice->customer_name }}</p>
        <p>{{ $invoice->customer_email }}</p>
        <p>{{ $invoice->customer_address }}</p>
    </div>

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-2 py-1 text-left">#</th>
                <th class="border px-2 py-1 text-left">Description</th>
                <th class="border px-2 py-1 text-center">Quantity</th>
                <th class="border px-2 py-1 text-right">Unit Price</th>
                <th class="border px-2 py-1 text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td class="border px-2 py-1 text-center">{{ $loop->iteration }}</td>
                    <td class="border px-2 py-1">{{ $item->description }}</td>
                    <td class="border px-2 py-1 text-center">{{ $item->quantity }}</td>
                    <td class="border px-2 py-1 text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="border px-2 py-1 text-right">${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6 text-right">
        <p><span class="font-semibold">Subtotal:</span> ${{ number_format($invoice->subtotal, 2) }}</p>
        <p><span class="font-semibold">Tax ({{ $invoice->tax_rate }}%):</span>
            ${{ number_format($invoice->tax_amount, 2) }}</p>
        <p class="text-xl font-bold">Total: ${{ number_format($invoice->total_amount, 2) }}</p>
    </div>
</body>

</html>
