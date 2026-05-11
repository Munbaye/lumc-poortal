<x-filament-panels::page>
<style>
    .ob-container { max-width: 1300px; margin: 0 auto; }
    .ob-sec { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .dark .ob-sec { background: #1f2937; border-color: #374151; }
    .ob-sec-head { background: #fdf2f8; border-bottom: 1px solid #fce7f3; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
    .dark .ob-sec-head { background: #4a044e; border-color: #86198f; }
    .ob-sec-title { font-size: 0.85rem; font-weight: 700; color: #9d174d; }
    .dark .ob-sec-title { color: #f0abfc; }
    .ob-sec-body { padding: 16px 20px; }

    .fg  { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
    .fg3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .fg2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .fg6 { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; }
    .cf  { grid-column: span 4; }
    .c2  { grid-column: span 2; }
    .c3  { grid-column: span 3; }

    .form-label { display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; margin-bottom: 5px; }
    .form-input { width: 100%; border-radius: 8px; padding: 9px 11px; font-size: 0.875rem; border: 1px solid #d1d5db; background: #fff; outline: none; box-sizing: border-box; }
    .form-input:focus { border-color: #db2777; box-shadow: 0 0 0 3px rgba(219,39,119,.1); }
    .dark .form-input { background: #374151; border-color: #4b5563; color: #f3f4f6; }
    textarea.form-input { resize: vertical; min-height: 60px; }
    .select-wrapper { position: relative; }
    .select-wrapper::after { content:''; position:absolute; right:10px; top:50%; transform:translateY(-50%); width:0; height:0; border-left:5px solid transparent; border-right:5px solid transparent; border-top:6px solid #6b7280; pointer-events:none; }
    .select-wrapper select { width:100%; border-radius:8px; padding:9px 30px 9px 11px; font-size:0.875rem; border:1px solid #d1d5db; background:#fff; outline:none; appearance:none; cursor:pointer; }
    .dark .select-wrapper select { background:#374151; border-color:#4b5563; color:#f3f4f6; }

    .sub-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; margin: 14px 0 8px; }
    .divider { border: none; border-top: 1px solid #fce7f3; margin: 16px 0; }
    .dark .divider { border-color: #374151; }

    /* Checkbox group */
    .cb-group { display: flex; flex-wrap: wrap; gap: 8px 16px; }
    .cb-item { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #374151; cursor: pointer; }
    .dark .cb-item { color: #e2e8f0; }
    .cb-item input[type="checkbox"] { width: 14px; height: 14px; accent-color: #db2777; }

    /* Previous pregnancies table */
    .pp-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    .pp-table th { background: #9d174d; color: #fff; padding: 6px 10px; text-align: left; font-size: 0.72rem; border: 1px solid #7e1444; }
    .pp-table td { padding: 5px 6px; border: 1px solid #e5e7eb; vertical-align: middle; }
    .dark .pp-table td { border-color: #374151; }
    .pp-table tr:nth-child(even) td { background: #fdf2f8; }
    .dark .pp-table tr:nth-child(even) td { background: #1a1825; }
    .pp-input { width: 100%; border: none; border-bottom: 1px solid #d1d5db; padding: 4px 4px; font-size: 0.8rem; background: transparent; outline: none; }
    .dark .pp-input { color: #f3f4f6; border-color: #4b5563; }
    .pp-input:focus { border-color: #db2777; }
    .btn-remove-row { background: #fee2e2; color: #991b1b; border: none; border-radius: 5px; padding: 3px 8px; font-size: 0.72rem; cursor: pointer; }
    .btn-add-row { background: #fdf2f8; color: #9d174d; border: 1px solid #f9a8d4; border-radius: 6px; padding: 6px 14px; font-size: 0.8rem; font-weight: 600; cursor: pointer; margin-top: 8px; }
    .btn-add-row:hover { background: #fce7f3; }

    .btn-primary { background: linear-gradient(135deg,#9d174d,#db2777); color:#fff; border:none; padding:12px 28px; border-radius:8px; font-size:0.9rem; font-weight:700; cursor:pointer; }
    .btn-primary:hover { opacity:.9; }
    .btn-secondary { background:#f3f4f6; color:#374151; border:1px solid #d1d5db; padding:10px 24px; border-radius:8px; font-size:0.85rem; font-weight:500; cursor:pointer; }
</style>

<div class="ob-container">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div style="background:linear-gradient(135deg,#9d174d,#db2777);border-radius:12px;padding:14px 22px;margin-bottom:20px;">
        <p style="color:#fff;font-size:1rem;font-weight:700;margin:0;">
            OB Record — {{ $visit->patient->display_name ?? $visit->patient->full_name }}
        </p>
        <p style="color:#fbcfe8;font-size:0.75rem;margin:3px 0 0;">
            {{ $visit->patient->case_no ?? $visit->patient->temporary_case_no }}
            &nbsp;|&nbsp; Admitted: {{ $visit->doctor_admitted_at ? \Carbon\Carbon::parse($visit->doctor_admitted_at)->format('M d, Y h:i A') : '—' }}
        </p>
    </div>

    <form wire:submit="save">

        {{-- ── Section 1: Obstetric History (G/P/T/A/L) ──────────────────── --}}
        <div class="ob-sec">
            <div class="ob-sec-head"><span class="ob-sec-title">📋 Obstetric History</span></div>
            <div class="ob-sec-body">
                <div class="fg6">
                    <div><label class="form-label">Gravida</label><input type="number" min="0" max="20" wire:model="gravida" class="form-input"></div>
                    <div><label class="form-label">Para</label><input type="number" min="0" max="20" wire:model="para" class="form-input"></div>
                    <div><label class="form-label">Term</label><input type="number" min="0" max="20" wire:model="term" class="form-input"></div>
                    <div><label class="form-label">Preterm</label><input type="number" min="0" max="20" wire:model="preterm" class="form-input"></div>
                    <div><label class="form-label">Abortion</label><input type="number" min="0" max="20" wire:model="abortion" class="form-input"></div>
                    <div><label class="form-label">Living</label><input type="number" min="0" max="20" wire:model="living" class="form-input"></div>
                </div>

                <hr class="divider">
                <p class="sub-title">Menstrual History</p>
                <div class="fg">
                    <div><label class="form-label">Menarche</label><input type="text" wire:model="menarche" class="form-input" placeholder="e.g. 13 yrs old"></div>
                    <div><label class="form-label">Cycle Interval</label><input type="text" wire:model="mensesInterval" class="form-input" placeholder="e.g. 28 days"></div>
                    <div><label class="form-label">Duration</label><input type="text" wire:model="mensesDuration" class="form-input" placeholder="e.g. 4-5 days"></div>
                    <div style="display:flex;align-items:center;gap:8px;padding-top:22px;">
                        <input type="checkbox" wire:model="dysmenorrhea" id="dysmen" style="width:15px;height:15px;accent-color:#db2777;">
                        <label for="dysmen" class="form-label" style="margin:0;cursor:pointer;">Dysmenorrhea</label>
                    </div>
                </div>

                <hr class="divider">
                <p class="sub-title">Prenatal Check-up</p>
                <div class="fg">
                    <div>
                        <label class="form-label">Check-up Site</label>
                        <div class="select-wrapper">
                            <select wire:model.live="prenatalCheckupType">
                                <option value="">Select...</option>
                                <option>LUMC</option>
                                <option>Health Center</option>
                                <option>Private Clinic</option>
                                <option>None</option>
                                <option>Others</option>
                            </select>
                        </div>
                    </div>
                    @if($prenatalCheckupType === 'Others')
                    <div>
                        <label class="form-label">Specify</label>
                        <input type="text" wire:model="prenatalCheckupOthers" class="form-input" placeholder="Specify site">
                    </div>
                    @endif
                    <div>
                        <label class="form-label">No. of Visits</label>
                        <input type="number" min="0" max="50" wire:model="prenatalVisitCount" class="form-input" placeholder="0">
                    </div>
                </div>

                <hr class="divider">
                <p class="sub-title">Past Medical & Family History</p>
                <div class="fg2">
                    <div><label class="form-label">Past Medical History</label><textarea rows="2" wire:model="pastMedicalHistory" class="form-input" placeholder="Hypertension, Diabetes, etc."></textarea></div>
                    <div><label class="form-label">Family History</label><textarea rows="2" wire:model="familyHistory" class="form-input" placeholder="Relevant family conditions"></textarea></div>
                </div>
            </div>
        </div>

        {{-- ── Section 2: Previous Pregnancies ───────────────────────────── --}}
        @if($gravida && $gravida > 1)
        <div class="ob-sec">
            <div class="ob-sec-head"><span class="ob-sec-title">🤰 Previous Pregnancies</span></div>
            <div class="ob-sec-body" style="overflow-x:auto;">
                <table class="pp-table">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>AOG / Term</th>
                            <th>Mode of Delivery</th>
                            <th>Delivery Date</th>
                            <th>Gender</th>
                            <th>Weight (g)</th>
                            <th>Complications</th>
                            <th style="width:36px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previousPregnancies as $idx => $row)
                        <tr wire:key="pp-{{ $idx }}">
                            <td style="text-align:center;font-weight:700;">{{ $row['gravida_order'] }}</td>
                            <td><input type="text" class="pp-input" wire:model="previousPregnancies.{{ $idx }}.aog_term" placeholder="e.g. 38 wks"></td>
                            <td><input type="text" class="pp-input" wire:model="previousPregnancies.{{ $idx }}.manner_of_delivery" placeholder="NSD / CS"></td>
                            <td><input type="date" class="pp-input" wire:model="previousPregnancies.{{ $idx }}.delivery_date"></td>
                            <td>
                                <select class="pp-input" wire:model="previousPregnancies.{{ $idx }}.gender">
                                    <option value="">—</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Unknown</option>
                                </select>
                            </td>
                            <td><input type="number" class="pp-input" wire:model="previousPregnancies.{{ $idx }}.weight_grams" placeholder="grams" min="0" max="7000"></td>
                            <td><input type="text" class="pp-input" wire:model="previousPregnancies.{{ $idx }}.complications" placeholder="None / specify"></td>
                            <td style="text-align:center;">
                                <button type="button" class="btn-remove-row" wire:click="removePregnancyRow({{ $idx }})">✕</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn-add-row" wire:click="addPregnancyRow">+ Add Row</button>
            </div>
        </div>
        @endif

        {{-- ── Section 3: Present Pregnancy ──────────────────────────────── --}}
        <div class="ob-sec">
            <div class="ob-sec-head"><span class="ob-sec-title">📅 Present Pregnancy</span></div>
            <div class="ob-sec-body">
                <div class="fg">
                    <div><label class="form-label">LMP</label><input type="date" wire:model="lmp" class="form-input"></div>
                    <div><label class="form-label">PMP</label><input type="date" wire:model="pmp" class="form-input"></div>
                    <div><label class="form-label">EDC</label><input type="date" wire:model="edc" class="form-input"></div>
                    <div><label class="form-label">AOG</label><input type="text" wire:model="aog" class="form-input" placeholder="e.g. 38 wks 2 days"></div>
                    <div><label class="form-label">Quickening Date</label><input type="date" wire:model="quickeningDate" class="form-input"></div>
                </div>

                <hr class="divider">
                <p class="sub-title">Symptoms</p>
                <div class="fg">
                    <div>
                        <label class="form-label">Morning Sickness</label>
                        <div class="select-wrapper">
                            <select wire:model="morningSickness">
                                <option value="">Select...</option>
                                <option>None</option><option>Mild</option><option>Moderate</option><option>Severe</option>
                            </select>
                        </div>
                    </div>
                    <div class="c3">
                        <label class="form-label">Abnormal Symptoms</label>
                        <div class="cb-group" style="margin-top:4px;">
                            @foreach($availableSymptoms as $sym)
                            <label class="cb-item">
                                <input type="checkbox" wire:model="abnormalSymptoms" value="{{ $sym }}">
                                <span>{{ $sym }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Edema</label>
                        <div class="cb-group" style="margin-top:4px;">
                            @foreach($edemaOptions as $opt)
                            <label class="cb-item">
                                <input type="checkbox" wire:model="edema" value="{{ $opt }}">
                                <span>{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="c3">
                        <label class="form-label">Other Symptoms / Notes</label>
                        <textarea rows="2" wire:model="otherSymptoms" class="form-input" placeholder="Describe other symptoms..."></textarea>
                    </div>
                </div>

                <hr class="divider">
                <p class="sub-title">Contractions</p>
                <div class="fg">
                    <div><label class="form-label">Frequency</label><input type="text" wire:model="contractionFrequency" class="form-input" placeholder="e.g. every 5 min"></div>
                    <div><label class="form-label">Duration</label><input type="text" wire:model="contractionDuration" class="form-input" placeholder="e.g. 40 seconds"></div>
                    <div style="display:flex;align-items:center;gap:8px;padding-top:22px;">
                        <input type="checkbox" wire:model="bog" id="bog" style="width:15px;height:15px;accent-color:#db2777;">
                        <label for="bog" class="form-label" style="margin:0;cursor:pointer;">Bag of Water (BOW)</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Section 4: Physical Examination ────────────────────────────── --}}
        <div class="ob-sec">
            <div class="ob-sec-head"><span class="ob-sec-title">🩺 Physical Examination</span></div>
            <div class="ob-sec-body">
                <p class="sub-title">General Condition</p>
                <div class="fg3">
                    <div><label class="form-label">Sensorium</label><input type="text" wire:model="conditionConscious" class="form-input" placeholder="e.g. Conscious, coherent"></div>
                    <div><label class="form-label">Strength</label><input type="text" wire:model="conditionStrength" class="form-input" placeholder="e.g. Good"></div>
                    <div><label class="form-label">Ambulatory</label><input type="text" wire:model="conditionAmbulatory" class="form-input" placeholder="e.g. Ambulatory"></div>
                </div>

                <hr class="divider">
                <p class="sub-title">Systems</p>
                <div class="fg">
                    <div><label class="form-label">HEENT</label><input type="text" wire:model="heent" class="form-input"></div>
                    <div><label class="form-label">Skin</label><input type="text" wire:model="skin" class="form-input"></div>
                    <div><label class="form-label">Heart</label><input type="text" wire:model="heart" class="form-input"></div>
                    <div><label class="form-label">Lungs</label><input type="text" wire:model="lungs" class="form-input"></div>
                    <div class="cf"><label class="form-label">Abdomen</label><textarea rows="2" wire:model="abdomen" class="form-input"></textarea></div>
                </div>

                <hr class="divider">
                <p class="sub-title">Fundic / Fetal Assessment</p>
                <div class="fg">
                    <div><label class="form-label">Fundic Height</label><input type="text" wire:model="fundicHeight" class="form-input" placeholder="e.g. 34 cm"></div>
                    <div><label class="form-label">Fetal Presentation</label><input type="text" wire:model="fetalPresentation" class="form-input" placeholder="e.g. Cephalic"></div>
                    <div><label class="form-label">Fetal Position</label><input type="text" wire:model="fetalPosition" class="form-input" placeholder="e.g. LOA"></div>
                    <div><label class="form-label">FHT (bpm)</label><input type="text" wire:model="fetalHeartTone" class="form-input" placeholder="e.g. 140 bpm"></div>
                    <div><label class="form-label">Engagement</label><input type="text" wire:model="engagement" class="form-input" placeholder="e.g. Engaged"></div>
                </div>

                <hr class="divider">
                <p class="sub-title">Internal Examination (IE) — Update</p>
                <div class="fg">
                    <div><label class="form-label">Cervical Dilation</label><input type="text" wire:model="ieCervicalDilation" class="form-input" placeholder="e.g. 6 cm"></div>
                    <div><label class="form-label">Effacement</label><input type="text" wire:model="ieEffacement" class="form-input" placeholder="e.g. 80%"></div>
                    <div><label class="form-label">Station</label><input type="text" wire:model="ieStation" class="form-input" placeholder="e.g. 0"></div>
                    <div><label class="form-label">Membranes</label><input type="text" wire:model="ieMembranes" class="form-input" placeholder="e.g. Ruptured"></div>
                    <div><label class="form-label">Presentation</label><input type="text" wire:model="iePresentation" class="form-input"></div>
                    <div class="c2"><label class="form-label">Other IE Findings</label><input type="text" wire:model="ieOtherFindings" class="form-input"></div>
                </div>
            </div>
        </div>

        {{-- ── Section 5: Nurse's Notes ─────────────────────────────────── --}}
        <div class="ob-sec">
            <div class="ob-sec-head"><span class="ob-sec-title">📝 Nurse's Notes</span></div>
            <div class="ob-sec-body">
                <textarea rows="4" wire:model="nursesNotes" class="form-input" placeholder="Additional nurse observations, patient responses, etc."></textarea>
            </div>
        </div>

        {{-- ── Buttons ─────────────────────────────────────────────────────── --}}
        <div style="display:flex;justify-content:flex-end;gap:12px;padding-bottom:40px;">
            <button type="button"
                onclick="window.location.href='/nurse/patient-chart?visitId={{ $visitId }}'"
                class="btn-secondary">← Back to Chart</button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>💾 Save OB Record</span>
                <span wire:loading>Saving…</span>
            </button>
        </div>

    </form>
</div>
</x-filament-panels::page>