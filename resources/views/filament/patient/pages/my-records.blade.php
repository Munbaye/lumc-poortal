<x-filament-panels::page>

    {{-- ── No linked record ─────────────────────────────────────────── --}}
    @if (!$this->patient)
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <x-heroicon-o-exclamation-circle class="w-14 h-14 text-gray-300 dark:text-gray-600 mb-4" />
            <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">No patient record linked to your account.</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                Please contact the LUMC registration desk to link your account.
            </p>
        </div>

    @else

        {{-- ── Patient header card ───────────────────────────────────── --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-700
                    bg-white dark:bg-gray-900 shadow-sm p-6 mb-6
                    flex flex-col sm:flex-row sm:items-center gap-5">

            {{-- Avatar initial --}}
            <div class="flex-shrink-0 w-16 h-16 rounded-full
                        bg-primary-100 dark:bg-primary-900
                        flex items-center justify-center
                        text-2xl font-black text-primary-600 dark:text-primary-300 select-none">
                {{ strtoupper(substr($this->patient->first_name, 0, 1)) }}
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold tracking-widest uppercase
                           text-gray-400 dark:text-gray-500 mb-0.5">
                    La Union Medical Center — Patient Portal
                </p>
                <h2 class="text-xl font-black text-gray-800 dark:text-gray-100 truncate">
                    {{ $this->patient->full_name }}
                </h2>
                <div class="flex flex-wrap gap-x-5 gap-y-1 mt-1
                            text-sm text-gray-500 dark:text-gray-400">
                    <span>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Case No:</span>
                        {{ $this->patient->case_no }}
                    </span>
                    <span>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Age:</span>
                        {{ $this->patient->age_display }}
                    </span>
                    <span>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Sex:</span>
                        {{ $this->patient->sex }}
                    </span>
                    @if($this->patient->birthday)
                    <span>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Birthday:</span>
                        {{ $this->patient->birthday->format('M d, Y') }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Visit count badge --}}
            <div class="flex-shrink-0 text-center
                        bg-primary-50 dark:bg-primary-900/30
                        border border-primary-200 dark:border-primary-700
                        rounded-xl px-5 py-3">
                <p class="text-2xl font-black text-primary-600 dark:text-primary-400">
                    {{ $this->visits->count() }}
                </p>
                <p class="text-[10px] font-bold uppercase tracking-widest
                           text-primary-500 dark:text-primary-500 mt-0.5">
                    {{ Str::plural('Visit', $this->visits->count()) }}
                </p>
            </div>
        </div>

        {{-- ── No visits yet ─────────────────────────────────────────── --}}
        @if ($this->visits->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <x-heroicon-o-clipboard-document-list
                    class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" />
                <p class="text-base font-semibold text-gray-500 dark:text-gray-400">
                    No visit records found.
                </p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                    Your records will appear here after your first hospital visit.
                </p>
            </div>

        @else

            {{-- ── Visit cards ───────────────────────────────────────── --}}
            <div class="space-y-5">
                @foreach ($this->visits as $visit)

                    @php
                        $statusColor = match($visit->status) {
                            'registered'  => 'yellow',
                            'vitals_done' => 'blue',
                            'assessed'    => 'green',
                            'admitted'    => 'purple',
                            'discharged'  => 'gray',
                            'referred'    => 'orange',
                            default       => 'gray',
                        };
                        $statusLabel = match($visit->status) {
                            'registered'  => 'Registered',
                            'vitals_done' => 'Vitals Done',
                            'assessed'    => 'Assessed',
                            'admitted'    => 'Admitted',
                            'discharged'  => 'Discharged',
                            'referred'    => 'Referred',
                            default       => ucfirst($visit->status),
                        };
                        $isEr = $visit->visit_type === 'ER';
                        $mh   = $visit->medicalHistory;
                        $vt   = $visit->latestVitals;
                    @endphp

                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700
                                bg-white dark:bg-gray-900 shadow-sm overflow-hidden">

                        {{-- Card header --}}
                        <div class="flex flex-wrap items-center justify-between gap-3
                                    px-5 py-4
                                    border-b border-gray-100 dark:border-gray-800
                                    bg-gray-50 dark:bg-gray-800/50">
                            <div class="flex items-center gap-3 flex-wrap">
                                {{-- Visit type badge --}}
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold',
                                    'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400' => $isEr,
                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400' => !$isEr,
                                ])>
                                    {{ $visit->visit_type }}
                                </span>

                                {{-- Status badge --}}
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold',
                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400' => $statusColor === 'yellow',
                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400' => $statusColor === 'blue',
                                    'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' => $statusColor === 'green',
                                    'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-400' => $statusColor === 'purple',
                                    'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' => $statusColor === 'gray',
                                    'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-400' => $statusColor === 'orange',
                                ])>
                                    {{ $statusLabel }}
                                </span>

                                @if($visit->payment_class)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                             text-xs font-bold
                                             bg-gray-100 text-gray-600
                                             dark:bg-gray-700 dark:text-gray-300">
                                    {{ $visit->payment_class }}
                                </span>
                                @endif
                            </div>

                            <span class="text-sm text-gray-400 dark:text-gray-500 font-medium">
                                {{ $visit->registered_at->format('M d, Y · h:i A') }}
                            </span>
                        </div>

                        <div class="px-5 py-4 space-y-4">

                            {{-- Chief complaint --}}
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest
                                           text-gray-400 dark:text-gray-500 mb-1">
                                    Chief Complaint
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $visit->chief_complaint }}
                                </p>
                            </div>

                            {{-- Vitals summary --}}
                            @if($vt)
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest
                                           text-gray-400 dark:text-gray-500 mb-2">
                                    Vital Signs
                                </p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    @foreach([
                                        ['Temp',    $vt->temperature    ? $vt->temperature . ' °C'  : null],
                                        ['PR',      $vt->pulse_rate     ? $vt->pulse_rate . ' bpm'  : null],
                                        ['RR',      $vt->respiratory_rate ? $vt->respiratory_rate . '/min' : null],
                                        ['O₂ Sat',  $vt->o2_saturation  ? $vt->o2_saturation . '%'  : null],
                                        ['BP',      $vt->blood_pressure ?? null],
                                        ['Weight',  $vt->weight_kg      ? $vt->weight_kg . ' kg'    : null],
                                    ] as [$label, $val])
                                        @if($val)
                                        <div class="rounded-lg bg-gray-50 dark:bg-gray-800
                                                    border border-gray-100 dark:border-gray-700
                                                    px-3 py-2 text-center">
                                            <p class="text-[9px] font-bold uppercase tracking-widest
                                                       text-gray-400 dark:text-gray-500">
                                                {{ $label }}
                                            </p>
                                            <p class="text-sm font-bold
                                                       text-gray-700 dark:text-gray-200 mt-0.5">
                                                {{ $val }}
                                            </p>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Assessment / Diagnosis --}}
                            @if($mh)
                            <div class="rounded-xl border border-green-100 dark:border-green-900/40
                                        bg-green-50 dark:bg-green-900/20 p-4 space-y-3">
                                <p class="text-[10px] font-black uppercase tracking-widest
                                           text-green-600 dark:text-green-400">
                                    Doctor's Assessment
                                </p>

                                @if($mh->diagnosis)
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider
                                               text-gray-400 dark:text-gray-500 mb-0.5">
                                        Diagnosis
                                    </p>
                                    <p class="text-sm font-semibold
                                               text-gray-800 dark:text-gray-200">
                                        {{ $mh->diagnosis }}
                                    </p>
                                </div>
                                @endif

                                @if($mh->plan)
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider
                                               text-gray-400 dark:text-gray-500 mb-0.5">
                                        Treatment Plan
                                    </p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $mh->plan }}
                                    </p>
                                </div>
                                @endif

                                @if($visit->disposition)
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider
                                               text-gray-400 dark:text-gray-500 mb-0.5">
                                        Outcome
                                    </p>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ $visit->disposition }}
                                        @if($visit->admitted_ward)
                                            — {{ $visit->admitted_ward }}
                                            @if($visit->admitted_service)
                                                ({{ $visit->admitted_service }})
                                            @endif
                                        @endif
                                    </p>
                                </div>
                                @endif

                                @if($mh->drug_allergies && $mh->drug_allergies !== 'NKDA')
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider
                                               text-red-400 mb-0.5">
                                        ⚠ Drug Allergies
                                    </p>
                                    <p class="text-sm font-semibold text-red-600 dark:text-red-400">
                                        {{ $mh->drug_allergies }}
                                    </p>
                                </div>
                                @endif
                            </div>
                            @endif

                            {{-- Discharge info --}}
                            @if($visit->discharged_at)
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                <span class="font-semibold">Discharged:</span>
                                {{ $visit->discharged_at->format('M d, Y · h:i A') }}
                            </p>
                            @endif

                        </div>
                    </div>

                @endforeach
            </div>

        @endif
    @endif

</x-filament-panels::page>