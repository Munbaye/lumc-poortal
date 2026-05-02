<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>TPR Record — LUMC</title>

<style>
@page { size: 8.5in 13in; margin: 0.4in 0.5in; }

body {
    font-family: 'Times New Roman', serif;
    font-size: 9pt;
    background: #d6d6d6;
}

.paper {
    width: 8.5in;
    min-height: 13in;
    margin: auto;
    background: white;
    padding: 0.4in 0.5in;
}

/* HEADER */
.header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    border-bottom:2px solid black;
    padding-bottom:6px;
}

.header-center {
    text-align:center;
    flex:1;
}

.header h1 {
    font-size:15pt;
    font-weight:bold;
}

/* TITLE */
.title {
    text-align:center;
    font-weight:bold;
    font-size:12pt;
    margin:6px 0;
}

/* TABLE */
table {
    width:100%;
    border-collapse:collapse;
    font-size:8pt;
}

td {
    border:1px solid black;
    padding:2px;
    text-align:center;
}

.label {
    text-align:left;
    font-weight:bold;
}

/* GRAPH */
.graph-container {
    display: flex;
    margin-top: 8px;
}

/* LEFT SCALE */
.left-scale {
    width: 70px;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    font-size: 7pt;
}

/* LABEL COLUMN */
.scale-labels {
    border: 1px solid black;
}

.scale-labels div {
    border-bottom: 1px solid black;
    height: 18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
}

/* SCALE NUMBERS */
.scale-values {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 4px;
}

/* TEMP */
.temp-values div {
    height: 10px;
}

/* PULSE */
.pulse-values div {
    height: 12px;
}

/* RESP */
.resp-values div {
    height: 12px;
}

