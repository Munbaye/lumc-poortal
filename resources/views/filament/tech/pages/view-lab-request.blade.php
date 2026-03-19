<x-filament-panels::page>

<style>
.page-wrap { max-width: 860px; margin: 0 auto; }
.back-link { display:inline-flex; align-items:center; gap:6px; font-size:.82rem; color:#6b7280; background:none; border:none; cursor:pointer; margin-bottom:16px; padding:0; }
.back-link:hover { color:#f97316; }

/* ── Request header ─────────────────────────────────────────── */
.req-header { background:linear-gradient(135deg,#7c2d12 0%,#f97316 100%); border-radius:10px; padding:18px 22px; margin-bottom:18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
.req-no-big { font-family:monospace; font-size:1.2rem; font-weight:900; color:#fff; }
.req-patient-big { font-size:1rem; font-weight:800; color:#fff; margin-top:3px; }
.req-case-big { font-family:monospace; font-size:.78rem; color:#fed7aa; margin-top:1px; }
.req-status-pill { padding:5px 16px; border-radius:9999px; font-size:.78rem; font-weight:700; }
.pill-pending   { background:rgba(255,255,255,.2); color:#fff; }
.pill-completed { background:#16a34a; color:#fff; }
.pill-inprogress { background:#eab308; color:#000; }

/* ── Info card ──────────────────────────────────────────────── */
.info-card { background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:16px 18px; margin-bottom:14px; }
.dark .info-card { background:#1f2937; border-color:#374151; }
.info-card-title { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#9ca3af; margin-bottom:10px; }
.info-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
.info-item label { font-size:.7rem; text-transform:uppercase; letter-spacing:.05em; color:#9ca3af; display:block; margin-bottom:2px; }
.info-item p { font-size:.875rem; font-weight:600; color:#111827; }
.dark .info-item p { color:#f3f4f6; }

/* ── Tests list ─────────────────────────────────────────────── */
.tests-card { background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:16px 18px; margin-bottom:14px; }
.dark .tests-card { background:#1f2937; border-color:#374151; }
.test-chip { display:inline-block; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; border-radius:5px; padding:3px 10px; font-size:.78rem; font-weight:600; margin:3px; }

/* ── Upload section ─────────────────────────────────────────── */
.upload-section { background:#fff; border:1.5px solid #f97316; border-radius:8px; padding:20px 22px; }
.dark .upload-section { background:#1f2937; border-color:#ea580c; }
.upload-title { font-size:.9rem; font-weight:700; color:#f97316; margin-bottom:14px; padding-bottom:8px; border-bottom:1px solid #fff7ed; }
.dark .upload-title { border-bottom-color:rgba(249,115,22,.2); }

.form-label { font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; display:block; margin-bottom:5px; }
.form-input { width:100%; border:1px solid #e5e7eb; border-radius:6px; padding:9px 12px; font-size:.875rem; background:#fff; color:#111827; outline:none; font-family:inherit; }
.dark .form-input { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.form-input:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,.12); }

/* File drop zone */
.file-zone { border:2px dashed #e5e7eb; border-radius:8px; padding:28px 20px; text-align:center; cursor:pointer; transition:border-color .15s; }
.file-zone:hover { border-color:#f97316; background:#fff7ed; }
.file-zone.has-file { border-color:#22c55e; background:#f0fdf4; }

.btn-upload { background:#f97316; color:#fff; border:none; border-radius:7px; padding:11px 28px; font-size:.9rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:7px; margin-top:16px; }
.btn-upload:hover { background:#ea580c; }
.btn-upload:disabled { opacity:.6; cursor:not-allowed; }

/* Completed result display */
.result-display { background:#f0fdf4; border:1.5px solid #22c55e; border-radius:8px; padding:16px 18px; }
.dark .result-display { background:rgba(34,197,94,.08); border-color:rgba(34,197,94,.4); }
</style>

@if($labRequest)
@php
    $patient = $labRequest->visit?->patient ?? $labRequest->patient;
    $isCompleted = $labRequest->isCompleted();
    $result = $labRequest->result;
@endphp

<div class="page-wrap">

    <button wire:click="goBack" type="button" class="back-link">← Back to Dashboard</button>

    {{-- Header --}}
    <div class="req-header">
        <div>
            <p class="req-no-big">{{ $labRequest->request_no }}</p>
            <p class="req-patient-big">{{ $patient?->full_name ?? '—' }}</p>
            <p class="req-case-big">{{ $patient?->case_no ?? '' }} · {{ $patient?->age_display ?? '' }} · {{ $patient?->sex ?? '' }}</p>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
            <span class="req-status-pill pill-{{ str_replace('_','', $labRequest->status) }}">
                {{ $labRequest->status_label }}
            </span>
            @if($labRequest->request_type === 'stat')
            <span style="background:#dc2626;color:#fff;padding:3px 10px;border-radius:9999px;font-size:.72rem;font-weight:800;">⚡ STAT</span>
            @endif
        </div>
    </div>

    {{-- Patient + Request Info --}}
    <div class="info-card">
        <p class="info-card-title">Request Details</p>
        <div class="info-grid">
            <div class="info-item"><label>Request No.</label><p style="font-family:monospace;">{{ $labRequest->request_no }}</p></div>
            <div class="info-item"><label>Date Requested</label><p>{{ $labRequest->date_requested?->format('M j, Y') ?? $labRequest->created_at->format('M j, Y') }}</p></div>
            <div class="info-item"><label>Ward / Service</label><p>{{ $labRequest->ward ?? '—' }}</p></div>
            <div class="info-item"><label>Ordering Physician</label><p>{{ $labRequest->requesting_physician ?? ($labRequest->doctor ? 'Dr. '.$labRequest->doctor->name : '—') }}</p></div>
            <div class="info-item"><label>Clinical Diagnosis</label><p>{{ $labRequest->clinical_diagnosis ?? '—' }}</p></div>
            <div class="info-item"><label>Submitted</label><p>{{ $labRequest->created_at->timezone('Asia/Manila')->format('M j, Y H:i') }}</p></div>
        </div>
    </div>

    {{-- Tests ordered --}}
    @if($labRequest->tests && count($labRequest->tests))
    <div class="tests-card">
        <p class="info-card-title">Tests Ordered ({{ count($labRequest->tests) }})</p>
        <div>
            @foreach($labRequest->tests as $test)
            <span class="test-chip">{{ $test }}</span>
            @endforeach
        </div>
        @if($labRequest->specimen)
        <p style="font-size:.78rem;color:#6b7280;margin-top:8px;">Specimen: <strong>{{ $labRequest->specimen }}</strong></p>
        @endif
        @if($labRequest->antibiotics_taken)
        <p style="font-size:.78rem;color:#6b7280;margin-top:2px;">Antibiotics: {{ $labRequest->antibiotics_taken }}</p>
        @endif
        @if($labRequest->other_tests)
        <p style="font-size:.78rem;color:#6b7280;margin-top:4px;">Others: {{ $labRequest->other_tests }}</p>
        @endif
    </div>
    @endif

    {{-- Result display (if already uploaded) --}}
    @if($isCompleted && $result)
    <div class="result-display" style="margin-bottom:14px;">
        <p style="font-size:.82rem;font-weight:700;color:#15803d;margin-bottom:8px;">✅ Result Uploaded</p>
        <div class="info-grid">
            <div class="info-item"><label>File</label>
                <a href="{{ $result->file_url }}" target="_blank"
                   style="color:#1d4ed8;font-size:.85rem;font-weight:600;text-decoration:none;">
                    {{ $result->file_type_icon }} {{ $result->file_name }}
                </a>
            </div>
            <div class="info-item"><label>Uploaded By</label><p>{{ $result->uploadedBy?->name ?? '—' }}</p></div>
            <div class="info-item"><label>Uploaded At</label><p>{{ $result->created_at->timezone('Asia/Manila')->format('M j, Y H:i') }}</p></div>
        </div>
        @if($result->notes)
        <p style="font-size:.8rem;color:#374151;margin-top:6px;">Notes: {{ $result->notes }}</p>
        @endif
    </div>
    @endif

    {{-- Upload section --}}
    @if(!$isCompleted)
    <div class="upload-section">
        <p class="upload-title">📤 Upload Laboratory Result</p>

        {{-- File upload --}}
        <div style="margin-bottom:12px;">
            <label class="form-label">Result File <span style="color:#dc2626;">*</span></label>
            <div class="file-zone {{ $resultFile ? 'has-file' : '' }}">
                <input type="file" wire:model="resultFile"
                       accept=".pdf,.jpg,.jpeg,.png,.webp"
                       style="display:none;" id="fileInput">
                @if($resultFile)
                    <p style="font-size:.85rem;color:#15803d;font-weight:700;">✅ {{ $resultFile->getClientOriginalName() }}</p>
                    <p style="font-size:.75rem;color:#6b7280;">Click to replace</p>
                @else
                    <p style="font-size:1.5rem;margin-bottom:6px;">📄</p>
                    <p style="font-size:.85rem;font-weight:700;color:#374151;">Drop file here or click to browse</p>
                    <p style="font-size:.75rem;color:#9ca3af;margin-top:3px;">PDF, JPG, PNG, WebP — max 20 MB</p>
                @endif
                <label for="fileInput" style="position:absolute;inset:0;cursor:pointer;"></label>
            </div>
            @error('resultFile') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
        </div>

        {{-- Notes --}}
        <div style="margin-bottom:0;">
            <label class="form-label">Tech Notes (optional)</label>
            <textarea wire:model="notes" rows="2"
                      class="form-input"
                      style="resize:vertical;"
                      placeholder="Any notes for the doctor or nurse…"></textarea>
        </div>

        <button wire:click="saveResult"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-60"
                type="button" class="btn-upload">
            <span wire:loading.remove wire:target="saveResult">📤 Upload Result &amp; Mark Completed</span>
            <span wire:loading wire:target="saveResult">Uploading…</span>
        </button>
    </div>
    @endif

</div>
@endif

</x-filament-panels::page>