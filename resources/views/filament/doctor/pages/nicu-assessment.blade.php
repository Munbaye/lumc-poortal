<x-filament-panels::page>
<style>
    .form-section { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .dark .form-section { background: #1f2937; border-color: #374151; }
    .section-header { background: #f8fafc; border-bottom: 1px solid #e5e7eb; padding: 12px 20px; display: flex; align-items: center; gap: 10px; }
    .dark .section-header { background: #1e2937; border-color: #334155; }
    .section-title { font-size: 0.9rem; font-weight: 700; color: #1e3a5f; }
    .dark .section-title { color: #93c5fd; }
    .section-body { padding: 20px; }
    .form-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .form-field-full { grid-column: span 4; }
    .form-field-half { grid-column: span 2; }
    .form-label { display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: 5px; }
    .form-input { width: 100%; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem; border: 1px solid #d1d5db; background: #fff; outline: none; }
    .dark .form-input { background: #374151; border-color: #4b5563; color: #f3f4f6; }
    .form-input:focus { border-color: #1d4ed8; box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.1); }
    textarea.form-input { resize: vertical; min-height: 60px; }
    .admission-section { background: #fffbeb; border: 2px solid #fde68a; border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .admission-header { background: #fef3c7; padding: 12px 20px; }
    .admission-body { padding: 20px; }
    .status-badge { display: inline-flex; align-items: center; gap: 8px; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .status-pending { background: #fee2e2; color: #991b1b; }
    .status-admitted { background: #d1fae5; color: #065f46; }
    .btn-primary { background: #1d4ed8; color: #fff; border: none; padding: 12px 28px; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; }
    .btn-primary:hover { background: #1e40af; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; cursor: pointer; }
    .checkbox-label { display: flex; align-items: center; gap: 10px; cursor: pointer; }
</style>

<div style="max-width: 1400px; margin: 0 auto;">
    {{-- Patient Header --}}
    <div style="background: linear-gradient(135deg, #1e3a5f, #1d4ed8); border-radius: 12px; padding: 16px 24px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                <p style="color: #fff; font-size: 1rem; font-weight: 700; margin: 0;">{{ $visit->patient->display_name ?? 'Baby' }}</p>
                <p style="color: #93c5fd; font-size: 0.75rem; margin: 2px 0 0;">
                    {{ $visit->patient->case_no ?? $visit->patient->temporary_case_no }} 
                    | Born: {{ $nicuAdmission?->date_time_of_birth ? \Carbon\Carbon::parse($nicuAdmission->date_time_of_birth)->format('M d, Y h:i A') : ($visit->patient->birth_datetime ? \Carbon\Carbon::parse($visit->patient->birth_datetime)->format('M d, Y h:i A') : '—') }}
                    | {{ $visit->patient->sex ?? '—' }}
                </p>
            </div>
            <div>
                @if($isAdmitted)
                    <span class="status-badge status-admitted">✅ ADMITTED</span>
                @else
                    <span class="status-badge status-pending">⚠️ PENDING ADMISSION</span>
                @endif
            </div>
        </div>
    </div>

    <form wire:submit="save">

        {{-- SECTION 1: Exam Information --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">📋 Exam Information</span>
            </div>
            <div class="section-body">
                <div class="form-grid">
                    <div>
                        <label class="form-label">Date of Exam</label>
                        <input type="date" wire:model="examDate" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Hours After Birth</label>
                        <input type="number" wire:model="hoursAfterBirth" class="form-input" placeholder="Hours">
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: APGAR Scores --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">💓 APGAR Scores</span>
            </div>
            <div class="section-body">
                <div class="form-grid">
                    <div>
                        <label class="form-label">1 Minute</label>
                        <input type="number" wire:model="apgarBirth" class="form-input" min="0" max="10" placeholder="0-10">
                    </div>
                    <div>
                        <label class="form-label">5 Minutes</label>
                        <input type="number" wire:model="apgar5Min" class="form-input" min="0" max="10" placeholder="0-10">
                    </div>
                    <div>
                        <label class="form-label">10 Minutes</label>
                        <input type="number" wire:model="apgar10Min" class="form-input" min="0" max="10" placeholder="0-10">
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: Measurements --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">📏 Measurements</span>
            </div>
            <div class="section-body">
                <div class="form-grid">
                    <div>
                        <label class="form-label">Birth Weight (g)</label>
                        <input type="number" step="1" wire:model="birthWeightG" class="form-input" placeholder="grams">
                    </div>
                    <div>
                        <label class="form-label">Birth Length (cm)</label>
                        <input type="number" step="0.1" wire:model="birthLengthCm" class="form-input" placeholder="cm">
                    </div>
                    <div>
                        <label class="form-label">Head Circumference (cm)</label>
                        <input type="number" step="0.1" wire:model="headCircumferenceCm" class="form-input" placeholder="cm">
                    </div>
                    <div>
                        <label class="form-label">Chest Circumference (cm)</label>
                        <input type="number" step="0.1" wire:model="chestCircumferenceCm" class="form-input" placeholder="cm">
                    </div>
                    <div>
                        <label class="form-label">Abdominal Circumference (cm)</label>
                        <input type="number" step="0.1" wire:model="abdominalCircumferenceCm" class="form-input" placeholder="cm">
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 4: Physical Examination --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">🩺 Physical Examination</span>
            </div>
            <div class="section-body">
                
                {{-- General --}}
                <div class="form-grid-2">
                    <div class="form-field-full">
                        <label class="form-label">General Condition</label>
                        <textarea wire:model="generalCondition" rows="2" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Muscular Tonus</label>
                        <select wire:model="generalMuscularTonus" class="form-input">
                            <option value="">Select</option>
                            <option>Normal</option>
                            <option>Hypotonia</option>
                            <option>Hypertonia</option>
                        </select>
                    </div>
                </div>

                {{-- Skin --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">SKIN</h5>
                <div class="form-grid">
                    <div><label class="form-label">Color</label><input type="text" wire:model="skinColor" class="form-input"></div>
                    <div><label class="form-label">Turgor</label><input type="text" wire:model="skinTurgor" class="form-input"></div>
                    <div><label class="form-label">Rash</label><input type="text" wire:model="skinRash" class="form-input"></div>
                    <div><label class="form-label">Desquamation</label><input type="text" wire:model="skinDesquamation" class="form-input"></div>
                </div>

                {{-- Head --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">HEAD</h5>
                <div class="form-grid">
                    <div><label class="form-label">Molding</label><input type="text" wire:model="headMolding" class="form-input"></div>
                    <div><label class="form-label">Scalp</label><input type="text" wire:model="headScalp" class="form-input"></div>
                    <div><label class="form-label">Fontanelles</label><input type="text" wire:model="headFontanelles" class="form-input"></div>
                    <div><label class="form-label">Suture</label><input type="text" wire:model="headSuture" class="form-input"></div>
                    <div class="form-field-full"><label class="form-label">Face</label><input type="text" wire:model="face" class="form-input"></div>
                </div>

                {{-- Eyes --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">EYES</h5>
                <div class="form-grid">
                    <div><label class="form-label">Conjunctiva</label><input type="text" wire:model="eyesConjunctiva" class="form-input"></div>
                    <div><label class="form-label">Sclera</label><input type="text" wire:model="eyesSclera" class="form-input"></div>
                    <div><label class="form-label">Pupils</label><input type="text" wire:model="eyesPupils" class="form-input"></div>
                    <div><label class="form-label">Discharge</label><input type="text" wire:model="eyesDischarge" class="form-input"></div>
                </div>

                {{-- Ears / Nose / Mouth --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">EARS / NOSE / MOUTH</h5>
                <div class="form-grid">
                    <div><label class="form-label">Ears</label><input type="text" wire:model="ears" class="form-input"></div>
                    <div><label class="form-label">Nose</label><input type="text" wire:model="nose" class="form-input"></div>
                    <div><label class="form-label">Mouth - Lip</label><input type="text" wire:model="mouthLip" class="form-input"></div>
                    <div><label class="form-label">Mouth - Tongue</label><input type="text" wire:model="mouthTongue" class="form-input"></div>
                    <div><label class="form-label">Mouth - Palate</label><input type="text" wire:model="mouthPalate" class="form-input"></div>
                </div>

                {{-- Neck --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">NECK</h5>
                <div class="form-grid-2">
                    <div><label class="form-label">Sternocleidomastoid</label><input type="text" wire:model="neckSternocleidomastoid" class="form-input"></div>
                    <div><label class="form-label">Fistula / Other</label><input type="text" wire:model="neckFistula" class="form-input"></div>
                </div>

                {{-- Chest --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">CHEST</h5>
                <div class="form-grid">
                    <div><label class="form-label">Shape</label><input type="text" wire:model="chestShape" class="form-input"></div>
                    <div><label class="form-label">Respiration</label><input type="text" wire:model="chestRespiration" class="form-input"></div>
                    <div><label class="form-label">Clavicles</label><input type="text" wire:model="chestClavicles" class="form-input"></div>
                    <div><label class="form-label">Breast</label><input type="text" wire:model="chestBreast" class="form-input"></div>
                    <div class="form-field-half"><label class="form-label">Heart</label><input type="text" wire:model="chestHeart" class="form-input"></div>
                    <div class="form-field-half"><label class="form-label">Lungs</label><input type="text" wire:model="chestLungs" class="form-input"></div>
                </div>

                {{-- Abdomen --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">ABDOMEN</h5>
                <div class="form-grid">
                    <div class="form-field-full"><label class="form-label">Abdomen</label><textarea wire:model="abdomen" rows="2" class="form-input"></textarea></div>
                    <div><label class="form-label">Spleen</label><input type="text" wire:model="spleen" class="form-input"></div>
                    <div><label class="form-label">Kidneys</label><input type="text" wire:model="kidneys" class="form-input"></div>
                    <div><label class="form-label">Liver</label><input type="text" wire:model="liver" class="form-input"></div>
                    <div><label class="form-label">Umbilical Cord</label><input type="text" wire:model="umbilicalCord" class="form-input"></div>
                </div>

                {{-- Genitals --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">GENITALS</h5>
                <div class="form-grid-2">
                    <div><label class="form-label">Male (Testes)</label><input type="text" wire:model="genitalsMale" class="form-input"></div>
                    <div><label class="form-label">Female (Vaginal Bleeding)</label><input type="text" wire:model="genitalsFemale" class="form-input"></div>
                </div>

                {{-- Hernia --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">HERNIA</h5>
                <div class="form-grid-2">
                    <div><label class="form-label">Inguinal Hernia</label><input type="text" wire:model="inguinalHernia" class="form-input"></div>
                    <div><label class="form-label">Diastasis Recti</label><input type="text" wire:model="diastasisRecti" class="form-input"></div>
                </div>

                {{-- Extremities & Orthopaedic --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">EXTREMITIES & ORTHOPAEDIC</h5>
                <div class="form-grid">
                    <div class="form-field-full"><label class="form-label">Extremities</label><textarea wire:model="extremities" rows="2" class="form-input"></textarea></div>
                    <div><label class="form-label">Clubfoot</label><input type="text" wire:model="clubfoot" class="form-input"></div>
                    <div><label class="form-label">Hip Dislocation</label><input type="text" wire:model="hipDislocation" class="form-input"></div>
                    <div><label class="form-label">Femoral Pulse</label><input type="text" wire:model="femoralPulse" class="form-input"></div>
                    <div><label class="form-label">Spine</label><input type="text" wire:model="spine" class="form-input"></div>
                    <div><label class="form-label">Anus</label><input type="text" wire:model="anus" class="form-input"></div>
                </div>

                {{-- Impression --}}
                <h5 style="font-size: 0.7rem; font-weight: 700; margin: 16px 0 8px; color: #6b7280;">IMPRESSION / DIAGNOSIS</h5>
                <div class="form-field-full">
                    <textarea wire:model="impression" rows="3" class="form-input"></textarea>
                </div>
            </div>
        </div>

        {{-- SECTION 5: Doctor's Orders --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-title">📝 Doctor's Orders</span>
            </div>
            <div class="section-body">
                <textarea wire:model="orderText" rows="5" class="form-input" placeholder="Type one order per line..."></textarea>
                <p style="font-size: 0.7rem; color: #6b7280; margin-top: 8px;">Each line becomes a separate order for the nurse to carry out.</p>
            </div>
        </div>

        {{-- SECTION 6: ADMISSION DECISION (AT THE BOTTOM) --}}
        <div class="admission-section">
            <div class="admission-header">
                <span style="font-weight: 700;">🏥 Admission Decision</span>
            </div>
            <div class="admission-body">
                @if($isAdmitted)
                    <div style="background: #d1fae5; padding: 16px; border-radius: 8px; text-align: center;">
                        <p style="margin: 0; color: #065f46; font-weight: 600;">✓ This baby has already been admitted to NICU</p>
                        <p style="margin: 5px 0 0 0; font-size: 0.8rem; color: #065f46;">Admitted on: {{ $visit->doctor_admitted_at ? \Carbon\Carbon::parse($visit->doctor_admitted_at)->format('M d, Y h:i A') : '—' }}</p>
                    </div>
                @else
                    <div style="background: #fef3c7; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                        <p style="margin: 0; font-size: 0.85rem;">Complete the assessment above, then decide if this baby requires NICU admission.</p>
                    </div>
                    
                    <div style="background: #f0fdf4; border: 2px solid #86efac; border-radius: 10px; padding: 16px;">
                        <label class="checkbox-label">
                            <input type="checkbox" wire:model.live="admitToNICU" style="width: 20px; height: 20px; accent-color: #059669;">
                            <span style="font-weight: 700; font-size: 1rem; color: #065f46;">✅ ADMIT THIS BABY TO NICU</span>
                        </label>
                        @if($admitToNICU)
                        <div style="margin-top: 12px; padding: 8px 12px; background: #d1fae5; border-radius: 8px;">
                            <p style="margin: 0; font-size: 0.8rem; color: #065f46;">
                                ⚕️ When saved, this baby will be officially admitted to NICU. 
                                The status will change to "Admitted" and the baby will appear in the Admitted Patients list.
                            </p>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Buttons --}}
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-bottom: 40px;">
            <button type="button" onclick="window.location.href='/doctor/nicu-babies'" class="btn-secondary">Cancel</button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    @if($isAdmitted) 💾 Update Assessment
                    @elseif($admitToNICU) ✅ Admit & Save
                    @else 💾 Save Assessment
                    @endif
                </span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </form>
</div>
</x-filament-panels::page>