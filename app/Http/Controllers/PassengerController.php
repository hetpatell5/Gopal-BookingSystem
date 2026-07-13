<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Passenger;
use App\Models\Bus;

class PassengerController extends Controller
{
    public function create(Request $request)
    {
        $buses = Bus::orderBy('name', 'asc')->get();
        $selectedBusId   = $request->input('bus_id');
        $selectedDate    = $request->input('date', now()->format('Y-m-d'));
        $busTypeFilter   = $request->input('bus_type_filter', '');

        $bookedSeats  = [];
        $passengerData = [];
        $selectedBus   = null;
        $totalSeats    = 40;
        $seatLayout    = '2x2';

        if ($selectedBusId) {
            $selectedBus = Bus::find($selectedBusId);
            if ($selectedBus) {
                $totalSeats = $selectedBus->total_seats ?? 40;
                $seatLayout = $selectedBus->seat_layout ?? '2x2';
                $passengers = $selectedBus->passengers()->whereDate('journey_date', $selectedDate)->get();
                foreach ($passengers as $passenger) {
                    $seats = array_map('trim', explode(',', $passenger->seat_number));
                    foreach ($seats as $seat) {
                        if (!empty($seat)) {
                            $bookedSeats[]      = $seat;
                            $passengerData[$seat] = $passenger;
                        }
                    }
                }
            }
        }

        return view('passengers.create', compact(
            'buses', 'selectedBusId', 'selectedDate',
            'bookedSeats', 'passengerData', 'selectedBus',
            'totalSeats', 'seatLayout', 'busTypeFilter'
        ));
    }

