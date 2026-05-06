<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctor's Order</title>
<style>
@page { size: 8.5in 13in portrait; margin: 0.45in 0.55in; }
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Times New Roman', serif; font-size: 9pt; background:#c9c9c9; }

@media screen {
    body { padding:50px 0; }
    .paper { width:8.5in; min-height:13in; margin:auto; background:#fff; padding:0.45in 0.55in; box-shadow:0 4px 20px rgba(0,0,0,.3); }
}
@media print {
    body { background:#fff; }
    .paper { width:100%; box-shadow:none; padding:0; }
    .no-print { display:none; }
}

/* ── Toolbar ── */
.toolbar { position:fixed; top:0; left:0; right:0; height:46px; background:#1e3a5f; color:#fff; font-family:'Segoe UI',system-ui,sans-serif; font-size:12px; display:flex; align-items:center; padding:0 22px; gap:14px; z-index:9999; box-shadow:0 2px 10px rgba(0,0,0,.35); }
.toolbar .lbl { font-size:13px; font-weight:700; }
.toolbar .tag { background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:3px; padding:2px 9px; font-size:10px; text-transform:uppercase; }
.toolbar .spacer { flex:1; }
.toolbar .pt-info { font-size:11px; color:rgba(255,255,255,.8); }
.btn-print { background:#fff; color:#1e3a5f; border:none; padding:6px 18px; border-radius:4px; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; }
.btn-print:hover { background:#dbeafe; }

/* ── Header ── */
.header { display:flex; align-items:center; gap:12px; border-bottom:2.5px solid #000; margin-bottom:10px; padding-bottom:9px; }
.logo-box { width:68px; height:68px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
.logo-box img { width:68px; height:68px; object-fit:contain; }
.logo-ph { width:68px; height:68px; flex-shrink:0; border:1.5px dashed #bbb; display:flex; align-items:center; justify-content:center; font-size:7.5pt; color:#bbb; text-align:center; line-height:1.4; }
.header-center { flex:1; text-align:center; line-height:1.35; }
.h-rep  { font-size:8.5pt; }
.h-prov { font-size:10pt; font-weight:bold; text-transform:uppercase; letter-spacing:.04em; }
.h-mun  { font-size:8.5pt; }
.h-hosp { font-size:15pt; font-weight:bold; text-transform:uppercase; letter-spacing:.06em; margin-top:3px; }

/* ── Patient Info Table ── */
.pt-table { width:100%; border-collapse:collapse; margin-bottom:10px; }
.pt-table td { border:1.2px solid #000; padding:3px 5px; vertical-align:top; font-size:8.5pt; }
.field-lbl { font-weight:bold; font-size:7.5pt; text-transform:uppercase; display:block; margin-bottom:2px; }
.field-val { display:block; min-height:14px; font-size:8.5pt; }
.cb-row { display:flex; gap:5px; flex-wrap:wrap; margin-top:2px; }
.cb { display:inline-flex; align-items:center; gap:3px; font-size:8pt; }
.sq { width:10px; height:10px; border:1.2px solid #000; display:inline-block; flex-shrink:0; }
.sq.checked { background:#000; }

/* ── Title ── */
.title { text-align:center; font-weight:bold; font-size:13pt; margin-bottom:5px; border-bottom:1px solid #000; padding-bottom:3px; text-transform:uppercase; letter-spacing:.05em; }

/* ── Legend ── */
.legend { text-align:center; font-size:8pt; margin-bottom:8px; }

/* ── Orders Table ── */
.table { width:100%; border-collapse:collapse; }
.table th, .table td { border:1.2px solid #000; padding:5px; font-size:8.5pt; }
.table th { text-align:center; font-weight:bold; background:#f0f0f0; font-size:8pt; text-transform:uppercase; }
.col-date   { width:18%; }
.col-order  { width:50%; }
.col-status { width:10%; }
.col-sign   { width:22%; }
.blank-row td { height:22px; }
.center { text-align:center; }
</style>
</head>
<body>

@php
    $patient     = $visit->patient;
    $history     = $visit->medicalHistory;
    $sex         = strtolower($patient->sex ?? '');
    $isMale      = $sex === 'male';
    $isFemale    = $sex === 'female';
    $civilStatus = strtolower($patient->civil_status ?? '');

    $fullName = trim(
        ($patient->family_name ?? '') . ', ' .
        ($patient->first_name  ?? '') . ' ' .
        ($patient->middle_name ?? '')
    );
    $caseNo = $patient->case_no ?? $visit->id ?? '—';
    $ward   = $visit->admitted_service ?? $history?->service ?? $visit->visit_type ?? '—';

    $statusLetter = fn(string $s): string => match(strtolower($s)) {
        'carried'        => 'C',
        'discontinued'   => 'D',
        'requested'      => 'R',
        'endorsed'       => 'E',
        'administrative' => 'A',
        default          => '',   // pending → blank
    };
@endphp

<div class="toolbar no-print">
    <span class="lbl">Doctor's Order Compliance Sheet</span>
    <span class="tag">NUR-???</span>
    <span class="pt-info">{{ $fullName }} &nbsp;·&nbsp; {{ $caseNo }}</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()">🖨️&nbsp;&nbsp;Print / Save as PDF</button>
</div>

<div class="paper">

{{-- ══ HEADER ══ --}}
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

{{-- ══ PATIENT INFO ══ --}}
<table class="pt-table">
    <tr>
        <td colspan="3" style="width:55%;">
            <span class="field-lbl">Patient's Name: (Last) &nbsp; (Given) &nbsp; (Middle)</span>
            <span class="field-val">{{ $fullName }}</span>
        </td>
        <td style="width:15%;">
            <span class="field-lbl">Hosp. Case No.</span>
            <span class="field-val">{{ $caseNo }}</span>
        </td>
        <td style="width:15%;">
            <span class="field-lbl">Ward / Service</span>
            <span class="field-val">{{ $ward }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="width:40%;">
            <span class="field-lbl">Permanent Address</span>
            <span class="field-val">{{ $patient->address ?? '—' }}</span>
        </td>
        <td style="width:15%;">
            <span class="field-lbl">Tel. No.</span>
            <span class="field-val">{{ $patient->contact_number ?? '—' }}</span>
        </td>
        <td>
            <span class="field-lbl">Sex</span>
            <div class="cb-row">
                <label class="cb"><span class="sq {{ $isMale   ? 'checked' : '' }}"></span> Male</label>
                <label class="cb"><span class="sq {{ $isFemale ? 'checked' : '' }}"></span> Female</label>
            </div>
        </td>
        <td>
            <span class="field-lbl">Civil Status</span>
            <div class="cb-row">
                <label class="cb"><span class="sq {{ $civilStatus === 'single'   ? 'checked' : '' }}"></span> Single</label>
                <label class="cb"><span class="sq {{ $civilStatus === 'married'  ? 'checked' : '' }}"></span> Married</label>
            </div>
            <div class="cb-row">
                <label class="cb"><span class="sq {{ $civilStatus === 'widowed'   ? 'checked' : '' }}"></span> Widowed</label>
                <label class="cb"><span class="sq {{ $civilStatus === 'separated' ? 'checked' : '' }}"></span> Separated</label>
            </div>
        </td>
    </tr>
</table>

{{-- ══ TITLE & LEGEND ══ --}}
<div class="title">Doctor's Order Compliance Sheet</div>
<div class="legend">
    C - Carried &nbsp;|&nbsp; A - Administrative &nbsp;|&nbsp; R - Requested &nbsp;|&nbsp; E - Endorsed &nbsp;|&nbsp; D - Discontinued
</div>

{{-- ══ ORDERS TABLE ══ --}}
<table class="table">
<thead>
<tr>
    <th class="col-date">Date / Time</th>
    <th class="col-order">Doctor's Order</th>
    <th class="col-status">Status</th>
    <th class="col-sign">Time / Posted / Signature</th>
</tr>
</thead>
<tbody>

@forelse($orders as $order)
<tr>
    <td>
        {{ \Carbon\Carbon::parse($order->order_date)
            ->timezone('Asia/Manila')
            ->format('M d, Y H:i') }}
    </td>

    <td style="{{ $order->status === 'discontinued' ? 'text-decoration:line-through;color:#555;' : '' }}">
        {{ $order->order_text }}
    </td>

    <td class="center">
        <strong>{{ $statusLetter($order->status) }}</strong>
    </td>

    <td>
        @if($order->completed_at && $order->status === 'carried')
            {{ \Carbon\Carbon::parse($order->completed_at)->timezone('Asia/Manila')->format('H:i') }}
            / {{ $order->completedBy->name ?? '' }}
        @endif
    </td>
</tr>
@empty
@endforelse

@php $fillerCount = max(0, 28 - $orders->count()); @endphp
@for($i = 0; $i < $fillerCount; $i++)
<tr class="blank-row"><td></td><td></td><td></td><td></td></tr>
@endfor

</tbody>
</table>

</div>{{-- /.paper --}}
</body>
</html>