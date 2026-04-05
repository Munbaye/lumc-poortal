<x-filament-panels::page>

<style>
/* ── Base ───────────────────────────────────────────────── */
.mr-wrap { max-width:860px; }

/* ── Patient header ─────────────────────────────────────── */
.mr-patient-bar {
    background:#fff; border:1px solid #e5e7eb; border-radius:10px;
    padding:16px 22px; margin-bottom:22px;
    display:flex; flex-wrap:wrap; gap:24px; align-items:center;
}
.dark .mr-patient-bar { background:#111827; border-color:#374151; }

.mr-patient-field p:first-child {
    font-size:.62rem; text-transform:uppercase; letter-spacing:.08em;
    color:#9ca3af; margin-bottom:3px;
}
.mr-patient-field p:last-child {
    font-weight:600; font-size:.88rem;
}

/* ── Section heading ────────────────────────────────────── */
.mr-section-label {
    font-size:.72rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.09em; color:#9ca3af;
    display:flex; align-items:center; gap:10px;
    margin-bottom:14px;
}
.mr-section-label::after {
    content:''; flex:1; height:1px; background:#e5e7eb;
}
.dark .mr-section-label::after { background:#374151; }

/* ── Visit card ─────────────────────────────────────────── */
.mr-card {
    background:#fff; border:1px solid #e5e7eb; border-radius:10px;
    overflow:hidden; margin-bottom:10px;
    transition:border-color .15s, box-shadow .15s, transform .15s;
    text-decoration:none; display:block;
}
.dark .mr-card { background:#111827; border-color:#374151; }
.mr-card:hover {
    border-color:#0369a1;
    box-shadow:0 4px 18px rgba(3,105,161,.1);
    transform:translateY(-1px);
}
.dark .mr-card:hover { border-color:#38bdf8; }

/* ── Card top bar ───────────────────────────────────────── */
.mr-card-bar {
    background:#f9fafb; border-bottom:1px solid #e5e7eb;
    padding:10px 18px;
    display:flex; flex-wrap:wrap; align-items:center; gap:8px;
}
.dark .mr-card-bar { background:#1f2937; border-color:#374151; }

/* ── Pill badges ────────────────────────────────────────── */
.pill {
    display:inline-flex; align-items:center;
    font-size:.68rem; font-weight:700; letter-spacing:.03em;
    border-radius:4px; padding:2px 9px;
}
.pill-dark   { background:#111827; color:#fff; }
.dark .pill-dark { background:#f9fafb; color:#111827; }
.pill-er     { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
.pill-opd    { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
.pill-sky    { background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; }
.pill-green  { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
.pill-amber  { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
.pill-slate  { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; }
.pill-orange { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
.pill-red    { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

/* ── Card body ──────────────────────────────────────────── */
.mr-card-body {
    padding:14px 18px;
    display:flex; flex-wrap:wrap; gap:18px; align-items:flex-start;
}

.mr-field { min-width:120px; }
.mr-field-label {
    font-size:.62rem; text-transform:uppercase; letter-spacing:.07em;
    color:#9ca3af; margin-bottom:3px;
}
.mr-field-value { font-weight:600; font-size:.86rem; line-height:1.4; }

/* ── Badge cluster ──────────────────────────────────────── */
.mr-badge-cluster {
    display:flex; flex-wrap:wrap; gap:5px;
    align-items:center; margin-left:auto;
}

/* ── Arrow indicator ────────────────────────────────────── */
.mr-arrow {
    display:flex; align-items:center; justify-content:center;
    width:26px; height:26px; border-radius:50%;
    border:1.5px solid #cbd5e1; flex-shrink:0;
    color:#94a3b8; transition:all .15s;
}
.mr-card:hover .mr-arrow {
    border-color:#0369a1; background:#0369a1; color:#fff;
}

/* ── Disposition footer ─────────────────────────────────── */
.mr-disp {
    padding:8px 18px; font-size:.76rem; font-weight:600;
    display:flex; align-items:center; gap:8px;
    border-top:1px solid #e5e7eb;
}
.dark .mr-disp { border-top-color:#374151; }

/* ── Responsive ─────────────────────────────────────────── */
@media(max-width:600px){
    .mr-patient-bar { gap:14px; }
    .mr-badge-cluster { margin-left:0; margin-top:4px; }
    .mr-card-body { gap:12px; }
}
</style>

<div class="mr-wrap">

@if (!$this->patient)
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;
                padding:48px;text-align:center;"
         class="dark:bg-gray-900 dark:border-gray-700">
        <p style="font-weight:700;font-size:.95rem;margin-bottom:6px;"
           class="text-gray-800 dark:text-white">No patient record linked to your account.</p>
        <p style="font-size:.83rem;" class="text-gray-400">
            Please contact the LUMC registration desk to link your account.
        </p>
    </div>

@else

    {{-- Incomplete info warning --}}
    @if($this->patient->has_incomplete_info)
    <div style="background:#fef2f2;border:1.5px solid #fca5a5;border-radius:10px;
                padding:14px 18px;margin-bottom:18px;display:flex;align-items:flex-start;gap:12px;">
        <div style="flex:1;">
            <p style="font-weight:700;font-size:.86rem;color:#b91c1c;margin-bottom:2px;">Incomplete Patient Information</p>
            <p style="font-size:.8rem;color:#9ca3af;">
                Your record has missing information. Please visit the registration desk
                or call <strong style="color:#6b7280;">(072) 607-5541</strong> to complete your profile.
            </p>
        </div>
    </div>
    @endif

    {{-- Patient bar --}}
    <div class="mr-patient-bar">
        <div class="mr-patient-field">
            <p>Case No</p>
            <p style="font-family:monospace;" class="text-gray-900 dark:text-white">{{ $this->patient->case_no }}</p>
        </div>
        <div class="mr-patient-field" style="flex:1;min-width:160px;">
            <p>Patient</p>
            <p class="text-gray-900 dark:text-white">
                {{ $this->patient->full_name }}
                @if($this->patient->has_incomplete_info)
                <span style="display:inline-block;background:#fef2f2;border:1px solid #fca5a5;
                             color:#b91c1c;font-size:.6rem;font-weight:800;
                             padding:1px 6px;border-radius:3px;margin-left:6px;
                             vertical-align:middle;">INCOMPLETE</span>
                @endif
            </p>
        </div>
        <div class="mr-patient-field">
            <p>Age / Sex</p>
            <p class="text-gray-700 dark:text-gray-300">{{ $this->patient->age_display }} / {{ $this->patient->sex }}</p>
        </div>
        @if($this->patient->birthday)
        <div class="mr-patient-field">
            <p>Birthday</p>
            <p class="text-gray-700 dark:text-gray-300">{{ $this->patient->birthday->format('M d, Y') }}</p>
        </div>
        @endif
        <div class="mr-patient-field" style="margin-left:auto;text-align:right;">
            <p>Total Visits</p>
            <p style="font-size:1.2rem;font-weight:700;" class="text-gray-900 dark:text-white">{{ $this->visits->count() }}</p>
        </div>
    </div>

    {{-- Section heading --}}
    <div class="mr-section-label">Visit History</div>

    @if ($this->visits->isEmpty())
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;
                    padding:48px;text-align:center;"
             class="dark:bg-gray-900 dark:border-gray-700">
            <p style="font-weight:700;font-size:.95rem;margin-bottom:5px;"
               class="text-gray-800 dark:text-white">No visit records yet.</p>
            <p style="font-size:.83rem;" class="text-gray-400">
                Your records will appear here after your first hospital visit.
            </p>
        </div>

    @else

        @foreach ($this->visits as $index => $visit)
        @php
            $isEr     = $visit->visit_type === 'ER';
            $mh       = $visit->medicalHistory;
            $labCount = $visit->labRequests->count();
            $radCount = $visit->radiologyRequests->count();
            $resultCount = $visit->labRequests->flatMap->results->count()
                         + $visit->radiologyRequests->flatMap->results->count();
            $visitNumber = $this->visits->count() - $index;

            $statusLabel = match($visit->status) {
                'registered'  => 'Registered',
                'vitals_done' => 'Vitals Recorded',
                'assessed'    => 'Assessed',
                'admitted'    => 'Admitted',
                'discharged'  => 'Discharged',
                'referred'    => 'Referred',
                default       => ucfirst($visit->status),
            };
            $statusPill = match($visit->status) {
                'registered'  => 'pill-amber',
                'vitals_done' => 'pill-sky',
                'assessed'    => 'pill-green',
                'admitted'    => 'pill-sky',
                'discharged'  => 'pill-slate',
                'referred'    => 'pill-orange',
                default       => 'pill-slate',
            };

            $dispPill = match($visit->disposition ?? '') {
                'Admitted'   => 'pill-green',
                'Discharged' => 'pill-slate',
                'Referred'   => 'pill-orange',
                'HAMA'       => 'pill-amber',
                'Expired'    => 'pill-red',
                default      => 'pill-slate',
            };

            $detailUrl = route('filament.patient.pages.view-visit-record') . '?visitId=' . $visit->id;
        @endphp

        <a href="{{ $detailUrl }}" class="mr-card">

            {{-- Top bar --}}
            <div class="mr-card-bar">
                <span class="pill pill-dark">Visit {{ $visitNumber }}</span>
                <span class="pill {{ $isEr ? 'pill-er' : 'pill-opd' }}">
                    {{ $isEr ? 'Emergency' : 'Outpatient' }}
                </span>
                <span class="pill {{ $statusPill }}">{{ $statusLabel }}</span>
                @if($visit->payment_class)
                <span class="pill pill-sky">{{ $visit->payment_class }}</span>
                @endif
                <span style="margin-left:auto;font-size:.74rem;font-weight:500;"
                      class="text-gray-400 dark:text-gray-500">
                    {{ $visit->registered_at->format('M d, Y · H:i') }}
                </span>
            </div>

            {{-- Body --}}
            <div class="mr-card-body">

                <div class="mr-field" style="flex:1;min-width:180px;">
                    <p class="mr-field-label">Chief Complaint</p>
                    <p class="mr-field-value" style="color:#111827;" class="dark:text-gray-100">
                        {{ $visit->chief_complaint }}
                    </p>
                </div>

                @if($visit->assignedDoctor)
                <div class="mr-field">
                    <p class="mr-field-label">Physician</p>
                    <p class="mr-field-value text-gray-800 dark:text-gray-200">
                        Dr. {{ $visit->assignedDoctor->name }}
                    </p>
                    @if($visit->assignedDoctor->specialty)
                    <p style="font-size:.74rem;" class="text-gray-400">{{ $visit->assignedDoctor->specialty }}</p>
                    @endif
                </div>
                @endif

                @if($mh && ($mh->diagnosis || $mh->admitting_impression))
                <div class="mr-field" style="max-width:220px;">
                    <p class="mr-field-label">Diagnosis</p>
                    <p class="mr-field-value text-gray-800 dark:text-gray-200">
                        {{ $mh->diagnosis ?? $mh->admitting_impression }}
                    </p>
                </div>
                @endif

                <div class="mr-badge-cluster">
                    @if($labCount)
                    <span class="pill pill-green">{{ $labCount }} Lab{{ $labCount > 1 ? 's' : '' }}</span>
                    @endif
                    @if($radCount)
                    <span class="pill pill-sky">{{ $radCount }} Imaging</span>
                    @endif
                    @if($resultCount)
                    <span class="pill pill-slate">{{ $resultCount }} Result{{ $resultCount > 1 ? 's' : '' }}</span>
                    @endif
                    <span class="mr-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>

            </div>

            {{-- Disposition footer --}}
            @if($visit->disposition)
            <div class="mr-disp">
                <span class="pill {{ $dispPill }}">{{ $visit->disposition }}</span>
                @if($visit->admitted_ward)
                <span style="font-size:.76rem;font-weight:500;" class="text-gray-500 dark:text-gray-400">
                    Ward: {{ $visit->admitted_ward }}
                </span>
                @endif
                @if($visit->discharged_at)
                <span style="margin-left:auto;font-size:.73rem;" class="text-gray-400">
                    {{ $visit->discharged_at->format('M d, Y') }}
                </span>
                @endif
            </div>
            @endif

        </a>
        @endforeach

    @endif

@endif
</div>

</x-filament-panels::page>