    public function index(Request $request)
    {
        $buses = Bus::orderBy('name', 'asc')->get();
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

            'passenger_mobile' => 'nullable|string',
            'traveler_name' => 'nullable|string',
            'traveler_number_plate' => 'nullable|string',
            'ac_type' => 'required|string',
            'journey_date' => 'required|date',
            'bus_time' => 'nullable|string',
            'total_seats' => 'nullable|integer',
            'per_seat_price' => 'nullable|numeric',
            'extra_passenger_amount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'payable_amount' => 'required|numeric',
            'pickup_stop' => 'nullable|string',
            'from_place' => 'nullable|string',
            'to_place' => 'nullable|string',
            'note' => 'nullable|string',
            'commission_percentage' => 'nullable|numeric',
            'commission_amount' => 'nullable|numeric',
        ]);

        $data = $request->all();
        $data['extra_passenger_amount'] = $data['extra_passenger_amount'] ?? 0;

        $passenger = Passenger::create($data);

        // Prepare WhatsApp message if mobile number is provided
        if (!empty($passenger->passenger_mobile)) {
            $bus = Bus::find($passenger->bus_id);
            $busName = $bus ? $bus->name : 'N/A';
            $date = \Carbon\Carbon::parse($passenger->journey_date)->format('d M, Y');
            
            $adminWhatsApp = auth()->check() && !empty(auth()->user()->whatsapp_number) ? "\n*Support:* " . auth()->user()->whatsapp_number : "";

            $pickupLocation = $passenger->pickup_stop ?: ($passenger->from_place ?: 'N/A');
            $busTime = $passenger->bus_time ?: 'N/A';
            $bakiPayment = $passenger->payable_amount ?: '0';

            $message = "*TICKET CONFIRMATION*\n\n"
                     . "Passenger Name: {$passenger->passenger_name}\n"
                     . "Bus Name: {$busName}\n"
                     . "Seat No: {$passenger->seat_number}\n"
                     . "Date: {$date}\n"
                     . "Pick-up Location: {$pickupLocation}\n"
                     . "Pickup Time: {$busTime}\n"
                     . "Baki Payment: Rs {$bakiPayment}\n\n"
                     . "Office Name: Jay Gopal Travels\n"
                     . "Mobile Number: 9904172734";
            
            // Clean mobile number (assuming India +91 default)
            $mobile = preg_replace('/[^0-9]/', '', $passenger->passenger_mobile);
            if (strlen($mobile) == 10) {
                $mobile = '91' . $mobile;
            }
            
            $whatsappLink = "https://api.whatsapp.com/send?phone={$mobile}&text=" . urlencode($message);
            
            return redirect()->back()->with([
                'success' => 'Seat booked successfully!',
                'whatsapp_link' => $whatsappLink
            ]);
        }

        return redirect()->back()->with('success', 'Seat booked successfully.');
    }

    public function edit(Passenger $passenger)
    {
        $buses = Bus::orderBy('name', 'asc')->get();
        $selectedBus = $passenger->bus;
        
        $totalSeats = $selectedBus->total_seats ?? 40;
        $seatLayout = $selectedBus->seat_layout ?? '2x2';
        
        $bookedSeats = [];
        if ($selectedBus) {
            $otherPassengers = $selectedBus->passengers()
                ->whereDate('journey_date', $passenger->journey_date)
                ->where('id', '!=', $passenger->id)
                ->get();
                
            foreach ($otherPassengers as $p) {
                $seats = array_map('trim', explode(',', $p->seat_number));
                foreach ($seats as $seat) {
                    if (!empty($seat)) {
                        $bookedSeats[] = $seat;
                    }
                }
            }
        }
        
        $currentSeats = array_map('trim', explode(',', $passenger->seat_number));

        return view('passengers.edit', compact(
            'passenger', 'buses', 'selectedBus', 'totalSeats', 'seatLayout', 'bookedSeats', 'currentSeats'
        ));
    }

    public function update(Request $request, Passenger $passenger)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'seat_number' => 'required|string',
            'passenger_name' => 'required|string',

            'passenger_mobile' => 'nullable|string',
            'traveler_name' => 'nullable|string',
            'traveler_number_plate' => 'nullable|string',
            'ac_type' => 'required|string',
            'journey_date' => 'required|date',
            'bus_time' => 'nullable|string',
            'total_seats' => 'nullable|integer',
            'per_seat_price' => 'nullable|numeric',
            'extra_passenger_amount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'payable_amount' => 'required|numeric',
            'pickup_stop' => 'nullable|string',
            'from_place' => 'nullable|string',
            'to_place' => 'nullable|string',
            'note' => 'nullable|string',
            'commission_percentage' => 'nullable|numeric',
            'commission_amount' => 'nullable|numeric',
        ]);

        $data = $request->all();
        $data['extra_passenger_amount'] = $data['extra_passenger_amount'] ?? 0;

        $passenger->update($data);

        return redirect()->route('passengers.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Passenger $passenger)
    {
        $passenger->delete();
        return redirect()->route('passengers.index')->with('success', 'Passenger booking cancelled/removed successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'passenger_ids' => 'required|array',
            'passenger_ids.*' => 'exists:passengers,id'
        ]);

        Passenger::whereIn('id', $request->passenger_ids)->delete();

        return redirect()->route('passengers.index')->with('success', count($request->passenger_ids) . ' passenger(s) removed successfully.');
    }

    public function toggleHisab(Request $request, Passenger $passenger)
    {
        $request->validate([
            'is_completed' => 'required|boolean'
        ]);

        $passenger->update([
            'is_hisab_completed' => $request->is_completed
        ]);

        return response()->json([
            'success' => true,
            'is_hisab_completed' => $passenger->is_hisab_completed
        ]);
    }

    public function printRegister(Request $request)
    {
        $selectedDate = $request->input('date', now()->format('Y-m-d'));
        
        $query = Passenger::with('bus')
            ->whereDate('journey_date', $selectedDate)
            ->orderBy('bus_id')
            ->orderBy('seat_number');

        $bus = null;
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
            $bus = Bus::find($request->bus_id);
        }

        $passengers = $query->get();

        $totalRevenue = $passengers->sum('total_amount');
        $totalPayable = $passengers->sum('payable_amount');
        $totalCommission = $passengers->sum('commission_amount');

        return view('passengers.register', compact('passengers', 'selectedDate', 'totalRevenue', 'totalPayable', 'totalCommission', 'bus'));
    }

    public function updatePayment(Request $request, Passenger $passenger)
    {
        $passenger->update([
            'payment_method'       => $request->input('payment_method'),
            'payment_collected_by' => $request->input('payment_collected_by'),
        ]);

        return response()->json(['success' => true]);
    }
}
