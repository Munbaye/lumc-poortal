<x-filament-panels::page>
<style>
    /* ── Page shell ─────────────────────────────────────────────────────────── */
    .ds-page { max-width: 1100px; margin: 0 auto; }

    /* ── Top toolbar (same style as NUR-014) ───────────────────────────────── */
    @media screen {
        .ds-toolbar {
            background: #1e3a5f;
            border-radius: 10px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
    }
    .ds-toolbar-lbl { font-size: 0.9rem; font-weight: 700; color: #fff; }
    .ds-toolbar-tag { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); border-radius: 3px; padding: 2px 9px; font-size: 0.68rem; text-transform: uppercase; color: rgba(255,255,255,.9); letter-spacing: .06em; }
    .ds-toolbar-info { font-size: 0.78rem; color: rgba(255,255,255,.75); }
    .ds-spacer { flex: 1; }
    .ds-status-pill { padding: 4px 14px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
    .ds-status-draft    { background: #fef3c7; color: #92400e; }
    .ds-status-final    { background: #d1fae5; color: #065f46; }
    .ds-status-readonly { background: #e0e7ff; color: #3730a3; }

    /* ── Paper card ─────────────────────────────────────────────────────────── */
    .ds-paper {
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        box-shadow: 0 2px 16px rgba(0,0,0,.07);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .dark .ds-paper { background: #1f2937; border-color: #374151; }

    /* ── LUMC header ──────────────────────────────────────────────────────────*/
    .ds-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 28px 12px;
        border-bottom: 2.5px solid #000;
    }
    .dark .ds-header { border-bottom-color: #e5e7eb; }
    .ds-logo-box { width: 64px; height: 64px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
    .ds-logo-box img { width: 64px; height: 64px; object-fit: contain; }
    .ds-logo-ph { width: 64px; height: 64px; flex-shrink: 0; border: 1.5px dashed #bbb; display: flex; align-items: center; justify-content: center; font-size: 7pt; color: #bbb; text-align: center; line-height: 1.4; }
    .ds-header-center { flex: 1; text-align: center; line-height: 1.35; }
    .ds-h-rep  { font-size: 8pt; color: #444; }
    .ds-h-prov { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; }
    .ds-h-mun  { font-size: 8.5pt; color: #444; }
    .ds-h-hosp { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; margin-top: 2px; }

    /* ── Form title band ────────────────────────────────────────────────────── */
    .ds-title-band {
        text-align: center;
        padding: 10px 28px 8px;
        border-bottom: 1px solid #e5e7eb;
    }
    .dark .ds-title-band { border-bottom-color: #374151; }
    .ds-title-band h1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: .1em; font-family: 'Times New Roman', Times, serif; }

    /* ── Form body ──────────────────────────────────────────────────────────── */
    .ds-body { padding: 18px 28px 24px; }

    /* ── Patient demographics row ───────────────────────────────────────────── */
    .ds-demo-row {
        display: grid;
        gap: 0;
        border: 1px solid #555;
    }
    .ds-demo-name-row {
        display: grid;
        grid-template-columns: 3fr 1fr 1fr;
        border-bottom: 1px solid #555;
    }
    .ds-demo-addr-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 2fr;
    }
    .ds-demo-cell {
        padding: 5px 9px;
        border-right: 1px solid #555;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .ds-demo-cell:last-child { border-right: none; }
    .ds-demo-label { font-size: 7pt; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #555; }
    .dark .ds-demo-label { color: #9ca3af; }
    .ds-demo-value {
        font-size: 9.5pt;
        font-weight: 600;
        color: #111;
        min-height: 18px;
        border-bottom: 1px solid #999;
        padding-bottom: 1px;
    }
    .dark .ds-demo-value { color: #f3f4f6; border-bottom-color: #6b7280; }

    /* Sex / Civil status checkboxes inline */
    .ds-sex-row { display: flex; gap: 10px; align-items: center; font-size: 9pt; }
    .ds-civil-row { display: flex; gap: 6px; align-items: center; font-size: 8.5pt; flex-wrap: wrap; }
    .ds-cb-opt { display: inline-flex; align-items: center; gap: 3px; font-size: 8.5pt; color: #111; }
    .dark .ds-cb-opt { color: #f3f4f6; }
    .ds-cb { width: 11px; height: 11px; border: 1.5px solid #555; border-radius: 2px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ds-cb.checked { background: #1d4ed8; border-color: #1d4ed8; }
    .ds-cb.checked::after { content: '✓'; font-size: 7px; color: #fff; line-height: 1; }

    /* ── Field rows inside the body ─────────────────────────────────────────── */
    .ds-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 10px;
    }
    .ds-field { display: flex; flex-direction: column; gap: 3px; }
    .ds-field label {
        font-size: 7.5pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #6b7280;
    }
    .dark .ds-field label { color: #9ca3af; }
    .ds-field input, .ds-field textarea, .ds-field select {
        font-size: 9.5pt;
        color: #111;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 5px;
        padding: 6px 9px;
        outline: none;
        font-family: 'Times New Roman', Times, serif;
        line-height: 1.5;
        width: 100%;
        box-sizing: border-box;
    }
    .dark .ds-field input, .dark .ds-field textarea { background: #374151; border-color: #4b5563; color: #f3f4f6; }
    .ds-field input:focus, .ds-field textarea:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.12); }
    .ds-field input[readonly], .ds-field textarea[readonly] {
        background: #f9fafb;
        color: #374151;
        cursor: not-allowed;
        border-color: #e5e7eb;
    }
    .dark .ds-field input[readonly], .dark .ds-field textarea[readonly] { background: #1f2937; color: #9ca3af; }

    /* Narrative textarea */
    .ds-textarea-wrap { margin-bottom: 14px; }
    .ds-textarea-label {
        font-size: 8.5pt;
        font-weight: 700;
        color: #374151;
        margin-bottom: 3px;
        display: block;
        font-family: 'Times New Roman', Times, serif;
    }
    .dark .ds-textarea-label { color: #d1d5db; }
    .ds-textarea-label .ds-req { color: #dc2626; margin-left: 2px; }
    .ds-textarea {
        width: 100%;
        min-height: 90px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 9px 11px;
        font-size: 10pt;
        line-height: 1.8;
        font-family: 'Times New Roman', Times, serif;
        color: #111;
        background: #fff;
        resize: vertical;
        box-sizing: border-box;
        outline: none;
    }
    .dark .ds-textarea { background: #374151; border-color: #4b5563; color: #f3f4f6; }
    .ds-textarea:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.12); }
    .ds-textarea[readonly] { background: #f9fafb; color: #374151; cursor: not-allowed; border-color: #e5e7eb; }
    .dark .ds-textarea[readonly] { background: #1f2937; color: #9ca3af; }
    .ds-textarea-tall { min-height: 120px; }
    .ds-textarea-xl   { min-height: 150px; }

    /* ── Readonly display block ─────────────────────────────────────────────── */
    .ds-ro-val {
        font-size: 10pt;
        color: #111;
        min-height: 22px;
        border-bottom: 1px solid #bbb;
        padding: 2px 3px 3px;
        font-family: 'Times New Roman', Times, serif;
        line-height: 1.5;
        white-space: pre-wrap;
    }
    .dark .ds-ro-val { color: #f3f4f6; border-bottom-color: #4b5563; }
    .ds-ro-area {
        font-size: 10.5pt;
        color: #111;
        min-height: 80px;
        border: 1px solid #d1d5db;
        background: #f9fafb;
        padding: 8px 10px;
        font-family: 'Times New Roman', Times, serif;
        line-height: 1.8;
        white-space: pre-wrap;
        border-radius: 5px;
    }
    .dark .ds-ro-area { color: #f3f4f6; background: #1f2937; border-color: #374151; }

    /* ── Notice banners ─────────────────────────────────────────────────────── */
    .ds-notice {
        padding: 10px 16px;
        border-radius: 7px;
        font-size: 0.8rem;
        margin-bottom: 14px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }
    .ds-notice-warn  { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
    .ds-notice-info  { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
    .ds-notice-final { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }

    /* ── Action buttons ─────────────────────────────────────────────────────── */
    .ds-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 6px;
        padding-bottom: 30px;
    }
    .btn-ds-cancel { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; padding: 10px 22px; border-radius: 7px; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
    .btn-ds-cancel:hover { background: #e5e7eb; }
    .btn-ds-draft { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; padding: 10px 22px; border-radius: 7px; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
    .btn-ds-draft:hover { background: #fde68a; }
    .btn-ds-discharge {
        background: linear-gradient(135deg, #059669, #047857);
        color: #fff; border: none;
        padding: 10px 28px;
        border-radius: 7px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 2px 8px rgba(5,150,105,.3);
    }
    .btn-ds-discharge:hover { background: linear-gradient(135deg, #047857, #065f46); }
    .btn-ds-discharge:disabled { background: #9ca3af; box-shadow: none; cursor: not-allowed; }

    /* ── Confirmation overlay ───────────────────────────────────────────────── */
    .ds-confirm-box {
        background: #fef2f2;
        border: 2px solid #dc2626;
        border-radius: 10px;
        padding: 18px 22px;
        margin-bottom: 16px;
    }
    .ds-confirm-title { font-size: 0.9rem; font-weight: 700; color: #dc2626; margin-bottom: 8px; }
    .ds-confirm-body  { font-size: 0.82rem; color: #374151; margin-bottom: 14px; line-height: 1.6; }
    .ds-confirm-actions { display: flex; gap: 10px; }
    .btn-confirm-yes { background: #dc2626; color: #fff; border: none; padding: 9px 22px; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 0.85rem; }
    .btn-confirm-yes:hover { background: #b91c1c; }
    .btn-confirm-no  { background: #e5e7eb; color: #374151; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.85rem; }
</style>

@php
    $patient  = $visit->patient;
    $history  = $visit->medicalHistory;
    $isRO     = $this->isReadonly;
    $sex      = strtoupper($sex ?? '');
    $civil    = strtoupper($civilStatus ?? '');
    $isDraft  = $dischargeSummary && !$dischargeSummary->is_finalized;
    $isFinal  = $dischargeSummary?->is_finalized;
@endphp

<div class="ds-page">

    {{-- ── Toolbar ─────────────────────────────────────────────────────────── --}}
    <div class="ds-toolbar">
        <span class="ds-toolbar-lbl">Discharge Summary</span>
        <span class="ds-toolbar-tag">FORM-DS-001</span>
        <span class="ds-toolbar-info">{{ $patient->full_name }} &nbsp;·&nbsp; {{ $patient->case_no }}</span>
        <div class="ds-spacer"></div>
        @if($isFinal)
            <span class="ds-status-pill ds-status-final">✓ Finalized</span>
        @elseif($isDraft)
            <span class="ds-status-pill ds-status-draft">✎ Draft Saved</span>
        @else
            <span class="ds-status-pill ds-status-draft">New</span>
        @endif
    </div>

    @if($isRO)
    <div class="ds-notice ds-notice-final">
        <span>✓</span>
        <span>This discharge summary has been <strong>finalized</strong> and the patient has been marked as discharged. No further edits are allowed.</span>
    </div>
    @else
    <div class="ds-notice ds-notice-warn">
        <span>⚠</span>
        <span>Clicking <strong>Finalize &amp; Discharge</strong> will mark this patient as <strong>discharged</strong> and lock this form. Use <strong>Save Draft</strong> to save progress without discharging.</span>
    </div>
    @endif

    {{-- ── Paper form ───────────────────────────────────────────────────────── --}}
    <div class="ds-paper">

        {{-- LUMC Header --}}
        <div class="ds-header">
            @if(file_exists(public_path('images/province-logo.png')))
                <div class="ds-logo-box"><img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union"></div>
            @else
                <div class="ds-logo-ph">Province<br>Seal</div>
            @endif

            <div class="ds-header-center">
                <div class="ds-h-rep">Republic of the Philippines</div>
                <div class="ds-h-prov">Province of La Union</div>
                <div class="ds-h-mun">Municipality of Agoo, La Union</div>
                <div class="ds-h-hosp">La Union Medical Center</div>
            </div>

            @if(file_exists(public_path('images/lumc-logo.png')))
                <div class="ds-logo-box"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC Logo"></div>
            @elseif(file_exists(public_path('images/bagong-pilipinas-logo-only.png')))
                <div class="ds-logo-box"><img src="{{ asset('images/bagong-pilipinas-logo-only.png') }}" alt="Logo"></div>
            @else
                <div class="ds-logo-ph">LUMC<br>Logo</div>
            @endif
        </div>

        {{-- Form Title --}}
        <div class="ds-title-band">
            <h1>Discharge Summary</h1>
        </div>

        <div class="ds-body">

            {{-- ── Patient Demographics Block ─────────────────────────────────── --}}
            <div class="ds-demo-row" style="margin-bottom: 12px;">

                {{-- Row 1: Name | Hosp Case No. | Ward/Service --}}
                <div class="ds-demo-name-row">
                    <div class="ds-demo-cell">
                        <span class="ds-demo-label">Patient's Name &nbsp;<span style="font-size:7pt;font-weight:400;">(Last, Given, Middle)</span></span>
                        <span class="ds-demo-value">{{ strtoupper($patientName) }}</span>
                    </div>
                    <div class="ds-demo-cell">
                        <span class="ds-demo-label">Hosp. Case No.</span>
                        <span class="ds-demo-value" style="font-family:monospace;">{{ $hospitalCaseNo }}</span>
                    </div>
                    <div class="ds-demo-cell">
                        <span class="ds-demo-label">Ward / Service</span>
                        <span class="ds-demo-value">{{ $wardService }}</span>
                    </div>
                </div>

                {{-- Row 2: Address | Tel No. | Sex | Civil Status --}}
                <div class="ds-demo-addr-row">
                    <div class="ds-demo-cell">
                        <span class="ds-demo-label">Permanent Address</span>
                        <span class="ds-demo-value">{{ $permanentAddress }}</span>
                    </div>
                    <div class="ds-demo-cell">
                        <span class="ds-demo-label">Tel. No.</span>
                        <span class="ds-demo-value">{{ $telephoneNo }}</span>
                    </div>
                    <div class="ds-demo-cell">
                        <span class="ds-demo-label">Sex</span>
                        <div style="margin-top: 3px;">
                            <div class="ds-sex-row">
                                <div class="ds-cb-opt"><div class="ds-cb {{ str_contains(strtoupper($sex), 'M') ? 'checked' : '' }}"></div> M</div>
                                <div class="ds-cb-opt"><div class="ds-cb {{ str_contains(strtoupper($sex), 'F') ? 'checked' : '' }}"></div> F</div>
                            </div>
                        </div>
                    </div>
                    <div class="ds-demo-cell">
                        <span class="ds-demo-label">Civil Status</span>
                        <div style="margin-top: 3px;">
                            <div class="ds-civil-row">
                                @foreach(['S' => 'S', 'M' => 'M', 'D' => 'D', 'W' => 'W', 'SEP' => 'Sep'] as $val => $lbl)
                                    <div class="ds-cb-opt">
                                        <div class="ds-cb {{ strtoupper(substr($civil,0,1)) === $val || $civil === $val ? 'checked' : '' }}"></div> {{ $lbl }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Core admission fields ────────────────────────────────────── --}}
            <div class="ds-field-row" style="margin-bottom: 10px;">
                <div class="ds-field">
                    <label>Date Admitted</label>
                    <input type="text" value="{{ $dateAdmitted }}" readonly>
                </div>
                <div class="ds-field">
                    <label>Date Discharged @if(!$isRO)<span style="color:#dc2626;">*</span>@endif</label>
                    @if($isRO)
                        <input type="text" value="{{ $dateDischarged ? \Carbon\Carbon::parse($dateDischarged)->timezone('Asia/Manila')->format('F j, Y g:i A') : '—' }}" readonly>
                    @else
                        <input type="datetime-local" wire:model="dateDischarged">
                    @endif
                </div>
            </div>

            <div class="ds-field-row" style="margin-bottom: 10px;">
                <div class="ds-field">
                    <label>Attending Physician</label>
                    @if($isRO)
                        <div class="ds-ro-val">{{ $attendingPhysician }}</div>
                    @else
                        <input type="text" wire:model="attendingPhysician" placeholder="Dr. ...">
                    @endif
                </div>
                <div class="ds-field">
                    <label>Admitting Diagnosis</label>
                    @if($isRO)
                        <div class="ds-ro-val">{{ $admittingDiagnosis }}</div>
                    @else
                        <input type="text" wire:model="admittingDiagnosis">
                    @endif
                </div>
            </div>

            <div class="ds-field-row" style="margin-bottom: 10px;">
                <div class="ds-field">
                    <label>Final Diagnosis @if(!$isRO)<span style="color:#dc2626;">*</span>@endif</label>
                    @if($isRO)
                        <div class="ds-ro-val">{{ $finalDiagnosis }}</div>
                    @else
                        <input type="text" wire:model="finalDiagnosis" placeholder="Enter final diagnosis">
                    @endif
                </div>
                <div class="ds-field">
                    <label>Chief Complaints</label>
                    @if($isRO)
                        <div class="ds-ro-val">{{ $chiefComplaints }}</div>
                    @else
                        <input type="text" wire:model="chiefComplaints">
                    @endif
                </div>
            </div>

            {{-- ── Narrative sections ───────────────────────────────────────── --}}

            {{-- Brief Clinical History & Pertinent P.E. --}}
            <div class="ds-textarea-wrap">
                <label class="ds-textarea-label">Brief Clinical History and Pertinent P.E.:</label>
                @if($isRO)
                    <div class="ds-ro-area ds-textarea-xl">{{ $briefClinicalHistory }}</div>
                @else
                    <textarea wire:model="briefClinicalHistory"
                        class="ds-textarea ds-textarea-xl"
                        placeholder="Summarize the patient's clinical history and pertinent physical examination findings..."></textarea>
                @endif
            </div>

            {{-- Laboratory Findings --}}
            <div class="ds-textarea-wrap">
                <label class="ds-textarea-label">
                    Laboratory Findings
                    <span style="font-size:7.5pt;font-weight:400;color:#6b7280;">(including EKG, X-ray, and other diagnostic procedures)</span>
                </label>
                @if($isRO)
                    <div class="ds-ro-area ds-textarea-tall">{{ $laboratoryFindings }}</div>
                @else
                    <textarea wire:model="laboratoryFindings"
                        class="ds-textarea ds-textarea-tall"
                        placeholder="Summarize all laboratory, imaging, and other diagnostic findings..."></textarea>
                @endif
            </div>

            {{-- Course in the Ward --}}
            <div class="ds-textarea-wrap">
                <label class="ds-textarea-label">
                    Course in the Ward:
                    <span style="font-size:7.5pt;font-weight:400;color:#6b7280;">(Include medications)</span>
                </label>
                @if($isRO)
                    <div class="ds-ro-area ds-textarea-xl">{{ $courseInWard }}</div>
                @else
                    <textarea wire:model="courseInWard"
                        class="ds-textarea ds-textarea-xl"
                        placeholder="Describe the patient's hospital course, treatment given, response to treatment, medications administered..."></textarea>
                @endif
            </div>

            {{-- Disposition --}}
            <div class="ds-textarea-wrap">
                <label class="ds-textarea-label">
                    Disposition @if(!$isRO)<span class="ds-req">*</span>@endif
                    <span style="font-size:7.5pt;font-weight:400;color:#6b7280;">(include home medication, special instruction and follow-up)</span>
                </label>
                @if($isRO)
                    <div class="ds-ro-area ds-textarea-tall">{{ $disposition }}</div>
                @else
                    <textarea wire:model="disposition"
                        class="ds-textarea ds-textarea-tall"
                        placeholder="Home medications, special instructions, follow-up schedule, referrals, diet, activity restrictions..."></textarea>
                @endif
            </div>

        </div>{{-- /.ds-body --}}
    </div>{{-- /.ds-paper --}}

    {{-- ── Confirmation box (discharge) ────────────────────────────────────── --}}
    @if(!$isRO)
    <div class="ds-confirm-box" id="ds-confirm" style="display:none;">
        <p class="ds-confirm-title">⚠ Confirm Patient Discharge</p>
        <p class="ds-confirm-body">
            You are about to <strong>finalize</strong> this discharge summary and mark
            <strong>{{ $patient->full_name }}</strong> as <strong>DISCHARGED</strong>.
            This action <u>cannot be undone</u> — the form will be locked and the patient
            will be removed from the active admitted list.
        </p>
        <div class="ds-confirm-actions">
            <button type="button" class="btn-confirm-yes" wire:click="finalizeAndDischarge" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="finalizeAndDischarge">✓ Yes, Discharge Patient</span>
                <span wire:loading wire:target="finalizeAndDischarge">Processing…</span>
            </button>
            <button type="button" class="btn-confirm-no" onclick="document.getElementById('ds-confirm').style.display='none'">
                Cancel
            </button>
        </div>
    </div>
    @endif

    {{-- ── Action Buttons ───────────────────────────────────────────────────── --}}
    @if(!$isRO)
    <div class="ds-actions">
        <button type="button" class="btn-ds-cancel"
            onclick="window.location.href='/doctor/patient-chart?visitId={{ $visitId }}'">
            ← Back to Chart
        </button>
        <button type="button" class="btn-ds-draft"
            wire:click="saveDraft"
            wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="saveDraft">💾 Save Draft</span>
            <span wire:loading wire:target="saveDraft">Saving…</span>
        </button>
        <button type="button" class="btn-ds-discharge"
            onclick="document.getElementById('ds-confirm').style.display='block';window.scrollTo({top:document.body.scrollHeight,behavior:'smooth'})">
            📋 Finalize &amp; Discharge Patient
        </button>
    </div>
    @else
    <div class="ds-actions">
        <a href="/doctor/patient-chart?visitId={{ $visitId }}"
            style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;padding:10px 22px;border-radius:7px;font-size:0.85rem;font-weight:600;text-decoration:none;">
            ← Back to Chart
        </a>
        <a href="{{ route('forms.discharge-summary', ['visit' => $visitId]) }}"
            target="_blank"
            style="background:#1d4ed8;color:#fff;border:none;padding:10px 22px;border-radius:7px;font-size:0.85rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            🖨️ Print / Save as PDF
        </a>
    </div>
    @endif

</div>
</x-filament-panels::page>