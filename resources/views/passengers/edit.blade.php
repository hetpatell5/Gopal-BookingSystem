@extends('layouts.app')

@section('title', 'Edit Passenger')
@section('header', 'Edit Passenger: ' . $passenger->passenger_name)

@section('content')
<style>
    .deck-container {
        display: flex;
        gap: 30px;
        justify-content: center;
        background: #f8f9fa;
        padding: 20px;
        border: 1px solid #e5e7eb;
        overflow-x: auto;
    }
    .deck {
        display: flex;
        flex-direction: column;
        min-width: 120px;
    }
    .deck-title {
        font-weight: bold;
        margin-bottom: 20px;
        font-size: 15px;
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
        gap: 12px;
    }
    .double-col {
        display: flex;
        gap: 10px;
    }
    /* Sleeper berth (1x2) — tall */
    .berth-sleeper {
        width: 45px;
        height: 90px;
    }
    /* Seater berth (2x2) — compact */
    .berth-seater {
        width: 40px;
        height: 50px;
    }
    .berth {
        border: 2px solid;
        border-radius: 0px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        background-color: white;
    }
    .berth::after {
        content: '';
        position: absolute;
        bottom: 4px;
        width: 22px;
        height: 6px;
        border-radius: 0px;
        opacity: 0.5;
    }
    .berth-available {
        border-color: #22c55e;
        color: #22c55e;
    }
    .berth-available::after { background-color: #22c55e; }
    .berth-available:hover  { background-color: #f0fdf4; }
    .berth-selected {
        border-color: #f0b44b;
        background-color: #f0b44b;
        color: white;
    }
    .berth-selected::after { background-color: white; }
    .berth-booked {
        border-color: #e5e7eb;
        background-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }
    .berth-booked::after { background-color: #9ca3af; }
    .berth-booked-label {
        font-size: 9px;
        color: #9ca3af;
        text-align: center;
        margin-top: 3px;
    }
    .berth-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>
<div style="display: flex; flex-wrap: wrap; gap: 2rem;">

    <!-- Seat Layout (Dynamic Lower + Upper Deck) -->
    <div style="flex: 1 1 45%; min-width: 300px;" class="bg-white rounded-none shadow-sm p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-[17px] font-bold text-[#1c2238]">
                Seat Layout
                @if($selectedBus)
                    <span class="ml-2 px-2 py-0.5 text-[11px] font-bold bg-gray-100 text-gray-500 rounded-sm">{{ $seatLayout }}</span>
                    <span class="ml-1 px-2 py-0.5 text-[11px] font-bold bg-blue-50 text-blue-600 rounded-sm">{{ $totalSeats }} seats</span>
                @endif
            </h2>
        </div>

        @if(!$selectedBus)
            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                <i class="fa-solid fa-bus text-4xl mb-3"></i>
                <p class="text-[14px] font-medium">Bus details not available.</p>
            </div>
        @else
            <div class="flex justify-between items-center mb-6 text-sm px-4">
                <div class="flex items-center"><span class="w-4 h-4 bg-white border-2 border-[#22c55e] rounded-none mr-2"></span> Available</div>
                <div class="flex items-center"><span class="w-4 h-4 bg-[#f0b44b] border-2 border-[#f0b44b] rounded-none mr-2"></span> Selected</div>
                <div class="flex items-center"><span class="w-4 h-4 bg-[#e5e7eb] border-2 border-[#e5e7eb] rounded-none mr-2"></span> Sold</div>
            </div>

            @php
                $lowerCount   = (int) ceil($totalSeats / 2);
                $upperCount   = (int) floor($totalSeats / 2);
                $berthSizeClass = ($seatLayout === '1x2') ? 'berth-sleeper' : 'berth-seater';

                $renderBerth = function(string $label, array $bookedSeats, array $currentSeats, string $sizeClass) {
                    $isBooked = in_array($label, $bookedSeats);
                    $isCurrent = in_array($label, $currentSeats);
                    
                    $cls      = $isBooked ? 'berth-booked' : ($isCurrent ? 'berth-selected' : 'berth-available');
                    $html  = '<div class="berth-wrapper">';
                    $html .= '<button type="button"'
                        . ' data-seat="' . e($label) . '"'
                        . ' data-booked="' . ($isBooked ? 'true' : 'false') . '"'
                        . ' class="berth ' . $sizeClass . ' ' . $cls . '">'
                        . e($label)
                        . '</button>';
                    if ($isBooked) $html .= '<span class="berth-booked-label">Sold</span>';
                    $html .= '</div>';
                    return $html;
                };

                $renderDeckRows = function(string $prefix, int $count, string $layout, array $bookedSeats, array $currentSeats, string $sizeClass) use ($renderBerth) {
                    $html = '';
                    if ($layout === '1x2') {
                        $leftCount = (int) floor($count / 3);
                        $rightRows = (int) ceil(($count - $leftCount) / 2);
                        $html .= '<div class="col-layout">';
                        for ($i = 1; $i <= $leftCount; $i++) {
                            $html .= $renderBerth($prefix . $i, $bookedSeats, $currentSeats, $sizeClass);
                        }
                        $html .= '</div>';
                        $html .= '<div style="width:40px;"></div>';
                        $html .= '<div class="double-col"><div class="col-layout">';
                        for ($row = 0; $row < $rightRows; $row++) {
                            $inner = $leftCount + ($row * 2) + 1;
                            $outer = $inner + 1;
                            $html .= '<div style="display:flex;gap:10px;">';
                            if ($inner <= $count) $html .= $renderBerth($prefix . $inner, $bookedSeats, $currentSeats, $sizeClass);
                            if ($outer <= $count) $html .= $renderBerth($prefix . $outer, $bookedSeats, $currentSeats, $sizeClass);
                            $html .= '</div>';
                        }
                        $html .= '</div></div>';
                    } else {
                        $rows = (int) ceil($count / 4);
                        $html .= '<div class="col-layout">';
                        for ($row = 0; $row < $rows; $row++) {
                            $s1 = $row * 4 + 1; $s2 = $row * 4 + 2;
                            $html .= '<div class="double-col">';
                            if ($s1 <= $count) $html .= $renderBerth($prefix . $s1, $bookedSeats, $currentSeats, $sizeClass);
                            if ($s2 <= $count) $html .= $renderBerth($prefix . $s2, $bookedSeats, $currentSeats, $sizeClass);
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                        $html .= '<div style="width:40px;"></div>';
                        $html .= '<div class="col-layout">';
                        for ($row = 0; $row < $rows; $row++) {
                            $s3 = $row * 4 + 3; $s4 = $row * 4 + 4;
                            $html .= '<div class="double-col">';
                            if ($s3 <= $count) $html .= $renderBerth($prefix . $s3, $bookedSeats, $currentSeats, $sizeClass);
                            if ($s4 <= $count) $html .= $renderBerth($prefix . $s4, $bookedSeats, $currentSeats, $sizeClass);
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                    }
                    return $html;
                };
            @endphp

            <div class="deck-container">
                <!-- Lower Deck -->
                <div class="deck">
                    <div class="deck-title">
                        Lower deck
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <circle cx="12" cy="12" r="4" stroke-width="2"/>
                            <path stroke-width="2" d="M12 2v6m0 8v6m-8-8h6m8 0h-6"/>
                        </svg>
                    </div>
                    <div class="row-layout">
                        {!! $renderDeckRows('L', $lowerCount, $seatLayout, $bookedSeats, $currentSeats, $berthSizeClass) !!}
                    </div>
                </div>

                <!-- Upper Deck -->
                <div class="deck">
                    <div class="deck-title">Upper deck</div>
                    <div class="row-layout">
                        {!! $renderDeckRows('U', $upperCount, $seatLayout, $bookedSeats, $currentSeats, $berthSizeClass) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Booking Form -->
    <div style="flex: 1 1 45%; min-width: 300px;" class="bg-white rounded-none shadow-sm p-6 relative">
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-[17px] font-bold text-[#1c2238]">Booking Details</h2>
            <a href="{{ route('passengers.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Back to List</a>
        </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-[#fee2e2] text-[#ef4444] rounded-none text-sm font-semibold border border-[#fca5a5]">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('passengers.update', $passenger->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Bus Selection -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Bus *</label>
                <select name="bus_id" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                    <option value="">Select Bus</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->id }}" {{ $passenger->bus_id == $bus->id ? 'selected' : '' }}>
                            {{ $bus->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Seat Number -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Seat Number(s) *</label>
                <input type="text" name="seat_number" id="seat_number" value="{{ old('seat_number', $passenger->seat_number) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px] bg-white text-[#1c2238]" required>
            </div>

            <!-- Passenger Name -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Passenger Name *</label>
                <input type="text" name="passenger_name" value="{{ old('passenger_name', $passenger->passenger_name) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Passenger Mobile -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Mobile Number</label>
                <input type="text" name="passenger_mobile" value="{{ old('passenger_mobile', $passenger->passenger_mobile) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>



            <!-- Traveler Name -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Traveler Name</label>
                <input type="text" name="traveler_name" value="{{ old('traveler_name', $passenger->traveler_name) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Traveler Number Plate -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Traveler Plate #</label>
                <input type="text" name="traveler_number_plate" value="{{ old('traveler_number_plate', $passenger->traveler_number_plate) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- AC / Non AC -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">AC/Non AC *</label>
                <select name="ac_type" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                    <option value="Non Ac" {{ $passenger->ac_type == 'Non Ac' ? 'selected' : '' }}>Non Ac</option>
                    <option value="Ac" {{ $passenger->ac_type == 'Ac' ? 'selected' : '' }}>Ac</option>
                </select>
            </div>

            <!-- Journey Date -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Journey Date *</label>
                <input type="date" name="journey_date" value="{{ old('journey_date', $passenger->journey_date) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Bus Time -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Bus Time</label>
                <input type="time" name="bus_time" value="{{ old('bus_time', $passenger->bus_time) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Total Seats -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Total Seats</label>
                <input type="number" name="total_seats" id="total_seats" value="{{ old('total_seats', $passenger->total_seats) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Per Seat Price -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Per Seat Price</label>
                <input type="number" step="0.01" name="per_seat_price" id="per_seat_price" value="{{ old('per_seat_price', $passenger->per_seat_price) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Auto calculates total">
            </div>

            <!-- Extra Passenger Amount -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Extra Passenger Amount</label>
                <input type="number" step="0.01" name="extra_passenger_amount" id="extra_passenger_amount" value="{{ old('extra_passenger_amount', $passenger->extra_passenger_amount) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Total Amount -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Total Amount *</label>
                <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount', $passenger->total_amount) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Payable Amount -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Payable Amount *</label>
                <input type="number" step="0.01" name="payable_amount" value="{{ old('payable_amount', $passenger->payable_amount) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Payment Type -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Payment Type</label>
                <select name="payment_method" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px] bg-white">
                    <option value="" {{ old('payment_method', $passenger->payment_method) == '' ? 'selected' : '' }}>— Select —</option>
                    <option value="Cash" {{ old('payment_method', $passenger->payment_method) == 'Cash' ? 'selected' : '' }}>💵 Cash</option>
                    <option value="GooglePay" {{ old('payment_method', $passenger->payment_method) == 'GooglePay' ? 'selected' : '' }}>📱 Google Pay</option>
                    <option value="PhonePe" {{ old('payment_method', $passenger->payment_method) == 'PhonePe' ? 'selected' : '' }}>📱 PhonePe</option>
                    <option value="Bank Transfer" {{ old('payment_method', $passenger->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                </select>
            </div>

            <!-- Collected By -->
            <div>
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Collected By</label>
                <input type="text" name="payment_collected_by" value="{{ old('payment_collected_by', $passenger->payment_collected_by) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Person who collected payment">
            </div>

            @if($passenger->bus && $passenger->bus->bus_type === 'Commission')
                <!-- Commission Percentage -->
                <div>
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Commission Percentage (%)</label>
                    <input type="number" step="0.01" name="commission_percentage" id="commission_percentage" value="{{ old('commission_percentage', $passenger->commission_percentage) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                </div>

                <!-- Commission Amount -->
                <div>
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Commission Amount</label>
                    <input type="number" step="0.01" name="commission_amount" id="commission_amount" value="{{ old('commission_amount', $passenger->commission_amount) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] bg-gray-50 text-[14px]" readonly>
                </div>
            @endif

            <!-- Pickup Stop -->
            <div class="md:col-span-2">
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Pickup Stop</label>
                <input type="text" name="pickup_stop" value="{{ old('pickup_stop', $passenger->pickup_stop) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- From Place -->
            <div class="md:col-span-1">
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">From Place</label>
                <input type="text" name="from_place" value="{{ old('from_place', $passenger->from_place) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- To Place -->
            <div class="md:col-span-1">
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">To Place</label>
                <input type="text" name="to_place" value="{{ old('to_place', $passenger->to_place) }}" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Note -->
            <div class="md:col-span-2">
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Note</label>
                <textarea name="note" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">{{ old('note', $passenger->note) }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" id="submitBtn" class="flex-1 bg-[#f0b44b] text-[#1c2238] font-bold py-3.5 rounded-none hover:bg-[#e0a43b] transition-colors shadow-sm">
                Save Changes
            </button>
            <a href="{{ route('passengers.index') }}" class="flex-1 bg-gray-100 text-gray-600 font-bold py-3.5 rounded-none hover:bg-gray-200 transition-colors shadow-sm text-center">
                Cancel
            </a>
        </div>
    </form>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const totalAmountInput = document.querySelector('input[name="total_amount"]');
        const commPercentInput = document.getElementById('commission_percentage');
        const commAmountInput = document.getElementById('commission_amount');
        const perSeatPriceInput = document.getElementById('per_seat_price');
        const extraAmountInput = document.getElementById('extra_passenger_amount');
        const totalSeatsInput = document.getElementById('total_seats');

        const seats = document.querySelectorAll('.berth');
        const seatInput = document.getElementById('seat_number');
        const submitBtn = document.getElementById('submitBtn');

        const calculateAmounts = (source = null) => {
            const extra = extraAmountInput ? (parseFloat(extraAmountInput.value) || 0) : 0;
            let baseTotal = 0;

            if (perSeatPriceInput && totalSeatsInput && (source === 'per_seat' || source === 'extra' || source === 'seats')) {
                const seatsCount = parseFloat(totalSeatsInput.value) || 0;
                const price = parseFloat(perSeatPriceInput.value) || 0;
                baseTotal = seatsCount * price;
                totalAmountInput.value = (baseTotal + extra).toFixed(2);
            } else {
                const total = parseFloat(totalAmountInput.value) || 0;
                baseTotal = Math.max(0, total - extra);
            }

            const total = parseFloat(totalAmountInput.value) || 0;
            const perSeatComm = parseFloat(commPercentInput?.value) || 0;
            
            if (commAmountInput) {
                const commAmount = (baseTotal * (perSeatComm / 100));
                commAmountInput.value = commAmount.toFixed(2);
            }
        };

        totalAmountInput.addEventListener('input', () => calculateAmounts('total'));
        if (commPercentInput) commPercentInput.addEventListener('input', () => calculateAmounts('comm'));
        if (perSeatPriceInput) perSeatPriceInput.addEventListener('input', () => calculateAmounts('per_seat'));
        if (extraAmountInput) extraAmountInput.addEventListener('input', () => calculateAmounts('extra'));
        if (totalSeatsInput) totalSeatsInput.addEventListener('input', () => {
            if (perSeatPriceInput && perSeatPriceInput.value) calculateAmounts('per_seat');
            else calculateAmounts();
        });

        // Interactive Seat Layout Logic
        let selectedSeats = @json($currentSeats ?? []);

        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                const isBooked  = seat.getAttribute('data-booked') === 'true';
                const seatLabel = seat.getAttribute('data-seat');

                if (isBooked) {
                    alert('This seat is already booked by someone else.');
                } else {
                    if (selectedSeats.includes(seatLabel)) {
                        selectedSeats = selectedSeats.filter(s => s !== seatLabel);
                        seat.classList.remove('berth-selected');
                        seat.classList.add('berth-available');
                    } else {
                        selectedSeats.push(seatLabel);
                        seat.classList.remove('berth-available');
                        seat.classList.add('berth-selected');
                    }

                    if (seatInput) seatInput.value = selectedSeats.join(', ');
                    if (totalSeatsInput) {
                        totalSeatsInput.value = selectedSeats.length || 1;
                        if (perSeatPriceInput && perSeatPriceInput.value) {
                            const price = parseFloat(perSeatPriceInput.value) || 0;
                            const extra = extraAmountInput ? (parseFloat(extraAmountInput.value) || 0) : 0;
                            if (totalAmountInput) {
                                totalAmountInput.value = (((selectedSeats.length || 1) * price) + extra).toFixed(2);
                                totalAmountInput.dispatchEvent(new Event('input'));
                            }
                        } else {
                            totalSeatsInput.dispatchEvent(new Event('input'));
                        }
                    }

                    if (submitBtn) {
                        if (selectedSeats.length > 0) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = `Save Changes (${selectedSeats.join(', ')})`;
                        } else {
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Select a Seat';
                        }
                    }
                }
            });
        });
    });
</script>
@endsection
