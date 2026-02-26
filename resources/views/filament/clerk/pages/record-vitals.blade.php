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
                🌡️ Vital Signs Recording
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

@if($visit)

{{-- Patient Info Card --}}
<div style="border-radius:12px;padding:16px;margin-bottom:20px;border:1px solid #bfdbfe;background:#eff6ff;"
     class="dark:bg-blue-950/50 dark:border-blue-800">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div>
            <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;" class="text-gray-500 dark:text-gray-400">Case No</span>
            <p style="font-weight:700;margin-top:2px;font-family:monospace;" class="text-blue-900 dark:text-blue-300">{{ $visit->patient->case_no }}</p>
        </div>
        <div>
            <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;" class="text-gray-500 dark:text-gray-400">Patient Name</span>
            <p style="font-weight:700;margin-top:2px;" class="text-gray-900 dark:text-white">{{ $visit->patient->full_name }}</p>
        </div>
        <div>
            <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;" class="text-gray-500 dark:text-gray-400">Age / Sex</span>
            <p style="font-weight:600;margin-top:2px;" class="text-gray-800 dark:text-gray-200">{{ $visit->patient->age_display }} / {{ $visit->patient->sex }}</p>
        </div>
        <div>
            <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;" class="text-gray-500 dark:text-gray-400">Chief Complaint</span>
            <p style="font-weight:600;margin-top:2px;" class="text-gray-800 dark:text-gray-200">{{ $visit->chief_complaint }}</p>
        </div>
    </div>
</div>

