<x-filament-panels::page>

<style>
/* ── NURSE DASHBOARD ─────────────────────────────────────────────── */

/* Stats bar */
.stats-bar {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}
.stat-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.dark .stat-card { background: #1f2937; border-color: #374151; }
.stat-icon {
    font-size: 1.8rem;
    width: 48px; height: 48px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 10px;
    flex-shrink: 0;
}
.stat-icon.blue   { background: #dbeafe; }
.stat-icon.amber  { background: #fef3c7; }
.stat-icon.rose   { background: #ffe4e6; }
.stat-body { flex: 1; }
.stat-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; color: #9ca3af; font-weight: 600; }
.stat-value { font-size: 1.6rem; font-weight: 800; color: #111827; line-height: 1.2; }
.dark .stat-value { color: #f3f4f6; }
.stat-sub   { font-size: .72rem; color: #6b7280; }

/* Search + filter bar */
.filter-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.search-wrap {
    flex: 1;
    min-width: 200px;
    position: relative;
}
.search-wrap .si {
    position: absolute;
    left: 11px; top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: .9rem;
    pointer-events: none;
}
.search-input {
    width: 100%;
    padding: 9px 12px 9px 34px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: .85rem;
    outline: none;
    background: #fff;
    color: #111827;
}
.dark .search-input { background: #1f2937; border-color: #374151; color: #f3f4f6; }
.search-input:focus { border-color: #f43f5e; box-shadow: 0 0 0 3px rgba(244,63,94,.12); }
.filter-select {
    padding: 9px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: .85rem;
    outline: none;
    background: #fff;
    color: #111827;
    min-width: 160px;
}
.dark .filter-select { background: #1f2937; border-color: #374151; color: #f3f4f6; }
.filter-select:focus { border-color: #f43f5e; }
.filter-badge {
    font-size: .72rem;
    background: #ffe4e6;
    color: #be123c;
    padding: 3px 10px;
    border-radius: 9999px;
    font-weight: 700;
    white-space: nowrap;
}

/* Table */
.patients-table-wrap {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}
.dark .patients-table-wrap { background: #1f2937; border-color: #374151; }
.patients-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .875rem;
}
.patients-table thead tr {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}
.dark .patients-table thead tr { background: #111827; border-bottom-color: #374151; }
.patients-table th {
    padding: 10px 14px;
    text-align: left;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #6b7280;
    white-space: nowrap;
}
.patients-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: top;
}
.dark .patients-table td { border-bottom-color: #374151; }
.patients-table tbody tr { transition: background .12s; cursor: pointer; }
.patients-table tbody tr:hover td { background: #fff1f2; }
.dark .patients-table tbody tr:hover td { background: rgba(244,63,94,.07); }
.patients-table tbody tr:last-child td { border-bottom: none; }

/* Patient name cell */
.pt-name { font-weight: 700; color: #111827; }
.dark .pt-name { color: #f3f4f6; }
.pt-case { font-family: monospace; font-size: .75rem; color: #6b7280; margin-top: 2px; }

/* Service badge */
.svc-badge {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 9999px;
    font-size: .72rem;
    font-weight: 700;
    background: #fce7f3;
    color: #9d174d;
    white-space: nowrap;
}

/* Payment class badge */
.pay-badge {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 9999px;
    font-size: .7rem;
    font-weight: 700;
}
.pay-charity { background: #d1fae5; color: #065f46; }
.pay-private { background: #f3f4f6; color: #374151; }

/* Pending orders badge */
.orders-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 26px;
    height: 22px;
    padding: 0 7px;
    border-radius: 9999px;
    font-size: .72rem;
    font-weight: 800;
}
.orders-badge.has-orders { background: #fef3c7; color: #92400e; }
.orders-badge.no-orders  { background: #d1fae5; color: #065f46; }

/* Status badge for "All" mode */
.status-pill { display:inline-block; padding:2px 9px; border-radius:9999px; font-size:.7rem; font-weight:700; }
.s-admitted   { background:#d1fae5; color:#065f46; }
.s-discharged { background:#f3f4f6; color:#374151; }
.s-registered { background:#fef9c3; color:#854d0e; }
.s-assessed   { background:#e0f2fe; color:#0c4a6e; }
.s-referred   { background:#fef3c7; color:#92400e; }
.s-vitals     { background:#f0fdf4; color:#166534; }

/* Entry type */
.type-er  { background:#fee2e2; color:#991b1b; font-size:.7rem; font-weight:700; padding:2px 7px; border-radius:9999px; }
.type-opd { background:#eff6ff; color:#1d4ed8; font-size:.7rem; font-weight:700; padding:2px 7px; border-radius:9999px; }

.adm-time { font-size:.78rem; color:#374151; white-space:nowrap; }
.dark .adm-time { color:#d1d5db; }
.adm-ago  { font-size:.7rem; color:#9ca3af; margin-top:2px; }

/* Open chart button */
.btn-open-chart {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f43f5e;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 14px;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}
.btn-open-chart:hover { background: #e11d48; }

/* Empty state */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}
.empty-icon { font-size: 2.8rem; margin-bottom: 10px; }
.empty-title { font-size: .95rem; font-weight: 700; color: #374151; margin-bottom: 4px; }
.dark .empty-title { color: #e5e7eb; }
.empty-sub { font-size: .82rem; color: #9ca3af; }

/* Pagination wrapper */
.pag-wrap { padding: 12px 14px; border-top: 1px solid #f3f4f6; }
.dark .pag-wrap { border-top-color: #374151; }
</style>

{{-- ── STATS BAR ────────────────────────────────────────────────────── --}}
<div class="stats-bar">
    <div class="stat-card">
        <div class="stat-icon blue">🏥</div>
        <div class="stat-body">
            <p class="stat-label">Currently Admitted</p>
            <p class="stat-value">{{ $this->totalAdmitted }}</p>
            <p class="stat-sub">patients on ward</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">📋</div>
        <div class="stat-body">
            <p class="stat-label">Pending Orders</p>
            <p class="stat-value">{{ $this->totalPendingOrders }}</p>
            <p class="stat-sub">orders awaiting action</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon rose">⏰</div>
        <div class="stat-body">
            <p class="stat-label">Shift Date</p>
            <p class="stat-value" style="font-size:1rem;margin-top:4px;">{{ now()->timezone('Asia/Manila')->format('M j, Y') }}</p>
            <p class="stat-sub">{{ now()->timezone('Asia/Manila')->format('l') }}</p>
        </div>
    </div>
</div>

{{-- ── VIEW TOGGLE + FILTER BAR ─────────────────────────────────── --}}
<div class="filter-bar">

    {{-- Admitted / All toggle --}}
    <div class="view-toggle">
        <button wire:click="$set('viewFilter','admitted')"
                type="button"
                class="vt-btn {{ $viewFilter === 'admitted' ? 'active' : '' }}">
            🏥 Admitted
        </button>
        <button wire:click="$set('viewFilter','all')"
                type="button"
                class="vt-btn {{ $viewFilter === 'all' ? 'active' : '' }}">
            🗂️ All Patients
        </button>
    </div>

    <div class="search-wrap">
        <span class="si">🔍</span>
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Search by patient name or case number…"
               class="search-input">
    </div>

    <select wire:model.live="serviceFilter" class="filter-select">
        <option value="">All Services</option>
        @foreach($this->serviceOptions as $svc)
        <option value="{{ $svc }}">{{ $svc }}</option>
        @endforeach
    </select>

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

{{-- ── PATIENTS TABLE ───────────────────────────────────────────────── --}}
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
                <th style="width:110px;"></th>
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
                <td style="color:#9ca3af;font-size:.75rem;font-weight:600;">
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
                <td>
                    <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
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
                    <p style="font-size:.83rem;color:#374151;line-height:1.4;">
                        {{ \Str::limit($visit->admitting_diagnosis ?? $visit->medicalHistory?->diagnosis ?? '—', 50) }}
                    </p>
                </td>
                <td style="font-size:.8rem;color:#374151;">
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

    {{-- Pagination --}}
    <div class="pag-wrap">
        {{ $this->admittedPatients->links() }}
    </div>

    @else
    <div class="empty-state">
        <div class="empty-icon">🏥</div>
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

</x-filament-panels::page>