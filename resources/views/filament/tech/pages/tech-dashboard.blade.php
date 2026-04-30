<x-filament-panels::page>

@php
    $tech      = auth()->user();
    $spec      = strtolower($tech->specialty ?? '');
    $isMedtech = $this->isMedtech;
    $isRadtech = $this->isRadtech;
    $queueType = $this->queueType;
    $labQueue  = $this->labQueue;
    $radQueue  = $this->radQueue;
    $title     = $this->getTitle();

    // Per-specialty design tokens
    if ($isMedtech && !$isRadtech) {
        $accentHex    = '#0d9488';
        $accentLight  = '#f0fdfa';
        $accentMid    = '#99f6e4';
        $accentDark   = '#0f766e';
        $accentRgb    = '13,148,136';
        $borderAccent = '#5eead4';
        $taglineIcon  = '🧪';
        $tagline      = 'Medical Technology · Laboratory Services';
    } elseif ($isRadtech && !$isMedtech) {
        $accentHex    = '#475569';
        $accentLight  = '#f8fafc';
        $accentMid    = '#cbd5e1';
        $accentDark   = '#334155';
        $accentRgb    = '71,85,105';
        $borderAccent = '#94a3b8';
        $taglineIcon  = '🩻';
        $tagline      = 'Radiology · Diagnostic Imaging';
    } else {
        $accentHex    = '#ea580c';
        $accentLight  = '#fff7ed';
        $accentMid    = '#fed7aa';
        $accentDark   = '#c2410c';
        $accentRgb    = '234,88,12';
        $borderAccent = '#fb923c';
        $taglineIcon  = '⚙️';
        $tagline      = 'Technical Services · All Departments';
    }

    $userInitials = strtoupper(
        substr($tech->first_name ?? $tech->name ?? 'U', 0, 1) .
        substr($tech->last_name ?? '', 0, 1)
    );
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
.fi-header-heading {
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    color: var(--text-heading) !important;
    display: none !important;
}
.fi-sidebar { background: #fff !important; border-right: 1px solid var(--border) !important; }
.fi-sidebar-close-btn { display: none !important; }

/* Hide default Filament brand — we inject our own below via JS */
.fi-sidebar-header { display: none !important; }

/* Our custom brand injected at top of sidebar nav */
.lumc-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 1.1rem 1rem 1rem;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 4px;
    flex-shrink: 0;
}
.lumc-brand img { width: 38px; height: 38px; object-fit: contain; flex-shrink: 0; }
.lumc-brand-text {
    font-size: 1.05rem;
    font-weight: 700;
    color: #111827;
    white-space: nowrap;
    line-height: 1.2;
    font-family: var(--font-sans);
}
.fi-sidebar-item-button {
    font-family: var(--font-sans) !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    color: var(--text-muted) !important;
    border-radius: 8px !important;
    transition: all 0.15s !important;
}
.fi-sidebar-item-button:hover { background: var(--accent-light) !important; color: var(--accent-dark) !important; }
.fi-sidebar-item-button.fi-active,
.fi-sidebar-item-button.fi-active:hover,
.fi-sidebar-item-button[aria-current].fi-active {
    background: var(--accent) !important;
    color: #fff !important;
    font-weight: 600 !important;
}
.fi-sidebar-item-button.fi-active svg,
.fi-sidebar-item-button.fi-active:hover svg {
    color: #fff !important;
    fill: #fff !important;
}
.fi-sidebar-item-button:not(.fi-active) {
    color: var(--text-muted) !important;
}
.fi-sidebar-item-button:not(.fi-active) svg {
    color: var(--text-muted) !important;
}
.fi-sidebar-item-button:not(.fi-active):hover {
    background: var(--accent-light) !important;
    color: var(--accent-dark) !important;
}
.fi-sidebar-item-button:not(.fi-active):hover svg {
    color: var(--accent-dark) !important;
}
.fi-topbar { background: #fff !important; border-bottom: 1px solid var(--border) !important; }
.fi-topbar-item-notifications, [data-notification-trigger] { display: none !important; }
.fi-avatar { background: var(--accent) !important; color: #fff !important; }
.fi-user-menu-button .fi-avatar-text { display: none !important; }

/* ════════════ HERO HEADER ════════════ */
.dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
    padding: 22px 26px;
    background: var(--accent);
    border: 1px solid var(--accent-dark);
    border-radius: 16px;
    position: relative;
    overflow: hidden;
}

.dash-header::before { display: none; }

.dash-header::after {
    content: '{{ $taglineIcon }}';
    position: absolute;
    right: 26px; top: 50%;
    transform: translateY(-50%);
    font-size: 4.5rem;
    opacity: 0.12;
    pointer-events: none;
    line-height: 1;
}

.dash-left { padding-left: 0; }

.dash-eyebrow {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 5px;
}

.eyebrow-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: rgba(255,255,255,.7);
    animation: blink 2.4s ease-in-out infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.35; }
}

