<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Emergency Room Record — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size:8.5in 13in portrait; margin:0.5in; }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:Arial,Helvetica,sans-serif;font-size:9pt;color:#000;background:#c9c9c9;}
        @media screen{body{padding:52px 0 40px;}.paper{width:8.5in;min-height:13in;margin:0 auto;background:#fff;box-shadow:0 4px 28px rgba(0,0,0,.28);padding:0.5in;}}
        /* No top padding when toolbar is hidden */
        body.readonly-mode{padding-top:12px !important;}
        @media print{body{background:#fff;padding:0;}.paper{width:100%;padding:0;box-shadow:none;}.no-print{display:none !important;}[contenteditable]{outline:none !important;background:transparent !important;}input[type="date"],input[type="number"],input[type="text"],input[type="tel"],input[type="time"]{border-color:#000 !important;background:transparent !important;outline:none !important;}}

        .toolbar{position:fixed;top:0;left:0;right:0;height:46px;background:#1e3a5f;color:#fff;font-family:'Segoe UI',system-ui,sans-serif;font-size:12px;display:flex;align-items:center;padding:0 22px;gap:14px;z-index:9999;box-shadow:0 2px 10px rgba(0,0,0,.35);}
        .toolbar .lbl{font-size:13px;font-weight:700;}.toolbar .tag{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:3px;padding:2px 9px;font-size:10px;text-transform:uppercase;}.toolbar .spacer{flex:1;}
        .btn-print{background:#fff;color:#1e3a5f;border:none;padding:6px 18px;border-radius:4px;font-size:12px;font-weight:700;cursor:pointer;}.btn-print:hover{background:#dbeafe;}
        .btn-save{background:#059669;color:#fff;border:none;padding:6px 22px;border-radius:4px;font-size:12px;font-weight:700;cursor:pointer;}.btn-save:hover{background:#047857;}.btn-save:disabled{opacity:.6;cursor:not-allowed;}

        /* Readonly: lock all interactivity */
        body.readonly-mode [contenteditable]{ pointer-events:none; cursor:default; }
        body.readonly-mode input,body.readonly-mode select,body.readonly-mode textarea{ pointer-events:none; cursor:default; }
        body.readonly-mode .sq{ pointer-events:none; cursor:default; }

        .ft{width:100%;border-collapse:collapse;font-size:9pt;}
        .ft td{border:1.5px solid #000;padding:3px 5px;vertical-align:top;}
        .L{font-weight:bold;font-size:8.5pt;text-transform:uppercase;}
        .Ls{font-weight:bold;font-size:7.5pt;text-transform:uppercase;}
        .f{display:inline-block;min-height:14px;min-width:60px;border-bottom:1px solid #555;vertical-align:bottom;outline:none;cursor:text;font-size:9pt;font-family:Arial,sans-serif;padding:0 2px;}
        .fb{display:block;width:100%;min-height:16px;border-bottom:1px solid #555;outline:none;cursor:text;font-size:9pt;font-family:Arial,sans-serif;padding:1px 2px;margin-top:2px;}
        .fb2{min-height:30px;}.fb3{min-height:60px;}.fb4{min-height:80px;}
        @media screen{.f:focus,.fb:focus{background:#fef9ec;}}
        .fi-date{border:none;border-bottom:1px solid #555;outline:none;font-size:9pt;font-family:Arial,sans-serif;padding:1px 2px;background:transparent;width:100%;margin-top:2px;}
        .fi-date:focus{background:#fef9ec;}
        .fi-num{border:none;border-bottom:1px solid #555;outline:none;font-size:9pt;font-family:Arial,sans-serif;padding:1px 2px;background:transparent;width:100%;margin-top:2px;}
        .fi-num:focus{background:#fef9ec;}
        .fi-inline{border:none;border-bottom:1px solid #555;outline:none;font-size:9pt;font-family:Arial,sans-serif;padding:0 2px;background:transparent;display:inline-block;min-width:40px;vertical-align:bottom;}
        .fi-inline:focus{background:#fef9ec;}
        .fi-tel{border:none;border-bottom:1px solid #555;outline:none;font-size:9pt;font-family:Arial,sans-serif;padding:1px 2px;background:transparent;width:100%;margin-top:2px;}
        .fi-tel:focus{background:#fef9ec;}
        @media print{.fi-date,.fi-num,.fi-inline,.fi-tel{border-bottom:1px solid #000 !important;}}
        .cb{display:inline-flex;align-items:center;gap:3px;margin-right:5px;white-space:nowrap;font-size:8.5pt;}
        .sq{width:10px;height:10px;border:1.2px solid #000;display:inline-block;flex-shrink:0;position:relative;vertical-align:middle;cursor:pointer;}
        .sq.on::after{content:'✓';position:absolute;top:-3px;left:0;font-size:10pt;font-weight:bold;line-height:1;}
        .hosp-name{font-size:14pt;font-weight:bold;text-transform:uppercase;line-height:1.2;}
        .hosp-addr{font-size:8.5pt;margin-top:2px;}
        .form-title{font-size:13pt;font-weight:bold;text-transform:uppercase;text-align:center;line-height:1.3;}
        #toast{position:fixed;bottom:22px;right:22px;background:#059669;color:#fff;padding:12px 22px;border-radius:8px;font-family:'Segoe UI',sans-serif;font-size:13px;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,.25);display:none;z-index:99999;}
        #toast.error{background:#dc2626;}
    </style>
</head>
<body class="{{ request()->boolean('readonly') ? 'readonly-mode' : '' }}">
@php
    $readonly = request()->boolean('readonly');
    $ce       = $readonly ? 'false' : 'true'; // shorthand for contenteditable attr
    $ro       = $readonly ? 'readonly' : '';   // shorthand for input readonly attr

    $er      = $erRecord ?? null;
    $patient = $visit->patient;
    $history = $visit->medicalHistory;
    $vitals  = $visit->latestVitals;
    $isER    = ($visit->visit_type ?? '') === 'ER';

    $doctorAdmittedAt  = $visit->doctor_admitted_at;
    $doctorAdmDateStr  = $doctorAdmittedAt?->timezone('Asia/Manila')->format('Y-m-d') ?? '';
    $doctorAdmTimeStr  = $doctorAdmittedAt?->timezone('Asia/Manila')->format('H:i') ?? '';

    $docUser = $history?->doctor;
    $doctorNameFormatted = '';
    if ($docUser) {
        $parts = explode(' ', trim($docUser->name));
        if (count($parts) >= 2) {
            $lastName  = strtoupper(array_pop($parts));
            $firstName = strtoupper($parts[0]);
            $middle    = isset($parts[1]) ? strtoupper(substr($parts[1], 0, 1)) . '.' : '';
            $doctorNameFormatted = trim("$firstName $middle $lastName");
        } else {
            $doctorNameFormatted = strtoupper($docUser->name);
        }
    }

    $typeOfSvc = $er?->type_of_service ?? $history?->service ?? '';
    $patFamily  = $er?->patient_family_name  ?? strtoupper($patient->family_name);
    $patFirst   = $er?->patient_first_name   ?? strtoupper($patient->first_name);
    $patMiddle  = $er?->patient_middle_name  ?? strtoupper($patient->middle_name ?? '');
    $addr       = $er?->permanent_address    ?? $patient->address;
    $tel        = $er?->telephone_no         ?? $patient->contact_number ?? '';
    $nat        = $er?->nationality          ?? $patient->nationality ?? 'Filipino';
    $age        = $er?->age                  ?? $patient->current_age ?? $patient->age ?? '';
    $bdateVal   = $er?->birthdate            ?? $patient->birthday;
    $bdateInput = $bdateVal ? \Carbon\Carbon::parse($bdateVal)->format('Y-m-d') : '';
    $sex        = $er?->sex                  ?? $patient->sex ?? '';
    $cs         = $er?->civil_status         ?? $patient->civil_status ?? '';
    $employer   = $er?->employer_name        ?? $patient->employer_name ?? '';
    $empTel     = $er?->employer_phone       ?? $patient->employer_phone ?? '';
    $regDate    = $er?->registration_date    ?? $visit->registered_at?->toDateString();
    $regDateInput = $regDate ? \Carbon\Carbon::parse($regDate)->format('Y-m-d') : '';
    $regTime    = $er?->registration_time    ?? $visit->registered_at?->timezone('Asia/Manila')->format('H:i') ?? '';
    $broughtBy  = $er?->brought_by          ?? '';
    $coa        = $er?->condition_on_arrival ?? '';
    $temp       = $er?->temperature          ?? $vitals?->temperature ?? '';
    $tempSite   = $er?->temperature_site     ?? $vitals?->temperature_site ?? '';
    $pulse      = $er?->pulse_rate           ?? $vitals?->pulse_rate ?? '';
    $bp         = $er?->blood_pressure       ?? $vitals?->blood_pressure ?? '';
    $rr         = $er?->respiratory_rate     ?? $vitals?->respiratory_rate ?? '';
    $ht         = $er?->height_cm            ?? $vitals?->height_cm ?? '';
    $wt         = $er?->weight_kg            ?? $vitals?->weight_kg ?? '';
    $cc         = $er?->chief_complaint      ?? $history?->chief_complaint ?? $visit->chief_complaint ?? '';
    $allergies  = $er?->allergies            ?? trim(($history?->drug_allergies ?? '').($history?->other_allergies ? ' / '.$history->other_allergies : ''));
    $curMed     = $er?->current_medication   ?? $history?->drug_therapy ?? '';
    $pfDx       = $er?->physical_findings_and_diagnosis ?? trim(($history?->admitting_impression ?? '').($history?->diagnosis ? "\n".$history->diagnosis : ''));
    $treatment  = $er?->treatment            ?? '';
    $disp       = $er?->disposition          ?? $visit->disposition ?? '';
    $condDisch  = $er?->condition_on_discharge ?? '';
    $medLegal   = (bool)($er?->medico_legal);
    $caseType   = $er?->case_type            ?? ($isER ? 'ER' : 'Non-ER');
    $notAuth    = $er?->notified_proper_authority ?? '';
    $dispDate   = $er?->disposition_date;
    $dispTime   = $er?->disposition_time;
    if (!$dispDate && strtolower($disp) === 'admitted' && $doctorAdmittedAt) {
        $dispDate = $doctorAdmittedAt->toDateString();
        $dispTime = $doctorAdmTimeStr;
    }
    $dispDateInput = $dispDate ? \Carbon\Carbon::parse($dispDate)->format('Y-m-d') : '';
    $dispTimeVal   = $dispTime ?? '';
@endphp

<script>
const DOCTOR_ADM_DATE = '{{ $doctorAdmDateStr }}';
const DOCTOR_ADM_TIME = '{{ $doctorAdmTimeStr }}';
</script>

<div id="toast"></div>

{{-- Toolbar: completely absent in readonly mode --}}
@unless($readonly)
<div class="toolbar no-print">
    <span class="lbl">Emergency Room Record (ER-001)</span>
    <span class="tag" style="background:rgba(16,185,129,.25);">{{ $patient->case_no }}</span>
    <span class="tag">{{ $patient->full_name }}</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()">🖨️ Print</button>
    <button id="btnSave" class="btn-save" onclick="saveAndContinue()">💾 Save &amp; Continue →</button>
</div>
@endunless

<div class="paper">
<table class="ft">

    <tr>
        <td style="width:32%;padding:6px 8px;">
            <div class="hosp-name">La Union Medical<br>Center</div>
            <div class="hosp-addr">Agoo, La Union</div>
        </td>
        <td colspan="5">
            <span class="L">Health Record No.:</span>
            <span id="f_hrno" class="f" contenteditable="{{ $ce }}" spellcheck="false">{{ $patient->case_no }}</span>
            &nbsp;&nbsp;&nbsp;
            <span class="L">Type of Service:</span>
            <span id="f_typeservice" class="f" contenteditable="{{ $ce }}" spellcheck="false" style="min-width:120px;">{{ $typeOfSvc }}</span>
        </td>
    </tr>

    <tr>
        <td style="text-align:center;padding:8px 5px;">
            <div class="form-title">Emergency Room<br>Record</div>
        </td>
        <td colspan="2" style="text-align:center;padding:8px 10px;">
            <span class="L">Case</span>&nbsp;&nbsp;
            <label class="cb"><span id="sq_er"   class="sq {{ $caseType==='ER'?'on':'' }}"   @unless($readonly)onclick="toggleSq(this,'sq_er','sq_noer')"@endunless></span> ER</label>
            <label class="cb"><span id="sq_noer" class="sq {{ $caseType!=='ER'?'on':'' }}"  @unless($readonly)onclick="toggleSq(this,'sq_noer','sq_er')"@endunless></span> Non-ER</label>
        </td>
        <td colspan="3">
            <div class="L">Medico-Legal</div>
            <div style="margin-top:4px;">
                <label class="cb"><span id="sq_mly" class="sq {{ $medLegal?'on':'' }}"  @unless($readonly)onclick="toggleSq(this,'sq_mly','sq_mln')"@endunless></span> Yes</label>
                <label class="cb"><span id="sq_mln" class="sq {{ !$medLegal?'on':'' }}" @unless($readonly)onclick="toggleSq(this,'sq_mln','sq_mly')"@endunless></span> No</label>
            </div>
        </td>
    </tr>

    <tr>
        <td><div class="L">Patient's Name: (Last)</div><div id="f_family" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $patFamily }}</div></td>
        <td colspan="2"><div class="L">(Given)</div><div id="f_first" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $patFirst }}</div></td>
        <td colspan="3"><div class="L">(Middle)</div><div id="f_middle" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $patMiddle }}</div></td>
    </tr>

    <tr>
        <td colspan="6"><span class="L">Permanent Address:</span><div id="f_addr" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $addr }}</div></td>
    </tr>

    <tr>
        <td style="width:13%;"><div class="L">Telephone No.</div><input type="tel" id="f_tel" class="fi-tel" value="{{ $tel }}" {{ $ro }}></td>
        <td style="width:13%;"><div class="L">Nationality:</div><div id="f_nat" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $nat }}</div></td>
        <td style="width:8%;"><div class="L">Age:</div><input type="number" id="f_age" class="fi-num" value="{{ $age }}" min="0" max="120" inputmode="numeric" {{ $ro }}></td>
        <td style="width:14%;">
            <div class="L">Birthdate:</div>
            <input type="date" id="f_bdate" class="fi-date" value="{{ $bdateInput }}" @unless($readonly)onchange="calcAgeFromBdate(this.value)"@endunless {{ $ro }}>
        </td>
        <td style="width:16%;">
            <div class="L">Sex:</div>
            <div style="margin-top:4px;line-height:1.9;">
                <label class="cb"><span id="sq_male"   class="sq {{ $sex==='Male'?'on':'' }}"   @unless($readonly)onclick="toggleSq(this,'sq_male','sq_female')"@endunless></span> Male</label><br>
                <label class="cb"><span id="sq_female" class="sq {{ $sex==='Female'?'on':'' }}" @unless($readonly)onclick="toggleSq(this,'sq_female','sq_male')"@endunless></span> Female</label>
            </div>
        </td>
        <td>
            <div class="L">Civil Status</div>
            <div style="margin-top:4px;line-height:1.9;">
                <label class="cb"><span id="sq_single"  class="sq {{ $cs==='Single'?'on':'' }}"    @unless($readonly)onclick="setSq('civil',this)"@endunless></span> Single</label>
                <label class="cb"><span id="sq_married" class="sq {{ $cs==='Married'?'on':'' }}"   @unless($readonly)onclick="setSq('civil',this)"@endunless></span> Married</label><br>
                <label class="cb"><span id="sq_widowed" class="sq {{ $cs==='Widowed'?'on':'' }}"   @unless($readonly)onclick="setSq('civil',this)"@endunless></span> Widow/Widower</label><br>
                <label class="cb"><span id="sq_sep"     class="sq {{ $cs==='Separated'?'on':'' }}" @unless($readonly)onclick="setSq('civil',this)"@endunless></span> Separated</label>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="2"><span class="L">Employer:</span><div id="f_employer" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $employer }}</div></td>
        <td colspan="2"><span class="L">Telephone No.:</span><input type="tel" id="f_emptel" class="fi-tel" value="{{ $empTel }}" {{ $ro }}></td>
        <td colspan="2">
            <div class="L">Notified Proper Authority:</div>
            <div style="margin-top:4px;">
                <label class="cb"><span id="sq_nay"  class="sq {{ $notAuth==='yes'?'on':'' }}" @unless($readonly)onclick="setSq('notauth',this)"@endunless></span> Yes</label>
                <label class="cb"><span id="sq_nan"  class="sq {{ $notAuth==='no'?'on':'' }}"  @unless($readonly)onclick="setSq('notauth',this)"@endunless></span> No</label>
                <label class="cb"><span id="sq_nana" class="sq {{ $notAuth==='na'?'on':'' }}"  @unless($readonly)onclick="setSq('notauth',this)"@endunless></span> Not Applicable</label>
            </div>
        </td>
    </tr>

    <tr>
        <td rowspan="2" style="vertical-align:top;">
            <div class="L">Date and Time of<br>Registration</div>
            <div style="margin-top:6px;">
                <div class="Ls">Date:</div>
                <input type="date" id="f_regdate" class="fi-date" value="{{ $regDateInput }}" {{ $ro }}>
                <div class="Ls" style="margin-top:5px;">Time:</div>
                <input type="time" id="f_regtime" class="fi-date" value="{{ $regTime }}" {{ $ro }}>
            </div>
        </td>
        <td colspan="5"><span class="L">Brought By:</span></td>
    </tr>
    <tr>
        <td colspan="5" style="padding:4px 6px;">
            @php $bb = strtolower($broughtBy); @endphp
            <label class="cb"><span id="sq_bb_self"     class="sq {{ str_contains($bb,'self')?'on':'' }}"      @unless($readonly)onclick="setSq('bb',this)"@endunless></span> Self</label>
            <label class="cb"><span id="sq_bb_family"   class="sq {{ str_contains($bb,'family')?'on':'' }}"    @unless($readonly)onclick="setSq('bb',this)"@endunless></span> Family Member</label>
            <label class="cb"><span id="sq_bb_rel"      class="sq {{ str_contains($bb,'relative')?'on':'' }}"  @unless($readonly)onclick="setSq('bb',this)"@endunless></span> Relatives</label>
            <label class="cb"><span id="sq_bb_friend"   class="sq {{ str_contains($bb,'friend')?'on':'' }}"    @unless($readonly)onclick="setSq('bb',this)"@endunless></span> Friend</label>
            <label class="cb"><span id="sq_bb_unknown"  class="sq {{ str_contains($bb,'unknown')?'on':'' }}"   @unless($readonly)onclick="setSq('bb',this)"@endunless></span> Unknown</label><br>
            <div style="margin-top:4px;">
                <label class="cb"><span id="sq_bb_police"   class="sq {{ str_contains($bb,'police')?'on':'' }}"    @unless($readonly)onclick="setSq('bb',this)"@endunless></span> Police</label>
                <label class="cb"><span id="sq_bb_neighbor" class="sq {{ str_contains($bb,'neighbor')?'on':'' }}"  @unless($readonly)onclick="setSq('bb',this)"@endunless></span> Neighbor</label>
                <label class="cb"><span id="sq_bb_amb"      class="sq {{ str_contains($bb,'ambulance')?'on':'' }}" @unless($readonly)onclick="setSq('bb',this)"@endunless></span> Ambulance</label>
                <label class="cb"><span id="sq_bb_other"    class="sq {{ str_contains($bb,'other')?'on':'' }}"     @unless($readonly)onclick="setSq('bb',this)"@endunless></span> Others</label>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="padding:3px 5px;">
            <div class="L">Conditions on Arrival:</div>
            @php $coaLow = strtolower($coa); @endphp
            <div style="margin-top:3px;">
                <label class="cb"><span id="sq_coa_good"  class="sq {{ str_contains($coaLow,'good')?'on':'' }}"  @unless($readonly)onclick="setSq('coa',this)"@endunless></span> Good</label>
                <label class="cb"><span id="sq_coa_fair"  class="sq {{ str_contains($coaLow,'fair')?'on':'' }}"  @unless($readonly)onclick="setSq('coa',this)"@endunless></span> Fair</label>
                <label class="cb"><span id="sq_coa_poor"  class="sq {{ str_contains($coaLow,'poor')?'on':'' }}"  @unless($readonly)onclick="setSq('coa',this)"@endunless></span> Poor</label>
                <label class="cb"><span id="sq_coa_shock" class="sq {{ str_contains($coaLow,'shock')?'on':'' }}" @unless($readonly)onclick="setSq('coa',this)"@endunless></span> Shock</label>
                <label class="cb"><span id="sq_coa_coma"  class="sq {{ str_contains($coaLow,'coma')?'on':'' }}"  @unless($readonly)onclick="setSq('coa',this)"@endunless></span> Comatose</label>
                <label class="cb"><span id="sq_coa_hemor" class="sq {{ str_contains($coaLow,'hemor')?'on':'' }}" @unless($readonly)onclick="setSq('coa',this)"@endunless></span> Hemorrhagic</label>
                <label class="cb"><span id="sq_coa_doa"   class="sq {{ str_contains($coaLow,'doa')?'on':'' }}"   @unless($readonly)onclick="setSq('coa',this)"@endunless></span> DOA</label>
            </div>
        </td>
        <td style="padding:3px 5px;">
            <div class="L">Temperature:</div>
            <input type="number" id="f_temp" class="fi-num" value="{{ $temp }}" step="0.1" min="30" max="45" inputmode="decimal" {{ $ro }}>
            <div style="margin-top:3px;">
                <label class="cb"><span id="sq_ts_ax" class="sq {{ ($tempSite==='Axilla')?'on':'' }}" @unless($readonly)onclick="setSq('tempsite',this)"@endunless></span> Axilla</label>
                <label class="cb"><span id="sq_ts_or" class="sq {{ ($tempSite==='Oral')?'on':'' }}"   @unless($readonly)onclick="setSq('tempsite',this)"@endunless></span> Oral</label>
                <label class="cb"><span id="sq_ts_re" class="sq {{ ($tempSite==='Rectal')?'on':'' }}" @unless($readonly)onclick="setSq('tempsite',this)"@endunless></span> Anal</label>
            </div>
        </td>
        <td style="padding:3px 5px;">
            <div class="L">Pulse:</div>
            <input type="number" id="f_pulse" class="fi-num" value="{{ $pulse }}" min="0" max="300" inputmode="numeric" {{ $ro }}>
        </td>
    </tr>

    <tr>
        <td><span class="L">BP:</span><span id="f_bp" class="f" contenteditable="{{ $ce }}" spellcheck="false" style="min-width:50px;">{{ $bp }}</span></td>
        <td><span class="L">Cardiac Rate:</span><input type="number" id="f_cardiac" class="fi-inline" value="{{ $pulse }}" min="0" max="300" inputmode="numeric" {{ $ro }}></td>
        <td colspan="2"><span class="L">Respiratory Rate:</span><input type="number" id="f_rr" class="fi-inline" value="{{ $rr }}" min="0" max="100" inputmode="numeric" {{ $ro }}></td>
        <td><span class="L">Height</span><input type="number" id="f_ht" class="fi-inline" value="{{ $ht }}" min="0" max="250" step="0.1" inputmode="decimal" {{ $ro }}></td>
        <td><span class="L">Weight:</span><input type="number" id="f_wt" class="fi-inline" value="{{ $wt }}" min="0" max="300" step="0.1" inputmode="decimal" {{ $ro }}></td>
    </tr>

    <tr>
        <td colspan="4" style="vertical-align:top;">
            <div class="L">Chief Complaint:</div>
            <div id="f_cc" class="fb fb3" contenteditable="{{ $ce }}" spellcheck="false">{{ $cc }}</div>
        </td>
        <td colspan="2" style="vertical-align:top;padding:0;">
            <table style="width:100%;border-collapse:collapse;height:100%;">
                <tr><td style="border:none;border-bottom:1.5px solid #000;padding:3px 5px;vertical-align:top;"><div class="L">Allergies:</div><div id="f_allergies" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $allergies }}</div></td></tr>
                <tr><td style="border:none;padding:3px 5px;vertical-align:top;"><div class="L">Current Medication:</div><div id="f_curmed" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $curMed }}</div></td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="6">
            <div class="L">Physical Finding and Diagnosis:</div>
            <div id="f_pfdx" class="fb fb4" contenteditable="{{ $ce }}" spellcheck="false">{{ $pfDx }}</div>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="vertical-align:top;">
            <div class="L">Treatment:</div>
            <div id="f_treatment" class="fb fb4" contenteditable="{{ $ce }}" spellcheck="false">{{ $treatment }}</div>
        </td>
        <td colspan="2" style="vertical-align:top;">
            <div class="L">Nurses Notes:</div>
            <div class="fb fb4" style="border-bottom:1px solid #555;"></div>
        </td>
    </tr>

    <tr>
        <td style="vertical-align:top;">
            <div class="L">Date of Disposition:</div>
            <input type="date" id="f_dispdate" class="fi-date" value="{{ $dispDateInput }}" {{ $ro }}>
            <div class="L" style="margin-top:8px;">Time:</div>
            <input type="time" id="f_disptime" class="fi-date" value="{{ $dispTimeVal }}" {{ $ro }}>
        </td>
        <td colspan="3" style="vertical-align:top;">
            <div class="L">Disposition:</div>
            @php $dispLow = strtolower($disp); @endphp
            <div style="margin-top:5px;line-height:2.1;font-size:8.5pt;">
                <label class="cb"><span id="sq_d_home" class="sq {{ $dispLow==='discharged'?'on':'' }}" @unless($readonly)onclick="setSq('disp',this)"@endunless></span> Treated and Sent Home</label>
                <label class="cb"><span id="sq_d_adm"  class="sq {{ $dispLow==='admitted'?'on':'' }}"  @unless($readonly)onclick="dispSelected(this,'adm')"@endunless></span> For Admission</label><br>
                <label class="cb"><span id="sq_d_ref"  class="sq {{ $dispLow==='referred'?'on':'' }}"  @unless($readonly)onclick="setSq('disp',this)"@endunless></span> Transferred/Referred</label>
                <label class="cb"><span id="sq_d_abs"  class="sq" @unless($readonly)onclick="setSq('disp',this)"@endunless></span> Absconded</label><br>
                <label class="cb"><span id="sq_d_ref2" class="sq" @unless($readonly)onclick="setSq('disp',this)"@endunless></span> Refused Admission</label>
                <label class="cb"><span id="sq_d_hama" class="sq {{ $dispLow==='hama'?'on':'' }}"      @unless($readonly)onclick="setSq('disp',this)"@endunless></span> HAMA / DAMA</label><br>
                <label class="cb"><span id="sq_d_owc"  class="sq" @unless($readonly)onclick="setSq('disp',this)"@endunless></span> Out When Called</label>
                <label class="cb"><span id="sq_d_died" class="sq {{ $dispLow==='expired'?'on':'' }}"   @unless($readonly)onclick="setSq('disp',this)"@endunless></span> Died</label>
            </div>
        </td>
        <td colspan="2" style="vertical-align:top;">
            <div class="L">Condition on Discharge</div>
            <div id="f_conddisch" class="fb fb3" contenteditable="{{ $ce }}" spellcheck="false">{{ $condDisch }}</div>
        </td>
    </tr>

    <tr><td colspan="6" style="height:18px;border-top:1.5px solid #000;"></td></tr>
</table>

<div style="display:flex;justify-content:space-between;margin-top:18px;padding:0 8px;">
    <div style="text-align:center;width:42%;">
        <div style="border-top:1.5px solid #000;padding-top:4px;margin-top:32px;">
            <div class="L">Nurse's Name (Printed) and <u>Signature</u></div>
        </div>
    </div>
    <div style="text-align:center;width:42%;">
        <div style="padding-top:4px;margin-top:8px;min-height:28px;font-size:9.5pt;font-weight:bold;letter-spacing:.04em;">
            {{ $doctorNameFormatted }}
        </div>
        <div style="border-top:1.5px solid #000;padding-top:4px;">
            <div class="L">Doctor's Name (Printed) and <u>Signature</u></div>
        </div>
    </div>
</div>

</div>{{-- /.paper --}}

{{-- JS only loaded in editable mode --}}
@unless($readonly)
<script>
function toggleSq(el, idA, idB) {
    const a = document.getElementById(idA), b = document.getElementById(idB);
    if (el.id === idA) { a.classList.toggle('on'); b.classList.remove('on'); }
    else { b.classList.toggle('on'); a.classList.remove('on'); }
}
const groups = {
    civil:    ['sq_single','sq_married','sq_widowed','sq_sep'],
    notauth:  ['sq_nay','sq_nan','sq_nana'],
    bb:       ['sq_bb_self','sq_bb_family','sq_bb_rel','sq_bb_friend','sq_bb_unknown','sq_bb_police','sq_bb_neighbor','sq_bb_amb','sq_bb_other'],
    coa:      ['sq_coa_good','sq_coa_fair','sq_coa_poor','sq_coa_shock','sq_coa_coma','sq_coa_hemor','sq_coa_doa'],
    tempsite: ['sq_ts_ax','sq_ts_or','sq_ts_re'],
    disp:     ['sq_d_home','sq_d_adm','sq_d_ref','sq_d_abs','sq_d_ref2','sq_d_hama','sq_d_owc','sq_d_died'],
};
function setSq(group, el) {
    groups[group].forEach(id => { const sq = document.getElementById(id); if (sq) sq.classList.remove('on'); });
    el.classList.toggle('on');
}
function dispSelected(el, key) {
    setSq('disp', el);
    if (key === 'adm' && el.classList.contains('on') && DOCTOR_ADM_DATE) {
        document.getElementById('f_dispdate').value = DOCTOR_ADM_DATE;
        document.getElementById('f_disptime').value = DOCTOR_ADM_TIME;
    }
}
function calcAgeFromBdate(val) {
    if (!val) return;
    const d = new Date(val), today = new Date();
    let age = today.getFullYear() - d.getFullYear();
    const m = today.getMonth() - d.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--;
    if (age >= 0 && age <= 120) document.getElementById('f_age').value = age;
}
function collectData() {
    const g   = id => document.getElementById(id);
    const ctxt = id => { const el = g(id); if (!el) return ''; return (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') ? el.value : el.innerText.trim(); };
    const isOn = id => g(id) ? g(id).classList.contains('on') : false;
    const bbMap   = {sq_bb_self:'Self',sq_bb_family:'Family',sq_bb_rel:'Relatives',sq_bb_friend:'Friend',sq_bb_unknown:'Unknown',sq_bb_police:'Police',sq_bb_neighbor:'Neighbor',sq_bb_amb:'Ambulance',sq_bb_other:'Other'};
    const coaMap  = {sq_coa_good:'Good',sq_coa_fair:'Fair',sq_coa_poor:'Poor',sq_coa_shock:'Shock',sq_coa_coma:'Comatose',sq_coa_hemor:'Hemorrhagic',sq_coa_doa:'DOA'};
    const csMap   = {sq_single:'Single',sq_married:'Married',sq_widowed:'Widowed',sq_sep:'Separated'};
    const dispMap = {sq_d_home:'Discharged',sq_d_adm:'Admitted',sq_d_ref:'Referred',sq_d_abs:'Absconded',sq_d_ref2:'Refused',sq_d_hama:'HAMA',sq_d_owc:'Out When Called',sq_d_died:'Expired'};
    const bbId   = groups.bb.find(isOn) ?? '';
    const coaId  = groups.coa.find(isOn) ?? '';
    const csId   = groups.civil.find(isOn) ?? '';
    const dispId = groups.disp.find(isOn) ?? '';
    const ts     = isOn('sq_ts_ax') ? 'Axilla' : (isOn('sq_ts_or') ? 'Oral' : (isOn('sq_ts_re') ? 'Rectal' : ''));
    const sex    = isOn('sq_male') ? 'Male' : (isOn('sq_female') ? 'Female' : '');
    const notAuth= isOn('sq_nay') ? 'yes' : (isOn('sq_nan') ? 'no' : (isOn('sq_nana') ? 'na' : ''));
    const caseType = isOn('sq_er') ? 'ER' : 'Non-ER';
    const medLegal = isOn('sq_mly');
    return {
        health_record_no: ctxt('f_hrno'), type_of_service: ctxt('f_typeservice'),
        medico_legal: medLegal, case_type: caseType, notified_proper_authority: notAuth,
        patient_family_name: ctxt('f_family'), patient_first_name: ctxt('f_first'), patient_middle_name: ctxt('f_middle'),
        permanent_address: ctxt('f_addr'), telephone_no: ctxt('f_tel'), nationality: ctxt('f_nat'),
        age: ctxt('f_age'), birthdate: ctxt('f_bdate'), sex, civil_status: csMap[csId] ?? '',
        employer_name: ctxt('f_employer'), employer_phone: ctxt('f_emptel'),
        registration_date: ctxt('f_regdate'), registration_time: ctxt('f_regtime'),
        brought_by: bbMap[bbId] ?? '', condition_on_arrival: coaMap[coaId] ?? '',
        temperature: ctxt('f_temp'), temperature_site: ts, pulse_rate: ctxt('f_pulse'),
        blood_pressure: ctxt('f_bp'), cardiac_rate: ctxt('f_cardiac'), respiratory_rate: ctxt('f_rr'),
        height_cm: ctxt('f_ht'), weight_kg: ctxt('f_wt'), chief_complaint: ctxt('f_cc'),
        allergies: ctxt('f_allergies'), current_medication: ctxt('f_curmed'),
        physical_findings_and_diagnosis: ctxt('f_pfdx'), treatment: ctxt('f_treatment'),
        disposition_date: ctxt('f_dispdate'), disposition_time: ctxt('f_disptime'),
        disposition: dispMap[dispId] ?? '', condition_on_discharge: ctxt('f_conddisch'),
    };
}
async function saveAndContinue() {
    const btn = document.getElementById('btnSave');
    btn.disabled = true; btn.textContent = 'Saving…';
    try {
        const res  = await fetch('{{ route("forms.er-record.save", ["visit" => $visit->id]) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: JSON.stringify(collectData()),
        });
        const json = await res.json();
        if (json.success) {
            showToast('✔ ER Record saved — advancing…');
            btn.textContent = '✔ Saved';
            window.parent.postMessage({ type: 'erSaved' }, '*');
        } else {
            showToast('⚠ Save failed: ' + (json.message ?? 'Unknown error'), true);
            btn.disabled = false; btn.textContent = '💾 Save & Continue →';
        }
    } catch (e) {
        showToast('⚠ Network error — check connection.', true);
        btn.disabled = false; btn.textContent = '💾 Save & Continue →';
    }
}
function showToast(msg, isError) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.className = isError ? 'error' : '';
    t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 4000);
}
document.addEventListener('DOMContentLoaded', function() {
    const bd = document.getElementById('f_bdate');
    if (bd && bd.value) calcAgeFromBdate(bd.value);
});
</script>
@endunless

</body>
</html>