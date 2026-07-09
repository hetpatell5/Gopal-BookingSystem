@extends('layouts.app')

@section('title', 'Print Ticket - ' . $ticket->passenger_name)
@section('header', 'Ticket Preview')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati:wght@500;700&display=swap');

        .ticket-container {
            font-family: 'Noto Sans Gujarati', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: transparent;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .ticket-wrapper {
            width: 21cm;
            height: 9cm;
            background: white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            box-sizing: border-box;
            color: #0a5c36;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        /* Print styles */
        @media print {
            @page {
                size: 21cm 9cm landscape;
                margin: 0;
            }

            body * {
                visibility: hidden;
            }

            .ticket-container,
            .ticket-container * {
                visibility: visible;
            }

            .ticket-container {
                position: absolute;
                left: 0;
                top: 0;
                margin: 0;
                padding: 0;
            }

            .ticket-wrapper {
                box-shadow: none;
                border: 1px solid #0a5c36 !important;
                margin: 0;
                width: 21cm;
                height: 9cm;
                page-break-after: always;
            }

            .left-section {
                border-right: 2px dashed #0a5c36 !important;
            }

            .t-box {
                border: 1.5px solid #0a5c36 !important;
            }
            
            .t-box-sm {
                border: 1.5px solid #0a5c36 !important;
            }

            .footer-bar {
                background-color: #0a5c36 !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .middle-block {
                border-top: 1px solid #0a5c36 !important;
                border-bottom: 1px solid #0a5c36 !important;
            }

            .no-print {
                display: none !important;
            }
        }

        .left-section {
            width: 15cm;
            display: flex;
            flex-direction: column;
            border-right: 2px dashed #0a5c36;
            box-sizing: border-box;
        }

        .right-section {
            width: 6cm;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        .middle-block {
            margin-top: 1.7cm;
            height: 5cm;
            width: 100%;
            display: flex;
            flex-direction: column;
            padding: 8px 12px;
            box-sizing: border-box;
            justify-content: center;
            border-top: 1px solid #0a5c36;
            border-bottom: 1px solid #0a5c36;
        }

        .middle-block-right {
            margin-top: 1.7cm;
            height: 5cm;
            width: 100%;
            display: flex;
            flex-direction: column;
            padding: 6px 10px;
            box-sizing: border-box;
            border-top: 1px solid #0a5c36;
            border-bottom: 1px solid #0a5c36;
        }

        .t-row {
            display: flex;
            gap: 6px;
            margin-bottom: 6px;
        }
        
        .t-row:last-child {
            margin-bottom: 0;
        }

        .t-box {
            border: 1.5px solid #0a5c36;
            border-radius: 6px;
            padding: 4px 6px;
            display: flex;
            align-items: center;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }
        
        .t-box-sm {
            border: 1.5px solid #0a5c36;
            border-radius: 4px;
            padding: 3px 5px;
            display: flex;
            align-items: center;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .t-label {
            color: #0a5c36;
            margin-right: 4px;
        }

        .t-val {
            color: #000;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Specific widths */
        .w-name { flex: 7; }
        .w-date { flex: 3; }
        .w-village { flex: 4; }
        .w-seats { flex: 3; }
        .w-time { flex: 3; }
        .w-deposit { flex: 1; }
        .w-baki { flex: 1; }
        .w-total { flex: 1; }
        .w-full { flex: 1; }

        .footer-bar {
            background-color: #0a5c36;
            color: white;
            text-align: center;
            font-size: 11px;
            padding: 4px;
            font-weight: 500;
            border-radius: 4px;
            margin-top: auto;
        }
    </style>

    <div class="bg-white rounded-none p-6 shadow-sm mb-6 flex justify-between items-center no-print border border-gray-100">
        <div>
            <h2 class="text-[18px] font-bold text-[#1c2238]">Print Ticket</h2>
            <p class="text-[13px] text-gray-500">Preview and print the ticket for {{ $ticket->passenger_name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tickets.index') }}"
                class="px-5 py-2 bg-gray-100 text-gray-600 font-bold text-[13px] rounded-none hover:bg-gray-200 transition-colors flex items-center">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back
            </a>
            <button onclick="window.print()"
                class="px-5 py-2 bg-[#1c2238] text-white font-bold text-[13px] rounded-none hover:bg-[#2a3454] transition-colors flex items-center">
                <i class="fa-solid fa-print mr-2"></i> Print (21x9 cm)
            </button>
            <a href="{{ route('tickets.pdf', $ticket->id) }}"
                class="px-5 py-2 bg-red-600 text-white font-bold text-[13px] rounded-none hover:bg-red-700 transition-colors flex items-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="ticket-container">
        <div class="ticket-wrapper">

            <!-- Left Section (Main) -->
            <div class="left-section">
                <div class="middle-block">
                    <div class="t-row">
                        <div class="t-box w-name">
                            <span class="t-label">નામ :</span>
                            <span class="t-val">{{ $ticket->passenger_name }}</span>
                        </div>
                        <div class="t-box w-date">
                            <span class="t-label">તા.:</span>
                            <span class="t-val">{{ \Carbon\Carbon::parse($ticket->journey_date)->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="t-row">
                        <div class="t-box w-village">
                            <span class="t-label">ગામ :</span>
                            <span class="t-val">{{ $ticket->to_place ?: $ticket->from_place }}</span>
                        </div>
                        <div class="t-box w-seats">
                            <span class="t-label">કુલ સીટ :</span>
                            <span class="t-val">{{ $ticket->total_seats }}</span>
                        </div>
                        <div class="t-box w-time">
                            <span class="t-label">ઉ. સમય:</span>
                            <span
                                class="t-val">{{ $ticket->bus_time ? \Carbon\Carbon::parse($ticket->bus_time)->format('h:i A') : '' }}</span>
                        </div>
                    </div>

                    <div class="t-row">
                        <div class="t-box w-full">
                            <span class="t-label">સીટ નં. :</span>
                            <span class="t-val whitespace-normal break-all">{{ $ticket->seat_number }}</span>
                        </div>
                    </div>

                    <div class="t-row">
                        <div class="t-box w-deposit">
                            <span class="t-label">ડિપોઝીટ :</span>
                            <span class="t-val">{{ $ticket->payable_amount }}</span>
                        </div>
                        <div class="t-box w-baki">
                            <span class="t-label">બાકી :</span>
                            <span class="t-val">{{ $ticket->total_amount - $ticket->payable_amount }}</span>
                        </div>
                        <div class="t-box w-total">
                            <span class="t-label">કુલ રૂ.:</span>
                            <span class="t-val">{{ $ticket->total_amount }}</span>
                        </div>
                    </div>

                    <div class="t-row">
                        <div class="t-box w-full">
                            <span class="t-label">પિકઅપ :</span>
                            <span class="t-val">{{ $ticket->pickup_stop }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section (Stub) -->
            <div class="right-section">
                <div class="middle-block-right">
                    <div class="t-row">
                        <div class="t-box-sm w-full" style="justify-content: flex-end; border: none; padding: 0;">
                            <span class="t-val mr-auto font-bold">{{ $ticket->traveler_name }}</span>
                            <span class="t-label font-bold" style="margin-right: 0; margin-left: 6px;">ટ્રાવેલ્સ</span>
                        </div>
                    </div>

                    <div class="t-row">
                        <div class="t-box-sm w-full">
                            <span class="t-label">નામ:</span>
                            <span
                                class="t-val text-[11px] truncate overflow-hidden whitespace-nowrap">{{ $ticket->passenger_name }}</span>
                        </div>
                    </div>

                    <div class="t-row">
                        <div class="t-box-sm w-name">
                            <span class="t-label">બસ નં:</span>
                            <span class="t-val text-[11px]">{{ $ticket->bus->plate_number }}</span>
                        </div>
                        <div class="t-box-sm w-date">
                            <span class="t-label">તા.:</span>
                            <span
                                class="t-val text-[11px]">{{ \Carbon\Carbon::parse($ticket->journey_date)->format('d/m') }}</span>
                        </div>
                    </div>

                    <div class="t-row">
                        <div class="t-box-sm w-name">
                            <span class="t-label">ગામ:</span>
                            <span class="t-val text-[11px]">{{ $ticket->to_place ?: $ticket->from_place }}</span>
                        </div>
                        <div class="t-box-sm w-date">
                            <span class="t-label">કુલ સીટ:</span>
                            <span class="t-val text-[11px]">{{ $ticket->total_seats }}</span>
                        </div>
                    </div>

                    <div class="t-row" style="flex-grow: 1;">
                        <div class="t-box-sm w-full" style="align-items: flex-start;">
                            <span class="t-label">સીટ નં:</span>
                            <span class="t-val whitespace-normal break-all">{{ $ticket->seat_number }}</span>
                        </div>
                    </div>
                    
                    <div class="footer-bar">
                        ઓલ સૌરાષ્ટ્રનું કન્ફર્મ બુકીંગ ફોન દ્વારા મળશે.
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection