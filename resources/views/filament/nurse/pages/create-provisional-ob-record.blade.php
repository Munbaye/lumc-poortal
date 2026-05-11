<x-filament-panels::page>

<style>
    .ob-form-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .dark .ob-form-card { background: #1f2937; border-color: #374151; }

    .ob-card-header {
        background: #fdf2f8;
        border-bottom: 1px solid #f9a8d4;
        padding: 14px 20px;
    }
    .dark .ob-card-header { background: #4a044e; border-color: #86198f; }
    .ob-card-header h3 { font-size: 1rem; font-weight: 700; margin: 0; color: #9d174d; }
    .dark .ob-card-header h3 { color: #f0abfc; }

    .ob-card-body { padding: 20px; }

    .form-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .col-full { grid-column: span 4; }
    .col-3 { grid-column: span 3; }
    .col-2 { grid-column: span 2; }

    .form-label {
        display: block; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em;
        color: #6b7280; margin-bottom: 5px;
    }
    .form-input {
        width: 100%; border-radius: 8px; padding: 10px 12px;
        font-size: 0.875rem; border: 1px solid #d1d5db;
        background: #fff; outline: none; box-sizing: border-box;
    }
    .form-input:focus { border-color: #db2777; box-shadow: 0 0 0 3px rgba(219,39,119,.1); }
    .dark .form-input { background: #374151; border-color: #4b5563; color: #f3f4f6; }
    .dark .form-input:focus { border-color: #ec4899; }

    .select-wrapper { position: relative; }
    .select-wrapper::after {
        content: ''; position: absolute; right: 12px; top: 50%;
        transform: translateY(-50%); width: 0; height: 0;
        border-left: 5px solid transparent; border-right: 5px solid transparent;
        border-top: 6px solid #6b7280; pointer-events: none;
    }
    .select-wrapper select {
        width: 100%; border-radius: 8px; padding: 10px 32px 10px 12px;
        font-size: 0.875rem; border: 1px solid #d1d5db; background: #fff;
        outline: none; appearance: none; cursor: pointer;
    }
    .select-wrapper select:focus { border-color: #db2777; box-shadow: 0 0 0 3px rgba(219,39,119,.1); }
    .dark .select-wrapper select { background: #374151; border-color: #4b5563; color: #f3f4f6; }

    .section-title { font-size: 0.8rem; font-weight: 700; margin-bottom: 12px; color: #9d174d; text-transform: uppercase; letter-spacing: .04em; }
    .dark .section-title { color: #f0abfc; }
    .section-divider { border: none; border-top: 1px solid #fce7f3; margin: 20px 0; }
    .dark .section-divider { border-color: #4b5563; }

    .info-box {
        background: #fdf2f8; border-left: 4px solid #f472b6;
        padding: 12px 16px; margin-bottom: 20px; border-radius: 8px;
    }
    .info-box p { margin: 0; font-size: 0.8rem; line-height: 1.5; color: #9d174d; }
    .dark .info-box { background: #4a044e; }
    .dark .info-box p { color: #f0abfc; }

    .hint-text { font-size: 0.7rem; color: #6b7280; margin-top: 3px; }
    .error-text { color: #dc2626; font-size: 0.7rem; margin-top: 3px; }

    .btn-primary {
        background: linear-gradient(135deg, #9d174d, #db2777);
        color: #fff; border: none; padding: 12px 28px;
        border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer;
    }
    .btn-primary:hover { opacity: .9; }
    .btn-secondary {
        background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;
        padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; cursor: pointer;
    }

    .vital-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; }
</style>

<div class="ob-form-card">
    <div class="ob-card-header">
        <h3 style="display:flex;align-items:center;gap:8px;">
            <x-heroicon-o-heart style="width:20px;height:20px;" />
            OB / Labor & Delivery — New Patient Arrival
        </h3>
    </div>
    <div class="ob-card-body">

        <div class="info-box">
            <p>
                <strong>About this form:</strong> This creates a <strong>temporary record</strong> so the OB patient can be seen immediately.
                Fill in the Patient's Personal Profile and vital signs. The full OB Record will be completed by the nurse after the doctor admits the patient.
            </p>
        </div>

        <form wire:submit="save">

            {{-- ── Section 1: Patient's Personal Profile ─────────────────────── --}}
            <p class="section-title">Patient's Personal Profile</p>

            <div class="form-grid">
                <div>
                    <label class="form-label">Family Name <span style="color:#dc2626">*</span></label>
                    <input type="text" wire:model="formData.family_name" class="form-input" placeholder="e.g. Dela Cruz" autofocus>
                    @error('formData.family_name') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">First Name <span style="color:#dc2626">*</span></label>
                    <input type="text" wire:model="formData.first_name" class="form-input" placeholder="e.g. Maria">
                    @error('formData.first_name') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Middle Name</label>
                    <input type="text" wire:model="formData.middle_name" class="form-input" placeholder="Optional">
                </div>
                <div style="display:flex;flex-direction:column;justify-content:flex-end;">
                    <label class="form-label">Sex</label>
                    <input type="text" class="form-input" value="Female" readonly disabled
                           style="background:#fdf2f8;color:#9d174d;font-weight:600;">
                    <p class="hint-text">OB patients are always Female</p>
                </div>

                <div class="col-2">
                    <label class="form-label">Complete Address <span style="color:#dc2626">*</span></label>
                    <input type="text" wire:model="formData.address" class="form-input" placeholder="Street, Barangay, Municipality, Province">
                    @error('formData.address') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Birthday <span style="color:#dc2626">*</span></label>
                    <input type="date" wire:model.live="formData.birthday" class="form-input">
                    @error('formData.birthday') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Age</label>
                    <input type="number" wire:model="formData.age" class="form-input" placeholder="Years">
                    <p class="hint-text">Auto-calculated from birthday</p>
                </div>

                <div>
                    <label class="form-label">Occupation</label>
                    <input type="text" wire:model="formData.occupation" class="form-input" placeholder="e.g. Housewife">
                </div>
                <div>
                    <label class="form-label">Civil Status <span style="color:#dc2626">*</span></label>
                    <div class="select-wrapper">
                        <select wire:model="formData.civil_status">
                            <option value="">Select...</option>
                            <option>Single</option>
                            <option>Married</option>
                            <option>Widowed</option>
                            <option>Separated</option>
                            <option>Annulled</option>
                        </select>
                    </div>
                    @error('formData.civil_status') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Spouse / Partner</label>
                    <input type="text" wire:model="formData.spouse_name" class="form-input" placeholder="Full name">
                </div>

                <div>
                    <label class="form-label">Father</label>
                    <input type="text" wire:model="formData.father_name" class="form-input" placeholder="Full name">
                </div>
                <div>
                    <label class="form-label">Mother</label>
                    <input type="text" wire:model="formData.mother_name" class="form-input" placeholder="Maiden name">
                </div>
            </div>

            <hr class="section-divider">

            {{-- ── Section 2: Vital Signs ──────────────────────────────────────── --}}
            <p class="section-title">Vital Signs (Triage)</p>

            <div class="vital-grid">
                <div>
                    <label class="form-label">Height (cm)</label>
                    <input type="number" step="0.1" wire:model="formData.height_cm" class="form-input" placeholder="e.g. 158">
                </div>
                <div>
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" step="0.1" wire:model="formData.weight_kg" class="form-input" placeholder="e.g. 62">
                </div>
                <div>
                    <label class="form-label">Temperature (°C)</label>
                    <input type="number" step="0.1" wire:model="formData.temperature" class="form-input" placeholder="e.g. 36.8">
                </div>
                <div>
                    <label class="form-label">Pulse (bpm)</label>
                    <input type="text" wire:model="formData.pulse" class="form-input" placeholder="e.g. 82">
                </div>
                <div>
                    <label class="form-label">Blood Pressure</label>
                    <input type="text" wire:model="formData.bp" class="form-input" placeholder="e.g. 120/80">
                    @error('formData.bp') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">RR (breaths/min)</label>
                    <input type="text" wire:model="formData.rr" class="form-input" placeholder="e.g. 18">
                </div>
            </div>

            <hr class="section-divider">

            {{-- ── Section 3: Quick OB Data ─────────────────────────────────────── --}}
            <p class="section-title">Chief Complaint & Quick OB Data</p>

            <div class="form-grid">
                <div class="col-full">
                    <label class="form-label">Chief Complaint <span style="color:#dc2626">*</span></label>
                    <input type="text" wire:model="formData.chief_complaint" class="form-input"
                           placeholder="e.g. Labor pains, BOW leaking, OB check-up">
                    @error('formData.chief_complaint') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Gravida</label>
                    <input type="number" wire:model="formData.gravida" min="1" max="20" class="form-input" placeholder="G">
                </div>
                <div>
                    <label class="form-label">Para</label>
                    <input type="number" wire:model="formData.para" min="0" max="20" class="form-input" placeholder="P">
                </div>
                <div>
                    <label class="form-label">LMP</label>
                    <input type="date" wire:model="formData.lmp" class="form-input">
                </div>
                <div>
                    <label class="form-label">AOG</label>
                    <input type="text" wire:model="formData.aog" class="form-input" placeholder="e.g. 38 wks 2 days">
                </div>
            </div>

            {{-- ── Submit ───────────────────────────────────────────────────────── --}}
            <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid #fce7f3;">
                <button type="button" onclick="window.history.back()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        <x-heroicon-o-archive-box style="width:15px;height:15px;display:inline-block;vertical-align:-2px;" />
                        Create OB Provisional Record
                    </span>
                    <span wire:loading>Creating...</span>
                </button>
            </div>

        </form>
    </div>
</div>

</x-filament-panels::page>