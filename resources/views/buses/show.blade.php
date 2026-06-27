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
        border-radius: 12px;
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
        border-radius: 6px;
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
        border-radius: 10px;
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
    <div class="bg-white rounded-none p-6 shadow-sm border-l-4 border-l-blue-500 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fa-solid fa-chair text-blue-500" style="font-size: 60px;"></i>
        </div>
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 relative z-10">Total Seats Booked</h3>
        <p class="text-3xl font-black text-[#1c2238] relative z-10">{{ $totalSeatsSold }}</p>
    </div>

    <!-- Revenue -->
    <div class="bg-white rounded-none p-6 shadow-sm border-l-4 border-l-green-500 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fa-solid fa-indian-rupee-sign text-green-500" style="font-size: 60px;"></i>
        </div>
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 relative z-10">Total Revenue</h3>
        <p class="text-3xl font-black text-green-600 relative z-10">₹{{ number_format($totalRevenue, 2) }}</p>
    </div>

    <!-- Commission -->
    <div class="bg-white rounded-none p-6 shadow-sm border-l-4 border-l-[#f0b44b] relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fa-solid fa-hand-holding-dollar text-[#f0b44b]" style="font-size: 60px;"></i>
        </div>
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 relative z-10">Total Commission</h3>
        <p class="text-3xl font-black text-[#f0b44b] relative z-10">₹{{ number_format($totalCommission, 2) }}</p>
    </div>

    <!-- Payable -->
    <div class="bg-white rounded-none p-6 shadow-sm border-l-4 border-l-[#1c2238] relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fa-solid fa-wallet text-[#1c2238]" style="font-size: 60px;"></i>
        </div>
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 relative z-10">Payable Amount</h3>
        <p class="text-3xl font-black text-[#1c2238] relative z-10">₹{{ number_format($totalPayable, 2) }}</p>
    </div>
</div>

