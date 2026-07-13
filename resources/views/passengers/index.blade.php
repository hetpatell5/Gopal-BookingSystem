@extends('layouts.app')

@section('title', 'All Passengers')
@section('header', 'Passenger Directory')

@section('content')

    <div class="bg-white rounded-none shadow-sm p-6 mb-8">
        <form method="GET" action="{{ route('passengers.index') }}" class="flex flex-wrap items-end gap-4">

            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Filter by
                    Bus</label>
                <select name="bus_id" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                    <option value="">All Buses</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                            {{ $bus->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Search
                    Passenger</label>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                    placeholder="Name, Mobile, Seat, or Pickup..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Filter by
                    Date</label>
                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">AC / Non AC</label>
                <select name="ac_type" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                    <option value="">All Types</option>
                    <option value="Ac" {{ request('ac_type') == 'Ac' ? 'selected' : '' }}>AC</option>
                    <option value="Non Ac" {{ request('ac_type') == 'Non Ac' ? 'selected' : '' }}>Non AC</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('passengers.index') }}"
                    class="bg-gray-100 text-gray-600 font-semibold py-2.5 px-4 rounded-none hover:bg-gray-200 transition-colors">
                    Clear
                </a>

                <div class="w-px h-10 bg-gray-200 mx-2"></div>

                <a href="{{ route('passengers.register', ['bus_id' => request('bus_id'), 'date' => request('date')]) }}"
                    target="_blank"
                    class="bg-[#f5b85a] border border-[#d9a243] text-[#1c2238] font-bold rounded-none px-4 py-1.5 flex items-center hover:bg-[#e0a43b] transition-colors shadow-sm">
                    <i class="fa-solid fa-print mr-3 text-[18px]"></i>
                    <div class="text-left leading-tight text-[13px]">
                        Print<br>Register
                    </div>
                </a>
            </div>
        </form>
    </div>



    @if(session('success'))
        <div class="mb-6 p-4 bg-[#e8f5ed] text-[#34a853] rounded-none text-sm font-semibold border border-[#bbf7d0]">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-none shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-[17px] font-bold text-[#1c2238] flex items-center">
                Passenger Records
                <span
                    class="ml-3 px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[12px]">{{ $passengers->total() }}</span>
            </h2>
            <div class="flex items-center gap-4">
                <button type="button" id="bulk-delete-btn"
                    class="hidden px-4 py-2 bg-red-50 text-red-600 font-bold rounded-none hover:bg-red-100 transition-colors text-[13px]">
                    <i class="fa-solid fa-trash mr-2"></i> Delete Selected
                </button>
                <span class="text-[13px] font-medium text-gray-500">Showing {{ $passengers->firstItem() ?? 0 }} to
                    {{ $passengers->lastItem() ?? 0 }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 w-[50px] bg-gray-50 border-b border-gray-100">
                            <input type="checkbox" id="select-all"
                                class="w-4 h-4 rounded-none border-gray-300 text-[#f0b44b] focus:ring-[#f0b44b]">
                        </th>
                        <th
                            class="px-4 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 border-b border-gray-100">
                            #</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'journey_date', 'sort_dir' => ($sortBy == 'journey_date' && $sortDir == 'asc') ? 'desc' : 'asc']) }}"
                                class="flex items-center hover:text-[#1c2238] transition-colors">
                                Journey Date
                                @if($sortBy == 'journey_date')
                                    <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_dir' => ($sortBy == 'created_at' && $sortDir == 'asc') ? 'desc' : 'asc']) }}"
                                class="flex items-center hover:text-[#1c2238] transition-colors">
                                Booking Date
                                @if($sortBy == 'created_at')
                                    <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Bus
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">
                            Seat(s)</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'passenger_name', 'sort_dir' => ($sortBy == 'passenger_name' && $sortDir == 'asc') ? 'desc' : 'asc']) }}"
                                class="flex items-center hover:text-[#1c2238] transition-colors">
                                Passenger
                                @if($sortBy == 'passenger_name')
                                    <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                                @endif
                            </a>
                        </th>
                        <th
                            class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 text-center">
                            Total Seats</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Jama</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Payment Type</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Collected By</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Baki</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_amount', 'sort_dir' => ($sortBy == 'total_amount' && $sortDir == 'asc') ? 'desc' : 'asc']) }}"
                                class="flex items-center hover:text-[#1c2238] transition-colors">
                                Total
                                @if($sortBy == 'total_amount')
                                    <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Note
                        </th>
                        <th
                            class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($passengers as $index => $passenger)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 w-[50px]">
                                <input type="checkbox" name="passenger_ids[]" value="{{ $passenger->id }}"
                                    class="row-checkbox w-4 h-4 rounded-none border-gray-300 text-[#f0b44b] focus:ring-[#f0b44b]">
                            </td>
                            <td class="px-4 py-4 text-[13px] font-bold text-gray-400">
                                {{ $passengers->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 text-[13px] font-medium text-gray-600">
                                {{ $passenger->journey_date ? \Carbon\Carbon::parse($passenger->journey_date)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-[13px] font-medium text-gray-500">
                                {{ $passenger->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">
                                <a href="{{ route('buses.show', $passenger->bus->id) }}"
                                    class="hover:text-[#f0b44b] hover:underline transition-colors">{{ $passenger->bus->name }}</a>
                                @if($passenger->traveler_number_plate)
                                    <br>
                                    <span class="text-[12px] text-gray-500 font-medium">{{ $passenger->traveler_number_plate }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[13px] font-bold text-[#f0b44b]">
                                {{ $passenger->seat_number }}</td>
                            <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">
                                {{ $passenger->passenger_name }}<br>
                                <span
                                    class="text-[12px] text-gray-500 font-medium">{{ $passenger->passenger_mobile ?: 'No Phone' }}</span>
                            </td>
                               <td class="px-7 py-4 text-[13px] font-bold text-gray-600 text-center">{{ $passenger->total_seats }} x {{ number_format($passenger->per_seat_price ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-[13px] font-bold text-green-600">
                                ₹{{ number_format($passenger->payable_amount, 2) }}
                            </td>
                            {{-- Payment Type inline --}}
                            <td class="px-3 py-3">
                                <select data-passenger-id="{{ $passenger->id }}" data-field="payment_method"
                                    class="inline-payment-field w-full text-[12px] font-semibold border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#f0b44b] bg-white cursor-pointer min-w-[110px] transition-all"
                                    style="color: {{ $passenger->payment_method == 'Cash' ? '#16a34a' : ($passenger->payment_method == 'GooglePay' ? '#2563eb' : '#6b7280') }}">
                                    <option value="" {{ empty($passenger->payment_method) ? 'selected' : '' }}>— Select —</option>
                                    <option value="Cash" {{ $passenger->payment_method == 'Cash' ? 'selected' : '' }}>💵 Cash</option>
                                    <option value="GooglePay" {{ $passenger->payment_method == 'GooglePay' ? 'selected' : '' }}>📱 Google Pay</option>
                                    <option value="PhonePe" {{ $passenger->payment_method == 'PhonePe' ? 'selected' : '' }}>📱 PhonePe</option>
                                    <option value="Bank Transfer" {{ $passenger->payment_method == 'Bank Transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                                </select>
                            </td>
                            {{-- Collected By inline --}}
                            <td class="px-3 py-3">
                                <input type="text" data-passenger-id="{{ $passenger->id }}" data-field="payment_collected_by"
                                    value="{{ $passenger->payment_collected_by }}"
                                    placeholder="Name..."
                                    class="inline-payment-field w-full text-[12px] font-semibold border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#f0b44b] bg-white min-w-[110px] transition-all" />
                            </td>
                            <td class="px-6 py-4 text-[13px] font-bold text-red-500">
                                ₹{{ number_format($passenger->total_amount - $passenger->payable_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">
                                ₹{{ number_format($passenger->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-[13px] text-gray-500 max-w-[150px] truncate"
                                title="{{ $passenger->note }}">
                                {{ $passenger->note ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    @if(!empty($passenger->passenger_mobile))
                                        @php
                                            $busName = $passenger->bus ? $passenger->bus->name : 'N/A';
                                            $date = \Carbon\Carbon::parse($passenger->journey_date)->format('d M, Y');
                                            $pickupLocation = $passenger->pickup_stop ?: ($passenger->from_place ?: 'N/A');
                                            $busTime = $passenger->bus_time ?: 'N/A';
                                            $bakiPayment = $passenger->payable_amount ?: '0';

                                            $msg = "*TICKET CONFIRMATION*\n\n"
                                                 . "Passenger Name: {$passenger->passenger_name}\n"
                                                 . "Bus Name: {$busName}\n"
                                                 . "Seat No: {$passenger->seat_number}\n"
                                                 . "Date: {$date}\n"
                                                 . "Pick-up Location: {$pickupLocation}\n"
                                                 . "Pickup Time: {$busTime}\n"
                                                 . "Baki Payment: Rs {$bakiPayment}\n\n"
                                                 . "Office Name: Jay Gopal Travels\n"
                                                 . "Mobile Number: 9904172734";
                                            $mobile = preg_replace('/[^0-9]/', '', $passenger->passenger_mobile);
                                            if (strlen($mobile) == 10)
                                                $mobile = '91' . $mobile;
                                            $waLink = "https://api.whatsapp.com/send?phone={$mobile}&text=" . urlencode($msg);
                                        @endphp
                                        <a href="{{ $waLink }}" target="_blank"
                                            class="text-gray-500 hover:text-[#25D366] transition-colors"
                                            title="Send WhatsApp Ticket">
                                            <i class="fa-brands fa-whatsapp text-[16px]"></i>
                                        </a>
                                    @endif

                                    <!-- Print Button -->
                                    <a href="{{ route('tickets.show', $passenger->id) }}" target="_blank"
                                        class="text-gray-500 hover:text-green-600 transition-colors" title="Print Ticket">
                                        <i class="fa-solid fa-print text-[16px]"></i>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('passengers.edit', $passenger->id) }}"
                                        class="text-gray-500 hover:text-blue-600 transition-colors" title="Edit Booking">
                                        <i class="fa-solid fa-pen text-[16px]"></i>
                                    </a>

                                    <!-- Remove Button -->
                                    <form method="POST" action="{{ route('passengers.destroy', $passenger->id) }}"
                                        onsubmit="return confirm('Are you sure you want to permanently remove this booking?');"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors"
                                            title="Remove Booking">
                                            <i class="fa-solid fa-trash text-[16px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500 text-[14px]">
                                No tickets found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($passengers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $passengers->links() }}
            </div>
        @endif
    </div>

    <form id="bulk-action-form" method="POST" action="{{ route('passengers.bulk_destroy') }}" style="display: none;">
        @csrf
        <div id="bulk-inputs-container"></div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllBtn = document.getElementById('select-all');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const bulkActionForm = document.getElementById('bulk-action-form');

            function updateBulkDeleteBtn() {
                const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkDeleteBtn.classList.remove('hidden');
                    bulkDeleteBtn.innerHTML = `<i class="fa-solid fa-trash mr-2"></i> Delete Selected (${checkedCount})`;
                } else {
                    bulkDeleteBtn.classList.add('hidden');
                }
            }

            selectAllBtn.addEventListener('change', function () {
                rowCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBulkDeleteBtn();
            });

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
                    selectAllBtn.checked = allChecked;
                    updateBulkDeleteBtn();
                });
            });

            bulkDeleteBtn.addEventListener('click', function () {
                if (confirm('Are you sure you want to permanently delete the selected passengers?')) {
                    const container = document.getElementById('bulk-inputs-container');
                    container.innerHTML = '';
                    document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'passenger_ids[]';
                        input.value = cb.value;
                        container.appendChild(input);
                    });
                    bulkActionForm.submit();
                }
            });
        });
    </script>

    <script>
        // Debounced live search — submits 400ms after the user stops typing
        (function () {
            const searchInput = document.getElementById('searchInput');
            if (!searchInput) return;
            let debounceTimer;
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    this.closest('form').submit();
                }, 400);
            });
        })();
    </script>

    <script>
        // Inline Payment Field AJAX Auto-Save
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

        function saveInlinePayment(passengerId, field, value, el) {
            el.style.borderColor = '#f0b44b';
            fetch(`/passengers/${passengerId}/update-payment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ [field]: value }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    el.style.borderColor = '#16a34a';
                    // Update colour for payment_method select
                    if (el.tagName === 'SELECT') {
                        const colors = { Cash: '#16a34a', GooglePay: '#2563eb', PhonePe: '#7c3aed', 'Bank Transfer': '#0369a1' };
                        el.style.color = colors[value] || '#6b7280';
                    }
                    setTimeout(() => { el.style.borderColor = ''; }, 1500);
                }
            })
            .catch(() => { el.style.borderColor = '#ef4444'; });
        }

        document.querySelectorAll('.inline-payment-field').forEach(el => {
            if (el.tagName === 'SELECT') {
                el.addEventListener('change', function () {
                    saveInlinePayment(this.dataset.passengerId, this.dataset.field, this.value, this);
                });
            } else {
                let timer;
                el.addEventListener('input', function () {
                    clearTimeout(timer);
                    const self = this;
                    timer = setTimeout(() => {
                        saveInlinePayment(self.dataset.passengerId, self.dataset.field, self.value, self);
                    }, 600);
                });
                el.addEventListener('blur', function () {
                    clearTimeout(timer);
                    saveInlinePayment(this.dataset.passengerId, this.dataset.field, this.value, this);
                });
            }
        });
    </script>
@endsection