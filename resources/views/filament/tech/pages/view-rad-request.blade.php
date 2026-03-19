<x-filament-panels::page>

<style>
.page-wrap { max-width: 860px; margin: 0 auto; }
.back-link { display:inline-flex; align-items:center; gap:6px; font-size:.82rem; color:#6b7280; background:none; border:none; cursor:pointer; margin-bottom:16px; padding:0; }
.back-link:hover { color:#6d28d9; }

/* ── Request header ─────────────────────────────────────────── */
.req-header { background:linear-gradient(135deg,#3b0764 0%,#6d28d9 100%); border-radius:10px; padding:18px 22px; margin-bottom:18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
.req-no-big { font-family:monospace; font-size:1.2rem; font-weight:900; color:#fff; }
.req-patient-big { font-size:1rem; font-weight:800; color:#fff; margin-top:3px; }
.req-case-big { font-family:monospace; font-size:.78rem; color:#c4b5fd; margin-top:1px; }
.modality-pill { background:rgba(255,255,255,.2); color:#fff; padding:4px 14px; border-radius:9999px; font-size:.82rem; font-weight:700; }

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

/* ── Clinical fields (read-only textarea style) ─────────────── */
.ro-field { background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:8px 12px; font-size:.875rem; color:#374151; line-height:1.6; min-height:50px; }
.dark .ro-field { background:#374151; border-color:#4b5563; color:#d1d5db; }

/* ── Upload section ─────────────────────────────────────────── */
.upload-section { background:#fff; border:1.5px solid #6d28d9; border-radius:8px; padding:20px 22px; }
.dark .upload-section { background:#1f2937; border-color:#5b21b6; }
.upload-title { font-size:.9rem; font-weight:700; color:#6d28d9; margin-bottom:14px; padding-bottom:8px; border-bottom:1px solid #ede9fe; }
.dark .upload-title { border-bottom-color:rgba(109,40,217,.2); }

.form-label { font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; display:block; margin-bottom:5px; }
.form-input { width:100%; border:1px solid #e5e7eb; border-radius:6px; padding:9px 12px; font-size:.875rem; background:#fff; color:#111827; outline:none; font-family:inherit; }
.dark .form-input { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.form-input:focus { border-color:#6d28d9; box-shadow:0 0 0 3px rgba(109,40,217,.12); }

.file-zone { border:2px dashed #e5e7eb; border-radius:8px; padding:28px 20px; text-align:center; cursor:pointer; transition:border-color .15s; position:relative; }
.file-zone:hover { border-color:#6d28d9; background:#faf5ff; }
.file-zone.has-file { border-color:#22c55e; background:#f0fdf4; }

.interp-area { width:100%; border:1.5px solid #6d28d9; border-radius:6px; padding:10px 14px; font-size:.9rem; background:#fff; color:#111827; outline:none; font-family:'Times New Roman', serif; line-height:1.7; resize:vertical; min-height:130px; }
.dark .interp-area { background:#374151; border-color:#5b21b6; color:#f3f4f6; }
.interp-area:focus { box-shadow:0 0 0 3px rgba(109,40,217,.12); }

.btn-upload { background:#6d28d9; color:#fff; border:none; border-radius:7px; padding:11px 28px; font-size:.9rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:7px; margin-top:16px; }
.btn-upload:hover { background:#5b21b6; }
.btn-upload:disabled { opacity:.6; cursor:not-allowed; }

.result-display { background:#f0fdf4; border:1.5px solid #22c55e; border-radius:8px; padding:16px 18px; }
.dark .result-display { background:rgba(34,197,94,.08); border-color:rgba(34,197,94,.4); }
</style>

@if($radRequest)
@php
    $patient     = $radRequest->visit?->patient ?? $radRequest->patient;
    $isCompleted = $radRequest->isCompleted();
    $result      = $radRequest->result;
@endphp

<div class="page-wrap">

    <button wire:click="goBack" type="button" class="back-link">← Back to Dashboard</button>

    {{-- Header --}}
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
            <span class="req-status-pill pill-{{ str_replace('_','', $radRequest->status) }}">
                {{ $radRequest->status_label }}
            </span>
        </div>
    </div>

    {{-- Request Details --}}
    <div class="info-card">
        <p class="info-card-title">Request Details</p>
        <div class="info-grid">
            <div class="info-item"><label>Request No.</label><p style="font-family:monospace;">{{ $radRequest->request_no }}</p></div>
            <div class="info-item"><label>Date Requested</label><p>{{ $radRequest->date_requested?->format('M j, Y') ?? $radRequest->created_at->format('M j, Y') }}</p></div>
            <div class="info-item"><label>Ward / Service</label><p>{{ $radRequest->ward ?? '—' }}</p></div>
            <div class="info-item"><label>Ordering Physician</label><p>{{ $radRequest->requesting_physician ?? ($radRequest->doctor ? 'Dr. '.$radRequest->doctor->name : '—') }}</p></div>
            <div class="info-item"><label>Source</label><p>{{ $radRequest->source ?? '—' }}</p></div>
            <div class="info-item"><label>Submitted</label><p>{{ $radRequest->created_at->timezone('Asia/Manila')->format('M j, Y H:i') }}</p></div>
        </div>
    </div>

    {{-- Clinical fields (read-only) --}}
    <div class="info-card">
        <p class="info-card-title">Clinical Information (Read-only)</p>
        <div style="margin-bottom:10px;">
            <p class="form-label">Examination Desired</p>
            <div class="ro-field">{{ $radRequest->examination_desired ?? '—' }}</div>
        </div>
        <div class="info-grid" style="margin-bottom:10px;">
            <div>
                <p class="form-label">Clinical Diagnosis</p>
                <div class="ro-field">{{ $radRequest->clinical_diagnosis ?? '—' }}</div>
            </div>
            <div style="grid-column:span 2;">
                <p class="form-label">Pertinent Clinical Findings</p>
                <div class="ro-field">{{ $radRequest->clinical_findings ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Result display (if already uploaded) --}}
    @if($isCompleted && $result)
    <div class="result-display" style="margin-bottom:14px;">
        <p style="font-size:.82rem;font-weight:700;color:#15803d;margin-bottom:8px;">✅ Result Uploaded</p>
        <div class="info-grid" style="margin-bottom:8px;">
            <div class="info-item"><label>File</label>
                <a href="{{ $result->file_url }}" target="_blank"
                   style="color:#6d28d9;font-size:.85rem;font-weight:600;text-decoration:none;">
                    {{ $result->file_type_icon }} {{ $result->file_name }}
                </a>
            </div>
            <div class="info-item"><label>Uploaded By</label><p>{{ $result->uploadedBy?->name ?? '—' }}</p></div>
            <div class="info-item"><label>Uploaded At</label><p>{{ $result->created_at->timezone('Asia/Manila')->format('M j, Y H:i') }}</p></div>
        </div>
        @if($result->interpretation)
        <div>
            <p style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;margin-bottom:4px;">Radiologist Interpretation</p>
            <div style="background:#fff;border:1px solid #d1fae5;border-radius:6px;padding:10px 14px;font-family:'Times New Roman',serif;font-size:.9rem;line-height:1.7;color:#111827;">
                {{ $result->interpretation }}
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Upload section --}}
    @if(!$isCompleted)
    <div class="upload-section">
        <p class="upload-title">🩻 Upload Radiology Result &amp; Interpretation</p>

        {{-- Radiologist Interpretation — prominent, full-width --}}
        <div style="margin-bottom:14px;">
            <label class="form-label" style="color:#6d28d9;">
                Radiologist Interpretation / Findings
                <span style="color:#9ca3af;font-weight:400;text-transform:none;letter-spacing:0;">(optional — fill before printing)</span>
            </label>
            <textarea wire:model="interpretation"
                      class="interp-area"
                      rows="6"
                      placeholder="Type the radiologist's findings and impression here…&#10;&#10;e.g., Chest PA: No acute cardiopulmonary infiltrates. Heart is not enlarged. Lung fields are clear. Costophrenic angles are sharp. Bony thorax is intact.&#10;&#10;Impression: Normal chest radiograph."></textarea>
        </div>

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
                    <p style="font-size:1.5rem;margin-bottom:6px;">🩻</p>
                    <p style="font-size:.85rem;font-weight:700;color:#374151;">Drop scan image or PDF here</p>
                    <p style="font-size:.75rem;color:#9ca3af;margin-top:3px;">PDF, JPG, PNG, WebP — max 30 MB</p>
                @endif
                <label for="fileInput" style="position:absolute;inset:0;cursor:pointer;"></label>
            </div>
            @error('resultFile') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
        </div>

        {{-- Notes --}}
        <div>
            <label class="form-label">Tech Notes (optional)</label>
            <textarea wire:model="notes" rows="2"
                      class="form-input" style="resize:vertical;"
                      placeholder="Technical notes, patient cooperation, repeat exposure, etc."></textarea>
        </div>

        <button wire:click="saveResult"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-60"
                type="button" class="btn-upload">
            <span wire:loading.remove wire:target="saveResult">🩻 Upload Result &amp; Mark Completed</span>
            <span wire:loading wire:target="saveResult">Uploading…</span>
        </button>
    </div>
    @endif

</div>
@endif

</x-filament-panels::page>