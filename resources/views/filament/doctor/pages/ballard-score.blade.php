<x-filament-panels::page>
<style>
    .ballard-container { max-width: 1400px; margin: 0 auto; }
    .form-section { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .dark .form-section { background: #1f2937; border-color: #374151; }
    .section-header { background: #f0fdf4; border-bottom: 1px solid #bbf7d0; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
    .dark .section-header { background: #064e3b; border-color: #065f46; }
    .section-title { font-size: 0.9rem; font-weight: 700; color: #166534; }
    .dark .section-title { color: #86efac; }
    .section-body { padding: 20px; }

    /* ── Scoring table layout ─────────────────────────────────────────── */
    .score-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    .score-table th { background: #1e3a5f; color: #fff; padding: 8px 10px; text-align: center; font-size: 0.75rem; font-weight: 700; border: 1px solid #1e40af; white-space: nowrap; }
    .dark .score-table th { background: #0f172a; border-color: #1e3a8a; }
    .score-table td { padding: 6px 8px; border: 1px solid #e5e7eb; vertical-align: middle; }
    .dark .score-table td { border-color: #374151; }
    .score-table tr:nth-child(even) td { background: #f8fafc; }
    .dark .score-table tr:nth-child(even) td { background: #1a2332; }
    .score-table tr:hover td { background: #eff6ff; }
    .dark .score-table tr:hover td { background: #1e2d45; }

    .criterion-name { font-weight: 700; color: #1e293b; min-width: 130px; }
    .dark .criterion-name { color: #e2e8f0; }
    .desc-cell { color: #64748b; font-size: 0.72rem; font-style: italic; max-width: 180px; }
    .dark .desc-cell { color: #94a3b8; }

    .score-btn { min-width: 34px; height: 30px; border-radius: 5px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; justify-content: center; transition: all 0.1s; margin: 1px; }
    .score-btn:hover { background: #dbeafe; border-color: #3b82f6; }
    .score-btn.active { background: #1d4ed8; color: #fff; border-color: #1d4ed8; box-shadow: 0 0 0 2px #93c5fd; }
    .dark .score-btn { background: #334155; border-color: #475569; color: #e2e8f0; }
    .dark .score-btn.active { background: #3b82f6; border-color: #60a5fa; color: #fff; }
    .score-btn.na { opacity: 0.3; cursor: default; }

    .subtotal-row td { background: #f0fdf4 !important; font-weight: 700; color: #166534; font-size: 0.85rem; }
    .dark .subtotal-row td { background: #064e3b !important; color: #86efac; }

    .result-box { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #bfdbfe; border-radius: 16px; padding: 20px 24px; text-align: center; margin-bottom: 24px; }
    .dark .result-box { background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 100%); border-color: #3b82f6; }
    .result-score { font-size: 2.5rem; font-weight: 800; color: #1d4ed8; }
    .dark .result-score { color: #60a5fa; }
    .result-ga { font-size: 1.8rem; font-weight: 800; color: #059669; }
    .dark .result-ga { color: #34d399; }

    .form-label { display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: 5px; }
    .form-input { width: 100%; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem; border: 1px solid #d1d5db; background: #fff; outline: none; }
    .form-input:disabled { background: #f3f4f6; color: #374151; cursor: not-allowed; }
    .dark .form-input { background: #374151; border-color: #4b5563; color: #f3f4f6; }
    .dark .form-input:disabled { background: #1f2937; color: #9ca3af; }

    .btn-primary { background: #1d4ed8; color: #fff; border: none; padding: 12px 28px; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; }
    .btn-primary:hover { background: #1e40af; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; cursor: pointer; }
</style>

@php
    $nmCriteria = [
        ['field' => 'nmPosture',        'label' => 'Posture',              'max' => 4,
         'descs' => [
             0 => 'All extremities extended, hips flat',
             1 => 'Slight flexion of hips & knees; arms extended',
             2 => 'Moderate flexion of hips & knees',
             3 => 'Brisk flexion of hips & knees; arms slightly flexed',
             4 => 'Full flexion of all four extremities',
         ]],
        ['field' => 'nmSquareWindow',   'label' => 'Square Window (Wrist)', 'max' => 5,
         'descs' => [
             0 => '> 90°',
             1 => '90°',
             2 => '60°',
             3 => '45°',
             4 => '30°',
             5 => '0°',
         ]],
        ['field' => 'nmArmRecoil',      'label' => 'Arm Recoil',           'max' => 4,
         'descs' => [
             0 => '180° – no recoil',
             1 => '140°–180° – slow recoil',
             2 => '110°–140° – brisk recoil',
             3 => '90°–110° – very brisk recoil',
             4 => '< 90° – extremely brisk',
         ]],
        ['field' => 'nmPoplitealAngle', 'label' => 'Popliteal Angle',      'max' => 5,
         'descs' => [
             0 => '180°',
             1 => '160°',
             2 => '140°',
             3 => '120°',
             4 => '100°',
             5 => '≤ 90°',
         ]],
        ['field' => 'nmScarfSign',      'label' => 'Scarf Sign',           'max' => 4,
         'descs' => [
             0 => 'Elbow past opposite axillary line',
             1 => 'Elbow to opposite axillary line',
             2 => 'Elbow at midsternal line',
             3 => 'Elbow does not reach midsternal line',
             4 => 'Elbow does not cross ipsilateral ant. axillary line',
         ]],
        ['field' => 'nmHeelToEar',      'label' => 'Heel to Ear',          'max' => 5,
         'descs' => [
             0 => '90° – heel easily to ear',
             1 => '≈ 80°',
             2 => '≈ 70°',
             3 => '≈ 60°',
             4 => '≈ 50°',
             5 => '≈ 40° – significant resistance',
         ]],
    ];

    $pmCriteria = [
        ['field' => 'pmSkin',           'label' => 'Skin',                 'max' => 5,
         'descs' => [
             0 => 'Gelatinous, red, translucent',
             1 => 'Smooth, pink, visible veins',
             2 => 'Superficial peeling &/or rash; few veins',
             3 => 'Cracking, pale areas; rare veins',
             4 => 'Parchment, deep cracking; no vessels',
             5 => 'Leathery, cracked, wrinkled',
         ]],
        ['field' => 'pmLanugo',         'label' => 'Lanugo',               'max' => 4,
         'descs' => [
             0 => 'None (sparse)',
             1 => 'Abundant',
             2 => 'Thinning',
             3 => 'Bald areas',
             4 => 'Mostly bald',
         ]],
        ['field' => 'pmPlantarSurface', 'label' => 'Plantar Surface',      'max' => 4,
         'descs' => [
             0 => '> 50 mm; no crease',
             1 => 'Faint red marks',
             2 => 'Anterior transverse crease only',
             3 => 'Creases on anterior 2/3',
             4 => 'Creases over entire sole',
         ]],
        ['field' => 'pmBreast',         'label' => 'Breast',               'max' => 5,
         'descs' => [
             0 => 'Imperceptible',
             1 => 'Barely perceptible',
             2 => 'Flat areola; no bud',
             3 => 'Stippled areola; 1–2 mm bud',
             4 => 'Raised areola; 3–4 mm bud',
             5 => 'Full areola; 5–10 mm bud',
         ]],
        ['field' => 'pmEyeEar',         'label' => 'Eye / Ear',            'max' => 5,
         'descs' => [
             0 => 'Lids fused loosely (–1)',
             1 => 'Lids open; pinna flat, stays folded',
             2 => 'Sl. curved pinna; soft, slow recoil',
             3 => 'Well-curved pinna; soft but ready recoil',
             4 => 'Formed & firm; instant recoil',
             5 => 'Thick cartilage; ear stiff',
         ]],
        ['field' => 'pmGenitals',       'label' => 'Genitals (♂/♀)',       'max' => 5,
         'descs' => [
             0 => '♂ Flat scrotum / ♀ Clitoris prominent, labia flat',
             1 => '♂ Testes in canal, faint rugae / ♀ Clitoris prom., sm. minora',
             2 => '♂ Testes descending, few rugae / ♀ Clitoris prom., enl. minora',
             3 => '♂ Testes down, good rugae / ♀ Majora & minora equally prom.',
             4 => '♂ Testes pendulous, deep rugae / ♀ Majora large, minora sm.',
             5 => '♂ Testes pendulous, rugae over scrotum / ♀ Majora covers clitoris & minora',
         ]],
    ];

    // Age at exam — computed in PHP so it updates on every Livewire re-render
    // triggered by wire:model.live on the examDatetime field.
    $birthDt        = $visit->nicuAdmission?->date_time_of_birth;
    $ageAtExamHours = null;
    if ($birthDt && $examDatetime) {
        try {
            $diff = \Carbon\Carbon::parse($birthDt)
                        ->diffInHours(\Carbon\Carbon::parse($examDatetime));
            $ageAtExamHours = (int) $diff;
        } catch (\Exception $e) {}
    }
@endphp

<div class="ballard-container">
    {{-- ── Header ────────────────────────────────────────────────────────── --}}
    <div style="background: linear-gradient(135deg, #1e3a5f, #1d4ed8); border-radius: 12px; padding: 16px 24px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div>
                <p style="color: #fff; font-size: 1.05rem; font-weight: 700; margin: 0;">{{ $visit->patient->display_name ?? 'Baby' }}</p>
                <p style="color: #93c5fd; font-size: 0.75rem; margin: 2px 0 0;">
                    {{ $visit->patient->case_no ?? $visit->patient->temporary_case_no }}
                    &nbsp;|&nbsp; Born:
                    {{ $birthDt
                        ? \Carbon\Carbon::parse($birthDt)->format('M d, Y h:i A')
                        : '—' }}
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                @if($examNumber == 1)
                    <span style="background: #059669; color: #fff; padding: 4px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">1st Exam (X)</span>
                @else
                    <span style="background: #f59e0b; color: #fff; padding: 4px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">2nd Exam (O)</span>
                @endif
            </div>
        </div>
    </div>

    <form wire:submit="save">

        {{-- ── Exam Information ────────────────────────────────────────────── --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">📋 Exam Information</span>
            </div>
            <div class="section-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
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

        {{-- ── Result Summary ───────────────────────────────────────────────── --}}
        <div class="result-box">
            <div style="display: flex; justify-content: center; align-items: center; gap: 48px; flex-wrap: wrap;">
                <div>
                    <p style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:#1e40af;margin:0 0 4px;">TOTAL SCORE</p>
                    <p class="result-score">{{ $totalScore ?? '—' }}</p>
                </div>
                <div style="width:1px;height:60px;background:#bfdbfe;"></div>
                <div>
                    <p style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:#065f46;margin:0 0 4px;">ESTIMATED GESTATIONAL AGE</p>
                    <p class="result-ga">{{ $estimatedGaWeeks ? $estimatedGaWeeks . ' weeks' : '—' }}</p>
                    @if($estimatedGaWeeks)
                        @php $ga = $estimatedGaWeeks; @endphp
                        <div style="margin-top:6px;">
                        @if($ga < 34)
                            <span style="background:#fef3c7;color:#92400e;padding:3px 14px;border-radius:20px;font-size:0.72rem;font-weight:700;">⚠ VERY PRETERM</span>
                        @elseif($ga < 37)
                            <span style="background:#fef9c3;color:#713f12;padding:3px 14px;border-radius:20px;font-size:0.72rem;font-weight:700;">⚠ PRETERM</span>
                        @elseif($ga <= 42)
                            <span style="background:#dcfce7;color:#166534;padding:3px 14px;border-radius:20px;font-size:0.72rem;font-weight:700;">✓ TERM</span>
                        @else
                            <span style="background:#fee2e2;color:#991b1b;padding:3px 14px;border-radius:20px;font-size:0.72rem;font-weight:700;">⚠ POST-TERM</span>
                        @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Two-column layout: NM + PM ─────────────────────────────────── --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

            {{-- ── Neuromuscular Maturity ─────────────────────────────────── --}}
            <div class="form-section">
                <div class="section-header">
                    <span class="section-title">🧠 NEUROMUSCULAR MATURITY</span>
                    <span style="font-size:0.75rem;color:#166534;font-weight:700;">Subtotal: {{ $this->nmSubtotal() }} / 27</span>
                </div>
                <div class="section-body" style="padding: 12px;">
                    <table class="score-table">
                        <thead>
                            <tr>
                                <th style="text-align:left;min-width:130px;">Criterion</th>
                                <th colspan="6">Score (select one)</th>
                                <th>Selected</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($nmCriteria as $crit)
                        @php
                            $field    = $crit['field'];
                            $selected = $this->{$field};
                        @endphp
                        <tr>
                            <td>
                                <span class="criterion-name">{{ $crit['label'] }}</span>
                                @if($selected !== null)
                                <br><span class="desc-cell">{{ $crit['descs'][$selected] }}</span>
                                @endif
                            </td>
                            @for($i = 0; $i <= 5; $i++)
                                @if($i <= $crit['max'])
                                    <td style="text-align:center;padding:4px;">
                                        <button type="button"
                                            wire:click="$set('{{ $field }}', {{ $i }})"
                                            class="score-btn {{ $selected === $i ? 'active' : '' }}"
                                            title="{{ $crit['descs'][$i] ?? '' }}">{{ $i }}</button>
                                    </td>
                                @else
                                    <td style="text-align:center;padding:4px;">
                                        <span class="score-btn na" style="background:#f1f5f9;border-color:#e2e8f0;color:#cbd5e1;">–</span>
                                    </td>
                                @endif
                            @endfor
                            <td style="text-align:center;font-weight:800;color:#1d4ed8;font-size:1.1rem;">
                                {{ $selected ?? '—' }}
                            </td>
                        </tr>
                        @endforeach
                        <tr class="subtotal-row">
                            <td colspan="7" style="text-align:right;padding-right:8px;">Neuromuscular Subtotal</td>
                            <td style="text-align:center;font-size:1.1rem;">{{ $this->nmSubtotal() }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Physical Maturity ───────────────────────────────────────── --}}
            <div class="form-section">
                <div class="section-header">
                    <span class="section-title">👶 PHYSICAL MATURITY</span>
                    <span style="font-size:0.75rem;color:#166534;font-weight:700;">Subtotal: {{ $this->pmSubtotal() }} / 28</span>
                </div>
                <div class="section-body" style="padding: 12px;">
                    <table class="score-table">
                        <thead>
                            <tr>
                                <th style="text-align:left;min-width:130px;">Criterion</th>
                                <th colspan="6">Score (select one)</th>
                                <th>Selected</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($pmCriteria as $crit)
                        @php
                            $field    = $crit['field'];
                            $selected = $this->{$field};
                        @endphp
                        <tr>
                            <td>
                                <span class="criterion-name">{{ $crit['label'] }}</span>
                                @if($selected !== null)
                                <br><span class="desc-cell">{{ $crit['descs'][$selected] }}</span>
                                @endif
                            </td>
                            @for($i = 0; $i <= 5; $i++)
                                @if($i <= $crit['max'])
                                    <td style="text-align:center;padding:4px;">
                                        <button type="button"
                                            wire:click="$set('{{ $field }}', {{ $i }})"
                                            class="score-btn {{ $selected === $i ? 'active' : '' }}"
                                            title="{{ $crit['descs'][$i] ?? '' }}">{{ $i }}</button>
                                    </td>
                                @else
                                    <td style="text-align:center;padding:4px;">
                                        <span class="score-btn na" style="background:#f1f5f9;border-color:#e2e8f0;color:#cbd5e1;">–</span>
                                    </td>
                                @endif
                            @endfor
                            <td style="text-align:center;font-weight:800;color:#1d4ed8;font-size:1.1rem;">
                                {{ $selected ?? '—' }}
                            </td>
                        </tr>
                        @endforeach
                        <tr class="subtotal-row">
                            <td colspan="7" style="text-align:right;padding-right:8px;">Physical Subtotal</td>
                            <td style="text-align:center;font-size:1.1rem;">{{ $this->pmSubtotal() }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Score Summary ───────────────────────────────────────────────── --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">📊 SCORE SUMMARY</span>
            </div>
            <div class="section-body" style="text-align: center;">
                <div style="display: flex; justify-content: center; gap: 48px; flex-wrap: wrap;">
                    <div>
                        <p style="font-size:0.7rem;color:#6b7280;text-transform:uppercase;margin-bottom:4px;">Neuromuscular</p>
                        <p style="font-size:2rem;font-weight:800;color:#1d4ed8;margin:0;">{{ $this->nmSubtotal() }}</p>
                        <p style="font-size:0.65rem;color:#94a3b8;">/ 27</p>
                    </div>
                    <div style="font-size:2rem;color:#e2e8f0;line-height:1;padding-top:16px;">+</div>
                    <div>
                        <p style="font-size:0.7rem;color:#6b7280;text-transform:uppercase;margin-bottom:4px;">Physical</p>
                        <p style="font-size:2rem;font-weight:800;color:#1d4ed8;margin:0;">{{ $this->pmSubtotal() }}</p>
                        <p style="font-size:0.65rem;color:#94a3b8;">/ 28</p>
                    </div>
                    <div style="font-size:2rem;color:#e2e8f0;line-height:1;padding-top:16px;">=</div>
                    <div>
                        <p style="font-size:0.7rem;color:#6b7280;text-transform:uppercase;margin-bottom:4px;">TOTAL SCORE</p>
                        <p style="font-size:2.4rem;font-weight:800;color:#1d4ed8;margin:0;">{{ $totalScore ?? '—' }}</p>
                        <p style="font-size:0.65rem;color:#94a3b8;">/ 55</p>
                    </div>
                    <div style="font-size:2rem;color:#e2e8f0;line-height:1;padding-top:16px;">→</div>
                    <div>
                        <p style="font-size:0.7rem;color:#6b7280;text-transform:uppercase;margin-bottom:4px;">Gestational Age</p>
                        <p style="font-size:2rem;font-weight:800;color:#059669;margin:0;">{{ $estimatedGaWeeks ? $estimatedGaWeeks . ' wks' : '—' }}</p>
                        @if($estimatedGaWeeks)
                            @php $ga = $estimatedGaWeeks; @endphp
                            @if($ga < 34)
                                <span style="background:#fef3c7;color:#92400e;padding:2px 10px;border-radius:20px;font-size:0.7rem;font-weight:700;">Very Preterm</span>
                            @elseif($ga < 37)
                                <span style="background:#fef9c3;color:#713f12;padding:2px 10px;border-radius:20px;font-size:0.7rem;font-weight:700;">Preterm</span>
                            @elseif($ga <= 42)
                                <span style="background:#dcfce7;color:#166534;padding:2px 10px;border-radius:20px;font-size:0.7rem;font-weight:700;">Term</span>
                            @else
                                <span style="background:#fee2e2;color:#991b1b;padding:2px 10px;border-radius:20px;font-size:0.7rem;font-weight:700;">Post-term</span>
                            @endif
                        @endif
                    </div>
                </div>

                @php
                    $allVals = [$nmPosture,$nmSquareWindow,$nmArmRecoil,$nmPoplitealAngle,$nmScarfSign,$nmHeelToEar,$pmSkin,$pmLanugo,$pmPlantarSurface,$pmBreast,$pmEyeEar,$pmGenitals];
                    $filledCount = count(array_filter($allVals, fn($v) => $v !== null));
                @endphp
                @if($filledCount < 12)
                <p style="margin-top:12px;font-size:0.8rem;color:#f59e0b;">
                    ⚠ Fill in all 12 criteria to compute the total score and save.
                    ({{ $filledCount }} / 12 filled)
                </p>
                @endif
            </div>
        </div>

        {{-- ── Action Buttons ──────────────────────────────────────────────── --}}
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-bottom: 40px;">
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