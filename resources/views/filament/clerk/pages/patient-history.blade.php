<x-filament-panels::page>

<style>
/* ── Patient header ───────────────────────────────────────────── */
.ph-header {
    background: linear-gradient(135deg, #1e3a5f, #1d4ed8);
    border-radius: 10px; padding: 16px 22px; margin-bottom: 20px;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
}
.ph-name { font-size: 1.05rem; font-weight: 800; color: #fff; }
.ph-case { font-family: monospace; font-size: .78rem; color: #93c5fd; margin-top: 2px; }
.ph-pills { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
.ph-pill {
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.22);
    border-radius: 6px; padding: 4px 12px; font-size: .78rem;
    color: #e0f2fe; font-weight: 600;
}

/* ── Back button ──────────────────────────────────────────────── */
.btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    background: none; border: 1px solid #e5e7eb; border-radius: 7px;
    padding: 8px 16px; font-size: .82rem; font-weight: 600;
    color: #374151; cursor: pointer; text-decoration: none; margin-bottom: 16px;
}
.btn-back:hover { background: #f3f4f6; }
.dark .btn-back { color: #e5e7eb; border-color: #374151; }
.dark .btn-back:hover { background: #374151; }

/* ── Stats bar ────────────────────────────────────────────────── */
.stats-bar {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 18px;
}
@media (max-width: 640px) { .stats-bar { grid-template-columns: repeat(2, 1fr); } }
.stat-card {
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 8px; padding: 12px 16px;
}
.dark .stat-card { background: #1f2937; border-color: #374151; }
.stat-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #9ca3af; }
.stat-value { font-size: 1.4rem; font-weight: 800; color: #111827; margin-top: 2px; }
.dark .stat-value { color: #f3f4f6; }

/* ── Table wrapper — horizontal scroll on small screens ──────── */
.vt-wrap {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow-x: auto;           /* scroll if too wide */
    -webkit-overflow-scrolling: touch;
}
.dark .vt-wrap { background: #1f2937; border-color: #374151; }

.vt-table {
    width: 100%;
    min-width: 720px;           /* prevents crushing on narrow viewports */
    border-collapse: collapse;
    font-size: .855rem;
}
.vt-table thead tr {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}
.dark .vt-table thead tr { background: #111827; border-bottom-color: #374151; }
.vt-table th {
    padding: 10px 12px;
    text-align: left;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #6b7280;
    white-space: nowrap;
}
.vt-table td {
    padding: 12px 12px;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}
.dark .vt-table td { border-bottom-color: #374151; }
.vt-table tbody tr { cursor: pointer; transition: background .12s; }
.vt-table tbody tr:hover td { background: #eff6ff; }
.dark .vt-table tbody tr:hover td { background: rgba(29,78,216,.07); }
.vt-table tbody tr:last-child td { border-bottom: none; }

/* Column widths */
.col-date    { width: 110px; min-width: 110px; }
.col-type    { width: 80px;  min-width: 80px;  }
.col-cc      { width: 160px; min-width: 140px; }
.col-dx      { width: 160px; min-width: 140px; }
.col-dr      { width: 130px; min-width: 110px; }
.col-status  { width: 100px; min-width: 90px;  }
.col-days    { width: 70px;  min-width: 60px;  text-align: center; }
.col-records { width: 100px; min-width: 90px;  }
.col-arrow   { width: 28px;  min-width: 28px;  text-align: center; }

/* Visit type badges */
.type-badge { display: inline-block; padding: 2px 9px; border-radius: 9999px; font-size: .7rem; font-weight: 700; white-space: nowrap; }
.type-er    { background: #fee2e2; color: #991b1b; }
.type-opd   { background: #eff6ff; color: #1d4ed8; }

/* Status badges */
.status-badge       { display: inline-block; padding: 2px 9px; border-radius: 9999px; font-size: .7rem; font-weight: 700; white-space: nowrap; }
.status-admitted    { background: #d1fae5; color: #065f46; }
.status-discharged  { background: #f3f4f6; color: #374151; }
.status-registered  { background: #fef9c3; color: #854d0e; }
.status-assessed    { background: #e0f2fe; color: #0c4a6e; }
.status-referred    { background: #fef3c7; color: #92400e; }
.status-vitals      { background: #f0fdf4; color: #166534; }

/* Record chips */
.chip { font-size: .62rem; font-weight: 700; padding: 1px 5px; border-radius: 4px; }
.chip-er   { background: #f0fdf4; color: #065f46; }
.chip-adm  { background: #eff6ff; color: #1d4ed8; }
.chip-ctc  { background: #fffbeb; color: #92400e; }
.chip-vs   { background: #f3f4f6; color: #374151; }
.chip-rx   { background: #f5f3ff; color: #5b21b6; }

/* Text truncation helpers */
.truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; display: block; }

/* Date/meta */
.visit-date { font-family: monospace; font-size: .78rem; font-weight: 600; color: #111827; }
.dark .visit-date { color: #f3f4f6; }
.visit-ago  { font-size: .67rem; color: #9ca3af; margin-top: 1px; }

/* Row arrow */
.row-arrow { color: #9ca3af; }
.vt-table tbody tr:hover .row-arrow { color: #1d4ed8; }

/* Currently admitted highlight */
.current-row td { background: #fffbeb !important; }
.current-badge { font-size: .62rem; font-weight: 700; background: #fef3c7; color: #92400e; padding: 1px 6px; border-radius: 9999px; margin-left: 5px; }

/* Empty state */
.empty-state { text-align: center; padding: 56px 24px; }
.empty-icon  { font-size: 2.8rem; margin-bottom: 10px; }
.empty-title { font-size: .95rem; font-weight: 700; color: #374151; margin-bottom: 4px; }
.dark .empty-title { color: #e5e7eb; }
.empty-sub   { font-size: .82rem; color: #9ca3af; }
</style>

@php $patient = $this->patient; @endphp

@if($patient)

{{-- Back --}}
<a href="{{ $this->getPatientListUrl() }}" class="btn-back">← Back to Patient Visits</a>

{{-- Patient header --}}
<div class="ph-header">
    <div>
        <p class="ph-name">{{ $patient->full_name }}</p>
        <p class="ph-case">
            {{ $patient->case_no }}
            @if($patient->age_display) · {{ $patient->age_display }} @endif
            @if($patient->sex) · {{ $patient->sex }} @endif
        </p>
    </div>
    <div class="ph-pills">
        @if($patient->address)
            <span class="ph-pill" style="font-size:.72rem;">{{ \Str::limit($patient->address, 40) }}</span>
        @endif
        @if($patient->contact_number)
            <span class="ph-pill">📞 {{ $patient->contact_number }}</span>
        @endif
        @if($patient->has_incomplete_info)
            <span class="ph-pill" style="background:rgba(220,38,38,.3);">⚠️ Incomplete Info</span>
        @endif
    </div>
</div>

@php
    $visits      = $this->visits;
    $totalVisits = $visits->count();
    $erVisits    = $visits->where('visit_type', 'ER')->count();
    $opdVisits   = $visits->where('visit_type', 'OPD')->count();
    $admissions  = $visits->filter(fn ($v) => $v->clerk_admitted_at !== null)->count();
@endphp

{{-- Stats --}}
<div class="stats-bar">
    <div class="stat-card">
        <p class="stat-label">Total Visits</p>
        <p class="stat-value">{{ $totalVisits }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">ER Visits</p>
        <p class="stat-value" style="color:#991b1b;">{{ $erVisits }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">OPD Visits</p>
        <p class="stat-value" style="color:#1d4ed8;">{{ $opdVisits }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Admissions</p>
        <p class="stat-value" style="color:#065f46;">{{ $admissions }}</p>
    </div>
</div>

{{-- Visit table --}}
<div class="vt-wrap">
    @if($visits->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">🗂️</div>
        <p class="empty-title">No visits found</p>
        <p class="empty-sub">This patient has no recorded visits yet.</p>
    </div>
    @else
    <table class="vt-table">
        <thead>
            <tr>
                <th class="col-date">Date</th>
                <th class="col-type">Entry</th>
                <th class="col-cc">Chief Complaint</th>
                <th class="col-dx">Diagnosis</th>
                <th class="col-dr">Physician</th>
                <th class="col-status">Status</th>
                <th class="col-days">Days</th>
                <th class="col-records">Records</th>
                <th class="col-arrow"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($visits as $visit)
            @php
                $hvHx    = $visit->medicalHistory;
                $dx      = $visit->admitting_diagnosis ?? $hvHx?->diagnosis ?? null;

                // Whole-number days only
                $days = null;
                if ($visit->clerk_admitted_at && $visit->discharged_at) {
                    $days = (int) $visit->clerk_admitted_at->diffInDays($visit->discharged_at);
                }

                $isCurrent = $visit->status === 'admitted' && !$visit->discharged_at;

                // Days "so far" for currently admitted
                $daysSoFar = null;
                if ($isCurrent && $visit->clerk_admitted_at) {
                    $daysSoFar = (int) $visit->clerk_admitted_at->diffInDays(now());
                }

                $statusClass = match($visit->status) {
                    'admitted'    => 'status-admitted',
                    'discharged'  => 'status-discharged',
                    'assessed'    => 'status-assessed',
                    'referred'    => 'status-referred',
                    'vitals_done' => 'status-vitals',
                    default       => 'status-registered',
                };
                $statusLabel = match($visit->status) {
                    'vitals_done' => 'Vitals Done',
                    default       => ucfirst(str_replace('_', ' ', $visit->status)),
                };
            @endphp

            <tr class="{{ $isCurrent ? 'current-row' : '' }}"
                onclick="window.location='{{ $this->getViewVisitUrl($visit->id) }}'"
                wire:key="visit-{{ $visit->id }}">

                <td class="col-date">
                    <p class="visit-date">{{ $visit->registered_at->timezone('Asia/Manila')->format('M j, Y') }}</p>
                    <p class="visit-ago">{{ $visit->registered_at->timezone('Asia/Manila')->format('H:i') }}</p>
                </td>

                <td class="col-type">
                    <span class="type-badge {{ $visit->visit_type === 'ER' ? 'type-er' : 'type-opd' }}">
                        {{ $visit->visit_type === 'ER' ? '🚑 ER' : '📋 OPD' }}
                    </span>
                    @if($isCurrent)
                        <span class="current-badge" style="display:block;margin-top:3px;">Active</span>
                    @endif
                </td>

                <td class="col-cc">
                    <span class="truncate" style="max-width:150px;" title="{{ $visit->chief_complaint }}">
                        {{ $visit->chief_complaint ?? '—' }}
                    </span>
                </td>

                <td class="col-dx">
                    <span class="truncate" style="max-width:150px;font-size:.78rem;color:#6b7280;" title="{{ $dx }}">
                        {{ $dx ? \Str::limit($dx, 40) : '—' }}
                    </span>
                </td>

                <td class="col-dr">
                    @if($hvHx?->doctor?->name)
                        <span style="font-size:.78rem;font-weight:600;color:#374151;">Dr. {{ $hvHx->doctor->name }}</span>
                    @else
                        <span style="color:#9ca3af;font-size:.75rem;">—</span>
                    @endif
                    @if($visit->payment_class)
                        <p style="font-size:.67rem;color:#9ca3af;margin-top:2px;">{{ $visit->payment_class }}</p>
                    @endif
                </td>

                <td class="col-status">
                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>

                <td class="col-days">
                    @if($days !== null)
                        <span style="font-weight:700;color:#374151;">{{ $days }}</span>
                        <span style="font-size:.67rem;color:#9ca3af;display:block;">day{{ $days !== 1 ? 's' : '' }}</span>
                    @elseif($daysSoFar !== null)
                        <span style="font-weight:700;color:#059669;">{{ $daysSoFar }}</span>
                        <span style="font-size:.62rem;color:#9ca3af;display:block;">so far</span>
                    @else
                        <span style="color:#9ca3af;font-size:.75rem;">—</span>
                    @endif
                </td>

                <td class="col-records">
                    <div style="display:flex;gap:3px;flex-wrap:wrap;">
                        @if($visit->erRecord)      <span class="chip chip-er">ER</span>@endif
                        @if($visit->admissionRecord)<span class="chip chip-adm">ADM</span>@endif
                        @if($visit->consentRecord)  <span class="chip chip-ctc">CTC</span>@endif
                        @if($visit->vitals->isNotEmpty()) <span class="chip chip-vs">VS</span>@endif
                        @if($visit->doctorsOrders->isNotEmpty()) <span class="chip chip-rx">Rx</span>@endif
                    </div>
                </td>

                <td class="col-arrow"><span class="row-arrow">→</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@if($visits->isNotEmpty())
<p style="font-size:.7rem;color:#9ca3af;margin-top:8px;">
    Tags: <strong>ER</strong> ER-001 &nbsp;·&nbsp;
    <strong>ADM</strong> Admission Record &nbsp;·&nbsp;
    <strong>CTC</strong> Consent to Care &nbsp;·&nbsp;
    <strong>VS</strong> Vital Signs &nbsp;·&nbsp;
    <strong>Rx</strong> Doctor's Orders
    &nbsp;·&nbsp; 🟡 = currently admitted
</p>
@endif

@else
<div style="text-align:center;padding:60px 20px;">
    <p style="color:#9ca3af;margin-bottom:10px;">Patient not found.</p>
    <a href="{{ $this->getPatientListUrl() }}" style="color:#1d4ed8;font-size:.875rem;">← Back</a>
</div>
@endif

</x-filament-panels::page>