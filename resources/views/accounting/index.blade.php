@extends('layouts.app')

@section('title', 'Accounting')
@section('header', 'Accounting')

@section('content')

@php
    $personalBuses   = $buses->where('bus_type', 'Personal');
    $commissionBuses = $buses->where('bus_type', 'Commission');
    $activeType      = request('bus_type', '');

    // ── Compute subtotals directly from per-bus $accountingData ────────────
    // This ensures subtotals ALWAYS match what's shown in the rows (no mismatch).

    $pRev  = 0; $pAdv  = 0; $pPend  = 0;
    foreach ($personalBuses as $bus) {
        $d = $accountingData->get($bus->id);
        if (!$d) continue;
        $pRev  += $d->total_revenue ?? 0;
        $pAdv  += $d->total_advance ?? 0;
        $pPend += $d->total_pending ?? 0;
    }

    $cRev  = 0; $cAdv  = 0; $cPend  = 0; $cComm = 0; $cNet = 0;
    foreach ($commissionBuses as $bus) {
        $d = $accountingData->get($bus->id);
        if (!$d) continue;
        $cRev  += $d->total_revenue     ?? 0;
        $cAdv  += $d->total_advance     ?? 0;
        $cPend += $d->total_pending     ?? 0;
        $cComm += $d->total_commission  ?? 0;
        $cNet  += $d->total_net_revenue ?? 0;
    }

    // Grand totals — respect the active toggle
    if ($activeType === 'Personal') {
        $grandRev  = $pRev;  $grandAdv  = $pAdv;
        $grandPend = $pPend; $grandComm = 0;
        $grandNet  = $pRev;  // personal owner keeps all revenue
    } elseif ($activeType === 'Commission') {
        $grandRev  = $cRev;  $grandAdv  = $cAdv;
        $grandPend = $cPend; $grandComm = $cComm;
        $grandNet  = $cNet;
    } else {
        $grandRev  = $pRev  + $cRev;
        $grandAdv  = $pAdv  + $cAdv;
        $grandPend = $pPend + $cPend;
        $grandComm = $cComm;
        $grandNet  = $pRev  + $cNet;
    }

    // KPI card bookings — always show combined (from controller data)
    $totalBookings     = ($personalData->bookings  ?? 0) + ($commissionData->bookings  ?? 0);
    $totalPersonalBook = $personalData->bookings   ?? 0;
    $totalCommBook     = $commissionData->bookings ?? 0;
@endphp

