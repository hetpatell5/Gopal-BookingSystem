@extends('layouts.app')

@section('title', 'All Passengers')
@section('header', 'Passenger Directory')

@section('content')

<div class="bg-white rounded-none shadow-sm p-6 mb-8">
    <form method="GET" action="{{ route('passengers.index') }}" class="flex flex-wrap items-end gap-4">
        
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Filter by Bus</label>
            <select name="bus_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                <option value="">All Buses</option>
                @foreach($buses as $bus)
                    <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                        {{ $bus->name }} ({{ $bus->plate_number }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[200px]">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Search Passenger</label>
            <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.submit()" placeholder="Name, Mobile, Seat, or Pickup..." class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
        </div>

        <div class="flex-1 min-w-[200px]">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Filter by Date</label>
            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
        </div>

        <div class="flex-1 min-w-[200px]">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">AC / Non AC</label>
            <select name="ac_type" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                <option value="">All Types</option>
                <option value="Ac" {{ request('ac_type') == 'Ac' ? 'selected' : '' }}>AC</option>
                <option value="Non Ac" {{ request('ac_type') == 'Non Ac' ? 'selected' : '' }}>Non AC</option>
            </select>
        </div>
    
        <div class="flex items-center gap-2">
            <a href="{{ route('passengers.index') }}" class="bg-gray-100 text-gray-600 font-semibold py-2.5 px-4 rounded-none hover:bg-gray-200 transition-colors">
                Clear
            </a>
            
            <div class="w-px h-10 bg-gray-200 mx-2"></div>
            
            <a href="{{ route('passengers.register', ['bus_id' => request('bus_id'), 'date' => request('date')]) }}" target="_blank" class="bg-[#f5b85a] border border-[#d9a243] text-[#1c2238] font-bold rounded-none px-4 py-1.5 flex items-center hover:bg-[#e0a43b] transition-colors shadow-sm">
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
    <form id="bulk-action-form" method="POST" action="{{ route('passengers.bulk_destroy') }}">
        @csrf
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-[17px] font-bold text-[#1c2238] flex items-center">
                Passenger Records
                <span class="ml-3 px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[12px]">{{ $passengers->total() }}</span>
            </h2>
            <div class="flex items-center gap-4">
                <button type="button" id="bulk-delete-btn" class="hidden px-4 py-2 bg-red-50 text-red-600 font-bold rounded-none hover:bg-red-100 transition-colors text-[13px]">
                    <i class="fa-solid fa-trash mr-2"></i> Delete Selected
                </button>
                <span class="text-[13px] font-medium text-gray-500">Showing {{ $passengers->firstItem() ?? 0 }} to {{ $passengers->lastItem() ?? 0 }}</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 w-[50px] bg-gray-50 border-b border-gray-100">
                            <input type="checkbox" id="select-all" class="w-4 h-4 rounded-none border-gray-300 text-[#f0b44b] focus:ring-[#f0b44b]">
                        </th>
                        <th class="px-4 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 border-b border-gray-100">#</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'journey_date', 'sort_dir' => ($sortBy == 'journey_date' && $sortDir == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center hover:text-[#1c2238] transition-colors">
                            Journey Date
                            @if($sortBy == 'journey_date')
                                <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Bus</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Seat(s)</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'passenger_name', 'sort_dir' => ($sortBy == 'passenger_name' && $sortDir == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center hover:text-[#1c2238] transition-colors">
                            Passenger
                            @if($sortBy == 'passenger_name')
                                <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_amount', 'sort_dir' => ($sortBy == 'total_amount' && $sortDir == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center hover:text-[#1c2238] transition-colors">
                            Amount
                            @if($sortBy == 'total_amount')
                                <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Note</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 text-right">Actions</th>
                </tr>
            </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($passengers as $index => $passenger)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 w-[50px]">
                            <input type="checkbox" name="passenger_ids[]" value="{{ $passenger->id }}" class="row-checkbox w-4 h-4 rounded-none border-gray-300 text-[#f0b44b] focus:ring-[#f0b44b]">
                        </td>
                        <td class="px-4 py-4 text-[13px] font-bold text-gray-400">
                            {{ $passengers->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-[13px] font-medium text-gray-600">
                        {{ $passenger->journey_date ? \Carbon\Carbon::parse($passenger->journey_date)->format('d M Y') : 'N/A' }}<br>
                        <span class="text-[11px] text-gray-400">Booked: {{ $passenger->created_at->format('d M') }}</span>
                    </td>
                    <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">
                        <a href="{{ route('buses.show', $passenger->bus->id) }}" class="hover:text-[#f0b44b] hover:underline transition-colors">{{ $passenger->bus->name }}</a><br>
                        <span class="text-[11px] text-gray-400 font-medium tracking-wide">{{ $passenger->bus->plate_number }}</span>
                    </td>
                    <td class="px-6 py-4 text-[13px] font-bold text-[#f0b44b]">{{ $passenger->seat_number }}</td>
                    <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">
                        {{ $passenger->passenger_name }}<br>
                        <span class="text-[12px] text-gray-500 font-medium">{{ $passenger->passenger_mobile ?: 'No Phone' }}</span>
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">
                        Total: ₹{{ $passenger->total_amount }}<br>
                        <span class="text-green-600 font-bold">Pay: ₹{{ $passenger->payable_amount }}</span>
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-500 max-w-[150px] truncate" title="{{ $passenger->note }}">
                        {{ $passenger->note ?: '-' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            @if(!empty($passenger->passenger_mobile))
                                @php
                                    $busName = $passenger->bus ? $passenger->bus->name : 'N/A';
                                    $date = \Carbon\Carbon::parse($passenger->journey_date)->format('d M, Y');
                                    $adminWhatsApp = auth()->check() && !empty(auth()->user()->whatsapp_number) ? "\n*Support:* " . auth()->user()->whatsapp_number : "";
                                    $msg = "🎫 *TICKET CONFIRMATION* 🎫\n\n*Bus:* {$busName}\n*Passenger:* {$passenger->passenger_name}\n*Seat No:* {$passenger->seat_number}\n*Date:* {$date}\n*Amount:* Rs {$passenger->total_amount}\n{$adminWhatsApp}\n\nThank you for booking with Setu! Have a safe journey.";
                                    $mobile = preg_replace('/[^0-9]/', '', $passenger->passenger_mobile);
                                    if (strlen($mobile) == 10) $mobile = '91' . $mobile;
                                    $waLink = "https://api.whatsapp.com/send?phone={$mobile}&text=" . urlencode($msg);
                                @endphp
                                <a href="{{ $waLink }}" target="_blank" class="text-gray-500 hover:text-[#25D366] transition-colors" title="Send WhatsApp Ticket">
                                    <i class="fa-brands fa-whatsapp text-[16px]"></i>
                                </a>
                            @endif

                            <!-- Print Button -->
                            <a href="{{ route('tickets.show', $passenger->id) }}" target="_blank" class="text-gray-500 hover:text-green-600 transition-colors" title="Print Ticket">
                                <i class="fa-solid fa-print text-[16px]"></i>
                            </a>

                            <!-- Edit Button -->
                            <a href="{{ route('passengers.edit', $passenger->id) }}" class="text-gray-500 hover:text-blue-600 transition-colors" title="Edit Booking">
                                <i class="fa-solid fa-pen text-[16px]"></i>
                            </a>
                            
                            <!-- Remove Button -->
                            <form method="POST" action="{{ route('passengers.destroy', $passenger->id) }}" onsubmit="return confirm('Are you sure you want to permanently remove this booking?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors" title="Remove Booking">
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
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        selectAllBtn.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteBtn();
        });

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
                selectAllBtn.checked = allChecked;
                updateBulkDeleteBtn();
            });
        });

        bulkDeleteBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to permanently delete the selected passengers?')) {
                bulkActionForm.submit();
            }
        });
    });
</script>
@endsection
