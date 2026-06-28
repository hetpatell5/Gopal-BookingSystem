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
            
            <div class="mb-4">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Bus Name</label>
                <input type="text" name="name" value="{{ old('name', $bus->name) }}" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <div class="mb-6">
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Plate Number</label>
                <input type="text" name="plate_number" value="{{ old('plate_number', $bus->plate_number) }}" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
            </div>

            <button type="submit" class="w-full bg-[#f0b44b] text-[#1c2238] font-bold py-2.5 rounded-none hover:bg-[#e0a43b] transition-colors">
                Update Bus
            </button>
        </form>
    </div>
</div>
@endsection
