@extends('layouts.app')

@section('title', 'Bus Accounting')
@section('header', 'Bus Accounting & Commissions')

@section('content')
<div class="bg-white rounded-[10px] shadow-sm p-6 mb-8">
    <form method="GET" action="{{ route('accounting.index') }}" class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[150px]">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="bg-[#1c2238] text-white font-semibold py-2.5 px-6 rounded-lg hover:bg-[#29324b] transition-colors">
                Filter Dates
            </button>
            <a href="{{ route('accounting.index') }}" class="bg-gray-100 text-gray-600 font-semibold py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                Clear
            </a>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Totals Cards across all buses in period -->
    @php
        $totalRev = 0; $totalComm = 0; $totalPay = 0; $totalSeats = 0;
        foreach($accountingData as $data) {
            $totalRev += $data->total_revenue;
            $totalComm += $data->total_commission;
            $totalPay += $data->total_payable;
            $totalSeats += $data->total_seats_sold;
        }
    @endphp

    <div class="bg-white p-6 rounded-[10px] shadow-sm border-l-4 border-[#1c2238]">
        <p class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Revenue</p>
        <p class="text-2xl font-black text-[#1c2238]">₹{{ number_format($totalRev, 2) }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-[10px] shadow-sm border-l-4 border-green-500">
        <p class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Payable</p>
        <p class="text-2xl font-black text-green-600">₹{{ number_format($totalPay, 2) }}</p>
    </div>

    <div class="bg-white p-6 rounded-[10px] shadow-sm border-l-4 border-[#f0b44b]">
        <p class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Commission</p>
        <p class="text-2xl font-black text-[#f0b44b]">₹{{ number_format($totalComm, 2) }}</p>
    </div>

    <div class="bg-white p-6 rounded-[10px] shadow-sm border-l-4 border-blue-500">
        <p class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Seats Sold</p>
        <p class="text-2xl font-black text-blue-600">{{ $totalSeats }} Seats</p>
    </div>
</div>

<div class="bg-white rounded-[10px] shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-[17px] font-bold text-[#1c2238]">Per Bus Accounting</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Bus Name</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Plate Number</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 text-center">Seats Sold</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 text-right">Revenue</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 text-right">Commission</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 text-right">Payable</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($buses as $bus)
                    @php
                        $data = $accountingData->get($bus->id);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-[13px] font-bold text-[#1c2238]">
                            <a href="{{ route('buses.show', $bus->id) }}" class="hover:text-[#f0b44b] transition-colors">
                                {{ $bus->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-[13px] font-semibold text-gray-500">{{ $bus->plate_number }}</td>
                        <td class="px-6 py-4 text-[13px] font-bold text-center {{ $data && $data->total_seats_sold > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                            {{ $data ? $data->total_seats_sold : 0 }}
                        </td>
                        <td class="px-6 py-4 text-[13px] font-bold text-right text-gray-700">
                            ₹{{ number_format($data ? $data->total_revenue : 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-[13px] font-bold text-right text-[#f0b44b]">
                            ₹{{ number_format($data ? $data->total_commission : 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-[13px] font-bold text-right text-green-600">
                            ₹{{ number_format($data ? $data->total_payable : 0, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
