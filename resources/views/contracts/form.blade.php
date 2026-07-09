@extends('layouts.app')

@section('title', 'Contract Form')
@section('header', 'Contract Form')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hind+Vadodara:wght@400;500;600;700&display=swap');

        .contract-wrapper {
            font-family: 'Hind Vadodara', Arial, sans-serif;
            background: #e5e5e5;
            padding: 24px 16px;
            color: #d32f2f;
            line-height: normal;
        }
        .contract-wrapper * { box-sizing: border-box; }

        /* ─── PAGE TOOLBAR ─── */
        .toolbar {
            max-width: 820px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1c2238;
            padding: 10px 16px;
            border-radius: 0;
        }
        .toolbar a.back-btn {
            text-decoration: none;
            color: #f0b44b;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-family: Arial, sans-serif;
        }
        .toolbar a.back-btn:hover { color: #fff; }
        .toolbar-right { display: flex; align-items: center; gap: 10px; }
        .toolbar-hint {
            color: #9ca3af;
            font-size: 12px;
            font-family: Arial, sans-serif;
        }
        .btn-print {
            background: #d32f2f;
            color: #fff;
            padding: 8px 20px;
            font-weight: 700;
            font-size: 13px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: Arial, sans-serif;
            border-radius: 0;
        }
        .btn-print:hover { opacity: 0.9; }

        /* ─── CONTRACT PAPER ─── */
        .contract {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            padding: 5mm;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .contract-inner {
            border: 3px solid #d32f2f;
            padding-bottom: 2px;
        }

        /* ─── COMMON INPUTS ─── */
        .val-input {
            border: none;
            background: transparent;
            font-family: inherit;
            color: #d32f2f;
            font-size: 15px;
            font-weight: 600;
            outline: none;
            width: 100%;
        }
        .val-input:hover { background: rgba(211, 47, 47, 0.05); }
        .val-input:focus { background: #fff8f0; }
        
        .line-input {
            border: none;
            border-bottom: 1.5px solid #d32f2f;
            width: 100%;
            display: inline-block;
            background: transparent;
            color: #d32f2f;
            font-size: 16px;
            font-family: inherit;
            font-weight: 600;
            outline: none;
            padding: 1px 5px;
            margin-bottom: 4px;
            line-height: 1.5;
        }

        /* ─── HEADER TOP ─── */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 4px 10px;
            font-size: 13px;
            line-height: 1.5;
            font-weight: 600;
        }
        .header-top .col-center { text-align: center; flex: 1; padding: 0 10px; color: #d32f2f; font-size:12px; }
        .header-top .col-right  { text-align: right; }

        /* ─── MAIN TITLE ─── */
        .main-title {
            text-align: center;
            padding: 0px 10px 4px;
        }
        .main-title .company-name {
            font-size: 55px;
            font-weight: 700;
            color: #d32f2f;
            line-height: 1;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .main-title .address-line {
            font-size: 14px;
            font-weight: 600;
            border-bottom: 3px solid #d32f2f;
            padding-bottom: 6px;
            margin-bottom: 0px;
        }

        /* ─── BOOKING ROW ─── */
        .booking-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
            background: #e8e8e8; /* Subtle gray background behind the row */
            border-bottom: 3px solid #d32f2f;
            align-items: center;
        }
        .bg-gray-bar {
            background: #eaeaea; 
            padding: 8px 10px;
            border-bottom: 3px solid #d32f2f;
        }
        .b-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .b-label {
            background: #d32f2f;
            color: #fff;
            padding: 4px 14px;
            font-size: 14px;
            font-weight: 700;
        }
        .b-val {
            border: 1.5px solid #d32f2f;
            background: #fff;
            height: 28px;
            width: 140px;
            padding: 0 8px;
        }

        /* ─── BANNERS ─── */
        .red-banner {
            background: #d32f2f;
            color: #fff;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            padding: 4px 10px;
            border-bottom: 2px solid #d32f2f;
            width: 100%;
        }

        /* ─── PARTY NAME SECTION ─── */
        .party-section {
            padding: 12px 14px 4px;
            border-bottom: 3px solid #d32f2f;
        }

        /* ─── DESTINATION SECTION ─── */
        .dest-section {
            padding: 6px 14px 2px;
            border-bottom: 2px solid #d32f2f;
        }
        .inline-dest {
            display: flex;
            align-items: flex-end;
            margin-bottom: 5px;
        }
        .inline-dest span {
            font-weight: 700;
            font-size: 15px;
            white-space: nowrap;
            margin-right: 8px;
        }

        /* ─── DATES SECTION ─── */
        .dates-section {
            padding: 6px 14px;
            border-bottom: 2px solid #d32f2f;
        }
        .date-row {
            display: flex;
            align-items: flex-end;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 700;
        }
        .date-row span { margin-right: 8px; white-space: nowrap; }
        .date-row input.line-input { margin-bottom: 0; padding-bottom: 0; }

        /* ─── FARE BOXES ─── */
        .fare-boxes {
            display: flex;
            gap: 12px;
            padding: 8px 14px;
            border-bottom: 2px solid #d32f2f;
            align-items: stretch;
        }
        .f-box {
            border: 2px solid #d32f2f;
            border-radius: 10px;
            padding: 8px;
            display: flex;
        }
        .f-box-1 { flex: 2; flex-direction: row; align-items: stretch; gap: 8px; padding-right: 0;}
        .f-box-2 { flex: 1.5; flex-direction: row; align-items: stretch; gap: 8px; padding-right:0;}
        .f-box-3 { flex: 1.5; flex-direction: column; align-items: center; text-align:center;}
        
        .f-lbl {
            font-weight: 600;
            font-size: 14px;
            line-height: 1.6;
        }
        .f-val-box {
            border: 1.5px solid #d32f2f;
            border-radius: 6px;
            width: 70px;
            min-height: 100%;
            margin: -8px 0 -8px 0;
            border-top: none; border-bottom:none; border-right:none;
            border-top-left-radius: 0; border-bottom-left-radius:0;
            padding: 5px;
        }
        .f-box-3-content {
            width: 100%;
            height: 40px;
        }

        /* ─── TERMS & SIGN SECTION ─── */
        .terms-sign-wrap {
            display: flex;
            padding: 8px 14px;
            gap: 20px;
        }
        .terms-box {
            flex: 2.2;
            border: 2px solid #d32f2f;
            border-radius: 12px;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.5;
        }
        .term-line {
            display: flex;
            align-items: flex-end;
            margin-bottom: 2px;
        }
        .term-line input {
            border: none;
            border-bottom: 1.5px solid #d32f2f;
            background: transparent;
            font-weight: 600;
            color: inherit;
            flex: 1;
            margin-left: 6px;
            outline: none;
        }
        .term-indent {
            padding-left: 18px;
        }
        
        .sign-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            text-align: right;
            padding-bottom: 10px;
        }
        .sign-firm {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 60px;
        }
        .sign-title {
            font-weight: 600;
            font-size: 14px;
        }

        /* ─── FOOTER JURISDICTION ─── */
        .jurisdiction-row {
            display: flex;
            justify-content: space-between;
            padding: 0 14px 6px;
            font-size: 14px;
            font-weight: 700;
        }

        /* ─── BOTTOM BANNER ─── */
        .bottom-banner {
            background: #d32f2f;
            color: #fff;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 10px;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }
        @media print {
            body * { visibility: hidden; }
            .contract-wrapper, .contract-wrapper * { visibility: visible; }
            .contract-wrapper {
                position: absolute; left: 0; top: 0; width: 100%;
                margin: 0; padding: 0; background: #fff !important;
            }
            .toolbar { display: none !important; }
            .contract { 
                width: 210mm; 
                min-height: 297mm;
                padding: 5mm;
                margin: 0;
                box-shadow: none;
            }
            .contract-wrapper * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .val-input::placeholder, .line-input::placeholder { color: transparent; }
        }
    </style>
<div class="contract-wrapper">

<div class="toolbar">
    <a href="{{ route('contracts.index') }}" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Contracts</a>
    <div class="toolbar-right">
        <span class="toolbar-hint" id="save-status"><i class="fa-solid fa-pen text-[10px]"></i> Enter details and save</span>
        <button class="btn-print" style="background:#10b981; margin-right:10px;" onclick="saveContract()">
            <i class="fa-solid fa-floppy-disk"></i> Save
        </button>
        <button class="btn-print" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Print
        </button>
    </div>
</div>

<form id="contract-form" style="display:none;">
    @csrf
    @if($contract->exists) @method('PUT') @endif
    <input type="hidden" name="booking_number" id="inp_booking_number">
    <input type="hidden" name="party_name" id="inp_party_name">
    <input type="hidden" name="contract_date" id="inp_contract_date">
    <input type="hidden" name="data[html]" id="inp_html">
</form>

@if(!empty($contract->data['html']))
    {!! $contract->data['html'] !!}
@else
<div class="contract" id="contract-container">
    <div class="contract-inner">
        <!-- HEADER TOP -->
        <div class="header-top">
            <div class="col-left">
                જય શ્રી સ્વામિનારાયણ<br>૦૨૬૧ ૨૫૧૧૭૦૦
            </div>
            <div class="col-center">
                શ્રી ૧||<br>શ્રી કષ્ટભંજન દેવ<br>શ્રી સુરાપુરા દાદા
            </div>
            <div class="col-right">
                આઈ શ્રી ખોડીયાર માં<br>મો. ૯૯૦૪૧ ૭૨૭૩૪<br>૯૯૦૪૧ ૭૨૨૩૩
            </div>
        </div>

        <!-- TITLE -->
        <div class="main-title">
            <div class="company-name">શ્રી હરિકૃષ્ણ ટ્રાવેલ્સ</div>
            <br>
            <div class="address-line">૯,અક્ષરદીપ શોપીંગ સેન્ટર, સીંગણપોર ચાર રસ્તા, વેડરોડ કતારગામ, સુરત - ૩૯૫૦૦૪</div>
        </div>

        <!-- BOOKING ROW -->
        <div class="bg-gray-bar">
            <div class="booking-row" style="border:none; padding:0; background:transparent;">
                <div class="b-group">
                    <div class="b-label">કોન્ટ્રાક્ટ બુકીંગ નં.</div>
                    <input type="text" class="b-val val-input" id="val_booking_number">
                </div>
                <div class="b-group">
                    <div class="b-label">તારીખ</div>
                    <input type="text" class="b-val val-input" id="val_contract_date">
                </div>
            </div>
        </div>

        <!-- BANNER 1 -->
        <div class="red-banner">:: બસ ભાડે રાખનાર પાર્ટીનું નામ ::</div>

        <!-- PARTY NAME -->
        <div class="party-section">
            <input type="text" class="line-input" id="val_party_name">
            <input type="text" class="line-input">
            <input type="text" class="line-input">
            <input type="text" class="line-input" style="margin-bottom:0;">
        </div>

        <!-- BANNER 2 -->
        <div class="red-banner" style="font-size:14px; font-weight:500;">
            નીચે જણાવેલી વિગતે તમારી બસ પાછળ જણાવેલી શરતો ને આધીન મુસાફરી માટે કોન્ટ્રાક્ટ કરેલો છે.
        </div>

        <!-- DESTINATIONS -->
        <div class="dest-section">
            <div class="inline-dest">
                <span>ટૂરમાં જવાના સ્થળો :</span>
                <input type="text" class="line-input" style="margin-bottom:0;">
            </div>
            <input type="text" class="line-input">
            <input type="text" class="line-input">
            <input type="text" class="line-input">
            <input type="text" class="line-input" style="margin-bottom:0;">
        </div>

        <!-- DATES -->
        <div class="dates-section">
            <div class="date-row">
                <span>બસ ઉપાડવાની તારીખ :</span>
                <input type="text" class="line-input" style="width:200px;">
                <span style="margin-left:15px;">સમય :</span>
                <input type="text" class="line-input">
            </div>
            <div class="date-row">
                <span>બસ પરત આવવાની તારીખ :</span>
                <input type="text" class="line-input" style="width:200px;">
                <span style="margin-left:15px;">સમય :</span>
                <input type="text" class="line-input">
            </div>
        </div>

        <!-- FARE BOXES -->
        <div class="fare-boxes">
            <div class="f-box f-box-1">
                <div class="f-lbl" style="flex:1;">
                    ભાડાનો દર ટેક્ષ સાથે રૂ.<br>
                    બીજા રાજ્યો માટે રૂ.<br>
                    આશરે કી.મી.
                </div>
                <div class="f-val-box"><textarea class="val-input" style="height:100%; resize:none;"></textarea></div>
            </div>
            <div class="f-box f-box-2">
                <div class="f-lbl" style="flex:1; display:flex; align-items:center;">
                    બસ પ્રવાસી ની સંખ્યા
                </div>
                <div class="f-val-box"><textarea class="val-input" style="height:100%; resize:none; text-align:center;"></textarea></div>
            </div>
            <div class="f-box f-box-3">
                <div class="f-lbl">ઉચ્ચક ભાડુ પેસેન્જર ટેક્ષ સાથે રૂ.</div>
                <div class="f-box-3-content"><input type="text" class="val-input" style="text-align:center; height:100%;"></div>
            </div>
        </div>

        <!-- TERMS & SIGNATURE -->
        <div class="terms-sign-wrap">
            <div class="terms-box">
                <div class="term-line">
                    <span>૧. એડવાન્સ આપેલી રકમ રૂ.</span>
                    <input type="text">
                </div>
                <div class="term-line">
                    <span>૨. નામ લીસ્ટ સાથે બસ ઉપડતા</span>
                </div>
                <div class="term-line term-indent">
                    <span>પહેલા આપવાની રકમ રૂ.</span>
                    <input type="text">
                </div>
                <div class="term-line">
                    <span>૩. રસ્તામાં ડ્રાઈવર ખર્ચ માટે</span>
                </div>
                <div class="term-line term-indent">
                    <span>આપવાની રકમ રૂ.</span>
                    <input type="text">
                </div>
                <div class="term-line" style="margin-top:6px;">
                    <span>૪.પ્રવાસ પુરો થયે ડ્રાઈવરને ચુકતે હિસાબ આપી દેવો.</span>
                </div>
                <div class="term-line">
                    <span>પાછળ જણાવેલી શરતો આધિન ઉપર ની વિગતો</span>
                </div>
            </div>
            <div class="sign-box">
                <div class="sign-firm">ફર્મ, શ્રી હરીકૃષ્ણ ટ્રાવેલ્સ</div>
                <div class="sign-title">વ્યવસ્થાપક ની સહી</div>
            </div>
        </div>

        <!-- JURISDICTION -->
        <div class="jurisdiction-row">
            <span>બસ ભાડે રાખવાનો કોન્ટ્રાક્ટ કરેલો છે.</span>
            <span>ન્યાય ક્ષેત્ર સુરત કોર્ટ</span>
        </div>
    </div>
    
    <!-- BOTTOM BANNER -->
    <div class="bottom-banner">
        ખાસ નોંધ - ડ્રાઈવરને રોજના રૂ.૫૦૦/- જમવાના આપવા, પાર્કીંગ ચાર્જ, પુલ ચાર્જ વગેરે પાર્ટી નો રહશે.
    </div>
</div>
@endif

<script>
    function saveContract() {
        document.getElementById('save-status').innerHTML = "<i class='fa-solid fa-spinner fa-spin'></i> Saving...";
        
        // Update input/textarea values as HTML attributes so they save properly
        document.querySelectorAll('#contract-container input').forEach(input => {
            input.setAttribute('value', input.value);
        });
        document.querySelectorAll('#contract-container textarea').forEach(txt => {
            txt.innerHTML = txt.value;
        });

        const container = document.getElementById('contract-container');
        if(document.activeElement) document.activeElement.blur();

        let booking_number = document.getElementById('val_booking_number') ? document.getElementById('val_booking_number').value.trim() : '';
        let contract_date = document.getElementById('val_contract_date') ? document.getElementById('val_contract_date').value.trim() : '';
        let party_name = document.getElementById('val_party_name') ? document.getElementById('val_party_name').value.trim() : '';

        document.getElementById('inp_booking_number').value = booking_number;
        document.getElementById('inp_contract_date').value = contract_date;
        document.getElementById('inp_party_name').value = party_name;
        document.getElementById('inp_html').value = container ? container.outerHTML : document.querySelector('.contract').outerHTML;

        let formData = new FormData(document.getElementById('contract-form'));
        
        fetch("{{ $contract->exists ? route('contracts.update', $contract->id) : route('contracts.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('save-status').innerHTML = "<i class='fa-solid fa-circle-check'></i> Saved Successfully!";
                setTimeout(() => {
                    if (data.id) {
                        window.location.href = "/contracts/" + data.id + "/edit";
                    }
                }, 1000);
            } else {
                document.getElementById('save-status').innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Error Saving!";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('save-status').innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Error Saving!";
        });
    }
</script>
</div>
@endsection
