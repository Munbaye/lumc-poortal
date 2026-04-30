<x-filament-panels::page>

    <style>
        /* ── Patient header ──────────────────────────────────────────── */
        .vv-header {
            background: linear-gradient(135deg, #1e3a5f, #1d4ed8);
            border-radius: 10px;
            padding: 14px 20px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .vv-name {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
        }

        .vv-case {
            font-family: monospace;
            font-size: .78rem;
            color: #93c5fd;
            margin-top: 2px;
        }

        .vv-pill {
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 6px;
            padding: 4px 12px;
            font-size: .78rem;
            color: #e0f2fe;
            font-weight: 600;
        }

        /* ── Section labels ──────────────────────────────────────────── */
        .form-section {
            margin-bottom: 28px;
        }

        .form-section-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #6b7280;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-section-line {
            flex: 1;
            border-top: 1px solid #e5e7eb;
        }

        .dark .form-section-line {
            border-top-color: #374151;
        }

        /* ── Iframe wrapper ──────────────────────────────────────────── */
        .form-iframe-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .form-iframe-wrap iframe {
            display: block;
            width: 100%;
            border: none;
        }

        /* ── Not-filled placeholder ──────────────────────────────────── */
        .not-filled {
            background: #f9fafb;
            border: 1.5px dashed #e5e7eb;
            border-radius: 8px;
            padding: 32px;
            text-align: center;
        }

        .dark .not-filled {
            background: #1f2937;
            border-color: #374151;
        }

        /* ── Back button ─────────────────────────────────────────────── */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            padding: 8px 16px;
            font-size: .82rem;
            font-weight: 800;
            color: #374151;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 16px;
            width: fit-content;
            max-width: 100%;
        }

        .btn-back:hover {
            background: #f3f4f6;
        }

        .dark .btn-back {
            color: #e5e7eb;
            border-color: #374151;
        }

        .dark .btn-back:hover {
            background: #374151;
        }

        /* ── Open-in-new-tab small button ────────────────────────────── */
        .btn-open {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: none;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 3px 10px;
            font-size: .72rem;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-open:hover {
            background: #f3f4f6;
        }
    </style>

    @php
    $patient = $record->patient;
    $history = $record->medicalHistory;
    $svc = $record->admitted_service ?? $history?->service ?? null;
    @endphp

    {{-- Back button --}}
    <a href="{{ \App\Filament\Clerk\Resources\VisitResource::getUrl('index') }}" class="btn-back">
        <x-filament::icon icon="heroicon-o-arrow-left" class="w-4 h-4" />
        Back to Patient Visits
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
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            @if($svc)<span class="vv-pill">{{ $svc }}</span>@endif
            <span class="vv-pill" style="{{ $record->visit_type==='ER'?'background:rgba(220,38,38,.3);':'' }}">
                <span style="display:inline-flex;align-items:center;gap:6px;">
                    @if($record->visit_type==='ER')
                    <x-filament::icon icon="heroicon-o-bolt" class="w-4 h-4 text-red-100" />
                    <span>ER</span>
                    @else
                    <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-4 h-4 text-blue-100" />
                    <span>OPD</span>
                    @endif
                </span>
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
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
     SECTION 1 — ER RECORD (ER-001)
     ══════════════════════════════════════════════════════════════ --}}
    <div class="form-section">
        <div class="form-section-label">
            <span style="display:inline-flex;align-items:center;gap:6px;">
                <x-filament::icon icon="heroicon-o-building-office-2" class="w-4 h-4 text-gray-500" />
                <span>Emergency Room Record (ER-001)</span>
            </span>
            <div class="form-section-line"></div>
            @if($this->hasErRecord())
            <span style="background:#d1fae5;color:#065f46;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">Saved</span>
            <a href="{{ $this->getErRecordUrl() }}" target="_blank" rel="noopener" class="btn-open">
                <x-filament::icon icon="heroicon-o-printer" class="w-4 h-4" />
                Print
                <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4" />
            </a>
            @else
            <span style="background:#fef3c7;color:#92400e;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">Not yet filled</span>
            @endif
        </div>

        @if($this->hasErRecord())
        <div class="form-iframe-wrap">
            <iframe
                src="{{ $this->getErRecordUrl() }}"
                title="ER Record — {{ $patient?->case_no }}"
                style="width:100%;min-height:1100px;border:none;display:block;"
                loading="lazy"></iframe>
        </div>
        @else
        <div class="not-filled">
            <div style="margin:0 0 8px;display:flex;justify-content:center;">
                <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-9 h-9 text-gray-400" />
            </div>
            <p style="font-size:.88rem;font-weight:700;color:#374151;margin:0 0 4px;">ER Record not yet filled</p>
            <p style="font-size:.78rem;color:#9ca3af;margin:0;">Filled by the clerk during the admission process.</p>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════
     SECTION 2 — ADMISSION & DISCHARGE RECORD (ADM-001)
     ══════════════════════════════════════════════════════════════ --}}
    <div class="form-section">
        <div class="form-section-label">
            <span style="display:inline-flex;align-items:center;gap:6px;">
                <x-filament::icon icon="heroicon-o-document-text" class="w-4 h-4 text-gray-500" />
                <span>Admission &amp; Discharge Record (ADM-001)</span>
            </span>
            <div class="form-section-line"></div>
            @if($this->hasAdmRecord())
            <span style="background:#d1fae5;color:#065f46;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">Saved</span>
            <a href="{{ $this->getAdmRecordUrl() }}" target="_blank" rel="noopener" class="btn-open">
                <x-filament::icon icon="heroicon-o-printer" class="w-4 h-4" />
                Print
                <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4" />
            </a>
            @else
            <span style="background:#fef3c7;color:#92400e;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">Not yet filled</span>
            @endif
        </div>

        @if($this->hasAdmRecord())
        <div class="form-iframe-wrap">
            <iframe
                src="{{ $this->getAdmRecordUrl() }}"
                title="ADM Record — {{ $patient?->case_no }}"
                style="width:100%;min-height:1100px;border:none;display:block;"
                loading="lazy"></iframe>
        </div>
        @else
        <div class="not-filled">
            <div style="margin:0 0 8px;display:flex;justify-content:center;">
                <x-filament::icon icon="heroicon-o-document" class="w-9 h-9 text-gray-400" />
            </div>
            <p style="font-size:.88rem;font-weight:700;color:#374151;margin:0 0 4px;">Admission Record not yet filled</p>
            <p style="font-size:.78rem;color:#9ca3af;margin:0;">Filled by the clerk during the admission process.</p>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════
     SECTION 3 — CONSENT TO CARE (NUR-002-1)
     Same visual treatment as the two forms above.
     Uses ?readonly=1 to hide the Save toolbar, showing the clean paper form.
     "Print ↗" button opens the editable version in a new tab for printing.
     ══════════════════════════════════════════════════════════════ --}}
    <div class="form-section">
        <div class="form-section-label">
            <span style="display:inline-flex;align-items:center;gap:6px;">
                <x-filament::icon icon="heroicon-o-document-check" class="w-4 h-4 text-gray-500" />
                <span>Consent to Care (NUR-002-1)</span>
            </span>
            <div class="form-section-line"></div>
            @if($this->hasConsentRecord())
            <span style="background:#d1fae5;color:#065f46;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">Saved</span>
            <a href="{{ $this->getConsentUrl() }}" target="_blank" rel="noopener" class="btn-open">
                <x-filament::icon icon="heroicon-o-printer" class="w-4 h-4" />
                Print
                <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4" />
            </a>
            @else
            <span style="background:#fef3c7;color:#92400e;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">Not yet filled</span>
            <a href="{{ $this->getConsentUrl() }}" target="_blank" rel="noopener" class="btn-open">
                <x-filament::icon icon="heroicon-o-pencil-square" class="w-4 h-4" />
                Fill now
                <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4" />
            </a>
            @endif
        </div>

        @if($this->hasConsentRecord())
        <div class="form-iframe-wrap">
            <iframe
                src="{{ $this->getConsentReadonlyUrl() }}"
                title="Consent to Care — {{ $patient?->case_no }}"
                style="width:100%;min-height:900px;border:none;display:block;"
                loading="lazy"></iframe>
        </div>
        @else
        <div class="not-filled">
            <div style="margin:0 0 8px;display:flex;justify-content:center;">
                <x-filament::icon icon="heroicon-o-pencil-square" class="w-9 h-9 text-gray-400" />
            </div>
            <p style="font-size:.88rem;font-weight:700;color:#374151;margin:0 0 4px;">Consent to Care not yet signed</p>
            <p style="font-size:.78rem;color:#9ca3af;margin:0 0 12px;">
                Generated during admission. Once saved it will appear here as a full read-only document.
            </p>
            <a href="{{ $this->getConsentUrl() }}" target="_blank" rel="noopener"
                style="display:inline-flex;align-items:center;gap:6px;background:#b45309;color:#fff;border:none;border-radius:7px;padding:9px 18px;font-size:.82rem;font-weight:700;text-decoration:none;">
                <x-filament::icon icon="heroicon-o-document-text" class="w-4 h-4" />
                Open Consent to Care
                <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4" />
            </a>
        </div>
        @endif
    </div>

</x-filament-panels::page>