<x-filament-panels::page>
<style>
    .ob-container { max-width: 1100px; margin: 0 auto; }
    .ob-sec { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .dark .ob-sec { background: #1f2937; border-color: #374151; }
    .ob-sec-head { background: #fdf2f8; border-bottom: 1px solid #fce7f3; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
    .dark .ob-sec-head { background: #4a044e; border-color: #86198f; }
    .ob-sec-title { font-size: 0.85rem; font-weight: 700; color: #9d174d; }
    .dark .ob-sec-title { color: #f0abfc; }
    .ob-sec-body { padding: 16px 20px; }

    .fg  { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
    .fg3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .fg2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .fg6 { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; }
    .cf { grid-column: span 4; }
    .c2 { grid-column: span 2; }

    .form-label { display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; margin-bottom: 5px; }
    .form-input { width: 100%; border-radius: 8px; padding: 9px 11px; font-size: 0.875rem; border: 1px solid #d1d5db; background: #fff; outline: none; box-sizing: border-box; }
    .form-input:focus { border-color: #db2777; box-shadow: 0 0 0 3px rgba(219,39,119,.1); }
    .form-input:disabled, .form-input[readonly] { background: #f9fafb; color: #6b7280; cursor: not-allowed; }
    .dark .form-input { background: #374151; border-color: #4b5563; color: #f3f4f6; }
    .dark .form-input:disabled, .dark .form-input[readonly] { background: #1f2937; color: #9ca3af; }
    textarea.form-input { resize: vertical; min-height: 80px; }

    .info-pill { display: inline-block; background: #fdf2f8; border: 1px solid #f9a8d4; border-radius: 8px; padding: 4px 12px; font-size: 0.75rem; font-weight: 600; color: #9d174d; margin-right: 6px; }

    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .badge-admitted { background: #d1fae5; color: #065f46; }
    .badge-pending  { background: #fee2e2; color: #991b1b; }

    .admit-box { background: #f0fdf4; border: 2px solid #86efac; border-radius: 10px; padding: 16px; }
    .dark .admit-box { background: #14532d; border-color: #16a34a; }

    .btn-primary { background: linear-gradient(135deg,#9d174d,#db2777); color: #fff; border: none; padding: 12px 28px; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; }
    .btn-primary:hover { opacity: .9; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; cursor: pointer; }
    .dark .btn-secondary { background: #374151; color: #e2e8f0; border-color: #4b5563; }
</style>

<div class="ob-container">

    {{-- ── Patient Header ──────────────────────────────────────────────────── --}}
    <div style="background:linear-gradient(135deg,#9d174d,#db2777);border-radius:12px;padding:16px 24px;margin-bottom:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <div>
                <p style="color:#fff;font-size:1.05rem;font-weight:700;margin:0;">{{ $patientName }}</p>
                <p style="color:#fbcfe8;font-size:0.75rem;margin:3px 0 0;">
                    {{ $caseNo }}
                    @if($gptal) &nbsp;|&nbsp; {{ $gptal }} @endif
                    @if($aog) &nbsp;|&nbsp; AOG: {{ $aog }} @endif
                    @if($lmp) &nbsp;|&nbsp; LMP: {{ $lmp }} @endif
                    @if($chiefComplaint) &nbsp;|&nbsp; CC: {{ $chiefComplaint }} @endif
                </p>
            </div>
            @if($isAdmitted)
                <span class="status-badge badge-admitted"><x-heroicon-o-check-circle class="w-4 h-4" /> ADMITTED TO OB</span>
            @else
                <span class="status-badge badge-pending">PENDING ADMISSION</span>
            @endif
        </div>
    </div>

    <form wire:submit="save">

        {{-- ── Triage Vitals (Read-only, entered by nurse) ────────────────── --}}
        @if($triageBp || $triagePulse || $triageTemp || $triageRr)
        <div class="ob-sec">
            <div class="ob-sec-head">
                <span class="ob-sec-title">📋 Triage Vitals (Entered by Nurse)</span>
            </div>
            <div class="ob-sec-body">
                <div class="fg">
                    <div>
                        <label class="form-label">Blood Pressure</label>
                        <input type="text" class="form-input" value="{{ $triageBp ?? '—' }}" readonly disabled>
                    </div>
                    <div>
                        <label class="form-label">Pulse</label>
                        <input type="text" class="form-input" value="{{ $triagePulse ?? '—' }}" readonly disabled>
                    </div>
                    <div>
                        <label class="form-label">Temperature (°C)</label>
                        <input type="text" class="form-input" value="{{ $triageTemp ?? '—' }}" readonly disabled>
                    </div>
                    <div>
                        <label class="form-label">RR</label>
                        <input type="text" class="form-input" value="{{ $triageRr ?? '—' }}" readonly disabled>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Internal Examination (IE) ───────────────────────────────────── --}}
        <div class="ob-sec">
            <div class="ob-sec-head">
                <span class="ob-sec-title">🔍 Internal Examination (IE)</span>
            </div>
            <div class="ob-sec-body">
                <div class="fg">
                    <div>
                        <label class="form-label">Cervical Dilation</label>
                        <input type="text" wire:model="ieCervicalDilation" class="form-input" placeholder="e.g. 4 cm">
                    </div>
                    <div>
                        <label class="form-label">Effacement</label>
                        <input type="text" wire:model="ieEffacement" class="form-input" placeholder="e.g. 80%">
                    </div>
                    <div>
                        <label class="form-label">Station</label>
                        <input type="text" wire:model="ieStation" class="form-input" placeholder="e.g. -1">
                    </div>
                    <div>
                        <label class="form-label">Membranes</label>
                        <input type="text" wire:model="ieMembranes" class="form-input" placeholder="Intact / Ruptured">
                    </div>
                    <div>
                        <label class="form-label">Presentation</label>
                        <input type="text" wire:model="iePresentation" class="form-input" placeholder="e.g. Cephalic">
                    </div>
                    <div class="c2">
                        <label class="form-label">Other IE Findings</label>
                        <input type="text" wire:model="ieOtherFindings" class="form-input" placeholder="Additional findings">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Fetal Assessment ─────────────────────────────────────────────── --}}
        <div class="ob-sec">
            <div class="ob-sec-head">
                <span class="ob-sec-title">👶 Fetal Assessment</span>
            </div>
            <div class="ob-sec-body">
                <div class="fg">
                    <div>
                        <label class="form-label">Fetal Heart Tone (FHT)</label>
                        <input type="text" wire:model="fetalHeartTone" class="form-input" placeholder="e.g. 142 bpm">
                    </div>
                    <div>
                        <label class="form-label">Fundic Height</label>
                        <input type="text" wire:model="fundicHeight" class="form-input" placeholder="e.g. 34 cm">
                    </div>
                    <div>
                        <label class="form-label">Fetal Presentation</label>
                        <input type="text" wire:model="fetalPresentation" class="form-input" placeholder="e.g. Cephalic">
                    </div>
                    <div>
                        <label class="form-label">Fetal Position</label>
                        <input type="text" wire:model="fetalPosition" class="form-input" placeholder="e.g. LOA">
                    </div>
                    <div>
                        <label class="form-label">Engagement</label>
                        <input type="text" wire:model="engagement" class="form-input" placeholder="e.g. Engaged">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Diagnosis on Admission ───────────────────────────────────────── --}}
        <div class="ob-sec">
            <div class="ob-sec-head">
                <span class="ob-sec-title">📋 Diagnosis on Admission <span style="color:#f43f5e;">*</span></span>
            </div>
            <div class="ob-sec-body">
                <textarea wire:model="diagnosisOnAdmission" rows="3" class="form-input"
                    placeholder="e.g. G3P2 (2-0-0-2), 38 weeks AOG, in active labor, LOA, live fetus, cephalic presentation, BOW intact"></textarea>
            </div>
        </div>

        {{-- ── Doctor's Orders ──────────────────────────────────────────────── --}}
        <div class="ob-sec">
            <div class="ob-sec-head">
                <span class="ob-sec-title">📝 Doctor's Orders</span>
            </div>
            <div class="ob-sec-body">
                <textarea wire:model="orderText" rows="6" class="form-input"
                    placeholder="One order per line, e.g.:&#10;Start IV D5LR 1L x 8 hrs&#10;Monitor FHT q 30 min&#10;NPO except ice chips&#10;Prep for normal spontaneous delivery"></textarea>
                <p style="font-size:0.7rem;color:#6b7280;margin-top:4px;">Each line becomes a separate order for the nurse to carry out.</p>
            </div>
        </div>

        {{-- ── Admission Decision ───────────────────────────────────────────── --}}
        <div style="margin-bottom:20px;">
            @if($isAdmitted)
                <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:10px;padding:16px;text-align:center;">
                    <p style="margin:0;color:#065f46;font-weight:700;font-size:0.85rem;">
                        <x-heroicon-o-check-circle class="w-4 h-4 inline" /> This patient has already been admitted to OB Ward.
                    </p>
                    <p style="margin:4px 0 0;font-size:0.75rem;color:#065f46;">
                        Admitted: {{ $visit->doctor_admitted_at ? \Carbon\Carbon::parse($visit->doctor_admitted_at)->format('M d, Y h:i A') : '—' }}
                    </p>
                </div>
            @else
                <div class="admit-box">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" wire:model.live="admitToOb"
                               style="width:18px;height:18px;accent-color:#059669;">
                        <span style="font-weight:700;font-size:0.9rem;color:#065f46;">ADMIT THIS PATIENT TO OB WARD</span>
                    </label>
                    @if($admitToOb)
                        <div style="margin-top:10px;padding:8px 12px;background:#bbf7d0;border-radius:6px;">
                            <p style="margin:0;font-size:0.75rem;color:#065f46;">
                                When saved, this patient will be officially admitted to OB. Status will change to "Admitted"
                                and the nurse can then complete the full OB Record.
                                The clerk will be notified for registration completion.
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── Buttons ─────────────────────────────────────────────────────── --}}
        <div style="display:flex;justify-content:flex-end;gap:12px;padding-bottom:40px;">
            <button type="button" onclick="window.location.href='/doctor/ob-patients'" class="btn-secondary">Cancel</button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    @if($admitToOb && !$isAdmitted) 💊 Admit & Save
                    @elseif($isAdmitted) 💾 Update Assessment
                    @else 💾 Save Assessment
                    @endif
                </span>
                <span wire:loading>Saving…</span>
            </button>
        </div>

    </form>
</div>
</x-filament-panels::page>