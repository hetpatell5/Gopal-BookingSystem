<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passenger;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Passenger::with('bus');

        // Search by phone or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('passenger_name', 'like', "%{$search}%")
                  ->orWhere('passenger_mobile', 'like', "%{$search}%")
                  ->orWhere('seat_number', 'like', "%{$search}%");
            });
        }

        // Filter by date (default to today if no search is provided)
        if ($request->filled('date')) {
            $query->whereDate('journey_date', $request->date);
        } elseif (!$request->filled('search')) {
            $query->whereDate('journey_date', Carbon::today());
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        return view('tickets.index', compact('tickets'));
    }

    public function show(Passenger $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function downloadPdf(Passenger $ticket)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.pdf', compact('ticket'))->setOptions(['isRemoteEnabled' => true]);
        // 28cm x 7cm in points: 28cm = 793.688pt, 7cm = 198.422pt
        $customPaper = array(0, 0, 793.688, 198.422);
        $pdf->setPaper($customPaper, 'landscape');
        
        return $pdf->download('Ticket-' . $ticket->passenger_name . '.pdf');
    }
}
