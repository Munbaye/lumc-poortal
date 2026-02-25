<x-filament-panels::page>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- LUMC HEADER — works in both light AND dark mode        --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="rounded-xl mb-6 overflow-hidden border border-blue-800"
         style="background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 100%);">
        <div class="flex items-center justify-between px-6 py-4">

            {{-- Left: Province Seal (fallback to styled div if image missing) --}}
            <div class="flex-shrink-0">
                @if(file_exists(public_path('images/la-union-seal.png')))
                    <img src="{{ asset('images/la-union-seal.png') }}" alt="La Union Seal" class="h-16 w-16 object-contain">
                @else
                    <div class="h-16 w-16 rounded-full flex items-center justify-center text-3xl"
                         style="background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.4);">
                        🏛️
                    </div>
                @endif
            </div>

            {{-- Center: Title --}}
            <div class="text-center flex-1 mx-4">
                <p class="text-xs font-light tracking-widest uppercase" style="color: #93c5fd;">
                    Republic of the Philippines | Province of La Union
                </p>
                <h1 class="text-2xl font-bold mt-1" style="color: #ffffff; text-shadow: 0 1px 3px rgba(0,0,0,0.4);">
                    LA UNION MEDICAL CENTER
                </h1>
                <div class="inline-flex items-center gap-2 mt-1 px-3 py-0.5 rounded-full text-sm font-semibold"
                     style="background: rgba(255,255,255,0.15); color: #e0f2fe;">
                    @if(($formData['registration_type'] ?? 'OPD') === 'ER')
                        🚑 Emergency Room Registration
                    @else
                        📋 Out-Patient Registration
                    @endif
                </div>
            </div>

            {{-- Right: PH Flag (fallback to styled div) --}}
            <div class="flex-shrink-0">
                @if(file_exists(public_path('images/ph-flag.png')))
                    <img src="{{ asset('images/ph-flag.png') }}" alt="Philippine Flag" class="h-16 w-16 object-contain">
                @else
                    <div class="h-16 w-16 rounded-full flex items-center justify-center text-3xl"
                         style="background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.4);">
                        🇵🇭
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- STEP 1: SEARCH                                         --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="rounded-xl shadow-sm p-6 mb-4 border
                bg-white border-gray-200
                dark:bg-gray-900 dark:border-gray-700">

        <h2 class="text-lg font-bold mb-1 text-gray-900 dark:text-white">
            🔍 Step 1: Search for Existing Patient
        </h2>
        <p class="text-sm mb-4 text-gray-500 dark:text-gray-400">
            ⚠️ Always search first to prevent duplicate records. Type at least 3 characters of the family name.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">

            {{-- Family Name --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">
                    Family Name (Last Name) *
                </label>
                <input type="text"
                    wire:model.live.debounce.400ms="searchFamilyName"
                    placeholder="e.g., Dela Cruz"
                    autofocus
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- First Name --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">First Name</label>
                <input type="text"
                    wire:model.live.debounce.400ms="searchFirstName"
                    placeholder="e.g., Juan"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Sex --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Sex</label>
                <select wire:model.live="searchSex"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Any</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            {{-- Birthday --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Birthday (approx. ±1 yr)</label>
                <input type="date"
                    wire:model.live="searchBirthday"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        {{-- ── Search Results ── --}}
        @if($hasSearched)
            @if(count($searchResults) > 0)
                <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm">
                        <thead style="background: #1e3a5f;">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-blue-100">Case No</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-blue-100">Full Name</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-blue-100">Age / Sex</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-blue-100">Birthday</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-blue-100">Last Visit</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-blue-100">Address</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-blue-100">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($searchResults as $r)
                            <tr class="transition
                                {{ $selectedPatientId == $r['id']
                                    ? 'bg-green-50 dark:bg-green-900/30'
                                    : 'bg-white dark:bg-gray-900 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                                <td class="px-3 py-2 font-mono text-xs text-blue-700 dark:text-blue-400">{{ $r['case_no'] }}</td>
                                <td class="px-3 py-2 font-semibold text-gray-900 dark:text-white">{{ $r['full_name'] }}</td>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-300">{{ $r['age_display'] }} / {{ $r['sex'] }}</td>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-300">{{ $r['birthday'] ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-500 dark:text-gray-400 text-xs">{{ $r['last_visit'] ?? 'No visit yet' }}</td>
                                <td class="px-3 py-2 text-gray-500 dark:text-gray-400 text-xs">{{ $r['address'] }}…</td>
                                <td class="px-3 py-2">
                                    <button wire:click="selectPatient({{ $r['id'] }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                        ✓ This is the patient
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-xs mt-2 text-gray-500 dark:text-gray-400">
                    {{ count($searchResults) }} result(s) found. Click "This is the patient" if you see a match.
                </p>

            @else
                {{-- No results --}}
                <div class="rounded-lg p-4 border
                            bg-yellow-50 border-yellow-300
                            dark:bg-yellow-900/20 dark:border-yellow-700">
                    <p class="font-semibold text-yellow-800 dark:text-yellow-300">
                        ⚠️ No similar patients found for "<strong>{{ $searchFamilyName }}</strong>"
                    </p>
                    <p class="text-sm mt-1 text-yellow-700 dark:text-yellow-400">
                        Double-check the spelling. If you are certain this is a new patient, check the box below.
                    </p>
                    <div class="mt-3 flex items-center gap-2">
                        <input type="checkbox" id="confirmNoMatch"
                            wire:model.live="confirmNoMatch"
                            class="w-4 h-4 rounded text-blue-600 border-gray-300 dark:border-gray-600">
                        <label for="confirmNoMatch" class="text-sm font-semibold cursor-pointer
                                                           text-gray-800 dark:text-gray-200">
                            I confirm no existing patient matches — create new record
                        </label>
                    </div>
                    @if($confirmNoMatch)
                        <button wire:click="showNewPatientForm"
                            class="mt-3 px-5 py-2 rounded-lg text-sm font-semibold text-white transition
                                   bg-blue-700 hover:bg-blue-800">
                            + Create New Patient Record
                        </button>
                    @endif
                </div>
            @endif
        @else
            <div class="text-center py-8 text-gray-400 dark:text-gray-600">
                <p class="text-4xl mb-2">🔍</p>
                <p class="text-sm">Type at least 3 characters in Family Name to begin searching…</p>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- STEP 2: REGISTRATION / UPDATE FORM                     --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if($showCreateForm)
    <div class="rounded-xl shadow-sm p-6 border
                bg-white border-gray-200
                dark:bg-gray-900 dark:border-gray-700">

        <h2 class="text-lg font-bold mb-5 text-gray-900 dark:text-white">
            @if($selectedPatientId)
                ✏️ Step 2: Update Existing Patient & Register Visit
            @else
                📋 Step 2: New Patient Registration
            @endif
        </h2>

        {{-- ── Registration Type ── --}}
        <div class="mb-5 p-3 rounded-lg border
                    bg-gray-50 border-gray-200
                    dark:bg-gray-800 dark:border-gray-700">
            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-200">Registration Type *</label>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" wire:model.live="formData.registration_type" value="OPD"
                           class="w-4 h-4 text-blue-600">
                    <span class="font-semibold text-blue-800 dark:text-blue-300">OPD (Out-Patient)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" wire:model.live="formData.registration_type" value="ER"
                           class="w-4 h-4 text-red-600">
                    <span class="font-semibold text-red-700 dark:text-red-400">🚑 ER (Emergency Room)</span>
                </label>
            </div>
        </div>

        {{-- ── Mandatory Fields ── --}}
        <p class="text-xs font-bold uppercase tracking-wider mb-3 text-gray-500 dark:text-gray-400">
            Required Information
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Family Name *</label>
                <input type="text" wire:model="formData.family_name"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           text-gray-900 bg-white placeholder-gray-400
                           dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                           @error('formData.family_name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('formData.family_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">First Name *</label>
                <input type="text" wire:model="formData.first_name"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           text-gray-900 bg-white placeholder-gray-400
                           dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                           @error('formData.first_name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('formData.first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Middle Name</label>
                <input type="text" wire:model="formData.middle_name"
                    placeholder="Optional"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Sex *</label>
                <select wire:model="formData.sex"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           bg-white text-gray-900
                           dark:bg-gray-800 dark:text-white
                           @error('formData.sex') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select sex…</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                @error('formData.sex') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Birthday</label>
                <input type="date" wire:model="formData.birthday"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Contact Number</label>
                <input type="text" wire:model="formData.contact_number"
                    placeholder="09XX-XXX-XXXX"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mb-5">
            <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">
                Complete Address (Barangay, City/Municipality, Province) *
            </label>
            <textarea wire:model="formData.address" rows="2"
                placeholder="e.g., Brgy. Poblacion, Agoo, La Union"
                class="w-full rounded-lg px-3 py-2 text-sm border
                       bg-white text-gray-900 placeholder-gray-400
                       dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       @error('formData.address') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                       focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            @error('formData.address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- ── Optional Fields ── --}}
        <p class="text-xs font-bold uppercase tracking-wider mb-3 text-gray-500 dark:text-gray-400">
            Optional Information
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-300">Civil Status</label>
                <select wire:model.live="formData.civil_status"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select…</option>
                    <option>Single</option>
                    <option>Married</option>
                    <option>Widowed</option>
                    <option>Separated</option>
                    <option>Annulled</option>
                </select>
            </div>

            @if(($formData['civil_status'] ?? '') === 'Married')
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-300">Spouse Name</label>
                <input type="text" wire:model="formData.spouse_name"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-300">Occupation</label>
                <input type="text" wire:model="formData.occupation"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-300">Father's Name</label>
                <input type="text" wire:model="formData.father_name"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-300">Mother's Name</label>
                <input type="text" wire:model="formData.mother_name"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        {{-- ── ER-Specific Fields ── --}}
        @if(($formData['registration_type'] ?? 'OPD') === 'ER')
        <div class="rounded-lg p-4 mb-5 border
                    bg-red-50 border-red-200
                    dark:bg-red-900/20 dark:border-red-700">
            <h3 class="font-bold mb-3 text-red-800 dark:text-red-300">🚑 Emergency Room Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Brought By</label>
                    <select wire:model="formData.brought_by"
                        class="w-full rounded-lg px-3 py-2 text-sm border
                               border-gray-300 bg-white text-gray-900
                               dark:border-gray-600 dark:bg-gray-800 dark:text-white
                               focus:outline-none focus:ring-2 focus:ring-red-400">
                        <option value="">Select…</option>
                        <option>Self</option>
                        <option>Family</option>
                        <option>Ambulance</option>
                        <option>Police</option>
                        <option>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Condition on Arrival</label>
                    <select wire:model="formData.condition_on_arrival"
                        class="w-full rounded-lg px-3 py-2 text-sm border
                               border-gray-300 bg-white text-gray-900
                               dark:border-gray-600 dark:bg-gray-800 dark:text-white
                               focus:outline-none focus:ring-2 focus:ring-red-400">
                        <option value="">Select…</option>
                        <option>Good</option>
                        <option>Fair</option>
                        <option>Poor</option>
                        <option>Shock</option>
                        <option>Comatose</option>
                        <option>Hemorrhagic</option>
                        <option>DOA</option>
                    </select>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Chief Complaint ── --}}
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">
                Chief Complaint *
            </label>
            <textarea wire:model="formData.chief_complaint" rows="2"
                placeholder="e.g., Fever for 3 days, cough, difficulty breathing"
                class="w-full rounded-lg px-3 py-2 text-sm border
                       bg-white text-gray-900 placeholder-gray-400
                       dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       @error('formData.chief_complaint') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                       focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            @error('formData.chief_complaint') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- ── Submit ── --}}
        <div class="flex gap-3 items-center">
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-not-allowed"
                    style="background:#1e3a5f;"
                    class="hover:opacity-90 text-white px-8 py-3 rounded-lg font-semibold text-sm transition">
                <span wire:loading.remove wire:target="save">💾 Save & Proceed to Vitals →</span>
                <span wire:loading wire:target="save">⏳ Saving…</span>
            </button>
        </div>
    </div>
    @endif

    {{-- Footer --}}
    <div class="text-center text-xs mt-6 pb-2 text-gray-400 dark:text-gray-500">
        LA UNION: Agkaysa! | Tel: (072) 607-5541-45 / (072) 607-5938 |
        ER: 0927-728-6330 | launionmedicalcenter@gmail.com | www.launion.gov.ph
    </div>

</x-filament-panels::page>