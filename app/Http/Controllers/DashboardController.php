<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passenger;
use App\Models\Bus;
use App\Models\FormTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // ── Today's stats ──────────────────────────────────────────
        $bookingsToday    = Passenger::whereDate('created_at', $today)->count();
        $revenueToday     = Passenger::whereDate('created_at', $today)->sum('total_amount');
        $commissionEarned = Passenger::whereDate('created_at', $today)->sum('commission_amount');

        // ── Monthly stats ──────────────────────────────────────────
        $bookingsMonth = Passenger::where('created_at', '>=', $thisMonth)->count();
        $revenueMonth  = Passenger::where('created_at', '>=', $thisMonth)->sum('total_amount');

        // ── Counts ─────────────────────────────────────────────────
        $totalBuses      = Bus::count();
        $personalBuses   = Bus::where('bus_type', 'Personal')->count();
        $commissionBuses = Bus::where('bus_type', 'Commission')->count();
        $totalForms      = FormTemplate::count();
        $totalPassengers = Passenger::count();

        // ── Recent bookings ────────────────────────────────────────
        $recentBookings = Passenger::with('bus')->orderBy('created_at', 'desc')->take(8)->get();

        // ── Chart: last 7 days revenue split by bus type ───────────
        $chartLabels        = [];
        $chartPersonal      = [];
        $chartCommission    = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[]     = $date->format('D d');
            $chartPersonal[]   = Passenger::whereDate('created_at', $date)
                ->whereHas('bus', fn($q) => $q->where('bus_type', 'Personal'))
                ->sum('total_amount');
            $chartCommission[] = Passenger::whereDate('created_at', $date)
                ->whereHas('bus', fn($q) => $q->where('bus_type', 'Commission'))
                ->sum('total_amount');
        }

        // ── Bus performance ────────────────────────────────────────
        $busPerformance = Bus::withCount(['passengers as today_bookings' => function ($q) use ($today) {
                $q->whereDate('created_at', $today);
            }])
            ->withSum(['passengers as today_revenue' => function ($q) use ($today) {
                $q->whereDate('created_at', $today);
            }], 'total_amount')
            ->orderByDesc('today_bookings')
            ->get();

        return view('dashboard', compact(
            'bookingsToday', 'revenueToday', 'commissionEarned',
            'bookingsMonth', 'revenueMonth',
            'totalBuses', 'personalBuses', 'commissionBuses',
            'totalForms', 'totalPassengers',
            'recentBookings',
            'chartLabels', 'chartPersonal', 'chartCommission',
            'busPerformance'
        ));
    }
}
