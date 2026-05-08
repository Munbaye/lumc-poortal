<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>OB Record</title>

<style>
@page { size: 8.5in 13in; margin: 0.5in; }

body {
    font-family: "Times New Roman", serif;
    font-size: 11pt;
    background: #cfcfcf;
}

.paper {
    width: 8.5in;
    min-height: 13in;
    margin: auto;
    background: white;
    padding: 0.5in;
}

/* HEADER */
.header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    border-bottom:2px solid black;
    padding-bottom:8px;
}

.header img { width:65px; }

.center {
    text-align:center;
    flex:1;
}

.center h1 {
    font-size:16pt;
    margin:2px 0;
    font-weight:bold;
}

.title {
    text-align:center;
    font-weight:bold;
    margin:10px 0;
}

/* LINE SYSTEM */
.line {
    display:inline-block;
    border-bottom:1px solid black;
    height:14px;
}

/* CONTROLLED LENGTHS */
.line-xs { width:40px; }
.line-sm { width:80px; }
.line-md { width:150px; }
.line-ms { width:200px; }
.line-lg { width:250px; }
.line-xl { width:400px; }

.full-line {
    width:100%;
    border-bottom:1px solid black;
    height:14px;
    margin-top:4px;
}

.full-line {
    width:100%;
    border-bottom:1px solid black;
    height:14px;
    margin-top:4px;
}


.section { margin-top:10px; }

/* TABLE */
table {
    width:100%;
    border-collapse:collapse;
    margin-top:8px;
}

td, th {
    border:1px solid black;
    padding:6px;          /* larger spacing */
    font-size:11pt;       /* bigger text */
    height:22px;          /* taller rows */
}

th {
    font-weight:bold;
    text-align:center;
}

/* PRINT */
@media print {
    body { background:white; }
    .paper { margin:0; }
}
</style>
</head>

<body>

@php $patient = $visit->patient; @endphp

<div class="paper">

<!-- HEADER -->
<div class="header">
    <img src="{{ asset('images/province-logo.png') }}">

    <div class="center">
        <div>Republic of the Philippines</div>
        <div><b>Province of La Union</b></div>
        <div>Municipality of Agoo</div>
        <h1>LA UNION MEDICAL CENTER</h1>
    </div>

    <img src="{{ asset('images/lumc-logo.png') }}">
</div>

<div class="title">OB RECORD</div>

<!-- TOP -->
<div>
<b>NAME OF PATIENT:</b>
<span class="line line-ms">{{ $patient->full_name }}</span>

&nbsp;&nbsp;

<b>Age:</b>
<span class="line line-xs">{{ $patient->age }}</span>

&nbsp;&nbsp;

<b>G:</b> <span class="line line-xs"></span>
<b>P:</b> <span class="line line-xs"></span>

( T <span class="line line-xs"></span>
  P <span class="line line-xs"></span>
  A <span class="line line-xs"></span>
  L <span class="line line-xs"></span> )
  </div>

<div class="section">
<b>HISTORY OF PRESENT ILLNESS / CHIEF COMPLAINT:</b>
<div class="full-line"></div>
<div class="full-line"></div>
</div>

<!-- PRENATAL -->
<div class="section">
<b>PRENATAL CHECK-UP:</b>
( ) PRIVATE <span class="line line-sm"></span>
( ) RHU <span class="line line-sm"></span>
( ) LYING-IN <span class="line line-sm"></span>
( ) OTHERS <span class="line line-sm"></span>
</div>

<!-- MENSTRUAL -->
<div class="section">
<b>MENSTRUAL HISTORY:</b><br>
Menarche: <span class="line line-sm"></span><br>

Succeeding Menses:
Interval: <span class="line line-sm"></span>
Duration: <span class="line line-sm"></span>
Dysmenorrhea ( ) </div>

<div class="section">
<b>PAST MEDICAL HISTORY:</b>
<div class="full-line"></div>
</div>

<div class="section">
<b>FAMILY HISTORY:</b>
<div class="full-line"></div>
</div>

<!-- TABLE -->
<div class="section">
<b>PREVIOUS PREGNANCIES</b>
(Include years, sex, term/preterm, manner of delivery, complications, and others)

<table>
<tr>
<th>GRAVIDA</th>
<th>AOG (TERM, PRE-TERM, ABORTION)</th>
<th>MANNER OF DELIVERY (NSD, CS, FORCEPS, CURETTAGE)</th>
<th>DATE OF DELIVERY</th>
<th>GENDER</th>
<th>WEIGHT</th>
<th>COMPLICATIONS</th>
</tr>

@for($i=0;$i<5;$i++)
<tr>
<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
</tr>
@endfor
</table>
</div>

<!-- PRESENT -->
<div class="section">
<b>PRESENT PREGNANCY:</b><br>
LMP: <span class="line line-sm"></span>
PMP: <span class="line line-sm"></span>
EDC: <span class="line line-sm"></span>
AOG: <span class="line line-sm"></span>
</div>

<div class="section">
Date of Quickening: <span class="line line-xl"></span>
</div>

<div class="section">
Morning Sickness:
( ) Mild ( ) Moderate ( ) Severe ( ) Absent
</div>

<div class="section">
Abnormal Symptoms:
( ) Headache ( ) Dizziness ( ) Blurring of Vision ( ) Jaundice ( ) Bleeding
</div>

<div class="section">
Edema:
( ) Localized ( ) Ankle ( ) Legs ( ) Hands ( ) Face
</div>

<!-- PHYSICAL -->
<div class="section">
<b>PHYSICAL EXAMINATION</b>
<br>
Condition on Admission:( ) Conscious ( ) Unconscious 
( ) Strong ( ) Fair ( ) Weak 
( ) Ambulatory ( ) Non-Ambulatory
</div>

<div class="section">
<b>Vital Signs:</b>
BP: <span class="line line-sm"></span>
PR: <span class="line line-sm"></span>
TEMP: <span class="line line-sm"></span>
WEIGHT: <span class="line line-sm"></span>
RR: <span class="line line-sm"></span>
</div>

<div class="section">
HEENT: <span class="line line-ms"></span>
SKIN: <span class="line line-ms"></span>
C/L: <span class="line line-ms"></span>
</div>

<div class="section">
HEART: <span class="line line-lg"></span>
</div>

<div class="section">
<b>Abdomen:</b> <br>
FH: <span class="line line-sm"></span>
FHT: <span class="line line-sm"></span>
Location: <span class="line line-sm"></span>
</div>

<div class="section">
<b>I.E.:</b><br>
Dilatation: <span class="line line-xs"></span> cm
Effacement: <span class="line line-xs"></span> %
Station: <span class="line line-xs"></span>
Presentation: <span class="line line-xs"></span> ( ) BOW
</div>

<div class="section">
<b>CONTRACTIONS ON ADMISSION:</b><br>
Interval: <span class="line line-sm"></span> /min
Duration: <span class="line line-sm"></span> /min
Intensity: <span class="line line-md"></span>
</div>

<div class="section">
<b>DIAGNOSIS ON ADMISSION:</b>
<div class="full-line"></div>
</div>

<div class="section">
<b>ATTENDING PHYSICIAN:</b>
<span class="line" style="width:300px;"></span>
</div>

</div>
</body>
</html>
