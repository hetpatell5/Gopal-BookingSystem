@extends('layouts.app')
@section('title', 'Personal Accounts')
@section('header', 'Personal Accounts')

@section('content')

<div class="flex flex-col gap-6 items-center">

    {{-- ── Top Bar ── --}}
    <div class="flex items-center justify-between w-full max-w-4xl">
        <p class="text-[13px] text-gray-500">Fill in the expense details below, then print the slip.</p>
        <button onclick="window.print()"
                class="inline-flex items-center gap-2 text-white font-bold text-[13px] px-5 py-2.5 shadow-sm rounded-md transition-colors"
                style="background:#1c2238;" onmouseover="this.style.background='#2d3a5a'" onmouseout="this.style.background='#1c2238'">
            <i class="fa-solid fa-print"></i> Print Slip
        </button>
    </div>

    {{-- ── EXPENSE FORM CARD ── --}}
    <div class="bg-white shadow-md rounded-md border border-gray-200 w-full max-w-4xl overflow-hidden" id="print-area">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <div>
                <h2 class="text-[17px] font-bold text-[#1c2238]">Personal Bus Expense Slip</h2>
                <p class="text-[12px] text-gray-500 mt-0.5" id="slip-date">Date: —</p>
            </div>
            <div class="text-right">
                <div class="text-[11px] text-gray-500 font-bold uppercase tracking-widest">Ref. No.</div>
                <div id="ref-no" class="text-[15px] font-bold text-[#1c2238]">#—</div>
            </div>
        </div>

        {{-- Form Fields --}}
        <div class="px-6 py-6">
            <table class="w-full text-[14px]">
                <thead>
                    <tr class="border-b-2 border-[#1c2238]">
                        <th class="text-left pb-3 text-[12px] font-bold text-gray-600 uppercase tracking-widest w-[40%]">Expense Item</th>
                        <th class="text-right pb-3 text-[12px] font-bold text-gray-600 uppercase tracking-widest w-[35%]">Details / Qty</th>
                        <th class="text-right pb-3 text-[12px] font-bold text-gray-600 uppercase tracking-widest w-[25%]">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody id="expense-rows">

                    {{-- Grease Cost --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">
                            Grease Cost
                        </td>
                        <td class="py-3 text-right">
                            <input type="text" class="detail-input w-full text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="e.g. 2 boxes">
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" min="0" step="0.01"
                                       class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm"
                                       placeholder="0.00">
                            </div>
                        </td>
                    </tr>

                    {{-- Tax --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">
                            Tax
                        </td>
                        <td class="py-3 text-right">
                            <input type="text" class="detail-input w-full text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="—">
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" min="0" step="0.01"
                                       class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm"
                                       placeholder="0.00">
                            </div>
                        </td>
                    </tr>

                    {{-- Toll Tax --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">
                            Toll Tax
                        </td>
                        <td class="py-3 text-right">
                            <input type="text" class="detail-input w-full text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="—">
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" min="0" step="0.01"
                                       class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm"
                                       placeholder="0.00">
                            </div>
                        </td>
                    </tr>

                    {{-- Diesel --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">
                            Diesel
                        </td>
                        <td class="py-3 text-right">
                            <input type="text" class="detail-input w-full text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="e.g. 50 ltr @ 90">
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" min="0" step="0.01"
                                       class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm"
                                       placeholder="0.00">
                            </div>
                        </td>
                    </tr>

                    {{-- Driver Salary --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">
                            Driver Salary
                        </td>
                        <td class="py-3 text-right">
                            <input type="text" class="detail-input w-full text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="—">
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" min="0" step="0.01"
                                       class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm"
                                       placeholder="0.00">
                            </div>
                        </td>
                    </tr>

                    {{-- Conductor Salary --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">
                            Conductor Salary
                        </td>
                        <td class="py-3 text-right">
                            <input type="text" class="detail-input w-full text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="—">
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" min="0" step="0.01"
                                       class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm"
                                       placeholder="0.00">
                            </div>
                        </td>
                    </tr>

                    {{-- Parking --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">
                            Parking
                        </td>
                        <td class="py-3 text-right">
                            <input type="text" class="detail-input w-full text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="—">
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" min="0" step="0.01"
                                       class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm"
                                       placeholder="0.00">
                            </div>
                        </td>
                    </tr>

                    {{-- Parchuran (Miscellaneous) --}}
                    <tr class="border-b border-gray-100 expense-row">
                        <td class="py-3 font-semibold text-[#1c2238]">
                            Parchuran
                        </td>
                        <td class="py-3 text-right">
                            <input type="text" class="detail-input w-full text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white text-gray-700 text-[13px] px-3 py-1.5 shadow-sm" placeholder="—">
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-gray-500 font-medium mr-1">₹</span>
                                <input type="number" min="0" step="0.01"
                                       class="amount-input w-32 text-right border border-gray-300 rounded-md focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none bg-white font-semibold text-[#1c2238] px-3 py-1.5 shadow-sm"
                                       placeholder="0.00">
                            </div>
                        </td>
                    </tr>

                </tbody>

                {{-- Total Row --}}
                <tfoot>
                    <tr class="bg-[#1c2238]">
                        <td colspan="2" class="px-4 py-4 text-[14px] font-bold text-white uppercase tracking-widest rounded-bl-md">
                            Total Amount
                        </td>
                        <td class="px-4 py-4 text-right rounded-br-md">
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

</div>

{{-- ── Print Styles ── --}}
<style>
    @media print {
        /* Hide everything except the slip */
        body > *:not(#print-wrapper) { display: none !important; }
        .page-actions, header, aside, nav { display: none !important; }

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
        
        /* Force colours */
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Set date & ref number ──
    const now = new Date();
    document.getElementById('slip-date').textContent = 'Date: ' + now.toLocaleDateString('en-IN', { day:'2-digit', month:'long', year:'numeric' });
    document.getElementById('ref-no').textContent = '#PA-' + now.getFullYear() + String(now.getMonth()+1).padStart(2,'0') + String(now.getDate()).padStart(2,'0') + '-' + Math.floor(Math.random()*900+100);
    document.getElementById('print-timestamp').textContent = 'Generated: ' + now.toLocaleString('en-IN');

    // ── Grand total ──
    document.querySelectorAll('.amount-input').forEach(input => {
        input.addEventListener('input', recalcTotal);
    });

    function recalcTotal() {
        let total = 0;
        document.querySelectorAll('.amount-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('grand-total').textContent = total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
});
</script>

@endsection
