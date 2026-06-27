<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Ticket - {{ $ticket->passenger_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .ticket-wrapper {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        @media print {
            body { background-color: white; margin: 0; padding: 0; }
            .ticket-wrapper { box-shadow: none; margin: 0; padding: 20px; max-width: 100%; border: none; }
            .no-print { display: none !important; }
        }
        .dotted-border { border-bottom: 2px dashed #e5e7eb; }
    </style>
</head>
<body>

    <div class="text-center mt-8 no-print">
        <button onclick="window.print()" class="px-8 py-3 bg-[#1c2238] text-white font-bold rounded-lg shadow-md hover:bg-[#2a3454] transition-colors">
            <i class="fa-solid fa-print mr-2"></i> Print This Ticket
        </button>
    </div>

    <div class="ticket-wrapper relative border border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-center pb-6 dotted-border mb-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-[#1c2238] flex items-center justify-center text-[#f0b44b] mr-4">
                    <i class="fa-solid fa-bus text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#1c2238] tracking-tight">SETU TRAVELS</h1>
                    <p class="text-sm font-medium text-gray-500">Official Boarding Pass</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">PNR / Ticket ID</div>
                <div class="text-xl font-black text-[#1c2238]">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <!-- Main Info -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Passenger Name</p>
                <p class="text-[16px] font-bold text-[#1c2238]">{{ $ticket->passenger_name }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Mobile</p>
                <p class="text-[16px] font-bold text-[#1c2238]">{{ $ticket->passenger_mobile ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Journey Date</p>
                <p class="text-[16px] font-bold text-[#1c2238]">{{ \Carbon\Carbon::parse($ticket->journey_date)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Departure Time</p>
                <p class="text-[16px] font-bold text-[#1c2238]">{{ $ticket->bus_time ? \Carbon\Carbon::parse($ticket->bus_time)->format('h:i A') : 'N/A' }}</p>
            </div>
        </div>

        <!-- Bus & Seat Info -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Bus Service</p>
                <p class="text-[15px] font-bold text-[#1c2238]">{{ $ticket->bus->name }}</p>
                <p class="text-[12px] font-medium text-gray-500">{{ $ticket->bus->plate_number }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Seat Number(s)</p>
                <p class="text-[20px] font-black text-[#f0b44b]">{{ $ticket->seat_number }}</p>
                <p class="text-[12px] font-medium text-gray-500">{{ $ticket->ac_type }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pickup Stop</p>
                <p class="text-[15px] font-bold text-[#1c2238]">{{ $ticket->pickup_stop ?: 'Main Office' }}</p>
                <p class="text-[12px] font-medium text-gray-500">Boarding Point</p>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="flex justify-between items-end pt-6 dotted-border border-t-0">
            <div>
                <p class="text-[12px] font-medium text-gray-500 mb-1">Booked on: {{ $ticket->created_at->format('d M Y, h:i A') }}</p>
                @if($ticket->traveler_name)
                <p class="text-[12px] font-medium text-gray-500">Booked via: {{ $ticket->traveler_name }}</p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Amount Paid</p>
                <p class="text-3xl font-black text-green-600">₹{{ number_format($ticket->total_amount, 2) }}</p>
            </div>
        </div>

        <!-- Footer terms -->
        <div class="mt-8 text-center text-[10px] text-gray-400 font-medium">
            This is a computer-generated ticket. Please arrive at the pickup location 15 minutes before departure. Tickets are non-refundable 24 hours prior to journey.
        </div>
    </div>

</body>
</html>
