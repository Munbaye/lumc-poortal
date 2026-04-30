<x-filament-panels::page>
    
    <style>
        .review-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            margin-bottom: 24px;
        }
        
        .dark .review-card {
            background: #1f2937;
            border-color: #374151;
        }
        
        .review-card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 20px;
        }
        
        .dark .review-card-header {
            background: #111827;
            border-color: #374151;
        }
        
        .review-card-header h3 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            color: #1e3a5f;
        }
        
        .dark .review-card-header h3 {
            color: #93c5fd;
        }
        
        .review-card-body {
            padding: 20px;
        }
        
        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .dark .info-row {
            border-bottom-color: #374151;
        }
        
        .info-label {
            width: 180px;
            font-weight: 600;
            color: #6b7280;
            font-size: 0.8rem;
        }
        
        .dark .info-label {
            color: #9ca3af;
        }
        
        .info-value {
            flex: 1;
            font-size: 0.85rem;
            color: #111827;
        }
        
        .dark .info-value {
            color: #e5e7eb;
        }
        
        .section-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1e3a5f;
            margin: 16px 0 12px 0;
            padding-bottom: 6px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .dark .section-title {
            color: #93c5fd;
            border-bottom-color: #374151;
        }
        
        .badge-provisional {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .dark .badge-provisional {
            background: #451a03;
            color: #fde68a;
        }
        
        .badge-permanent {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .dark .badge-permanent {
            background: #064e3b;
            color: #86efac;
        }
        
        .btn-success {
            background: #16a34a;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        
        .btn-success:hover {
            background: #15803d;
        }
        
        .btn-warning {
            background: #f59e0b;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        
        .btn-warning:hover {
            background: #d97706;
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
            transition: all 0.2s;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        
        .dark .btn-secondary {
            background: #374151;
            color: #e5e7eb;
            border-color: #4b5563;
        }
        
        .dark .btn-secondary:hover {
            background: #4b5563;
        }
        
        .info-box-blue {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .dark .info-box-blue {
            background: #1e3a5f;
        }
        
        .info-box-yellow {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .dark .info-box-yellow {
            background: #451a03;
        }
        
        .info-box-green {
            background: #d1fae5;
            border-left: 4px solid #16a34a;
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .dark .info-box-green {
            background: #064e3b;
        }
    </style>
    
    <div class="review-card">
        <div class="review-card-header">
            <h3>📝 Review & Convert Provisional Record</h3>
        </div>
        <div class="review-card-body">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <span class="badge-provisional">⚠️ Provisional Record</span>
                    <span style="margin-left: 10px; font-size:0.8rem; color:#6b7280;">
                        Temporary ID: {{ $baby?->temporary_case_no ?? '—' }}
                    </span>
                </div>
                <div>
                    <span style="font-size:0.8rem; color:#6b7280;">
                        Created: {{ $baby?->created_at ? $baby->created_at->format('M d, Y h:i A') : '—' }}
                    </span>
                </div>
            </div>
            
            <!-- After conversion, this will show -->
            @if($baby && !$baby->is_provisional)
            <div class="info-box-green">
                <p style="margin: 0; font-weight: 700; color: #065f46;">✓ This record has been converted to permanent</p>
                <p style="margin: 5px 0 0 0; font-size: 0.8rem; color: #065f46;">Case Number: {{ $baby->case_no }}</p>
            </div>
            @endif
            
            <!-- Baby Information -->
            <div class="section-title">👶 Baby Information</div>
            <div class="info-row">
                <div class="info-label">Baby's Full Name:</div>
                <div class="info-value">
                    @if($baby)
                        {{ $baby->baby_family_name ?? '—' }}, 
                        {{ $baby->baby_first_name ?? '—' }} 
                        {{ $baby->baby_middle_name ? $baby->baby_middle_name : '' }}
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Sex:</div>
                <div class="info-value">{{ $baby?->sex ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Birth Date/Time:</div>
                <div class="info-value">{{ $baby?->birth_datetime ? \Carbon\Carbon::parse($baby->birth_datetime)->format('M d, Y h:i A') : '—' }}</div>
            </div>
            
            @if($nicuAdmission)
            <div class="info-row">
                <div class="info-label">Birth Weight:</div>
                <div class="info-value">{{ $nicuAdmission->birth_weight_grams ? number_format($nicuAdmission->birth_weight_grams) . ' g' : '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">APGAR Scores:</div>
                <div class="info-value">{{ $nicuAdmission->apgar_display ?? '—' }}</div>
            </div>
            @endif
            
            <!-- Mother Information -->
            <div class="section-title">👩 Mother Information (Reference Only)</div>
            <div class="info-row">
                <div class="info-label">Mother's Full Name:</div>
                <div class="info-value">
                    @if($baby)
                        {{ $baby->mother_first_name ?? '—' }} 
                        {{ $baby->mother_middle_name ? $baby->mother_middle_name : '' }} 
                        {{ $baby->mother_family_name ?? '—' }}
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Mother's Age:</div>
                <div class="info-value">{{ $baby?->mother_age ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Mother's Address:</div>
                <div class="info-value">{{ $baby?->mother_address_full ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Mother's Contact:</div>
                <div class="info-value">{{ $baby?->mother_contact ?? '—' }}</div>
            </div>
            
            @if($nicuAdmission)
            <!-- Obstetric History -->
            <div class="section-title">📊 Obstetric History</div>
            <div class="info-row">
                <div class="info-label">Gravida / Para:</div>
                <div class="info-value">{{ $nicuAdmission->mother_gravida ?? '—' }} / {{ $nicuAdmission->mother_para ?? '—' }}</div>
            </div>
            
            <!-- Prenatal Care -->
            <div class="section-title">🏥 Prenatal Care</div>
            <div class="info-row">
                <div class="info-label">Checkup Site:</div>
                <div class="info-value">{{ $nicuAdmission->prenatal_checkup_site ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Number of Visits:</div>
                <div class="info-value">{{ $nicuAdmission->prenatal_visit_count ?? '—' }}</div>
            </div>
            
            <!-- Maternal History -->
            <div class="section-title">📋 Maternal History</div>
            <div class="info-row">
                <div class="info-label">Medical History:</div>
                <div class="info-value">{{ $nicuAdmission->maternal_history ?: '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Signs/Symptoms:</div>
                <div class="info-value">{{ $nicuAdmission->maternal_signs_symptoms ?: '—' }}</div>
            </div>
            
            <!-- Pregnancy Interventions -->
            <div class="section-title">💊 Pregnancy Interventions</div>
            <div class="info-row">
                <div class="info-label">Multivitamins:</div>
                <div class="info-value">{{ $nicuAdmission->took_multivitamins ? 'Yes' : 'No' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Ultrasound:</div>
                <div class="info-value">{{ $nicuAdmission->had_ultrasound ? 'Yes' : 'No' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Preterm Labor:</div>
                <div class="info-value">{{ $nicuAdmission->had_preterm_labor ? 'Yes' : 'No' }}</div>
            </div>
            @if($nicuAdmission->had_preterm_labor)
            <div class="info-row">
                <div class="info-label">Steroids Given:</div>
                <div class="info-value">{{ $nicuAdmission->steroids_given ?: '—' }}</div>
            </div>
            @endif
            @endif
            
            <!-- Edit Button (always show for provisional records) -->
            @if($baby && $baby->is_provisional)
            <div style="display: flex; justify-content: flex-start; margin: 20px 0 10px 0;">
                <button type="button" wire:click="editBabyInfo" class="btn-warning">
                    ✏️ Edit Baby Information
                </button>
                <span style="margin-left: 12px; font-size:0.7rem; color:#6b7280; align-self: center;">
                    Click to edit/correct any information before converting
                </span>
            </div>
            @endif
            
            <!-- Conversion Warning & Buttons -->
            @if($baby && $baby->is_provisional)
            <div class="info-box-yellow">
                <p style="margin: 0 0 8px 0; font-weight: 700; color: #92400e;">⚠️ Before Converting</p>
                <p style="margin: 0; font-size: 0.8rem; color: #92400e;">
                    This will generate a permanent case number (LUMC-YYYY-xxxxxx) and convert this provisional record.
                    All clinical data (vitals, medications, nurse's notes, doctor's orders) will remain linked.
                    This action cannot be undone.
                </p>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                <button type="button" onclick="window.location.href='/clerk/visits?tab=provisional'" class="btn-secondary">
                    ← Back to List
                </button>
                <button type="button" wire:click="convert" class="btn-success" wire:loading.attr="disabled">
                    <span wire:loading.remove>✅ Convert to Permanent Record</span>
                    <span wire:loading>Converting...</span>
                </button>
            </div>
            @elseif($baby && !$baby->is_provisional)
            <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="window.location.href='/clerk/visits'" class="btn-secondary">
                    ← Back to Visits
                </button>
            </div>
            @endif
            
        </div>
    </div>
    
</x-filament-panels::page>