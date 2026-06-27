@extends('layouts.app')

@section('title', 'Edit Passenger')
@section('header', 'Edit Passenger: ' . $passenger->passenger_name)

@section('content')
<div class="bg-white rounded-[10px] shadow-sm p-8 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
        <h2 class="text-[17px] font-bold text-[#1c2238]">Booking Details</h2>
        <a href="{{ route('passengers.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Back to List</a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-[#fee2e2] text-[#ef4444] rounded-md text-sm font-semibold border border-[#fca5a5]">
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
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Bus *</label>
                <select name="bus_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                    <option value="">Select Bus</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->id }}" {{ $passenger->bus_id == $bus->id ? 'selected' : '' }}>
                            {{ $bus->name }} ({{ $bus->plate_number }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Seat Number -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Seat Number(s) *</label>
                <input type="text" name="seat_number" value="{{ old('seat_number', $passenger->seat_number) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Passenger Name -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Passenger Name *</label>
                <input type="text" name="passenger_name" value="{{ old('passenger_name', $passenger->passenger_name) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Passenger Mobile -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Mobile Number</label>
                <input type="text" name="passenger_mobile" value="{{ old('passenger_mobile', $passenger->passenger_mobile) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Village Name -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Village Name</label>
                <input type="text" name="village_name" value="{{ old('village_name', $passenger->village_name) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Traveler Name -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Traveler Name</label>
                <input type="text" name="traveler_name" value="{{ old('traveler_name', $passenger->traveler_name) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Traveler Number Plate -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Traveler Plate #</label>
                <input type="text" name="traveler_number_plate" value="{{ old('traveler_number_plate', $passenger->traveler_number_plate) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- AC / Non AC -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">AC/Non AC *</label>
                <select name="ac_type" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                    <option value="Non Ac" {{ $passenger->ac_type == 'Non Ac' ? 'selected' : '' }}>Non Ac</option>
                    <option value="Ac" {{ $passenger->ac_type == 'Ac' ? 'selected' : '' }}>Ac</option>
                </select>
            </div>

            <!-- Journey Date -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Journey Date *</label>
                <input type="date" name="journey_date" value="{{ old('journey_date', $passenger->journey_date) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Bus Time -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Bus Time</label>
                <input type="time" name="bus_time" value="{{ old('bus_time', $passenger->bus_time) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Total Seats -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Total Seats</label>
                <input type="number" name="total_seats" value="{{ old('total_seats', $passenger->total_seats) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Total Amount -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Total Amount *</label>
                <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount', $passenger->total_amount) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Payable Amount -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Payable Amount *</label>
                <input type="number" step="0.01" name="payable_amount" value="{{ old('payable_amount', $passenger->payable_amount) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Commission (%) -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Commission (%)</label>
                <input type="number" step="0.01" name="commission_percentage" id="commission_percentage" value="{{ old('commission_percentage', $passenger->commission_percentage) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Commission Amount -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Commission Amount</label>
                <input type="number" step="0.01" name="commission_amount" id="commission_amount" value="{{ old('commission_amount', $passenger->commission_amount) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] bg-gray-50 text-[14px]" readonly>
            </div>

            <!-- Pickup Stop -->
            <div class="md:col-span-2">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Pickup Stop</label>
                <input type="text" name="pickup_stop" value="{{ old('pickup_stop', $passenger->pickup_stop) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
            </div>

            <!-- Note -->
            <div class="md:col-span-2">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Note</label>
                <textarea name="note" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">{{ old('note', $passenger->note) }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="flex-1 bg-[#f0b44b] text-[#1c2238] font-bold py-3.5 rounded-lg hover:bg-[#e0a43b] transition-colors shadow-sm">
                Save Changes
            </button>
            <a href="{{ route('passengers.index') }}" class="flex-1 bg-gray-100 text-gray-600 font-bold py-3.5 rounded-lg hover:bg-gray-200 transition-colors shadow-sm text-center">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const totalAmountInput = document.querySelector('input[name="total_amount"]');
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
    });
</script>
@endsection
