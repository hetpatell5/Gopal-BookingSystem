<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bus Hisab - {{ $bus->name }}</title>
    <style>
        @page { margin: 15px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #000; margin: 0; padding: 0; }
        .header { margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; }
        .header h1 { margin: 0 0 2px 0; font-size: 16px; color: #000; text-transform: uppercase; }
        .header p { margin: 0; font-size: 11px; color: #333; }
        
        .meta { width: 100%; margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .meta table { width: 100%; border-collapse: collapse; }
        .meta td { font-size: 10px; color: #333; }
        
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.data th, table.data td { padding: 5px; border: 1px solid #000; text-align: left; }
        table.data th { background-color: #f5f5f5; color: #000; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        table.data td { font-size: 10px; }
        
        .r { text-align: right !important; }
        .c { text-align: center !important; }
        .t-b { font-weight: bold; }
        
        .total-row td { background-color: #eaeaea; font-weight: bold; font-size: 11px; }
        
        .summary-box { width: 40%; float: right; margin-top: 10px; }
        .summary-box table { width: 100%; border-collapse: collapse; }
        .summary-box td { padding: 4px; border: 1px solid #000; font-size: 11px; }
        .summary-box td.label { background-color: #f5f5f5; font-weight: bold; width: 60%; }
        
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $bus->name }} - HISAB REPORT</h1>
        <p>Type: <strong>{{ $bus->bus_type }}</strong> | Plate: <strong>{{ $bus->plate_number }}</strong></p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td><strong>Date Range:</strong> 
                    @if($request->filled('date_from') && $request->filled('date_to'))
                        {{ \Carbon\Carbon::parse($request->date_from)->format('d M Y') }} to {{ \Carbon\Carbon::parse($request->date_to)->format('d M Y') }}
                    @elseif($request->filled('date_from'))
                        From {{ \Carbon\Carbon::parse($request->date_from)->format('d M Y') }}
                    @elseif($request->filled('date_to'))
                        Up to {{ \Carbon\Carbon::parse($request->date_to)->format('d M Y') }}
                    @else
                        All Time
                    @endif
                </td>
                <td class="r"><strong>Generated On:</strong> {{ date('d M Y, h:i A') }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Booking Date</th>
                <th>Journey Date</th>
                <th>Passenger Name</th>
                <th class="c">Seats</th>
                <th class="r">Rate</th>
                <th class="r">Gross Revenue</th>
                <th class="r">Advance Paid</th>
                <th class="r">Pending (Baki)</th>
                <th class="c">Status</th>
                @if($bus->bus_type === 'Commission')
                <th class="r">Commission</th>
                <th class="r">Net to Owner</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            @php
                $pend = $booking->total_amount - $booking->payable_amount;
                $net = $booking->total_amount - $booking->commission_amount;
            @endphp
            <tr>
                <td>{{ $booking->created_at->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->journey_date)->format('d M Y') }}</td>
                <td class="t-b">{{ $booking->passenger_name }}</td>
                <td class="c">{{ $booking->total_seats }} <span style="font-size: 9px; color: #444;">({{ $booking->seat_number }})</span></td>
                <td class="r" style="color: #666;">₹{{ number_format($booking->per_seat_price ?? 0, 2) }}</td>
                <td class="r">₹{{ number_format($booking->total_amount, 2) }}</td>
                <td class="r">₹{{ number_format($booking->payable_amount, 2) }}</td>
                <td class="r">₹{{ number_format($pend, 2) }}</td>
                <td class="c" style="color: {{ $booking->is_hisab_completed ? 'green' : 'red' }}; font-weight: bold; font-size: 9px;">
                    {{ $booking->is_hisab_completed ? 'PAID' : 'UNPAID' }}
                </td>
                @if($bus->bus_type === 'Commission')
                <td class="r">₹{{ number_format($booking->commission_amount, 2) }}</td>
                <td class="r t-b">₹{{ number_format($net, 2) }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ $bus->bus_type === 'Commission' ? '11' : '9' }}" class="c">No bookings found for the selected period.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="r">TOTAL ({{ $totals->total_bookings ?? 0 }} Bookings)</td>
                <td class="c">{{ $totals->total_seats_sold ?? 0 }}</td>
                <td class="r"></td>
                <td class="r">₹{{ number_format($totals->total_revenue ?? 0, 2) }}</td>
                <td class="r">₹{{ number_format($totals->total_advance ?? 0, 2) }}</td>
                <td class="r">₹{{ number_format($totals->total_pending ?? 0, 2) }}</td>
                <td class="c"></td>
                @if($bus->bus_type === 'Commission')
                <td class="r">₹{{ number_format($totals->total_commission ?? 0, 2) }}</td>
                <td class="r">₹{{ number_format($totals->total_net_revenue ?? 0, 2) }}</td>
                @endif
            </tr>
        </tfoot>
    </table>

    <div class="clearfix">
        <div class="summary-box">
            <table>
                <tr><td class="label">Gross Revenue:</td><td class="r t-b">₹{{ number_format($totals->total_revenue ?? 0, 2) }}</td></tr>
                <tr><td class="label">Advance Collected:</td><td class="r t-b">₹{{ number_format($totals->total_advance ?? 0, 2) }}</td></tr>
                <tr><td class="label">Pending Amount:</td><td class="r t-b">₹{{ number_format($totals->total_pending ?? 0, 2) }}</td></tr>
                @if($bus->bus_type === 'Commission')
                <tr><td class="label">Commission Total:</td><td class="r t-b">₹{{ number_format($totals->total_commission ?? 0, 2) }}</td></tr>
                <tr><td class="label">Net Owner Revenue:</td><td class="r t-b" style="font-size: 13px;">₹{{ number_format($totals->total_net_revenue ?? 0, 2) }}</td></tr>
                @endif
            </table>
        </div>
    </div>

</body>
</html>
