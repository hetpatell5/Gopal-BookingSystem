<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day-to-Day Register - {{ $bus->name }} ({{ $selectedDate === 'All Dates' ? 'All Dates' : \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }})</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Gujarati', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #a32a3d;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #a32a3d;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        th, td {
            border: 1px solid #a32a3d;
            padding: 8px 4px;
            text-align: center;
        }
        th {
            background-color: #a32a3d;
            color: white;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
            padding-left: 8px;
        }
        .empty-row td {
            height: 25px;
        }
        .footer-summary {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 40px;
            font-weight: bold;
            font-size: 16px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            .no-print {
                display: none;
            }
        }
        .print-btn {
            background-color: #a32a3d;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn no-print">Print Register</button>

    <div class="header">
        <h1>શ્રી હરિકૃષ્ણ (Shree Harikrishna) - {{ $bus->name }}</h1>
        <div class="meta-info">
            <div>તારીખ (Date): {{ $selectedDate === 'All Dates' ? 'All Dates' : \Carbon\Carbon::parse($selectedDate)->format('d / m / Y') }}</div>
            <div>વાર (Day): {{ $selectedDate === 'All Dates' ? '-' : \Carbon\Carbon::parse($selectedDate)->format('l') }}</div>
            <div>ગાડી નંબર (Bus No.): {{ $bus->plate_number }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">સીટ નં.<br>(Seat)</th>
                <th width="11%">નામ<br>(Name)</th>
                <th width="9%">ફોન નં.<br>(Phone)</th>
                <th width="10%">રૂટ<br>(Route)</th>
                <th width="8%">મુસાફરી<br>(Journey)</th>
                <th width="8%">બુકિંગ<br>(Booked)</th>
                <th width="10%">ટ્રાવેલ્સ<br>(Travels)</th>
                <th width="6%">ગા. નંબર<br>(AC/Non)</th>
                <th width="4%">કુલ સીટ<br>(Seats)</th>
                <th width="6%">ભાવ<br>(Price)</th>
                <th width="6%">કુલ<br>(Total)</th>
                <th width="6%">જમા<br>(Adv)</th>
                <th width="6%">બાકી<br>(Baki)</th>
                <th width="6%">નોંધ<br>(Note)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($passengers as $passenger)
            <tr>
                <td><strong>{{ $passenger->seat_number }}</strong></td>
                <td class="text-left">{{ $passenger->passenger_name }}</td>
                <td>{{ $passenger->passenger_mobile }}</td>
                <td class="text-left">{{ $passenger->from_place }} {{ $passenger->to_place ? ' - '.$passenger->to_place : '' }}</td>
                <td>{{ \Carbon\Carbon::parse($passenger->journey_date)->format('d/m/Y') }}</td>
                <td>{{ $passenger->created_at->format('d/m/Y') }}</td>
                <td class="text-left">{{ $passenger->traveler_name ?: '-' }}</td>
                <td>{{ $passenger->ac_type ?: '-' }}</td>
                <td>{{ $passenger->total_seats }}</td>
                <td>₹{{ $passenger->per_seat_price }}</td>
                <td>₹{{ $passenger->total_amount }}</td>
                <td>₹{{ $passenger->payable_amount }}</td>
                <td>₹{{ max(0, $passenger->total_amount - $passenger->payable_amount) }}</td>
                <td>{{ $passenger->note ?: '-' }}</td>
            </tr>
            @endforeach
            
            <!-- Generate some empty rows for handwriting -->
            @for($i = 0; $i < (30 - $passengers->count()); $i++)
            <tr class="empty-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <div class="footer-summary">
        <div>કુલ જમા (Total Amount): ₹{{ $totalRevenue }}</div>
        <div>એડવાન્સ જમા (Total Deposit): ₹{{ $totalPayable }}</div>
    </div>

</body>
</html>
