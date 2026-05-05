<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctor's Order</title>

<style>
@page { size: 8.5in 13in portrait; margin: 0.45in 0.55in; }

* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: 'Times New Roman', serif;
    font-size: 9pt;
    background:#c9c9c9;
}


/* SCREEN VIEW */
@media screen {
    body { padding:50px 0; }

    .paper {
        width:8.5in;
        min-height:13in;
        margin:auto;
        background:#fff;
        padding:0.45in 0.55in;
        box-shadow:0 4px 20px rgba(0,0,0,.3);
    }
}

/* PRINT */
@media print {
    body { background:#fff; }
    .paper { width:100%; box-shadow:none; padding:0; }
    .no-print { display:none; }
}

/* TOOLBAR */
.toolbar {
    position:fixed;
    top:0; left:0; right:0;
    height:45px;
    background:#1e3a5f;
    color:#fff;
    display:flex;
    align-items:center;
    padding:0 20px;
    font-size:13px;
}
.toolbar button {
    margin-left:auto;
    padding:5px 15px;
    font-weight:bold;
    cursor:pointer;
}

/* HEADER */
.header {
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 2px solid #000;
    margin-bottom: 10px;
    padding-bottom: 5px;
}

.logo-box {
    width: 65px;
    height: 65px;
}

.logo-box img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.logo-ph {
    width: 65px;
    height: 65px;
    border: 1px dashed #aaa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8pt;
}

.header-center {
    flex: 1;
    text-align: center;
}

.h-rep { font-size: 8.5pt; }
.h-prov { font-size: 10pt; font-weight: bold; }
.h-mun { font-size: 8.5pt; }
.h-hosp { font-size: 15pt; font-weight: bold; }


/* PATIENT INFO */
.pt-table {
    width:100%;
    border-collapse:collapse;
    margin-bottom:10px;
}
.pt-table td {
    border:1px solid #000;
    padding:4px;
    font-size:8.5pt;
}

/* TITLE */
.title {
    text-align:center;
    font-weight:bold;
    font-size:13pt;
    margin-bottom:5px;
    border-bottom:1px solid #000;
}

/* LEGEND */
.legend {
    text-align:center;
    font-size:8pt;
    margin-bottom:5px;
}

/* TABLE */
.table {
    width:100%;
    border-collapse:collapse;
}
.table th, .table td {
    border:1px solid #000;
    padding:5px;
    font-size:8.5pt;
}
.table th {
    text-align:center;
    font-weight:bold;
}

/* COLUMN WIDTHS */
.col-date { width:18%; }
.col-order { width:52%; }
.col-status { width:10%; }
.col-sign { width:20%; }

.blank-row td { height:22px; }
.center { text-align:center; }
</style>

</head>

<body>

<div class="toolbar no-print">
    Doctor’s Order Compliance Sheet
    <button onclick="window.print()">Print</button>
</div>

<div class="paper">

<!-- HEADER -->

<div class="header">
    @if(file_exists(public_path('images/province-logo.png')))
        <div class="logo-box">
            <img src="{{ asset('images/province-logo.png') }}">
        </div>
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
        <div class="logo-box">
            <img src="{{ asset('images/lumc-logo.png') }}">
        </div>
    @else
        <div class="logo-ph">LUMC<br>Logo</div>
    @endif
</div>

<!-- PATIENT INFO -->

<table class="pt-table">
<tr>
<td colspan="3"><b>Patient Name:</b> ______________________________</td>
<td><b>Case No:</b> ________</td>
<td><b>Ward:</b> ________</td>
</tr>

<tr>
<td colspan="2"><b>Address:</b> ______________________________</td>
<td><b>Tel No:</b> ________</td>
<td><b>Sex:</b> ☐ M ☐ F</td>
<td><b>Civil:</b> ☐ S ☐ M ☐ W ☐ Sep</td>
</tr>
</table>

<!-- TITLE -->

<div class="title">DOCTOR’S ORDER COMPLIANCE SHEET</div>

<!-- LEGEND -->

<div class="legend">
C - Carried | A - Administrative | R - Requested | E - Endorsed | D - Discontinued
</div>

<!-- TABLE -->

<table class="table">
<thead>
<tr>
<th class="col-date">Date/Time</th>
<th class="col-order">Doctor’s Order</th>
<th class="col-status">Status</th>
<th class="col-sign">Time/Posted/Signature</th>
</tr>
</thead>

<tbody>

{{-- <!-- SAMPLE -->

<tr>
<td>________</td>
<td>________________________________________</td>
<td class="center">__</td>
<td>__________________</td>
</tr> --}}

@foreach($orders as $order)
<tr>
    <td>
        {{ \Carbon\Carbon::parse($order->ordered_at)->format('M d, Y h:i A') }}
    </td>

    <td>
        {{ $order->order }}
    </td>

    <td class="center">
        <strong>{{ $order->status }}</strong>
    </td>

    <td>
        @if($order->carried_at)
            {{ \Carbon\Carbon::parse($order->carried_at)->format('h:i A') }}
            / {{ $order->nurse_name }}
        @endif
    </td>
</tr>
@endforeach

<!-- BLANK ROWS -->

@for($i=0;$i<28;$i++)

<tr class="blank-row">
<td></td><td></td><td></td><td></td>
</tr>
@endfor

</tbody>
</table>

</div>

</body>
</html>
