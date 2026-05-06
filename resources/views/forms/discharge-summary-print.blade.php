<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discharge Summary — {{ $patient->full_name }}</title>
    <style>
        @page { size: 8.5in 13in portrait; margin: 0.5in 0.6in; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000;
            background: #c9c9c9;
        }
        @media screen {
            body { padding: 52px 0 40px; }
            .paper { width: 8.5in; min-height: 13in; margin: 0 auto; background: #fff; box-shadow: 0 4px 28px rgba(0,0,0,.28); padding: 0.5in 0.6in; }
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
        .btn-print { background: #fff; color: #1e3a5f; border: none; padding: 6px 18px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; }
        .btn-print:hover { background: #dbeafe; }

        /* ── Header ── */
        .header { display: flex; align-items: center; gap: 12px; padding-bottom: 9px; border-bottom: 2.5px solid #000; margin-bottom: 10px; }
        .logo-box { width: 68px; height: 68px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .logo-box img { width: 68px; height: 68px; object-fit: contain; }
        .logo-ph { width: 68px; height: 68px; flex-shrink: 0; border: 1.5px dashed #bbb; display: flex; align-items: center; justify-content: center; font-size: 7.5pt; color: #bbb; text-align: center; line-height: 1.4; }
        .header-center { flex: 1; text-align: center; line-height: 1.35; }
        .h-rep  { font-size: 8.5pt; color: #444; }
        .h-prov { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; }
        .h-mun  { font-size: 8.5pt; color: #444; }
        .h-hosp { font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; }

        /* ── Form title ── */
        .title-band { text-align: center; margin: 6px 0 12px; }
        .title-band h1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: .1em; }

        /* ── Patient demographics box ── */
        .demo-box { border: 1px solid #555; margin-bottom: 12px; }
        .demo-row { display: flex; border-bottom: 1px solid #555; }
        .demo-row:last-child { border-bottom: none; }
        .demo-cell { flex: 1; padding: 4px 8px; border-right: 1px solid #555; }
        .demo-cell:last-child { border-right: none; }
        .demo-cell.wide { flex: 2.5; }
        .demo-label { font-size: 7pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; color: #555; display: block; margin-bottom: 1px; }
        .demo-val { font-size: 10pt; font-weight: 600; border-bottom: 1px solid #999; min-height: 18px; padding: 0 2px 1px; display: block; }

        /* Sex / Civil checkboxes */
        .cb-row { display: flex; gap: 8px; align-items: center; font-size: 9.5pt; margin-top: 2px; }
        .cb-opt { display: inline-flex; align-items: center; gap: 3px; }
        .cb { width: 11px; height: 11px; border: 1.5px solid #555; border-radius: 2px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cb.on { background: #000; }
        .cb.on::after { content: '✓'; font-size: 7px; color: #fff; line-height: 1; }

        /* ── Two-column header fields ── */
        .hdr-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 20px; margin-bottom: 8px; }
        .hdr-field { display: flex; flex-direction: column; }
        .hdr-label { font-size: 7.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; color: #555; }
        .hdr-val { font-size: 10pt; border-bottom: 1px solid #888; min-height: 19px; padding: 0 2px 1px; }

        /* ── Narrative sections ── */
        .section { margin-bottom: 10px; }
        .section-label { font-size: 9.5pt; font-weight: bold; color: #111; margin-bottom: 3px; }
        .section-label .sub { font-weight: normal; font-size: 8.5pt; color: #555; }
        .text-line { border-bottom: 1px solid #888; min-height: 22px; width: 100%; margin-bottom: 2px; font-size: 10.5pt; padding: 2px 4px; line-height: 1.5; white-space: pre-wrap; word-break: break-word; }
        .text-line:last-child { margin-bottom: 0; }

        /* ── Physician signature block ── */
        .physician-sig-wrap {
            display: flex;
            justify-content: flex-end;
            margin-top: 32px;
        }
        .physician-sig-inner {
            text-align: center;
            min-width: 260px;
        }
        .physician-sig-inner .sig-img-area {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            min-height: 60px;
            margin-bottom: 4px;
        }
        .physician-sig-inner .sig-img-area img {
            max-height: 55px;
            max-width: 180px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }
        .physician-sig-inner .sig-img-placeholder {
            height: 55px;
        }
        .physician-sig-inner .sig-name {
            font-size: 9pt;
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }
        .physician-sig-inner .sig-rule {
            border-bottom: 1px solid #000;
            margin-bottom: 3px;
        }
        .physician-sig-inner .sig-caption {
            font-size: 8pt;
            color: #555;
            display: block;
        }

        /* ── Print date ── */
        .print-footer { font-size: 7.5pt; color: #888; text-align: right; margin-top: 18px; border-top: 0.5px solid #ccc; padding-top: 4px; }
    </style>
</head>
<body>

@php
    use Carbon\Carbon;
    $ds      = $dischargeSummary;
    $sexUC   = strtoupper($ds->sex ?? '');
    $civUC   = strtoupper(substr($ds->civil_status ?? '', 0, 3));

    $wrapLines = function (string $text, int $lines = 5): array {
        $parts = array_values(array_filter(explode("\n", $text)));
        while (count($parts) < $lines) { $parts[] = ''; }
        return $parts;
    };

    // ── Attending Physician Signature ──────────────────────────────────────
    // Try via visit relationship first
    $attendingDoctor = null;

    if (isset($visit)) {
        $attendingDoctor = $visit->medicalHistory?->doctor ?? null;
    }

    // Fallback: match by name stored in discharge summary
    if (!$attendingDoctor && !empty($ds->attending_physician)) {
        $searchName = trim(str_replace(['Dr. ', 'DR. ', 'dr. '], '', $ds->attending_physician));
        $attendingDoctor = \App\Models\User::where('name', $searchName)
            ->orWhere('name', $ds->attending_physician)
            ->orWhereRaw("CONCAT(COALESCE(first_name,''), ' ', COALESCE(last_name,'')) = ?", [$searchName])
            ->orWhereRaw("CONCAT(COALESCE(first_name,''), ' ', COALESCE(last_name,'')) = ?", [$ds->attending_physician])
            ->first(['id', 'name', 'first_name', 'last_name', 'middle_name', 'signature']);
    }

    $attendingSignature   = $attendingDoctor?->signature ?? null;
    $attendingPrintedName = $attendingDoctor
        ? strtoupper($attendingDoctor->full_name ?: $attendingDoctor->name)
        : strtoupper($ds->attending_physician ?? '');
@endphp

<div class="toolbar no-print">
    <span class="lbl">Discharge Summary</span>
    <span class="tag">FORM-DS-001</span>
    <span style="font-size:11px;color:rgba(255,255,255,.75);">{{ $patient->full_name }} &nbsp;·&nbsp; {{ $patient->case_no }}</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()"><x-heroicon-o-printer class="w-4 h-4" />&nbsp; Print / Save as PDF</button>
</div>

<div class="paper">

    {{-- LUMC Header --}}
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
            <div class="logo-box"><img src="{{ asset('images/bagong-pilipinas-logo-only.png') }}" alt="Logo"></div>
        @else
            <div class="logo-ph">LUMC<br>Logo</div>
        @endif
    </div>

    {{-- Title --}}
    <div class="title-band"><h1>Discharge Summary</h1></div>

    {{-- Patient demographics box --}}
    <div class="demo-box">
        {{-- Row 1 --}}
        <div class="demo-row">
            <div class="demo-cell wide">
                <span class="demo-label">Patient's Name (Last, Given, Middle)</span>
                <span class="demo-val">{{ strtoupper($ds->patient_full_name) }}</span>
            </div>
            <div class="demo-cell">
                <span class="demo-label">Hosp. Case No.</span>
                <span class="demo-val" style="font-family:monospace;">{{ $ds->hospital_case_no }}</span>
            </div>
            <div class="demo-cell">
                <span class="demo-label">Ward / Service</span>
                <span class="demo-val">{{ $ds->ward_service }}</span>
            </div>
        </div>
        {{-- Row 2 --}}
        <div class="demo-row">
            <div class="demo-cell wide">
                <span class="demo-label">Permanent Address</span>
                <span class="demo-val">{{ $ds->permanent_address }}</span>
            </div>
            <div class="demo-cell">
                <span class="demo-label">Tel. No.</span>
                <span class="demo-val">{{ $ds->telephone_no }}</span>
            </div>
            <div class="demo-cell">
                <span class="demo-label">Sex</span>
                <div class="cb-row">
                    <span class="cb-opt"><span class="cb {{ str_contains($sexUC, 'M') ? 'on' : '' }}"></span> M</span>
                    <span class="cb-opt"><span class="cb {{ str_contains($sexUC, 'F') ? 'on' : '' }}"></span> F</span>
                </div>
            </div>
            <div class="demo-cell">
                <span class="demo-label">Civil Status</span>
                <div class="cb-row" style="font-size:8.5pt;">
                    @foreach(['S' => 'S', 'M' => 'M', 'D' => 'D', 'W' => 'W', 'SEP' => 'Sep'] as $v => $lbl)
                        <span class="cb-opt"><span class="cb {{ $civUC === $v ? 'on' : '' }}"></span> {{ $lbl }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Core clinical header fields --}}
    <div class="hdr-fields">
        <div class="hdr-field">
            <span class="hdr-label">Date Admitted</span>
            <span class="hdr-val">{{ $ds->date_admitted?->timezone('Asia/Manila')->format('F j, Y g:i A') }}</span>
        </div>
        <div class="hdr-field">
            <span class="hdr-label">Date Discharged</span>
            <span class="hdr-val">{{ $ds->date_discharged?->timezone('Asia/Manila')->format('F j, Y g:i A') }}</span>
        </div>
        <div class="hdr-field">
            <span class="hdr-label">Attending Physician</span>
            <span class="hdr-val">{{ $ds->attending_physician }}</span>
        </div>
        <div class="hdr-field">
            <span class="hdr-label">Admitting Diagnosis</span>
            <span class="hdr-val">{{ $ds->admitting_diagnosis }}</span>
        </div>
        <div class="hdr-field">
            <span class="hdr-label">Final Diagnosis</span>
            <span class="hdr-val">{{ $ds->final_diagnosis }}</span>
        </div>
        <div class="hdr-field">
            <span class="hdr-label">Chief Complaints</span>
            <span class="hdr-val">{{ $ds->chief_complaints }}</span>
        </div>
    </div>

    {{-- Brief Clinical History --}}
    <div class="section">
        <div class="section-label">Brief Clinical History And Pertinent P.E.:</div>
        <div class="lines-block">
            @foreach($wrapLines($ds->brief_clinical_history ?? '', 5) as $line)
                <div class="text-line">{{ $line }}</div>
            @endforeach
        </div>
    </div>

    {{-- Laboratory Findings --}}
    <div class="section">
        <div class="section-label">Laboratory Findings <span class="sub">(including EKG, X-ray, and other diagnostic procedures)</span></div>
        <div class="lines-block">
            @foreach($wrapLines($ds->laboratory_findings ?? '', 5) as $line)
                <div class="text-line">{{ $line }}</div>
            @endforeach
        </div>
    </div>

    {{-- Course in the Ward --}}
    <div class="section">
        <div class="section-label">Course in the Ward: <span class="sub">(Include medications)</span></div>
        <div class="lines-block">
            @foreach($wrapLines($ds->course_in_ward ?? '', 6) as $line)
                <div class="text-line">{{ $line }}</div>
            @endforeach
        </div>
    </div>

    {{-- Disposition --}}
    <div class="section">
        <div class="section-label">Disposition <span class="sub">(include home medication, special instruction and follow-up)</span></div>
        <div class="lines-block">
            @foreach($wrapLines($ds->disposition ?? '', 5) as $line)
                <div class="text-line">{{ $line }}</div>
            @endforeach
        </div>
    </div>

    {{-- ── Attending Physician Signature Block ── --}}
    <div class="physician-sig-wrap">
        <div class="physician-sig-inner">
            {{-- Signature image --}}
            <div class="sig-img-area">
                @if($attendingSignature)
                    <img src="{{ $attendingSignature }}" alt="Physician Signature">
                @else
                    <div class="sig-img-placeholder"></div>
                @endif
            </div>
            {{-- Printed name ABOVE the line --}}
            <span class="sig-name">
                {{ $attendingPrintedName
                    ? $attendingPrintedName . ', M.D.'
                    : '___________________________________, M.D.' }}
            </span>
            {{-- Line BELOW the name --}}
            <div class="sig-rule"></div>
            <span class="sig-caption">Signature over Printed Name</span>
        </div>
    </div>

    <div class="print-footer no-print">

</div>
</body>
</html>