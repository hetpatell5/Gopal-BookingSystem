<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Bus;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::all();
        return view('buses.index', compact('buses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:buses',
        ]);

        $bus = Bus::create([
            'name' => $request->name,
            'plate_number' => $request->plate_number,
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

        // Accounting Data for this bus map
        $totalRevenue = $allPassengersForDate->sum('total_amount');
        $totalCommission = $allPassengersForDate->sum('commission_amount');
        $totalPayable = $allPassengersForDate->sum('payable_amount');
        $totalSeatsSold = $allPassengersForDate->sum('total_seats');

        // Query for the passenger table
        $query = $bus->passengers()->whereDate('journey_date', $selectedDate);

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

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $validSortColumns = ['seat_number', 'passenger_name', 'total_amount', 'payable_amount', 'created_at'];
        
        if (in_array($sortBy, $validSortColumns) && in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $passengers = $query->paginate(50)->withQueryString();

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
            'sortDir'
        ));
    }

    public function printRegister(Request $request, Bus $bus)
    {
        $selectedDate = $request->input('journey_date', now()->format('Y-m-d'));

        // Fetch passengers for the selected date on this bus
        $passengers = \App\Models\Passenger::where('bus_id', $bus->id)
            ->whereDate('journey_date', $selectedDate)
            ->orderBy('seat_number')
            ->get();

        $totalRevenue = $passengers->sum('total_amount');
        $totalPayable = $passengers->sum('payable_amount');
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
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:buses,plate_number,' . $bus->id,
        ]);

        $bus->update([
            'name' => $request->name,
            'plate_number' => $request->plate_number,
        ]);

        return redirect()->route('buses.index')->with('success', 'Bus updated successfully.');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('buses.index')->with('success', 'Bus deleted successfully.');
    }
}
