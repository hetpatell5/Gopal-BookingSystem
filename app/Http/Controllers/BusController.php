<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Bus;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::orderBy('name', 'asc')->get();
        return view('buses.index', compact('buses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'bus_type'     => 'required|in:Personal,Commission',
            'total_seats'  => 'required|integer|min:1|max:60',
            'ac_non_ac'    => 'required|in:AC,Non AC',
            'seat_layout'  => 'required|in:1x2,2x2',
        ]);

        $bus = Bus::create([
            'name'         => $request->name,
            'plate_number' => 'PN-' . strtoupper(uniqid()),
            'bus_type'     => $request->bus_type,
            'total_seats'  => $request->total_seats,
            'ac_non_ac'    => $request->ac_non_ac,
            'seat_layout'  => $request->seat_layout,
        ]);

        return redirect()->route('buses.show', $bus->id)->with('success', 'Bus added successfully.');
    }

    public function show(Request $request, Bus $bus)
    {
        // Determine the date to show (default to today)
        $selectedDate = $request->input('date', \Carbon\Carbon::today()->format('Y-m-d'));

        // Load ALL passengers for the bus map for this specific date
        $allPassengersForDate = $bus->passengers()->whereDate('journey_date', $selectedDate)->get();
        
        $bookedSeats = [];
        $passengerData = [];

        foreach ($allPassengersForDate as $passenger) {
            // Split seat numbers if multiple were booked together (e.g. "1, 2, 5")
            $seats = array_map('trim', explode(',', $passenger->seat_number));
            foreach ($seats as $seat) {
                if (!empty($seat)) {
                    $bookedSeats[] = $seat;
                    $passengerData[$seat] = $passenger;
                }
            }
        }

        // Query for the passenger table (Show all bookings for this bus by default)
        $query = $bus->passengers();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('passenger_name', 'like', "%{$search}%")
                  ->orWhere('passenger_mobile', 'like', "%{$search}%")
                  ->orWhere('seat_number', 'like', "%{$search}%")
                  ->orWhere('pickup_stop', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ac_type')) {
            $query->where('ac_type', $request->ac_type);
        }

        if ($request->filled('filter_date')) {
            $query->whereDate('journey_date', $request->filter_date);
        }

        // Accounting Data based on filtered query
        $filteredStats = (clone $query)->get();
        $totalRevenue    = $filteredStats->sum('total_amount');
        $totalCommission = $filteredStats->sum('commission_amount');
        $totalPayable    = $filteredStats->sum('payable_amount');
        $totalSeatsSold  = $filteredStats->sum('total_seats');

        // Sorting
        $sortBy  = $request->input('sort_by', 'journey_date');
        $sortDir = $request->input('sort_dir', 'desc');
        $validSortColumns = ['seat_number', 'passenger_name', 'total_amount', 'payable_amount', 'created_at', 'journey_date'];
        
        if (in_array($sortBy, $validSortColumns) && in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $passengers = $query->paginate(50)->withQueryString();

        // Build seat list dynamically
        $totalSeats  = $bus->total_seats  ?? 40;
        $seatLayout  = $bus->seat_layout  ?? '2x2';

        return view('buses.show', compact(
            'bus', 
            'passengers', 
            'bookedSeats', 
            'passengerData',
            'totalRevenue',
            'totalCommission',
            'totalPayable',
            'totalSeatsSold',
            'selectedDate',
            'sortBy',
            'sortDir',
            'totalSeats',
            'seatLayout'
        ));
    }

    public function printRegister(Request $request, Bus $bus)
    {
        $query = $bus->passengers();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('passenger_name', 'like', "%{$search}%")
                  ->orWhere('passenger_mobile', 'like', "%{$search}%")
                  ->orWhere('seat_number', 'like', "%{$search}%")
                  ->orWhere('pickup_stop', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ac_type')) {
            $query->where('ac_type', $request->ac_type);
        }

        if ($request->filled('filter_date')) {
            $query->whereDate('journey_date', $request->filter_date);
            $selectedDate = \Carbon\Carbon::parse($request->filter_date)->format('d M, Y');
        } else {
            $selectedDate = 'All Dates';
        }

        // Sorting
        $sortBy  = $request->input('sort_by', 'journey_date');
        $sortDir = $request->input('sort_dir', 'desc');
        $validSortColumns = ['seat_number', 'passenger_name', 'total_amount', 'payable_amount', 'created_at', 'journey_date'];
        
        if (in_array($sortBy, $validSortColumns) && in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('journey_date', 'desc');
        }

        $passengers = $query->get();

        $totalRevenue    = $passengers->sum('total_amount');
        $totalPayable    = $passengers->sum('payable_amount');
        $totalCommission = $passengers->sum('commission_amount');

        return view('buses.register', compact('bus', 'passengers', 'selectedDate', 'totalRevenue', 'totalPayable', 'totalCommission'));
    }

    public function edit(Bus $bus)
    {
        return view('buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'bus_type'     => 'required|in:Personal,Commission',
            'total_seats'  => 'required|integer|min:1|max:60',
            'ac_non_ac'    => 'required|in:AC,Non AC',
            'seat_layout'  => 'required|in:1x2,2x2',
        ]);

        $bus->update([
            'name'         => $request->name,
            'bus_type'     => $request->bus_type,
            'total_seats'  => $request->total_seats,
            'ac_non_ac'    => $request->ac_non_ac,
            'seat_layout'  => $request->seat_layout,
        ]);

        return redirect()->route('buses.index')->with('success', 'Bus updated successfully.');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('buses.index')->with('success', 'Bus deleted successfully.');
    }
}