.eyebrow-label {
    font-size: 0.67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(255,255,255,.75);
}

.dash-title {
    font-size: 1.55rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 5px;
    letter-spacing: -0.025em;
    line-height: 1.2;
}

.dash-tagline {
    font-size: 0.78rem;
    color: rgba(255,255,255,.7);
}

.dash-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 7px;
    flex-shrink: 0;
}

.user-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 9999px;
    padding: 5px 12px 5px 6px;
}

.user-avatar {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: #ffffff;
    color: var(--accent);
    font-size: 0.65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.user-name {
    font-size: 0.78rem;
    font-weight: 600;
    color: #ffffff;
}

.specialty-pill {
    font-size: 0.68rem;
    font-weight: 700;
    background: rgba(255,255,255,.15);
    color: #ffffff;
    border: 1px solid rgba(255,255,255,.3);
    padding: 3px 11px;
    border-radius: 9999px;
}

.dash-clock {
    font-size: 0.68rem;
    color: rgba(255,255,255,.6);
    font-variant-numeric: tabular-nums;
}

/* ════════════ STAT CARDS ════════════ */
.stats-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 13px;
    margin-bottom: 22px;
}

.stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: box-shadow 0.18s, transform 0.18s;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    box-shadow: 0 6px 22px rgba(0,0,0,0.07);
    transform: translateY(-2px);
}

.stat-card.is-accent {
    border-color: var(--border-accent);
    background: linear-gradient(140deg, var(--accent-light) 0%, #fff 65%);
}

.stat-card.is-accent::after {
    content: '';
    position: absolute;
    bottom: -16px; right: -16px;
    width: 64px; height: 64px;
    background: var(--accent);
    opacity: 0.07;
    border-radius: 50%;
}

.stat-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.si-accent { background: var(--accent-light); }
.si-green  { background: #f0fdf4; }
.si-blue   { background: #eff6ff; }

.stat-body {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.stat-label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-subtle);
    margin-bottom: 2px;
}

.stat-value {
    font-size: 2.2rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.1;
    letter-spacing: -0.03em;
    font-variant-numeric: tabular-nums;
}

.stat-sub {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 2px;
}

/* ════════════ QUEUE CONTROLS ════════════ */
.queue-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.search-wrap {
    flex: 1;
    min-width: 220px;
    position: relative;
}

.search-icon {
    position: absolute;
    left: 12px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-subtle);
    font-size: 0.82rem;
    pointer-events: none;
}

.search-input {
    width: 100%;
    padding: 9px 14px 9px 36px;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 0.83rem;
    font-family: var(--font-sans);
    color: var(--text-body);
    background: var(--surface);
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.search-input::placeholder { color: var(--text-subtle); }

.search-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.12);
}

.filter-tabs {
    display: inline-flex;
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 3px;
}

.ft {
    padding: 6px 18px;
    border-radius: 7px;
    font-size: 0.76rem;
    font-weight: 600;
    font-family: var(--font-sans);
    color: var(--text-muted);
    background: none;
    border: none;
    cursor: pointer;
    transition: all 0.15s;
}

.ft.active {
    background: var(--surface);
    color: var(--accent);
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

/* ════════════ QUEUE SECTION ════════════ */
.queue-section { margin-bottom: 26px; }

.queue-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 11px;
}

