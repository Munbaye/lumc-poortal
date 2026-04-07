<x-filament-panels::page>

<style>
.adm-card {
    background:#fff;border:1px solid #e5e7eb;border-radius:10px;
    padding:18px 20px;margin-bottom:10px;
    display:grid;grid-template-columns:1fr auto;gap:16px;align-items:center;
}
.adm-card:hover { border-color:#d1d5db;box-shadow:0 2px 8px rgba(0,0,0,.06); }
.adm-meta { font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:2px; }
.adm-val  { font-weight:600;font-size:.88rem; }
</style>

{{-- ── Header ──────────────────────────────────────────────────────────────── --}}
<div style="background:linear-gradient(135deg,#1e3a5f 0%,#1d4ed8 100%);border-radius:10px;
            padding:18px 24px;margin-bottom:20px;
            display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1 style="color:#fff;font-size:1.15rem;font-weight:700;margin:0 0 3px;">
            Pending Admissions
        </h1>
        <p style="color:#bfdbfe;font-size:.82rem;margin:0;">
            Patients admitted by doctor awaiting admission completion.
        </p>
    </div>
    @php $pending = $this->getPendingVisits(); @endphp
    <div style="background:rgba(255,255,255,.18);border-radius:8px;padding:10px 18px;text-align:center;">
        <p style="color:#e0f2fe;font-size:1.6rem;font-weight:700;margin:0;">{{ $pending->count() }}</p>
        <p style="color:#bfdbfe;font-size:.72rem;margin:0;">pending</p>
    </div>
</div>

{{-- ── Empty state ─────────────────────────────────────────────────────────── --}}
@if($pending->isEmpty())
<div style="text-align:center;padding:60px 20px;background:#fff;border:1px solid #e5e7eb;
            border-radius:10px;" class="dark:bg-gray-900 dark:border-gray-700">
    <div style="margin:0 0 10px;display:flex;justify-content:center;">
        <x-filament::icon icon="heroicon-o-check-circle" class="w-10 h-10 text-emerald-600" />
    </div>
    <p style="font-weight:700;font-size:1rem;color:#374151;margin:0 0 4px;" class="dark:text-white">
        No pending admissions
    </p>
    <p style="font-size:.83rem;color:#9ca3af;margin:0;">
        All doctor-admitted patients have been processed.
    </p>
</div>

@else

@foreach($pending as $visit)
@php
    $h = $visit->medicalHistory;
    $p = $visit->patient;
    $svc = $visit->admitted_service ?? $h?->service ?? null;
@endphp
<div class="adm-card dark:bg-gray-900 dark:border-gray-700">

    <div style="display:flex;gap:24px;flex-wrap:wrap;align-items:flex-start;">

        {{-- Patient name --}}
        <div style="min-width:160px;">
            <p class="adm-meta">Patient</p>
            <p class="adm-val dark:text-white" style="font-size:.93rem;">{{ $p->full_name }}</p>
            <p style="font-family:monospace;font-size:.75rem;color:#6b7280;margin-top:1px;">
                {{ $p->case_no }}
            </p>
        </div>

        {{-- Age / sex / entry --}}
        <div>
            <p class="adm-meta">Age / Sex</p>
            <p class="adm-val dark:text-gray-300">{{ $p->age_display }} / {{ $p->sex }}</p>
            <span style="display:inline-block;margin-top:4px;padding:2px 10px;border-radius:9999px;
                         font-size:.72rem;font-weight:700;
                         background:{{ $visit->visit_type==='ER'?'#fef2f2':'#eff6ff' }};
                         color:{{ $visit->visit_type==='ER'?'#dc2626':'#1d4ed8' }};">
                {{ $visit->visit_type==='ER'?'🚑 ER':'📋 OPD' }}
            </span>
        </div>

        {{-- Admitting diagnosis + service --}}
        <div style="flex:1;min-width:200px;">
            <p class="adm-meta">Admitting Diagnosis</p>
            <p class="adm-val dark:text-gray-200" style="font-size:.85rem;">
                {{ $visit->admitting_diagnosis ?? $h?->diagnosis ?? $visit->chief_complaint ?? '—' }}
            </p>
            @if($svc)
            <span style="display:inline-block;margin-top:3px;background:#059669;color:#fff;
                         font-size:.68rem;font-weight:700;padding:1px 8px;border-radius:9999px;">
                {{ $svc }}
            </span>
            @endif
        </div>

        {{-- Doctor + admission time --}}
        <div>
            <p class="adm-meta">Admitted By</p>
            @if($h?->doctor)
            <p class="adm-val dark:text-gray-300" style="font-size:.82rem;">
                Dr. {{ $h->doctor->name }}
            </p>
            @endif
            <p style="font-size:.72rem;color:#9ca3af;margin-top:2px;">
                🕐 {{ $visit->doctor_admitted_at->timezone('Asia/Manila')->format('M j, Y H:i') }}
            </p>
            <p style="font-size:.7rem;color:#9ca3af;">
                {{ $visit->doctor_admitted_at->diffForHumans() }}
            </p>
        </div>

        {{-- Orders count --}}
        @if($visit->doctorsOrders->count() > 0)
        <div>
            <p class="adm-meta">Orders</p>
            <p class="adm-val dark:text-gray-300" style="font-size:.82rem;">
                {{ $visit->doctorsOrders->count() }} written
            </p>
            <p style="font-size:.7rem;color:#d97706;">
                {{ $visit->doctorsOrders->where('is_completed', false)->count() }} pending
            </p>
        </div>
        @endif

    </div>

    {{-- Action --}}
    <div style="flex-shrink:0;text-align:right;">
        <a href="{{ \App\Filament\Clerk\Pages\CompleteAdmission::getUrl(['visitId' => $visit->id]) }}"
           style="display:inline-block;background:#059669;color:#fff;padding:10px 22px;
                  border-radius:8px;font-size:.85rem;font-weight:700;
                  text-decoration:none;white-space:nowrap;"
           onmouseover="this.style.background='#047857'"
           onmouseout="this.style.background='#059669'">
            Complete Admission →
        </a>
    </div>

</div>
@endforeach

@endif

</x-filament-panels::page>