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
        
        .observation-checkbox {
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        
        .observation-checkbox input {
            margin-right: 6px;
            width: 16px;
            height: 16px;
        }
        
        .observation-checkbox label {
            font-size: 0.8rem;
            cursor: pointer;
        }
        
        .warning-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
    </style>
    
    <div class="nicu-form-card">
        <div class="nicu-card-header">
            <h3>🩺 New Baby Arrival - Create Provisional Record</h3>
        </div>
        <div class="nicu-card-body">
            
            <div class="info-box">
                <p style="margin:0; font-size:0.85rem;">
                    <strong>📋 About this form:</strong> This creates a <strong>temporary record</strong> so you can start documenting care immediately.
                    The baby will receive a temporary ID (TEMP-YYYYMMDD-XXX). Complete the full registration after talking to the mother.
                </p>
            </div>
            
            <form wire:submit="save">
                
                <!-- Section 1: Required Information -->
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Required Information</h4>
                    
                    <div class="form-grid">
                        <div class="form-field-full">
                            <label class="form-label">Mother's Last Name <span style="color:#dc2626;">*</span></label>
                            <input type="text" 
                                   wire:model="formData.mother_last_name" 
                                   class="form-input"
                                   placeholder="e.g., Dela Cruz"
                                   autofocus>
                            @error('formData.mother_last_name') 
                                <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
                            @enderror
                            <p style="font-size:0.7rem; color:#6b7280; margin-top:4px;">This will be used as the baby's temporary identifier.</p>
                        </div>
                        
                        <div class="form-field">
                            <label class="form-label">Baby's Sex <span style="color:#dc2626;">*</span></label>
                            <select wire:model="formData.baby_sex" class="form-input">
                                <option value="">Select...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('formData.baby_sex') 
                                <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        <div class="form-field">
                            <label class="form-label">Birth Date & Time <span style="color:#dc2626;">*</span></label>
                            <input type="datetime-local" 
                                   wire:model="formData.birth_datetime" 
                                   class="form-input"
                                   min="{{ $birthDateTimeMin }}"
                                   max="{{ $birthDateTimeMax }}">
                            @error('formData.birth_datetime') 
                                <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Section 2: APGAR Scores -->
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">APGAR Scores</h4>
                    
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">1 Minute</label>
                            <input type="number" 
                                   wire:model="formData.apgar_1min" 
                                   min="0" max="10" 
                                   class="form-input"
                                   placeholder="0-10">
                        </div>
                        
                        <div class="form-field">
                            <label class="form-label">5 Minutes</label>
                            <input type="number" 
                                   wire:model="formData.apgar_5min" 
                                   min="0" max="10" 
                                   class="form-input"
                                   placeholder="0-10">
                        </div>
                        
                        <div class="form-field">
                            <label class="form-label">10 Minutes</label>
                            <input type="number" 
                                   wire:model="formData.apgar_10min" 
                                   min="0" max="10" 
                                   class="form-input"
                                   placeholder="0-10 (only if 5 min <7)">
                            <p style="font-size:0.65rem; color:#6b7280; margin-top:2px;">Required only if 5-minute APGAR is below 7</p>
                        </div>
                    </div>
                </div>
                
                <!-- Section 3: Birth Measurements -->
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Birth Measurements</h4>
                    
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Birth Weight (grams)</label>
                            <input type="number" 
                                   wire:model="formData.birth_weight_grams" 
                                   step="1" 
                                   class="form-input"
                                   placeholder="e.g., 2850">
                        </div>
                        
                        <div class="form-field">
                            <label class="form-label">Birth Length (cm)</label>
                            <input type="number" 
                                   wire:model="formData.birth_length_cm" 
                                   step="0.1" 
                                   class="form-input"
                                   placeholder="e.g., 48.5">
                        </div>
                    </div>
                </div>
                
                <!-- Section 4: Transfer Information -->
                <div style="margin-bottom: 24px;">
                    <div class="form-field-full">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 12px;">
                            <input type="checkbox" wire:model="formData.is_transfer" style="width: 18px; height: 18px;">
                            <span style="font-weight: 600;">This baby is transferred from another facility</span>
                        </label>
                        
                        @if($formData['is_transfer'])
                        <div class="form-field-half">
                            <label class="form-label">Referring Facility <span style="color:#dc2626;">*</span></label>
                            <input type="text" 
                                   wire:model="formData.referring_facility" 
                                   class="form-input"
                                   placeholder="e.g., Balaoan District Hospital">
                            @error('formData.referring_facility') 
                                <p class="text-danger text-xs mt-1" style="color:#dc2626; font-size:0.7rem;">{{ $message }}</p> 
                            @enderror
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Section 5: Observations -->
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Immediate Observations</h4>
                    
                    <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                        @foreach($availableObservations as $key => $label)
                        <label class="observation-checkbox">
                            <input type="checkbox" wire:model="formData.observations" value="{{ $key }}">
                            <span>{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Section 6: Baby's Name (if already known) -->
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; color:#1e3a5f;">Baby's Name (if already known)</h4>
                    <p style="font-size:0.75rem; color:#6b7280; margin-bottom:12px;">If the mother has already named the baby, you can enter it now. Otherwise, leave blank and complete later.</p>
                    
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Family Name</label>
                            <input type="text" wire:model="formData.baby_family_name" class="form-input" placeholder="e.g., Dela Cruz">
                        </div>
                        <div class="form-field">
                            <label class="form-label">First Name</label>
                            <input type="text" wire:model="formData.baby_first_name" class="form-input" placeholder="e.g., Maria">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Middle Name</label>
                            <input type="text" wire:model="formData.baby_middle_name" class="form-input" placeholder="Optional">
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <button type="button" wire:click="$dispatch('close')" class="btn-secondary" style="background:#f3f4f6; padding: 10px 24px; border-radius: 8px; border: 1px solid #d1d5db; cursor: pointer;">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>💾 Create Provisional Record</span>
                        <span wire:loading>Creating...</span>
                    </button>
                </div>
                
            </form>
        </div>
    </div>
    
    <div class="warning-box" style="margin-top: 16px;">
        <p style="margin:0; font-size:0.8rem;">
            <strong>⚠️ Important:</strong> This creates a <strong>TEMPORARY</strong> record with ID format <strong>TEMP-YYYYMMDD-XXX</strong>.
            The clerk must convert this to a permanent record (LUMC-YYYY-xxxxxx) on the next business day.
            All clinical data entered before conversion will remain linked to the permanent record.
        </p>
    </div>
    
</x-filament-panels::page>