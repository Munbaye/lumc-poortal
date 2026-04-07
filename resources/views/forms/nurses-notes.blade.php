<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse's Notes — NUR-010 — LA UNION MEDICAL CENTER</title>
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
        .pt-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .pt-table td { border: 1.2px solid #000; padding: 3px 5px; vertical-align: top; font-size: 8.5pt; }
        .lbl { font-weight: bold; font-size: 7.5pt; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .val { display: block; min-height: 14px; font-size: 8.5pt; }
        .val-line { display: block; min-height: 14px; border-bottom: 1px solid #555; margin-top: 2px; }
        .cb-row { display: flex; gap: 5px; flex-wrap: wrap; margin-top: 2px; }
        .cb { display: inline-flex; align-items: center; gap: 2px; font-size: 8pt; }
        .sq { width: 10px; height: 10px; border: 1.2px solid #000; display: inline-block; flex-shrink: 0; }
        .sq.checked { background: #000; }

        /* ── Form Title ── */
        .title-band { text-align: center; margin: 0 0 8px; }
        .title-band h1 { display: inline-block; font-size: 13pt; font-weight: bold; letter-spacing: .06em; border-bottom: 1.5px solid #000; padding-bottom: 3px; }

        /* ── Notes Table ── */
        .notes-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
        .notes-table th { border: 1.2px solid #000; padding: 5px 6px; font-size: 9pt; font-weight: bold; text-decoration: underline; text-align: center; background: #f8f8f8; vertical-align: bottom; }
        .notes-table td { border: 1.2px solid #000; vertical-align: top; padding: 4px 5px; font-size: 8.5pt; }

        /* Column widths — Date, Shift, Notes (wide), Nurse Name */
        .col-date  { width: 11%; text-align: center; }
        .col-shift { width: 10%; text-align: center; }
        .col-notes { width: 63%; }
        .col-sig   { width: 16%; text-align: center; vertical-align: middle; }

        /* Data rows */
        .td-date  { text-align: center; vertical-align: top; line-height: 1.5; }
        .td-shift { text-align: center; vertical-align: top; }
        .td-sig   { text-align: center; vertical-align: middle; font-style: italic; font-size: 8pt; line-height: 1.4; }
        .td-notes { vertical-align: top; line-height: 1.55; }

        /* FDAR labels inside the Notes cell */
        .fdar-row { display: flex; gap: 4px; align-items: baseline; margin-bottom: 3px; }
        .fdar-row:last-child { margin-bottom: 0; }
        .fdar-letter {
            font-weight: bold;
            font-size: 9pt;
            min-width: 14px;
            flex-shrink: 0;
        }
        .fdar-text { font-size: 8.5pt; line-height: 1.5; }

        /* Shift badge */
        .shift-badge {
            display: inline-block;
            font-size: 7.5pt;
            font-weight: bold;
            border: 1px solid #000;
            border-radius: 2px;
            padding: 1px 4px;
            line-height: 1.4;
            white-space: nowrap;
        }

        /* Blank padding rows */
        .blank-row td { height: 22px; }
    </style>
</head>
<body>

@php
    $patient = $visit->patient;
    $history = $visit->medicalHistory;

    // Fetch notes oldest-first for chronological display on the paper form
    $notes = \App\Models\NursesNote::where('visit_id', $visit->id)
                ->with('nurse')
                ->orderBy('noted_at', 'asc')
                ->get();

    $sex         = strtolower($patient->sex ?? '');
    $isMale      = $sex === 'male';
    $isFemale    = $sex === 'female';
    $civilStatus = strtolower($patient->civil_status ?? '');

    // Pad to at least 30 total rows
    $totalRows   = max(30, $notes->count());
    $blankNeeded = $totalRows - $notes->count();
@endphp

<div class="toolbar no-print">
    <span class="lbl">Nurse's Notes</span>
    <span class="tag">NUR-010</span>
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
                    <label class="cb"><span class="sq {{ $isMale   ? 'checked':'' }}"></span> M</label>
                    <label class="cb"><span class="sq {{ $isFemale ? 'checked':'' }}"></span> F</label>
                </div>
            </td>
            <td>
                <span class="lbl">Civil Status</span>
                <div class="cb-row">
                    <label class="cb"><span class="sq {{ $civilStatus==='single'   ? 'checked':'' }}"></span> S</label>
                    <label class="cb"><span class="sq {{ in_array($civilStatus,['annulled']) ? 'checked':'' }}"></span> D</label>
                    <label class="cb"><span class="sq {{ $civilStatus==='separated' ? 'checked':'' }}"></span> Sep</label>
                </div>
                <div class="cb-row">
                    <label class="cb"><span class="sq {{ $civilStatus==='married'  ? 'checked':'' }}"></span> M</label>
                    <label class="cb"><span class="sq {{ $civilStatus==='widowed'  ? 'checked':'' }}"></span> W</label>
                </div>
            </td>
        </tr>
    </table>

    <div class="title-band"><h1>Nurse's Notes</h1></div>

    <table class="notes-table">
        <thead>
            <tr>
                <th class="col-date">Date</th>
                <th class="col-shift">Shift</th>
                <th class="col-notes">Notes</th>
                <th class="col-sig">Nurse Signature</th>
            </tr>
        </thead>
        <tbody>

            {{-- ── Real data rows ── --}}
            @foreach($notes as $note)
            @php
                $notedAt = $note->noted_at?->timezone('Asia/Manila');

                // Build FDAR content lines — only show fields that have data
                $fdarLines = [];
                if (filled($note->focus))    $fdarLines[] = ['F', $note->focus];
                if (filled($note->data))     $fdarLines[] = ['D', $note->data];
                if (filled($note->action))   $fdarLines[] = ['A', $note->action];
                if (filled($note->response)) $fdarLines[] = ['R', $note->response];
            @endphp
            <tr>
                {{-- Date --}}
                <td class="td-date col-date">
                    @if($notedAt)
                        {{ $notedAt->format('M j, Y') }}<br>
                        <strong>{{ $notedAt->format('g:i A') }}</strong>
                    @else
                        —
                    @endif
                </td>

                {{-- Shift --}}
                <td class="td-shift col-shift">
                    @if($note->shift)
                        <span class="shift-badge">{{ $note->shift }}</span>
                    @else
                        —
                    @endif
                </td>

                {{-- FDAR Notes --}}
                <td class="td-notes col-notes">
                    @foreach($fdarLines as [$letter, $text])
                    <div class="fdar-row">
                        <span class="fdar-letter">{{ $letter }}.</span>
                        <span class="fdar-text">{{ $text }}</span>
                    </div>
                    @endforeach
                    @if(empty($fdarLines))
                        <span style="color:#999;font-style:italic;">—</span>
                    @endif
                </td>

                {{-- Nurse Name / Signature --}}
                <td class="td-sig col-sig">
                    {{ $note->nurse?->name ?? $note->nurse_name ?? '—' }}
                </td>
            </tr>
            @endforeach

            {{-- ── Blank padding rows ── --}}
            @for($i = 0; $i < $blankNeeded; $i++)
            <tr class="blank-row">
                <td class="col-date">&nbsp;</td>
                <td class="col-shift">&nbsp;</td>
                <td class="col-notes">&nbsp;</td>
                <td class="col-sig">&nbsp;</td>
            </tr>
            @endfor

        </tbody>
    </table>

</div>
</body>
</html>