<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Accounting Ledger</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1c2238;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .filters {
            margin-bottom: 20px;
            font-size: 11px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-size: 11px;
            text-transform: uppercase;
            color: #444;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .text-red { color: #e11d48; }
        .text-green { color: #059669; }
        .text-blue { color: #2563eb; }
        .bg-gray { background-color: #f1f5f9; }
        .summary-box {
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f8f9fa;
            width: 50%;
            float: right;
        }
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .summary-label {
            display: table-cell;
            font-weight: bold;
        }
        .summary-value {
            display: table-cell;
            text-align: right;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #1c2238;
            border-bottom: 2px solid #f0b44b;
            padding-bottom: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bus Accounting Ledger</h1>
        <p>Statement of Revenue, Advance, and Commission</p>
    </div>

    <div class="filters">
        <strong>Date Range:</strong> 
        {{ $request->date_from ? date('d-m-Y', strtotime($request->date_from)) : 'Beginning' }} 
        to 
        {{ $request->date_to ? date('d-m-Y', strtotime($request->date_to)) : 'Present' }}
        <br>
        <strong>Bus Type:</strong> {{ ucfirst($request->bus_type) ?: 'All' }}
    </div>

    <!-- Main Ledger Table -->
    <table>
        <thead>
            <tr>
                <th>Bus Name</th>
                <th>Type</th>
                <th class="text-center">Bookings</th>
                <th class="text-center">Seats</th>
                <th class="text-right">Gross Rev.</th>
                <th class="text-right">Advance</th>
                <th class="text-right">Pending</th>
                @if($request->bus_type !== 'Personal')
                <th class="text-right">Commission</th>
                @endif
                <th class="text-right">Net Revenue</th>
            </tr>
        </thead>
        <tbody>
            @php
                $gBookings = 0;
                $gSeats = 0;
                $gRev = 0;
                $gAdv = 0;
                $gPend = 0;
                $gComm = 0;
                $gNet = 0;
            @endphp
            @foreach($buses as $bus)
                @if(isset($accountingData[$bus->id]))
                    @php
                        $data = $accountingData[$bus->id];
                        $gBookings += $data->total_bookings;
                        $gSeats += $data->total_seats_sold;
                        $gRev += $data->total_revenue;
                        $gAdv += $data->total_advance;
                        $gPend += $data->total_pending;
                        $gComm += $data->total_commission;
                        $gNet += $data->total_net_revenue;
                    @endphp
                    <tr>
                        <td class="font-bold">{{ $bus->name }} <br><span style="font-size: 10px; color: #888;">{{ $bus->number_plate }}</span></td>
                        <td>{{ $bus->bus_type }}</td>
                        <td class="text-center">{{ $data->total_bookings }}</td>
                        <td class="text-center">{{ $data->total_seats_sold }}</td>
                        <td class="text-right font-bold text-green">Rs. {{ number_format($data->total_revenue, 0) }}</td>
                        <td class="text-right text-blue">Rs. {{ number_format($data->total_advance, 0) }}</td>
                        <td class="text-right text-red">Rs. {{ number_format($data->total_pending, 0) }}</td>
                        @if($request->bus_type !== 'Personal')
                        <td class="text-right text-red">{{ $bus->bus_type == 'Commission' ? 'Rs. '.number_format($data->total_commission, 0) : '-' }}</td>
                        @endif
                        <td class="text-right font-bold">Rs. {{ number_format($data->total_net_revenue, 0) }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray font-bold">
                <td colspan="2" class="text-right">GRAND TOTAL</td>
                <td class="text-center">{{ $gBookings }}</td>
                <td class="text-center">{{ $gSeats }}</td>
                <td class="text-right text-green">Rs. {{ number_format($gRev, 0) }}</td>
                <td class="text-right text-blue">Rs. {{ number_format($gAdv, 0) }}</td>
                <td class="text-right text-red">Rs. {{ number_format($gPend, 0) }}</td>
                @if($request->bus_type !== 'Personal')
                <td class="text-right text-red">Rs. {{ number_format($gComm, 0) }}</td>
                @endif
                <td class="text-right">Rs. {{ number_format($gNet, 0) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="clearfix">
        <div class="summary-box">
            <div class="section-title">Summary Breakdown</div>
            <div class="summary-row">
                <span class="summary-label">Total Gross Revenue:</span>
                <span class="summary-value font-bold text-green">Rs. {{ number_format($gRev, 0) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Advance Collected (Cash in hand):</span>
                <span class="summary-value text-blue">Rs. {{ number_format($gAdv, 0) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Pending from Passengers:</span>
                <span class="summary-value text-red">Rs. {{ number_format($gPend, 0) }}</span>
            </div>
            @if($request->bus_type !== 'Personal')
            <div class="summary-row">
                <span class="summary-label">Total Commission Deducted:</span>
                <span class="summary-value text-red">-Rs. {{ number_format($gComm, 0) }}</span>
            </div>
            @endif
            <hr style="border: 0; border-top: 1px dashed #ccc; margin: 10px 0;">
            <div class="summary-row font-bold" style="font-size: 14px;">
                <span class="summary-label">Net Payable / Revenue:</span>
                <span class="summary-value">Rs. {{ number_format($gNet, 0) }}</span>
            </div>
        </div>
    </div>
</body>
</html>
