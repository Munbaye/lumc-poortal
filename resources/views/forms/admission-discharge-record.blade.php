<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admission and Discharge Record — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size:8.5in 13in portrait; margin:0.5in 0.6in; }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:Arial,Helvetica,sans-serif;font-size:9pt;color:#000;background:#c9c9c9;}
        @media screen{body{padding:52px 0 40px;}.paper{width:8.5in;min-height:13in;margin:0 auto;background:#fff;box-shadow:0 4px 28px rgba(0,0,0,.28);padding:0.5in 0.6in;}}
        body.readonly-mode{padding-top:12px !important;}
        @media print{body{background:#fff;padding:0;}.paper{width:100%;padding:0;box-shadow:none;}.no-print{display:none !important;}[contenteditable]{outline:none !important;background:transparent !important;}input{border-color:#000 !important;background:transparent !important;outline:none !important;}}

        .toolbar{position:fixed;top:0;left:0;right:0;height:46px;background:#1e3a5f;color:#fff;font-family:'Segoe UI',system-ui,sans-serif;font-size:12px;display:flex;align-items:center;padding:0 22px;gap:14px;z-index:9999;box-shadow:0 2px 10px rgba(0,0,0,.35);}
        .toolbar .lbl{font-size:13px;font-weight:700;}.toolbar .tag{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:3px;padding:2px 9px;font-size:10px;text-transform:uppercase;}.toolbar .spacer{flex:1;}
        .btn-print{background:#fff;color:#1e3a5f;border:none;padding:6px 18px;border-radius:4px;font-size:12px;font-weight:700;cursor:pointer;}.btn-print:hover{background:#dbeafe;}
        .btn-save{background:#059669;color:#fff;border:none;padding:6px 22px;border-radius:4px;font-size:12px;font-weight:700;cursor:pointer;}.btn-save:hover{background:#047857;}.btn-save:disabled{opacity:.6;cursor:not-allowed;}

        /* Readonly: lock all interactivity */
        body.readonly-mode [contenteditable]{ pointer-events:none; cursor:default; }
        body.readonly-mode input,body.readonly-mode select,body.readonly-mode textarea{ pointer-events:none; cursor:default; }
        body.readonly-mode .sq{ pointer-events:none; cursor:default; }

        .page-title{text-align:center;font-size:14pt;font-weight:bold;text-decoration:underline;text-transform:uppercase;margin-bottom:14px;letter-spacing:.04em;}
        .ft{width:100%;border-collapse:collapse;font-size:9pt;}
        .ft td{border:1.2px solid #000;padding:3px 5px;vertical-align:top;}
        .L{font-weight:bold;font-size:8.5pt;text-transform:uppercase;}
        .Ls{font-weight:bold;font-size:7.5pt;text-transform:uppercase;}
        .f{display:inline-block;min-height:14px;min-width:60px;border-bottom:1px solid #555;vertical-align:bottom;outline:none;cursor:text;font-size:9pt;font-family:Arial,sans-serif;padding:0 2px;}
        .fb{display:block;width:100%;min-height:16px;border-bottom:1px solid #555;outline:none;cursor:text;font-size:9pt;font-family:Arial,sans-serif;padding:1px 2px;margin-top:2px;}
        .fb2{min-height:28px;}.fb3{min-height:44px;}
        @media screen{.f:focus,.fb:focus{background:#fef9ec;}}
        .fi-date{border:none;border-bottom:1px solid #555;outline:none;font-size:9pt;font-family:Arial,sans-serif;padding:1px 2px;background:transparent;width:100%;margin-top:2px;}
        .fi-date:focus{background:#fef9ec;}
        .fi-num{border:none;border-bottom:1px solid #555;outline:none;font-size:9pt;font-family:Arial,sans-serif;padding:1px 2px;background:transparent;width:100%;margin-top:2px;}
        .fi-num:focus{background:#fef9ec;}
        .fi-tel{border:none;border-bottom:1px solid #555;outline:none;font-size:9pt;font-family:Arial,sans-serif;padding:1px 2px;background:transparent;width:100%;margin-top:2px;}
        .fi-tel:focus{background:#fef9ec;}
        @media print{.fi-date,.fi-num,.fi-tel{border-bottom:1px solid #000 !important;}}
        .cb{display:inline-flex;align-items:center;gap:3px;margin-right:5px;white-space:nowrap;font-size:8.5pt;}
        .sq{width:10px;height:10px;border:1.2px solid #000;display:inline-block;flex-shrink:0;position:relative;vertical-align:middle;cursor:pointer;}
        .sq.on::after{content:'✓';position:absolute;top:-3px;left:0;font-size:10pt;font-weight:bold;line-height:1;}
        .icd-boxes{display:inline-flex;gap:3px;margin-left:6px;}
        .icd-box{width:22px;height:22px;border:1.2px solid #000;display:inline-block;}
        #toast{position:fixed;bottom:22px;right:22px;background:#059669;color:#fff;padding:12px 22px;border-radius:8px;font-family:'Segoe UI',sans-serif;font-size:13px;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,.25);display:none;z-index:99999;}
        #toast.error{background:#dc2626;}
    </style>
</head>
<body class="{{ request()->boolean('readonly') ? 'readonly-mode' : '' }}">
@php
    $readonly = request()->boolean('readonly');
    $ce       = $readonly ? 'false' : 'true';
    $ro       = $readonly ? 'readonly' : '';

    $adm     = $admRecord ?? null;
    $er      = $erRecord  ?? null;
    $patient = $visit->patient;
    $history = $visit->medicalHistory;
    $svc     = $visit->admitted_service ?? $history?->service ?? null;

    $first = fn(...$vals) => collect($vals)->first(fn($v) => filled($v)) ?? '';

    $patFamilyVal = $first($adm?->patient_family_name, $er?->patient_family_name, strtoupper($patient->family_name));
    $patFirstVal  = $first($adm?->patient_first_name,  $er?->patient_first_name,  strtoupper($patient->first_name));
    $patMiddleVal = $first($adm?->patient_middle_name, $er?->patient_middle_name, strtoupper($patient->middle_name ?? ''));
    $patNameFormatted = trim($patFamilyVal . ', ' . $patFirstVal . ($patMiddleVal ? ' ' . $patMiddleVal : ''));

    $addr       = $first($adm?->permanent_address,   $er?->permanent_address,   $patient->address);
    $tel        = $first($adm?->telephone_no,         $er?->telephone_no,         $patient->contact_number ?? '');
    $sex        = $first($adm?->sex,                  $er?->sex,                  $patient->sex ?? '');
    $cs         = $first($adm?->civil_status,         $er?->civil_status,         $patient->civil_status ?? '');
    $bdateRaw   = $first($adm?->birthdate, $er?->birthdate, $patient->birthday);
    $bdateInput = $bdateRaw ? \Carbon\Carbon::parse($bdateRaw)->format('Y-m-d') : '';
    $age        = $first($adm?->age,                  $er?->age,                  $patient->current_age ?? $patient->age ?? '');
    $birthplace = $first($adm?->birthplace,                                       $patient->birthplace ?? '');
    $nat        = $first($adm?->nationality,          $er?->nationality,          $patient->nationality ?? 'Filipino');
    $religion   = $first($adm?->religion,                                         $patient->religion ?? '');
    $occ        = $first($adm?->occupation,                                       $patient->occupation ?? '');
    $empName    = $first($adm?->employer_name,        $er?->employer_name,        $patient->employer_name ?? '');
    $empAddr    = $first($adm?->employer_address,                                 $patient->employer_address ?? '');
    $empTel     = $first($adm?->employer_phone,       $er?->employer_phone,       $patient->employer_phone ?? '');
    $dadName    = $first($adm?->father_name,                                      $patient->father_full_name ?? $patient->father_name ?? '');
    $dadAddr    = $first($adm?->father_address,                                   $patient->father_address ?? '');
    $dadTel     = $first($adm?->father_phone,                                     $patient->father_phone ?? '');
    $momName    = $first($adm?->mother_maiden_name,                               $patient->mother_maiden_name ?? $patient->mother_name ?? '');
    $momAddr    = $first($adm?->mother_address,                                   $patient->mother_address ?? '');
    $momTel     = $first($adm?->mother_phone,                                     $patient->mother_phone ?? '');

    $doctorAdmittedAt = $visit->doctor_admitted_at;
    $admDateDefault   = $doctorAdmittedAt?->toDateString() ?? now()->toDateString();
    $admTimeDefault   = $doctorAdmittedAt?->timezone('Asia/Manila')->format('H:i') ?? '';
    $admDateInput  = $first($adm?->admission_date ? \Carbon\Carbon::parse($adm->admission_date)->format('Y-m-d') : null, $admDateDefault);
    $admTimeVal    = $first($adm?->admission_time, $admTimeDefault);

    $dischDateInput = $adm?->discharge_date ? \Carbon\Carbon::parse($adm->discharge_date)->format('Y-m-d') : ($visit->discharged_at ? $visit->discharged_at->toDateString() : '');
    $dischTimeVal   = $first($adm?->discharge_time, $visit->discharged_at ? $visit->discharged_at->timezone('Asia/Manila')->format('H:i') : '');
    $totalDays = $adm?->total_days ?? '';
    $ward      = $first($adm?->ward_service, $svc);
    $typeAdm   = $first($adm?->type_of_admission, 'New');
    $ssc       = $first($adm?->social_service_class, $patient->social_service_class ?? '');
    $alert     = $adm?->alert ?? '';
    $allergicTo= $first($adm?->allergic_to, $er?->allergies, $history?->drug_allergies ?? '');
    $healthIns = $adm?->health_insurance_name ?? '';
    $philId    = $first($adm?->philhealth_id,  $patient->philhealth_id ?? '');
    $philType  = $first($adm?->philhealth_type, $patient->philhealth_type ?? '');
    $dataFurnBy  = $adm?->data_furnished_by ?? '';
    $dataFurnAdd = $adm?->data_furnished_address ?? '';
    $dataFurnRel = $adm?->data_furnished_relation ?? '';
    $admDx     = $first($adm?->admission_diagnosis, $visit->admitting_diagnosis ?? $history?->admitting_impression ?? $history?->diagnosis ?? '');
    $finalDx   = $first($adm?->final_diagnosis, $history?->diagnosis ?? '');
    $otherDx   = $first($adm?->other_diagnosis, $history?->differential_diagnosis ?? '');
    $principalOp = $adm?->principal_operation ?? '';
    $disp      = $first($adm?->disposition, $visit->disposition ?? '');
    $results   = $adm?->results ?? '';
    $physician = $history?->doctor ? 'Dr. ' . $history->doctor->name : '';
@endphp

<div id="toast"></div>

{{-- Toolbar: completely absent in readonly mode --}}
@unless($readonly)
<div class="toolbar no-print">
    <span class="lbl">Admission and Discharge Record (ADM-001)</span>
    <span class="tag" style="background:rgba(16,185,129,.25);">{{ $patient->case_no }}</span>
    <span class="tag">{{ $patient->full_name }}</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()">🖨️ Print</button>
    <button id="btnSave" class="btn-save" onclick="saveAndContinue()">💾 Save &amp; Continue →</button>
</div>
@endunless

<div class="paper">
<div class="page-title">Admission and Discharge Record</div>

<table class="ft">

    <tr>
        <td colspan="4">
            <span class="L">Patient's Name:</span>
            <span id="f_patname" class="f" contenteditable="{{ $ce }}" spellcheck="false"
                  style="min-width:220px;font-weight:bold;">{{ $patNameFormatted }}</span>
        </td>
        <td colspan="2" style="vertical-align:top;">
            <div class="L">Hosp. Case No.</div>
            <div id="f_caseno" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $patient->case_no }}</div>
            <div class="L" style="margin-top:4px;">Ward/Services</div>
            <div id="f_ward" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $ward }}</div>
        </td>
    </tr>

    <tr>
        <td colspan="2"><div class="L">Permanent Address:</div><div id="f_addr" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $addr }}</div></td>
        <td><div class="L">Tel. No.</div><input type="tel" id="f_tel" class="fi-tel" value="{{ $tel }}" style="min-height:28px;" {{ $ro }}></td>
        <td style="vertical-align:top;">
            <div class="L">Sex</div>
            <div style="margin-top:3px;line-height:1.9;">
                <label class="cb"><span id="sq_male"   class="sq {{ $sex==='Male'?'on':'' }}"   @unless($readonly)onclick="toggleSq(this,'sq_male','sq_female')"@endunless></span> M</label><br>
                <label class="cb"><span id="sq_female" class="sq {{ $sex==='Female'?'on':'' }}" @unless($readonly)onclick="toggleSq(this,'sq_female','sq_male')"@endunless></span> F</label>
            </div>
        </td>
        <td colspan="2" style="vertical-align:top;">
            <div class="L">Civil Status</div>
            <div style="margin-top:3px;line-height:1.9;">
                <label class="cb"><span id="sq_cs_s"   class="sq {{ $cs==='Single'?'on':'' }}"    @unless($readonly)onclick="setSq('cs',this)"@endunless></span> S</label>
                <label class="cb"><span id="sq_cs_m"   class="sq {{ $cs==='Married'?'on':'' }}"   @unless($readonly)onclick="setSq('cs',this)"@endunless></span> M</label>
                <label class="cb"><span id="sq_cs_sep" class="sq {{ $cs==='Separated'?'on':'' }}" @unless($readonly)onclick="setSq('cs',this)"@endunless></span> Sep</label><br>
                <label class="cb"><span id="sq_cs_w"   class="sq {{ $cs==='Widowed'?'on':'' }}"   @unless($readonly)onclick="setSq('cs',this)"@endunless></span> W</label>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="L">Birthdate</div>
            <input type="date" id="f_bdate" class="fi-date" value="{{ $bdateInput }}" @unless($readonly)onchange="calcAge(this.value)"@endunless {{ $ro }}>
        </td>
        <td><div class="L">Age</div><input type="number" id="f_age" class="fi-num" value="{{ $age }}" min="0" max="120" inputmode="numeric" {{ $ro }}></td>
        <td><div class="L">Birthplace</div><div id="f_birthplace" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $birthplace }}</div></td>
        <td><div class="L">Nationality</div><div id="f_nat" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $nat }}</div></td>
        <td><div class="L">Religion</div><div id="f_religion" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $religion }}</div></td>
        <td><div class="L">Occupation</div><div id="f_occ" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $occ }}</div></td>
    </tr>

    <tr>
        <td colspan="2"><div class="L">Employer</div><div id="f_empname" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $empName }}</div></td>
        <td colspan="2"><div class="L">Address</div><div id="f_empaddr" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $empAddr }}</div></td>
        <td colspan="2"><div class="L">Tel.No.</div><input type="tel" id="f_emptel" class="fi-tel" value="{{ $empTel }}" {{ $ro }}></td>
    </tr>

    <tr>
        <td colspan="2"><div class="L">Father's Name</div><div id="f_dadname" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $dadName }}</div></td>
        <td colspan="2"><div class="L">Address</div><div id="f_dadaddr" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $dadAddr }}</div></td>
        <td colspan="2"><div class="L">Tel.No.</div><input type="tel" id="f_dadtel" class="fi-tel" value="{{ $dadTel }}" {{ $ro }}></td>
    </tr>

    <tr>
        <td colspan="2"><div class="L">Mother's (Maiden) Name</div><div id="f_momname" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $momName }}</div></td>
        <td colspan="2"><div class="L">Address</div><div id="f_momaddr" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $momAddr }}</div></td>
        <td colspan="2"><div class="L">Tel.No.</div><input type="tel" id="f_momtel" class="fi-tel" value="{{ $momTel }}" {{ $ro }}></td>
    </tr>

    <tr>
        <td colspan="2" style="vertical-align:top;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 8px;">
                <div>
                    <div class="L">Admission:</div>
                    <div class="Ls" style="margin-top:5px;">Date:</div>
                    <input type="date" id="f_admdate" class="fi-date" value="{{ $admDateInput }}" {{ $ro }}>
                    <div class="Ls" style="margin-top:5px;">Time:</div>
                    <input type="time" id="f_admtime" class="fi-date" value="{{ $admTimeVal }}" {{ $ro }}>
                </div>
                <div>
                    <div class="L">Discharge:</div>
                    <div class="Ls" style="margin-top:5px;">Date:</div>
                    <input type="date" id="f_dischdate" class="fi-date" value="{{ $dischDateInput }}" {{ $ro }}>
                    <div class="Ls" style="margin-top:5px;">Time:</div>
                    <input type="time" id="f_dischtime" class="fi-date" value="{{ $dischTimeVal }}" {{ $ro }}>
                </div>
            </div>
        </td>
        <td colspan="2" style="vertical-align:top;">
            <div class="L">Total No. of Days</div>
            <input type="number" id="f_totaldays" class="fi-num" value="{{ $totalDays }}" min="0" inputmode="numeric" style="min-height:28px;" {{ $ro }}>
        </td>
        <td colspan="2" style="vertical-align:top;">
            <div class="L">Attending Physician</div>
            <div id="f_physician" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $physician }}</div>
        </td>
    </tr>

    <tr>
        <td colspan="6">
            <span class="L">Type of Admission:</span>&nbsp;&nbsp;
            <label class="cb"><span id="sq_adm_new" class="sq {{ $typeAdm==='New'?'on':'' }}" @unless($readonly)onclick="toggleSq(this,'sq_adm_new','sq_adm_old')"@endunless></span> New</label>
            <label class="cb"><span id="sq_adm_old" class="sq {{ $typeAdm==='Old'?'on':'' }}" @unless($readonly)onclick="toggleSq(this,'sq_adm_old','sq_adm_new')"@endunless></span> Old</label>
        </td>
    </tr>

    <tr>
        <td colspan="6">
            <span class="L">Social Service Classification:</span>&nbsp;&nbsp;
            @foreach(['A','B','C1','C2','C3','D'] as $cls)
            <label class="cb"><span id="sq_ssc_{{ strtolower($cls) }}" class="sq {{ $ssc===$cls?'on':'' }}" @unless($readonly)onclick="setSq('ssc',this)"@endunless></span> {{ $cls }}</label>
            @endforeach
        </td>
    </tr>

    <tr>
        <td colspan="2" style="vertical-align:top;">
            <div class="L">Alert:</div>
            <div id="f_alert" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $alert }}</div>
            <div class="L" style="margin-top:5px;">Allergic To</div>
            <div id="f_allergic" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $allergicTo }}</div>
        </td>
        <td colspan="2" style="vertical-align:top;">
            <div class="L">Health Insurance Name:</div>
            <div id="f_healthins" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $healthIns }}</div>
            <div class="L" style="margin-top:5px;">PhilHealth ID No.</div>
            <div id="f_philid" class="fb" contenteditable="{{ $ce }}" spellcheck="false">{{ $philId }}</div>
        </td>
        <td colspan="2" style="vertical-align:top;">
            <div class="L" style="text-decoration:underline;">PhilHealth Type</div>
            <div style="margin-top:4px;line-height:2.0;">
                @foreach(['Government'=>'Govt.','Indigent'=>'Indigent','Private'=>'Private','Self-Employed'=>'Self Employed'] as $val => $label)
                <label class="cb"><span id="sq_phil_{{ strtolower(str_replace([' ','-'],'',$val)) }}" class="sq {{ $philType===$val?'on':'' }}" @unless($readonly)onclick="setSq('philtype',this)"@endunless></span> {{ $label }}</label><br>
                @endforeach
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="2"><div class="L">Data Furnished By:</div><div id="f_datafurnby" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $dataFurnBy }}</div></td>
        <td colspan="2"><div class="L">Address</div><div id="f_datafurnaddr" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $dataFurnAdd }}</div></td>
        <td colspan="2"><div class="L">Relation to Patient</div><div id="f_datafurnrel" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $dataFurnRel }}</div></td>
    </tr>

    <tr>
        <td colspan="4" style="vertical-align:top;">
            <div class="L">Admission Diagnosis:</div>
            <div id="f_admdx" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $admDx }}</div>
        </td>
        <td colspan="2" style="vertical-align:top;text-align:right;">
            <div class="L">ICD Code No.</div>
            <div class="icd-boxes" style="margin-top:4px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="vertical-align:top;">
            <div class="L">Final Diagnosis:</div>
            <div id="f_finaldx" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $finalDx }}</div>
        </td>
        <td colspan="2" style="vertical-align:top;text-align:right;">
            <div class="icd-boxes" style="margin-top:20px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="vertical-align:top;">
            <div class="L">Other Diagnosis:</div>
            <div id="f_otherdx" class="fb fb3" contenteditable="{{ $ce }}" spellcheck="false">{{ $otherDx }}</div>
        </td>
        <td colspan="2" style="vertical-align:top;text-align:right;">
            <div class="icd-boxes" style="margin-top:8px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
            <div class="icd-boxes" style="margin-top:4px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="vertical-align:top;">
            <div class="L">Principal Operation/Procedure:</div>
            <div id="f_principalop" class="fb fb2" contenteditable="{{ $ce }}" spellcheck="false">{{ $principalOp }}</div>
        </td>
        <td colspan="2" style="vertical-align:top;text-align:right;">
            <div class="icd-boxes" style="margin-top:20px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
        </td>
    </tr>

    <tr>
        <td style="vertical-align:top;width:22%;">
            <div class="L">Disposition</div>
            @php $dispLow = strtolower($disp); @endphp
            <div style="margin-top:5px;line-height:2.1;">
                <label class="cb"><span id="sq_d_disc" class="sq {{ $dispLow==='discharged'?'on':'' }}" @unless($readonly)onclick="setSq('disp',this)"@endunless></span> Discharge</label><br>
                <label class="cb"><span id="sq_d_tran" class="sq {{ $dispLow==='referred'?'on':'' }}"   @unless($readonly)onclick="setSq('disp',this)"@endunless></span> Transferred</label><br>
                <label class="cb"><span id="sq_d_hama" class="sq {{ $dispLow==='hama'?'on':'' }}"       @unless($readonly)onclick="setSq('disp',this)"@endunless></span> HAMA</label><br>
                <label class="cb"><span id="sq_d_abs"  class="sq" @unless($readonly)onclick="setSq('disp',this)"@endunless></span> Absconded</label>
            </div>
        </td>
        <td colspan="2" style="vertical-align:top;">
            <div class="L">Results:</div>
            <div style="margin-top:5px;line-height:2.1;">
                <label class="cb"><span id="sq_r_rec"    class="sq" @unless($readonly)onclick="setSq('results',this)"@endunless></span> Recovered</label>
                <label class="cb"><span id="sq_r_imp"    class="sq" @unless($readonly)onclick="setSq('results',this)"@endunless></span> Improved</label><br>
                <label class="cb"><span id="sq_r_died"   class="sq {{ $dispLow==='expired'?'on':'' }}" @unless($readonly)onclick="setSq('results',this)"@endunless></span> Died</label>
                <label class="cb"><span id="sq_r_unimp"  class="sq" @unless($readonly)onclick="setSq('results',this)"@endunless></span> Unimproved</label><br>
                <label class="cb"><span id="sq_r_48m"    class="sq" @unless($readonly)onclick="setSq('results',this)"@endunless></span> -48 Hours</label>
                <label class="cb"><span id="sq_r_auto"   class="sq" @unless($readonly)onclick="setSq('results',this)"@endunless></span> Autopsy</label><br>
                <label class="cb"><span id="sq_r_48p"    class="sq" @unless($readonly)onclick="setSq('results',this)"@endunless></span> +48 Hours</label>
                <label class="cb"><span id="sq_r_noauto" class="sq" @unless($readonly)onclick="setSq('results',this)"@endunless></span> No Autopsy</label>
            </div>
        </td>
        <td colspan="3" style="vertical-align:top;">
            <div class="L">Attending Physician</div>
            <div id="f_physician2" class="fb fb3" contenteditable="{{ $ce }}" spellcheck="false">{{ $physician }}</div>
            <div style="border-top:1px solid #000;margin-top:20px;padding-top:3px;text-align:center;">
                <span style="font-size:8.5pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ,M.D.</span><br>
                <span class="Ls">Signature</span>
            </div>
        </td>
    </tr>

