<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ballard Score NUR-018-B — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size: 8.5in 13in portrait; margin: 0.5in 0.6in 0.5in 0.6in; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 10pt; color: #000; background: #c9c9c9; }

        @media screen {
            body { padding: 52px 0 56px; }
            .paper { width: 8.5in; min-height: 13in; margin: 0 auto; background: #fff; box-shadow: 0 4px 28px rgba(0,0,0,.28); padding: 0.5in 0.6in; }
        }
        @media print {
            html, body { margin: 0; padding: 0; background: #fff; }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .paper {
                width: 100%;
                padding: 0;
                margin: 0;
                box-shadow: none;
            }
            .no-print { display: none !important; }
            .circle-btn { display: none !important; }
            .circled {
                border: 2px solid #000 !important;
                border-radius: 50% !important;
                background: transparent !important;
                color: #000 !important;
            }
            /* ── Selected cell: show filled circle in print ── */
            .score-cell.selected {
                background: #e0e0e0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .score-cell.selected .circled {
                border: 2.5px solid #000 !important;
                border-radius: 50% !important;
                background: #000 !important;
                color: #fff !important;
                font-weight: bold !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .score-cell.selected .score-desc {
                font-weight: bold !important;
            }
            .score-cell img {
                display: block !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* ── FIX: prevent borders from vanishing at page breaks ── */
            .score-table {
                border-collapse: separate !important;
                border-spacing: 0 !important;
                border: 1.5px solid #000 !important;
            }
            .score-table th,
            .score-table td {
                border-right: 1px solid #000 !important;
                border-bottom: 1px solid #000 !important;
                border-top: 0 !important;
                border-left: 0 !important;
            }
            .score-table thead th {
                border-top: 0 !important;
            }
            .score-table tbody tr:first-child td {
                border-top: 0 !important;
            }

            /* ── FIX: repeat table headers across page breaks ── */
            .score-table thead {
                display: table-header-group;
            }

            /* ── FIX: avoid row splitting across pages ── */
            .score-table tbody tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            /* ── FIX: keep section heading with its table ── */
            .sec-h {
                page-break-after: avoid;
                break-after: avoid;
            }

            /* ── FIX: keep the two-col layout together if possible ── */
            .two-col {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            /* Keep scoring-table borders clean too */
            .scoring-table {
                border-collapse: separate !important;
                border-spacing: 0 !important;
                border: 1.5px solid #000 !important;
            }
            .scoring-table th,
            .scoring-table td {
                border-right: 1px solid #000 !important;
                border-bottom: 1px solid #000 !important;
                border-top: 0 !important;
                border-left: 0 !important;
            }

            .maturity-table {
                border-collapse: separate !important;
                border-spacing: 0 !important;
                border: 1.5px solid #000 !important;
            }
            .maturity-table th,
            .maturity-table td {
                border-right: 1px solid #000 !important;
                border-bottom: 1px solid #000 !important;
                border-top: 0 !important;
                border-left: 0 !important;
            }

            .patient-bar {
                border: 1.5px solid #000 !important;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .pb-cell {
                border-right: 1px solid #000 !important;
            }
        }

        /* ── TOOLBAR ── */
        .toolbar {
            position: fixed; top: 0; left: 0; right: 0; height: 46px;
            background: #1e3a5f; color: #fff;
            font-family: 'Segoe UI', system-ui, sans-serif; font-size: 12px;
            display: flex; align-items: center; padding: 0 22px; gap: 14px;
            z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,.35);
        }
        .toolbar .lbl { font-size: 13px; font-weight: 700; }
        .toolbar .tag { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); border-radius: 3px; padding: 2px 9px; font-size: 10px; letter-spacing: .05em; text-transform: uppercase; }
        .toolbar .hint { opacity: .5; font-size: 11px; }
        .toolbar .spacer { flex: 1; }
        .btn-print { background: #fff; color: #1e3a5f; border: none; padding: 6px 20px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; }
        .btn-print:hover { background: #dbeafe; }

        /* ── HEADER ── */
        .header { display: flex; align-items: center; gap: 12px; padding-bottom: 8px; border-bottom: 2.5px solid #000; margin-bottom: 6px; }
        .logo-box { width: 60px; height: 60px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .logo-box img { width: 60px; height: 60px; object-fit: contain; }
        .header-center { flex: 1; text-align: center; line-height: 1.3; }
        .header-center .h-rep  { font-size: 8pt; }
        .header-center .h-prov { font-size: 9.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; }
        .header-center .h-mun  { font-size: 8pt; }
        .header-center .h-hosp { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; margin-top: 2px; }

        /* ── TITLE ── */
        .title-band { text-align: center; margin: 8px 0 4px; }
        .title-band h1 { font-size: 12pt; font-weight: bold; text-transform: uppercase; letter-spacing: .1em; border-bottom: 1.5px solid #000; padding-bottom: 3px; display: inline-block; }
        .title-band .subtitle { font-size: 8.5pt; font-style: italic; margin-top: 3px; }
        .form-code { text-align: right; font-size: 8pt; font-family: monospace; color: #555; margin-bottom: 6px; }

        /* ── PATIENT BAR ── */
        .patient-bar { border: 1px solid #000; display: grid; grid-template-columns: 2fr 1fr 1fr; margin-bottom: 8px; }
        .pb-cell { padding: 4px 8px; border-right: 1px solid #000; font-size: 9.5pt; }
        .pb-cell:last-child { border-right: none; }
        .pb-label { font-size: 7pt; text-transform: uppercase; letter-spacing: .05em; color: #555; }
        .pb-value { font-weight: bold; margin-top: 1px; min-height: 14px; }

        /* ── SECTION HEADING ── */
        .sec-h { background: #111827; color: #fff; font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: .08em; padding: 3px 8px; margin: 8px 0 4px; }

        /* ── SCORING TABLE ── */
        .score-table { width: 100%; border-collapse: collapse; font-size: 8pt; margin-bottom: 8px; }
        .score-table th, .score-table td { border: 1px solid #000; text-align: center; vertical-align: middle; padding: 3px 4px; line-height: 1.2; }
        .score-table thead th { background: #f3f4f6; font-weight: bold; font-size: 7.5pt; text-transform: uppercase; letter-spacing: .04em; }
        .score-table .row-label { text-align: left; font-weight: bold; padding-left: 6px; font-size: 8pt; background: #f9fafb; white-space: nowrap; }
        .score-table .sub-label { font-size: 6.5pt; font-weight: normal; display: block; color: #555; }

        /* Score cell */
        .score-cell { position: relative; cursor: pointer; min-width: 62px; min-height: 34px; }
        .score-desc { font-size: 7pt; line-height: 1.2; color: #374151; }
        .score-cell.selected .score-desc { color: #000; }
        .score-cell.selected { background: #e0f2fe; }
        .circled { display: inline-block; width: 22px; height: 22px; border-radius: 50%; line-height: 22px; font-size: 8pt; font-weight: bold; border: 2px solid #9ca3af; color: #9ca3af; margin-bottom: 2px; }
        .score-cell.selected .circled { border-color: #1d4ed8; color: #1d4ed8; background: #dbeafe; }

        /* NM image inside score cell */
        .nm-img {
            width: 58px;
            height: 40px;
            object-fit: contain;
            display: block;
            margin: 0 auto 3px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 2px;
            background: #fff;
        }
        .score-cell.selected .nm-img {
            border-color: #93c5fd;
            background: #eff6ff;
        }

        /* ── TWO COLUMN LAYOUT ── */
        .two-col { display: grid; grid-template-columns: 1fr 340px; gap: 12px; margin-top: 8px; }
        .right-col { display: flex; flex-direction: column; gap: 8px; }

        /* ── INFO BOXES ── */
        .info-row { display: flex; align-items: baseline; gap: 6px; margin-bottom: 5px; font-size: 9pt; }
        .info-row .ir-label { font-size: 8.5pt; white-space: nowrap; min-width: 90px; }
        .info-line { flex: 1; border-bottom: 1px solid #000; min-height: 16px; }
        .info-unit { font-size: 8pt; white-space: nowrap; }

        /* ── MATURITY RATING TABLE ── */
        .maturity-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
        .maturity-table th { background: #111827; color: #fff; padding: 3px 0; text-align: center; font-size: 7.5pt; text-transform: uppercase; letter-spacing: .05em; }
        .maturity-table td { border: 1px solid #000; text-align: center; padding: 2px 6px; }
        .maturity-table tr.highlight td { background: #fef9c3; font-weight: bold; }

        /* ── SCORING SECTION ── */
        .scoring-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
        .scoring-table th { background: #111827; color: #fff; padding: 3px 10px; text-align: center; font-size: 7.5pt; text-transform: uppercase; letter-spacing: .05em; }
        .scoring-table td { border: 1px solid #000; padding: 5px 8px; vertical-align: top; }
        .scoring-table .sl-label { font-size: 7.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; color: #555; display: block; margin-bottom: 3px; }
        .scoring-table .sl-line { border-bottom: 1px solid #000; min-height: 16px; margin-bottom: 4px; }
        .scoring-table .sl-unit { font-size: 7pt; color: #555; }

        /* ── TOTAL SCORE DISPLAY ── */
        .total-bar { border: 2px solid #000; display: grid; grid-template-columns: 1fr 1fr; margin-top: 8px; }
        .total-cell { padding: 6px 10px; text-align: center; }
        .total-cell:first-child { border-right: 2px solid #000; }
        .total-cell .tc-label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: .04em; color: #555; }
        .total-cell .tc-value { font-size: 22pt; font-weight: bold; font-family: 'Segoe UI', system-ui, sans-serif; color: #1d4ed8; line-height: 1; margin-top: 2px; }
        .total-cell .tc-weeks { font-size: 9pt; color: #059669; font-weight: bold; margin-top: 2px; }

        /* ── PRINT HINT ── */
        .screen-tip { font-family: 'Segoe UI', system-ui, sans-serif; font-size: 9.5px; color: #374151; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 5px 12px; margin-bottom: 8px; }
        @media print { .screen-tip { display: none !important; } }

    </style>
</head>
<body>

<div class="toolbar no-print">
    <span class="lbl">LUMC · Ballard Score</span>
    <span class="tag">NUR-018-B</span>
    @isset($patient)<span class="tag" style="background:rgba(16,185,129,.22);border-color:rgba(16,185,129,.5);">{{ $patient->case_no ?? '' }}</span>@endisset
    <span class="hint">Click a score cell to select · Print produces hardcopy</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="paper">

    <div class="screen-tip no-print">
        Click any score cell to circle/select a value. The total score and gestational age will update automatically.
    </div>

    {{-- FORM CODE --}}
    <div class="form-code">NUR-018-B</div>

    {{-- HEADER --}}
    <div class="header">
        <div class="logo-box">
            <img src="{{ asset('images/province-logo.png') }}" alt="Province Logo">
        </div>
        <div class="header-center">
            <div class="h-rep">Republic of the Philippines</div>
            <div class="h-prov">Province of La Union</div>
            <div class="h-mun">Municipality of Agoo, La Union</div>
            <div class="h-hosp">La Union Medical Center</div>
        </div>
        <div class="logo-box">
            <img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC Logo">
        </div>
    </div>

    {{-- TITLE --}}
    <div class="title-band">
        <h1>Newborn Maturity Rating and Classification</h1>
        <div class="subtitle">Estimation of Gestational Age by Maturity Rating</div>
    </div>

    {{-- PATIENT BAR --}}
    <div class="patient-bar">
        <div class="pb-cell">
            <div class="pb-label">Patient Name</div>
            <div class="pb-value">{{ isset($patient) ? strtoupper($patient->full_name) : '' }}</div>
        </div>
        <div class="pb-cell">
            <div class="pb-label">Case No.</div>
            <div class="pb-value">{{ $patient->case_no ?? '' }}</div>
        </div>
        <div class="pb-cell">
            <div class="pb-label">Date</div>
            <div class="pb-value">{{ $today ?? '' }}</div>
        </div>
    </div>

    {{-- HEADER INFO ROW --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:6px;font-size:9pt;">
        <div>
            <div class="info-row"><span class="ir-label">Gestation by Dates:</span><span class="info-line"></span><span class="info-unit">wks &nbsp;&nbsp;</span><span class="info-line" style="max-width:40px;"></span><span class="info-unit">days</span></div>
            <div class="info-row"><span class="ir-label">Birth date:</span><span class="info-line"></span><span class="info-unit">Hour:</span><span class="info-line" style="max-width:60px;"></span></div>
            <div class="info-row"><span class="ir-label">APGAR:</span><span class="info-line" style="max-width:40px;"></span><span class="info-unit">1 min &nbsp;</span><span class="info-line" style="max-width:40px;"></span><span class="info-unit">5 min</span></div>
        </div>
        <div style="font-size:8pt;font-style:italic;padding-top:4px;">
            <strong>Symbols:</strong> &nbsp; <strong>X</strong> = 1st Exam &nbsp;&nbsp; <strong>O</strong> = 2nd Exam
        </div>
    </div>

    {{-- NEUROMUSCULAR MATURITY --}}
    <div class="sec-h">Neuromuscular Maturity</div>

    <table class="score-table" id="neuro-table">
        <thead>
            <tr>
                <th style="width:110px;">Feature</th>
                <th>Score 0</th>
                <th>Score 1</th>
                <th>Score 2</th>
                <th>Score 3</th>
                <th>Score 4</th>
                <th>Score 5</th>
                <th style="width:60px;">Score</th>
            </tr>
        </thead>
        <tbody>

            {{-- POSTURE --}}
            <tr>
                <td class="row-label">Posture</td>
                @for($i = 0; $i <= 4; $i++)
                <td class="score-cell" data-row="posture" data-score="{{ $i }}" onclick="selectScore(this)">
                    <img src="{{ asset('images/ballard/nm/nmPosture/' . $i . '.png') }}"
                         alt="Posture {{ $i }}" class="nm-img"
                         onerror="this.style.display='none'">
                    <span class="circled">{{ $i }}</span><br>
                    <span class="score-desc">{{ ['Flat, no flexion','Slight hip/leg flexion','Stronger leg flexion','Arms & legs flexed','Full flexion'][$i] }}</span>
                </td>
                @endfor
                <td style="background:#f9fafb;opacity:.4;cursor:not-allowed;"></td>
                <td id="score-posture" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>

            {{-- SQUARE WINDOW --}}
            <tr>
                <td class="row-label">Square Window<span class="sub-label">(Wrist)</span></td>
                @php $squareDescs = ['90°','60°','45°','30°','0°']; @endphp
                @for($i = 0; $i <= 4; $i++)
                <td class="score-cell" data-row="square" data-score="{{ $i }}" onclick="selectScore(this)">
                    <img src="{{ asset('images/ballard/nm/nmSquareWindow/' . $i . '.png') }}"
                         alt="Square Window {{ $i }}" class="nm-img"
                         onerror="this.style.display='none'">
                    <span class="circled">{{ $i }}</span><br>
                    <span class="score-desc">{{ $squareDescs[$i] }}</span>
                </td>
                @endfor
                <td style="background:#f9fafb;opacity:.4;cursor:not-allowed;"></td>
                <td id="score-square" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>

            {{-- ARM RECOIL --}}
            <tr>
                <td class="row-label">Arm Recoil</td>
                @php $armDescs = ['180° (none)','140–180°','110–140°','90–110°','< 90°']; @endphp
                @for($i = 0; $i <= 4; $i++)
                <td class="score-cell" data-row="arm" data-score="{{ $i }}" onclick="selectScore(this)">
                    <img src="{{ asset('images/ballard/nm/nmArmRecoil/' . $i . '.png') }}"
                         alt="Arm Recoil {{ $i }}" class="nm-img"
                         onerror="this.style.display='none'">
                    <span class="circled">{{ $i }}</span><br>
                    <span class="score-desc">{{ $armDescs[$i] }}</span>
                </td>
                @endfor
                <td style="background:#f9fafb;opacity:.4;cursor:not-allowed;"></td>
                <td id="score-arm" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>

            {{-- POPLITEAL ANGLE --}}
            <tr>
                <td class="row-label">Popliteal Angle</td>
                @php $poplitealDescs = ['180°','160°','130°','110°','90°','< 90°']; @endphp
                @for($i = 0; $i <= 5; $i++)
                <td class="score-cell" data-row="popliteal" data-score="{{ $i }}" onclick="selectScore(this)">
                    <img src="{{ asset('images/ballard/nm/nmPoplitealAngle/' . $i . '.png') }}"
                         alt="Popliteal Angle {{ $i }}" class="nm-img"
                         onerror="this.style.display='none'">
                    <span class="circled">{{ $i }}</span><br>
                    <span class="score-desc">{{ $poplitealDescs[$i] }}</span>
                </td>
                @endfor
                <td id="score-popliteal" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>

            {{-- SCARF SIGN --}}
            <tr>
                <td class="row-label">Scarf Sign</td>
                @php $scarfDescs = ['Elbow to opposite axilla','Elbow past midline','Elbow at midline','Elbow short of midline','Elbow cannot reach midline']; @endphp
                @for($i = 0; $i <= 4; $i++)
                <td class="score-cell" data-row="scarf" data-score="{{ $i }}" onclick="selectScore(this)">
                    <img src="{{ asset('images/ballard/nm/nmScarfSign/' . $i . '.png') }}"
                         alt="Scarf Sign {{ $i }}" class="nm-img"
                         onerror="this.style.display='none'">
                    <span class="circled">{{ $i }}</span><br>
                    <span class="score-desc">{{ $scarfDescs[$i] }}</span>
                </td>
                @endfor
                <td style="background:#f9fafb;opacity:.4;cursor:not-allowed;"></td>
                <td id="score-scarf" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>

            {{-- HEEL TO EAR --}}
            <tr>
                <td class="row-label">Heel to Ear</td>
                @php $heelDescs = ['Touches ear, full extension','Near ear','Near face','Near umbilicus','Near knee']; @endphp
                @for($i = 0; $i <= 4; $i++)
                <td class="score-cell" data-row="heel" data-score="{{ $i }}" onclick="selectScore(this)">
                    <img src="{{ asset('images/ballard/nm/nmHeelToEar/' . $i . '.png') }}"
                         alt="Heel to Ear {{ $i }}" class="nm-img"
                         onerror="this.style.display='none'">
                    <span class="circled">{{ $i }}</span><br>
                    <span class="score-desc">{{ $heelDescs[$i] }}</span>
                </td>
                @endfor
                <td style="background:#f9fafb;opacity:.4;cursor:not-allowed;"></td>
                <td id="score-heel" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>

        </tbody>
    </table>

    {{-- PHYSICAL MATURITY --}}
    <div class="sec-h">Physical Maturity</div>

    <table class="score-table" id="physical-table">
        <thead>
            <tr>
                <th style="width:110px;">Feature</th>
                <th>Score 0</th>
                <th>Score 1</th>
                <th>Score 2</th>
                <th>Score 3</th>
                <th>Score 4</th>
                <th>Score 5</th>
                <th style="width:60px;">Score</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="row-label">Skin</td>
                <td class="score-cell" data-row="skin" data-score="0" onclick="selectScore(this)"><span class="circled">0</span><br><span class="score-desc">Gelatinous, red, translucent</span></td>
                <td class="score-cell" data-row="skin" data-score="1" onclick="selectScore(this)"><span class="circled">1</span><br><span class="score-desc">Smooth, pink, visible veins</span></td>
                <td class="score-cell" data-row="skin" data-score="2" onclick="selectScore(this)"><span class="circled">2</span><br><span class="score-desc">Superficial peeling &amp; rash, few veins</span></td>
                <td class="score-cell" data-row="skin" data-score="3" onclick="selectScore(this)"><span class="circled">3</span><br><span class="score-desc">Cracking, pale areas, rare veins</span></td>
                <td class="score-cell" data-row="skin" data-score="4" onclick="selectScore(this)"><span class="circled">4</span><br><span class="score-desc">Parchment, deep cracking, no vessels</span></td>
                <td class="score-cell" data-row="skin" data-score="5" onclick="selectScore(this)"><span class="circled">5</span><br><span class="score-desc">Leathery, cracked, wrinkled</span></td>
                <td id="score-skin" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>
            <tr>
                <td class="row-label">Lanugo</td>
                <td class="score-cell" data-row="lanugo" data-score="0" onclick="selectScore(this)"><span class="circled">0</span><br><span class="score-desc">None</span></td>
                <td class="score-cell" data-row="lanugo" data-score="1" onclick="selectScore(this)"><span class="circled">1</span><br><span class="score-desc">Sparse</span></td>
                <td class="score-cell" data-row="lanugo" data-score="2" onclick="selectScore(this)"><span class="circled">2</span><br><span class="score-desc">Abundant</span></td>
                <td class="score-cell" data-row="lanugo" data-score="3" onclick="selectScore(this)"><span class="circled">3</span><br><span class="score-desc">Thinning</span></td>
                <td class="score-cell" data-row="lanugo" data-score="4" onclick="selectScore(this)"><span class="circled">4</span><br><span class="score-desc">Bald areas</span></td>
                <td class="score-cell" data-row="lanugo" data-score="5" onclick="selectScore(this)"><span class="circled">5</span><br><span class="score-desc">Mostly bald</span></td>
                <td id="score-lanugo" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>
            <tr>
                <td class="row-label">Plantar Surface</td>
                <td class="score-cell" data-row="plantar" data-score="0" onclick="selectScore(this)"><span class="circled">0</span><br><span class="score-desc">Heel-toe 40–50mm: –1; &lt;40mm: –2</span></td>
                <td class="score-cell" data-row="plantar" data-score="1" onclick="selectScore(this)"><span class="circled">1</span><br><span class="score-desc">&gt; 50mm, no crease</span></td>
                <td class="score-cell" data-row="plantar" data-score="2" onclick="selectScore(this)"><span class="circled">2</span><br><span class="score-desc">Faint red marks</span></td>
                <td class="score-cell" data-row="plantar" data-score="3" onclick="selectScore(this)"><span class="circled">3</span><br><span class="score-desc">Anterior transverse crease only</span></td>
                <td class="score-cell" data-row="plantar" data-score="4" onclick="selectScore(this)"><span class="circled">4</span><br><span class="score-desc">Creases ant. 2/3</span></td>
                <td class="score-cell" data-row="plantar" data-score="5" onclick="selectScore(this)"><span class="circled">5</span><br><span class="score-desc">Creases over entire sole</span></td>
                <td id="score-plantar" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>
            <tr>
                <td class="row-label">Breast</td>
                <td class="score-cell" data-row="breast" data-score="0" onclick="selectScore(this)"><span class="circled">0</span><br><span class="score-desc">Imperceptible</span></td>
                <td class="score-cell" data-row="breast" data-score="1" onclick="selectScore(this)"><span class="circled">1</span><br><span class="score-desc">Barely perceptible</span></td>
                <td class="score-cell" data-row="breast" data-score="2" onclick="selectScore(this)"><span class="circled">2</span><br><span class="score-desc">Flat areola, no bud</span></td>
                <td class="score-cell" data-row="breast" data-score="3" onclick="selectScore(this)"><span class="circled">3</span><br><span class="score-desc">Stippled areola, 1–2mm bud</span></td>
                <td class="score-cell" data-row="breast" data-score="4" onclick="selectScore(this)"><span class="circled">4</span><br><span class="score-desc">Raised areola, 3–4mm bud</span></td>
                <td class="score-cell" data-row="breast" data-score="5" onclick="selectScore(this)"><span class="circled">5</span><br><span class="score-desc">Full areola, 5–10mm bud</span></td>
                <td id="score-breast" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>
            <tr>
                <td class="row-label">Eye / Ear</td>
                <td class="score-cell" data-row="eye" data-score="0" onclick="selectScore(this)"><span class="circled">0</span><br><span class="score-desc">Lids fused loosely: –1; tightly: –2</span></td>
                <td class="score-cell" data-row="eye" data-score="1" onclick="selectScore(this)"><span class="circled">1</span><br><span class="score-desc">Lids open, pinna flat, stays folded</span></td>
                <td class="score-cell" data-row="eye" data-score="2" onclick="selectScore(this)"><span class="circled">2</span><br><span class="score-desc">Sl. curved pinna, soft, slow recoil</span></td>
                <td class="score-cell" data-row="eye" data-score="3" onclick="selectScore(this)"><span class="circled">3</span><br><span class="score-desc">Well-curved pinna, soft but ready recoil</span></td>
                <td class="score-cell" data-row="eye" data-score="4" onclick="selectScore(this)"><span class="circled">4</span><br><span class="score-desc">Formed &amp; firm, instant recoil</span></td>
                <td class="score-cell" data-row="eye" data-score="5" onclick="selectScore(this)"><span class="circled">5</span><br><span class="score-desc">Thick cartilage, ear stiff</span></td>
                <td id="score-eye" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>
            <tr>
                <td class="row-label">Genitals<span class="sub-label">(Male)</span></td>
                <td class="score-cell" data-row="genm" data-score="0" onclick="selectScore(this)"><span class="circled">0</span><br><span class="score-desc">Scrotum flat, smooth</span></td>
                <td class="score-cell" data-row="genm" data-score="1" onclick="selectScore(this)"><span class="circled">1</span><br><span class="score-desc">Scrotum empty, faint rugae</span></td>
                <td class="score-cell" data-row="genm" data-score="2" onclick="selectScore(this)"><span class="circled">2</span><br><span class="score-desc">Testes in upper canal, rare rugae</span></td>
                <td class="score-cell" data-row="genm" data-score="3" onclick="selectScore(this)"><span class="circled">3</span><br><span class="score-desc">Testes descending, few rugae</span></td>
                <td class="score-cell" data-row="genm" data-score="4" onclick="selectScore(this)"><span class="circled">4</span><br><span class="score-desc">Testes down, good rugae</span></td>
                <td class="score-cell" data-row="genm" data-score="5" onclick="selectScore(this)"><span class="circled">5</span><br><span class="score-desc">Testes pendulous, deep rugae</span></td>
                <td id="score-genm" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>
            <tr>
                <td class="row-label">Genitals<span class="sub-label">(Female)</span></td>
                <td class="score-cell" data-row="genf" data-score="0" onclick="selectScore(this)"><span class="circled">0</span><br><span class="score-desc">Clitoris prominent, labia flat</span></td>
                <td class="score-cell" data-row="genf" data-score="1" onclick="selectScore(this)"><span class="circled">1</span><br><span class="score-desc">Clitoris prominent, small labia minora</span></td>
                <td class="score-cell" data-row="genf" data-score="2" onclick="selectScore(this)"><span class="circled">2</span><br><span class="score-desc">Clitoris prominent, enlarging minora</span></td>
                <td class="score-cell" data-row="genf" data-score="3" onclick="selectScore(this)"><span class="circled">3</span><br><span class="score-desc">Majora &amp; minora equally prominent</span></td>
                <td class="score-cell" data-row="genf" data-score="4" onclick="selectScore(this)"><span class="circled">4</span><br><span class="score-desc">Majora large, minora small</span></td>
                <td class="score-cell" data-row="genf" data-score="5" onclick="selectScore(this)"><span class="circled">5</span><br><span class="score-desc">Majora cover clitoris &amp; minora</span></td>
                <td id="score-genf" style="font-weight:bold;font-size:12pt;text-align:center;">—</td>
            </tr>
        </tbody>
    </table>

    {{-- BOTTOM TWO COLUMN --}}
    <div class="two-col">
        <div>
            {{-- TOTAL SCORE --}}
            <div class="total-bar no-print">
                <div class="total-cell">
                    <div class="tc-label">Total Score</div>
                    <div class="tc-value" id="total-score">—</div>
                </div>
                <div class="total-cell">
                    <div class="tc-label">Gestational Age</div>
                    <div class="tc-weeks" id="total-weeks">— weeks</div>
                </div>
            </div>

            {{-- SCORING SECTION --}}
            <div class="sec-h" style="margin-top:10px;">Scoring Section</div>
            <table class="scoring-table">
                <thead>
                    <tr>
                        <th style="width:38%;">Item</th>
                        <th>1st Exam (X)</th>
                        <th>2nd Exam (O)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="sl-label">Estimating Gest. Age by Maturity Rating</span></td>
                        <td><div class="sl-line"></div><span class="sl-unit">weeks</span></td>
                        <td><div class="sl-line"></div><span class="sl-unit">weeks</span></td>
                    </tr>
                    <tr>
                        <td><span class="sl-label">Time of Exam</span></td>
                        <td><div class="sl-line"></div><span class="sl-unit">Date</span><div class="sl-line" style="margin-top:5px;"></div><span class="sl-unit">Hour ___pm</span></td>
                        <td><div class="sl-line"></div><span class="sl-unit">Date</span><div class="sl-line" style="margin-top:5px;"></div><span class="sl-unit">Hour ___pm</span></td>
                    </tr>
                    <tr>
                        <td><span class="sl-label">Age at Exam</span></td>
                        <td><div class="sl-line"></div><span class="sl-unit">Hours</span></td>
                        <td><div class="sl-line"></div><span class="sl-unit">Hours</span></td>
                    </tr>
                    <tr>
                        <td><span class="sl-label">Signature of Examiner</span></td>
                        <td style="height:40px;"><div class="sl-line" style="margin-top:24px;"></div><span class="sl-unit">M.D./M.D.R.</span></td>
                        <td style="height:40px;"><div class="sl-line" style="margin-top:24px;"></div><span class="sl-unit">M.D./M.D.R.</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="right-col">
            <div>
                <div class="sec-h">Maturity Rating</div>
                <table class="maturity-table">
                    <thead>
                        <tr><th>Score</th><th>Weeks</th></tr>
                    </thead>
                    <tbody id="maturity-body">
                        <tr data-score="10"><td>10</td><td>28</td></tr>
                        <tr data-score="15"><td>15</td><td>30</td></tr>
                        <tr data-score="20"><td>20</td><td>32</td></tr>
                        <tr data-score="25"><td>25</td><td>34</td></tr>
                        <tr data-score="30"><td>30</td><td>36</td></tr>
                        <tr data-score="35"><td>35</td><td>38</td></tr>
                        <tr data-score="40"><td>40</td><td>40</td></tr>
                        <tr data-score="45"><td>45</td><td>42</td></tr>
                        <tr data-score="50"><td>50</td><td>44</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>{{-- /paper --}}

<script>
    const scores = {};

    // Mirror the PHP $gaLookup table exactly — key = score, value = weeks.
    // Uses the same "closest key" logic as the PHP lookupGa() method so the
    // print view always shows the same GA as the Livewire exam form.
    const gaLookup = {
        '-10': 20,
        '-5':  22,
        '0':   24,
        '5':   26,
        '10':  28,
        '15':  30,
        '20':  32,
        '25':  34,
        '30':  36,
        '35':  38,
        '40':  40,
        '45':  42,
        '50':  44,
    };

    function getWeeks(total) {
        const keys = Object.keys(gaLookup).map(Number).sort((a, b) => a - b);
        const minKey = keys[0];
        const maxKey = keys[keys.length - 1];

        // Clamp to table bounds
        if (total <= minKey) return gaLookup[minKey];
        if (total >= maxKey) return gaLookup[maxKey];

        // Find the closest key — same logic as PHP collect()->sortBy(abs diff)->first()
        let closestKey = keys[0];
        let closestDiff = Math.abs(keys[0] - total);
        for (const k of keys) {
            const diff = Math.abs(k - total);
            if (diff < closestDiff) {
                closestDiff = diff;
                closestKey  = k;
            }
        }
        return gaLookup[closestKey];
    }

    function selectScore(cell) {
        const row   = cell.dataset.row;
        const score = parseInt(cell.dataset.score);

        document.querySelectorAll(`[data-row="${row}"]`).forEach(c => c.classList.remove('selected'));

        if (scores[row] === score) {
            delete scores[row];
        } else {
            cell.classList.add('selected');
            scores[row] = score;
        }

        const scoreCell = document.getElementById('score-' + row);
        if (scoreCell) scoreCell.textContent = scores[row] !== undefined ? scores[row] : '—';

        updateTotal();
    }

    function updateTotal() {
        const neuroRows    = ['posture','square','arm','popliteal','scarf','heel'];
        const physicalRows = ['skin','lanugo','plantar','breast','eye','genm','genf'];
        const allRows = [...neuroRows, ...physicalRows];

        let total = 0;
        allRows.forEach(r => {
            if (scores[r] !== undefined) total += scores[r];
        });

        const totalEl = document.getElementById('total-score');
        const weeksEl = document.getElementById('total-weeks');
        const rows    = document.querySelectorAll('#maturity-body tr');

        if (Object.keys(scores).length === 0) {
            totalEl.textContent = '—';
            weeksEl.textContent = '— weeks';
            rows.forEach(r => r.classList.remove('highlight'));
            return;
        }

        totalEl.textContent = total;
        const weeks = getWeeks(total);
        weeksEl.textContent = weeks ? weeks + ' weeks' : '? weeks';

        rows.forEach(r => {
            const s = parseInt(r.dataset.score);
            r.classList.toggle('highlight', s <= total && s + 5 > total);
        });
    }
</script>

{{-- AUTO-POPULATE SAVED SCORES FROM DATABASE --}}
@php
    $exam1 = isset($visit)
        ? \App\Models\NicuBallardExam::where('visit_id', $visit->id)
            ->where('exam_number', 1)
            ->first()
        : null;
@endphp

@if($exam1)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const saved = {
        posture:   {{ $exam1->nm_posture         ?? 'null' }},
        square:    {{ $exam1->nm_square_window   ?? 'null' }},
        arm:       {{ $exam1->nm_arm_recoil      ?? 'null' }},
        popliteal: {{ $exam1->nm_popliteal_angle ?? 'null' }},
        scarf:     {{ $exam1->nm_scarf_sign      ?? 'null' }},
        heel:      {{ $exam1->nm_heel_to_ear     ?? 'null' }},
        skin:      {{ $exam1->pm_skin            ?? 'null' }},
        lanugo:    {{ $exam1->pm_lanugo          ?? 'null' }},
        plantar:   {{ $exam1->pm_plantar_surface ?? 'null' }},
        breast:    {{ $exam1->pm_breast          ?? 'null' }},
        eye:       {{ $exam1->pm_eye_ear         ?? 'null' }},
        genm:      {{ $exam1->pm_genitals        ?? 'null' }},
    };

    Object.entries(saved).forEach(([row, score]) => {
        if (score === null) return;
        const cell = document.querySelector(`[data-row="${row}"][data-score="${score}"]`);
        if (cell) selectScore(cell);
    });
});
</script>
@endif

</body>
</html>