<x-filament-panels::page>

<style>
/* ═══════════════════════════════════════════════════════════════
   TECH DASHBOARD
   ═══════════════════════════════════════════════════════════════ */

/* ── Stats bar ──────────────────────────────────────────────── */
.stats-bar { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
.stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:16px 18px; display:flex; align-items:center; gap:14px; }
.dark .stat-card { background:#1f2937; border-color:#374151; }
.stat-icon { font-size:1.7rem; width:44px; height:44px; display:flex; align-items:center; justify-content:center; border-radius:9px; flex-shrink:0; }
.stat-icon.orange { background:#fff7ed; }
.stat-icon.blue   { background:#eff6ff; }
.stat-icon.green  { background:#f0fdf4; }
.stat-icon.violet { background:#f5f3ff; }
.stat-label { font-size:.7rem; text-transform:uppercase; letter-spacing:.06em; color:#9ca3af; font-weight:600; }
.stat-value { font-size:1.6rem; font-weight:800; color:#111827; line-height:1.2; }
.dark .stat-value { color:#f3f4f6; }
.stat-sub { font-size:.7rem; color:#6b7280; }

/* ── Queue controls ─────────────────────────────────────────── */
.queue-controls { display:flex; align-items:center; gap:12px; margin-bottom:16px; flex-wrap:wrap; }
.search-wrap { flex:1; min-width:200px; position:relative; }
.search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#9ca3af; }
.search-input { width:100%; padding:9px 12px 9px 34px; border:1px solid #e5e7eb; border-radius:8px; font-size:.85rem; outline:none; background:#fff; color:#111827; }
.dark .search-input { background:#1f2937; border-color:#374151; color:#f3f4f6; }
.search-input:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,.12); }

.filter-tabs { display:inline-flex; background:#f3f4f6; border-radius:8px; padding:3px; }
.dark .filter-tabs { background:#374151; }
.ft { padding:6px 16px; border-radius:6px; font-size:.8rem; font-weight:600; color:#6b7280; cursor:pointer; border:none; background:none; transition:all .15s; }
.ft.active { background:#fff; color:#f97316; box-shadow:0 1px 4px rgba(0,0,0,.1); }
.dark .ft.active { background:#1f2937; color:#fb923c; }

/* ── Queue sections ─────────────────────────────────────────── */
.queue-section { margin-bottom:24px; }
.queue-section-title { font-size:.85rem; font-weight:700; color:#374151; margin-bottom:10px; display:flex; align-items:center; gap:8px; }
.dark .queue-section-title { color:#e5e7eb; }
.qs-badge { font-size:.7rem; background:#f3f4f6; color:#6b7280; padding:2px 8px; border-radius:9999px; font-weight:700; }
.qs-badge.pending { background:#fff7ed; color:#c2410c; }
.qs-badge.completed { background:#f0fdf4; color:#15803d; }

/* ── Request rows ───────────────────────────────────────────── */
.req-table-wrap { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
.dark .req-table-wrap { background:#1f2937; border-color:#374151; }
.req-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.req-table thead tr { background:#f9fafb; border-bottom:1px solid #e5e7eb; }
.dark .req-table thead tr { background:#111827; border-bottom-color:#374151; }
.req-table th { padding:9px 14px; text-align:left; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#6b7280; white-space:nowrap; }
.req-table td { padding:11px 14px; border-bottom:1px solid #f3f4f6; vertical-align:top; }
.dark .req-table td { border-bottom-color:#374151; }
.req-table tbody tr:hover td { background:#fff7ed; cursor:pointer; }
.dark .req-table tbody tr:hover td { background:rgba(249,115,22,.07); }
.req-table tbody tr:last-child td { border-bottom:none; }

.req-no { font-family:monospace; font-size:.78rem; color:#f97316; font-weight:700; }
.req-patient-name { font-weight:700; color:#111827; }
.dark .req-patient-name { color:#f3f4f6; }
.req-patient-case { font-family:monospace; font-size:.7rem; color:#6b7280; }
.req-test-list { font-size:.78rem; color:#374151; line-height:1.5; }
.dark .req-test-list { color:#d1d5db; }
.req-doctor { font-size:.78rem; color:#6b7280; }
.req-time { font-size:.75rem; color:#374151; white-space:nowrap; }
.req-time-ago { font-size:.68rem; color:#9ca3af; }

/* Priority badge */
.stat-badge { display:inline-block; padding:2px 9px; border-radius:9999px; font-size:.68rem; font-weight:700; white-space:nowrap; }
.stat-stat    { background:#fee2e2; color:#991b1b; }
.stat-routine { background:#f3f4f6; color:#6b7280; }
.stat-completed { background:#d1fae5; color:#065f46; }
.stat-pending   { background:#fff7ed; color:#c2410c; }
.stat-inprogress { background:#fef9c3; color:#854d0e; }

/* Open button */
.btn-open { background:#f97316; color:#fff; border:none; border-radius:6px; padding:6px 14px; font-size:.78rem; font-weight:700; cursor:pointer; white-space:nowrap; }
.btn-open:hover { background:#ea580c; }
.btn-open.rad { background:#6d28d9; }
.btn-open.rad:hover { background:#5b21b6; }

/* Empty state */
.empty-state { text-align:center; padding:40px 20px; }
.empty-icon { font-size:2.4rem; margin-bottom:9px; }
.empty-title { font-size:.88rem; font-weight:700; color:#374151; margin-bottom:4px; }
.dark .empty-title { color:#e5e7eb; }
.empty-sub { font-size:.78rem; color:#9ca3af; }
</style>

@php
    $tech       = auth()->user();
    $queueType  = $this->queueType;
    $labQueue   = $this->labQueue;
    $radQueue   = $this->radQueue;
@endphp

{{-- ── STATS BAR ──────────────────────────────────────────────── --}}
<div class="stats-bar">
    @if($queueType !== 'radiology')
    <div class="stat-card">
        <div class="stat-icon orange">🧪</div>
        <div>
            <p class="stat-label">Pending Lab</p>
            <p class="stat-value">{{ $this->pendingLabCount }}</p>
            <p class="stat-sub">requests awaiting</p>
        </div>
    </div>
    @endif
    @if($queueType !== 'lab')
    <div class="stat-card">
        <div class="stat-icon violet">🩻</div>
        <div>
            <p class="stat-label">Pending Radiology</p>
            <p class="stat-value">{{ $this->pendingRadCount }}</p>
            <p class="stat-sub">requests awaiting</p>
        </div>
    </div>
    @endif
    <div class="stat-card" style="{{ $queueType === 'both' ? '' : 'grid-column: span 1' }}">
        <div class="stat-icon green">✅</div>
        <div>
            <p class="stat-label">My Results Today</p>
            <p class="stat-value">{{ $this->myCompletedToday }}</p>
            <p class="stat-sub">uploaded today</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">📋</div>
        <div>
            <p class="stat-label">My Total Results</p>
            <p class="stat-value">{{ $this->myTotalResults }}</p>
            <p class="stat-sub">all time</p>
        </div>
    </div>
</div>

{{-- ── QUEUE CONTROLS ──────────────────────────────────────────── --}}
<div class="queue-controls">
    <div class="search-wrap">
        <span class="si">🔍</span>
        <input type="text" wire:model.live.debounce.300ms="search"
               class="search-input"
               placeholder="Search by request no, patient name, or diagnosis…">
    </div>
    <div class="filter-tabs">
        <button wire:click="$set('queueFilter', 'pending')"   type="button"
                class="ft {{ $queueFilter === 'pending'   ? 'active' : '' }}">
            Pending
        </button>
        <button wire:click="$set('queueFilter', 'completed')" type="button"
                class="ft {{ $queueFilter === 'completed' ? 'active' : '' }}">
            Completed
        </button>
    </div>
    @if($tech->specialty)
    <span style="font-size:.75rem;background:#fff7ed;color:#c2410c;padding:3px 10px;border-radius:9999px;font-weight:700;">
        {{ $tech->specialty }}
    </span>
    @endif
</div>

{{-- ── LAB QUEUE ───────────────────────────────────────────────── --}}
@if($queueType !== 'radiology')
<div class="queue-section">
    <p class="queue-section-title">
        🧪 Laboratory Requests
        <span class="qs-badge {{ $queueFilter === 'pending' ? 'pending' : 'completed' }}">
            {{ $labQueue->count() }}
        </span>
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

{{-- ── RADIOLOGY QUEUE ─────────────────────────────────────────── --}}
@if($queueType !== 'lab')
<div class="queue-section">
    <p class="queue-section-title">
        🩻 Radiology Requests
        <span class="qs-badge {{ $queueFilter === 'pending' ? 'pending' : 'completed' }}">
            {{ $radQueue->count() }}
        </span>
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
                    <td><span class="req-no" style="color:#6d28d9;">{{ $req->request_no }}</span></td>
                    <td>
                        <p class="req-patient-name">{{ $req->patient?->full_name ?? '—' }}</p>
                        <p class="req-patient-case">{{ $req->patient?->case_no ?? '' }}</p>
                    </td>
                    <td>
                        @if($req->modality)
                        <span class="stat-badge" style="background:#f5f3ff;color:#5b21b6;margin-bottom:3px;">{{ $req->modality }}</span>
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
                        <button wire:click="openRadRequest({{ $req->id }})" type="button" class="btn-open rad">
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