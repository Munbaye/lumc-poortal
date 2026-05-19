{{-- Partial: ER Triage vitals section --}}
<div class="er-card" style="margin-top:16px;margin-bottom:0;">
    <div class="er-card-header green">
        <x-heroicon-o-heart class="w-5 h-5 text-green-700" />
        <span class="er-card-title green">Initial Vital Signs</span>
        <span style="font-size:.72rem;color:#6b7280;margin-left:auto;">* Temperature, Pulse, and RR are required</span>
    </div>
    <div class="er-card-body">
        <div class="form-grid g4">
            <div>
                <label class="f-label">Temperature (°C) *</label>
                <input type="number" step="0.1" wire:model="temperature" class="f-input" placeholder="36.5" />
            </div>
            <div>
                <label class="f-label">Temp site</label>
                <select wire:model="temperatureSite" class="f-input f-select">
                    <option>Axilla</option>
                    <option>Oral</option>
                    <option>Rectal</option>
                    <option>Tympanic</option>
                </select>
            </div>
            <div>
                <label class="f-label">Pulse rate (bpm) *</label>
                <input type="number" wire:model="pulseRate" class="f-input" placeholder="72" />
            </div>
            <div>
                <label class="f-label">Respiratory rate *</label>
                <input type="number" wire:model="respiratoryRate" class="f-input" placeholder="18" />
            </div>
            <div>
                <label class="f-label">Blood pressure</label>
                <input wire:model="bloodPressure" class="f-input" placeholder="120/80" />
            </div>
            <div>
                <label class="f-label">O₂ saturation (%)</label>
                <input type="number" wire:model="o2Saturation" class="f-input" placeholder="98" />
            </div>
            <div>
                <label class="f-label">Pain scale (0–10)</label>
                <select wire:model="painScale" class="f-input f-select">
                    <option value="">N/A</option>
                    @for($i = 0; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="f-label">Weight (kg)</label>
                <input type="number" step="0.1" wire:model="weightKg" class="f-input" placeholder="60" />
            </div>
            <div>
                <label class="f-label">Height (cm)</label>
                <input type="number" step="0.1" wire:model="heightCm" class="f-input" placeholder="165" />
            </div>
            <div class="span3">
                <label class="f-label">Notes / remarks</label>
                <input wire:model="vitalNotes" class="f-input" placeholder="Any observations…" />
            </div>
        </div>
    </div>
</div>