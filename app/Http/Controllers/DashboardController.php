<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passenger;
use App\Models\Bus;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $bookingsToday = Passenger::whereDate('created_at', $today)->count();
        $revenueToday = Passenger::whereDate('created_at', $today)->sum('total_amount');
        $commissionEarned = Passenger::whereDate('created_at', $today)->sum('commission_amount');
        $activeBuses = Bus::count();

        $recentBookings = Passenger::with('bus')->orderBy('created_at', 'desc')->take(6)->get();

        // Chart Data (Last 7 Days Revenue)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('D, M d');
            $chartData[] = Passenger::whereDate('created_at', $date)->sum('total_amount');
        }

        // Today's Bus Performance
        $busPerformance = Bus::withCount(['passengers as today_bookings' => function($query) use ($today) {
            $query->whereDate('created_at', $today);
        }])->withSum(['passengers as today_revenue' => function($query) use ($today) {
            $query->whereDate('created_at', $today);
        }], 'total_amount')->get();

        return view('dashboard', compact(
            'bookingsToday',
            'revenueToday',
            'commissionEarned',
            'activeBuses',
            'recentBookings',
            'chartLabels',
            'chartData',
            'busPerformance'
        ));
    }
}
