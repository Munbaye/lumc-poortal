<x-filament-panels::page>

{{-- LUMC HEADER --}}
<div class="rounded-xl mb-6 overflow-hidden"
     style="background:linear-gradient(135deg,#1e3a5f 0%,#1d4ed8 100%);border:1px solid #1e40af;">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex-shrink-0">
            @if(file_exists(public_path('images/la-union-seal.png')))
                <img src="{{ asset('images/la-union-seal.png') }}" alt="La Union Seal" class="h-16 w-16 object-contain">
            @else
                <div class="h-16 w-16 rounded-full flex items-center justify-center text-3xl"
                     style="background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.4)">🏛️</div>
            @endif
        </div>
        <div class="text-center flex-1 mx-4">
            <p style="color:#93c5fd;font-size:11px;letter-spacing:.1em;text-transform:uppercase;">
                Republic of the Philippines | Province of La Union
            </p>
            <h1 style="color:#fff;font-size:1.5rem;font-weight:700;margin-top:2px;text-shadow:0 1px 3px rgba(0,0,0,.4);">
                LA UNION MEDICAL CENTER
            </h1>
            <div style="display:inline-flex;gap:6px;margin-top:4px;padding:3px 14px;border-radius:9999px;
                        background:rgba(255,255,255,.18);color:#e0f2fe;font-size:13px;font-weight:600;">
                @if(($formData['registration_type'] ?? 'OPD') === 'ER')
                    🚑 Emergency Room Registration
                @else
                    📋 Out-Patient Registration
                @endif
            </div>
        </div>
        <div class="flex-shrink-0">
            @if(file_exists(public_path('images/ph-flag.png')))
                <img src="{{ asset('images/ph-flag.png') }}" alt="Philippine Flag" class="h-16 w-16 object-contain">
            @else
                <div class="h-16 w-16 rounded-full flex items-center justify-center text-3xl"
                     style="background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.4)">🇵🇭</div>
            @endif
        </div>
    </div>
</div>

