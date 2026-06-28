@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Card 1 -->
    <div class="bg-white rounded-none p-6 shadow-sm border-l-4 border-l-[#f0b44b]">
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Bookings Today</h3>
        <p class="text-3xl font-bold text-[#1c2238]">{{ $bookingsToday }}</p>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-none p-6 shadow-sm border-l-4 border-l-green-500">
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Revenue Today</h3>
        <p class="text-3xl font-bold text-green-600">₹{{ number_format($revenueToday, 2) }}</p>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-none p-6 shadow-sm border-l-4 border-l-blue-500">
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Commission Earned</h3>
        <p class="text-3xl font-bold text-blue-600">₹{{ number_format($commissionEarned, 2) }}</p>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-none p-6 shadow-sm border-l-4 border-l-[#1c2238]">
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Active Buses</h3>
        <p class="text-3xl font-bold text-[#1c2238]">{{ $activeBuses }}</p>
    </div>

</div>

<!-- Charts & Performance Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Revenue Trend Chart -->
    <div class="lg:col-span-2 bg-white rounded-none p-6 shadow-sm">
        <h2 class="text-[15px] font-bold text-[#1c2238] mb-4">Revenue Trend (Last 7 Days)</h2>
        <div class="relative h-[300px] w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Today's Bus Performance -->
    <div class="bg-white rounded-none p-6 shadow-sm flex flex-col">
        <h2 class="text-[15px] font-bold text-[#1c2238] mb-4">Today's Performance by Bus</h2>
        
        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
            <div class="space-y-4">
                @forelse($busPerformance as $bus)
                <div class="border border-gray-100 rounded-none p-4 hover:border-[#f0b44b] transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="text-[13px] font-bold text-[#1c2238]">{{ $bus->name }}</h4>
                            <p class="text-[11px] text-gray-500 font-medium">{{ $bus->plate_number }}</p>
                        </div>
                        <span class="inline-flex items-center justify-center px-2 py-1 rounded-none bg-[#fff8eb] text-[#f0b44b] text-[11px] font-bold">
                            {{ $bus->today_bookings }} Seats
                        </span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-gray-50">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Revenue</span>
                        <span class="text-[14px] font-black text-green-600">₹{{ number_format($bus->today_revenue ?: 0, 2) }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400 text-sm">No active buses found.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="bg-white rounded-none shadow-sm">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
        <h2 class="text-[17px] font-bold text-[#1c2238]">Recent Bookings</h2>
        <span class="text-[13px] text-gray-500 font-medium">Last 6</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white w-24">Seat</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Passenger</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Bus</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Pickup</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white">Amount</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-widest bg-white w-32">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">{{ $booking->seat_number }}</td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">{{ $booking->passenger_name }}</td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">
                        <a href="{{ route('buses.show', $booking->bus->id) }}" class="hover:text-[#f0b44b] transition-colors">
                            {{ $booking->bus->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-[13px] text-gray-600 font-medium">{{ $booking->pickup_stop ?: '-' }}</td>
                    <td class="px-6 py-4 text-[13px] font-semibold text-[#1c2238]">₹{{ number_format($booking->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-[#e8f5ed] text-[#34a853]">
                            Booked
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-[13px]">No bookings yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(240, 180, 75, 0.4)'); // #f0b44b with opacity
        gradient.addColorStop(1, 'rgba(240, 180, 75, 0.0)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Revenue (₹)',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#f0b44b',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f0b44b',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1c2238',
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '₹ ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 11 },
                            color: '#9ca3af'
                        }
                    },
                    y: {
                        border: { dash: [4, 4] },
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 11 },
                            color: '#9ca3af',
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endsection
