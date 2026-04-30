<x-filament-panels::page>
    <style>
        /* ── Stepper ─────────────────────────────────────────────────── */
        .ca-stepper {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .ca-step {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .ca-step:last-child {
            flex: 0;
        }

        .ca-step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 800;
            flex-shrink: 0;
            transition: all .2s;
        }

        .ca-step-dot.done {
            background: #059669;
            color: #fff;
            cursor: pointer;
        }

        .ca-step-dot.active {
            background: #1d4ed8;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(29, 78, 216, .15);
        }

        .ca-step-dot.wait {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: default;
        }

        .dark .ca-step-dot.wait {
            background: #374151;
        }

        .ca-step-label {
            font-size: .75rem;
            font-weight: 600;
            margin-left: 8px;
            white-space: nowrap;
        }

        .ca-step-label.done {
            color: #059669;
        }

        .ca-step-label.active {
            color: #1d4ed8;
        }

        .ca-step-label.wait {
            color: #9ca3af;
        }

        .ca-step-line {
            flex: 1;
            height: 2px;
            margin: 0 10px;
        }

        .ca-step-line.done {
            background: #059669;
        }

        .ca-step-line.wait {
            background: #e5e7eb;
        }

        .dark .ca-step-line.wait {
            background: #374151;
        }

        /* ── Patient header ──────────────────────────────────────────── */
        .ca-header {
            background: linear-gradient(135deg, #1e3a5f, #1d4ed8);
            border-radius: 10px;
            padding: 14px 20px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .ca-header-name {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
        }

        .ca-header-case {
            font-family: monospace;
            font-size: .78rem;
            color: #93c5fd;
            margin-top: 2px;
        }

        .ca-header-pill {
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 6px;
            padding: 4px 12px;
            font-size: .78rem;
            color: #e0f2fe;
            font-weight: 600;
        }

        .ca-back-link {
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .3);
            color: #fff;
            font-size: .78rem;
            font-weight: 600;
            padding: 7px 14px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .ca-back-link:hover {
            background: rgba(255, 255, 255, .25);
        }

        /* ── Iframe wrapper ──────────────────────────────────────────── */
        .form-iframe-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .dark .form-iframe-wrap {
            border-color: #374151;
            background: #1f2937;
        }

        .form-iframe-wrap iframe {
            display: block;
            width: 100%;
            border: none;
        }

        /* ── Step hint box ───────────────────────────────────────────── */
        .step-hint {
            font-size: .82rem;
            color: #6b7280;
            margin-bottom: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
        }

        .dark .step-hint {
            background: #1f2937;
            border-color: #374151;
            color: #9ca3af;
        }

        /* ── Step 4 review: form section headers ─────────────────────── */
        .review-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 16px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
        }

        .dark .review-head {
            background: #111827;
            border-color: #374151;
        }

        .review-title {
            font-size: .88rem;
            font-weight: 700;
            color: #111827;
        }

        .dark .review-title {
            color: #f3f4f6;
        }

        .review-meta {
            font-size: .72rem;
            color: #6b7280;
            margin-top: 2px;
        }

        .review-frame-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .dark .review-frame-wrap {
            border-color: #374151;
        }

        /* ── Buttons ─────────────────────────────────────────────────── */
        .btn-complete {
            background: #059669;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 32px;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .btn-complete:hover {
            background: #047857;
        }

        .btn-complete:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .btn-back-link {
            background: #fff;
            color: #374151;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 22px;
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .dark .btn-back-link {
            background: #374151;
            color: #e5e7eb;
            border-color: #4b5563;
        }

        .btn-back-link:hover {
            background: #f3f4f6;
        }

        .btn-sm {
            background: none;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 5px 12px;
            font-size: .75rem;
            color: #374151;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-sm:hover {
            background: #f3f4f6;
        }

        /* ── Loading overlay ─────────────────────────────────────────── */
        .iframe-saving-overlay {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .75);
            z-index: 10;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 12px;
            border-radius: 8px;
        }

        .dark .iframe-saving-overlay {
            background: rgba(17, 24, 39, .75);
        }

        .iframe-saving-overlay.show {
            display: flex;
        }

        .spinner {
            width: 36px;
            height: 36px;
            border: 4px solid #e5e7eb;
            border-top-color: #1d4ed8;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .saving-msg {
            font-size: .85rem;
            font-weight: 600;
            color: #374151;
        }

        .dark .saving-msg {
            color: #e5e7eb;
        }

        .iframe-container {
            position: relative;
        }

        /* ── Step 4 success banner ───────────────────────────────────── */
        .all-done-banner {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }
    </style>

    @if($visit && $patient)
    @php
    $history = $visit->medicalHistory;
    $svc = $visit->admitted_service ?? $history?->service ?? null;
    @endphp

    {{-- Patient header --}}
    <div class="ca-header">
        <div>
            <p class="ca-header-name">{{ $patient->full_name }}</p>
            <p class="ca-header-case">{{ $patient->case_no }} · {{ $patient->age_display }} · {{ $patient->sex }}</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            @if($svc)<span class="ca-header-pill">{{ $svc }}</span>@endif
            <span class="ca-header-pill" style="{{ $visit->visit_type==='ER' ? 'background:rgba(220,38,38,.3);' : '' }}">
                <span style="display:inline-flex;align-items:center;gap:6px;">
                    @if($visit->visit_type==='ER')
                        <x-filament::icon icon="heroicon-o-bolt" class="w-4 h-4 text-red-100" />
                        <span>ER</span>
                    @else
                        <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-4 h-4 text-blue-100" />
                        <span>OPD</span>
                    @endif
                </span>
            </span>
            @if($history?->doctor)<span class="ca-header-pill">Dr. {{ $history->doctor->name }}</span>@endif
            <span class="ca-header-pill" style="background:rgba(245,158,11,.25);">
                <span style="display:inline-flex;align-items:center;gap:6px;">
                    <x-filament::icon icon="heroicon-o-clock" class="w-4 h-4 text-yellow-100" />
                    <span>Admitted {{ $visit->doctor_admitted_at->timezone('Asia/Manila')->format('M j H:i') }}</span>
                </span>
            </span>
        </div>
        <a href="{{ \App\Filament\Clerk\Pages\PendingAdmissions::getUrl() }}" class="ca-back-link">
            <x-filament::icon icon="heroicon-o-arrow-left" class="w-4 h-4" />
            Pending List
        </a>
    </div>

    {{-- Stepper (4 steps) --}}
    <div class="ca-stepper">
        @php
        $steps = [
        [1, 'ER Record', 'ER-001'],
        [2, 'Adm. Record', 'ADM-001'],
        [3, 'Consent', 'NUR-002-1'],
        [4, 'Review & Complete', ''],
        ];
        @endphp
        @foreach($steps as [$num, $label, $code])
        @php $cls = $step > $num ? 'done' : ($step === $num ? 'active' : 'wait'); @endphp
        <div class="ca-step">
            <div class="ca-step-dot {{ $cls }}"
                @if($cls==='done' ) wire:click="goToStep({{ $num }})" title="Go back to step {{ $num }}" @endif>
                @if($cls === 'done')
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4" />
                @else
                    {{ $num }}
                @endif
            </div>
            <div>
                <p class="ca-step-label {{ $cls }}">{{ $label }}</p>
                @if($code)<p style="font-size:.63rem;color:#9ca3af;margin-left:8px;">{{ $code }}</p>@endif
            </div>
        </div>
        @if(!$loop->last)
        <div class="ca-step-line {{ $step > $num ? 'done' : 'wait' }}"></div>
        @endif
        @endforeach
    </div>

    {{-- ══ STEP 1: ER RECORD ══════════════════════════════════════════ --}}
    @if($step === 1)
    <div class="step-hint">
        <span style="display:inline-flex;align-items:center;gap:8px;">
            <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-5 h-5 text-gray-500" />
            <strong>Step 1 of 4 — Emergency Room Record (ER-001)</strong>
        </span><br>
        Fill in all fields. When done, click <strong>Save &amp; Continue</strong> inside the form toolbar.
        The page will advance to Step 2 automatically.
    </div>

    <div class="iframe-container">
        <div class="iframe-saving-overlay" id="erSavingOverlay">
            <div class="spinner"></div>
            <p class="saving-msg">Saving ER Record…</p>
        </div>
        <div class="form-iframe-wrap">
            <iframe src="{{ $this->getErRecordFormUrl() }}"
                id="erFrame" title="ER Record — {{ $patient->case_no }}"
                style="width:100%;min-height:1200px;border:none;display:block;"></iframe>
        </div>
    </div>

    {{-- ══ STEP 2: ADMISSION & DISCHARGE RECORD ══════════════════════════ --}}
    @elseif($step === 2)
    <div class="step-hint">
        <span style="display:inline-flex;align-items:center;gap:8px;">
            <x-filament::icon icon="heroicon-o-building-office-2" class="w-5 h-5 text-gray-500" />
            <strong>Step 2 of 4 — Admission and Discharge Record (ADM-001)</strong>
        </span><br>
        Fields shared with the ER Record are pre-filled. Fill remaining fields, then click
        <strong>Save &amp; Continue</strong> in the form toolbar.
    </div>

    <div class="iframe-container">
        <div class="iframe-saving-overlay" id="admSavingOverlay">
            <div class="spinner"></div>
            <p class="saving-msg">Saving Admission Record…</p>
        </div>
        <div class="form-iframe-wrap">
            <iframe src="{{ $this->getAdmRecordFormUrl() }}"
                id="admFrame" title="Admission Record — {{ $patient->case_no }}"
                style="width:100%;min-height:1300px;border:none;display:block;"></iframe>
        </div>
    </div>

    {{-- ══ STEP 3: CONSENT TO CARE ════════════════════════════════════════ --}}
    @elseif($step === 3)
    <div class="step-hint">
        <span style="display:inline-flex;align-items:center;gap:8px;">
            <x-filament::icon icon="heroicon-o-pencil-square" class="w-5 h-5 text-gray-500" />
            <strong>Step 3 of 4 — Consent to Care (NUR-002-1)</strong>
        </span><br>
        Patient name and doctor name are pre-filled. Either the patient <em>or</em> the guardian
        must fill and sign. When ready, click <strong>Save &amp; Continue</strong> in the form toolbar.
    </div>

    <div class="iframe-container">
        <div class="iframe-saving-overlay" id="consentSavingOverlay">
            <div class="spinner"></div>
            <p class="saving-msg">Saving Consent to Care…</p>
        </div>
        <div class="form-iframe-wrap">
            <iframe src="{{ $this->getConsentFormUrl() }}"
                id="consentFrame" title="Consent to Care — {{ $patient->case_no }}"
                style="width:100%;min-height:950px;border:none;display:block;"></iframe>
        </div>
    </div>

    {{-- ══ STEP 4: REVIEW ALL THREE FORMS + COMPLETE ═══════════════════════ --}}
    @elseif($step === 4)

    <div class="all-done-banner">
        <p style="font-size:.92rem;font-weight:700;color:#15803d;margin:0 0 3px;">
            <span style="display:inline-flex;align-items:center;gap:8px;">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-emerald-700" />
                <span>All three forms saved — review in full before completing admission</span>
            </span>
        </p>
        <p style="font-size:.8rem;color:#166534;margin:0;">
            Scroll through all documents. When satisfied, click
            <strong>Complete Admission</strong> at the bottom.
        </p>
    </div>

    {{-- ── ER Record (read-only) ── --}}
    <div>
        <div class="review-head">
            <div>
                <p class="review-title" style="display:inline-flex;align-items:center;gap:8px;">
                    <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-5 h-5 text-gray-500" />
                    <span>Emergency Room Record (ER-001)</span>
                </p>
                @if($visit->erRecord?->registration_date)
                <p class="review-meta">
                    Date: {{ $visit->erRecord->registration_date->format('M j, Y') }}
                    @if($visit->erRecord->brought_by) · Brought by: {{ $visit->erRecord->brought_by }} @endif
                </p>
                @endif
            </div>
            <div style="display:flex;gap:8px;">
                <button wire:click="goToStep(1)" type="button" class="btn-sm" style="display:inline-flex;align-items:center;gap:6px;">
                    <x-filament::icon icon="heroicon-o-pencil-square" class="w-4 h-4" />
                    Edit
                </button>
                <a href="{{ $this->getErRecordFormUrl() }}" target="_blank" rel="noopener" class="btn-sm" style="display:inline-flex;align-items:center;gap:6px;">
                    <x-filament::icon icon="heroicon-o-printer" class="w-4 h-4" />
                    Print
                </a>
            </div>
        </div>
        <div class="review-frame-wrap">
            <iframe src="{{ $this->getErRecordReadonlyUrl() }}"
                title="ER Record — Read Only"
                style="width:100%;min-height:1200px;border:none;display:block;"
                loading="lazy"></iframe>
        </div>
    </div>

    {{-- ── Admission & Discharge Record (read-only) ── --}}
    <div>
        <div class="review-head">
            <div>
                <p class="review-title" style="display:inline-flex;align-items:center;gap:8px;">
                    <x-filament::icon icon="heroicon-o-building-office-2" class="w-5 h-5 text-gray-500" />
                    <span>Admission and Discharge Record (ADM-001)</span>
                </p>
                @if($visit->admissionRecord?->admission_date)
                <p class="review-meta">
                    Admitted: {{ $visit->admissionRecord->admission_date->format('M j, Y') }}
                    @if($visit->admissionRecord->ward_service) · {{ $visit->admissionRecord->ward_service }} @endif
                </p>
                @endif
            </div>
            <div style="display:flex;gap:8px;">
                <button wire:click="goToStep(2)" type="button" class="btn-sm" style="display:inline-flex;align-items:center;gap:6px;">
                    <x-filament::icon icon="heroicon-o-pencil-square" class="w-4 h-4" />
                    Edit
                </button>
                <a href="{{ $this->getAdmRecordFormUrl() }}" target="_blank" rel="noopener" class="btn-sm" style="display:inline-flex;align-items:center;gap:6px;">
                    <x-filament::icon icon="heroicon-o-printer" class="w-4 h-4" />
                    Print
                </a>
            </div>
        </div>
        <div class="review-frame-wrap">
            <iframe src="{{ $this->getAdmRecordReadonlyUrl() }}"
                title="Admission Record — Read Only"
                style="width:100%;min-height:1300px;border:none;display:block;"
                loading="lazy"></iframe>
        </div>
    </div>

    {{-- ── Consent to Care (read-only) ── --}}
    <div>
        <div class="review-head">
            <div>
                <p class="review-title" style="display:inline-flex;align-items:center;gap:8px;">
                    <x-filament::icon icon="heroicon-o-document-check" class="w-5 h-5 text-gray-500" />
                    <span>Consent to Care (NUR-002-1)</span>
                </p>
                @if($visit->consentRecord)
                <p class="review-meta">
                    {{ $visit->consentRecord->active_section === 1
                    ? 'Patient: ' . ($visit->consentRecord->patient_name ?? '—')
                    : 'Guardian: ' . ($visit->consentRecord->guardian_name ?? '—') }}
                </p>
                @endif
            </div>
            <div style="display:flex;gap:8px;">
                <button wire:click="goToStep(3)" type="button" class="btn-sm" style="display:inline-flex;align-items:center;gap:6px;">
                    <x-filament::icon icon="heroicon-o-pencil-square" class="w-4 h-4" />
                    Edit
                </button>
                <a href="{{ $this->getConsentFormUrl() }}" target="_blank" rel="noopener" class="btn-sm" style="display:inline-flex;align-items:center;gap:6px;">
                    <x-filament::icon icon="heroicon-o-printer" class="w-4 h-4" />
                    Print
                </a>
            </div>
        </div>
        <div class="review-frame-wrap">
            <iframe src="{{ $this->getConsentReadonlyUrl() }}"
                title="Consent to Care — Read Only"
                style="width:100%;min-height:950px;border:none;display:block;"
                loading="lazy"></iframe>
        </div>
    </div>

    {{-- ── Complete Admission button ── --}}
    <div style="display:flex;align-items:center;gap:12px;padding-top:20px;border-top:1px solid #e5e7eb;margin-top:8px;">
        <button wire:click="completeAdmission"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-60"
            type="button"
            class="btn-complete">
            <span wire:loading.remove wire:target="completeAdmission" style="display:inline-flex;align-items:center;gap:8px;">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5" />
                Complete Admission
            </span>
            <span wire:loading wire:target="completeAdmission">Processing…</span>
        </button>
        <a href="{{ \App\Filament\Clerk\Pages\PendingAdmissions::getUrl() }}" class="btn-back-link" style="display:inline-flex;align-items:center;gap:6px;">
            <x-filament::icon icon="heroicon-o-arrow-left" class="w-4 h-4" />
            Back to Pending List
        </a>
    </div>

    @endif {{-- /step --}}

    {{-- postMessage bridge — triggers page reload after each iframe saves --}}
    <script>
        window.addEventListener('message', function(e) {
            if (!e.data || !e.data.type) return;

            const overlayMap = {
                'erSaved': 'erSavingOverlay',
                'admSaved': 'admSavingOverlay',
                'consentSaved': 'consentSavingOverlay',
            };

            const overlayId = overlayMap[e.data.type];
            if (overlayId) {
                const overlay = document.getElementById(overlayId);
                if (overlay) overlay.classList.add('show');
                setTimeout(function() {
                    window.location.reload();
                }, 400);
            }
        });
    </script>

    @else
    <div style="text-align:center;padding:60px 20px;">
        <p style="color:#9ca3af;margin-bottom:8px;">Visit not found or not pending admission.</p>
        <a href="{{ \App\Filament\Clerk\Pages\PendingAdmissions::getUrl() }}"
            style="color:#1d4ed8;font-size:.875rem;display:inline-flex;align-items:center;gap:6px;">
            <x-filament::icon icon="heroicon-o-arrow-left" class="w-4 h-4" />
            Back to Pending List
        </a>
    </div>
    @endif

</x-filament-panels::page>