@extends('layouts.app')

@section('title', $bus->name . ' Dashboard')
@section('header', $bus->name . ' Dashboard (' . $bus->plate_number . ')')

@section('content')
<style>
    .deck-container {
        display: flex;
        gap: 30px;
        justify-content: center;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 0px;
        border: 1px solid #e5e7eb;
        overflow-x: auto;
    }
    .deck {
        display: flex;
        flex-direction: column;
        min-width: 200px;
    }
    .deck-title {
        font-weight: bold;
        margin-bottom: 20px;
        font-size: 16px;
        color: #1c2238;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .row-layout {
        display: flex;
        justify-content: space-between;
    }
    .col-layout {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .double-col {
        display: flex;
        gap: 10px;
    }
    .berth {
        width: 45px;
        height: 90px;
        border: 2px solid;
        border-radius: 0px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        background-color: white;
    }
    /* Pillow styling */
    .berth::after {
        content: '';
        position: absolute;
        bottom: 5px;
        width: 25px;
        height: 8px;
        border-radius: 0px;
        opacity: 0.5;
    }
    .berth-available {
        border-color: #22c55e;
        color: #22c55e;
    }
    .berth-available::after {
        background-color: #22c55e;
    }
    .berth-available:hover {
        background-color: #f0fdf4;
    }
    
    .berth-selected {
        border-color: #f0b44b;
        background-color: #f0b44b;
        color: white;
    }
    .berth-selected::after {
        background-color: white;
    }
    
    .berth-booked {
        border-color: #e5e7eb;
        background-color: #e5e7eb;
        color: #9ca3af;
        cursor: pointer;
    }
    .berth-booked::after {
        background-color: #9ca3af;
    }
    
    .berth-booked-label {
        font-size: 10px;
        color: #9ca3af;
        text-align: center;
        margin-top: 4px;
    }
    .berth-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>

<!-- Bus Dashboard Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Seats -->
    <div class="bg-white rounded-none p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fa-solid fa-chair text-blue-500" style="font-size: 60px;"></i>
        </div>
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 relative z-10">Total Seats Booked</h3>
        <p class="text-3xl font-black text-[#1c2238] relative z-10">{{ $totalSeatsSold }}</p>
    </div>

    <!-- Revenue -->
    <div class="bg-white rounded-none p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fa-solid fa-indian-rupee-sign text-green-500" style="font-size: 60px;"></i>
        </div>
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 relative z-10">Total Revenue</h3>
        <p class="text-3xl font-black text-green-600 relative z-10">₹{{ number_format($totalRevenue, 2) }}</p>
    </div>

    <!-- Commission -->
    <div class="bg-white rounded-none p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fa-solid fa-hand-holding-dollar text-[#f0b44b]" style="font-size: 60px;"></i>
        </div>
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 relative z-10">Total Commission</h3>
        <p class="text-3xl font-black text-[#f0b44b] relative z-10">₹{{ number_format($totalCommission, 2) }}</p>
    </div>

    <!-- Payable -->
    <div class="bg-white rounded-none p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fa-solid fa-wallet text-[#1c2238]" style="font-size: 60px;"></i>
        </div>
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 relative z-10">Payable Amount</h3>
        <p class="text-3xl font-black text-[#1c2238] relative z-10">₹{{ number_format($totalPayable, 2) }}</p>
    </div>
</div>



<!-- Passenger List Filter & Table -->
<div class="mt-8 bg-white rounded-none shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-[17px] font-bold text-[#1c2238] flex items-center">
            Passenger List
            <span class="ml-3 px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[12px]">{{ $passengers->total() }}</span>
        </h2>
        
        <form method="GET" action="{{ route('buses.show', $bus->id) }}" class="flex flex-wrap items-center gap-3">
            <input type="hidden" name="date" value="{{ $selectedDate }}">
            
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search passenger..." class="pl-3 pr-3 py-1.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] min-w-[180px]">
            </div>
            
            <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="px-3 py-1.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] text-gray-600">

            <select name="ac_type" onchange="this.form.submit()" class="px-3 py-1.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px]">
                <option value="">All Types</option>
                <option value="Ac" {{ request('ac_type') == 'Ac' ? 'selected' : '' }}>AC</option>
                <option value="Non Ac" {{ request('ac_type') == 'Non Ac' ? 'selected' : '' }}>Non AC</option>
            </select>
            
            <button type="submit" class="px-4 py-1.5 bg-[#f0b44b] text-[#1c2238] font-bold text-[13px] rounded-none hover:bg-[#e0a43b] transition-colors">
                Filter
            </button>
            <a href="{{ route('buses.show', ['bus' => $bus->id]) }}" class="px-4 py-1.5 bg-gray-100 text-gray-600 font-bold text-[13px] rounded-none hover:bg-gray-200 transition-colors">
                Clear
            </a>
            <button type="submit" formaction="{{ route('buses.register', $bus->id) }}" formtarget="_blank" class="px-4 py-1.5 bg-[#1c2238] text-[#f0b44b] font-bold text-[13px] rounded-none hover:bg-[#2a3454] transition-colors flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'seat_number', 'sort_dir' => ($sortBy == 'seat_number' && $sortDir == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center hover:text-[#1c2238] transition-colors">
                            Seat(s)
                            @if($sortBy == 'seat_number')
                                <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'passenger_name', 'sort_dir' => ($sortBy == 'passenger_name' && $sortDir == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center hover:text-[#1c2238] transition-colors">
                            Passenger
                            @if($sortBy == 'passenger_name')
                                <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Contact</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'journey_date', 'sort_dir' => ($sortBy == 'journey_date' && $sortDir == 'desc') ? 'asc' : 'desc']) }}" class="flex items-center hover:text-[#1c2238] transition-colors">
                            Route & Journey
                            @if($sortBy == 'journey_date')
                                <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Village / Pickup</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">AC/Non AC</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_amount', 'sort_dir' => ($sortBy == 'total_amount' && $sortDir == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center hover:text-[#1c2238] transition-colors">
                            Amt/Adv/Baki
                            @if($sortBy == 'total_amount')
                                <i class="fa-solid fa-sort-{{ $sortDir == 'asc' ? 'up mt-1' : 'down mb-1' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 opacity-40"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Booking Date</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Traveler</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Note</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($passengers as $passenger)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-[13px] font-bold text-[#f0b44b]">{{ $passenger->seat_number }}</td>
                    <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">{{ $passenger->passenger_name }}</td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">{{ $passenger->passenger_mobile ?: 'N/A' }}</td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">
                        {{ \Carbon\Carbon::parse($passenger->journey_date)->format('d M, Y') }} <br>
                        @if($passenger->from_place || $passenger->to_place)
                            <span class="text-[11px] text-gray-500 font-bold">{{ $passenger->from_place }} @if($passenger->to_place) - {{ $passenger->to_place }} @endif</span>
                        @else
                            <span class="text-[11px] text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">
                        <span class="text-[11px] text-gray-400">Pickup: {{ $passenger->pickup_stop ?: '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">{{ $passenger->ac_type }}</td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium whitespace-nowrap">
                        <span class="text-gray-800 font-bold">₹{{ $passenger->total_amount }}</span> <br>
                        <span class="text-[11px] text-green-600 font-bold">Adv: ₹{{ $passenger->payable_amount }}</span> <br>
                        <span class="text-[11px] text-red-600 font-bold">Baki: ₹{{ number_format($passenger->total_amount - $passenger->payable_amount, 2) }}</span>
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium whitespace-nowrap">
                        {{ $passenger->created_at->format('d M, Y') }}<br>
                        <span class="text-[11px] text-gray-400">{{ $passenger->created_at->format('h:i A') }}</span>
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">
                        {{ $passenger->traveler_name ?: '-' }} <br>
                        <span class="text-[11px] text-gray-400">{{ $passenger->traveler_number_plate ?: '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-500 max-w-[120px] truncate" title="{{ $passenger->note }}">
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
                            <form method="POST" action="{{ route('passengers.destroy', $passenger->id) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?');" class="inline-block">
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
                    <td colspan="11" class="px-6 py-8 text-center text-[13px] text-gray-500 font-medium">No passengers booked on this bus yet.</td>
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

<!-- Cancel Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 transition-opacity">
    <div class="bg-[#1c2238] rounded-none w-full max-w-md p-6 transform scale-95 transition-transform shadow-2xl relative border border-gray-700/50">
        <!-- Close button (top right) -->
        <button id="closeCancelModalBtn" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Red Header Bar -->
        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#ef4444] rounded-t-[10px]"></div>

        <div class="text-center mt-2">
            <!-- Alert Icon -->
            <div class="w-16 h-16 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-4 border border-red-500/20">
                <svg class="w-8 h-8 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-white mb-2 tracking-wide">Cancel Seat <span id="cancelSeatNumber" class="text-[#f0b44b]"></span></h3>
            <p class="text-[13px] text-gray-400 mb-2">Booked by: <span id="cancelPassengerName" class="text-white font-medium"></span></p>
            <p class="text-sm text-gray-300 font-medium leading-relaxed mb-6">
                Are you sure you want to cancel this booking? This action cannot be undone.
            </p>
            
            <div class="flex space-x-3 w-full">
                <button type="button" id="cancelActionNo" class="flex-1 px-4 py-2.5 rounded-none text-sm font-semibold text-white bg-gray-700 hover:bg-gray-600 transition-colors border border-gray-600">
                    NO, KEEP IT
                </button>
                <form id="cancelForm" method="POST" action="" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 rounded-none text-sm font-bold text-white bg-[#ef4444] hover:bg-[#dc2626] transition-colors border border-[#dc2626]">
                        YES, CANCEL
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const seats = document.querySelectorAll('.berth');
        const seatInput = document.getElementById('seat_number');
        const submitBtn = document.getElementById('submitBtn');
        const totalSeatsInput = document.querySelector('input[name="total_seats"]');
        
        // Commission and Baki calculations
        const totalAmountInput = document.getElementById('total_amount');
        const commPercentInput = document.getElementById('commission_percentage');
        const commAmountInput = document.getElementById('commission_amount');
        const payableAmountInput = document.getElementById('payable_amount');
        const bakiPaymentInput = document.getElementById('baki_payment');
        const perSeatPriceInput = document.getElementById('per_seat_price');
        
        if (totalAmountInput) {
            const calculateAmounts = (source = null) => {
                if (perSeatPriceInput && totalSeatsInput && source === 'per_seat') {
                    const seats = parseFloat(totalSeatsInput.value) || 0;
                    const price = parseFloat(perSeatPriceInput.value) || 0;
                    totalAmountInput.value = (seats * price).toFixed(2);
                }

                const total = parseFloat(totalAmountInput.value) || 0;
                if(commPercentInput && commAmountInput) {
                    const perSeatComm = parseFloat(commPercentInput.value) || 0;
                    const seats = parseFloat(totalSeatsInput.value) || 1;
                    commAmountInput.value = (seats * perSeatComm).toFixed(2);
                }
                if(payableAmountInput && bakiPaymentInput) {
                    const advance = parseFloat(payableAmountInput.value) || 0;
                    bakiPaymentInput.value = (total - advance).toFixed(2);
                }
            };

            totalAmountInput.addEventListener('input', () => calculateAmounts('total'));
            if(commPercentInput) commPercentInput.addEventListener('input', () => calculateAmounts('comm'));
            if(payableAmountInput) payableAmountInput.addEventListener('input', () => calculateAmounts('pay'));
            if(perSeatPriceInput) perSeatPriceInput.addEventListener('input', () => calculateAmounts('per_seat'));
            if(totalSeatsInput) totalSeatsInput.addEventListener('input', () => {
                if (perSeatPriceInput && perSeatPriceInput.value) calculateAmounts('per_seat');
                else calculateAmounts();
            });
        }

        let selectedSeats = [];

        const cancelModal = document.getElementById('cancelModal');
        const closeCancelModalBtn = document.getElementById('closeCancelModalBtn');
        const cancelActionNo = document.getElementById('cancelActionNo');
        const cancelForm = document.getElementById('cancelForm');
        const cancelSeatNumber = document.getElementById('cancelSeatNumber');
        const cancelPassengerName = document.getElementById('cancelPassengerName');

        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                const isBooked = seat.getAttribute('data-booked') === 'true';
                const seatLabel = seat.getAttribute('data-seat');

                if (isBooked) {
                    // Show Cancel Modal
                    const pid = seat.getAttribute('data-pid');
                    const pname = seat.getAttribute('data-pname');
                    const pseats = seat.getAttribute('data-pseats');
                    
                    cancelSeatNumber.textContent = pseats;
                    cancelPassengerName.textContent = pname;
                    cancelForm.action = `/passengers/${pid}`;
                    
                    cancelModal.classList.remove('hidden');
                    cancelModal.classList.add('flex');
                    setTimeout(() => {
                        cancelModal.querySelector('div').classList.remove('scale-95');
                        cancelModal.querySelector('div').classList.add('scale-100');
                    }, 10);
                } else {
                    if (selectedSeats.includes(seatLabel)) {
                        // Deselect
                        selectedSeats = selectedSeats.filter(s => s !== seatLabel);
                        seat.classList.remove('berth-selected');
                        seat.classList.add('berth-available');
                    } else {
                        // Select
                        selectedSeats.push(seatLabel);
                        seat.classList.remove('berth-available');
                        seat.classList.add('berth-selected');
                    }

                    seatInput.value = selectedSeats.join(', ');
                    totalSeatsInput.value = selectedSeats.length || 1;
                    
                    if (document.getElementById('per_seat_price') && document.getElementById('per_seat_price').value) {
                        const price = parseFloat(document.getElementById('per_seat_price').value) || 0;
                        if (totalAmountInput) {
                            totalAmountInput.value = ((selectedSeats.length || 1) * price).toFixed(2);
                            totalAmountInput.dispatchEvent(new Event('input'));
                        }
                    }

                    if (selectedSeats.length > 0) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = `Book Seat${selectedSeats.length > 1 ? 's' : ''} ${selectedSeats.join(', ')}`;
                    } else {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Select a Seat to Book';
                    }
                }
            });
        });

        const closeCancelModal = () => {
            cancelModal.querySelector('div').classList.remove('scale-100');
            cancelModal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                cancelModal.classList.add('hidden');
                cancelModal.classList.remove('flex');
            }, 150);
        };

        closeCancelModalBtn.addEventListener('click', closeCancelModal);
        cancelActionNo.addEventListener('click', closeCancelModal);
    });
</script>
@endsection
