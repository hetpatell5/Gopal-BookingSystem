@extends('layouts.app')

@section('title', 'Bus Accounting Dashboard')
@section('header', 'Bus Accounting: ' . $bus->name)

@section('content')

{{-- ── Filter Bar ── --}}
<div class="bg-white shadow-sm p-4 mb-6 rounded-none border border-gray-100">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-none border border-gray-200 {{ $bus->bus_type === 'Personal' ? 'bg-orange-50 text-[#f0b44b]' : 'bg-indigo-50 text-[#1c2238]' }} flex items-center justify-center text-lg shadow-sm">
                <i class="fa-solid {{ $bus->bus_type === 'Personal' ? 'fa-bus' : 'fa-handshake' }}"></i>
            </div>
            <div>
                <h2 class="text-[17px] font-bold text-[#1c2238] uppercase tracking-wide">{{ $bus->name }}</h2>
                <p class="text-[11px] text-gray-500 font-bold font-mono tracking-widest">{{ $bus->plate_number }} <span class="mx-1">•</span> {{ $bus->bus_type }} Bus</p>
            </div>
        </div>
        <a href="{{ route('accounting.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Ledger
        </a>
    </div>

    <form method="GET" action="{{ route('accounting.show', $bus->id) }}" class="flex flex-wrap items-end gap-3 w-full border-t border-gray-100 pt-4">
        <div class="min-w-[160px] flex-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full border border-gray-200 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#f0b44b]">
        </div>
        <div class="min-w-[160px] flex-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="w-full border border-gray-200 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#f0b44b]">
        </div>
        <div class="flex gap-2 shrink-0">
            <button type="submit" class="bg-[#1c2238] text-white font-bold text-[13px] px-5 py-2 rounded-none hover:bg-[#29324b] transition-colors">
                <i class="fa-solid fa-filter mr-1.5 text-[#f0b44b]"></i> Apply Filter
            </button>
            <button type="submit" name="action" value="export_pdf" class="bg-[#f0b44b] text-[#1c2238] font-bold text-[13px] px-5 py-2 rounded-none hover:bg-[#e0a23b] transition-colors shadow-sm">
                <i class="fa-solid fa-print mr-1.5"></i> Print Hisab
            </button>
            <a href="{{ route('accounting.show', $bus->id) }}" class="bg-gray-100 text-gray-600 font-semibold text-[13px] px-4 py-2 rounded-none hover:bg-gray-200 transition-colors flex items-center">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- ── Summary KPI Cards for this Bus ── --}}
<div class="flex flex-nowrap overflow-x-auto gap-4 mb-6 pb-2 w-full">
    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Bookings</p>
        <p class="text-[28px] font-black text-[#1c2238] leading-none mb-1">{{ $totals->total_bookings ?? 0 }}</p>
        <p class="text-[11px] text-gray-500">{{ $totals->total_seats_sold ?? 0 }} seats sold</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Gross Revenue</p>
        <p class="text-[28px] font-black text-green-600 leading-none mb-1">₹{{ number_format($totals->total_revenue ?? 0, 2) }}</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Advance Collected</p>
        <p class="text-[28px] font-black text-blue-600 leading-none mb-1">₹{{ number_format($totals->total_advance ?? 0, 2) }}</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pending (Baki)</p>
        <p class="text-[28px] font-black {{ ($totals->total_pending ?? 0) > 0 ? 'text-rose-500' : 'text-emerald-500' }} leading-none mb-1">₹{{ number_format($totals->total_pending ?? 0, 2) }}</p>
    </div>

    @if($bus->bus_type === 'Commission')
    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Net Owner Revenue</p>
        <p class="text-[28px] font-black text-[#1c2238] leading-none mb-1">₹{{ number_format($totals->total_net_revenue ?? 0, 2) }}</p>
        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest">Comm: ₹{{ number_format($totals->total_commission ?? 0, 2) }}</p>
    </div>
    @endif
</div>

