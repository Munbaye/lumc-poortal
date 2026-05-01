<x-filament-panels::page>

<style>
/*
 * ALL selectors are prefixed with #adm-page-root so nothing leaks
 * outside this page into Filament's sidebar / nav.
 */

/* ── Toolbar ──────────────────────────────────────────────────────────────── */
#adm-page-root .adm-toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #1e3a5f;
    color: #fff;
    padding: 10px 20px;
    border-radius: 10px 10px 0 0;
    flex-wrap: wrap;
}
#adm-page-root .adm-toolbar .tl { font-size: 14px; font-weight: 700; flex: 1; }
#adm-page-root .adm-toolbar .tag {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 4px;
    padding: 2px 10px;
    font-size: 11px;
    text-transform: uppercase;
}
#adm-page-root .btn-print-adm {
    background: #fff; color: #1e3a5f; border: none;
    padding: 6px 16px; border-radius: 5px; font-size: 13px;
    font-weight: 700; cursor: pointer;
}
#adm-page-root .btn-print-adm:hover { background: #dbeafe; }
#adm-page-root .btn-save-adm {
    background: #059669; color: #fff; border: none;
    padding: 6px 20px; border-radius: 5px; font-size: 13px;
    font-weight: 700; cursor: pointer;
}
#adm-page-root .btn-save-adm:hover { background: #047857; }
#adm-page-root .btn-save-adm:disabled { opacity: .6; cursor: not-allowed; }

/* ── Paper wrapper ────────────────────────────────────────────────────────── */
#adm-page-root .adm-paper {
    background: #fff;
    border: 1px solid #d1d5db;
    border-radius: 0 0 10px 10px;
    padding: 24px 28px;
    /* NO font-size here — scoped only inside .ft below */
}
.dark #adm-page-root .adm-paper { background: #1f2937; border-color: #374151; }

/* Font size scoped ONLY to the form table and its children */
#adm-page-root .adm-paper .ft {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 9.5pt;
    color: #111;
}
.dark #adm-page-root .adm-paper .ft { color: #e5e7eb; }
#adm-page-root .adm-paper .ft * {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 9.5pt;
}
#adm-page-root .adm-paper .ft td {
    border: 1.2px solid #000;
    padding: 3px 5px;
    vertical-align: top;
}
.dark #adm-page-root .adm-paper .ft td { border-color: #6b7280; }

#adm-page-root .adm-paper .page-title {
    text-align: center;
    font-size: 15pt;
    font-weight: bold;
    text-decoration: underline;
    text-transform: uppercase;
    margin-bottom: 14px;
    letter-spacing: .04em;
    font-family: Arial, Helvetica, sans-serif;
    color: #111;
}
.dark #adm-page-root .adm-paper .page-title { color: #e5e7eb; }

#adm-page-root .L  { font-weight: bold; font-size: 8.5pt !important; text-transform: uppercase; }
#adm-page-root .Ls { font-weight: bold; font-size: 7.5pt !important; text-transform: uppercase; }

/* Editable spans */
#adm-page-root .f {
    display: inline-block; min-height: 14px; min-width: 60px;
    border-bottom: 1px solid #555;
    vertical-align: bottom; outline: none; cursor: text;
    font-size: 9pt !important; font-family: Arial, sans-serif; padding: 0 2px;
}
#adm-page-root .fb {
    display: block; width: 100%; min-height: 16px;
    border-bottom: 1px solid #555;
    outline: none; cursor: text;
    font-size: 9pt !important; font-family: Arial, sans-serif;
    padding: 1px 2px; margin-top: 2px;
}
#adm-page-root .fb2 { min-height: 28px; }
#adm-page-root .fb3 { min-height: 44px; }
#adm-page-root .f:focus,
#adm-page-root .fb:focus { background: #fef9ec; }
.dark #adm-page-root .f:focus,
.dark #adm-page-root .fb:focus { background: #374151; }

/* Native inputs inside the table */
#adm-page-root .fi {
    border: none; border-bottom: 1px solid #555; outline: none;
    font-size: 9pt !important; font-family: Arial, sans-serif;
    padding: 1px 2px; background: transparent; width: 100%; margin-top: 2px;
}
#adm-page-root .fi:focus { background: #fef9ec; }
.dark #adm-page-root .fi { color: #e5e7eb; }
.dark #adm-page-root .fi:focus { background: #374151; }

/* Checkboxes */
#adm-page-root .cb {
    display: inline-flex; align-items: center; gap: 3px;
    margin-right: 5px; white-space: nowrap; font-size: 8.5pt !important;
}
#adm-page-root .sq {
    width: 10px; height: 10px; border: 1.2px solid #000;
    display: inline-block; flex-shrink: 0; position: relative;
    vertical-align: middle; cursor: pointer;
}
.dark #adm-page-root .sq { border-color: #9ca3af; }
#adm-page-root .sq.on::after {
    content: '✓'; position: absolute; top: -3px; left: 0;
    font-size: 10pt; font-weight: bold; line-height: 1;
}

