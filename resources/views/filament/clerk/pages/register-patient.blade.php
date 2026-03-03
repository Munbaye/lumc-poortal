<x-filament-panels::page>

{{-- ══ CREDENTIALS MODAL ══ --}}
@if($showCredentialsModal)
<div style="position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);
            display:flex;align-items:center;justify-content:center;padding:24px;">
    <div style="background:#fff;border-radius:12px;width:100%;max-width:420px;
                box-shadow:0 20px 48px rgba(0,0,0,.18);overflow:hidden;">

        {{-- Modal Header --}}
        <div style="background:#f0fdf4;border-bottom:1px solid #bbf7d0;padding:20px 24px;
                    display:flex;align-items:center;gap:12px;">
            <div style="width:38px;height:38px;background:#16a34a;border-radius:8px;
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-size:1.1rem;flex-shrink:0;">✓</div>
            <div>
                <p style="font-size:.95rem;font-weight:700;color:#15803d;margin:0;">
                    Patient Account Created
                </p>
                <p style="font-size:.75rem;color:#6b7280;margin:0;">
                    Provide these login details to the patient now
                </p>
            </div>
        </div>

        {{-- Modal Body --}}
        <div style="padding:24px;">

            <div style="margin-bottom:12px;">
                <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;
                          letter-spacing:.08em;color:#6b7280;margin:0 0 5px;">
                    Username
                </p>
                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;
                            padding:11px 14px;font-family:monospace;font-size:1rem;
                            font-weight:700;color:#111827;user-select:all;letter-spacing:.02em;">
                    {{ $credUsername }}
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;
                          letter-spacing:.08em;color:#6b7280;margin:0 0 5px;">
                    Initial Password
                </p>
                <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:8px;
                            padding:11px 14px;font-family:monospace;font-size:1rem;
                            font-weight:700;color:#dc2626;user-select:all;letter-spacing:.02em;">
                    {{ $credPassword }}
                </div>
            </div>

            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;
                        padding:10px 14px;margin-bottom:20px;">
                <p style="font-size:.78rem;color:#92400e;font-weight:600;margin:0;">
                    ⚠️ Patient must change this password on first login. Note it down before continuing.
                </p>
            </div>

            <button wire:click="dismissCredentialsModal"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60"
                    style="width:100%;background:#1e3a5f;color:#fff;border:none;
                           padding:11px 20px;border-radius:8px;font-size:.875rem;
                           font-weight:600;cursor:pointer;letter-spacing:.01em;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'">
                <span wire:loading.remove wire:target="dismissCredentialsModal">
                    I've noted the credentials — Continue
                </span>
                <span wire:loading wire:target="dismissCredentialsModal">
                    Redirecting…
                </span>
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══ LUMC HEADER ══ --}}
<div style="background:linear-gradient(135deg,#1e3a5f 0%,#1d4ed8 100%);border:1px solid #1e40af;
            border-radius:12px;margin-bottom:24px;overflow:hidden;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 24px;">
        <div style="flex-shrink:0;">
            @if(file_exists(public_path('images/la-union-seal.png')))
                <img src="{{ asset('images/la-union-seal.png') }}" alt="La Union Seal"
                     style="height:64px;width:64px;object-fit:contain;">
            @else
                <div style="height:64px;width:64px;border-radius:50%;
                            background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.4);
                            display:flex;align-items:center;justify-content:center;font-size:1.8rem;">🏛️</div>
            @endif
        </div>
        <div style="text-align:center;flex:1;margin:0 16px;">
            <p style="color:#93c5fd;font-size:11px;letter-spacing:.1em;
                      text-transform:uppercase;margin:0 0 2px;">
                Republic of the Philippines | Province of La Union
            </p>
            <h1 style="color:#fff;font-size:1.4rem;font-weight:700;margin:0 0 6px;
                       text-shadow:0 1px 3px rgba(0,0,0,.4);">
                LA UNION MEDICAL CENTER
            </h1>
            <div style="display:inline-flex;align-items:center;gap:6px;padding:3px 14px;
                        border-radius:9999px;background:rgba(255,255,255,.18);
                        color:#e0f2fe;font-size:13px;font-weight:600;">
                @if($isUnknownMode)
                    🚨 Unknown Patient Registration
                @elseif(($formData['registration_type'] ?? 'OPD') === 'ER')
                    🚑 Emergency Room Registration
                @else
                    📋 Out-Patient Registration
                @endif
            </div>
        </div>
        <div style="flex-shrink:0;">
            @if(file_exists(public_path('images/ph-flag.png')))
                <img src="{{ asset('images/ph-flag.png') }}" alt="Philippine Flag"
                     style="height:64px;width:64px;object-fit:contain;">
            @else
                <div style="height:64px;width:64px;border-radius:50%;
                            background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.4);
                            display:flex;align-items:center;justify-content:center;font-size:1.8rem;">🇵🇭</div>
            @endif
        </div>
    </div>
