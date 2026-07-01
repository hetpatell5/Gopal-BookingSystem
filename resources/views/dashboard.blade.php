@extends('layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- 1. QUICK ACCESS CARDS                                         --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Booking --}}
    <a href="{{ route('passengers.create') }}"
       class="group bg-[#f0b44b] rounded-none p-5 flex flex-col items-start gap-3 shadow-sm hover:bg-[#e0a43b] transition-all hover:shadow-md">
        <div class="w-10 h-10 bg-white/25 rounded-none flex items-center justify-center">
            <i class="fa-solid fa-plus text-white text-lg"></i>
        </div>
        <div>
            <p class="text-[13px] font-black text-white uppercase tracking-wider">New Booking</p>
            <p class="text-[11px] text-white/70 mt-0.5">Book a seat now</p>
        </div>
        <i class="fa-solid fa-arrow-right text-white/60 group-hover:translate-x-1 transition-transform ml-auto"></i>
    </a>

    {{-- Accounting --}}
    <a href="{{ route('accounting.index') }}"
       class="group bg-[#1c2238] rounded-none p-5 flex flex-col items-start gap-3 shadow-sm hover:bg-[#29324b] transition-all hover:shadow-md">
        <div class="w-10 h-10 bg-white/10 rounded-none flex items-center justify-center">
            <i class="fa-solid fa-calculator text-[#f0b44b] text-lg"></i>
        </div>
        <div>
            <p class="text-[13px] font-black text-white uppercase tracking-wider">Accounting</p>
            <p class="text-[11px] text-[#8e98ac] mt-0.5">Revenue &amp; commissions</p>
        </div>
        <i class="fa-solid fa-arrow-right text-[#8e98ac] group-hover:translate-x-1 transition-transform ml-auto"></i>
    </a>

    {{-- Buses --}}
    <a href="{{ route('buses.index') }}"
       class="group bg-white rounded-none p-5 flex flex-col items-start gap-3 shadow-sm hover:shadow-md transition-all">
        <div class="w-10 h-10 bg-[#f0b44b]/10 rounded-none flex items-center justify-center">
            <i class="fa-solid fa-bus text-[#f0b44b] text-lg"></i>
        </div>
        <div>
            <p class="text-[13px] font-black text-[#1c2238] uppercase tracking-wider">Buses</p>
            <p class="text-[11px] text-gray-400 mt-0.5">{{ $totalBuses }} total · {{ $personalBuses }} personal · {{ $commissionBuses }} commission</p>
        </div>
        <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-[#f0b44b] group-hover:translate-x-1 transition-all ml-auto"></i>
    </a>

    {{-- Forms --}}
    <a href="{{ route('forms.index') }}"
       class="group bg-white rounded-none p-5 flex flex-col items-start gap-3 shadow-sm hover:shadow-md transition-all">
        <div class="w-10 h-10 bg-[#6366f1]/10 rounded-none flex items-center justify-center">
            <i class="fa-solid fa-clipboard-list text-[#6366f1] text-lg"></i>
        </div>
        <div>
            <p class="text-[13px] font-black text-[#1c2238] uppercase tracking-wider">Forms</p>
            <p class="text-[11px] text-gray-400 mt-0.5">{{ $totalForms }} form{{ $totalForms != 1 ? 's' : '' }} created</p>
        </div>
        <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-[#6366f1] group-hover:translate-x-1 transition-all ml-auto"></i>
    </a>

</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- 2. STAT CARDS                                                 --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-none p-5 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Today's Bookings</p>
        <p class="text-3xl font-black text-[#1c2238]">{{ $bookingsToday }}</p>
        <p class="text-[12px] font-semibold text-gray-500 mt-1.5">{{ $bookingsMonth }} this month</p>
    </div>

    <div class="bg-white rounded-none p-5 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Today's Revenue</p>
        <p class="text-3xl font-black text-green-600">₹{{ number_format($revenueToday, 0) }}</p>
        <p class="text-[12px] font-semibold text-gray-500 mt-1.5">₹{{ number_format($revenueMonth, 0) }} this month</p>
    </div>

    <div class="bg-white rounded-none p-5 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Commission Earned</p>
        <p class="text-3xl font-black text-[#d97706]">₹{{ number_format($commissionEarned, 0) }}</p>
        <p class="text-[12px] font-semibold text-gray-500 mt-1.5">Today · commission buses</p>
    </div>

    <div class="bg-white rounded-none p-5 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">All Passengers</p>
        <p class="text-3xl font-black text-blue-600">{{ $totalPassengers }}</p>
        <p class="text-[12px] font-semibold text-gray-500 mt-1.5">total bookings ever</p>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- 3. CHART + BUS PERFORMANCE                                    --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Revenue chart --}}
    <div class="lg:col-span-2 bg-white rounded-none p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-[15px] font-bold text-[#1c2238]">Revenue – Last 7 Days</h2>
            <div class="flex items-center gap-4 text-[11px] font-semibold text-gray-400">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#f0b44b] inline-block"></span> Personal
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#6366f1] inline-block"></span> Commission
                </span>
            </div>
        </div>
        <div class="relative h-[260px]">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Today's bus performance --}}
    <div class="bg-white rounded-none p-5 shadow-sm flex flex-col">
        <h2 class="text-[15px] font-bold text-[#1c2238] mb-4 shrink-0">Today's Bus Activity</h2>
        <div class="flex-1 overflow-y-auto space-y-3 pr-1" style="max-height:260px;">
            @forelse($busPerformance->where('today_bookings', '>', 0) as $bus)
                <a href="{{ route('buses.show', $bus->id) }}"
                   class="flex items-center gap-3 p-3 border border-gray-100 hover:border-[#f0b44b] transition-colors group block">
                    <div class="w-8 h-8 rounded-none flex items-center justify-center shrink-0
                        {{ $bus->bus_type === 'Commission' ? 'bg-[#6366f1]/10 text-[#6366f1]' : 'bg-[#f0b44b]/10 text-[#d97706]' }}">
                        <i class="fa-solid fa-bus text-[13px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12px] font-bold text-[#1c2238] truncate group-hover:text-[#f0b44b] transition-colors">{{ $bus->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $bus->plate_number }} · {{ $bus->bus_type }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[13px] font-black text-green-600">₹{{ number_format($bus->today_revenue ?: 0, 0) }}</p>
                        <p class="text-[10px] text-gray-400">{{ $bus->today_bookings }} seats</p>
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center py-10 text-gray-300">
                    <i class="fa-solid fa-bus-simple text-3xl mb-2"></i>
                    <p class="text-[12px]">No bookings today yet</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- 4. RECENT BOOKINGS TABLE                                      --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-none shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-[15px] font-bold text-[#1c2238]">Recent Bookings</h2>
        <a href="{{ route('passengers.index') }}"
           class="text-[12px] font-bold text-[#f0b44b] hover:text-[#d97706] transition-colors">
            View all <i class="fa-solid fa-arrow-right ml-1 text-[10px]"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-[13px]">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="text-left px-5 py-3 font-bold text-[10px] uppercase tracking-widest text-gray-400">Seat</th>
                    <th class="text-left px-5 py-3 font-bold text-[10px] uppercase tracking-widest text-gray-400">Passenger</th>
                    <th class="text-left px-5 py-3 font-bold text-[10px] uppercase tracking-widest text-gray-400">Bus</th>
                    <th class="text-left px-5 py-3 font-bold text-[10px] uppercase tracking-widest text-gray-400">Type</th>
                    <th class="text-left px-5 py-3 font-bold text-[10px] uppercase tracking-widest text-gray-400">Pickup</th>
                    <th class="text-left px-5 py-3 font-bold text-[10px] uppercase tracking-widest text-gray-400">Amount</th>
                    <th class="text-left px-5 py-3 font-bold text-[10px] uppercase tracking-widest text-gray-400">Commission</th>
                    <th class="text-left px-5 py-3 font-bold text-[10px] uppercase tracking-widest text-gray-400">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5 font-bold text-[#1c2238]">{{ $booking->seat_number }}</td>
                    <td class="px-5 py-3.5 text-gray-700 font-medium">{{ $booking->passenger_name }}</td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('buses.show', $booking->bus->id) }}"
                           class="text-gray-600 hover:text-[#f0b44b] transition-colors font-medium">
                            {{ $booking->bus->name }}
                        </a>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-full
                            {{ $booking->bus->bus_type === 'Commission' ? 'bg-[#6366f1]/10 text-[#6366f1]' : 'bg-[#f0b44b]/10 text-[#d97706]' }}">
                            {{ $booking->bus->bus_type }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-500">{{ $booking->pickup_stop ?: '—' }}</td>
                    <td class="px-5 py-3.5 font-bold text-[#1c2238]">₹{{ number_format($booking->total_amount, 0) }}</td>
                    <td class="px-5 py-3.5">
                        @if($booking->commission_amount > 0)
                            <span class="text-[#d97706] font-bold">₹{{ number_format($booking->commission_amount, 0) }}</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-gray-400 text-[11px] whitespace-nowrap">
                        {{ $booking->created_at->format('d M, H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-400 text-[13px]">
                        <i class="fa-solid fa-ticket text-2xl mb-2 block text-gray-200"></i>
                        No bookings yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    const gradientPersonal = ctx.createLinearGradient(0, 0, 0, 260);
    gradientPersonal.addColorStop(0, 'rgba(240,180,75,0.35)');
    gradientPersonal.addColorStop(1, 'rgba(240,180,75,0)');

    const gradientCommission = ctx.createLinearGradient(0, 0, 0, 260);
    gradientCommission.addColorStop(0, 'rgba(99,102,241,0.25)');
    gradientCommission.addColorStop(1, 'rgba(99,102,241,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Personal (₹)',
                    data: {!! json_encode($chartPersonal) !!},
                    borderColor: '#f0b44b',
                    backgroundColor: gradientPersonal,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f0b44b',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Commission (₹)',
                    data: {!! json_encode($chartCommission) !!},
                    borderColor: '#6366f1',
                    backgroundColor: gradientCommission,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1c2238',
                    padding: 12,
                    titleFont: { size: 12 },
                    bodyFont: { size: 13, weight: 'bold' },
                    displayColors: true,
                    callbacks: {
                        label: ctx => ' ₹' + ctx.parsed.y.toLocaleString()
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#9ca3af', font: { size: 11 } }
                },
                y: {
                    border: { dash: [4, 4] },
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 11 },
                        callback: v => '₹' + v.toLocaleString()
                    }
                }
            }
        }
    });
});
</script>
@endsection
