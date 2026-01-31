<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoiceData['invoice_number'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            @page { margin: 0; size: auto; }
            body { margin: 0; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .print-container { padding: 40px; box-shadow: none; border: none; max-width: 100%; margin: 0; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden print-container">
        
        <!-- Action Buttons (No Print) -->
        <div class="bg-gray-900 px-8 py-4 flex justify-between items-center no-print">
            <a href="{{ route('customer.gigs.order.details', $order->id) }}" class="text-gray-300 hover:text-white flex items-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Order
            </a>
            <button onclick="window.print()" class="bg-white text-gray-900 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                </svg>
                Print Invoice
            </button>
        </div>

        <!-- Invoice Content -->
        <div class="p-8 md:p-12">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">INVOICE</h1>
                    <p class="text-gray-500 font-medium text-lg">#{{ $invoiceData['invoice_number'] }}</p>
                </div>
                <div class="mt-6 md:mt-0 text-right">
                    <div class="text-2xl font-bold text-indigo-600 tracking-tight">AI Local Services</div>
                    <p class="text-gray-500 mt-1 text-sm">Empowering Local Connections</p>
                </div>
            </div>

            <!-- Meta Data -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12 border-b border-gray-100 pb-12">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Billed To</h3>
                        <div class="text-gray-900 font-semibold text-lg">{{ $invoiceData['buyer']['name'] }}</div>
                        <div class="text-gray-600">{{ $invoiceData['buyer']['email'] }}</div>
                        <div class="text-gray-600">{{ $invoiceData['buyer']['address'] }}</div>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Provider</h3>
                        <div class="text-gray-900 font-semibold text-lg">{{ $invoiceData['seller']['name'] }}</div>
                        <div class="text-gray-600">{{ $invoiceData['seller']['email'] }}</div>
                    </div>
                </div>
                <div class="space-y-6 md:text-right">
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Issue Date</h3>
                        <div class="text-gray-900 font-medium">{{ $invoiceData['issue_date'] }}</div>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Due Date</h3>
                        <div class="text-gray-900 font-medium">{{ $invoiceData['due_date'] }}</div>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Status</h3>
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ strtolower($invoiceData['status']) == 'completed' || strtolower($invoiceData['status']) == 'delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $invoiceData['status'] }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-12">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider w-1/2">Description</th>
                            <th class="py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right">Qty</th>
                            <th class="py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right">Unit Price</th>
                            <th class="py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($invoiceData['items'] as $item)
                        <tr>
                            <td class="py-4 text-gray-900 font-medium">
                                {{ $item['description'] }}
                            </td>
                            <td class="py-4 text-gray-600 text-right">{{ $item['quantity'] }}</td>
                            <td class="py-4 text-gray-600 text-right">${{ number_format($item['unit_price'], 2) }}</td>
                            <td class="py-4 text-gray-900 font-semibold text-right">${{ number_format($item['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="flex justify-end border-t border-gray-100 pt-8 mb-12">
                <div class="w-full md:w-1/2 space-y-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-medium">${{ number_format($invoiceData['subtotal'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Tax (0%)</span>
                        <span class="font-medium">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-900 text-xl font-bold pt-4 border-t border-gray-200">
                        <span>Total</span>
                        <span>${{ number_format($invoiceData['total'], 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500 mt-12 pt-8 border-t border-gray-100">
                <p class="mb-2">Thank you for your business!</p>
                <p>If you have any questions about this invoice, please contact support@ailocalservices.com</p>
            </div>
            
        </div>
    </div>

</body>
</html>
