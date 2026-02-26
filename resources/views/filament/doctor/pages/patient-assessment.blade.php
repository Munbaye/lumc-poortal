<x-filament-panels::page>

@if($visit)

{{-- PATIENT BANNER --}}
<div style="border-radius:12px;padding:14px 18px;margin-bottom:16px;border:1px solid #99f6e4;background:#f0fdfa;"
     class="dark:bg-teal-950/50 dark:border-teal-700">
    <div class="grid grid-cols-2 md:grid-cols-6 gap-3 text-sm">
        <div>
            <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#6b7280;">Case No</span>
            <p style="font-weight:700;font-family:monospace;color:#134e4a;" class="dark:text-teal-300">
                {{ $visit->patient->case_no }}
            </p>
        </div>
        <div class="md:col-span-2">
            <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#6b7280;">Patient</span>
            <p style="font-weight:700;color:#111827;" class="dark:text-white">{{ $visit->patient->full_name }}</p>
        </div>
        <div>
            <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#6b7280;">Age/Sex</span>
            <p style="font-weight:600;color:#374151;" class="dark:text-gray-200">
                {{ $visit->patient->age_display }} / {{ $visit->patient->sex }}
            </p>
        </div>
        <div>
            <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#6b7280;">Type</span>
            <p style="font-weight:700;">
                @if($visit->visit_type === 'ER')
                    <span style="color:#dc2626;">🚑 ER</span>
                @else
                    <span style="color:#1d4ed8;">📋 OPD</span>
                @endif
            </p>
        </div>
        <div>
            <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#6b7280;">Classification</span>
            <p style="font-weight:700;">
                @if(($visit->payment_class ?? 'Charity') === 'Private')
                    <span style="color:#7c3aed;">💳 Private</span>
                @else
                    <span style="color:#16a34a;">🏥 Charity</span>
                @endif
            </p>
        </div>
    </div>
</div>

