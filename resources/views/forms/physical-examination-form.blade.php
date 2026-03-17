{{--
    NUR-005 — Physical Examination Form
    Path : resources/views/forms/physical-examination-form.blade.php
    Route: GET /forms/physical-exam-form/{visit}

    Variables (from ChartController::physicalExamForm):
      $visit, $patient, $history, $doctor, $vitals, $today
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physical Examination — NUR-005 — LA UNION MEDICAL CENTER</title>
    <style>

        @page {
            size: 8.5in 14in portrait;
            margin: 0.65in 0.75in 0.65in 0.75in;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #c9c9c9;
        }

        @media screen {
            body  { padding: 52px 0 56px; }
            .paper {
                width: 8.5in;
                min-height: 14in;
                margin: 0 auto;
                background: #fff;
                box-shadow: 0 4px 28px rgba(0,0,0,.28);
                padding: 0.65in 0.75in;
            }
        }
        @media print {
            body  { background: #fff; padding: 0; }
            .paper { width: 100%; padding: 0; box-shadow: none; }
            .no-print { display: none !important; }
            [contenteditable] {
                outline: none !important;
                box-shadow: none !important;
                background: transparent !important;
            }
        }

        /* ── TOOLBAR ────────────────────────────────────────────────── */
        .toolbar {
            position: fixed; top: 0; left: 0; right: 0; height: 46px;
            background: #1e3a5f; color: #fff;
            font-family: 'Segoe UI', system-ui, sans-serif; font-size: 12px;
            display: flex; align-items: center; padding: 0 22px; gap: 14px;
            z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,.35);
        }
        .toolbar .lbl  { font-size: 13px; font-weight: 700; }
        .toolbar .tag  {
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
            border-radius: 3px; padding: 2px 9px;
            font-size: 10px; letter-spacing: .05em; text-transform: uppercase;
        }
        .toolbar .hint { opacity: .5; font-size: 11px; }
        .toolbar .spacer { flex: 1; }
        .btn-print {
            background: #fff; color: #1e3a5f; border: none; padding: 6px 20px;
            border-radius: 4px; font-size: 12px; font-weight: 700;
            cursor: pointer; font-family: inherit;
        }
        .btn-print:hover { background: #dbeafe; }

        /* ── HEADER ─────────────────────────────────────────────────── */
        .header {
            display: flex; align-items: center; gap: 12px;
            padding-bottom: 9px; border-bottom: 2.5px solid #000;
        }
        .logo-box { width: 68px; height: 68px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .logo-box img { width: 68px; height: 68px; object-fit: contain; }
        .logo-ph {
            width: 68px; height: 68px; flex-shrink: 0;
            border: 1.5px dashed #bbb; display: flex; align-items: center;
            justify-content: center; font-size: 7.5pt; color: #bbb; text-align: center; line-height: 1.4;
        }
        .header-center { flex: 1; text-align: center; line-height: 1.35; }
        .header-center .h-rep  { font-size: 8.5pt; }
        .header-center .h-prov { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; }
        .header-center .h-mun  { font-size: 8.5pt; }
        .header-center .h-hosp { font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; }

        /* ── TITLE ──────────────────────────────────────────────────── */
        .title-band { text-align: center; margin: 14px 0 6px; }
        .title-band h1 {
            display: inline-block; font-size: 13pt; font-weight: bold;
            text-transform: uppercase; letter-spacing: .12em;
            border-bottom: 1.5px solid #000; padding-bottom: 3px;
        }
        .form-code { text-align: center; font-size: 8.5pt; color: #555; margin-bottom: 14px; }

        /* ── PATIENT INFO BAR ───────────────────────────────────────── */
        .patient-bar {
            border: 1px solid #000;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            margin-bottom: 12px;
        }
        .pb-cell {
            padding: 5px 8px;
            border-right: 1px solid #000;
            font-size: 10pt;
        }
        .pb-cell:last-child { border-right: none; }
        .pb-label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: .05em; color: #555; }
        .pb-value { font-weight: bold; margin-top: 1px; }

        /* ── VITALS SUMMARY BOX ─────────────────────────────────────── */
        .vitals-box {
            border: 1px solid #000;
            margin-bottom: 12px;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }
        .vb-cell {
            padding: 5px 7px;
            border-right: 1px solid #000;
            text-align: center;
        }
        .vb-cell:last-child { border-right: none; }
        .vb-label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: .04em; color: #555; }
        .vb-value {
            font-size: 10.5pt;
            font-weight: bold;
            margin-top: 2px;
        }
        .abnormal { color: #dc2626; }

        /* ── SECTION HEADING ────────────────────────────────────────── */
        .sec-h {
            background: #111827;
            color: #fff;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 3px 8px;
            margin: 10px 0 6px;
        }

        /* ── PE ROW (label + field on same line) ────────────────────── */
        .pe-row {
            display: grid;
            grid-template-columns: 155px 1fr;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 5px;
            font-size: 10.5pt;
        }
        .pe-label {
            font-size: 10pt;
            font-weight: bold;
            text-align: right;
            padding-right: 4px;
        }

        /* ── EDITABLE FIELD ─────────────────────────────────────────── */
        .field {
            display: inline-block;
            border-bottom: 1px solid #000;
            vertical-align: bottom;
            min-height: 18px;
            line-height: 18px;
            padding: 0 3px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            color: #000;
            outline: none;
            cursor: text;
            white-space: pre-wrap;
            overflow: visible;
        }
        .field-full { width: 100%; }

        .field-block {
            border: 1px solid #000;
            min-height: 54px;
            padding: 5px 7px;
            font-size: 10.5pt;
            line-height: 1.7;
            display: block;
            width: 100%;
            font-family: 'Times New Roman', Times, serif;
            white-space: pre-wrap;
            outline: none;
            margin-bottom: 6px;
        }

        @media screen {
            .field:focus, .field-block:focus {
                background: #fefce8; border-color: #1d4ed8;
            }
            .field:hover:not(:focus), .field-block:hover:not(:focus) {
                border-color: #555;
            }
        }

        /* ── ADMITTING IMPRESSION ───────────────────────────────────── */
        .impression-block {
            border: 1px solid #000;
            min-height: 60px;
            padding: 6px 8px;
            font-size: 10.5pt;
            font-weight: bold;
            line-height: 1.7;
            display: block;
            width: 100%;
            font-family: 'Times New Roman', Times, serif;
            white-space: pre-wrap;
            outline: none;
            margin-top: 4px;
        }

        /* ── SIGNATURE ROW ──────────────────────────────────────────── */
        .sig-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 14px;
        }
        .sig-block { display: flex; flex-direction: column; }
        .sig-line  { border-bottom: 1px solid #000; height: 42px; width: 100%; display:flex;align-items:flex-end;padding-bottom:3px; }
        .sig-cap   { font-size: 9pt; text-align: center; font-style: italic; margin-top: 3px; }

        /* ── SCREEN TIP ─────────────────────────────────────────────── */
        .screen-tip {
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-size: 10px; color: #374151;
            background: #eff6ff; border: 1px solid #bfdbfe;
            border-radius: 4px; padding: 6px 14px;
            margin-bottom: 14px; line-height: 1.6;
        }
        @media print { .screen-tip { display: none; } }

        .divider { border: none; border-top: 1px solid #000; margin: 12px 0; }
    </style>
</head>
<body>

{{-- TOOLBAR --}}
<div class="toolbar no-print">
    <span class="lbl">LUMC · Physical Examination</span>
    <span class="tag">NUR-005</span>
    @isset($patient)
    <span class="tag" style="background:rgba(16,185,129,.22);border-color:rgba(16,185,129,.45);">
        {{ $patient->case_no }}
    </span>
    @endisset
    <span class="hint">Legal 8.5 × 14 in &nbsp;·&nbsp; Click any field to edit</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()">🖨️&nbsp;&nbsp;Print / Save as PDF</button>
</div>

<div class="paper">

    <div class="screen-tip no-print">
        💡 <strong>Click any underlined / bordered field</strong> to edit before printing.
    </div>

    {{-- HEADER --}}
    <div class="header">
        @if(file_exists(public_path('images/province-logo.png')))
            <div class="logo-box"><img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union"></div>
        @else
            <div class="logo-ph">Province<br>Seal</div>
        @endif

        <div class="header-center">
            <div class="h-rep">Republic of the Philippines</div>
            <div class="h-prov">Province of La Union</div>
            <div class="h-mun">Municipality of Agoo, La Union</div>
            <div class="h-hosp">La Union Medical Center</div>
        </div>

        @if(file_exists(public_path('images/lumc-logo.png')))
            <div class="logo-box"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC Logo"></div>
        @elseif(file_exists(public_path('images/bagong-pilipinas-logo-only.png')))
            <div class="logo-box"><img src="{{ asset('images/bagong-pilipinas-logo-only.png') }}" alt="Bagong Pilipinas"></div>
        @else
            <div class="logo-ph">LUMC<br>Logo</div>
        @endif
    </div>

    {{-- TITLE --}}
    <div class="title-band"><h1>Physical Examination Form</h1></div>
    <div class="form-code">NUR-005 &nbsp;·&nbsp; Physical Assessment</div>

    {{-- PATIENT INFO BAR --}}
    @php
        $pName   = $patient->full_name ?? '';
        $pAge    = ($patient->age_display ?? '') . ' / ' . ($patient->sex ?? '');
        $docName = $doctor ? 'Dr. ' . $doctor->name : '';
        $admDate = $visit->clerk_admitted_at
            ? $visit->clerk_admitted_at->timezone('Asia/Manila')->format('M j, Y')
            : '';
        $service = $visit->admitted_service ?? $history?->service ?? '';
    @endphp

    <div class="patient-bar">
        <div class="pb-cell">
            <p class="pb-label">Patient Name</p>
            <p class="pb-value">{{ $pName }}</p>
        </div>
        <div class="pb-cell">
            <p class="pb-label">Age / Sex</p>
            <p class="pb-value">{{ $pAge }}</p>
        </div>
        <div class="pb-cell">
            <p class="pb-label">Case No.</p>
            <p class="pb-value" style="font-family:monospace;">{{ $patient->case_no ?? '' }}</p>
        </div>
        <div class="pb-cell">
            <p class="pb-label">Admitting Physician</p>
            <p class="pb-value">{{ $docName }}</p>
        </div>
        <div class="pb-cell">
            <p class="pb-label">Date Admitted</p>
            <p class="pb-value">{{ $admDate }}</p>
        </div>
        <div class="pb-cell">
            <p class="pb-label">Service</p>
            <p class="pb-value">{{ $service }}</p>
        </div>
    </div>

    {{-- VITAL SIGNS SUMMARY --}}
    <div class="sec-h">Vital Signs at Admission</div>
    @php
        $vbp  = $vitals?->blood_pressure     ?? '—';
        $vpr  = $vitals?->pulse_rate          ? $vitals->pulse_rate . ' bpm' : '—';
        $vrr  = $vitals?->respiratory_rate    ? $vitals->respiratory_rate . '/min' : '—';
        $vt   = $vitals?->temperature         ? $vitals->temperature . '°C' : '—';
        $vo2  = $vitals?->o2_saturation       ? $vitals->o2_saturation . '%' : '—';
        $vwt  = $vitals?->weight_kg           ? $vitals->weight_kg . ' kg' : '—';
        $vpain= $vitals?->pain_scale !== null ? $vitals->pain_scale . '/10' : '—';

        $prAbn  = $vitals?->pulse_rate        && ($vitals->pulse_rate < 60 || $vitals->pulse_rate > 100);
        $rrAbn  = $vitals?->respiratory_rate  && ($vitals->respiratory_rate < 12 || $vitals->respiratory_rate > 20);
        $tAbn   = $vitals?->temperature       && ($vitals->temperature < 36.0 || $vitals->temperature > 37.5);
        $o2Abn  = $vitals?->o2_saturation     && $vitals->o2_saturation < 95;
        $pnAbn  = $vitals?->pain_scale !== null && (int)$vitals->pain_scale >= 7;
    @endphp
    <div class="vitals-box">
        <div class="vb-cell"><p class="vb-label">BP</p><p class="vb-value">{{ $vbp }}</p></div>
        <div class="vb-cell"><p class="vb-label">Pulse Rate</p><p class="vb-value {{ $prAbn ? 'abnormal':'' }}">{{ $vpr }}</p></div>
        <div class="vb-cell"><p class="vb-label">Resp. Rate</p><p class="vb-value {{ $rrAbn ? 'abnormal':'' }}">{{ $vrr }}</p></div>
        <div class="vb-cell"><p class="vb-label">Temp</p><p class="vb-value {{ $tAbn ? 'abnormal':'' }}">{{ $vt }}</p></div>
        <div class="vb-cell"><p class="vb-label">O₂ Sat</p><p class="vb-value {{ $o2Abn ? 'abnormal':'' }}">{{ $vo2 }}</p></div>
        <div class="vb-cell"><p class="vb-label">Weight</p><p class="vb-value">{{ $vwt }}</p></div>
        <div class="vb-cell"><p class="vb-label">Pain Scale</p><p class="vb-value {{ $pnAbn ? 'abnormal':'' }}">{{ $vpain }}</p></div>
    </div>

    {{-- ══ PHYSICAL EXAMINATION ══ --}}
    <div class="sec-h">Physical Examination — Head to Toe</div>

    @php
        $peFields = [
            ['peSkin',            'pe_skin',            'Skin'],
            ['peHeadEent',        'pe_head_eent',       'Head / EENT'],
            ['peLymphNodes',      'pe_lymph_nodes',     'Lymph Nodes'],
            ['peChest',           'pe_chest',           'Chest'],
            ['peLungs',           'pe_lungs',           'Lungs'],
            ['peCardiovascular',  'pe_cardiovascular',  'Cardiovascular'],
            ['peBreast',          'pe_breast',          'Breast'],
            ['peAbdomen',         'pe_abdomen',         'Abdomen'],
            ['peRectum',          'pe_rectum',          'Rectum'],
            ['peGenitalia',       'pe_genitalia',       'Genitalia'],
            ['peMusculoskeletal', 'pe_musculoskeletal', 'Musculoskeletal'],
            ['peExtremities',     'pe_extremities',     'Extremities'],
            ['peNeurology',       'pe_neurology',       'Neurology'],
        ];
    @endphp

    @foreach($peFields as [$camel, $snake, $label])
    <div class="pe-row">
        <p class="pe-label">{{ $label }}:</p>
        <span
            class="field field-full"
            contenteditable="true"
            spellcheck="false"
            aria-label="{{ $label }}"
        >{{ $history?->{$snake} ?? '' }}</span>
    </div>
    @endforeach

    {{-- ══ ADMITTING IMPRESSION ══ --}}
    <div class="sec-h">Admitting Impression / Diagnosis</div>
    <div
        class="impression-block"
        contenteditable="true"
        spellcheck="false"
        aria-label="Admitting Impression"
    >{{ $history?->admitting_impression ?? '' }}</div>

    @if($history?->diagnosis)
    <div class="pe-row" style="margin-top:6px;">
        <p class="pe-label">Final Diagnosis:</p>
        <span
            class="field field-full"
            contenteditable="true"
            spellcheck="false"
        >{{ $history->diagnosis }}</span>
    </div>
    @endif

    @if($history?->plan)
    <div class="sec-h">Management Plan</div>
    <div
        class="field-block"
        contenteditable="true"
        spellcheck="false"
        style="min-height:44px;"
    >{{ $history->plan }}</div>
    @endif

    <hr class="divider">

    {{-- SIGNATURE --}}
    <div class="sig-row">
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-cap">Signature of Examining Physician</div>
        </div>
        <div class="sig-block">
            <div class="sig-line" style="display:flex;align-items:flex-end;padding-bottom:3px;">
                <span
                    class="field"
                    style="width:100%;border:none;text-align:center;"
                    contenteditable="true"
                    spellcheck="false"
                >{{ $docName }}</span>
            </div>
            <div class="sig-cap">Printed Name</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:12px;">
        <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="border-bottom:1px solid #000;width:100%;height:32px;display:flex;align-items:flex-end;padding-bottom:2px;">
                <span class="field" style="width:100%;border:none;text-align:center;" contenteditable="true" spellcheck="false">{{ $today ?? '' }}</span>
            </div>
            <p style="font-size:9pt;font-style:italic;margin-top:3px;">Date &amp; Time</p>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;">
            <div style="border-bottom:1px solid #000;width:100%;height:32px;"></div>
            <p style="font-size:9pt;font-style:italic;margin-top:3px;">PRC License No.</p>
        </div>
    </div>

</div>{{-- /.paper --}}

<script>
/* Single-line fields: suppress Enter */
document.querySelectorAll('.pe-row .field, .sig-row .field').forEach(function (el) {
    el.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); }
    });
    el.addEventListener('click', function () {
        if (!el.textContent.trim()) return;
        var r = document.createRange();
        r.selectNodeContents(el);
        var s = window.getSelection();
        s.removeAllRanges();
        s.addRange(r);
    });
});
</script>

</body>
</html>