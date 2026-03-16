<x-filament-panels::page>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    .td-wrap  { font-family: 'DM Sans', sans-serif; }
    .td-mono  { font-family: 'DM Mono', monospace; }

    /* ── Greeting Banner ── */
    .td-banner {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 60%, #1e3a8a 100%);
        border-radius: 16px;
        padding: 28px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        position: relative;
        overflow: hidden;
    }
    .td-banner::before {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }
    .td-banner::after {
        content: '';
        position: absolute;
        right: 60px; bottom: -60px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }
    .td-banner-greeting {
        font-size: 13px;
        font-weight: 500;
        color: rgba(255,255,255,0.72);
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .td-banner-name {
        font-size: 26px;
        font-weight: 700;
        color: #fff;
        line-height: 1.2;
    }
    .td-banner-sub {
        font-size: 14px;
        color: rgba(255,255,255,0.68);
        margin-top: 6px;
    }
    .td-banner-date {
        text-align: right;
        color: rgba(255,255,255,0.82);
        font-size: 14px;
        font-weight: 500;
        white-space: nowrap;
        position: relative;
        z-index: 1;
    }
    .td-banner-date .td-time {
        font-size: 34px;
        font-weight: 700;
        color: #fff;
        display: block;
        line-height: 1.1;
        font-family: 'DM Mono', monospace;
        letter-spacing: -0.02em;
    }

    /* ── Stat Cards ── */
    .td-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-top: 20px;
    }
    @media (max-width: 1100px) { .td-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px)  { .td-stats { grid-template-columns: 1fr; } }

    .td-card {
        background: #fff;
        border: 1.5px solid #e5e7eb;
        border-radius: 14px;
        padding: 22px 24px 18px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        transition: box-shadow 0.2s, transform 0.18s;
        position: relative;
        overflow: hidden;
    }
    .td-card:hover {
        box-shadow: 0 8px 28px rgba(0,0,0,0.09);
        transform: translateY(-2px);
    }
    .td-card-accent {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        border-radius: 14px 14px 0 0;
    }
    .td-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }
    .td-card-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .td-card-icon svg { width: 22px; height: 22px; }
    .td-card-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #6b7280;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .td-card-value {
        font-size: 40px;
        font-weight: 700;
        line-height: 1;
        font-family: 'DM Mono', monospace;
        letter-spacing: -0.02em;
    }
    .td-card-footer {
        font-size: 13px;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 5px;
        padding-top: 2px;
        border-top: 1px solid #f3f4f6;
    }
    .td-card-footer svg { width: 13px; height: 13px; flex-shrink: 0; }

    /* Color variants */
    .td-card-blue .td-card-accent    { background: #1d4ed8; }
    .td-card-blue .td-card-icon      { background: #eff6ff; }
    .td-card-blue .td-card-icon svg  { color: #1d4ed8; }
    .td-card-blue .td-card-value     { color: #1d4ed8; }

    .td-card-green .td-card-accent   { background: #16a34a; }
    .td-card-green .td-card-icon     { background: #f0fdf4; }
    .td-card-green .td-card-icon svg { color: #16a34a; }
    .td-card-green .td-card-value    { color: #16a34a; }

    .td-card-violet .td-card-accent   { background: #7c3aed; }
    .td-card-violet .td-card-icon     { background: #f5f3ff; }
    .td-card-violet .td-card-icon svg { color: #7c3aed; }
    .td-card-violet .td-card-value    { color: #7c3aed; }

    .td-card-slate .td-card-accent   { background: #475569; }
    .td-card-slate .td-card-icon     { background: #f8fafc; }
    .td-card-slate .td-card-icon svg { color: #475569; }
    .td-card-slate .td-card-value    { color: #334155; }

    /* ── Scope notice ── */
    .td-scope-notice {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        font-size: 12.5px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 99px;
        margin-top: 14px;
    }
    .td-scope-notice svg { width: 13px; height: 13px; }

    /* ── Orders Panel ── */
    .td-panel {
        background: #fff;
        border: 1.5px solid #e5e7eb;
        border-radius: 16px;
        margin-top: 24px;
        overflow: hidden;
    }
    .td-panel-header {
        padding: 20px 28px;
        border-bottom: 1.5px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .td-panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .td-panel-title svg { width: 20px; height: 20px; color: #1d4ed8; flex-shrink: 0; }
    .td-panel-title h2  { font-size: 17px; font-weight: 700; color: #111827; margin: 0; }

    .td-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 11px;
        border-radius: 99px;
    }
    .td-badge-dot {
        width: 7px; height: 7px;
        background: #1d4ed8;
        border-radius: 50%;
        animation: td-pulse 1.8s ease-in-out infinite;
    }
    @keyframes td-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.45; transform: scale(0.8); }
    }

    /* ── Table ── */
    .td-table-wrap { overflow-x: auto; }
    .td-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14.5px;
    }
    .td-table thead tr {
        background: #f9fafb;
        border-bottom: 1.5px solid #e5e7eb;
    }
    .td-table th {
        padding: 13px 22px;
        text-align: left;
        font-size: 11.5px;
        font-weight: 700;
        color: #6b7280;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .td-table th:last-child { text-align: right; }
    .td-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.12s;
    }
    .td-table tbody tr:last-child { border-bottom: none; }
    .td-table tbody tr:hover { background: #eff6ff; }
    .td-table td {
        padding: 17px 22px;
        vertical-align: middle;
        color: #374151;
    }
    .td-table td:last-child { text-align: right; }

    .td-patient-name { font-size: 15px; font-weight: 600; color: #111827; line-height: 1.3; }
    .td-case-no      { font-size: 12px; color: #9ca3af; margin-top: 3px; }
    .td-order-text   { color: #374151; line-height: 1.55; max-width: 300px; }
    .td-doctor       { font-weight: 500; color: #374151; }
    .td-date         { font-weight: 600; color: #374151; }
    .td-time-small   { font-size: 12.5px; color: #9ca3af; margin-top: 2px; }

    /* Fix 4: Waiting duration pill */
    .td-waiting {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 99px;
        white-space: nowrap;
        font-family: 'DM Mono', monospace;
    }
    .td-waiting.urgent  { background: #fef2f2; color: #dc2626; }  /* > 2 hrs */
    .td-waiting.warning { background: #fff7ed; color: #c2410c; }  /* 1–2 hrs */
    .td-waiting.normal  { background: #f0fdf4; color: #15803d; }  /* < 1 hr  */
    .td-waiting svg     { width: 11px; height: 11px; }

    /* Fix 5: Upload button per row instead of Mark Done */
    .td-upload-row-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #1d4ed8;
        color: #fff !important;
        font-size: 13px;
        font-weight: 600;
        padding: 9px 16px;
        border-radius: 9px;
        text-decoration: none !important;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .td-upload-row-btn:hover {
        background: #1e40af;
        box-shadow: 0 2px 10px rgba(29,78,216,0.25);
    }
    .td-upload-row-btn svg { width: 14px; height: 14px; }

    /* Empty state */
    .td-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 64px 32px;
        text-align: center;
    }
    .td-empty-icon {
        width: 64px; height: 64px;
        background: #f0fdf4;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
    }
    .td-empty-icon svg { width: 30px; height: 30px; color: #16a34a; }
    .td-empty h3 { font-size: 17px; font-weight: 700; color: #111827; margin: 0 0 6px; }
    .td-empty p  { font-size: 14.5px; color: #9ca3af; margin: 0; }

    .td-more-notice {
        padding: 14px 22px;
        text-align: center;
        font-size: 13px;
        color: #9ca3af;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
    }

    /* ── Dark mode ── */
    .dark .td-card,
    .dark .td-panel            { background: #1e293b; border-color: #334155; }
    .dark .td-panel-header     { border-color: #334155; }
    .dark .td-panel-title h2   { color: #f1f5f9; }
    .dark .td-card-label       { color: #94a3b8; }
    .dark .td-card-footer      { color: #64748b; border-color: #334155; }
    .dark .td-card-slate .td-card-value { color: #94a3b8; }
    .dark .td-scope-notice     { background: rgba(29,78,216,0.12); border-color: #1e40af; color: #93c5fd; }
    .dark .td-table thead tr   { background: #0f172a; border-color: #334155; }
    .dark .td-table th         { color: #64748b; }
    .dark .td-table tbody tr   { border-color: #1e293b; }
    .dark .td-table tbody tr:hover { background: rgba(29,78,216,0.07); }
    .dark .td-table td         { color: #cbd5e1; }
    .dark .td-patient-name     { color: #f1f5f9; }
    .dark .td-case-no          { color: #64748b; }
    .dark .td-order-text       { color: #cbd5e1; }
    .dark .td-doctor           { color: #94a3b8; }
    .dark .td-date             { color: #cbd5e1; }
    .dark .td-more-notice      { background: #0f172a; border-color: #1e293b; color: #475569; }
    .dark .td-empty-icon       { background: rgba(22,163,74,0.1); }
    .dark .td-empty h3         { color: #f1f5f9; }
    .dark .td-empty p          { color: #64748b; }
    .dark .td-table-wrap       { background: #1e293b; }
</style>

<div class="td-wrap">

    {{-- ── Greeting Banner ── --}}
    <div class="td-banner">
        <div style="position:relative;z-index:1">
            <p class="td-banner-greeting">Welcome back</p>
            <p class="td-banner-name">{{ auth()->user()->name }}</p>
            <p class="td-banner-sub">
                {{ auth()->user()->specialty ?? 'Medical Technologist' }}
                &nbsp;·&nbsp; LUMC Tech Portal
            </p>
        </div>
        <div class="td-banner-date">
            <span class="td-time" id="td-live-time">{{ now()->format('H:i') }}</span>
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>

    {{-- Fix 2: Scope notice — tells the tech whose numbers these are --}}
    <div class="td-scope-notice">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
        Showing stats for your account only
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="td-stats" style="margin-top:14px">

        {{-- Fix 2: Pending orders (hospital-wide — all techs see all pending) --}}
        <div class="td-card td-card-blue">
            <div class="td-card-accent"></div>
            <div class="td-card-top">
                <div>
                    <p class="td-card-label">Pending Orders</p>
                    <p class="td-card-value">{{ $this->getPendingOrdersCount() }}</p>
                </div>
                <div class="td-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/></svg>
                </div>
            </div>
            <div class="td-card-footer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                @if($this->getPendingOrdersCount() > 0) Requires attention @else All clear @endif
            </div>
        </div>

        {{-- Fix 2+3: Completed THIS WEEK by this tech (not just today, not all techs) --}}
        <div class="td-card td-card-green">
            <div class="td-card-accent"></div>
            <div class="td-card-top">
                <div>
                    <p class="td-card-label">Completed This Week</p>
                    <p class="td-card-value">{{ $this->getCompletedThisWeekCount() }}</p>
                </div>
                <div class="td-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
            </div>
            <div class="td-card-footer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5"/></svg>
                Your completions this week
            </div>
        </div>

        {{-- Fix 2: My uploads today (this tech only) --}}
        <div class="td-card td-card-violet">
            <div class="td-card-accent"></div>
            <div class="td-card-top">
                <div>
                    <p class="td-card-label">My Uploads Today</p>
                    <p class="td-card-value">{{ $this->getMyUploadsTodayCount() }}</p>
                </div>
                <div class="td-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                </div>
            </div>
            <div class="td-card-footer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                Files you uploaded today
            </div>
        </div>

        {{-- Fix 2: My total results (this tech only) --}}
        <div class="td-card td-card-slate">
            <div class="td-card-accent"></div>
            <div class="td-card-top">
                <div>
                    <p class="td-card-label">My Total Results</p>
                    <p class="td-card-value">{{ $this->getMyTotalResultsCount() }}</p>
                </div>
                <div class="td-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/></svg>
                </div>
            </div>
            <div class="td-card-footer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"/></svg>
                All-time uploads by you
            </div>
        </div>

    </div>

    {{-- ── Pending Doctor's Orders ── --}}
    <div class="td-panel">

        <div class="td-panel-header">
            <div class="td-panel-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/></svg>
                <h2>Pending Doctor's Orders</h2>
            </div>
            @if($this->getPendingOrdersCount() > 0)
                <span class="td-badge">
                    <span class="td-badge-dot"></span>
                    {{ $this->getPendingOrdersCount() }} pending
                </span>
            @endif
        </div>

        @php $orders = $this->getPendingOrders(); @endphp

        @if($orders->isEmpty())
            <div class="td-empty">
                <div class="td-empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <h3>All Clear!</h3>
                <p>No pending doctor's orders at the moment. Great work.</p>
            </div>
        @else
            <div class="td-table-wrap">
                <table class="td-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Order / Instruction</th>
                            <th>Ordering Physician</th>
                            <th>Ordered At</th>
                            <th>Waiting</th>  {{-- Fix 4 --}}
                            <th>Action</th>   {{-- Fix 5 --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @php
                                // Fix 4: Calculate waiting duration
                                $minutesWaiting = $order->created_at->diffInMinutes(now());
                                $hoursWaiting   = $order->created_at->diffInHours(now());

                                if ($minutesWaiting < 60) {
                                    $waitLabel = $minutesWaiting . 'm';
                                    $waitClass = 'normal';
                                } elseif ($hoursWaiting < 2) {
                                    $waitLabel = $hoursWaiting . 'h ' . ($minutesWaiting % 60) . 'm';
                                    $waitClass = 'warning';
                                } else {
                                    $waitLabel = $hoursWaiting . 'h';
                                    $waitClass = 'urgent';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <p class="td-patient-name">
                                        {{ $order->visit?->patient
                                            ? $order->visit->patient->first_name . ' ' . $order->visit->patient->family_name
                                            : '—' }}
                                    </p>
                                    <p class="td-case-no td-mono">{{ $order->visit?->patient?->case_no ?? '' }}</p>
                                </td>
                                <td><p class="td-order-text">{{ $order->order_text }}</p></td>
                                <td><p class="td-doctor">{{ $order->doctor?->name ?? '—' }}</p></td>
                                <td>
                                    <p class="td-date">{{ $order->created_at->format('M d, Y') }}</p>
                                    <p class="td-time-small td-mono">{{ $order->created_at->format('h:i A') }}</p>
                                </td>

                                {{-- Fix 4: Waiting duration with urgency color --}}
                                <td>
                                    <span class="td-waiting {{ $waitClass }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        {{ $waitLabel }}
                                    </span>
                                </td>

                                {{-- Fix 5: Upload button per row — no Mark Done --}}
                                <td>
                                    <a href="{{ \App\Filament\Tech\Resources\ResultUploadResource::getUrl('create') }}?order={{ $order->id }}"
                                       class="td-upload-row-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                                        Upload Result
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($this->getPendingOrdersCount() > 20)
                <div class="td-more-notice">
                    Showing oldest 20 of <strong>{{ $this->getPendingOrdersCount() }}</strong> pending orders — most urgent first.
                </div>
            @endif
        @endif

    </div>

</div>

<script>
    (function () {
        function pad(n) { return String(n).padStart(2, '0'); }
        function tick() {
            const el = document.getElementById('td-live-time');
            if (!el) return;
            const d = new Date();
            el.textContent = pad(d.getHours()) + ':' + pad(d.getMinutes());
        }
        tick();
        setInterval(tick, 30000);
    })();
</script>
</x-filament-panels::page>