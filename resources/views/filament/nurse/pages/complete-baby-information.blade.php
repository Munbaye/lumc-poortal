<x-filament-panels::page>
    
    <style>
        .nicu-form-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            margin-bottom: 24px;
        }
        
        .dark .nicu-form-card {
            background: #1f2937;
            border-color: #374151;
        }
        
        .nicu-card-header {
            background: #f0fdf4;
            border-bottom: 1px solid #bbf7d0;
            padding: 14px 20px;
        }
        
        .dark .nicu-card-header {
            background: #064e3b;
            border-color: #065f46;
        }
        
        .nicu-card-header h3 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            color: #166534;
        }
        
        .dark .nicu-card-header h3 {
            color: #86efac;
        }
        
        .nicu-card-body {
            padding: 20px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        
        .form-field-full {
            grid-column: span 4;
        }
        
        .form-field-half {
            grid-column: span 2;
        }
        
        .form-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .form-input {
            width: 100%;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.875rem;
            border: 1px solid #d1d5db;
            background: #fff;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        select.form-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            cursor: pointer;
        }
        
        .dark .form-input {
            background-color: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .dark select.form-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239ca3af' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-color: #374151;
        }
        
        .form-input:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.1);
        }
        
        textarea.form-input {
            resize: vertical;
            min-height: 80px;
        }

        /* ── Select wrapper: single custom arrow only ── */
        .select-wrapper {
            position: relative;
            display: block;
        }

        .select-wrapper::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid #6b7280;
            pointer-events: none;
            z-index: 1;
        }

        .select-wrapper select {
            width: 100%;
            border-radius: 8px;
            padding: 10px 32px 10px 12px;
            font-size: 0.875rem;
            border: 1px solid #d1d5db;
            background: #fff;
            background-image: none;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }

        .dark .select-wrapper select {
            background: #374151;
            background-image: none;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .select-wrapper select:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1e3a5f, #1d4ed8);
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            opacity: 0.9;
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
        }
        
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .section-divider {
            border-top: 1px solid #e5e7eb;
            margin: 20px 0;
        }

        .section-title {
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: #1e3a5f;
        }

        .checkbox-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: flex-start;
        }

        .checkbox-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            color: #374151;
            font-weight: 500;
        }

        .checkbox-label input[type="checkbox"] {
            width: 15px;
            height: 15px;
            cursor: pointer;
            accent-color: #1d4ed8;
            flex-shrink: 0;
        }

        .checkbox-detail-input {
            margin-top: 2px;
            width: 220px;
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 0.8rem;
            border: 1px solid #d1d5db;
            background: #fff;
            outline: none;
        }

        .dark .checkbox-detail-input {
            background: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .checkbox-detail-input:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 2px rgba(29, 78, 216, 0.1);
        }
        
        .required-star {
            color: #dc2626;
        }

        .hint-text {
            font-size: 0.7rem;
            color: #6b7280;
            margin-top: 3px;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.7rem;
            margin-top: 3px;
        }
    </style>
    
    <div class="nicu-form-card">
        <div class="nicu-card-header">
            <h3>📋 Complete Baby Information - NUR-022-0 (Maternal/Prenatal History)</h3>
        </div>
        <div class="nicu-card-body">
            
            <div class="info-box">
                <p style="margin:0; font-size:0.8rem;">
                    <strong>👶 Temporary ID:</strong> {{ $baby->temporary_case_no ?? '—' }}<br>
                    <strong>📅 Birth Date/Time:</strong> {{ $baby->birth_datetime ? \Carbon\Carbon::parse($baby->birth_datetime)->format('M d, Y h:i A') : '—' }}<br>
                    <strong>⚥ Sex:</strong> {{ $baby->sex ?? '—' }}
                </p>
            </div>
            
            <form wire:submit="save">
                
                {{-- ── Section 1: Baby's Official Name ─────────────────────────── --}}
                <p class="section-title">Baby's Official Name</p>
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Family Name <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.baby_family_name" 
                               class="form-input"
                               placeholder="e.g., Dela Cruz">
                        @error('formData.baby_family_name') 
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">First Name <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.baby_first_name" 
                               class="form-input"
                               placeholder="e.g., Maria">
                        @error('formData.baby_first_name') 
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Middle Name</label>
                        <input type="text" 
                               wire:model="formData.baby_middle_name" 
                               class="form-input"
                               placeholder="Optional">
                    </div>
                </div>
                
                <div class="section-divider"></div>
                
                {{-- ── Section 2: Mother's Information ─────────────────────────── --}}
                <p class="section-title">Mother's Information</p>
                <p class="hint-text" style="margin-bottom:12px;">This information is for reference only. The mother is NOT created as a patient record.</p>
                
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Mother's Family Name <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.mother_family_name" 
                               class="form-input"
                               placeholder="e.g., Dela Cruz">
                        @error('formData.mother_family_name') 
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Mother's First Name <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.mother_first_name" 
                               class="form-input"
                               placeholder="e.g., Maria">
                        @error('formData.mother_first_name') 
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Mother's Middle Name</label>
                        <input type="text" 
                               wire:model="formData.mother_middle_name" 
                               class="form-input"
                               placeholder="Optional">
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Mother's Age</label>
                        <input type="number" 
                               wire:model="formData.mother_age" 
                               class="form-input"
                               placeholder="e.g., 28"
                               min="12" max="60">
                    </div>
                    
                    <div class="form-field-full">
                        <label class="form-label">Mother's Complete Address <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.mother_address_full" 
                               class="form-input"
                               placeholder="e.g., Brgy. Poblacion, Agoo, La Union">
                        @error('formData.mother_address_full') 
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Mother's Contact Number <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.mother_contact" 
                               class="form-input"
                               placeholder="e.g., 09123456789">
                        @error('formData.mother_contact') 
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="section-divider"></div>
                
                {{-- ── Section 3: Obstetric History ────────────────────────────── --}}
                <p class="section-title">Obstetric History</p>
                
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Gravida (Total Pregnancies)</label>
                        <input type="number" 
                               wire:model="formData.mother_gravida" 
                               class="form-input"
                               placeholder="e.g., 2"
                               min="0" max="20">
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Para (Deliveries ≥20 weeks)</label>
                        <input type="number" 
                               wire:model="formData.mother_para" 
                               class="form-input"
                               placeholder="e.g., 1"
                               min="0" max="20">
                    </div>
                </div>
                
                <div class="section-divider"></div>
                
                {{-- ── Section 4: Prenatal Care ─────────────────────────────────── --}}
                <p class="section-title">Prenatal Care</p>
                
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Prenatal Checkup Site</label>
                        <div class="select-wrapper">
                            <select wire:model="formData.prenatal_checkup_site">
                                <option value="">Select...</option>
                                <option value="LUMC">LUMC</option>
                                <option value="Health Center">Health Center</option>
                                <option value="Private Clinic">Private Clinic</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Number of Prenatal Visits</label>
                        <input type="number" 
                               wire:model="formData.prenatal_visit_count" 
                               class="form-input"
                               placeholder="e.g., 8"
                               min="0" max="50">
                    </div>
                </div>
                
                <div class="section-divider"></div>
                
                {{-- ── Section 5: Maternal History ─────────────────────────────── --}}
                <p class="section-title">Maternal History</p>
                
                <div class="form-grid">
                    <div class="form-field-full">
                        <label class="form-label">Maternal Medical History (Chronic conditions, OB complications)</label>
                        <textarea wire:model="formData.maternal_history" 
                                  class="form-input"
                                  placeholder="e.g., GDM, Hypertension, Previous preterm delivery, etc."></textarea>
                    </div>
                    
                    <div class="form-field-full">
                        <label class="form-label">Maternal Signs/Symptoms during pregnancy</label>
                        <textarea wire:model="formData.maternal_signs_symptoms" 
                                  class="form-input"
                                  placeholder="e.g., Fever, Bleeding, Edema, etc."></textarea>
                    </div>
                </div>
                
                <div class="section-divider"></div>
                
                {{-- ── Section 6: Pregnancy Interventions ──────────────────────── --}}
                <p class="section-title">Pregnancy Interventions</p>

                <div class="checkbox-row">

                    {{-- Took Multivitamins --}}
                    <div class="checkbox-item">
                        <label class="checkbox-label">
                            <input type="checkbox" wire:model.live="formData.took_multivitamins">
                            Took Multivitamins
                        </label>
                        @if($formData['took_multivitamins'])
                        <input type="text"
                               wire:model="formData.multivitamins_details"
                               class="checkbox-detail-input"
                               placeholder="e.g., Folic acid, Iron, Calcium">
                        @endif
                    </div>

                    {{-- Had Ultrasound --}}
                    <div class="checkbox-item">
                        <label class="checkbox-label">
                            <input type="checkbox" wire:model.live="formData.had_ultrasound">
                            Had Ultrasound
                        </label>
                        @if($formData['had_ultrasound'])
                        <input type="text"
                               wire:model="formData.ultrasound_details"
                               class="checkbox-detail-input"
                               placeholder="e.g., AOG 28 weeks, normal">
                        @endif
                    </div>

                    {{-- Had Preterm Labor --}}
                    <div class="checkbox-item">
                        <label class="checkbox-label">
                            <input type="checkbox" wire:model.live="formData.had_preterm_labor">
                            Had Preterm Labor
                        </label>
                        @if($formData['had_preterm_labor'])
                        <input type="text"
                               wire:model="formData.steroids_given"
                               class="checkbox-detail-input"
                               placeholder="e.g., Betamethasone 12mg x 2 doses">
                        @endif
                    </div>

                </div>
                
                {{-- ── Submit ───────────────────────────────────────────────────── --}}
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <button type="button" onclick="window.history.back()" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled" @if($isReadOnly) disabled @endif>
                        <span wire:loading.remove>💾 {{ $this->getSubmitButtonLabel() }}</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
                
            </form>
        </div>
    </div>
    
    <div class="info-box" style="background:#fef3c7; border-left-color:#f59e0b; margin-top:16px;">
        <p style="margin:0; font-size:0.8rem;">
            <strong>⚠️ Note:</strong> After saving this information, the clerk will convert this provisional record to a permanent record (LUMC-YYYY-xxxxxx).
            All clinical data (vitals, medications, notes) will remain linked to the permanent record.
        </p>
    </div>
    
</x-filament-panels::page>