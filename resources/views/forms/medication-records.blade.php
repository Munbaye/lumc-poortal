<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Records — NUR-011 — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size: 13in 8.5in landscape; margin: 0.45in 0.55in; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; color: #000; background: #c9c9c9; }
        @media screen {
            body { padding: 52px 0 40px; }
            .paper { width: 13in; min-height: 8.5in; margin: 0 auto; background: #fff; box-shadow: 0 4px 28px rgba(0,0,0,.28); padding: 0.45in 0.55in; }
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

        /* ── Header (matches history-form) ── */
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
        .val-line { display: block; min-height: 14px; border-bottom: 1px solid #555; margin-top: 2px; }
        .cb-row { display: flex; gap: 5px; flex-wrap: wrap; margin-top: 2px; }
        .cb { display: inline-flex; align-items: center; gap: 2px; font-size: 8pt; }
        .sq { width: 10px; height: 10px; border: 1.2px solid #000; display: inline-block; flex-shrink: 0; }

        /* ── Form Title ── */
        .title-band { text-align: center; margin: 0 0 3px; }
        .title-band h1 { display: inline-block; font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: .10em; border-bottom: 1.5px solid #000; padding-bottom: 3px; }
        .form-note { font-size: 8.5pt; text-decoration: underline; margin-bottom: 8px; display: block; text-align: center; }

        /* ── Medication Grid ── */
        .med-table { width: 100%; border-collapse: collapse; font-size: 7.5pt; }
        .med-table th, .med-table td { border: 1.2px solid #000; padding: 2px 3px; text-align: center; vertical-align: middle; }
        .med-table th { background: #f0f0f0; font-weight: bold; font-size: 7pt; text-transform: uppercase; }
        .col-shift { font-size: 7pt; text-align: left; padding: 2px 4px; width: 34px; }
        .col-day { width: 30px; min-width: 30px; height: 18px; }
        .shift-group-first td { border-top: 1.2px solid #000; }
        .shift-group td { border-top: none; }
        .date-header { font-size: 6.5pt; }
    </style>
</head>
<body>

<div class="toolbar no-print">
    <span class="lbl">Medication Records</span>
    <span class="tag">NUR-011</span>
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

    <table class="pt-table">
        <tr>
            <td colspan="3" style="width:55%;">
                <span class="lbl">Patient's Name: (Last) &nbsp; (Given) &nbsp; (Middle)</span>
                <span class="val-line">&nbsp;</span>
            </td>
            <td style="width:15%;">
                <span class="lbl">Hosp. Case No.</span>
                <span class="val-line">&nbsp;</span>
            </td>
            <td style="width:15%;">
                <span class="lbl">Ward / Service</span>
                <span class="val-line">&nbsp;</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="width:40%;">
                <span class="lbl">Permanent Address</span>
                <span class="val-line">&nbsp;</span>
                <span class="val-line" style="margin-top:2px;">&nbsp;</span>
            </td>
            <td style="width:15%;">
                <span class="lbl">Tel. No.</span>
                <span class="val-line">&nbsp;</span>
            </td>
            <td>
                <span class="lbl">Sex</span>
                <div class="cb-row">
                    <label class="cb"><span class="sq"></span> M</label>
                    <label class="cb"><span class="sq"></span> F</label>
                </div>
            </td>
            <td>
                <span class="lbl">Civil Status</span>
                <div class="cb-row">
                    <label class="cb"><span class="sq"></span> S</label>
                    <label class="cb"><span class="sq"></span> D</label>
                    <label class="cb"><span class="sq"></span> Sep</label>
                </div>
                <div class="cb-row">
                    <label class="cb"><span class="sq"></span> M</label>
                    <label class="cb"><span class="sq"></span> W</label>
                </div>
            </td>
        </tr>
    </table>

    <div class="title-band"><h1>Medication Records</h1></div>
    <span class="form-note">C — Circle all doses not given; state reason in Nurse's Notes</span>

    <table class="med-table">
        <thead>
            <tr>
                <th rowspan="2" style="width:160px; min-width:160px; text-align:left; padding-left:5px;">Medication</th>
                <th rowspan="2" style="width:34px;">Shift</th>
                @for ($d = 1; $d <= 14; $d++)
                <th class="date-header">Date<br>{{ $d }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @for ($m = 1; $m <= 6; $m++)
            <tr class="shift-group-first">
                <td rowspan="3" style="text-align:left; padding:3px 5px;">&nbsp;</td>
                <td class="col-shift">7-3</td>
                @for ($d = 1; $d <= 14; $d++)<td class="col-day">&nbsp;</td>@endfor
            </tr>
            <tr class="shift-group">
                <td class="col-shift">3-11</td>
                @for ($d = 1; $d <= 14; $d++)<td class="col-day">&nbsp;</td>@endfor
            </tr>
            <tr class="shift-group">
                <td class="col-shift">11-7</td>
                @for ($d = 1; $d <= 14; $d++)<td class="col-day">&nbsp;</td>@endfor
            </tr>
            @endfor
        </tbody>
    </table>

</div>
</body>
</html>