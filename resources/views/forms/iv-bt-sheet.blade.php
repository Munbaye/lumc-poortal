<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IV / BT Sheet — NUR-012 — LA UNION MEDICAL CENTER</title>
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

        /* ── Patient Info Table ── */
        .pt-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .pt-table td { border: 1.2px solid #000; padding: 3px 5px; vertical-align: top; font-size: 8.5pt; }
        .lbl { font-weight: bold; font-size: 7.5pt; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .val { display: block; min-height: 14px; font-size: 8.5pt; }
        .val-line { display: block; min-height: 14px; border-bottom: 1px solid #555; margin-top: 2px; }
        .cb-row { display: flex; gap: 5px; flex-wrap: wrap; margin-top: 2px; }
        .cb { display: inline-flex; align-items: center; gap: 2px; font-size: 8pt; }
        .sq { width: 10px; height: 10px; border: 1.2px solid #000; display: inline-block; flex-shrink: 0; }
        .sq.checked { background: #000; }

        /* ── Form Title ── */
        .title-band { text-align: center; margin: 0 0 10px; }
        .title-band h1 { display: inline-block; font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: .08em; border-bottom: 1.5px solid #000; padding-bottom: 3px; line-height: 1.5; }

        /* ── IV/BT Table ── */
        .ivbt-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
        .ivbt-table th { border: 1.2px solid #000; padding: 5px 4px; text-align: center; font-weight: bold; font-size: 8pt; text-transform: uppercase; background: #f0f0f0; vertical-align: middle; line-height: 1.3; }
        .ivbt-table td { border: 1.2px solid #000; padding: 2px 4px; height: 26px; vertical-align: middle; text-align: center; font-size: 8.5pt; }
        .ivbt-table td.col-left { text-align: left; }
        .col-date-started  { width: 11%; }
        .col-time-started  { width: 10%; }
        .col-bottle-no     { width: 8%;  }
        .col-iv-solution   { width: 30%; }
        .col-date-consumed { width: 15%; }
        .col-remarks       { width: 14%; }
        .col-nurse-sig     { width: 12%; }
        .blank-row td { height: 26px; }
        .nurse-sig-text { font-style: italic; font-size: 7.5pt; line-height: 1.4; }
        .consumed-blank { color: #bbb; font-style: italic; font-size: 7.5pt; }
    </style>
</head>
<body>

@php
    $patient   = $visit->patient;
    $history   = $visit->medicalHistory;
    $ivEntries = \App\Models\IvFluidEntry::where('visit_id', $visit->id)
                    ->orderBy('date_started', 'asc')
                    ->orderBy('time_started', 'asc')
                    ->orderBy('bottle_number', 'asc')
                    ->get();

    $sex         = $patient->sex ?? '';
    $isMale      = strtolower($sex) === 'male';
    $isFemale    = strtolower($sex) === 'female';
    $civilStatus = strtolower($patient->civil_status ?? '');

    // Pad to at least 24 rows
    $totalRows   = max(24, $ivEntries->count());
    $blankNeeded = $totalRows - $ivEntries->count();
@endphp

<div class="toolbar no-print">
    <span class="lbl">IV / BT Sheet</span>
    <span class="tag">NUR-012</span>
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

    {{-- ── Patient Info Table ── --}}
    <table class="pt-table">
        <tr>
            <td colspan="3" style="width:55%;">
                <span class="lbl">Patient's Name: (Last) &nbsp; (Given) &nbsp; (Middle)</span>
                <span class="val">
                    {{ $patient->family_name }},
                    {{ $patient->first_name }}
                    {{ $patient->middle_name ?? '' }}
                </span>
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
                    <label class="cb"><span class="sq {{ $isMale ? 'checked' : '' }}"></span> M</label>
                    <label class="cb"><span class="sq {{ $isFemale ? 'checked' : '' }}"></span> F</label>
                </div>
            </td>
            <td>
                <span class="lbl">Civil Status</span>
                <div class="cb-row">
                    <label class="cb"><span class="sq {{ $civilStatus === 'single' ? 'checked' : '' }}"></span> S</label>
                    <label class="cb"><span class="sq {{ in_array($civilStatus, ['annulled']) ? 'checked' : '' }}"></span> D</label>
                    <label class="cb"><span class="sq {{ $civilStatus === 'separated' ? 'checked' : '' }}"></span> Sep</label>
                </div>
                <div class="cb-row">
                    <label class="cb"><span class="sq {{ $civilStatus === 'married' ? 'checked' : '' }}"></span> M</label>
                    <label class="cb"><span class="sq {{ $civilStatus === 'widowed' ? 'checked' : '' }}"></span> W</label>
                </div>
            </td>
        </tr>
    </table>

    <div class="title-band">
        <h1>Intravenous Fluid Sheet /<br>Blood Transfusion Sheet</h1>
    </div>

    <table class="ivbt-table">
        <thead>
            <tr>
                <th class="col-date-started">Date<br>Started</th>
                <th class="col-time-started">Time<br>Started</th>
                <th class="col-bottle-no">Bottle<br>No.</th>
                <th class="col-iv-solution">IV Solution Amount &amp;<br>Regulation Rate</th>
                <th class="col-date-consumed">Date &amp; Time<br>Consumed</th>
                <th class="col-remarks">Remarks</th>
                <th class="col-nurse-sig">Nurse<br>Signature</th>
            </tr>
        </thead>
        <tbody>

            {{-- ── Real data rows ── --}}
            @foreach($ivEntries as $entry)
            <tr>
                {{-- Date Started --}}
                <td>{{ $entry->date_started->format('M j, Y') }}</td>

                {{-- Time Started --}}
                <td>
                    @php
                        // time_started is stored as "H:i:s" string — parse it for display
                        $timeDisplay = \Carbon\Carbon::createFromFormat('H:i:s', $entry->time_started)
                                        ->format('g:i A');
                    @endphp
                    {{ $timeDisplay }}
                </td>

                {{-- Bottle # --}}
                <td>{{ $entry->bottle_number }}</td>

                {{-- IV Solution --}}
                <td class="col-left">{{ $entry->iv_solution }}</td>

                {{-- Date & Time Consumed --}}
                <td>
                    @if($entry->consumed_at)
                        {{ $entry->consumed_at->timezone('Asia/Manila')->format('M j, Y') }}<br>
                        {{ $entry->consumed_at->timezone('Asia/Manila')->format('g:i A') }}
                    @else
                        <span class="consumed-blank">—</span>
                    @endif
                </td>

                {{-- Remarks --}}
                <td class="col-left">{{ $entry->remarks ?? '' }}</td>

                {{-- Nurse Signature --}}
                <td>
                    <span class="nurse-sig-text">{{ $entry->nurse_name }}</span>
                    @if($entry->editor_name)
                        <br><span style="font-size:6.5pt;color:#555;">
                            (ed. {{ $entry->editor_name }})
                        </span>
                    @endif
                </td>
            </tr>
            @endforeach

            {{-- ── Blank padding rows ── --}}
            @for($i = 0; $i < $blankNeeded; $i++)
            <tr class="blank-row">
                <td class="col-date-started">&nbsp;</td>
                <td class="col-time-started">&nbsp;</td>
                <td class="col-bottle-no">&nbsp;</td>
                <td class="col-iv-solution">&nbsp;</td>
                <td class="col-date-consumed">&nbsp;</td>
                <td class="col-remarks">&nbsp;</td>
                <td class="col-nurse-sig">&nbsp;</td>
            </tr>
            @endfor

        </tbody>
    </table>

</div>
</body>
</html>