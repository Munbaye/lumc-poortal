<x-filament-panels::page>
    {{-- Header --}}
    <div class="text-center mb-4 p-4 bg-teal-800 text-white rounded-xl flex items-center justify-between">
        <img src="{{ asset('images/la-union-seal.png') }}" alt="" class="h-14">
        <div>
            <h1 class="text-2xl font-bold">LA UNION MEDICAL CENTER</h1>
            <p class="text-sm">Physician Assessment Form</p>
        </div>
        <img src="{{ asset('images/ph-flag.png') }}" alt="" class="h-14">
    </div>

    @if($visit)
    {{-- Patient Summary Card --}}
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 mb-4 grid grid-cols-2 md:grid-cols-5 gap-3 text-sm">
        <div><span class="text-gray-500">Case No</span><p class="font-bold text-teal-900">{{ $visit->patient->case_no }}</p></div>
        <div><span class="text-gray-500">Name</span><p class="font-bold">{{ $visit->patient->full_name }}</p></div>
        <div><span class="text-gray-500">Age/Sex</span><p class="font-semibold">{{ $visit->patient->age_display }} / {{ $visit->patient->sex }}</p></div>
        <div><span class="text-gray-500">Birthday</span><p class="font-semibold">{{ $visit->patient->birthday?->format('M d, Y') ?? 'Unknown' }}</p></div>
        <div><span class="text-gray-500">Status</span><p class="font-bold text-teal-700 uppercase">{{ $visit->status }}</p></div>
    </div>

    {{-- Latest Vitals --}}
    @if($latestVitals = $visit->latestVitals)
    <div class="bg-white border rounded-xl p-4 mb-4 text-sm">
        <h3 class="font-bold text-gray-700 mb-2">Latest Vitals
            <span class="text-xs text-gray-400 font-normal">
                (by {{ $latestVitals->nurse_name }}, recorded {{ $latestVitals->taken_at->format('M d, Y H:i') }})
            </span>
        </h3>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
            <div class="text-center bg-gray-50 rounded p-2">
                <p class="text-xs text-gray-500">Temp</p>
                <p class="font-bold">{{ $latestVitals->temperature }}°C</p>
                <p class="text-xs">{{ $latestVitals->temperature_site }}</p>
            </div>
            <div class="text-center bg-gray-50 rounded p-2">
                <p class="text-xs text-gray-500">Pulse</p>
                <p class="font-bold">{{ $latestVitals->pulse_rate }}</p>
            </div>
            <div class="text-center bg-gray-50 rounded p-2">
                <p class="text-xs text-gray-500">RR</p>
                <p class="font-bold">{{ $latestVitals->respiratory_rate }}</p>
            </div>
            @if($latestVitals->blood_pressure)
            <div class="text-center bg-gray-50 rounded p-2">
                <p class="text-xs text-gray-500">BP</p>
                <p class="font-bold">{{ $latestVitals->blood_pressure }}</p>
            </div>
            @endif
            @if($latestVitals->o2_saturation)
            <div class="text-center bg-gray-50 rounded p-2">
                <p class="text-xs text-gray-500">O₂ Sat</p>
                <p class="font-bold">{{ $latestVitals->o2_saturation }}%</p>
            </div>
            @endif
            @if($latestVitals->weight_kg)
            <div class="text-center bg-gray-50 rounded p-2">
                <p class="text-xs text-gray-500">Wt/Ht</p>
                <p class="font-bold">{{ $latestVitals->weight_kg }}kg</p>
                <p class="text-xs">{{ $latestVitals->height_cm }}cm</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Assessment Form --}}
    <div class="bg-white rounded-xl shadow p-6 mb-4">
        <h2 class="text-xl font-bold text-teal-900 mb-4">📋 Medical History & Assessment</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Chief Complaint</label>
                <textarea wire:model="chiefComplaint" rows="1"
                    class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">History of Present Illness (HPI)</label>
                <textarea wire:model="historyOfPresentIllness" rows="4"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                    placeholder="Duration, onset, character, associated symptoms..."></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Past Medical History</label>
                    <textarea wire:model="pastMedicalHistory" rows="3"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="Previous illnesses, hospitalizations, surgeries..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Family History</label>
                    <textarea wire:model="familyHistory" rows="3"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="DM, HPN, CA, etc. in family..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Allergies</label>
                    <textarea wire:model="allergies" rows="2"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="Drug, food, environmental allergies (NKDA if none)..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Medications</label>
                    <textarea wire:model="currentMedications" rows="2"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="Maintenance meds, vitamins..."></textarea>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Physical Examination Findings</label>
                <textarea wire:model="physicalExam" rows="5"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                    placeholder="General, HEENT, Chest, Abdomen, Extremities, Neuro..."></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis / Impression</label>
                    <textarea wire:model="diagnosis" rows="3"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="e.g., Community-acquired pneumonia, right..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Differential Diagnosis</label>
                    <textarea wire:model="differentialDiagnosis" rows="3"
                        class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Management Plan / Remarks</label>
                <textarea wire:model="plan" rows="3"
                    class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            </div>

            {{-- Disposition --}}
            <div class="bg-gray-50 rounded-xl p-4">
                <h3 class="font-bold text-gray-700 mb-3">📤 Disposition</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                    @foreach(['Discharged','Admitted','Referred','HAMA','Expired'] as $d)
                    <label class="flex items-center gap-2 cursor-pointer bg-white border rounded-lg p-2 hover:bg-blue-50
                        {{ $disposition === $d ? 'border-blue-600 bg-blue-50' : '' }}">
                        <input type="radio" wire:model.live="disposition" value="{{ $d }}">
                        <span class="text-sm font-medium">{{ $d }}</span>
                    </label>
                    @endforeach
                </div>

                @if($disposition === 'Admitted')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ward / Service</label>
                        <input type="text" wire:model="admittedWard"
                            placeholder="e.g., Medical Ward, ICU, Pedia Ward"
                            class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select wire:model="service" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Select service...</option>
                            <option>Internal Medicine</option>
                            <option>Pediatrics</option>
                            <option>OB-Gynecology</option>
                            <option>Surgery</option>
                            <option>Orthopedics</option>
                            <option>ENT</option>
                            <option>Ophthalmology</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment / Classification</label>
                        <select wire:model="paymentType" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Select...</option>
                            <option>PhilHealth</option>
                            <option>Indigent / Malasakit</option>
                            <option>Private Pay</option>
                            <option>Senior Citizen</option>
                            <option>PWD</option>
                            <option>4Ps</option>
                        </select>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button wire:click="save"
                class="bg-teal-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-teal-700">
                💾 Save Assessment
            </button>
        </div>
    </div>

    {{-- Doctor's Orders --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-teal-900 mb-4">📝 Doctor's Orders</h2>
        <div class="flex gap-3 mb-4">
            <input type="text" wire:model="newOrder"
                wire:keydown.enter="addOrder"
                placeholder="Type an order and press Enter..."
                class="flex-1 border rounded-lg px-3 py-2 text-sm">
            <button wire:click="addOrder"
                class="bg-teal-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-600">
                + Add Order
            </button>
        </div>
        @if(count($orders) > 0)
        <ol class="space-y-2">
            @foreach($orders as $i => $order)
            <li class="flex items-start gap-3 bg-gray-50 rounded p-3">
                <span class="text-teal-800 font-bold">{{ $i + 1 }}.</span>
                <span class="flex-1 text-sm">{{ $order['order_text'] }}</span>
                <span class="text-xs text-gray-400">
                    {{ $order['is_completed'] ? '✅ Done' : '⏳ Pending' }}
                </span>
            </li>
            @endforeach
        </ol>
        @endif
    </div>
    @endif

    <div class="text-center text-xs text-gray-400 mt-6 pb-4">
        LA UNION: Agkaysa! | Tel: (072) 607-5541-45 / (072) 607-5938 |
        ER: 0927-728-6330 | launionmedicalcenter@gmail.com
    </div>
</x-filament-panels::page>