{{-- STEP 1: SEARCH --}}
<div class="rounded-xl shadow-sm mb-4 dark:border-gray-700"
     style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;">

    <h2 style="font-size:1.05rem;font-weight:700;margin-bottom:4px;" class="text-gray-900 dark:text-white">
        🔍 Step 1: Search for Existing Patient
    </h2>
    <p style="font-size:.83rem;margin-bottom:16px;" class="text-gray-500 dark:text-gray-400">
        ⚠️ Always search first to prevent duplicate records. Type at least 3 characters.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Family Name *</label>
            <input type="text" wire:model.live.debounce.400ms="searchFamilyName"
                placeholder="e.g., Dela Cruz" autofocus
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900 placeholder-gray-400
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">First Name</label>
            <input type="text" wire:model.live.debounce.400ms="searchFirstName"
                placeholder="e.g., Juan"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900 placeholder-gray-400
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Sex</label>
            <select wire:model.live="searchSex"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Any</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Birthday (approx. ±1 yr)</label>
            <input type="date" wire:model.live="searchBirthday"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    @if($hasSearched)
        @if(count($searchResults) > 0)
            <div class="rounded-lg overflow-hidden dark:border-gray-700" style="border:1px solid #e5e7eb;">
                <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
                    <thead style="background:#1e3a5f;">
                        <tr>
                            @foreach(['Case No','Full Name','Age / Sex','Birthday','Last Visit','Address',''] as $h)
                            <th style="padding:10px 12px;text-align:left;color:#bfdbfe;font-size:.73rem;font-weight:600;">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($searchResults as $r)
                        <tr class="{{ $selectedPatientId == $r['id'] ? 'bg-green-50 dark:bg-green-900/30' : 'bg-white dark:bg-gray-900 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}"
                            style="border-top:1px solid #f3f4f6;">
                            <td class="px-3 py-2 font-mono text-xs text-blue-700 dark:text-blue-400">{{ $r['case_no'] }}</td>
                            <td class="px-3 py-2 font-bold text-gray-900 dark:text-white">{{ $r['full_name'] }}</td>
                            <td class="px-3 py-2 text-gray-700 dark:text-gray-300">{{ $r['age_display'] }} / {{ $r['sex'] }}</td>
                            <td class="px-3 py-2 text-gray-600 dark:text-gray-300 text-xs">{{ $r['birthday'] ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-500 dark:text-gray-400 text-xs">{{ $r['last_visit'] ?? 'No visit' }}</td>
                            <td class="px-3 py-2 text-gray-500 dark:text-gray-400 text-xs">{{ $r['address'] }}…</td>
                            <td class="px-3 py-2">
                                <button wire:click="selectPatient({{ $r['id'] }})"
                                    style="background:#16a34a;color:#ffffff;border:none;padding:6px 14px;border-radius:6px;
                                           font-size:.75rem;font-weight:700;cursor:pointer;white-space:nowrap;"
                                    onmouseover="this.style.background='#15803d'"
                                    onmouseout="this.style.background='#16a34a'">
                                    ✓ This is the patient
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                {{ count($searchResults) }} result(s). Click "This is the patient" if you see a match above.
            </p>
        @else
            <div style="background:#fefce8;border:1px solid #fde047;border-radius:10px;padding:16px;"
                 class="dark:bg-yellow-900/20 dark:border-yellow-700">
                <p style="font-weight:700;color:#854d0e;" class="dark:text-yellow-300">
                    ⚠️ No similar patients found for "<strong>{{ $searchFamilyName }}</strong>"
                </p>
                <p style="font-size:.83rem;margin-top:6px;color:#92400e;" class="dark:text-yellow-400">
                    Double-check spelling. If certain this is a new patient, tick the box below.
                </p>
                <div style="display:flex;align-items:center;gap:8px;margin-top:12px;">
                    <input type="checkbox" id="confirmNoMatch" wire:model.live="confirmNoMatch"
                        style="width:16px;height:16px;cursor:pointer;accent-color:#1d4ed8;">
                    <label for="confirmNoMatch"
                        style="font-size:.83rem;font-weight:700;cursor:pointer;"
                        class="text-gray-800 dark:text-gray-200">
                        I confirm no existing patient matches — create new record
                    </label>
                </div>
                @if($confirmNoMatch)
                    <button wire:click="showNewPatientForm"
                        style="margin-top:12px;background:#1d4ed8;color:#ffffff;border:none;
                               padding:10px 22px;border-radius:8px;font-size:.85rem;font-weight:700;cursor:pointer;"
                        onmouseover="this.style.background='#1e40af'"
                        onmouseout="this.style.background='#1d4ed8'">
                        + Create New Patient Record
                    </button>
                @endif
            </div>
        @endif
    @else
        <div class="text-center py-10 text-gray-400 dark:text-gray-600">
            <p class="text-4xl mb-2">🔍</p>
            <p class="text-sm">Type at least 3 characters in Family Name to begin searching…</p>
        </div>
    @endif
</div>

{{-- STEP 2: REGISTRATION FORM --}}
@if($showCreateForm)
<div class="rounded-xl shadow-sm dark:border-gray-700"
     style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;">

    <h2 style="font-size:1.05rem;font-weight:700;margin-bottom:20px;" class="text-gray-900 dark:text-white">
        @if($selectedPatientId) ✏️ Step 2: Update Existing Patient & Register Visit
        @else 📋 Step 2: New Patient Registration
        @endif
    </h2>

    {{-- Registration Type --}}
    <div style="border-radius:8px;padding:12px;margin-bottom:20px;border:1px solid #e5e7eb;"
         class="bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
        <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:8px;" class="text-gray-700 dark:text-gray-200">
            Entry Point *
        </label>
        <div style="display:flex;gap:24px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="radio" wire:model.live="formData.registration_type" value="OPD"
                    style="accent-color:#1d4ed8;width:16px;height:16px;">
                <span style="font-weight:700;" class="text-blue-800 dark:text-blue-300">📋 OPD (Out-Patient)</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="radio" wire:model.live="formData.registration_type" value="ER"
                    style="accent-color:#dc2626;width:16px;height:16px;">
                <span style="font-weight:700;" class="text-red-700 dark:text-red-400">🚑 ER (Emergency Room)</span>
            </label>
        </div>
    </div>

    {{-- Required fields --}}
    <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px;" class="text-gray-500 dark:text-gray-400">Required Information</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;"
                   class="{{ $errors->has('formData.family_name') ? 'text-red-600' : 'text-gray-700 dark:text-gray-200' }}">
                Family Name *
            </label>
            <input type="text" wire:model="formData.family_name"
                class="w-full rounded-lg px-3 py-2 text-sm
                       {{ $errors->has('formData.family_name') ? 'border-2 border-red-500 bg-red-50 dark:bg-red-900/20' : 'border border-gray-300 dark:border-gray-600' }}
                       text-gray-900 bg-white placeholder-gray-400
                       dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('formData.family_name')
                <p style="color:#ef4444;font-size:.73rem;margin-top:3px;font-weight:600;">⚠️ {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;"
                   class="{{ $errors->has('formData.first_name') ? 'text-red-600' : 'text-gray-700 dark:text-gray-200' }}">
                First Name *
            </label>
            <input type="text" wire:model="formData.first_name"
                class="w-full rounded-lg px-3 py-2 text-sm
                       {{ $errors->has('formData.first_name') ? 'border-2 border-red-500 bg-red-50 dark:bg-red-900/20' : 'border border-gray-300 dark:border-gray-600' }}
                       text-gray-900 bg-white placeholder-gray-400
                       dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('formData.first_name')
                <p style="color:#ef4444;font-size:.73rem;margin-top:3px;font-weight:600;">⚠️ {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Middle Name</label>
            <input type="text" wire:model="formData.middle_name" placeholder="Optional"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300
                       text-gray-900 bg-white placeholder-gray-400
                       dark:border-gray-600 dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;"
                   class="{{ $errors->has('formData.sex') ? 'text-red-600' : 'text-gray-700 dark:text-gray-200' }}">
                Sex *
            </label>
            <select wire:model="formData.sex"
                class="w-full rounded-lg px-3 py-2 text-sm
                       {{ $errors->has('formData.sex') ? 'border-2 border-red-500 bg-red-50 dark:bg-red-900/20' : 'border border-gray-300 dark:border-gray-600' }}
                       text-gray-900 bg-white dark:text-white dark:bg-gray-800
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select sex…</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            @error('formData.sex')
                <p style="color:#ef4444;font-size:.73rem;margin-top:3px;font-weight:600;">⚠️ {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Birthday</label>
            <input type="date" wire:model="formData.birthday"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300
                       text-gray-900 bg-white dark:border-gray-600 dark:text-white dark:bg-gray-800
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Contact Number</label>
            <input type="text" wire:model="formData.contact_number" placeholder="09XX-XXX-XXXX"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300
                       text-gray-900 bg-white placeholder-gray-400
                       dark:border-gray-600 dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    {{-- Address --}}
    <div class="mb-5">
        <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;"
               class="{{ $errors->has('formData.address') ? 'text-red-600' : 'text-gray-700 dark:text-gray-200' }}">
            Complete Address (Barangay, City/Municipality, Province) *
        </label>
        <textarea wire:model="formData.address" rows="2"
            placeholder="e.g., Brgy. Poblacion, Agoo, La Union"
            class="w-full rounded-lg px-3 py-2 text-sm
                   {{ $errors->has('formData.address') ? 'border-2 border-red-500 bg-red-50 dark:bg-red-900/20' : 'border border-gray-300 dark:border-gray-600' }}
                   text-gray-900 bg-white placeholder-gray-400
                   dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                   focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        @error('formData.address')
            <p style="color:#ef4444;font-size:.73rem;margin-top:3px;font-weight:600;">⚠️ {{ $message }}</p>
        @enderror
    </div>

    {{-- Optional fields --}}
    <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px;" class="text-gray-500 dark:text-gray-400">Optional Information</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div>
            <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:4px;" class="text-gray-600 dark:text-gray-300">Civil Status</label>
            <select wire:model.live="formData.civil_status"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select…</option>
                <option>Single</option><option>Married</option><option>Widowed</option>
                <option>Separated</option><option>Annulled</option>
            </select>
        </div>
        @if(($formData['civil_status'] ?? '') === 'Married')
        <div>
            <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:4px;" class="text-gray-600 dark:text-gray-300">Spouse Name</label>
            <input type="text" wire:model="formData.spouse_name"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        @endif
        <div>
            <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:4px;" class="text-gray-600 dark:text-gray-300">Occupation</label>
            <input type="text" wire:model="formData.occupation"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:4px;" class="text-gray-600 dark:text-gray-300">Father's Name</label>
            <input type="text" wire:model="formData.father_name"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:4px;" class="text-gray-600 dark:text-gray-300">Mother's Name</label>
            <input type="text" wire:model="formData.mother_name"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    {{-- ER fields --}}
    @if(($formData['registration_type'] ?? 'OPD') === 'ER')
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:16px;margin-bottom:20px;"
         class="dark:bg-red-900/20 dark:border-red-700">
        <h3 style="font-weight:700;margin-bottom:12px;" class="text-red-800 dark:text-red-300">🚑 Emergency Room Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:4px;" class="text-gray-700 dark:text-gray-300">Brought By</label>
                <select wire:model="formData.brought_by"
                    class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-400">
                    <option value="">Select…</option>
                    <option>Self</option><option>Family</option><option>Ambulance</option><option>Police</option><option>Other</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:4px;" class="text-gray-700 dark:text-gray-300">Condition on Arrival</label>
                <select wire:model="formData.condition_on_arrival"
                    class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-400">
                    <option value="">Select…</option>
                    <option>Good</option><option>Fair</option><option>Poor</option>
                    <option>Shock</option><option>Comatose</option><option>Hemorrhagic</option><option>DOA</option>
                </select>
            </div>
        </div>
    </div>
    @endif

    {{-- Chief Complaint --}}
    <div class="mb-6">
        <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;"
               class="{{ $errors->has('formData.chief_complaint') ? 'text-red-600' : 'text-gray-700 dark:text-gray-200' }}">
            Chief Complaint *
        </label>
        <textarea wire:model="formData.chief_complaint" rows="2"
            placeholder="e.g., Fever for 3 days, cough, difficulty breathing"
            class="w-full rounded-lg px-3 py-2 text-sm
                   {{ $errors->has('formData.chief_complaint') ? 'border-2 border-red-500 bg-red-50 dark:bg-red-900/20' : 'border border-gray-300 dark:border-gray-600' }}
                   text-gray-900 bg-white placeholder-gray-400
                   dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                   focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        @error('formData.chief_complaint')
            <p style="color:#ef4444;font-size:.73rem;margin-top:3px;font-weight:600;">⚠️ {{ $message }}</p>
        @enderror
    </div>

    {{-- Submit --}}
    <button wire:click="save"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50"
            style="background:#1e3a5f;color:#fff;border:none;padding:12px 32px;border-radius:8px;font-size:.9rem;font-weight:700;cursor:pointer;"
            onmouseover="this.style.opacity='.88'"
            onmouseout="this.style.opacity='1'">
        <span wire:loading.remove wire:target="save">💾 Save &amp; Proceed to Vitals →</span>
        <span wire:loading wire:target="save">⏳ Saving…</span>
    </button>
</div>
@endif

<div style="text-align:center;font-size:.72rem;color:#9ca3af;margin-top:24px;padding-bottom:8px;">
    LA UNION: Agkaysa! | Tel: (072) 607-5541-45 / (072) 607-5938 | ER: 0927-728-6330 | launionmedicalcenter@gmail.com
</div>
</x-filament-panels::page>