/* ICD boxes */
#adm-page-root .icd-boxes { display: inline-flex; gap: 3px; margin-left: 6px; }
#adm-page-root .icd-box   { width: 22px; height: 22px; border: 1.2px solid #000; display: inline-block; }

/* ── Convert section — normal browser font sizes, NOT print sizes ─────────── */
#adm-page-root .convert-section {
    margin-top: 28px;
    border: 2px solid #f59e0b;
    border-radius: 10px;
    padding: 20px 24px;
    background: #fffbeb;
    font-size: 0.875rem;
    font-family: inherit;
}
.dark #adm-page-root .convert-section { background: #451a03; border-color: #92400e; }
#adm-page-root .convert-section h3 {
    margin: 0 0 10px 0; font-size: 1rem; font-weight: 700; color: #92400e;
    font-family: inherit;
}
.dark #adm-page-root .convert-section h3 { color: #fde68a; }
#adm-page-root .convert-section p {
    margin: 0 0 12px 0; font-size: 0.875rem; color: #92400e; font-family: inherit;
}
.dark #adm-page-root .convert-section p { color: #fde68a; }

#adm-page-root .btn-convert {
    background: #16a34a; color: #fff; border: none;
    padding: 12px 28px; border-radius: 8px;
    font-size: 0.9rem; font-weight: 700; cursor: pointer; font-family: inherit;
}
#adm-page-root .btn-convert:hover { background: #15803d; }
#adm-page-root .btn-convert:disabled { opacity: .6; cursor: not-allowed; }
#adm-page-root .btn-back {
    background: #f3f4f6; color: #374151;
    border: 1px solid #d1d5db;
    padding: 10px 22px; border-radius: 8px;
    font-size: 0.875rem; font-weight: 500; cursor: pointer; font-family: inherit;
}
#adm-page-root .btn-back:hover { background: #e5e7eb; }
.dark #adm-page-root .btn-back { background: #374151; color: #e5e7eb; border-color: #4b5563; }

/* Toast — fixed position, fine to be global */
#admToast {
    position: fixed; bottom: 22px; right: 22px;
    background: #059669; color: #fff;
    padding: 12px 22px; border-radius: 8px;
    font-size: 13px; font-weight: 600;
    box-shadow: 0 4px 16px rgba(0,0,0,.25);
    display: none; z-index: 99999;
}
#admToast.error { background: #dc2626; }

/* Print */
@media print {
    #adm-page-root .adm-toolbar,
    #adm-page-root .convert-section,
    #adm-page-root .no-print { display: none !important; }
    #adm-page-root .adm-paper { border: none; padding: 0; }
    [contenteditable] { outline: none !important; background: transparent !important; }
    input { background: transparent !important; outline: none !important; }
}
</style>

