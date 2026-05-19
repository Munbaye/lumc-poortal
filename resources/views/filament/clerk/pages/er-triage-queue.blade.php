<x-filament-panels::page>
<style>
.tq-wrap{max-width:900px}
.tq-header{background:linear-gradient(135deg,#1e3a5f 0%,#1d4ed8 100%);border-radius:10px;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.fi{width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:7px 10px;font-size:.84rem;color:#111827;background:#fff;box-sizing:border-box;transition:border-color .15s}
.dark .fi{background:#374151;border-color:#4b5563;color:#f9fafb}
.fi:focus{outline:none;border-color:#1d4ed8;box-shadow:0 0 0 2px rgba(29,78,216,.08)}
.fi-sel{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;background-size:14px;padding-right:28px}
.fl{display:block;font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;margin-bottom:3px}
.dark .fl{color:#9ca3af}
.req{color:#dc2626}
.opt{color:#9ca3af;font-weight:400;text-transform:none;letter-spacing:0;font-size:.65rem;}
.g2{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
.g3{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.g4{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.s2{grid-column:span 2}.s3{grid-column:span 3}.s4{grid-column:span 4}
.badge{display:inline-flex;align-items:center;padding:2px 9px;border-radius:9999px;font-size:.68rem;font-weight:600}
.badge-r{background:#fee2e2;color:#b91c1c}.badge-b{background:#dbeafe;color:#1d4ed8}.badge-g{background:#dcfce7;color:#15803d}.badge-a{background:#fef3c7;color:#92400e}
.dark .badge-r{background:#450a0a;color:#fca5a5}.dark .badge-b{background:#0c1a2e;color:#93c5fd}.dark .badge-g{background:#052e16;color:#86efac}
.vc{display:inline-flex;flex-direction:column;align-items:center;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:5px 8px;min-width:48px}
.dark .vc{background:#1e293b;border-color:#334155}
.vv{font-size:.86rem;font-weight:700;color:#0f172a;line-height:1}
.dark .vv{color:#f1f5f9}
.vl{font-size:.6rem;color:#64748b;margin-top:1px}
.visit-row{border:1.5px solid #fde68a;border-radius:10px;padding:14px 16px;margin-bottom:10px;background:#fffbeb}
.dark .visit-row{background:#2d1c00;border-color:#92400e}
.btn{border:none;border-radius:7px;padding:8px 16px;font-size:.82rem;font-weight:600;cursor:pointer;transition:opacity .15s}
.btn:hover{opacity:.86}
.btn-blue{background:#1e3a5f;color:#fff}
.btn-green{background:#16a34a;color:#fff}
.btn-red{background:#dc2626;color:#fff}
.btn-gray{background:#e5e7eb;color:#374151}
.dark .btn-gray{background:#374151;color:#f9fafb}
.btn-sm{padding:5px 12px;font-size:.76rem}
.mo{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px}
.mo-box{background:#fff;border-radius:12px;width:100%;max-width:580px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.25)}
.dark .mo-box{background:#1f2937}
.mo-head{background:linear-gradient(135deg,#1e3a5f,#1d4ed8);padding:14px 20px;border-radius:12px 12px 0 0;display:flex;align-items:center;justify-content:space-between}
.mo-body{padding:20px}
.mo-foot{padding:12px 20px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;gap:8px}
.dark .mo-foot{border-color:#374151}
.sl{font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:8px;display:flex;align-items:center;gap:5px}
.div{border:none;border-top:1px solid #f1f5f9;margin:14px 0}
.dark .div{border-color:#374151}
.phil-box{background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:12px 14px;margin-top:4px}
.dark .phil-box{background:#0c1a2e;border-color:#1e40af}
.info-box{border-radius:8px;padding:10px 14px;font-size:.76rem;margin-bottom:14px;display:flex;align-items:flex-start;gap:8px}
</style>

<div class="tq-wrap">

{{-- HEADER --}}
<div class="tq-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <x-heroicon-o-queue-list class="w-5 h-5 text-white"/>
        </div>
        <div>
            <div style="color:#fff;font-size:.92rem;font-weight:700;">ER Triage Queue</div>
            <div style="color:#93c5fd;font-size:.7rem;margin-top:1px;">Patients triaged by nurse — complete official registration</div>
        </div>
    </div>
    <span style="color:#fff;font-size:.78rem;opacity:.8;">{{ now()->format('M d, Y H:i') }}</span>
</div>

{{-- LEGEND --}}
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px;font-size:.72rem;color:#6b7280;">
    <div style="display:flex;align-items:center;gap:5px;">
        <span style="width:10px;height:10px;background:#dc2626;border-radius:50%;display:inline-block;"></span> Red/Orange = 1-click register
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
        <span style="width:10px;height:10px;background:#1e3a5f;border-radius:50%;display:inline-block;"></span> Yellow/Green = fill demographics
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
        <span style="width:10px;height:10px;background:#9ca3af;border-radius:50%;display:inline-block;"></span> Unknown = 1-click register
    </div>
</div>

{{-- SEARCH --}}
<div style="margin-bottom:14px;">
    <input wire:model.live="search" class="fi" placeholder="Search by patient name…" style="max-width:300px;"/>
</div>

{{-- QUEUE --}}
@forelse($this->triageVisits as $visit)
@php
    $needsModal = \App\Filament\Clerk\Pages\ErTriageQueue::needsModal($visit);
    $meta = \App\Filament\Nurse\Pages\ErTriage::TRIAGE_CATEGORIES[$visit->triage_category ?? ''] ?? null;
@endphp
<div class="visit-row">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">

        {{-- Patient info --}}
        <div style="flex:1;min-width:200px;">
            <div style="display:flex;align-items:center;gap:7px;flex-wrap:wrap;margin-bottom:4px;">
                <span style="font-weight:700;font-size:.92rem;">{{ $visit->patient->full_name }}</span>
                @if($visit->patient->is_unknown)
                    <span class="badge badge-r">Unknown</span>
                @else
                    <span class="badge badge-b">{{ $visit->patient->has_incomplete_info ? 'Incomplete' : 'Pre-registered' }}</span>
                @endif
                @if($meta)
                    <span style="background:{{ $meta['color'] }};color:#fff;font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:9999px;">{{ strtoupper($meta['label']) }}</span>
                @endif
            </div>
            <div style="font-size:.75rem;color:#6b7280;margin-bottom:5px;">
                {{ $visit->patient->sex }} · {{ $visit->patient->age_display }}
            </div>
            <div style="font-size:.8rem;color:#374151;margin-bottom:5px;">
                <strong>CC:</strong> {{ $visit->chief_complaint }}
            </div>
            @if($visit->condition_on_arrival || $visit->brought_by)
            <div style="font-size:.73rem;color:#6b7280;">
                @if($visit->condition_on_arrival)<strong>Arrival:</strong> {{ $visit->condition_on_arrival }}@endif
                @if($visit->brought_by) · <strong>By:</strong> {{ $visit->brought_by }}@endif
            </div>
            @endif
        </div>

        {{-- Vitals --}}
        <div style="flex-shrink:0;">
            @if($visit->latestVitals)
            <div style="font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;margin-bottom:5px;">Triage vitals</div>
            <div style="display:flex;gap:5px;flex-wrap:wrap;">
                @if($visit->latestVitals->temperature)<div class="vc"><span class="vv">{{ $visit->latestVitals->temperature }}°</span><span class="vl">Temp</span></div>@endif
                @if($visit->latestVitals->pulse_rate)<div class="vc"><span class="vv">{{ $visit->latestVitals->pulse_rate }}</span><span class="vl">Pulse</span></div>@endif
                @if($visit->latestVitals->blood_pressure)<div class="vc"><span class="vv">{{ $visit->latestVitals->blood_pressure }}</span><span class="vl">BP</span></div>@endif
                @if($visit->latestVitals->respiratory_rate)<div class="vc"><span class="vv">{{ $visit->latestVitals->respiratory_rate }}</span><span class="vl">RR</span></div>@endif
                @if($visit->latestVitals->o2_saturation)<div class="vc"><span class="vv">{{ $visit->latestVitals->o2_saturation }}%</span><span class="vl">SpO₂</span></div>@endif
            </div>
            @else
            <span style="font-size:.73rem;color:#9ca3af;">No vitals</span>
            @endif
        </div>

        {{-- Action --}}
        <div style="flex-shrink:0;text-align:right;">
            <div style="font-size:.67rem;color:#9ca3af;margin-bottom:6px;">
                Triaged {{ ($visit->triage_at ?? $visit->registered_at)?->diffForHumans() }}
                @if($visit->triageNurse)<br>by {{ $visit->triageNurse->full_name }}@endif
            </div>

            @if(!$needsModal)
            {{-- 1-click: unknown OR red/orange critical --}}
            <button wire:click="quickRegister({{ $visit->id }})"
                wire:loading.attr="disabled"
                wire:target="quickRegister({{ $visit->id }})"
                class="btn btn-{{ $visit->patient->is_unknown ? 'green' : 'red' }} btn-sm">
                <span wire:loading.remove wire:target="quickRegister({{ $visit->id }})">✓ Register Now</span>
                <span wire:loading wire:target="quickRegister({{ $visit->id }})">…</span>
            </button>
            <div style="font-size:.63rem;color:#9ca3af;margin-top:2px;">
                {{ $visit->patient->is_unknown ? '1-click · fill identity later' : '1-click · fill demographics after stabilized' }}
            </div>
            @else
            {{-- Modal: yellow/green non-critical --}}
            <button wire:click="openRegisterModal({{ $visit->id }})" class="btn btn-blue btn-sm">
                Complete & Register
            </button>
            <div style="font-size:.63rem;color:#9ca3af;margin-top:2px;">Fill demographics first</div>
            @endif
        </div>

    </div>
</div>
@empty
<div style="text-align:center;padding:48px;color:#9ca3af;">
    <x-heroicon-o-check-circle class="w-10 h-10 mx-auto mb-3 text-green-400"/>
    <p style="font-size:.88rem;font-weight:600;">No patients in triage queue.</p>
    <p style="font-size:.78rem;margin-top:4px;">Waiting for nurse to triage new ER arrivals.</p>
</div>
@endforelse

{{-- ═══ REGISTRATION MODAL (yellow/green patients) ═══ --}}
@if($showRegisterModal)
<div class="mo" wire:click.self="closeModal">
    <div class="mo-box">

        <div class="mo-head">
            <div style="display:flex;align-items:center;gap:10px;">
                <x-heroicon-o-user-plus class="w-5 h-5 text-white"/>
                <div>
                    <div style="color:#fff;font-size:.9rem;font-weight:700;">Complete ER Registration</div>
                    <div style="color:#93c5fd;font-size:.7rem;">Pre-filled from nurse triage — complete missing info</div>
                </div>
            </div>
            <button wire:click="closeModal" type="button"
                style="color:rgba(255,255,255,.7);background:none;border:none;cursor:pointer;font-size:1.2rem;line-height:1;">✕</button>
        </div>

        <div class="mo-body">

            {{-- Patient name --}}
            <div class="sl"><x-heroicon-o-user class="w-3 h-3"/> Patient identity</div>
            <div class="g4" style="margin-bottom:12px;">
                <div>
                    <label class="fl">Family name <span class="req">*</span></label>
                    <input wire:model="regData.family_name" class="fi" placeholder="dela Cruz"/>
                    @error('regData.family_name')<div style="font-size:.69rem;color:#dc2626;margin-top:2px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="fl">First name <span class="req">*</span></label>
                    <input wire:model="regData.first_name" class="fi" placeholder="Juan"/>
                    @error('regData.first_name')<div style="font-size:.69rem;color:#dc2626;margin-top:2px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="fl">Middle name</label>
                    <input wire:model="regData.middle_name" class="fi"/>
                </div>
                <div>
                    <label class="fl">Sex <span class="req">*</span></label>
                    <select wire:model="regData.sex" class="fi fi-sel">
                        <option value="">—</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    @error('regData.sex')<div style="font-size:.69rem;color:#dc2626;margin-top:2px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="fl">Birthday</label>
                    <input type="date" wire:model.live="regData.birthday" class="fi"/>
                </div>
                <div>
                    <label class="fl">Age</label>
                    <input type="number" wire:model="regData.age" class="fi"/>
                </div>
                <div class="s2">
                    <label class="fl">Contact no.</label>
                    <input wire:model="regData.contact_number" class="fi" placeholder="09xx"/>
                </div>
            </div>

            <hr class="div"/>

            {{-- Demographics --}}
            <div class="sl"><x-heroicon-o-map-pin class="w-3 h-3"/> Demographics</div>
            <div style="margin-bottom:10px;">
                <label class="fl">Full address <span class="req">*</span></label>
                <input wire:model="regData.address" class="fi" placeholder="Barangay, City, Province"/>
                @error('regData.address')<div style="font-size:.69rem;color:#dc2626;margin-top:2px;">{{ $message }}</div>@enderror
            </div>
            <div class="g3" style="margin-bottom:12px;">
                <div>
                    <label class="fl">Civil status <span class="req">*</span></label>
                    <select wire:model="regData.civil_status" class="fi fi-sel">
                        <option value="">—</option>
                        <option>Single</option>
                        <option>Married</option>
                        <option>Widowed</option>
                        <option>Separated</option>
                        <option>Annulled</option>
                    </select>
                    @error('regData.civil_status')<div style="font-size:.69rem;color:#dc2626;margin-top:2px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="fl">Occupation</label>
                    <input wire:model="regData.occupation" class="fi" placeholder="e.g. Student"/>
                </div>
                <div>
                    <label class="fl">Payment class <span class="req">*</span></label>
                    <select wire:model="regData.payment_class" class="fi fi-sel">
                        <option value="">—</option>
                        <option>PhilHealth</option>
                        <option>Private</option>
                        <option>Service</option>
                        <option>Indigent</option>
                        <option>Charity</option>
                    </select>
                    @error('regData.payment_class')<div style="font-size:.69rem;color:#dc2626;margin-top:2px;">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- PhilHealth — optional --}}
            <hr class="div"/>
            <div class="sl">
                <x-heroicon-o-identification class="w-3 h-3"/> PhilHealth
                <span class="opt">— optional, can be filled later</span>
            </div>
            <div class="phil-box">
                <div class="g2">
                    <div>
                        <label class="fl">PhilHealth ID no.</label>
                        <input wire:model="regData.philhealth_id" class="fi" placeholder="xx-xxxxxxxxx-x"/>
                    </div>
                    <div>
                        <label class="fl">Member type</label>
                        <select wire:model="regData.philhealth_type" class="fi fi-sel">
                            <option value="">— skip for now</option>
                            <option value="Government">Government</option>
                            <option value="Private">Private</option>
                            <option value="Self-Employed">Self-Employed</option>
                            <option value="Indigent">Indigent</option>
                        </select>
                    </div>
                </div>
                <div style="font-size:.7rem;color:#0369a1;margin-top:6px;">
                    ℹ If PhilHealth info is not available now, skip — clerk can update in patient record later.
                </div>
            </div>

            <hr class="div"/>

            {{-- Chief complaint --}}
            <div class="sl"><x-heroicon-o-clipboard-document-check class="w-3 h-3"/> Chief complaint</div>
            <input wire:model="regData.chief_complaint" class="fi" placeholder="Main reason for ER visit"/>

        </div>

        <div class="mo-foot">
            <button wire:click="closeModal" type="button" class="btn btn-gray btn-sm">Cancel</button>
            <button wire:click="saveRegistration"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-60"
                class="btn btn-blue">
                <span wire:loading.remove wire:target="saveRegistration">Register Patient →</span>
                <span wire:loading wire:target="saveRegistration">Registering…</span>
            </button>
        </div>

    </div>
</div>
@endif

</div>
</x-filament-panels::page>