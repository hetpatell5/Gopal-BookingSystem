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

        // Load the bus and its booked passengers FOR THIS SPECIFIC DATE
        $passengers = $bus->passengers()->whereDate('journey_date', $selectedDate)->get();
        
        $bookedSeats = [];
        $passengerData = [];

        foreach ($passengers as $passenger) {
            // Split seat numbers if multiple were booked together (e.g. "1, 2, 5")
            $seats = array_map('trim', explode(',', $passenger->seat_number));
            foreach ($seats as $seat) {
                if (!empty($seat)) {
                    $bookedSeats[] = $seat;
                    $passengerData[$seat] = $passenger;
                }
            }
        }

        // Accounting Data for this bus
        $totalRevenue = $passengers->sum('total_amount');
        $totalCommission = $passengers->sum('commission_amount');
        $totalPayable = $passengers->sum('payable_amount');
        $totalSeatsSold = $passengers->sum('total_seats');

        return view('buses.show', compact(
            'bus', 
            'passengers', 
            'bookedSeats', 
            'passengerData',
            'totalRevenue',
            'totalCommission',
            'totalPayable',
            'totalSeatsSold',
            'selectedDate'
        ));
    }
}
