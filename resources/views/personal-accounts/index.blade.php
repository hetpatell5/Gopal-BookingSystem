@extends('layouts.app')
@section('title', 'Personal Accounts')
@section('header', 'Personal Accounts')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row justify-between sm:items-center gap-4 max-w-6xl mx-auto w-full">
    <h2 class="text-[18px] font-black text-[#1c2238] uppercase tracking-widest">Expenses Dashboard</h2>
    <a href="{{ route('personal-accounts.create') }}" class="bg-[#1c2238] text-white font-bold text-[13px] px-4 py-2 rounded-none hover:bg-[#29324b] transition-colors shadow-sm uppercase tracking-widest flex items-center justify-center">
        <i class="fa-solid fa-plus mr-2"></i> Record New Expense
    </a>
</div>

{{-- Filter Box (Top Horizontal) --}}
<div class="bg-white rounded-none shadow-sm border border-gray-200 mb-6 max-w-6xl mx-auto w-full">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h2 class="text-[13px] font-bold text-[#1c2238] uppercase tracking-widest"><i class="fa-solid fa-search mr-2 text-[#f0b44b]"></i> Search Expense Slips</h2>
    </div>
    <div class="p-6">
        <form action="{{ route('personal-accounts.filter') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Start Date</label>
                <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-none focus:outline-none focus:ring-1 focus:ring-[#f0b44b] text-[13px] shadow-sm">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">End Date</label>
                <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-none focus:outline-none focus:ring-1 focus:ring-[#f0b44b] text-[13px] shadow-sm">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Bus Number / Name</label>
                <input type="text" name="bus_number" class="w-full px-4 py-2 border border-gray-300 rounded-none focus:outline-none focus:ring-1 focus:ring-[#f0b44b] text-[13px] shadow-sm" placeholder="e.g. GJ-01">
            </div>
            <div class="flex items-center">
                <button type="submit" class="bg-[#1c2238] text-white font-bold rounded-none px-6 py-2 hover:bg-[#29324b] transition-colors shadow-sm whitespace-nowrap h-[38px] text-[13px]">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6 items-start max-w-6xl mx-auto w-full">
    <div class="flex-1 w-full flex flex-col gap-6">
        @if(session('success'))
            <div class="w-full p-4 text-sm text-green-700 bg-green-100 rounded-none border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- Yearly Summary Widget --}}
        <div class="mb-8">
            <h2 class="text-[15px] font-bold text-[#1c2238] uppercase tracking-widest flex items-center mb-4"><i class="fa-solid fa-chart-line mr-2 text-[#f0b44b]"></i> Profit & Loss Summary ({{ $selectedBus ? $selectedBus->name : 'All Buses' }} - {{ $year }})</h2>
            
            <div class="flex flex-col md:flex-row gap-4">
                <div class="bg-white p-5 border border-gray-200 shadow-sm rounded-none flex-1">
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Income</p>
                    <p class="text-[20px] font-black text-[#1c2238]">₹ {{ number_format($yearlySummary['revenue'], 2) }}</p>
                </div>
                <div class="bg-white p-5 border border-gray-200 shadow-sm rounded-none flex-1">
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Agent Commission</p>
                    <p class="text-[20px] font-black text-red-500">₹ {{ number_format($yearlySummary['commission'], 2) }}</p>
                </div>
                <div class="bg-white p-5 border border-gray-200 shadow-sm rounded-none flex-1">
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Expenses</p>
                    <p class="text-[20px] font-black text-red-500">₹ {{ number_format($yearlySummary['expenses'], 2) }}</p>
                </div>
                <div class="bg-white p-5 border border-gray-200 shadow-sm rounded-none flex-1 {{ $yearlySummary['net_profit'] >= 0 ? 'bg-[#e8f5ed] border-[#34a853]' : 'bg-[#fee2e2] border-[#ef4444]' }}">
                    <p class="text-[11px] font-bold uppercase tracking-widest mb-1 {{ $yearlySummary['net_profit'] >= 0 ? 'text-[#34a853]' : 'text-[#ef4444]' }}">Net Profit / Loss</p>
                    <p class="text-[20px] font-black {{ $yearlySummary['net_profit'] >= 0 ? 'text-[#34a853]' : 'text-[#ef4444]' }}">
                        ₹ {{ number_format($yearlySummary['net_profit'], 2) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Monthly Expenses --}}
        <div class="w-full">
            <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-[15px] font-bold text-[#1c2238] uppercase tracking-widest flex items-center"><i class="fa-solid fa-calendar-days mr-2 text-[#f0b44b]"></i> Monthly Ledger</h2>
                <form action="{{ route('personal-accounts.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    
                    <div class="flex items-center">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mr-3">Bus:</label>
                        <div class="relative inline-block min-w-[180px]">
                            <select name="bus_id" onchange="this.form.submit()" class="appearance-none w-full bg-white border border-gray-300 text-[#1c2238] font-bold text-[13px] py-2 pl-4 pr-10 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#f0b44b] focus:border-transparent transition-all cursor-pointer hover:border-gray-400">
                                <option value="" {{ empty($busId) ? 'selected' : '' }}>All Buses</option>
                                @foreach($buses as $b)
                                    <option value="{{ $b->id }}" {{ (string)$busId === (string)$b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="fa-solid fa-chevron-down text-[11px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mr-3">Year:</label>
                        <div class="relative inline-block min-w-[120px]">
                            <select name="year" onchange="this.form.submit()" class="appearance-none w-full bg-white border border-gray-300 text-[#1c2238] font-bold text-[13px] py-2 pl-4 pr-10 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#f0b44b] focus:border-transparent transition-all cursor-pointer hover:border-gray-400">
                                @foreach($availableYears as $y)
                                    <option value="{{ $y }}" {{ (string)$year === (string)$y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="fa-solid fa-chevron-down text-[11px]"></i>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($monthlyTotals as $index => $total)
                    @if($total->revenue > 0 || $total->expenses > 0)
                    <div class="bg-white p-4 flex flex-col border border-gray-200 shadow-sm hover:border-[#f0b44b] hover:shadow-md transition-all rounded-none overflow-hidden relative group">
                        
                        <div class="flex justify-between items-center mb-3 border-b border-gray-100 pb-2">
                            <span class="text-[14px] font-black text-[#1c2238] uppercase tracking-widest group-hover:text-[#f0b44b] transition-colors">
                                {{ date('F Y', mktime(0, 0, 0, $total->month, 1, $total->year)) }}
                            </span>
                            <a href="{{ route('personal-accounts.month', ['year' => $total->year, 'month' => $total->month]) }}" class="text-[11px] font-bold text-white bg-[#1c2238] hover:bg-[#29324b] px-3 py-1 uppercase tracking-widest transition-colors">
                                View Slips
                            </a>
                        </div>
                        
                        <div class="flex justify-between items-center text-center mb-3">
                            <div class="flex-1">
                                <p class="text-[11px] font-bold text-gray-500 tracking-wide mb-1">Income</p>
                                <p class="text-[16px] font-black text-[#1c2238]">₹{{ number_format($total->revenue, 0) }}</p>
                            </div>
                            <div class="flex-1 border-l border-r border-gray-100">
                                <p class="text-[11px] font-bold text-gray-500 tracking-wide mb-1">Comm.</p>
                                <p class="text-[16px] font-black text-red-500">₹{{ number_format($total->commission, 0) }}</p>
                            </div>
                            <div class="flex-1">
                                <p class="text-[11px] font-bold text-gray-500 tracking-wide mb-1">Expense</p>
                                <p class="text-[16px] font-black text-red-500">₹{{ number_format($total->expenses, 0) }}</p>
                            </div>
                        </div>

                        <div class="mt-auto pt-2 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Net Profit/Loss</span>
                            <span class="text-[16px] font-black {{ $total->net_profit >= 0 ? 'text-[#34a853]' : 'text-[#ef4444]' }}">
                                ₹ {{ number_format($total->net_profit, 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
