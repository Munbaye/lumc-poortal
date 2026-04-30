<x-filament-panels::page>

@php
    $nurse = auth()->user();
    $userInitials = strtoupper(
        substr($nurse->first_name ?? $nurse->name ?? 'N', 0, 1) .
        substr($nurse->last_name ?? '', 0, 1)
    );
@endphp

<style>
/* ════════════════════════════════════════════════════════════════
   NURSE — PATIENT LIST DASHBOARD
   Rose/Pink · light + dark safe
   #be123c primary — DO NOT override fi-topbar or fi-sidebar here
   Those are handled by NursePanelProvider.php
════════════════════════════════════════════════════════════════ */

:root {
    --np:       #be123c;
    --npa:      #f43f5e;
    --np-dark:  #9f1239;
    --np-light: #fff1f2;
    --np-mid:   #fecdd3;
    --np-sh:    rgba(190,18,60,.1);
    --np-rgb:   190,18,60;
}

/* ── Hero header ───────────────────────────────────────────── */
.dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
    padding: 22px 26px;
    background: linear-gradient(135deg, #f43f5e 0%, #be123c 55%, #881337 100%);
    border-radius: 16px;
    position: relative;
    overflow: hidden;
}
.dash-header::before {
    content: '';
    position: absolute;
    width: 300px; height: 300px;
    background: rgba(255,255,255,.06);
    border-radius: 50%;
    top: -80px; right: -60px;
    pointer-events: none;
}
.dash-header::after {
    content: '🏥';
    position: absolute; right: 26px; top: 50%;
    transform: translateY(-50%);
    font-size: 4.5rem; opacity: .08;
    pointer-events: none; line-height: 1;
}
.dash-left { position: relative; z-index: 1; }
.dash-eyebrow { display: flex; align-items: center; gap: 7px; margin-bottom: 5px; }
.eyebrow-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: rgba(255,255,255,.7);
    animation: blink 2.4s ease-in-out infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.35} }
