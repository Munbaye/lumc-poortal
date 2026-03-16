<x-filament-panels::page>
<div style="max-width:900px;">
@if($visit)

{{-- Patient bar --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:14px 18px;
            margin-bottom:20px;display:flex;flex-wrap:wrap;gap:20px;align-items:center;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div>
        <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Case No</p>
        <p style="font-family:monospace;font-weight:700;font-size:.93rem;" class="text-gray-900 dark:text-white">{{ $visit->patient->case_no }}</p>
    </div>
    <div style="flex:1;min-width:180px;">
        <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Patient</p>
        <p style="font-weight:700;" class="text-gray-900 dark:text-white">{{ $visit->patient->full_name }}</p>
    </div>
    <div>
        <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Age / Sex</p>
        <p style="font-weight:600;" class="text-gray-700 dark:text-gray-300">{{ $visit->patient->age_display }} / {{ $visit->patient->sex }}</p>
    </div>
    <div>
        <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Entry</p>
        <span style="display:inline-block;padding:3px 10px;border-radius:4px;font-size:.75rem;font-weight:700;
                     background:{{ $visit->visit_type==='ER'?'#fef2f2':'#eff6ff' }};
                     color:{{ $visit->visit_type==='ER'?'#dc2626':'#1d4ed8' }};">
            {{ $visit->visit_type==='ER'?'🚑 ER':'📋 OPD' }}
        </span>
    </div>
    <div style="max-width:260px;">
        <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px;">Chief Complaint</p>
        <p style="font-weight:600;font-size:.83rem;" class="text-gray-700 dark:text-gray-300">{{ $visit->chief_complaint }}</p>
    </div>
</div>

{{-- Vitals summary --}}
@if($v = $visit->latestVitals)
@php
    function vitalBg($val, $low, $high): string {
        if ($val === null || $val === '') return '';
        return ($val < $low || $val > $high) ? 'background:#fef2f2;border-color:#fca5a5;color:#dc2626;font-weight:700;' : '';
    }
@endphp
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:12px 18px;margin-bottom:20px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <p style="font-size:.69rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:10px;">
        Latest Vital Signs — {{ $v->nurse_name }}, {{ $v->taken_at->format('M j Y H:i') }}
    </p>
    <div style="display:flex;flex-wrap:wrap;gap:8px;">
        @foreach([['BP',$v->blood_pressure,null,null],['PR',$v->pulse_rate,60,100],['RR',$v->respiratory_rate,12,20],['Temp',$v->temperature,36.0,37.5],['O₂',$v->o2_saturation,95,100],['Wt',$v->weight_kg,null,null]] as [$lbl,$val,$lo,$hi])
        @if($val !== null)
        <div style="padding:5px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:.83rem;{{ ($lo&&$hi)?vitalBg($val,$lo,$hi):'' }}"
             class="dark:border-gray-700 dark:bg-gray-800">
            <span style="color:#9ca3af;font-size:.7rem;">{{ $lbl }}</span>
            <span style="font-weight:700;margin-left:5px;" class="dark:text-white">{{ $val }}{{ $lbl==='Temp'?' °C':($lbl==='PR'?' bpm':($lbl==='RR'?'/min':($lbl==='O₂'?'%':($lbl==='Wt'?' kg':'')))) }}</span>
        </div>
        @endif
        @endforeach
        @if($v->pain_scale !== null)
        <div style="padding:5px 12px;border:1px solid {{ (int)$v->pain_scale>=7?'#fca5a5':'#e5e7eb' }};border-radius:6px;font-size:.83rem;{{ (int)$v->pain_scale>=7?'background:#fef2f2;color:#dc2626;font-weight:700;':'' }}"
             class="{{ (int)$v->pain_scale<7?'dark:border-gray-700 dark:bg-gray-800':'' }}">
            <span style="color:#9ca3af;font-size:.7rem;">Pain</span>
            <span style="font-weight:700;margin-left:5px;" class="dark:text-white">{{ $v->pain_scale }}/10</span>
        </div>
        @endif
    </div>
</div>
@else
<div style="background:#fefce8;border:1px solid #fde047;border-radius:8px;padding:10px 16px;margin-bottom:20px;font-size:.83rem;">
    <span style="color:#854d0e;">⚠️ No vital signs recorded for this visit.</span>
</div>
@endif

{{-- ── SECTION 1: PHYSICAL EXAMINATION ─── --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:22px;margin-bottom:14px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid #f3f4f6;" class="dark:border-gray-700">
        <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;border-radius:4px;padding:2px 8px;" class="dark:bg-white dark:text-gray-900">1</span>
        <h2 style="font-size:.93rem;font-weight:700;" class="text-gray-900 dark:text-white">Physical Examination</h2>
        <span style="font-size:.72rem;color:#9ca3af;">NUR-005</span>
    </div>
    @php $peRows = [['peSkin','Skin'],['peHeadEent','Head / EENT'],['peLymphNodes','Lymph Nodes'],['peChest','Chest'],['peLungs','Lungs'],['peCardiovascular','Cardiovascular'],['peBreast','Breast'],['peAbdomen','Abdomen'],['peRectum','Rectum'],['peGenitalia','Genitalia'],['peMusculoskeletal','Musculoskeletal'],['peExtremities','Extremities'],['peNeurology','Neurology']]; @endphp
    <div style="display:grid;gap:6px;">
        @foreach($peRows as [$prop,$label])
        <div style="display:grid;grid-template-columns:150px 1fr;align-items:center;gap:10px;">
            <label style="font-size:.77rem;font-weight:600;text-align:right;color:#6b7280;" class="dark:text-gray-400">{{ $label }}</label>
            <input type="text" wire:model="{{ $prop }}" placeholder="Normal / describe findings"
                   style="border:1px solid #e5e7eb;border-radius:5px;padding:5px 9px;font-size:.82rem;background:#fff;color:#111827;width:100%;"
                   class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-400">
        </div>
        @endforeach
    </div>
    <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f3f4f6;" class="dark:border-gray-700">
        <div style="display:grid;grid-template-columns:150px 1fr;align-items:start;gap:10px;">
            <label style="font-size:.77rem;font-weight:700;text-align:right;padding-top:5px;color:#374151;" class="dark:text-gray-300">Admitting Impression</label>
            <textarea wire:model="admittingImpression" rows="2" placeholder="Clinical impression after physical examination…"
                      style="border:1px solid #d1d5db;border-radius:5px;padding:6px 9px;font-size:.82rem;background:#fff;color:#111827;resize:vertical;width:100%;"
                      class="dark:bg-gray-800 dark:border-gray-500 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-400"></textarea>
        </div>
    </div>
</div>

{{-- ── SECTION 2: MEDICAL HISTORY ─── --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:22px;margin-bottom:14px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid #f3f4f6;" class="dark:border-gray-700">
        <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;border-radius:4px;padding:2px 8px;" class="dark:bg-white dark:text-gray-900">2</span>
        <h2 style="font-size:.93rem;font-weight:700;" class="text-gray-900 dark:text-white">Medical History</h2>
        <span style="font-size:.72rem;color:#9ca3af;">NUR-006</span>
    </div>
    <div style="display:grid;gap:11px;">
        @foreach([['chiefComplaint','Chief Complaint',2,'As described by patient'],['historyOfPresentIllness','History of Present Complaint',4,'Onset, duration, character, associated symptoms…'],['pastMedicalHistory','Past History — Illnesses & Operations',3,'Previous hospitalizations, surgeries, chronic conditions…'],['familyHistory','Family History',2,'DM, HPN, CA, heart disease in family…'],['occupationEnvironment','Occupation & Environment',2,'Type of work, exposures, living conditions…']] as [$prop,$label,$rows,$ph])
        <div>
            <label style="display:block;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;color:#374151;" class="dark:text-gray-400">{{ $label }}</label>
            <textarea wire:model="{{ $prop }}" rows="{{ $rows }}" placeholder="{{ $ph }}"
                      style="width:100%;border:1px solid #e5e7eb;border-radius:5px;padding:6px 9px;font-size:.82rem;background:#fff;color:#111827;resize:vertical;"
                      class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-400"></textarea>
        </div>
        @endforeach
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
            <div>
                <label style="display:block;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;color:#dc2626;">Drug Allergies</label>
                <textarea wire:model="drugAllergies" rows="2" placeholder="NKDA if none. Drug + reaction…" style="width:100%;border:1px solid #fca5a5;border-radius:5px;padding:6px 9px;font-size:.82rem;background:#fff;color:#111827;resize:vertical;" class="dark:bg-gray-800 dark:border-red-700 dark:text-white focus:outline-none focus:ring-1 focus:ring-red-300"></textarea>
            </div>
            <div>
                <label style="display:block;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;" class="text-gray-500 dark:text-gray-400">Drug Therapy</label>
                <textarea wire:model="drugTherapy" rows="2" placeholder="Maintenance meds, dose, frequency…" style="width:100%;border:1px solid #e5e7eb;border-radius:5px;padding:6px 9px;font-size:.82rem;background:#fff;color:#111827;resize:vertical;" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-400"></textarea>
            </div>
            <div>
                <label style="display:block;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;" class="text-gray-500 dark:text-gray-400">Other Allergies</label>
                <textarea wire:model="otherAllergies" rows="2" placeholder="Food, latex, contrast dye…" style="width:100%;border:1px solid #e5e7eb;border-radius:5px;padding:6px 9px;font-size:.82rem;background:#fff;color:#111827;resize:vertical;" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-400"></textarea>
            </div>
        </div>
    </div>
</div>

{{-- ── SECTION 3: DIAGNOSIS ─── --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:22px;margin-bottom:14px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid #f3f4f6;" class="dark:border-gray-700">
        <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;border-radius:4px;padding:2px 8px;" class="dark:bg-white dark:text-gray-900">3</span>
        <h2 style="font-size:.93rem;font-weight:700;" class="text-gray-900 dark:text-white">Diagnosis</h2>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:10px;">
        <div>
            <label style="display:block;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;" class="text-gray-500 dark:text-gray-400">Final Diagnosis</label>
            <textarea wire:model="diagnosis" rows="3" placeholder="e.g., Community-Acquired Pneumonia, Moderate Risk…" style="width:100%;border:1px solid #e5e7eb;border-radius:5px;padding:6px 9px;font-size:.82rem;background:#fff;color:#111827;resize:vertical;" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-400"></textarea>
        </div>
        <div>
            <label style="display:block;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;" class="text-gray-500 dark:text-gray-400">Differential Diagnosis</label>
            <textarea wire:model="differentialDiagnosis" rows="3" style="width:100%;border:1px solid #e5e7eb;border-radius:5px;padding:6px 9px;font-size:.82rem;background:#fff;color:#111827;resize:vertical;" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-400"></textarea>
        </div>
    </div>
    <div>
        <label style="display:block;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;" class="text-gray-500 dark:text-gray-400">Management Plan</label>
        <textarea wire:model="plan" rows="2" style="width:100%;border:1px solid #e5e7eb;border-radius:5px;padding:6px 9px;font-size:.82rem;background:#fff;color:#111827;resize:vertical;" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-400"></textarea>
    </div>
</div>

{{-- ── SECTION 4: DISPOSITION ─── --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:22px;margin-bottom:14px;"
     class="dark:bg-gray-900 dark:border-gray-700">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;padding-bottom:10px;border-bottom:1px solid #f3f4f6;" class="dark:border-gray-700">
        <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;border-radius:4px;padding:2px 8px;" class="dark:bg-white dark:text-gray-900">4</span>
        <h2 style="font-size:.93rem;font-weight:700;" class="text-gray-900 dark:text-white">Disposition</h2>
        <span style="font-size:.72rem;color:#9ca3af;">Ward assignment and payment type completed by clerk</span>
    </div>

    <p style="font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;" class="text-gray-500 dark:text-gray-400">Will you admit this patient?</p>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px;">
        <button wire:click="$set('willAdmit',false)" type="button"
                style="padding:16px;border-radius:8px;cursor:pointer;text-align:center;border:2px solid {{ $willAdmit===false?'#dc2626':'#e5e7eb' }};background:{{ $willAdmit===false?'#fef2f2':'#fff' }};color:{{ $willAdmit===false?'#dc2626':'#374151' }};"
                class="{{ $willAdmit!==false?'dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300':'' }}">
            <div style="font-size:1.8rem;margin-bottom:6px;">🏠</div>
            <div style="font-weight:700;font-size:.9rem;">NOT Admitting</div>
            <div style="font-size:.72rem;opacity:.7;margin-top:2px;">Discharge / Refer / HAMA / Expired</div>
        </button>
        <button wire:click="$set('willAdmit',true)" type="button"
                style="padding:16px;border-radius:8px;cursor:pointer;text-align:center;border:2px solid {{ $willAdmit===true?'#059669':'#e5e7eb' }};background:{{ $willAdmit===true?'#f0fdf4':'#fff' }};color:{{ $willAdmit===true?'#065f46':'#374151' }};"
                class="{{ $willAdmit!==true?'dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300':'' }}">
            <div style="font-size:1.8rem;margin-bottom:6px;">🏥</div>
            <div style="font-weight:700;font-size:.9rem;">ADMIT to Ward</div>
            <div style="font-size:.72rem;opacity:.7;margin-top:2px;">Clerk completes admission details</div>
        </button>
    </div>

    @if($willAdmit === false)
    <div style="border-top:1px solid #f3f4f6;padding-top:14px;" class="dark:border-gray-700">
        <p style="font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;" class="text-gray-500 dark:text-gray-400">Select outcome</p>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
            @foreach([['Discharged','🏠','Treated, sent home'],['Referred','🔄','Referred elsewhere'],['HAMA','⚠️','Left against medical advice'],['Expired','✝','Deceased at facility']] as [$val,$icon,$desc])
            <button wire:click="$set('outpatientDisposition','{{ $val }}')" type="button"
                    style="padding:10px 18px;border-radius:6px;cursor:pointer;font-weight:600;font-size:.83rem;border:2px solid {{ $outpatientDisposition===$val?'#374151':'#e5e7eb' }};background:{{ $outpatientDisposition===$val?'#111827':'#fff' }};color:{{ $outpatientDisposition===$val?'#fff':'#374151' }};"
                    class="{{ $outpatientDisposition!==$val?'dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300':'' }}">
                {{ $icon }} {{ $val }}
                <span style="display:block;font-size:.7rem;font-weight:400;opacity:.7;margin-top:1px;">{{ $desc }}</span>
            </button>
            @endforeach
        </div>
        @if(!$outpatientDisposition)<p style="font-size:.73rem;color:#d97706;margin-top:8px;">← Select an outcome above</p>@endif
    </div>
    @endif

    @if($willAdmit === true)
    <div style="border-top:1px solid #f3f4f6;padding-top:16px;" class="dark:border-gray-700">
        {{-- Service Type — searchable native select --}}
        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;color:#065f46;" class="dark:text-green-400">
                Admitting Service *
            </label>
            <select wire:model.live="admittingService"
                    style="width:100%;max-width:320px;border:1.5px solid {{ $admittingService?'#059669':'#d1d5db' }};border-radius:7px;padding:9px 12px;font-size:.875rem;background:#fff;color:#111827;-webkit-appearance:menulist;appearance:menulist;"
                    class="dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-400">
                <option value="">— Select admitting service —</option>
                @foreach($serviceOptions as $svc)
                <option value="{{ $svc }}" @selected($admittingService === $svc)>{{ $svc }}</option>
                @endforeach
            </select>
            @if(!$admittingService)
            <p style="font-size:.73rem;color:#d97706;margin-top:6px;">⚠️ Service type required before saving</p>
            @else
            <p style="font-size:.73rem;color:#059669;margin-top:5px;font-weight:600;">✓ {{ $admittingService }} — clerk will assign the ward</p>
            @endif
        </div>

        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 14px;" class="dark:bg-green-900/20 dark:border-green-700">
            <p style="font-size:.82rem;font-weight:600;color:#065f46;margin:0;" class="dark:text-green-300">
                ✓ Clerk will complete ward assignment, PhilHealth details, and payment classification. Write your orders in Section 5 below.
            </p>
        </div>
    </div>
    @endif

    @if($willAdmit === null)
    <p style="font-size:.73rem;color:#d97706;margin-top:4px;">⚠️ Make a decision before saving.</p>
    @endif
</div>

{{-- ── SECTION 5: DOCTOR'S ORDERS (only when admitting) ─── --}}
@if($willAdmit === true)
<div style="background:#fff;border:1px solid #bbf7d0;border-radius:8px;padding:22px;margin-bottom:14px;"
     class="dark:bg-gray-900 dark:border-green-800">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid #d1fae5;" class="dark:border-green-800">
        <span style="background:#059669;color:#fff;font-size:.68rem;font-weight:700;border-radius:4px;padding:2px 8px;">5</span>
        <h2 style="font-size:.93rem;font-weight:700;" class="text-gray-900 dark:text-white">Doctor's Orders</h2>
        <span style="font-size:.72rem;color:#059669;" class="dark:text-green-400">
            NUR-009 — {{ $admittingService ?: 'admitting service' }} · each order checked off separately by nurse
        </span>
    </div>
    <div style="display:flex;gap:8px;margin-bottom:12px;">
        <input type="text" wire:model="newOrder" wire:keydown.enter.prevent="addOrder"
               placeholder="e.g., IVF D5LR 1L x 8hrs, CBC with platelet, Chest X-ray PA, Paracetamol 500mg q6h PRN…"
               style="flex:1;border:1px solid #bbf7d0;border-radius:6px;padding:9px 12px;font-size:.83rem;background:#fff;color:#111827;"
               class="dark:bg-gray-800 dark:border-green-700 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-400">
        <button wire:click="addOrder" type="button"
                style="background:#059669;color:#fff;border:none;padding:9px 18px;border-radius:6px;font-size:.83rem;font-weight:600;cursor:pointer;white-space:nowrap;"
                onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
            + Add
        </button>
    </div>
    @if(count($orders) > 0)
    <ol style="margin:0;padding:0;list-style:none;display:grid;gap:6px;">
        @foreach($orders as $i => $order)
        <li style="display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:6px;border:1px solid {{ $order['is_completed']?'#bbf7d0':'#d1fae5' }};background:{{ $order['is_completed']?'#f0fdf4':'#f9fafb' }};"
            class="dark:bg-gray-800 dark:border-gray-700">
            <span style="color:#9ca3af;font-size:.73rem;min-width:22px;flex-shrink:0;">{{ $i+1 }}.</span>
            <span style="flex:1;font-size:.83rem;" class="text-gray-800 dark:text-gray-200">{{ $order['order_text'] }}</span>
            @if($order['id'] ?? null)
                <span style="font-size:.7rem;color:{{ $order['is_completed']?'#16a34a':'#d97706' }};font-weight:600;flex-shrink:0;">{{ $order['is_completed']?'✓ Done':'Pending' }}</span>
            @else
                <button wire:click="removeOrder({{ $i }})" type="button" style="color:#9ca3af;font-size:.75rem;border:none;background:none;cursor:pointer;padding:2px 6px;flex-shrink:0;" onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#9ca3af'" title="Remove">✕</button>
            @endif
        </li>
        @endforeach
    </ol>
    @else
    <p style="font-size:.8rem;color:#9ca3af;text-align:center;padding:14px 0;">No orders yet. Type above and press Enter or click + Add.</p>
    @endif
</div>
@endif

{{-- Save --}}
<div style="display:flex;align-items:center;gap:12px;padding:14px 0 28px;">
    <button wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button"
            style="background:#111827;color:#fff;border:none;padding:11px 34px;border-radius:6px;font-size:.88rem;font-weight:600;cursor:pointer;"
            class="dark:bg-white dark:text-gray-900">
        <span wire:loading.remove wire:target="save">💾 Save Assessment</span>
        <span wire:loading wire:target="save">Saving…</span>
    </button>
    <a href="/doctor/patient-queues" style="padding:11px 20px;border-radius:6px;font-size:.83rem;font-weight:500;text-decoration:none;border:1px solid #e5e7eb;color:#374151;" class="dark:border-gray-600 dark:text-gray-300">← Back to Queue</a>
    @if($willAdmit === false && !$outpatientDisposition)<span style="font-size:.75rem;color:#dc2626;">⚠️ Select a specific outcome in Section 4</span>@endif
    @if($willAdmit === true && !$admittingService)<span style="font-size:.75rem;color:#dc2626;">⚠️ Select a service type in Section 4</span>@endif
</div>

@endif
</div>
</x-filament-panels::page>