</div>

{{-- shared select style helper (applied via appearance:auto to restore native arrow) --}}
<style>
    .lumc-select {
    -webkit-appearance: menulist !important;
    -moz-appearance: menulist !important;
    appearance: menulist !important;
    background-image: none !important;
    }
    .lumc-btn-primary {
        display:inline-flex;align-items:center;gap:8px;
        background:linear-gradient(135deg,#1e3a5f,#1d4ed8);
        color:#fff;border:none;
        padding:11px 28px;border-radius:9px;font-size:.875rem;
        font-weight:700;cursor:pointer;letter-spacing:.02em;
        box-shadow:0 2px 8px rgba(29,78,216,.25);
        transition:all .18s;
    }
    .lumc-btn-primary:hover {
        background:linear-gradient(135deg,#1e40af,#2563eb);
        box-shadow:0 4px 16px rgba(29,78,216,.35);
        transform:translateY(-1px);
    }
    .lumc-btn-primary:active { transform:translateY(0); }

    .lumc-btn-danger {
        display:inline-flex;align-items:center;gap:8px;
        background:linear-gradient(135deg,#b91c1c,#dc2626);
        color:#fff;border:none;
        padding:11px 28px;border-radius:9px;font-size:.875rem;
        font-weight:700;cursor:pointer;letter-spacing:.02em;
        box-shadow:0 2px 8px rgba(220,38,38,.25);
        transition:all .18s;
    }
    .lumc-btn-danger:hover {
        background:linear-gradient(135deg,#991b1b,#b91c1c);
        box-shadow:0 4px 16px rgba(220,38,38,.35);
        transform:translateY(-1px);
    }
    .lumc-btn-danger:active { transform:translateY(0); }

    .lumc-btn-secondary {
        display:inline-flex;align-items:center;gap:8px;
        background:#fff;color:#4b5563;
        border:1.5px solid #d1d5db;
        padding:11px 24px;border-radius:9px;font-size:.875rem;
        font-weight:600;cursor:pointer;letter-spacing:.01em;
        transition:all .18s;
    }
    .lumc-btn-secondary:hover {
        background:#f3f4f6;border-color:#9ca3af;color:#111827;
        transform:translateY(-1px);
    }
    .lumc-btn-secondary:active { transform:translateY(0); }
    .lumc-input {
        width:100%;border-radius:8px;padding:10px 12px;font-size:.875rem;
        border:1px solid #d1d5db;background:#fff;color:#111827;
        outline:none;box-sizing:border-box;
    }
    .lumc-input:focus { border-color:#1d4ed8;box-shadow:0 0 0 3px rgba(29,78,216,.1); }
    .lumc-input-error { border:2px solid #ef4444 !important;background:#fef2f2 !important; }
    .lumc-label { display:block;font-size:.78rem;font-weight:700;margin-bottom:5px;color:#374151; }
    .lumc-label-error { color:#dc2626; }
    .lumc-error { color:#ef4444;font-size:.73rem;margin-top:4px;font-weight:600; }
    .lumc-section {
        background:#fff;border:1px solid #e5e7eb;border-radius:12px;
        padding:24px;margin-bottom:16px;
    }
</style>

{{-- ══ UNKNOWN MODE ══ --}}
@if($isUnknownMode)
<div style="background:#fff;border:2px solid #dc2626;border-radius:12px;
            padding:24px;margin-bottom:20px;">

    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;">
        <div>
            <h2 style="font-size:1rem;font-weight:700;color:#dc2626;margin:0 0 4px;">
                🚨 Unknown Patient Registration
            </h2>
            <p style="font-size:.82rem;color:#6b7280;margin:0;">
                Registered as ER. Identity can be updated later by the clerk or admin.
            </p>
        </div>
        <button wire:click="cancelUnknownMode" class="lumc-btn-secondary">
            ← Cancel
        </button>
    </div>

    {{-- Chief Complaint --}}
    <div style="margin-bottom:16px;">
        <label class="lumc-label {{ $errors->has('unknownFormData.chief_complaint') ? 'lumc-label-error' : '' }}">
            Chief Complaint / Reason for ER Visit *
        </label>
        <textarea wire:model="unknownFormData.chief_complaint" rows="2"
            placeholder="e.g., Unresponsive, found unconscious, trauma victim…"
            class="lumc-input {{ $errors->has('unknownFormData.chief_complaint') ? 'lumc-input-error' : '' }}"
            style="resize:vertical;"></textarea>
        @error('unknownFormData.chief_complaint')
            <p class="lumc-error">⚠️ {{ $message }}</p>
        @enderror
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

        <div>
            <label class="lumc-label">Apparent Sex</label>
            <select wire:model="unknownFormData.sex"
                    class="lumc-input lumc-select">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div>
            <label class="lumc-label {{ $errors->has('unknownFormData.brought_by') ? 'lumc-label-error' : '' }}">
                Brought By *
            </label>
            {{-- Values must match the DB ENUM: Self, Family, Ambulance, Police, Other --}}
            <select wire:model="unknownFormData.brought_by"
                    class="lumc-input lumc-select {{ $errors->has('unknownFormData.brought_by') ? 'lumc-input-error' : '' }}">
                <option value="" disabled>Select…</option>
                <option value="Self">Self</option>
                <option value="Family">Family</option>
                <option value="Ambulance">Ambulance</option>
                <option value="Police">Police</option>
                <option value="Other">Other</option>
            </select>
            @error('unknownFormData.brought_by')
                <p class="lumc-error">⚠️ {{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Age Range --}}
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;
                padding:16px;margin-bottom:16px;">
        <label style="display:block;font-size:.78rem;font-weight:700;
                      margin-bottom:10px;color:#dc2626;">
            Estimated Age Range
        </label>
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="flex:1;">
                <label style="display:block;font-size:.73rem;font-weight:600;
                              margin-bottom:4px;color:#6b7280;">Min Age</label>
                <input type="number" wire:model.live="unknownFormData.age_range_min"
                       min="0" max="120" placeholder="e.g., 20"
                       class="lumc-input">
            </div>
            <div style="padding-top:20px;font-weight:700;color:#9ca3af;">—</div>
            <div style="flex:1;">
                <label style="display:block;font-size:.73rem;font-weight:600;
                              margin-bottom:4px;color:#6b7280;">Max Age</label>
                <input type="number" wire:model.live="unknownFormData.age_range_max"
                       min="0" max="120" placeholder="e.g., 30"
                       class="lumc-input">
            </div>
            <div style="flex:2;padding-top:20px;">
                @if($unknownFormData['age_range_min'] && $unknownFormData['age_range_max'])
                <div style="background:#fff;border:1px solid #fecaca;border-radius:8px;
                            padding:8px 12px;text-align:center;font-size:.82rem;
                            font-weight:700;color:#dc2626;">
                    ≈ {{ round(($unknownFormData['age_range_min'] + $unknownFormData['age_range_max']) / 2) }} y/o
                </div>
                @endif
            </div>
        </div>
        <p style="font-size:.7rem;color:#9ca3af;margin-top:6px;">
            Best estimate. Leave blank if completely unknown.
        </p>
    </div>

    {{-- Condition on Arrival --}}
    <div style="margin-bottom:24px;">
        <label class="lumc-label">Condition on Arrival</label>
        {{-- Values must match the DB ENUM: Good, Fair, Poor, Shock, Comatose, Hemorrhagic, DOA --}}
        <select wire:model="unknownFormData.condition_on_arrival"
                class="lumc-input lumc-select">
            <option value="" disabled>Select…</option>
            <option value="Good">Good</option>
            <option value="Fair">Fair</option>
            <option value="Poor">Poor</option>
            <option value="Shock">Shock</option>
            <option value="Comatose">Comatose</option>
            <option value="Hemorrhagic">Hemorrhagic</option>
            <option value="DOA">DOA</option>
        </select>
    </div>

    <button wire:click="saveUnknown"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-60"
            class="lumc-btn-danger">
        <span wire:loading.remove wire:target="saveUnknown">🚨 Register Unknown Patient</span>
        <span wire:loading wire:target="saveUnknown">⏳ Registering…</span>
    </button>
</div>

@else

{{-- ══ STEP 1: SEARCH ══ --}}
<div class="lumc-section">

    <div style="display:flex;align-items:flex-start;justify-content:space-between;
                margin-bottom:16px;">
        <div>
            <h2 style="font-size:1rem;font-weight:700;margin:0 0 4px;color:#111827;">
                🔍 Step 1: Search for Existing Patient
            </h2>
            <p style="font-size:.83rem;color:#6b7280;margin:0;">
                Always search first to prevent duplicate records. Type at least 2 characters.
            </p>
        </div>
        <button wire:click="activateUnknownMode" class="lumc-btn-danger"
                style="flex-shrink:0;margin-left:16px;white-space:nowrap;">
            🚨 Unknown Patient
        </button>
    </div>

    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:16px;">
        <div>
            <label class="lumc-label">Family Name *</label>
            <input type="text" wire:model.live.debounce.400ms="searchFamilyName"
                   placeholder="e.g., Dela Cruz" autofocus
                   class="lumc-input">
        </div>
        <div>
            <label class="lumc-label">First Name</label>
            <input type="text" wire:model.live.debounce.400ms="searchFirstName"
                   placeholder="e.g., Juan"
                   class="lumc-input">
        </div>
        <div>
            <label class="lumc-label">Sex</label>
            <select wire:model.live="searchSex" class="lumc-input lumc-select">
                <option value="" disabled>Any</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div>
            <label class="lumc-label">
                Birthday
                <span style="font-size:.65rem;color:#9ca3af;font-weight:400;">(±1 yr)</span>
            </label>
            <input type="date" wire:model.live="searchBirthday" class="lumc-input">
        </div>
        <div>
            <label class="lumc-label">
                Age
                <span style="font-size:.65rem;color:#9ca3af;font-weight:400;">(±2 yrs)</span>
            </label>
            <input type="number" wire:model.live.debounce.400ms="searchAge"
                   placeholder="e.g., 25" min="0" max="120"
                   class="lumc-input">
        </div>
    </div>

    @if($hasSearched)
        @if(count($searchResults) > 0)
            <div style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
                    <thead style="background:#1e3a5f;">
                        <tr>
                            @foreach(['Case No','Full Name','Age / Sex','Birthday','Last Visit','Address','Status',''] as $h)
                            <th style="padding:10px 12px;text-align:left;color:#bfdbfe;
                                       font-size:.72rem;font-weight:600;white-space:nowrap;">
                                {{ $h }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($searchResults as $r)
                        <tr style="border-top:1px solid #f3f4f6;
                                   background:{{ $selectedPatientId == $r['id']
                                        ? '#f0fdf4'
                                        : ($r['has_incomplete'] ? '#fef2f2' : '#fff') }};">
                            <td style="padding:9px 12px;font-family:monospace;
                                       font-size:.75rem;color:#1d4ed8;">
                                {{ $r['case_no'] }}
                            </td>
                            <td style="padding:9px 12px;font-weight:600;
                                       color:{{ $r['has_incomplete'] ? '#dc2626' : '#111827' }};">
                                {{ $r['full_name'] }}
                                @if($r['has_incomplete'])
                                    <span style="display:inline-block;background:#fef2f2;
                                                 border:1px solid #fca5a5;color:#dc2626;
                                                 font-size:.6rem;font-weight:700;
                                                 padding:1px 5px;border-radius:4px;margin-left:4px;">
                                        INCOMPLETE
                                    </span>
                                @endif
                            </td>
                            <td style="padding:9px 12px;color:#374151;">
                                {{ $r['age_display'] }} / {{ $r['sex'] }}
                            </td>
                            <td style="padding:9px 12px;color:#6b7280;font-size:.78rem;">
                                {{ $r['birthday'] ?? '—' }}
                            </td>
                            <td style="padding:9px 12px;color:#6b7280;font-size:.78rem;">
                                {{ $r['last_visit'] ?? 'No visit' }}
                            </td>
                            <td style="padding:9px 12px;color:#6b7280;font-size:.78rem;">
                                {{ $r['address'] }}…
                            </td>
                            <td style="padding:9px 12px;">
                                @if($r['has_incomplete'])
                                    <span style="font-size:.68rem;color:#dc2626;font-weight:600;">
                                        ⚠️ Incomplete
                                    </span>
                                @else
                                    <span style="font-size:.68rem;color:#16a34a;font-weight:600;">
                                        ✓ Complete
                                    </span>
                                @endif
                            </td>
                            <td style="padding:9px 12px;">
                                <button wire:click="selectPatient({{ $r['id'] }})"
                                        style="background:#16a34a;color:#fff;border:none;
                                               padding:6px 12px;border-radius:6px;
                                               font-size:.75rem;font-weight:600;
                                               cursor:pointer;white-space:nowrap;"
                                        onmouseover="this.style.opacity='.85'"
                                        onmouseout="this.style.opacity='1'">
                                    ✓ Select
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p style="font-size:.75rem;color:#6b7280;margin-top:8px;">
                {{ count($searchResults) }} result(s) found — includes fuzzy/typo matches.
            </p>
        @else
            <div style="background:#fefce8;border:1px solid #fde047;
                        border-radius:10px;padding:16px;">
                <p style="font-weight:700;color:#854d0e;margin:0 0 4px;">
                    ⚠️ No similar patients found for "<strong>{{ $searchFamilyName }}</strong>"
                </p>
                <p style="font-size:.83rem;color:#92400e;margin:0 0 12px;">
                    Double-check spelling. If certain this is a new patient, tick the box below.
                </p>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <input type="checkbox" id="confirmNoMatch"
                           wire:model.live="confirmNoMatch"
                           style="width:16px;height:16px;cursor:pointer;accent-color:#1d4ed8;">
                    <label for="confirmNoMatch"
                           style="font-size:.83rem;font-weight:600;cursor:pointer;color:#111827;">
                        I confirm no existing patient matches — create new record
                    </label>
                </div>
                @if($confirmNoMatch)
                    <button wire:click="showNewPatientForm" class="lumc-btn-primary">
                        + Create New Patient Record
                    </button>
                @endif
            </div>
        @endif
    @else
        <div style="text-align:center;padding:40px 0;color:#9ca3af;">
            <p style="font-size:2rem;margin:0 0 8px;">🔍</p>
            <p style="font-size:.875rem;margin:0;">
                Type at least 2 characters in Family Name to begin searching…
            </p>
        </div>
    @endif
</div>

{{-- ══ STEP 2: REGISTRATION FORM ══ --}}
@if($showCreateForm)
<div class="lumc-section">

    <h2 style="font-size:1rem;font-weight:700;margin:0 0 20px;color:#111827;">
        @if($selectedPatientId)
            ✏️ Step 2: Update Existing Patient &amp; Register Visit
        @else
            📋 Step 2: New Patient Registration
        @endif
    </h2>

    {{-- Entry Point --}}
    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;
                padding:14px;margin-bottom:20px;">
        <label class="lumc-label">Entry Point *</label>
        <div style="display:flex;gap:24px;margin-top:4px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="radio" wire:model.live="formData.registration_type" value="OPD"
                       style="accent-color:#1d4ed8;width:16px;height:16px;">
                <span style="font-weight:600;color:#1d4ed8;">📋 OPD (Out-Patient)</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="radio" wire:model.live="formData.registration_type" value="ER"
                       style="accent-color:#dc2626;width:16px;height:16px;">
                <span style="font-weight:600;color:#dc2626;">🚑 ER (Emergency Room)</span>
            </label>
        </div>
    </div>

    {{-- Required --}}
    <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;
              letter-spacing:.08em;color:#9ca3af;margin:0 0 12px;">
        Required Information
    </p>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;">

        <div>
            <label class="lumc-label {{ $errors->has('formData.family_name') ? 'lumc-label-error' : '' }}">
                Family Name *
            </label>
            <input type="text" wire:model="formData.family_name"
                   class="lumc-input {{ $errors->has('formData.family_name') ? 'lumc-input-error' : '' }}">
            @error('formData.family_name')
                <p class="lumc-error">⚠️ {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="lumc-label {{ $errors->has('formData.first_name') ? 'lumc-label-error' : '' }}">
                First Name *
            </label>
            <input type="text" wire:model="formData.first_name"
                   class="lumc-input {{ $errors->has('formData.first_name') ? 'lumc-input-error' : '' }}">
            @error('formData.first_name')
                <p class="lumc-error">⚠️ {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="lumc-label">Middle Name</label>
            <input type="text" wire:model="formData.middle_name"
                   placeholder="Optional" class="lumc-input">
        </div>

        <div>
            <label class="lumc-label {{ $errors->has('formData.sex') ? 'lumc-label-error' : '' }}">
                Sex *
            </label>
            <select wire:model="formData.sex"
                    class="lumc-input lumc-select {{ $errors->has('formData.sex') ? 'lumc-input-error' : '' }}">
                <option value="" disabled>Select sex…</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            @error('formData.sex')
                <p class="lumc-error">⚠️ {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="lumc-label">
                Birthday
                <span style="font-size:.65rem;color:#9ca3af;font-weight:400;"> — preferred</span>
            </label>
            <input type="date" wire:model.live="formData.birthday" class="lumc-input">
            @if($formData['birthday'])
                <p style="font-size:.7rem;color:#059669;margin-top:3px;">
                    ✓ Age calculated automatically
                </p>
            @endif
        </div>

        <div>
            <label class="lumc-label">
                Age
                <span style="font-size:.65rem;color:#9ca3af;font-weight:400;"> — if no birthday</span>
            </label>
            <input type="number" wire:model="formData.age"
                   placeholder="e.g., 25" min="0" max="120"
                   {{ $formData['birthday'] ? 'disabled' : '' }}
                   class="lumc-input"
                   style="{{ $formData['birthday'] ? 'background:#f3f4f6;color:#9ca3af;cursor:not-allowed;' : '' }}">
            @if($formData['birthday'])
                <p style="font-size:.7rem;color:#9ca3af;margin-top:3px;">
                    Disabled — birthday takes priority
                </p>
            @endif
        </div>

        <div>
            <label class="lumc-label">Contact Number</label>
            <input type="text" wire:model="formData.contact_number"
                   placeholder="09XX-XXX-XXXX" class="lumc-input">
        </div>

    </div>

    {{-- Address --}}
    <div style="margin-bottom:20px;">
        <label class="lumc-label {{ $errors->has('formData.address') ? 'lumc-label-error' : '' }}">
            Complete Address *
        </label>
        <textarea wire:model="formData.address" rows="2"
                  placeholder="e.g., Brgy. Poblacion, Agoo, La Union"
                  class="lumc-input {{ $errors->has('formData.address') ? 'lumc-input-error' : '' }}"
                  style="resize:vertical;"></textarea>
        @error('formData.address')
            <p class="lumc-error">⚠️ {{ $message }}</p>
        @enderror
    </div>

    {{-- Optional --}}
    <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;
              letter-spacing:.08em;color:#9ca3af;margin:0 0 12px;">
        Optional Information
    </p>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;">
        <div>
            <label class="lumc-label">Civil Status</label>
            <select wire:model.live="formData.civil_status"
                    class="lumc-input lumc-select">
                <option value="" disabled>Select…</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
                <option value="Annulled">Annulled</option>
            </select>
        </div>
        @if(($formData['civil_status'] ?? '') === 'Married')
        <div>
            <label class="lumc-label">Spouse Name</label>
            <input type="text" wire:model="formData.spouse_name" class="lumc-input">
        </div>
        @endif
        <div>
            <label class="lumc-label">Occupation</label>
            <input type="text" wire:model="formData.occupation" class="lumc-input">
        </div>
        <div>
            <label class="lumc-label">Father's Name</label>
            <input type="text" wire:model="formData.father_name" class="lumc-input">
        </div>
        <div>
            <label class="lumc-label">Mother's Name</label>
            <input type="text" wire:model="formData.mother_name" class="lumc-input">
        </div>
    </div>

    {{-- ER Details --}}
    @if(($formData['registration_type'] ?? 'OPD') === 'ER')
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;
                padding:16px;margin-bottom:20px;">
        <h3 style="font-size:.875rem;font-weight:700;color:#dc2626;margin:0 0 14px;">
            🚑 Emergency Room Details
        </h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <label class="lumc-label">Brought By</label>
                {{-- Values must match the DB ENUM: Self, Family, Ambulance, Police, Other --}}
                <select wire:model="formData.brought_by"
                        class="lumc-input lumc-select">
                    <option value="" disabled>Select…</option>
                    <option value="Self">Self</option>
                    <option value="Family">Family</option>
                    <option value="Ambulance">Ambulance</option>
                    <option value="Police">Police</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div>
                <label class="lumc-label">Condition on Arrival</label>
                {{-- Values must match the DB ENUM: Good, Fair, Poor, Shock, Comatose, Hemorrhagic, DOA --}}
                <select wire:model="formData.condition_on_arrival"
                        class="lumc-input lumc-select">
                    <option value="" disabled>Select…</option>
                    <option value="Good">Good</option>
                    <option value="Fair">Fair</option>
                    <option value="Poor">Poor</option>
                    <option value="Shock">Shock</option>
                    <option value="Comatose">Comatose</option>
                    <option value="Hemorrhagic">Hemorrhagic</option>
                    <option value="DOA">DOA</option>
                </select>
            </div>
        </div>
    </div>
    @endif

    {{-- Chief Complaint --}}
    <div style="margin-bottom:20px;">
        <label class="lumc-label {{ $errors->has('formData.chief_complaint') ? 'lumc-label-error' : '' }}">
            Chief Complaint *
        </label>
        <textarea wire:model="formData.chief_complaint" rows="2"
                  placeholder="e.g., Fever for 3 days, cough, difficulty breathing"
                  class="lumc-input {{ $errors->has('formData.chief_complaint') ? 'lumc-input-error' : '' }}"
                  style="resize:vertical;"></textarea>
        @error('formData.chief_complaint')
            <p class="lumc-error">⚠️ {{ $message }}</p>
        @enderror
    </div>

    {{-- Incomplete Info Flag --}}
    <div style="background:#fef2f2;border:2px solid #fca5a5;border-radius:10px;
                padding:16px;margin-bottom:24px;">
        <div style="display:flex;align-items:flex-start;gap:10px;">
            <input type="checkbox" id="hasIncompleteInfo"
                   wire:model.live="formData.has_incomplete_info"
                   style="width:16px;height:16px;margin-top:2px;
                          cursor:pointer;accent-color:#dc2626;flex-shrink:0;">
            <div>
                <label for="hasIncompleteInfo"
                       style="font-size:.85rem;font-weight:700;cursor:pointer;
                              color:#dc2626;display:block;margin-bottom:3px;">
                    ⚠️ Patient has missing or incomplete information
                </label>
                <p style="font-size:.78rem;color:#9ca3af;margin:0;">
                    When checked, this patient's name shows in
                    <strong style="color:#dc2626;">red</strong>
                    across all panels until resolved.
                </p>
            </div>
        </div>
        @if($formData['has_incomplete_info'])
        <div style="margin-top:10px;padding:8px 12px;background:#fff;
                    border-radius:6px;border:1px solid #fca5a5;">
            <p style="font-size:.78rem;color:#dc2626;font-weight:600;margin:0;">
                🔴 Patient will be flagged as having incomplete information.
            </p>
        </div>
        @endif
    </div>

    {{-- Submit --}}
    <button wire:click="save"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-60"
            class="lumc-btn-primary">
        <span wire:loading.remove wire:target="save">💾 Save &amp; Proceed to Vitals</span>
        <span wire:loading wire:target="save">⏳ Saving…</span>
    </button>

</div>
@endif

@endif {{-- end isUnknownMode else --}}

<div style="text-align:center;font-size:.72rem;color:#9ca3af;margin-top:24px;padding-bottom:8px;">
    LA UNION: Agkaysa! | Tel: (072) 607-5541 | ER: 0927-728-6330
</div>

</x-filament-panels::page>