@extends('layouts.freelancer')

@section('title', 'Earnings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showClearingModal: false }">
    
    <!-- Header Section -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 tracking-tight">Earnings</h1>
            <p class="text-slate-500 mt-2 text-base">Manage your income and withdrawals.</p>
        </div>
        <a href="#" @click.prevent="showComingSoonToast()" class="text-sm font-semibold text-green-600 hover:underline">Learn more about this page</a>
    </div>

    <!-- Tabs -->
    <div class="border-b border-slate-200 mb-10">
        <nav class="-mb-px flex space-x-10">
            <a href="#" class="border-green-500 text-slate-900 whitespace-nowrap pb-4 border-b-[3px] font-bold text-sm">
                Overview
            </a>
            <a href="#" @click.prevent="showComingSoonToast()" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap pb-4 border-b-[3px] font-medium text-sm transition-colors">
                Financial documents
            </a>
        </nav>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Available Funds -->
        <div class="bg-white p-8 rounded-sm border border-slate-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)] flex flex-col justify-between h-full hover:shadow-md transition-shadow duration-300">
            <div>
                <h3 class="text-base font-bold text-slate-800 mb-6">Available funds</h3>
                
                <div class="mb-8">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">BALANCE AVAILABLE FOR USE</p>
                    <p class="text-5xl font-extrabold text-slate-900 tracking-tight">${{ number_format($availableFunds, 2) }}</p>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-slate-500 mb-1">Withdrawn to date:</p>
                    <p class="text-lg font-bold text-slate-700">${{ number_format(abs($withdrawn), 2) }}</p>
                </div>
            </div>
            
            <div>
                <a href="{{ route('provider.freelancer.withdraw.page') }}" class="w-full inline-flex justify-center bg-black hover:bg-slate-800 text-white font-bold py-3.5 px-4 rounded-[4px] transition-colors mb-4 text-sm">
                    Withdraw balance
                </a>
                <a href="{{ route('provider.freelancer.payout.index') }}" class="block text-center text-sm font-medium text-slate-500 hover:text-green-600 hover:underline transition-colors">
                    Manage payout methods
                </a>
            </div>
        </div>

        <!-- Future Payments -->
        <div class="bg-white p-8 rounded-sm border border-slate-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)] flex flex-col h-full hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center gap-2 mb-6">
                <h3 class="text-base font-bold text-slate-800">Future payments</h3>
                <i class="far fa-question-circle text-slate-400 text-sm cursor-help" title="Payments that are pending clearance or from active orders"></i>
            </div>
            
            <div class="border-b border-slate-100 pb-8 mb-8">
                <div class="flex items-center gap-2 mb-2 justify-between">
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-slate-500 font-medium">Payments being cleared</p>
                        <i class="far fa-question-circle text-slate-300 text-xs cursor-help" title="Funds currently in the clearing period"></i>
                    </div>
                    <button @click="showClearingModal = true" class="text-xs font-bold text-green-600 hover:text-green-700 hover:underline transition-colors">
                        View List
                    </button>
                </div>
                <p class="text-3xl font-bold text-slate-900">${{ number_format($pendingClearance, 2) }}</p>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-2">
                    <p class="text-sm text-slate-500 font-medium">Payments for active orders</p>
                    <i class="far fa-question-circle text-slate-300 text-xs cursor-help" title="Expected earnings from orders currently in progress"></i>
                </div>
                <p class="text-3xl font-bold text-slate-900">${{ number_format($activeOrdersValue, 2) }}</p>
            </div>
        </div>

        <!-- Earnings & Expenses -->
        <div class="bg-white p-8 rounded-sm border border-slate-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)] flex flex-col h-full hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-bold text-slate-800">Earnings & expenses</h3>
                    <i class="far fa-question-circle text-slate-400 text-sm cursor-help"></i>
                </div>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" class="text-xs font-semibold text-slate-500 flex items-center gap-1 hover:text-slate-700 uppercase tracking-wide">
                        Since joining <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                    </button>
                </div>
            </div>
            
            <div class="border-b border-slate-100 pb-8 mb-8">
                <div class="flex items-center gap-2 mb-2">
                    <p class="text-sm text-slate-500 font-medium">Earnings to date</p>
                    <i class="far fa-question-circle text-slate-300 text-xs cursor-help"></i>
                </div>
                <p class="text-3xl font-bold text-slate-900">${{ number_format($netIncome, 2) }}</p>
                <p class="text-xs text-slate-400 mt-2">Your earnings since joining.</p>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-2">
                    <p class="text-sm text-slate-500 font-medium">Expenses to date</p>
                    <i class="far fa-question-circle text-slate-300 text-xs cursor-help"></i>
                </div>
                <p class="text-3xl font-bold text-slate-900">${{ number_format(abs($expenses), 2) }}</p>
                <p class="text-xs text-slate-400 mt-2">Earnings spent on purchases since joining.</p>
            </div>
        </div>
    </div>
    
    <!-- Filters & Table Section -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="px-4 py-2.5 bg-white border border-slate-300 rounded-[4px] text-sm font-semibold text-slate-700 hover:bg-slate-50 flex items-center gap-3 shadow-sm transition-all">
                    Date range
                    <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                </button>
            </div>
            
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="px-4 py-2.5 bg-white border border-slate-300 rounded-[4px] text-sm font-semibold text-slate-700 hover:bg-slate-50 flex items-center gap-3 shadow-sm transition-all">
                    Activity
                    <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-6">
             <div class="flex border border-slate-300 rounded-[4px] overflow-hidden shadow-sm">
                <button class="p-2.5 bg-slate-100 text-slate-600 hover:bg-slate-200 border-r border-slate-300 transition-colors">
                    <i class="fas fa-table"></i>
                </button>
                <button class="p-2.5 bg-white text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-chart-line"></i>
                </button>
            </div>
            <button @click="showComingSoonToast()" class="text-green-600 text-sm font-bold hover:underline flex items-center gap-2 transition-colors">
                <i class="fas fa-file-export"></i> Email activity report
            </button>
        </div>
    </div>

    <p class="text-sm text-slate-500 mb-4 font-medium">
        Showing results {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }}
    </p>

    <div class="bg-white rounded-sm border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-8 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-8 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Activity</th>
                        <th scope="col" class="px-8 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-8 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">From</th>
                        <th scope="col" class="px-8 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Order</th>
                        <th scope="col" class="px-8 py-4 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-600 font-medium">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500">
                                <span class="flex items-center gap-3">
                                    @if($transaction->type == 'credit')
                                        <span class="w-6 h-6 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-xs">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        Order Revenue
                                    @elseif(Str::contains(strtolower($transaction->description), 'withdraw'))
                                        <span class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs">
                                            <i class="fas fa-arrow-down"></i>
                                        </span>
                                        Withdrawal
                                    @else
                                        <span class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                        Expense
                                    @endif
                                </span>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500">
                                {{ Str::limit($transaction->description, 30) }}
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500 font-medium">
                                {{ $transaction->reference_type == 'GigOrder' ? 'Order' : 'System' }}
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap text-sm text-green-600 hover:underline cursor-pointer font-medium">
                                @if($transaction->reference_type == 'GigOrder')
                                    #{{ $transaction->reference_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-right {{ $transaction->type == 'credit' ? 'text-green-600' : 'text-slate-700' }}">
                                {{ $transaction->type == 'credit' ? '+' : '-' }}${{ number_format(abs($transaction->amount), 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-slate-500">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
    <!-- Clearing Payments Modal -->
    <div x-show="showClearingModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showClearingModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" @click="showClearingModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showClearingModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-0 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">
                                    Payments being cleared
                                </h3>
                                <button @click="showClearingModal = false" class="text-slate-400 hover:text-slate-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="mt-2 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                                @if(isset($clearingPayments) && $clearingPayments->count() > 0)
                                    <table class="min-w-full divide-y divide-slate-100">
                                        <thead class="bg-slate-50 sticky top-0">
                                            <tr>
                                                <th class="px-3 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Date</th>
                                                <th class="px-3 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Order</th>
                                                <th class="px-3 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                                                <th class="px-3 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Clears On</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-100">
                                            @foreach($clearingPayments as $payment)
                                                <tr class="hover:bg-slate-50 transition-colors">
                                                    <td class="px-3 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $payment->created_at->format('M d') }}</td>
                                                    <td class="px-3 py-3 text-xs text-slate-900 font-medium whitespace-nowrap">
                                                        @if($payment->reference_type == 'GigOrder')
                                                            <a href="{{ route('provider.freelancer.orders.show', $payment->reference_id) }}" class="text-green-600 hover:underline">
                                                                #{{ $payment->reference_id }}
                                                            </a>
                                                        @else
                                                            {{ Str::limit($payment->description, 15) }}
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-3 text-xs text-green-600 font-bold text-right whitespace-nowrap">+${{ number_format($payment->amount, 2) }}</td>
                                                    <td class="px-3 py-3 text-xs text-slate-500 text-right whitespace-nowrap">
                                                        {{ $payment->available_at ? $payment->available_at->format('M d') : 'Pending' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center py-10">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-check-circle text-slate-300 text-xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-slate-900">No payments clearing</p>
                                        <p class="text-xs text-slate-500 mt-1">All your funds have been cleared or withdrawn.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                    <button type="button" class="w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-all" @click="showClearingModal = false">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showComingSoonToast() {
        const container = document.getElementById('toast-container');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = 'toast bg-white border-l-4 border-blue-500 shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] transform translate-x-full animate-slide-in';
        toast.innerHTML = `
            <div class="text-blue-500">
                <i class="fas fa-info-circle text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Coming Soon</h4>
                <p class="text-sm text-gray-600">This feature is currently under development.</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.replace('animate-slide-in', 'animate-slide-out');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
</script>
@endsection
