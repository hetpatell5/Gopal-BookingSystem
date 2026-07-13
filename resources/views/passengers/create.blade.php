@extends('layouts.app')

@section('title', 'Quick Booking')
@section('header', 'Quick Booking')

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

<!-- Step 1: Bus Type + Bus + Date Selection -->
<div class="mb-6 bg-white rounded-none shadow-sm p-6">
    <form id="busSelectForm" method="GET" action="{{ route('passengers.create') }}" class="flex flex-col md:flex-row gap-4 items-end">

        <!-- Bus Type Filter -->
        <div class="flex-1">
            <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Bus Type</label>
            <select name="bus_type_filter" onchange="document.getElementById('busSelectForm').submit()"
                    class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                <option value="">-- All Types --</option>
                <option value="Personal"    {{ $busTypeFilter === 'Personal'    ? 'selected' : '' }}>Personal</option>
                <option value="Commission"  {{ $busTypeFilter === 'Commission'  ? 'selected' : '' }}>Commission</option>
            </select>
        </div>

        <!-- Bus Dropdown (filtered by type if selected) -->
        <div class="flex-1">
            <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Select Bus</label>
            <select name="bus_id" onchange="document.getElementById('busSelectForm').submit()"
                    class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                <option value="">-- Choose a Bus --</option>
                @foreach($buses->when($busTypeFilter, fn($q) => $q->where('bus_type', $busTypeFilter)) as $bus)
                    <option value="{{ $bus->id }}" {{ $selectedBusId == $bus->id ? 'selected' : '' }}>
                        {{ $bus->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Journey Date -->
        <div class="flex-1">
            <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-2">Journey Date</label>
            <div class="flex gap-2">
                <input type="date" name="date" value="{{ $selectedDate }}"
                       class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                <button type="submit" class="bg-[#1c2238] text-white px-4 py-2 text-[13px] font-bold uppercase tracking-wider hover:bg-[#29324b] transition-colors whitespace-nowrap">Load</button>
            </div>
        </div>

    </form>
</div>

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

        @if(!$selectedBusId)
            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                <i class="fa-solid fa-bus text-4xl mb-3"></i>
                <p class="text-[14px] font-medium">Please select a bus type and bus to view the layout.</p>
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

                $renderBerth = function(string $label, array $bookedSeats, string $sizeClass) {
                    $isBooked = in_array($label, $bookedSeats);
                    $cls      = $isBooked ? 'berth-booked' : 'berth-available';
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

                $renderDeckRows = function(string $prefix, int $count, string $layout, array $bookedSeats, string $sizeClass) use ($renderBerth) {
                    $html = '';
                    if ($layout === '1x2') {
                        $leftCount = (int) floor($count / 3);
                        $rightRows = (int) ceil(($count - $leftCount) / 2);
                        $html .= '<div class="col-layout">';
                        for ($i = 1; $i <= $leftCount; $i++) {
                            $html .= $renderBerth($prefix . $i, $bookedSeats, $sizeClass);
                        }
                        $html .= '</div>';
                        $html .= '<div style="width:40px;"></div>';
                        $html .= '<div class="double-col"><div class="col-layout">';
                        for ($row = 0; $row < $rightRows; $row++) {
                            $inner = $leftCount + ($row * 2) + 1;
                            $outer = $inner + 1;
                            $html .= '<div style="display:flex;gap:10px;">';
                            if ($inner <= $count) $html .= $renderBerth($prefix . $inner, $bookedSeats, $sizeClass);
                            if ($outer <= $count) $html .= $renderBerth($prefix . $outer, $bookedSeats, $sizeClass);
                            $html .= '</div>';
                        }
                        $html .= '</div></div>';
                    } else {
                        $rows = (int) ceil($count / 4);
                        $html .= '<div class="col-layout">';
                        for ($row = 0; $row < $rows; $row++) {
                            $s1 = $row * 4 + 1; $s2 = $row * 4 + 2;
                            $html .= '<div class="double-col">';
                            if ($s1 <= $count) $html .= $renderBerth($prefix . $s1, $bookedSeats, $sizeClass);
                            if ($s2 <= $count) $html .= $renderBerth($prefix . $s2, $bookedSeats, $sizeClass);
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                        $html .= '<div style="width:40px;"></div>';
                        $html .= '<div class="col-layout">';
                        for ($row = 0; $row < $rows; $row++) {
                            $s3 = $row * 4 + 3; $s4 = $row * 4 + 4;
                            $html .= '<div class="double-col">';
                            if ($s3 <= $count) $html .= $renderBerth($prefix . $s3, $bookedSeats, $sizeClass);
                            if ($s4 <= $count) $html .= $renderBerth($prefix . $s4, $bookedSeats, $sizeClass);
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
                        {!! $renderDeckRows('L', $lowerCount, $seatLayout, $bookedSeats, $berthSizeClass) !!}
                    </div>
                </div>

                <!-- Upper Deck -->
                <div class="deck">
                    <div class="deck-title">Upper deck</div>
                    <div class="row-layout">
                        {!! $renderDeckRows('U', $upperCount, $seatLayout, $bookedSeats, $berthSizeClass) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Booking Form -->
    <div style="flex: 1 1 45%; min-width: 300px;" class="bg-white rounded-none shadow-sm p-6 relative">
        <h2 class="text-[17px] font-bold text-[#1c2238] mb-6">Booking Details</h2>

        @if(session('success'))
            <div class="mb-6 rounded-none overflow-hidden border border-[#34a853]/30 shadow-sm">
                <div class="bg-[#e8f5ed] px-4 py-3 flex items-center gap-2 text-[#34a853] text-sm font-semibold">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    {{ session('success') }}
                </div>
                @if(session('whatsapp_link'))
                    <a href="{{ session('whatsapp_link') }}" target="_blank"
                       style="display:block; background-color:#25D366; color:#ffffff; text-decoration:none; padding:10px 16px; font-size:13px; font-weight:700; letter-spacing:0.05em; text-transform:uppercase;">
                        <span style="display:flex; align-items:center; gap:8px;">
                            <i class="fa-brands fa-whatsapp" style="font-size:18px;"></i>
                            Send Ticket on WhatsApp
                        </span>
                    </a>
                @endif
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-[#fee2e2] text-[#ef4444] rounded-none text-sm font-semibold">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('passengers.store') }}" method="POST">
            @csrf
            <input type="hidden" name="bus_id"      value="{{ $selectedBusId }}">
            <input type="hidden" name="journey_date" value="{{ $selectedDate }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- From Place -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">From Place</label>
                    <input type="text" name="from_place" value="{{ old('from_place') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           placeholder="Enter source" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- To Place -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">To Place</label>
                    <input type="text" name="to_place" value="{{ old('to_place') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           placeholder="Enter destination" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Seat Number -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Seat Number(s) *</label>
                    <input type="text" id="seat_number" name="seat_number" value="{{ old('seat_number') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           placeholder="{{ $selectedBusId ? 'Select from layout' : 'Select a bus first' }}"
                           required {{ !$selectedBusId ? 'readonly' : '' }}>
                </div>

                <!-- Passenger Name -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Passenger Name *</label>
                    <input type="text" name="passenger_name" value="{{ old('passenger_name') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           placeholder="Enter name" required {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Mobile Number -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Mobile Number</label>
                    <input type="text" name="passenger_mobile" value="{{ old('passenger_mobile') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           placeholder="Enter mobile" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>


                <!-- Traveler Name (from bus name) -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Traveler Name</label>
                    <input type="text" name="traveler_name" value="{{ old('traveler_name', $selectedBus?->name ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           placeholder="Enter traveler name" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Traveler Plate # (from bus plate) -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Traveler Plate #</label>
                    <input type="text" name="traveler_number_plate" value="{{ old('traveler_number_plate', $selectedBus?->plate_number ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           placeholder="Enter plate number" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- AC / Non AC -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">AC/Non AC *</label>
                    <select name="ac_type" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                            required {{ !$selectedBusId ? 'disabled' : '' }}>
                        <option value="Non Ac" {{ old('ac_type') == 'Non Ac' ? 'selected' : '' }}>Non Ac</option>
                        <option value="Ac"     {{ old('ac_type') == 'Ac'     ? 'selected' : '' }}>Ac</option>
                    </select>
                </div>

                <!-- Bus Time -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Bus Time</label>
                    <input type="time" name="bus_time" value="{{ old('bus_time') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                 <!-- Pickup Stop -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Pickup Stop</label>
                    <input type="text" name="pickup_stop" value="{{ old('pickup_stop') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           placeholder="Enter pickup location" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Total Seats -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Total Seats</label>
                    <input type="number" name="total_seats" id="total_seats" value="{{ old('total_seats', 1) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Per Seat Price -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Per Seat Price</label>
                    <input type="number" step="0.01" name="per_seat_price" id="per_seat_price"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           value="{{ old('per_seat_price') }}" placeholder="Auto calculates total" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Extra Passenger Amount -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Extra Passenger Amount</label>
                    <input type="number" step="0.01" name="extra_passenger_amount" id="extra_passenger_amount"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           value="{{ old('extra_passenger_amount', 0) }}" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Total Amount -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Total Amount *</label>
                    <input type="number" step="0.01" name="total_amount" id="total_amount"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           value="{{ old('total_amount', 0) }}" required {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Advance Payment -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Advance Payment *</label>
                    <input type="number" step="0.01" name="payable_amount" id="payable_amount"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           value="{{ old('payable_amount', 0) }}" required {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Baki Payment -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Baki Payment</label>
                    <input type="number" step="0.01" id="baki_payment"
                           class="w-full px-3 py-2 border border-gray-200 bg-gray-50 text-[14px] text-gray-500 rounded-none focus:outline-none"
                           value="0" readonly>
                </div>

                <!-- Payment Type -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Payment Type</label>
                    <select name="payment_method" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px] bg-white" {{ !$selectedBusId ? 'disabled' : '' }}>
                        <option value="" {{ old('payment_method') == '' ? 'selected' : '' }}>— Select —</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="GooglePay" {{ old('payment_method') == 'GooglePay' ? 'selected' : '' }}>Google Pay</option>
                        <option value="PhonePe" {{ old('payment_method') == 'PhonePe' ? 'selected' : '' }}>PhonePe</option>
                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                </div>

                <!-- Collected By -->
                <div class="mb-2">
                    <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Collected By</label>
                    <input type="text" name="payment_collected_by" value="{{ old('payment_collected_by') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                           placeholder="Person who collected payment" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                @if($selectedBus && $selectedBus->bus_type === 'Commission')
                    <!-- Commission Percentage -->
                    <div class="mb-2">
                        <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Commission Percentage (%)</label>
                        <input type="number" step="0.01" name="commission_percentage" id="commission_percentage"
                               class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                               value="{{ old('commission_percentage', 0) }}">
                    </div>

                    <!-- Commission Amount -->
                    <div class="mb-2">
                        <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Commission Amount</label>
                        <input type="number" step="0.01" name="commission_amount" id="commission_amount"
                               class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] bg-gray-50 text-[14px]"
                               value="{{ old('commission_amount', 0) }}" readonly>
                    </div>
                @endif

            </div>

            <!-- Note -->
            <div class="mb-6 mt-2">
                <label class="block text-[13px] font-black text-[#1c2238] uppercase tracking-wide mb-1">Note</label>
                <textarea name="note" rows="2"
                          class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]"
                          placeholder="Additional remarks" {{ !$selectedBusId ? 'disabled' : '' }}>{{ old('note') }}</textarea>
            </div>

            <button type="submit" id="submitBtn"
                    class="w-full bg-[#f0b44b] text-[#1c2238] font-black uppercase tracking-wide py-3 rounded-none hover:bg-[#e0a43b] transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ !$selectedBusId ? 'disabled' : '' }}>
                {{ $selectedBusId ? 'Select a Seat to Book' : 'Please Select a Bus' }}
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const seats           = document.querySelectorAll('.berth');
    const seatInput       = document.getElementById('seat_number');
    const submitBtn       = document.getElementById('submitBtn');
    const totalSeatsInput = document.querySelector('input[name="total_seats"]');

    const totalAmountInput  = document.getElementById('total_amount');
    const commPercentInput  = document.getElementById('commission_percentage');
    const commAmountInput   = document.getElementById('commission_amount');
    const payableAmountInput = document.getElementById('payable_amount');
    const bakiPaymentInput  = document.getElementById('baki_payment');

    const perSeatPriceInput = document.getElementById('per_seat_price');
    const extraAmountInput  = document.getElementById('extra_passenger_amount');

    if (totalAmountInput) {
        const calculateAmounts = (source = null) => {
            const extra = extraAmountInput ? (parseFloat(extraAmountInput.value) || 0) : 0;
            let baseTotal = 0;

            if (perSeatPriceInput && totalSeatsInput && (source === 'per_seat' || source === 'extra' || source === 'seats')) {
                const seats = parseFloat(totalSeatsInput.value) || 0;
                const price = parseFloat(perSeatPriceInput.value) || 0;
                baseTotal = seats * price;
                totalAmountInput.value = (baseTotal + extra).toFixed(2);
            } else {
                const total = parseFloat(totalAmountInput.value) || 0;
                baseTotal = Math.max(0, total - extra);
            }

            const total = parseFloat(totalAmountInput.value) || 0;
            
            if (commPercentInput && commAmountInput) {
                const percentage = parseFloat(commPercentInput.value) || 0;
                commAmountInput.value = (baseTotal * (percentage / 100)).toFixed(2);
            }
            if (payableAmountInput && bakiPaymentInput) {
                const advance = parseFloat(payableAmountInput.value) || 0;
                bakiPaymentInput.value = (total - advance).toFixed(2);
            }
        };
        
        totalAmountInput.addEventListener('input', () => calculateAmounts('total'));
        if (commPercentInput)   commPercentInput.addEventListener('input', () => calculateAmounts('comm'));
        if (payableAmountInput) payableAmountInput.addEventListener('input', () => calculateAmounts('pay'));
        if (perSeatPriceInput)  perSeatPriceInput.addEventListener('input', () => calculateAmounts('per_seat'));
        if (extraAmountInput)   extraAmountInput.addEventListener('input', () => calculateAmounts('extra'));
        if (totalSeatsInput)    totalSeatsInput.addEventListener('input', () => {
            calculateAmounts('seats');
        });
    }

    let selectedSeats = [];

    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            const isBooked  = seat.getAttribute('data-booked') === 'true';
            const seatLabel = seat.getAttribute('data-seat');

            if (isBooked) {
                alert('This seat is already booked.');
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

                if (seatInput)       seatInput.value = selectedSeats.join(', ');
                if (totalSeatsInput) {
                    totalSeatsInput.value = selectedSeats.length || 1;
                    if (document.getElementById('per_seat_price') && document.getElementById('per_seat_price').value) {
                        const price = parseFloat(document.getElementById('per_seat_price').value) || 0;
                        const extra = extraAmountInput ? (parseFloat(extraAmountInput.value) || 0) : 0;
                        if (totalAmountInput) {
                            totalAmountInput.value = (((selectedSeats.length || 1) * price) + extra).toFixed(2);
                            // Trigger input event to calculate commission/baki
                            totalAmountInput.dispatchEvent(new Event('input'));
                        }
                    }
                }

                if (submitBtn) {
                    if (selectedSeats.length > 0) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = `Book Seat${selectedSeats.length > 1 ? 's' : ''} ${selectedSeats.join(', ')}`;
                    } else {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Select a Seat to Book';
                    }
                }
            }
        });
    });
});
</script>
@endsection
