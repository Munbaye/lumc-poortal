<x-filament-panels::page>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- LUMC HEADER                                            --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="rounded-xl mb-6 overflow-hidden border border-blue-800"
         style="background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 100%);">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex-shrink-0">
                @if(file_exists(public_path('images/la-union-seal.png')))
                    <img src="{{ asset('images/la-union-seal.png') }}" alt="La Union Seal" class="h-16 w-16 object-contain">
                @else
                    <div class="h-16 w-16 rounded-full flex items-center justify-center text-3xl"
                         style="background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.4);">🏛️</div>
                @endif
            </div>
            <div class="text-center flex-1 mx-4">
                <p class="text-xs font-light tracking-widest uppercase" style="color: #93c5fd;">
                    Republic of the Philippines | Province of La Union
                </p>
                <h1 class="text-2xl font-bold mt-1" style="color: #ffffff; text-shadow: 0 1px 3px rgba(0,0,0,0.4);">
                    LA UNION MEDICAL CENTER
                </h1>
                <div class="inline-flex items-center gap-2 mt-1 px-3 py-0.5 rounded-full text-sm font-semibold"
                     style="background: rgba(255,255,255,0.15); color: #e0f2fe;">
                    🌡️ Vital Signs Recording
                </div>
            </div>
            <div class="flex-shrink-0">
                @if(file_exists(public_path('images/ph-flag.png')))
                    <img src="{{ asset('images/ph-flag.png') }}" alt="Philippine Flag" class="h-16 w-16 object-contain">
                @else
                    <div class="h-16 w-16 rounded-full flex items-center justify-center text-3xl"
                         style="background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.4);">🇵🇭</div>
                @endif
            </div>
        </div>
    </div>

    @if($visit)

    {{-- ── Patient Info Card ── --}}
    <div class="rounded-xl p-4 mb-6 border
                bg-blue-50 border-blue-200
                dark:bg-blue-950/50 dark:border-blue-800">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Case No</span>
                <p class="font-bold mt-0.5 text-blue-900 dark:text-blue-300">{{ $visit->patient->case_no }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Patient Name</span>
                <p class="font-bold mt-0.5 text-gray-900 dark:text-white">{{ $visit->patient->full_name }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Age / Sex</span>
                <p class="font-semibold mt-0.5 text-gray-800 dark:text-gray-200">{{ $visit->patient->age_display }} / {{ $visit->patient->sex }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Chief Complaint</span>
                <p class="font-semibold mt-0.5 text-gray-800 dark:text-gray-200">{{ $visit->chief_complaint }}</p>
            </div>
        </div>
    </div>

    {{-- ── Vitals Form ── --}}
    <div class="rounded-xl shadow-sm p-6 border
                bg-white border-gray-200
                dark:bg-gray-900 dark:border-gray-700">

        <h2 class="text-lg font-bold mb-5 text-gray-900 dark:text-white">🌡️ Vital Signs Entry</h2>

        {{-- ── Nurse Name (Special Field) ── --}}
        <div class="mb-5 p-4 rounded-lg border
                    bg-yellow-50 border-yellow-300
                    dark:bg-yellow-900/20 dark:border-yellow-700">
            <label class="block text-sm font-bold mb-1 text-yellow-800 dark:text-yellow-300">
                Nurse / Person Who Took Vitals *
            </label>
            <input type="text" wire:model="nurseName"
                placeholder="e.g., Nurse Maria Santos, Intern Juan Dela Cruz, Midwife Ana Reyes"
                class="w-full rounded-lg px-3 py-2 text-sm border
                       bg-white text-gray-900 placeholder-gray-400
                       dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       @error('nurseName') border-red-500 @else border-yellow-400 dark:border-yellow-600 @enderror
                       focus:outline-none focus:ring-2 focus:ring-yellow-400">
            <p class="text-xs mt-1 text-yellow-700 dark:text-yellow-400">
                Type the full name — for nurses, interns, volunteers, or midwives without system accounts
            </p>
            @error('nurseName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- ── Vitals Grid ── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

            {{-- Temperature --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Temperature (°C) *</label>
                <input type="number" step="0.1" wire:model="temperature"
                    placeholder="37.0" min="30" max="45"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           bg-white text-gray-900 placeholder-gray-400
                           dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           @error('temperature') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('temperature') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Temperature Site --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Temperature Site</label>
                <select wire:model="temperatureSite"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Axilla">Axilla (Armpit)</option>
                    <option value="Oral">Oral (Mouth)</option>
                    <option value="Rectal">Rectal</option>
                </select>
            </div>

            {{-- Pulse Rate --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Pulse Rate (bpm) *</label>
                <input type="number" wire:model="pulseRate"
                    placeholder="80" min="20" max="300"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           bg-white text-gray-900 placeholder-gray-400
                           dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           @error('pulseRate') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('pulseRate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Respiratory Rate --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Respiratory Rate (breaths/min) *</label>
                <input type="number" wire:model="respiratoryRate"
                    placeholder="18" min="0" max="80"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           bg-white text-gray-900 placeholder-gray-400
                           dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           @error('respiratoryRate') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('respiratoryRate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Weight --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Weight (kg)</label>
                <input type="number" step="0.01" wire:model.live="weightKg"
                    placeholder="60.00"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                @if($weightKg && $weightKg < 10)
                    <p class="text-orange-600 dark:text-orange-400 text-xs mt-1">
                        ⚠️ Pedia weight (&lt;10 kg) — Blood Pressure field is hidden
                    </p>
                @endif
            </div>

            {{-- Height --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Height (cm)</label>
                <input type="number" step="0.1" wire:model="heightCm"
                    placeholder="160.0"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Blood Pressure: hidden if pedia --}}
            @if($this->showBp)
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Blood Pressure (mmHg)</label>
                <input type="text" wire:model="bloodPressure"
                    placeholder="120/80"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Format: systolic/diastolic (e.g. 120/80)</p>
            </div>
            @endif

            {{-- O2 Saturation --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">O₂ Saturation (%)</label>
                <input type="number" wire:model="o2Saturation"
                    placeholder="98" min="0" max="100"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Pain Scale --}}
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Pain Scale (0–10)</label>
                <select wire:model="painScale"
                    class="w-full rounded-lg px-3 py-2 text-sm border
                           border-gray-300 bg-white text-gray-900
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Not assessed</option>
                    @for($i = 0; $i <= 10; $i++)
                        <option value="{{ $i }}">
                            {{ $i }}{{ $i == 0 ? ' — No pain' : ($i >= 8 ? ' — Severe' : ($i >= 4 ? ' — Moderate' : ' — Mild')) }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        {{-- Notes --}}
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Additional Notes / Observations</label>
            <textarea wire:model="notes" rows="2"
                placeholder="Any additional clinical observations…"
                class="w-full rounded-lg px-3 py-2 text-sm border
                       border-gray-300 bg-white text-gray-900 placeholder-gray-400
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 items-center">
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-not-allowed"
                    style="background:#1e3a5f;"
                    class="hover:opacity-90 text-white px-8 py-3 rounded-lg font-semibold text-sm transition">
                <span wire:loading.remove wire:target="save">💾 Save Vital Signs</span>
                <span wire:loading wire:target="save">⏳ Saving…</span>
            </button>
            <a href="{{ \App\Filament\Clerk\Resources\VisitResource::getUrl('index') }}"
               class="px-5 py-3 rounded-lg text-sm font-medium transition
                      bg-gray-100 text-gray-700 hover:bg-gray-200
                      dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                ← Back to Patients List
            </a>
        </div>
    </div>

    @else

    <div class="text-center py-12">
        <p class="text-gray-500 dark:text-gray-400">
            No visit found.
            <a href="{{ \App\Filament\Clerk\Pages\RegisterPatient::getUrl() }}"
               class="text-blue-600 dark:text-blue-400 underline hover:no-underline">
                Register a patient first
            </a>.
        </p>
    </div>

    @endif

    {{-- Footer --}}
    <div class="text-center text-xs mt-6 pb-2 text-gray-400 dark:text-gray-500">
        LA UNION: Agkaysa! | Tel: (072) 607-5541-45 / (072) 607-5938 |
        ER: 0927-728-6330 | launionmedicalcenter@gmail.com
    </div>

</x-filament-panels::page>