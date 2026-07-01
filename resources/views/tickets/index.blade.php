@extends('layouts.app')

@section('title', 'Print Tickets')
@section('header', 'Print Tickets')

@section('content')
<div class="bg-white rounded-none p-6 shadow-sm mb-6">
    <form method="GET" action="{{ route('tickets.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
        
        <!-- Search Field -->
        <div class="w-full md:w-1/3">
            <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Search Passenger</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Mobile, or Seat..." class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
        </div>

        <!-- Date Filter -->
        <div class="w-full md:w-1/4">
            <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Journey Date</label>
            <input type="date" name="date" value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[14px]">
        </div>

        <!-- Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="px-6 py-2.5 bg-[#1c2238] text-white font-semibold rounded-none hover:bg-[#2a3454] transition-colors">
                Search
            </button>
            <a href="{{ route('tickets.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-none hover:bg-gray-200 transition-colors">
                Clear
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-none shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Journey Date</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Passenger</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Bus</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Seat(s)</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50">Note</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-gray-50 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-[13px] font-medium text-gray-600">
                        {{ \Carbon\Carbon::parse($ticket->journey_date)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-[14px] font-bold text-[#1c2238]">{{ $ticket->passenger_name }}</div>
                        <div class="text-[12px] text-gray-500">{{ $ticket->passenger_mobile }}</div>
                    </td>
                    <td class="px-6 py-4 text-[13px] font-medium text-gray-600">
                        {{ $ticket->bus->name }}
                    </td>
                    <td class="px-6 py-4 text-[14px] font-bold text-[#1c2238]">
                        {{ $ticket->seat_number }}
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-500 max-w-[150px] truncate" title="{{ $ticket->note }}">
                        {{ $ticket->note ?: '-' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <!-- Print Button -->
                            <a href="{{ route('tickets.show', $ticket->id) }}" target="_blank" class="text-gray-500 hover:text-green-600 transition-colors" title="Print Ticket">
                                <i class="fa-solid fa-print text-[16px]"></i>
                            </a>
                            
                            <!-- PDF Button -->
                            <a href="{{ route('tickets.pdf', $ticket->id) }}" class="text-gray-500 hover:text-red-600 transition-colors" title="Download PDF">
                                <i class="fa-solid fa-file-pdf text-[16px]"></i>
                            </a>

                            <!-- Edit Button -->
                            <a href="{{ route('passengers.edit', $ticket->id) }}" class="text-gray-500 hover:text-blue-600 transition-colors" title="Edit Booking">
                                <i class="fa-solid fa-pen text-[16px]"></i>
                            </a>
                            
                            <!-- Remove Button -->
                            <form method="POST" action="{{ route('passengers.destroy', $ticket->id) }}" onsubmit="return confirm('Are you sure you want to permanently remove this booking?');" class="inline-block">
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
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-[14px]">
                        No tickets found matching your search.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
