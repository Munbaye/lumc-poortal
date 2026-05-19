<x-filament-panels::page>
<style>
.er-wrap{max-width:780px}

.er-tabs{display:flex;border-bottom:2px solid #e5e7eb;margin-bottom:20px}
.dark .er-tabs{border-color:#374151}
.er-tab{padding:9px 20px;font-size:.82rem;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;color:#6b7280;display:flex;align-items:center;gap:6px}
.er-tab.on{color:#dc2626;border-bottom-color:#dc2626}
.dark .er-tab{color:#9ca3af}
.dark .er-tab.on{color:#f87171;border-bottom-color:#f87171}
.er-bdg{background:#fef3c7;color:#92400e;border-radius:9999px;padding:1px 7px;font-size:.68rem;font-weight:700}
.er-bdg-r{background:#fee2e2;color:#b91c1c}
.dark .er-bdg{background:#2d1c00;color:#fde68a}
.dark .er-bdg-r{background:#450a0a;color:#fca5a5}
.er-sl{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:8px;display:flex;align-items:center;gap:5px}
.dark .er-sl{color:#6b7280}
.er-div{border:none;border-top:1px solid #f1f5f9;margin:18px 0}
.dark .er-div{border-color:#374151}
.fl{display:block;font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;margin-bottom:3px}
.dark .fl{color:#9ca3af}
.fi{width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:7px 10px;font-size:.84rem;color:#111827;background:#fff;box-sizing:border-box;transition:border-color .15s}
.dark .fi{background:#374151;border-color:#4b5563;color:#f9fafb}
.fi:focus{outline:none;border-color:#dc2626;box-shadow:0 0 0 2px rgba(220,38,38,.08)}
.fi[readonly]{background:#f9fafb;color:#6b7280}
.dark .fi[readonly]{background:#1f2937;color:#6b7280}
.fi-sel{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;background-size:14px;padding-right:28px}
.g2{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
.g3{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.g4{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.s2{grid-column:span 2}.s3{grid-column:span 3}.s4{grid-column:span 4}
.er-result{padding:9px 12px;border-radius:6px;border:1px solid #e5e7eb;margin-bottom:5px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;transition:background .1s;background:#fff}
.dark .er-result{background:#1f2937;border-color:#374151}
.er-result:hover{background:#f0f9ff;border-color:#bae6fd}
.er-case{background:#dbeafe;color:#1e40af;border-radius:9999px;padding:2px 8px;font-size:.68rem;font-weight:700}
.dark .er-case{background:#0c1a2e;color:#93c5fd}
.er-priority{border-radius:8px;padding:10px 14px}
.er-vital{background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:7px 9px;text-align:center}
.dark .er-vital{background:#1e293b;border-color:#334155}
.er-vital-v{font-size:.9rem;font-weight:700;color:#0f172a}
.dark .er-vital-v{color:#f1f5f9}
.er-vital-l{font-size:.62rem;color:#64748b;margin-top:1px}
.er-note{background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:7px 11px;font-size:.73rem;color:#92400e}
.dark .er-note{background:#2d1c00;border-color:#92400e;color:#fde68a}
.er-card{border:1.5px solid #e5e7eb;border-radius:10px;padding:14px 16px;margin-bottom:10px;background:#fff}
.dark .er-card{background:#1f2937;border-color:#374151}
.er-queue-item{border:1.5px solid #fde68a;border-radius:8px;padding:11px 14px;margin-bottom:8px;background:#fffbeb}
.dark .er-queue-item{background:#2d1c00;border-color:#92400e}
.er-btn{border:none;border-radius:7px;padding:9px 20px;font-size:.84rem;font-weight:700;cursor:pointer;transition:opacity .15s}
.er-btn:hover{opacity:.87}
.er-btn-red{background:#dc2626;color:#fff}
.er-btn-blue{background:#1e3a5f;color:#fff}
.er-btn-gray{background:#e5e7eb;color:#374151}
.dark .er-btn-gray{background:#374151;color:#f9fafb}
.er-btn-sm{padding:6px 13px;font-size:.77rem}
</style>

<div class="er-wrap">



<div x-data="{ tab: 'triage', showConfirm: false }">

{{-- TABS --}}
<div class="er-tabs">
    <button class="er-tab" :class="{ on: tab === 'triage' }" @click="tab = 'triage'">
        <x-heroicon-o-plus-circle class="w-4 h-4"/> New Triage
    </button>
    <button class="er-tab" :class="{ on: tab === 'assign' }" @click="tab = 'assign'">
        <x-heroicon-o-user-plus class="w-4 h-4"/> Assign Doctor
        @php $pa = \App\Models\Visit::where('visit_type','ER')->where('status','registered')->whereNull('assigned_doctor_id')->count(); @endphp
        @if($pa > 0)<span class="er-bdg">{{ $pa }}</span>@endif
    </button>
    <button class="er-tab" :class="{ on: tab === 'queue' }" @click="tab = 'queue'">
        <x-heroicon-o-clock class="w-4 h-4"/> Triage Queue
        @php $tq = \App\Models\Visit::where('visit_type','ER')->where('status','triage')->count(); @endphp
        @if($tq > 0)<span class="er-bdg er-bdg-r">{{ $tq }}</span>@endif
    </button>
</div>

{{-- ═══ TAB: NEW TRIAGE ═══ --}}
<div x-show="tab === 'triage'" x-cloak>

{{-- ─── STEP 1: SEARCH (shown when form not yet open) ─── --}}
@if(!$showTriageForm && !$isUnknownMode)

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
    <div class="er-sl" style="margin-bottom:0;">
        <x-heroicon-o-magnifying-glass class="w-3 h-3"/> Search patient
    </div>
    <button wire:click="activateUnknownMode" type="button"
        style="font-size:.76rem;font-weight:600;color:#dc2626;background:#fef2f2;border:1px solid #fecaca;border-radius:6px;padding:5px 12px;cursor:pointer;">
        Cannot obtain patient info →
    </button>
</div>

<div class="g2" style="margin-bottom:12px;">
    <div>
        <label class="fl">Family name *</label>
        <input wire:model.live="searchFamilyName" class="fi" placeholder="dela Cruz" autofocus/>
    </div>
    <div>
        <label class="fl">First name</label>
        <input wire:model.live="searchFirstName" class="fi" placeholder="Juan"/>
    </div>
    <div>
        <label class="fl">Sex</label>
        <select wire:model.live="searchSex" class="fi fi-sel">
            <option value="">Any</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    <div>
        <label class="fl">Birthday</label>
        <input type="date" wire:model.live="searchBirthday" class="fi"/>
    </div>
</div>

{{-- Search results --}}
@if($hasSearched)
    @if(count($searchResults) > 0)
        <p style="font-size:.73rem;color:#374151;margin:0 0 8px;font-weight:600;">{{ count($searchResults) }} patient(s) found</p>
        @foreach($searchResults as $r)
        <div class="er-result" wire:click="selectPatient({{ $r['id'] }})">
            <div>
                <div style="font-weight:700;font-size:.85rem;">{{ $r['full_name'] }}</div>
                <div style="font-size:.71rem;color:#6b7280;margin-top:1px;">
                    {{ $r['sex'] }} · {{ $r['age_display'] }} · {{ $r['birthday'] ?? '—' }}
                </div>
                @if($r['last_visit'])
                <div style="font-size:.68rem;color:#9ca3af;">Last visit: {{ $r['last_visit'] }}</div>
                @endif
            </div>
            <span class="er-case">{{ $r['case_no'] }}</span>
        </div>
        @endforeach

        <hr class="er-div"/>
    @endif

    {{-- No match found --}}
    @if(count($searchResults) === 0)
    <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:12px 16px;margin-bottom:14px;">
        <p style="color:#92400e;font-size:.83rem;font-weight:700;margin:0 0 8px;">No existing record found.</p>
        <label style="display:flex;align-items:center;gap:8px;font-size:.8rem;color:#92400e;cursor:pointer;margin-bottom:10px;">
            <input type="checkbox" wire:model.live="confirmNoMatch"/>
            I confirm there is no existing record for this patient
        </label>
        @if($confirmNoMatch)
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <button wire:click="showNewPatientForm" class="er-btn er-btn-red er-btn-sm">
                Fill Triage Form
            </button>
            <button wire:click="activateUnknownMode" class="er-btn er-btn-gray er-btn-sm">
                Unknown / Unidentified Patient
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- Has results but nurse wants new anyway --}}
    @if(count($searchResults) > 0)
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
        <label style="display:flex;align-items:center;gap:8px;font-size:.78rem;color:#6b7280;cursor:pointer;">
            <input type="checkbox" wire:model.live="confirmNoMatch"/>
            None of these match — register as new patient
        </label>
        @if($confirmNoMatch)
        <button wire:click="showNewPatientForm" class="er-btn er-btn-red er-btn-sm">
            Fill Triage Form
        </button>
        <button wire:click="activateUnknownMode" class="er-btn er-btn-gray er-btn-sm">
            Unknown Patient
        </button>
        @endif
    </div>
    @endif
@endif

@endif {{-- end search step --}}

{{-- ─── UNKNOWN PATIENT MODE ─── --}}
@if($isUnknownMode)
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
    <x-heroicon-o-question-mark-circle class="w-5 h-5 text-red-400" style="flex-shrink:0;"/>
    <div>
        <div style="font-size:.82rem;font-weight:700;color:#b91c1c;">Unknown / Unidentified Patient</div>
        <div style="font-size:.72rem;color:#6b7280;margin-top:1px;">
            Will be saved as <strong>John #001</strong> or <strong>Jane #001</strong> (auto-numbered). Clerk will update the real identity after registration.
        </div>
    </div>
    <button wire:click="cancelUnknownMode" type="button"
        style="margin-left:auto;font-size:.72rem;color:#6b7280;background:none;border:none;cursor:pointer;flex-shrink:0;">
        ✕ Cancel
    </button>
</div>
@endif

{{-- ─── TRIAGE FORM (shown after search confirms or patient selected) ─── --}}
@if($showTriageForm || $isUnknownMode)

{{-- Who is selected / new / unknown --}}
@if($selectedPatientId)
<div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:10px 14px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
    <div style="font-size:.8rem;font-weight:600;color:#15803d;">
        ✓ Existing patient: {{ $patientData['family_name'] }}, {{ $patientData['first_name'] }}
    </div>
    <button wire:click="clearSelectedPatient" type="button"
        style="font-size:.7rem;color:#dc2626;background:none;border:none;cursor:pointer;">✕ Change</button>
</div>
@endif

{{-- NURSE ON DUTY --}}
<div style="margin-bottom:16px;">
    <div class="er-sl"><x-heroicon-o-user-circle class="w-3 h-3"/> Triage officer</div>
    <div style="max-width:300px">
        <label class="fl">Nurse on duty *</label>
        <input wire:model="triageNurseOnDuty" class="fi" placeholder="Full name"/>
    </div>
</div>

<hr class="er-div"/>

{{-- PATIENT BASIC INFO (hidden for unknown, shown for known/new) --}}
@if(!$isUnknownMode)
<div style="margin-bottom:16px;">
    <div class="er-sl"><x-heroicon-o-user class="w-3 h-3"/> Patient basic info
        <span style="font-size:.66rem;font-weight:400;text-transform:none;letter-spacing:0;color:#9ca3af;">— clerk completes full demographics</span>
    </div>
    <div class="g4">
        <div>
            <label class="fl">Family name</label>
            <input wire:model="patientData.family_name" class="fi" placeholder="dela Cruz"
                @if($selectedPatientId) readonly @endif/>
        </div>
        <div>
            <label class="fl">First name</label>
            <input wire:model="patientData.first_name" class="fi" placeholder="Juan"
                @if($selectedPatientId) readonly @endif/>
        </div>
        <div>
            <label class="fl">Middle name</label>
            <input wire:model="patientData.middle_name" class="fi"
                @if($selectedPatientId) readonly @endif/>
        </div>
        <div>
            <label class="fl">Sex *</label>
            <select wire:model="patientData.sex" class="fi fi-sel" @if($selectedPatientId) disabled @endif>
                <option value="">—</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div>
            <label class="fl">Birthday</label>
            <input type="date" wire:model.live="patientData.birthday" class="fi"
                @if($selectedPatientId) readonly @endif/>
        </div>
        <div>
            <label class="fl">Age</label>
            <input type="number" wire:model="patientData.age" class="fi"
                @if($selectedPatientId) readonly @endif/>
        </div>
        <div>
            <label class="fl">Contact no.</label>
            <input wire:model="patientData.contact_number" class="fi" placeholder="09xx"/>
        </div>
        <div>
            <label class="fl">Brought by</label>
            <input wire:model="patientData.brought_by" class="fi" placeholder="Family, Ambulance…"/>
        </div>
    </div>
    <div class="er-note" style="margin-top:10px;">
        Clerk will complete: full address · civil status · payment class · official case number
    </div>
</div>
@else
{{-- Unknown patient — just sex and brought by --}}
<div style="margin-bottom:16px;">
    <div class="er-sl"><x-heroicon-o-user class="w-3 h-3"/> Basic info</div>
    <div class="g2">
        <div>
            <label class="fl">Sex *</label>
            <select wire:model="patientData.sex" class="fi fi-sel">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div>
            <label class="fl">Brought by</label>
            <input wire:model="patientData.brought_by" class="fi" placeholder="Ambulance, Bystander…"/>
        </div>
    </div>
</div>
@endif

<hr class="er-div"/>

{{-- TRIAGE ASSESSMENT --}}
<div style="margin-bottom:16px;">
    <div class="er-sl"><x-heroicon-o-clipboard-document-check class="w-3 h-3"/> Triage assessment</div>
    <div class="g3" style="margin-bottom:10px;">
        <div class="s2">
            <label class="fl">Chief complaint *</label>
            <input wire:model.live="chiefComplaint" class="fi" placeholder="Main reason for ER visit"/>
        </div>
        <div>
            <label class="fl">Duration</label>
            <select wire:model="complaintDuration" class="fi fi-sel">
                <option>< 1 day</option>
                <option>1–3 days</option>
                <option>1 week</option>
                <option>> 1 week</option>
            </select>
        </div>
    </div>
    <div class="g4" style="margin-bottom:10px;">
        <div>
            <label class="fl">Consciousness *</label>
            <select wire:model.live="consciousness" class="fi fi-sel">
                <option value="alert">Alert</option>
                <option value="drowsy">Drowsy</option>
                <option value="unconscious">Unconscious</option>
            </select>
        </div>
        <div>
            <label class="fl">Breathing *</label>
            <select wire:model.live="breathing" class="fi fi-sel">
                <option value="normal">Normal</option>
                <option value="difficulty">Difficulty</option>
                <option value="severe">Severe</option>
            </select>
        </div>
        <div>
            <label class="fl">Mobility</label>
            <select wire:model="mobility" class="fi fi-sel">
                <option value="walking">Walking</option>
                <option value="needs_assistance">Needs Assistance</option>
                <option value="bedridden">Bedridden</option>
            </select>
        </div>
        <div>
            <label class="fl">Condition on arrival</label>
            <select wire:model="patientData.condition_on_arrival" class="fi fi-sel">
                <option>Ambulatory</option>
                <option>Stretcher</option>
                <option>Wheelchair</option>
                <option>Carried</option>
                <option>Critical</option>
            </select>
        </div>
    </div>
    <div class="g2" style="margin-bottom:10px;">
        <div>
            <label class="fl">Triage category *</label>
            <select wire:model.live="triageCategory" class="fi fi-sel">
                <option value="">Select category</option>
                <option value="red">Red — Immediate</option>
                <option value="orange">Orange — Very Urgent</option>
                <option value="yellow">Yellow — Urgent</option>
                <option value="green">Green — Minor</option>
                <option value="black">Black — Expectant</option>
            </select>
            @if($categoryManuallySet)
            <button wire:click="resetAutoTriage" type="button"
                style="font-size:.67rem;color:#6b7280;background:none;border:none;cursor:pointer;margin-top:2px;padding:0;">
                ↻ Reset to auto-suggest
            </button>
            @else
            <span style="font-size:.67rem;color:#9ca3af;display:block;margin-top:2px;">Auto-suggested from complaint & condition</span>
            @endif
        </div>
        @if($triageCategory)
        <div>
            <label class="fl">Priority</label>
            <div class="er-priority" style="background:{{ $this->getTriageCategoryMeta()['color'] }}">
                <div style="font-size:.95rem;font-weight:700;color:#fff;">{{ strtoupper($this->getTriageCategoryMeta()['label']) }}</div>
                <div style="font-size:.72rem;color:rgba(255,255,255,.85);margin-top:1px;">{{ $this->getTriageCategoryMeta()['badge'] }}</div>
            </div>
        </div>
        @endif
    </div>
    <div>
        <label class="fl">Remarks</label>
        <textarea wire:model="triageNotes" class="fi" rows="2" placeholder="Additional observations…"></textarea>
    </div>
</div>

<hr class="er-div"/>

{{-- VITAL SIGNS --}}
<div style="margin-bottom:16px;">
    <div class="er-sl">
        <x-heroicon-o-heart class="w-3 h-3"/> Initial vital signs
        <span style="font-size:.66rem;font-weight:400;text-transform:none;letter-spacing:0;color:#9ca3af;">— temp, pulse & RR required</span>
    </div>
    <div class="g4">
        <div>
            <label class="fl">Temperature °C *</label>
            <input type="number" step="0.1" wire:model="temperature" class="fi" placeholder="36.5"/>
        </div>
        <div>
            <label class="fl">Site</label>
            <select wire:model="temperatureSite" class="fi fi-sel">
                <option>Axilla</option><option>Oral</option><option>Rectal</option><option>Tympanic</option>
            </select>
        </div>
        <div>
            <label class="fl">Pulse rate *</label>
            <input type="number" wire:model="pulseRate" class="fi" placeholder="72"/>
        </div>
        <div>
            <label class="fl">Resp. rate *</label>
            <input type="number" wire:model="respiratoryRate" class="fi" placeholder="18"/>
        </div>
        <div>
            <label class="fl">Blood pressure</label>
            <input wire:model="bloodPressure" class="fi" placeholder="120/80"/>
        </div>
        <div>
            <label class="fl">SpO₂ %</label>
            <input type="number" wire:model="o2Saturation" class="fi" placeholder="98"/>
        </div>
        <div>
            <label class="fl">Pain scale</label>
            <select wire:model="painScale" class="fi fi-sel">
                <option value="">N/A</option>
                @for($i=0;$i<=10;$i++)<option value="{{ $i }}">{{ $i }}</option>@endfor
            </select>
        </div>
        <div>
            <label class="fl">Weight kg</label>
            <input type="number" step="0.1" wire:model="weightKg" class="fi" placeholder="60"/>
        </div>
        <div class="s2">
            <label class="fl">Height cm</label>
            <input type="number" step="0.1" wire:model="heightCm" class="fi" placeholder="165"/>
        </div>
        <div class="s2">
            <label class="fl">Notes</label>
            <input wire:model="vitalNotes" class="fi" placeholder="Any observations…"/>
        </div>
    </div>
</div>

{{-- SAVE --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
    <button wire:click="$set('showTriageForm', false); $set('isUnknownMode', false); $set('confirmNoMatch', false);"
        type="button" class="er-btn er-btn-gray er-btn-sm">
        ← Back to Search
    </button>
    <button @click="showConfirm = true" type="button" class="er-btn er-btn-red">
        Save & Forward to Clerk →
    </button>

    {{-- CONFIRMATION MODAL --}}
    <div x-show="showConfirm" x-cloak
        style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.45);z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;">
        <div style="background:#fff;border-radius:12px;width:100%;max-width:480px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2);"
            class="dark:bg-gray-800" @click.away="showConfirm = false">

            {{-- Modal header --}}
            <div style="background:linear-gradient(135deg,#991b1b,#dc2626);padding:14px 20px;display:flex;align-items:center;gap:10px;">
                <x-heroicon-o-paper-airplane class="w-5 h-5 text-white"/>
                <div>
                    <div style="color:#fff;font-size:.9rem;font-weight:700;">Forward to Clerk?</div>
                    <div style="color:#fca5a5;font-size:.7rem;margin-top:1px;">Please review before forwarding</div>
                </div>
            </div>

            {{-- Modal body --}}
            <div style="padding:18px 20px;">

                {{-- Patient --}}
                <div style="margin-bottom:12px;">
                    <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:4px;">Patient</div>
                    <div style="font-size:.88rem;font-weight:700;color:#111827;" class="dark:text-white">
                        @if($isUnknownMode)
                            Unknown — will be saved as {{ ($patientData['sex'] ?? 'Male') === 'Female' ? 'Jane Doe' : 'John Doe' }} (auto-numbered)
                        @elseif(!empty(trim($patientData['family_name'] ?? '')))
                            {{ strtoupper($patientData['family_name']) }}, {{ $patientData['first_name'] }}
                            {{ $patientData['middle_name'] ? $patientData['middle_name'][0].'.' : '' }}
                        @else
                            Unknown — will default to {{ ($patientData['sex'] ?? 'Male') === 'Female' ? 'Jane Doe' : 'John Doe' }}
                        @endif
                    </div>
                    @if(!$isUnknownMode && ($patientData['sex'] || $patientData['age']))
                    <div style="font-size:.74rem;color:#6b7280;margin-top:2px;">
                        {{ $patientData['sex'] ?? '' }}
                        {{ $patientData['age'] ? '· ' . $patientData['age'] . ' y/o' : '' }}
                    </div>
                    @endif
                </div>

                {{-- Chief complaint --}}
                @if($chiefComplaint)
                <div style="margin-bottom:12px;">
                    <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:4px;">Chief complaint</div>
                    <div style="font-size:.84rem;color:#374151;" class="dark:text-gray-300">{{ $chiefComplaint }}</div>
                </div>
                @endif

                {{-- Triage category --}}
                @if($triageCategory)
                <div style="margin-bottom:12px;">
                    <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:4px;">Triage category</div>
                    <span style="background:{{ $this->getTriageCategoryMeta()['color'] }};color:#fff;font-size:.78rem;font-weight:700;padding:3px 12px;border-radius:9999px;display:inline-block;">
                        {{ strtoupper($this->getTriageCategoryMeta()['label']) }}
                    </span>
                </div>
                @endif

                {{-- Vitals summary --}}
                @if($temperature || $pulseRate || $bloodPressure || $respiratoryRate)
                <div style="margin-bottom:16px;">
                    <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:6px;">Vitals</div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        @if($temperature)
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:5px 10px;text-align:center;" class="dark:bg-gray-700 dark:border-gray-600">
                            <div style="font-size:.9rem;font-weight:700;color:#0f172a;" class="dark:text-white">{{ $temperature }}°</div>
                            <div style="font-size:.62rem;color:#64748b;">Temp</div>
                        </div>
                        @endif
                        @if($pulseRate)
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:5px 10px;text-align:center;" class="dark:bg-gray-700 dark:border-gray-600">
                            <div style="font-size:.9rem;font-weight:700;color:#0f172a;" class="dark:text-white">{{ $pulseRate }}</div>
                            <div style="font-size:.62rem;color:#64748b;">Pulse</div>
                        </div>
                        @endif
                        @if($bloodPressure)
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:5px 10px;text-align:center;" class="dark:bg-gray-700 dark:border-gray-600">
                            <div style="font-size:.9rem;font-weight:700;color:#0f172a;" class="dark:text-white">{{ $bloodPressure }}</div>
                            <div style="font-size:.62rem;color:#64748b;">BP</div>
                        </div>
                        @endif
                        @if($respiratoryRate)
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:5px 10px;text-align:center;" class="dark:bg-gray-700 dark:border-gray-600">
                            <div style="font-size:.9rem;font-weight:700;color:#0f172a;" class="dark:text-white">{{ $respiratoryRate }}</div>
                            <div style="font-size:.62rem;color:#64748b;">RR</div>
                        </div>
                        @endif
                        @if($o2Saturation)
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:5px 10px;text-align:center;" class="dark:bg-gray-700 dark:border-gray-600">
                            <div style="font-size:.9rem;font-weight:700;color:#0f172a;" class="dark:text-white">{{ $o2Saturation }}%</div>
                            <div style="font-size:.62rem;color:#64748b;">SpO₂</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Nurse --}}
                <div style="font-size:.72rem;color:#9ca3af;">
                    Triaged by: <strong>{{ $triageNurseOnDuty }}</strong> · {{ now()->format('M d, Y H:i') }}
                </div>
            </div>

            {{-- Modal footer --}}
            <div style="padding:12px 20px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;gap:8px;" class="dark:border-gray-700">
                <button @click="showConfirm = false" type="button" class="er-btn er-btn-gray er-btn-sm">
                    ← Go back & edit
                </button>
                <button wire:click="saveTriage" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                    @click="showConfirm = false" class="er-btn er-btn-red">
                    <span wire:loading.remove wire:target="saveTriage">Confirm & Forward to Clerk →</span>
                    <span wire:loading wire:target="saveTriage">Saving…</span>
                </button>
            </div>

        </div>
    </div>
</div>

@endif {{-- end triage form --}}

</div>{{-- end tab triage --}}

{{-- ═══ TAB: ASSIGN DOCTOR ═══ --}}
<div x-show="tab === 'assign'" x-cloak>

    {{-- Show waiting info if there are patients still with clerk --}}
    @php
        $hasTriage = \Illuminate\Support\Facades\Schema::hasColumn('visits', 'triage_nurse_id');
        $waitingForClerk = \App\Models\Visit::where('visit_type','ER')
            ->when($hasTriage,
                fn($q) => $q->where('status','triage'),
                fn($q) => $q->whereNull('clerk_id')->whereIn('status',['registered','vitals_done'])
            )->count();
    @endphp
    @if($waitingForClerk > 0)
    <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
        <x-heroicon-o-clock class="w-4 h-4 text-amber-600" style="flex-shrink:0;"/>
        <div style="font-size:.78rem;color:#92400e;">
            <strong>{{ $waitingForClerk }} patient(s)</strong> still waiting for clerk registration.
            They will appear here once the clerk completes registration and a case number is generated.
        </div>
    </div>
    @endif

    <div style="margin-bottom:12px;">
        <input wire:model.live="assignSearch" class="fi" placeholder="Search by name or case no…" style="max-width:280px;"/>
    </div>
    @forelse($this->registeredErVisits as $visit)
    <div class="er-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div style="flex:1;min-width:180px;">
                <div style="font-weight:700;font-size:.88rem;">{{ $visit->patient->full_name }}</div>
                <div style="font-size:.73rem;color:#6b7280;margin-top:2px;">
                    <span class="er-case">{{ $visit->patient->case_no }}</span>
                    &nbsp;{{ $visit->patient->sex }} · {{ $visit->patient->age_display }}
                </div>
                <div style="font-size:.78rem;margin-top:5px;"><strong>CC:</strong> {{ $visit->chief_complaint }}</div>
                @if($visit->triage_category ?? null)
                    @php $m = \App\Filament\Nurse\Pages\ErTriage::TRIAGE_CATEGORIES[$visit->triage_category] ?? null; @endphp
                    @if($m)<span style="background:{{ $m['color'] }};color:#fff;font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:9999px;display:inline-block;margin-top:3px;">{{ strtoupper($m['label']) }}</span>@endif
                @endif
                @if($visit->latestVitals)
                <div style="display:flex;gap:5px;margin-top:7px;flex-wrap:wrap;">
                    @if($visit->latestVitals->temperature)<div class="er-vital"><div class="er-vital-v">{{ $visit->latestVitals->temperature }}°</div><div class="er-vital-l">Temp</div></div>@endif
                    @if($visit->latestVitals->pulse_rate)<div class="er-vital"><div class="er-vital-v">{{ $visit->latestVitals->pulse_rate }}</div><div class="er-vital-l">Pulse</div></div>@endif
                    @if($visit->latestVitals->blood_pressure)<div class="er-vital"><div class="er-vital-v">{{ $visit->latestVitals->blood_pressure }}</div><div class="er-vital-l">BP</div></div>@endif
                    @if($visit->latestVitals->respiratory_rate)<div class="er-vital"><div class="er-vital-v">{{ $visit->latestVitals->respiratory_rate }}</div><div class="er-vital-l">RR</div></div>@endif
                    @if($visit->latestVitals->o2_saturation)<div class="er-vital"><div class="er-vital-v">{{ $visit->latestVitals->o2_saturation }}%</div><div class="er-vital-l">SpO₂</div></div>@endif
                </div>
                @endif
            </div>
            <div style="min-width:200px;flex-shrink:0;" x-data="{ doctorId: '' }">
                <label class="fl">Assign doctor</label>
                <div style="display:flex;gap:7px;align-items:center;">
                    <select x-model="doctorId" class="fi fi-sel" style="flex:1;">
                        <option value="">Select doctor…</option>
                        @foreach($this->doctors as $doc)<option value="{{ $doc['id'] }}">{{ $doc['label'] }}</option>@endforeach
                    </select>
                    <button @click="if(doctorId) $wire.assignDoctor({{ $visit->id }}, doctorId)"
                        class="er-btn er-btn-blue er-btn-sm" :disabled="!doctorId">Assign</button>
                </div>
                <div style="font-size:.66rem;color:#9ca3af;margin-top:3px;">Registered {{ $visit->registered_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:36px;color:#9ca3af;">
        <x-heroicon-o-user-group class="w-7 h-7 mx-auto mb-2"/>
        <p style="font-size:.82rem;font-weight:600;">No patients ready for doctor assignment.</p>
        <p style="font-size:.73rem;margin-top:4px;">
            Patients appear here only after the clerk completes registration.<br>
            Check the <strong>Triage Queue</strong> tab to see pending registrations.
        </p>
    </div>
    @endforelse
</div>{{-- end tab assign --}}

{{-- ═══ TAB: TRIAGE QUEUE ═══ --}}
<div x-show="tab === 'queue'" x-cloak>
    @php
        $hasTriage = \Illuminate\Support\Facades\Schema::hasColumn('visits', 'triage_nurse_id');

        // Awaiting clerk registration
        $pendingQueue = \App\Models\Visit::with(['patient','latestVitals'])
            ->where('visit_type','ER')
            ->when($hasTriage,
                fn($q) => $q->where('status','triage'),
                fn($q) => $q->whereNull('clerk_id')->whereIn('status',['registered','vitals_done'])
            )
            ->orderBy('registered_at','asc')->get();

        // Already registered by clerk today — awaiting doctor assignment
        $registeredToday = \App\Models\Visit::with(['patient','latestVitals'])
            ->where('visit_type','ER')
            ->where('status','registered')
            ->whereNotNull('clerk_id')
            ->whereNull('assigned_doctor_id')
            ->whereDate('registered_at', today())
            ->orderBy('registered_at','desc')->get();
    @endphp

    {{-- PENDING SECTION --}}
    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:8px;display:flex;align-items:center;gap:5px;">
        <x-heroicon-o-clock class="w-3 h-3"/> Waiting for clerk registration
        @if($pendingQueue->isNotEmpty())
        <span style="background:#fee2e2;color:#b91c1c;border-radius:9999px;padding:1px 8px;font-size:.68rem;font-weight:700;">{{ $pendingQueue->count() }}</span>
        @endif
    </div>

    @forelse($pendingQueue as $visit)
    <div style="border:1.5px solid #fde68a;border-radius:10px;padding:11px 14px;margin-bottom:8px;background:#fffbeb;" class="dark:bg-amber-950 dark:border-amber-800">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <div style="font-weight:700;font-size:.86rem;">{{ $visit->patient->full_name }}</div>
                <div style="font-size:.72rem;color:#6b7280;margin-top:1px;">
                    {{ $visit->patient->sex }} · {{ $visit->patient->age_display }}
                    &nbsp;·&nbsp;<strong>CC:</strong> {{ $visit->chief_complaint }}
                </div>
                @if($visit->triage_category ?? null)
                    @php $m = \App\Filament\Nurse\Pages\ErTriage::TRIAGE_CATEGORIES[$visit->triage_category] ?? null; @endphp
                    @if($m)<span style="background:{{ $m['color'] }};color:#fff;font-size:.66rem;font-weight:700;padding:1px 8px;border-radius:9999px;display:inline-block;margin-top:3px;">{{ strtoupper($m['label']) }}</span>@endif
                @endif
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <span style="background:#fee2e2;color:#b91c1c;border-radius:9999px;padding:2px 9px;font-size:.68rem;font-weight:700;">⏳ Awaiting clerk</span>
                <div style="font-size:.66rem;color:#9ca3af;margin-top:2px;">{{ ($visit->triage_at ?? $visit->registered_at)?->diffForHumans() }}</div>
                @if($visit->triage_nurse_name ?? null)<div style="font-size:.66rem;color:#9ca3af;">by {{ $visit->triage_nurse_name }}</div>@endif
            </div>
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:20px;color:#9ca3af;border:1px dashed #e5e7eb;border-radius:8px;margin-bottom:16px;">
        <p style="font-size:.8rem;">No patients waiting for clerk registration.</p>
    </div>
    @endforelse

    {{-- REGISTERED SECTION --}}
    @if($registeredToday->isNotEmpty())
    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin:16px 0 8px;display:flex;align-items:center;gap:5px;">
        <x-heroicon-o-check-circle class="w-3 h-3 text-green-500"/> Registered today — awaiting doctor assignment
        <span style="background:#dcfce7;color:#15803d;border-radius:9999px;padding:1px 8px;font-size:.68rem;font-weight:700;">{{ $registeredToday->count() }}</span>
    </div>
    @foreach($registeredToday as $visit)
    <div style="border:1.5px solid #86efac;border-radius:10px;padding:11px 14px;margin-bottom:8px;background:#f0fdf4;" class="dark:bg-green-950 dark:border-green-800">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <div style="font-weight:700;font-size:.86rem;">{{ $visit->patient->full_name }}</div>
                <div style="font-size:.72rem;color:#6b7280;margin-top:1px;">
                    <span style="background:#dbeafe;color:#1d4ed8;border-radius:9999px;padding:1px 7px;font-size:.68rem;font-weight:700;">{{ $visit->patient->case_no }}</span>
                    &nbsp;{{ $visit->patient->sex }} · {{ $visit->patient->age_display }}
                    &nbsp;·&nbsp;<strong>CC:</strong> {{ $visit->chief_complaint }}
                </div>
                @if($visit->triage_category ?? null)
                    @php $m = \App\Filament\Nurse\Pages\ErTriage::TRIAGE_CATEGORIES[$visit->triage_category] ?? null; @endphp
                    @if($m)<span style="background:{{ $m['color'] }};color:#fff;font-size:.66rem;font-weight:700;padding:1px 8px;border-radius:9999px;display:inline-block;margin-top:3px;">{{ strtoupper($m['label']) }}</span>@endif
                @endif
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <span style="background:#dcfce7;color:#15803d;border-radius:9999px;padding:2px 9px;font-size:.68rem;font-weight:700;">✓ Registered</span>
                <div style="font-size:.66rem;color:#9ca3af;margin-top:2px;">{{ $visit->registered_at->diffForHumans() }}</div>
                <div style="font-size:.66rem;color:#16a34a;font-weight:600;margin-top:2px;">→ Go to Assign Doctor tab</div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

</div>{{-- end tab queue --}}

</div>{{-- end x-data --}}
</div>{{-- end er-wrap --}}
</x-filament-panels::page>