.queue-header-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: var(--accent-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.queue-header-title {
    font-size: 0.86rem;
    font-weight: 700;
    color: var(--text-heading);
    margin: 0;
    flex: 1;
}

.qs-badge {
    font-size: 0.64rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 9999px;
    letter-spacing: 0.03em;
}

.qs-badge.pending  { background: #fff7ed; color: #c2410c; }
.qs-badge.done     { background: #f0fdf4; color: #15803d; }

/* ════════════ REQUEST TABLE ════════════ */
.req-table-wrap {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
}

.req-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.81rem;
}

.req-table thead tr {
    background: var(--surface-alt);
    border-bottom: 1px solid var(--border);
}

.req-table th {
    padding: 10px 15px;
    text-align: left;
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-subtle);
    white-space: nowrap;
}

.req-table td {
    padding: 12px 15px;
    border-bottom: 1px solid var(--border-soft);
    color: var(--text-body);
    vertical-align: middle;
}

.req-table tbody tr:last-child td { border-bottom: none; }
.req-table tbody tr { cursor: pointer; transition: background 0.1s; }
.req-table tbody tr:hover td { background: var(--accent-light); }

.req-no {
    font-family: var(--font-mono);
    font-size: 0.73rem;
    font-weight: 500;
    color: var(--accent);
    background: var(--accent-light);
    border: 1px solid var(--accent-mid);
    padding: 3px 8px;
    border-radius: 6px;
    white-space: nowrap;
}

.req-patient-name {
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 2px;
    font-size: 0.83rem;
}

.req-patient-case {
    font-family: var(--font-mono);
    font-size: 0.66rem;
    color: var(--text-subtle);
}

.req-test-list { font-size: 0.77rem; color: var(--text-body); line-height: 1.5; }
.req-doctor    { font-size: 0.77rem; color: var(--text-muted); }
.req-time      { font-size: 0.75rem; color: var(--text-body); white-space: nowrap; }
.req-time-ago  { font-size: 0.65rem; color: var(--text-subtle); }

/* ── Badges ── */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 0.63rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    white-space: nowrap;
}