{{-- ── Filter Bar ──────────────────────────────────────────────────────── --}}
<div class="bg-white shadow-sm p-4 mb-6 rounded-none border border-gray-100">
    <form method="GET" action="{{ route('accounting.index') }}" class="flex flex-wrap items-end gap-3 w-full">
        <div class="min-w-[160px] flex-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full border border-gray-200 rounded-none px-3 py-2 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#f0b44b]">
        </div>
        <div class="min-w-[160px] flex-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="w-full border border-gray-200 rounded-none px-3 py-2 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#f0b44b]">
        </div>
        <div class="min-w-[180px] flex-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Search Bus</label>
            <select name="bus_id" class="w-full border border-gray-200 rounded-none px-3 py-2 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#f0b44b] bg-white">
                <option value="">All Buses</option>
                @foreach($allBuses as $b)
                    <option value="{{ $b->id }}" {{ request('bus_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="bus_type" value="{{ request('bus_type') }}">
        <div class="flex gap-2 shrink-0">
            <button type="submit" class="bg-[#1c2238] text-white font-bold text-[13px] px-5 py-2 rounded-none hover:bg-[#29324b] transition-colors">
                <i class="fa-solid fa-filter mr-1.5 text-[#f0b44b]"></i> Apply
            </button>
            <button type="submit" name="action" value="export_pdf" 
                    onclick="if(!document.querySelector('input[name=date_from]').value || !document.querySelector('input[name=date_to]').value) { alert('Please select both From and To dates to print the ledger.'); return false; }"
                    class="bg-[#f0b44b] text-[#1c2238] font-bold text-[13px] px-5 py-2 rounded-none hover:bg-[#e0a23b] transition-colors shadow-sm">
                <i class="fa-solid fa-print mr-1.5"></i> Print
            </button>
            <a href="{{ route('accounting.index') }}" class="bg-gray-100 text-gray-600 font-semibold text-[13px] px-4 py-2 rounded-none hover:bg-gray-200 transition-colors flex items-center">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- ── Summary KPI Cards ─────────────────────────────────────────────────── --}}
<div class="flex flex-nowrap overflow-x-auto gap-4 mb-6 pb-2 w-full">
    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Bookings</p>
        <p class="text-[28px] font-black text-[#1c2238] leading-none mb-1">{{ $totalBookings }}</p>
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-[11px] text-gray-500">{{ $totalPersonalBook }} personal · {{ $totalCommBook }} commission</span>
        </div>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Gross Revenue</p>
        <p class="text-[28px] font-black text-green-600 leading-none mb-1">₹{{ number_format($grandRev, 0) }}</p>
        <p class="text-[11px] text-gray-500">Total billed to passengers</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Advance Collected</p>
        <p class="text-[28px] font-black text-blue-600 leading-none mb-1">₹{{ number_format($grandAdv, 0) }}</p>
        <p class="text-[11px] text-gray-500">Received upfront from passengers</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pending (Baki)</p>
        <p class="text-[28px] font-black text-rose-500 leading-none mb-1">₹{{ number_format($grandPend, 0) }}</p>
        <p class="text-[11px] text-gray-500">Still owed by passengers</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Commission</p>
        <p class="text-[28px] font-black text-orange-500 leading-none mb-1">₹{{ number_format($grandComm, 0) }}</p>
        <p class="text-[11px] text-gray-500">Deducted from gross revenue</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Net Owner Profit</p>
        <p class="text-[28px] font-black leading-none mb-1 {{ $grandNet == 0 ? 't-grn' : 't-ros' }}">₹{{ number_format($grandNet, 0) }}</p>
        <p class="text-[11px] text-gray-500">Gross revenue minus commission</p>
    </div>
</div>

{{-- ── Bus Ledger ────────────────────────────────────────────────────────── --}}
<style>
.ledger-container {
  font-family: 'Inter', -apple-system, sans-serif;
  background: #fff;
  border-radius: 0;
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  border: 1px solid #e5e7eb;
  overflow: hidden;
  margin-bottom: 2rem;
}
.ld-header {
  display: flex; justify-content: space-between; align-items: center;
  padding: 20px 28px; border-bottom: 1px solid #e5e7eb;
}
.ld-title { display: flex; align-items: center; gap: 14px; }
.ld-icon  { background:#192132; border-radius:0; width:42px; height:42px; display:flex; align-items:center; justify-content:center; font-size:20px; }
.ld-title h2 { margin:0 0 3px; font-size:18px; font-weight:700; color:#192132; }
.ld-title p  { margin:0; font-size:13px; color:#9ca3af; }
.ld-toggle { background:#f1f5f9; border-radius:0; display:flex; padding:4px; gap:4px; }
.ld-toggle button { border:none; background:transparent; padding:7px 18px; border-radius:0; font-size:13px; font-weight:500; color:#64748b; cursor:pointer; transition:all 0.2s; }
.ld-toggle button.active { background:#192132; color:#fff; }
.ld-sec-hdr { padding:12px 20px; background:#fafafa; border-bottom:1px solid #e5e7eb; font-size:14px; font-weight:600; color:#374151; border-top:1px solid #e5e7eb; }
.ld-table { width:100%; border-collapse:collapse; }
.ld-table th, .ld-table td { padding:12px 16px; border-bottom:1px solid #f3f4f6; font-size:13px; }
.ld-table th { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#6b7280; background:#f9fafb; text-align:left; border-bottom:1px solid #e5e7eb; }
.ld-table tbody tr:hover { background:#f9fafb; }
.ld-table tbody tr:last-child td { border-bottom:none; }
/* Subtotal row */
.ld-subtotal td { background:#f3f4f6; border-top:2px solid #d1d5db; border-bottom:none !important; font-weight:700; font-size:13px; }
/* Grand Total row */
.ld-grand td { background:#192132; color:#fff; border-top:3px solid #0f1623; border-bottom:none !important; font-weight:700; font-size:14px; padding:14px 16px; }
.r { text-align:right !important; }
.c { text-align:center !important; }
.t-mut { color:#9ca3af; }
.t-grn { color:#059669; }
.t-ros { color:#e11d48; }
.t-blu { color:#60a5fa; }
.t-sky { color:#0ea5e9; }
.t-egrn{ color:#34d399; }
.t-wht { color:#fff; }
.t-b   { font-weight:700; }
</style>

<div class="ledger-container">

  {{-- Header --}}
  <div class="ld-header">
    <div class="ld-title">
      <div class="ld-icon"><i class="fa-solid fa-book-open text-[#f0b44b]"></i></div>
      <div>
        <h2>Bus Ledger</h2>
        <p>Revenue, advance, pending & commission per bus</p>
      </div>
    </div>
    <div class="ld-toggle">
      <button class="{{ $activeType==='' ? 'active':'' }}" onclick="location.href='{{ request()->fullUrlWithQuery(['bus_type'=>'']) }}'">All</button>
      <button class="{{ $activeType==='Personal' ? 'active':'' }}" onclick="location.href='{{ request()->fullUrlWithQuery(['bus_type'=>'Personal']) }}'">Personal</button>
      <button class="{{ $activeType==='Commission' ? 'active':'' }}" onclick="location.href='{{ request()->fullUrlWithQuery(['bus_type'=>'Commission']) }}'">Commission</button>
    </div>
  </div>

  {{-- ── PERSONAL BUSES ── --}}
  @if($personalBuses->count() && $activeType !== 'Commission')
  <div>
    <div class="ld-sec-hdr"><i class="fa-solid fa-bus text-[#f0b44b] mr-2"></i> Personal Buses</div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50/50">
      @foreach($personalBuses as $bus)
        <a href="{{ route('accounting.show', $bus->id) }}?{{ request()->getQueryString() }}" class="block bg-white border border-gray-200 rounded-none p-5 hover:border-[#f0b44b] transition-all group relative">
          <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-none bg-white border border-gray-100 flex items-center justify-center text-[#f0b44b] group-hover:bg-[#f0b44b] group-hover:text-[#1c2238] transition-colors shadow-sm">
                  <i class="fa-solid fa-bus text-lg"></i>
              </div>
              <div>
                  <h3 class="font-bold text-[#1c2238] text-[15px] tracking-wide uppercase mb-1">{{ $bus->name }}</h3>
                  <p class="text-[11px] font-bold text-gray-500 font-mono tracking-widest">{{ $bus->plate_number }}</p>
              </div>
          </div>
          <div class="mt-5 pt-4 border-t border-gray-100 flex justify-between items-center">
              <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-[#f0b44b] transition-colors">View Ledger</span>
              <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-[#f0b44b] transition-colors text-sm"></i>
          </div>
        </a>
      @endforeach
    </div>
  </div>
  @endif

  {{-- ── COMMISSION BUSES ── --}}
  @if($commissionBuses->count() && $activeType !== 'Personal')
  <div>
    <div class="ld-sec-hdr"><i class="fa-solid fa-handshake text-[#1c2238] mr-2"></i> Commission Buses</div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50/50">
      @foreach($commissionBuses as $bus)
        <a href="{{ route('accounting.show', $bus->id) }}?{{ request()->getQueryString() }}" class="block bg-white border border-gray-200 rounded-none p-5 hover:border-[#1c2238] transition-all group relative">
          <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-none bg-white border border-gray-100 flex items-center justify-center text-[#1c2238] group-hover:bg-[#1c2238] group-hover:text-white transition-colors shadow-sm">
                  <i class="fa-solid fa-handshake text-lg"></i>
              </div>
              <div>
                  <h3 class="font-bold text-[#1c2238] text-[15px] tracking-wide uppercase mb-1">{{ $bus->name }}</h3>
                  <p class="text-[11px] font-bold text-gray-500 font-mono tracking-widest">{{ $bus->plate_number }}</p>
              </div>
          </div>
          <div class="mt-5 pt-4 border-t border-gray-100 flex justify-between items-center">
              <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-[#1c2238] transition-colors">View Ledger</span>
              <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-[#1c2238] transition-colors text-sm"></i>
          </div>
        </a>
      @endforeach
    </div>
  </div>
  @endif

  {{-- ── GRAND TOTAL — shown only in 'All' mode, as a separate combined bar ── --}}
  @if($activeType === '')
  <div style="background:#192132; padding:16px 20px;">
    {{-- Personal row --}}
    @if($personalBuses->count())
    <div style="display:flex; align-items:center; padding:6px 0; border-bottom:1px solid #2d3654;">
      <span style="flex:1; font-size:13px; font-weight:600; color:#94a3b8;"><i class="fa-solid fa-bus text-[#f0b44b] mr-1.5"></i> Personal Total</span>
      <span style="width:160px; text-align:right; font-size:14px; font-weight:700; color:#fff;">₹{{ number_format($pRev, 2) }}</span>
      <span style="width:160px; text-align:right; font-size:14px; color:#0ea5e9;">₹{{ number_format($pAdv, 2) }}</span>
      <span style="width:160px; text-align:right; font-size:14px; color:{{ $pPend > 0 ? '#f43f5e' : '#34d399' }};">₹{{ number_format($pPend, 2) }}</span>
      <span style="width:140px; text-align:right; font-size:14px; color:#4b5563;">—</span>
      <span style="width:160px; text-align:right; font-size:14px; font-weight:700; color:#34d399;">₹{{ number_format($pRev, 2) }}</span>
    </div>
    @endif
    {{-- Commission row --}}
    @if($commissionBuses->count())
    <div style="display:flex; align-items:center; padding:6px 0; border-bottom:1px solid #2d3654;">
      <span style="flex:1; font-size:13px; font-weight:600; color:#94a3b8;"><i class="fa-solid fa-handshake text-[#6366f1] mr-1.5"></i> Commission Total</span>
      <span style="width:160px; text-align:right; font-size:14px; font-weight:700; color:#fff;">₹{{ number_format($cRev, 2) }}</span>
      <span style="width:160px; text-align:right; font-size:14px; color:#0ea5e9;">₹{{ number_format($cAdv, 2) }}</span>
      <span style="width:160px; text-align:right; font-size:14px; color:{{ $cPend > 0 ? '#f43f5e' : '#34d399' }};">₹{{ number_format($cPend, 2) }}</span>
      <span style="width:140px; text-align:right; font-size:14px; color:#60a5fa;">₹{{ number_format($cComm, 2) }}</span>
      <span style="width:160px; text-align:right; font-size:14px; font-weight:700; color:#34d399;">₹{{ number_format($cNet, 2) }}</span>
    </div>
    @endif
    {{-- Grand Total row --}}
    <div style="display:flex; align-items:center; padding:10px 0 0 0;">
      <span style="flex:1; font-size:16px; font-weight:800; color:#fff;">Grand Total</span>
      <div style="display:flex; gap:0;">
        <div style="width:160px; text-align:right; padding-right:0;">
          <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:3px;">Gross Revenue</div>
          <div style="font-size:17px; font-weight:800; color:#fff;">₹{{ number_format($grandRev, 2) }}</div>
        </div>
        <div style="width:160px; text-align:right;">
          <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:3px;">Advance</div>
          <div style="font-size:17px; font-weight:800; color:#0ea5e9;">₹{{ number_format($grandAdv, 2) }}</div>
        </div>
        <div style="width:160px; text-align:right;">
          <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:3px;">Pending</div>
          <div style="font-size:17px; font-weight:800; color:{{ $grandPend > 0 ? '#f43f5e' : '#34d399' }};">₹{{ number_format($grandPend, 2) }}</div>
        </div>
        <div style="width:140px; text-align:right;">
          <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:3px;">Commission</div>
          <div style="font-size:17px; font-weight:800; color:#60a5fa;">₹{{ number_format($grandComm, 2) }}</div>
        </div>
        <div style="width:160px; text-align:right;">
          <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:3px;">Net to Owner</div>
          <div style="font-size:20px; font-weight:900; color:{{ $grandNet == 0 ? '#34d399' : '#f43f5e' }};">₹{{ number_format($grandNet, 2) }}</div>
        </div>
      </div>
    </div>
  @endif

</div>

{{-- ── COMMISSION LEDGER ── --}}
@if($commissionBuses->count() && $activeType !== 'Personal')
<div class="ledger-container">
  <div class="ld-header">
    <div class="ld-title">
      <div class="ld-icon"><i class="fa-solid fa-file-invoice-dollar text-[#60a5fa]"></i></div>
      <div>
        <h2>Commission Ledger</h2>
        <p>Breakdown of total commission by bus</p>
      </div>
    </div>
  </div>
  <table class="ld-table">
    <thead>
      <tr>
        <th>Bus Name</th>
        <th class="c">Bookings</th>
        <th class="r">Gross Revenue</th>
        <th class="r">Commission Amount</th>
        <th class="r">Net to Owner</th>
        <th class="c">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($commissionBuses as $bus)
        @php 
          $d = $accountingData->get($bus->id); 
          if(!$d) continue;
        @endphp
        <tr>
          <td class="t-b text-[#1c2238] uppercase">{{ $bus->name }} <span class="text-xs text-gray-400 normal-case ml-1">({{ $bus->plate_number }})</span></td>
          <td class="c">{{ $d->total_bookings }}</td>
          <td class="r t-b">₹{{ number_format($d->total_revenue, 2) }}</td>
          <td class="r t-blu t-b">₹{{ number_format($d->total_commission, 2) }}</td>
          <td class="r t-b {{ ($d->total_net_revenue ?? 0) == 0 ? 't-grn' : 't-ros' }}">
            ₹{{ number_format($d->total_net_revenue ?? 0, 2) }}
          </td>
          <td class="c">
              <a href="{{ route('accounting.show', $bus->id) }}?{{ request()->getQueryString() }}" class="text-[#1c2238] hover:text-[#f0b44b] transition-colors font-bold text-xs uppercase tracking-widest">
                  View <i class="fa-solid fa-arrow-right ml-1"></i>
              </a>
          </td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="ld-subtotal">
        <td class="r uppercase tracking-widest text-xs">Total</td>
        <td class="c">{{ $commissionBuses->sum(fn($b) => $accountingData->get($b->id)->total_bookings ?? 0) }}</td>
        <td class="r">₹{{ number_format($cRev, 2) }}</td>
        <td class="r t-blu t-b" style="font-size: 15px;">₹{{ number_format($cComm, 2) }}</td>
        <td class="r t-b {{ $cNet == 0 ? 't-grn' : 't-ros' }}" style="font-size: 15px;">
            ₹{{ number_format(abs($cNet), 2) }}
        </td>
        <td></td>
      </tr>
    </tfoot>
  </table>
</div>
@endif

@endsection
