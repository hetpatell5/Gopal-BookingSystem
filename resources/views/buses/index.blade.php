@extends('layouts.app')

@section('title', 'Manage Buses')
@section('header', 'Manage Buses')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Add Bus Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-none shadow-sm p-6">
            <h2 class="text-[17px] font-bold text-[#1c2238] mb-6">Add New Bus</h2>
            
            @if(session('success'))
                <div class="mb-4 p-3 bg-[#e8f5ed] text-[#34a853] rounded-none text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-[#fee2e2] text-[#ef4444] rounded-none text-sm font-semibold">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('buses.store') }}" method="POST" id="addBusForm">
                @csrf
                
                <!-- Bus Name -->
                <div class="mb-4">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Bus Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="e.g. Gandhinagar Travels" required>
                </div>

                <!-- Plate Number + Total Seats -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Plate Number</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="GJ-01-CC-2143" required>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Total Seats</label>
                        <input type="number" name="total_seats" value="{{ old('total_seats', 40) }}" min="1" max="60" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" placeholder="40" required>
                    </div>
                </div>

                <!-- Bus Type + AC / Non AC -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Bus Type</label>
                        <select name="bus_type" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                            <option value="Personal" {{ old('bus_type') == 'Personal' ? 'selected' : '' }}>Personal</option>
                            <option value="Commission" {{ old('bus_type') == 'Commission' ? 'selected' : '' }}>Commission</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">AC / Non AC</label>
                        <select name="ac_non_ac" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                            <option value="AC" {{ old('ac_non_ac') == 'AC' ? 'selected' : '' }}>AC</option>
                            <option value="Non AC" {{ old('ac_non_ac', 'Non AC') == 'Non AC' ? 'selected' : '' }}>Non AC</option>
                        </select>
                    </div>
                </div>

                <!-- Seat Layout -->
                <div class="mb-4">
                    <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wide mb-2">Seat Position Layout</label>
                    <select name="seat_layout" id="seat_layout_select" class="w-full px-4 py-2 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]" required>
                        <option value="2x2" {{ old('seat_layout', '2x2') == '2x2' ? 'selected' : '' }}>2x2 — Seater (2 seats each side)</option>
                        <option value="1x2" {{ old('seat_layout') == '1x2' ? 'selected' : '' }}>1x2 — Sleeper (1 left, 2 right)</option>
                    </select>
                    <!-- Mini preview -->
                    <div id="layout_preview" class="mt-3 p-3 bg-gray-50 border border-gray-100 flex items-center justify-center gap-3">
                        <!-- Filled by JS -->
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('addBusForm').reset(); updatePreview();" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-600 font-bold text-[13px] rounded-none hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-[#1c2238] text-white font-bold py-2.5 rounded-none hover:bg-[#2a3350] transition-colors text-[13px]">
                        Save Bus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Buses List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-none shadow-sm">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-[17px] font-bold text-[#1c2238]">Existing Buses</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Bus Name</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Plate Number</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Type</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Seats</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Layout</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($buses as $bus)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">
                                <a href="{{ route('buses.show', $bus->id) }}" class="text-[#f0b44b] hover:underline">{{ $bus->name }}</a>
                                <div class="text-[11px] text-gray-400 font-medium mt-0.5">{{ $bus->ac_non_ac ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-[13px] font-medium text-gray-600">{{ $bus->plate_number }}</td>
                            <td class="px-6 py-4 text-[13px] font-medium text-gray-600">
                                @if(($bus->bus_type ?? 'Personal') == 'Personal')
                                    <span class="px-2 py-1 text-[11px] font-bold bg-blue-50 text-blue-600 rounded-sm">Personal</span>
                                @else
                                    <span class="px-2 py-1 text-[11px] font-bold bg-[#fff8ec] text-[#f0b44b] rounded-sm">Commission</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[13px] font-medium text-gray-600">{{ $bus->total_seats ?? '-' }}</td>
                            <td class="px-6 py-4 text-[13px] font-medium text-gray-600">{{ $bus->seat_layout ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('buses.edit', $bus->id) }}" class="text-gray-500 hover:text-blue-600 transition-colors" title="Edit Bus">
                                        <i class="fa-solid fa-pen text-[16px]"></i>
                                    </a>
                                    <form method="POST" action="{{ route('buses.destroy', $bus->id) }}" onsubmit="return confirm('Are you sure you want to delete this bus? All associated passengers and records will be lost.');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors" title="Delete Bus">
                                            <i class="fa-solid fa-trash text-[16px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-[13px] text-gray-500 font-medium">No buses found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    function updatePreview() {
        const layout = document.getElementById('seat_layout_select').value;
        const preview = document.getElementById('layout_preview');

        if (layout === '1x2') {
            preview.innerHTML = `
                <div style="display:flex;flex-direction:column;gap:6px;align-items:center;">
                    <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                    <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                </div>
                <div style="width:16px;border-left:2px dashed #e5e7eb;height:90px;margin:0 6px;"></div>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <div style="display:flex;gap:4px;">
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                    </div>
                    <div style="display:flex;gap:4px;">
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                    </div>
                </div>
                <span style="font-size:11px;color:#6b7280;margin-left:8px;">1 left, 2 right<br>(Sleeper)</span>`;
        } else {
            preview.innerHTML = `
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <div style="display:flex;gap:4px;">
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                    </div>
                    <div style="display:flex;gap:4px;">
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                    </div>
                </div>
                <div style="width:16px;border-left:2px dashed #e5e7eb;height:90px;margin:0 6px;"></div>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <div style="display:flex;gap:4px;">
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                    </div>
                    <div style="display:flex;gap:4px;">
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                        <div style="width:28px;height:40px;border:2px solid #22c55e;border-radius:2px;background:#f0fdf4;"></div>
                    </div>
                </div>
                <span style="font-size:11px;color:#6b7280;margin-left:8px;">2 left, 2 right<br>(Seater)</span>`;
        }
    }

    document.getElementById('seat_layout_select').addEventListener('change', updatePreview);
    updatePreview(); // run on load
</script>
@endsection
