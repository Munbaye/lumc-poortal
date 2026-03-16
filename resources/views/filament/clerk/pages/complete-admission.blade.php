<x-filament-panels::page>

<style>
.ca-section  { background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:22px;margin-bottom:14px; }
.ca-label    { display:block;font-size:.78rem;font-weight:700;margin-bottom:5px;color:#374151; }
.ca-sublabel { font-size:.68rem;font-weight:400;color:#9ca3af;margin-left:3px; }
.ca-input    { width:100%;border:1px solid #d1d5db;border-radius:7px;padding:9px 12px;
               font-size:.875rem;background:#fff;color:#111827;box-sizing:border-box;outline:none; }
.ca-input:focus { border-color:#1d4ed8;box-shadow:0 0 0 3px rgba(29,78,216,.1); }
.ca-grid-2   { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
.ca-grid-3   { display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px; }
.ca-sec-head { display:flex;align-items:center;gap:10px;margin-bottom:16px;
               padding-bottom:10px;border-bottom:1px solid #f3f4f6; }
.ca-num      { background:#111827;color:#fff;font-size:.68rem;font-weight:700;
               border-radius:4px;padding:2px 8px; }
.ca-sec-title { font-size:.93rem;font-weight:700;color:#111827; }
.ca-opt-tag  { font-size:.68rem;background:#f3f4f6;color:#6b7280;
               padding:2px 7px;border-radius:4px;font-weight:600; }
.ca-select {
    -webkit-appearance: menulist !important;
    -moz-appearance: menulist !important;
    appearance: menulist !important;
    background-image: none !important;
}
</style>

@if($visit && $patient)

{{-- ── Header ──────────────────────────────────────────────────────────────── --}}
<div style="background:linear-gradient(135deg,#1e3a5f 0%,#1d4ed8 100%);border-radius:10px;
            padding:16px 24px;margin-bottom:20px;
            display:flex;align-items:center;justify-content:space-between;">
    <div>
        <p style="color:#93c5fd;font-size:11px;letter-spacing:.1em;text-transform:uppercase;margin:0;">
            La Union Medical Center
        </p>
        <h1 style="color:#fff;font-size:1.15rem;font-weight:700;margin:4px 0 0;">
            📋 Complete Admission
        </h1>
    </div>
    <div style="background:rgba(255,255,255,.18);border-radius:8px;padding:10px 18px;text-align:center;">
        <p style="font-family:monospace;color:#e0f2fe;font-size:.95rem;font-weight:700;margin:0;">
            {{ $patient->case_no }}
        </p>
        <p style="color:#bfdbfe;font-size:.72rem;margin:0;">Case No</p>
    </div>
</div>

{{-- ── Doctor's Admission Summary (read-only) ──────────────────────────────── --}}
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;
            padding:16px 20px;margin-bottom:20px;">
    <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;
              color:#065f46;margin:0 0 12px;">
        Doctor's Admission Summary
    </p>

    <div style="display:flex;flex-wrap:wrap;gap:20px;margin-bottom:{{ $visit->doctorsOrders->count() > 0 ? '14px' : '0' }};">

        <div>
            <p style="font-size:.68rem;color:#6b7280;margin:0 0 2px;">Patient</p>
            <p style="font-weight:700;color:#111827;margin:0;font-size:.92rem;">{{ $patient->full_name }}</p>
            <p style="font-size:.75rem;color:#6b7280;margin-top:1px;">{{ $patient->age_display }} / {{ $patient->sex }}</p>
        </div>

        <div style="flex:1;min-width:200px;">
            <p style="font-size:.68rem;color:#6b7280;margin:0 0 2px;">Admitting Diagnosis</p>
            <p style="font-weight:700;color:#065f46;margin:0;font-size:.9rem;">
                {{ $visit->admitting_diagnosis ?? $visit->medicalHistory?->diagnosis ?? $visit->chief_complaint }}
            </p>
        </div>

        @php $svc = $visit->admitted_service ?? $visit->medicalHistory?->service ?? null; @endphp
        @if($svc)
        <div>
            <p style="font-size:.68rem;color:#6b7280;margin:0 0 4px;">Admitting Service</p>
            <span style="display:inline-block;background:#059669;color:#fff;
                         font-size:.78rem;font-weight:700;padding:4px 14px;border-radius:9999px;">
                {{ $svc }}
            </span>
        </div>
        @endif

        @if($visit->medicalHistory?->doctor)
        <div>
            <p style="font-size:.68rem;color:#6b7280;margin:0 0 2px;">Admitting Physician</p>
            <p style="font-weight:700;color:#111827;margin:0;font-size:.88rem;">
                Dr. {{ $visit->medicalHistory->doctor->name }}
            </p>
            @if($visit->medicalHistory->doctor->specialty)
            <p style="font-size:.72rem;color:#6b7280;margin-top:1px;">
                {{ $visit->medicalHistory->doctor->specialty }}
            </p>
            @endif
        </div>
        @endif

        <div>
            <p style="font-size:.68rem;color:#6b7280;margin:0 0 2px;">Entry</p>
            <span style="display:inline-block;padding:3px 10px;border-radius:4px;
                         font-size:.75rem;font-weight:700;
                         background:{{ $visit->visit_type==='ER'?'#fef2f2':'#eff6ff' }};
                         color:{{ $visit->visit_type==='ER'?'#dc2626':'#1d4ed8' }};">
                {{ $visit->visit_type==='ER'?'🚑 ER':'📋 OPD' }}
            </span>
        </div>

        <div>
            <p style="font-size:.68rem;color:#6b7280;margin:0 0 2px;">Doctor Admitted At</p>
            <p style="font-weight:700;color:#111827;margin:0;font-size:.82rem;">
                🕐 {{ $doctorAdmittedAtDisplay }}
            </p>
        </div>

    </div>

    {{-- Doctor's orders (read-only for clerk) --}}
    @if($visit->doctorsOrders->count() > 0)
    <div style="padding-top:12px;border-top:1px solid #bbf7d0;">
        <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;
                  color:#6b7280;margin:0 0 6px;">
            Doctor's Orders ({{ $visit->doctorsOrders->count() }} total)
        </p>
        <ol style="margin:0;padding:0;list-style:none;display:grid;gap:4px;">
            @foreach($visit->doctorsOrders as $i => $ord)
            <li style="display:flex;gap:8px;font-size:.82rem;padding:6px 10px;
                       background:#fff;border-radius:5px;border:1px solid #d1fae5;color:#374151;">
                <span style="color:#9ca3af;flex-shrink:0;font-size:.75rem;">{{ $i+1 }}.</span>
                <span style="flex:1;">{{ $ord->order_text }}</span>
                <span style="font-size:.7rem;font-weight:600;flex-shrink:0;
                             color:{{ $ord->is_completed?'#16a34a':'#d97706' }};">
                    {{ $ord->is_completed ? '✓ Done' : 'Pending' }}
                </span>
            </li>
            @endforeach
        </ol>
    </div>
    @endif
</div>

{{-- ══ SECTION 1: PAYMENT CLASSIFICATION ══════════════════════════════════════ --}}
<div class="ca-section dark:bg-gray-900 dark:border-gray-700">
    <div class="ca-sec-head dark:border-gray-700">
        <span class="ca-num dark:bg-white dark:text-gray-900">1</span>
        <span class="ca-sec-title dark:text-white">Payment Classification</span>
        <span style="font-size:.72rem;color:#dc2626;font-weight:700;">Required</span>
    </div>
    <p style="font-size:.82rem;color:#6b7280;margin:0 0 14px;">
        Determines billing pathway. Decide based on social service class and PhilHealth status below.
    </p>
    <div class="ca-grid-2">
        <label style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:14px;
                      border-radius:8px;
                      border:2px solid {{ $paymentClass==='Charity'?'#16a34a':'#e5e7eb' }};
                      background:{{ $paymentClass==='Charity'?'#f0fdf4':'#fff' }};"
               class="{{ $paymentClass!=='Charity'?'dark:bg-gray-800 dark:border-gray-600':'' }}">
            <input type="radio" wire:model.live="paymentClass" value="Charity"
                   style="accent-color:#16a34a;width:18px;height:18px;flex-shrink:0;">
            <div>
                <p style="font-weight:700;font-size:.88rem;margin:0 0 2px;
                           color:{{ $paymentClass==='Charity'?'#166534':'#374151' }};"
                   class="{{ $paymentClass!=='Charity'?'dark:text-gray-200':'' }}">🏥 Charity</p>
                <p style="font-size:.72rem;color:#6b7280;margin:0;" class="dark:text-gray-400">
                    PhilHealth Indigent / Govt · 4Ps · Malasakit Center
                </p>
            </div>
        </label>
        <label style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:14px;
                      border-radius:8px;
                      border:2px solid {{ $paymentClass==='Private'?'#7c3aed':'#e5e7eb' }};
                      background:{{ $paymentClass==='Private'?'#faf5ff':'#fff' }};"
               class="{{ $paymentClass!=='Private'?'dark:bg-gray-800 dark:border-gray-600':'' }}">
            <input type="radio" wire:model.live="paymentClass" value="Private"
                   style="accent-color:#7c3aed;width:18px;height:18px;flex-shrink:0;">
            <div>
                <p style="font-weight:700;font-size:.88rem;margin:0 0 2px;
                           color:{{ $paymentClass==='Private'?'#6d28d9':'#374151' }};"
                   class="{{ $paymentClass!=='Private'?'dark:text-gray-200':'' }}">💳 Private Pay</p>
                <p style="font-size:.72rem;color:#6b7280;margin:0;" class="dark:text-gray-400">
                    PhilHealth Private / Self-Employed · HMO · Cash
                </p>
            </div>
        </label>
    </div>
    @error('paymentClass')
    <p style="color:#ef4444;font-size:.75rem;margin-top:6px;">⚠️ {{ $message }}</p>
    @enderror
</div>

{{-- ══ SECTION 2: PERSONAL DETAILS ════════════════════════════════════════════ --}}
<div class="ca-section dark:bg-gray-900 dark:border-gray-700">
    <div class="ca-sec-head dark:border-gray-700">
        <span class="ca-num dark:bg-white dark:text-gray-900">2</span>
        <span class="ca-sec-title dark:text-white">Personal Details</span>
        <span class="ca-opt-tag">Optional</span>
    </div>

    <div class="ca-grid-3" style="margin-bottom:14px;">
        <div>
            <label class="ca-label dark:text-gray-300">
                Birthplace<span class="ca-sublabel">(city/municipality, province)</span>
            </label>
            <input type="text" wire:model="birthplace" placeholder="e.g., Agoo, La Union"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">Religion</label>
            <input type="text" wire:model="religion" placeholder="e.g., Roman Catholic"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">Nationality</label>
            <input type="text" wire:model="nationality" placeholder="e.g., Filipino"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
    </div>

    {{-- Read-only data from registration --}}
    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:10px 14px;"
         class="dark:bg-gray-800 dark:border-gray-700">
        <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;
                  color:#9ca3af;margin:0 0 6px;">From registration</p>
        <div style="display:flex;flex-wrap:wrap;gap:16px;font-size:.82rem;">
            <span class="dark:text-gray-300"><span style="color:#9ca3af;">Name:</span> <strong>{{ $patient->full_name }}</strong></span>
            <span class="dark:text-gray-300"><span style="color:#9ca3af;">Age/Sex:</span> <strong>{{ $patient->age_display }} / {{ $patient->sex }}</strong></span>
            @if($patient->birthday)
            <span class="dark:text-gray-300"><span style="color:#9ca3af;">Birthday:</span> <strong>{{ $patient->birthday->format('M j, Y') }}</strong></span>
            @endif
            @if($patient->civil_status)
            <span class="dark:text-gray-300"><span style="color:#9ca3af;">Civil Status:</span> <strong>{{ $patient->civil_status }}</strong></span>
            @endif
            @if($patient->contact_number)
            <span class="dark:text-gray-300"><span style="color:#9ca3af;">Contact:</span> <strong>{{ $patient->contact_number }}</strong></span>
            @endif
            @if($patient->address)
            <span class="dark:text-gray-300" style="flex:1;min-width:150px;"><span style="color:#9ca3af;">Address:</span> <strong>{{ $patient->address }}</strong></span>
            @endif
        </div>
    </div>
</div>

{{-- ══ SECTION 3: EMPLOYER ════════════════════════════════════════════════════ --}}
<div class="ca-section dark:bg-gray-900 dark:border-gray-700">
    <div class="ca-sec-head dark:border-gray-700">
        <span class="ca-num dark:bg-white dark:text-gray-900">3</span>
        <span class="ca-sec-title dark:text-white">Employer Information</span>
        <span class="ca-opt-tag">Optional</span>
    </div>
    <div class="ca-grid-3">
        <div>
            <label class="ca-label dark:text-gray-300">Full Employer Name</label>
            <input type="text" wire:model="employerName" placeholder="e.g., La Union Provincial Government"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">Employer Phone</label>
            <input type="text" wire:model="employerPhone" placeholder="(072) 888-1234"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">Employer Address</label>
            <input type="text" wire:model="employerAddress" placeholder="City, Province"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
    </div>
</div>

{{-- ══ SECTION 4: FAMILY CONTACTS ════════════════════════════════════════════ --}}
<div class="ca-section dark:bg-gray-900 dark:border-gray-700">
    <div class="ca-sec-head dark:border-gray-700">
        <span class="ca-num dark:bg-white dark:text-gray-900">4</span>
        <span class="ca-sec-title dark:text-white">Family Contacts</span>
        <span class="ca-opt-tag">Optional</span>
    </div>

    <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;
              color:#6b7280;margin:0 0 8px;">Father</p>
    <div class="ca-grid-3" style="margin-bottom:16px;">
        <div>
            <label class="ca-label dark:text-gray-300">Full Name</label>
            <input type="text" wire:model="fatherFullName" placeholder="Juan Santos Dela Cruz"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">Phone</label>
            <input type="text" wire:model="fatherPhone" placeholder="09XX-XXX-XXXX"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">Address</label>
            <input type="text" wire:model="fatherAddress" placeholder="Brgy., City, Province"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
    </div>

    <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;
              color:#6b7280;margin:0 0 8px;">Mother</p>
    <div class="ca-grid-3">
        <div>
            <label class="ca-label dark:text-gray-300">
                Maiden Name<span class="ca-sublabel">(family name before marriage)</span>
            </label>
            <input type="text" wire:model="motherMaidenName" placeholder="Maria Garcia Santos"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">Phone</label>
            <input type="text" wire:model="motherPhone" placeholder="09XX-XXX-XXXX"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">Address</label>
            <input type="text" wire:model="motherAddress" placeholder="Brgy., City, Province"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
    </div>
</div>

{{-- ══ SECTION 5: PHILHEALTH & SOCIAL CLASS ═══════════════════════════════════ --}}
<div class="ca-section dark:bg-gray-900 dark:border-gray-700">
    <div class="ca-sec-head dark:border-gray-700">
        <span class="ca-num dark:bg-white dark:text-gray-900">5</span>
        <span class="ca-sec-title dark:text-white">PhilHealth & Social Service Classification</span>
        <span class="ca-opt-tag">Optional</span>
    </div>
    <div class="ca-grid-3" style="margin-bottom:14px;">
        <div>
            <label class="ca-label dark:text-gray-300">PhilHealth ID No.</label>
            <input type="text" wire:model="philhealthId" placeholder="XX-XXXXXXXXX-X"
                   style="font-family:monospace;"
                   class="ca-input dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">PhilHealth Member Type</label>
            <select wire:model.live="philhealthType"
                     class="ca-input ca-select dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                <option value="">— Select type —</option>
                <option value="Government">Government (GSIS / AFP / PNP)</option>
                <option value="Indigent">Indigent / Sponsored</option>
                <option value="Private">Private (Employed)</option>
                <option value="Self-Employed">Self-Employed / Informal Sector</option>
            </select>
        </div>
        <div>
            <label class="ca-label dark:text-gray-300">
                Social Service Class<span class="ca-sublabel">(A=highest, D=indigent)</span>
            </label>
            <select wire:model.live="socialServiceClass"
                    class="ca-input ca-select dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                <option value="">— Select class —</option>
                <option value="A">Class A — High income</option>
                <option value="B">Class B — Middle income</option>
                <option value="C1">Class C1 — Lower-middle, PhilHealth Private</option>
                <option value="C2">Class C2 — Lower-middle, PhilHealth Govt</option>
                <option value="C3">Class C3 — Marginalized, PhilHealth Indigent</option>
                <option value="D">Class D — Indigent / 4Ps / Malasakit</option>
            </select>
        </div>
    </div>

    {{-- Auto-suggestion based on class + PhilHealth type --}}
    @if($socialServiceClass && $philhealthType)
    @php
        $suggestCharity = in_array($socialServiceClass, ['C2','C3','D'])
            || in_array($philhealthType, ['Indigent','Government']);
        $suggestion = $suggestCharity ? 'Charity' : 'Private';
        $sColor     = $suggestCharity ? '#166534' : '#5b21b6';
        $sBg        = $suggestCharity ? '#f0fdf4' : '#faf5ff';
        $sBorder    = $suggestCharity ? '#bbf7d0' : '#c4b5fd';
    @endphp
    <div style="background:{{ $sBg }};border:1px solid {{ $sBorder }};border-radius:6px;padding:10px 14px;">
        <p style="font-size:.80rem;font-weight:600;color:{{ $sColor }};margin:0;">
            💡 Suggested payment based on Class {{ $socialServiceClass }} / {{ $philhealthType }}:
            <strong>{{ $suggestion }}</strong>
            @if($paymentClass !== $suggestion)
            <span style="color:#d97706;"> — Section 1 is currently set to {{ $paymentClass }}</span>
            @endif
        </p>
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     CONSENT TO CARE SECTION
     ─────────────────────────────────────────────────────────────────────────
     Placed AFTER all data-entry sections and BEFORE the Submit button,
     so the clerk fills the form first, then generates + prints the consent,
     then clicks "Complete Admission".
     ══════════════════════════════════════════════════════════════════════════ --}}
<div style="background:#fffbeb;border:1.5px solid #fcd34d;border-radius:10px;
            padding:16px 20px;margin-bottom:18px;">

    <div style="display:flex;align-items:flex-start;justify-content:space-between;
                gap:20px;flex-wrap:wrap;">

        {{-- Left: instructions --}}
        <div style="flex:1;min-width:260px;">
            <p style="font-size:.9rem;font-weight:700;color:#78350f;margin:0 0 5px;">
                📄 Consent to Care &nbsp;<span style="font-size:.7rem;font-weight:600;
                    background:#fde68a;color:#92400e;padding:1px 7px;border-radius:3px;">
                    NUR-002-1
                </span>
            </p>
            <p style="font-size:.8rem;color:#92400e;margin:0;line-height:1.55;">
                Open the pre-filled Consent to Care form in a new tab.
                The patient's name, doctor's name, and today's date are filled automatically.
                The clerk can type witness names, guardian name, and relation before printing.
            </p>
            <p style="font-size:.75rem;color:#b45309;margin-top:6px;font-style:italic;">
                ⚠️ Ensure the patient or their authorized representative signs before
                clicking "Complete Admission" below.
            </p>
        </div>

        {{-- Right: the button --}}
        {{--
            Opens /forms/consent-to-care/{visit} in a new browser tab.
            Uses the named route 'forms.consent-to-care'.
            target="_blank" + rel="noopener" is the correct way to open in new tab from an <a>.
            We use an <a> tag (not a Livewire button) because we want a real browser tab,
            not a Livewire action — no data needs to be sent to the server for this action.
        --}}
        <a href="{{ route('forms.consent-to-care', ['visit' => $visit->id]) }}"
           target="_blank"
           rel="noopener noreferrer"
           style="display:inline-flex;align-items:center;gap:8px;flex-shrink:0;
                  background:#b45309;color:#fff;
                  padding:11px 26px;border-radius:8px;
                  font-size:.9rem;font-weight:700;
                  text-decoration:none;
                  border:none;cursor:pointer;
                  box-shadow:0 1px 4px rgba(0,0,0,.18);
                  white-space:nowrap;"
           onmouseover="this.style.background='#92400e'"
           onmouseout="this.style.background='#b45309'">
            📋&nbsp; Generate Consent to Care
        </a>

    </div>
</div>

{{-- ── Submit + Back ───────────────────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;gap:12px;padding:14px 0 28px;">

    {{--
        "Complete Admission" — Livewire save() method.
        Sets clerk_admitted_at, removes the visit from the pending list.
    --}}
    <button wire:click="save"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-60"
            type="button"
            style="background:#059669;color:#fff;border:none;padding:12px 36px;border-radius:8px;
                   font-size:.9rem;font-weight:700;cursor:pointer;"
            onmouseover="this.style.background='#047857'"
            onmouseout="this.style.background='#059669'">
        <span wire:loading.remove wire:target="save">✅ Complete Admission</span>
        <span wire:loading wire:target="save">Processing…</span>
    </button>

    <a href="{{ \App\Filament\Clerk\Pages\PendingAdmissions::getUrl() }}"
       style="padding:12px 22px;border-radius:8px;font-size:.85rem;font-weight:600;
              text-decoration:none;border:1px solid #e5e7eb;color:#374151;"
       class="dark:border-gray-600 dark:text-gray-300">
        ← Back to Pending List
    </a>

</div>

@else
{{-- Fallback if visit or patient failed to load --}}
<div style="text-align:center;padding:60px 20px;">
    <p style="color:#9ca3af;">Visit not found or not pending admission.</p>
    <a href="{{ \App\Filament\Clerk\Pages\PendingAdmissions::getUrl() }}"
       style="color:#1d4ed8;font-size:.875rem;">← Back to Pending List</a>
</div>
@endif

</x-filament-panels::page>