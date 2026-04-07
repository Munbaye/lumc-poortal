<x-filament-panels::page>

<style>
/* ── Patient header ──────────────────────────────────────────── */
.vv-header {
    background:linear-gradient(135deg,#1e3a5f,#1d4ed8);
    border-radius:10px; padding:14px 20px; margin-bottom:18px;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:10px;
}
.vv-name { font-size:1rem; font-weight:800; color:#fff; }
.vv-case { font-family:monospace; font-size:.78rem; color:#93c5fd; margin-top:2px; }
.vv-pill {
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.22);
    border-radius:6px; padding:4px 12px; font-size:.78rem; color:#e0f2fe; font-weight:600;
}
.btn-back {
    display:inline-flex; align-items:center; gap:6px;
    background:none; border:1px solid #e5e7eb; border-radius:7px;
    padding:8px 16px; font-size:.82rem; font-weight:600;
    color:#374151; cursor:pointer; text-decoration:none; margin-bottom:16px;
}
.btn-back:hover { background:#f3f4f6; }
.dark .btn-back { color:#e5e7eb; border-color:#374151; }
.dark .btn-back:hover { background:#374151; }
.btn-history {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.3);
    color:#fff; font-size:.78rem; font-weight:600;
    padding:7px 14px; border-radius:6px; text-decoration:none; white-space:nowrap;
}
.btn-history:hover { background:rgba(255,255,255,.25); }
.sec-head { font-size:.9rem;font-weight:700;color:#111827;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e5e7eb; }
.dark .sec-head { color:#f3f4f6;border-bottom-color:#374151; }
</style>

@php
    $patient = $record->patient;
    $history = $record->medicalHistory;
    $svc     = $record->admitted_service ?? $history?->service ?? null;
@endphp

{{-- Back button --}}
<a href="{{ \App\Filament\Clerk\Resources\VisitResource::getUrl('index') }}" class="btn-back">
    ← Back to Patient Visits
</a>

{{-- Patient header --}}
<div class="vv-header">
    <div>
        <p class="vv-name">{{ $patient?->full_name ?? '—' }}</p>
        <p class="vv-case">
            {{ $patient?->case_no ?? '' }}
            @if($patient?->age_display) · {{ $patient->age_display }}@endif
            @if($patient?->sex) · {{ $patient->sex }}@endif
        </p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        @if($svc)<span class="vv-pill">{{ $svc }}</span>@endif
        <span class="vv-pill" style="{{ $record->visit_type==='ER'?'background:rgba(220,38,38,.3);':'' }}">
            {{ $record->visit_type==='ER' ? '🚑 ER' : '📋 OPD' }}
        </span>
        @if($history?->doctor)
            <span class="vv-pill">Dr. {{ $history->doctor->name }}</span>
        @endif
        <span class="vv-pill" style="background:rgba(0,0,0,.2);">
            {{ ucfirst(str_replace('_',' ',$record->status)) }}
        </span>
        @if($record->registered_at)
        <span class="vv-pill" style="background:rgba(0,0,0,.15);font-size:.7rem;">
            {{ $record->registered_at->timezone('Asia/Manila')->format('M j, Y H:i') }}
        </span>
        @endif
        @if($patient)
        <a href="{{ $this->getPatientHistoryUrl() }}" class="btn-history">
            🗂️ All Visits for This Patient →
        </a>
        @endif
    </div>
</div>

<p class="sec-head">Patient Forms — Read-Only View</p>

{{-- ══ Shared forms component — edit PatientFormsPanel.php to add new forms ══ --}}
<x-patient-forms-panel :visitId="$record->id" panel="clerk" />

</x-filament-panels::page>