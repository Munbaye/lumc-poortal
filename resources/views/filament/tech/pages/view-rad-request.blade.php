<x-filament-panels::page>
<style>
/* ══ PAGE ════════════════════════════════════════════════════════════ */
.page-wrap { max-width: 960px; margin: 0 auto; }
.back-link { display:inline-flex; align-items:center; gap:6px; font-size:.82rem; color:#6b7280; background:none; border:none; cursor:pointer; margin-bottom:16px; padding:0; }
.back-link:hover { color:#6d28d9; }

.req-header { background:linear-gradient(135deg,#3b0764 0%,#6d28d9 100%); border-radius:10px; padding:16px 22px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
.req-no-big { font-family:monospace; font-size:1.2rem; font-weight:900; color:#fff; }
.req-patient-big { font-size:1rem; font-weight:800; color:#fff; margin-top:3px; }
.req-case-big { font-family:monospace; font-size:.78rem; color:#c4b5fd; margin-top:1px; }
.modality-pill { background:rgba(255,255,255,.2); color:#fff; padding:4px 14px; border-radius:9999px; font-size:.82rem; font-weight:700; }
.req-status-pill { padding:5px 16px; border-radius:9999px; font-size:.78rem; font-weight:700; }
.pill-pending    { background:rgba(255,255,255,.2); color:#fff; }
.pill-completed  { background:#16a34a; color:#fff; }
.pill-inprogress { background:#eab308; color:#000; }

.section-header { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#6b7280; margin-bottom:8px; display:flex; align-items:center; gap:8px; }
.section-header .sh-line { flex:1; border-top:1px solid #e5e7eb; }
.dark .section-header .sh-line { border-top-color:#374151; }

/* ══ PAPER DOCUMENT ══════════════════════════════════════════════════ */
.paper-doc { background:#fff; border:1px solid #d1d5db; border-radius:8px; padding:0.55in 0.65in; margin-bottom:18px; font-family:'Times New Roman', Times, serif; font-size:11pt; color:#000; box-shadow:0 2px 8px rgba(0,0,0,.08); }
.pd-header { display:flex; align-items:center; gap:14px; padding-bottom:8px; border-bottom:2.5px solid #000; }
.pd-logo { width:60px; height:60px; flex-shrink:0; border:1.5px dashed #bbb; display:flex; align-items:center; justify-content:center; font-size:7pt; color:#bbb; text-align:center; line-height:1.3; border-radius:3px; }
.pd-logo img { width:60px; height:60px; object-fit:contain; }
.pd-center { flex:1; text-align:center; line-height:1.3; }
.pd-center .h-name { font-size:14pt; font-weight:bold; text-transform:uppercase; letter-spacing:.06em; }
.pd-center .h-sub  { font-size:10pt; font-weight:bold; margin-top:4px; }
.pd-center .h-addr { font-size:8pt; color:#444; margin-top:2px; }

.pd-g4 { display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:10px; margin-bottom:7px; }
.pd-g3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:7px; }
.pd-g2x { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:10px; margin-bottom:8px; }
.pd-fg { margin-bottom:0; }
.pd-fl { font-size:8pt; text-transform:uppercase; letter-spacing:.05em; color:#777; display:block; margin-bottom:1px; font-family:'Segoe UI',system-ui,sans-serif; }
.pd-val { font-size:10.5pt; font-weight:500; color:#000; border-bottom:1px solid #bbb; padding:2px 3px; min-height:20px; display:block; }
.pd-area-val { background:#f9f9f9; border:1px solid #ccc; padding:5px 7px; font-size:10.5pt; line-height:1.65; min-height:50px; color:#000; white-space:pre-wrap; }
.pd-divider { border:none; border-top:1px solid #000; margin:8px 0; }
.pd-divider-thick { border:none; border-top:2px solid #000; margin:8px 0; }
.pd-sec-label { font-size:8.5pt; font-weight:bold; text-transform:uppercase; letter-spacing:.06em; color:#444; margin-bottom:5px; font-family:'Segoe UI',system-ui,sans-serif; }
.pd-footer4 { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-top:8px; }
.pd-interp { border:1.5px solid #000; padding:8px 10px; min-height:140px; font-size:10.5pt; line-height:1.75; color:#000; white-space:pre-wrap; }
.pd-sig-line { border-bottom:1px solid #000; height:32px; margin-top:20px; }
.pd-sig-cap { font-size:8.5pt; text-align:center; font-style:italic; margin-top:2px; font-family:'Segoe UI',system-ui,sans-serif; }

/* modality / source (read-only visual) */
.pd-modality-row { display:flex; align-items:center; justify-content:space-around; border:1.5px solid #000; padding:8px 24px; margin-bottom:8px; }
.pd-modality-opt { display:inline-flex; align-items:center; gap:7px; font-size:12pt; font-weight:bold; }
.pd-radio { width:14px; height:14px; border-radius:50%; border:2px solid #000; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pd-radio.on { background:#000; }
.pd-radio.on::after { content:''; width:6px; height:6px; background:#fff; border-radius:50%; }
.pd-source-row { display:flex; align-items:center; justify-content:space-around; border:1px solid #ddd; background:#fafafa; padding:6px 10px; margin-bottom:8px; font-family:'Segoe UI',system-ui,sans-serif; }
.pd-source-opt { display:inline-flex; align-items:center; gap:5px; font-size:10pt; font-weight:600; }
.pd-checkbox { width:12px; height:12px; border:1.5px solid #555; border-radius:2px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pd-checkbox.on { background:#000; }
.pd-checkbox.on::after { content:'✓'; font-size:8px; color:#fff; line-height:1; }

/* ══ COMPLETED RESULTS ═══════════════════════════════════════════════ */
.result-box { background:#f0fdf4; border:1.5px solid #22c55e; border-radius:8px; padding:18px 20px; margin-bottom:16px; }
.result-box-title { font-size:.82rem; font-weight:700; color:#15803d; margin-bottom:12px; display:flex; align-items:center; gap:7px; }
.result-file-link { display:inline-flex; align-items:center; gap:5px; color:#6d28d9; font-size:.82rem; font-weight:600; text-decoration:none; padding:5px 12px; background:#f5f3ff; border:1px solid #ddd6fe; border-radius:6px; }
.result-file-link:hover { background:#ede9fe; }
.result-interp-box { background:#fff; border:1.5px solid #c4b5fd; border-radius:8px; padding:14px 16px; margin-top:14px; }
.result-interp-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6d28d9; margin-bottom:8px; }
.result-interp-text { font-family:'Times New Roman', serif; font-size:.95rem; line-height:1.8; color:#111827; white-space:pre-wrap; }

/* ══ TECH TIMELINE ═══════════════════════════════════════════════════ */
.tech-section { background:#fff; border:2px solid #6d28d9; border-radius:10px; padding:24px 26px; }
.dark .tech-section { background:#1f2937; border-color:#5b21b6; }
.tech-title { font-size:1rem; font-weight:800; color:#6d28d9; margin-bottom:20px; padding-bottom:10px; border-bottom:2px solid #ede9fe; display:flex; align-items:center; gap:8px; }
.dark .tech-title { border-bottom-color:rgba(109,40,217,.2); }

.step-row { display:flex; align-items:flex-start; gap:16px; margin-bottom:20px; }
.step-num { width:32px; height:32px; border-radius:50%; font-size:.8rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
.step-num.s-done    { background:#059669; color:#fff; }
.step-num.s-active  { background:#6d28d9; color:#fff; }
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
.btn-violet { background:#6d28d9; color:#fff; } .btn-violet:hover { background:#5b21b6; }
.btn-blue   { background:#3b82f6; color:#fff; } .btn-blue:hover   { background:#2563eb; }
.btn-step:disabled { opacity:.45; cursor:not-allowed; }

.tf-label { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; display:block; margin-bottom:5px; }
.tf-area { width:100%; border:1px solid #e5e7eb; border-radius:6px; padding:9px 12px; font-size:.875rem; background:#fff; color:#111827; outline:none; font-family:inherit; resize:vertical; }
.dark .tf-area { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.tf-area:focus { border-color:#6d28d9; box-shadow:0 0 0 3px rgba(109,40,217,.1); }
.interp-area { width:100%; border:1.5px solid #6d28d9; border-radius:6px; padding:10px 14px; font-size:.9rem; background:#fff; color:#111827; outline:none; font-family:'Times New Roman',serif; line-height:1.75; resize:vertical; min-height:140px; }
.dark .interp-area { background:#374151; border-color:#5b21b6; color:#f3f4f6; }
.interp-area:focus { box-shadow:0 0 0 3px rgba(109,40,217,.1); }

.file-queue { margin-top:8px; }
.file-item { display:flex; align-items:center; gap:10px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:8px 12px; margin-bottom:6px; }
.dark .file-item { background:#374151; border-color:#4b5563; }
.file-item-name { flex:1; font-size:.82rem; color:#374151; font-weight:600; }
.dark .file-item-name { color:#e5e7eb; }
.file-item-size { font-size:.72rem; color:#9ca3af; }
.btn-rm { background:none; border:1px solid #fca5a5; color:#dc2626; border-radius:5px; padding:3px 9px; font-size:.75rem; cursor:pointer; flex-shrink:0; }
.btn-rm:hover { background:#fee2e2; }
.drop-zone { border:2px dashed #e5e7eb; border-radius:8px; padding:22px 18px; text-align:center; transition:border-color .15s; }
.drop-zone.enabled { cursor:pointer; } .drop-zone.enabled:hover { border-color:#6d28d9; background:#faf5ff; }
.drop-zone.locked { opacity:.45; background:#f9fafb; cursor:not-allowed; }

.btn-complete { background:#059669; color:#fff; border:none; border-radius:7px; padding:12px 32px; font-size:.95rem; font-weight:800; cursor:pointer; display:inline-flex; align-items:center; gap:8px; margin-top:20px; }
.btn-complete:hover:not(:disabled) { background:#047857; }
.btn-complete:disabled { opacity:.45; cursor:not-allowed; }

.lock-notice { display:inline-flex; align-items:center; gap:6px; font-size:.78rem; color:#9ca3af; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:6px 12px; margin-top:6px; }
.dark .lock-notice { background:#374151; border-color:#4b5563; }
</style>

@if($radRequest)
@php
    $patient     = $radRequest->visit?->patient ?? $radRequest->patient;
    $isCompleted = $radRequest->isCompleted();
    $uploads     = $radRequest->results()->with('uploadedBy')->latest()->get();

    // Timeline gate
    $s1done = !!$radRequest->request_received_at;   // Received
    $s2done = !!$radRequest->exam_started_at;        // Exam Started

    // File upload + complete requires BOTH s1 and s2
    $canUpload   = $s1done && $s2done;
    $canComplete = $canUpload && !empty($resultFiles);

    // Single interpretation: from the request record, or first upload that has it
    $bestInterp = $radRequest->radiologist_interpretation
        ?? $uploads->firstWhere('interpretation', '!=', null)?->interpretation
        ?? null;

    $src = strtoupper($radRequest->source ?? '');
@endphp

<div class="page-wrap">

<button wire:click="goBack" type="button" class="back-link">← Back to Dashboard</button>

{{-- ── STATUS BAR ────────────────────────────────────────────────── --}}
<div class="req-header">
    <div>
        <p class="req-no-big">{{ $radRequest->request_no }}</p>
        <p class="req-patient-big">{{ $patient?->full_name ?? '—' }}</p>
        <p class="req-case-big">{{ $patient?->case_no ?? '' }} · {{ $patient?->age_display ?? '' }} · {{ $patient?->sex ?? '' }}</p>
    </div>
    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
        @if($radRequest->modality)
        <span class="modality-pill">{{ $radRequest->modality }}</span>
        @endif
        <span class="req-status-pill pill-{{ str_replace('_','', $radRequest->status) }}">{{ $radRequest->status_label }}</span>
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
            <div class="h-sub">Radiology Request Form</div>
            <div class="h-addr">Brgy. Nazareno, Agoo, La Union &nbsp;·&nbsp; (072) 607-5541</div>
        </div>
        @if(file_exists(public_path('images/province-logo.png')))
            <div class="pd-logo"><img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union"></div>
        @else
            <div class="pd-logo">Province<br>Seal</div>
        @endif
    </div>

    <div class="pd-divider-thick" style="margin-top:10px;"></div>

    {{-- Modality --}}
    <div class="pd-modality-row">
        @foreach(['X-RAY','ULTRASOUND','CT SCAN'] as $mod)
        <div class="pd-modality-opt">
            <div class="pd-radio {{ $radRequest->modality === $mod ? 'on' : '' }}"></div>
            &nbsp;{{ $mod }}
        </div>
        @endforeach
    </div>

    <div class="pd-g4">
        <div class="pd-fg"><span class="pd-fl">Date</span><span class="pd-val">{{ $radRequest->date_requested?->format('Y-m-d') ?? $radRequest->created_at->format('Y-m-d') }}</span></div>
        <div class="pd-fg"><span class="pd-fl">RAD File No.</span><span class="pd-val" style="font-family:monospace;font-weight:bold;">{{ $radRequest->request_no }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Hospital No. (Case No.)</span><span class="pd-val">{{ $patient?->case_no ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Service / Ward</span><span class="pd-val">{{ $radRequest->ward ?? '—' }}</span></div>
    </div>

    {{-- Source --}}
    <div class="pd-source-row">
        @foreach(['OPD','ER','PRIVATE','PHIC','CHARITY / INDIGENT'] as $s)
        @php $match = $src === strtoupper($s) || ($src === 'CHARITY' && str_contains($s,'CHARITY')); @endphp
        <div class="pd-source-opt"><div class="pd-checkbox {{ $match ? 'on' : '' }}"></div>&nbsp;{{ $s }}</div>
        @endforeach
    </div>

    <div class="pd-sec-label">Patient Name</div>
    <div class="pd-g3">
        <div class="pd-fg"><span class="pd-fl">Family Name</span><span class="pd-val">{{ strtoupper($patient?->family_name ?? '—') }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Given Name</span><span class="pd-val">{{ strtoupper($patient?->first_name ?? '—') }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Middle Name</span><span class="pd-val">{{ strtoupper($patient?->middle_name ?? '—') }}</span></div>
    </div>

    <div class="pd-g2x">
        <div class="pd-fg"><span class="pd-fl">Address</span><span class="pd-val">{{ $patient?->address ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Date of Birth</span><span class="pd-val">{{ $patient?->birthday?->format('Y-m-d') ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Age</span><span class="pd-val">{{ $patient?->age_display ?? $patient?->current_age ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Sex</span><span class="pd-val">{{ $patient?->sex ?? '—' }}</span></div>
    </div>

    <div class="pd-divider"></div>

    <div style="margin-bottom:7px;"><span class="pd-fl">Examination Desired</span><div class="pd-area-val">{{ $radRequest->examination_desired ?? '—' }}</div></div>
    <div style="margin-bottom:7px;"><span class="pd-fl">Clinical Diagnosis</span><div class="pd-area-val">{{ $radRequest->clinical_diagnosis ?? '—' }}</div></div>
    <div style="margin-bottom:9px;"><span class="pd-fl">Pertinent / Brief Clinical Findings</span><div class="pd-area-val">{{ $radRequest->clinical_findings ?? '—' }}</div></div>

    <div class="pd-divider"></div>

    <div style="margin-top:10px;margin-bottom:12px;">
        <div class="pd-sec-label">Requesting Physician</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:end;">
            <div><span class="pd-fl">Name</span><div class="pd-val" style="font-size:11pt;font-weight:bold;">{{ $radRequest->requesting_physician ?? ($radRequest->doctor ? 'Dr. '.$radRequest->doctor->name : '—') }}</div></div>
            <div><div class="pd-sig-line"></div><div class="pd-sig-cap">Signature over Printed Name / PRC No.</div></div>
        </div>
    </div>

    <div>
        <div class="pd-sec-label">Radiologist Interpretation / Findings</div>
        <div class="pd-interp">{{ $radRequest->radiologist_interpretation ?? '' }}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:6px;">
            <div><div class="pd-sig-line"></div><div class="pd-sig-cap">Radiologist — Signature / PRC No.</div></div>
            <div><div class="pd-sig-line"></div><div class="pd-sig-cap">Date &amp; Time Reported</div></div>
        </div>
    </div>

    <div class="pd-divider" style="margin-top:12px;"></div>

    {{-- Footer: Date Requested | Request Received | Exam Started | Exam Done --}}
    <div class="pd-footer4">
        <div class="pd-fg"><span class="pd-fl">Date Requested</span><span class="pd-val">{{ $radRequest->date_requested?->format('Y-m-d') ?? '—' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Request Received</span><span class="pd-val" style="font-size:9pt;">{{ $radRequest->request_received_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Exam Started</span><span class="pd-val" style="font-size:9pt;">{{ $radRequest->exam_started_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
        <div class="pd-fg"><span class="pd-fl">Exam Done</span><span class="pd-val" style="font-size:9pt;">{{ $radRequest->exam_done_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
    </div>

</div>{{-- /.paper-doc --}}

{{-- ══ COMPLETED RESULTS VIEW ══════════════════════════════════════════ --}}
@if($uploads->isNotEmpty())
<div class="result-box">
    <p class="result-box-title">✅ {{ $uploads->count() }} Result File(s) — {{ $radRequest->request_no }}</p>

    {{-- Show uploader name only once (using the most recent upload's user) --}}
    @php
        $uploader = $uploads->first()?->uploadedBy?->name ?? '—';
    @endphp
    <p style="font-size:0.82rem; color:#6d28d9; font-weight:600; margin: -4px 0 12px 2px;">
        Uploaded by {{ $uploader }}
    </p>

    {{-- File list — now without per-file uploader/date --}}
    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:4px;">
        @foreach($uploads as $u)
        <div>
            <a href="{{ $u->file_url }}" target="_blank" class="result-file-link">
                {{ $u->file_type_icon }} {{ $u->file_name }}
                <span style="font-size:.7rem; font-weight:400; color:#6b7280;">
                    ({{ $u->file_size_human }})
                </span>
            </a>
            {{-- Removed the <p> that had "Uploaded by … · date" --}}
        </div>
        @endforeach
    </div>

    {{-- ONE big interpretation block (unchanged) --}}
    @if($bestInterp)
    <div class="result-interp-box">
        <p class="result-interp-label">📋 Radiologist Interpretation</p>
        <div class="result-interp-text">{{ $bestInterp }}</div>
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
                @if($radRequest->request_received_at)
                <span class="step-ts">{{ $radRequest->request_received_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</span>
                @endif
            </p>
            @if(!$radRequest->request_received_at)
            <button type="button" wire:click="markReceived" wire:loading.attr="disabled" class="btn-step btn-violet">
                📥 Mark as Received
            </button>
            @else
            <span style="font-size:.78rem;color:#059669;font-weight:600;">✅ Received</span>
            @endif
        </div>
    </div>

    <div class="step-connector {{ $s1done ? 'done' : '' }}"></div>

    {{-- ── STEP 2: Exam Started ───────────────────────────────────── --}}
    <div class="step-row">
        <div class="step-num {{ $s2done ? 's-done' : ($s1done ? 's-active' : 's-waiting') }}">{{ $s2done ? '✓' : '2' }}</div>
        <div class="step-body">
            <p class="step-title">
                Exam Started
                @if($radRequest->exam_started_at)
                <span class="step-ts">{{ $radRequest->exam_started_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</span>
                @endif
            </p>
            @if(!$radRequest->exam_started_at)
            <button type="button" wire:click="markExamStarted" wire:loading.attr="disabled"
                    class="btn-step btn-blue" {{ !$s1done ? 'disabled' : '' }}>
                ▶ Mark Exam Started
            </button>
            @if(!$s1done)<p class="lock-notice">🔒 Complete Step 1 first</p>@endif
            @else
            <span style="font-size:.78rem;color:#059669;font-weight:600;">✅ Exam started</span>
            @endif
        </div>
    </div>

    <div class="step-connector {{ $s2done ? 'done' : '' }}"></div>

    {{-- ── STEP 3: Upload Files ───────────────────────────────────── --}}
    <div class="step-row">
        <div class="step-num {{ !empty($resultFiles) && $canUpload ? 's-active' : 's-waiting' }}">3</div>
        <div class="step-body">
            <p class="step-title">Result Files <span style="color:#dc2626;">*</span></p>

            @if(!$canUpload)
            <p class="lock-notice">
                🔒
                @if(!$s1done) Complete Step 1 (Mark as Received) first
                @elseif(!$s2done) Complete Step 2 (Exam Started) first
                @endif
            </p>
            @else

            @if(!empty($resultFiles))
            <div class="file-queue">
                @foreach($resultFiles as $i => $f)
                <div class="file-item" wire:key="rf-{{ $i }}">
                    <span style="font-size:1.1rem;">🩻</span>
                    <span class="file-item-name">{{ $f->getClientOriginalName() }}</span>
                    <span class="file-item-size">{{ round($f->getSize() / 1024, 1) }} KB</span>
                    <button type="button" wire:click="removeFile({{ $i }})" class="btn-rm">✕</button>
                </div>
                @endforeach
            </div>
            @endif

            <div class="drop-zone enabled" style="margin-top:{{ empty($resultFiles) ? '0' : '8px' }};">
                <input type="file" wire:model="resultFiles" id="radFiles"
                       accept=".pdf,.jpg,.jpeg,.png,.webp" multiple style="display:none;">
                <label for="radFiles" style="cursor:pointer;display:block;">
                    <p style="font-size:1.3rem;margin-bottom:4px;">🩻</p>
                    <p style="font-size:.85rem;font-weight:700;color:#374151;">{{ empty($resultFiles) ? 'Drop scan image or PDF here' : '+ Add more files' }}</p>
                    <p style="font-size:.75rem;color:#9ca3af;margin-top:3px;">PDF · JPG · PNG · WebP · max 30 MB each</p>
                </label>
            </div>
            <div wire:loading wire:target="resultFiles" style="font-size:.78rem;color:#9ca3af;margin-top:5px;">Uploading…</div>
            @error('resultFiles')   <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
            @error('resultFiles.*') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror

            @endif
        </div>
    </div>

    <div class="step-connector {{ $canUpload ? 'done' : '' }}"></div>

    {{-- ── STEP 4: Radiologist Interpretation ───────────────────── --}}
    <div class="step-row">
        <div class="step-num s-waiting">4</div>
        <div class="step-body">
            <p class="step-title" style="color:#6d28d9;">Radiologist Interpretation <span style="font-weight:400;font-size:.8rem;color:#9ca3af;">(optional)</span></p>
            <textarea wire:model="interpretation"
                      class="interp-area"
                      rows="7"
                      placeholder="Type the radiologist's findings and impression here…&#10;&#10;e.g., Chest PA: No acute cardiopulmonary infiltrates. Heart is not enlarged. Lung fields are clear. Costophrenic angles are sharp. Bony thorax is intact.&#10;&#10;Impression: Normal chest radiograph."></textarea>
            @error('interpretation') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="step-connector"></div>

    {{-- ── STEP 5: Tech Notes ─────────────────────────────────────── --}}
    <div class="step-row">
        <div class="step-num s-waiting">5</div>
        <div class="step-body">
            <p class="step-title">Tech Notes <span style="font-weight:400;font-size:.8rem;color:#9ca3af;">(optional)</span></p>
            <textarea wire:model="notes" rows="2" class="tf-area" placeholder="Technical notes, patient cooperation, repeat exposure, etc."></textarea>
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
        @elseif(!$s2done) Mark Exam Started first
        @elseif(empty($resultFiles)) Add at least one result file
        @endif
    </p>
    @else
    <p style="font-size:.72rem;color:#9ca3af;margin-top:8px;">Exam Done time will be recorded automatically when you complete.</p>
    @endif

</div>
@endif

</div>{{-- /.page-wrap --}}
@endif

</x-filament-panels::page>