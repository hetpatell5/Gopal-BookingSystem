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
                   class="w-full border border-gray-200 rounded-none px-3 py-2 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#f0b44b]">
        </div>
        <div class="min-w-[160px] flex-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="w-full border border-gray-200 rounded-none px-3 py-2 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#f0b44b]">
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
        <p class="text-[11px] text-gray-500">Total fare billed to passengers</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Advance Collected</p>
        <p class="text-[28px] font-black text-blue-600 leading-none mb-1">₹{{ number_format($totals->total_advance ?? 0, 2) }}</p>
        <p class="text-[11px] text-gray-500">Received upfront from passengers</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Bus Ma Apela (Baki)</p>
        <p class="text-[28px] font-black t-grn leading-none mb-1">₹{{ number_format($totals->total_baki ?? 0, 2) }}</p>
        <p class="text-[11px] text-gray-500">Still owed by passengers</p>
    </div>

    @if($bus->bus_type === 'Commission')
    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Commission</p>
        <p class="text-[28px] font-black text-orange-500 leading-none mb-1">₹{{ number_format($totals->total_commission ?? 0, 2) }}</p>
        <p class="text-[11px] text-gray-500">Agent's commission deducted</p>
    </div>

    <div class="flex-1 min-w-[200px] bg-white shadow-sm p-4 border border-gray-100 rounded-none">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Net Owner Profit</p>
        <p class="text-[28px] font-black t-grn leading-none mb-1">₹{{ number_format($totals->total_net_profit ?? 0, 2) }}</p>
        <p class="text-[11px] text-gray-500">Gross revenue minus commission</p>
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
.ld-table th, .ld-table td { padding:10px 14px; border-bottom:1px solid #f3f4f6; font-size:13px; }
.ld-table th { font-size:12px; font-weight:700; color:#374151; background:#f9fafb; text-align:left; border-bottom:1px solid #e5e7eb; white-space:nowrap; }
.ld-table tbody tr:hover { background:#fafafa; }
.ld-table tbody tr:last-child td { border-bottom:none; }
.ld-subtotal td, .ld-subtotal th { background:#f3f4f6; border-top:2px solid #d1d5db; border-bottom:none !important; font-weight:700; font-size:13px; }
.r { text-align:right !important; }
.c { text-align:center !important; }
.t-mut { color:#9ca3af; }
.t-grn { color:#059669; }
.t-ros { color:#e11d48; }
.t-blu { color:#60a5fa; }
.t-sky { color:#0ea5e9; }
.t-egrn{ color:#34d399; }
.t-b   { font-weight:700; }
.inline-contact-input {
  border: 1px solid transparent;
  background: transparent;
  border-radius: 4px;
  padding: 3px 6px;
  font-size: 12px;
  width: 100%;
  min-width: 100px;
  transition: border-color 0.2s, background 0.2s;
  outline: none;
}
.inline-contact-input:hover {
  border-color: #d1d5db;
  background: #fff;
}
.inline-contact-input:focus {
  border-color: #f0b44b;
  background: #fff;
  box-shadow: 0 0 0 2px rgba(240,180,75,0.15);
}
.inline-contact-input.saved {
  border-color: #34d399;
  animation: flash-green 0.5s ease;
}
@keyframes flash-green {
@keyframes flash-green {
  0%   { background:#d1fae5; }
  100% { background:#fff; }
}
.wa-btn {
  display: inline-flex; justify-content: center; align-items: center;
  width: 32px; height: 32px; border-radius: 50%;
  background: #f0fdf4; color: #16a34a; transition: all 0.2s;
  text-decoration: none; cursor: pointer; border: 1px solid #dcfce7;
}
.wa-btn:hover {
  background: #22c55e; color: #fff; border-color: #22c55e;
}
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
    <div class="overflow-x-auto">
    <table class="ld-table">
      <thead>
        <tr>
          <th>Journey Date</th>
          <th class="c">Bookings</th>
          <th class="c">Total Seats</th>
          <th class="r">Gross Revenue</th>
          <th class="r">Advance Paid</th>
          <th class="r">Pending (Baki)</th>
          @if($bus->bus_type === 'Commission')
          <th class="r">Commission <span style="font-size:10px;font-weight:900;color:#059669;">(L)</span></th>
          <th class="r">Net to Owner <span style="font-size:10px;font-weight:900;color:#f43f5e;">(D)</span></th>
          @endif
          <th>Person Name</th>
          <th>Collection Date</th>
          <th>Mobile No.</th>
          <th class="c">Status</th>
          <th class="c">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($bookings as $booking)
          @php
              // If hisab is completed/paid, pending is effectively 0
              $pend = $booking->is_hisab_completed ? 0 : ($booking->total_amount - $booking->payable_amount);
              
              // Raw net to owner (Advance - Commission)
              $rawNet = $booking->payable_amount - $booking->commission_amount;
              $netColor = $booking->is_hisab_completed ? 't-grn' : ($rawNet < 0 ? 't-grn' : 't-ros');
              $netSuffix = $rawNet < 0 ? '(L)' : '(D)';
              $netSuffixColor = $booking->is_hisab_completed ? '#059669' : ($rawNet < 0 ? '#059669' : '#f43f5e');
          @endphp
          <tr class="contact-row" data-journey-date="{{ $booking->journey_date }}">
            <td class="t-b">{{ \Carbon\Carbon::parse($booking->journey_date)->format('d M Y') }}</td>
            <td class="c text-gray-500 font-bold">{{ $booking->total_bookings }}</td>
            <td class="c">{{ $booking->total_seats }}</td>
            <td class="r t-b">₹{{ number_format($booking->total_amount, 2) }}</td>
            <td class="r t-sky">₹{{ number_format($booking->payable_amount, 2) }}</td>
            <td class="r {{ $pend > 0 ? 't-ros' : 't-grn' }} t-b">₹{{ number_format($pend, 2) }}</td>
            @if($bus->bus_type === 'Commission')
            <td class="r t-grn t-b">₹{{ number_format($booking->commission_amount, 2) }}</td>
            <td class="r {{ $netColor }} t-b">
                ₹{{ number_format(abs($rawNet), 2) }}
                <span style="font-size:10px;font-weight:900;color:{{ $netSuffixColor }};">{{ $netSuffix }}</span>
            </td>
            @endif
            {{-- Inline editable: Person Name --}}
            <td>
              <input type="text"
                     class="inline-contact-input contact-field"
                     data-field="hisab_person_name"
                     value="{{ $booking->hisab_person_name }}"
                     placeholder="Person name…">
            </td>
            {{-- Inline editable: Collection Date --}}
            <td>
              <input type="date"
                     class="inline-contact-input contact-field"
                     data-field="hisab_collection_date"
                     value="{{ $booking->hisab_collection_date }}">
            </td>
            {{-- Inline editable: Mobile Number --}}
            <td>
              <input type="text"
                     class="inline-contact-input contact-field"
                     data-field="hisab_mobile_number"
                     value="{{ $booking->hisab_mobile_number }}"
                     placeholder="Mobile no…"
                     maxlength="20">
            </td>
            {{-- Status --}}
            <td class="c">
                <div class="flex items-center justify-center gap-2">
                    <input type="checkbox"
                           class="daily-hisab-checkbox w-4 h-4 rounded-none border-gray-300 text-[#f0b44b] focus:ring-[#f0b44b] cursor-pointer"
                           data-journey-date="{{ $booking->journey_date }}"
                           {{ $booking->is_hisab_completed ? 'checked' : '' }}
                           title="Mark Daily Hisab as Paid/Completed">
                    <span class="status-label text-[10px] font-bold {{ $booking->is_hisab_completed ? 'text-[#059669]' : 'text-[#f43f5e]' }}">
                        {{ $booking->is_hisab_completed ? 'PAID' : 'UNPAID' }}
                    </span>
                </div>
            </td>
            {{-- Actions: WhatsApp --}}
            <td class="c">
              @php
                $originalPend = $booking->total_amount - $booking->payable_amount;
                
                $waMsg = "*Bus Hisab – {$bus->name}*\n";
                $waMsg .= "Date: " . \Carbon\Carbon::parse($booking->journey_date)->format('d M Y') . "\n";
                $waMsg .= "Bookings: {$booking->total_bookings} | Seats: {$booking->total_seats}\n";
                $waMsg .= "Gross: Rs " . number_format($booking->total_amount, 2) . "\n";
                $waMsg .= "Advance: Rs " . number_format($booking->payable_amount, 2) . "\n";
                if($originalPend > 0) {
                    $waMsg .= "Baki: Rs " . number_format($originalPend, 2) . "\n";
                }
                if($bus->bus_type === 'Commission') {
                    $waMsg .= "Commission (L): Rs " . number_format($booking->commission_amount, 2) . "\n";
                    $waMsg .= "Net to Owner {$netSuffix}: Rs " . number_format(abs($rawNet), 2) . "\n";
                }
                
                $waMsg .= "Status: " . ($booking->is_hisab_completed ? "PAID" : "UNPAID") . "\n";
                
                if ($booking->is_hisab_completed) {
                    if ($booking->hisab_person_name) {
                        $waMsg .= "Person Name: {$booking->hisab_person_name}\n";
                    }
                    if ($booking->hisab_collection_date) {
                        $waMsg .= "Collection Date: " . \Carbon\Carbon::parse($booking->hisab_collection_date)->format('d-m-Y') . "\n";
                    }
                    if ($booking->hisab_mobile_number) {
                        $waMsg .= "Mobile No.: {$booking->hisab_mobile_number}\n";
                    }
                }
                
                $waMsg .= "\n— Shree Harikrushna Travels | 9904172734";
              @endphp
              <button type="button"
                 class="wa-btn"
                 title="Send Hisab on WhatsApp"
                 onclick="sendHisabWhatsapp(this, '{{ rawurlencode($waMsg) }}')">
                <i class="fa-brands fa-whatsapp"></i>
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="ld-subtotal">
          <td class="r uppercase tracking-widest text-xs">Total</td>
          <td class="c">{{ $totals->total_bookings ?? 0 }}</td>
          <td class="c">{{ $totals->total_seats_sold ?? 0 }}</td>
          <td class="r">₹{{ number_format($totals->total_revenue ?? 0, 2) }}</td>
          <td class="r t-sky">₹{{ number_format($totals->total_advance ?? 0, 2) }}</td>
          <td class="r {{ ($totals->total_pending ?? 0) > 0 ? 't-ros' : 't-grn' }}">₹{{ number_format($totals->total_pending ?? 0, 2) }}</td>
          @if($bus->bus_type === 'Commission')
          @php
              $totalRawNet = $totals->total_net_revenue ?? 0;
              $totalNetColor = $totalRawNet < 0 ? 't-grn' : 't-ros';
              $totalNetSuffix = $totalRawNet < 0 ? '(L)' : '(D)';
              $totalNetSuffixColor = $totalRawNet < 0 ? '#059669' : '#f43f5e';
          @endphp
          <th class="r t-grn">₹{{ number_format($totals->total_commission ?? 0, 2) }}</th>
          <th class="r {{ $totalNetColor }}">
              ₹{{ number_format(abs($totalRawNet), 2) }}
              <span style="font-size:10px;font-weight:900;color:{{ $totalNetSuffixColor }};">{{ $totalNetSuffix }}</span>
          </th>
          @endif
          <td colspan="3"></td>
          <td class="c"></td>
          <td class="c"></td>
        </tr>
      </tfoot>
    </table>
    </div>
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
    const busId = '{{ $bus->id }}';
    const csrfToken = '{{ csrf_token() }}';

    // ── Hisab Paid/Unpaid toggle ──────────────────────────────────────────────
    document.querySelectorAll('.daily-hisab-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const journeyDate = this.dataset.journeyDate;
            const isChecked = this.checked;
            const label = this.nextElementSibling;

            label.textContent = isChecked ? 'PAID' : 'UNPAID';
            label.className = 'status-label text-[10px] font-bold ' + (isChecked ? 'text-green-600' : 'text-gray-400');

            fetch(`/accounting/${busId}/toggle-daily-hisab`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ journey_date: journeyDate, is_completed: isChecked })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    this.checked = !isChecked;
                    label.textContent = this.checked ? 'PAID' : 'UNPAID';
                    label.className = 'status-label text-[10px] font-bold ' + (this.checked ? 'text-green-600' : 'text-gray-400');
                }
            })
            .catch(() => {
                this.checked = !isChecked;
                label.textContent = this.checked ? 'PAID' : 'UNPAID';
                label.className = 'status-label text-[10px] font-bold ' + (this.checked ? 'text-green-600' : 'text-gray-400');
            });
        });
    });

    // ── Inline Contact Fields (auto-save on blur or Enter) ────────────────────
    function saveContactRow(row) {
        const journeyDate = row.dataset.journeyDate;
        const fields = {};
        row.querySelectorAll('.contact-field').forEach(inp => {
            fields[inp.dataset.field] = inp.value;
        });

        fetch(`/accounting/${busId}/update-hisab-contact`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ journey_date: journeyDate, ...fields })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                row.querySelectorAll('.contact-field').forEach(inp => {
                    inp.classList.add('saved');
                    setTimeout(() => inp.classList.remove('saved'), 1200);
                });
            }
        });
    }

    document.querySelectorAll('.contact-row').forEach(row => {
        row.querySelectorAll('.contact-field').forEach(inp => {
            inp.addEventListener('blur', () => saveContactRow(row));
            inp.addEventListener('keydown', e => { if(e.key === 'Enter') { inp.blur(); } });
        });
    });
});

function sendHisabWhatsapp(btn, encodedMsg) {
    const row = btn.closest('tr');
    const mobileInput = row.querySelector('input[data-field="hisab_mobile_number"]');
    let rawNumber = mobileInput ? mobileInput.value : '';
    rawNumber = rawNumber.replace(/[^0-9]/g, '');
    
    if (rawNumber.length === 10) {
        rawNumber = '91' + rawNumber; // Add India code by default if 10 digits
    }
    
    let url = "https://wa.me/";
    if (rawNumber) {
        url += rawNumber;
    }
    url += "?text=" + encodedMsg;
    
    window.open(url, '_blank');
}
</script>
@endsection
