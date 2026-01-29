@extends('layouts.freelancer')

@section('title', 'Earnings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Header Section -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 tracking-tight">Earnings</h1>
            <p class="text-slate-500 mt-2 text-base">Manage your income and withdrawals.</p>
        </div>
        <a href="#" class="text-sm font-semibold text-green-600 hover:underline">Learn more about this page</a>
    </div>

    <!-- Tabs -->
    <div class="border-b border-slate-200 mb-10">
        <nav class="-mb-px flex space-x-10">
            <a href="#" class="border-green-500 text-slate-900 whitespace-nowrap pb-4 border-b-[3px] font-bold text-sm">
                Overview
            </a>
            <a href="#" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap pb-4 border-b-[3px] font-medium text-sm transition-colors">
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
                    <p class="text-5xl font-extrabold text-slate-900 tracking-tight">$0.00</p>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-slate-500 mb-1">Withdrawn to date:</p>
                    <p class="text-lg font-bold text-slate-700">$611.20</p>
                </div>
            </div>
            
            <div>
                <button class="w-full bg-black hover:bg-slate-800 text-white font-bold py-3.5 px-4 rounded-[4px] transition-colors mb-4 text-sm">
                    Withdraw balance
                </button>
                <a href="#" class="block text-center text-sm font-medium text-slate-500 hover:text-green-600 hover:underline transition-colors">
                    Manage payout methods
                </a>
            </div>
        </div>

        <!-- Future Payments -->
        <div class="bg-white p-8 rounded-sm border border-slate-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)] flex flex-col h-full hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center gap-2 mb-6">
                <h3 class="text-base font-bold text-slate-800">Future payments</h3>
                <i class="far fa-question-circle text-slate-400 text-sm cursor-help"></i>
            </div>
            
            <div class="border-b border-slate-100 pb-8 mb-8">
                <div class="flex items-center gap-2 mb-2">
                    <p class="text-sm text-slate-500 font-medium">Payments being cleared</p>
                    <i class="far fa-question-circle text-slate-300 text-xs cursor-help"></i>
                </div>
                <p class="text-3xl font-bold text-slate-900">$0.00</p>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-2">
                    <p class="text-sm text-slate-500 font-medium">Payments for active orders</p>
                    <i class="far fa-question-circle text-slate-300 text-xs cursor-help"></i>
                </div>
                <p class="text-3xl font-bold text-slate-900">$0.00</p>
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
                <p class="text-3xl font-bold text-slate-900">$611.20</p>
                <p class="text-xs text-slate-400 mt-2">Your earnings since joining.</p>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-2">
                    <p class="text-sm text-slate-500 font-medium">Expenses to date</p>
                    <i class="far fa-question-circle text-slate-300 text-xs cursor-help"></i>
                </div>
                <p class="text-3xl font-bold text-slate-900">$0.00</p>
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
             <button class="text-green-600 text-sm font-bold hover:underline flex items-center gap-2 transition-colors">
                <i class="fas fa-file-export"></i> Email activity report
            </button>
        </div>
    </div>

    <p class="text-sm text-slate-500 mb-4 font-medium">Showing results 1-43 of 43</p>

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
                    <!-- Static Row Example 1 -->
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-600 font-medium">Jan 04, 2026</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500">
                            <span class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs">
                                    <i class="fas fa-arrow-down"></i>
                                </span>
                                Withdrawal
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500">Transferred successfully</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500 font-medium">Payoneer</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-400">-</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-slate-700 text-right">-$24.00</td>
                    </tr>
                     <!-- Static Row Example 2 -->
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-600 font-medium">Dec 28, 2025</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500">
                            <span class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-xs">
                                    <i class="fas fa-check"></i>
                                </span>
                                Order Revenue
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500">Order completed</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500 font-medium">ProMarket</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-green-600 hover:underline cursor-pointer font-medium">#FO89322</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-green-600 text-right">$120.00</td>
                    </tr>
                     <!-- Static Row Example 3 -->
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-600 font-medium">Dec 15, 2025</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500">
                             <span class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs">
                                    <i class="fas fa-arrow-down"></i>
                                </span>
                                Withdrawal
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500">Transferred successfully</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500 font-medium">PayPal</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-400">-</td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-slate-700 text-right">-$580.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
