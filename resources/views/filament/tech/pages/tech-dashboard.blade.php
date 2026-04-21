<x-filament-panels::page>

<style>
/* ════════════════════════════════════════════════════════════════
   TECH DASHBOARD  — light + dark safe
   RAD  = gray/slate  #475569
   MED  = teal        #0f766e
   TECH = orange      #ea580c
   All dynamic colors flow through --accent-color CSS variable
════════════════════════════════════════════════════════════════ */

/* ── Hero banner ───────────────────────────────────────────── */
.tech-hero {
    background: var(--hero-gradient);
    border-radius: 1rem;
    padding: 1.75rem 2rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    min-height: 148px;
}
.tech-hero::before {
    content: '';
    position: absolute;
    width: 320px; height: 320px;
    background: rgba(255,255,255,.07);
    border-radius: 50%;
    top: -100px; right: -80px;
    pointer-events: none;
}
.tech-hero::after {
    content: '';
    position: absolute;
    width: 200px; height: 200px;
    background: rgba(255,255,255,.045);
    border-radius: 50%;
    bottom: -70px; right: 220px;
    pointer-events: none;
}
.tech-hero-left  { position:relative; z-index:1; flex:1; min-width:0; }
.tech-hero-right { position:relative; z-index:1; display:flex; gap:.65rem; flex-shrink:0; }
.tech-hero-greeting { font-size:.82rem; font-weight:500; opacity:.82; margin:0 0 .2rem; }
.tech-hero-name     { font-size:1.65rem; font-weight:800; margin:0 0 .3rem; line-height:1.15; letter-spacing:-.01em; }
.tech-hero-sub      { font-size:.82rem; opacity:.75; margin:0; }

/* Stat chips inside hero */
.hero-chip {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.22);
    border-radius: .75rem;
    padding: .7rem 1rem;
    text-align: center;
    min-width: 86px;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}
.hero-chip-icon {
    width: 26px; height: 26px;
    border-radius: .4rem;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .3rem;
    font-size: .95rem;
    line-height: 1;
}
.hero-chip-num   { font-size:1.55rem; font-weight:800; line-height:1; margin-bottom:.18rem; }
.hero-chip-label { font-size:.65rem; font-weight:700; opacity:.8; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; }

/* ── Queue controls ─────────────────────────────────────────── */
.queue-controls { display:flex; align-items:center; gap:12px; margin-bottom:16px; flex-wrap:wrap; }