@php
    $visit     = $this->visit;
    $baby      = $this->baby;
    $nicuAdm   = $this->nicuAdmission;
    $admRecord = $this->admRecord;
    $tz        = 'Asia/Manila';

    $first = fn(...$vals) => collect($vals)->first(fn($v) => filled($v)) ?? '';

    // ── Patient name ──────────────────────────────────────────────────────────
    $patFamily = $first($admRecord?->patient_family_name, $baby?->baby_family_name, strtoupper($baby?->family_name ?? ''));
    $patFirst  = $first($admRecord?->patient_first_name,  $baby?->baby_first_name,  strtoupper($baby?->first_name ?? ''));
    $patMiddle = $first($admRecord?->patient_middle_name, $baby?->baby_middle_name, strtoupper($baby?->middle_name ?? ''));
    $patNameFormatted = trim($patFamily . ', ' . $patFirst . ($patMiddle ? ' ' . $patMiddle : ''));

    // ── Demographics ──────────────────────────────────────────────────────────
    $addr       = $first($admRecord?->permanent_address, $baby?->mother_address_full, $baby?->address);
    $tel        = $first($admRecord?->telephone_no,      $baby?->mother_contact,      $baby?->contact_number ?? '');
    $sex        = $first($admRecord?->sex,               $baby?->sex ?? '');
    $cs         = $first($admRecord?->civil_status,      '');
    $bdateRaw   = $first($admRecord?->birthdate,         $baby?->birth_datetime,      $baby?->birthday);
    $bdateInput = $bdateRaw ? \Carbon\Carbon::parse($bdateRaw)->format('Y-m-d') : '';
    $age        = $first($admRecord?->age,               $baby?->age ?? $baby?->current_age ?? '');
    $birthplace = $first($admRecord?->birthplace,        $baby?->birthplace ?? '');
    $nat        = $first($admRecord?->nationality,       $baby?->nationality ?? 'Filipino');
    $religion   = $first($admRecord?->religion,          $baby?->religion ?? '');
    $occ        = $first($admRecord?->occupation,        '');

    $empName = $first($admRecord?->employer_name,    $baby?->employer_name ?? '');
    $empAddr = $first($admRecord?->employer_address, $baby?->employer_address ?? '');
    $empTel  = $first($admRecord?->employer_phone,   $baby?->employer_phone ?? '');

    $dadName = $first($admRecord?->father_name,    $baby?->father_full_name ?? $baby?->father_name ?? '');
    $dadAddr = $first($admRecord?->father_address, $baby?->father_address ?? '');
    $dadTel  = $first($admRecord?->father_phone,   $baby?->father_phone ?? '');

    $momName = $first($admRecord?->mother_maiden_name,
                      trim(($baby?->mother_first_name ?? '') . ' ' . ($baby?->mother_middle_name ?? '') . ' ' . ($baby?->mother_family_name ?? '')));
    $momAddr = $first($admRecord?->mother_address, $baby?->mother_address_full ?? '');
    $momTel  = $first($admRecord?->mother_phone,   $baby?->mother_contact ?? '');

    // ── Admission date/time ───────────────────────────────────────────────────
    // Priority:
    //   1. admRecord (clerk already saved the form) — use as-is
    //   2. clerk_admitted_at (clerk completed admission)
    //   3. doctor_admitted_at (doctor admitted the patient)
    //   4. blank — never fall back to birth time or created_at
    // All DB timestamps are UTC → convert to Asia/Manila before display.
    $admDateInput = '';
    $admTimeVal   = '';

    if ($admRecord?->admission_date) {
        // Clerk already saved the ADM-001 form — use stored values as-is
        $admDateInput = \Carbon\Carbon::parse($admRecord->admission_date)->format('Y-m-d');
        $admTimeVal   = $admRecord->admission_time ?? '';
    } elseif ($visit->clerk_admitted_at) {
        // Clerk completed admission
        $dt           = \Carbon\Carbon::parse($visit->clerk_admitted_at)->setTimezone($tz);
        $admDateInput = $dt->format('Y-m-d');
        $admTimeVal   = $dt->format('H:i');
    } elseif ($visit->doctor_admitted_at) {
        // Doctor admitted the patient
        $dt           = \Carbon\Carbon::parse($visit->doctor_admitted_at)->setTimezone($tz);
        $admDateInput = $dt->format('Y-m-d');
        $admTimeVal   = $dt->format('H:i');
    }
    // else: both are null → fields stay blank, clerk fills them in manually

    // ── Discharge date/time ───────────────────────────────────────────────────
    $dischDateInput = '';
    $dischTimeVal   = '';
    if ($admRecord?->discharge_date) {
        $dischDateInput = \Carbon\Carbon::parse($admRecord->discharge_date)->format('Y-m-d');
        $dischTimeVal   = $admRecord->discharge_time ?? '';
    } elseif ($visit->discharged_at) {
        $dt             = \Carbon\Carbon::parse($visit->discharged_at)->setTimezone($tz);
        $dischDateInput = $dt->format('Y-m-d');
        $dischTimeVal   = $dt->format('H:i');
    }

    $totalDays = $admRecord?->total_days ?? '';

    // ── Ward / service ────────────────────────────────────────────────────────
    $ward    = $first($admRecord?->ward_service,         $visit->admitted_service, 'NICU');
    $typeAdm = $first($admRecord?->type_of_admission,    'New');
    $ssc     = $first($admRecord?->social_service_class, $baby?->social_service_class ?? '');

    $alert      = $admRecord?->alert ?? '';
    $allergicTo = $first($admRecord?->allergic_to, '');

    $healthIns = $admRecord?->health_insurance_name ?? '';
    $philId    = $first($admRecord?->philhealth_id,   $baby?->philhealth_id ?? '');
    $philType  = $first($admRecord?->philhealth_type, $baby?->philhealth_type ?? '');

    $dataFurnBy  = $admRecord?->data_furnished_by ?? '';
    $dataFurnAdd = $admRecord?->data_furnished_address ?? '';
    $dataFurnRel = $admRecord?->data_furnished_relation ?? '';

    // ── Diagnoses ─────────────────────────────────────────────────────────────
    $admDx       = $first($admRecord?->admission_diagnosis, $visit->admitting_diagnosis ?? $nicuAdm?->reason_for_nicu_admission ?? '');
    $finalDx     = $first($admRecord?->final_diagnosis,     '');
    $otherDx     = $first($admRecord?->other_diagnosis,     '');
    $principalOp = $admRecord?->principal_operation ?? '';
    $disp        = $first($admRecord?->disposition,         $visit->disposition ?? '');
    $results     = $admRecord?->results ?? '';

    // ── Case number ───────────────────────────────────────────────────────────
    $caseNo = $baby?->is_provisional
        ? ($baby?->temporary_case_no ?? 'PROVISIONAL')
        : ($baby?->case_no ?? '—');

    // ── Attending physician ───────────────────────────────────────────────────
    $physician = $visit->medicalHistory?->doctor
        ? 'Dr. ' . $visit->medicalHistory->doctor->name
        : '';
@endphp

<div id="admToast"></div>

{{-- ══ Root wrapper — all CSS scoped inside this ID ══════════════════════════ --}}
<div id="adm-page-root">

