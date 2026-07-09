@extends('layouts.app')
@section('title', 'Record Expense')
@section('header', 'Record Expense')

@section('content')

<div class="mb-6 flex justify-between items-center max-w-3xl mx-auto w-full">
    <a href="{{ route('personal-accounts.index') }}" class="text-sm font-semibold text-gray-500 hover:text-[#f0b44b] transition-colors">
        <i class="fa-solid fa-arrow-left mr-1"></i> Back to Dashboard
    </a>
</div>

<div class="max-w-3xl mx-auto w-full">
    @if(session('success'))
        <div class="w-full p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-none border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('personal-accounts.store') }}" method="POST" class="w-full flex flex-col gap-6" id="expense-form">
        @csrf
        <input type="hidden" name="slip_date" id="input_slip_date">
        <input type="hidden" name="total_amount" id="input_total_amount">

        {{-- ── EXPENSE FORM CARD ── --}}
        <div class="bg-white shadow-sm rounded-none border border-gray-200 w-full overflow-hidden" id="print-area">
            
            <div class="px-6 py-5 border-b border-gray-200 bg-[#f0b44b] text-center">
                <h2 class="text-[18px] font-black text-[#1c2238] uppercase tracking-widest">Personal Bus Expense Slip</h2>
                <p class="text-[13px] text-[#1c2238] font-bold mt-1 opacity-80" id="slip-date">Date: —</p>
            </div>

            {{-- Bus Info Fields --}}
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Bus Name</label>
                    <input type="text" name="bus_name" class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="Enter Bus Name">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Bus Number</label>
                    <input type="text" name="bus_number" class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="e.g. GJ-01-XX-1234">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Manager Name</label>
                    <input type="text" name="manager_name" class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="Enter Manager Name">
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
                        @foreach(['grease_cost' => 'Grease Cost', 'tax' => 'Tax', 'toll_tax' => 'Toll Tax'] as $field => $label)
                        <tr class="border-b border-gray-100 expense-row">
                            <td class="py-3 font-semibold text-[#1c2238]">{{ $label }}</td>
                            <td class="py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <span class="text-gray-500 font-medium mr-1">₹</span>
                                    <input type="number" name="{{ $field }}" min="0" step="0.01" class="amount-input w-32 text-right border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        {{-- Diesel --}}
                        <tr class="border-b border-gray-100 expense-row">
                            <td class="py-3">
                                <div class="flex items-center gap-6">
                                    <span class="font-semibold text-[#1c2238]">Diesel</span>
                                    <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-none border border-gray-200">
                                        <div class="flex items-center gap-1.5">
                                            <input type="number" name="diesel_liter" id="diesel_liter" min="0" step="0.01" class="w-16 text-right border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-2 py-1 shadow-sm" placeholder="Liters">
                                            <span class="text-[11px] text-gray-500 font-bold uppercase tracking-wide">Ltr</span>
                                        </div>
                                        <span class="text-gray-300 text-[12px] font-bold mx-1"><i class="fa-solid fa-xmark"></i></span>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[12px] text-gray-500 font-bold">₹</span>
                                            <input type="number" name="diesel_rate" id="diesel_rate" min="0" step="0.01" class="w-16 text-right border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-2 py-1 shadow-sm" placeholder="Rate">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <span class="text-gray-500 font-medium mr-1">₹</span>
                                    <input type="number" name="diesel_amount" min="0" step="0.01" id="diesel_amount" class="amount-input w-32 text-right border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                                </div>
                            </td>
                        </tr>

                        @foreach(['driver_salary' => 'Driver Salary', 'conductor_salary' => 'Conductor Salary', 'parking' => 'Parking', 'parchuran' => 'Parchuran'] as $field => $label)
                        <tr class="border-b border-gray-100 expense-row">
                            <td class="py-3 font-semibold text-[#1c2238]">{{ $label }}</td>
                            <td class="py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <span class="text-gray-500 font-medium mr-1">₹</span>
                                    <input type="number" name="{{ $field }}" min="0" step="0.01" class="amount-input w-32 text-right border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm" placeholder="0.00">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    {{-- Total Row --}}
                    <tfoot>
                        <tr class="bg-[#1c2238]">
                            <td class="px-4 py-4 text-[14px] font-bold text-white uppercase tracking-widest">
                                Total Amount
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-[#f0b44b] font-bold text-[18px]">₹ <span id="grand-total">0.00</span></span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            {{-- Footer note --}}
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50">
                <div class="text-[12px] text-gray-500 font-semibold" id="print-timestamp"></div>
            </div>
        </div>
    </form>
    
    {{-- ── Top Bar ── --}}
    <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between w-full gap-4 bg-white p-4 rounded-none shadow-sm border border-gray-200">
        <p class="text-[13px] text-gray-500 font-medium">Fill in the expense details above, then print or save the slip.</p>
        <div class="flex items-center gap-3">
            <button type="submit" form="expense-form" class="whitespace-nowrap inline-flex items-center justify-center gap-2 text-[#1c2238] font-bold text-[13px] px-6 py-2 shadow-sm rounded-none transition-colors bg-[#f0b44b] hover:bg-[#e0a43b]">
                <i class="fa-solid fa-floppy-disk"></i> Save Info
            </button>
            <button type="button" onclick="window.print()" class="whitespace-nowrap inline-flex items-center justify-center gap-2 text-white font-bold text-[13px] px-6 py-2 shadow-sm rounded-none transition-colors" style="background:#1c2238;" onmouseover="this.style.background='#2d3a5a'" onmouseout="this.style.background='#1c2238'">
                <i class="fa-solid fa-print"></i> Print Slip
            </button>
        </div>
    </div>
</div>

{{-- ── Print Styles ── --}}
<style>
    @media print {
        body > *:not(#print-wrapper) { display: none !important; }
        .page-actions, header, aside, nav, button, .mt-6, .mb-6 { display: none !important; }
        input {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        #print-area {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            max-width: 100% !important;
        }
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const now = new Date();

    // Set Date
    document.getElementById('slip-date').textContent = 'Date: ' + now.toLocaleDateString('en-IN', { day:'2-digit', month:'long', year:'numeric' });
    document.getElementById('input_slip_date').value = now.toISOString().split('T')[0];

    document.getElementById('print-timestamp').textContent = 'Generated: ' + now.toLocaleString('en-IN');

    // ── Grand total ──
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

    dieselLiter.addEventListener('input', calculateDiesel);
    dieselRate.addEventListener('input', calculateDiesel);
    dieselAmount.addEventListener('input', function() {
        dieselLiter.value = '';
        dieselRate.value = '';
        recalcTotal();
    });
});
</script>
@endsection