.badge-stat       { background: #fee2e2; color: #991b1b; }
.badge-routine    { background: var(--border-soft); color: var(--text-muted); }
.badge-completed  { background: #dcfce7; color: #15803d; }
.badge-pending    { background: #fff7ed; color: #c2410c; }
.badge-inprogress { background: #fef9c3; color: #854d0e; }
.badge-modality   { background: var(--accent-light); color: var(--accent-dark); border: 1px solid var(--accent-mid); }

/* ── Action button ── */
.btn-open {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 13px;
    border-radius: 8px;
    font-size: 0.74rem;
    font-weight: 700;
    font-family: var(--font-sans);
    border: none;
    cursor: pointer;
    background: var(--accent);
    color: #fff;
    transition: background 0.15s, transform 0.15s;
    white-space: nowrap;
}

.btn-open:hover {
    background: var(--accent-dark);
    transform: translateX(2px);
}

/* ════════════ EMPTY STATE ════════════ */
.empty-state { text-align: center; padding: 46px 20px; }

.empty-icon-wrap {
    width: 52px; height: 52px;
    background: var(--accent-light);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin: 0 auto 13px;
}

.empty-title { font-size: 0.9rem; font-weight: 700; color: var(--text-heading); margin-bottom: 4px; }
.empty-sub   { font-size: 0.77rem; color: var(--text-subtle); }

/* ════════════ DIVIDER ════════════ */
.section-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 4px 0 24px;
}

@media (max-width: 768px) {
    .stats-bar { grid-template-columns: 1fr 1fr; }
    .dash-header { flex-direction: column; gap: 12px; padding: 16px; }
    .dash-header::after { display: none; }
    .dash-right { align-items: flex-start; flex-direction: row; flex-wrap: wrap; gap: 8px; }
    .dash-title { font-size: 1.2rem; }
}

@media (max-width: 480px) {
    .stats-bar { grid-template-columns: 1fr; }
    .dash-left { padding-left: 10px; }
    .dash-title { font-size: 1.1rem; }
    .queue-controls { flex-direction: column; align-items: stretch; }
    .search-wrap { min-width: unset; width: 100%; }
    .filter-tabs { align-self: flex-start; }
    .req-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .req-table { min-width: 600px; }
    .queue-header { flex-wrap: wrap; }
    .dash-right { flex-direction: column; align-items: flex-start; }
    .user-chip { align-self: flex-start; }
}
</style>

{{-- ══ HERO HEADER ══ --}}
<div class="dash-header">
    <div class="dash-left">
        <div class="dash-eyebrow">
            <div class="eyebrow-dot"></div>
            <span class="eyebrow-label">Active · LUMC Portal</span>
        </div>
        <h1 class="dash-title">{{ $title }}</h1>
        <p class="dash-tagline">{{ $taglineIcon }} {{ $tagline }}</p>
    </div>
    <div class="dash-right">
        <div class="user-chip">
            <div class="user-avatar">{{ $userInitials }}</div>
            <span class="user-name">{{ $tech->name ?? 'Technician' }}</span>
        </div>
        @if($tech->specialty)
        <span class="specialty-pill">{{ $tech->specialty }}</span>
        @endif
        <span class="dash-clock" id="dash-clock">{{ now()->timezone('Asia/Manila')->format('D, M j · H:i') }}</span>
    </div>
</div>

{{-- ══ STAT CARDS ══ --}}
<div class="stats-bar">
    @if($queueType !== 'radiology')
    <div class="stat-card is-accent">
        <div class="stat-icon si-accent">🧪</div>
        <div class="stat-body">
            <div class="stat-label">Pending Lab</div>
            <div class="stat-value">{{ $this->pendingLabCount }}</div>
            <div class="stat-sub">requests awaiting</div>
        </div>
    </div>
    @endif

    @if($queueType !== 'lab')
    <div class="stat-card is-accent">
        <div class="stat-icon si-accent">🩻</div>
        <div class="stat-body">
            <div class="stat-label">Pending Radiology</div>
            <div class="stat-value">{{ $this->pendingRadCount }}</div>
            <div class="stat-sub">requests awaiting</div>
        </div>
    </div>
    @endif

    <div class="stat-card">
        <div class="stat-icon si-green">✅</div>
        <div class="stat-body">
            <div class="stat-label">My Results Today</div>
            <div class="stat-value">{{ $this->myCompletedToday }}</div>
            <div class="stat-sub">uploaded today</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon si-blue">📋</div>
        <div class="stat-body">
            <div class="stat-label">Total Results</div>
            <div class="stat-value">{{ $this->myTotalResults }}</div>
            <div class="stat-sub">cumulative uploads</div>
        </div>
    </div>
</div>

{{-- ══ QUEUE CONTROLS ══ --}}
<div class="queue-controls">
    <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text"
               wire:model.live.debounce.300ms="search"
               class="search-input"
               placeholder="Search by request no., patient name, or diagnosis…">
    </div>
    <div class="filter-tabs">
        <button wire:click="$set('queueFilter', 'pending')" type="button"
                class="ft {{ $queueFilter === 'pending' ? 'active' : '' }}">
            Pending
        </button>
        <button wire:click="$set('queueFilter', 'completed')" type="button"
                class="ft {{ $queueFilter === 'completed' ? 'active' : '' }}">
            Completed
        </button>
    </div>
</div>

{{-- ══ LAB QUEUE ══ --}}
@if($queueType !== 'radiology')
<div class="queue-section">
    <div class="queue-header">
        <div class="queue-header-icon">🧪</div>
        <p class="queue-header-title">Laboratory Requests</p>
        <span class="qs-badge {{ $queueFilter === 'pending' ? 'pending' : 'done' }}">
            {{ $labQueue->count() }} {{ $queueFilter === 'pending' ? 'pending' : 'completed' }}
        </span>
    </div>
    <div class="req-table-wrap">
        @if($labQueue->isEmpty())
            <div class="empty-state">
                <div class="empty-icon-wrap">🧪</div>
                <p class="empty-title">{{ $queueFilter === 'pending' ? 'No pending lab requests' : 'No completed lab requests' }}</p>
                <p class="empty-sub">{{ $queueFilter === 'pending' ? 'All clear — no pending laboratory requests at the moment.' : 'Completed requests will appear here.' }}</p>
            </div>
        @else
        <table class="req-table">
            <thead>
                <tr>
                    <th>Request No.</th>
                    <th>Patient</th>
                    <th>Tests Ordered</th>
                    <th>Ordered By</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th style="width:86px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($labQueue as $req)
                <tr wire:click="openLabRequest({{ $req->id }})" wire:key="lab-{{ $req->id }}">
                    <td><span class="req-no">{{ $req->request_no }}</span></td>
                    <td>
                        <p class="req-patient-name">{{ $req->patient?->full_name ?? '—' }}</p>
                        <p class="req-patient-case">{{ $req->patient?->case_no ?? '' }}</p>
                    </td>
                    <td>
                        <p class="req-test-list">
                            @if($req->tests && count($req->tests))
                                {{ implode(', ', array_slice($req->tests, 0, 3)) }}
                                @if(count($req->tests) > 3)
                                    <span style="color:var(--text-subtle)"> +{{ count($req->tests) - 3 }} more</span>
                                @endif
                            @else
                                <span style="color:var(--text-subtle)">—</span>
                            @endif
                        </p>
                    </td>
                    <td class="req-doctor">@if($req->doctor) Dr. {{ $req->doctor->name }} @else — @endif</td>
                    <td>
                        <span class="badge {{ $req->request_type === 'stat' ? 'badge-stat' : 'badge-routine' }}">
                            {{ strtoupper($req->request_type ?? 'ROUTINE') }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ str_replace('_', '', $req->status) }}">{{ $req->status_label }}</span>
                    </td>
                    <td>
                        <p class="req-time">{{ $req->created_at->timezone('Asia/Manila')->format('M j, H:i') }}</p>
                        <p class="req-time-ago">{{ $req->created_at->diffForHumans() }}</p>
                    </td>
                    <td wire:click.stop>
                        <button wire:click="openLabRequest({{ $req->id }})" type="button" class="btn-open">Open →</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endif

@if($queueType === 'both')<hr class="section-divider">@endif

{{-- ══ RADIOLOGY QUEUE ══ --}}
@if($queueType !== 'lab')
<div class="queue-section">
    <div class="queue-header">
        <div class="queue-header-icon">🩻</div>
        <p class="queue-header-title">Radiology Requests</p>
        <span class="qs-badge {{ $queueFilter === 'pending' ? 'pending' : 'done' }}">
            {{ $radQueue->count() }} {{ $queueFilter === 'pending' ? 'pending' : 'completed' }}
        </span>
    </div>
    <div class="req-table-wrap">
        @if($radQueue->isEmpty())
            <div class="empty-state">
                <div class="empty-icon-wrap">🩻</div>
                <p class="empty-title">{{ $queueFilter === 'pending' ? 'No pending radiology requests' : 'No completed radiology requests' }}</p>
                <p class="empty-sub">{{ $queueFilter === 'pending' ? 'All clear — no pending radiology requests at the moment.' : 'Completed requests will appear here.' }}</p>
            </div>
        @else
        <table class="req-table">
            <thead>
                <tr>
                    <th>Request No.</th>
                    <th>Patient</th>
                    <th>Modality / Exam</th>
                    <th>Ordered By</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th style="width:86px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($radQueue as $req)
                <tr wire:click="openRadRequest({{ $req->id }})" wire:key="rad-{{ $req->id }}">
                    <td><span class="req-no">{{ $req->request_no }}</span></td>
                    <td>
                        <p class="req-patient-name">{{ $req->patient?->full_name ?? '—' }}</p>
                        <p class="req-patient-case">{{ $req->patient?->case_no ?? '' }}</p>
                    </td>
                    <td>
                        @if($req->modality)
                            <span class="badge badge-modality" style="margin-bottom:4px;display:inline-block;">{{ $req->modality }}</span><br>
                        @endif
                        <span class="req-test-list" style="font-size:.74rem;">{{ \Str::limit($req->examination_desired ?? '—', 50) }}</span>
                    </td>
                    <td class="req-doctor">@if($req->doctor) Dr. {{ $req->doctor->name }} @else — @endif</td>
                    <td>
                        <span class="badge badge-{{ str_replace('_', '', $req->status) }}">{{ $req->status_label }}</span>
                    </td>
                    <td>
                        <p class="req-time">{{ $req->created_at->timezone('Asia/Manila')->format('M j, H:i') }}</p>
                        <p class="req-time-ago">{{ $req->created_at->diffForHumans() }}</p>
                    </td>
                    <td wire:click.stop>
                        <button wire:click="openRadRequest({{ $req->id }})" type="button" class="btn-open">Open →</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endif

<script>
(function () {
    const el = document.getElementById('dash-clock');
    if (!el) return;
    const fmt = () => new Date().toLocaleString('en-PH', {
        timeZone: 'Asia/Manila', weekday: 'short', month: 'short',
        day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false
    }).replace(',', ' ·');
    el.textContent = fmt();
    setInterval(() => { el.textContent = fmt(); }, 30000);
})();
</script>

<script>
(function () {
    const brandName = @json($title === 'MedTech Dashboard' ? 'LUMC — MedTech' : ($title === 'RadTech Dashboard' ? 'LUMC — RadTech' : 'LUMC — Tech Portal'));
    const logoUrl   = @json(asset('images/lumc-logo.png'));
    const accent    = @json($accentHex);
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
            .fi-sidebar-item-button.fi-active span {
                color: #ffffff !important;
            }
            .fi-sidebar-item-button:not(.fi-active):hover {
                background: ${accentLight} !important;
                color: ${accentDark} !important;
            }
            .fi-sidebar-item-button:not(.fi-active):hover svg {
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
        brand.innerHTML =
            '<img src="' + logoUrl + '" alt="LUMC">' +
            '<span class="lumc-brand-text">' + brandName + '</span>';
        nav.parentElement.insertBefore(brand, nav);
    }

    function run () {
        injectNavStyle();
        injectBrand();
    }

    // Run immediately, on DOM ready, on Livewire navigation, and on intervals
    // to handle Filament's late CSS loading
    run();
    document.addEventListener('DOMContentLoaded', run);
    document.addEventListener('livewire:navigated', run);
    setTimeout(run, 50);
    setTimeout(run, 200);
    setTimeout(run, 500);
})();
</script>



</x-filament-panels::page>