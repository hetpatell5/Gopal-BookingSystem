<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Print Ticket - {{ $ticket->passenger_name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati:wght@500;700&display=swap');

        body {
            font-family: 'Noto Sans Gujarati', 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #0a5c36;
        }

        @page {
            size: 21cm 9cm landscape;
            margin: 0;
        }

        .ticket-wrapper {
            width: 21cm;
            height: 9cm;
            border-collapse: collapse;
            border: 1px solid #0a5c36;
        }

        .left-section {
            width: 15cm;
            vertical-align: top;
            border-right: 2px dashed #0a5c36;
            padding-top: 1.7cm;
            box-sizing: border-box;
        }

        .right-section {
            width: 6cm;
            vertical-align: top;
            padding-top: 1.7cm;
            box-sizing: border-box;
        }

        .middle-block {
            height: 5cm;
            width: 100%;
            border-top: 1px solid #0a5c36;
            border-bottom: 1px solid #0a5c36;
            padding: 4px 10px;
            box-sizing: border-box;
        }

        .middle-block-right {
            height: 5cm;
            width: 100%;
            border-top: 1px solid #0a5c36;
            border-bottom: 1px solid #0a5c36;
            padding: 4px 6px;
            box-sizing: border-box;
        }

        .t-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px;
        }

        .t-table-sm {
            width: 100%;
            border-collapse: separate;
            border-spacing: 3px;
        }

        .t-box {
            border: 1.5px solid #0a5c36;
            border-radius: 6px;
            padding: 3px 6px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
        }

        .t-box-sm {
            border: 1.5px solid #0a5c36;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 11px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
        }

        .t-label {
            color: #0a5c36;
        }

        .t-val {
            color: #000;
        }

        .footer-bar {
            background-color: #0a5c36;
            color: white;
            text-align: center;
            font-size: 11px;
            padding: 4px;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <table class="ticket-wrapper">
        <tr>
            <td class="left-section">
                <div class="middle-block">
                    <table class="t-table">
                        <tr>
                            <td class="t-box" style="width: 70%;">
                                <span class="t-label">નામ :</span>
                                <span class="t-val">{{ $ticket->passenger_name }}</span>
                            </td>
                            <td class="t-box" style="width: 30%;">
                                <span class="t-label">તા.:</span>
                                <span
                                    class="t-val">{{ \Carbon\Carbon::parse($ticket->journey_date)->format('d/m/Y') }}</span>
                            </td>
                        </tr>
                    </table>

                    <table class="t-table">
                        <tr>
                            <td class="t-box" style="width: 40%;">
                                <span class="t-label">ગામ :</span>
                                <span class="t-val">{{ $ticket->to_place ?: $ticket->from_place }}</span>
                            </td>
                            <td class="t-box" style="width: 30%;">
                                <span class="t-label">કુલ સીટ :</span>
                                <span class="t-val">{{ $ticket->total_seats }}</span>
                            </td>
                            <td class="t-box" style="width: 30%;">
                                <span class="t-label">ઉ. સમય:</span>
                                <span
                                    class="t-val">{{ $ticket->bus_time ? \Carbon\Carbon::parse($ticket->bus_time)->format('h:i A') : '' }}</span>
                            </td>
                        </tr>
                    </table>

                    <table class="t-table">
                        <tr>
                            <td class="t-box" style="width: 100%;">
                                <span class="t-label">સીટ નં. :</span>
                                <span class="t-val">{{ $ticket->seat_number }}</span>
                            </td>
                        </tr>
                    </table>

                    <table class="t-table">
                        <tr>
                            <td class="t-box" style="width: 33%;">
                                <span class="t-label">ડિપોઝીટ :</span>
                                <span class="t-val">{{ $ticket->payable_amount }}</span>
                            </td>
                            <td class="t-box" style="width: 33%;">
                                <span class="t-label">બાકી :</span>
                                <span class="t-val">{{ $ticket->total_amount - $ticket->payable_amount }}</span>
                            </td>
                            <td class="t-box" style="width: 34%;">
                                <span class="t-label">કુલ રૂ.:</span>
                                <span class="t-val">{{ $ticket->total_amount }}</span>
                            </td>
                        </tr>
                    </table>

                    <table class="t-table">
                        <tr>
                            <td class="t-box" style="width: 100%;">
                                <span class="t-label">પિકઅપ :</span>
                                <span class="t-val">{{ $ticket->pickup_stop }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>

            <td class="right-section">
                <div class="middle-block-right">
                    <table class="t-table-sm">
                        <tr>
                            <td class="t-box-sm" style="text-align: right; width: 100%; border: none; padding: 0;">
                                <span class="t-val font-bold">{{ $ticket->traveler_name }}</span>
                                <span class="t-label font-bold" style="margin-left: 6px;">ટ્રાવેલ્સ</span>
                            </td>
                        </tr>
                    </table>

                    <table class="t-table-sm">
                        <tr>
                            <td class="t-box-sm" style="width: 100%;">
                                <span class="t-label">નામ:</span>
                                <span class="t-val" style="font-size: 11px;">{{ $ticket->passenger_name }}</span>
                            </td>
                        </tr>
                    </table>

                    <table class="t-table-sm">
                        <tr>
                            <td class="t-box-sm" style="width: 60%;">
                                <span class="t-label">બસ નં:</span>
                                <span class="t-val" style="font-size: 11px;">{{ $ticket->bus->plate_number }}</span>
                            </td>
                            <td class="t-box-sm" style="width: 40%;">
                                <span class="t-label">તા.:</span>
                                <span class="t-val"
                                    style="font-size: 11px;">{{ \Carbon\Carbon::parse($ticket->journey_date)->format('d/m') }}</span>
                            </td>
                        </tr>
                    </table>

                    <table class="t-table-sm">
                        <tr>
                            <td class="t-box-sm" style="width: 60%;">
                                <span class="t-label">ગામ:</span>
                                <span class="t-val" style="font-size: 11px;">{{ $ticket->to_place ?: $ticket->from_place }}</span>
                            </td>
                            <td class="t-box-sm" style="width: 40%;">
                                <span class="t-label">કુલ સીટ:</span>
                                <span class="t-val" style="font-size: 11px;">{{ $ticket->total_seats }}</span>
                            </td>
                        </tr>
                    </table>

                    <table class="t-table-sm">
                        <tr>
                            <td class="t-box-sm" style="width: 100%;">
                                <span class="t-label">સીટ નં:</span>
                                <span class="t-val" style="font-size: 11px;">{{ $ticket->seat_number }}</span>
                            </td>
                        </tr>
                    </table>
                    
                    <div class="footer-bar">
                        ઓલ સૌરાષ્ટ્રનું કન્ફર્મ બુકીંગ ફોન દ્વારા મળશે.
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>