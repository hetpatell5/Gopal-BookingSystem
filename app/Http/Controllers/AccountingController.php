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

        if ($request->action === 'export_pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('accounting.pdf', compact(
                'buses', 'accountingData', 'personalData', 'commissionData', 'request'
            ));
            // Setting paper to A4 landscape for better column fit
            $pdf->setPaper('a4', 'landscape');
            
            $fileName = 'ledger_statement_';
            if ($request->filled('date_from')) $fileName .= $request->date_from . '_';
            if ($request->filled('date_to')) $fileName .= $request->date_to;
            else $fileName .= date('Y_m_d');
            
            return $pdf->download($fileName . '.pdf');
        }

        return view('accounting.index', compact(
            'buses', 'accountingData', 'personalData', 'commissionData'
        ));
    }

    public function show(Request $request, Bus $bus)
    {
        $query = Passenger::where('bus_id', $bus->id)
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'),   fn($q) => $q->whereDate('created_at', '<=', $request->date_to));

        $bookings = (clone $query)
            ->orderBy('created_at', 'desc')
            ->get();

        $totals = (clone $query)
            ->selectRaw('
                COUNT(id) as total_bookings,
                SUM(total_seats) as total_seats_sold,
                SUM(total_amount) as total_revenue,
                SUM(payable_amount) as total_advance,
                SUM(total_amount - payable_amount) as total_pending,
                SUM(commission_amount) as total_commission,
                SUM(total_amount - commission_amount) as total_net_revenue
            ')
            ->first();

        if ($request->action === 'export_pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('accounting.show_pdf', compact('bus', 'bookings', 'totals', 'request'));
            $pdf->setPaper('a4', 'portrait');
            
            $fileName = 'bus_' . str_replace(' ', '_', strtolower($bus->name)) . '_hisab_';
            if ($request->filled('date_from')) $fileName .= $request->date_from . '_';
            if ($request->filled('date_to')) $fileName .= $request->date_to;
            else $fileName .= date('Y_m_d');
            
            return $pdf->download($fileName . '.pdf');
        }

        return view('accounting.show', compact('bus', 'bookings', 'totals'));
    }
}
