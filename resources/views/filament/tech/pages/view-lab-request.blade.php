<x-filament-panels::page>
<style>
/* ══ PAGE ════════════════════════════════════════════════════════════ */
.page-wrap { max-width: 960px; margin: 0 auto; }
.back-link { display:inline-flex; align-items:center; gap:6px; font-size:.82rem; color:#6b7280; background:none; border:none; cursor:pointer; margin-bottom:16px; padding:0; }
.back-link:hover { color:#f97316; }

/* ── Status header bar ──────────────────────────────────────────── */
.req-header { background:linear-gradient(135deg,#7c2d12 0%,#f97316 100%); border-radius:10px; padding:16px 22px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
.req-no-big { font-family:monospace; font-size:1.2rem; font-weight:900; color:#fff; }
.req-patient-big { font-size:1rem; font-weight:800; color:#fff; margin-top:3px; }
.req-case-big { font-family:monospace; font-size:.78rem; color:#fed7aa; margin-top:1px; }
.req-status-pill { padding:5px 16px; border-radius:9999px; font-size:.78rem; font-weight:700; }
.pill-pending    { background:rgba(255,255,255,.2); color:#fff; }
.pill-completed  { background:#16a34a; color:#fff; }
.pill-inprogress { background:#eab308; color:#000; }

/* ══ SECTION HEADERS ═════════════════════════════════════════════════ */
.section-header { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#6b7280; margin-bottom:8px; display:flex; align-items:center; gap:8px; }
.section-header .sh-line { flex:1; border-top:1px solid #e5e7eb; }
.dark .section-header .sh-line { border-top-color:#374151; }

/* ══ PAPER DOCUMENT ══════════════════════════════════════════════════ */
.paper-doc { background:#fff; border:1px solid #d1d5db; border-radius:8px; padding:0.45in 0.55in; margin-bottom:18px; font-family:'Segoe UI', system-ui, sans-serif; font-size:10pt; color:#000; box-shadow:0 2px 8px rgba(0,0,0,.08); }
.pd-header { display:flex; align-items:center; gap:12px; padding-bottom:7px; border-bottom:2px solid #000; margin-bottom:7px; }
.pd-logo { width:52px; height:52px; flex-shrink:0; border:1.5px dashed #bbb; display:flex; align-items:center; justify-content:center; font-size:6.5pt; color:#bbb; text-align:center; line-height:1.3; border-radius:3px; }
.pd-logo img { width:52px; height:52px; object-fit:contain; }
.pd-center { flex:1; text-align:center; line-height:1.25; }
.pd-center .h-name { font-size:12pt; font-weight:bold; text-transform:uppercase; letter-spacing:.05em; }
.pd-center .h-sub  { font-size:10pt; font-weight:600; color:#444; margin-top:2px; }
.pd-center .h-ref  { font-size:7.5pt; color:#666; margin-top:2px; font-family:monospace; }
.pd-g4 { display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:8px; margin-bottom:5px; }
.pd-g6 { display:grid; grid-template-columns:1fr 1fr 1fr 1fr 1fr 2fr; gap:8px; margin-bottom:5px; }
.pd-fg { margin-bottom:0; }
.pd-fl { font-size:7.5pt; text-transform:uppercase; letter-spacing:.04em; color:#777; display:block; margin-bottom:1px; }
.pd-val { font-size:9.5pt; font-weight:600; color:#000; border-bottom:1px solid #bbb; padding:1px 2px; min-height:18px; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pd-phys { background:#f9fafb; border:1px solid #e5e7eb; border-radius:5px; padding:7px 12px; margin-bottom:8px; }
.pd-phys-label { font-size:8pt; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#374151; margin-bottom:5px; }
.pd-phys-name { font-size:10pt; font-weight:bold; color:#000; border-bottom:1px solid #000; padding-bottom:2px; min-height:22px; }
.pd-divider { border:none; border-top:1px solid #000; margin:5px 0; }
.pd-tests-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:6px; margin:6px 0; }
.pd-test-section { border:1px solid #ddd; border-radius:4px; overflow:hidden; }
.pd-test-head { padding:3px 7px; font-size:7.5pt; font-weight:700; text-transform:uppercase; letter-spacing:.05em; display:flex; align-items:center; gap:5px; }
.pd-test-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
.pd-test-item { display:flex; align-items:center; gap:6px; padding:2px 7px; margin:1px 4px; border-radius:2px; }
.pd-test-item.checked { background:#eef2ff; }
.pd-cb { width:11px; height:11px; border:1.5px solid #bbb; border-radius:2px; flex-shrink:0; display:flex; align-items:center; justify-content:center; background:#fff; }
.pd-test-item.checked .pd-cb { background:#4f46e5; border-color:#4f46e5; }
.pd-test-item.checked .pd-cb::after { content:''; display:block; width:5px; height:3px; border-left:1.5px solid #fff; border-bottom:1.5px solid #fff; transform:rotate(-45deg) translate(1px,-1px); }
.pd-test-name { font-size:8pt; color:#374151; line-height:1.3; }
.pd-test-item.checked .pd-test-name { color:#3730a3; font-weight:600; }
.pd-micro-extra { padding:4px 7px; border-top:1px solid #e5e7eb; }
.pd-micro-val { font-size:8pt; color:#374151; border-bottom:1px dashed #bbb; min-height:16px; padding:1px 2px; }
.pd-footer5 { display:grid; grid-template-columns:1fr 1fr 1fr 1fr 1fr; gap:8px; margin-top:8px; }

/* ══ COMPLETED RESULTS ═══════════════════════════════════════════════ */
.result-box { background:#f0fdf4; border:1.5px solid #22c55e; border-radius:8px; padding:18px 20px; margin-bottom:16px; }
.result-box-title { font-size:.82rem; font-weight:700; color:#15803d; margin-bottom:12px; display:flex; align-items:center; gap:7px; }
.result-file-link { display:inline-flex; align-items:center; gap:5px; color:#1d4ed8; font-size:.82rem; font-weight:600; text-decoration:none; padding:5px 12px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; }
.result-file-link:hover { background:#dbeafe; }
.result-notes-box { background:#fff; border:1px solid #d1fae5; border-radius:6px; padding:10px 14px; margin-top:12px; }
.result-notes-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#059669; margin-bottom:5px; }
.result-notes-text { font-size:.875rem; color:#374151; line-height:1.6; }

/* ══ TECH TIMELINE ═══════════════════════════════════════════════════ */
.tech-section { background:#fff; border:2px solid #f97316; border-radius:10px; padding:24px 26px; }
.dark .tech-section { background:#1f2937; border-color:#ea580c; }
.tech-title { font-size:1rem; font-weight:800; color:#f97316; margin-bottom:20px; padding-bottom:10px; border-bottom:2px solid #fff7ed; display:flex; align-items:center; gap:8px; }
.dark .tech-title { border-bottom-color:rgba(249,115,22,.2); }

.step-row { display:flex; align-items:flex-start; gap:16px; margin-bottom:20px; }
.step-num { width:32px; height:32px; border-radius:50%; font-size:.8rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
.step-num.s-done    { background:#059669; color:#fff; }
.step-num.s-active  { background:#f97316; color:#fff; }
.step-num.s-waiting { background:#e5e7eb; color:#9ca3af; }
.dark .step-num.s-waiting { background:#374151; }
.step-body { flex:1; }
.step-title { font-size:.88rem; font-weight:700; color:#111827; margin-bottom:6px; }
.dark .step-title { color:#f3f4f6; }
.step-ts { display:inline-block; background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; font-size:.72rem; font-weight:700; padding:2px 10px; border-radius:9999px; margin-left:8px; white-space:nowrap; }
.step-connector { width:2px; height:16px; background:#e5e7eb; margin-left:15px; margin-bottom:4px; }
.dark .step-connector { background:#374151; }
.step-connector.done { background:#059669; }

.btn-step { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:7px; padding:9px 20px; font-size:.85rem; font-weight:700; cursor:pointer; transition:background .15s; }
.btn-orange { background:#f97316; color:#fff; } .btn-orange:hover { background:#ea580c; }
.btn-blue   { background:#3b82f6; color:#fff; } .btn-blue:hover   { background:#2563eb; }
.btn-step:disabled { opacity:.45; cursor:not-allowed; }

.tf-label { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; display:block; margin-bottom:5px; }
.tf-input { border:1px solid #e5e7eb; border-radius:6px; padding:8px 12px; font-size:.875rem; background:#fff; color:#111827; outline:none; font-family:inherit; }
.dark .tf-input { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.tf-input:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,.1); }
.tf-input:disabled { background:#f9fafb; color:#9ca3af; }
.dark .tf-input:disabled { background:#1f2937; }
.tf-area { width:100%; border:1px solid #e5e7eb; border-radius:6px; padding:9px 12px; font-size:.875rem; background:#fff; color:#111827; outline:none; font-family:inherit; resize:vertical; }
.dark .tf-area { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.tf-area:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,.1); }

/* file upload */
.file-queue { margin-top:8px; }
.file-item { display:flex; align-items:center; gap:10px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:8px 12px; margin-bottom:6px; }
.dark .file-item { background:#374151; border-color:#4b5563; }
.file-item-name { flex:1; font-size:.82rem; color:#374151; font-weight:600; }
.dark .file-item-name { color:#e5e7eb; }
.file-item-size { font-size:.72rem; color:#9ca3af; }
.btn-rm { background:none; border:1px solid #fca5a5; color:#dc2626; border-radius:5px; padding:3px 9px; font-size:.75rem; cursor:pointer; flex-shrink:0; }
.btn-rm:hover { background:#fee2e2; }
.drop-zone { border:2px dashed #e5e7eb; border-radius:8px; padding:22px 18px; text-align:center; transition:border-color .15s; }
.drop-zone.enabled { cursor:pointer; } .drop-zone.enabled:hover { border-color:#f97316; background:#fff7ed; }
.drop-zone.locked { opacity:.45; background:#f9fafb; cursor:not-allowed; }

/* complete button */
.btn-complete { background:#059669; color:#fff; border:none; border-radius:7px; padding:12px 32px; font-size:.95rem; font-weight:800; cursor:pointer; display:inline-flex; align-items:center; gap:8px; margin-top:20px; }
.btn-complete:hover:not(:disabled) { background:#047857; }
.btn-complete:disabled { opacity:.45; cursor:not-allowed; }

/* lock notice */
.lock-notice { display:inline-flex; align-items:center; gap:6px; font-size:.78rem; color:#9ca3af; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:6px 12px; margin-top:6px; }
.dark .lock-notice { background:#374151; border-color:#4b5563; }
</style>

@if($labRequest)
@php
    $patient     = $labRequest->visit?->patient ?? $labRequest->patient;
    $isCompleted = $labRequest->isCompleted();
    $uploads     = $labRequest->results()->with('uploadedBy')->latest()->get();

    // Timeline gate — each step requires previous to be done
    $s1done = !!$labRequest->request_received_at;   // Received
    $s3done = !!$labRequest->test_started_at;        // Test Started

    // File upload + complete button gate: must have received first
    $canUpload   = $s1done;
    $canComplete = $s1done && !empty($resultFiles);

    // Full test catalog
    $col1 = [
        ['style'=>'background:#dbeafe;color:#1e40af','dot'=>'#3b82f6','label'=>'Hematology',
         'tests'=>['Complete Blood Count (CBC)','Reticulocyte Count','Peripheral Blood Smear','Malarial Smear','Clotting / Bleeding Time','Prothrombin Time (PT-PA)','APTT','ESR']],
        ['style'=>'background:#f3f4f6;color:#374151','dot'=>'#6b7280','label'=>'Blood Typing',
         'tests'=>['Blood Typing','Crossmatching']],
        ['style'=>'background:#ede9fe;color:#5b21b6','dot'=>'#8b5cf6','label'=>'Serology',
         'tests'=>['Dengue NS1 + IgM/IgG (Combo)','Typhidot','ASTO — Qualitative','ASTO — Semi-Quantitative','CRP — Qualitative','CRP — Semi-Quantitative','Rheumatoid Factor — Qualitative','HBsAg — Rapid','HBsAg — EIA','Anti-HCV — Rapid','VDRL/RPR — Rapid','Referral HIV (HACT)']],
    ];
    $col2 = [
        ['style'=>'background:#dcfce7;color:#166534','dot'=>'#22c55e','label'=>'Clinical Chemistry',
         'tests'=>['Fasting Blood Sugar','Random Blood Sugar','OGTT','2-hr Post-prandial BG','HbA1c','Uric Acid','Amylase','LDH']],
        ['style'=>'background:#fee2e2;color:#991b1b','dot'=>'#ef4444','label'=>'Lipid Profile',
         'tests'=>['Total Cholesterol','Total, HDL & LDL Cholesterol','Triglycerides','Complete Lipid Profile']],
        ['style'=>'background:#fce7f3;color:#9d174d','dot'=>'#ec4899','label'=>'Serum Electrolytes',
         'tests'=>['Sodium, Potassium, Chloride','Phosphorus','Magnesium','Calcium — Total','Calcium — Ionized']],
        ['style'=>'background:#e0f2fe;color:#0c4a6e','dot'=>'#0ea5e9','label'=>'Renal Profile',
         'tests'=>['BUN','Creatinine','Creatinine Clearance','Sodium, Potassium, Chloride','Total Protein','Albumin']],
        ['style'=>'background:#d1fae5;color:#065f46','dot'=>'#10b981','label'=>'HBT Profile',
         'tests'=>['AST / SGOT','ALT / SGPT','Alkaline Phosphatase','Total Protein','Albumin','Total Bilirubin','Total, Direct & Indirect Bili.','PT-PA','Troponin-T']],
    ];
    $col3 = [
        ['id'=>'micro','style'=>'background:#fef9c3;color:#854d0e','dot'=>'#eab308','label'=>'Clinical Microscopy',
         'tests'=>['Routine Urinalysis','Urine Ketones','Pregnancy Test — Urine','Pregnancy Test — Serum','Seminal Fluid Analysis','Body Fluid Analysis','Cell Count / Differential','Routine Fecalysis','Fecalysis with Concentration','Fecal Occult Blood']],
        ['id'=>'mbio','style'=>'background:#ffedd5;color:#9a3412','dot'=>'#f97316','label'=>'Microbiology',
         'tests'=>['Gram Stain','Acid Fast Stain (AFB)','India Ink Stain','KOH Preparation','Culture and Sensitivity']],
    ];
    $selectedTests = $labRequest->tests ?? [];

    // Collect all tech notes (non-null, unique)
    $allNotes = $uploads->pluck('notes')->filter()->unique()->values();
@endphp

<div class="page-wrap">

<button wire:click="goBack" type="button" class="back-link">← Back to Dashboard</button>

{{-- ── STATUS BAR ──────────────────────────────────────────────────── --}}
<div class="req-header">
    <div>
        <p class="req-no-big">{{ $labRequest->request_no }}</p>
        <p class="req-patient-big">{{ $patient?->full_name ?? '—' }}</p>
        <p class="req-case-big">{{ $patient?->case_no ?? '' }} · {{ $patient?->age_display ?? '' }} · {{ $patient?->sex ?? '' }}</p>
    </div>
    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
        <span class="req-status-pill pill-{{ str_replace('_','', $labRequest->status) }}">{{ $labRequest->status_label }}</span>
        @if($labRequest->request_type === 'stat')
        <span style="background:#dc2626;color:#fff;padding:3px 10px;border-radius:9999px;font-size:.72rem;font-weight:800;">⚡ STAT</span>
        @endif
    </div>
</div>

{{-- ══ SECTION 1: CLINICAL INFO — PAPER FORM (READ-ONLY) ══════════ --}}
<div class="section-header">
    <span>🔒 Clinical Info — Original Request (Read-only)</span>
    <div class="sh-line"></div>
</div>

<div class="paper-doc">

    <div class="pd-header">
        @if(file_exists(public_path('images/lumc-logo.png')))
            <div class="pd-logo"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC"></div>
        @else
            <div class="pd-logo">LUMC<br>Logo</div>
        @endif
        <div class="pd-center">
            <div class="h-name">La Union Medical Center</div>
            <div class="h-sub">Clinical Laboratory Request Form</div>
            <div class="h-ref">LAB-001-1 Rev. 1 &nbsp;·&nbsp; Brgy. Nazareno, Agoo, La Union &nbsp;·&nbsp; (072) 607-5541 loc. 117/118</div>
        </div>
        @if(file_exists(public_path('images/province-logo.png')))
            <div class="pd-logo"><img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union"></div>
        @else
            <div class="pd-logo">Province<br>Seal</div>
        @endif
    </div>

    <div class="pd-g4">
        <div class="pd-fg"><span class="pd-fl">Date of Request</span><span class="pd-val">{{ $labRequest->date_requested?->format('Y-m-d') ?? $labRequest->created_at->format('Y-m-d') }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Hospital No. (Case No.)</span><span class="pd-val">{{ $patient?->case_no ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Receipt No.</span><span class="pd-val" style="font-family:monospace;font-weight:bold;">{{ $labRequest->request_no }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Ward / Service</span><span class="pd-val">{{ $labRequest->ward ?? '—' }}</span></div>
    </div>
    <div class="pd-g4">
        <div class="pd-fg"><span class="pd-fl">Surname</span><span class="pd-val">{{ strtoupper($patient?->family_name ?? '—') }}</span></div>
        <div class="pd-fg"><span class="pd-fl">First Name</span><span class="pd-val">{{ strtoupper($patient?->first_name ?? '—') }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Middle Name</span><span class="pd-val">{{ strtoupper($patient?->middle_name ?? '—') }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Address</span><span class="pd-val">{{ $patient?->address ?? '—' }}</span></div>
    </div>
    <div class="pd-g6">
        <div class="pd-fg"><span class="pd-fl">Birth Date</span><span class="pd-val">{{ $patient?->birthday?->format('Y-m-d') ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Age</span><span class="pd-val">{{ $patient?->age_display ?? $patient?->current_age ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Sex</span><span class="pd-val">{{ $patient?->sex ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Civil Status</span><span class="pd-val">—</span></div>
        <div class="pd-fg"><span class="pd-fl">Request Type</span><span class="pd-val" style="font-weight:bold;color:{{ $labRequest->request_type === 'stat' ? '#dc2626' : '#000' }};">{{ strtoupper($labRequest->request_type ?? 'ROUTINE') }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Clinical Diagnosis</span><span class="pd-val">{{ $labRequest->clinical_diagnosis ?? '—' }}</span></div>
    </div>

    <hr class="pd-divider">

    <div class="pd-phys">
        <div class="pd-phys-label">Requesting Physician</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div><span class="pd-fl">Name</span><div class="pd-phys-name">{{ $labRequest->requesting_physician ?? ($labRequest->doctor ? 'Dr. '.$labRequest->doctor->name : '—') }}</div></div>
            <div style="display:flex;flex-direction:column;justify-content:flex-end;">
                <div style="border-bottom:1px solid #000;height:28px;"></div>
                <div style="font-size:8pt;text-align:center;font-style:italic;margin-top:2px;">Signature / PRC No. &amp; Date</div>
            </div>
        </div>
    </div>

    {{-- Test grid --}}
    <div class="pd-tests-grid">
        <div style="display:flex;flex-direction:column;gap:5px;">
            @foreach($col1 as $sec)
            <div class="pd-test-section">
                <div class="pd-test-head" style="{{ $sec['style'] }};"><span class="pd-test-dot" style="background:{{ $sec['dot'] }};"></span>{{ $sec['label'] }}</div>
                @foreach($sec['tests'] as $t)
                <div class="pd-test-item {{ in_array($t, $selectedTests) ? 'checked' : '' }}"><div class="pd-cb"></div><span class="pd-test-name">{{ $t }}</span></div>
                @endforeach
            </div>
            @endforeach
        </div>
        <div style="display:flex;flex-direction:column;gap:5px;">
            @foreach($col2 as $sec)
            <div class="pd-test-section">
                <div class="pd-test-head" style="{{ $sec['style'] }};"><span class="pd-test-dot" style="background:{{ $sec['dot'] }};"></span>{{ $sec['label'] }}</div>
                @foreach($sec['tests'] as $t)
                <div class="pd-test-item {{ in_array($t, $selectedTests) ? 'checked' : '' }}"><div class="pd-cb"></div><span class="pd-test-name">{{ $t }}</span></div>
                @endforeach
            </div>
            @endforeach
        </div>
        <div style="display:flex;flex-direction:column;gap:5px;">
            @foreach($col3 as $sec)
            <div class="pd-test-section">
                <div class="pd-test-head" style="{{ $sec['style'] }};"><span class="pd-test-dot" style="background:{{ $sec['dot'] }};"></span>{{ $sec['label'] }}</div>
                @foreach($sec['tests'] as $t)
                <div class="pd-test-item {{ in_array($t, $selectedTests) ? 'checked' : '' }}"><div class="pd-cb"></div><span class="pd-test-name">{{ $t }}</span></div>
                @endforeach
                @if(($sec['id'] ?? '') === 'mbio')
                <div class="pd-micro-extra"><span style="font-size:7pt;color:#888;text-transform:uppercase;letter-spacing:.04em;">Specimen</span><div class="pd-micro-val">{{ $labRequest->specimen ?? '' }}</div></div>
                <div class="pd-micro-extra"><span style="font-size:7pt;color:#888;text-transform:uppercase;letter-spacing:.04em;">Antibiotics / Duration</span><div class="pd-micro-val">{{ $labRequest->antibiotics_taken ?? '' }}</div></div>
                @endif
            </div>
            @endforeach
            @if($labRequest->other_tests)
            <div class="pd-test-section">
                <div class="pd-test-head" style="background:#f3f4f6;color:#374151;"><span class="pd-test-dot" style="background:#9ca3af;"></span>Others (Send-Out)</div>
                <div style="padding:5px 7px;font-size:8pt;color:#374151;">{{ $labRequest->other_tests }}</div>
            </div>
            @endif
        </div>
    </div>

    <hr class="pd-divider" style="margin:6px 0;">

    {{-- Footer timestamps (read-only, updated as tech acts) --}}
    <div class="pd-footer5">
        <div class="pd-fg"><span class="pd-fl">Date</span><span class="pd-val">{{ $labRequest->date_requested?->format('Y-m-d') ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Request Received</span><span class="pd-val">{{ $labRequest->request_received_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Specimen Collected</span><span class="pd-val">{{ $labRequest->specimen_collected ?? '' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Test Started</span><span class="pd-val">{{ $labRequest->test_started_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Test Done</span><span class="pd-val">{{ $labRequest->test_done_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:10px;">
        <div><div style="border-bottom:1px solid #000;height:28px;margin-top:16px;"></div><div style="font-size:8pt;text-align:center;font-style:italic;margin-top:2px;">Requesting Physician — Signature / PRC No.</div></div>
        <div><div style="border-bottom:1px solid #000;height:28px;margin-top:16px;"></div><div style="font-size:8pt;text-align:center;font-style:italic;margin-top:2px;">Verified by (Lab Staff) / Date</div></div>
    </div>

</div>{{-- /.paper-doc --}}

{{-- ══ COMPLETED RESULTS VIEW ══════════════════════════════════════════ --}}
@if($uploads->isNotEmpty())
<div class="result-box">
    <p class="result-box-title">✅ {{ $uploads->count() }} Result File(s) — {{ $labRequest->request_no }}</p>

    {{-- Single "Uploaded by" line using the most recent uploader --}}
    @php
        $uploader = $uploads->first()?->uploadedBy?->name ?? '—';
    @endphp
    <p style="font-size:0.82rem; color:#f97316; font-weight:600; margin: -4px 0 12px 2px;">
        Uploaded by {{ $uploader }}
    </p>

    {{-- File list — cleaner, no per-file uploader/date --}}
    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:{{ $allNotes->isNotEmpty() ? '12px' : '0' }};">
        @foreach($uploads as $u)
        <div>
            <a href="{{ $u->file_url }}" target="_blank" class="result-file-link">
                {{ $u->file_type_icon }} {{ $u->file_name }}
                <span style="font-size:.7rem; font-weight:400; color:#6b7280;">
                    ({{ $u->file_size_human }})
                </span>
            </a>
            {{-- Removed the <p> with "Uploaded by … · date" --}}
        </div>
        @endforeach
    </div>

    {{-- Tech Notes block (unchanged) --}}
    @if($allNotes->isNotEmpty())
    <div class="result-notes-box">
        <p class="result-notes-label">📝 Tech Notes</p>
        @foreach($allNotes as $note)
        <p class="result-notes-text">{{ $note }}</p>
        @endforeach
    </div>
    @endif
</div>
@endif

{{-- ══ TECH TIMELINE — only shown when not yet completed ══════════════ --}}
@if(!$isCompleted)
<div class="section-header" style="margin-top:4px;">
    <span>🔧 Tech Timeline / Status</span>
    <div class="sh-line"></div>
</div>

<div class="tech-section">
    <p class="tech-title">🔧 Tech Timeline / Status</p>

    {{-- ── STEP 1: Mark as Received ──────────────────────────────── --}}
    <div class="step-row">
        <div class="step-num {{ $s1done ? 's-done' : 's-active' }}">{{ $s1done ? '✓' : '1' }}</div>
        <div class="step-body">
            <p class="step-title">
                Mark as Received
                @if($labRequest->request_received_at)
                <span class="step-ts">{{ $labRequest->request_received_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</span>
                @endif
            </p>
            @if(!$labRequest->request_received_at)
            <button type="button" wire:click="markReceived" wire:loading.attr="disabled" class="btn-step btn-orange">
                📥 Mark as Received
            </button>
            @else
            <span style="font-size:.78rem;color:#059669;font-weight:600;">✅ Received</span>
            @endif
        </div>
    </div>

    <div class="step-connector {{ $s1done ? 'done' : '' }}"></div>

    {{-- ── STEP 2: Specimen Collected ────────────────────────────── --}}
    <div class="step-row">
        <div class="step-num {{ $labRequest->specimen_collected ? 's-done' : ($s1done ? 's-active' : 's-waiting') }}">{{ $labRequest->specimen_collected ? '✓' : '2' }}</div>
        <div class="step-body">
            <p class="step-title">Specimen Collected</p>
            <div style="display:flex;gap:10px;align-items:center;">
                <input type="text"
                       wire:model="specimenCollected"
                       class="tf-input"
                       style="width:280px;"
                       placeholder="e.g. venous blood, urine, stool"
                       {{ !$s1done ? 'disabled' : '' }}>
                <button type="button" wire:click="saveSpecimen" wire:loading.attr="disabled"
                        class="btn-step btn-orange" style="padding:8px 16px;"
                        {{ !$s1done ? 'disabled' : '' }}>
                    Save
                </button>
            </div>
            @if(!$s1done)
            <p class="lock-notice">🔒 Complete Step 1 first</p>
            @elseif($labRequest->specimen_collected)
            <p style="font-size:.75rem;color:#059669;margin-top:4px;font-weight:600;">✔ Saved: {{ $labRequest->specimen_collected }}</p>
            @endif
        </div>
    </div>

    <div class="step-connector {{ $s1done ? 'done' : '' }}"></div>

    {{-- ── STEP 3: Test Started ───────────────────────────────────── --}}
    <div class="step-row">
        <div class="step-num {{ $s3done ? 's-done' : ($s1done ? 's-active' : 's-waiting') }}">{{ $s3done ? '✓' : '3' }}</div>
        <div class="step-body">
            <p class="step-title">
                Test Started
                @if($labRequest->test_started_at)
                <span class="step-ts">{{ $labRequest->test_started_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</span>
                @endif
            </p>
            @if(!$labRequest->test_started_at)
            <button type="button" wire:click="markTestStarted" wire:loading.attr="disabled"
                    class="btn-step btn-blue" {{ !$s1done ? 'disabled' : '' }}>
                ▶ Mark Test Started
            </button>
            @if(!$s1done)<p class="lock-notice">🔒 Complete Step 1 first</p>@endif
            @else
            <span style="font-size:.78rem;color:#059669;font-weight:600;">✅ Test started</span>
            @endif
        </div>
    </div>

    <div class="step-connector {{ $s1done ? 'done' : '' }}"></div>

    {{-- ── STEP 4: Upload Result Files ───────────────────────────── --}}
    <div class="step-row">
        <div class="step-num {{ !empty($resultFiles) && $canUpload ? 's-active' : 's-waiting' }}">4</div>
        <div class="step-body">
            <p class="step-title">Result Files <span style="color:#dc2626;">*</span></p>

            @if(!$canUpload)
            <p class="lock-notice">🔒 Complete Step 1 (Mark as Received) before uploading files</p>
            @else

            @if(!empty($resultFiles))
            <div class="file-queue">
                @foreach($resultFiles as $i => $f)
                <div class="file-item" wire:key="lf-{{ $i }}">
                    <span style="font-size:1.1rem;">📄</span>
                    <span class="file-item-name">{{ $f->getClientOriginalName() }}</span>
                    <span class="file-item-size">{{ round($f->getSize() / 1024, 1) }} KB</span>
                    <button type="button" wire:click="removeFile({{ $i }})" class="btn-rm">✕</button>
                </div>
                @endforeach
            </div>
            @endif

            <div class="drop-zone enabled" style="margin-top:{{ empty($resultFiles) ? '0' : '8px' }};">
                <input type="file" wire:model="resultFiles" id="labFiles"
                       accept=".pdf,.jpg,.jpeg,.png,.webp" multiple style="display:none;">
                <label for="labFiles" style="cursor:pointer;display:block;">
                    <p style="font-size:1.3rem;margin-bottom:4px;">📄</p>
                    <p style="font-size:.85rem;font-weight:700;color:#374151;">{{ empty($resultFiles) ? 'Drop files here or click to browse' : '+ Add more files' }}</p>
                    <p style="font-size:.75rem;color:#9ca3af;margin-top:3px;">PDF · JPG · PNG · WebP · max 20 MB each</p>
                </label>
            </div>
            <div wire:loading wire:target="resultFiles" style="font-size:.78rem;color:#9ca3af;margin-top:5px;">Uploading…</div>
            @error('resultFiles')   <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
            @error('resultFiles.*') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror

            <div style="margin-top:12px;">
                <label class="tf-label">Tech Notes (optional)</label>
                <textarea wire:model="notes" rows="2" class="tf-area" placeholder="Any notes for the doctor or nurse…"></textarea>
            </div>

            @endif
        </div>
    </div>

    {{-- ── COMPLETE BUTTON ────────────────────────────────────────── --}}
    <button wire:click="saveResult"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50"
            type="button"
            class="btn-complete"
            {{ !$canComplete ? 'disabled' : '' }}>
        <span wire:loading.remove wire:target="saveResult">✅ Complete Request &amp; Upload Results</span>
        <span wire:loading wire:target="saveResult">Saving…</span>
    </button>
    @if(!$canComplete)
    <p class="lock-notice" style="margin-top:8px;">
        🔒
        @if(!$s1done) Mark as Received first
        @elseif(empty($resultFiles)) Add at least one result file
        @endif
    </p>
    @else
    <p style="font-size:.72rem;color:#9ca3af;margin-top:8px;">Test Done time will be recorded automatically when you complete.</p>
    @endif

</div>
@endif

</div>{{-- /.page-wrap --}}
@endif

</x-filament-panels::page>