{{-- VITALS SUMMARY --}}
@if($latestVitals = $visit->latestVitals)
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 18px;margin-bottom:16px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <p style="font-size:.73rem;font-weight:700;text-transform:uppercase;color:#6b7280;margin-bottom:8px;">
        🌡️ Vital Signs — by {{ $latestVitals->nurse_name }}, {{ $latestVitals->taken_at->format('M d H:i') }}
    </p>
    <div class="grid grid-cols-4 md:grid-cols-7 gap-2 text-center text-sm">
        @foreach([
            ['BP', $latestVitals->blood_pressure ?? '—'],
            ['CR', $latestVitals->pulse_rate ?? '—'],
            ['RR', $latestVitals->respiratory_rate ?? '—'],
            ['PR', $latestVitals->pulse_rate ?? '—'],
            ['Temp', ($latestVitals->temperature ?? '—') . '°C'],
            ['O₂', ($latestVitals->o2_saturation ?? '—') . '%'],
            ['Wt', ($latestVitals->weight_kg ?? '—') . 'kg'],
        ] as [$label, $val])
        <div style="background:#f9fafb;border-radius:8px;padding:8px 4px;" class="dark:bg-gray-800">
            <p style="font-size:.68rem;color:#9ca3af;">{{ $label }}</p>
            <p style="font-weight:700;font-size:.95rem;" class="text-gray-900 dark:text-white">{{ $val }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 1: NUR-005 PHYSICAL EXAMINATION    --}}
{{-- ═══════════════════════════════════════════ --}}
<div style="background:#fff;border:2px solid #0d9488;border-radius:12px;padding:24px;margin-bottom:16px;"
     class="dark:bg-gray-900">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
        <span style="background:#0d9488;color:#fff;border-radius:50%;width:28px;height:28px;
                     display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">1</span>
        <h2 style="font-size:1.05rem;font-weight:700;color:#134e4a;" class="dark:text-teal-300">
            Physical Assessment (NUR-005)
        </h2>
        <span style="font-size:.73rem;color:#6b7280;margin-left:4px;">
            — All positive findings and all important negative findings
        </span>
    </div>
    <p style="font-size:.78rem;color:#6b7280;margin-bottom:18px;margin-left:38px;">
        Complete physical examination first before history taking
    </p>

    @php
        $peFields = [
            ['peSkin',           'SKIN'],
            ['peHeadEent',       'HEAD / EENT'],
            ['peLymphNodes',     'LYMPH NODES'],
            ['peChest',          'CHEST'],
            ['peLungs',          'LUNGS'],
            ['peCardiovascular', 'CARDIOVASCULAR'],
            ['peBreast',         'BREAST'],
            ['peAbdomen',        'ABDOMEN'],
            ['peRectum',         'RECTUM'],
            ['peGenitalia',      'GENITALIA'],
            ['peMusculoskeletal','MUSCULOSKELETAL'],
            ['peExtremities',    'EXTREMITIES'],
            ['peNeurology',      'NEUROLOGY'],
        ];
    @endphp

    <div class="space-y-3">
        @foreach($peFields as [$prop, $label])
        <div class="flex items-start gap-3">
            <label style="width:160px;flex-shrink:0;font-size:.78rem;font-weight:700;padding-top:8px;
                          color:#374151;letter-spacing:.03em;" class="dark:text-gray-300">
                {{ $label }}
            </label>
            <span style="color:#9ca3af;padding-top:8px;">=</span>
            <input type="text" wire:model="{{ $prop }}"
                   placeholder="Findings (type 'normal' or describe abnormalities)"
                   class="flex-1 rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                          dark:border-gray-600 dark:bg-gray-800 dark:text-white
                          focus:outline-none focus:ring-2 focus:ring-teal-500">
        </div>
        @endforeach
    </div>

    {{-- Admitting Impression (from NUR-005) --}}
    <div style="margin-top:20px;padding-top:16px;border-top:2px dashed #99f6e4;">
        <label style="display:block;font-size:.83rem;font-weight:700;margin-bottom:6px;color:#134e4a;"
               class="dark:text-teal-300">
            ADMITTING IMPRESSION *
        </label>
        <textarea wire:model="admittingImpression" rows="2"
                  placeholder="Initial clinical impression based on physical examination..."
                  class="w-full rounded-lg px-3 py-2 text-sm border border-teal-400 bg-white text-gray-900
                         dark:border-teal-600 dark:bg-gray-800 dark:text-white
                         focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 2: NUR-006 HISTORY                 --}}
{{-- ═══════════════════════════════════════════ --}}
<div style="background:#fff;border:2px solid #1d4ed8;border-radius:12px;padding:24px;margin-bottom:16px;"
     class="dark:bg-gray-900">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
        <span style="background:#1d4ed8;color:#fff;border-radius:50%;width:28px;height:28px;
                     display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">2</span>
        <h2 style="font-size:1.05rem;font-weight:700;color:#1e3a8a;" class="dark:text-blue-300">
            Medical History (NUR-006)
        </h2>
    </div>

    <div class="space-y-4">
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                   class="dark:text-gray-300">CHIEF COMPLAINT</label>
            <textarea wire:model="chiefComplaint" rows="2"
                      class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                             dark:border-gray-600 dark:bg-gray-800 dark:text-white
                             focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                   class="dark:text-gray-300">HISTORY OF PRESENT COMPLAINT</label>
            <textarea wire:model="historyOfPresentIllness" rows="4"
                      placeholder="Duration, onset, character, aggravating/relieving factors, associated symptoms..."
                      class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                             dark:border-gray-600 dark:bg-gray-800 dark:text-white
                             focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                   class="dark:text-gray-300">PAST HISTORY (Previous Illness and Operations)</label>
            <textarea wire:model="pastMedicalHistory" rows="3"
                      placeholder="Previous hospitalizations, surgeries, chronic illnesses..."
                      class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                             dark:border-gray-600 dark:bg-gray-800 dark:text-white
                             focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                   class="dark:text-gray-300">FAMILY HISTORY</label>
            <textarea wire:model="familyHistory" rows="2"
                      placeholder="DM, HPN, cancer, heart disease in family members..."
                      class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                             dark:border-gray-600 dark:bg-gray-800 dark:text-white
                             focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        {{-- 2-column: Occupation & Drug Allergies --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                       class="dark:text-gray-300">OCCUPATION AND ENVIRONMENT</label>
                <textarea wire:model="occupationEnvironment" rows="3"
                          placeholder="Job, exposure to chemicals, animals, smoke, etc..."
                          class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                                 dark:border-gray-600 dark:bg-gray-800 dark:text-white
                                 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#dc2626;"
                       class="dark:text-red-400">DRUG ALLERGIES ⚠️</label>
                <textarea wire:model="drugAllergies" rows="3"
                          placeholder="NKDA if none. List drug name and reaction type..."
                          class="w-full rounded-lg px-3 py-2 text-sm border border-red-300 bg-white text-gray-900
                                 dark:border-red-700 dark:bg-gray-800 dark:text-white
                                 focus:outline-none focus:ring-2 focus:ring-red-400"></textarea>
            </div>
            <div>
                <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                       class="dark:text-gray-300">DRUG THERAPY (Current Medications)</label>
                <textarea wire:model="drugTherapy" rows="3"
                          placeholder="Maintenance meds, dose, frequency..."
                          class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                                 dark:border-gray-600 dark:bg-gray-800 dark:text-white
                                 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                       class="dark:text-gray-300">OTHER ALLERGIES</label>
                <textarea wire:model="otherAllergies" rows="3"
                          placeholder="Food, environmental, contrast dye, latex..."
                          class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                                 dark:border-gray-600 dark:bg-gray-800 dark:text-white
                                 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 3: DISPOSITION                     --}}
{{-- ═══════════════════════════════════════════ --}}
<div style="background:#fff;border:2px solid #7c3aed;border-radius:12px;padding:24px;margin-bottom:16px;"
     class="dark:bg-gray-900">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
        <span style="background:#7c3aed;color:#fff;border-radius:50%;width:28px;height:28px;
                     display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">3</span>
        <h2 style="font-size:1.05rem;font-weight:700;color:#4c1d95;" class="dark:text-purple-300">
            Diagnosis &amp; Disposition
        </h2>
    </div>

    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                       class="dark:text-gray-300">FINAL DIAGNOSIS</label>
                <textarea wire:model="diagnosis" rows="3"
                          placeholder="e.g., Community-Acquired Pneumonia, Moderate Risk..."
                          class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                                 dark:border-gray-600 dark:bg-gray-800 dark:text-white
                                 focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            <div>
                <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                       class="dark:text-gray-300">DIFFERENTIAL DIAGNOSIS</label>
                <textarea wire:model="differentialDiagnosis" rows="3"
                          class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                                 dark:border-gray-600 dark:bg-gray-800 dark:text-white
                                 focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
        </div>
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;color:#374151;"
                   class="dark:text-gray-300">MANAGEMENT PLAN / REMARKS</label>
            <textarea wire:model="plan" rows="3"
                      class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                             dark:border-gray-600 dark:bg-gray-800 dark:text-white
                             focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
        </div>

        {{-- Disposition choices --}}
        <div style="background:#faf5ff;border:1px solid #d8b4fe;border-radius:10px;padding:16px;"
             class="dark:bg-purple-950/30 dark:border-purple-700">
            <h3 style="font-weight:700;margin-bottom:12px;color:#4c1d95;" class="dark:text-purple-300">
                📤 DISPOSITION *
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                @foreach([
                    ['Discharged', '🏠', '#16a34a'],
                    ['Admitted',   '🛏️', '#1d4ed8'],
                    ['Referred',   '↗️', '#d97706'],
                    ['HAMA',       '✋', '#dc2626'],
                    ['Expired',    '✝️', '#374151'],
                ] as [$d, $icon, $color])
                <label style="cursor:pointer;border-radius:8px;padding:10px;border:2px solid {{ $disposition === $d ? $color : '#e5e7eb' }};
                               background:{{ $disposition === $d ? 'rgba(0,0,0,.04)' : '#fff' }};
                               display:flex;align-items:center;gap:8px;"
                       class="dark:bg-gray-800 hover:border-gray-400">
                    <input type="radio" wire:model.live="disposition" value="{{ $d }}"
                           style="accent-color:{{ $color }};">
                    <span style="font-size:.85rem;font-weight:700;color:{{ $color }};">{{ $icon }} {{ $d }}</span>
                </label>
                @endforeach
            </div>

            @if($disposition === 'Admitted')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-300">Ward</label>
                    <input type="text" wire:model="admittedWard"
                           placeholder="e.g., Medical Ward, ICU, Pedia Ward"
                           class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                                  dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-300">Service</label>
                    <select wire:model="service" class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                                  dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select...</option>
                        <option>Internal Medicine</option><option>Pediatrics</option>
                        <option>OB-Gynecology</option><option>Surgery</option>
                        <option>Orthopedics</option><option>ENT</option><option>Ophthalmology</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-300">Payment Type</label>
                    <select wire:model="paymentType" class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                                  dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select...</option>
                        <option>PhilHealth</option><option>Indigent / Malasakit</option>
                        <option>4Ps</option><option>Senior Citizen</option>
                        <option>PWD</option><option>Private Pay</option>
                    </select>
                </div>
            </div>
            @endif

            @if($disposition === 'Referred')
            <div class="mt-2">
                <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-300">Referral Details</label>
                <textarea wire:model="plan" rows="2"
                          placeholder="Referred to: _____, For: _____, Reason: _____"
                          class="w-full rounded-lg px-3 py-2 text-sm border border-amber-400 bg-white text-gray-900
                                 dark:border-amber-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-400"></textarea>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 4: DOCTOR'S ORDERS                 --}}
{{-- ═══════════════════════════════════════════ --}}
<div style="background:#fff;border:2px solid #0891b2;border-radius:12px;padding:24px;margin-bottom:16px;"
     class="dark:bg-gray-900">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
        <span style="background:#0891b2;color:#fff;border-radius:50%;width:28px;height:28px;
                     display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">4</span>
        <h2 style="font-size:1.05rem;font-weight:700;color:#164e63;" class="dark:text-cyan-300">
            Doctor's Orders (NUR-009)
        </h2>
    </div>
    <div class="flex gap-3 mb-4">
        <input type="text" wire:model="newOrder"
               wire:keydown.enter="addOrder"
               placeholder="Type an order and press Enter — e.g., CBC with platelet, Chest X-ray PA..."
               class="flex-1 rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                      dark:border-gray-600 dark:bg-gray-800 dark:text-white
                      focus:outline-none focus:ring-2 focus:ring-cyan-500">
        <button wire:click="addOrder"
                style="background:#0891b2;color:#fff;border:none;padding:8px 20px;border-radius:8px;font-weight:700;cursor:pointer;">
            + Add Order
        </button>
    </div>
    @if(count($orders) > 0)
    <ol class="space-y-2">
        @foreach($orders as $i => $order)
        <li style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:10px 14px;display:flex;align-items:center;gap:12px;"
            class="dark:bg-cyan-950/30 dark:border-cyan-800">
            <span style="color:#0891b2;font-weight:700;min-width:24px;">{{ $i + 1 }}.</span>
            <span style="flex:1;font-size:.88rem;" class="text-gray-800 dark:text-gray-200">{{ $order['order_text'] }}</span>
            <span style="font-size:.73rem;" class="{{ $order['is_completed'] ? 'text-green-600' : 'text-amber-600' }}">
                {{ $order['is_completed'] ? '✅ Done' : '⏳ Pending' }}
            </span>
        </li>
        @endforeach
    </ol>
    @else
    <p style="font-size:.83rem;color:#9ca3af;font-style:italic;">No orders yet. Add lab requests, medications, nursing orders above.</p>
    @endif
</div>

{{-- SAVE BUTTON --}}
<div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:20px;"
     class="dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center gap-4">
        <button wire:click="save"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50"
                style="background:#134e4a;color:#fff;border:none;padding:14px 40px;border-radius:10px;
                       font-size:1rem;font-weight:700;cursor:pointer;">
            <span wire:loading.remove wire:target="save">💾 Save Assessment &amp; Finalize</span>
            <span wire:loading wire:target="save">⏳ Saving…</span>
        </button>
        <a href="/doctor/patient-queues"
           style="padding:14px 20px;border-radius:10px;font-size:.9rem;font-weight:600;text-decoration:none;"
           class="bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300">
            ← Back to Queue
        </a>
        @if(!$disposition)
        <span style="font-size:.78rem;color:#dc2626;font-style:italic;">⚠️ Please select a Disposition before saving</span>
        @endif
    </div>
</div>

@endif

<div style="text-align:center;font-size:.72rem;color:#9ca3af;margin-top:20px;padding-bottom:8px;">
    LA UNION: Agkaysa! | Tel: (072) 607-5541-45 / (072) 607-5938 | ER: 0927-728-6330 | launionmedicalcenter@gmail.com
</div>
</x-filament-panels::page>