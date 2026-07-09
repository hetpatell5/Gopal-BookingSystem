<?php

namespace App\Http\Controllers;

use App\Models\AgentTicket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgentTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = AgentTicket::query();

        if ($request->filled('date')) {
            $query->whereDate('sale_date', $request->date);
        }

        if ($request->filled('agent_name')) {
            $query->where('agent_name', 'like', '%' . $request->agent_name . '%');
        }

        if ($request->filled('bus_name')) {
            $query->where('bus_name', 'like', '%' . $request->bus_name . '%');
        }

        $sortField = $request->input('sort', 'sale_date');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSorts = ['sale_date', 'agent_name', 'bus_name', 'total_amount', 'commission_amount', 'net_amount'];
        
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
            if ($sortField !== 'created_at') {
                 $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('sale_date', 'desc')->orderBy('created_at', 'desc');
        }

        $tickets = $query->get();
            
        // Calculate totals for the summary cards
        $totalCommission = $tickets->sum('commission_amount');
        $totalNetRevenue = $tickets->sum('net_amount');
        $totalSeats = $tickets->sum('total_seats');
        
        return view('personal-accounts.agent-tickets-index', compact('tickets', 'totalCommission', 'totalNetRevenue', 'totalSeats'));
    }

    public function create()
    {
        return view('personal-accounts.agent-tickets-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'agent_name' => 'required|string|max:255',
            'bus_name' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'seat_price' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'commission_percentage' => 'required|numeric',
            'commission_amount' => 'required|numeric',
            'net_amount' => 'required|numeric',
        ]);

        AgentTicket::create($request->all());

        return redirect()->route('agent-tickets.index')->with('success', 'Agent ticket commission recorded successfully!');
    }

    public function edit($id)
    {
        $ticket = AgentTicket::findOrFail($id);
        return view('personal-accounts.agent-tickets-edit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'agent_name' => 'required|string|max:255',
            'bus_name' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'seat_price' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'commission_percentage' => 'required|numeric',
            'commission_amount' => 'required|numeric',
            'net_amount' => 'required|numeric',
        ]);

        $ticket = AgentTicket::findOrFail($id);
        $ticket->update($request->all());

        return redirect()->route('agent-tickets.index')->with('success', 'Agent ticket updated successfully!');
    }

    public function destroy($id)
    {
        $ticket = AgentTicket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('agent-tickets.index')->with('success', 'Agent ticket deleted successfully!');
    }
}
