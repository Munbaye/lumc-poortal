<x-filament-panels::page>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    .ru-wrap { font-family: 'DM Sans', sans-serif; max-width: 900px; }
    .ru-mono { font-family: 'DM Mono', monospace; }

    /* ── Step card ── */
    .ru-step {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .ru-step-header {
        padding: 18px 26px 16px;
        border-bottom: 1.5px solid #f1f5f9;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }
    .ru-step-num {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: #1d4ed8;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .ru-step-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 3px;
    }
    .ru-step-desc {
        font-size: 13px;
        color: #94a3b8;
        margin: 0;
        line-height: 1.5;
    }
    .ru-step-body { padding: 22px 26px; }

    /* ── Info pills ── */
    .ru-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 16px;
    }
    @media (max-width: 640px) { .ru-info-grid { grid-template-columns: 1fr; } }
    .ru-info-pill {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
    }
    .ru-info-pill-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }
    .ru-info-pill-value {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.4;
    }
    .ru-info-pill.full { grid-column: 1 / -1; }

    /* ── Phase blocks inside initial reading ── */
    .ru-phase {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
    }
    .ru-phase:last-of-type { margin-bottom: 0; }

    .ru-phase-header {
        padding: 13px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
    }
    .ru-phase-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .ru-phase-icon svg { width: 16px; height: 16px; }

    .ru-phase-icon.pre  { background: #fef3c7; }
    .ru-phase-icon.pre svg  { color: #d97706; }
    .ru-phase-icon.ana  { background: #ede9fe; }
    .ru-phase-icon.ana svg  { color: #7c3aed; }
    .ru-phase-icon.post { background: #dcfce7; }
    .ru-phase-icon.post svg { color: #16a34a; }

    .ru-phase-title { font-size: 14px; font-weight: 700; color: #1e293b; }
    .ru-phase-hint  { font-size: 12px; color: #94a3b8; margin-top: 1px; }

    .ru-phase-progress {
        margin-left: auto;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 99px;
        white-space: nowrap;
    }
    .ru-phase-progress.done    { background: #dcfce7; color: #15803d; }
    .ru-phase-progress.partial { background: #fef9c3; color: #a16207; }
    .ru-phase-progress.none    { background: #f1f5f9; color: #94a3b8; }

    .ru-phase-body { padding: 16px 18px; }

    /* checklist items */
    .ru-checklist { display: flex; flex-direction: column; gap: 10px; }
    .ru-check-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.12s;
        user-select: none;
    }
    .ru-check-item:hover { background: #eff6ff; border-color: #bfdbfe; }
    .ru-check-item.checked {
        background: #f0fdf4;
        border-color: #86efac;
    }
    .ru-check-box {
        width: 20px; height: 20px;
        border-radius: 5px;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.12s;
        background: #fff;
    }
    .ru-check-item.checked .ru-check-box {
        background: #16a34a;
        border-color: #16a34a;
    }
    .ru-check-box svg { width: 12px; height: 12px; color: #fff; display: none; }
    .ru-check-item.checked .ru-check-box svg { display: block; }
    .ru-check-label {
        font-size: 14px;
        color: #374151;
        line-height: 1.4;
        flex: 1;
    }
    .ru-check-item.checked .ru-check-label { color: #15803d; }

    /* ── Critical value panel ── */
    .ru-critical-toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        background: #fff9f9;
        border: 1.5px solid #fecaca;
        border-radius: 12px;
        margin-top: 20px;
        cursor: pointer;
        transition: background 0.12s;
        gap: 16px;
    }
    .ru-critical-toggle-row:hover { background: #fef2f2; }
    .ru-critical-toggle-row.active {
        background: #fef2f2;
        border-color: #f87171;
    }
    .ru-critical-label {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ru-critical-label svg { width: 20px; height: 20px; color: #dc2626; flex-shrink: 0; }
    .ru-critical-title { font-size: 14px; font-weight: 700; color: #dc2626; }
    .ru-critical-sub   { font-size: 12.5px; color: #9ca3af; margin-top: 2px; }

    .ru-toggle-pill {
        width: 44px; height: 24px;
        border-radius: 99px;
        background: #d1d5db;
        position: relative;
        flex-shrink: 0;
        transition: background 0.15s;
    }
    .ru-toggle-pill.on { background: #dc2626; }
    .ru-toggle-pill::after {
        content: '';
        position: absolute;
        top: 3px; left: 3px;
        width: 18px; height: 18px;
        border-radius: 50%;
        background: #fff;
        transition: left 0.15s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .ru-toggle-pill.on::after { left: 23px; }

    .ru-critical-reason {
        margin-top: 12px;
        padding: 14px 18px;
        background: #fef2f2;
        border: 1.5px solid #fecaca;
        border-radius: 10px;
        display: none;
    }
    .ru-critical-reason.visible { display: block; }
    .ru-critical-reason label {
        font-size: 13px;
        font-weight: 700;
        color: #dc2626;
        display: block;
        margin-bottom: 8px;
    }

    /* ── Submit bar ── */
    .ru-submit-bar {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px 26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .ru-submit-info { font-size: 13.5px; color: #64748b; line-height: 1.5; }
    .ru-submit-info strong { color: #1e293b; }
    .ru-submit-actions { display: flex; gap: 10px; flex-shrink: 0; }
    .ru-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 14px;
        font-weight: 600;
        padding: 11px 24px;
        border-radius: 10px;
        cursor: pointer;
        border: none;
        font-family: 'DM Sans', sans-serif;
        transition: all 0.15s;
        text-decoration: none;
    }
    .ru-btn svg { width: 16px; height: 16px; }
    .ru-btn.primary { background: #1d4ed8; color: #fff; }
    .ru-btn.primary:hover { background: #1e40af; box-shadow: 0 4px 14px rgba(29,78,216,0.28); }
    .ru-btn.ghost   { background: #f1f5f9; color: #475569; }
    .ru-btn.ghost:hover { background: #e2e8f0; }

    /* ── Dark mode ── */
    .dark .ru-step,
    .dark .ru-submit-bar      { background: #1e293b; border-color: #334155; }
    .dark .ru-step-header     { border-color: #334155; }
    .dark .ru-step-title      { color: #f1f5f9; }
    .dark .ru-step-desc       { color: #64748b; }
    .dark .ru-info-pill       { background: #0f172a; border-color: #334155; }
    .dark .ru-info-pill-value { color: #e2e8f0; }
    .dark .ru-phase           { border-color: #334155; }
    .dark .ru-phase-header    { border-color: #1e293b; }
    .dark .ru-phase-title     { color: #f1f5f9; }
    .dark .ru-phase-body      { background: #1e293b; }
    .dark .ru-check-item      { background: #0f172a; border-color: #334155; }
    .dark .ru-check-item:hover { background: rgba(29,78,216,0.1); border-color: #1e40af; }
    .dark .ru-check-item.checked { background: rgba(22,163,74,0.1); border-color: #166534; }
    .dark .ru-check-box       { background: #1e293b; border-color: #475569; }
    .dark .ru-check-label     { color: #cbd5e1; }
    .dark .ru-check-item.checked .ru-check-label { color: #4ade80; }
    .dark .ru-critical-toggle-row { background: #1e293b; border-color: #7f1d1d; }
    .dark .ru-critical-toggle-row:hover { background: rgba(220,38,38,0.08); }
    .dark .ru-critical-reason { background: rgba(220,38,38,0.08); border-color: #7f1d1d; }
    .dark .ru-submit-info     { color: #94a3b8; }
    .dark .ru-submit-info strong { color: #e2e8f0; }
    .dark .ru-btn.ghost       { background: #334155; color: #94a3b8; }
    .dark .ru-btn.ghost:hover { background: #475569; }
</style>

<div class="ru-wrap">
<x-filament-panels::form wire:submit="save">

    {{-- ── STEP 1: Select Doctor's Order ── --}}
    <div class="ru-step">
        <div class="ru-step-header">
            <div class="ru-step-num">1</div>
            <div>
                <p class="ru-step-title">Select Doctor's Order</p>
                <p class="ru-step-desc">Choose the pending order you are fulfilling — patient info fills in automatically.</p>
            </div>
        </div>
        <div class="ru-step-body">
            {{ $this->form->getComponent('doctors_order_id') }}
            <div class="ru-info-grid">
                <div class="ru-info-pill">
                    <p class="ru-info-pill-label">Patient</p>
                    <p class="ru-info-pill-value">{{ $this->data['_patient_info'] ?? '— Select an order above' }}</p>
                </div>
                <div class="ru-info-pill">
                    <p class="ru-info-pill-label">Requesting Doctor</p>
                    <p class="ru-info-pill-value">{{ $this->data['_requesting_doctor'] ?? '—' }}</p>
                </div>
                <div class="ru-info-pill">
                    <p class="ru-info-pill-label">Order / Instruction</p>
                    <p class="ru-info-pill-value">{{ $this->data['_order_text'] ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STEP 2: Initial Reading ── --}}
    <div class="ru-step">
        <div class="ru-step-header">
            <div class="ru-step-num">2</div>
            <div>
                <p class="ru-step-title">Initial Reading</p>
                <p class="ru-step-desc">
                    Validate all three analytical phases before uploading. This ensures the result is accurate, reliable, and consistent.
                    <strong style="color:#1d4ed8"> All items are required.</strong>
                </p>
            </div>
        </div>
        <div class="ru-step-body">

            {{-- Pre-Analytical Phase --}}
            <div class="ru-phase">
                <div class="ru-phase-header">
                    <div class="ru-phase-icon pre">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>
                    </div>
                    <div>
                        <p class="ru-phase-title">Pre-Analytical Phase</p>
                        <p class="ru-phase-hint">Checks before the test was run — specimen, labeling, patient match</p>
                    </div>
                    <span class="ru-phase-progress none" id="pre-progress">0 / 5</span>
                </div>
                <div class="ru-phase-body">
                    {{-- Rendered by Filament but visually hidden — we use custom UI above --}}
                    <div style="display:none">{{ $this->form->getComponent('pre_analytical_checks') }}</div>

                    {{-- Custom checklist UI --}}
                    <div class="ru-checklist" id="pre-checklist">
                        @foreach([
                            'patient_match'       => 'Patient name on result matches the selected patient',
                            'specimen_labeled'    => 'Specimen is properly labeled with patient ID',
                            'collection_recorded' => 'Date and time of specimen collection is recorded',
                            'specimen_condition'  => 'Specimen condition is acceptable (no hemolysis, lipemia, or icterus)',
                            'test_matches_order'  => 'Test requested matches the doctor\'s order',
                        ] as $key => $label)
                            <label class="ru-check-item" data-phase="pre" data-key="{{ $key }}">
                                <div class="ru-check-box">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                </div>
                                <span class="ru-check-label">{{ $label }}</span>
                                <input type="checkbox"
                                       wire:model="data.pre_analytical_checks"
                                       value="{{ $key }}"
                                       style="display:none"
                                       id="pre_{{ $key }}">
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Analytical Phase --}}
            <div class="ru-phase">
                <div class="ru-phase-header">
                    <div class="ru-phase-icon ana">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                    </div>
                    <div>
                        <p class="ru-phase-title">Analytical Phase</p>
                        <p class="ru-phase-hint">Checks during the test — QC, instrument, reference ranges</p>
                    </div>
                    <span class="ru-phase-progress none" id="ana-progress">0 / 4</span>
                </div>
                <div class="ru-phase-body">
                    <div style="display:none">{{ $this->form->getComponent('analytical_checks') }}</div>
                    <div class="ru-checklist" id="ana-checklist">
                        @foreach([
                            'qc_passed'           => 'Quality Control (QC) was performed and passed',
                            'reference_ranges'    => 'Reference ranges are appropriate for patient age and sex',
                            'no_instrument_error' => 'No instrument or equipment errors were encountered',
                            'reportable_range'    => 'Result is within the reportable range of the method',
                        ] as $key => $label)
                            <label class="ru-check-item" data-phase="ana" data-key="{{ $key }}">
                                <div class="ru-check-box">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                </div>
                                <span class="ru-check-label">{{ $label }}</span>
                                <input type="checkbox"
                                       wire:model="data.analytical_checks"
                                       value="{{ $key }}"
                                       style="display:none"
                                       id="ana_{{ $key }}">
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Post-Analytical Phase --}}
            <div class="ru-phase">
                <div class="ru-phase-header">
                    <div class="ru-phase-icon post">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <div>
                        <p class="ru-phase-title">Post-Analytical Phase</p>
                        <p class="ru-phase-hint">Checks after results are generated — legibility, consistency, release readiness</p>
                    </div>
                    <span class="ru-phase-progress none" id="post-progress">0 / 4</span>
                </div>
                <div class="ru-phase-body">
                    <div style="display:none">{{ $this->form->getComponent('post_analytical_checks') }}</div>
                    <div class="ru-checklist" id="post-checklist">
                        @foreach([
                            'result_legible'        => 'Result is legible and clearly readable',
                            'clinically_consistent' => 'Values are consistent with patient\'s clinical condition',
                            'previous_reviewed'     => 'Result has been reviewed against previous results (if available)',
                            'reference_indicated'   => 'Reference ranges are indicated on the result',
                        ] as $key => $label)
                            <label class="ru-check-item" data-phase="post" data-key="{{ $key }}">
                                <div class="ru-check-box">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                </div>
                                <span class="ru-check-label">{{ $label }}</span>
                                <input type="checkbox"
                                       wire:model="data.post_analytical_checks"
                                       value="{{ $key }}"
                                       style="display:none"
                                       id="post_{{ $key }}">
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Initial Impression --}}
            <div style="margin-top: 20px;">
                {{ $this->form->getComponent('initial_impression') }}
            </div>

            {{-- Critical Value Flag --}}
            <div class="ru-critical-toggle-row" id="ru-critical-row" onclick="toggleCritical()">
                <div class="ru-critical-label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                    <div>
                        <p class="ru-critical-title">Flag as Critical Value</p>
                        <p class="ru-critical-sub">Enable if any value is dangerously abnormal and requires immediate doctor attention.</p>
                    </div>
                </div>
                <div class="ru-toggle-pill" id="ru-toggle-pill"></div>
            </div>

            <div class="ru-critical-reason" id="ru-critical-reason">
                <label>Critical Value — Description <span style="color:#dc2626">*</span></label>
                {{ $this->form->getComponent('critical_reason') }}
            </div>

            {{-- Hidden toggle field wired to Livewire --}}
            <div style="display:none">{{ $this->form->getComponent('is_critical') }}</div>

        </div>
    </div>

    {{-- ── STEP 3: Result Details ── --}}
    <div class="ru-step">
        <div class="ru-step-header">
            <div class="ru-step-num">3</div>
            <div>
                <p class="ru-step-title">Result Details</p>
                <p class="ru-step-desc">Specify the test name and who performed it.</p>
            </div>
        </div>
        <div class="ru-step-body">
            {{ $this->form->getComponent('test_name') }}
            {{ $this->form->getComponent('performed_by') }}
            {{ $this->form->getComponent('result_type') }}
            {{ $this->form->getComponent('notes') }}
        </div>
    </div>

    {{-- ── STEP 4: Attach Files ── --}}
    <div class="ru-step">
        <div class="ru-step-header">
            <div class="ru-step-num">4</div>
            <div>
                <p class="ru-step-title">Attach Result Files</p>
                <p class="ru-step-desc">Upload one or more result files. JPEG, PNG, or PDF — max 10 MB each.</p>
            </div>
        </div>
        <div class="ru-step-body">
            {{ $this->form->getComponent('files') }}
        </div>
    </div>

    {{-- ── Submit Bar ── --}}
    <div class="ru-submit-bar">
        <p class="ru-submit-info">
            Submitting will mark the doctor's order as <strong>completed</strong>
            and set the result status to <strong>Pending Validation</strong>.
            Critical values will notify the doctor immediately.
        </p>
        <div class="ru-submit-actions">
            <button type="button" wire:click="discard" class="ru-btn ghost">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                Discard
            </button>
            <button type="submit" class="ru-btn primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                Submit Results
            </button>
        </div>
    </div>

</x-filament-panels::form>
</div>

<script>
(function () {
    const phases = {
        pre:  { total: 5, checked: new Set() },
        ana:  { total: 4, checked: new Set() },
        post: { total: 4, checked: new Set() },
    };

    function updateProgress(phase) {
        const p     = phases[phase];
        const count = p.checked.size;
        const total = p.total;
        const el    = document.getElementById(phase + '-progress');
        if (!el) return;

        el.textContent = count + ' / ' + total;
        el.className = 'ru-phase-progress ' + (
            count === 0    ? 'none' :
            count < total  ? 'partial' : 'done'
        );
    }

    // Wire up custom checklist UI to hidden Livewire checkboxes
    document.querySelectorAll('.ru-check-item').forEach(function (item) {
        item.addEventListener('click', function () {
            const phase  = item.dataset.phase;
            const key    = item.dataset.key;
            const hidden = document.getElementById(phase + '_' + key);

            if (!hidden) return;

            const isChecked = !hidden.checked;
            hidden.checked  = isChecked;

            // Trigger Livewire sync
            hidden.dispatchEvent(new Event('change', { bubbles: true }));

            item.classList.toggle('checked', isChecked);

            if (isChecked) {
                phases[phase].checked.add(key);
            } else {
                phases[phase].checked.delete(key);
            }

            updateProgress(phase);
        });
    });

    // Initialise progress from current state (page refresh / Livewire re-render)
    document.querySelectorAll('.ru-check-item').forEach(function (item) {
        const phase  = item.dataset.phase;
        const key    = item.dataset.key;
        const hidden = document.getElementById(phase + '_' + key);
        if (hidden && hidden.checked) {
            item.classList.add('checked');
            phases[phase].checked.add(key);
        }
    });
    ['pre', 'ana', 'post'].forEach(updateProgress);

    // Critical value toggle
    window.toggleCritical = function () {
        const row     = document.getElementById('ru-critical-row');
        const pill    = document.getElementById('ru-toggle-pill');
        const reason  = document.getElementById('ru-critical-reason');

        // find the hidden Livewire toggle input
        const toggle  = document.querySelector('input[wire\\:model="data.is_critical"]')
                     || document.querySelector('input[id*="is_critical"]');

        const isOn = pill.classList.toggle('on');
        row.classList.toggle('active', isOn);
        reason.classList.toggle('visible', isOn);

        if (toggle) {
            toggle.checked = isOn;
            toggle.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
})();
</script>
</x-filament-panels::page>