{{-- ── Day-to-Day Hisab Table ── --}}
<style>
.ledger-container {
  font-family: 'Inter', -apple-system, sans-serif;
  background: #fff;
  border-radius: 0;
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  overflow: hidden;
  margin-bottom: 2rem;
  border: 1px solid #e5e7eb;
}
.ld-header {
  display: flex; justify-content: space-between; align-items: center;
  padding: 20px 28px; border-bottom: 1px solid #e5e7eb;
}
.ld-title { display: flex; align-items: center; gap: 14px; }
.ld-icon  { background:#1c2238; border-radius:0; width:42px; height:42px; display:flex; align-items:center; justify-content:center; font-size:20px; }
.ld-title h2 { margin:0 0 3px; font-size:16px; font-weight:800; color:#1c2238; text-transform:uppercase; letter-spacing:0.05em; }
.ld-title p  { margin:0; font-size:12px; font-weight:700; color:#9ca3af; letter-spacing:0.02em; }
.ld-table { width:100%; border-collapse:collapse; }
.ld-table th, .ld-table td { padding:12px 16px; border-bottom:1px solid #f3f4f6; font-size:13px; }
.ld-table th { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#6b7280; background:#f9fafb; text-align:left; border-bottom:1px solid #e5e7eb; }
.ld-table tbody tr:hover { background:#f9fafb; }
.ld-table tbody tr:last-child td { border-bottom:none; }
.ld-subtotal td { background:#f3f4f6; border-top:2px solid #d1d5db; border-bottom:none !important; font-weight:700; font-size:13px; }
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
  <div class="ld-header">
    <div class="ld-title">
      <div class="ld-icon"><i class="fa-solid fa-calendar-days text-[#f0b44b]"></i></div>
      <div>
        <h2>Day-to-Day Hisab</h2>
        <p>Daily breakdown of revenue and collections</p>
      </div>
    </div>
  </div>

  @if($bookings->count() > 0)
    <table class="ld-table">
      <thead>
        <tr>
          <th>Booking Date</th>
          <th>Journey Date</th>
          <th>Passenger Name</th>
          <th class="c">Seats</th>
          <th class="r">Rate</th>
          <th class="r">Gross Revenue</th>
          <th class="r">Advance Paid</th>
          <th class="r">Pending (Baki)</th>
          <th class="c">Hisab Status</th>
          @if($bus->bus_type === 'Commission')
          <th class="r">Commission</th>
          <th class="r">Net to Owner</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach($bookings as $booking)
          @php
              $pend = $booking->total_amount - $booking->payable_amount;
              $net = $booking->total_amount - $booking->commission_amount;
          @endphp
          <tr>
            <td class="t-mut" style="font-size:12px;">{{ $booking->created_at->format('d M Y') }}</td>
            <td class="t-b">{{ \Carbon\Carbon::parse($booking->journey_date)->format('d M Y') }}</td>
            <td class="t-b text-[#1c2238] uppercase tracking-wide">{{ $booking->passenger_name }}</td>
            <td class="c">{{ $booking->total_seats }} <span class="text-xs text-gray-400">({{ $booking->seat_number }})</span></td>
            <td class="r text-gray-500 font-mono">₹{{ number_format($booking->per_seat_price ?? 0, 2) }}</td>
            <td class="r t-b">₹{{ number_format($booking->total_amount, 2) }}</td>
            <td class="r t-sky">₹{{ number_format($booking->payable_amount, 2) }}</td>
            <td class="r {{ $pend > 0 ? 't-ros' : 't-grn' }}">₹{{ number_format($pend, 2) }}</td>
            <td class="c">
                <div class="flex items-center justify-center gap-2">
                    <input type="checkbox" 
                           class="hisab-checkbox w-4 h-4 rounded-none border-gray-300 text-[#f0b44b] focus:ring-[#f0b44b] cursor-pointer"
                           data-passenger-id="{{ $booking->id }}" 
                           {{ $booking->is_hisab_completed ? 'checked' : '' }}
                           title="Mark as Paid/Completed">
                    <span class="status-label text-[10px] font-bold {{ $booking->is_hisab_completed ? 'text-green-600' : 'text-gray-400' }}">
                        {{ $booking->is_hisab_completed ? 'PAID' : 'UNPAID' }}
                    </span>
                </div>
            </td>
            @if($bus->bus_type === 'Commission')
            <td class="r t-sky">₹{{ number_format($booking->commission_amount, 2) }}</td>
            <td class="r t-egrn t-b">₹{{ number_format($net, 2) }}</td>
            @endif
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="ld-subtotal">
          <td colspan="3" class="r uppercase tracking-widest text-xs">Total for {{ $totals->total_bookings ?? 0 }} Bookings</td>
          <td class="c">{{ $totals->total_seats_sold ?? 0 }}</td>
          <td class="r"></td>
          <td class="r">₹{{ number_format($totals->total_revenue ?? 0, 2) }}</td>
          <td class="r t-sky">₹{{ number_format($totals->total_advance ?? 0, 2) }}</td>
          <td class="r {{ ($totals->total_pending ?? 0) > 0 ? 't-ros' : 't-grn' }}">₹{{ number_format($totals->total_pending ?? 0, 2) }}</td>
          <td class="c"></td>
          @if($bus->bus_type === 'Commission')
          <td class="r t-sky">₹{{ number_format($totals->total_commission ?? 0, 2) }}</td>
          <td class="r t-egrn">₹{{ number_format($totals->total_net_revenue ?? 0, 2) }}</td>
          @endif
        </tr>
      </tfoot>
    </table>
  @else
    <div class="p-12 text-center text-gray-500">
        <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300"></i>
        <p class="text-[15px] font-semibold text-[#1c2238] mb-1">No Hisab Data</p>
        <p class="text-[13px]">There is no booking data for this bus in the selected period.</p>
    </div>
  @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.hisab-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const passengerId = this.dataset.passengerId;
            const isChecked = this.checked;
            const label = this.nextElementSibling;
            
            // Optimistically update label
            if (isChecked) {
                label.textContent = 'PAID';
                label.classList.remove('text-gray-400', 'text-red-600');
                label.classList.add('text-green-600');
            } else {
                label.textContent = 'UNPAID';
                label.classList.remove('text-green-600');
                label.classList.add('text-gray-400');
            }
            
            fetch(`/passengers/${passengerId}/toggle-hisab`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ is_completed: isChecked })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Failed to update status.');
                    this.checked = !isChecked; // revert
                    if (this.checked) {
                        label.textContent = 'PAID';
                        label.classList.add('text-green-600');
                        label.classList.remove('text-gray-400');
                    } else {
                        label.textContent = 'UNPAID';
                        label.classList.add('text-gray-400');
                        label.classList.remove('text-green-600');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the status.');
                this.checked = !isChecked; // revert
                if (this.checked) {
                    label.textContent = 'PAID';
                    label.classList.add('text-green-600');
                    label.classList.remove('text-gray-400');
                } else {
                    label.textContent = 'UNPAID';
                    label.classList.add('text-gray-400');
                    label.classList.remove('text-green-600');
                }
            });
        });
    });
});
</script>
@endsection