.search-wrap { flex:1; min-width:200px; position:relative; }
.search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); }
html:not(.dark) .search-wrap .si { color:#9ca3af; }
html.dark       .search-wrap .si { color:#475569; }

.search-input {
    width:100%; padding:9px 12px 9px 34px;
    border:1.5px solid; border-radius:8px; font-size:.875rem; outline:none;
    transition:border-color .15s, box-shadow .15s;
}
html:not(.dark) .search-input { background:#fff; border-color:#e5e7eb; color:#111827; }
html:not(.dark) .search-input:focus { border-color:var(--accent-color); box-shadow:0 0 0 3px var(--accent-shadow); }
html:not(.dark) .search-input::placeholder { color:#9ca3af; }
html.dark       .search-input { background:#0f172a; border-color:#1e293b; color:#e2e8f0; }
html.dark       .search-input:focus { border-color:var(--accent-color); box-shadow:0 0 0 3px var(--accent-shadow); }
html.dark       .search-input::placeholder { color:#334155; }

/* Filter tabs */
.filter-tabs { display:inline-flex; border-radius:8px; padding:3px; border:1px solid; }
html:not(.dark) .filter-tabs { background:#f3f4f6; border-color:#e5e7eb; }
html.dark       .filter-tabs { background:#0f172a; border-color:#1e293b; }

.ft { padding:6px 16px; border-radius:6px; font-size:.82rem; font-weight:600; cursor:pointer; border:none; background:none; transition:all .15s; }
html:not(.dark) .ft        { color:#6b7280; }
html:not(.dark) .ft.active { background:#fff; color:var(--accent-color); box-shadow:0 1px 4px rgba(0,0,0,.1); font-weight:700; }
html.dark       .ft        { color:#64748b; }
html.dark       .ft.active { background:#1e293b; color:var(--accent-light); box-shadow:0 1px 4px rgba(0,0,0,.3); font-weight:700; }

/* Specialty pill */

/* ── Queue sections ─────────────────────────────────────────── */
.queue-section { margin-bottom:24px; }
.queue-section-title { font-size:.875rem; font-weight:700; margin-bottom:10px; display:flex; align-items:center; gap:8px; }
html:not(.dark) .queue-section-title { color:#374151; }
html.dark       .queue-section-title { color:#e5e7eb; }

.qs-badge { font-size:.7rem; padding:2px 8px; border-radius:9999px; font-weight:700; }
html:not(.dark) .qs-badge           { background:#f3f4f6; color:#6b7280; }
html:not(.dark) .qs-badge.pending   { background:var(--accent-shadow); color:var(--accent-color); }
html:not(.dark) .qs-badge.completed { background:#f0fdf4; color:#15803d; }
html.dark       .qs-badge           { background:#1e293b; color:#64748b; }
html.dark       .qs-badge.pending   { background:var(--accent-shadow); color:var(--accent-light); }
html.dark       .qs-badge.completed { background:#052e16; color:#4ade80; }

/* ── Table ──────────────────────────────────────────────────── */
.req-table-wrap { border-radius:10px; overflow:hidden; border:1px solid; }
html:not(.dark) .req-table-wrap { background:#fff; border-color:#e5e7eb; box-shadow:0 1px 4px rgba(0,0,0,.06); }
html.dark       .req-table-wrap { background:#0f172a; border-color:#1e293b; }

.req-table { width:100%; border-collapse:collapse; font-size:.875rem; }
.req-table thead tr { border-bottom:1px solid; }
html:not(.dark) .req-table thead tr { background:#f9fafb; border-bottom-color:#e5e7eb; }
html.dark       .req-table thead tr { background:#0a0f1a; border-bottom-color:#1e293b; }

.req-table th { padding:9px 14px; text-align:left; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; white-space:nowrap; }
html:not(.dark) .req-table th { color:#6b7280; }
html.dark       .req-table th { color:#475569; }

.req-table td { padding:11px 14px; border-bottom:1px solid; vertical-align:top; }
html:not(.dark) .req-table td { border-bottom-color:#f3f4f6; color:#374151; }
html.dark       .req-table td { border-bottom-color:#1e293b; color:#cbd5e1; }
.req-table tbody tr:last-child td { border-bottom:none !important; }
html:not(.dark) .req-table tbody tr:hover td { background:#f9fafb; cursor:pointer; }
html.dark       .req-table tbody tr:hover td { background:rgba(255,255,255,.02); cursor:pointer; }

/* Table cell helpers */
.req-no { font-family:monospace; font-size:.78rem; font-weight:700; color:var(--accent-color); }
html.dark .req-no { color:var(--accent-light); }

.req-patient-name { font-weight:700; }
html:not(.dark) .req-patient-name { color:#111827; }
html.dark       .req-patient-name { color:#f1f5f9; }

.req-patient-case { font-family:monospace; font-size:.7rem; }
html:not(.dark) .req-patient-case { color:#9ca3af; }
html.dark       .req-patient-case { color:#475569; }

.req-test-list { font-size:.78rem; line-height:1.5; }
html:not(.dark) .req-test-list { color:#374151; }
html.dark       .req-test-list { color:#94a3b8; }

.req-doctor { font-size:.78rem; }
html:not(.dark) .req-doctor { color:#6b7280; }
html.dark       .req-doctor { color:#64748b; }

.req-time { font-size:.75rem; white-space:nowrap; }
html:not(.dark) .req-time { color:#374151; }
html.dark       .req-time { color:#cbd5e1; }

.req-time-ago { font-size:.68rem; }
html:not(.dark) .req-time-ago { color:#9ca3af; }
html.dark       .req-time-ago { color:#475569; }

/* Status/priority badges */
.stat-badge { display:inline-block; padding:2px 9px; border-radius:9999px; font-size:.68rem; font-weight:700; white-space:nowrap; }
.stat-stat        { background:#fee2e2; color:#991b1b; }
.stat-routine     { background:#f3f4f6; color:#6b7280; }
.stat-completed   { background:#d1fae5; color:#065f46; }
.stat-pending     { background:#fff7ed; color:#c2410c; }
.stat-inprogress  { background:#fef9c3; color:#854d0e; }
html.dark .stat-stat       { background:#450a0a; color:#fca5a5; }
html.dark .stat-routine    { background:#1e293b; color:#94a3b8; }
html.dark .stat-completed  { background:#052e16; color:#6ee7b7; }
html.dark .stat-pending    { background:#431407; color:#fdba74; }
html.dark .stat-inprogress { background:#1c1400; color:#fde047; }

/* Modality badge — uses accent color */
.modality-badge {
    display:inline-block; padding:2px 9px; border-radius:9999px;
    font-size:.68rem; font-weight:700; white-space:nowrap; margin-bottom:3px;
    background:var(--accent-shadow);
    color:var(--accent-color);
}
html.dark .modality-badge { color:var(--accent-light); }

/* Open button — uses accent color for ALL specialties */
.btn-open {
    color:#fff; border:none; border-radius:6px;
    padding:6px 14px; font-size:.78rem; font-weight:700;
    cursor:pointer; white-space:nowrap;
    background:var(--accent-color);
    transition:opacity .15s;
}
.btn-open:hover { opacity:.85; }

/* Empty state */
.empty-state { text-align:center; padding:40px 20px; }
.empty-icon  { font-size:2.4rem; margin-bottom:9px; }
.empty-title { font-size:.9rem; font-weight:700; margin-bottom:4px; }
html:not(.dark) .empty-title { color:#374151; }
html.dark       .empty-title { color:#e5e7eb; }
.empty-sub { font-size:.78rem; }
html:not(.dark) .empty-sub { color:#9ca3af; }
html.dark       .empty-sub { color:#475569; }

@media (max-width:768px) {
    .tech-hero { flex-direction:column; align-items:flex-start; min-height:auto; }
    .tech-hero-right { flex-wrap:wrap; width:100%; }
    .hero-chip { flex:1; min-width:70px; }
    .tech-hero-name { font-size:1.3rem; }
}
</style>

@php
    $tech      = auth()->user();
    $queueType = $this->queueType;
    $labQueue  = $this->labQueue;
    $radQueue  = $this->radQueue;

    if ($this->isMedtech && !$this->isRadtech) {
        // ── MEDTECH — teal ──────────────────────────────────────
        $gradient    = 'linear-gradient(135deg, #0d9488 0%, #0f766e 55%, #134e4a 100%)';
        $heroSub     = '🧬 Laboratory · Medical Technology';
        $accentColor = '#0f766e';
        $accentLight = '#5eead4';
        $accentShadow= 'rgba(15,118,110,.1)';

    } elseif ($this->isRadtech && !$this->isMedtech) {
        // ── RADTECH — gray/slate ─────────────────────────────────
        $gradient    = 'linear-gradient(135deg, #64748b 0%, #475569 55%, #334155 100%)';
        $heroSub     = '🩻 Radiology · Diagnostic Imaging';
        $accentColor = '#475569';
        $accentLight = '#94a3b8';
        $accentShadow= 'rgba(71,85,105,.1)';

    } else {
        // ── TECH — orange ────────────────────────────────────────
        $gradient    = 'linear-gradient(135deg, #f97316 0%, #ea580c 55%, #c2410c 100%)';
        $heroSub     = '🔬 Laboratory · General Tech';
        $accentColor = '#ea580c';
        $accentLight = '#fb923c';
        $accentShadow= 'rgba(234,88,12,.1)';
    }

    $hour     = (int) now()->format('H');
    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
@endphp

{{-- Inject CSS variables so every element picks up the right specialty color --}}
<style>
    :root {
        --accent-color:  {{ $accentColor }};
        --accent-light:  {{ $accentLight }};
        --accent-shadow: {{ $accentShadow }};
    }
</style>

{{-- ════════════════════════════════════════════════════════════
     HERO BANNER
════════════════════════════════════════════════════════════ --}}
<div class="tech-hero" style="--hero-gradient:{{ $gradient }}">
    <div class="tech-hero-left">
        <p class="tech-hero-greeting">{{ $greeting }},</p>
        <h2 class="tech-hero-name">{{ $tech->name ?? 'Tech Staff' }}</h2>
        <p class="tech-hero-sub">{{ $heroSub }}</p>
    </div>

    <div class="tech-hero-right">
        @if($queueType !== 'radiology')
        <div class="hero-chip">
            <div class="hero-chip-icon">🧪</div>
            <div class="hero-chip-num">{{ $this->pendingLabCount }}</div>
            <div class="hero-chip-label">Pending Lab</div>
        </div>
        @endif

        @if($queueType !== 'lab')
        <div class="hero-chip">
            <div class="hero-chip-icon">🩻</div>
            <div class="hero-chip-num">{{ $this->pendingRadCount }}</div>
            <div class="hero-chip-label">Pending Rad</div>
        </div>
        @endif

        <div class="hero-chip">
            <div class="hero-chip-icon">✅</div>
            <div class="hero-chip-num">{{ $this->myCompletedToday }}</div>
            <div class="hero-chip-label">Done Today</div>
        </div>

        <div class="hero-chip">
            <div class="hero-chip-icon">📋</div>
            <div class="hero-chip-num">{{ $this->myTotalResults }}</div>
            <div class="hero-chip-label">Total Results</div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     QUEUE CONTROLS
════════════════════════════════════════════════════════════ --}}
<div class="queue-controls" style="margin-top:1.5rem;">
    <div class="search-wrap">
        <span class="si">🔍</span>
        <input type="text" wire:model.live.debounce.300ms="search"
               class="search-input"
               placeholder="Search by request no, patient name, or diagnosis…">
    </div>
    <div class="filter-tabs">
        <button wire:click="$set('queueFilter','pending')" type="button"
                class="ft {{ $queueFilter === 'pending' ? 'active' : '' }}">
            Pending
        </button>
        <button wire:click="$set('queueFilter','completed')" type="button"
                class="ft {{ $queueFilter === 'completed' ? 'active' : '' }}">
            Completed
        </button>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     LAB QUEUE
════════════════════════════════════════════════════════════ --}}
@if($queueType !== 'radiology')
<div class="queue-section">
    <p class="queue-section-title">
        🧪 Laboratory Requests
        <span class="qs-badge {{ $queueFilter }}">{{ $labQueue->count() }}</span>
    </p>
    <div class="req-table-wrap">
        @if($labQueue->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">🧪</div>
            <p class="empty-title">
                @if($queueFilter === 'pending') No pending lab requests @else No completed lab requests @endif
            </p>
            <p class="empty-sub">{{ $queueFilter === 'pending' ? 'All clear — no pending laboratory requests.' : 'Completed requests will appear here.' }}</p>
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
                    <th style="width:100px;"></th>
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
                                    <span style="color:#9ca3af;"> +{{ count($req->tests) - 3 }} more</span>
                                @endif
                            @else
                                <span style="color:#9ca3af;">—</span>
                            @endif
                        </p>
                    </td>
                    <td class="req-doctor">
                        @if($req->doctor) Dr. {{ $req->doctor->name }} @else — @endif
                    </td>
                    <td>
                        <span class="stat-badge {{ $req->request_type === 'stat' ? 'stat-stat' : 'stat-routine' }}">
                            {{ strtoupper($req->request_type ?? 'ROUTINE') }}
                        </span>
                    </td>
                    <td>
                        <span class="stat-badge stat-{{ str_replace('_','',$req->status) }}">
                            {{ $req->status_label }}
                        </span>
                    </td>
                    <td>
                        <p class="req-time">{{ $req->created_at->timezone('Asia/Manila')->format('M j H:i') }}</p>
                        <p class="req-time-ago">{{ $req->created_at->diffForHumans() }}</p>
                    </td>
                    <td wire:click.stop>
                        <button wire:click="openLabRequest({{ $req->id }})" type="button" class="btn-open">
                            Open →
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     RADIOLOGY QUEUE
════════════════════════════════════════════════════════════ --}}
@if($queueType !== 'lab')
<div class="queue-section">
    <p class="queue-section-title">
        🩻 Radiology Requests
        <span class="qs-badge {{ $queueFilter }}">{{ $radQueue->count() }}</span>
    </p>
    <div class="req-table-wrap">
        @if($radQueue->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">🩻</div>
            <p class="empty-title">
                @if($queueFilter === 'pending') No pending radiology requests @else No completed radiology requests @endif
            </p>
            <p class="empty-sub">{{ $queueFilter === 'pending' ? 'All clear — no pending radiology requests.' : 'Completed requests will appear here.' }}</p>
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
                    <th style="width:100px;"></th>
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
                        <span class="modality-badge">{{ $req->modality }}</span>
                        <br>
                        @endif
                        <span class="req-test-list" style="font-size:.75rem;">
                            {{ \Str::limit($req->examination_desired ?? '—', 50) }}
                        </span>
                    </td>
                    <td class="req-doctor">
                        @if($req->doctor) Dr. {{ $req->doctor->name }} @else — @endif
                    </td>
                    <td>
                        <span class="stat-badge stat-{{ str_replace('_','',$req->status) }}">
                            {{ $req->status_label }}
                        </span>
                    </td>
                    <td>
                        <p class="req-time">{{ $req->created_at->timezone('Asia/Manila')->format('M j H:i') }}</p>
                        <p class="req-time-ago">{{ $req->created_at->diffForHumans() }}</p>
                    </td>
                    <td wire:click.stop>
                        <button wire:click="openRadRequest({{ $req->id }})" type="button" class="btn-open">
                            Open →
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endif

</x-filament-panels::page>