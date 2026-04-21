<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Records — NUR-011 — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size: 13in 8.5in landscape; margin: 0.4in 0.45in; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; color: #000; background: #c9c9c9; }
        @media screen {
            body { padding: 52px 0 40px; }
            .paper { width: 13in; min-height: 8.5in; margin: 0 auto; background: #fff;
                     box-shadow: 0 4px 28px rgba(0,0,0,.28); padding: 0.4in 0.45in; }
        }
        @media print {
            body { background: #fff; padding: 0; }
            .paper { width: 100%; padding: 0; box-shadow: none; }
            .no-print { display: none !important; }
        }

        /* ── Toolbar ── */
        .toolbar { position:fixed; top:0; left:0; right:0; height:46px; background:#1e3a5f; color:#fff;
                   font-family:'Segoe UI',system-ui,sans-serif; font-size:12px; display:flex; align-items:center;
                   padding:0 22px; gap:14px; z-index:9999; box-shadow:0 2px 10px rgba(0,0,0,.35); }
        .toolbar .lbl { font-size:13px; font-weight:700; }
        .toolbar .tag { background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:3px; padding:2px 9px; font-size:10px; text-transform:uppercase; }
        .toolbar .pt-info { font-size:11px; color:rgba(255,255,255,.8); }
        .toolbar .spacer { flex:1; }
        .btn-print { background:#fff; color:#1e3a5f; border:none; padding:6px 18px; border-radius:4px; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; }
        .btn-print:hover { background:#dbeafe; }

        /* ── Header ── */
        .header { display:flex; align-items:center; gap:12px; padding-bottom:9px; border-bottom:2.5px solid #000; margin-bottom:8px; }
        .logo-box { width:60px; height:60px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
        .logo-box img { width:60px; height:60px; object-fit:contain; }
        .logo-ph { width:60px; height:60px; flex-shrink:0; border:1.5px dashed #bbb; display:flex; align-items:center; justify-content:center; font-size:7pt; color:#bbb; text-align:center; line-height:1.4; }
        .header-center { flex:1; text-align:center; line-height:1.3; }
        .h-rep  { font-size:8pt; }
        .h-prov { font-size:9.5pt; font-weight:bold; text-transform:uppercase; letter-spacing:.04em; }
        .h-mun  { font-size:8pt; }
        .h-hosp { font-size:14pt; font-weight:bold; text-transform:uppercase; letter-spacing:.06em; margin-top:2px; }

        /* ── Patient Info Table ── */
        .pt-table { width:100%; border-collapse:collapse; margin-bottom:6px; }
        .pt-table td { border:1.2px solid #000; padding:2px 5px; vertical-align:top; font-size:8pt; }
        .lbl { font-weight:bold; font-size:7pt; text-transform:uppercase; display:block; margin-bottom:1px; }
        .val { display:block; min-height:13px; font-size:8.5pt; }
        .cb-row { display:flex; gap:5px; flex-wrap:wrap; margin-top:1px; }
        .cb  { display:inline-flex; align-items:center; gap:2px; font-size:7.5pt; }
        .sq  { width:9px; height:9px; border:1.2px solid #000; display:inline-block; flex-shrink:0; }
        .sq.checked { background:#000; }

        /* ── Form Title ── */
        .title-band { text-align:center; margin:0 0 2px; }
        .title-band h1 { display:inline-block; font-size:12.5pt; font-weight:bold; text-transform:uppercase;
                         letter-spacing:.10em; border-bottom:1.5px solid #000; padding-bottom:2px; }
        .form-note { font-size:8pt; text-decoration:underline; margin-bottom:6px; display:block; text-align:center; }

        /* ── Medication Grid ── */
        .med-table { width:100%; border-collapse:collapse; font-size:7.5pt; table-layout:fixed; }
        .med-table th, .med-table td {
            border:1.2px solid #000; padding:1px 2px;
            text-align:center; vertical-align:middle;
        }
        .med-table thead th {
            background:#f0f0f0; font-weight:bold;
            font-size:6.5pt; text-transform:uppercase;
            padding:3px 2px;
        }

        /* Column widths */
        .col-med   { width:140pt; text-align:left; padding-left:4pt !important; }
        .col-shift { width:26pt; font-size:6.5pt; text-align:left; padding:2px 3px !important; }
        .col-day   { min-width:24pt; height:16pt; }

        /* Shift colour bands */
        .sh-73   { background:#e0f2fe; font-weight:bold; color:#1e40af; }
        .sh-311  { background:#ede9fe; font-weight:bold; color:#5b21b6; }
        .sh-117  { background:#ccfbf1; font-weight:bold; color:#0f766e; }

        /* Data cells */
        .data-cell { font-size:7.5pt; font-family:'Courier New',monospace; font-weight:600; }

        /* Group separator — thicker top border between medications */
        .group-start td { border-top:2px solid #000 !important; }

        /* Empty row for padding */
        .empty-med td { height:14pt; }

        /* Date header */
        .date-th { font-size:6pt !important; white-space:nowrap; }
    </style>
</head>
<body>

@php
    $patient    = $visit->patient;
    $history    = $visit->medicalHistory;

    $dateColumnsModel = \App\Models\MarDateColumn::where('visit_id', $visit->id)->first();
    $dates      = $dateColumnsModel?->dates ?? [];

    $entries    = \App\Models\MarEntry::where('visit_id', $visit->id)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();

    $sex         = strtolower($patient->sex ?? '');
    $isMale      = $sex === 'male';
    $isFemale    = $sex === 'female';
    $civilStatus = strtolower($patient->civil_status ?? '');

    // Pad rows to at least 8 medication groups
    $totalMeds   = max(8, $entries->count());
    $blankNeeded = $totalMeds - $entries->count();
@endphp

<div class="toolbar no-print">
    <span class="lbl">Medication Records</span>
    <span class="tag">NUR-011</span>
    <span class="pt-info">{{ $patient->full_name }} &nbsp;·&nbsp; {{ $patient->case_no }}</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()">🖨️&nbsp;&nbsp;Print / Save as PDF</button>
</div>

<div class="paper">

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

    {{-- ── Patient Info ── --}}
    <table class="pt-table">
        <tr>
            <td colspan="3" style="width:55%;">
                <span class="lbl">Patient's Name: (Last) &nbsp; (Given) &nbsp; (Middle)</span>
                <span class="val">{{ $patient->family_name }}, {{ $patient->first_name }} {{ $patient->middle_name ?? '' }}</span>
            </td>
            <td style="width:15%;">
                <span class="lbl">Hosp. Case No.</span>
                <span class="val">{{ $patient->case_no }}</span>
            </td>
            <td style="width:15%;">
                <span class="lbl">Ward / Service</span>
                <span class="val">{{ $visit->admitted_service ?? $history?->service ?? '—' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="width:40%;">
                <span class="lbl">Permanent Address</span>
                <span class="val">{{ $patient->address ?? '—' }}</span>
            </td>
            <td style="width:15%;">
                <span class="lbl">Tel. No.</span>
                <span class="val">{{ $patient->contact_number ?? '—' }}</span>
            </td>
            <td>
                <span class="lbl">Sex</span>
                <div class="cb-row">
                    <label class="cb"><span class="sq {{ $isMale   ? 'checked':'' }}"></span> M</label>
                    <label class="cb"><span class="sq {{ $isFemale ? 'checked':'' }}"></span> F</label>
                </div>
            </td>
            <td>
                <span class="lbl">Civil Status</span>
                <div class="cb-row">
                    <label class="cb"><span class="sq {{ $civilStatus==='single'    ? 'checked':'' }}"></span> S</label>
                    <label class="cb"><span class="sq {{ $civilStatus==='annulled'  ? 'checked':'' }}"></span> D</label>
                    <label class="cb"><span class="sq {{ $civilStatus==='separated' ? 'checked':'' }}"></span> Sep</label>
                </div>
                <div class="cb-row">
                    <label class="cb"><span class="sq {{ $civilStatus==='married'   ? 'checked':'' }}"></span> M</label>
                    <label class="cb"><span class="sq {{ $civilStatus==='widowed'   ? 'checked':'' }}"></span> W</label>
                </div>
            </td>
        </tr>
    </table>

    <div class="title-band"><h1>Medication Records</h1></div>
    <span class="form-note">C — Circle all doses not given; state reason in Nurse's Notes</span>

    <table class="med-table">
        <thead>
            <tr>
                <th class="col-med" style="text-align:left;padding-left:5px;">Medication</th>
                <th class="col-shift">Shift</th>
                @foreach($dates as $d)
                <th class="date-th col-day">
                    {{ \Carbon\Carbon::parse($d)->format('M j') }}<br>
                    <span style="font-weight:400;">{{ \Carbon\Carbon::parse($d)->format('D') }}</span>
                </th>
                @endforeach
                {{-- Pad to at least 14 date columns if fewer dates --}}
                @for($pad = count($dates); $pad < 14; $pad++)
                <th class="date-th col-day">—</th>
                @endfor
            </tr>
        </thead>
        <tbody>

            {{-- ── Real medication rows ── --}}
            @foreach($entries as $entry)
            @foreach(['7-3', '3-11', '11-7'] as $shiftIdx => $shift)
            @php
                $isFirst    = $shiftIdx === 0;
                $shiftClass = match($shift) { '7-3' => 'sh-73', '3-11' => 'sh-311', '11-7' => 'sh-117' };
            @endphp
            <tr class="{{ $isFirst ? 'group-start' : '' }}">

                @if($isFirst)
                <td class="col-med" rowspan="3" style="vertical-align:middle;font-weight:bold;font-size:7.5pt;">
                    {{ $entry->medication_name }}
                </td>
                @endif

                <td class="col-shift {{ $shiftClass }}">{{ $shift }}</td>

                @foreach($dates as $d)
                @php $cellVal = $entry->getTime($d, $shift); @endphp
                <td class="col-day data-cell">{{ $cellVal ?: '' }}</td>
                @endforeach

                {{-- Pad to 14 columns if fewer dates --}}
                @for($pad = count($dates); $pad < 14; $pad++)
                <td class="col-day">&nbsp;</td>
                @endfor

            </tr>
            @endforeach
            @endforeach

            {{-- ── Blank padding rows ── --}}
            @for($i = 0; $i < $blankNeeded; $i++)
            @foreach(['7-3', '3-11', '11-7'] as $shiftIdx => $shift)
            @php $shiftClass = match($shift) { '7-3' => 'sh-73', '3-11' => 'sh-311', '11-7' => 'sh-117' }; @endphp
            <tr class="{{ $shiftIdx === 0 ? 'group-start' : '' }}">
                @if($shiftIdx === 0)<td class="col-med" rowspan="3">&nbsp;</td>@endif
                <td class="col-shift {{ $shiftClass }}">{{ $shift }}</td>
                @for($d = 0; $d < max(14, count($dates)); $d++)<td class="col-day">&nbsp;</td>@endfor
            </tr>
            @endforeach
            @endfor

        </tbody>
    </table>

</div>
</body>
</html>