{{-- ── Toolbar ────────────────────────────────────────────────────────────── --}}
<div class="adm-toolbar no-print">
    <span class="tl">Admission and Discharge Record (ADM-001)</span>
    <span class="tag" style="background:rgba(245,158,11,.3);">
        {{ $baby?->is_provisional ? '⚠ PROVISIONAL' : '✓ PERMANENT' }}
    </span>
    <span class="tag">{{ $caseNo }}</span>
    @if($baby)
    <span class="tag">
        {{ trim(($baby->baby_family_name ?? $baby->family_name ?? '') . ', ' . ($baby->baby_first_name ?? $baby->first_name ?? '')) }}
    </span>
    @endif
    <button class="btn-print-adm" onclick="window.print()">🖨️ Print / PDF</button>
    <button id="btnSaveAdm" class="btn-save-adm" onclick="admSaveRecord()">💾 Save Record</button>
</div>

{{-- ── ADM-001 Paper ──────────────────────────────────────────────────────── --}}
<div class="adm-paper">
    <div class="page-title">Admission and Discharge Record</div>

    <table class="ft">

        {{-- Row 1: Name + Case No + Ward --}}
        <tr>
            <td colspan="4">
                <span class="L">Patient's Name:</span>
                <span id="f_patname" class="f" contenteditable="true" spellcheck="false"
                      style="min-width:220px;font-weight:bold;">{{ $patNameFormatted }}</span>
            </td>
            <td colspan="2" style="vertical-align:top;">
                <div class="L">Hosp. Case No.</div>
                <div id="f_caseno" class="fb" contenteditable="true" spellcheck="false">{{ $caseNo }}</div>
                <div class="L" style="margin-top:4px;">Ward/Services</div>
                <div id="f_ward" class="fb" contenteditable="true" spellcheck="false">{{ $ward }}</div>
            </td>
        </tr>

        {{-- Row 2: Address, Tel, Sex, Civil Status --}}
        <tr>
            <td colspan="2">
                <div class="L">Permanent Address:</div>
                <div id="f_addr" class="fb fb2" contenteditable="true" spellcheck="false">{{ $addr }}</div>
            </td>
            <td>
                <div class="L">Tel. No.</div>
                <input type="tel" id="f_tel" class="fi" value="{{ $tel }}" style="min-height:28px;">
            </td>
            <td style="vertical-align:top;">
                <div class="L">Sex</div>
                <div style="margin-top:3px;line-height:1.9;">
                    <label class="cb">
                        <span id="sq_male"   class="sq {{ $sex==='Male'?'on':'' }}"   onclick="toggleSq('sq_male','sq_female')"></span> M
                    </label><br>
                    <label class="cb">
                        <span id="sq_female" class="sq {{ $sex==='Female'?'on':'' }}" onclick="toggleSq('sq_female','sq_male')"></span> F
                    </label>
                </div>
            </td>
            <td colspan="2" style="vertical-align:top;">
                <div class="L">Civil Status</div>
                <div style="margin-top:3px;line-height:1.9;">
                    <label class="cb"><span id="sq_cs_s"   class="sq {{ $cs==='Single'?'on':'' }}"    onclick="setSq('cs',this)"></span> S</label>
                    <label class="cb"><span id="sq_cs_m"   class="sq {{ $cs==='Married'?'on':'' }}"   onclick="setSq('cs',this)"></span> M</label>
                    <label class="cb"><span id="sq_cs_sep" class="sq {{ $cs==='Separated'?'on':'' }}" onclick="setSq('cs',this)"></span> Sep</label><br>
                    <label class="cb"><span id="sq_cs_w"   class="sq {{ $cs==='Widowed'?'on':'' }}"   onclick="setSq('cs',this)"></span> W</label>
                </div>
            </td>
        </tr>

        {{-- Row 3: Birthdate, Age, Birthplace, Nationality, Religion, Occupation --}}
        <tr>
            <td>
                <div class="L">Birthdate</div>
                <input type="date" id="f_bdate" class="fi" value="{{ $bdateInput }}" onchange="calcAge(this.value)">
            </td>
            <td>
                <div class="L">Age</div>
                <input type="number" id="f_age" class="fi" value="{{ $age }}" min="0" max="120" inputmode="numeric">
            </td>
            <td>
                <div class="L">Birthplace</div>
                <div id="f_birthplace" class="fb" contenteditable="true" spellcheck="false">{{ $birthplace }}</div>
            </td>
            <td>
                <div class="L">Nationality</div>
                <div id="f_nat" class="fb" contenteditable="true" spellcheck="false">{{ $nat }}</div>
            </td>
            <td>
                <div class="L">Religion</div>
                <div id="f_religion" class="fb" contenteditable="true" spellcheck="false">{{ $religion }}</div>
            </td>
            <td>
                <div class="L">Occupation</div>
                <div id="f_occ" class="fb" contenteditable="true" spellcheck="false">{{ $occ }}</div>
            </td>
        </tr>

        {{-- Row 4: Employer --}}
        <tr>
            <td colspan="2">
                <div class="L">Employer</div>
                <div id="f_empname" class="fb" contenteditable="true" spellcheck="false">{{ $empName }}</div>
            </td>
            <td colspan="2">
                <div class="L">Address</div>
                <div id="f_empaddr" class="fb" contenteditable="true" spellcheck="false">{{ $empAddr }}</div>
            </td>
            <td colspan="2">
                <div class="L">Tel.No.</div>
                <input type="tel" id="f_emptel" class="fi" value="{{ $empTel }}">
            </td>
        </tr>

        {{-- Row 5: Father --}}
        <tr>
            <td colspan="2">
                <div class="L">Father's Name</div>
                <div id="f_dadname" class="fb" contenteditable="true" spellcheck="false">{{ $dadName }}</div>
            </td>
            <td colspan="2">
                <div class="L">Address</div>
                <div id="f_dadaddr" class="fb" contenteditable="true" spellcheck="false">{{ $dadAddr }}</div>
            </td>
            <td colspan="2">
                <div class="L">Tel.No.</div>
                <input type="tel" id="f_dadtel" class="fi" value="{{ $dadTel }}">
            </td>
        </tr>

        {{-- Row 6: Mother --}}
        <tr>
            <td colspan="2">
                <div class="L">Mother's (Maiden) Name</div>
                <div id="f_momname" class="fb" contenteditable="true" spellcheck="false">{{ $momName }}</div>
            </td>
            <td colspan="2">
                <div class="L">Address</div>
                <div id="f_momaddr" class="fb" contenteditable="true" spellcheck="false">{{ $momAddr }}</div>
            </td>
            <td colspan="2">
                <div class="L">Tel.No.</div>
                <input type="tel" id="f_momtel" class="fi" value="{{ $momTel }}">
            </td>
        </tr>

        {{-- Row 7: Admission/Discharge dates, Total Days, Physician --}}
        <tr>
            <td colspan="2" style="vertical-align:top;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 8px;">
                    <div>
                        <div class="L">Admission:</div>
                        <div class="Ls" style="margin-top:5px;">Date:</div>
                        <input type="date" id="f_admdate" class="fi" value="{{ $admDateInput }}">
                        <div class="Ls" style="margin-top:5px;">Time:</div>
                        <input type="time" id="f_admtime" class="fi" value="{{ $admTimeVal }}">
                    </div>
                    <div>
                        <div class="L">Discharge:</div>
                        <div class="Ls" style="margin-top:5px;">Date:</div>
                        <input type="date" id="f_dischdate" class="fi" value="{{ $dischDateInput }}">
                        <div class="Ls" style="margin-top:5px;">Time:</div>
                        <input type="time" id="f_dischtime" class="fi" value="{{ $dischTimeVal }}">
                    </div>
                </div>
            </td>
            <td colspan="2" style="vertical-align:top;">
                <div class="L">Total No. of Days</div>
                <input type="number" id="f_totaldays" class="fi" value="{{ $totalDays }}" min="0" inputmode="numeric" style="min-height:28px;">
            </td>
            <td colspan="2" style="vertical-align:top;">
                <div class="L">Attending Physician</div>
                <div id="f_physician" class="fb fb2" contenteditable="true" spellcheck="false">{{ $physician }}</div>
            </td>
        </tr>

        {{-- Row 8: Type of Admission --}}
        <tr>
            <td colspan="6">
                <span class="L">Type of Admission:</span>&nbsp;&nbsp;
                <label class="cb">
                    <span id="sq_adm_new" class="sq {{ $typeAdm==='New'?'on':'' }}" onclick="toggleSq('sq_adm_new','sq_adm_old')"></span> New
                </label>
                <label class="cb">
                    <span id="sq_adm_old" class="sq {{ $typeAdm==='Old'?'on':'' }}" onclick="toggleSq('sq_adm_old','sq_adm_new')"></span> Old
                </label>
            </td>
        </tr>

        {{-- Row 9: Social Service Classification --}}
        <tr>
            <td colspan="6">
                <span class="L">Social Service Classification:</span>&nbsp;&nbsp;
                @foreach(['A','B','C1','C2','C3','D'] as $cls)
                <label class="cb">
                    <span id="sq_ssc_{{ strtolower($cls) }}" class="sq {{ $ssc===$cls?'on':'' }}" onclick="setSq('ssc',this)"></span> {{ $cls }}
                </label>
                @endforeach
            </td>
        </tr>

        {{-- Row 10: Alert, Health Insurance, PhilHealth --}}
        <tr>
            <td colspan="2" style="vertical-align:top;">
                <div class="L">Alert:</div>
                <div id="f_alert" class="fb" contenteditable="true" spellcheck="false">{{ $alert }}</div>
                <div class="L" style="margin-top:5px;">Allergic To</div>
                <div id="f_allergic" class="fb" contenteditable="true" spellcheck="false">{{ $allergicTo }}</div>
            </td>
            <td colspan="2" style="vertical-align:top;">
                <div class="L">Health Insurance Name:</div>
                <div id="f_healthins" class="fb fb2" contenteditable="true" spellcheck="false">{{ $healthIns }}</div>
                <div class="L" style="margin-top:5px;">PhilHealth ID No.</div>
                <div id="f_philid" class="fb" contenteditable="true" spellcheck="false">{{ $philId }}</div>
            </td>
            <td colspan="2" style="vertical-align:top;">
                <div class="L" style="text-decoration:underline;">PhilHealth Type</div>
                <div style="margin-top:4px;line-height:2.0;">
                    @foreach(['Government'=>'Govt.','Indigent'=>'Indigent','Private'=>'Private','Self-Employed'=>'Self Employed'] as $val => $label)
                    <label class="cb">
                        <span id="sq_phil_{{ strtolower(str_replace([' ','-'],'',$val)) }}"
                              class="sq {{ $philType===$val?'on':'' }}"
                              onclick="setSq('philtype',this)"></span> {{ $label }}
                    </label><br>
                    @endforeach
                </div>
            </td>
        </tr>

        {{-- Row 11: Data Furnished By --}}
        <tr>
            <td colspan="2">
                <div class="L">Data Furnished By:</div>
                <div id="f_datafurnby" class="fb fb2" contenteditable="true" spellcheck="false">{{ $dataFurnBy }}</div>
            </td>
            <td colspan="2">
                <div class="L">Address</div>
                <div id="f_datafurnaddr" class="fb fb2" contenteditable="true" spellcheck="false">{{ $dataFurnAdd }}</div>
            </td>
            <td colspan="2">
                <div class="L">Relation to Patient</div>
                <div id="f_datafurnrel" class="fb fb2" contenteditable="true" spellcheck="false">{{ $dataFurnRel }}</div>
            </td>
        </tr>

        {{-- Row 12: Admission Diagnosis --}}
        <tr>
            <td colspan="4" style="vertical-align:top;">
                <div class="L">Admission Diagnosis:</div>
                <div id="f_admdx" class="fb fb2" contenteditable="true" spellcheck="false">{{ $admDx }}</div>
            </td>
            <td colspan="2" style="vertical-align:top;text-align:right;">
                <div class="L">ICD Code No.</div>
                <div class="icd-boxes" style="margin-top:4px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
            </td>
        </tr>

        {{-- Row 13: Final Diagnosis --}}
        <tr>
            <td colspan="4" style="vertical-align:top;">
                <div class="L">Final Diagnosis:</div>
                <div id="f_finaldx" class="fb fb2" contenteditable="true" spellcheck="false">{{ $finalDx }}</div>
            </td>
            <td colspan="2" style="vertical-align:top;text-align:right;">
                <div class="icd-boxes" style="margin-top:20px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
            </td>
        </tr>

        {{-- Row 14: Other Diagnosis --}}
        <tr>
            <td colspan="4" style="vertical-align:top;">
                <div class="L">Other Diagnosis:</div>
                <div id="f_otherdx" class="fb fb3" contenteditable="true" spellcheck="false">{{ $otherDx }}</div>
            </td>
            <td colspan="2" style="vertical-align:top;text-align:right;">
                <div class="icd-boxes" style="margin-top:8px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
                <div class="icd-boxes" style="margin-top:4px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
            </td>
        </tr>

        {{-- Row 15: Principal Operation --}}
        <tr>
            <td colspan="4" style="vertical-align:top;">
                <div class="L">Principal Operation/Procedure:</div>
                <div id="f_principalop" class="fb fb2" contenteditable="true" spellcheck="false">{{ $principalOp }}</div>
            </td>
            <td colspan="2" style="vertical-align:top;text-align:right;">
                <div class="icd-boxes" style="margin-top:20px;">@for($i=0;$i<5;$i++)<span class="icd-box"></span>@endfor</div>
            </td>
        </tr>

        {{-- Row 16: Disposition / Results / Physician signature --}}
        <tr>
            <td style="vertical-align:top;width:22%;">
                <div class="L">Disposition</div>
                @php $dispLow = strtolower($disp); @endphp
                <div style="margin-top:5px;line-height:2.1;">
                    <label class="cb"><span id="sq_d_disc" class="sq {{ $dispLow==='discharged'?'on':'' }}" onclick="setSq('disp',this)"></span> Discharge</label><br>
                    <label class="cb"><span id="sq_d_tran" class="sq {{ $dispLow==='referred'?'on':'' }}"   onclick="setSq('disp',this)"></span> Transferred</label><br>
                    <label class="cb"><span id="sq_d_hama" class="sq {{ $dispLow==='hama'?'on':'' }}"       onclick="setSq('disp',this)"></span> HAMA</label><br>
                    <label class="cb"><span id="sq_d_abs"  class="sq"                                        onclick="setSq('disp',this)"></span> Absconded</label>
                </div>
            </td>
            <td colspan="2" style="vertical-align:top;">
                <div class="L">Results:</div>
                <div style="margin-top:5px;line-height:2.1;">
                    @php $resLow = strtolower($results); @endphp
                    <label class="cb"><span id="sq_r_rec"    class="sq {{ $resLow==='recovered'?'on':'' }}"  onclick="setSq('results',this)"></span> Recovered</label>
                    <label class="cb"><span id="sq_r_imp"    class="sq {{ $resLow==='improved'?'on':'' }}"   onclick="setSq('results',this)"></span> Improved</label><br>
                    <label class="cb"><span id="sq_r_died"   class="sq {{ ($dispLow==='expired'||$resLow==='died')?'on':'' }}" onclick="setSq('results',this)"></span> Died</label>
                    <label class="cb"><span id="sq_r_unimp"  class="sq {{ $resLow==='unimproved'?'on':'' }}" onclick="setSq('results',this)"></span> Unimproved</label><br>
                    <label class="cb"><span id="sq_r_48m"    class="sq {{ $resLow==='-48 hours'?'on':'' }}"  onclick="setSq('results',this)"></span> -48 Hours</label>
                    <label class="cb"><span id="sq_r_auto"   class="sq {{ $resLow==='autopsy'?'on':'' }}"    onclick="setSq('results',this)"></span> Autopsy</label><br>
                    <label class="cb"><span id="sq_r_48p"    class="sq {{ $resLow==='+48 hours'?'on':'' }}"  onclick="setSq('results',this)"></span> +48 Hours</label>
                    <label class="cb"><span id="sq_r_noauto" class="sq {{ $resLow==='no autopsy'?'on':'' }}" onclick="setSq('results',this)"></span> No Autopsy</label>
                </div>
            </td>
            <td colspan="3" style="vertical-align:top;">
                <div class="L">Attending Physician</div>
                <div id="f_physician2" class="fb fb3" contenteditable="true" spellcheck="false">{{ $physician }}</div>
                <div style="border-top:1px solid #000;margin-top:20px;padding-top:3px;text-align:center;">
                    <span style="font-size:8.5pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ,M.D.</span><br>
                    <span class="Ls">Signature</span>
                </div>
            </td>
        </tr>

    </table>
