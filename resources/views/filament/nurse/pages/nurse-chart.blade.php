<x-filament-panels::page>

<style>
/* ══════════════════════════════════════════════════════════════════
   NURSE CHART STYLES (unchanged from original except orders tab)
   ══════════════════════════════════════════════════════════════════ */

.chart-page { display:flex; flex-direction:column; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; background:#fff; }
.dark .chart-page { background:#111827; border-color:#374151; }

.chart-header {
    background: linear-gradient(135deg, #881337 0%, #f43f5e 100%);
    padding: 16px 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; flex-wrap: wrap;
}
.chart-header-left { flex: 1; min-width: 200px; }
.pt-name-big { font-size: 1.1rem; font-weight: 800; color: #fff; letter-spacing: .02em; }
.pt-case-big { font-family: monospace; font-size: .78rem; color: #fda4af; margin-top: 2px; }
.header-pills { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
.h-pill { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.22); border-radius: 6px; padding: 5px 14px; text-align: center; }
.h-pill .pl { font-size: .6rem; text-transform: uppercase; letter-spacing: .06em; color: #fda4af; }
.h-pill .pv { font-size: .82rem; font-weight: 700; color: #fff; }
.svc-pill { background: #be123c; color: #fff; font-size: .72rem; font-weight: 700; padding: 4px 14px; border-radius: 9999px; }
.btn-back-hdr { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.3); color:#fff; font-size:.78rem; font-weight:600; padding:7px 14px; border-radius:6px; text-decoration:none; flex-shrink:0; cursor:pointer; }
.btn-back-hdr:hover { background:rgba(255,255,255,.25); }

.chart-tabs { display:flex; border-bottom:2px solid #e5e7eb; background:#fff; padding:0 16px; overflow-x:auto; }
.dark .chart-tabs { background:#1f2937; border-bottom-color:#374151; }
.chart-tab { display:inline-flex; align-items:center; gap:6px; padding:11px 14px; font-size:.8rem; font-weight:600; color:#6b7280; cursor:pointer; border:none; background:none; border-bottom:2.5px solid transparent; margin-bottom:-2px; white-space:nowrap; transition:color .15s, border-color .15s; }
.chart-tab:hover { color:#374151; }
.dark .chart-tab { color:#9ca3af; }
.chart-tab.active { color:#f43f5e; border-bottom-color:#f43f5e; font-weight:700; }
.dark .chart-tab.active { color:#fb7185; border-bottom-color:#fb7185; }
.tab-badge { background:#ef4444; color:#fff; font-size:.62rem; font-weight:700; padding:1px 5px; border-radius:9999px; min-width:17px; text-align:center; }
.tab-badge-green { background:#059669; }

.chart-content { padding: 22px 26px; background: #f9fafb; min-height: 480px; }
.dark .chart-content { background: #111827; }

.sec-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid #e5e7eb; }
.dark .sec-head { border-bottom-color:#374151; }
.sec-title { font-size:.95rem; font-weight:700; color:#111827; }
.dark .sec-title { color:#f3f4f6; }

/* ── REDESIGNED ORDERS TAB ────────────────────────────────────── */

/* Order group */
.order-group-wrap { margin-bottom: 24px; }
.order-group-hdr { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
.order-group-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#6b7280; white-space:nowrap; }
.order-group-line { flex:1; border-top:1px solid #e5e7eb; }
.dark .order-group-line { border-top-color:#374151; }
.order-group-doc { font-size:.7rem; color:#9ca3af; white-space:nowrap; }

/* Individual order row */
.order-row {
    display:grid;
    grid-template-columns:28px 1fr auto;
    gap:12px;
    align-items:start;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:8px;
    padding:12px 14px;
    margin-bottom:8px;
    transition:border-color .12s;
}
.dark .order-row { background:#1f2937; border-color:#374151; }
.order-row.is-pending { border-left:3px solid #f59e0b; }
.order-row.is-carried { border-left:3px solid #059669; opacity:.8; }
.order-row.is-discontinued { border-left:3px solid #dc2626; opacity:.6; }
.order-row:hover { border-color:#d1d5db; }
.dark .order-row:hover { border-color:#4b5563; }

.order-num-circle {
    width:24px; height:24px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:.68rem; font-weight:800;
    flex-shrink:0; margin-top:1px;
}
.order-num-pending      { background:#fef3c7; color:#92400e; }
.order-num-carried      { background:#d1fae5; color:#065f46; }
.order-num-discontinued { background:#fee2e2; color:#991b1b; }

.order-body { flex:1; min-width:0; }
.order-text-main { font-size:.9rem; color:#111827; font-weight:500; line-height:1.5; word-break:break-word; }
.dark .order-text-main { color:#f3f4f6; }
.order-text-main.struck { text-decoration:line-through; opacity:.6; }
.order-carry-meta { font-size:.7rem; color:#059669; margin-top:4px; font-style:italic; }
.order-disc-meta  { font-size:.7rem; color:#dc2626; margin-top:4px; font-style:italic; }
.order-written-meta { font-size:.7rem; color:#9ca3af; margin-top:3px; }

/* Mark as carried action area */
.carry-action { display:flex; flex-direction:column; align-items:flex-end; gap:5px; flex-shrink:0; }
.status-badge { display:inline-block; padding:2px 10px; border-radius:9999px; font-size:.68rem; font-weight:700; white-space:nowrap; }
.status-pending      { background:#fef3c7; color:#92400e; }
.status-carried      { background:#d1fae5; color:#065f46; }
.status-discontinued { background:#fee2e2; color:#991b1b; }

.btn-carry {
    background:#059669; color:#fff; border:none; border-radius:6px;
    padding:6px 14px; font-size:.78rem; font-weight:700;
    cursor:pointer; white-space:nowrap; display:inline-flex; align-items:center; gap:5px;
}
.btn-carry:hover { background:#047857; }
.btn-carry-confirm { background:#dc2626; color:#fff; border:none; border-radius:6px; padding:6px 14px; font-size:.78rem; font-weight:700; cursor:pointer; }
.btn-cancel-sm { background:#e5e7eb; color:#374151; border:none; border-radius:6px; padding:6px 10px; font-size:.75rem; cursor:pointer; }
.dark .btn-cancel-sm { background:#374151; color:#e5e7eb; }

/* Mark All as Carried banner */
.mark-all-banner {
    background:#f0fdf4; border:1.5px solid #86efac; border-radius:8px;
    padding:12px 16px; margin-bottom:16px;
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    flex-wrap:wrap;
}
.dark .mark-all-banner { background:#022c22; border-color:#16a34a; }
.mark-all-text { font-size:.82rem; color:#15803d; }
.dark .mark-all-text { color:#4ade80; }
.btn-mark-all {
    background:#059669; color:#fff; border:none; border-radius:7px;
    padding:8px 18px; font-size:.83rem; font-weight:700; cursor:pointer;
    display:inline-flex; align-items:center; gap:6px; white-space:nowrap;
}
.btn-mark-all:hover { background:#047857; }

/* ── SOAP Notes (unchanged) ───────────────────────────────────── */
.soap-form { background:#fff; border:1.5px solid #f43f5e; border-radius:8px; padding:18px 20px; margin-bottom:20px; }
.dark .soap-form { background:#1f2937; border-color:#be123c; }
.soap-form-title { font-size:.85rem; font-weight:700; color:#be123c; margin-bottom:14px; padding-bottom:8px; border-bottom:1px solid #ffe4e6; }
.dark .soap-form-title { border-bottom-color:rgba(190,18,60,.25); }
.soap-row { display: grid; grid-template-columns: 80px 1fr; gap: 10px; margin-bottom: 10px; align-items: start; }
.soap-letter { font-size: 1.1rem; font-weight: 900; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px; }
.soap-s { background: #dbeafe; color: #1e40af; }
.soap-o { background: #dcfce7; color: #166534; }
.soap-a { background: #fef9c3; color: #854d0e; }
.soap-p { background: #ede9fe; color: #5b21b6; }
.soap-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; display: block; margin-bottom: 3px; }
.soap-textarea { width: 100%; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 10px; font-size: .85rem; resize: vertical; font-family: inherit; color: #111827; background: #fff; outline: none; line-height: 1.6; }
.dark .soap-textarea { background: #374151; border-color: #4b5563; color: #f3f4f6; }
.soap-textarea:focus { border-color: #f43f5e; box-shadow: 0 0 0 2px rgba(244,63,94,.12); }

.note-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px 16px; margin-bottom: 10px; }
.dark .note-card { background: #1f2937; border-color: #374151; }
.note-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid #f3f4f6; }
.dark .note-header { border-bottom-color: #374151; }
.note-nurse { font-size: .82rem; font-weight: 700; color: #374151; }
.dark .note-nurse { color: #e5e7eb; }
.note-time { font-size: .72rem; color: #9ca3af; }
.note-soap-row { display: grid; grid-template-columns: 28px 1fr; gap: 8px; margin-bottom: 7px; align-items: start; }
.note-soap-letter { font-size: .75rem; font-weight: 900; width: 22px; height: 22px; border-radius: 5px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
.note-soap-text { font-size: .83rem; color: #374151; line-height: 1.55; }
.dark .note-soap-text { color: #d1d5db; }
.note-soap-label { font-size: .65rem; font-weight: 700; text-transform: uppercase; color: #9ca3af; }

/* Buttons */
.btn-primary { background:#f43f5e; color:#fff; border:none; border-radius:7px; padding:9px 22px; font-size:.85rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.btn-primary:hover { background:#e11d48; }
.btn-secondary { background:#fff; color:#374151; border:1px solid #e5e7eb; border-radius:7px; padding:9px 18px; font-size:.85rem; font-weight:600; cursor:pointer; }
.dark .btn-secondary { background:#374151; color:#e5e7eb; border-color:#4b5563; }
.btn-secondary:hover { background:#f3f4f6; }
.btn-add-note { background:#f43f5e; color:#fff; border:none; border-radius:7px; padding:9px 18px; font-size:.83rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.btn-add-note:hover { background:#e11d48; }
.btn-add-note.is-cancel { background:#6b7280; }
.btn-add-note.is-cancel:hover { background:#4b5563; }

/* Placeholders */
.placeholder-wrap { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.placeholder-card { background: #fff; border: 1.5px dashed #e5e7eb; border-radius: 10px; padding: 32px 20px; text-align: center; }
.dark .placeholder-card { background: #1f2937; border-color: #374151; }
.placeholder-card.full-width { grid-column: 1 / -1; }
.ph-icon { font-size: 2.4rem; margin-bottom: 10px; }
.ph-title { font-size: .92rem; font-weight: 700; color: #374151; margin-bottom: 5px; }
.dark .ph-title { color: #e5e7eb; }
.ph-desc { font-size: .8rem; color: #9ca3af; margin-bottom: 16px; line-height: 1.6; }
.btn-coming-soon { background: #f3f4f6; color: #9ca3af; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 18px; font-size: .82rem; font-weight: 600; cursor: not-allowed; }

.empty-state { text-align: center; padding: 48px 20px; background: #fff; border: 1.5px dashed #e5e7eb; border-radius: 8px; }
.dark .empty-state { background: #1f2937; border-color: #374151; }
.empty-icon { font-size: 2.5rem; margin-bottom: 9px; }
.empty-title { font-size: .9rem; font-weight: 700; color: #374151; margin-bottom: 3px; }
.dark .empty-title { color: #e5e7eb; }
.empty-sub { font-size: .78rem; color: #9ca3af; }
</style>

@if($visit && $visit->patient)
@php
    $patient    = $visit->patient;
    $history    = $visit->medicalHistory;
    $allOrders  = $visit->doctorsOrders ?? collect();
    $allNotes   = $visit->nursesNotes   ?? collect();
    $pendingCnt = $allOrders->where('status', 'pending')->count();
    $service    = $visit->admitted_service ?? $history?->service ?? '—';
    $admittedAt = $visit->clerk_admitted_at
        ? $visit->clerk_admitted_at->timezone('Asia/Manila')->format('M j, Y H:i')
        : '—';
@endphp

<div class="chart-page">

    {{-- ════════════════════════════════════════════════════
         PATIENT HEADER
    ════════════════════════════════════════════════════ --}}
    <div class="chart-header">
        <div class="chart-header-left">
            <p class="pt-name-big">{{ $patient->full_name }}</p>
            <p class="pt-case-big">{{ $patient->case_no }}</p>
        </div>
        <div class="header-pills">
            <div class="h-pill"><p class="pl">Age / Sex</p><p class="pv">{{ $patient->age_display }} · {{ $patient->sex }}</p></div>
            <div class="h-pill"><p class="pl">Admitting Diagnosis</p><p class="pv" style="font-size:.76rem;max-width:200px;white-space:normal;line-height:1.3;">{{ $visit->admitting_diagnosis ?? $history?->diagnosis ?? '—' }}</p></div>
            <span class="svc-pill">{{ $service }}</span>
            <div class="h-pill"><p class="pl">Admitted</p><p class="pv">{{ $admittedAt }}</p></div>
            @if($history?->doctor)<div class="h-pill"><p class="pl">Physician</p><p class="pv">Dr. {{ $history->doctor->name }}</p></div>@endif
        </div>
        <button wire:click="goBack" type="button" class="btn-back-hdr">← Patient List</button>
    </div>

    {{-- ════════════════════════════════════════════════════
         TAB BAR
    ════════════════════════════════════════════════════ --}}
    <div class="chart-tabs">
        <button wire:click="setTab('orders')"   class="chart-tab {{ $activeTab==='orders'   ? 'active':'' }}">
            📋 Doctor's Orders
            @if($pendingCnt > 0)<span class="tab-badge">{{ $pendingCnt }}</span>
            @elseif($allOrders->count() > 0)<span class="tab-badge tab-badge-green">✓</span>@endif
        </button>
        <button wire:click="setTab('notes')"    class="chart-tab {{ $activeTab==='notes'    ? 'active':'' }}">
            📝 Nurse's Notes
            @if($allNotes->count() > 0)<span class="tab-badge" style="background:#6366f1;">{{ $allNotes->count() }}</span>@endif
        </button>
        <button wire:click="setTab('mar')"      class="chart-tab {{ $activeTab==='mar'      ? 'active':'' }}">💊 MAR</button>
        <button wire:click="setTab('vitals')"   class="chart-tab {{ $activeTab==='vitals'   ? 'active':'' }}">📊 Vitals Sheet</button>
        <button wire:click="setTab('iv')"       class="chart-tab {{ $activeTab==='iv'       ? 'active':'' }}">💧 IV Fluid</button>
        <button wire:click="setTab('blood')"    class="chart-tab {{ $activeTab==='blood'    ? 'active':'' }}">🩸 Blood Transfusion</button>
        <button wire:click="setTab('io')"       class="chart-tab {{ $activeTab==='io'       ? 'active':'' }}">📏 I &amp; O</button>
        <button wire:click="setTab('handover')" class="chart-tab {{ $activeTab==='handover' ? 'active':'' }}">🔄 Handover</button>
    </div>

    {{-- ════════════════════════════════════════════════════
         CONTENT AREA
    ════════════════════════════════════════════════════ --}}
    <div class="chart-content">

        {{-- ══════════════════════════════════════════════════
             DOCTOR'S ORDERS — REDESIGNED
             Each line = one order the nurse marks individually.
             Different nurses on different shifts can each mark
             one instance of recurring orders (e.g., 3× a day).
        ══════════════════════════════════════════════════ --}}
        @if($activeTab === 'orders')

        <div class="sec-head">
            <h2 class="sec-title">Doctor's Orders</h2>
            <span style="font-size:.78rem;color:#6b7280;">
                {{ $allOrders->count() }} order(s)
                &nbsp;·&nbsp; <span style="color:#d97706;font-weight:700;">{{ $pendingCnt }} pending</span>
                &nbsp;·&nbsp; {{ $allOrders->where('status','carried')->count() }} carried
            </span>
        </div>

        @if($allOrders->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <p class="empty-title">No orders written yet</p>
            <p class="empty-sub">Doctor's orders will appear here once written from the Doctor panel.</p>
        </div>

        @else

        {{-- Mark All as Carried — only when there are pending orders --}}
        @if($pendingCnt > 0)
        <div class="mark-all-banner">
            <div class="mark-all-text">
                <strong>{{ $pendingCnt }} pending order(s)</strong> — click "Mark All as Carried" to carry all at once, or mark each item individually below.
            </div>
            <button wire:click="carryAllOrders"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60"
                    type="button" class="btn-mark-all">
                <span wire:loading.remove wire:target="carryAllOrders">✅ Mark All as Carried</span>
                <span wire:loading wire:target="carryAllOrders">Marking…</span>
            </button>
        </div>
        @endif

        {{-- Order groups (by date written) --}}
        @foreach($allOrders->groupBy(fn($o) => $o->order_date?->timezone('Asia/Manila')->format('Y-m-d H:i')) as $dateKey => $group)
        <div class="order-group-wrap">
            <div class="order-group-hdr">
                <p class="order-group-label">
                    {{ \Carbon\Carbon::parse($dateKey)->timezone('Asia/Manila')->format('F j, Y · H:i') }}
                </p>
                <div class="order-group-line"></div>
                @if($group->first()->doctor)
                <p class="order-group-doc">Dr. {{ $group->first()->doctor->name }}</p>
                @endif
            </div>

            @foreach($group as $i => $order)
            <div class="order-row is-{{ $order->status }}" wire:key="n-order-{{ $order->id }}">

                {{-- Number circle --}}
                <div class="order-num-circle order-num-{{ $order->status }}">
                    {{ $i + 1 }}
                </div>

                {{-- Order text + metadata --}}
                <div class="order-body">
                    <p class="order-text-main {{ $order->isDiscontinued() ? 'struck':'' }}">
                        {{ $order->order_text }}
                    </p>
                    <p class="order-written-meta">
                        Written {{ $order->order_date?->timezone('Asia/Manila')->format('M j, Y H:i') }}
                    </p>
                    @if($order->isCarried() && $order->completed_at)
                    <p class="order-carry-meta">
                        ✓ Carried by {{ $order->completedBy?->name ?? 'Nurse' }}
                        at {{ $order->completed_at->timezone('Asia/Manila')->format('M j, Y H:i') }}
                    </p>
                    @endif
                    @if($order->isDiscontinued() && $order->completed_at)
                    <p class="order-disc-meta">
                        ✕ Discontinued at {{ $order->completed_at->timezone('Asia/Manila')->format('M j, Y H:i') }}
                    </p>
                    @endif
                </div>

                {{-- Action area --}}
                <div class="carry-action">
                    <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>

                    @if($order->isPending())
                        @if($confirmCarryId === $order->id)
                        {{-- Two-step confirm --}}
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;margin-top:2px;">
                            <p style="font-size:.68rem;color:#059669;font-weight:700;">Mark as carried?</p>
                            <div style="display:flex;gap:5px;">
                                <button wire:click="carryOrder({{ $order->id }})"
                                        wire:loading.attr="disabled"
                                        type="button" class="btn-carry-confirm">
                                    <span wire:loading.remove wire:target="carryOrder({{ $order->id }})">✓ Yes</span>
                                    <span wire:loading wire:target="carryOrder({{ $order->id }})">…</span>
                                </button>
                                <button wire:click="$set('confirmCarryId', null)" type="button" class="btn-cancel-sm">No</button>
                            </div>
                        </div>
                        @else
                        <button wire:click="$set('confirmCarryId', {{ $order->id }})"
                                type="button" class="btn-carry" style="margin-top:2px;">
                            ✓ Mark as Carried
                        </button>
                        @endif
                    @endif
                </div>

            </div>
            @endforeach
        </div>
        @endforeach

        @endif {{-- allOrders empty --}}

        {{-- ══════════════════════════════════════════════════
             NURSE'S NOTES (SOAP) — unchanged
        ══════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'notes')

        <div class="sec-head">
            <h2 class="sec-title">Nurse's Notes</h2>
            <button wire:click="toggleAddNote" type="button"
                    class="btn-add-note {{ $addingNote ? 'is-cancel':'' }}">
                @if($addingNote) ✕ Cancel @else ✏️ Add SOAP Note @endif
            </button>
        </div>

        @if($addingNote)
        <div class="soap-form">
            <p class="soap-form-title">
                New SOAP Note &nbsp;·&nbsp;
                <span style="font-weight:400;color:#6b7280;">
                    {{ now()->timezone('Asia/Manila')->format('F j, Y H:i') }}
                    &nbsp;·&nbsp; {{ auth()->user()->name }}
                </span>
            </p>
            <div class="soap-row">
                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;"><div class="soap-letter soap-s">S</div><span style="font-size:.62rem;color:#1e40af;font-weight:700;">Subj.</span></div>
                <div><span class="soap-label">Subjective — what the patient / family reports</span><textarea wire:model="soapS" rows="3" class="soap-textarea" placeholder="e.g., Patient c/o severe headache, rated 8/10. States pain started 2 hours ago."></textarea></div>
            </div>
            <div class="soap-row">
                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;"><div class="soap-letter soap-o">O</div><span style="font-size:.62rem;color:#166534;font-weight:700;">Obj.</span></div>
                <div><span class="soap-label">Objective — measurable / observable data</span><textarea wire:model="soapO" rows="3" class="soap-textarea" placeholder="e.g., BP 150/90 mmHg, PR 88 bpm, Temp 37.2°C, O2 sat 98%. IV D5W infusing well at 20 gtts/min."></textarea></div>
            </div>
            <div class="soap-row">
                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;"><div class="soap-letter soap-a">A</div><span style="font-size:.62rem;color:#854d0e;font-weight:700;">Assess.</span></div>
                <div><span class="soap-label">Assessment — nursing interpretation</span><textarea wire:model="soapA" rows="2" class="soap-textarea" placeholder="e.g., Acute pain related to hypertensive crisis."></textarea></div>
            </div>
            <div class="soap-row">
                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;"><div class="soap-letter soap-p">P</div><span style="font-size:.62rem;color:#5b21b6;font-weight:700;">Plan</span></div>
                <div><span class="soap-label">Plan — nursing interventions and next steps</span><textarea wire:model="soapP" rows="3" class="soap-textarea" placeholder="e.g., Administered captopril 25mg SL as ordered. Monitoring BP every 15 mins."></textarea></div>
            </div>
            <div style="display:flex;gap:10px;margin-top:14px;">
                <button wire:click="saveNote" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button" class="btn-primary"><span wire:loading.remove wire:target="saveNote">💾 Save Note</span><span wire:loading wire:target="saveNote">Saving…</span></button>
                <button wire:click="toggleAddNote" type="button" class="btn-secondary">Cancel</button>
            </div>
        </div>
        @endif

        @if($allNotes->isEmpty() && !$addingNote)
        <div class="empty-state"><div class="empty-icon">📝</div><p class="empty-title">No nurse's notes yet</p><p class="empty-sub">Click "Add SOAP Note" above to write the first nursing note.</p></div>
        @else
        @foreach($allNotes as $note)
        <div class="note-card" wire:key="note-{{ $note->id }}">
            <div class="note-header">
                <div><p class="note-nurse">{{ $note->nurse?->name ?? 'Unknown Nurse' }}</p><p class="note-time">{{ $note->noted_at?->timezone('Asia/Manila')->format('F j, Y · H:i') }} &nbsp;·&nbsp; {{ $note->noted_at?->diffForHumans() }}</p></div>
                <span style="font-size:.7rem;background:#f3f4f6;padding:2px 8px;border-radius:4px;color:#6b7280;font-weight:700;">SOAP</span>
            </div>
            @if($note->subjective)<div class="note-soap-row"><div class="note-soap-letter soap-s" style="font-size:.68rem;">S</div><div><p class="note-soap-label">Subjective</p><p class="note-soap-text">{{ $note->subjective }}</p></div></div>@endif
            @if($note->objective)<div class="note-soap-row"><div class="note-soap-letter soap-o" style="font-size:.68rem;">O</div><div><p class="note-soap-label">Objective</p><p class="note-soap-text">{{ $note->objective }}</p></div></div>@endif
            @if($note->assessment)<div class="note-soap-row"><div class="note-soap-letter soap-a" style="font-size:.68rem;">A</div><div><p class="note-soap-label">Assessment</p><p class="note-soap-text">{{ $note->assessment }}</p></div></div>@endif
            @if($note->plan)<div class="note-soap-row"><div class="note-soap-letter soap-p" style="font-size:.68rem;">P</div><div><p class="note-soap-label">Plan</p><p class="note-soap-text">{{ $note->plan }}</p></div></div>@endif
        </div>
        @endforeach
        @endif

        {{-- ══ PLACEHOLDER TABS (unchanged) ══════════════════════════ --}}
        @elseif($activeTab === 'mar')
        @include('filament.nurse.pages.partials.placeholder', ['icon'=>'💊','title'=>'Medication Administration Record (MAR)','desc'=>'Track all medications administered — drug name, dose, route, time, and nurse signature. Alerts for missed or overdue medications.','full'=>true])

        @elseif($activeTab === 'vitals')
        @include('filament.nurse.pages.partials.placeholder', ['icon'=>'📊','title'=>'Vital Signs Monitoring Sheet','desc'=>'Continuous vital signs tracking — Blood Pressure, Pulse Rate, Respiratory Rate, Temperature, O₂ Saturation, and Pain Scale over time.','full'=>true])

        @elseif($activeTab === 'iv')
        @include('filament.nurse.pages.partials.placeholder', ['icon'=>'💧','title'=>'IV Fluid Monitoring Sheet','desc'=>'Track IV fluid orders, bottle numbers, infusion rates, intake totals, and site assessments per shift.','full'=>true])

        @elseif($activeTab === 'blood')
        @include('filament.nurse.pages.partials.placeholder', ['icon'=>'🩸','title'=>'Blood Transfusion Sheet','desc'=>'Record blood product transfusions — blood type, cross-match, pre/intra/post-transfusion vital signs, and adverse reaction monitoring.','full'=>true])

        @elseif($activeTab === 'io')
        @include('filament.nurse.pages.partials.placeholder', ['icon'=>'📏','title'=>'Intake & Output Record','desc'=>'Monitor all fluid intake (oral, IV, NG) and output (urine, drain, emesis, stool) with 8-hour and 24-hour totals.','full'=>true])

        @elseif($activeTab === 'handover')
        @include('filament.nurse.pages.partials.placeholder', ['icon'=>'🔄','title'=>'Nursing Handover / Endorsement','desc'=>'Structured shift-to-shift endorsement using SBAR format (Situation, Background, Assessment, Recommendation) for safe patient handover.','full'=>true])

        @endif

    </div>{{-- /.chart-content --}}

</div>{{-- /.chart-page --}}

@else
<div style="text-align:center;padding:60px 20px;">
    <p style="color:#9ca3af;margin-bottom:10px;">Visit not found or not accessible.</p>
    <button wire:click="goBack" type="button" style="color:#f43f5e;font-size:.875rem;background:none;border:none;cursor:pointer;">← Back to Patient List</button>
</div>
@endif

</x-filament-panels::page>