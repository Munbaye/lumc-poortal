<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breastfeeding Observation Job Aid — NUR-044-0 — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size: 8.5in 13in portrait; margin: 0.45in 0.55in; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; color: #000; background: #c9c9c9; }
        @media screen {
            body { padding: 52px 0 40px; }
            .paper { width: 8.5in; margin: 0 auto 40px; background: #fff; box-shadow: 0 4px 28px rgba(0,0,0,.28); padding: 0.45in 0.55in; }
        }
        @media print {
            html, body { margin: 0; padding: 0; background: #fff; }
            .toolbar, .no-print { display: none !important; }

            /* Each .paper is exactly one printed page */
            .paper {
                display: block;
                width: 100%;
                min-height: 0;
                padding: 0;
                margin: 0;
                box-shadow: none;
                border: none;
                page-break-before: always;
                page-break-after: always;
                page-break-inside: avoid;
                break-before: page;
                break-after: page;
                break-inside: avoid;
                overflow: hidden;
            }
            /* The very first .paper should not add a blank page before it */
            .paper:first-of-type {
                page-break-before: auto;
                break-before: auto;
            }
            /* Prevent anything from breaking across pages */
            .obs-columns, .header, .title-band, .pt-info-row,
            .section-intro, .sig-section {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        /* ── Toolbar ── */
        .toolbar { position: fixed; top: 0; left: 0; right: 0; height: 46px; background: #1e3a5f; color: #fff; font-family: 'Segoe UI', system-ui, sans-serif; font-size: 12px; display: flex; align-items: center; padding: 0 22px; gap: 14px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,.35); }
        .toolbar .lbl { font-size: 13px; font-weight: 700; }
        .toolbar .tag { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); border-radius: 3px; padding: 2px 9px; font-size: 10px; text-transform: uppercase; }
        .toolbar .spacer { flex: 1; }
        .toolbar .pt-info { font-size: 11px; color: rgba(255,255,255,.8); }
        .btn-print { background: #fff; color: #1e3a5f; border: none; padding: 6px 18px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; }
        .btn-print:hover { background: #dbeafe; }

        /* ── Header ── */
        .header { display: flex; align-items: center; gap: 12px; padding-bottom: 9px; border-bottom: 2.5px solid #000; margin-bottom: 10px; }
        .logo-box { width: 68px; height: 68px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .logo-box img { width: 68px; height: 68px; object-fit: contain; }
        .logo-ph { width: 68px; height: 68px; flex-shrink: 0; border: 1.5px dashed #bbb; display: flex; align-items: center; justify-content: center; font-size: 7.5pt; color: #bbb; text-align: center; line-height: 1.4; }
        .header-center { flex: 1; text-align: center; line-height: 1.35; }
        .h-rep  { font-size: 8.5pt; }
        .h-prov { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; }
        .h-mun  { font-size: 8.5pt; }
        .h-hosp { font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; }
        .form-code { font-size: 8pt; font-weight: bold; text-align: right; margin-bottom: 4px; }

        /* ── Form Title ── */
        .title-band { text-align: center; margin: 6px 0 10px; }
        .title-band h1 { display: inline-block; font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; text-decoration: underline; }

        /* ── Patient Info Row ── */
        .pt-info-row { display: flex; gap: 0; margin-bottom: 10px; font-size: 9pt; }
        .pt-info-left { flex: 1; }
        .pt-info-right { flex: 1; text-align: right; }
        .pt-field { margin-bottom: 4px; display: flex; align-items: flex-end; gap: 4px; }
        .pt-label { font-weight: bold; white-space: nowrap; }
        .pt-value { border-bottom: 1px solid #000; flex: 1; min-height: 14px; padding: 0 3px 1px; min-width: 120px; }
        .pt-inline { display: inline-flex; align-items: flex-end; gap: 4px; }

        /* ── Section headers ── */
        .section-intro { display: flex; margin-bottom: 8px; }
        .section-intro-left { flex: 1; font-size: 9pt; }
        .section-intro-right { flex: 1; font-size: 9pt; padding-left: 10px; }

        /* ── Two-column checklist layout (no cards — matches hardcopy) ── */
        .obs-block { margin-bottom: 18px; page-break-inside: avoid; }
        .obs-block-header { background: #f0f0f0; border: 1px solid #bbb; border-bottom: none; padding: 4px 8px; font-size: 8pt; display: flex; gap: 16px; flex-wrap: wrap; }
        .obs-block-header strong { font-size: 8.5pt; }
        .obs-columns { display: table; width: 100%; border: 1px solid #bbb; border-collapse: collapse; }
        .obs-col-left  { display: table-cell; width: 50%; border-right: 1px solid #bbb; padding: 6px 8px; vertical-align: top; }
        .obs-col-right { display: table-cell; width: 50%; padding: 6px 8px; vertical-align: top; }

        /* ── Section label (GENERAL, BREAST, etc.) ── */
        .section-label { font-size: 8.5pt; font-weight: bold; margin: 6px 0 1px; display: block; }
        .section-label:first-child { margin-top: 0; }

        /* ── Sub-section label (Mother, Baby) ── */
        .sub-label { font-size: 8pt; font-style: italic; margin: 2px 0 1px 2px; display: block; }

        /* ── Checkbox items ── */
        .cb-item { display: flex; align-items: flex-start; gap: 4px; font-size: 8pt; margin-bottom: 2px; line-height: 1.4; }
        .cb-box { width: 9px; height: 9px; border: 1.2px solid #000; flex-shrink: 0; margin-top: 2px; display: inline-block; position: relative; }
        .cb-box.checked::after { content: '\2713'; position: absolute; top: -3px; left: 0px; font-size: 9pt; font-weight: bold; line-height: 1; }

        /* ── Section divider between sections ── */
        .sec-divider { border: none; border-top: 0.8px dashed #ccc; margin: 5px 0; }

        /* ── Empty state ── */
        .empty-box { border: 1.5px dashed #bbb; padding: 24px; text-align: center; color: #999; font-style: italic; font-size: 9pt; border-radius: 3px; }

        /* ── Signature line at bottom ── */
        .sig-section { margin-top: 20px; display: flex; justify-content: flex-end; }
        .sig-line { text-align: center; min-width: 260px; }
        .sig-name { font-size: 8.5pt; min-height: 20px; line-height: 1.4; padding-bottom: 4px; }
        .sig-rule { border-bottom: 0.5px solid #000; width: 100%; display: block; margin-bottom: 3px; }
        .sig-label { font-size: 7.5pt; color: #444; margin-top: 3px; }
    </style>
</head>
<body>

@php
    use App\Models\NicuBreastfeedingObservation;
    use Carbon\Carbon;

    $patient       = $visit->patient;
    $nicuAdmission = $visit->nicuAdmission ?? null;

    // Mother's name
    $motherName = trim(
        ($patient->mother_first_name ?? '') . ' ' .
        ($patient->mother_middle_name ? $patient->mother_middle_name . ' ' : '') .
        ($patient->mother_family_name ?? $patient->mother_last_name_at_birth ?? '')
    );
    if (!$motherName) {
        $motherName = $patient->mother_name ?? '—';
    }

    // Baby's name
    $babyName = $patient->display_name ?? $patient->full_name ?? ('Baby of ' . ($patient->mother_last_name_at_birth ?? '—'));

    // Birth datetime for age calculation
    $birthDt = $nicuAdmission?->date_time_of_birth ?? $patient->birth_datetime ?? null;

    // Fetch all observations, oldest first
    $observations = NicuBreastfeedingObservation::where('visit_id', $visit->id)
        ->with('observer')
        ->orderBy('observation_date', 'asc')
        ->orderBy('observation_time', 'asc')
        ->get();

    // Field map: section => subsections => well/diff fields
    $sections = [
        'GENERAL' => [
            'Mother' => [
                'well' => [
                    'general_mother_healthy' => 'Mother looks healthy',
                    'general_mother_relaxed' => 'Mother relaxed and comfortable',
                    'general_mother_bonding' => 'Signs of bonding between mother and baby',
                ],
                'diff' => [
                    'general_mother_ill'            => 'Mother looks ill or depressed',
                    'general_mother_tense'          => 'Mother looks tense and uncomfortable',
                    'general_mother_no_eye_contact' => 'No mother / baby eye contact',
                ],
            ],
            'Baby' => [
                'well' => [
                    'general_baby_healthy' => 'Baby looks healthy',
                    'general_baby_calm'    => 'Baby calm and relaxed',
                    'general_baby_roots'   => 'Baby reaches or roots for breast if hungry',
                ],
                'diff' => [
                    'general_baby_sleepy_ill'      => 'Baby looks sleepy or ill',
                    'general_baby_restless_crying' => 'Baby is restless or crying',
                    'general_baby_no_root'         => 'Baby does not reach or root',
                ],
            ],
        ],
        'Breast' => [
            '' => [
                'well' => [
                    'breast_healthy'      => 'Breast look healthy',
                    'breast_no_pain'      => 'No pain or discomfort',
                    'breast_fingers_away' => 'Breast well supported with fingers away from nipple',
                ],
                'diff' => [
                    'breast_red_swollen_sore'  => 'Breasts look red, swollen, or sore',
                    'breast_painful'           => 'Breast or nipple painful',
                    'breast_fingers_on_areola' => 'Breast held with fingers on areola',
                ],
            ],
        ],
        "Baby's Position" => [
            '' => [
                'well' => [
                    'position_head_body_line' => "Baby's head and body in line",
                    'position_held_close'     => "Baby hold close to mother's body",
                    'position_body_supported' => "Baby's whole body supported",
                    'position_nose_to_nipple' => 'Baby approaches breast, nose to nipple',
                ],
                'diff' => [
                    'position_neck_twisted'   => "Baby's neck and head twisted to feed",
                    'position_not_held_close' => 'Baby not held close',
                    'position_head_neck_only' => 'Baby supported by head and neck only',
                    'position_chin_to_nipple' => 'Baby approaches breast, lower lip / chin to nipple',
                ],
            ],
        ],
        "Baby's Attachment" => [
            '' => [
                'well' => [
                    'attachment_more_areola_above'   => "More areola seen above baby's top lip",
                    'attachment_mouth_open_wide'     => "Baby's mouth open wide",
                    'attachment_lip_turned_out'      => 'Lower lip turned outwards',
                    'attachment_chin_touches_breast' => "Baby's chin touches breast",
                ],
                'diff' => [
                    'attachment_more_areola_below'      => 'More areola seen below bottom lip',
                    'attachment_mouth_not_wide'         => "Baby's mouth not open wide",
                    'attachment_lips_forward_turned_in' => 'Lips pointing forward or turned in',
                    'attachment_chin_not_touching'      => "Baby's chin not touching breast",
                ],
            ],
        ],
        'Suckling' => [
            '' => [
                'well' => [
                    'suckling_slow_deep_pauses' => 'Slow, deep sucks with pauses',
                    'suckling_cheeks_round'     => 'Cheeks round when suckling',
                    'suckling_baby_releases'    => 'Baby releases breast when finished',
                    'suckling_oxytocin_reflex'  => 'Mother notices signs of oxytocin reflex',
                ],
                'diff' => [
                    'suckling_rapid_shallow'      => 'Rapid shallow sucks',
                    'suckling_cheeks_pulled_in'   => 'Cheeks pulled in when suckling',
                    'suckling_mother_takes_off'   => 'Mother takes baby off the breast',
                    'suckling_no_oxytocin_reflex' => 'No signs of oxytocin reflex method',
                ],
            ],
        ],
    ];
@endphp

<div class="toolbar no-print">
    <span class="lbl">Breastfeeding Observation Job Aid</span>
    <span class="tag">NUR-044-0</span>
    <span class="pt-info">{{ $babyName }} &nbsp;&middot;&nbsp; {{ $patient->case_no ?? $patient->temporary_case_no ?? '' }}</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()">&#128438;&nbsp;&nbsp;Print / Save as PDF</button>
</div>

{{-- ── One .paper div per observation — each is its own visible page on screen ── --}}

@if($observations->isEmpty())
<div class="paper">
    <div class="form-code">NUR-044-&#216;</div>
    <div class="header">
        @if(file_exists(public_path('images/province-logo.png')))
            <div class="logo-box"><img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union"></div>
        @else
            <div class="logo-ph">Province<br>Seal</div>
        @endif
        <div class="header-center">
            <div class="h-rep">Republic of the Philippines</div>
            <div class="h-prov">Province of La Union</div>
            <div class="h-mun">Municipality of Agoo, La Union</div>
            <div class="h-hosp">La Union Medical Center</div>
        </div>
        @if(file_exists(public_path('images/lumc-logo.png')))
            <div class="logo-box"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC Logo"></div>
        @elseif(file_exists(public_path('images/bagong-pilipinas-logo-only.png')))
            <div class="logo-box"><img src="{{ asset('images/bagong-pilipinas-logo-only.png') }}" alt="Bagong Pilipinas"></div>
        @else
            <div class="logo-ph">LUMC<br>Logo</div>
        @endif
    </div>
    <div class="title-band"><h1>Breastfeeding Observation Job Aid</h1></div>
    <div class="empty-box">No breastfeeding observations recorded yet for this visit.</div>
</div>
@else
    @foreach($observations as $obsIndex => $obs)
    @php
        $totalObs = $observations->count();

        // Resolve observer's printed name and designation
        $obsUser = $obs->observer ?? null;
        if ($obsUser) {
            $obsFullName = trim(
                ($obsUser->first_name ?? '') . ' ' .
                ($obsUser->middle_name ?? '') . ' ' .
                ($obsUser->last_name ?? '')
            );
            if (!$obsFullName) {
                $obsFullName = $obsUser->name ?? '';
            }
            $obsDesignation = $obsUser->designation ?? $obsUser->position ?? '';
        } else {
            $obsFullName    = '';
            $obsDesignation = '';
        }
    @endphp

    <div class="paper">

        {{-- ── Page indicator (screen only) ── --}}
        <div class="no-print" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;padding-bottom:8px;border-bottom:2px dashed #d1d5db;">
            <span style="font-family:'Segoe UI',sans-serif;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;">
                Page {{ $obsIndex + 1 }} of {{ $totalObs }}
            </span>
            <span style="font-family:'Segoe UI',sans-serif;font-size:10px;color:#9ca3af;">
                Breastfeeding Observation Job Aid &nbsp;·&nbsp; NUR-044-&#216;
            </span>
        </div>

        {{-- ── Form Code ── --}}
        <div class="form-code">NUR-044-&#216;</div>

        {{-- ── Institutional Header ── --}}
        <div class="header">
            @if(file_exists(public_path('images/province-logo.png')))
                <div class="logo-box"><img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union"></div>
            @else
                <div class="logo-ph">Province<br>Seal</div>
            @endif
            <div class="header-center">
                <div class="h-rep">Republic of the Philippines</div>
                <div class="h-prov">Province of La Union</div>
                <div class="h-mun">Municipality of Agoo, La Union</div>
                <div class="h-hosp">La Union Medical Center</div>
            </div>
            @if(file_exists(public_path('images/lumc-logo.png')))
                <div class="logo-box"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC Logo"></div>
            @elseif(file_exists(public_path('images/bagong-pilipinas-logo-only.png')))
                <div class="logo-box"><img src="{{ asset('images/bagong-pilipinas-logo-only.png') }}" alt="Bagong Pilipinas"></div>
            @else
                <div class="logo-ph">LUMC<br>Logo</div>
            @endif
        </div>

        {{-- ── Form Title ── --}}
        <div class="title-band"><h1>Breastfeeding Observation Job Aid</h1></div>

        {{-- ── Patient Info ── --}}
        <div class="pt-info-row" style="margin-bottom:8px;">
            <div class="pt-info-left">
                <div class="pt-field">
                    <span class="pt-label">Mother's Name:</span>
                    <span class="pt-value">{{ $motherName }}</span>
                </div>
                <div class="pt-field">
                    <span class="pt-label">Baby's Name:</span>
                    <span class="pt-value">{{ $babyName }}</span>
                </div>
            </div>
            <div class="pt-info-right" style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                <div class="pt-inline">
                    <span class="pt-label">Date:</span>
                    <span class="pt-value" style="min-width:110px;">{{ now()->format('m / d / Y') }}</span>
                </div>
                <div class="pt-inline">
                    <span class="pt-label">Baby's Age:</span>
                    <span class="pt-value" style="min-width:80px;">
                        @if($birthDt)
                            @php
                                $birth = \Carbon\Carbon::parse($birthDt);
                                $nowDt = now();
                                $hrs   = (int) $birth->diffInHours($nowDt);
                                $days  = (int) $birth->diffInDays($nowDt);
                                $remH  = $hrs - ($days * 24);
                            @endphp
                            @if($days >= 1)
                                {{ $days }}d {{ $remH }}h
                            @else
                                {{ $hrs }}h
                            @endif
                        @else
                            &mdash;
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- ── Column Headers ── --}}
        <div class="section-intro">
            <div class="section-intro-left"><strong>Signs that breastfeeding is going well:</strong></div>
            <div class="section-intro-right"><strong>Signs of possible difficulty</strong></div>
        </div>

        {{-- ── Checklist ── --}}
        <div class="obs-columns">

            {{-- LEFT — Going Well --}}
            <div class="obs-col-left">
                @foreach($sections as $sectionName => $subsections)
                    <span class="section-label">{{ $sectionName }}:</span>
                    @foreach($subsections as $subName => $fields)
                        @if($subName)
                            <span class="sub-label"><em>{{ $subName }}:</em></span>
                        @endif
                        @foreach($fields['well'] as $field => $label)
                            <div class="cb-item">
                                <span class="cb-box {{ $obs->{$field} ? 'checked' : '' }}"></span>
                                <span>{{ $label }}</span>
                            </div>
                        @endforeach
                    @endforeach
                    @if(!$loop->last)
                        <hr class="sec-divider">
                    @endif
                @endforeach
            </div>

            {{-- RIGHT — Possible Difficulty --}}
            <div class="obs-col-right">
                @foreach($sections as $sectionName => $subsections)
                    <span class="section-label" style="visibility:hidden;">{{ $sectionName }}:</span>
                    @foreach($subsections as $subName => $fields)
                        @if($subName)
                            <span class="sub-label" style="visibility:hidden;"><em>{{ $subName }}:</em></span>
                        @endif
                        @foreach($fields['diff'] as $field => $label)
                            <div class="cb-item">
                                <span class="cb-box {{ $obs->{$field} ? 'checked' : '' }}"></span>
                                <span>{{ $label }}</span>
                            </div>
                        @endforeach
                    @endforeach
                    @if(!$loop->last)
                        <hr class="sec-divider">
                    @endif
                @endforeach
            </div>

        </div>{{-- /.obs-columns --}}

        {{-- ── Signature Line ── --}}
        <div class="sig-section">
            <div class="sig-line">
                <div class="sig-name">
                    @if($obsFullName)
                        <strong>{{ strtoupper($obsFullName) }}</strong>
                        @if($obsDesignation)
                            <br><span style="font-size:7.5pt;font-weight:normal;">{{ $obsDesignation }}</span>
                        @endif
                    @else
                        &nbsp;
                    @endif
                </div>
                <div class="sig-rule"></div>
                <div class="sig-label">Signature over Printed Name / Designation</div>
            </div>
        </div>

    </div>{{-- /.paper --}}

    @endforeach
@endif
</body>
</html>