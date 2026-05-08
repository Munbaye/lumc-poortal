<x-filament-panels::page>

@php
    $isMed     = $this->isMedtech && !$this->isRadtech;
    $isRad     = $this->isRadtech && !$this->isMedtech;
    $queueType = $this->queueType;
    $labQueue  = $this->labQueue;
    $radQueue  = $this->radQueue;

    if ($isMed) {
        $accent     = '#0f766e';
        $dark       = '#134e4a'; $light  = '#5eead4';
        $bgLight    = '#f0fdfa'; $bgDark = 'rgba(13,148,136,.12)'; $sh     = 'rgba(13,148,136,.12)';
    } elseif ($isRad) {
        $accent     = '#475569';
        $dark       = '#334155'; $light  = '#94a3b8';
        $bgLight    = '#f8fafc'; $bgDark = 'rgba(71,85,105,.12)'; $sh     = 'rgba(71,85,105,.12)';
    } else {
        $accent     = '#ea580c';
        $dark       = '#c2410c'; $light  = '#fb923c';
        $bgLight    = '#fff7ed'; $bgDark = 'rgba(234,88,12,.12)'; $sh     = 'rgba(234,88,12,.12)';
    }

@endphp

<style>
/* ════════════════════════════════════════════════════════════════
   TECH DASHBOARD — matches Nurse Portal layout
   Specialty accent injected via PHP variables → inline CSS vars
════════════════════════════════════════════════════════════════ */
:root {
    --ta:       {{ $accent }};
    --ta-dark:  {{ $dark }};
    --ta-light: {{ $light }};
    --ta-bgl:   {{ $bgLight }};
    --ta-bgd:   {{ $bgDark }};
    --ta-sh:    {{ $sh }};
}

