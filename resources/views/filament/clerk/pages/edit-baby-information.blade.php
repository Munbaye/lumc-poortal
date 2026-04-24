<x-filament-panels::page>
    
    <style>
        .form-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            margin-bottom: 24px;
        }
        
        .dark .form-card {
            background: #1f2937;
            border-color: #374151;
        }
        
        .form-card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 20px;
        }
        
        .dark .form-card-header {
            background: #111827;
            border-color: #374151;
        }
        
        .form-card-header h3 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            color: #1e3a5f;
        }
        
        .dark .form-card-header h3 {
            color: #93c5fd;
        }
        
        .form-card-body {
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
        
        .dark .form-label {
            color: #9ca3af;
        }
        
        .form-input {
            width: 100%;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.875rem;
            border: 1px solid #d1d5db;
            background: #fff;
            outline: none;
        }
        
        .dark .form-input {
            background: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }
        
        .form-input:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.1);
        }
        
        textarea.form-input {
            resize: vertical;
            min-height: 80px;
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
        
        .dark .btn-secondary {
            background: #374151;
            color: #e5e7eb;
            border-color: #4b5563;
        }
        
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .dark .info-box {
            background: #1e3a5f;
        }
        
        .section-divider {
            border-top: 1px solid #e5e7eb;
            margin: 20px 0;
        }
        
        .dark .section-divider {
            border-top-color: #374151;
        }
        
        .checkbox-group {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.85rem;
        }
        
        .checkbox-label input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        .required-star {
            color: #dc2626;
        }
    </style>
    
    <div class="form-card">
        <div class="form-card-header">
            <h3>✏️ Edit Baby Information</h3>
        </div>
        <div class="form-card-body">
            
            <div class="info-box">
                <p style="margin:0; font-size:0.85rem;">
                    <strong>👶 Temporary ID:</strong> {{ $baby->temporary_case_no ?? '—' }}<br>
                    <strong>📅 Birth Date/Time:</strong> {{ $baby->birth_datetime ? \Carbon\Carbon::parse($baby->birth_datetime)->format('M d, Y h:i A') : '—' }}<br>
                    <strong>⚥ Sex:</strong> {{ $baby->sex ?? '—' }}
                </p>
            </div>
            
            <form wire:submit="save">
                
                <!-- Section 1: Baby's Official Name -->
                <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Baby's Official Name</h4>
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Family Name <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.baby_family_name" 
                               class="form-input"
                               placeholder="e.g., Dela Cruz">
                        @error('formData.baby_family_name') 
                            <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">First Name <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.baby_first_name" 
                               class="form-input"
                               placeholder="e.g., Maria">
                        @error('formData.baby_first_name') 
                            <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
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
                
                <!-- Section 2: Mother's Information -->
                <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Mother's Information</h4>
                <p style="font-size:0.75rem; color:#6b7280; margin-bottom:12px;">This information is for reference only. The mother is NOT created as a patient record.</p>
                
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Mother's Family Name <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.mother_family_name" 
                               class="form-input"
                               placeholder="e.g., Dela Cruz">
                        @error('formData.mother_family_name') 
                            <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Mother's First Name <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.mother_first_name" 
                               class="form-input"
                               placeholder="e.g., Maria">
                        @error('formData.mother_first_name') 
                            <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
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
                            <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
                        @enderror
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Mother's Contact Number <span class="required-star">*</span></label>
                        <input type="text" 
                               wire:model="formData.mother_contact" 
                               class="form-input"
                               placeholder="e.g., 09123456789">
                        @error('formData.mother_contact') 
                            <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>
                
                <div class="section-divider"></div>
                
                <!-- Section 3: Obstetric History -->
                <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Obstetric History</h4>
                
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
                
                <!-- Section 4: Prenatal Care -->
                <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Prenatal Care</h4>
                
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Prenatal Checkup Site</label>
                        <select wire:model="formData.prenatal_checkup_site" class="form-input">
                            <option value="">Select...</option>
                            <option value="LUMC">LUMC</option>
                            <option value="Health Center">Health Center</option>
                            <option value="Private Clinic">Private Clinic</option>
                            <option value="None">None</option>
                        </select>
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
                
                <!-- Section 5: Maternal History -->
                <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Maternal History</h4>
                
                <div class="form-grid">
                    <div class="form-field-full">
                        <label class="form-label">Maternal Medical History</label>
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
                
                <!-- Section 6: Pregnancy Interventions -->
                <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Pregnancy Interventions</h4>
                
                <div class="form-field-full">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" wire:model="formData.took_multivitamins">
                            <span>Took Multivitamins</span>
                        </label>
                        
                        <label class="checkbox-label">
                            <input type="checkbox" wire:model="formData.had_ultrasound">
                            <span>Had Ultrasound</span>
                        </label>
                        
                        <label class="checkbox-label">
                            <input type="checkbox" wire:model="formData.had_preterm_labor">
                            <span>Had Preterm Labor</span>
                        </label>
                    </div>
                </div>
                
                @if($formData['had_preterm_labor'])
                <div class="form-field-half" style="margin-top: 12px;">
                    <label class="form-label">Steroids Given</label>
                    <input type="text" 
                           wire:model="formData.steroids_given" 
                           class="form-input"
                           placeholder="e.g., Betamethasone 12mg x 2 doses">
                </div>
                @endif
                
                <!-- Submit Buttons -->
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <button type="button" onclick="window.history.back()" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>💾 Save Changes</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
                
            </form>
        </div>
    </div>
    
</x-filament-panels::page>