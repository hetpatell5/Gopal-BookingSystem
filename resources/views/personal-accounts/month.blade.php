@extends('layouts.app')
@section('title', 'Expenses for ' . $monthName)
@section('header', 'Expenses for ' . $monthName)

@section('content')

<div class="flex flex-col gap-6 max-w-4xl mx-auto w-full">

    <div class="flex items-center justify-between w-full bg-white p-4 rounded-md shadow-sm border border-gray-200 no-print">
        <div class="flex items-center gap-3">
            <a href="{{ route('personal-accounts.index') }}" class="text-gray-500 hover:text-[#f0b44b] transition-colors"><i class="fa-solid fa-arrow-left"></i> Back to Accounts</a>
        </div>
        <div class="flex items-center gap-4">
            <h2 class="text-[15px] font-bold text-[#1c2238]">{{ $monthName }}</h2>
            <button onclick="window.print()" class="bg-[#f0b44b] hover:bg-[#e0a43b] text-[#1c2238] font-bold text-[13px] px-4 py-1.5 rounded transition-colors shadow-sm">
                <i class="fa-solid fa-print"></i> Print
            </button>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-[15px] font-bold text-[#1c2238]"><i class="fa-regular fa-calendar mr-2"></i> Daily Expense Summary</h2>
        </div>
        
        @php
            $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1);
            $daysInMonth = $startOfMonth->daysInMonth;
            $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 = Sun, 6 = Sat
            
            $prevMonth = $startOfMonth->copy()->subMonth();
            $nextMonth = $startOfMonth->copy()->addMonth();

            $totalsMap = [];
            if(isset($dailyTotals)) {
                foreach($dailyTotals as $daily) {
                    $totalsMap[\Carbon\Carbon::parse($daily->date)->format('Y-m-d')] = $daily->total;
                }
            }
        @endphp

        {{-- Box Grid Calendar View --}}
        <div class="p-6 bg-white border-b border-gray-200 no-print">
            {{-- Calendar Header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-[16px] font-black text-[#1c2238] uppercase tracking-wider">{{ $startOfMonth->format('F Y') }}</h3>
                <div class="flex items-center gap-2">
                    <a href="{{ route('personal-accounts.month', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-600 hover:bg-[#f0b44b] hover:text-white hover:border-[#f0b44b] transition-colors shadow-sm">
                        <i class="fa-solid fa-chevron-left text-[12px]"></i>
                    </a>
                    <a href="{{ route('personal-accounts.month', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-600 hover:bg-[#f0b44b] hover:text-white hover:border-[#f0b44b] transition-colors shadow-sm">
                        <i class="fa-solid fa-chevron-right text-[12px]"></i>
                    </a>
                </div>
            </div>

            {{-- Calendar Grid Container --}}
            <div class="border-t border-l border-gray-200" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));">
                {{-- Days of Week Header --}}
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                    <div class="py-2.5 text-[11px] font-bold text-[#1c2238] uppercase tracking-widest bg-gray-50 border-r border-b border-gray-200 text-center">
                        {{ $dayName }}
                    </div>
                @endforeach
                
                {{-- Empty slots --}}
                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="bg-gray-50 border-r border-b border-gray-200 opacity-60" style="min-height: 80px;"></div>
                @endfor
                
                {{-- Days of the month --}}
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $hasExpense = isset($totalsMap[$currentDateStr]);
                        $expenseTotal = $hasExpense ? $totalsMap[$currentDateStr] : 0;
                        $isToday = $currentDateStr === date('Y-m-d');
                    @endphp
                    
                    @if($hasExpense)
                        <a href="{{ route('personal-accounts.date', ['date' => $currentDateStr]) }}" class="{{ $isToday ? 'border-2 border-[#1c2238] z-10 relative bg-transparent' : 'border-r border-b border-[#d9a243] bg-yellow-50' }} p-2 flex flex-col justify-between hover:bg-[#f0b44b]/20 transition-colors cursor-pointer group block" style="min-height: 80px;">
                            <span class="text-[14px] font-black text-[#1c2238] block">{{ $day }}</span>
                            <div class="mt-auto text-right">
                                <span class="text-[12px] font-black text-[#1c2238] group-hover:scale-105 transition-transform inline-block bg-[#f0b44b] px-1.5 py-0.5 shadow-sm">
                                    ₹ {{ number_format($expenseTotal, 0) }}
                                </span>
                            </div>
                        </a>
                    @else
                        <div class="{{ $isToday ? 'border-2 border-[#1c2238] z-10 relative bg-transparent' : 'border-r border-b border-gray-200 bg-white' }} p-2 flex flex-col justify-between" style="min-height: 80px;">
                            <span class="text-[13px] font-medium {{ $isToday ? 'text-[#1c2238] font-bold' : 'text-gray-400' }} block">{{ $day }}</span>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
        
        @if(isset($dailyTotals) && $dailyTotals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-gray-100">
                            <th class="py-3 px-6 text-[12px] font-bold text-gray-500 uppercase tracking-widest w-[70%]">Date</th>
                            <th class="py-3 px-6 text-[12px] font-bold text-gray-500 uppercase tracking-widest w-[30%] text-right">Total Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($dailyTotals as $daily)
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('personal-accounts.date', ['date' => $daily->date]) }}'">
                                <td class="py-4 px-6">
                                    <span class="text-[14px] font-bold text-[#1c2238]">{{ \Carbon\Carbon::parse($daily->date)->format('d M, Y (l)') }}</span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <span class="text-[14px] font-black text-[#f0b44b]">₹ {{ number_format($daily->total, 2) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-gray-400 text-[14px]">
                No expenses found for this month.
            </div>
        @endif
    </div>

</div>
<style>
    @media print {
        .no-print, header, nav, aside, footer { display: none !important; }
        body { background-color: white !important; }
        .shadow-md, .shadow-sm { box-shadow: none !important; }
        .border-gray-200 { border-color: #e5e7eb !important; }
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
@endsection
