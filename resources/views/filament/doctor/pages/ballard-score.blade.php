<x-filament-panels::page>
<style>
    .ballard-container { max-width: 1400px; margin: 0 auto; }

    .form-section {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .dark .form-section { background: #1f2937; border-color: #374151; }

    .section-header {
        background: #f0fdf4;
        border-bottom: 1px solid #bbf7d0;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .dark .section-header { background: #064e3b; border-color: #065f46; }
    .section-title { font-size: 0.85rem; font-weight: 700; color: #166534; }
    .dark .section-title { color: #86efac; }

    /* ── Criterion row ──────────────────────────────────────────────────── */
    .criterion-row {
        padding: 12px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .criterion-row { border-bottom-color: #1e293b; }
    .criterion-row:last-child { border-bottom: none; }

    .criterion-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }
    .dark .criterion-label { color: #e2e8f0; }

    /* ── Pill buttons - all in one row ────────────────────────────────────── */
    .pills-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .score-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 82px;
        max-width: 105px;
        padding: 6px 8px 5px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        transition: all 0.12s ease;
        text-align: center;
        flex: 1 1 82px;
    }
    .dark .score-pill { background: #334155; border-color: #475569; }

    .score-pill:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(59,130,246,0.15);
    }
    .dark .score-pill:hover { background: #1e3a5f; }

    .score-pill.active {
        background: #1d4ed8;
        border-color: #1d4ed8;
        box-shadow: 0 0 0 2px #93c5fd66;
        transform: translateY(-1px);
    }
    .dark .score-pill.active { background: #2563eb; border-color: #60a5fa; }

    .pill-num {
        font-size: 0.95rem;
        font-weight: 800;
        color: #1e3a5f;
        line-height: 1;
        margin-bottom: 3px;
    }
    .score-pill.active .pill-num { color: #fff; }
    .dark .score-pill .pill-num { color: #e2e8f0; }
    .dark .score-pill.active .pill-num { color: #fff; }

    .pill-label {
        font-size: 0.58rem;
        color: #64748b;
        line-height: 1.2;
        word-break: break-word;
        hyphens: auto;
    }
    .score-pill.active .pill-label { color: #bfdbfe; }
    .dark .score-pill .pill-label { color: #94a3b8; }
    .dark .score-pill.active .pill-label { color: #93c5fd; }

    /* ── Subtotal bar ────────────────────────────────────────────────────── */
    .subtotal-row {
        background: #f0fdf4;
        border-top: 1px solid #bbf7d0;
        padding: 8px 20px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
    }
    .dark .subtotal-row { background: #064e3b; border-color: #065f46; }
    .subtotal-label { font-size: 0.72rem; color: #166534; font-weight: 600; }
    .dark .subtotal-label { color: #86efac; }
    .subtotal-value { font-size: 1.25rem; font-weight: 800; color: #166534; }
    .dark .subtotal-value { color: #4ade80; }

    /* ── Result & misc ───────────────────────────────────────────────────── */
    .result-box {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 2px solid #bfdbfe;
        border-radius: 14px;
        padding: 18px 24px;
        text-align: center;
        margin-bottom: 20px;
    }
    .dark .result-box { background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 100%); border-color: #3b82f6; }

    .result-score { font-size: 2.4rem; font-weight: 800; color: #1d4ed8; }
    .dark .result-score { color: #60a5fa; }
    .result-ga { font-size: 1.7rem; font-weight: 800; color: #059669; }
    .dark .result-ga { color: #34d399; }

    .form-label { display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: 5px; }
    .form-input { width: 100%; border-radius: 8px; padding: 9px 12px; font-size: 0.875rem; border: 1px solid #d1d5db; background: #fff; outline: none; }
    .form-input:disabled { background: #f3f4f6; color: #374151; cursor: not-allowed; }
    .dark .form-input { background: #374151; border-color: #4b5563; color: #f3f4f6; }
    .dark .form-input:disabled { background: #1f2937; color: #9ca3af; }

    .btn-primary { background: #1d4ed8; color: #fff; border: none; padding: 12px 28px; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; }
    .btn-primary:hover { background: #1e40af; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; cursor: pointer; }

    .section-spacing {
        margin-bottom: 20px;
    }
</style>

@php
    $nmCriteria = [
        ['field' => 'nmPosture',        'label' => 'Posture',               'max' => 4,
         'descs' => [
             0 => 'All ext. extended',
             1 => 'Slight flex hips/knees',
             2 => 'Moderate flexion',
             3 => 'Brisk flexion',
             4 => 'Full flexion all 4',
         ]],
        ['field' => 'nmSquareWindow',   'label' => 'Square Window',         'max' => 5,
         'descs' => [
             0 => '> 90°',
             1 => '90°',
             2 => '60°',
             3 => '45°',
             4 => '30°',
             5 => '0°',
         ]],
        ['field' => 'nmArmRecoil',      'label' => 'Arm Recoil',            'max' => 4,
         'descs' => [
             0 => '180°, no recoil',
             1 => '140–180° slow',
             2 => '110–140° brisk',
             3 => '90–110° v. brisk',
             4 => '< 90° extr. brisk',
         ]],
        ['field' => 'nmPoplitealAngle', 'label' => 'Popliteal Angle',       'max' => 5,
         'descs' => [
             0 => '180°',
             1 => '160°',
             2 => '140°',
             3 => '120°',
             4 => '100°',
             5 => '≤ 90°',
         ]],
        ['field' => 'nmScarfSign',      'label' => 'Scarf Sign',            'max' => 4,
         'descs' => [
             0 => 'Past opp. axillary',
             1 => 'To opp. axillary',
             2 => 'At midsternal',
             3 => 'Not midsternal',
             4 => 'Not ipsilat. axillary',
         ]],
        ['field' => 'nmHeelToEar',      'label' => 'Heel to Ear',           'max' => 5,
         'descs' => [
             0 => '90°, easy',
             1 => '≈ 80°',
             2 => '≈ 70°',
             3 => '≈ 60°',
             4 => '≈ 50°',
             5 => '≈ 40°, resist.',
         ]],
    ];

    $pmCriteria = [
        ['field' => 'pmSkin',           'label' => 'Skin',                  'max' => 5,
         'descs' => [
             0 => 'Gelatinous, red',
             1 => 'Smooth, pink',
             2 => 'Superficial peeling',
             3 => 'Cracking, pale',
             4 => 'Parchment, deep crack',
             5 => 'Leathery, wrinkled',
         ]],
        ['field' => 'pmLanugo',         'label' => 'Lanugo',                'max' => 4,
         'descs' => [
             0 => 'None (sparse)',
             1 => 'Abundant',
             2 => 'Thinning',
             3 => 'Bald areas',
             4 => 'Mostly bald',
         ]],
        ['field' => 'pmPlantarSurface', 'label' => 'Plantar Surface',       'max' => 4,
         'descs' => [
             0 => '> 50mm, no crease',
             1 => 'Faint red marks',
             2 => 'Ant. crease only',
             3 => 'Creases ant. 2/3',
             4 => 'Creases entire sole',
         ]],
        ['field' => 'pmBreast',         'label' => 'Breast',                'max' => 5,
         'descs' => [
             0 => 'Imperceptible',
             1 => 'Barely perceptible',
             2 => 'Flat areola, no bud',
             3 => 'Stippled, 1–2mm bud',
             4 => 'Raised, 3–4mm bud',
             5 => 'Full, 5–10mm bud',
         ]],
        ['field' => 'pmEyeEar',         'label' => 'Eye / Ear',             'max' => 5,
         'descs' => [
             0 => 'Lids fused loosely',
             1 => 'Pinna flat, folded',
             2 => 'Sl. curved, soft',
             3 => 'Well-curved, ready',
             4 => 'Formed, firm, instant',
             5 => 'Thick cartilage, stiff',
         ]],
        ['field' => 'pmGenitals',       'label' => 'Genitals (♂/♀)',        'max' => 5,
         'descs' => [
             0 => '♂ Flat / ♀ Clitoris prom.',
             1 => '♂ Testes in canal / ♀ Sm. minora',
             2 => '♂ Descending / ♀ Enl. minora',
             3 => '♂ Good rugae / ♀ Equally prom.',
             4 => '♂ Pendulous / ♀ Majora lg.',
             5 => '♂ Deep rugae / ♀ Majora covers all',
         ]],
    ];

    $birthDt        = $visit->nicuAdmission?->date_time_of_birth;
    $ageAtExamHours = null;
    if ($birthDt && $examDatetime) {
        try {
            $ageAtExamHours = (int) \Carbon\Carbon::parse($birthDt)
                ->diffInHours(\Carbon\Carbon::parse($examDatetime));
        } catch (\Exception $e) {}
    }
@endphp

<div class="ballard-container">

    {{-- ── Header ────────────────────────────────────────────────────────── --}}
    <div style="background: linear-gradient(135deg, #1e3a5f, #1d4ed8); border-radius: 12px; padding: 14px 22px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div>
                <p style="color: #fff; font-size: 1rem; font-weight: 700; margin: 0;">{{ $visit->patient->display_name ?? 'Baby' }}</p>
                <p style="color: #93c5fd; font-size: 0.72rem; margin: 2px 0 0;">
                    {{ $visit->patient->case_no ?? $visit->patient->temporary_case_no }}
                    &nbsp;|&nbsp; Born:
                    {{ $birthDt ? \Carbon\Carbon::parse($birthDt)->format('M d, Y h:i A') : '—' }}
                </p>
            </div>
            @if($examNumber == 1)
                <span style="background:#059669;color:#fff;padding:4px 14px;border-radius:20px;font-size:0.72rem;font-weight:700;">1st Exam</span>
            @else
                <span style="background:#f59e0b;color:#fff;padding:4px 14px;border-radius:20px;font-size:0.72rem;font-weight:700;">2nd Exam</span>
            @endif
        </div>
    </div>

    <form wire:submit="save">

        {{-- ── Exam Information ────────────────────────────────────────────── --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">📋 Exam Information</span>
            </div>
            <div style="padding: 16px 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div>
                        <label class="form-label">Exam Date & Time <span style="color:#dc2626">*</span></label>
                        <input type="datetime-local" wire:model.live="examDatetime" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Age at Exam</label>
                        <input type="text" class="form-input" readonly disabled
                            value="{{ $ageAtExamHours !== null
                                ? $ageAtExamHours . ' hour' . ($ageAtExamHours === 1 ? '' : 's') . ' old'
                                : 'Set exam date/time above' }}">
                    </div>
                    <div>
                        <label class="form-label">Examiner</label>
                        <input type="text" class="form-input" value="{{ auth()->user()->name }}" readonly disabled>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Result Banner ────────────────────────────────────────────────── --}}
        <div class="result-box">
            <div style="display: flex; justify-content: center; align-items: center; gap: 48px; flex-wrap: wrap;">
                <div>
                    <p style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:#1e40af;margin:0 0 2px;">TOTAL SCORE</p>
                    <p class="result-score">{{ $totalScore ?? '—' }}</p>
                </div>
                <div style="width:1px;height:50px;background:#bfdbfe;"></div>
                <div>
                    <p style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:#065f46;margin:0 0 2px;">ESTIMATED GESTATIONAL AGE</p>
                    <p class="result-ga">{{ $estimatedGaWeeks ? $estimatedGaWeeks . ' weeks' : '—' }}</p>
                    @if($estimatedGaWeeks)
                        @php $ga = $estimatedGaWeeks; @endphp
                        <div style="margin-top:4px;">
                        @if($ga < 34)
                            <span style="background:#fef3c7;color:#92400e;padding:3px 14px;border-radius:20px;font-size:0.7rem;font-weight:700;">⚠ VERY PRETERM</span>
                        @elseif($ga < 37)
                            <span style="background:#fef9c3;color:#713f12;padding:3px 14px;border-radius:20px;font-size:0.7rem;font-weight:700;">⚠ PRETERM</span>
                        @elseif($ga <= 42)
                            <span style="background:#dcfce7;color:#166534;padding:3px 14px;border-radius:20px;font-size:0.7rem;font-weight:700;">✓ TERM</span>
                        @else
                            <span style="background:#fee2e2;color:#991b1b;padding:3px 14px;border-radius:20px;font-size:0.7rem;font-weight:700;">⚠ POST-TERM</span>
                        @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Neuromuscular Maturity (Top section) ─────────────────────────── --}}
        <div class="form-section section-spacing">
            <div class="section-header">
                <span class="section-title">🧠 NEUROMUSCULAR MATURITY</span>
                <span style="font-size:0.72rem;color:#166534;font-weight:700;">{{ $this->nmSubtotal() }} / 27</span>
            </div>

            @foreach($nmCriteria as $crit)
            @php $field = $crit['field']; $selected = $this->{$field}; @endphp
            <div class="criterion-row">
                <div class="criterion-label">{{ $crit['label'] }}</div>
                <div class="pills-row">
                    @for($i = 0; $i <= $crit['max']; $i++)
                    <button type="button"
                        wire:click="$set('{{ $field }}', {{ $i }})"
                        class="score-pill {{ $selected === $i ? 'active' : '' }}">
                        <span class="pill-num">{{ $i }}</span>
                        <span class="pill-label">{{ $crit['descs'][$i] }}</span>
                    </button>
                    @endfor
                </div>
            </div>
            @endforeach

            <div class="subtotal-row">
                <span class="subtotal-label">Neuromuscular Subtotal</span>
                <span class="subtotal-value">{{ $this->nmSubtotal() }}</span>
                <span class="subtotal-label">/ 27</span>
            </div>
        </div>

        {{-- ── Physical Maturity (Bottom section) ───────────────────────────── --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">👶 PHYSICAL MATURITY</span>
                <span style="font-size:0.72rem;color:#166534;font-weight:700;">{{ $this->pmSubtotal() }} / 28</span>
            </div>

            @foreach($pmCriteria as $crit)
            @php $field = $crit['field']; $selected = $this->{$field}; @endphp
            <div class="criterion-row">
                <div class="criterion-label">{{ $crit['label'] }}</div>
                <div class="pills-row">
                    @for($i = 0; $i <= $crit['max']; $i++)
                    <button type="button"
                        wire:click="$set('{{ $field }}', {{ $i }})"
                        class="score-pill {{ $selected === $i ? 'active' : '' }}">
                        <span class="pill-num">{{ $i }}</span>
                        <span class="pill-label">{{ $crit['descs'][$i] }}</span>
                    </button>
                    @endfor
                </div>
            </div>
            @endforeach

            <div class="subtotal-row">
                <span class="subtotal-label">Physical Subtotal</span>
                <span class="subtotal-value">{{ $this->pmSubtotal() }}</span>
                <span class="subtotal-label">/ 28</span>
            </div>
        </div>

        {{-- ── Score Summary ────────────────────────────────────────────────── --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">📊 SCORE SUMMARY</span>
            </div>
            <div style="padding: 16px 20px; text-align: center;">
                <div style="display: flex; justify-content: center; gap: 36px; flex-wrap: wrap; align-items: flex-start;">
                    <div>
                        <p style="font-size:0.68rem;color:#6b7280;text-transform:uppercase;margin-bottom:4px;">Neuromuscular</p>
                        <p style="font-size:1.9rem;font-weight:800;color:#1d4ed8;margin:0;">{{ $this->nmSubtotal() }}</p>
                        <p style="font-size:0.62rem;color:#94a3b8;margin:0;">/ 27</p>
                    </div>
                    <div style="font-size:1.8rem;color:#e2e8f0;padding-top:14px;">+</div>
                    <div>
                        <p style="font-size:0.68rem;color:#6b7280;text-transform:uppercase;margin-bottom:4px;">Physical</p>
                        <p style="font-size:1.9rem;font-weight:800;color:#1d4ed8;margin:0;">{{ $this->pmSubtotal() }}</p>
                        <p style="font-size:0.62rem;color:#94a3b8;margin:0;">/ 28</p>
                    </div>
                    <div style="font-size:1.8rem;color:#e2e8f0;padding-top:14px;">=</div>
                    <div>
                        <p style="font-size:0.68rem;color:#6b7280;text-transform:uppercase;margin-bottom:4px;">Total Score</p>
                        <p style="font-size:2.2rem;font-weight:800;color:#1d4ed8;margin:0;">{{ $totalScore ?? '—' }}</p>
                        <p style="font-size:0.62rem;color:#94a3b8;margin:0;">/ 55</p>
                    </div>
                    <div style="font-size:1.8rem;color:#e2e8f0;padding-top:14px;">→</div>
                    <div>
                        <p style="font-size:0.68rem;color:#6b7280;text-transform:uppercase;margin-bottom:4px;">Gestational Age</p>
                        <p style="font-size:1.9rem;font-weight:800;color:#059669;margin:0;">{{ $estimatedGaWeeks ? $estimatedGaWeeks . ' wks' : '—' }}</p>
                        @if($estimatedGaWeeks)
                            @php $ga = $estimatedGaWeeks; @endphp
                            @if($ga < 34)
                                <span style="background:#fef3c7;color:#92400e;padding:2px 10px;border-radius:20px;font-size:0.68rem;font-weight:700;">Very Preterm</span>
                            @elseif($ga < 37)
                                <span style="background:#fef9c3;color:#713f12;padding:2px 10px;border-radius:20px;font-size:0.68rem;font-weight:700;">Preterm</span>
                            @elseif($ga <= 42)
                                <span style="background:#dcfce7;color:#166534;padding:2px 10px;border-radius:20px;font-size:0.68rem;font-weight:700;">Term</span>
                            @else
                                <span style="background:#fee2e2;color:#991b1b;padding:2px 10px;border-radius:20px;font-size:0.68rem;font-weight:700;">Post-term</span>
                            @endif
                        @endif
                    </div>
                </div>

                @php
                    $allVals = [$nmPosture,$nmSquareWindow,$nmArmRecoil,$nmPoplitealAngle,$nmScarfSign,$nmHeelToEar,$pmSkin,$pmLanugo,$pmPlantarSurface,$pmBreast,$pmEyeEar,$pmGenitals];
                    $filledCount = count(array_filter($allVals, fn($v) => $v !== null));
                @endphp
                @if($filledCount < 12)
                <p style="margin-top:10px;font-size:0.78rem;color:#f59e0b;">
                    ⚠ Fill in all 12 criteria to compute the total score.
                    ({{ $filledCount }} / 12 filled)
                </p>
                @endif
            </div>
        </div>

        {{-- ── Action Buttons ───────────────────────────────────────────────── --}}
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; padding-bottom: 40px;">
            <button type="button"
                onclick="window.location.href='/doctor/patient-chart?visitId={{ $visitId }}&tab=ballard'"
                class="btn-secondary">
                Cancel
            </button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>💾 Save Ballard Score</span>
                <span wire:loading>Saving…</span>
            </button>
        </div>

    </form>
</div>
</x-filament-panels::page>