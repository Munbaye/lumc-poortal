<x-filament-panels::page>

<style>
/* ══════════════════════════════════════════════════════════════════════
   PATIENT CHART — TOP-TAB LAYOUT
   ══════════════════════════════════════════════════════════════════════ */

/* ── Page wrapper ─────────────────────────────────────────────────── */
.chart-page {
    display: flex;
    flex-direction: column;
    gap: 0;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
}
.dark .chart-page { background: #111827; border-color: #374151; }

/* ── Patient header ───────────────────────────────────────────────── */
.chart-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 100%);
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}
.chart-header-left { flex: 1; min-width: 200px; }
.chart-header-left .patient-name {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: .02em;
}
.chart-header-left .case-no {
    font-family: monospace;
    font-size: .78rem;
    color: #93c5fd;
    margin-top: 2px;
}
.chart-header-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}
.h-pill {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 6px;
    padding: 5px 14px;
    text-align: center;
}
.h-pill .pill-label { font-size: .6rem; text-transform: uppercase; letter-spacing: .06em; color: #93c5fd; }
.h-pill .pill-value { font-size: .82rem; font-weight: 700; color: #fff; }
.h-service-badge {
    background: #059669;
    color: #fff;
    font-size: .72rem;
    font-weight: 700;
    padding: 4px 14px;
    border-radius: 9999px;
}
.chart-header-back {
    flex-shrink: 0;
}
.btn-back-header {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    color: #fff;
    font-size: .78rem;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: 6px;
    text-decoration: none;
    cursor: pointer;
}
.btn-back-header:hover { background: rgba(255,255,255,.25); }

/* ── Top tab bar ─────────────────────────────────────────────────── */
.chart-tabs {
    display: flex;
    border-bottom: 2px solid #e5e7eb;
    background: #fff;
    padding: 0 20px;
    gap: 0;
    overflow-x: auto;
}
.dark .chart-tabs { background: #1f2937; border-bottom-color: #374151; }

.chart-tab {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 13px 18px;
    font-size: .83rem;
    font-weight: 600;
    color: #6b7280;
    cursor: pointer;
    border: none;
    background: none;
    border-bottom: 2.5px solid transparent;
    margin-bottom: -2px;
    white-space: nowrap;
    transition: color .15s, border-color .15s;
    position: relative;
}
.chart-tab:hover { color: #374151; }
.dark .chart-tab { color: #9ca3af; }
.dark .chart-tab:hover { color: #e5e7eb; }

.chart-tab.active {
    color: #1d4ed8;
    border-bottom-color: #1d4ed8;
    font-weight: 700;
}
.dark .chart-tab.active { color: #60a5fa; border-bottom-color: #60a5fa; }

.tab-icon { font-size: .95rem; }
.tab-badge {
    background: #ef4444;
    color: #fff;
    font-size: .62rem;
    font-weight: 700;
    padding: 1px 5px;
    border-radius: 9999px;
    min-width: 18px;
    text-align: center;
}
.tab-badge-warn { background: #f59e0b; }

/* ── Tab content area ────────────────────────────────────────────── */
.chart-content {
    padding: 24px 28px;
    background: #f9fafb;
    min-height: 420px;
}
.dark .chart-content { background: #111827; }

/* ── Section headings ─────────────────────────────────────────────── */
.sec-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
}
.dark .sec-head { border-bottom-color: #374151; }
.sec-title { font-size: .95rem; font-weight: 700; color: #111827; }
.dark .sec-title { color: #f3f4f6; }

/* ── Placeholder card ────────────────────────────────────────────── */
.placeholder-card {
    text-align: center;
    padding: 52px 24px;
    background: #fff;
    border: 1.5px dashed #e5e7eb;
    border-radius: 8px;
}
.dark .placeholder-card { background: #1f2937; border-color: #374151; }
.placeholder-card .ph-icon { font-size: 2.6rem; margin-bottom: 10px; }
.placeholder-card .ph-title { font-size: .92rem; font-weight: 700; color: #374151; margin-bottom: 4px; }
.dark .placeholder-card .ph-title { color: #e5e7eb; }
.placeholder-card .ph-sub { font-size: .8rem; color: #9ca3af; }

/* ── Document link cards (History & Assessment) ────────────────── */
.doc-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    cursor: pointer;
    text-decoration: none;
    transition: border-color .15s, box-shadow .15s;
}
.dark .doc-card { background: #1f2937; border-color: #374151; }
.doc-card:hover { border-color: #1d4ed8; box-shadow: 0 2px 12px rgba(29,78,216,.12); }
.doc-card-icon { font-size: 2rem; flex-shrink: 0; }
.doc-card-body { flex: 1; }
.doc-card-title { font-size: .92rem; font-weight: 700; color: #111827; margin-bottom: 3px; }
.dark .doc-card-title { color: #f3f4f6; }
.doc-card-meta { font-size: .75rem; color: #6b7280; }
.doc-card-arrow { font-size: 1.1rem; color: #9ca3af; flex-shrink: 0; }
.doc-card-label {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    background: #eff6ff;
    color: #1d4ed8;
    padding: 2px 8px;
    border-radius: 4px;
    margin-bottom: 4px;
    display: inline-block;
}

/* ── Vitals table ────────────────────────────────────────────────── */
.vitals-wrap { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: auto; }
.dark .vitals-wrap { background: #1f2937; border-color: #374151; }
.vitals-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.vitals-table th {
    background: #f3f4f6;
    padding: 8px 11px;
    text-align: left;
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb;
}
.dark .vitals-table th { background: #111827; color: #9ca3af; border-bottom-color: #374151; }
.vitals-table td { padding: 9px 11px; border-bottom: 1px solid #f3f4f6; color: #374151; }
.dark .vitals-table td { border-bottom-color: #1f2937; color: #d1d5db; }
.vitals-table tr:last-child td { border-bottom: none; }
.vitals-table tr:hover td { background: #f9fafb; }
.dark .vitals-table tr:hover td { background: rgba(255,255,255,.03); }
.abnormal { color: #dc2626 !important; font-weight: 700; }

/* ── Orders ─────────────────────────────────────────────────────── */
.order-group-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    margin-top: 4px;
}
.order-group-label {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #6b7280;
    white-space: nowrap;
}
.order-group-line { flex: 1; border-top: 1px solid #e5e7eb; }
.dark .order-group-line { border-top-color: #374151; }
.order-group-doc { font-size: .7rem; color: #9ca3af; white-space: nowrap; }

.order-item {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 7px;
    padding: 11px 14px;
    margin-bottom: 7px;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
    align-items: start;
}
.dark .order-item { background: #1f2937; border-color: #374151; }
.order-item:hover { border-color: #d1d5db; }
.dark .order-item:hover { border-color: #4b5563; }
.order-num { font-size: .68rem; color: #9ca3af; font-family: monospace; margin-bottom: 2px; }
.order-text { font-size: .875rem; color: #111827; font-weight: 500; line-height: 1.4; }
.dark .order-text { color: #f3f4f6; }
.order-meta { font-size: .7rem; color: #9ca3af; margin-top: 3px; }

.status-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 9999px;
    font-size: .68rem;
    font-weight: 700;
    white-space: nowrap;
}
.status-pending      { background: #fef3c7; color: #92400e; }
.status-carried      { background: #d1fae5; color: #065f46; }
.status-discontinued { background: #fee2e2; color: #991b1b; }
.order-text-discontinued { text-decoration: line-through; opacity: .6; }

.btn-discontinue {
    font-size: .7rem;
    color: #9ca3af;
    background: none;
    border: 1px solid #e5e7eb;
    border-radius: 5px;
    padding: 3px 9px;
    cursor: pointer;
    margin-top: 4px;
}
.btn-discontinue:hover { border-color: #dc2626; color: #dc2626; }

/* ── Write-order form ────────────────────────────────────────────── */
.order-form-wrap {
    background: #fff;
    border: 1.5px solid #3b82f6;
    border-radius: 8px;
    padding: 18px 20px;
    margin-bottom: 20px;
}
.dark .order-form-wrap { background: #1f2937; border-color: #2563eb; }
.order-form-title {
    font-size: .83rem;
    font-weight: 700;
    color: #1d4ed8;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eff6ff;
}
.dark .order-form-title { border-bottom-color: #1e3a5f; }

.order-line-row {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 8px;
    align-items: center;
    margin-bottom: 7px;
}
.ol-input {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 8px 11px;
    font-size: .85rem;
    background: #fff;
    color: #111827;
    outline: none;
}
.dark .ol-input { background: #374151; border-color: #4b5563; color: #f3f4f6; }
.ol-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); }
.btn-remove-line {
    background: none;
    border: 1px solid #e5e7eb;
    color: #9ca3af;
    border-radius: 5px;
    padding: 7px 10px;
    cursor: pointer;
    font-size: .78rem;
    flex-shrink: 0;
    line-height: 1;
}
.btn-remove-line:hover { border-color: #dc2626; color: #dc2626; }
.btn-add-line {
    background: none;
    border: 1px dashed #9ca3af;
    border-radius: 6px;
    padding: 7px 14px;
    font-size: .8rem;
    color: #6b7280;
    cursor: pointer;
    width: 100%;
    margin: 8px 0 0;
}
.btn-add-line:hover { border-color: #3b82f6; color: #3b82f6; }

/* ── Action buttons ──────────────────────────────────────────────── */
.btn-primary {
    background: #1d4ed8; color: #fff;
    border: none; border-radius: 7px;
    padding: 9px 22px; font-size: .85rem; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
}
.btn-primary:hover { background: #1e40af; }
.btn-secondary {
    background: #fff; color: #374151;
    border: 1px solid #e5e7eb; border-radius: 7px;
    padding: 9px 18px; font-size: .85rem; font-weight: 600;
    cursor: pointer;
}
.dark .btn-secondary { background: #374151; color: #e5e7eb; border-color: #4b5563; }
.btn-secondary:hover { background: #f3f4f6; }
.btn-write {
    background: #059669; color: #fff;
    border: none; border-radius: 7px;
    padding: 9px 18px; font-size: .83rem; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
}
.btn-write:hover { background: #047857; }
.btn-write.is-cancel { background: #6b7280; }
.btn-write.is-cancel:hover { background: #4b5563; }
.btn-lab {
    background: #7c3aed; color: #fff;
    border: none; border-radius: 7px;
    padding: 9px 18px; font-size: .83rem; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
}
.btn-lab:hover { background: #6d28d9; }

/* ── Lab modal ───────────────────────────────────────────────────── */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 8888;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.modal-box {
    background: #fff;
    border-radius: 10px;
    padding: 28px;
    max-width: 440px;
    width: 100%;
    box-shadow: 0 20px 60px rgba(0,0,0,.3);
}
.dark .modal-box { background: #1f2937; }
.modal-title { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 8px; }
.dark .modal-title { color: #f3f4f6; }
.modal-body { font-size: .875rem; color: #6b7280; line-height: 1.65; margin-bottom: 20px; }
</style>

@if($visit && $visit->patient)
@php
    $patient    = $visit->patient;
    $history    = $visit->medicalHistory;
    $allOrders  = $visit->doctorsOrders ?? collect();
    $allVitals  = $visit->vitals ?? collect();
    $pendingCnt = $allOrders->where('status', 'pending')->count();
    $service    = $visit->admitted_service ?? $history?->service ?? '—';
    $admittedAt = $visit->clerk_admitted_at
        ? $visit->clerk_admitted_at->timezone('Asia/Manila')->format('M j, Y H:i')
        : '—';
@endphp

<div class="chart-page">

    {{-- ════════════════════════════════════════════════════════════════
         PATIENT HEADER — shown once at the top, never repeated
         ════════════════════════════════════════════════════════════════ --}}
    <div class="chart-header">
        <div class="chart-header-left">
            <p class="patient-name">{{ $patient->full_name }}</p>
            <p class="case-no">{{ $patient->case_no }}</p>
        </div>

        <div class="chart-header-pills">
            <div class="h-pill">
                <p class="pill-label">Age / Sex</p>
                <p class="pill-value">{{ $patient->age_display }} · {{ $patient->sex }}</p>
            </div>
            <div class="h-pill">
                <p class="pill-label">Admitting Diagnosis</p>
                <p class="pill-value" style="font-size:.78rem;max-width:200px;white-space:normal;line-height:1.3;">
                    {{ $visit->admitting_diagnosis ?? $history?->diagnosis ?? '—' }}
                </p>
            </div>
            <span class="h-service-badge">{{ $service }}</span>
            <div class="h-pill">
                <p class="pill-label">Admitted</p>
                <p class="pill-value">{{ $admittedAt }}</p>
            </div>
            @if($history?->doctor)
            <div class="h-pill">
                <p class="pill-label">Physician</p>
                <p class="pill-value">Dr. {{ $history->doctor->name }}</p>
            </div>
            @endif
        </div>

        <div class="chart-header-back">
            <a href="{{ \App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index') }}"
               class="btn-back-header">
                ← Admitted Patients
            </a>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════
         TOP TAB BAR
         ════════════════════════════════════════════════════════════════ --}}
    <div class="chart-tabs">

        <button wire:click="setTab('profile')"
                class="chart-tab {{ $activeTab === 'profile' ? 'active' : '' }}">
            <span class="tab-icon">👤</span> Profile
        </button>

        <button wire:click="setTab('vitals')"
                class="chart-tab {{ $activeTab === 'vitals' ? 'active' : '' }}">
            <span class="tab-icon">📊</span> Vital Signs
            @if($allVitals->count() > 0)
            <span class="tab-badge tab-badge-warn">{{ $allVitals->count() }}</span>
            @endif
        </button>

        <button wire:click="setTab('history')"
                class="chart-tab {{ $activeTab === 'history' ? 'active' : '' }}">
            <span class="tab-icon">📋</span> History &amp; Assessment
        </button>

        <button wire:click="setTab('orders')"
                class="chart-tab {{ $activeTab === 'orders' ? 'active' : '' }}">
            <span class="tab-icon">📝</span> Doctor's Orders
            @if($pendingCnt > 0)
            <span class="tab-badge">{{ $pendingCnt }}</span>
            @endif
        </button>

        <button wire:click="setTab('results')"
                class="chart-tab {{ $activeTab === 'results' ? 'active' : '' }}">
            <span class="tab-icon">🔬</span> Lab / Radiology
        </button>

    </div>

    {{-- ════════════════════════════════════════════════════════════════
         TAB CONTENT AREA
         ════════════════════════════════════════════════════════════════ --}}
    <div class="chart-content">

        {{-- ── TAB: PROFILE ──────────────────────────────────────────── --}}
        @if($activeTab === 'profile')
        <div class="sec-head">
            <h2 class="sec-title">Patient Profile</h2>
        </div>
        <div class="placeholder-card">
            <div class="ph-icon">📄</div>
            <p class="ph-title">Patient Profile Form</p>
            <p class="ph-sub">Softcopy of the patient registration form will appear here.<br>
                Demographic details, contact information, and emergency contacts.</p>
        </div>

        {{-- ── TAB: VITAL SIGNS ──────────────────────────────────────── --}}
        @elseif($activeTab === 'vitals')
        <div class="sec-head">
            <h2 class="sec-title">Vital Signs</h2>
            <span style="font-size:.78rem;color:#6b7280;">{{ $allVitals->count() }} recording(s)</span>
        </div>

        @if($allVitals->isEmpty())
        <div class="placeholder-card">
            <div class="ph-icon">📊</div>
            <p class="ph-title">No vital signs recorded yet</p>
            <p class="ph-sub">Vitals are recorded by the nurse from the Nurse panel.</p>
        </div>
        @else
        <div class="vitals-wrap">
            <table class="vitals-table">
                <thead>
                    <tr>
                        <th>Date / Time</th>
                        <th>Nurse</th>
                        <th>BP</th>
                        <th>PR (bpm)</th>
                        <th>RR (/min)</th>
                        <th>Temp (°C)</th>
                        <th>O₂ Sat (%)</th>
                        <th>Pain /10</th>
                        <th>Wt (kg)</th>
                        <th>Ht (cm)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allVitals as $v)
                    <tr>
                        <td style="white-space:nowrap;font-family:monospace;font-size:.76rem;">
                            {{ $v->taken_at->timezone('Asia/Manila')->format('M j, Y H:i') }}
                        </td>
                        <td style="font-size:.78rem;">{{ $v->nurse_name }}</td>
                        <td>{{ $v->blood_pressure ?? '—' }}</td>
                        <td class="{{ ($v->pulse_rate && ($v->pulse_rate < 60 || $v->pulse_rate > 100)) ? 'abnormal' : '' }}">
                            {{ $v->pulse_rate ?? '—' }}
                        </td>
                        <td class="{{ ($v->respiratory_rate && ($v->respiratory_rate < 12 || $v->respiratory_rate > 20)) ? 'abnormal' : '' }}">
                            {{ $v->respiratory_rate ?? '—' }}
                        </td>
                        <td class="{{ ($v->temperature && ($v->temperature < 36.0 || $v->temperature > 37.5)) ? 'abnormal' : '' }}">
                            {{ $v->temperature ?? '—' }}
                        </td>
                        <td class="{{ ($v->o2_saturation && $v->o2_saturation < 95) ? 'abnormal' : '' }}">
                            {{ $v->o2_saturation ?? '—' }}
                        </td>
                        <td class="{{ ($v->pain_scale !== null && (int)$v->pain_scale >= 7) ? 'abnormal' : '' }}">
                            {{ $v->pain_scale ?? '—' }}
                        </td>
                        <td>{{ $v->weight_kg ?? '—' }}</td>
                        <td>{{ $v->height_cm ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- ── TAB: HISTORY & ASSESSMENT ─────────────────────────────── --}}
        @elseif($activeTab === 'history')
        <div class="sec-head">
            <h2 class="sec-title">History &amp; Assessment Forms</h2>
            <span style="font-size:.78rem;color:#6b7280;">Open each form to view · Click "Print / Save as PDF" inside</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

            {{-- NUR-006 History Form --}}
            <a href="{{ route('forms.history-form', ['visit' => $visit->id]) }}"
               target="_blank" rel="noopener"
               class="doc-card">
                <span class="doc-card-icon">📝</span>
                <div class="doc-card-body">
                    <p class="doc-card-label">NUR-006</p>
                    <p class="doc-card-title">History Form</p>
                    <p class="doc-card-meta">
                        Chief complaint · HPI · Past medical · Family history<br>
                        Allergies · Current medications
                    </p>
                </div>
                <span class="doc-card-arrow">↗</span>
            </a>

            {{-- NUR-005 Physical Examination Form --}}
            <a href="{{ route('forms.physical-exam-form', ['visit' => $visit->id]) }}"
               target="_blank" rel="noopener"
               class="doc-card">
                <span class="doc-card-icon">🩺</span>
                <div class="doc-card-body">
                    <p class="doc-card-label">NUR-005</p>
                    <p class="doc-card-title">Physical Examination Form</p>
                    <p class="doc-card-meta">
                        Head-to-toe physical examination findings<br>
                        Admitting impression
                    </p>
                </div>
                <span class="doc-card-arrow">↗</span>
            </a>

        </div>

        {{-- Clinical Face Sheet placeholder --}}
        <div class="placeholder-card" style="padding:28px;">
            <div class="ph-icon">🗂</div>
            <p class="ph-title">Clinical Face Sheet</p>
            <p class="ph-sub">Summary sheet with diagnosis, disposition, and management plan. Coming soon.</p>
        </div>

        {{-- ── TAB: DOCTOR'S ORDERS ──────────────────────────────────── --}}
        @elseif($activeTab === 'orders')
        <div class="sec-head">
            <h2 class="sec-title">Doctor's Orders</h2>
            <div style="display:flex;gap:8px;align-items:center;">
                <span style="font-size:.78rem;color:#6b7280;">
                    {{ $allOrders->count() }} total · {{ $pendingCnt }} pending
                </span>

                {{-- Request Lab / Radiology --}}
                <button wire:click="openLabModal" type="button" class="btn-lab">
                    🔬 Request Lab / Radiology
                </button>

                {{-- Write New Orders --}}
                <button wire:click="toggleWriteOrders" type="button"
                        class="btn-write {{ $writingOrders ? 'is-cancel' : '' }}">
                    @if($writingOrders) ✕ Cancel @else ✏️ Write New Orders @endif
                </button>
            </div>
        </div>

        {{-- Write-order form ──────────────────────────────────────── --}}
        @if($writingOrders)
        <div class="order-form-wrap">
            <p class="order-form-title">
                ✏️ New Doctor's Orders
                &nbsp;·&nbsp;
                <span style="font-weight:400;color:#6b7280;">
                    {{ now()->timezone('Asia/Manila')->format('F j, Y H:i') }}
                    &nbsp;· Dr. {{ auth()->user()->name }}
                </span>
            </p>

            @foreach($orderLines as $i => $line)
            <div class="order-line-row">
                <input
                    type="text"
                    wire:model="orderLines.{{ $i }}.text"
                    placeholder="Order {{ $i + 1 }} — e.g., IVF D5W 500ml @ 20 gtts/min"
                    class="ol-input"
                >
                <button wire:click="removeOrderLine({{ $i }})" type="button"
                        class="btn-remove-line" title="Remove">✕</button>
            </div>
            @endforeach

            <button wire:click="addOrderLine" type="button" class="btn-add-line">
                + Add Another Order Line
            </button>

            <div style="display:flex;gap:10px;margin-top:14px;">
                <button wire:click="saveOrders"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60"
                        type="button" class="btn-primary">
                    <span wire:loading.remove wire:target="saveOrders">💾 Save Orders</span>
                    <span wire:loading wire:target="saveOrders">Saving…</span>
                </button>
                <button wire:click="toggleWriteOrders" type="button" class="btn-secondary">
                    Cancel
                </button>
            </div>
        </div>
        @endif

        {{-- Existing orders ──────────────────────────────────────── --}}
        @if($allOrders->isEmpty() && !$writingOrders)
        <div class="placeholder-card">
            <div class="ph-icon">📝</div>
            <p class="ph-title">No orders written yet</p>
            <p class="ph-sub">Click "Write New Orders" above to write your first set of orders.</p>
        </div>
        @else
        @foreach($allOrders->groupBy(fn($o) => $o->order_date?->timezone('Asia/Manila')->format('Y-m-d H:i')) as $dateKey => $group)
        <div style="margin-bottom:22px;">
            <div class="order-group-header">
                <p class="order-group-label">
                    {{ \Carbon\Carbon::parse($dateKey)->timezone('Asia/Manila')->format('F j, Y H:i') }}
                </p>
                <div class="order-group-line"></div>
                @if($group->first()->doctor)
                <p class="order-group-doc">Dr. {{ $group->first()->doctor->name }}</p>
                @endif
            </div>

            @foreach($group as $i => $order)
            <div class="order-item">
                <div>
                    <p class="order-num">{{ $i + 1 }}.</p>
                    <p class="order-text {{ $order->isDiscontinued() ? 'order-text-discontinued' : '' }}">
                        {{ $order->order_text }}
                    </p>
                    <p class="order-meta">
                        {{ $order->order_date?->timezone('Asia/Manila')->format('M j, Y H:i') }}
                        @if($order->isCarried() && $order->completed_at)
                            · Carried {{ $order->completed_at->timezone('Asia/Manila')->format('H:i') }}
                        @endif
                    </p>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->status_label }}
                    </span>
                    @if($order->isPending())
                        @if($confirmDiscontinueId === $order->id)
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;margin-top:4px;">
                            <p style="font-size:.68rem;color:#dc2626;font-weight:600;">Discontinue this order?</p>
                            <div style="display:flex;gap:4px;">
                                <button wire:click="discontinueOrder({{ $order->id }})" type="button"
                                        style="font-size:.7rem;background:#dc2626;color:#fff;border:none;
                                               border-radius:4px;padding:3px 9px;cursor:pointer;">
                                    Yes
                                </button>
                                <button wire:click="$set('confirmDiscontinueId', null)" type="button"
                                        style="font-size:.7rem;background:#e5e7eb;color:#374151;border:none;
                                               border-radius:4px;padding:3px 8px;cursor:pointer;">
                                    Cancel
                                </button>
                            </div>
                        </div>
                        @else
                        <button wire:click="$set('confirmDiscontinueId', {{ $order->id }})" type="button"
                                class="btn-discontinue">
                            Discontinue
                        </button>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
        @endif

        {{-- ── TAB: LAB / RADIOLOGY ──────────────────────────────────── --}}
        @elseif($activeTab === 'results')
        <div class="sec-head">
            <h2 class="sec-title">Lab &amp; Radiology Results</h2>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div class="placeholder-card">
                <div class="ph-icon">🧪</div>
                <p class="ph-title">Laboratory Results</p>
                <p class="ph-sub">CBC, urinalysis, chemistry panel, culture &amp; sensitivity.</p>
            </div>
            <div class="placeholder-card">
                <div class="ph-icon">🩻</div>
                <p class="ph-title">Radiology / Imaging</p>
                <p class="ph-sub">Chest X-ray, ultrasound, CT scan, ECG and other imaging.</p>
            </div>
        </div>
        @endif

    </div>{{-- /.chart-content --}}

</div>{{-- /.chart-page --}}

{{-- ════════════════════════════════════════════════════════════════════
     LAB / RADIOLOGY REQUEST MODAL
     ════════════════════════════════════════════════════════════════════ --}}
@if($showLabModal)
<div class="modal-overlay" wire:click.self="closeLabModal">
    <div class="modal-box">
        <p class="modal-title">🔬 Request Lab / Radiology</p>
        <div class="modal-body">
            <p style="margin-bottom:10px;">
                The electronic Lab/Radiology request form is currently under development.
            </p>
            <p>
                In the meantime, please use the manual request slips available at the nursing station.
                Digital request submission will be available in a future update.
            </p>
        </div>
        <div style="display:flex;justify-content:flex-end;">
            <button wire:click="closeLabModal" type="button" class="btn-secondary">
                Close
            </button>
        </div>
    </div>
</div>
@endif

@else
{{-- Visit not found fallback --}}
<div style="text-align:center;padding:60px 20px;">
    <p style="color:#9ca3af;margin-bottom:10px;">Visit not found or not accessible.</p>
    <a href="{{ \App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index') }}"
       style="color:#1d4ed8;font-size:.875rem;">← Back to Admitted Patients</a>
</div>
@endif

</x-filament-panels::page>