@extends('layouts.app')

@section('title', 'Edit Bus')
@section('header', 'Edit Bus')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-none shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-[17px] font-bold text-[#1c2238]">Edit Bus: {{ $bus->name }}</h2>
            <a href="{{ route('buses.index') }}" class="text-[13px] text-gray-500 hover:text-[#f0b44b] font-bold">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
        
        @if($errors->any())
            <div class="mb-4 p-3 bg-[#fee2e2] text-[#ef4444] rounded-none text-sm font-semibold">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('buses.update', $bus->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Bus Name -->
            <div class="mb-4">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Bus Name</label>
                <input type="text" name="name" value="{{ old('name', $bus->name) }}" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Total Seats -->
            <div class="mb-4">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Total Seats</label>
                <input type="number" name="total_seats" value="{{ old('total_seats', $bus->total_seats ?? 40) }}" min="1" max="60" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <!-- Bus Type + AC / Non AC -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Bus Type</label>
                    <select name="bus_type" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                        <option value="Personal" {{ old('bus_type', $bus->bus_type ?? 'Personal') == 'Personal' ? 'selected' : '' }}>Personal</option>
                        <option value="Commission" {{ old('bus_type', $bus->bus_type) == 'Commission' ? 'selected' : '' }}>Commission</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">AC / Non AC</label>
                    <select name="ac_non_ac" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                        <option value="AC" {{ old('ac_non_ac', $bus->ac_non_ac) == 'AC' ? 'selected' : '' }}>AC</option>
                        <option value="Non AC" {{ old('ac_non_ac', $bus->ac_non_ac ?? 'Non AC') == 'Non AC' ? 'selected' : '' }}>Non AC</option>
                    </select>
                </div>
            </div>

            <!-- Seat Layout -->
            <div class="mb-4">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Seat Position Layout</label>
                <select name="seat_layout" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                    <option value="2x2" {{ old('seat_layout', $bus->seat_layout ?? '2x2') == '2x2' ? 'selected' : '' }}>2x2 — Seater (2 seats each side)</option>
                    <option value="1x2" {{ old('seat_layout', $bus->seat_layout) == '1x2' ? 'selected' : '' }}>1x2 — Sleeper (1 left, 2 right)</option>
                </select>
                <p class="mt-2 text-[11px] text-orange-500 font-semibold">
                    ⚠️ Changing seat layout will re-arrange the seat map. Existing bookings (seat numbers) are not automatically changed.
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('buses.index') }}" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-600 font-bold text-[13px] rounded-none hover:bg-gray-50 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="flex-1 bg-[#1c2238] text-white font-bold py-2.5 rounded-none hover:bg-[#2a3350] transition-colors text-[13px]">
                    Update Bus
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