</table>
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
    cs:       ['sq_cs_s','sq_cs_m','sq_cs_sep','sq_cs_w'],
    ssc:      ['sq_ssc_a','sq_ssc_b','sq_ssc_c1','sq_ssc_c2','sq_ssc_c3','sq_ssc_d'],
    philtype: ['sq_phil_government','sq_phil_indigent','sq_phil_private','sq_phil_selfemployed'],
    disp:     ['sq_d_disc','sq_d_tran','sq_d_hama','sq_d_abs'],
    results:  ['sq_r_rec','sq_r_imp','sq_r_died','sq_r_unimp','sq_r_48m','sq_r_auto','sq_r_48p','sq_r_noauto'],
};
function setSq(group, el) {
    groups[group].forEach(id => { const sq = document.getElementById(id); if(sq) sq.classList.remove('on'); });
    el.classList.toggle('on');
}
function calcAge(val) {
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
    const csMap    = {sq_cs_s:'Single',sq_cs_m:'Married',sq_cs_sep:'Separated',sq_cs_w:'Widowed'};
    const sscMap   = {sq_ssc_a:'A',sq_ssc_b:'B',sq_ssc_c1:'C1',sq_ssc_c2:'C2',sq_ssc_c3:'C3',sq_ssc_d:'D'};
    const philMap  = {sq_phil_government:'Government',sq_phil_indigent:'Indigent',sq_phil_private:'Private',sq_phil_selfemployed:'Self-Employed'};
    const dispMap  = {sq_d_disc:'Discharged',sq_d_tran:'Referred',sq_d_hama:'HAMA',sq_d_abs:'Absconded'};
    const resMap   = {sq_r_rec:'Recovered',sq_r_imp:'Improved',sq_r_died:'Died',sq_r_unimp:'Unimproved',sq_r_48m:'-48 Hours',sq_r_auto:'Autopsy',sq_r_48p:'+48 Hours',sq_r_noauto:'No Autopsy'};
    const csId   = groups.cs.find(isOn) ?? '';
    const sscId  = groups.ssc.find(isOn) ?? '';
    const philId = groups.philtype.find(isOn) ?? '';
    const dispId = groups.disp.find(isOn) ?? '';
    const resId  = groups.results.find(isOn) ?? '';
    const sex    = isOn('sq_male') ? 'Male' : (isOn('sq_female') ? 'Female' : '');
    const typeAdm = isOn('sq_adm_new') ? 'New' : (isOn('sq_adm_old') ? 'Old' : '');
    return {
        patient_name_display: ctxt('f_patname'), permanent_address: ctxt('f_addr'),
        telephone_no: ctxt('f_tel'), sex, civil_status: csMap[csId] ?? '',
        birthdate: ctxt('f_bdate'), age: ctxt('f_age'), birthplace: ctxt('f_birthplace'),
        nationality: ctxt('f_nat'), religion: ctxt('f_religion'), occupation: ctxt('f_occ'),
        employer_name: ctxt('f_empname'), employer_address: ctxt('f_empaddr'), employer_phone: ctxt('f_emptel'),
        father_name: ctxt('f_dadname'), father_address: ctxt('f_dadaddr'), father_phone: ctxt('f_dadtel'),
        mother_maiden_name: ctxt('f_momname'), mother_address: ctxt('f_momaddr'), mother_phone: ctxt('f_momtel'),
        admission_date: ctxt('f_admdate'), admission_time: ctxt('f_admtime'),
        discharge_date: ctxt('f_dischdate'), discharge_time: ctxt('f_dischtime'),
        total_days: ctxt('f_totaldays'), ward_service: ctxt('f_ward'), type_of_admission: typeAdm,
        social_service_class: sscMap[sscId] ?? '', alert: ctxt('f_alert'), allergic_to: ctxt('f_allergic'),
        health_insurance_name: ctxt('f_healthins'), philhealth_id: ctxt('f_philid'), philhealth_type: philMap[philId] ?? '',
        data_furnished_by: ctxt('f_datafurnby'), data_furnished_address: ctxt('f_datafurnaddr'), data_furnished_relation: ctxt('f_datafurnrel'),
        admission_diagnosis: ctxt('f_admdx'), final_diagnosis: ctxt('f_finaldx'), other_diagnosis: ctxt('f_otherdx'),
        principal_operation: ctxt('f_principalop'), disposition: dispMap[dispId] ?? '', results: resMap[resId] ?? '',
    };
}
async function saveAndContinue() {
    const btn = document.getElementById('btnSave');
    btn.disabled = true; btn.textContent = 'Saving…';
    try {
        const res  = await fetch('{{ route("forms.adm-record.save", ["visit" => $visit->id]) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: JSON.stringify(collectData()),
        });
        const json = await res.json();
        if (json.success) {
            showToast('✔ Admission Record saved — advancing…');
            btn.textContent = '✔ Saved';
            window.parent.postMessage({ type: 'admSaved', paymentClass: json.payment_class ?? 'Charity' }, '*');
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
    setTimeout(() => { t.style.display = 'none'; }, 4500);
}
document.addEventListener('DOMContentLoaded', function() {
    const bd = document.getElementById('f_bdate');
    if (bd && bd.value) calcAge(bd.value);
});
</script>
@endunless

</body>
</html>