.eyebrow-label { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.75); }
.dash-title { font-size:1.55rem; font-weight:800; color:#fff; margin:0 0 5px; letter-spacing:-.025em; line-height:1.2; }
.dash-tagline { font-size:.78rem; color:rgba(255,255,255,.72); }

.dash-right {
    position: relative; z-index: 1;
    display: flex; flex-direction: column;
    align-items: flex-end; gap: 7px; flex-shrink: 0;
}
.user-chip {
    display: flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 9999px; padding: 5px 12px 5px 6px;
    backdrop-filter: blur(4px);
}
.user-avatar {
    width: 26px; height: 26px; border-radius: 50%;
    background: #fff; color: var(--np);
    font-size: .65rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.user-name  { font-size:.78rem; font-weight:600; color:#fff; }
.dash-clock { font-size:.68rem; color:rgba(255,255,255,.62); font-variant-numeric:tabular-nums; }

/* ── Stat cards ────────────────────────────────────────────── */
.stats-bar { display:grid; grid-template-columns:repeat(3,1fr); gap:13px; margin-bottom:22px; }

.stat-card { border-radius:14px; padding:18px 20px; display:flex; align-items:center; gap:16px; border:1px solid; transition:box-shadow .18s,transform .18s; }
html:not(.dark) .stat-card { background:#fff; border-color:#e5e7eb; box-shadow:0 1px 4px rgba(0,0,0,.05); }
html.dark       .stat-card { background:#0f172a; border-color:#1e293b; }
.stat-card:hover { box-shadow:0 6px 22px rgba(0,0,0,.08); transform:translateY(-2px); }
html:not(.dark) .stat-card.is-accent { border-color:var(--np-mid); background:linear-gradient(140deg,var(--np-light) 0%,#fff 65%); }
html.dark       .stat-card.is-accent { border-color:#4c0519; background:linear-gradient(140deg,rgba(190,18,60,.12) 0%,#0f172a 65%); }

.stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
html:not(.dark) .si-rose  { background:#fff1f2; }
html:not(.dark) .si-amber { background:#fef3c7; }
html:not(.dark) .si-blue  { background:#eff6ff; }
html.dark .si-rose  { background:#4c0519; }
html.dark .si-amber { background:#1c1400; }
html.dark .si-blue  { background:#0c1a2e; }

.stat-label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin-bottom:2px; }
html:not(.dark) .stat-label { color:#9ca3af; }
html.dark       .stat-label { color:#475569; }

.stat-value { font-size:2.2rem; font-weight:800; line-height:1.1; letter-spacing:-.03em; font-variant-numeric:tabular-nums; }
html:not(.dark) .stat-value { color:#111827; }
html.dark       .stat-value { color:#f1f5f9; }

.stat-sub { font-size:.75rem; margin-top:2px; }
html:not(.dark) .stat-sub { color:#6b7280; }
html.dark       .stat-sub { color:#475569; }

/* ── Filter bar ────────────────────────────────────────────── */
.filter-bar { display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap; }

.view-toggle { display:inline-flex; border-radius:10px; padding:3px; border:1px solid; }
html:not(.dark) .view-toggle { background:#f9fafb; border-color:#e5e7eb; }
html.dark       .view-toggle { background:#0f172a; border-color:#1e293b; }

.vt-btn { padding:6px 16px; border-radius:7px; font-size:.78rem; font-weight:600; color:#6b7280; background:none; border:none; cursor:pointer; transition:all .15s; }
html:not(.dark) .vt-btn.active { background:#fff; color:var(--np); box-shadow:0 1px 4px rgba(0,0,0,.08); font-weight:700; }
html.dark       .vt-btn.active { background:#1e293b; color:#fda4af; box-shadow:0 1px 4px rgba(0,0,0,.3); font-weight:700; }

.search-wrap { flex:1; min-width:220px; position:relative; }
.search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); pointer-events:none; font-size:.82rem; }
html:not(.dark) .search-icon { color:#9ca3af; }
html.dark       .search-icon { color:#475569; }

.search-input {
    width:100%; padding:9px 14px 9px 36px;
    border:1.5px solid; border-radius:10px; font-size:.83rem; outline:none;
    transition:border-color .15s,box-shadow .15s;
}
html:not(.dark) .search-input { background:#fff; border-color:#e5e7eb; color:#111827; }
html:not(.dark) .search-input:focus { border-color:var(--np); box-shadow:0 0 0 3px var(--np-sh); }
html:not(.dark) .search-input::placeholder { color:#9ca3af; }
html.dark       .search-input { background:#0f172a; border-color:#1e293b; color:#e2e8f0; }
html.dark       .search-input:focus { border-color:var(--npa); box-shadow:0 0 0 3px rgba(244,63,94,.15); }
html.dark       .search-input::placeholder { color:#334155; }

.select-wrap { min-width:160px; }
.filter-select {
    width:100%; padding:9px 32px 9px 14px;
    border:1.5px solid; border-radius:10px; font-size:.83rem; outline:none; cursor:pointer;
    appearance:none; -webkit-appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 12px center; background-size:12px;
    transition:border-color .15s;
}
html:not(.dark) .filter-select { background-color:#fff; border-color:#e5e7eb; color:#111827; }
html:not(.dark) .filter-select:focus { border-color:var(--np); box-shadow:0 0 0 3px var(--np-sh); }
html.dark       .filter-select { background-color:#0f172a; border-color:#1e293b; color:#e2e8f0; }
html.dark       .filter-select:focus { border-color:var(--npa); }

.filter-badge { font-size:.72rem; padding:3px 10px; border-radius:9999px; font-weight:700; white-space:nowrap; }
html:not(.dark) .filter-badge { background:var(--np-light); color:var(--np-dark); }
html.dark       .filter-badge { background:#4c0519; color:#fda4af; }

/* ── Patients table ────────────────────────────────────────── */
.patients-table-wrap { border-radius:14px; overflow:hidden; border:1px solid; }
html:not(.dark) .patients-table-wrap { background:#fff; border-color:#e5e7eb; }
html.dark       .patients-table-wrap { background:#0f172a; border-color:#1e293b; }

.patients-table { width:100%; border-collapse:collapse; font-size:.83rem; }

.patients-table thead tr { border-bottom:1px solid; }
html:not(.dark) .patients-table thead tr { background:#f9fafb; border-bottom-color:#e5e7eb; }
html.dark       .patients-table thead tr { background:#0a0f1a; border-bottom-color:#1e293b; }

.patients-table th { padding:10px 15px; text-align:left; font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; white-space:nowrap; }
html:not(.dark) .patients-table th { color:#9ca3af; }
html.dark       .patients-table th { color:#475569; }

.patients-table td { padding:12px 15px; border-bottom:1px solid; vertical-align:middle; }
html:not(.dark) .patients-table td { border-bottom-color:#f3f4f6; color:#374151; }
html.dark       .patients-table td { border-bottom-color:#1e293b; color:#cbd5e1; }
.patients-table tbody tr:last-child td { border-bottom:none !important; }
.patients-table tbody tr { cursor:pointer; transition:background .1s; }
html:not(.dark) .patients-table tbody tr:hover td { background:var(--np-light); }
html.dark       .patients-table tbody tr:hover td { background:rgba(190,18,60,.07); }

.pt-name { font-weight:700; font-size:.85rem; }
html:not(.dark) .pt-name { color:#111827; }
html.dark       .pt-name { color:#f1f5f9; }
.pt-case { font-family:monospace; font-size:.7rem; color:#9ca3af; margin-top:2px; }

.svc-badge { display:inline-block; padding:2px 9px; border-radius:9999px; font-size:.7rem; font-weight:700; }
html:not(.dark) .svc-badge { background:#fce7f3; color:#9d174d; }
html.dark       .svc-badge { background:#4c0519; color:#fda4af; }

.pay-badge { display:inline-block; padding:2px 9px; border-radius:9999px; font-size:.68rem; font-weight:700; }
html:not(.dark) .pay-charity { background:#d1fae5; color:#065f46; }
html:not(.dark) .pay-private { background:#f3f4f6; color:#374151; }
html.dark .pay-charity { background:#052e16; color:#6ee7b7; }
html.dark .pay-private { background:#1e293b; color:#94a3b8; }

.orders-badge { display:inline-flex; align-items:center; justify-content:center; min-width:26px; height:22px; padding:0 7px; border-radius:9999px; font-size:.72rem; font-weight:800; }
.orders-badge.has-orders { background:#fef3c7; color:#92400e; }
.orders-badge.no-orders  { background:#d1fae5; color:#065f46; }
html.dark .orders-badge.has-orders { background:#1c1400; color:#fde047; }
html.dark .orders-badge.no-orders  { background:#052e16; color:#6ee7b7; }

.status-pill { display:inline-block; padding:2px 9px; border-radius:9999px; font-size:.7rem; font-weight:700; }
.s-admitted   { background:#d1fae5; color:#065f46; }
.s-discharged { background:#f3f4f6; color:#374151; }
.s-registered { background:#fef9c3; color:#854d0e; }
.s-assessed   { background:#e0f2fe; color:#0c4a6e; }
.s-referred   { background:#fef3c7; color:#92400e; }
.s-vitals     { background:#f0fdf4; color:#166534; }
html.dark .s-admitted   { background:#052e16; color:#6ee7b7; }
html.dark .s-discharged { background:#1e293b; color:#94a3b8; }
html.dark .s-registered { background:#1c1400; color:#fde047; }
html.dark .s-assessed   { background:#0c1a2e; color:#7dd3fc; }
html.dark .s-referred   { background:#1c1400; color:#fde047; }
html.dark .s-vitals     { background:#052e16; color:#86efac; }

.type-er  { background:#fee2e2; color:#991b1b; font-size:.7rem; font-weight:700; padding:2px 7px; border-radius:9999px; }
.type-opd { background:#eff6ff; color:#1d4ed8; font-size:.7rem; font-weight:700; padding:2px 7px; border-radius:9999px; }
html.dark .type-er  { background:#450a0a; color:#fca5a5; }
html.dark .type-opd { background:#0c1a2e; color:#7dd3fc; }

.adm-time { font-size:.78rem; white-space:nowrap; }
html:not(.dark) .adm-time { color:#374151; }
html.dark       .adm-time { color:#cbd5e1; }
.adm-ago { font-size:.68rem; color:#9ca3af; margin-top:2px; }

.btn-open-chart {
    display:inline-flex; align-items:center; gap:5px;
    background:var(--np); color:#fff; border:none;
    border-radius:8px; padding:6px 14px;
    font-size:.76rem; font-weight:700;
    cursor:pointer; white-space:nowrap;
    transition:background .15s,transform .15s;
}
.btn-open-chart:hover { background:var(--np-dark); transform:translateX(2px); }

/* ── Empty state ───────────────────────────────────────────── */
.empty-state { text-align:center; padding:60px 20px; }
.empty-icon-wrap { width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; margin:0 auto 14px; }
html:not(.dark) .empty-icon-wrap { background:var(--np-light); }
html.dark       .empty-icon-wrap { background:#4c0519; }
.empty-title { font-size:.9rem; font-weight:700; margin-bottom:4px; }
html:not(.dark) .empty-title { color:#111827; }
html.dark       .empty-title { color:#f1f5f9; }
.empty-sub { font-size:.78rem; color:#9ca3af; }

.pag-wrap { padding:12px 15px; border-top:1px solid; }
html:not(.dark) .pag-wrap { border-top-color:#f3f4f6; }
html.dark       .pag-wrap { border-top-color:#1e293b; }

@media(max-width:768px) {
    .stats-bar { grid-template-columns:1fr; }
    .dash-header { flex-direction:column; gap:12px; padding:16px; }
    .dash-header::after { display:none; }
    .dash-right { align-items:flex-start; flex-direction:row; flex-wrap:wrap; gap:8px; }
    .filter-bar { flex-direction:column; align-items:stretch; }
    .search-wrap { min-width:unset; width:100%; }
    .patients-table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .patients-table { min-width:600px; }
}
</style>

{{-- ══ HERO HEADER ══ --}}
<div class="dash-header">
    <div class="dash-left">
        <div class="dash-eyebrow">
            <div class="eyebrow-dot"></div>
            <span class="eyebrow-label">Active · LUMC Portal</span>
        </div>
        <h1 class="dash-title">Nurse Dashboard</h1>
        <p class="dash-tagline">🏥 Nursing Services · Patient Care</p>
    </div>
    <div class="dash-right">
        <div class="user-chip">
            <div class="user-avatar">{{ $userInitials }}</div>
            <span class="user-name">{{ $nurse->name ?? 'Nurse' }}</span>
        </div>
        <span class="dash-clock" id="nurse-clock">{{ now()->timezone('Asia/Manila')->format('D, M j · H:i') }}</span>
    </div>
</div>

{{-- ══ STAT CARDS ══ --}}
<div class="stats-bar">
    <div class="stat-card is-accent">
        <div class="stat-icon si-rose">🏥</div>
        <div>
            <div class="stat-label">Currently Admitted</div>
            <div class="stat-value">{{ $this->totalAdmitted }}</div>
            <div class="stat-sub">patients on ward</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-amber">📋</div>
        <div>
            <div class="stat-label">Pending Orders</div>
            <div class="stat-value">{{ $this->totalPendingOrders }}</div>
            <div class="stat-sub">orders awaiting action</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue">⏰</div>
        <div>
            <div class="stat-label">Shift Date</div>
            <div class="stat-value" style="font-size:1.3rem;">{{ now()->timezone('Asia/Manila')->format('M j, Y') }}</div>
            <div class="stat-sub">{{ now()->timezone('Asia/Manila')->format('l') }}</div>
        </div>
    </div>
</div>

{{-- ══ FILTER BAR ══ --}}
<div class="filter-bar">
    <div class="view-toggle">
        <button wire:click="$set('viewFilter','admitted')" type="button"
                class="vt-btn {{ $viewFilter === 'admitted' ? 'active' : '' }}">
            🏥 Admitted
        </button>
        <button wire:click="$set('viewFilter','all')" type="button"
                class="vt-btn {{ $viewFilter === 'all' ? 'active' : '' }}">
            🗂️ All Patients
        </button>
    </div>
    <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="Search by patient name or case number…"
               class="search-input">
    </div>
    <div class="select-wrap">
        <select wire:model.live="serviceFilter" class="filter-select">
            <option value="">All Services</option>
            @foreach($this->serviceOptions as $svc)
            <option value="{{ $svc }}">{{ $svc }}</option>
            @endforeach
        </select>
    </div>
    @if($search || $serviceFilter)
    <span class="filter-badge">
        Filtered
        @if($search) · "{{ $search }}" @endif
        @if($serviceFilter) · {{ $serviceFilter }} @endif
    </span>
    <button wire:click="$set('search',''); $set('serviceFilter','')"
            style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:.8rem;">
        ✕ Clear
    </button>
    @endif
</div>

{{-- ══ PATIENTS TABLE ══ --}}
<div class="patients-table-wrap">
    @if($this->admittedPatients->count() > 0)
    <table class="patients-table">
        <thead>
            <tr>
                <th style="width:32px;">#</th>
                <th>Patient</th>
                @if($viewFilter === 'all')
                    <th>Entry</th>
                    <th>Status</th>
                @endif
                <th>Service</th>
                <th>Diagnosis</th>
                <th>Physician</th>
                @if($viewFilter === 'admitted')
                    <th style="text-align:center;">Pending Orders</th>
                @endif
                <th>{{ $viewFilter === 'admitted' ? 'Admitted' : 'Registered' }}</th>
                <th style="width:120px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($this->admittedPatients as $i => $visit)
            @php
                $pendingCount = $visit->doctorsOrders->count();
                $statusLabel  = match($visit->status) {
                    'vitals_done' => 'Vitals Done',
                    default       => ucfirst(str_replace('_', ' ', $visit->status)),
                };
                $statusClass = match($visit->status) {
                    'admitted'    => 's-admitted',
                    'discharged'  => 's-discharged',
                    'assessed'    => 's-assessed',
                    'referred'    => 's-referred',
                    'vitals_done' => 's-vitals',
                    default       => 's-registered',
                };
            @endphp
            <tr wire:click="openChart({{ $visit->id }})" wire:key="row-{{ $visit->id }}">
                <td style="font-size:.75rem;font-weight:600;color:#9ca3af;">
                    {{ $this->admittedPatients->firstItem() + $i }}
                </td>
                <td>
                    <p class="pt-name">{{ $visit->patient->full_name }}</p>
                    <p class="pt-case">{{ $visit->patient->case_no }}</p>
                    <p style="font-size:.7rem;color:#9ca3af;margin-top:1px;">
                        {{ $visit->patient->age_display }} · {{ $visit->patient->sex }}
                    </p>
                </td>
                @if($viewFilter === 'all')
                <td>
                    <span class="{{ $visit->visit_type === 'ER' ? 'type-er' : 'type-opd' }}">
                        {{ $visit->visit_type === 'ER' ? '🚑 ER' : '📋 OPD' }}
                    </span>
                </td>
                <td><span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span></td>
                @endif
                <td>
                    @if($visit->admitted_service)
                        <span class="svc-badge">{{ $visit->admitted_service }}</span>
                    @else
                        <span style="color:#9ca3af;">—</span>
                    @endif
                    @if($visit->payment_class)
                    <br><span class="pay-badge {{ $visit->payment_class === 'Private' ? 'pay-private' : 'pay-charity' }}" style="margin-top:3px;">
                        {{ $visit->payment_class }}
                    </span>
                    @endif
                </td>
                <td style="max-width:200px;">
                    <p style="font-size:.83rem;line-height:1.4;">
                        {{ \Str::limit($visit->admitting_diagnosis ?? $visit->medicalHistory?->diagnosis ?? '—', 50) }}
                    </p>
                </td>
                <td style="font-size:.8rem;color:#6b7280;">
                    @if($visit->medicalHistory?->doctor)
                        Dr. {{ $visit->medicalHistory->doctor->name }}
                    @else
                        <span style="color:#9ca3af;">—</span>
                    @endif
                </td>
                @if($viewFilter === 'admitted')
                <td style="text-align:center;">
                    <span class="orders-badge {{ $pendingCount > 0 ? 'has-orders' : 'no-orders' }}">
                        {{ $pendingCount > 0 ? $pendingCount : '✓' }}
                    </span>
                </td>
                @endif
                <td>
                    @if($viewFilter === 'admitted')
                        @if($visit->clerk_admitted_at)
                            <p class="adm-time">{{ $visit->clerk_admitted_at->timezone('Asia/Manila')->format('M j H:i') }}</p>
                            <p class="adm-ago">{{ $visit->clerk_admitted_at->diffForHumans() }}</p>
                        @else
                            <p class="adm-time">{{ $visit->doctor_admitted_at->timezone('Asia/Manila')->format('M j H:i') }}</p>
                            <p style="font-size:.7rem;margin-top:2px;">
                                <span style="background:#fef3c7;color:#92400e;padding:2px 7px;border-radius:9999px;font-weight:700;font-size:.68rem;">⏳ Pending Clerk</span>
                            </p>
                        @endif
                    @else
                        <p class="adm-time">{{ $visit->registered_at->timezone('Asia/Manila')->format('M j H:i') }}</p>
                        <p class="adm-ago">{{ $visit->registered_at->diffForHumans() }}</p>
                    @endif
                </td>
                <td wire:click.stop>
                    <button wire:click="openChart({{ $visit->id }})" type="button" class="btn-open-chart">
                        Open Chart →
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pag-wrap">{{ $this->admittedPatients->links() }}</div>

    @else
    <div class="empty-state">
        <div class="empty-icon-wrap">🏥</div>
        <p class="empty-title">
            @if($search || $serviceFilter) No patients match your search
            @elseif($viewFilter === 'admitted') No admitted patients
            @else No patients found
            @endif
        </p>
        <p class="empty-sub">
            @if($search || $serviceFilter) Try adjusting your search or filter criteria.
            @elseif($viewFilter === 'admitted') Switch to "All Patients" to see patients with other statuses.
            @else No patient visits are recorded yet.
            @endif
        </p>
    </div>
    @endif
</div>

<script>
(function () {
    const el = document.getElementById('nurse-clock');
    if (!el) return;
    const fmt = () => new Date().toLocaleString('en-PH', {
        timeZone:'Asia/Manila', weekday:'short', month:'short',
        day:'numeric', hour:'2-digit', minute:'2-digit', hour12:false
    }).replace(',', ' ·');
    el.textContent = fmt();
    setInterval(() => { el.textContent = fmt(); }, 30000);
})();
</script>

</x-filament-panels::page>