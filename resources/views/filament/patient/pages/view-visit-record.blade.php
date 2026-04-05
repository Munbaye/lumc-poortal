<x-filament-panels::page>

<style>
/* ── Accordion sections ─────────────────────────────────── */
.vr-section { border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; margin-bottom:12px; }
.dark .vr-section { border-color:#374151; }

.vr-toggle {
    width:100%; border:none; cursor:pointer;
    display:flex; align-items:center; gap:10px;
    padding:13px 18px; text-align:left;
    background:#f9fafb; transition:background .15s;
}
.dark .vr-toggle { background:#1f2937; }
.vr-toggle:hover { background:#f3f4f6; }
.dark .vr-toggle:hover { background:#374151; }

.vr-chevron {
    margin-left:auto; color:#9ca3af; flex-shrink:0;
    transition:transform .22s ease;
}
.vr-section.open .vr-chevron { transform:rotate(180deg); }

.vr-body {
    display:none; border-top:1px solid #e5e7eb;
    background:#fff; padding:18px;
}
.dark .vr-body { background:#111827; border-top-color:#374151; }
.vr-section.open .vr-body { display:block; }

/* ── Badges ─────────────────────────────────────────────── */
.badge {
    display:inline-flex; align-items:center;
    font-size:.65rem; font-weight:700; letter-spacing:.04em;
    border-radius:4px; padding:2px 8px; flex-shrink:0;
}
.b-cyan  { background:#0891b2; color:#fff; }
.b-green { background:#15803d; color:#fff; }
.b-blue  { background:#1d4ed8; color:#fff; }

/* ── Info grid ──────────────────────────────────────────── */
.info-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(155px,1fr));
    gap:14px;
}

/* ── Result file row ────────────────────────────────────── */
.rrow {
    display:flex; align-items:center; gap:10px;
    padding:10px 14px; border-radius:7px;
    text-decoration:none; transition:all .15s;
}
.rrow-lab { border:1.5px solid #bbf7d0; background:#f0fdf4; }
.rrow-lab:hover { border-color:#4ade80; background:#dcfce7; }
.rrow-rad { border:1.5px solid #bfdbfe; background:#eff6ff; }
.rrow-rad:hover { border-color:#60a5fa; background:#dbeafe; }

/* ── Test pill ──────────────────────────────────────────── */
.tpill {
    padding:2px 9px; border-radius:4px; font-size:.74rem; font-weight:600;
    background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d;
}

/* ── Pending box ────────────────────────────────────────── */
.pbox {
    padding:10px 14px; border-radius:6px; background:#f9fafb;
    border:1px dashed #d1d5db; text-align:center; margin-top:8px;
}
.dark .pbox { background:#1f2937; border-color:#4b5563; }

/* ── Responsive ─────────────────────────────────────────── */
@media(max-width:600px){
    .banner-row { flex-direction:column !important; gap:10px !important; }
    .banner-right { text-align:left !important; }
    .info-grid { grid-template-columns:1fr 1fr; }
}
</style>

@if (!$this->visit)
    <p class="text-gray-500 dark:text-gray-400" style="padding:20px;">Visit not found.</p>
@else
<div style="max-width:860px; padding-bottom:32px;">

    {{-- ── Back link ── --}}
    <div style="margin-bottom:18px;">
        <a href="{{ route('filament.patient.pages.my-records') }}"
           style="display:inline-flex;align-items:center;gap:5px;font-size:.82rem;font-weight:600;
                  text-decoration:none;color:#6b7280;transition:color .15s;"
           onmouseover="this.style.color='#0369a1'" onmouseout="this.style.color='#6b7280'">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            My Records
        </a>
    </div>

    {{-- ── Visit header banner ── --}}
    <div style="background:linear-gradient(135deg,#0c4a6e 0%,#0369a1 100%);
                border-radius:12px; padding:20px 22px; margin-bottom:16px; color:#fff;">
        <div class="banner-row" style="display:flex;align-items:flex-start;gap:14px;">

            <div style="flex:1;min-width:0;">
                {{-- Type + status badges --}}
                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px;">
                    <span style="background:rgba(255,255,255,.18);font-size:.67rem;font-weight:700;
                                  border-radius:5px;padding:3px 9px;">
                        {{ $this->isEr ? '🚑 EMERGENCY' : '📋 OUTPATIENT' }}
                    </span>
                    <span style="background:rgba(255,255,255,.18);font-size:.67rem;font-weight:700;
                                  border-radius:5px;padding:3px 9px;">
                        {{ strtoupper($this->statusLabel) }}
                    </span>
                    @if($this->visit->payment_class)
                    <span style="background:rgba(255,255,255,.18);font-size:.67rem;font-weight:700;
                                  border-radius:5px;padding:3px 9px;">
                        {{ $this->visit->payment_class }}
                    </span>
                    @endif
                </div>

                {{-- Chief complaint --}}
                <p style="font-size:1.06rem;font-weight:700;line-height:1.35;margin-bottom:5px;">
                    {{ $this->visit->chief_complaint }}
                </p>

                {{-- Date --}}
                <p style="font-size:.76rem;opacity:.7;">
                    {{ $this->visit->registered_at->format('F j, Y · H:i') }}
                </p>

                {{-- Doctor --}}
                @if($this->doctor)
                <p style="font-size:.79rem;margin-top:7px;opacity:.85;">
                    👨‍⚕️ Dr. {{ $this->doctor->name }}
                    @if($this->doctor->specialty)
                        <span style="opacity:.6;"> — {{ $this->doctor->specialty }}</span>
                    @endif
                    @if($this->visit->admitted_service)
                        <span style="opacity:.6;"> · {{ $this->visit->admitted_service }}</span>
                    @endif
                </p>
                @endif
            </div>

            {{-- Right side: case no + disposition pill --}}
            <div class="banner-right" style="text-align:right;flex-shrink:0;">
                <p style="font-size:.62rem;opacity:.5;text-transform:uppercase;letter-spacing:.07em;margin-bottom:2px;">Case No</p>
                <p style="font-weight:700;font-size:.86rem;font-family:monospace;opacity:.9;">{{ $this->patient->case_no }}</p>

                @if($this->visit->disposition)
                @php
                    $dBg = match($this->visit->disposition){
                        'Admitted'   => 'rgba(134,239,172,.25)',
                        'Discharged' => 'rgba(255,255,255,.15)',
                        'Referred'   => 'rgba(253,186,116,.25)',
                        'HAMA'       => 'rgba(253,224,71,.25)',
                        'Expired'    => 'rgba(252,165,165,.25)',
                        default      => 'rgba(255,255,255,.15)',
                    };
                    $dIcon = match($this->visit->disposition){
                        'Admitted'=>'🏥','Discharged'=>'🏠','Referred'=>'🔄',
                        'HAMA'=>'⚠️','Expired'=>'✝',default=>'📋',
                    };
                @endphp
                <div style="margin-top:10px;padding:5px 10px;border-radius:6px;
                             background:{{ $dBg }};display:inline-block;text-align:left;">
                    <p style="font-size:.77rem;font-weight:700;">{{ $dIcon }} {{ $this->visit->disposition }}</p>
                    @if($this->visit->admitted_ward)
                    <p style="font-size:.67rem;opacity:.75;margin-top:1px;">Ward: {{ $this->visit->admitted_ward }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════ --}}
    {{-- Admission Details — open by default          --}}
    {{-- ════════════════════════════════════════════ --}}
    @if($this->admRec)
    <div class="vr-section open" id="sec-adm">
        <button class="vr-toggle" onclick="vrToggle('sec-adm')" type="button">
            <span class="badge b-cyan">ADM</span>
            <span style="font-size:.87rem;font-weight:700;" class="text-gray-800 dark:text-gray-100">Admission Details</span>
            @if($this->admRec->admission_date)
            <span style="font-size:.73rem;" class="text-gray-400">· {{ $this->admRec->admission_date->format('M d, Y') }}</span>
            @endif
            <svg class="vr-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div class="vr-body">
            <div class="info-grid">
                @if($this->admRec->admission_date)
                <div>
                    <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:3px;">Admitted</p>
                    <p style="font-weight:600;font-size:.85rem;" class="text-gray-800 dark:text-gray-200">
                        {{ $this->admRec->admission_date->format('M d, Y') }}
                        @if($this->admRec->admission_time)
                        <span style="font-size:.76rem;color:#9ca3af;"> {{ $this->admRec->admission_time }}</span>
                        @endif
                    </p>
                </div>
                @endif
                @if($this->admRec->discharge_date)
                <div>
                    <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:3px;">Discharged</p>
                    <p style="font-weight:600;font-size:.85rem;" class="text-gray-800 dark:text-gray-200">
                        {{ $this->admRec->discharge_date->format('M d, Y') }}
                        @if($this->admRec->discharge_time)
                        <span style="font-size:.76rem;color:#9ca3af;"> {{ $this->admRec->discharge_time }}</span>
                        @endif
                    </p>
                </div>
                @endif
                @if($this->admRec->total_days)
                <div>
                    <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:3px;">Length of Stay</p>
                    <p style="font-weight:600;font-size:.85rem;" class="text-gray-800 dark:text-gray-200">
                        {{ $this->admRec->total_days }} day{{ $this->admRec->total_days != 1 ? 's' : '' }}
                    </p>
                </div>
                @endif
                @if($this->admRec->ward_service)
                <div>
                    <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:3px;">Ward / Service</p>
                    <p style="font-weight:600;font-size:.85rem;" class="text-gray-800 dark:text-gray-200">{{ $this->admRec->ward_service }}</p>
                </div>
                @endif
                @if($this->admRec->type_of_admission)
                <div>
                    <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:3px;">Admission Type</p>
                    <p style="font-weight:600;font-size:.85rem;" class="text-gray-800 dark:text-gray-200">{{ $this->admRec->type_of_admission }}</p>
                </div>
                @endif
                @if($this->admRec->philhealth_id)
                <div>
                    <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:3px;">PhilHealth ID</p>
                    <p style="font-weight:600;font-size:.85rem;font-family:monospace;" class="text-gray-800 dark:text-gray-200">{{ $this->admRec->philhealth_id }}</p>
                </div>
                @endif
                @if($this->admRec->final_diagnosis)
                <div style="grid-column:1/-1;">
                    <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:3px;">Final Diagnosis</p>
                    <p style="font-weight:700;font-size:.9rem;" class="text-gray-800 dark:text-gray-200">{{ $this->admRec->final_diagnosis }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════ --}}
    {{-- Laboratory — open if results exist           --}}
    {{-- ════════════════════════════════════════════ --}}
    @if($this->labReqs->isNotEmpty())
    @php $labHasResults = $this->labReqs->flatMap->results->isNotEmpty(); @endphp
    <div class="vr-section {{ $labHasResults ? 'open' : '' }}" id="sec-lab">
        <button class="vr-toggle" onclick="vrToggle('sec-lab')" type="button">
            <span class="badge b-green">LAB</span>
            <span style="font-size:.87rem;font-weight:700;" class="text-gray-800 dark:text-gray-100">Laboratory</span>
            <span style="font-size:.73rem;" class="text-gray-400">
                · {{ $this->labReqs->count() }} request{{ $this->labReqs->count()!=1?'s':'' }}
                @if($labHasResults)
                    <span style="color:#15803d;font-weight:700;"> · Results available</span>
                @else
                    · Pending
                @endif
            </span>
            <svg class="vr-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div class="vr-body">
            <div style="display:grid;gap:12px;">
                @foreach($this->labReqs as $lab)
                @php
                    $labResults = $lab->results;
                    $lsb = match($lab->status){
                        'completed'   => 'background:#dcfce7;color:#15803d;',
                        'in_progress' => 'background:#fef9c3;color:#854d0e;',
                        default       => 'background:#f3f4f6;color:#6b7280;',
                    };
                @endphp
                <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;" class="dark:border-gray-700">

                    {{-- Sub-header --}}
                    <div style="padding:9px 14px;background:#f0fdf4;border-bottom:1px solid #bbf7d0;
                                 display:flex;flex-wrap:wrap;align-items:center;gap:7px;"
                         class="dark:bg-green-900/10 dark:border-green-900">
                        <span style="font-family:monospace;font-size:.74rem;font-weight:700;color:#15803d;">{{ $lab->request_no }}</span>
                        <span style="padding:2px 8px;border-radius:4px;font-size:.67rem;font-weight:700;{{ $lsb }}">{{ $lab->status_label }}</span>
                        @if($lab->request_type === 'stat')
                        <span style="padding:2px 8px;border-radius:4px;font-size:.67rem;font-weight:700;background:#fef2f2;color:#dc2626;">STAT</span>
                        @endif
                        @if($lab->date_requested)
                        <span style="margin-left:auto;font-size:.71rem;" class="text-gray-400">{{ $lab->date_requested->format('M d, Y') }}</span>
                        @endif
                    </div>

                    <div style="padding:12px 14px;">
                        @if($lab->tests && count($lab->tests))
                        <div style="margin-bottom:9px;">
                            <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:5px;">Tests Ordered</p>
                            <div style="display:flex;flex-wrap:wrap;gap:5px;">
                                @foreach($lab->tests as $test)
                                <span class="tpill">{{ $test }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($lab->clinical_diagnosis)
                        <div style="margin-bottom:9px;">
                            <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:2px;">Clinical Diagnosis</p>
                            <p style="font-size:.83rem;" class="text-gray-700 dark:text-gray-300">{{ $lab->clinical_diagnosis }}</p>
                        </div>
                        @endif

                        @if($labResults->isNotEmpty())
                        <div style="margin-top:8px;padding-top:8px;border-top:1px solid #f3f4f6;" class="dark:border-gray-700">
                            <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:6px;">
                                Results ({{ $labResults->count() }})
                            </p>
                            <div style="display:grid;gap:5px;">
                                @foreach($labResults as $result)
                                <a href="{{ $this->getFileUrl($result->file_path) }}" target="_blank" class="rrow rrow-lab">
                                    <span style="font-size:1.2rem;flex-shrink:0;">{{ $result->file_type_icon }}</span>
                                    <div style="flex:1;min-width:0;">
                                        <p style="font-size:.8rem;font-weight:600;color:#15803d;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $result->file_name }}</p>
                                        <p style="font-size:.7rem;color:#9ca3af;">{{ $result->file_size_human }}@if($result->created_at) · {{ $result->created_at->format('M d, Y') }}@endif</p>
                                    </div>
                                    <span style="font-size:.71rem;font-weight:600;color:#15803d;flex-shrink:0;">Open ↗</span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="pbox">
                            <p style="font-size:.74rem;" class="text-gray-400 dark:text-gray-500">Results not yet uploaded.</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════ --}}
    {{-- Radiology & Imaging — open if results exist  --}}
    {{-- ════════════════════════════════════════════ --}}
    @if($this->radReqs->isNotEmpty())
    @php $radHasResults = $this->radReqs->flatMap->results->isNotEmpty(); @endphp
    <div class="vr-section {{ $radHasResults ? 'open' : '' }}" id="sec-rad">
        <button class="vr-toggle" onclick="vrToggle('sec-rad')" type="button">
            <span class="badge b-blue">RAD</span>
            <span style="font-size:.87rem;font-weight:700;" class="text-gray-800 dark:text-gray-100">Radiology & Imaging</span>
            <span style="font-size:.73rem;" class="text-gray-400">
                · {{ $this->radReqs->count() }} request{{ $this->radReqs->count()!=1?'s':'' }}
                @if($radHasResults)
                    <span style="color:#1d4ed8;font-weight:700;"> · Results available</span>
                @else
                    · Pending
                @endif
            </span>
            <svg class="vr-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div class="vr-body">
            <div style="display:grid;gap:12px;">
                @foreach($this->radReqs as $rad)
                @php
                    $radResults = $rad->results;
                    $rsb = match($rad->status){
                        'completed'   => 'background:#dbeafe;color:#1d4ed8;',
                        'in_progress' => 'background:#fef9c3;color:#854d0e;',
                        default       => 'background:#f3f4f6;color:#6b7280;',
                    };
                @endphp
                <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;" class="dark:border-gray-700">

                    {{-- Sub-header --}}
                    <div style="padding:9px 14px;background:#eff6ff;border-bottom:1px solid #bfdbfe;
                                 display:flex;flex-wrap:wrap;align-items:center;gap:7px;"
                         class="dark:bg-blue-900/10 dark:border-blue-900">
                        <span style="font-family:monospace;font-size:.74rem;font-weight:700;color:#1d4ed8;">{{ $rad->request_no }}</span>
                        @if($rad->modality)
                        <span style="padding:2px 8px;border-radius:4px;font-size:.67rem;font-weight:700;background:#dbeafe;color:#1e40af;">{{ strtoupper($rad->modality) }}</span>
                        @endif
                        <span style="padding:2px 8px;border-radius:4px;font-size:.67rem;font-weight:700;{{ $rsb }}">{{ $rad->status_label }}</span>
                        @if($rad->date_requested)
                        <span style="margin-left:auto;font-size:.71rem;" class="text-gray-400">{{ $rad->date_requested->format('M d, Y') }}</span>
                        @endif
                    </div>

                    <div style="padding:12px 14px;">
                        @if($rad->examination_desired)
                        <div style="margin-bottom:9px;">
                            <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:2px;">Examination</p>
                            <p style="font-size:.87rem;font-weight:600;" class="text-gray-800 dark:text-gray-200">{{ $rad->examination_desired }}</p>
                        </div>
                        @endif

                        @if($rad->clinical_diagnosis)
                        <div style="margin-bottom:9px;">
                            <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:2px;">Clinical Indication</p>
                            <p style="font-size:.83rem;" class="text-gray-700 dark:text-gray-300">{{ $rad->clinical_diagnosis }}</p>
                        </div>
                        @endif

                        @if($rad->radiologist_interpretation)
                        <div style="margin-bottom:9px;padding:11px 14px;border-radius:7px;
                                    border:1.5px solid #bfdbfe;background:#eff6ff;"
                             class="dark:bg-blue-900/10 dark:border-blue-800">
                            <p style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#1d4ed8;margin-bottom:4px;">🩻 Radiologist Interpretation</p>
                            <p style="font-size:.85rem;line-height:1.65;white-space:pre-line;" class="text-gray-800 dark:text-gray-200">{{ $rad->radiologist_interpretation }}</p>
                        </div>
                        @endif

                        @if($radResults->isNotEmpty())
                        <div style="margin-top:8px;padding-top:8px;border-top:1px solid #f3f4f6;" class="dark:border-gray-700">
                            <p style="font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:6px;">
                                Images & Files ({{ $radResults->count() }})
                            </p>
                            <div style="display:grid;gap:5px;">
                                @foreach($radResults as $result)
                                @php $isImg = str_contains($result->file_mime??'','image'); @endphp
                                <a href="{{ $this->getFileUrl($result->file_path) }}" target="_blank" class="rrow rrow-rad">
                                    <span style="font-size:1.2rem;flex-shrink:0;">{{ $result->file_type_icon }}</span>
                                    <div style="flex:1;min-width:0;">
                                        <p style="font-size:.8rem;font-weight:600;color:#1d4ed8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $result->file_name }}</p>
                                        <p style="font-size:.7rem;color:#9ca3af;">{{ $isImg?'Image':'File' }} · {{ $result->file_size_human }}@if($result->created_at) · {{ $result->created_at->format('M d, Y') }}@endif</p>
                                        @if($result->interpretation)
                                        <p style="font-size:.75rem;color:#1d4ed8;margin-top:2px;font-style:italic;">"{{ Str::limit($result->interpretation,80) }}"</p>
                                        @endif
                                    </div>
                                    <span style="font-size:.71rem;font-weight:600;color:#1d4ed8;flex-shrink:0;">Open ↗</span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="pbox">
                            <p style="font-size:.74rem;" class="text-gray-400 dark:text-gray-500">Images/results not yet uploaded.</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- No records at all --}}
    @if(!$this->admRec && $this->labReqs->isEmpty() && $this->radReqs->isEmpty())
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;
                padding:40px;text-align:center;" class="dark:bg-gray-900 dark:border-gray-700">
        <div style="font-size:2rem;margin-bottom:10px;">📋</div>
        <p style="font-weight:700;font-size:.93rem;margin-bottom:4px;" class="text-gray-800 dark:text-white">No records yet.</p>
        <p style="font-size:.81rem;" class="text-gray-400 dark:text-gray-500">Records will appear here as your visit progresses.</p>
    </div>
    @endif

</div>
@endif

<script>
function vrToggle(id){
    const el = document.getElementById(id);
    if(el) el.classList.toggle('open');
}
</script>

</x-filament-panels::page>