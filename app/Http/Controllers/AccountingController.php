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
        // ── Base query filters ──────────────────────────────────────────────
        $baseQuery = Passenger::query()
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'),   fn($q) => $q->whereDate('created_at', '<=', $request->date_to));

        // Apply bus_type filter for ledger toggle
        $ledgerQuery = (clone $baseQuery)
            ->when($request->filled('bus_type'), fn($q) =>
                $q->whereHas('bus', fn($bq) => $bq->where('bus_type', $request->bus_type))
            );

        $buses = Bus::orderBy('bus_type')->orderBy('name')->get();

        // ── Per-bus accounting ──────────────────────────────────────────────
        // total_amount    = full ticket fare billed
        // payable_amount  = advance collected upfront
        // commission_amount = commission deducted (commission buses only)
        // pending_amount  = total_amount - payable_amount (still owed by passenger)
        // net_revenue     = total_amount - commission_amount (what owner keeps)

        $accountingData = (clone $ledgerQuery)
            ->select(
                'bus_id',
                DB::raw('COUNT(id)                                           as total_bookings'),
                DB::raw('SUM(total_seats)                                    as total_seats_sold'),
                DB::raw('SUM(total_amount)                                   as total_revenue'),       // gross billed
                DB::raw('SUM(payable_amount)                                 as total_advance'),       // advance collected
                DB::raw('SUM(total_amount - payable_amount)                  as total_pending'),       // still owed
                DB::raw('SUM(commission_amount)                              as total_commission'),    // commission
                DB::raw('SUM(total_amount - commission_amount)               as total_net_revenue')   // owner keeps
            )
            ->groupBy('bus_id')
            ->get()
            ->keyBy('bus_id');

        // ── Personal bus summary ────────────────────────────────────────────
        $personalData = (clone $baseQuery)
            ->whereHas('bus', fn($q) => $q->where('bus_type', 'Personal'))
            ->selectRaw('
                COUNT(id)                              as bookings,
                SUM(total_seats)                       as seats,
                SUM(total_amount)                      as revenue,
                SUM(payable_amount)                    as advance,
                SUM(total_amount - payable_amount)     as pending
            ')
            ->first();

        // ── Commission bus summary ──────────────────────────────────────────
        $commissionData = (clone $baseQuery)
            ->whereHas('bus', fn($q) => $q->where('bus_type', 'Commission'))
            ->selectRaw('
                COUNT(id)                              as bookings,
                SUM(total_seats)                       as seats,
                SUM(total_amount)                      as revenue,
                SUM(payable_amount)                    as advance,
                SUM(total_amount - payable_amount)     as pending,
                SUM(commission_amount)                 as commission,
                SUM(total_amount - commission_amount)  as net_revenue
            ')
            ->first();

        return view('accounting.index', compact(
            'buses', 'accountingData', 'personalData', 'commissionData'
        ));
    }
}
