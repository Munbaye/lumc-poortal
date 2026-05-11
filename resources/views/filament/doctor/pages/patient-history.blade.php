<x-filament-panels::page>

<style>
/* ── Doctor History — Teal design tokens ── */
:root {
  --phdr-teal-50: #f0fdfa; --phdr-teal-100: #ccfbf1;
  --phdr-teal-600: #0d9488; --phdr-teal-700: #0f766e;
  --phdr-teal-900: #134e4a;
  --phdr-slate-200: #e2e8f0; --phdr-slate-400: #94a3b8;
  --phdr-slate-800: #1e293b; --phdr-radius-lg: 14px;
  --phdr-transition: 150ms cubic-bezier(0.4,0,0.2,1);
}
.dark { --phdr-slate-200: #374151; --phdr-slate-400: #6b7280; --phdr-slate-800: #1f2937; }

/* ── Page hero ── */
.ph-hero {
  background: linear-gradient(145deg, #134e4a 0%, #0f766e 40%, #0d9488 75%, #14b8a6 100%);
  padding: 20px 24px 30px; position: relative; overflow: hidden;
}
.dark .ph-hero {
  background: linear-gradient(145deg, #042f2e 0%, #134e4a 40%, #0f766e 75%, #0d9488 100%);
}
.ph-hero::before {
  content: ''; position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 80% 60% at 15% 40%, rgba(255,255,255,.09) 0%, transparent 55%),
    radial-gradient(ellipse 50% 40% at 85% 65%, rgba(255,255,255,.05) 0%, transparent 50%);
  pointer-events: none;
}
.ph-hero-top {
  display: flex; align-items: flex-start; justify-content: space-between;
  gap: 16px; flex-wrap: wrap; position: relative; z-index: 1;
}
.ph-name {
  font-size: clamp(1.1rem, 2.5vw, 1.45rem);
  font-weight: 800; color: #fff;
  letter-spacing: -.02em; line-height: 1.2;
  text-shadow: 0 1px 3px rgba(0,0,0,.18); margin: 0;
}
.ph-case {
  font-size: .73rem; color: rgba(255,255,255,.75);
  margin-top: 7px; display: flex; align-items: center; gap: 9px; flex-wrap: wrap;
}
.ph-pill {
  display: inline-flex; align-items: center;
  background: rgba(255,255,255,.14); color: #fff;
  border: 1px solid rgba(255,255,255,.25);
  font-size: .65rem; font-weight: 700;
  padding: 3px 12px; border-radius: 9999px;
  white-space: nowrap; letter-spacing: .03em;
}

/* ── Info strip ── */
.ph-strip {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  background: #fff; position: relative; z-index: 2;
  margin: -14px 22px 0;
  border-radius: var(--phdr-radius-lg);
  border-top: 2px solid rgba(13,148,136,.1);
  box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 6px 12px rgba(0,0,0,.05);
  overflow: hidden;
}
.dark .ph-strip { background: #1f2937; border-top-color: rgba(45,212,191,.12); }
.ph-strip::before {
  content: ''; position: absolute; left: 0; top: 10px; bottom: 10px; width: 3px;
  background: linear-gradient(to bottom, #0f766e, #2dd4bf);
  border-radius: 0 3px 3px 0; opacity: .5;
}
.ph-strip-cell {
  padding: 12px 18px; display: flex; flex-direction: column;
  justify-content: center; gap: 4px; position: relative; min-height: 56px;
}
.ph-strip-cell:first-child { padding-left: 22px; }
.ph-strip-cell:not(:last-child)::after {
  content: ''; position: absolute; right: 0; top: 50%;
  transform: translateY(-50%); width: 1px; height: 48%;
  background: linear-gradient(to bottom, transparent, var(--phdr-slate-200) 20%, var(--phdr-slate-200) 80%, transparent);
}
.ph-strip-label {
  font-size: .59rem; text-transform: uppercase; letter-spacing: .09em;
  color: var(--phdr-slate-400); font-weight: 700; white-space: nowrap;
}
.ph-strip-value {
  font-size: .84rem; font-weight: 700; color: var(--phdr-slate-800); line-height: 1.3;
}
.dark .ph-strip-value { color: #f3f4f6; }

/* ── Back button ── */
.btn-back {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,.11); border: 1.5px solid rgba(255,255,255,.22);
  color: rgba(255,255,255,.9); font-size: .76rem; font-weight: 600;
  padding: 8px 15px; border-radius: 6px;
  text-decoration: none; cursor: pointer; white-space: nowrap;
  transition: background var(--phdr-transition), transform var(--phdr-transition);
}
.btn-back:hover { background: rgba(255,255,255,.21); color: #fff; transform: translateY(-1px); }

/* ── Stats bar ── */
.stats-bar { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:18px; }
@media(max-width:640px) { .stats-bar { grid-template-columns:repeat(2,1fr); } }
.stat-card {
  border-radius: 12px; padding: 16px 18px;
  display: flex; align-items: center; gap: 14px;
  border: 1px solid;
}
html:not(.dark) .stat-card { background: #fff; border-color: #e5e7eb; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
html.dark .stat-card { background: #0f172a; border-color: #1e293b; }
.stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
html:not(.dark) .si-teal  { background: #f0fdfa; color: #0d9488; }
html:not(.dark) .si-red   { background: #fee2e2; color: #991b1b; }
html:not(.dark) .si-blue  { background: #eff6ff; color: #1d4ed8; }
html:not(.dark) .si-green { background: #f0fdf4; color: #059669; }
html.dark .si-teal  { background: #042f2e; color: #5eead4; }
html.dark .si-red   { background: #450a0a; color: #fca5a5; }
html.dark .si-blue  { background: #0c1a2e; color: #7dd3fc; }
html.dark .si-green { background: #052e16; color: #6ee7b7; }
.stat-label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; }
.stat-value { font-size:2rem; font-weight:800; line-height:1.1; color:#111827; }
.dark .stat-value { color:#f1f5f9; }

/* ── Visits table ── */
.vt-wrap { background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow-x:auto; -webkit-overflow-scrolling:touch; }
.dark .vt-wrap { background:#1f2937; border-color:#374151; }
.vt-table { width:100%; min-width:780px; border-collapse:collapse; font-size:.83rem; }
.vt-table thead tr { background:#f9fafb; border-bottom:2px solid #e5e7eb; }
.dark .vt-table thead tr { background:#111827; border-bottom-color:#374151; }
.vt-table th { padding:10px 14px; text-align:left; font-size:.64rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#9ca3af; white-space:nowrap; }
.vt-table td { padding:11px 14px; border-bottom:1px solid #f3f4f6; vertical-align:top; }
.dark .vt-table td { border-bottom-color:#1f2937; }
.vt-table tbody tr { cursor:pointer; transition:background .1s; }
.vt-table tbody tr:hover td { background:#f0fdfa; }
.dark .vt-table tbody tr:hover td { background:rgba(13,148,136,.06); }
.vt-table tbody tr:last-child td { border-bottom:none; }
.col-date    { width:130px; min-width:130px; }
.col-type    { width:90px;  min-width:90px; }
.col-cc      { width:18%;   min-width:140px; }
.col-dx      { width:18%;   min-width:140px; }
.col-dr      { width:140px; min-width:120px; }
.col-status  { width:110px; min-width:100px; }
.col-days    { width:72px;  min-width:60px; text-align:center; }
.col-records { width:110px; min-width:100px; }
.col-arrow   { width:32px;  min-width:32px; text-align:center; }
.type-badge { display:inline-block; padding:2px 9px; border-radius:9999px; font-size:.7rem; font-weight:700; white-space:nowrap; }
.type-er  { background:#fee2e2; color:#991b1b; }
.type-opd { background:#f0fdfa; color:#0f766e; }
.status-badge { display:inline-block; padding:2px 9px; border-radius:9999px; font-size:.7rem; font-weight:700; white-space:nowrap; }
.status-admitted   { background:#d1fae5; color:#065f46; }
.status-discharged { background:#f3f4f6; color:#374151; }
.status-registered { background:#fef9c3; color:#854d0e; }
.status-assessed   { background:#e0f2fe; color:#0c4a6e; }
.status-referred   { background:#fef3c7; color:#92400e; }
.status-vitals     { background:#f0fdfa; color:#0f766e; }
.chip { font-size:.62rem; font-weight:700; padding:1px 5px; border-radius:4px; }
.chip-er  { background:#f0fdf4; color:#065f46; }
.chip-adm { background:#f0fdfa; color:#0f766e; }
.chip-ctc { background:#fffbeb; color:#92400e; }
.chip-vs  { background:#f3f4f6; color:#374151; }
.chip-rx  { background:#f5f3ff; color:#5b21b6; }
.truncate { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%; display:block; }
.visit-date { font-family:monospace; font-size:.78rem; font-weight:600; color:#111827; }
.dark .visit-date { color:#f3f4f6; }
.visit-ago { font-size:.67rem; color:#9ca3af; margin-top:1px; }
.row-arrow { color:#9ca3af; }
.vt-table tbody tr:hover .row-arrow { color:#0d9488; }
.current-row td { background:#f0fdfa !important; }
.current-badge { font-size:.62rem; font-weight:700; background:#ccfbf1; color:#0f766e; padding:1px 6px; border-radius:9999px; display:block; margin-top:2px; }
.empty-state { text-align:center; padding:56px 24px; }
.empty-icon  { font-size:2.8rem; margin-bottom:10px; }
.empty-title { font-size:.95rem; font-weight:700; color:#374151; margin-bottom:4px; }
.dark .empty-title { color:#e5e7eb; }
.empty-sub { font-size:.82rem; color:#9ca3af; }
</style>

@php $patient = $this->patient; @endphp

@if($patient)
<div class="ph-hero">
    <div class="ph-hero-top">
        <div>
            <p class="ph-name">{{ $patient->full_name }}</p>
            <p class="ph-case">
                <span style="font-family:monospace;opacity:.8;">{{ $patient->case_no }}</span>
                @if($patient->age_display)<span class="ph-pill">{{ $patient->age_display }}</span>@endif
                @if($patient->sex)<span class="ph-pill">{{ $patient->sex }}</span>@endif
                @if($patient->has_incomplete_info)
                <span class="ph-pill" style="background:rgba(220,38,38,.3);">
                    <x-heroicon-o-exclamation-triangle class="w-3 h-3 inline mr-1" />Incomplete Info
                </span>
                @endif
            </p>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            @if($patient->contact_number)
            <span class="ph-pill"><x-heroicon-o-phone class="w-3 h-3 inline mr-1" />{{ $patient->contact_number }}</span>
            @endif
            <a href="{{ $this->getPatientListUrl() }}" class="btn-back">
                <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
                Patient List
            </a>
        </div>
    </div>
</div>

{{-- Floating info strip --}}
<div class="ph-strip" style="margin-bottom:22px;">
    <div class="ph-strip-cell">
        <p class="ph-strip-label">Total Visits</p>
        <p class="ph-strip-value" style="color:#0d9488;">{{ $visits->count() }}</p>
    </div>
    <div class="ph-strip-cell">
        <p class="ph-strip-label">ER Visits</p>
        <p class="ph-strip-value" style="color:#991b1b;">{{ $visits->where('visit_type','ER')->count() }}</p>
    </div>
    <div class="ph-strip-cell">
        <p class="ph-strip-label">OPD Visits</p>
        <p class="ph-strip-value" style="color:#1d4ed8;">{{ $visits->where('visit_type','OPD')->count() }}</p>
    </div>
    <div class="ph-strip-cell">
        <p class="ph-strip-label">Admissions</p>
        <p class="ph-strip-value" style="color:#065f46;">{{ $visits->filter(fn($v) => $v->clerk_admitted_at !== null)->count() }}</p>
    </div>
    @if($patient->address)
    <div class="ph-strip-cell" style="flex:2;">
        <p class="ph-strip-label">Address</p>
        <p class="ph-strip-value" style="font-size:.78rem;font-weight:600;">{{ \Str::limit($patient->address, 50) }}</p>
    </div>
    @endif
</div>

@php $visits = $this->visits; @endphp

<div class="vt-wrap">
    @if($visits->isEmpty())
    <div class="empty-state">
        <div class="empty-icon"><x-heroicon-o-rectangle-stack class="w-12 h-12 inline text-gray-400" /></div>
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
                $hvHx       = $visit->medicalHistory;
                $dx         = $visit->admitting_diagnosis ?? $hvHx?->diagnosis ?? null;
                $days       = null;
                if ($visit->clerk_admitted_at && $visit->discharged_at) {
                    $days = $visit->clerk_admitted_at->diff($visit->discharged_at)->days;
                }
                $isCurrent  = $visit->status === 'admitted' && !$visit->discharged_at;
                $daysSoFar  = ($isCurrent && $visit->clerk_admitted_at)
                    ? $visit->clerk_admitted_at->diff(now())->days
                    : null;
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
                onclick="window.location='{{ $this->getOpenChartUrl($visit->id) }}'"
                wire:key="v-{{ $visit->id }}">

                <td class="col-date">
                    <p class="visit-date">{{ $visit->registered_at->timezone('Asia/Manila')->format('M j, Y') }}</p>
                    <p class="visit-ago">{{ $visit->registered_at->timezone('Asia/Manila')->format('H:i') }} · {{ $visit->registered_at->diffForHumans() }}</p>
                </td>

                <td class="col-type">
                    <span class="type-badge {{ $visit->visit_type === 'ER' ? 'type-er' : 'type-opd' }}">
                        {{ $visit->visit_type === 'ER' ? 'ER' : 'OPD' }}
                    </span>
                    @if($isCurrent)<span class="current-badge">Active</span>@endif
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

                <td class="col-days" style="text-align:center;white-space:nowrap;">
                    @if($days !== null)
                        <span style="font-size:.92rem;font-weight:800;color:#374151;">{{ (int)$days }}</span>
                        <span style="font-size:.64rem;color:#9ca3af;display:block;margin-top:1px;">day{{ (int)$days !== 1 ? 's' : '' }}</span>
                    @elseif($daysSoFar !== null)
                        <span style="font-size:.92rem;font-weight:800;color:#059669;">{{ (int)$daysSoFar }}</span>
                        <span style="font-size:.64rem;color:#9ca3af;display:block;margin-top:1px;">so far</span>
                    @else
                        <span style="color:#d1d5db;font-size:.8rem;">—</span>
                    @endif
                </td>

                <td class="col-records">
                    <div style="display:flex;gap:3px;flex-wrap:wrap;">
                        @if($visit->erRecord)       <span class="chip chip-er">ER</span>@endif
                        @if($visit->admissionRecord)<span class="chip chip-adm">ADM</span>@endif
                        @if($visit->consentRecord)  <span class="chip chip-ctc">CTC</span>@endif
                        @if($visit->vitals->isNotEmpty())       <span class="chip chip-vs">VS</span>@endif
                        @if($visit->doctorsOrders->isNotEmpty())<span class="chip chip-rx">Rx</span>@endif
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
    Tags: <strong>ER</strong> ER-001 · <strong>ADM</strong> Admission Record ·
    <strong>CTC</strong> Consent to Care · <strong>VS</strong> Vital Signs ·
    <strong>Rx</strong> Doctor's Orders · Active = currently admitted
</p>
@endif

@else
<div style="text-align:center;padding:60px 20px;">
    <p style="color:#9ca3af;margin-bottom:10px;">Patient not found.</p>
    <a href="{{ $this->getPatientListUrl() }}" style="color:#2563eb;font-size:.875rem;">← Back</a>
</div>
@endif

</x-filament-panels::page>