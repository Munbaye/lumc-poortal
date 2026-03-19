{{--
    Clinical Laboratory Request Form  (LAB-001-1)
    Path : resources/views/forms/lab-request-form.blade.php
    Route: GET  /forms/lab-request/{visit}  → labRequest()
           POST /forms/lab-request/{visit}  → labRequestStore()

    Changes vs previous version:
      - Auto-generated $requestNo (LAB-YYYY-NNNNN) pre-filled in Receipt No. field
      - "Requesting Physician" moved to TOP (before the test grid)
      - "Medical Technologist" signature removed
      - "Submit Request" button saves selected tests + form data via fetch() POST
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lab Request {{ $requestNo }} — LA UNION MEDICAL CENTER</title>
    <style>

        @page { size: 8.5in 14in portrait; margin: 0.45in 0.55in 0.45in 0.55in; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; font-size: 10pt; color: #000; background: #c9c9c9; }

        @media screen {
            body  { padding: 52px 0 56px; }
            .paper { width: 8.5in; min-height: 14in; margin: 0 auto; background: #fff; box-shadow: 0 4px 28px rgba(0,0,0,.28); padding: 0.45in 0.55in; }
        }
        @media print {
            body  { background: #fff; padding: 0; }
            .paper { width: 100%; padding: 0; box-shadow: none; }
            .no-print { display: none !important; }
            input, select { background: transparent !important; outline: none !important; -webkit-print-color-adjust: exact; }
            input[type="radio"] { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .test-item.checked .cb { background: #000 !important; print-color-adjust: exact; }
        }

        /* ── TOOLBAR ──────────────────────────────────────────────── */
        .toolbar { position: fixed; top: 0; left: 0; right: 0; height: 46px; background: #1e3a5f; color: #fff; font-family: 'Segoe UI', system-ui, sans-serif; font-size: 12px; display: flex; align-items: center; padding: 0 22px; gap: 14px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,.35); }
        .toolbar .lbl  { font-size: 13px; font-weight: 700; }
        .toolbar .tag  { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); border-radius: 3px; padding: 2px 9px; font-size: 10px; letter-spacing: .05em; text-transform: uppercase; }
        .toolbar .tag-no { background: rgba(16,185,129,.25); border-color: rgba(16,185,129,.5); font-family: monospace; font-size: 11px; font-weight: 700; }
        .toolbar .hint { opacity: .5; font-size: 11px; }
        .toolbar .spacer { flex: 1; }
        .btn-print  { background: #fff; color: #1e3a5f; border: none; padding: 6px 18px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; }
        .btn-print:hover { background: #dbeafe; }
        .btn-submit { background: #059669; color: #fff; border: none; padding: 6px 18px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; }
        .btn-submit:hover { background: #047857; }
        .btn-submit:disabled { opacity: .6; cursor: not-allowed; }

        /* ── HEADER ──────────────────────────────────────────────── */
        .header { display: flex; align-items: center; gap: 12px; padding-bottom: 7px; border-bottom: 2px solid #000; margin-bottom: 7px; }
        .logo-box { width: 56px; height: 56px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .logo-box img { width: 56px; height: 56px; object-fit: contain; }
        .logo-ph { width: 56px; height: 56px; flex-shrink: 0; border: 1.5px dashed #bbb; display: flex; align-items: center; justify-content: center; font-size: 6.5pt; color: #bbb; text-align: center; line-height: 1.3; }
        .header-center { flex: 1; text-align: center; line-height: 1.25; }
        .header-center .h-name { font-size: 12pt; font-weight: bold; text-transform: uppercase; letter-spacing: .05em; }
        .header-center .h-sub  { font-size: 10pt; font-weight: 600; color: #444; margin-top: 2px; }
        .header-center .h-ref  { font-size: 7.5pt; color: #666; margin-top: 2px; font-family: monospace; }

        /* ── COMMON ──────────────────────────────────────────────── */
        .divider { border: none; border-top: 1px solid #000; margin: 5px 0; }
        .fl { font-size: 7.5pt; text-transform: uppercase; letter-spacing: .04em; color: #555; display: block; margin-bottom: 1px; }
        .fi { width: 100%; border: none; border-bottom: 1px solid #888; outline: none; font-size: 9.5pt; padding: 1px 2px; background: transparent; font-family: inherit; color: #000; }
        .fi:focus { background: #fefce8; border-bottom-color: #1d4ed8; }
        .fg { margin-bottom: 5px; }
        .g4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 8px; }
        .g3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; }

        /* ── REQUEST TYPE RADIOS ─────────────────────────────────── */
        .radio-row { display: flex; align-items: center; gap: 14px; font-size: 9.5pt; font-weight: 600; flex-wrap: wrap; }
        .radio-row label { display: inline-flex; align-items: center; gap: 4px; cursor: pointer; }
        .radio-row input[type="radio"] { width: 12px; height: 12px; accent-color: #000; cursor: pointer; }

        /* ── REQUESTING PHYSICIAN BLOCK ──────────────────────────── */
        .phys-block { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 5px; padding: 8px 12px; margin-bottom: 8px; }
        .phys-block-label { font-size: 8pt; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #374151; margin-bottom: 6px; }
        .sig-line { border-bottom: 1px solid #000; height: 34px; }
        .sig-cap  { font-size: 8pt; text-align: center; font-style: italic; margin-top: 2px; }

        /* ── TEST GRID ──────────────────────────────────────────── */
        .tests-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; margin: 6px 0; }
        .test-section { border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
        .test-sec-head { padding: 3px 7px; font-size: 7.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; display: flex; align-items: center; gap: 5px; }
        .test-sec-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

        .test-item { display: flex; align-items: center; gap: 6px; padding: 2px 7px; cursor: pointer; transition: background .1s; border-radius: 2px; margin: 1px 4px; }
        .test-item:hover { background: #f3f4f6; }
        .test-item.checked { background: #eef2ff; }
        .test-item.checked .cb { background: #4f46e5; border-color: #4f46e5; }
        .test-item.checked .cb::after { content: ''; display: block; width: 5px; height: 3px; border-left: 1.5px solid #fff; border-bottom: 1.5px solid #fff; transform: rotate(-45deg) translate(1px, -1px); }
        .test-item.checked .test-name { color: #3730a3; font-weight: 600; }

        .cb { width: 11px; height: 11px; border: 1.5px solid #9ca3af; border-radius: 2px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: #fff; transition: all .1s; }
        .test-name { font-size: 8pt; color: #374151; line-height: 1.3; }

        .micro-extra { padding: 4px 7px; border-top: 1px solid #e5e7eb; }
        .micro-fi { width: 100%; border: none; border-bottom: 1px solid #bbb; font-size: 8pt; padding: 1px 2px; background: transparent; outline: none; font-family: inherit; }

        /* ── SCREEN TIP ──────────────────────────────────────────── */
        .screen-tip { font-family: 'Segoe UI', system-ui, sans-serif; font-size: 10px; color: #374151; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 5px 12px; margin-bottom: 8px; line-height: 1.6; }
        @media print { .screen-tip { display: none; } }

        /* ── TOAST ───────────────────────────────────────────────── */
        #toast { position: fixed; bottom: 22px; right: 22px; background: #059669; color: #fff; padding: 12px 22px; border-radius: 8px; font-family: 'Segoe UI', system-ui, sans-serif; font-size: 13px; font-weight: 600; box-shadow: 0 4px 16px rgba(0,0,0,.25); display: none; z-index: 99999; max-width: 380px; }
        #toast.error { background: #dc2626; }

    </style>
</head>
<body>

<div id="toast"></div>

{{-- TOOLBAR --}}
<div class="toolbar no-print">
    <span class="lbl">LUMC · Laboratory Request</span>
    <span class="tag">LAB-001-1</span>
    <span class="tag tag-no">{{ $requestNo }}</span>
    @isset($patient)
    <span class="tag" style="background:rgba(16,185,129,.22);border-color:rgba(16,185,129,.45);">{{ $patient->case_no }}</span>
    @endisset
    <span class="hint">Click tests to select · Submit saves to system · Print produces hardcopy</span>
    <span class="spacer"></span>
    <button id="btnSubmit" class="btn-submit" onclick="submitForm()">✔ Submit Request</button>
    &nbsp;
    <button class="btn-print" onclick="window.print()">🖨️ Print / PDF</button>
</div>

<div class="paper">

    <div class="screen-tip no-print">
        💡 <strong>Click test names</strong> to select (highlighted in blue). Click <strong>Submit Request</strong> to save to the system, then <strong>Print / PDF</strong> for the hardcopy.
    </div>

    {{-- HEADER --}}
    <div class="header">
        @if(file_exists(public_path('images/lumc-logo.png')))
            <div class="logo-box"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC"></div>
        @else
            <div class="logo-ph">LUMC<br>Logo</div>
        @endif
        <div class="header-center">
            <div class="h-name">La Union Medical Center</div>
            <div class="h-sub">Clinical Laboratory Request Form</div>
            <div class="h-ref">LAB-001-1 Rev. 1 &nbsp;·&nbsp; Brgy. Nazareno, Agoo, La Union &nbsp;·&nbsp; (072) 607-5541 loc. 117/118</div>
        </div>
        @if(file_exists(public_path('images/province-logo.png')))
            <div class="logo-box"><img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union"></div>
        @else
            <div class="logo-ph">Province<br>Seal</div>
        @endif
    </div>

    {{-- ── PATIENT DEMOGRAPHICS ─────────────────────────────────────── --}}
    <div class="g4" style="margin-bottom:5px;">
        <div class="fg"><span class="fl">Date of Request</span><input type="date" id="f_date" class="fi" value="{{ $today ?? '' }}"></div>
        <div class="fg"><span class="fl">Hospital No. (Case No.)</span><input type="text" id="f_hosp" class="fi" value="{{ $hospitalNo ?? '' }}"></div>
        <div class="fg"><span class="fl">Receipt No.</span><input type="text" id="f_receipt" class="fi" value="{{ $requestNo }}" style="font-family:monospace;font-weight:bold;"></div>
        <div class="fg"><span class="fl">Ward / Service</span><input type="text" id="f_ward" class="fi" value="{{ $ward ?? '' }}"></div>
    </div>

    <div class="g4" style="margin-bottom:5px;">
        <div class="fg"><span class="fl">Surname</span><input type="text" id="f_family" class="fi" value="{{ $familyName ?? '' }}"></div>
        <div class="fg"><span class="fl">First Name</span><input type="text" id="f_first"  class="fi" value="{{ $firstName ?? '' }}"></div>
        <div class="fg"><span class="fl">Middle Name</span><input type="text" id="f_middle" class="fi" value="{{ $middleName ?? '' }}"></div>
        <div class="fg"><span class="fl">Address</span><input type="text" id="f_addr" class="fi" value="{{ $address ?? '' }}"></div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr 2fr;gap:8px;margin-bottom:5px;">
        <div class="fg"><span class="fl">Birth Date</span><input type="date" id="f_dob" class="fi" value="{{ $dateOfBirth ?? '' }}"></div>
        <div class="fg"><span class="fl">Age</span><input type="text" id="f_age" class="fi" value="{{ $age ?? '' }}"></div>
        <div class="fg">
            <span class="fl">Gender</span>
            <select id="f_sex" class="fi" style="border-bottom:1px solid #888;">
                <option value="">—</option>
                <option {{ ($sex ?? '') === 'Male'   ? 'selected':'' }}>Male</option>
                <option {{ ($sex ?? '') === 'Female' ? 'selected':'' }}>Female</option>
            </select>
        </div>
        <div class="fg">
            <span class="fl">Civil Status</span>
            <select id="f_civil" class="fi" style="border-bottom:1px solid #888;">
                <option value="">—</option>
                <option>Single</option><option>Married</option><option>Widowed</option><option>Separated</option>
            </select>
        </div>
        <div class="fg">
            <span class="fl">Request Type</span>
            <div style="display:flex;gap:8px;margin-top:3px;">
                <label style="display:inline-flex;align-items:center;gap:3px;font-size:8.5pt;cursor:pointer;"><input type="radio" name="req_type" value="routine" id="rt_routine" checked style="accent-color:#4f46e5;"> Routine</label>
                <label style="display:inline-flex;align-items:center;gap:3px;font-size:8.5pt;cursor:pointer;"><input type="radio" name="req_type" value="stat"    id="rt_stat"    style="accent-color:#f59e0b;"> STAT</label>
            </div>
        </div>
        <div class="fg"><span class="fl">Clinical Diagnosis</span><input type="text" id="f_diag" class="fi" value="{{ $clinicalDiagnosis ?? '' }}"></div>
    </div>

    <div class="divider"></div>

    {{-- ══════════════════════════════════════════════════════════════════
         REQUESTING PHYSICIAN — moved to the top, before the test grid
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="phys-block">
        <div class="phys-block-label">Requesting Physician</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:end;">
            <div class="fg" style="margin-bottom:0;">
                <span class="fl">Name</span>
                <input type="text" id="f_phys" class="fi"
                       value="{{ $requestingPhysician ?? '' }}"
                       style="font-size:10pt;font-weight:bold;">
            </div>
            <div>
                <div class="sig-line"></div>
                <div class="sig-cap">Signature / PRC No. &amp; Date</div>
            </div>
        </div>
    </div>

    {{-- ── TEST SELECTION ───────────────────────────────────────────── --}}
    <div class="tests-grid">

        {{-- COL 1 --}}
        <div style="display:flex;flex-direction:column;gap:5px;">
            <div class="test-section">
                <div class="test-sec-head" style="background:#dbeafe;color:#1e40af;"><span class="test-sec-dot" style="background:#3b82f6;"></span>Hematology</div>
                <div id="sec-hema"></div>
            </div>
            <div class="test-section">
                <div class="test-sec-head" style="background:#f3f4f6;color:#374151;"><span class="test-sec-dot" style="background:#6b7280;"></span>Blood Typing</div>
                <div class="test-item" onclick="toggle(this)"><div class="cb"></div><span class="test-name">Blood Typing</span></div>
                <div class="test-item" onclick="toggle(this)"><div class="cb"></div><span class="test-name">Crossmatching</span></div>
            </div>
            <div class="test-section">
                <div class="test-sec-head" style="background:#ede9fe;color:#5b21b6;"><span class="test-sec-dot" style="background:#8b5cf6;"></span>Serology</div>
                <div id="sec-serology"></div>
            </div>
        </div>

        {{-- COL 2 --}}
        <div style="display:flex;flex-direction:column;gap:5px;">
            <div class="test-section">
                <div class="test-sec-head" style="background:#dcfce7;color:#166534;"><span class="test-sec-dot" style="background:#22c55e;"></span>Clinical Chemistry</div>
                <div id="sec-chem"></div>
            </div>
            <div class="test-section">
                <div class="test-sec-head" style="background:#fee2e2;color:#991b1b;"><span class="test-sec-dot" style="background:#ef4444;"></span>Lipid Profile</div>
                <div id="sec-lipid"></div>
            </div>
            <div class="test-section">
                <div class="test-sec-head" style="background:#fce7f3;color:#9d174d;"><span class="test-sec-dot" style="background:#ec4899;"></span>Serum Electrolytes</div>
                <div id="sec-electro"></div>
            </div>
            <div class="test-section">
                <div class="test-sec-head" style="background:#e0f2fe;color:#0c4a6e;"><span class="test-sec-dot" style="background:#0ea5e9;"></span>Renal Profile</div>
                <div id="sec-renal"></div>
            </div>
            <div class="test-section">
                <div class="test-sec-head" style="background:#d1fae5;color:#065f46;"><span class="test-sec-dot" style="background:#10b981;"></span>HBT Profile</div>
                <div id="sec-hbt"></div>
            </div>
        </div>

        {{-- COL 3 --}}
        <div style="display:flex;flex-direction:column;gap:5px;">
            <div class="test-section">
                <div class="test-sec-head" style="background:#fef9c3;color:#854d0e;"><span class="test-sec-dot" style="background:#eab308;"></span>Clinical Microscopy</div>
                <div id="sec-micro"></div>
            </div>
            <div class="test-section">
                <div class="test-sec-head" style="background:#ffedd5;color:#9a3412;"><span class="test-sec-dot" style="background:#f97316;"></span>Microbiology</div>
                <div id="sec-mbio"></div>
                <div class="micro-extra">
                    <span style="font-size:7pt;color:#888;text-transform:uppercase;letter-spacing:.04em;">Specimen</span>
                    <input type="text" id="f_specimen" class="micro-fi" placeholder="Specimen type">
                </div>
                <div class="micro-extra">
                    <span style="font-size:7pt;color:#888;text-transform:uppercase;letter-spacing:.04em;">Antibiotics / Duration</span>
                    <input type="text" id="f_antibiotics" class="micro-fi" placeholder="Antibiotic name · duration">
                </div>
            </div>
            <div class="test-section">
                <div class="test-sec-head" style="background:#f3f4f6;color:#374151;"><span class="test-sec-dot" style="background:#9ca3af;"></span>Others (Send-Out)</div>
                <div style="padding:5px 7px;">
                    <textarea id="f_others" style="width:100%;border:1px solid #e5e7eb;border-radius:3px;font-size:8pt;padding:3px 5px;resize:none;height:48px;outline:none;font-family:inherit;" placeholder="Other tests / remarks…"></textarea>
                </div>
            </div>
        </div>

    </div>

    <div class="divider" style="margin:6px 0;"></div>

    {{-- ── FOOTER TIMESTAMPS + PHYSICIAN SIGNATURE ─────────────────── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;margin-bottom:8px;">
        <div class="fg"><span class="fl">Date</span><input type="date" id="f_date2" class="fi" value="{{ $today ?? '' }}"></div>
        <div class="fg"><span class="fl">Request Received</span><input type="time" id="f_received" class="fi"></div>
        <div class="fg"><span class="fl">Specimen Collected By</span><input type="text" id="f_specimen_by" class="fi" placeholder="Name"></div>
        <div class="fg"><span class="fl">Test Done</span><input type="time" id="f_test_done" class="fi"></div>
    </div>

    {{-- Single signature row: Requesting Physician only --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:6px;">
        <div>
            <div class="sig-line" style="margin-top:20px;"></div>
            <div class="sig-cap">Requesting Physician — Signature / PRC No.</div>
        </div>
        <div style="display:flex;flex-direction:column;justify-content:flex-end;">
            <div class="sig-line" style="margin-top:20px;"></div>
            <div class="sig-cap">Verified by (Lab Staff) / Date</div>
        </div>
    </div>

</div>{{-- /.paper --}}

<script>
/* ── Test catalogue ──────────────────────────────────────────────── */
const TESTS = {
    'sec-hema'    : ['Complete Blood Count (CBC)', 'Reticulocyte Count', 'Peripheral Blood Smear', 'Malarial Smear', 'Clotting / Bleeding Time', 'Prothrombin Time (PT-PA)', 'APTT', 'ESR'],
    'sec-serology': ['Dengue NS1 + IgM/IgG (Combo)', 'Typhidot', 'ASTO — Qualitative', 'ASTO — Semi-Quantitative', 'CRP — Qualitative', 'CRP — Semi-Quantitative', 'Rheumatoid Factor — Qualitative', 'HBsAg — Rapid', 'HBsAg — EIA', 'Anti-HCV — Rapid', 'VDRL/RPR — Rapid', 'Referral HIV (HACT)'],
    'sec-chem'    : ['Fasting Blood Sugar', 'Random Blood Sugar', 'OGTT', '2-hr Post-prandial BG', 'HbA1c', 'Uric Acid', 'Amylase', 'LDH'],
    'sec-lipid'   : ['Total Cholesterol', 'Total, HDL & LDL Cholesterol', 'Triglycerides', 'Complete Lipid Profile'],
    'sec-electro' : ['Sodium, Potassium, Chloride', 'Phosphorus', 'Magnesium', 'Calcium — Total', 'Calcium — Ionized'],
    'sec-renal'   : ['BUN', 'Creatinine', 'Creatinine Clearance', 'Sodium, Potassium, Chloride', 'Total Protein', 'Albumin'],
    'sec-hbt'     : ['AST / SGOT', 'ALT / SGPT', 'Alkaline Phosphatase', 'Total Protein', 'Albumin', 'Total Bilirubin', 'Total, Direct & Indirect Bili.', 'PT-PA', 'Troponin-T'],
    'sec-micro'   : ['Routine Urinalysis', 'Urine Ketones', 'Pregnancy Test — Urine', 'Pregnancy Test — Serum', 'Seminal Fluid Analysis', 'Body Fluid Analysis', 'Cell Count / Differential', 'Routine Fecalysis', 'Fecalysis with Concentration', 'Fecal Occult Blood'],
    'sec-mbio'    : ['Gram Stain', 'Acid Fast Stain (AFB)', 'India Ink Stain', 'KOH Preparation', 'Culture and Sensitivity'],
};

/* Render tests into their containers */
Object.entries(TESTS).forEach(([id, tests]) => {
    const c = document.getElementById(id);
    if (!c) return;
    tests.forEach(name => {
        const d = document.createElement('div');
        d.className = 'test-item';
        d.onclick = function() { toggle(this); };
        d.innerHTML = `<div class="cb"></div><span class="test-name">${name}</span>`;
        c.appendChild(d);
    });
});

function toggle(el) { el.classList.toggle('checked'); }

/* ── Collect selected tests ──────────────────────────────────────── */
function getSelectedTests() {
    return Array.from(document.querySelectorAll('.test-item.checked .test-name'))
        .map(el => el.textContent.trim());
}

/* ── Submit to server ────────────────────────────────────────────── */
async function submitForm() {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.textContent = 'Saving…';

    const selectedTests = getSelectedTests();
    if (selectedTests.length === 0) {
        showToast('⚠ Please select at least one test before submitting.', true);
        btn.disabled = false;
        btn.textContent = '✔ Submit Request';
        return;
    }

    const reqType = document.querySelector('input[name="req_type"]:checked')?.value ?? 'routine';

    const payload = {
        request_no:           document.getElementById('f_receipt').value.trim(),
        request_type:         reqType,
        ward:                 document.getElementById('f_ward').value.trim(),
        clinical_diagnosis:   document.getElementById('f_diag').value.trim(),
        requesting_physician: document.getElementById('f_phys').value.trim(),
        tests:                selectedTests,
        specimen:             document.getElementById('f_specimen').value.trim(),
        antibiotics_taken:    document.getElementById('f_antibiotics').value.trim(),
        other_tests:          document.getElementById('f_others').value.trim(),
        date_requested:       document.getElementById('f_date').value,
    };

    try {
        const res = await fetch('{{ route("forms.lab-request.store", ["visit" => $visit->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const json = await res.json();

        if (json.success) {
            showToast('✔ ' + json.message, false);
            btn.textContent = '✔ Saved';
        } else {
            showToast('⚠ Save failed. Please try again.', true);
            btn.disabled = false;
            btn.textContent = '✔ Submit Request';
        }
    } catch (err) {
        showToast('⚠ Network error — check connection.', true);
        btn.disabled = false;
        btn.textContent = '✔ Submit Request';
    }
}

function showToast(msg, isError) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = isError ? 'error' : '';
    t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 4500);
}
</script>

</body>
</html>