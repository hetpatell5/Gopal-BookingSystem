@extends('layouts.app')
@section('title', 'Agent Sales Ledger')
@section('header', 'Agent Sales Ledger')

@section('content')

<div class="mb-6 flex justify-between items-center max-w-6xl mx-auto w-full">
    <a href="{{ route('personal-accounts.index') }}" class="text-sm font-semibold text-gray-500 hover:text-[#f0b44b] transition-colors">
        <i class="fa-solid fa-arrow-left mr-1"></i> Back to Personal Accounts
    </a>
    
    <a href="{{ route('agent-tickets.create') }}" class="bg-[#1c2238] text-white font-bold text-[13px] px-4 py-2 rounded-none hover:bg-[#29324b] transition-colors shadow-sm uppercase tracking-widest">
        <i class="fa-solid fa-plus mr-2"></i> Record New Sale
    </a>
</div>

<div class="max-w-6xl mx-auto w-full">
    @if(session('success'))
        <div class="w-full p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-none border border-green-200">
            {{ session('success') }}
        </div>
    @endif

 

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow-sm p-5 border border-gray-100 rounded-none">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Seats (Sheets)</p>
            <p class="text-[28px] font-black text-[#1c2238] leading-none mb-1">{{ $totalSeats }}</p>
        </div>
        <div class="bg-white shadow-sm p-5 border border-gray-100 rounded-none">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Commission</p>
            <p class="text-[28px] font-black text-rose-500 leading-none mb-1">₹{{ number_format($totalCommission, 2) }}</p>
        </div>
        <div class="bg-white shadow-sm p-5 border border-gray-100 rounded-none">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Net Owner Revenue</p>
            <p class="text-[28px] font-black text-green-600 leading-none mb-1">₹{{ number_format($totalNetRevenue, 2) }}</p>
        </div>
    </div>

       {{-- Filter Bar --}}
    <div class="bg-white shadow-sm rounded-none border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-[13px] font-bold text-[#1c2238] uppercase tracking-widest"><i class="fa-solid fa-filter mr-2 text-[#f0b44b]"></i> Filter Ledger</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('agent-tickets.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none text-[13px] px-3 py-2 shadow-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Agent Name</label>
                    <input type="text" name="agent_name" value="{{ request('agent_name') }}" placeholder="Search Agent..." class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none text-[13px] px-3 py-2 shadow-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Bus Name</label>
                    <input type="text" name="bus_name" value="{{ request('bus_name') }}" placeholder="Search Bus..." class="w-full border border-gray-300 rounded-none focus:border-[#f0b44b] focus:ring-1 focus:ring-[#f0b44b] outline-none text-[13px] px-3 py-2 shadow-sm">
                </div>
                <div class="flex items-end gap-2 mt-4 md:mt-0">
                    <button type="submit" class="bg-[#1c2238] text-white font-bold text-[13px] px-6 py-2 rounded-none hover:bg-[#29324b] transition-colors shadow-sm h-[38px]">
                        Apply
                    </button>
                    @if(request()->hasAny(['date', 'agent_name', 'bus_name']))
                        <a href="{{ route('agent-tickets.index') }}" class="bg-gray-100 text-gray-600 font-bold text-[13px] px-4 py-2 rounded-none hover:bg-gray-200 transition-colors shadow-sm flex items-center justify-center h-[38px]" title="Clear Filters">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Ledger Table --}}
    <div class="bg-white shadow-sm rounded-none border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-[15px] font-bold text-[#1c2238]"><i class="fa-solid fa-list mr-2 text-[#f0b44b]"></i> Sales Ledger</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-200 bg-gray-50 w-[40px]">#</th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-200 bg-gray-50">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sale_date', 'direction' => request('sort', 'sale_date') == 'sale_date' && request('direction', 'desc') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-[#f0b44b] transition-colors flex items-center">
                                Date <i class="fa-solid fa-sort{{ request('sort', 'sale_date') == 'sale_date' ? (request('direction', 'desc') == 'asc' ? '-up' : '-down') : '' }} ml-1"></i>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-200 bg-gray-50">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'agent_name', 'direction' => request('sort') == 'agent_name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-[#f0b44b] transition-colors flex items-center">
                                Agent / Bus <i class="fa-solid fa-sort{{ request('sort') == 'agent_name' ? (request('direction') == 'asc' ? '-up' : '-down') : '' }} ml-1"></i>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-200 bg-gray-50 text-right">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_amount', 'direction' => request('sort') == 'total_amount' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-[#f0b44b] transition-colors flex items-center justify-end">
                                Total Amt <i class="fa-solid fa-sort{{ request('sort') == 'total_amount' ? (request('direction') == 'asc' ? '-up' : '-down') : '' }} ml-1"></i>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-200 bg-gray-50 text-right">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'commission_amount', 'direction' => request('sort') == 'commission_amount' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-[#f0b44b] transition-colors flex items-center justify-end">
                                Comm <i class="fa-solid fa-sort{{ request('sort') == 'commission_amount' ? (request('direction') == 'asc' ? '-up' : '-down') : '' }} ml-1"></i>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-200 bg-gray-50 text-right">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'net_amount', 'direction' => request('sort') == 'net_amount' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-[#f0b44b] transition-colors flex items-center justify-end">
                                Net <i class="fa-solid fa-sort{{ request('sort') == 'net_amount' ? (request('direction') == 'asc' ? '-up' : '-down') : '' }} ml-1"></i>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-200 bg-gray-50 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-[13px] font-bold text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-[13px] text-gray-500">{{ \Carbon\Carbon::parse($ticket->sale_date)->format('d M Y') }}</td>
                            <td class="px-4 py-4">
                                <div class="text-[14px] font-bold text-[#1c2238]">{{ $ticket->agent_name }}</div>
                                <div class="text-[11px] font-bold text-gray-500 tracking-widest uppercase mt-0.5">{{ $ticket->bus_name }}</div>
                            </td>
                            <td class="px-4 py-4 text-[13px] font-bold text-gray-600 text-right">
                                <div class="text-[#1c2238]">₹{{ number_format($ticket->total_amount, 2) }}</div>
                                <div class="text-[10px] text-gray-400">{{ $ticket->total_seats }}x ₹{{ number_format($ticket->seat_price, 0) }}</div>
                            </td>
                            <td class="px-4 py-4 text-[13px] font-bold text-rose-500 text-right">
                                ₹{{ number_format($ticket->commission_amount, 2) }} 
                                <span class="text-[10px] text-gray-400">({{ $ticket->commission_percentage + 0 }}%)</span>
                            </td>
                            <td class="px-4 py-4 text-[14px] font-black text-green-600 text-right">₹{{ number_format($ticket->net_amount, 2) }}</td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('agent-tickets.edit', $ticket->id) }}" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-[#f0b44b] hover:text-[#1c2238] text-gray-600 rounded-none transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen text-[12px]"></i>
                                    </a>
                                    <form action="{{ route('agent-tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this agent ticket?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-rose-500 hover:text-white text-gray-600 rounded-none transition-colors" title="Delete">
                                            <i class="fa-solid fa-trash text-[12px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400 text-[13px]">
                                <i class="fa-solid fa-inbox text-3xl mb-3 text-gray-300"></i><br>
                                No agent sales recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
