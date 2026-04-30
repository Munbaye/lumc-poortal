<x-filament-panels::page>

@php
    $nurse = auth()->user();
    $userInitials = strtoupper(
        substr($nurse->first_name ?? $nurse->name ?? 'N', 0, 1) .
        substr($nurse->last_name ?? '', 0, 1)
    );
    // Nurse portal accent — rose/pink
    $accentHex    = '#f43f5e';
    $accentLight  = '#fff1f2';
    $accentMid    = '#fecdd3';
    $accentDark   = '#e11d48';
    $accentRgb    = '244,63,94';
    $borderAccent = '#fda4af';
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    --accent:        {{ $accentHex }};
    --accent-light:  {{ $accentLight }};
    --accent-mid:    {{ $accentMid }};
    --accent-dark:   {{ $accentDark }};
    --accent-rgb:    {{ $accentRgb }};
    --border-accent: {{ $borderAccent }};

    --text-heading:  #111827;
    --text-body:     #374151;
    --text-muted:    #6b7280;
    --text-subtle:   #9ca3af;

    --surface:       #ffffff;
    --surface-alt:   #f9fafb;
    --border:        #e5e7eb;
    --border-soft:   #f3f4f6;

    --font-sans: 'DM Sans', system-ui, sans-serif;
    --font-mono: 'DM Mono', monospace;
}

* { font-family: var(--font-sans); box-sizing: border-box; }

