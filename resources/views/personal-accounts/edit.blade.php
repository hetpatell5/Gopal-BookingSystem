@extends('layouts.app')
@section('title', 'Edit Expense Slip')
@section('header', 'Edit Expense Slip')

@section('content')
<div class="flex flex-col gap-6 items-start max-w-4xl mx-auto w-full">

    <div class="flex items-center justify-between w-full bg-white p-4 rounded-md shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('personal-accounts.date', ['date' => $expense->slip_date]) }}" class="text-gray-500 hover:text-[#f0b44b] transition-colors"><i class="fa-solid fa-arrow-left"></i> Back to Slips</a>
        </div>
        <h2 class="text-[15px] font-bold text-[#1c2238]">Editing Slip for {{ \Carbon\Carbon::parse($expense->slip_date)->format('d M, Y') }}</h2>
    </div>

    @if(session('success'))
        <div class="w-full p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('personal-accounts.update', $expense->id) }}" method="POST" class="w-full flex flex-col gap-6" id="expense-form">
    @csrf
    @method('PUT')
    
    <input type="hidden" name="slip_date" id="input_slip_date" value="{{ $expense->slip_date }}">
    <input type="hidden" name="total_amount" id="input_total_amount" value="{{ $expense->total_amount }}">

    <div class="bg-white shadow-md rounded-md border border-gray-200 w-full overflow-hidden" id="print-area">
        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-gray-200 bg-[#f0b44b] text-center">
            <h2 class="text-[18px] font-black text-[#1c2238] uppercase tracking-widest">Edit Expense Slip</h2>
            <p class="text-[13px] text-[#1c2238] font-bold mt-1 opacity-80" id="slip-date">Date: {{ \Carbon\Carbon::parse($expense->slip_date)->format('d M, Y') }}</p>
        </div>

        {{-- Bus Info Fields --}}
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Bus Name</label>
                <input type="text" name="bus_name" value="{{ $expense->bus_name }}" class="w-full border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="Enter Bus Name">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Bus Number</label>
                <input type="text" name="bus_number" value="{{ $expense->bus_number }}" class="w-full border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="e.g. GJ-01-XX-1234">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Manager Name</label>
                <input type="text" name="manager_name" value="{{ $expense->manager_name }}" class="w-full border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="Enter Manager Name">
            </div>
        </div>

        {{-- Form Fields --}}
        <div class="px-6 py-6">
            <table class="w-full text-[14px]">
                <thead>
                    <tr class="border-b-2 border-[#1c2238]">
                        <th class="text-left pb-3 text-[12px] font-bold text-gray-600 uppercase tracking-widest w-[60%]">Expense Item</th>
                        <th class="text-right pb-3 text-[12px] font-bold text-gray-600 uppercase tracking-widest w-[40%]">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody id="expense-rows">
                    {{-- Grease Cost --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">Grease Cost</td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" name="grease_cost" value="{{ $expense->grease_cost }}" min="0" step="0.01" class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                            </div>
                        </td>
                    </tr>
                    {{-- Tax --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">Tax</td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" name="tax" value="{{ $expense->tax }}" min="0" step="0.01" class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                            </div>
                        </td>
                    </tr>
                    {{-- Toll Tax --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">Toll Tax</td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" name="toll_tax" value="{{ $expense->toll_tax }}" min="0" step="0.01" class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                            </div>
                        </td>
                    </tr>
                    {{-- Diesel --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3">
                            <div class="flex items-center gap-6">
                                <span class="font-semibold text-[#1c2238]">Diesel</span>
                                <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-md border border-gray-200">
                                    <div class="flex items-center gap-1.5">
                                        <input type="number" name="diesel_liter" value="{{ $expense->diesel_liter }}" id="diesel_liter" min="0" step="0.01" class="w-16 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-2 py-1 shadow-sm" placeholder="Liters">
                                        <span class="text-[11px] text-gray-500 font-bold uppercase tracking-wide">Ltr</span>
                                    </div>
                                    <span class="text-gray-300 text-[12px] font-bold mx-1"><i class="fa-solid fa-xmark"></i></span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[12px] text-gray-500 font-bold">₹</span>
                                        <input type="number" name="diesel_rate" value="{{ $expense->diesel_rate }}" id="diesel_rate" min="0" step="0.01" class="w-16 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-2 py-1 shadow-sm" placeholder="Rate">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" name="diesel_amount" value="{{ $expense->diesel_amount }}" min="0" step="0.01" id="diesel_amount" class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                            </div>
                        </td>
                    </tr>
                    {{-- Driver Salary --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">Driver Salary</td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" name="driver_salary" value="{{ $expense->driver_salary }}" min="0" step="0.01" class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                            </div>
                        </td>
                    </tr>
                    {{-- Conductor Salary --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">Conductor Salary</td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" name="conductor_salary" value="{{ $expense->conductor_salary }}" min="0" step="0.01" class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                            </div>
                        </td>
                    </tr>
                    {{-- Parking --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">Parking</td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" name="parking" value="{{ $expense->parking }}" min="0" step="0.01" class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                            </div>
                        </td>
                    </tr>
                    {{-- Parchuran (Miscellaneous) --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">Parchuran</td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" name="parchuran" value="{{ $expense->parchuran }}" min="0" step="0.01" class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                            </div>
                        </td>
                    </tr>
                </tbody>
                {{-- Total Row --}}
                <tfoot>
                    <tr class="bg-[#1c2238]">
                        <td class="px-4 py-4 text-[14px] font-bold text-white uppercase tracking-widest rounded-bl-md">
                            Total Amount
                        </td>
                        <td class="px-4 py-4 text-right rounded-br-md">
                            <span class="text-[#f0b44b] font-bold text-[18px]">₹ <span id="grand-total">{{ number_format($expense->total_amount, 2) }}</span></span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    {{-- ── Top Bar ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between w-full gap-4 bg-white p-4 rounded-md shadow-sm border border-gray-200">
        <p class="text-[13px] text-gray-500 font-medium">Update the expense details and save changes.</p>
        <div class="flex items-center gap-3">
            <button type="submit" class="whitespace-nowrap inline-flex items-center justify-center gap-2 text-[#1c2238] font-bold text-[13px] px-6 py-2 shadow-sm rounded-md transition-colors bg-[#f0b44b] hover:bg-[#e0a43b]">
                <i class="fa-solid fa-floppy-disk"></i> Update Info
            </button>
        </div>
    </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalAmountInput = document.getElementById('input_total_amount');
    
    document.querySelectorAll('.amount-input').forEach(input => {
        input.addEventListener('input', recalcTotal);
    });

    function recalcTotal() {
        let total = 0;
        document.querySelectorAll('.amount-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('grand-total').textContent = total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        totalAmountInput.value = total.toFixed(2);
    }

    // ── Diesel Auto Calculation ──
    const dieselLiter = document.getElementById('diesel_liter');
    const dieselRate = document.getElementById('diesel_rate');
    const dieselAmount = document.getElementById('diesel_amount');

    function calculateDiesel() {
        const liter = parseFloat(dieselLiter.value) || 0;
        const rate = parseFloat(dieselRate.value) || 0;
        
        if (liter > 0 || rate > 0) {
            dieselAmount.value = (liter * rate).toFixed(2);
            recalcTotal();
        } else if (liter === 0 && rate === 0) {
            dieselAmount.value = '';
            recalcTotal();
        }
    }

    if (dieselLiter) dieselLiter.addEventListener('input', calculateDiesel);
    if (dieselRate) dieselRate.addEventListener('input', calculateDiesel);
    
    // Initial calc just in case
    recalcTotal();
});
</script>
@endsection
