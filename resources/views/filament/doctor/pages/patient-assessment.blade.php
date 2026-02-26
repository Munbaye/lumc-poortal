<x-filament-panels::page>
<div style="max-width:900px;">

{{-- Patient bar --}}
@if($visit)
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:14px 18px;margin-bottom:20px;display:flex;flex-wrap:wrap;gap:20px;align-items:center;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div>
        <p style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Case No</p>
        <p style="font-family:monospace;font-weight:700;font-size:.95rem;" class="text-gray-900 dark:text-white">{{ $visit->patient->case_no }}</p>
    </div>
    <div style="flex:1;min-width:180px;">
        <p style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Patient</p>
        <p style="font-weight:700;" class="text-gray-900 dark:text-white">{{ $visit->patient->full_name }}</p>
    </div>
    <div>
        <p style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Age / Sex</p>
        <p style="font-weight:600;" class="text-gray-700 dark:text-gray-300">{{ $visit->patient->age_display }} / {{ $visit->patient->sex }}</p>
    </div>
    <div>
        <p style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Visit</p>
        <p style="font-weight:600;" class="text-gray-700 dark:text-gray-300">
            {{ $visit->visit_type }}
            @if(($visit->payment_class ?? '') === 'Private')
            · <span style="color:#6b7280;font-size:.78rem;">Private</span>
            @endif
        </p>
    </div>
    <div>
        <p style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Chief Complaint</p>
        <p style="font-weight:600;" class="text-gray-700 dark:text-gray-300">{{ $visit->chief_complaint }}</p>
    </div>
</div>

{{-- Vitals summary --}}
@if($v = $visit->latestVitals)
@php
    function vitalColor($val, $low, $high) {
        if ($val === null || $val === '') return '';
        return ($val < $low || $val > $high) ? 'color:#dc2626;font-weight:700;' : '';
    }
@endphp
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:12px 18px;margin-bottom:20px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <p style="font-size:.7rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:10px;">
        Vital Signs — {{ $v->nurse_name }}, {{ $v->taken_at->format('M j H:i') }}
    </p>
    <div style="display:flex;flex-wrap:wrap;gap:16px;font-size:.83rem;">
        @if($v->blood_pressure)
        <div><span style="color:#9ca3af;">BP</span> <span style="font-weight:600;margin-left:4px;" class="dark:text-white">{{ $v->blood_pressure }}</span></div>
        @endif
        <div><span style="color:#9ca3af;">PR</span> <span style="font-weight:600;margin-left:4px;{{ vitalColor($v->pulse_rate, 60, 100) }}" class="{{ !($v->pulse_rate < 60 || $v->pulse_rate > 100) ? 'dark:text-white' : '' }}">{{ $v->pulse_rate ?? '—' }} bpm</span></div>
        <div><span style="color:#9ca3af;">RR</span> <span style="font-weight:600;margin-left:4px;{{ vitalColor($v->respiratory_rate, 12, 20) }}" class="{{ !($v->respiratory_rate < 12 || $v->respiratory_rate > 20) ? 'dark:text-white' : '' }}">{{ $v->respiratory_rate ?? '—' }}/min</span></div>
        <div><span style="color:#9ca3af;">Temp</span> <span style="font-weight:600;margin-left:4px;{{ vitalColor($v->temperature, 36.0, 37.5) }}" class="{{ !($v->temperature < 36.0 || $v->temperature > 37.5) ? 'dark:text-white' : '' }}">{{ $v->temperature ?? '—' }}°C</span></div>
        @if($v->o2_saturation)
        <div><span style="color:#9ca3af;">O₂ Sat</span> <span style="font-weight:600;margin-left:4px;{{ vitalColor($v->o2_saturation, 95, 100) }}" class="{{ $v->o2_saturation >= 95 ? 'dark:text-white' : '' }}">{{ $v->o2_saturation }}%</span></div>
        @endif
        @if($v->weight_kg)
        <div><span style="color:#9ca3af;">Weight</span> <span style="font-weight:600;margin-left:4px;" class="dark:text-white">{{ $v->weight_kg }} kg</span></div>
        @endif
        @if($v->pain_scale !== null && $v->pain_scale !== '')
        <div><span style="color:#9ca3af;">Pain</span> <span style="font-weight:600;margin-left:4px;{{ (int)$v->pain_scale >= 7 ? 'color:#dc2626;font-weight:700;' : '' }}" class="{{ (int)$v->pain_scale < 7 ? 'dark:text-white' : '' }}">{{ $v->pain_scale }}/10</span></div>
        @endif
    </div>