/* ── Stat cards ──────────────────────────────────────────────── */
.td-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 13px;
    margin-bottom: 16px;
}
.td-stat {
    border-radius: 14px; padding: 18px 20px;
    display: flex; align-items: center; gap: 16px;
    border: 1px solid;
    transition: box-shadow .18s, transform .18s;
    cursor: default;
}
html:not(.dark) .td-stat {
    background: #fff; border-color: #e5e7eb;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
html.dark .td-stat { background: #0f172a; border-color: #1e293b; }
.td-stat:hover { box-shadow: 0 6px 22px rgba(0,0,0,.08); transform: translateY(-2px); }

/* Accent stat card */
html:not(.dark) .td-stat.accent {
    border-color: var(--ta-light);
    background: linear-gradient(140deg, var(--ta-bgl) 0%, #fff 65%);
}
html.dark .td-stat.accent {
    border-color: var(--ta-brd);
    background: linear-gradient(140deg, var(--ta-bgd) 0%, #0f172a 65%);
}

.td-stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
/* Icon colour variants */
html:not(.dark) .ti-accent { background: var(--ta-bgl); color: var(--ta); }
html:not(.dark) .ti-green  { background: #f0fdf4; color: #059669; }
html:not(.dark) .ti-amber  { background: #fef3c7; color: #d97706; }
html:not(.dark) .ti-blue   { background: #eff6ff; color: #2563eb; }
html.dark .ti-accent { background: var(--ta-bgd); color: var(--ta-light); }
html.dark .ti-green  { background: #052e16; color: #6ee7b7; }
html.dark .ti-amber  { background: #1c1400; color: #fde047; }
html.dark .ti-blue   { background: #0c1a2e; color: #7dd3fc; }

.td-stat-label {
    font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em; margin-bottom: 2px;
}
html:not(.dark) .td-stat-label { color: #9ca3af; }
html.dark       .td-stat-label { color: #475569; }

.td-stat-value {
    font-size: 2.2rem; font-weight: 800; line-height: 1.1;
    letter-spacing: -.03em; font-variant-numeric: tabular-nums;
}
html:not(.dark) .td-stat-value { color: #111827; }
html.dark       .td-stat-value { color: #f1f5f9; }

.td-stat-sub { font-size: .75rem; margin-top: 2px; }
html:not(.dark) .td-stat-sub { color: #6b7280; }
html.dark       .td-stat-sub { color: #475569; }

/* ── Filter / search bar ──────────────────────────────────────── */
.td-filter-bar {
    display: flex; align-items: center;
    gap: 10px; margin-bottom: 12px; flex-wrap: wrap;
}
.td-view-toggle {
    display: inline-flex; border-radius: 10px; padding: 3px; border: 1px solid;
}
html:not(.dark) .td-view-toggle { background: #f9fafb; border-color: #e5e7eb; }
html.dark       .td-view-toggle { background: #0f172a; border-color: #1e293b; }

.td-vt-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 16px; border-radius: 7px;
    font-size: .78rem; font-weight: 600; color: #6b7280;
    background: none; border: none; cursor: pointer;
    transition: all .15s; white-space: nowrap;
}
html:not(.dark) .td-vt-btn.active {
    background: #fff; color: var(--ta);
    box-shadow: 0 1px 4px rgba(0,0,0,.08); font-weight: 700;
}
html.dark .td-vt-btn.active {
    background: #1e293b; color: var(--ta-light);
    box-shadow: 0 1px 4px rgba(0,0,0,.3); font-weight: 700;
}

.td-search-wrap { flex: 1; min-width: 220px; position: relative; }
.td-search-icon {
    position: absolute; left: 11px; top: 50%;
    transform: translateY(-50%); pointer-events: none;
    color: #9ca3af; display: flex; align-items: center;
}
.td-search {
    width: 100%; padding: 9px 14px 9px 36px;
    border: 1.5px solid; border-radius: 10px;
    font-size: .83rem; outline: none;
    transition: border-color .15s, box-shadow .15s;
}
html:not(.dark) .td-search {
    background: #fff; border-color: #e5e7eb; color: #111827;
}
html:not(.dark) .td-search:focus {
    border-color: var(--ta); box-shadow: 0 0 0 3px var(--ta-sh);
}
html:not(.dark) .td-search::placeholder { color: #9ca3af; }
html.dark .td-search {
    background: #0f172a; border-color: #1e293b; color: #e2e8f0;
}
html.dark .td-search:focus {
    border-color: var(--taa); box-shadow: 0 0 0 3px var(--ta-bgd);
}
html.dark .td-search::placeholder { color: #334155; }

.td-filter-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 9px;
    font-size: .78rem; font-weight: 600;
    cursor: pointer; border: 1.5px solid;
    transition: all .15s; white-space: nowrap;
}
html:not(.dark) .td-filter-btn {
    background: #fff; border-color: #e5e7eb; color: #374151;
}
html:not(.dark) .td-filter-btn:hover { border-color: var(--ta); color: var(--ta); }
html.dark .td-filter-btn {
    background: #0f172a; border-color: #1e293b; color: #94a3b8;
}
html.dark .td-filter-btn:hover { border-color: var(--taa); color: var(--ta-light); }

/* ── Section heading ──────────────────────────────────────────── */
.td-sec-head {
    display: flex; align-items: center; gap: 10px;
    margin: 0 0 12px;
}
.td-sec-title {
    display: flex; align-items: center; gap: 7px;
    font-size: .78rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .06em; white-space: nowrap;
}
html:not(.dark) .td-sec-title { color: #374151; }
html.dark       .td-sec-title { color: #e2e8f0; }
.td-sec-line { flex: 1; border-top: 1.5px solid; }
html:not(.dark) .td-sec-line { border-color: #e5e7eb; }
html.dark       .td-sec-line { border-color: #1e293b; }

.td-cnt-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 22px; height: 20px; padding: 0 7px;
    border-radius: 9999px; font-size: .68rem; font-weight: 800;
}
html:not(.dark) .td-cnt-badge.has  { background: var(--ta-bgl); color: var(--ta); }
html:not(.dark) .td-cnt-badge.zero { background: #f3f4f6; color: #6b7280; }
html:not(.dark) .td-cnt-badge.done { background: #d1fae5; color: #065f46; }
html.dark .td-cnt-badge.has  { background: var(--ta-bgd); color: var(--ta-light); }
html.dark .td-cnt-badge.zero { background: #1e293b; color: #475569; }
html.dark .td-cnt-badge.done { background: #052e16; color: #4ade80; }

/* ── Requests table ───────────────────────────────────────────── */
.td-table-wrap {
    border-radius: 14px; overflow: hidden;
    border: 1px solid; margin-bottom: 16px;
}
html:not(.dark) .td-table-wrap {
    background: #fff; border-color: #e5e7eb;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
html.dark .td-table-wrap { background: #0f172a; border-color: #1e293b; }

.td-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
.td-table thead tr { border-bottom: 1px solid; }
html:not(.dark) .td-table thead tr { background: #f9fafb; border-bottom-color: #e5e7eb; }
html.dark       .td-table thead tr { background: #0a0f1a; border-bottom-color: #1e293b; }

.td-table th {
    padding: 10px 15px; text-align: left;
    font-size: .62rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em; white-space: nowrap;
}
html:not(.dark) .td-table th { color: #9ca3af; }
html.dark       .td-table th { color: #475569; }

.td-table td {
    padding: 13px 15px; border-bottom: 1px solid; vertical-align: middle;
}
html:not(.dark) .td-table td { border-bottom-color: #f3f4f6; color: #374151; }
html.dark       .td-table td { border-bottom-color: #1e293b; color: #cbd5e1; }
.td-table tbody tr:last-child td { border-bottom: none !important; }
.td-table tbody tr { cursor: pointer; transition: background .1s; }
html:not(.dark) .td-table tbody tr:hover td { background: var(--ta-bgl); }
html.dark       .td-table tbody tr:hover td { background: var(--ta-bgd); }

/* Table cell atoms */
.td-req-no {
    font-family: 'SF Mono', 'Fira Code', monospace;
    font-size: .75rem; font-weight: 700;
    color: var(--ta); display: block;
}
html.dark .td-req-no { color: var(--ta-light); }

.td-pt-name { font-weight: 700; font-size: .85rem; display: block; }
html:not(.dark) .td-pt-name { color: #111827; }
html.dark       .td-pt-name { color: #f1f5f9; }

.td-pt-case {
    font-family: 'SF Mono', 'Fira Code', monospace;
    font-size: .7rem; color: #9ca3af; margin-top: 2px; display: block;
}
html.dark .td-pt-case { color: #334155; }

.td-test-list { font-size: .78rem; line-height: 1.5; }
html:not(.dark) .td-test-list { color: #374151; }
html.dark       .td-test-list { color: #94a3b8; }

.td-doctor { font-size: .78rem; }
html:not(.dark) .td-doctor { color: #6b7280; }
html.dark       .td-doctor { color: #64748b; }

.td-modality {
    display: inline-block; padding: 2px 9px; border-radius: 9999px;
    font-size: .7rem; font-weight: 700; margin-bottom: 3px;
    background: var(--ta-bgl); color: var(--ta);
}
html.dark .td-modality { background: var(--ta-bgd); color: var(--ta-light); }

/* Status/priority badges */
.td-stat-badge {
    display: inline-block; padding: 3px 10px;
    border-radius: 9999px; font-size: .68rem; font-weight: 700; white-space: nowrap;
}
.tds-stat       { background: #fee2e2; color: #991b1b; }
.tds-routine    { background: #f3f4f6; color: #6b7280; }
.tds-completed  { background: #d1fae5; color: #065f46; }
.tds-pending    { background: #fef3c7; color: #92400e; }
.tds-inprogress { background: #fef9c3; color: #854d0e; }
html.dark .tds-stat       { background: #450a0a; color: #fca5a5; }
html.dark .tds-routine    { background: #1e293b; color: #94a3b8; }
html.dark .tds-completed  { background: #052e16; color: #6ee7b7; }
html.dark .tds-pending    { background: #431407; color: #fdba74; }
html.dark .tds-inprogress { background: #1c1400; color: #fde047; }

.td-time { font-size: .78rem; white-space: nowrap; }
html:not(.dark) .td-time { color: #374151; }
html.dark       .td-time { color: #cbd5e1; }
.td-time-ago { font-size: .68rem; color: #9ca3af; margin-top: 2px; }

/* Open button */
.td-btn-open {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--ta); color: #fff;
    border: none; border-radius: 8px;
    padding: 7px 15px; font-size: .76rem; font-weight: 700;
    cursor: pointer; white-space: nowrap;
    transition: opacity .15s;
}
.td-btn-open:hover { opacity: .85; }

/* Empty state */
.td-empty { text-align: center; padding: 52px 20px; }
.td-empty-icon { margin-bottom: 12px; display: flex; justify-content: center; }
.td-empty-title {
    font-size: .9rem; font-weight: 700; margin-bottom: 4px;
}
html:not(.dark) .td-empty-title { color: #374151; }
html.dark       .td-empty-title { color: #e2e8f0; }
.td-empty-sub { font-size: .78rem; color: #9ca3af; }

@media (max-width: 900px) {
    .td-stats { grid-template-columns: repeat(2, 1fr); }
    }
@media (max-width: 600px) {
    .td-stats { grid-template-columns: 1fr 1fr; }
    .td-filter-bar { flex-wrap: wrap; }
}

/* ══ RESPONSIVE — Tech Dashboard ═════════════════════════════════════ */
@media (max-width: 1024px) {
    .td-stats { grid-template-columns: repeat(2, 1fr); }
    .td-table th, .td-table td { padding: 10px 10px; }
}
@media (max-width: 768px) {
    .td-stats { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .td-filter-bar { flex-direction: column; align-items: stretch; gap: 8px; }
    .td-view-toggle { width: 100%; justify-content: center; }
    .td-search-wrap { width: 100%; }
    .td-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .td-table { min-width: 620px; font-size: .78rem; }
    .td-table th { font-size: .6rem; padding: 8px 8px; }
    .td-table td { padding: 10px 8px; }
    .td-btn-open { padding: 5px 10px; font-size: .72rem; }
    .td-sec-head { flex-wrap: wrap; }
}
@media (max-width: 480px) {
    .td-stats { grid-template-columns: 1fr 1fr; gap: 8px; }
    .td-stat { padding: 12px 10px; gap: 8px; }
    .td-stat-value { font-size: 1.7rem; }
    .td-stat-icon { width: 38px; height: 38px; }
    .td-table { min-width: 520px; }
    .td-pt-name { font-size: .8rem; }
    .td-test-list { font-size: .72rem; }
}

</style>

{{-- ════════════════════════════════════════════════════════════
     PAGE TITLE
════════════════════════════════════════════════════════════ --}}
<h2 style="font-size:1.75rem;font-weight:800;color:#111827;margin:0 0 16px;letter-spacing:-.025em;">Dashboard</h2>

{{-- ════════════════════════════════════════════════════════════
     STAT CARDS
════════════════════════════════════════════════════════════ --}}
<div class="td-stats">

    @if($queueType !== 'radiology')
    <div class="td-stat accent">
        <div class="td-stat-icon ti-accent">
            <x-heroicon-o-beaker class="w-6 h-6" />
        </div>
        <div>
            <p class="td-stat-label">Pending Lab</p>
            <p class="td-stat-value">{{ $this->pendingLabCount }}</p>
            <p class="td-stat-sub">requests awaiting action</p>
        </div>
    </div>
    @endif

    @if($queueType !== 'lab')
    <div class="td-stat">
        <div class="td-stat-icon ti-blue">
            <x-heroicon-o-photo class="w-6 h-6" />
        </div>
        <div>
            <p class="td-stat-label">Pending Rad</p>
            <p class="td-stat-value">{{ $this->pendingRadCount }}</p>
            <p class="td-stat-sub">requests awaiting action</p>
        </div>
    </div>
    @endif

    <div class="td-stat">
        <div class="td-stat-icon ti-green">
            <x-heroicon-o-check-circle class="w-6 h-6" />
        </div>
        <div>
            <p class="td-stat-label">Done Today</p>
            <p class="td-stat-value">{{ $this->myCompletedToday }}</p>
            <p class="td-stat-sub">completed today</p>
        </div>
    </div>

    <div class="td-stat">
        <div class="td-stat-icon ti-amber">
            <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
        </div>
        <div>
            <p class="td-stat-label">Total Results</p>
            <p class="td-stat-value">{{ $this->myTotalResults }}</p>
            <p class="td-stat-sub">all time</p>
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════
     FILTER BAR
════════════════════════════════════════════════════════════ --}}
<div class="td-filter-bar">
    <div class="td-view-toggle">
        <button wire:click="$set('queueFilter','pending')" type="button"
                class="td-vt-btn {{ $queueFilter === 'pending' ? 'active' : '' }}">
            <x-heroicon-o-clock class="w-3 h-3" />
            Pending
        </button>
        <button wire:click="$set('queueFilter','completed')" type="button"
                class="td-vt-btn {{ $queueFilter === 'completed' ? 'active' : '' }}">
            <x-heroicon-o-check-circle class="w-3 h-3" />
            Completed
        </button>
    </div>
    <div class="td-search-wrap">
        <span class="td-search-icon">
            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
        </span>
        <input type="text"
               wire:model.live.debounce.300ms="search"
               class="td-search"
               placeholder="Search by request no., patient name, or diagnosis…">
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     LAB QUEUE
════════════════════════════════════════════════════════════ --}}
@if($queueType !== 'radiology')
<div>
    <div class="td-sec-head">
        <div class="td-sec-title">
            <x-heroicon-o-beaker class="w-4 h-4" />
            Laboratory Requests
        </div>
        <span class="td-cnt-badge {{ $labQueue->count() > 0 ? 'has' : 'zero' }}">
            {{ $labQueue->count() }}
        </span>
        <div class="td-sec-line"></div>
    </div>

    <div class="td-table-wrap">
        @if($labQueue->isEmpty())
        <div class="td-empty">
            <div class="td-empty-icon">
                <x-heroicon-o-beaker class="w-12 h-12 text-gray-300" />
            </div>
            <p class="td-empty-title">
                {{ $queueFilter === 'pending' ? 'No pending lab requests' : 'No completed lab requests' }}
            </p>
            <p class="td-empty-sub">
                {{ $queueFilter === 'pending' ? 'All clear — queue is empty.' : 'Completed requests will appear here.' }}
            </p>
        </div>
        @else
        <table class="td-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Tests Ordered</th>
                    <th>Ordered By</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th style="width:110px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($labQueue as $idx => $req)
                <tr wire:click="openLabRequest({{ $req->id }})" wire:key="lab-{{ $req->id }}">
                    <td>
                        <span style="font-size:.75rem;font-weight:600;color:#9ca3af;">{{ $idx + 1 }}</span>
                    </td>
                    <td>
                        <span class="td-req-no">{{ $req->request_no }}</span>
                        <span class="td-pt-name">{{ $req->patient?->full_name ?? '—' }}</span>
                        <span class="td-pt-case">{{ $req->patient?->case_no ?? '' }}</span>
                    </td>
                    <td>
                        <span class="td-test-list">
                            @if($req->tests && count($req->tests))
                                {{ implode(', ', array_slice($req->tests, 0, 3)) }}
                                @if(count($req->tests) > 3)
                                    <span style="color:#9ca3af;font-size:.72rem;"> +{{ count($req->tests) - 3 }} more</span>
                                @endif
                            @else
                                <span style="color:#9ca3af;">—</span>
                            @endif
                        </span>
                    </td>
                    <td class="td-doctor">
                        {{ $req->doctor ? 'Dr. '.$req->doctor->name : '—' }}
                    </td>
                    <td>
                        <span class="td-stat-badge {{ $req->request_type === 'stat' ? 'tds-stat' : 'tds-routine' }}">
                            {{ strtoupper($req->request_type ?? 'ROUTINE') }}
                        </span>
                    </td>
                    <td>
                        <span class="td-stat-badge tds-{{ str_replace('_', '', $req->status) }}">
                            {{ $req->status_label }}
                        </span>
                    </td>
                    <td>
                        <p class="td-time">{{ $req->created_at->timezone('Asia/Manila')->format('M j, H:i') }}</p>
                        <p class="td-time-ago">{{ $req->created_at->diffForHumans() }}</p>
                    </td>
                    <td wire:click.stop>
                        <button wire:click="openLabRequest({{ $req->id }})" type="button" class="td-btn-open">
                            Open Chart <x-heroicon-o-arrow-right class="w-3 h-3" />
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
<div>
    <div class="td-sec-head">
        <div class="td-sec-title">
            <x-heroicon-o-photo class="w-4 h-4" />
            Radiology Requests
        </div>
        <span class="td-cnt-badge {{ $radQueue->count() > 0 ? 'has' : 'zero' }}">
            {{ $radQueue->count() }}
        </span>
        <div class="td-sec-line"></div>
    </div>

    <div class="td-table-wrap">
        @if($radQueue->isEmpty())
        <div class="td-empty">
            <div class="td-empty-icon">
                <x-heroicon-o-photo class="w-12 h-12 text-gray-300" />
            </div>
            <p class="td-empty-title">
                {{ $queueFilter === 'pending' ? 'No pending radiology requests' : 'No completed radiology requests' }}
            </p>
            <p class="td-empty-sub">
                {{ $queueFilter === 'pending' ? 'All clear — queue is empty.' : 'Completed requests will appear here.' }}
            </p>
        </div>
        @else
        <table class="td-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Modality / Exam</th>
                    <th>Ordered By</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th style="width:110px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($radQueue as $idx => $req)
                <tr wire:click="openRadRequest({{ $req->id }})" wire:key="rad-{{ $req->id }}">
                    <td>
                        <span style="font-size:.75rem;font-weight:600;color:#9ca3af;">{{ $idx + 1 }}</span>
                    </td>
                    <td>
                        <span class="td-req-no">{{ $req->request_no }}</span>
                        <span class="td-pt-name">{{ $req->patient?->full_name ?? '—' }}</span>
                        <span class="td-pt-case">{{ $req->patient?->case_no ?? '' }}</span>
                    </td>
                    <td>
                        @if($req->modality)
                        <span class="td-modality">{{ $req->modality }}</span><br>
                        @endif
                        <span class="td-test-list">{{ \Str::limit($req->examination_desired ?? '—', 50) }}</span>
                    </td>
                    <td class="td-doctor">
                        {{ $req->doctor ? 'Dr. '.$req->doctor->name : '—' }}
                    </td>
                    <td>
                        <span class="td-stat-badge tds-{{ str_replace('_', '', $req->status) }}">
                            {{ $req->status_label }}
                        </span>
                    </td>
                    <td>
                        <p class="td-time">{{ $req->created_at->timezone('Asia/Manila')->format('M j, H:i') }}</p>
                        <p class="td-time-ago">{{ $req->created_at->diffForHumans() }}</p>
                    </td>
                    <td wire:click.stop>
                        <button wire:click="openRadRequest({{ $req->id }})" type="button" class="td-btn-open">
                            Open Chart <x-heroicon-o-arrow-right class="w-3 h-3" />
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