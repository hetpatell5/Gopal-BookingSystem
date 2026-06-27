<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Passenger;
use App\Models\Bus;

class PassengerController extends Controller
{
    public function index(Request $request)
    {
        $buses = Bus::all();
        $query = Passenger::with('bus');

        // Filter by Bus
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        // Filter by Search (Name, Mobile, Seat)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('passenger_name', 'like', "%{$search}%")
                  ->orWhere('passenger_mobile', 'like', "%{$search}%")
                  ->orWhere('seat_number', 'like', "%{$search}%")
                  ->orWhere('pickup_stop', 'like', "%{$search}%");
            });
        }

        // Filter by Date (using journey_date)
        if ($request->filled('date')) {
            $query->whereDate('journey_date', $request->date);
        }

        // Filter by AC Type
        if ($request->filled('ac_type')) {
            $query->where('ac_type', $request->ac_type);
        }

        // Filter by Village Name
        if ($request->filled('village_name')) {
            $query->where('village_name', 'like', '%' . $request->village_name . '%');
        }

        // Filter by Traveler Name
        if ($request->filled('traveler_name')) {
            $query->where('traveler_name', 'like', '%' . $request->traveler_name . '%');
        }

        // Summary Stats based on filters
        $totalRevenue = (clone $query)->sum('total_amount');
        $totalCommission = (clone $query)->sum('commission_amount');
        $totalSeatsSold = (clone $query)->sum('total_seats');

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        
        $allowedSorts = ['journey_date', 'passenger_name', 'total_amount', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $passengers = $query->paginate(15)->withQueryString();

        return view('passengers.index', compact('buses', 'passengers', 'totalRevenue', 'totalCommission', 'totalSeatsSold', 'sortBy', 'sortDir'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'seat_number' => 'required|string',
            'passenger_name' => 'required|string',
            'village_name' => 'nullable|string',
            'passenger_mobile' => 'nullable|string',
            'traveler_name' => 'nullable|string',
            'traveler_number_plate' => 'nullable|string',
            'ac_type' => 'required|string',
            'journey_date' => 'required|date',
            'bus_time' => 'nullable|string',
            'total_seats' => 'nullable|integer',
            'total_amount' => 'required|numeric',
            'payable_amount' => 'required|numeric',
            'pickup_stop' => 'nullable|string',
            'note' => 'nullable|string',
            'commission_percentage' => 'nullable|numeric',
            'commission_amount' => 'nullable|numeric',
        ]);

        Passenger::create($request->all());

        return redirect()->back()->with('success', 'Seat booked successfully.');
    }

    public function edit(Passenger $passenger)
    {
        $buses = Bus::all();
        return view('passengers.edit', compact('passenger', 'buses'));
    }

    public function update(Request $request, Passenger $passenger)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'seat_number' => 'required|string',
            'passenger_name' => 'required|string',
            'village_name' => 'nullable|string',
            'passenger_mobile' => 'nullable|string',
            'traveler_name' => 'nullable|string',
            'traveler_number_plate' => 'nullable|string',
            'ac_type' => 'required|string',
            'journey_date' => 'required|date',
            'bus_time' => 'nullable|string',
            'total_seats' => 'nullable|integer',
            'total_amount' => 'required|numeric',
            'payable_amount' => 'required|numeric',
            'pickup_stop' => 'nullable|string',
            'note' => 'nullable|string',
            'commission_percentage' => 'nullable|numeric',
            'commission_amount' => 'nullable|numeric',
        ]);

        $passenger->update($request->all());

        return redirect()->route('passengers.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Passenger $passenger)
    {
        $passenger->delete();
        return back()->with('success', 'Passenger booking cancelled/removed successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'passenger_ids' => 'required|array',
            'passenger_ids.*' => 'exists:passengers,id'
        ]);

        Passenger::whereIn('id', $request->passenger_ids)->delete();

        return back()->with('success', count($request->passenger_ids) . ' passenger(s) removed successfully.');
    }
}