<div style="display: flex; flex-wrap: wrap; gap: 2rem;">
    
    <!-- Seat Layout (Bus Map) -->
    <div style="flex: 1 1 45%; min-width: 300px;" class="bg-white rounded-[10px] shadow-sm p-6 relative">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h2 class="text-[17px] font-bold text-[#1c2238]">Seat Layout (Sleeper)</h2>
            <form method="GET" action="{{ route('buses.show', $bus->id) }}" class="flex items-center gap-2">
                <label class="text-[12px] font-bold text-gray-500 uppercase tracking-widest">Date:</label>
                <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" class="px-3 py-1.5 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] font-bold text-[#1c2238] bg-gray-50">
            </form>
        </div>
        
        <div class="flex justify-between items-center mb-6 text-sm px-4">
            <div class="flex items-center"><span class="w-4 h-4 bg-white border-2 border-[#22c55e] rounded mr-2"></span> Available</div>
            <div class="flex items-center"><span class="w-4 h-4 bg-[#f0b44b] border-2 border-[#f0b44b] rounded mr-2"></span> Selected</div>
            <div class="flex items-center"><span class="w-4 h-4 bg-[#e5e7eb] border-2 border-[#e5e7eb] rounded mr-2"></span> Sold</div>
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
                                $passengerId = $isBooked ? $passengerData[$seatLabel]->id : null;
                                $passengerName = $isBooked ? $passengerData[$seatLabel]->passenger_name : '';
                            @endphp
                            <div class="berth-wrapper">
                                <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" data-pid="{{ $passengerId }}" data-pname="{{ $passengerName }}" data-pseats="{{ $isBooked ? $passengerData[$seatLabel]->seat_number : '' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
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
                                    $seatLabel = "L" . (5 + ($i * 2) - 1); // L6, L8, L10...
                                    $isBooked = in_array($seatLabel, $bookedSeats);
                                    $passengerId = $isBooked ? $passengerData[$seatLabel]->id : null;
                                    $passengerName = $isBooked ? $passengerData[$seatLabel]->passenger_name : '';
                                @endphp
                                <div class="berth-wrapper">
                                    <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" data-pid="{{ $passengerId }}" data-pname="{{ $passengerName }}" data-pseats="{{ $isBooked ? $passengerData[$seatLabel]->seat_number : '' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
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
                                    $seatLabel = "L" . (5 + ($i * 2)); // L7, L9, L11...
                                    $isBooked = in_array($seatLabel, $bookedSeats);
                                    $passengerId = $isBooked ? $passengerData[$seatLabel]->id : null;
                                    $passengerName = $isBooked ? $passengerData[$seatLabel]->passenger_name : '';
                                @endphp
                                <div class="berth-wrapper">
                                    <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" data-pid="{{ $passengerId }}" data-pname="{{ $passengerName }}" data-pseats="{{ $isBooked ? $passengerData[$seatLabel]->seat_number : '' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
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
                                $passengerId = $isBooked ? $passengerData[$seatLabel]->id : null;
                                $passengerName = $isBooked ? $passengerData[$seatLabel]->passenger_name : '';
                            @endphp
                            <div class="berth-wrapper">
                                <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" data-pid="{{ $passengerId }}" data-pname="{{ $passengerName }}" data-pseats="{{ $isBooked ? $passengerData[$seatLabel]->seat_number : '' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
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
                                    $passengerId = $isBooked ? $passengerData[$seatLabel]->id : null;
                                    $passengerName = $isBooked ? $passengerData[$seatLabel]->passenger_name : '';
                                @endphp
                                <div class="berth-wrapper">
                                    <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" data-pid="{{ $passengerId }}" data-pname="{{ $passengerName }}" data-pseats="{{ $isBooked ? $passengerData[$seatLabel]->seat_number : '' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
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
                                    $passengerId = $isBooked ? $passengerData[$seatLabel]->id : null;
                                    $passengerName = $isBooked ? $passengerData[$seatLabel]->passenger_name : '';
                                @endphp
                                <div class="berth-wrapper">
                                    <button type="button" data-seat="{{ $seatLabel }}" data-booked="{{ $isBooked ? 'true' : 'false' }}" data-pid="{{ $passengerId }}" data-pname="{{ $passengerName }}" data-pseats="{{ $isBooked ? $passengerData[$seatLabel]->seat_number : '' }}" class="berth {{ $isBooked ? 'berth-booked' : 'berth-available' }}">
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
    </div>

    <!-- Booking Form -->
    <div style="flex: 1 1 45%; min-width: 300px;" class="bg-white rounded-[10px] shadow-sm p-6 relative">
        <h2 class="text-[17px] font-bold text-[#1c2238] mb-6">Booking Details</h2>
        
        @if(session('success'))
            <div class="mb-4 p-3 bg-[#e8f5ed] text-[#34a853] rounded-md text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-[#fee2e2] text-[#ef4444] rounded-md text-sm font-semibold">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="bookingForm" action="{{ route('passengers.store') }}" method="POST">
            @csrf
            <input type="hidden" name="bus_id" value="{{ $bus->id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Seat Number -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Seat Number *</label>
                    <input type="text" id="seat_number" name="seat_number" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] bg-gray-50 text-[14px]" placeholder="Select from layout" readonly required>
                </div>

                <!-- Passenger Name -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Passenger Name *</label>
                    <input type="text" name="passenger_name" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter name" required>
                </div>

                <!-- Passenger Mobile -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Mobile Number</label>
                    <input type="text" name="passenger_mobile" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter mobile">
                </div>

                <!-- Village Name -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Village Name</label>
                    <input type="text" name="village_name" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter village">
                </div>

                <!-- Traveler Name -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Traveler Name</label>
                    <input type="text" name="traveler_name" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter traveler name">
                </div>

                <!-- Traveler Number Plate -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Traveler Plate #</label>
                    <input type="text" name="traveler_number_plate" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter plate number">
                </div>

                <!-- AC / Non AC -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">AC/Non AC *</label>
                    <select name="ac_type" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                        <option value="Non Ac">Non Ac</option>
                        <option value="Ac">Ac</option>
                    </select>
                </div>

                <!-- Journey Date -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Journey Date *</label>
                    <input type="date" name="journey_date" value="{{ $selectedDate }}" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px] bg-gray-100 cursor-not-allowed" required readonly>
                    <p class="text-[10px] text-gray-400 mt-1">Change date using the filter on the seat layout map.</p>
                </div>

                <!-- Bus Time -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Bus Time</label>
                    <input type="time" name="bus_time" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
                </div>

                <!-- Total Amount -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Total Amount *</label>
                    <input type="number" step="0.01" name="total_amount" id="total_amount" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" value="0" required>
                </div>

                <!-- Payable Amount -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Payable Amount *</label>
                    <input type="number" step="0.01" name="payable_amount" id="payable_amount" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" value="0" required>
                </div>

                <!-- Pickup Stop -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Pickup Stop</label>
                    <input type="text" name="pickup_stop" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Enter pickup location">
                </div>

                <!-- Total Seats -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Total Seats</label>
                    <input type="number" name="total_seats" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" value="1">
                </div>

                <!-- Commission (%) -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Commission (%)</label>
                    <input type="number" step="0.01" name="commission_percentage" id="commission_percentage" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" value="0">
                </div>

                <!-- Commission Amount -->
                <div class="mb-2">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Commission Amount</label>
                    <input type="number" step="0.01" name="commission_amount" id="commission_amount" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] bg-gray-50 text-[14px]" value="0" readonly>
                </div>
            </div>

            <!-- Note -->
            <div class="mb-4 mt-2">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-1">Note</label>
                <textarea name="note" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="Additional remarks"></textarea>
            </div>

            <button type="submit" id="submitBtn" class="w-full bg-[#f0b44b] text-[#1c2238] font-bold py-3 rounded-md hover:bg-[#e0a43b] transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                Select a Seat to Book
            </button>
        </form>
    </div>
</div>

<!-- Passenger List Table -->
<div class="mt-8 bg-white rounded-[10px] shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-[17px] font-bold text-[#1c2238]">Passenger List</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Seat(s)</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Passenger</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Contact</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Village / Pickup</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">AC/Non AC</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Amt / Payable</th>
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
                        {{ $passenger->village_name ?: '-' }} <br>
                        <span class="text-[11px] text-gray-400">Pickup: {{ $passenger->pickup_stop ?: '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">{{ $passenger->ac_type }}</td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">
                        ₹{{ $passenger->total_amount }} <br>
                        <span class="text-[11px] text-green-600 font-bold">₹{{ $passenger->payable_amount }}</span>
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
                    <td colspan="9" class="px-6 py-8 text-center text-[13px] text-gray-500 font-medium">No passengers booked on this bus yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Cancel Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 transition-opacity">
    <div class="bg-[#1c2238] rounded-[10px] w-full max-w-md p-6 transform scale-95 transition-transform shadow-2xl relative border border-gray-700/50">
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
                <button type="button" id="cancelActionNo" class="flex-1 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-gray-700 hover:bg-gray-600 transition-colors border border-gray-600">
                    NO, KEEP IT
                </button>
                <form id="cancelForm" method="POST" action="" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 rounded-lg text-sm font-bold text-white bg-[#ef4444] hover:bg-[#dc2626] transition-colors border border-[#dc2626]">
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
        
        // Commission calculations
        const totalAmountInput = document.getElementById('total_amount');
        const commPercentInput = document.getElementById('commission_percentage');
        const commAmountInput = document.getElementById('commission_amount');

        const calculateCommission = () => {
            const total = parseFloat(totalAmountInput.value) || 0;
            const percent = parseFloat(commPercentInput.value) || 0;
            const commAmount = (total * percent) / 100;
            commAmountInput.value = commAmount.toFixed(2);
        };

        totalAmountInput.addEventListener('input', calculateCommission);
        commPercentInput.addEventListener('input', calculateCommission);

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