</div>{{-- /.adm-paper --}}

{{-- ── Convert to Permanent Section ──────────────────────────────────────────── --}}
@if($baby && $baby->is_provisional)
<div class="convert-section no-print">
    <h3>
        <x-heroicon-o-exclamation-triangle class="w-5 h-5 inline -mt-0.5 mr-1" />
        Convert to Permanent Record
    </h3>
    <p>
        Save the Admission Record above first, then click <strong>Convert to Permanent</strong> to generate
        a permanent case number (LUMC-YYYY-xxxxxx) for this baby.
        All clinical data will remain linked. <strong>This cannot be undone.</strong>
    </p>
    <p>
        Current Temporary ID: <strong>{{ $baby->temporary_case_no ?? '—' }}</strong> &nbsp;|&nbsp;
        Registered: {{ \Carbon\Carbon::parse($baby->created_at)->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}
    </p>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <button type="button" class="btn-back" onclick="window.location.href='/clerk/visits?tab=provisional'">
            ← Back to List
        </button>
        <button type="button" wire:click="convert" class="btn-convert" wire:loading.attr="disabled">
            <span wire:loading.remove>
                <x-heroicon-o-check-circle class="w-4 h-4 inline -mt-0.5 mr-1" />
                Convert to Permanent Record
            </span>
            <span wire:loading>Converting...</span>
        </button>
    </div>
</div>
@elseif($baby && !$baby->is_provisional)
<div class="no-print" style="margin-top:20px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:10px;padding:16px 20px;font-size:0.875rem;font-family:inherit;">
    <p style="margin:0;font-weight:700;color:#065f46;">
        ✓ This record has been converted to permanent — Case No: {{ $baby->case_no }}
    </p>
    <div style="margin-top:12px;">
        <button type="button" class="btn-back" onclick="window.location.href='/clerk/visits'">← Back to Visits</button>
    </div>
</div>
@endif

</div>{{-- /#adm-page-root --}}

{{-- ── JavaScript ──────────────────────────────────────────────────────────── --}}
<script>
function toggleSq(idA, idB) {
    const a = document.getElementById(idA), b = document.getElementById(idB);
    a.classList.toggle('on');
    b.classList.remove('on');
}

const sqGroups = {
    cs:       ['sq_cs_s','sq_cs_m','sq_cs_sep','sq_cs_w'],
    ssc:      ['sq_ssc_a','sq_ssc_b','sq_ssc_c1','sq_ssc_c2','sq_ssc_c3','sq_ssc_d'],
    philtype: ['sq_phil_government','sq_phil_indigent','sq_phil_private','sq_phil_selfemployed'],
    disp:     ['sq_d_disc','sq_d_tran','sq_d_hama','sq_d_abs'],
    results:  ['sq_r_rec','sq_r_imp','sq_r_died','sq_r_unimp','sq_r_48m','sq_r_auto','sq_r_48p','sq_r_noauto'],
};

function setSq(group, el) {
    sqGroups[group].forEach(id => {
        const sq = document.getElementById(id);
        if (sq) sq.classList.remove('on');
    });
    el.classList.toggle('on');
}

function calcAge(val) {
    if (!val) return;
    const d = new Date(val), today = new Date();
    let age = today.getFullYear() - d.getFullYear();
    const m = today.getMonth() - d.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--;
    if (age >= 0 && age <= 150) document.getElementById('f_age').value = age;
}

function g(id)    { return document.getElementById(id); }
function ctxt(id) {
    const el = g(id);
    if (!el) return '';
    return (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA')
        ? el.value
        : el.innerText.trim();
}
function isOn(id) { return g(id) ? g(id).classList.contains('on') : false; }

function collectAdmData() {
    const csMap   = {sq_cs_s:'Single',sq_cs_m:'Married',sq_cs_sep:'Separated',sq_cs_w:'Widowed'};
    const sscMap  = {sq_ssc_a:'A',sq_ssc_b:'B',sq_ssc_c1:'C1',sq_ssc_c2:'C2',sq_ssc_c3:'C3',sq_ssc_d:'D'};
    const philMap = {sq_phil_government:'Government',sq_phil_indigent:'Indigent',sq_phil_private:'Private',sq_phil_selfemployed:'Self-Employed'};
    const dispMap = {sq_d_disc:'Discharged',sq_d_tran:'Referred',sq_d_hama:'HAMA',sq_d_abs:'Absconded'};
    const resMap  = {sq_r_rec:'Recovered',sq_r_imp:'Improved',sq_r_died:'Died',sq_r_unimp:'Unimproved',sq_r_48m:'-48 Hours',sq_r_auto:'Autopsy',sq_r_48p:'+48 Hours',sq_r_noauto:'No Autopsy'};

    const csId   = sqGroups.cs.find(isOn)      ?? '';
    const sscId  = sqGroups.ssc.find(isOn)     ?? '';
    const philId = sqGroups.philtype.find(isOn) ?? '';
    const dispId = sqGroups.disp.find(isOn)    ?? '';
    const resId  = sqGroups.results.find(isOn) ?? '';
    const sex     = isOn('sq_male') ? 'Male' : (isOn('sq_female') ? 'Female' : '');
    const typeAdm = isOn('sq_adm_new') ? 'New' : (isOn('sq_adm_old') ? 'Old' : '');

    return {
        patient_name_display:    ctxt('f_patname'),
        permanent_address:       ctxt('f_addr'),
        telephone_no:            ctxt('f_tel'),
        sex,
        civil_status:            csMap[csId] ?? '',
        birthdate:               ctxt('f_bdate'),
        age:                     ctxt('f_age'),
        birthplace:              ctxt('f_birthplace'),
        nationality:             ctxt('f_nat'),
        religion:                ctxt('f_religion'),
        occupation:              ctxt('f_occ'),
        employer_name:           ctxt('f_empname'),
        employer_address:        ctxt('f_empaddr'),
        employer_phone:          ctxt('f_emptel'),
        father_name:             ctxt('f_dadname'),
        father_address:          ctxt('f_dadaddr'),
        father_phone:            ctxt('f_dadtel'),
        mother_maiden_name:      ctxt('f_momname'),
        mother_address:          ctxt('f_momaddr'),
        mother_phone:            ctxt('f_momtel'),
        admission_date:          ctxt('f_admdate'),
        admission_time:          ctxt('f_admtime'),
        discharge_date:          ctxt('f_dischdate'),
        discharge_time:          ctxt('f_dischtime'),
        total_days:              ctxt('f_totaldays'),
        ward_service:            ctxt('f_ward'),
        type_of_admission:       typeAdm,
        social_service_class:    sscMap[sscId] ?? '',
        alert:                   ctxt('f_alert'),
        allergic_to:             ctxt('f_allergic'),
        health_insurance_name:   ctxt('f_healthins'),
        philhealth_id:           ctxt('f_philid'),
        philhealth_type:         philMap[philId] ?? '',
        data_furnished_by:       ctxt('f_datafurnby'),
        data_furnished_address:  ctxt('f_datafurnaddr'),
        data_furnished_relation: ctxt('f_datafurnrel'),
        admission_diagnosis:     ctxt('f_admdx'),
        final_diagnosis:         ctxt('f_finaldx'),
        other_diagnosis:         ctxt('f_otherdx'),
        principal_operation:     ctxt('f_principalop'),
        disposition:             dispMap[dispId] ?? '',
        results:                 resMap[resId] ?? '',
    };
}

async function admSaveRecord() {
    const btn = document.getElementById('btnSaveAdm');
    btn.disabled = true;
    btn.textContent = 'Saving…';
    try {
        const res = await fetch('{{ route("forms.adm-record.save", ["visit" => $visit->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(collectAdmData()),
        });
        const json = await res.json();
        if (json.success) {
            showAdmToast('✔ Admission Record saved successfully.');
            btn.textContent = '✔ Saved';
            btn.disabled = false;
        } else {
            showAdmToast('⚠ Save failed: ' + (json.message ?? 'Unknown error'), true);
            btn.textContent = '💾 Save Record';
            btn.disabled = false;
        }
    } catch (e) {
        showAdmToast('⚠ Network error — check connection.', true);
        btn.textContent = '💾 Save Record';
        btn.disabled = false;
    }
}

function showAdmToast(msg, isError) {
    const t = document.getElementById('admToast');
    t.textContent = msg;
    t.className = isError ? 'error' : '';
    t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 4500);
}

document.addEventListener('DOMContentLoaded', function () {
    const bd = document.getElementById('f_bdate');
    if (bd && bd.value) calcAge(bd.value);
});
</script>

</x-filament-panels::page>