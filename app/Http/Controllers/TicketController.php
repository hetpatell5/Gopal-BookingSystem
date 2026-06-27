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
}
