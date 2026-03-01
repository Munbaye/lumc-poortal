<x-filament-panels::page>
<div style="max-width:900px;">

@if (!$this->patient)

    {{-- ── No linked record ──────────────────────────────────────────────── --}}
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:40px;
                text-align:center;" class="dark:bg-gray-900 dark:border-gray-700">
        <div style="font-size:2.5rem;margin-bottom:12px;">🔗</div>
        <p style="font-weight:700;font-size:1rem;margin-bottom:6px;"
           class="text-gray-800 dark:text-white">No patient record linked to your account.</p>
        <p style="font-size:.84rem;" class="text-gray-400 dark:text-gray-500">
            Please contact the LUMC registration desk to link your account.
        </p>
    </div>

@else

    {{-- ── Patient header bar ────────────────────────────────────────────── --}}
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:14px 18px;
                margin-bottom:20px;display:flex;flex-wrap:wrap;gap:20px;align-items:center;"
         class="dark:bg-gray-900 dark:border-gray-700">
        <div>
            <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;
                      color:#9ca3af;margin-bottom:2px;">Case No</p>
            <p style="font-family:monospace;font-weight:700;font-size:.93rem;"
               class="text-gray-900 dark:text-white">{{ $this->patient->case_no }}</p>
        </div>
        <div style="flex:1;min-width:180px;">
            <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;
                      color:#9ca3af;margin-bottom:2px;">Patient</p>
            <p style="font-weight:700;" class="text-gray-900 dark:text-white">
                {{ $this->patient->full_name }}
            </p>
        </div>
        <div>
            <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;
                      color:#9ca3af;margin-bottom:2px;">Age / Sex</p>
            <p style="font-weight:600;" class="text-gray-700 dark:text-gray-300">
                {{ $this->patient->age_display }} / {{ $this->patient->sex }}
            </p>
        </div>
        @if($this->patient->birthday)
        <div>
            <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;
                      color:#9ca3af;margin-bottom:2px;">Birthday</p>
            <p style="font-weight:600;" class="text-gray-700 dark:text-gray-300">
                {{ $this->patient->birthday->format('M d, Y') }}
            </p>
        </div>
        @endif
        <div>
            <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;
                      color:#9ca3af;margin-bottom:2px;">Total Visits</p>
            <p style="font-weight:700;font-size:1.1rem;" class="text-gray-900 dark:text-white">
                {{ $this->visits->count() }}
            </p>
        </div>
    </div>

    {{-- ── No visits ──────────────────────────────────────────────────────── --}}
    @if ($this->visits->isEmpty())
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:40px;
                    text-align:center;" class="dark:bg-gray-900 dark:border-gray-700">
            <div style="font-size:2.5rem;margin-bottom:12px;">📋</div>
            <p style="font-weight:700;font-size:1rem;margin-bottom:6px;"
               class="text-gray-800 dark:text-white">No visit records yet.</p>
            <p style="font-size:.84rem;" class="text-gray-400 dark:text-gray-500">
                Your records will appear here after your first hospital visit.
            </p>
        </div>

    @else

        {{-- ── One card per visit ─────────────────────────────────────────── --}}
        @foreach ($this->visits as $index => $visit)
        @php
            $vt  = $visit->latestVitals;
            $mh  = $visit->medicalHistory;
            $isEr = $visit->visit_type === 'ER';
            $statusLabel = match($visit->status) {
                'registered'  => 'Registered',
                'vitals_done' => 'Vitals Done',
                'assessed'    => 'Assessed',
                'admitted'    => 'Admitted',
                'discharged'  => 'Discharged',
                'referred'    => 'Referred',
                default       => ucfirst($visit->status),
            };
            $statusStyle = match($visit->status) {
                'registered'  => 'background:#fefce8;color:#854d0e;',
                'vitals_done' => 'background:#eff6ff;color:#1d4ed8;',
                'assessed'    => 'background:#f0fdf4;color:#15803d;',
                'admitted'    => 'background:#faf5ff;color:#6d28d9;',
                'discharged'  => 'background:#f9fafb;color:#374151;',
                'referred'    => 'background:#fff7ed;color:#c2410c;',
                default       => 'background:#f9fafb;color:#374151;',
            };
        @endphp

        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;
                    margin-bottom:20px;overflow:hidden;"
             class="dark:bg-gray-900 dark:border-gray-700">

            {{-- Visit header strip ──────────────────────────────────────── --}}
            <div style="background:#f9fafb;border-bottom:1px solid #e5e7eb;
                        padding:12px 18px;display:flex;flex-wrap:wrap;
                        align-items:center;gap:12px;"
                 class="dark:bg-gray-800 dark:border-gray-700">

                {{-- Visit number --}}
                <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;
                              border-radius:4px;padding:2px 8px;"
                      class="dark:bg-white dark:text-gray-900">
                    Visit {{ $this->visits->count() - $index }}
                </span>

                {{-- Visit type --}}
                <span style="display:inline-block;padding:3px 12px;border-radius:4px;
                              font-size:.75rem;font-weight:700;
                              background:{{ $isEr ? '#fef2f2' : '#eff6ff' }};
                              color:{{ $isEr ? '#dc2626' : '#1d4ed8' }};">
                    {{ $isEr ? '🚑 ER' : '📋 OPD' }}
                </span>

                {{-- Status --}}
                <span style="display:inline-block;padding:3px 12px;border-radius:4px;
                              font-size:.75rem;font-weight:700;{{ $statusStyle }}">
                    {{ $statusLabel }}
                </span>

                @if($visit->payment_class)
                <span style="display:inline-block;padding:3px 12px;border-radius:4px;
                              font-size:.75rem;font-weight:700;
                              background:#f5f3ff;color:#5b21b6;">
                    {{ $visit->payment_class }}
                </span>
                @endif

                <span style="margin-left:auto;font-size:.78rem;font-weight:500;"
                      class="text-gray-400 dark:text-gray-500">
                    {{ $visit->registered_at->format('M d, Y — H:i') }}
                </span>
            </div>

            <div style="padding:18px 18px 22px;">

                {{-- ── Chief complaint ──────────────────────────────────── --}}
                <div style="margin-bottom:16px;">
                    <p style="font-size:.67rem;text-transform:uppercase;letter-spacing:.06em;
                              color:#9ca3af;margin-bottom:3px;">Chief Complaint</p>
                    <p style="font-weight:600;font-size:.88rem;"
                       class="text-gray-800 dark:text-gray-200">
                        {{ $visit->chief_complaint }}
                    </p>
                    @if($visit->brought_by || $visit->condition_on_arrival)
                    <p style="font-size:.78rem;margin-top:3px;" class="text-gray-400 dark:text-gray-500">
                        @if($visit->brought_by)
                            Brought by: <strong>{{ $visit->brought_by }}</strong>
                        @endif
                        @if($visit->condition_on_arrival)
                            &nbsp;·&nbsp; Condition: <strong>{{ $visit->condition_on_arrival }}</strong>
                        @endif
                    </p>
                    @endif
                </div>

                {{-- ── SECTION 1 · Vital Signs ──────────────────────────── --}}
                @if($vt)
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;
                            padding:14px 18px;margin-bottom:14px;"
                     class="dark:bg-gray-900 dark:border-gray-700">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;
                                padding-bottom:8px;border-bottom:1px solid #f3f4f6;"
                         class="dark:border-gray-700">
                        <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;
                                      border-radius:4px;padding:2px 8px;"
                              class="dark:bg-white dark:text-gray-900">1</span>
                        <h2 style="font-size:.93rem;font-weight:700;"
                            class="text-gray-900 dark:text-white">Vital Signs</h2>
                        <span style="font-size:.72rem;color:#9ca3af;">
                            Recorded by {{ $vt->nurse_name }}
                            — {{ $vt->taken_at->format('M j Y H:i') }}
                        </span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @php
                            $vitalsMap = [
                                ['BP',   $vt->blood_pressure,    null, null],
                                ['PR',   $vt->pulse_rate,          60,  100],
                                ['RR',   $vt->respiratory_rate,    12,   20],
                                ['Temp', $vt->temperature,       36.0, 37.5],
                                ['O₂',   $vt->o2_saturation,       95,  100],
                                ['Wt',   $vt->weight_kg,          null, null],
                            ];
                        @endphp
                        @foreach($vitalsMap as [$lbl, $val, $lo, $hi])
                        @if($val !== null)
                        @php
                            $abnormal = $lo && $hi && ($val < $lo || $val > $hi);
                            $tileStyle = $abnormal
                                ? 'background:#fef2f2;border-color:#fca5a5;color:#dc2626;font-weight:700;'
                                : '';
                            $suffix = match($lbl) {
                                'Temp' => ' °C', 'PR' => ' bpm',
                                'RR'   => '/min', 'O₂' => '%',
                                'Wt'   => ' kg',  default => '',
                            };
                        @endphp
                        <div style="padding:5px 12px;border:1px solid #e5e7eb;border-radius:6px;
                                    font-size:.83rem;{{ $tileStyle }}"
                             class="{{ !$abnormal ? 'dark:border-gray-700 dark:bg-gray-800' : '' }}">
                            <span style="color:#9ca3af;font-size:.7rem;">{{ $lbl }}</span>
                            <span style="font-weight:700;margin-left:5px;" class="dark:text-white">
                                {{ $val }}{{ $suffix }}
                            </span>
                        </div>
                        @endif
                        @endforeach
                        @if($vt->pain_scale !== null)
                        @php $highPain = (int)$vt->pain_scale >= 7; @endphp
                        <div style="padding:5px 12px;border:1px solid {{ $highPain?'#fca5a5':'#e5e7eb' }};
                                    border-radius:6px;font-size:.83rem;
                                    {{ $highPain?'background:#fef2f2;color:#dc2626;font-weight:700;':'' }}"
                             class="{{ !$highPain ? 'dark:border-gray-700 dark:bg-gray-800' : '' }}">
                            <span style="color:#9ca3af;font-size:.7rem;">Pain</span>
                            <span style="font-weight:700;margin-left:5px;"
                                  class="dark:text-white">{{ $vt->pain_scale }}/10</span>
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div style="background:#fefce8;border:1px solid #fde047;border-radius:8px;
                            padding:10px 16px;margin-bottom:14px;font-size:.83rem;"
                     class="dark:bg-yellow-900/20 dark:border-yellow-700">
                    <span style="color:#854d0e;" class="dark:text-yellow-400">
                        ⚠️ No vital signs recorded for this visit.
                    </span>
                </div>
                @endif

                {{-- ── SECTION 2 · Physical Examination ────────────────── --}}
                @if($mh)
                @php
                    $peRows = [
                        ['Skin',            $mh->pe_skin],
                        ['Head / EENT',     $mh->pe_head_eent],
                        ['Lymph Nodes',     $mh->pe_lymph_nodes],
                        ['Chest',           $mh->pe_chest],
                        ['Lungs',           $mh->pe_lungs],
                        ['Cardiovascular',  $mh->pe_cardiovascular],
                        ['Breast',          $mh->pe_breast],
                        ['Abdomen',         $mh->pe_abdomen],
                        ['Rectum',          $mh->pe_rectum],
                        ['Genitalia',       $mh->pe_genitalia],
                        ['Musculoskeletal', $mh->pe_musculoskeletal],
                        ['Extremities',     $mh->pe_extremities],
                        ['Neurology',       $mh->pe_neurology],
                    ];
                    $peRows = array_filter($peRows, fn($r) => !empty($r[1]));
                @endphp
                @if(count($peRows) || $mh->admitting_impression)
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;
                            padding:14px 18px;margin-bottom:14px;"
                     class="dark:bg-gray-900 dark:border-gray-700">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;
                                padding-bottom:8px;border-bottom:1px solid #f3f4f6;"
                         class="dark:border-gray-700">
                        <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;
                                      border-radius:4px;padding:2px 8px;"
                              class="dark:bg-white dark:text-gray-900">2</span>
                        <h2 style="font-size:.93rem;font-weight:700;"
                            class="text-gray-900 dark:text-white">Physical Examination</h2>
                        <span style="font-size:.72rem;color:#9ca3af;">NUR-005</span>
                    </div>

                    <div style="display:grid;gap:5px;">
                        @foreach($peRows as [$label, $val])
                        <div style="display:grid;grid-template-columns:150px 1fr;
                                    align-items:baseline;gap:10px;">
                            <p style="font-size:.77rem;font-weight:600;text-align:right;"
                               class="text-gray-400 dark:text-gray-500">{{ $label }}</p>
                            <p style="font-size:.83rem;" class="text-gray-700 dark:text-gray-300">
                                {{ $val }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    @if($mh->admitting_impression)
                    <div style="margin-top:10px;padding-top:10px;border-top:1px solid #f3f4f6;"
                         class="dark:border-gray-700">
                        <div style="display:grid;grid-template-columns:150px 1fr;
                                    align-items:baseline;gap:10px;">
                            <p style="font-size:.77rem;font-weight:700;text-align:right;"
                               class="text-gray-600 dark:text-gray-300">Admitting Impression</p>
                            <p style="font-size:.83rem;font-weight:600;"
                               class="text-gray-800 dark:text-gray-200">
                                {{ $mh->admitting_impression }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- ── SECTION 3 · Medical History ──────────────────────── --}}
                @php
                    $histRows = [
                        ['Chief Complaint',                 $mh->chief_complaint],
                        ['History of Present Illness',      $mh->history_of_present_illness],
                        ['Past Medical History',            $mh->past_medical_history],
                        ['Family History',                  $mh->family_history],
                        ['Occupation & Environment',        $mh->occupation_environment],
                        ['Drug Therapy',                    $mh->drug_therapy],
                        ['Other Allergies',                 $mh->other_allergies],
                    ];
                    $histRows = array_filter($histRows, fn($r) => !empty($r[1]));
                @endphp
                @if(count($histRows) || $mh->drug_allergies)
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;
                            padding:14px 18px;margin-bottom:14px;"
                     class="dark:bg-gray-900 dark:border-gray-700">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;
                                padding-bottom:8px;border-bottom:1px solid #f3f4f6;"
                         class="dark:border-gray-700">
                        <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;
                                      border-radius:4px;padding:2px 8px;"
                              class="dark:bg-white dark:text-gray-900">3</span>
                        <h2 style="font-size:.93rem;font-weight:700;"
                            class="text-gray-900 dark:text-white">Medical History</h2>
                        <span style="font-size:.72rem;color:#9ca3af;">NUR-006</span>
                    </div>

                    <div style="display:grid;gap:10px;">
                        @foreach($histRows as [$label, $val])
                        <div>
                            <p style="font-size:.71rem;font-weight:700;text-transform:uppercase;
                                      letter-spacing:.05em;margin-bottom:2px;"
                               class="text-gray-400 dark:text-gray-500">{{ $label }}</p>
                            <p style="font-size:.83rem;white-space:pre-line;"
                               class="text-gray-700 dark:text-gray-300">{{ $val }}</p>
                        </div>
                        @endforeach

                        @if($mh->drug_allergies)
                        <div style="padding:10px 14px;border-radius:6px;
                                    border:1px solid {{ $mh->drug_allergies === 'NKDA' ? '#e5e7eb' : '#fca5a5' }};
                                    background:{{ $mh->drug_allergies === 'NKDA' ? '#f9fafb' : '#fef2f2' }};"
                             class="{{ $mh->drug_allergies === 'NKDA' ? 'dark:bg-gray-800 dark:border-gray-600' : 'dark:bg-red-900/20 dark:border-red-700' }}">
                            <p style="font-size:.71rem;font-weight:700;text-transform:uppercase;
                                      letter-spacing:.05em;margin-bottom:2px;
                                      color:{{ $mh->drug_allergies === 'NKDA' ? '#6b7280' : '#dc2626' }};">
                                {{ $mh->drug_allergies === 'NKDA' ? 'Drug Allergies' : '⚠ Drug Allergies' }}
                            </p>
                            <p style="font-size:.83rem;font-weight:{{ $mh->drug_allergies === 'NKDA' ? '400' : '700' }};
                                      color:{{ $mh->drug_allergies === 'NKDA' ? '#374151' : '#dc2626' }};"
                               class="{{ $mh->drug_allergies === 'NKDA' ? 'dark:text-gray-300' : 'dark:text-red-400' }}">
                                {{ $mh->drug_allergies }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- ── SECTION 4 · Diagnosis ────────────────────────────── --}}
                @if($mh->diagnosis || $mh->differential_diagnosis || $mh->plan)
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;
                            padding:14px 18px;margin-bottom:14px;"
                     class="dark:bg-gray-900 dark:border-gray-700">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;
                                padding-bottom:8px;border-bottom:1px solid #f3f4f6;"
                         class="dark:border-gray-700">
                        <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;
                                      border-radius:4px;padding:2px 8px;"
                              class="dark:bg-white dark:text-gray-900">4</span>
                        <h2 style="font-size:.93rem;font-weight:700;"
                            class="text-gray-900 dark:text-white">Diagnosis</h2>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:10px;">
                        @if($mh->diagnosis)
                        <div>
                            <p style="font-size:.71rem;font-weight:700;text-transform:uppercase;
                                      letter-spacing:.05em;margin-bottom:3px;"
                               class="text-gray-400 dark:text-gray-500">Final Diagnosis</p>
                            <p style="font-size:.88rem;font-weight:600;"
                               class="text-gray-800 dark:text-gray-200">{{ $mh->diagnosis }}</p>
                        </div>
                        @endif
                        @if($mh->differential_diagnosis)
                        <div>
                            <p style="font-size:.71rem;font-weight:700;text-transform:uppercase;
                                      letter-spacing:.05em;margin-bottom:3px;"
                               class="text-gray-400 dark:text-gray-500">Differential Diagnosis</p>
                            <p style="font-size:.83rem;"
                               class="text-gray-700 dark:text-gray-300">
                                {{ $mh->differential_diagnosis }}
                            </p>
                        </div>
                        @endif
                    </div>

                    @if($mh->plan)
                    <div>
                        <p style="font-size:.71rem;font-weight:700;text-transform:uppercase;
                                  letter-spacing:.05em;margin-bottom:3px;"
                           class="text-gray-400 dark:text-gray-500">Management Plan</p>
                        <p style="font-size:.83rem;white-space:pre-line;"
                           class="text-gray-700 dark:text-gray-300">{{ $mh->plan }}</p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- ── SECTION 5 · Disposition ──────────────────────────── --}}
                @if($visit->disposition)
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;
                            padding:14px 18px;"
                     class="dark:bg-gray-900 dark:border-gray-700">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;
                                padding-bottom:8px;border-bottom:1px solid #f3f4f6;"
                         class="dark:border-gray-700">
                        <span style="background:#111827;color:#fff;font-size:.68rem;font-weight:700;
                                      border-radius:4px;padding:2px 8px;"
                              class="dark:bg-white dark:text-gray-900">5</span>
                        <h2 style="font-size:.93rem;font-weight:700;"
                            class="text-gray-900 dark:text-white">Patient Disposition</h2>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                        @php
                            $dispIcon = match($visit->disposition) {
                                'Discharged' => '🏠', 'Admitted'   => '🏥',
                                'Referred'   => '🔄', 'HAMA'       => '⚠️',
                                'Expired'    => '✝',  default      => '📋',
                            };
                            $dispStyle = match($visit->disposition) {
                                'Admitted'   => 'background:#f0fdf4;border-color:#86efac;color:#065f46;',
                                'Discharged' => 'background:#f9fafb;border-color:#d1d5db;color:#374151;',
                                'Referred'   => 'background:#fff7ed;border-color:#fdba74;color:#c2410c;',
                                'HAMA'       => 'background:#fefce8;border-color:#fde047;color:#854d0e;',
                                'Expired'    => 'background:#fef2f2;border-color:#fca5a5;color:#991b1b;',
                                default      => 'background:#f9fafb;border-color:#e5e7eb;color:#374151;',
                            };
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:6px;
                                     padding:7px 16px;border-radius:6px;border:2px solid;
                                     font-size:.88rem;font-weight:700;{{ $dispStyle }}">
                            {{ $dispIcon }} {{ $visit->disposition }}
                        </span>

                        @if($visit->admitted_ward)
                        <span style="font-size:.83rem;font-weight:600;"
                              class="text-gray-600 dark:text-gray-300">
                            — Ward: <strong>{{ $visit->admitted_ward }}</strong>
                        </span>
                        @endif

                        @if($visit->admitted_service)
                        <span style="font-size:.83rem;" class="text-gray-500 dark:text-gray-400">
                            · Service: <strong>{{ $visit->admitted_service }}</strong>
                        </span>
                        @endif

                        @if($visit->discharged_at)
                        <span style="font-size:.78rem;margin-left:auto;"
                              class="text-gray-400 dark:text-gray-500">
                            {{ $visit->disposition === 'Admitted' ? 'Admitted' : 'Discharged' }}:
                            {{ $visit->discharged_at->format('M d, Y H:i') }}
                        </span>
                        @endif
                    </div>
                </div>
                @endif

                @endif {{-- end @if($mh) --}}

            </div>{{-- end card body --}}
        </div>{{-- end visit card --}}
        @endforeach

    @endif
@endif
</div>
</x-filament-panels::page>