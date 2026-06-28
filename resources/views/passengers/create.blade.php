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
        cursor: not-allowed;
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

<!-- Bus & Date Selection Form -->
<div class="mb-6 bg-white rounded-none shadow-sm p-6">
    <form id="busSelectForm" method="GET" action="{{ route('passengers.create') }}" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Select Bus to View Layout</label>
            <select name="bus_id" onchange="document.getElementById('busSelectForm').submit()" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                <option value="">-- Choose a Bus --</option>
                @foreach($buses as $bus)
                    <option value="{{ $bus->id }}" {{ $selectedBusId == $bus->id ? 'selected' : '' }}>
                        {{ $bus->name }} ({{ $bus->plate_number }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Journey Date</label>
            <input type="date" name="date" value="{{ $selectedDate }}" onchange="document.getElementById('busSelectForm').submit()" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
        </div>
    </form>
</div>

<div style="display: flex; flex-wrap: wrap; gap: 2rem;">
    <!-- Seat Layout (Bus Map) -->
    <div style="flex: 1 1 45%; min-width: 300px;" class="bg-white rounded-none shadow-sm p-6 relative">
        <h2 class="text-[17px] font-bold text-[#1c2238] mb-6">Seat Layout (Sleeper)</h2>
        
        @if(!$selectedBusId)
            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                <i class="fa-solid fa-bus text-4xl mb-3"></i>
                <p class="text-[14px] font-medium">Please select a bus to view the layout.</p>
            </div>
        @else
            <div class="flex justify-between items-center mb-6 text-sm px-4">
                <div class="flex items-center"><span class="w-4 h-4 bg-white border-2 border-[#22c55e] rounded-none mr-2"></span> Available</div>
                <div class="flex items-center"><span class="w-4 h-4 bg-[#f0b44b] border-2 border-[#f0b44b] rounded-none mr-2"></span> Selected</div>
                <div class="flex items-center"><span class="w-4 h-4 bg-[#e5e7eb] border-2 border-[#e5e7eb] rounded-none mr-2"></span> Sold</div>
            </div>

            <div class="deck-container">
                <!-- Lower Deck -->
                <div class="deck">
                    <div class="deck-title">
                        Lower deck
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <circle cx="12" cy="12" r="4" stroke-width="2"/>
                            <path stroke-width="2" d="M12 2v6m0 8v6m-8-8h6m8 0h-6"/>
                        </svg>
                    </div>
                    
                    <div class="row-layout">
                        <!-- Left Single Col -->
                        <div class="col-layout">
                            @for($i=1; $i<=5; $i++)
                                @php
                                    $seatLabel = "L" . $i;
                                    $isBooked = in_array($seatLabel, $bookedSeats);
                                @endphp
                                <div class="berth-wrapper">
                                    <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
                                        {{ $seatLabel }}
                                    </button>
                                    @if($isBooked) <span class="berth-booked-label">Sold</span> @endif
                                </div>
                            @endfor
                        </div>
                        
                        <!-- Aisle -->
                        <div style="width: 40px;"></div>
                        
                        <!-- Right Double Col -->
                        <div class="double-col">
                            <!-- Inner Col -->
                            <div class="col-layout">
                                @for($i=1; $i<=5; $i++)
                                    @php
                                        $seatLabel = "L" . (5 + ($i * 2) - 1);
                                        $isBooked = in_array($seatLabel, $bookedSeats);
                                    @endphp
                                    <div class="berth-wrapper">
                                        <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
                                            {{ $seatLabel }}
                                        </button>
                                        @if($isBooked) <span class="berth-booked-label">Sold</span> @endif
                                    </div>
                                @endfor
                            </div>
                            <!-- Outer Col -->
                            <div class="col-layout">
                                @for($i=1; $i<=5; $i++)
                                    @php
                                        $seatLabel = "L" . (5 + ($i * 2));
                                        $isBooked = in_array($seatLabel, $bookedSeats);
                                    @endphp
                                    <div class="berth-wrapper">
                                        <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
                                            {{ $seatLabel }}
                                        </button>
                                        @if($isBooked) <span class="berth-booked-label">Sold</span> @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upper Deck -->
                <div class="deck">
                    <div class="deck-title">
                        Upper deck
                    </div>
                    
                    <div class="row-layout">
                        <!-- Left Single Col -->
                        <div class="col-layout">
                            @for($i=1; $i<=5; $i++)
                                @php
                                    $seatLabel = "U" . $i;
                                    $isBooked = in_array($seatLabel, $bookedSeats);
                                @endphp
                                <div class="berth-wrapper">
                                    <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
                                        {{ $seatLabel }}
                                    </button>
                                    @if($isBooked) <span class="berth-booked-label">Sold</span> @endif
                                </div>
                            @endfor
                        </div>
                        
                        <!-- Aisle -->
                        <div style="width: 40px;"></div>
                        
                        <!-- Right Double Col -->
                        <div class="double-col">
                            <!-- Inner Col -->
                            <div class="col-layout">
                                @for($i=1; $i<=5; $i++)
                                    @php
                                        $seatLabel = "U" . (5 + ($i * 2) - 1);
                                        $isBooked = in_array($seatLabel, $bookedSeats);
                                    @endphp
                                    <div class="berth-wrapper">
                                        <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
                                            {{ $seatLabel }}
                                        </button>
                                        @if($isBooked) <span class="berth-booked-label">Sold</span> @endif
                                    </div>
                                @endfor
                            </div>
                            <!-- Outer Col -->
                            <div class="col-layout">
                                @for($i=1; $i<=5; $i++)
                                    @php
                                        $seatLabel = "U" . (5 + ($i * 2));
                                        $isBooked = in_array($seatLabel, $bookedSeats);
                                    @endphp
                                    <div class="berth-wrapper">
                                        <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
                                            {{ $seatLabel }}
                                        </button>
                                        @if($isBooked) <span class="berth-booked-label">Sold</span> @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Booking Form -->
    <div style="flex: 1 1 45%; min-width: 300px;" class="bg-white rounded-none shadow-sm p-6 relative">
        <h2 class="text-[17px] font-bold text-[#1c2238] mb-6">Booking Details</h2>
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-[#e8f5ed] border border-[#34a853]/20 shadow-sm rounded-none text-sm font-semibold flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center text-[#34a853]">
                    <i class="fa-solid fa-circle-check mr-2 text-lg"></i>
                    {{ session('success') }}
                </div>
                
                @if(session('whatsapp_link'))
                    <a href="{{ session('whatsapp_link') }}" target="_blank" class="px-4 py-2 bg-[#25D366] text-white text-[12px] font-bold uppercase tracking-wider hover:bg-[#128C7E] transition-colors rounded-none flex items-center shadow-sm">
                        <i class="fa-brands fa-whatsapp text-lg mr-2"></i> Send Ticket on WhatsApp
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
            
            <input type="hidden" name="bus_id" value="{{ $selectedBusId }}">
            <input type="hidden" name="journey_date" value="{{ $selectedDate }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Seat Number -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Seat Number(s) *</label>
                    <input type="text" id="seat_number" name="seat_number" value="{{ old('seat_number') }}" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="{{ $selectedBusId ? 'Select from layout' : 'Select a bus first' }}" required {{ !$selectedBusId ? 'readonly' : '' }}>
                </div>

                <!-- Passenger Name -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Passenger Name *</label>
                    <input type="text" name="passenger_name" value="{{ old('passenger_name') }}" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter name" required {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Passenger Mobile -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Mobile Number</label>
                    <input type="text" name="passenger_mobile" value="{{ old('passenger_mobile') }}" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter mobile" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Village Name -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Village Name</label>
                    <input type="text" name="village_name" value="{{ old('village_name') }}" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter village" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Traveler Name -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Traveler Name</label>
                    <input type="text" name="traveler_name" value="{{ old('traveler_name') }}" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter traveler name" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Traveler Number Plate -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Traveler Plate #</label>
                    <input type="text" name="traveler_number_plate" value="{{ old('traveler_number_plate') }}" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter plate number" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- AC / Non AC -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">AC/Non AC *</label>
                    <select name="ac_type" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required {{ !$selectedBusId ? 'disabled' : '' }}>
                        <option value="Non Ac" {{ old('ac_type') == 'Non Ac' ? 'selected' : '' }}>Non Ac</option>
                        <option value="Ac" {{ old('ac_type') == 'Ac' ? 'selected' : '' }}>Ac</option>
                    </select>
                </div>

                <!-- Bus Time -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Bus Time</label>
                    <input type="time" name="bus_time" value="{{ old('bus_time') }}" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Total Amount -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Total Amount *</label>
                    <input type="number" step="0.01" name="total_amount" id="total_amount" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" value="{{ old('total_amount', 0) }}" required {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Advance Payment -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Advance Payment *</label>
                    <input type="number" step="0.01" name="payable_amount" id="payable_amount" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" value="{{ old('payable_amount', 0) }}" required {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>
                
                <!-- Baki Payment -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Baki Payment</label>
                    <input type="number" step="0.01" id="baki_payment" class="w-full px-3 py-2 border border-gray-200 bg-gray-50 text-[14px] text-gray-500 rounded-none focus:outline-none" value="0" readonly>
                </div>
                
                <!-- Pickup Stop -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Pickup Stop</label>
                    <input type="text" name="pickup_stop" value="{{ old('pickup_stop') }}" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter pickup location" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Total Seats -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Total Seats</label>
                    <input type="number" name="total_seats" value="{{ old('total_seats', 1) }}" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Commission (%) -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Commission (%)</label>
                    <input type="number" step="0.01" name="commission_percentage" id="commission_percentage" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" value="{{ old('commission_percentage', 0) }}" {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>

                <!-- Commission Amount -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Commission Amount</label>
                    <input type="number" step="0.01" name="commission_amount" id="commission_amount" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] bg-gray-50 text-[14px]" value="{{ old('commission_amount', 0) }}" readonly {{ !$selectedBusId ? 'disabled' : '' }}>
                </div>
            </div>

            <!-- Note -->
            <div class="mb-6 mt-2">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Note</label>
                <textarea name="note" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Additional remarks" {{ !$selectedBusId ? 'disabled' : '' }}>{{ old('note') }}</textarea>
            </div>

            <button type="submit" id="submitBtn" class="w-full bg-[#f0b44b] text-[#1c2238] font-bold py-3 rounded-none hover:bg-[#e0a43b] transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" {{ !$selectedBusId ? 'disabled' : '' }}>
                {{ $selectedBusId ? 'Select a Seat to Book' : 'Please Select a Bus' }}
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const seats = document.querySelectorAll('.berth');
        const seatInput = document.getElementById('seat_number');
        const submitBtn = document.getElementById('submitBtn');
        const totalSeatsInput = document.querySelector('input[name="total_seats"]');
        
        // Commission calculations
        const totalAmountInput = document.getElementById('total_amount');
        const commPercentInput = document.getElementById('commission_percentage');
        const commAmountInput = document.getElementById('commission_amount');
        
        // Baki Payment calculation
        const payableAmountInput = document.getElementById('payable_amount');
        const bakiPaymentInput = document.getElementById('baki_payment');

        if (totalAmountInput) {
            const calculateAmounts = () => {
                const total = parseFloat(totalAmountInput.value) || 0;
                
                // Commission
                if(commPercentInput && commAmountInput) {
                    const percent = parseFloat(commPercentInput.value) || 0;
                    const commAmount = (total * percent) / 100;
                    commAmountInput.value = commAmount.toFixed(2);
                }
                
                // Baki Payment
                if(payableAmountInput && bakiPaymentInput) {
                    const advance = parseFloat(payableAmountInput.value) || 0;
                    const baki = total - advance;
                    bakiPaymentInput.value = baki.toFixed(2);
                }
            };

            totalAmountInput.addEventListener('input', calculateAmounts);
            if(commPercentInput) commPercentInput.addEventListener('input', calculateAmounts);
            if(payableAmountInput) payableAmountInput.addEventListener('input', calculateAmounts);
        }

        let selectedSeats = [];

        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                const isBooked = seat.getAttribute('data-booked') === 'true';
                const seatLabel = seat.getAttribute('data-seat');

                if (isBooked) {
                    // Cannot book a booked seat in quick booking mode
                    alert('This seat is already booked.');
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

                    if (seatInput) seatInput.value = selectedSeats.join(', ');
                    if (totalSeatsInput) totalSeatsInput.value = selectedSeats.length || 1;

                    if (selectedSeats.length > 0) {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = `Book Seat${selectedSeats.length > 1 ? 's' : ''} ${selectedSeats.join(', ')}`;
                        }
                    } else {
                        if (submitBtn) {
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
