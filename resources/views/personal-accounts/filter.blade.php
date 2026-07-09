@extends('layouts.app')
@section('title', 'Filter Results')
@section('header', 'Filtered Personal Accounts')

@section('content')

<div class="flex flex-col gap-6 max-w-6xl mx-auto w-full">

    <div class="flex flex-col sm:flex-row items-center justify-between w-full bg-white p-4 rounded-md shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('personal-accounts.index') }}" class="text-gray-500 hover:text-[#f0b44b] transition-colors"><i class="fa-solid fa-arrow-left"></i> Back to Accounts</a>
        </div>
        <h2 class="text-[15px] font-bold text-[#1c2238] mt-4 sm:mt-0"><i class="fa-solid fa-filter mr-2 text-[#f0b44b]"></i> Search Results ({{ $expenses->count() }} found)</h2>
    </div>

    @if(isset($expenses) && $expenses->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($expenses as $index => $expense)
                <div class="bg-white shadow-md rounded-md border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <div>
                            <span class="text-[12px] font-bold text-gray-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($expense->slip_date)->format('d M, Y') }}</span>
                            <div class="mt-1 flex items-center gap-3 text-[12px] text-gray-500 font-medium">
                                @if($expense->bus_name)<span><i class="fa-solid fa-bus mr-1"></i> {{ $expense->bus_name }}</span>@endif
                                @if($expense->bus_number)<span><i class="fa-solid fa-hashtag mr-1"></i> {{ $expense->bus_number }}</span>@endif
                                @if($expense->manager_name)<span><i class="fa-solid fa-user-tie mr-1"></i> {{ $expense->manager_name }}</span>@endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Total</span>
                            <div class="text-[16px] font-black text-[#f0b44b]">₹ {{ number_format($expense->total_amount, 2) }}</div>
                        </div>
                    </div>
                    
                    <div class="p-0">
                        <table class="w-full text-left text-[13px]">
                            <tbody class="divide-y divide-gray-50">
                                @if($expense->grease_cost > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Grease Cost</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->grease_cost, 2) }}</td></tr>
                                @endif
                                
                                @if($expense->tax > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Tax</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->tax, 2) }}</td></tr>
                                @endif

                                @if($expense->toll_tax > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Toll Tax</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->toll_tax, 2) }}</td></tr>
                                @endif

                                @if($expense->diesel_amount > 0)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 px-5 font-semibold text-gray-600">
                                        Diesel <span class="text-[11px] text-gray-400 font-normal ml-2">({{ $expense->diesel_liter }} Ltr × ₹{{ $expense->diesel_rate }})</span>
                                    </td>
                                    <td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->diesel_amount, 2) }}</td>
                                </tr>
                                @endif

                                @if($expense->driver_salary > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Driver Salary</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->driver_salary, 2) }}</td></tr>
                                @endif

                                @if($expense->conductor_salary > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Conductor Salary</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->conductor_salary, 2) }}</td></tr>
                                @endif

                                @if($expense->parking > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Parking</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->parking, 2) }}</td></tr>
                                @endif

                                @if($expense->parchuran > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Parchuran</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->parchuran, 2) }}</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white p-12 text-center text-gray-400 text-[14px] shadow-sm rounded-md border border-gray-200">
            <i class="fa-solid fa-magnifying-glass text-[40px] mb-4 text-gray-200 block"></i>
            No matching expenses found for your filter criteria.
        </div>
    @endif

</div>
@endsection
