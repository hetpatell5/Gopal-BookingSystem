@extends('layouts.app')

@section('title', 'Bus Contracts')
@section('header', 'Bus Contracts')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-bold text-[#1c2238]">All Contracts</h2>
    <a href="{{ route('contracts.create') }}" class="bg-[#c0001a] text-white px-4 py-2 rounded font-bold hover:bg-[#a00016]">
        + New Contract
    </a>
</div>

<div class="bg-white shadow-sm rounded border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-200 bg-gray-50">
                <th class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">ID</th>
                <th class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Booking No</th>
                <th class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Party Name</th>
                <th class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Date</th>
                <th class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($contracts as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-[13px] font-medium text-gray-600">{{ $c->id }}</td>
                <td class="px-6 py-4 text-[13px] font-bold text-[#c0001a]">{{ $c->booking_number ?: '-' }}</td>
                <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">{{ $c->party_name ?: '-' }}</td>
                <td class="px-6 py-4 text-[13px] text-gray-500">{{ $c->contract_date ?: '-' }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('contracts.edit', $c->id) }}" class="text-[#f0b44b] hover:text-[#d99f3c] mr-3" title="Edit/Print">
                        <i class="fa-solid fa-file-contract"></i> Edit/Print
                    </a>
                    <form action="{{ route('contracts.destroy', $c->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this contract?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-400 font-medium">No contracts found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100">
        {{ $contracts->links() }}
    </div>
</div>
@endsection