/* ── Filament chrome overrides ── */
.fi-header-heading { display: none !important; }
.fi-sidebar { background: #fff !important; border-right: 1px solid var(--border) !important; }
.fi-sidebar-close-btn { display: none !important; }
.fi-sidebar-header { display: none !important; }
.lumc-brand {
    display: flex; align-items: center; gap: 12px;
    padding: 1.1rem 1rem 1rem;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 4px; flex-shrink: 0;
}
.lumc-brand img { width: 38px; height: 38px; object-fit: contain; flex-shrink: 0; }
.lumc-brand-text {
    font-size: 1.05rem; font-weight: 700; color: #111827;
    white-space: nowrap; line-height: 1.2; font-family: var(--font-sans);
}
.fi-sidebar-item-button {
    font-family: var(--font-sans) !important;
    font-size: 0.875rem !important; font-weight: 500 !important;
    color: var(--text-muted) !important; border-radius: 8px !important;
    transition: all 0.15s !important;
}
.fi-topbar { background: #fff !important; border-bottom: 1px solid var(--border) !important; }
.fi-topbar-item-notifications, [data-notification-trigger] { display: none !important; }
.fi-avatar { background: var(--accent) !important; color: #fff !important; }
.fi-user-menu-button .fi-avatar-text { display: none !important; }

/* ════════════ HERO HEADER ════════════ */
.dash-header {
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; margin-bottom: 24px; padding: 22px 26px;
    background: var(--accent);
    border: 1px solid var(--accent-dark);
    border-radius: 16px; position: relative; overflow: hidden;
}
.dash-header::after {
    content: '🏥';
    position: absolute; right: 26px; top: 50%;
    transform: translateY(-50%);
    font-size: 4.5rem; opacity: 0.1; pointer-events: none; line-height: 1;
}
.dash-left { padding-left: 0; }
.dash-eyebrow { display: flex; align-items: center; gap: 7px; margin-bottom: 5px; }
.eyebrow-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: rgba(255,255,255,.7);
    animation: blink 2.4s ease-in-out infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.35} }
.eyebrow-label {
    font-size: 0.67rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.1em; color: rgba(255,255,255,.75);
}
.dash-title {
    font-size: 1.55rem; font-weight: 700; color: #fff;
    margin: 0 0 5px; letter-spacing: -0.025em; line-height: 1.2;
}
.dash-tagline { font-size: 0.78rem; color: rgba(255,255,255,.7); }
.dash-right {
    display: flex; flex-direction: column;
    align-items: flex-end; gap: 7px; flex-shrink: 0;
}
.user-chip {
    display: flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 9999px; padding: 5px 12px 5px 6px;
}
.user-avatar {
    width: 26px; height: 26px; border-radius: 50%;
    background: #fff; color: var(--accent);
    font-size: 0.65rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.user-name { font-size: 0.78rem; font-weight: 600; color: #fff; }
.specialty-pill {
    font-size: 0.68rem; font-weight: 700;
    background: rgba(255,255,255,.15); color: #fff;
    border: 1px solid rgba(255,255,255,.3);
    padding: 3px 11px; border-radius: 9999px;
}
.dash-clock { font-size: 0.68rem; color: rgba(255,255,255,.6); font-variant-numeric: tabular-nums; }

/* ════════════ STAT CARDS ════════════ */
.stats-bar {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 13px; margin-bottom: 22px;
}
.stat-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 14px; padding: 18px 20px;
    display: flex; align-items: center; gap: 16px;
    transition: box-shadow 0.18s, transform 0.18s;
    position: relative; overflow: hidden;
}
.stat-card:hover { box-shadow: 0 6px 22px rgba(0,0,0,0.07); transform: translateY(-2px); }
.stat-card.is-accent {
    border-color: var(--border-accent);
    background: linear-gradient(140deg, var(--accent-light) 0%, #fff 65%);
}
.stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.si-rose  { background: #fff1f2; }
.si-amber { background: #fef3c7; }
.si-blue  { background: #eff6ff; }
.stat-body { display: flex; flex-direction: column; flex: 1; }
.stat-label {
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.08em; color: var(--text-subtle); margin-bottom: 2px;
}
.stat-value {
    font-size: 2.2rem; font-weight: 800; color: #111827;
    line-height: 1.1; letter-spacing: -0.03em; font-variant-numeric: tabular-nums;
}
.stat-sub { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }

/* ════════════ FILTER BAR ════════════ */
.filter-bar {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 16px; flex-wrap: wrap;
}
.view-toggle {
    display: inline-flex; background: var(--surface-alt);
    border: 1px solid var(--border); border-radius: 10px; padding: 3px;
}
.vt-btn {
    padding: 6px 16px; border-radius: 7px; font-size: 0.78rem;
    font-weight: 600; font-family: var(--font-sans);
    color: var(--text-muted); background: none; border: none;
    cursor: pointer; transition: all 0.15s;
}
.vt-btn.active {
    background: var(--surface); color: var(--accent);
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}
.search-wrap { flex: 1; min-width: 220px; position: relative; }
.search-icon {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%); color: var(--text-subtle);
    font-size: 0.82rem; pointer-events: none;
}
.search-input {
    width: 100%; padding: 9px 14px 9px 36px;
    border: 1px solid var(--border); border-radius: 10px;
    font-size: 0.83rem; font-family: var(--font-sans);
    color: var(--text-body); background: var(--surface); outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.search-input::placeholder { color: var(--text-subtle); }
.search-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.12);
}
.select-wrap { min-width: 160px; position: relative; }
.filter-select {
    width: 100%; padding: 9px 14px;
    border: 1px solid var(--border) !important;
    border-radius: 10px !important; font-size: 0.83rem; font-family: var(--font-sans);
    color: var(--text-body); background: var(--surface) !important;
    outline: none !important; cursor: pointer;
    /* Remove ALL browser and Filament arrows */
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 12px center !important;
    background-size: 12px !important;
    padding-right: 32px !important;
}
.filter-select:focus {
    border-color: var(--accent) !important;
    box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.12) !important;
}
/* Kill any Filament wrapper arrows on our select */
.select-wrap::before,
.select-wrap::after { display: none !important; }
.select-wrap > *::before,
.select-wrap > *::after { display: none !important; }
.filter-badge {
    font-size: 0.72rem; background: var(--accent-light);
    color: var(--accent-dark); padding: 3px 10px;
    border-radius: 9999px; font-weight: 700; white-space: nowrap;
}

/* ════════════ TABLE ════════════ */
.patients-table-wrap {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 14px; overflow: hidden;
}
.patients-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
.patients-table thead tr {
    background: var(--surface-alt); border-bottom: 1px solid var(--border);
}
.patients-table th {
    padding: 10px 15px; text-align: left;
    font-size: 0.62rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--text-subtle); white-space: nowrap;
}
.patients-table td {
    padding: 12px 15px; border-bottom: 1px solid var(--border-soft);
    color: var(--text-body); vertical-align: middle;
}
.patients-table tbody tr:last-child td { border-bottom: none; }
.patients-table tbody tr { cursor: pointer; transition: background 0.1s; }
.patients-table tbody tr:hover td { background: var(--accent-light); }

.pt-name { font-weight: 700; color: var(--text-heading); font-size: 0.85rem; }
.pt-case { font-family: var(--font-mono); font-size: 0.7rem; color: var(--text-subtle); margin-top: 2px; }

.svc-badge {
    display: inline-block; padding: 2px 9px; border-radius: 9999px;
    font-size: 0.7rem; font-weight: 700;
    background: #fce7f3; color: #9d174d; white-space: nowrap;
}
.pay-badge { display: inline-block; padding: 2px 9px; border-radius: 9999px; font-size: 0.68rem; font-weight: 700; }
.pay-charity { background: #d1fae5; color: #065f46; }
.pay-private { background: #f3f4f6; color: #374151; }

.orders-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 26px; height: 22px; padding: 0 7px;
    border-radius: 9999px; font-size: 0.72rem; font-weight: 800;
}
.orders-badge.has-orders { background: #fef3c7; color: #92400e; }
.orders-badge.no-orders  { background: #d1fae5; color: #065f46; }

.status-pill { display:inline-block; padding:2px 9px; border-radius:9999px; font-size:.7rem; font-weight:700; }
.s-admitted   { background:#d1fae5; color:#065f46; }
.s-discharged { background:#f3f4f6; color:#374151; }
.s-registered { background:#fef9c3; color:#854d0e; }
.s-assessed   { background:#e0f2fe; color:#0c4a6e; }
.s-referred   { background:#fef3c7; color:#92400e; }
.s-vitals     { background:#f0fdf4; color:#166534; }

.type-er  { background:#fee2e2; color:#991b1b; font-size:.7rem; font-weight:700; padding:2px 7px; border-radius:9999px; }
.type-opd { background:#eff6ff; color:#1d4ed8; font-size:.7rem; font-weight:700; padding:2px 7px; border-radius:9999px; }

.adm-time { font-size:0.78rem; color:var(--text-body); white-space:nowrap; }
.adm-ago  { font-size:0.68rem; color:var(--text-subtle); margin-top:2px; }

.btn-open-chart {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--accent); color: #fff; border: none;
    border-radius: 8px; padding: 6px 14px;
    font-size: 0.76rem; font-weight: 700;
    font-family: var(--font-sans); cursor: pointer; white-space: nowrap;
    transition: background 0.15s, transform 0.15s;
}
.btn-open-chart:hover { background: var(--accent-dark); transform: translateX(2px); }

/* ════════════ EMPTY STATE ════════════ */
.empty-state { text-align: center; padding: 60px 20px; }
.empty-icon-wrap {
    width: 56px; height: 56px; background: var(--accent-light);
    border-radius: 16px; display: flex; align-items: center;
    justify-content: center; font-size: 1.6rem; margin: 0 auto 14px;
}
.empty-title { font-size: 0.9rem; font-weight: 700; color: var(--text-heading); margin-bottom: 4px; }
.empty-sub { font-size: 0.78rem; color: var(--text-subtle); }

.pag-wrap { padding: 12px 15px; border-top: 1px solid var(--border-soft); }

/* ════════════ MOBILE ════════════ */
@media (max-width: 768px) {
    .stats-bar { grid-template-columns: 1fr; }
    .dash-header { flex-direction: column; gap: 12px; padding: 16px; }
    .dash-header::after { display: none; }
    .dash-right { align-items: flex-start; flex-direction: row; flex-wrap: wrap; gap: 8px; }
    .filter-bar { flex-direction: column; align-items: stretch; }
    .search-wrap { min-width: unset; width: 100%; }
    .patients-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .patients-table { min-width: 600px; }
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
        @if($nurse->specialty)
        <span class="specialty-pill">{{ $nurse->specialty }}</span>
        @endif
        <span class="dash-clock" id="nurse-clock">{{ now()->timezone('Asia/Manila')->format('D, M j · H:i') }}</span>
    </div>
</div>

{{-- ══ STAT CARDS ══ --}}
<div class="stats-bar">
    <div class="stat-card is-accent">
        <div class="stat-icon si-rose">🏥</div>
        <div class="stat-body">
            <div class="stat-label">Currently Admitted</div>
            <div class="stat-value">{{ $this->totalAdmitted }}</div>
            <div class="stat-sub">patients on ward</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-amber">📋</div>
        <div class="stat-body">
            <div class="stat-label">Pending Orders</div>
            <div class="stat-value">{{ $this->totalPendingOrders }}</div>
            <div class="stat-sub">orders awaiting action</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue">⏰</div>
        <div class="stat-body">
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
            style="background:none;border:none;color:var(--text-subtle);cursor:pointer;font-size:.8rem;">
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
                <td style="color:var(--text-subtle);font-size:.75rem;font-weight:600;">
                    {{ $this->admittedPatients->firstItem() + $i }}
                </td>
                <td>
                    <p class="pt-name">{{ $visit->patient->full_name }}</p>
                    <p class="pt-case">{{ $visit->patient->case_no }}</p>
                    <p style="font-size:.7rem;color:var(--text-subtle);margin-top:1px;">
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
                        <span style="color:var(--text-subtle);">—</span>
                    @endif
                    @if($visit->payment_class)
                    <br><span class="pay-badge {{ $visit->payment_class === 'Private' ? 'pay-private' : 'pay-charity' }}" style="margin-top:3px;">
                        {{ $visit->payment_class }}
                    </span>
                    @endif
                </td>
                <td style="max-width:200px;">
                    <p style="font-size:.83rem;color:var(--text-body);line-height:1.4;">
                        {{ \Str::limit($visit->admitting_diagnosis ?? $visit->medicalHistory?->diagnosis ?? '—', 50) }}
                    </p>
                </td>
                <td style="font-size:.8rem;color:var(--text-muted);">
                    @if($visit->medicalHistory?->doctor)
                        Dr. {{ $visit->medicalHistory->doctor->name }}
                    @else
                        <span style="color:var(--text-subtle);">—</span>
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
        timeZone: 'Asia/Manila', weekday: 'short', month: 'short',
        day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false
    }).replace(',', ' ·');
    el.textContent = fmt();
    setInterval(() => { el.textContent = fmt(); }, 30000);
})();

(function () {
    const logoUrl = @json(asset('images/lumc-logo.png'));
    const accent  = @json($accentHex);
    const accentLight = @json($accentLight);
    const accentDark  = @json($accentDark);

    function injectNavStyle () {
        const old = document.getElementById('lumc-nav-style');
        if (old) old.remove();
        const s = document.createElement('style');
        s.id = 'lumc-nav-style';
        s.textContent = `
            .fi-sidebar-item-button.fi-active,
            .fi-sidebar-item-button.fi-active:hover {
                background: ${accent} !important;
                color: #ffffff !important;
                font-weight: 600 !important;
            }
            .fi-sidebar-item-button.fi-active svg,
            .fi-sidebar-item-button.fi-active span { color: #ffffff !important; }
            .fi-sidebar-item-button:not(.fi-active):hover {
                background: ${accentLight} !important;
                color: ${accentDark} !important;
            }
        `;
        document.head.appendChild(s);
    }

    function injectBrand () {
        const nav = document.querySelector('.fi-sidebar-nav');
        if (!nav) return;
        if (document.querySelector('.lumc-brand')) return;
        const brand = document.createElement('div');
        brand.className = 'lumc-brand';
        brand.innerHTML = '<img src="' + logoUrl + '" alt="LUMC"><span class="lumc-brand-text">LUMC — Nurse Portal</span>';
        nav.parentElement.insertBefore(brand, nav);
    }

    function run () { injectNavStyle(); injectBrand(); }
    run();
    document.addEventListener('DOMContentLoaded', run);
    document.addEventListener('livewire:navigated', run);
    setTimeout(run, 50);
    setTimeout(run, 200);
    setTimeout(run, 500);
})();
</script>

</x-filament-panels::page>