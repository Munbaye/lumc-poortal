<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vital Sign Monitoring Sheet — NUR-014 — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size: 8.5in 13in portrait; margin: 0.45in 0.55in; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; color: #000; background: #c9c9c9; }
        @media screen {
            body { padding: 52px 0 40px; }
            .paper { width: 8.5in; min-height: 13in; margin: 0 auto; background: #fff; box-shadow: 0 4px 28px rgba(0,0,0,.28); padding: 0.45in 0.55in; }
        }
        @media print {
            body { background: #fff; padding: 0; }
            .paper { width: 100%; padding: 0; box-shadow: none; }
            .no-print { display: none !important; }
        }

        /* ── Toolbar ── */
        .toolbar { position: fixed; top: 0; left: 0; right: 0; height: 46px; background: #1e3a5f; color: #fff; font-family: 'Segoe UI', system-ui, sans-serif; font-size: 12px; display: flex; align-items: center; padding: 0 22px; gap: 14px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,.35); }
        .toolbar .lbl { font-size: 13px; font-weight: 700; }
        .toolbar .tag { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); border-radius: 3px; padding: 2px 9px; font-size: 10px; text-transform: uppercase; }
        .toolbar .spacer { flex: 1; }
        .toolbar .pt-info { font-size: 11px; color: rgba(255,255,255,.8); }
        .btn-print { background: #fff; color: #1e3a5f; border: none; padding: 6px 18px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; }
        .btn-print:hover { background: #dbeafe; }

        /* ── Header ── */
        .header { display: flex; align-items: center; gap: 12px; padding-bottom: 9px; border-bottom: 2.5px solid #000; margin-bottom: 10px; }
        .logo-box { width: 68px; height: 68px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .logo-box img { width: 68px; height: 68px; object-fit: contain; }
        .logo-ph { width: 68px; height: 68px; flex-shrink: 0; border: 1.5px dashed #bbb; display: flex; align-items: center; justify-content: center; font-size: 7.5pt; color: #bbb; text-align: center; line-height: 1.4; }
        .header-center { flex: 1; text-align: center; line-height: 1.35; }
        .h-rep  { font-size: 8.5pt; }
        .h-prov { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; }
        .h-mun  { font-size: 8.5pt; }
        .h-hosp { font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; }

        /* ── Form Title ── */
        .title-band { text-align: center; margin: 0 0 8px; }
        .title-band h1 { display: inline-block; font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: .08em; margin-top: 2px; }

        /* ── Patient Header Line ── */
        .pt-header { display: flex; gap: 12px; margin-bottom: 10px; font-size: 9pt; flex-wrap: wrap; align-items: flex-end; }
        .pt-field { display: flex; align-items: flex-end; gap: 4px; }
        .pt-field-label { font-weight: bold; white-space: nowrap; font-size: 9pt; }
        .pt-field-value { border-bottom: 1px solid #000; min-height: 14px; padding: 0 4px 1px; font-size: 9pt; }
        .pt-field-line { border-bottom: 1px solid #000; min-height: 14px; min-width: 60px; }

        /* ── Vital Signs Table ── */
        .vs-table { width: 100%; border-collapse: collapse; font-size: 8pt; }
        .vs-table th { border: 1.2px solid #000; padding: 5px 4px; font-weight: bold; font-size: 8pt; text-align: center; vertical-align: middle; background: #f0f0f0; line-height: 1.35; }
        .vs-table td { border: 1.2px solid #000; height: 26px; text-align: center; vertical-align: middle; padding: 2px 3px; font-size: 8pt; }
        .vs-table td.col-datetime { text-align: left; padding: 2px 4px; line-height: 1.35; }
        .vs-table td.col-text     { text-align: left; padding: 2px 4px; line-height: 1.35; }
        .col-datetime { width: 15%; }
        .col-spo2     { width: 7%;  }
        .col-cr       { width: 7%;  }
        .col-pr       { width: 7%;  }
        .col-rr       { width: 7%;  }
        .col-temp     { width: 8%;  }
        .col-neuro    { width: 18%; }
        .col-others   { width: 14%; }
        .col-remarks  { width: 17%; }
        .abnormal     { font-weight: bold; }
        .blank-row td { height: 26px; }
        .nurse-sig-small { font-size: 6.5pt; color: #444; display: block; margin-top: 1px; border-top: 0.5px solid #ccc; padding-top: 1px; }
    </style>
</head>
<body>

@php
    $patient = $visit->patient;
    $history = $visit->medicalHistory;
    $vitals  = \App\Models\Vital::where('visit_id', $visit->id)
                    ->orderBy('taken_at', 'asc')
                    ->get();

    // How many blank rows to pad to at least 26 total rows
    $totalRows   = max(26, $vitals->count());
    $blankNeeded = $totalRows - $vitals->count();
@endphp

<div class="toolbar no-print">
    <span class="lbl">Vital Sign Monitoring Sheet</span>
    <span class="tag">NUR-014</span>
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

    <div class="title-band"><h1>Vital Sign Monitoring Sheet</h1></div>

    <div class="pt-header">
        <div class="pt-field" style="flex:3; min-width:240px;">
            <span class="pt-field-label">Name of Patient:</span>
            <span class="pt-field-value" style="flex:1;">{{ $patient->full_name }}</span>
        </div>
        <div class="pt-field">
            <span class="pt-field-label">Age:</span>
            <span class="pt-field-value" style="min-width:50px;">{{ $patient->age_display ?? '—' }}</span>
        </div>
        <div class="pt-field">
            <span class="pt-field-label">Sex:</span>
            <span class="pt-field-value" style="min-width:50px;">{{ $patient->sex }}</span>
        </div>
        <div class="pt-field" style="flex:2; min-width:140px;">
            <span class="pt-field-label">Ward:</span>
            <span class="pt-field-value" style="flex:1;">{{ $visit->admitted_service ?? $history?->service ?? '—' }}</span>
        </div>
        <div class="pt-field" style="flex:2; min-width:160px;">
            <span class="pt-field-label">Hospital Case No.:</span>
            <span class="pt-field-value" style="flex:1;">{{ $patient->case_no }}</span>
        </div>
    </div>

    <table class="vs-table">
        <thead>
            <tr>
                <th class="col-datetime">Date &amp;<br>Time</th>
                <th class="col-spo2">SpO₂<br>(%)</th>
                <th class="col-cr">CR<br>(bpm)</th>
                <th class="col-pr">PR<br>(min)</th>
                <th class="col-rr">RR<br>(min)</th>
                <th class="col-temp">Temp.<br>(°C)</th>
                <th class="col-neuro">Neurological<br>Vital Sign</th>
                <th class="col-others">Others</th>
                <th class="col-remarks">Remarks /<br>Nurse</th>
            </tr>
        </thead>
        <tbody>

            {{-- ── Real data rows ── --}}
            @foreach($vitals as $v)
            @php
                $abnO2   = $v->o2_saturation    !== null && $v->o2_saturation < 95;
                $abnPR   = $v->pulse_rate        !== null && ($v->pulse_rate < 60 || $v->pulse_rate > 100);
                $abnRR   = $v->respiratory_rate  !== null && ($v->respiratory_rate < 12 || $v->respiratory_rate > 20);
                $abnTemp = $v->temperature       !== null && ($v->temperature < 36.0 || $v->temperature > 37.5);
                $takenAt = $v->taken_at->timezone('Asia/Manila');
            @endphp
            <tr>
                {{-- Date & Time --}}
                <td class="col-datetime">
                    {{ $takenAt->format('M j, Y') }}<br>
                    <strong>{{ $takenAt->format('g:i A') }}</strong>
                </td>

                {{-- SpO₂ --}}
                <td class="{{ $abnO2 ? 'abnormal' : '' }}">
                    {{ $v->o2_saturation !== null ? $v->o2_saturation : '' }}
                </td>

                {{-- CR --}}
                <td>{{ $v->cardiac_rate ?? '' }}</td>

                {{-- PR --}}
                <td class="{{ $abnPR ? 'abnormal' : '' }}">
                    {{ $v->pulse_rate ?? '' }}
                </td>

                {{-- RR --}}
                <td class="{{ $abnRR ? 'abnormal' : '' }}">
                    {{ $v->respiratory_rate ?? '' }}
                </td>

                {{-- Temp --}}
                <td class="{{ $abnTemp ? 'abnormal' : '' }}">
                    @if($v->temperature)
                        {{ number_format($v->temperature, 1) }}
                        @if($v->temperature_site && $v->temperature_site !== 'Axilla')
                            <br><span style="font-size:6.5pt;">({{ $v->temperature_site }})</span>
                        @endif
                    @endif
                </td>

                {{-- Neurological VS --}}
                <td class="col-text">{{ $v->neurological_vs ?? '' }}</td>

                {{-- Others --}}
                <td class="col-text">{{ $v->others_vs ?? '' }}</td>

                {{-- Remarks / Nurse --}}
                <td class="col-text">
                    {{ $v->notes ?? '' }}
                    @if($v->nurse_name)
                        <span class="nurse-sig-small">{{ $v->nurse_name }}</span>
                    @endif
                </td>
            </tr>
            @endforeach

            {{-- ── Blank padding rows ── --}}
            @for($i = 0; $i < $blankNeeded; $i++)
            <tr class="blank-row">
                <td class="col-datetime">&nbsp;</td>
                <td class="col-spo2">&nbsp;</td>
                <td class="col-cr">&nbsp;</td>
                <td class="col-pr">&nbsp;</td>
                <td class="col-rr">&nbsp;</td>
                <td class="col-temp">&nbsp;</td>
                <td class="col-neuro">&nbsp;</td>
                <td class="col-others">&nbsp;</td>
                <td class="col-remarks">&nbsp;</td>
            </tr>
            @endfor

        </tbody>
    </table>

</div>
</body>
</html>