</div>
@endif

{{-- ─── SECTION 1: PHYSICAL EXAMINATION ─── --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;margin-bottom:16px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div style="display:flex;align-items:baseline;gap:12px;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f3f4f6;" class="dark:border-gray-700">
        <span style="font-size:.7rem;font-weight:700;background:#111827;color:#fff;border-radius:4px;padding:2px 8px;" class="dark:bg-white dark:text-gray-900">1</span>
        <h2 style="font-size:.95rem;font-weight:700;" class="text-gray-900 dark:text-white">Physical Examination</h2>
        <span style="font-size:.73rem;color:#9ca3af;">NUR-005 — All positive findings and important negative findings</span>
    </div>

    @php
        $peFields = [
            ['peSkin',           'Skin'],
            ['peHeadEent',       'Head / EENT'],
            ['peLymphNodes',     'Lymph Nodes'],
            ['peChest',          'Chest'],
            ['peLungs',          'Lungs'],
            ['peCardiovascular', 'Cardiovascular'],
            ['peBreast',         'Breast'],
            ['peAbdomen',        'Abdomen'],
            ['peRectum',         'Rectum'],
            ['peGenitalia',      'Genitalia'],
            ['peMusculoskeletal','Musculoskeletal'],
            ['peExtremities',    'Extremities'],
            ['peNeurology',      'Neurology'],
        ];
    @endphp

    <div style="display:grid;gap:8px;">
        @foreach($peFields as [$prop, $label])
        <div style="display:grid;grid-template-columns:160px 1fr;align-items:center;gap:12px;">
            <label style="font-size:.78rem;font-weight:600;color:#6b7280;text-align:right;" class="dark:text-gray-400">{{ $label }}</label>
            <input type="text" wire:model="{{ $prop }}"
                   placeholder="Normal / describe findings"
                   style="border:1px solid #e5e7eb;border-radius:6px;padding:6px 10px;font-size:.83rem;width:100%;background:#fff;color:#111827;"
                   class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400">
        </div>
        @endforeach
    </div>

    <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f3f4f6;" class="dark:border-gray-700">
        <div style="display:grid;grid-template-columns:160px 1fr;align-items:start;gap:12px;">
            <label style="font-size:.78rem;font-weight:700;color:#374151;text-align:right;padding-top:6px;" class="dark:text-gray-300">Admitting Impression</label>
            <textarea wire:model="admittingImpression" rows="2"
                      placeholder="Initial clinical impression from physical examination..."
                      style="border:1px solid #d1d5db;border-radius:6px;padding:6px 10px;font-size:.83rem;width:100%;background:#fff;color:#111827;resize:vertical;"
                      class="dark:bg-gray-800 dark:border-gray-500 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400"></textarea>
        </div>
    </div>
</div>

{{-- ─── SECTION 2: MEDICAL HISTORY ─── --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;margin-bottom:16px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div style="display:flex;align-items:baseline;gap:12px;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f3f4f6;" class="dark:border-gray-700">
        <span style="font-size:.7rem;font-weight:700;background:#111827;color:#fff;border-radius:4px;padding:2px 8px;" class="dark:bg-white dark:text-gray-900">2</span>
        <h2 style="font-size:.95rem;font-weight:700;" class="text-gray-900 dark:text-white">Medical History</h2>
        <span style="font-size:.73rem;color:#9ca3af;">NUR-006</span>
    </div>

    @php
        $historyFields = [
            ['chiefComplaint',         'Chief Complaint',                           2,  'Chief complaint as described by patient'],
            ['historyOfPresentIllness','History of Present Complaint',              4,  'Duration, onset, character, associated symptoms...'],
            ['pastMedicalHistory',     'Past History (Illnesses & Operations)',     3,  'Previous hospitalizations, surgeries, chronic conditions...'],
            ['familyHistory',          'Family History',                            2,  'DM, HPN, CA, heart disease in family...'],
            ['occupationEnvironment',  'Occupation & Environment',                  2,  'Work, exposures, living conditions...'],
        ];
    @endphp

    <div style="display:grid;gap:14px;">
        @foreach($historyFields as [$prop, $label, $rows, $ph])
        <div>
            <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;" class="dark:text-gray-400">{{ $label }}</label>
            <textarea wire:model="{{ $prop }}" rows="{{ $rows }}" placeholder="{{ $ph }}"
                      style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:8px 10px;font-size:.83rem;background:#fff;color:#111827;resize:vertical;"
                      class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400"></textarea>
        </div>
        @endforeach

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div>
                <label style="display:block;font-size:.75rem;font-weight:700;color:#dc2626;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Drug Allergies</label>
                <textarea wire:model="drugAllergies" rows="2" placeholder="NKDA if none. Drug name + reaction..."
                          style="width:100%;border:1px solid #fca5a5;border-radius:6px;padding:8px 10px;font-size:.83rem;background:#fff;color:#111827;resize:vertical;"
                          class="dark:bg-gray-800 dark:border-red-700 dark:text-white focus:outline-none focus:ring-1 focus:ring-red-300"></textarea>
            </div>
            <div>
                <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;" class="dark:text-gray-400">Drug Therapy (Current Meds)</label>
                <textarea wire:model="drugTherapy" rows="2" placeholder="Maintenance meds, dose, frequency..."
                          style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:8px 10px;font-size:.83rem;background:#fff;color:#111827;resize:vertical;"
                          class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400"></textarea>
            </div>
            <div>
                <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;" class="dark:text-gray-400">Other Allergies</label>
                <textarea wire:model="otherAllergies" rows="2" placeholder="Food, latex, contrast dye..."
                          style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:8px 10px;font-size:.83rem;background:#fff;color:#111827;resize:vertical;"
                          class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400"></textarea>
            </div>
        </div>
    </div>
</div>

{{-- ─── SECTION 3: DIAGNOSIS & DISPOSITION ─── --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;margin-bottom:16px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div style="display:flex;align-items:baseline;gap:12px;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f3f4f6;" class="dark:border-gray-700">
        <span style="font-size:.7rem;font-weight:700;background:#111827;color:#fff;border-radius:4px;padding:2px 8px;" class="dark:bg-white dark:text-gray-900">3</span>
        <h2 style="font-size:.95rem;font-weight:700;" class="text-gray-900 dark:text-white">Diagnosis & Disposition</h2>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
            <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;" class="dark:text-gray-400">Final Diagnosis</label>
            <textarea wire:model="diagnosis" rows="3"
                      placeholder="e.g., Community-Acquired Pneumonia, Moderate Risk..."
                      style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:8px 10px;font-size:.83rem;background:#fff;color:#111827;resize:vertical;"
                      class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400"></textarea>
        </div>
        <div>
            <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;" class="dark:text-gray-400">Differential Diagnosis</label>
            <textarea wire:model="differentialDiagnosis" rows="3"
                      style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:8px 10px;font-size:.83rem;background:#fff;color:#111827;resize:vertical;"
                      class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400"></textarea>
        </div>
    </div>
    <div style="margin-bottom:14px;">
        <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;" class="dark:text-gray-400">Management Plan</label>
        <textarea wire:model="plan" rows="2"
                  style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:8px 10px;font-size:.83rem;background:#fff;color:#111827;resize:vertical;"
                  class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400"></textarea>
    </div>

    {{-- Disposition --}}
    <div style="border-top:1px solid #f3f4f6;padding-top:14px;" class="dark:border-gray-700">
        <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:10px;" class="dark:text-gray-400">Disposition *</label>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
            @foreach(['Discharged','Admitted','Referred','HAMA','Expired'] as $d)
            <label style="display:flex;align-items:center;gap:6px;padding:7px 16px;border-radius:6px;cursor:pointer;font-size:.83rem;font-weight:600;border:1px solid {{ $disposition === $d ? '#374151' : '#e5e7eb' }};background:{{ $disposition === $d ? '#111827' : '#fff' }};color:{{ $disposition === $d ? '#fff' : '#374151' }};"
                   class="{{ $disposition !== $d ? 'dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300' : '' }}">
                <input type="radio" wire:model.live="disposition" value="{{ $d }}" style="display:none;">
                {{ $d }}
            </label>
            @endforeach
        </div>

        @if($disposition === 'Admitted')
        <div style="margin-top:12px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
            <div>
                <label style="display:block;font-size:.73rem;font-weight:600;color:#6b7280;margin-bottom:4px;" class="dark:text-gray-400">Ward</label>
                <input type="text" wire:model="admittedWard" placeholder="e.g., Medical Ward, ICU"
                       style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:6px 10px;font-size:.83rem;background:#fff;color:#111827;"
                       class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400">
            </div>
            <div>
                <label style="display:block;font-size:.73rem;font-weight:600;color:#6b7280;margin-bottom:4px;" class="dark:text-gray-400">Service</label>
                <select wire:model="service"
                        style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:6px 10px;font-size:.83rem;background:#fff;color:#111827;"
                        class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">Select...</option>
                    <option>Internal Medicine</option><option>Pediatrics</option>
                    <option>OB-Gynecology</option><option>Surgery</option>
                    <option>Orthopedics</option><option>ENT</option><option>Ophthalmology</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:.73rem;font-weight:600;color:#6b7280;margin-bottom:4px;" class="dark:text-gray-400">Payment Type</label>
                <select wire:model="paymentType"
                        style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:6px 10px;font-size:.83rem;background:#fff;color:#111827;"
                        class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">Select...</option>
                    <option>PhilHealth</option><option>Indigent / Malasakit</option>
                    <option>4Ps</option><option>Senior Citizen</option><option>PWD</option><option>Private Pay</option>
                </select>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ─── SECTION 4: DOCTOR'S ORDERS ─── --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;margin-bottom:16px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div style="display:flex;align-items:baseline;gap:12px;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f3f4f6;" class="dark:border-gray-700">
        <span style="font-size:.7rem;font-weight:700;background:#111827;color:#fff;border-radius:4px;padding:2px 8px;" class="dark:bg-white dark:text-gray-900">4</span>
        <h2 style="font-size:.95rem;font-weight:700;" class="text-gray-900 dark:text-white">Doctor's Orders</h2>
        <span style="font-size:.73rem;color:#9ca3af;">NUR-009 — Press Enter to add</span>
    </div>

    <div style="display:flex;gap:8px;margin-bottom:12px;">
        <input type="text" wire:model="newOrder" wire:keydown.enter="addOrder"
               placeholder="e.g., CBC with platelet, Chest X-ray PA, IVF D5LR 1L x 8hrs..."
               style="flex:1;border:1px solid #e5e7eb;border-radius:6px;padding:8px 12px;font-size:.83rem;background:#fff;color:#111827;"
               class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-gray-400">
        <button wire:click="addOrder"
                style="border:1px solid #d1d5db;background:#fff;color:#374151;padding:8px 16px;border-radius:6px;font-size:.83rem;font-weight:600;cursor:pointer;white-space:nowrap;"
                class="dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-50">
            Add
        </button>
    </div>
    @if(count($orders) > 0)
    <ol style="margin:0;padding:0;list-style:none;display:grid;gap:6px;">
        @foreach($orders as $i => $order)
        <li style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#f9fafb;border-radius:6px;border:1px solid #f3f4f6;font-size:.83rem;"
            class="dark:bg-gray-800 dark:border-gray-700">
            <span style="color:#9ca3af;font-size:.73rem;min-width:20px;">{{ $i + 1 }}.</span>
            <span style="flex:1;" class="text-gray-800 dark:text-gray-200">{{ $order['order_text'] }}</span>
            <span style="font-size:.7rem;color:{{ $order['is_completed'] ? '#16a34a' : '#d97706' }};">
                {{ $order['is_completed'] ? 'Done' : 'Pending' }}
            </span>
        </li>
        @endforeach
    </ol>
    @else
    <p style="font-size:.8rem;color:#9ca3af;">No orders added yet.</p>
    @endif
</div>

{{-- ─── SAVE ─── --}}
<div style="display:flex;align-items:center;gap:12px;padding:16px 0 24px;">
    <button wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-60"
            style="background:#111827;color:#fff;border:none;padding:10px 32px;border-radius:6px;font-size:.88rem;font-weight:600;cursor:pointer;"
            class="dark:bg-white dark:text-gray-900">
        <span wire:loading.remove wire:target="save">Save Assessment</span>
        <span wire:loading wire:target="save">Saving…</span>
    </button>
    <a href="/doctor/patient-queues"
       style="padding:10px 18px;border-radius:6px;font-size:.83rem;font-weight:500;text-decoration:none;border:1px solid #e5e7eb;color:#374151;"
       class="dark:border-gray-600 dark:text-gray-300 hover:bg-gray-50">
        Back to Queue
    </a>
    @if(!$disposition)
    <span style="font-size:.75rem;color:#dc2626;">Select a disposition before saving</span>
    @endif
</div>

@endif
</div>
</x-filament-panels::page>