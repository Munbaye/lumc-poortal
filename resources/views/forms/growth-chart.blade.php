{{--
    Growth Chart — WHO Child Growth Standards (Birth to 24 months)
    Path : resources/views/forms/growth-chart.blade.php
    Route: GET /forms/growth-chart/{visit}

    Variables (from ChartController::growthChart):
      $visit, $patient, $today
      $weightMeasurements  — array of ['age_months', 'value', 'date', 'z_score', 'color']
      $lengthMeasurements  — array of ['age_months', 'value', 'date', 'z_score', 'color']
      $gender              — 'boy' | 'girl'

    Usage:
      $weightMeasurements = WHOGrowthChart::buildMeasurements($visit, 'weight', $gender);
      $lengthMeasurements = WHOGrowthChart::buildMeasurements($visit, 'length', $gender);
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growth Chart — WHO — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size: 8.5in 14in portrait; margin: 0.5in 0.6in; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 10pt; color: #000; background: #c9c9c9; }
        @media screen { body { padding: 52px 0 56px; } .paper { width: 8.5in; min-height: 14in; margin: 0 auto; background: #fff; box-shadow: 0 4px 28px rgba(0,0,0,.28); padding: 0.5in 0.6in; } }
        @media print { body { background: #fff; padding: 0; } .paper { width: 100%; padding: 0; box-shadow: none; } .no-print { display: none !important; } }

        /* TOOLBAR */
        .toolbar { position: fixed; top: 0; left: 0; right: 0; height: 46px; background: #1e3a5f; color: #fff; font-family: 'Segoe UI', system-ui, sans-serif; font-size: 12px; display: flex; align-items: center; padding: 0 22px; gap: 14px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,.35); }
        .toolbar .lbl { font-size: 13px; font-weight: 700; }
        .toolbar .tag { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); border-radius: 3px; padding: 2px 9px; font-size: 10px; letter-spacing: .05em; text-transform: uppercase; }
        .toolbar .spacer { flex: 1; }
        .btn-print { background: #fff; color: #1e3a5f; border: none; padding: 6px 20px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; }

        /* HEADER */
        .header { display: flex; align-items: center; gap: 10px; padding-bottom: 7px; border-bottom: 2.5px solid #000; margin-bottom: 5px; }
        .logo-box { width: 56px; height: 56px; flex-shrink: 0; }
        .logo-box img { width: 56px; height: 56px; object-fit: contain; }
        .hc { flex: 1; text-align: center; line-height: 1.35; }
        .hc .rep  { font-size: 7.5pt; }
        .hc .prov { font-size: 9pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; }
        .hc .mun  { font-size: 7.5pt; }
        .hc .hosp { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; margin-top: 2px; }
        .form-code { text-align: right; font-size: 7.5pt; font-family: monospace; color: #555; margin-bottom: 4px; }

        /* TITLE */
        .title-band { text-align: center; margin: 5px 0 2px; }
        .title-band h1 { font-size: 12pt; font-weight: bold; text-transform: uppercase; letter-spacing: .1em; border-bottom: 1.5px solid #000; padding-bottom: 2px; display: inline-block; }
        .subtitle { text-align: center; font-size: 8pt; font-style: italic; margin-bottom: 5px; }

        /* PATIENT BAR */
        .pat-bar { border: 1px solid #000; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; margin-bottom: 6px; }
        .pb { padding: 3px 7px; border-right: 1px solid #000; }
        .pb:last-child { border-right: none; }
        .pb .lbl { font-size: 6.5pt; text-transform: uppercase; letter-spacing: .04em; color: #555; }
        .pb .val { font-weight: bold; font-size: 9.5pt; min-height: 14px; }

        /* GENDER TOGGLE */
        .gender-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; font-size: 9pt; font-family: 'Segoe UI', system-ui, sans-serif; }
        .gender-btn { padding: 4px 18px; border-radius: 9999px; border: 1.5px solid #000; font-size: 9pt; font-weight: 700; cursor: pointer; font-family: inherit; background: #fff; }
        .gender-btn.active-boy  { background: #dbeafe; border-color: #1d4ed8; color: #1d4ed8; }
        .gender-btn.active-girl { background: #fce7f3; border-color: #be185d; color: #be185d; }

        /* SECTION HEADER */
        .sec { background: #111827; color: #fff; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: .08em; padding: 2px 8px; margin: 6px 0 4px; }

        /* LEGEND */
        .legend { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 6px; font-size: 7.5pt; font-family: 'Segoe UI', system-ui, sans-serif; align-items: center; }
        .legend-item { display: flex; align-items: center; gap: 5px; }
        .legend-line { width: 28px; height: 3px; border-radius: 2px; }
        .legend-dot  { width: 10px; height: 10px; border-radius: 50%; border: 1.5px solid #fff; }

        /* CHART CONTAINER */
        .chart-wrap { border: 1px solid #e5e7eb; border-radius: 4px; margin-bottom: 8px; overflow: hidden; }
        .chart-wrap svg { display: block; width: 100%; }

        /* MEASUREMENTS TABLE */
        .meas-tbl { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 6px; }
        .meas-tbl th { background: #111827; color: #fff; padding: 3px 7px; text-align: center; font-size: 8pt; text-transform: uppercase; letter-spacing: .04em; }
        .meas-tbl td { border: 1px solid #000; padding: 3px 7px; text-align: center; font-size: 9pt; }
        .meas-tbl .lbl-cell { text-align: left; font-weight: bold; background: #f9fafb; }
        .z-normal  { color: #059669; font-weight: bold; }
        .z-warning { color: #d97706; font-weight: bold; }
        .z-danger  { color: #dc2626; font-weight: bold; }

        /* STATUS BOX */
        .status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 6px; }
        .status-box { border: 1px solid #000; padding: 5px 8px; }
        .status-box .st-label { font-size: 7pt; text-transform: uppercase; letter-spacing: .04em; color: #555; margin-bottom: 2px; }
        .status-box .st-val { font-size: 10.5pt; font-weight: bold; }
        .status-box .st-sub { font-size: 7.5pt; color: #555; }

        /* SIG */
        .sig-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px; }
        .sig-box { border-top: 1px solid #000; padding-top: 3px; text-align: center; font-size: 8.5pt; }
    </style>
</head>
<body>

<div class="toolbar no-print">
    <span class="lbl">LUMC · Growth Chart</span>
    <span class="tag">WHO 0–24 months</span>
    @isset($patient)
    <span class="tag" style="background:rgba(16,185,129,.22);border-color:rgba(16,185,129,.5);">{{ $patient->case_no ?? '' }}</span>
    @endisset
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="paper">
    <div class="form-code">WHO-GC-001</div>

    {{-- HEADER --}}
    <div class="header">
        <div class="logo-box"><img src="{{ asset('images/province-logo.png') }}" alt="Province Logo"></div>
        <div class="hc">
            <div class="rep">Republic of the Philippines</div>
            <div class="prov">Province of La Union</div>
            <div class="mun">Municipality of Agoo, La Union</div>
            <div class="hosp">La Union Medical Center</div>
        </div>
        <div class="logo-box"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC Logo"></div>
    </div>

    <div class="title-band"><h1>Child Growth Chart</h1></div>
    <div class="subtitle">WHO Child Growth Standards — Birth to 24 Months</div>

    {{-- PATIENT BAR --}}
    <div class="pat-bar">
        <div class="pb">
            <div class="lbl">Patient Name</div>
            <div class="val">{{ isset($patient) ? strtoupper($patient->full_name) : '' }}</div>
        </div>
        <div class="pb">
            <div class="lbl">Case No.</div>
            <div class="val">{{ $patient->case_no ?? '' }}</div>
        </div>
        <div class="pb">
            <div class="lbl">Date of Birth</div>
            <div class="val">{{ isset($patient) && $patient->birthday ? \Carbon\Carbon::parse($patient->birthday)->format('M d, Y') : '' }}</div>
        </div>
        <div class="pb">
            <div class="lbl">Sex</div>
            <div class="val" style="color:{{ ($gender ?? 'boy') === 'girl' ? '#be185d' : '#1d4ed8' }};">
                {{ ($gender ?? 'boy') === 'girl' ? 'Female' : 'Male' }}
            </div>
        </div>
    </div>

    {{-- GENDER LABEL --}}
    <div class="gender-bar no-print">
        <span style="font-weight:bold;font-size:9pt;">Chart for:</span>
        <span class="gender-btn {{ ($gender ?? 'boy') === 'boy' ? 'active-boy' : '' }}" id="btn-boy" onclick="setGender('boy')">Boys</span>
        <span class="gender-btn {{ ($gender ?? 'boy') === 'girl' ? 'active-girl' : '' }}" id="btn-girl" onclick="setGender('girl')">Girls</span>
        <span style="font-size:8.5pt;color:#6b7280;font-family:'Segoe UI',system-ui,sans-serif;">
            Currently showing: <strong>{{ ($gender ?? 'boy') === 'girl' ? 'Girls' : 'Boys' }}</strong> WHO reference curves
        </span>
    </div>

    {{-- ═══════════════════════════════════
         WEIGHT-FOR-AGE CHART
    ═══════════════════════════════════ --}}
    <div class="sec">Weight-for-Age (Birth to 24 Months)</div>

    <div class="legend">
        <div class="legend-item"><div class="legend-line" style="background:#dc2626;"></div><span>-3 SD (Severe underweight)</span></div>
        <div class="legend-item"><div class="legend-line" style="background:#f97316;"></div><span>-2 SD (Underweight)</span></div>
        <div class="legend-item"><div class="legend-line" style="background:#10b981;height:4px;"></div><span>0 SD (Median)</span></div>
        <div class="legend-item"><div class="legend-line" style="background:#f97316;"></div><span>+2 SD (Overweight)</span></div>
        <div class="legend-item"><div class="legend-line" style="background:#dc2626;"></div><span>+3 SD (Obese)</span></div>
        <div class="legend-item"><div class="legend-dot" style="background:#3b82f6;border-color:#1d4ed8;"></div><span>Patient's weight</span></div>
    </div>

    @php
        $gender    = $gender ?? 'boy';
        $wData     = $gender === 'girl'
            ? \App\Helpers\WHOGrowthChart::getWeightGirls()
            : \App\Helpers\WHOGrowthChart::getWeightBoys();
        $lData     = $gender === 'girl'
            ? \App\Helpers\WHOGrowthChart::getLengthGirls()
            : \App\Helpers\WHOGrowthChart::getLengthBoys();

        $weightMeasurements = $weightMeasurements ?? [];
        $lengthMeasurements = $lengthMeasurements ?? [];

        // Render Weight SVG
        $weightSvg = \App\Helpers\WHOGrowthChart::renderChart('weight', $gender, $weightMeasurements);

        // Render Length SVG
        $lengthSvg = \App\Helpers\WHOGrowthChart::renderChart('length', $gender, $lengthMeasurements);
    @endphp

    <div class="chart-wrap">
        {!! $weightSvg !!}
    </div>

    {{-- WEIGHT TABLE --}}
    @if(!empty($weightMeasurements))
    <table class="meas-tbl">
        <thead>
            <tr>
                <th>Date</th>
                <th>Age (months)</th>
                <th>Weight (kg)</th>
                <th>Z-Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($weightMeasurements as $m)
            @php
                $z = $m['z_score'] ?? null;
                $zClass = $z !== null ? ($z < -3 || $z > 3 ? 'z-danger' : ($z < -2 || $z > 2 ? 'z-warning' : 'z-normal')) : '';
                $status = $z !== null
                    ? ($z < -3 ? 'Severely Underweight' : ($z < -2 ? 'Underweight' : ($z > 3 ? 'Obese' : ($z > 2 ? 'Overweight' : 'Normal'))))
                    : '—';
            @endphp
            <tr>
                <td>{{ $m['date'] ?? '' }}</td>
                <td>{{ $m['age_months'] ?? '' }}</td>
                <td style="font-weight:bold;">{{ $m['value'] ?? '' }} kg</td>
                <td class="{{ $zClass }}">{{ $z ?? '—' }}</td>
                <td class="{{ $zClass }}">{{ $status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="border:1px solid #e5e7eb;padding:8px 12px;font-size:8.5pt;color:#9ca3af;text-align:center;margin-bottom:8px;">No weight measurements recorded yet.</div>
    @endif

    {{-- ═══════════════════════════════════
         LENGTH/HEIGHT-FOR-AGE CHART
    ═══════════════════════════════════ --}}
    <div class="sec">Length/Height-for-Age (Birth to 24 Months)</div>

    <div class="legend">
        <div class="legend-item"><div class="legend-line" style="background:#dc2626;"></div><span>-3 SD (Severely stunted)</span></div>
        <div class="legend-item"><div class="legend-line" style="background:#f97316;"></div><span>-2 SD (Stunted)</span></div>
        <div class="legend-item"><div class="legend-line" style="background:#10b981;height:4px;"></div><span>0 SD (Median)</span></div>
        <div class="legend-item"><div class="legend-line" style="background:#f97316;"></div><span>+2 SD (Tall)</span></div>
        <div class="legend-item"><div class="legend-line" style="background:#dc2626;"></div><span>+3 SD (Very tall)</span></div>
        <div class="legend-item"><div class="legend-dot" style="background:#8b5cf6;border-color:#6d28d9;"></div><span>Patient's length</span></div>
    </div>

    <div class="chart-wrap">
        {!! $lengthSvg !!}
    </div>

    {{-- LENGTH TABLE --}}
    @if(!empty($lengthMeasurements))
    <table class="meas-tbl">
        <thead>
            <tr>
                <th>Date</th>
                <th>Age (months)</th>
                <th>Length (cm)</th>
                <th>Z-Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lengthMeasurements as $m)
            @php
                $z = $m['z_score'] ?? null;
                $zClass = $z !== null ? ($z < -3 || $z > 3 ? 'z-danger' : ($z < -2 || $z > 2 ? 'z-warning' : 'z-normal')) : '';
                $status = $z !== null
                    ? ($z < -3 ? 'Severely Stunted' : ($z < -2 ? 'Stunted' : ($z > 3 ? 'Very Tall' : ($z > 2 ? 'Tall' : 'Normal'))))
                    : '—';
            @endphp
            <tr>
                <td>{{ $m['date'] ?? '' }}</td>
                <td>{{ $m['age_months'] ?? '' }}</td>
                <td style="font-weight:bold;">{{ $m['value'] ?? '' }} cm</td>
                <td class="{{ $zClass }}">{{ $z ?? '—' }}</td>
                <td class="{{ $zClass }}">{{ $status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="border:1px solid #e5e7eb;padding:8px 12px;font-size:8.5pt;color:#9ca3af;text-align:center;margin-bottom:8px;">No length/height measurements recorded yet.</div>
    @endif

    {{-- STATUS SUMMARY --}}
    @php
        $latestWeight = !empty($weightMeasurements) ? end($weightMeasurements) : null;
        $latestLength = !empty($lengthMeasurements) ? end($lengthMeasurements) : null;
    @endphp
    @if($latestWeight || $latestLength)
    <div class="sec">Latest Assessment Summary</div>
    <div class="status-grid">
        @if($latestWeight)
        @php
            $wz = $latestWeight['z_score'] ?? null;
            $wStatus = $wz !== null
                ? ($wz < -3 ? 'Severely Underweight' : ($wz < -2 ? 'Underweight' : ($wz > 3 ? 'Obese' : ($wz > 2 ? 'Overweight' : 'Normal Weight'))))
                : '—';
            $wColor = $wz !== null ? ($wz < -2 || $wz > 2 ? '#dc2626' : '#059669') : '#374151';
        @endphp
        <div class="status-box">
            <div class="st-label">Latest Weight</div>
            <div class="st-val" style="color:{{ $wColor }};">{{ $latestWeight['value'] }} kg</div>
            <div class="st-sub">Age: {{ $latestWeight['age_months'] }} months &nbsp;|&nbsp; Z-score: {{ $wz ?? '—' }} &nbsp;|&nbsp; {{ $wStatus }}</div>
        </div>
        @endif
        @if($latestLength)
        @php
            $lz = $latestLength['z_score'] ?? null;
            $lStatus = $lz !== null
                ? ($lz < -3 ? 'Severely Stunted' : ($lz < -2 ? 'Stunted' : ($lz > 3 ? 'Very Tall' : ($lz > 2 ? 'Tall' : 'Normal Length'))))
                : '—';
            $lColor = $lz !== null ? ($lz < -2 || $lz > 2 ? '#dc2626' : '#059669') : '#374151';
        @endphp
        <div class="status-box">
            <div class="st-label">Latest Length/Height</div>
            <div class="st-val" style="color:{{ $lColor }};">{{ $latestLength['value'] }} cm</div>
            <div class="st-sub">Age: {{ $latestLength['age_months'] }} months &nbsp;|&nbsp; Z-score: {{ $lz ?? '—' }} &nbsp;|&nbsp; {{ $lStatus }}</div>
        </div>
        @endif
    </div>
    @endif

    {{-- NOTES --}}
    <div style="font-weight:bold;font-size:9pt;margin-bottom:3px;">Remarks / Nutritional Assessment Notes:</div>
    <div style="border:1px solid #000;min-height:44px;padding:5px 8px;font-size:9.5pt;margin-bottom:8px;"></div>

    {{-- SIGNATURE --}}
    <div class="sig-row">
        <div>
            <div style="display:flex;gap:6px;margin-bottom:4px;align-items:baseline;font-size:9.5pt;"><span style="font-weight:bold;font-size:9pt;">Assessed by:</span><span style="border-bottom:1px solid #000;flex:1;"></span></div>
            <div style="display:flex;gap:6px;align-items:baseline;font-size:9.5pt;"><span style="font-weight:bold;font-size:9pt;">Date:</span><span style="border-bottom:1px solid #000;flex:1;">{{ $today ?? '' }}</span></div>
        </div>
        <div>
            <div style="min-height:36px;"></div>
            <div class="sig-box">Signature of Nurse / Nutritionist</div>
        </div>
    </div>

    <div style="text-align:center;font-size:7pt;color:#9ca3af;margin-top:10px;font-style:italic;">
        Source: WHO Child Growth Standards (2006) — WHO Multicentre Growth Reference Study Group
    </div>

</div>

<script>
function setGender(g) {
    document.getElementById('btn-boy').className  = 'gender-btn' + (g === 'boy'  ? ' active-boy'  : '');
    document.getElementById('btn-girl').className = 'gender-btn' + (g === 'girl' ? ' active-girl' : '');
    // In the actual app this would reload with ?gender=girl param
    window.location.href = window.location.pathname + '?gender=' + g;
}
</script>
</body>
</html>