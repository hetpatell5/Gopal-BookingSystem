<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day-to-Day Register - {{ $bus ? $bus->name : 'All Buses' }} ({{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }})</title>
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
        <h1>શ્રી હરિકૃષ્ણ (Shree Harikrishna) - {{ $bus ? $bus->name : 'All Buses (Day-to-Day)' }}</h1>
        <div class="meta-info">
            <div>તારીખ (Date): {{ \Carbon\Carbon::parse($selectedDate)->format('d / m / Y') }}</div>
            <div>વાર (Day): {{ \Carbon\Carbon::parse($selectedDate)->format('l') }}</div>
            <div>ગાડી નંબર (Bus No.): {{ $bus ? $bus->plate_number : 'Multiple' }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                @if(!$bus)
                <th width="8%">બસ<br>(Bus)</th>
                @endif
                <th width="5%">સીટ નં.<br>(Seat)</th>
                <th width="12%">રૂટ<br>(Route)</th>
                <th width="20%">નામ<br>(Name)</th>
                <th width="12%">ફોન નં.<br>(Phone)</th>
                <th width="12%">ટ્રાવેલ્સ<br>(Travels)</th>
                <th width="8%">ગા. નંબર<br>(AC/Non)</th>
                <th width="8%">સીટ જમા<br>(Amount)</th>
                <th width="8%">જમા<br>(Deposit)</th>
                <th width="8%">બાકી<br>(Pending)</th>
                <th width="7%">નોંધ<br>(Note)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($passengers as $passenger)
            <tr>
                @if(!$bus)
                <td>
                    {{ $passenger->bus ? $passenger->bus->name : '-' }}<br>
                    <span style="font-size: 10px; color: #555;">{{ $passenger->bus ? $passenger->bus->plate_number : '' }}</span>
                </td>
                @endif
                <td><strong>{{ $passenger->seat_number }}</strong></td>
                <td class="text-left">{{ $passenger->from_place }} {{ $passenger->to_place ? ' - '.$passenger->to_place : '' }}</td>
                <td class="text-left">{{ $passenger->passenger_name }}</td>
                <td>{{ $passenger->passenger_mobile }}</td>
                <td class="text-left">
                    {{ $passenger->traveler_name ?: '-' }}
                    @if($passenger->traveler_number_plate)
                        <br><span style="font-size: 10px; color: #555;">{{ $passenger->traveler_number_plate }}</span>
                    @endif
                </td>
                <td>{{ $passenger->ac_type ?: '-' }}</td>
                <td>₹{{ $passenger->total_amount }}</td>
                <td>₹{{ $passenger->payable_amount }}</td>
                <td>₹{{ max(0, $passenger->total_amount - $passenger->payable_amount) }}</td>
                <td>{{ $passenger->note ?: '-' }}</td>
            </tr>
            @endforeach
            
            <!-- Generate empty rows to fill the rest of the A4 page -->
            @php
                $rowsPerPage = 36; // Optimized for A4 portrait
                $remainder = $passengers->count() % $rowsPerPage;
                $emptyRowsCount = ($remainder == 0 && $passengers->count() > 0) ? 0 : $rowsPerPage - $remainder;
                if ($passengers->count() == 0) $emptyRowsCount = $rowsPerPage;
            @endphp
            
            @for($i = 0; $i < $emptyRowsCount; $i++)
            <tr class="empty-row">
                @if(!$bus)
                <td></td>
                @endif
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
