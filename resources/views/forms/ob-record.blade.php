<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>OB Record</title>

<style>

/* PRINT SIZE */
@page {
    size: 8.5in 13in;
    margin: 0.5in;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 11pt;
    background: #c9c9c9;
}

/* PAPER STYLE (LIKE TPR) */
.paper {
    width: 8.5in;
    min-height: 13in;
    margin: 20px auto;
    background: white;
    padding: 0.5in;
    box-shadow: 0 4px 25px rgba(0,0,0,0.3);
}

/* HEADER */
.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid black;
    padding-bottom: 6px;
}

.header img {
    width: 70px;
}

.header-center {
    text-align: center;
    flex: 1;
}

.header-center h1 {
    font-size: 16pt;
    margin: 2px 0;
}

/* TITLE */
.title {
    text-align: center;
    font-weight: bold;
    margin-top: 6px;
}

/* UNDERLINE STYLE */
.line {
    display: inline-block;
    border-bottom: 1px solid black;
    min-width: 150px;
}

/* SECTION */
.section {
    margin-top: 8px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 5px;
}

td, th {
    border: 1px solid black;
    padding: 4px;
    font-size: 10pt;
}

/* PRINT CLEAN */
@media print {
    body {
        background: white;
    }

    .paper {
        margin: 0;
        box-shadow: none;
    }
}

</style>
</head>

<body>

@php
    $patient = $visit->patient;
@endphp

<div class="paper">

    <!-- HEADER -->
    <div class="header">

        <img src="{{ asset('images/province-logo.png') }}">

        <div class="header-center">
            <div>Republic of the Philippines</div>
            <div><b>Province of La Union</b></div>
            <div>Municipality of Agoo</div>
            <h1>LA UNION MEDICAL CENTER</h1>
        </div>

        <img src="{{ asset('images/lumc-logo.png') }}">

    </div>

    <div class="title">OB RECORD</div>

    <!-- PATIENT INFO -->
    <div class="section">
        Name of Patient:
        <span class="line">{{ $patient->full_name ?? '' }}</span>

        &nbsp;&nbsp; Age:
        <span class="line" style="min-width:60px;">{{ $patient->age ?? '' }}</span>

        &nbsp;&nbsp; G:
        <span class="line" style="min-width:50px;"></span>

        &nbsp;&nbsp; P:
        <span class="line" style="min-width:50px;"></span>

        ( T P A L )
    </div>

    <div class="section">
        History of Present Illness / Chief Complaint:
        <div class="line" style="width:100%;"></div>
        <div class="line" style="width:100%;"></div>
    </div>

    <div class="section">
        Prenatal Check-Up:
        ( ) Private &nbsp;
        ( ) RHU &nbsp;
        ( ) Lying-in &nbsp;
        ( ) Others
    </div>

    <div class="section">
        <b>Menstrual History:</b><br>
        Menarche: <span class="line"></span>
        Interval: <span class="line"></span>
        Duration: <span class="line"></span>
    </div>

    <div class="section">
        <b>Past Medical History:</b>
        <div class="line" style="width:100%;"></div>
    </div>

    <div class="section">
        <b>Family History:</b>
        <div class="line" style="width:100%;"></div>
    </div>

    <!-- PREVIOUS PREGNANCIES -->
    <div class="section">
        <b>Previous Pregnancies (Include years, sex,term/preterm,manner of delivery,complications, and others)</b>

        <table>
            <tr>
                <th>Gravida</th>
                <th>AOG</th>
                <th>Manner of Delivery</th>
                <th>Date</th>
                <th>Gender</th>
                <th>Weight</th>
                <th>Complications</th>
            </tr>

            @for($i=0;$i<5;$i++)
            <tr>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            @endfor
        </table>
    </div>

    <!-- PRESENT PREGNANCY -->
    <div class="section">
        <b>Present Pregnancy:</b><br>

        LMP: <span class="line"></span>
        PMP: <span class="line"></span>
        EDC: <span class="line"></span>
        AOG: <span class="line"></span>
        <div>
        Date of Quickening: <span class="line"></span>
    </div>
    </div>

    <div class="section">
        Morning Sickness:
        ( ) Mild ( ) Moderate ( ) Severe ( ) Absent
    </div>

    <div class="section">
        Abnormal Symptoms:
        ( ) Headache
        ( ) Dizziness
        ( ) Blurring of Vision
        ( ) Jaundice
        ( ) Bleeding
    </div>

    <div class="section">
        <b>Physical Examination:</b><br>

        Condition:
        ( ) Conscious
        ( ) Unconscious
        ( ) Strong
        ( ) Fair
        ( ) Weak
        ( ) Ambulatory
        ( ) Non-Ambulatory
    </div>

    <div class="section">
        Vital Signs:
        BP: <span class="line"></span>
        PR: <span class="line"></span>
        TEMP: <span class="line"></span>
    </div>
<div class="section">
 Weight: <span class="line"></span>
RR: <span class="line"></span>     </div>
    
    <div class="section">
       HEENT: <span class="line"></span>
        SKIN:<span class="line"></span>
        CL: <span class="line"></span>
    </div>

    <div class="section">
       HEART: <span class="line"></span>
    </div>

    <div class="section">
        Abdomen:<br>
        FH: <span class="line"></span>
        FHT: <span class="line"></span>
        Location: <span class="line"></span>
    </div>

    <div class="section">
        IE:<br>
        Dilatation: <span class="line"> cm</span>
        Effacement: <span class="line"> %</span>
        Station: <span class="line"></span>
        Presentation:<span class="line">()BOW</span>
    </div>

    <div class="section">
        Contractions on Admission:<br>
        Interval: <span class="line">/min</span>
        Duration: <span class="line">/min</span>
        Intensity: <span class="line"></span>
    </div>

    <div class="section">
        Diagnosis on Admission:
        <div class="line" style="width:100%;"></div>
    </div>

    <div class="section">
        Attending Physician:
        <span class="line" style="width:300px;"></span>
    </div>

</div>

</body>
</html>