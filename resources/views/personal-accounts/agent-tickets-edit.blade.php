@extends('layouts.app')
@section('title', 'Edit Agent Sale')
@section('header', 'Edit Agent Sale')

@section('content')

<div class="mb-6 flex justify-between items-center max-w-2xl mx-auto w-full">
    <a href="{{ route('agent-tickets.index') }}" class="text-sm font-semibold text-gray-500 hover:text-[#f0b44b] transition-colors">
        <i class="fa-solid fa-arrow-left mr-1"></i> Back to Sales Ledger
    </a>
</div>

<div class="max-w-2xl mx-auto w-full">
    @if(session('success'))
        <div class="w-full p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-none border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-none border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-[#f0b44b] flex justify-between items-center">
            <h2 class="text-[16px] font-black text-[#1c2238] uppercase tracking-widest"><i class="fa-solid fa-pen-to-square mr-2"></i> Edit Agent Sale</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('agent-tickets.update', $ticket->id) }}" method="POST" class="flex flex-col gap-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Date</label>
                        <input type="date" name="sale_date" value="{{ \Carbon\Carbon::parse($ticket->sale_date)->format('Y-m-d') }}" required class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none text-[14px] px-3 py-2.5 shadow-sm">
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Agent Name</label>
                        <input type="text" name="agent_name" value="{{ $ticket->agent_name }}" required placeholder="Enter Agent Name" class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none text-[14px] px-3 py-2.5 shadow-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Bus Name</label>
                    <input type="text" name="bus_name" value="{{ $ticket->bus_name }}" required placeholder="Enter Bus Name" class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none text-[14px] px-3 py-2.5 shadow-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Seats (Sheets)</label>
                        <input type="number" id="total_seats" name="total_seats" min="1" value="{{ $ticket->total_seats }}" required class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none text-[14px] px-3 py-2.5 shadow-sm font-bold text-[#1c2238]">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Seat Price ₹</label>
                        <input type="number" id="seat_price" name="seat_price" step="0.01" min="0" value="{{ $ticket->seat_price }}" required placeholder="e.g. 600" class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none text-[14px] px-3 py-2.5 shadow-sm font-bold text-[#1c2238]">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Commission %</label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="comm_pct" name="commission_percentage" step="0.01" min="0" value="{{ $ticket->commission_percentage + 0 }}" required placeholder="e.g. 10" class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none text-[14px] px-3 py-2.5 shadow-sm">
                            <span class="text-gray-500 font-bold text-lg">%</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 border border-gray-200 mt-4 rounded-none">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-[13px] font-bold text-gray-500 uppercase tracking-widest">Total Amount</span>
                        <span class="font-bold text-[#1c2238] text-[16px]">₹ <span id="lbl_total_amt">{{ number_format($ticket->total_amount, 2) }}</span></span>
                        <input type="hidden" name="total_amount" id="inp_total_amt" value="{{ $ticket->total_amount }}">
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[13px] font-bold text-gray-500 uppercase tracking-widest">Commission Amount</span>
                        <span class="font-bold text-rose-500 text-[16px]">₹ <span id="lbl_comm_amt">{{ number_format($ticket->commission_amount, 2) }}</span></span>
                        <input type="hidden" name="commission_amount" id="inp_comm_amt" value="{{ $ticket->commission_amount }}">
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t-2 border-gray-200">
                        <span class="text-[14px] font-black text-gray-600 uppercase tracking-widest">Net Revenue</span>
                        <span class="font-black text-green-600 text-[24px]">₹ <span id="lbl_net_amt">{{ number_format($ticket->net_amount, 2) }}</span></span>
                        <input type="hidden" name="net_amount" id="inp_net_amt" value="{{ $ticket->net_amount }}">
                    </div>
                </div>

                <button type="submit" class="w-full mt-4 bg-[#1c2238] text-white font-bold text-[15px] px-4 py-4 rounded-none hover:bg-[#29324b] transition-colors shadow-sm uppercase tracking-widest">
                    <i class="fa-solid fa-save mr-2"></i> Update Agent Record
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const elSeats = document.getElementById('total_seats');
    const elPrice = document.getElementById('seat_price');
    const elPct = document.getElementById('comm_pct');
    const lblTotal = document.getElementById('lbl_total_amt');
    const lblComm = document.getElementById('lbl_comm_amt');
    const lblNet = document.getElementById('lbl_net_amt');
    const inpTotal = document.getElementById('inp_total_amt');
    const inpComm = document.getElementById('inp_comm_amt');
    const inpNet = document.getElementById('inp_net_amt');

    function calculate() {
        const seats = parseInt(elSeats.value) || 0;
        const price = parseFloat(elPrice.value) || 0;
        const pct = parseFloat(elPct.value) || 0;
        
        const total = seats * price;
        const comm = total * (pct / 100);
        const net = total - comm;

        lblTotal.textContent = total.toFixed(2);
        lblComm.textContent = comm.toFixed(2);
        lblNet.textContent = net.toFixed(2);
        
        inpTotal.value = total.toFixed(2);
        inpComm.value = comm.toFixed(2);
        inpNet.value = net.toFixed(2);
    }

    elSeats.addEventListener('input', calculate);
    elPrice.addEventListener('input', calculate);
    elPct.addEventListener('input', calculate);
});
</script>

@endsection
