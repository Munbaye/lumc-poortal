<x-filament-panels::page>

@php
@endphp

<style>
/* ════════════════════════════════════════════════════════════════
   NURSE — PATIENT LIST DASHBOARD
   Rose/Pink · light + dark safe
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

/* ── Stat cards ────────────────────────────────────────────── */
.stats-bar { display:grid; grid-template-columns:repeat(4,1fr); gap:13px; margin-bottom:10px; }
.stat-card { border-radius:14px; padding:18px 20px; display:flex; align-items:center; gap:16px; border:1px solid; transition:box-shadow .18s,transform .18s; }
html:not(.dark) .stat-card { background:#fff; border-color:#e5e7eb; box-shadow:0 1px 4px rgba(0,0,0,.05); }
html.dark       .stat-card { background:#0f172a; border-color:#1e293b; }
.stat-card:hover { box-shadow:0 6px 22px rgba(0,0,0,.08); transform:translateY(-2px); }
html:not(.dark) .stat-card.is-accent { border-color:var(--np-mid); background:linear-gradient(140deg,var(--np-light) 0%,#fff 65%); }
html.dark       .stat-card.is-accent { border-color:#4c0519; background:linear-gradient(140deg,rgba(190,18,60,.12) 0%,#0f172a 65%); }

.stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
html:not(.dark) .si-rose  { background:#fff1f2; color:#be123c; }
html:not(.dark) .si-green { background:#f0fdf4; color:#059669; }
html:not(.dark) .si-amber { background:#fef3c7; color:#d97706; }
html:not(.dark) .si-blue  { background:#eff6ff; color:#2563eb; }
html.dark .si-rose  { background:#4c0519; color:#fda4af; }
html.dark .si-green { background:#052e16; color:#6ee7b7; }
html.dark .si-amber { background:#1c1400; color:#fde047; }
html.dark .si-blue  { background:#0c1a2e; color:#7dd3fc; }

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
.filter-bar { display:flex; align-items:center; gap:10px; margin-bottom:6px; flex-wrap:wrap; }

.view-toggle { display:inline-flex; border-radius:10px; padding:3px; border:1px solid; }
html:not(.dark) .view-toggle { background:#f9fafb; border-color:#e5e7eb; }
html.dark       .view-toggle { background:#0f172a; border-color:#1e293b; }

.vt-btn { display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:7px; font-size:.78rem; font-weight:600; color:#6b7280; background:none; border:none; cursor:pointer; transition:all .15s; white-space:nowrap; }
.vt-btn svg { width:13px; height:13px; flex-shrink:0; }
html:not(.dark) .vt-btn.active { background:#fff; color:var(--np); box-shadow:0 1px 4px rgba(0,0,0,.08); font-weight:700; }
html.dark       .vt-btn.active { background:#1e293b; color:#fda4af; box-shadow:0 1px 4px rgba(0,0,0,.3); font-weight:700; }

.search-wrap { flex:1; min-width:220px; position:relative; }
.search-icon { position:absolute; left:11px; top:50%; transform:translateY(-50%); pointer-events:none; color:#9ca3af; display:flex; align-items:center; }
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

.type-badge { display:inline-flex; align-items:center; gap:4px; font-size:.7rem; font-weight:700; padding:2px 8px; border-radius:9999px; }
.type-er  { background:#fee2e2; color:#991b1b; }
.type-opd { background:#eff6ff; color:#1d4ed8; }
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
.empty-icon-wrap { width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; }
html:not(.dark) .empty-icon-wrap { background:var(--np-light); color:var(--np); }
html.dark       .empty-icon-wrap { background:#4c0519; color:#fda4af; }
.empty-title { font-size:.9rem; font-weight:700; margin-bottom:4px; }
html:not(.dark) .empty-title { color:#111827; }
html.dark       .empty-title { color:#f1f5f9; }
.empty-sub { font-size:.78rem; color:#9ca3af; }

.pag-wrap { padding:12px 15px; border-top:1px solid; }
html:not(.dark) .pag-wrap { border-top-color:#f3f4f6; }
html.dark       .pag-wrap { border-top-color:#1e293b; }

/* ── Filter button ─────────────────────────────────────────── */
.btn-filter {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 16px; border-radius:10px; font-size:.83rem; font-weight:600;
    cursor:pointer; border:1.5px solid; white-space:nowrap;
    transition:all .15s;
}
html:not(.dark) .btn-filter { background:#fff; border-color:#e5e7eb; color:#374151; }
html.dark       .btn-filter { background:#0f172a; border-color:#1e293b; color:#e2e8f0; }
html:not(.dark) .btn-filter:hover { border-color:var(--np); color:var(--np); }
html.dark       .btn-filter:hover { border-color:var(--npa); color:#fda4af; }
html:not(.dark) .btn-filter.is-active { border-color:var(--np); color:var(--np); background:var(--np-light); }
html.dark       .btn-filter.is-active { border-color:var(--npa); color:#fda4af; background:#4c0519; }
.btn-filter .f-count {
    background:var(--np); color:#fff; font-size:.58rem; font-weight:800;
    width:16px; height:16px; border-radius:50%;
    display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;
}
html.dark .btn-filter .f-count { background:var(--npa); }

/* ── Filter panel ──────────────────────────────────────────── */
.filter-panel-wrap { position:relative; display:flex; justify-content:flex-end; min-height:0; margin-bottom:0; }
.filter-panel {
    position:absolute; right:0; top:6px; z-index:100;
    width:300px; background:#fff; border:1px solid #e5e7eb;
    border-radius:12px; padding:18px 20px;
    box-shadow:0 8px 32px rgba(0,0,0,.14);
}
html.dark .filter-panel { background:#0f172a; border-color:#1e293b; box-shadow:0 8px 32px rgba(0,0,0,.5); }
.fp-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; padding-bottom:10px; border-bottom:1px solid #f3f4f6; }
html.dark .fp-head { border-bottom-color:#1e293b; }
.fp-head-title { font-size:.88rem; font-weight:700; color:#111827; }
html.dark .fp-head-title { color:#f1f5f9; }
.fp-reset { font-size:.78rem; color:var(--np); font-weight:600; background:none; border:none; cursor:pointer; padding:0; }
html.dark .fp-reset { color:#fda4af; }
.fp-field { margin-bottom:13px; }
.fp-label { display:block; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; margin-bottom:4px; }
html:not(.dark) .fp-label { color:#9ca3af; }
html.dark       .fp-label { color:#475569; }
.fp-sel, .fp-date { width:100%; padding:8px 10px; border:1.5px solid; border-radius:8px; font-size:.82rem; outline:none; box-sizing:border-box; transition:border-color .15s; }
html:not(.dark) .fp-sel, html:not(.dark) .fp-date { background:#fff; border-color:#e5e7eb; color:#111827; }
html:not(.dark) .fp-sel:focus, html:not(.dark) .fp-date:focus { border-color:var(--np); }
html.dark       .fp-sel, html.dark       .fp-date { background:#0f172a; border-color:#1e293b; color:#e2e8f0; }
html.dark       .fp-sel:focus, html.dark       .fp-date:focus { border-color:var(--npa); }
.fp-sel { appearance:none; -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 9px center; background-size:11px; padding-right:26px; }
.fp-date-row { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
.fp-date-sub { font-size:.65rem; color:#9ca3af; margin-bottom:3px; }
.fp-active-range { font-size:.7rem; color:var(--np); font-weight:600; margin-top:5px; background:var(--np-light); border-radius:6px; padding:3px 8px; display:inline-flex; align-items:center; gap:5px; }
html.dark .fp-active-range { color:#fda4af; background:#4c0519; }

/* ── Active filter badges ──────────────────────────────────── */
.active-filter-bar { display:flex; align-items:center; gap:6px; flex-wrap:wrap; padding:4px 0 6px; font-size:.78rem; }
.af-label { color:#6b7280; font-weight:600; white-space:nowrap; font-size:.75rem; }
html.dark .af-label { color:#475569; }
.af-badge { display:inline-flex; align-items:center; gap:4px; background:var(--np-light); color:var(--np-dark); border:1px solid var(--np-mid); border-radius:9999px; padding:2px 10px; font-size:.7rem; font-weight:700; white-space:nowrap; }
html.dark .af-badge { background:#4c0519; color:#fda4af; border-color:#7f1d1d; }
.af-badge button { background:none; border:none; cursor:pointer; color:inherit; font-size:.85rem; padding:0; margin-left:2px; line-height:1; }
.af-clear-all { background:none; border:none; color:#9ca3af; cursor:pointer; font-size:.75rem; font-weight:600; margin-left:2px; }
html.dark .af-clear-all { color:#475569; }

.pending-clerk-badge { background:#fef3c7; color:#92400e; padding:2px 7px; border-radius:9999px; font-weight:700; font-size:.68rem; display:inline-flex; align-items:center; gap:4px; }
html.dark .pending-clerk-badge { background:#1c1400; color:#fde047; }

@media(max-width:768px) {
    .stats-bar { grid-template-columns:1fr; }
    .filter-bar { flex-direction:column; align-items:stretch; }
    .search-wrap { min-width:unset; width:100%; }
    .patients-table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .patients-table { min-width:600px; }
}
</style>

{{-- ══ STAT CARDS ══ --}}
<div class="stats-bar">
    <div class="stat-card is-accent">
        <div class="stat-icon si-rose">
            <x-heroicon-o-building-office-2 class="w-6 h-6" />
        </div>
        <div>
            <div class="stat-label">Currently Admitted</div>
            <div class="stat-value">{{ $this->totalAdmitted }}</div>
            <div class="stat-sub">patients on ward</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green">
            <x-heroicon-o-check-circle class="w-6 h-6" />
        </div>
        <div>
            <div class="stat-label">Discharged</div>
            <div class="stat-value">{{ $this->totalDischarged }}</div>
            <div class="stat-sub">total discharged</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-amber">
            <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
        </div>
        <div>
            <div class="stat-label">Pending Orders</div>
            <div class="stat-value">{{ $this->totalPendingOrders }}</div>
            <div class="stat-sub">orders awaiting action</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue">
            <x-heroicon-o-calendar-days class="w-6 h-6" />
        </div>
        <div>
            <div class="stat-label">Shift Date</div>
            <div class="stat-value" style="font-size:1.3rem;">{{ now()->timezone('Asia/Manila')->format('M j, Y') }}</div>
            <div class="stat-sub">{{ now()->timezone('Asia/Manila')->format('l') }}</div>
        </div>
    </div>
</div>

{{-- ══ FILTER + PANEL + ACTIVE FILTERS + TABLE (grouped to kill Filament spacing) ══ --}}
<div style="display:flex; flex-direction:column; gap:6px;">

    {{-- ══ FILTER BAR ══ --}}
    <div class="filter-bar">
        <div class="view-toggle">
            <button wire:click="$set('viewFilter','admitted')" type="button"
                    class="vt-btn {{ $viewFilter === 'admitted' ? 'active' : '' }}">
                <x-heroicon-o-building-office-2 class="w-4 h-4" />
                Admitted
            </button>
            <button wire:click="$set('viewFilter','discharged')" type="button"
                    class="vt-btn {{ $viewFilter === 'discharged' ? 'active' : '' }}">
                <x-heroicon-o-check-circle class="w-4 h-4" />
                Discharged
            </button>
            <button wire:click="$set('viewFilter','all')" type="button"
                    class="vt-btn {{ $viewFilter === 'all' ? 'active' : '' }}">
                <x-heroicon-o-folder-open class="w-4 h-4" />
                All Patients
            </button>
        </div>
        <div class="search-wrap">
            <span class="search-icon">
                <x-heroicon-o-magnifying-glass style="width:15px;height:15px;" />
            </span>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search by patient name or case number…"
                   class="search-input">
        </div>
        <button wire:click="toggleFilters" type="button"
                class="btn-filter {{ $showFilters ? 'is-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
            Filters
            @if($this->activeFilterCount > 0)
            <span class="f-count">{{ $this->activeFilterCount }}</span>
            @endif
        </button>
        @if($search)
        <button wire:click="$set('search','')" type="button"
                style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:.78rem;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
            <x-heroicon-o-x-mark style="width:13px;height:13px;" /> Clear
        </button>
        @endif
    </div>

    {{-- ══ FILTER PANEL ══ --}}
    <div class="filter-panel-wrap">
        @if($showFilters)
        <div class="filter-panel">
            <div class="fp-head">
                <span class="fp-head-title">Filters</span>
                <button type="button" wire:click="clearFilters" class="fp-reset">Reset</button>
            </div>
            <div class="fp-field">
                <label class="fp-label">Sex</label>
                <select wire:model.live="sexFilter" class="fp-sel">
                    <option value="">All</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="fp-field">
                <label class="fp-label">Service</label>
                <select wire:model.live="serviceFilter" class="fp-sel">
                    <option value="">All Services</option>
                    @foreach($this->serviceOptions as $svc)
                    <option value="{{ $svc }}">{{ $svc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fp-field">
                <label class="fp-label">Date Range (Registration)</label>
                <div class="fp-date-row">
                    <div>
                        <div class="fp-date-sub">From</div>
                        <input type="date" wire:model.live="dateFrom" class="fp-date">
                    </div>
                    <div>
                        <div class="fp-date-sub">Until</div>
                        <input type="date" wire:model.live="dateUntil" class="fp-date">
                    </div>
                </div>
                @if($dateFrom || $dateUntil)
                <div class="fp-active-range">
                    <x-heroicon-o-calendar-days style="width:13px;height:13px;" />
                    @if($dateFrom && $dateUntil)
                        {{ \Carbon\Carbon::parse($dateFrom)->format('M j') }} – {{ \Carbon\Carbon::parse($dateUntil)->format('M j, Y') }}
                    @elseif($dateFrom)
                        From {{ \Carbon\Carbon::parse($dateFrom)->format('M j, Y') }}
                    @else
                        Until {{ \Carbon\Carbon::parse($dateUntil)->format('M j, Y') }}
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ══ ACTIVE FILTERS ══ --}}
    @if($this->hasActiveFilters)
    <div class="active-filter-bar">
        <span class="af-label">Active filters:</span>
        @if($sexFilter)
        <span class="af-badge">Sex: {{ $sexFilter }} <button wire:click="$set('sexFilter','')">×</button></span>
        @endif
        @if($serviceFilter)
        <span class="af-badge">Service: {{ $serviceFilter }} <button wire:click="$set('serviceFilter','')">×</button></span>
        @endif
        @if($dateFrom)
        <span class="af-badge">From: {{ \Carbon\Carbon::parse($dateFrom)->format('M j, Y') }} <button wire:click="$set('dateFrom','')">×</button></span>
        @endif
        @if($dateUntil)
        <span class="af-badge">Until: {{ \Carbon\Carbon::parse($dateUntil)->format('M j, Y') }} <button wire:click="$set('dateUntil','')">×</button></span>
        @endif
        <button wire:click="clearFilters" class="af-clear-all">Clear all ×</button>
    </div>
    @endif

    {{-- ══ PATIENTS TABLE ══ --}}
    <div class="patients-table-wrap">
        @if($this->admittedPatients->count() > 0)
        <table class="patients-table">
            <thead>
                <tr>
                    <th style="width:32px;">#</th>
                    <th>Patient</th>
                    @if($viewFilter === 'all' || $viewFilter === 'discharged')
                        <th>Entry</th>
                        <th>Status</th>
                    @endif
                    <th>Service</th>
                    <th>Diagnosis</th>
                    <th>Physician</th>
                    @if($viewFilter === 'admitted')
                        <th style="text-align:center;">Pending Orders</th>
                    @endif
                    <th>{{ $viewFilter === 'admitted' ? 'Admitted' : ($viewFilter === 'discharged' ? 'Discharged' : 'Registered') }}</th>
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
                    @if($viewFilter === 'all' || $viewFilter === 'discharged')
                    <td>
                        @if($visit->visit_type === 'ER')
                            <span class="type-badge type-er">
                                <x-heroicon-o-truck style="width:11px;height:11px;" /> ER
                            </span>
                        @else
                            <span class="type-badge type-opd">
                                <x-heroicon-o-clipboard-document-list style="width:11px;height:11px;" /> OPD
                            </span>
                        @endif
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
                            @if($pendingCount > 0)
                                {{ $pendingCount }}
                            @else
                                <x-heroicon-o-check style="width:12px;height:12px;" />
                            @endif
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
                                <p style="margin-top:2px;">
                                    <span class="pending-clerk-badge">
                                        <x-heroicon-o-clock style="width:11px;height:11px;" />
                                        Pending Clerk
                                    </span>
                                </p>
                            @endif
                        @elseif($viewFilter === 'discharged')
                            @if($visit->discharged_at)
                                <p class="adm-time">{{ $visit->discharged_at->timezone('Asia/Manila')->format('M j H:i') }}</p>
                                <p class="adm-ago">{{ $visit->discharged_at->diffForHumans() }}</p>
                            @else
                                <p style="color:#9ca3af;font-size:.78rem;">—</p>
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
            <div class="empty-icon-wrap">
                <x-heroicon-o-building-office-2 class="w-7 h-7" />
            </div>
            <p class="empty-title">
                @if($search || $this->hasActiveFilters) No patients match your search or filters
                @elseif($viewFilter === 'admitted') No admitted patients
                @elseif($viewFilter === 'discharged') No discharged patients
                @else No patients found
                @endif
            </p>
            <p class="empty-sub">
                @if($search || $this->hasActiveFilters) Try adjusting your filters or clearing them.
                @elseif($viewFilter === 'admitted') Switch to "Discharged" or "All Patients" to see other patients.
                @elseif($viewFilter === 'discharged') No patients have been discharged yet.
                @else No patient visits are recorded yet.
                @endif
            </p>
        </div>
        @endif
    </div>

</div>{{-- end grouped wrapper --}}

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