{{-- Vitals Form --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;"
     class="dark:bg-gray-900 dark:border-gray-700">

    <h2 style="font-size:1.05rem;font-weight:700;margin-bottom:20px;" class="text-gray-900 dark:text-white">
        🌡️ Vital Signs Entry
    </h2>

    {{-- Nurse Name --}}
    <div style="background:#fefce8;border:1px solid #fde047;border-radius:10px;padding:16px;margin-bottom:20px;"
         class="dark:bg-yellow-900/20 dark:border-yellow-700">
        <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:6px;"
               class="{{ $errors->has('nurseName') ? 'text-red-600' : 'text-yellow-800 dark:text-yellow-300' }}">
            Nurse / Person Who Took Vitals *
        </label>
        <input type="text" wire:model="nurseName"
            placeholder="e.g., Nurse Maria Santos, Intern Juan Dela Cruz, Midwife Ana Reyes"
            class="w-full rounded-lg px-3 py-2 text-sm
                   {{ $errors->has('nurseName') ? 'border-2 border-red-500 bg-red-50 dark:bg-red-900/20' : 'border border-yellow-400 dark:border-yellow-600' }}
                   text-gray-900 bg-white placeholder-gray-400
                   dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                   focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <p style="font-size:.73rem;margin-top:4px;" class="text-yellow-700 dark:text-yellow-400">
            Type full name — handles nurses, interns, volunteers, or midwives without system accounts
        </p>
        @error('nurseName')
            <p style="color:#ef4444;font-size:.73rem;margin-top:3px;font-weight:600;">⚠️ {{ $message }}</p>
        @enderror
    </div>

    {{-- Vitals Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

        {{-- Temperature --}}
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;"
                   class="{{ $errors->has('temperature') ? 'text-red-600' : 'text-gray-700 dark:text-gray-200' }}">
                Temperature (°C) *
            </label>
            <input type="number" step="0.1" wire:model="temperature" placeholder="37.0" min="30" max="45"
                class="w-full rounded-lg px-3 py-2 text-sm
                       {{ $errors->has('temperature') ? 'border-2 border-red-500 bg-red-50 dark:bg-red-900/20' : 'border border-gray-300 dark:border-gray-600' }}
                       text-gray-900 bg-white placeholder-gray-400
                       dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('temperature')
                <p style="color:#ef4444;font-size:.73rem;margin-top:3px;font-weight:600;">⚠️ {{ $message }}</p>
            @enderror
        </div>

        {{-- Temperature Site --}}
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Temperature Site</label>
            <select wire:model="temperatureSite"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Axilla">Axilla (Armpit)</option>
                <option value="Oral">Oral (Mouth)</option>
                <option value="Rectal">Rectal</option>
            </select>
        </div>

        {{-- Pulse Rate --}}
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;"
                   class="{{ $errors->has('pulseRate') ? 'text-red-600' : 'text-gray-700 dark:text-gray-200' }}">
                Pulse Rate (bpm) *
            </label>
            <input type="number" wire:model="pulseRate" placeholder="80" min="20" max="300"
                class="w-full rounded-lg px-3 py-2 text-sm
                       {{ $errors->has('pulseRate') ? 'border-2 border-red-500 bg-red-50 dark:bg-red-900/20' : 'border border-gray-300 dark:border-gray-600' }}
                       text-gray-900 bg-white placeholder-gray-400
                       dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('pulseRate')
                <p style="color:#ef4444;font-size:.73rem;margin-top:3px;font-weight:600;">⚠️ {{ $message }}</p>
            @enderror
        </div>

        {{-- Respiratory Rate --}}
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;"
                   class="{{ $errors->has('respiratoryRate') ? 'text-red-600' : 'text-gray-700 dark:text-gray-200' }}">
                Respiratory Rate (breaths/min) *
            </label>
            <input type="number" wire:model="respiratoryRate" placeholder="18" min="0" max="80"
                class="w-full rounded-lg px-3 py-2 text-sm
                       {{ $errors->has('respiratoryRate') ? 'border-2 border-red-500 bg-red-50 dark:bg-red-900/20' : 'border border-gray-300 dark:border-gray-600' }}
                       text-gray-900 bg-white placeholder-gray-400
                       dark:text-white dark:bg-gray-800 dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('respiratoryRate')
                <p style="color:#ef4444;font-size:.73rem;margin-top:3px;font-weight:600;">⚠️ {{ $message }}</p>
            @enderror
        </div>

        {{-- Weight --}}
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Weight (kg)</label>
            <input type="number" step="0.01" wire:model.live="weightKg" placeholder="60.00"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900 placeholder-gray-400
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
            @if($weightKg && $weightKg < 10)
                <p style="color:#ea580c;font-size:.73rem;margin-top:3px;" class="dark:text-orange-400">
                    ⚠️ Pedia weight (&lt;10 kg) — Blood Pressure field hidden
                </p>
            @endif
        </div>

        {{-- Height --}}
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Height (cm)</label>
            <input type="number" step="0.1" wire:model="heightCm" placeholder="160.0"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900 placeholder-gray-400
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Blood Pressure (hidden if pedia) --}}
        @if($this->showBp)
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Blood Pressure (mmHg)</label>
            <input type="text" wire:model="bloodPressure" placeholder="120/80"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900 placeholder-gray-400
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p style="font-size:.7rem;margin-top:3px;" class="text-gray-400 dark:text-gray-500">Format: 120/80</p>
        </div>
        @endif

        {{-- O2 Saturation --}}
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">O₂ Saturation (%)</label>
            <input type="number" wire:model="o2Saturation" placeholder="98" min="0" max="100"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900 placeholder-gray-400
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Pain Scale --}}
        <div>
            <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Pain Scale (0–10)</label>
            <select wire:model="painScale"
                class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900
                       dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Not assessed</option>
                @for($i = 0; $i <= 10; $i++)
                    <option value="{{ $i }}">
                        {{ $i }}
                        @if($i == 0) — No pain
                        @elseif($i <= 3) — Mild
                        @elseif($i <= 6) — Moderate
                        @else — Severe
                        @endif
                    </option>
                @endfor
            </select>
        </div>
    </div>

    {{-- Notes --}}
    <div class="mb-6">
        <label style="display:block;font-size:.78rem;font-weight:700;margin-bottom:4px;" class="text-gray-700 dark:text-gray-200">Additional Notes / Observations</label>
        <textarea wire:model="notes" rows="2"
            placeholder="Any additional clinical observations…"
            class="w-full rounded-lg px-3 py-2 text-sm border border-gray-300 bg-white text-gray-900 placeholder-gray-400
                   dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500
                   focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3 items-center">
        <button wire:click="save"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50"
                style="background:#1e3a5f;color:#fff;border:none;padding:12px 32px;border-radius:8px;font-size:.9rem;font-weight:700;cursor:pointer;"
                onmouseover="this.style.opacity='.88'"
                onmouseout="this.style.opacity='1'">
            <span wire:loading.remove wire:target="save">💾 Save Vital Signs</span>
            <span wire:loading wire:target="save">⏳ Saving…</span>
        </button>
        <a href="{{ \App\Filament\Clerk\Resources\VisitResource::getUrl('index') }}"
           style="padding:12px 20px;border-radius:8px;font-size:.85rem;font-weight:600;text-decoration:none;"
           class="bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            ← Back to Patients List
        </a>
    </div>
</div>

@else

<div class="text-center py-12">
    <p class="text-gray-500 dark:text-gray-400">
        No visit found.
        <a href="{{ \App\Filament\Clerk\Pages\RegisterPatient::getUrl() }}"
           class="text-blue-600 dark:text-blue-400 underline">Register a patient first</a>.
    </p>
</div>

@endif

<div style="text-align:center;font-size:.72rem;color:#9ca3af;margin-top:24px;padding-bottom:8px;">
    LA UNION: Agkaysa! | Tel: (072) 607-5541-45 / (072) 607-5938 | ER: 0927-728-6330 | launionmedicalcenter@gmail.com
</div>

</x-filament-panels::page>