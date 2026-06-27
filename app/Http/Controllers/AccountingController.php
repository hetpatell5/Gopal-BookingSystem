<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Passenger;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $query = Passenger::query();
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $buses = Bus::all();
        
        // Group by bus_id to calculate totals
        $accountingData = $query->select(
            'bus_id',
            DB::raw('COUNT(id) as total_bookings'),
            DB::raw('SUM(total_seats) as total_seats_sold'),
            DB::raw('SUM(total_amount) as total_revenue'),
            DB::raw('SUM(payable_amount) as total_payable'),
            DB::raw('SUM(commission_amount) as total_commission')
        )->groupBy('bus_id')->get()->keyBy('bus_id');

        return view('accounting.index', compact('buses', 'accountingData'));
    }
}
