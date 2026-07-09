<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Form - Editable</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hind+Vadodara:wght@400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Hind Vadodara', Arial, sans-serif;
            background: #e5e5e5;
            padding: 24px 16px;
            color: #c0001a;
        }

        /* ─── PAGE TOOLBAR ─── */
        .toolbar {
            max-width: 820px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1c2238;
            padding: 10px 16px;
            border-radius: 4px;
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
            background: #c0001a;
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
            border-radius: 3px;
        }
        .btn-print:hover { background: #a00016; }

        /* ─── EDITABLE ELEMENT COMMON ─── */
        .e {
            /* Editable element — shows subtle highlight on hover/focus */
            outline: none;
            border: none;
            background: transparent;
            color: inherit;
            font-family: inherit;
            font-size: inherit;
            font-weight: inherit;
            width: 100%;
            display: inline-block;
            cursor: text;
        }
        .e:hover   { background: rgba(192,0,26,0.06); }
        .e:focus   { background: #fff8f0; outline: 1px dashed #c0001a; }

        /* ─── CONTRACT PAPER ─── */
        .contract {
            max-width: 820px;
            margin: 0 auto;
            background: #fff;
            border: 2.5px solid #c0001a;
        }

        /* ─── HEADER TOP ─── */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 8px 14px 6px;
            border-bottom: 1.5px solid #c0001a;
            font-size: 12.5px;
            line-height: 1.7;
            font-weight: 600;
        }
        .header-top .col-center { text-align: center; flex: 1; padding: 0 10px; }
        .header-top .col-right  { text-align: right; }

        /* ─── MAIN TITLE ─── */
        .main-title {
            text-align: center;
            padding: 4px 14px 6px;
            border-bottom: 2.5px solid #c0001a;
        }
        .main-title .company-name {
            font-size: 40px;
            font-weight: 700;
            color: #c0001a;
            line-height: 1.15;
            letter-spacing: 1px;
            min-height: 52px;
        }
        .main-title .address-line {
            font-size: 13px;
            font-weight: 600;
            padding-bottom: 2px;
            min-height: 22px;
        }

        /* ─── BOOKING ROW ─── */
        .booking-row {
            display: flex;
            align-items: stretch;
            border-bottom: 2.5px solid #c0001a;
        }
        .booking-group {
            display: flex;
            align-items: stretch;
        }
        .booking-group:first-child {
            flex: 3;
            border-right: 1.5px solid #c0001a;
        }
        .booking-group:last-child {
            flex: 1;
        }
        /* The red label tag inside booking group */
        .booking-group .lbl {
            background: #c0001a;
            color: #fff;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            cursor: text;
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }
        .booking-group .lbl:hover   { background: #a00016; }
        .booking-group .lbl.e:focus { outline: 1px dashed #fff; background: #a00016; }
        /* The white editable input area */
        .booking-group .val {
            flex: 1;
            min-width: 0;
            padding: 8px 10px;
            font-size: 14px;
            color: #c0001a;
            font-family: inherit;
            font-weight: 600;
            outline: none;
            cursor: text;
            min-height: 38px;
            border: none;
        }
        .booking-group .val:hover { background: rgba(192,0,26,0.05); }
        .booking-group .val:focus { background: #fff8f0; outline: 1px dashed #c0001a; }

        /* ─── SECTION HEADER ─── */
        .sec-hdr {
            background: #c0001a;
            color: #fff;
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            padding: 6px 14px;
            letter-spacing: 0.5px;
            min-height: 34px;
        }
        .sec-hdr.e:hover   { background: #a00016; }
        .sec-hdr.e:focus   { background: #a00016; outline: 1px dashed #fff; }

        /* ─── PARTY NAME BOX ─── */
        .party-box {
            padding: 8px 14px 10px;
            border-bottom: 2.5px solid #c0001a;
        }

        /* ─── LINE INPUT ─── */
        .line-input {
            display: block;
            width: 100%;
            border: none;
            border-bottom: 1px solid #c0001a;
            font-size: 15px;
            color: #c0001a;
            padding: 4px 4px;
            margin-bottom: 6px;
            background: transparent;
            font-family: inherit;
            font-weight: inherit;
            outline: none;
            cursor: text;
        }
        .line-input:hover { background: rgba(192,0,26,0.05); }
        .line-input:focus { background: #fff8f0; }

        /* ─── INFO BANNER ─── */
        .info-banner {
            background: #c0001a;
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 14px;
            border-bottom: 1px solid #c0001a;
            min-height: 32px;
            line-height: 1.5;
        }
        .info-banner.e:hover { background: #a00016; }
        .info-banner.e:focus { background: #a00016; outline: 1px dashed #fff; }

        /* ─── JOURNEY SECTION ─── */
        .journey-section { padding: 8px 14px; border-bottom: 0; }
        .journey-row {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 4px;
        }
        .journey-row .jlbl {
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            min-width: 170px;
            cursor: text;
        }
        .journey-row .jlbl:hover { background: rgba(192,0,26,0.06); }
        .journey-row .jlbl.e:focus { background: #fff8f0; outline: 1px dashed #c0001a; }
        .journey-row .jval {
            flex: 1;
            border: none;
            border-bottom: 1px solid #c0001a;
            font-size: 14px;
            color: #c0001a;
            padding: 2px 4px;
            background: transparent;
            font-family: inherit;
            outline: none;
        }
        .journey-row .jval:hover { background: rgba(192,0,26,0.05); }
        .journey-row .jval:focus { background: #fff8f0; }

        /* ─── DATE-TIME ROWS ─── */
        .dt-rows {
            padding: 4px 14px 8px;
            border-bottom: 2.5px solid #c0001a;
        }
        .dt-row {
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin-bottom: 4px;
            font-size: 13px;
        }
        .dt-row .dtlbl {
            font-weight: 700;
            white-space: nowrap;
            cursor: text;
            min-width: 200px;
        }
        .dt-row .dtlbl:hover   { background: rgba(192,0,26,0.06); }
        .dt-row .dtlbl.e:focus { background: #fff8f0; outline: 1px dashed #c0001a; }
        .dt-row .dtval {
            flex: 1;
            border: none;
            border-bottom: 1px solid #c0001a;
            font-size: 13px;
            color: #c0001a;
            padding: 2px 4px;
            background: transparent;
            font-family: inherit;
            outline: none;
        }
        .dt-row .dtval:hover { background: rgba(192,0,26,0.05); }
        .dt-row .dtval:focus { background: #fff8f0; }
        .dt-row .time-lbl { font-weight: 700; white-space: nowrap; cursor: text; }
        .dt-row .time-lbl:hover   { background: rgba(192,0,26,0.06); }
        .dt-row .time-lbl.e:focus { background: #fff8f0; outline: 1px dashed #c0001a; }
        .dt-row .time-val {
            width: 90px;
            border: none;
            border-bottom: 1px solid #c0001a;
            font-size: 13px;
            color: #c0001a;
            padding: 2px 4px;
            background: transparent;
            font-family: inherit;
            outline: none;
        }
        .dt-row .time-val:hover { background: rgba(192,0,26,0.05); }
        .dt-row .time-val:focus { background: #fff8f0; }

        /* ─── FARE TABLE ─── */
        .fare-section { padding: 0 14px; border-bottom: 2.5px solid #c0001a; }
        .fare-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .fare-table td {
            border: 1.5px solid #c0001a;
            padding: 10px 10px;
            font-size: 13px;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        /* 3 label cols (22% each = 66%) + 3 value cols (11.3% each = 33.9%) ≈ 100% */
        .fare-table .ft-lbl {
            font-weight: 700;
            width: 22%;
            line-height: 1.7;
            vertical-align: middle;
        }
        .fare-table .ft-val {
            width: 11%;
            text-align: center;
            vertical-align: middle;
        }

        /* ─── BOTTOM SECTION ─── */
        .bottom-section {
            display: flex;
            align-items: stretch;
            border-bottom: 2.5px solid #c0001a;
        }
        .terms-side {
            flex: 1;
            border-right: 2px solid #c0001a;
            padding: 8px 12px;
            font-size: 13px;
        }
        .term-row {
            display: flex;
            align-items: baseline;
            gap: 6px;
            margin-bottom: 6px;
        }
        .term-row .tlbl {
            white-space: nowrap;
            font-weight: 600;
            cursor: text;
        }
        .term-row .tlbl:hover   { background: rgba(192,0,26,0.06); }
        .term-row .tlbl.e:focus { background: #fff8f0; outline: 1px dashed #c0001a; }
        .term-row .tval {
            flex: 1;
            border: none;
            border-bottom: 1px solid #c0001a;
            font-size: 13px;
            color: #c0001a;
            padding: 1px 3px;
            background: transparent;
            font-family: inherit;
            outline: none;
        }
        .term-row .tval:hover { background: rgba(192,0,26,0.05); }
        .term-row .tval:focus { background: #fff8f0; }
        .text-term {
            font-size: 12.5px;
            font-weight: 600;
            margin-top: 5px;
            line-height: 1.6;
            cursor: text;
        }
        .text-term:hover   { background: rgba(192,0,26,0.06); }
        .text-term.e:focus { background: #fff8f0; outline: 1px dashed #c0001a; }

        .sign-side {
            width: 210px;
            padding: 10px 12px;
            text-align: right;
            font-size: 13px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .sign-firm {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 55px;
            line-height: 1.5;
            min-height: 22px;
        }
        .sign-label {
            border-top: 1px solid #c0001a;
            padding-top: 4px;
            font-size: 13px;
            font-weight: 600;
            min-height: 22px;
        }

        /* ─── JURISDICTION ─── */
        .jurisdiction {
            text-align: right;
            padding: 5px 14px;
            font-size: 13px;
            font-weight: 700;
            border-bottom: 2.5px solid #c0001a;
            min-height: 30px;
        }

        /* ─── FOOTER BANNER ─── */
        .footer-banner {
            background: #c0001a;
            color: #fff;
            padding: 8px 14px;
            font-size: 12.5px;
            font-weight: 600;
            text-align: center;
            line-height: 1.6;
        }
        .footer-banner.e:hover { background: #a00016; }
        .footer-banner.e:focus { background: #a00016; outline: 1px dashed #fff; }

        /* ─── PRINT STYLES ─── */
        @media print {
            body { background: #fff !important; padding: 0; }
            .toolbar { display: none !important; }
            .contract { max-width: 100%; border: 2px solid #c0001a; }
            [contenteditable]:hover,
            [contenteditable]:focus {
                background: transparent !important;
                outline: none !important;
            }
            .e { background: transparent !important; outline: none !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<!-- ═══ TOOLBAR ═══ -->
<div class="toolbar">
    <a href="{{ route('forms.index') }}" class="back-btn">&#8592; Back to Forms</a>
    <div class="toolbar-right">
        <span class="toolbar-hint">✏️ Click any text to edit it</span>
        <button class="btn-print" onclick="window.print()">🖨️ Print</button>
    </div>
</div>

<div class="contract">

    <!-- ═══ HEADER TOP ═══ -->
    <div class="header-top">
        <div class="col-left">
            <span class="e" contenteditable="true">જય શ્રી સ્વામિનારાયણ</span><br>
            <span class="e" contenteditable="true">૦૨૬૧ ૨૫૧૧૭૦૦</span>
        </div>
        <div class="col-center">
            <span class="e" contenteditable="true">શ્રી વ..</span><br>
            <span class="e" contenteditable="true">શ્રી કષ્ટભંજન દેવ</span><br>
            <span class="e" contenteditable="true">શ્રી સુરાપુરા દાદા</span>
        </div>
        <div class="col-right">
            <span class="e" contenteditable="true">આઈ શ્રી ખોડિયાર મા</span><br>
            <span class="e" contenteditable="true">મો. ૯૯૦૪૧ ૭૨૭૩૪</span><br>
            <span class="e" contenteditable="true">૯૯૦૪૧ ૭૨૨૩૩</span>
        </div>
    </div>

    <!-- ═══ MAIN TITLE ═══ -->
    <div class="main-title">
        <div class="company-name e" contenteditable="true">શ્રી હરીકૃષ્ણ ટ્રાવેલ્સ</div>
        <div class="address-line e" contenteditable="true">૯,અક્ષરદ્વીપ શોપીંગ સેન્ટર, સીંગણપોર ચાર રસ્તા, વેડરોડ કતારગામ, સુરત - ૩૯૫૦૦૪</div>
    </div>

    <!-- ═══ BOOKING ROW ═══ -->
    <div class="booking-row">
        <div class="booking-group">
            <span class="lbl e" contenteditable="true">કોન્ટ્રાક્ટ બુકીંગ નં.</span>
            <span class="val e" contenteditable="true"></span>
        </div>
        <div class="booking-group">
            <span class="lbl e" contenteditable="true">તારીખ</span>
            <span class="val e" contenteditable="true"></span>
        </div>
    </div>

    <!-- ═══ PARTY NAME HEADER ═══ -->
    <div class="sec-hdr e" contenteditable="true">:: બસ ભાડે રાખનાર પાર્ટીનું નામ ::</div>

    <!-- ═══ PARTY NAME LINES ═══ -->
    <div class="party-box">
        <div class="line-input e" contenteditable="true"></div>
        <div class="line-input e" contenteditable="true"></div>
        <div class="line-input e" contenteditable="true"></div>
    </div>

    <!-- ═══ INFO BANNER ═══ -->
    <div class="info-banner e" contenteditable="true">
        નીચે જણાવેલી વિગતે તમારી બસ પાછળ જણાવેલી શરતો ને આધીન મુસાફરી માટે કોન્ટ્રાક્ટ કરેલો છે.
    </div>

    <!-- ═══ DESTINATION ═══ -->
    <div class="journey-section" style="border-bottom:0; padding-bottom:0;">
        <div class="journey-row">
            <span class="jlbl e" contenteditable="true">દૂરમાં જવાના સ્થળો :</span>
            <input class="jval" type="text" placeholder="">
        </div>
    </div>
    <div style="padding: 0 14px 8px;">
        <div class="line-input" contenteditable="true"></div>
        <div class="line-input" contenteditable="true"></div>
        <div class="line-input" contenteditable="true"></div>
    </div>

    <!-- ═══ DEPARTURE / RETURN DATE-TIME ═══ -->
    <div class="dt-rows">
        <div class="dt-row">
            <span class="dtlbl e" contenteditable="true">બસ ઉપાડવાની તારીખ :</span>
            <input class="dtval" type="text" placeholder="">
            <span class="time-lbl e" contenteditable="true">સમય :</span>
            <input class="time-val" type="text" placeholder="">
        </div>
        <div class="dt-row">
            <span class="dtlbl e" contenteditable="true">બસ પરત આવવાની તારીખ :</span>
            <input class="dtval" type="text" placeholder="">
            <span class="time-lbl e" contenteditable="true">સમય :</span>
            <input class="time-val" type="text" placeholder="">
        </div>
    </div>

    <!-- ═══ FARE TABLE ═══ -->
    <div class="fare-section">
        <table class="fare-table">
            <tr>
                <td class="ft-lbl">
                    <div class="e" contenteditable="true">ભાડાનો દર ટેક્ષ સાથે રૂ.</div>
                    <div class="e" contenteditable="true">બીજા રાજ્યો માટે રૂ.</div>
                    <div class="e" contenteditable="true">આશરે કી.મી.</div>
                </td>
                <td class="ft-val"><div class="e" contenteditable="true" style="min-height:50px;"></div></td>
                <td class="ft-lbl"><div class="e" contenteditable="true">બસ પ્રવાસી ની સંખ્યા</div></td>
                <td class="ft-val"><div class="e" contenteditable="true" style="min-height:50px;"></div></td>
                <td class="ft-lbl"><div class="e" contenteditable="true">ઉચ્ચક ભાડુ પૅસેન્જર ટેક્ષ સાથે રૂ.</div></td>
                <td class="ft-val"><div class="e" contenteditable="true" style="min-height:50px;"></div></td>
            </tr>
        </table>
    </div>

    <!-- ═══ BOTTOM SECTION ═══ -->
    <div class="bottom-section">
        <div class="terms-side">
            <div class="term-row">
                <span class="tlbl e" contenteditable="true">૧. એડવાન્સ આપેલી રકમ રૂ.</span>
                <input class="tval" type="text" placeholder="">
            </div>
            <div class="term-row">
                <span class="tlbl e" contenteditable="true">૨. નામ લીસ્ટ સાથે બસ ઉપડતા</span>
            </div>
            <div class="term-row" style="padding-left:18px;">
                <span class="tlbl e" contenteditable="true">પહેલા આપવાની રકમ રૂ.</span>
                <input class="tval" type="text" placeholder="">
            </div>
            <div class="term-row">
                <span class="tlbl e" contenteditable="true">૩. રસ્તામાં ડ્રાઈવર ખર્ચ માટે</span>
            </div>
            <div class="term-row" style="padding-left:18px;">
                <span class="tlbl e" contenteditable="true">આપવાની રકમ રૂ.</span>
                <input class="tval" type="text" placeholder="">
            </div>
            <div class="text-term e" contenteditable="true">
                ૪. પ્રવાસ પૂરો થાયે ડ્રાઈવરને ચૂકતે હિસાબ આપી દેવો.
            </div>
            <div class="text-term e" contenteditable="true" style="margin-top:8px;">
                પાછળ જણાવેલી શરતો આધિન ઉપર ની વિગતો
            </div>
        </div>

        <div class="sign-side">
            <div class="sign-firm e" contenteditable="true">ફર્મ, શ્રી હરીકૃષ્ણ ટ્રાવેલ્સ</div>
            <div class="sign-label e" contenteditable="true">વ્યવસ્થાપક ની સહી</div>
        </div>
    </div>

    <!-- ═══ JURISDICTION ═══ -->
    <div class="jurisdiction e" contenteditable="true">ન્યાય ક્ષેત્ર સુરત કોર્ટ</div>

    <!-- ═══ FOOTER BANNER ═══ -->
    <div class="footer-banner e" contenteditable="true">
        બસ ભાડે રાખવાનો કોન્ટ્રાક્ટ કરેલો છે.<br>
        ખાસ નૉધ - ડ્રાઈવરને રોજના રૂ.૫૦૦/- જમવાના આપવા, પાર્કીંગ ચાર્જ, પૂલ ચાર્જ વગેરે પાર્ટી નો રહેશે.
    </div>

</div>

</body>
</html>