/* GRAPH */
.graph {
    flex: 1;
    height: 320px;
    background-image:
        linear-gradient(to right, #bbb 1px, transparent 1px),
        linear-gradient(to bottom, #bbb 1px, transparent 1px);
    background-size: 16px 10px;
    position: relative;
}

.graph::after {
    content: "";
    position: absolute;
    top: 50%;
    width: 100%;
    border-top: 2px solid black;
}

/* FOOTER */
.footer {
    display:flex;
    justify-content:space-between;
    margin-top:8px;
    font-size:8pt;
    font-weight:bold;
}
</style>
</head>

<body>

@php
$patient = $visit->patient;

$temps = [];
$pulse = [];
$resp  = [];

foreach($vitals ?? [] as $v){
    $day = \Carbon\Carbon::parse($v->taken_at)->day;
    $temps[$day] = $v->temperature;
    $pulse[$day] = $v->pulse_rate;
    $resp[$day]  = $v->respiratory_rate;
}
@endphp

<div class="paper">

<!-- HEADER -->
<div class="header">
    <img src="{{ asset('images/province-logo.png') }}" style="width:65px;">
    
    <div class="header-center">
        <div>Republic of the Philippines</div>
        <div><strong>Province of La Union</strong></div>
        <div>Municipality of Agoo</div>
        <h1>LA UNION MEDICAL CENTER</h1>
    </div>

    <img src="{{ asset('images/lumc-logo.png') }}" style="width:65px;">
</div>

<div class="title">TPR RECORD</div>

<!-- PATIENT INFO -->
<div style="margin-top:10px; font-size:9pt;">

    <div style="margin-bottom:5px;">
        <strong>Name of Patient:</strong>
        <span style="display:inline-block; min-width:250px; border-bottom:1px solid black;">
            {{ $patient->full_name }}
        </span>

        &nbsp;&nbsp;&nbsp;

        <strong>Age:</strong>
        <span style="display:inline-block; width:60px; border-bottom:1px solid black; text-align:center;">
            {{ $patient->age ?? '' }}
        </span>

        &nbsp;&nbsp;&nbsp;

        <strong>Sex:</strong>
        <span style="display:inline-block; width:80px; border-bottom:1px solid black; text-align:center;">
            {{ $patient->sex }}
        </span>

        &nbsp;&nbsp;&nbsp;

        <strong>Ward:</strong>
        <span style="display:inline-block; min-width:180px; border-bottom:1px solid black;">
            {{ $visit->ward ?? '' }}
        </span>
    </div>

    <div>
        <strong>Hospital Case No.:</strong>
        <span style="display:inline-block; min-width:250px; border-bottom:1px solid black;">
            {{ $visit->case_no ?? '' }}
        </span>
    </div>

</div>

<!-- DAYS -->
<div style="margin-top:8px; font-size:9pt;">

    <div style="margin-bottom:4px;">
        <strong>Day of Month:</strong>
        <span style="display:inline-block; width:80%; border-bottom:1px solid black;"></span>
    </div>

    <div style="margin-bottom:4px;">
        <strong>No. of Days in Hospital:</strong>
        <span style="display:inline-block; width:60%; border-bottom:1px solid black;"></span>
    </div>

    <div>
        <strong>Weight:</strong>
        <span style="display:inline-block; width:40%; border-bottom:1px solid black;"></span>
    </div>

</div>

<!-- GRAPH -->
{{-- <div class="graph-container">

    <!-- LEFT SIDE -->
    <div class="left-scale">

        <!-- LABELS -->
        <div class="scale-labels">
            <div>Resp</div>
            <div>Pulse</div>
            <div>Temp</div>
        </div>

        <!-- TEMPERATURE -->
        <div class="scale-values temp-values">
            <div><b>°C</b></div>
            @for($i=42;$i>=35;$i--)
                <div>{{ $i }}</div>
            @endfor
        </div>

        <!-- PULSE -->
        <div class="scale-values pulse-values">
            @foreach([180,160,140,120,100,80,60] as $p)
                <div>{{ $p }}</div>
            @endforeach
        </div>

        <!-- RESP -->
        <div class="scale-values resp-values">
            @foreach([50,40,30,20,10] as $r)
                <div>{{ $r }}</div>
            @endforeach
        </div>

    </div> --}}
<!-- GRAPH WITH HORIZONTAL LABELS -->
<div style="margin-top:10px;">

    <table style="width:100%; border-collapse:collapse;">
        <tr>

            <!-- LEFT LABELS (HORIZONTAL NA) -->
            <td style="width:120px; border:1px solid black; vertical-align:top; padding:0;">

                <!-- LABEL HEADER -->
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="border-bottom:1px solid black; text-align:center;">Resp</td>
                        <td style="border-bottom:1px solid black; text-align:center;">Pulse</td>
                        <td style="border-bottom:1px solid black; text-align:center;">Temp</td>
                    </tr>
                </table>

                <!-- VALUES -->
                <div style="padding:5px; font-size:7pt; line-height:10px; text-align:right;">

                    <!-- TEMP -->
                    <div>°C</div>
                    <div>42</div>
                    <div>41</div>
                    <div>40</div>
                    <div>39</div>
                    <div>38</div>
                    <div>37</div>
                    <div>36</div>
                    <div>35</div>

                    <br>
 <div style="padding:5px; font-size:7pt; line-height:10px; text-align:center;">
                    <!-- PULSE -->
                    <div>180</div>
                    <div>160</div>
                    <div>140</div>
                    <div>120</div>
                    <div>100</div>
                    <div>80</div>
                    <div>60</div>

                    <br>
 <div style="padding:5px; font-size:7pt; line-height:10px; text-align:left;">
                    <!-- RESP -->
                    <div>50</div>
                    <div>40</div>
                    <div>30</div>
                    <div>20</div>
                    <div>10</div>

                </div>
            </td>

            <!-- GRAPH GRID  -->
            <td style="border:1px solid black;">

                <div style="
                    height:300px; 
                    background-image:
                        linear-gradient(to right, #bbb 1px, transparent 1px),
                        linear-gradient(to bottom, #bbb 1px, transparent 1px);
                    background-size:15px 10px;
                    position:relative;
                ">

                    {{-- <!-- MID LINE -->
                    <div style="
                        position:absolute;
                        top:50%;
                        width:100%;
                        border-top:2px solid black;
                    "></div> --}}

                </div>

            </td>

        </tr>
    </table>

</div>

<!-- URINE + STOOL -->
<table style="margin-top:10px;">
<tr>
    <td class="label">URINE</td>
    <td>7-3</td>
    @for($i=1;$i<=31;$i++) <td></td> @endfor
</tr>
<tr>
    <td></td>
    <td>3-11</td>
    @for($i=1;$i<=31;$i++) <td></td> @endfor
</tr>
<tr>
    <td></td>
    <td>11-7</td>
    @for($i=1;$i<=31;$i++) <td></td> @endfor
</tr>

<tr>
    <td class="label">STOOL</td>
    <td>7-3</td>
    @for($i=1;$i<=31;$i++) <td></td> @endfor
</tr>
<tr>
    <td></td>
    <td>3-11</td>
    @for($i=1;$i<=31;$i++) <td></td> @endfor
</tr>
<tr>
    <td></td>
    <td>11-7</td>
    @for($i=1;$i<=31;$i++) <td></td> @endfor
</tr>
</table>

<!-- FOOTER -->
<div class="footer">
    <div>Hospital Form No. 10</div>
    <div>GRAPHIC RECORD</div>
</div>

</div>
</tr>
</table>

</body>
</html>