<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>TPR Record — LA UNION MEDICAL CENTER</title>
<style>
    @page { 
        size: 8.5in 13in portrait; 
        margin: 0.4in 0.45in; 
    }

    * { 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }

    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 8pt;
        color: #000;
        background: #c9c9c9;
    }

    @media screen {
        body { padding: 48px 0 30px; }
        .paper {
            width: 8.5in;
            min-height: 13in;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 4px 28px rgba(0,0,0,.3);
            padding: 0.4in 0.45in;
        }
    }

    @media print {
        html, body { margin: 0; padding: 0; background: #fff; }
        .paper { 
            width: 100%; 
            padding: 0; 
            margin: 0;
            box-shadow: none; 
            page-break-inside: avoid;
            break-inside: avoid;
            overflow: hidden;
        }
        .no-print { display: none !important; }
    }

    .toolbar {
        position: fixed; top: 0; left: 0; right: 0; height: 44px;
        background: #1e3a5f; color: #fff;
        font-family: 'Segoe UI', system-ui, sans-serif; font-size: 12px;
        display: flex; align-items: center; padding: 0 20px; gap: 12px;
        z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,.35);
    }
    .toolbar .lbl { font-size: 13px; font-weight: 700; }
    .toolbar .spacer { flex: 1; }
    .btn-print {
        background: #fff; color: #1e3a5f; border: none;
        padding: 6px 18px; border-radius: 4px; font-size: 12px;
        font-weight: 700; cursor: pointer;
    }
    .btn-print:hover { background: #dbeafe; }

    .hdr {
        display: flex; align-items: center; gap: 10px;
        padding-bottom: 6px; border-bottom: 2px solid #000;
        margin-bottom: 8px;
    }
    .hdr-logo { width: 56px; height: 56px; object-fit: contain; flex-shrink: 0; }
    .hdr-logo-ph {
        width: 56px; height: 56px; border: 1px dashed #bbb; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 7pt; color: #bbb; text-align: center;
    }
    .hdr-center { flex: 1; text-align: center; line-height: 1.35; }
    .hdr-center .h-rep  { font-size: 7.5pt; }
    .hdr-center .h-prov { font-size: 9pt; font-weight: bold; text-transform: uppercase; }
    .hdr-center .h-mun  { font-size: 7.5pt; }
    .hdr-center .h-hosp { font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: .04em; }

    .pt-info {
        display: flex; gap: 14px; flex-wrap: wrap;
        font-size: 8pt; margin-bottom: 8px;
    }
    .pt-field { display: flex; align-items: baseline; gap: 4px; }
    .pt-field .lbl { font-weight: bold; white-space: nowrap; }
    .pt-field .val {
        border-bottom: 1px solid #000;
        min-width: 110px; padding: 0 4px;
        display: inline-block;
    }
    .val-wide { min-width: 220px; }
    .val-sm   { min-width: 60px; }

    .form-title {
        text-align: center; font-weight: bold; font-size: 11pt;
        text-transform: uppercase; letter-spacing: .08em;
        margin: 6px 0 8px;
        border-top: 1px solid #000; border-bottom: 1px solid #000;
        padding: 3px 0;
    }

    /* Main TPR table */
    .tpr-main {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 6.5pt;
    }
    .tpr-main td, .tpr-main th {
        border: 1px solid #000;
        padding: 0;
        text-align: center;
        vertical-align: middle;
    }
    
    /* Increased width for readability */
    .col-label {
        width: 85px; 
        text-align: left;
        font-weight: bold;
        padding: 2px 6px;
    }

    .row-top td { height: 18px; }
    .row-graph td { padding: 0; vertical-align: top; }
    .day-hdr {
        font-weight: bold;
        background: #f8f8f8;
        height: 18px;
    }

    /* I/O rows */
    .io-row td { height: 18px; line-height: 18px; }
    .io-label-word-cell {
        width: 45px;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .io-shift-cell {
        width: 40px; /* Wider shift labels */
        text-align: center;
        font-size: 7pt;
        font-weight: bold;
        background: #fafafa;
    }
    .io-data-cell {
        font-size: 7pt;
    }
</style>
</head>
<body>

@php
use Carbon\Carbon;

$patient    = $visit->patient;
$admittedAt = $visit->clerk_admitted_at 
    ? Carbon::parse($visit->clerk_admitted_at)->timezone('Asia/Manila') 
    : null;

// Bed
$bedLabel = '—';
$bedRecord = \App\Models\Bed::where('visit_id', $visit->id)->with('room.ward')->first();
if ($bedRecord) {
    $roomNo = $bedRecord->room?->room_number ?? '';
    $wardName = $bedRecord->room?->ward?->name ?? $bedRecord->ward?->name ?? '';
    $bedLabel = trim(implode(' / ', array_filter([$wardName, $roomNo, $bedRecord->bed_label])));
}

// Vitals
$vitalsByDay = [];
$weightByDay = [];

foreach (($vitals ?? []) as $v) {
    $dt   = Carbon::parse($v->taken_at)->timezone('Asia/Manila');
    $day  = (int) $dt->format('j');
    $hour = (int) $dt->format('G');
    $shift = match(true) {
        $hour >= 7  && $hour < 15 => '7-3',
        $hour >= 15 && $hour < 23 => '3-11',
        default                   => '11-7',
    };

    if (!isset($vitalsByDay[$day][$shift]) || 
        $dt->gt(Carbon::parse($vitalsByDay[$day][$shift]->taken_at))) {
        $vitalsByDay[$day][$shift] = $v;
    }

    if ($v->weight_kg !== null) {
        $weightByDay[$day] = $v->weight_kg;
    }
}

// I/O
$ioByDay = [];
foreach (\App\Models\TprIoEntry::where('visit_id', $visit->id)->get() as $io) {
    $day = (int) Carbon::parse($io->date)->format('j');
    $ioByDay[$day][$io->shift] = $io;
}

// === Scale Geometry ===
$DEG_H = 48;
$TOP_MARGIN = 16;

$tempY  = fn(float $t): float => $TOP_MARGIN + (42.0 - $t) * $DEG_H;
$pulseY = fn(float $p): float => $TOP_MARGIN + (42.0 - 36.0) * $DEG_H + (180.0 - $p) / 20.0 * $DEG_H;
$respY  = fn(float $r): float => $pulseY(60.0) + (40.0 - $r) / 10.0 * $DEG_H;

$svgH = (int) ceil($respY(0)) + 16;

$svgCols = 93;
$colW    = 13.5;
$svgW    = $svgCols * $colW;

$shifts = ['7-3', '3-11', '11-7'];

$toX = function(int $day, string $shift) use ($colW, $shifts): float {
    $shiftIdx = array_search($shift, $shifts);
    $colIdx   = ($day - 1) * 3 + $shiftIdx;
    return round($colIdx * $colW + $colW / 2, 2);
};

// Plot points
$tempPoints = $pulsePoints = $respPoints = [];

for ($d = 1; $d <= 31; $d++) {
    foreach ($shifts as $shift) {
        $v = $vitalsByDay[$d][$shift] ?? null;
        if (!$v) continue;
        $x = $toX($d, $shift);

        if ($v->temperature) {
            $val = (float)$v->temperature;
            if ($val >= 35 && $val <= 42) {
                $tempPoints[] = ['x' => $x, 'y' => $tempY($val), 'v' => number_format($val, 1)];
            }
        }
        if ($v->pulse_rate) {
            $val = (float)$v->pulse_rate;
            if ($val >= 40 && $val <= 220) {
                $pulsePoints[] = ['x' => $x, 'y' => $pulseY($val), 'v' => (int)$val];
            }
        }
        if ($v->respiratory_rate) {
            $val = (float)$v->respiratory_rate;
            if ($val >= 0 && $val <= 60) {
                $respPoints[] = ['x' => $x, 'y' => $respY($val), 'v' => (int)$val];
            }
        }
    }
}

$polyline = fn($pts) => implode(' ', array_map(fn($p) => "{$p['x']},{$p['y']}", $pts));
@endphp

<div class="toolbar no-print">
    <span class="lbl"><x-heroicon-o-heart style="width:14px;height:14px;display:inline-block;vertical-align:-2px;" /> TPR Record</span>
    <span style="font-size:11px;color:rgba(255,255,255,.85);">
        {{ $patient->full_name }} • {{ $patient->case_no }}
    </span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()"><x-heroicon-o-printer style="width:14px;height:14px;display:inline-block;vertical-align:-2px;" /> Print / Save as PDF</button>
</div>

<div class="paper">

    <div class="hdr">
        @if(file_exists(public_path('images/province-logo.png')))
            <img src="{{ asset('images/province-logo.png') }}" class="hdr-logo" alt="Province">
        @else
            <div class="hdr-logo-ph">Province<br>Seal</div>
        @endif

        <div class="hdr-center">
            <div class="h-rep">Republic of the Philippines</div>
            <div class="h-prov">Province of La Union</div>
            <div class="h-mun">Municipality of Agoo, La Union</div>
            <div class="h-hosp">La Union Medical Center</div>
        </div>

        @if(file_exists(public_path('images/lumc-logo.png')))
            <img src="{{ asset('images/lumc-logo.png') }}" class="hdr-logo" alt="LUMC">
        @else
            <div class="hdr-logo-ph">LUMC<br>Logo</div>
        @endif
    </div>

    <div class="pt-info">
        <div class="pt-field"><span class="lbl">Name:</span><span class="val val-wide">{{ $patient->full_name }}</span></div>
        <div class="pt-field"><span class="lbl">Age:</span><span class="val val-sm">{{ $patient->age_display ?? $patient->age ?? '' }}</span></div>
        <div class="pt-field"><span class="lbl">Sex:</span><span class="val val-sm">{{ $patient->sex }}</span></div>
        <div class="pt-field"><span class="lbl">Ward / Service:</span><span class="val">{{ $visit->admitted_service ?? '' }}</span></div>
        <div class="pt-field"><span class="lbl">Bed No.:</span><span class="val">{{ $bedLabel }}</span></div>
        <div class="pt-field"><span class="lbl">Case No.:</span><span class="val">{{ $patient->case_no }}</span></div>
    </div>

    <div class="form-title">TPR GRAPHIC RECORD</div>

    <table class="tpr-main">
        <tr class="row-top">
            <td class="col-label" colspan="2">Day of Month</td>
            @php $startDay = $admittedAt ? (int)$admittedAt->format('j') : 1; @endphp
            @for($i = 0; $i < 31; $i++)
                @php $current = $startDay + $i; @endphp
                <td colspan="3" class="day-hdr">{{ $current > 31 ? $current - 31 : $current }}</td>
            @endfor
        </tr>

        <tr class="row-top">
            <td class="col-label" colspan="2">Hospital Day</td>
            @for($i = 1; $i <= 31; $i++)
                <td colspan="3" style="height:18px;font-size:7pt;">{{ $i }}</td>
            @endfor
        </tr>

        <tr class="row-top">
            <td class="col-label" colspan="2">Weight (kg)</td>
            @for($d = 1; $d <= 31; $d++)
                <td colspan="3" style="height:18px;font-size:7pt;">
                    {{ $weightByDay[$d] ?? '' ? number_format($weightByDay[$d], 1) : '' }}
                </td>
            @endfor
        </tr>

        <tr class="row-graph">
            <td class="col-label" colspan="2" style="padding:0; border-right:1px solid #000;">
                <svg viewBox="0 0 85 {{ $svgH }}" width="85" height="{{ $svgH }}" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                    <text x="22" y="9" text-anchor="end" font-size="7" font-weight="bold">RESP</text>
                    <text x="52" y="9" text-anchor="end" font-size="7" font-weight="bold">PULSE</text>
                    <text x="82" y="9" text-anchor="end" font-size="7" font-weight="bold">TEMP</text>

                    <text x="82" y="14" text-anchor="end" font-size="7.5" font-weight="bold">C°</text>
                    @foreach([42,41,40,39,38,37,36,35] as $t)
                        @php $y = $tempY($t); @endphp
                        <text x="82" y="{{ $y + 4 }}" text-anchor="end" font-size="7.5">{{ $t }}</text>
                        <line x1="74" y1="{{ $y }}" x2="85" y2="{{ $y }}" stroke="#000" stroke-width="0.6"/>
                    @endforeach

                    @foreach([180,160,140,120,100,80,60] as $p)
                        @php $y = $pulseY($p); @endphp
                        <text x="52" y="{{ $y + 4.5 }}" text-anchor="end" font-size="7.5">{{ $p }}</text>
                        <line x1="44" y1="{{ $y }}" x2="55" y2="{{ $y }}" stroke="#000" stroke-width="0.6"/>
                    @endforeach

                    @foreach([50,40,30,20,10] as $r)
                        @php $y = $respY($r); @endphp
                        <text x="22" y="{{ $y + 4 }}" text-anchor="end" font-size="7.5">{{ $r }}</text>
                        <line x1="14" y1="{{ $y }}" x2="25" y2="{{ $y }}" stroke="#000" stroke-width="0.6"/>
                    @endforeach

                    <line x1="28" y1="0" x2="28" y2="{{ $svgH }}" stroke="#000" stroke-width="0.5"/>
                    <line x1="58" y1="0" x2="58" y2="{{ $svgH }}" stroke="#000" stroke-width="0.5"/>
                </svg>
            </td>
            <td colspan="93" style="padding:0;">
                <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" width="100%" height="{{ $svgH }}" 
                     xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <rect x="0" y="0" width="{{ $svgW }}" height="{{ $svgH }}" fill="#fff"/>

                    @php $gridStep = $DEG_H / 4; @endphp
                    @for($i = 0; $i <= ceil(($svgH - $TOP_MARGIN) / $gridStep); $i++)
                        @php $y = $TOP_MARGIN + $i * $gridStep; $major = ($i % 4 === 0); @endphp
                        <line x1="0" y1="{{ $y }}" x2="{{ $svgW }}" y2="{{ $y }}" 
                               stroke="{{ $major ? '#999' : '#ddd' }}" stroke-width="{{ $major ? '0.6' : '0.25' }}"/>
                    @endfor

                    @for($c = 0; $c <= $svgCols; $c++)
                        @php $x = $c * $colW; $daySep = ($c % 3 === 0); @endphp
                        <line x1="{{ $x }}" y1="0" x2="{{ $x }}" y2="{{ $svgH }}" 
                               stroke="{{ $daySep ? '#777' : '#ddd' }}" stroke-width="{{ $daySep ? '0.7' : '0.25' }}"/>
                    @endfor

                    <line x1="0" y1="{{ $tempY(37) }}" x2="{{ $svgW }}" y2="{{ $tempY(37) }}" stroke="#cc0000" stroke-width="1.2" stroke-dasharray="4 2"/>
                    
                    @if(count($tempPoints) >= 2)
                        <polyline points="{{ $polyline($tempPoints) }}" fill="none" stroke="#cc0000" stroke-width="1.6" stroke-linejoin="round"/>
                    @endif
                    @foreach($tempPoints as $p)
                        <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="3.5" fill="#cc0000" stroke="#fff" stroke-width="0.8"/>
                        <text x="{{ $p['x'] }}" y="{{ $p['y']-6 }}" text-anchor="middle" font-size="8" fill="#cc0000" font-weight="bold">{{ $p['v'] }}</text>
                    @endforeach

                    @if(count($pulsePoints) >= 2)
                        <polyline points="{{ $polyline($pulsePoints) }}" fill="none" stroke="#dd8800" stroke-width="1.6" stroke-linejoin="round"/>
                    @endif
                    @foreach($pulsePoints as $p)
                        <line x1="{{ $p['x']-4 }}" y1="{{ $p['y']-4 }}" x2="{{ $p['x']+4 }}" y2="{{ $p['y']+4 }}" stroke="#dd8800" stroke-width="1.8"/>
                        <line x1="{{ $p['x']+4 }}" y1="{{ $p['y']-4 }}" x2="{{ $p['x']-4 }}" y2="{{ $p['y']+4 }}" stroke="#dd8800" stroke-width="1.8"/>
                        <text x="{{ $p['x'] }}" y="{{ $p['y']-6 }}" text-anchor="middle" font-size="8" fill="#dd8800" font-weight="bold">{{ $p['v'] }}</text>
                    @endforeach

                    @if(count($respPoints) >= 2)
                        <polyline points="{{ $polyline($respPoints) }}" fill="none" stroke="#2266aa" stroke-width="1.6" stroke-linejoin="round"/>
                    @endif
                    @foreach($respPoints as $p)
                        <rect x="{{ $p['x']-3.5 }}" y="{{ $p['y']-3.5 }}" width="7" height="7" fill="#2266aa" stroke="#fff" stroke-width="0.7"/>
                        <text x="{{ $p['x'] }}" y="{{ $p['y']-6 }}" text-anchor="middle" font-size="8" fill="#2266aa" font-weight="bold">{{ $p['v'] }}</text>
                    @endforeach

                    <rect x="0" y="0" width="{{ $svgW }}" height="{{ $svgH }}" fill="none" stroke="#000" stroke-width="1.5"/>
                </svg>
            </td>
        </tr>

        <tr class="io-row">
            <td class="io-label-word-cell" rowspan="3">URINE</td>
            <td class="io-shift-cell">7-3</td>
            @for($d = 1; $d <= 31; $d++)
                @php $io = $ioByDay[$d]['7-3'] ?? null; @endphp
                <td colspan="3" class="io-data-cell">{{ $io?->urine_count ? $io->urine_count.'×' : '' }}</td>
            @endfor
        </tr>
        <tr class="io-row">
            <td class="io-shift-cell">3-11</td>
            @for($d = 1; $d <= 31; $d++)
                @php $io = $ioByDay[$d]['3-11'] ?? null; @endphp
                <td colspan="3" class="io-data-cell">{{ $io?->urine_count ? $io->urine_count.'×' : '' }}</td>
            @endfor
        </tr>
        <tr class="io-row">
            <td class="io-shift-cell">11-7</td>
            @for($d = 1; $d <= 31; $d++)
                @php $io = $ioByDay[$d]['11-7'] ?? null; @endphp
                <td colspan="3" class="io-data-cell">{{ $io?->urine_count ? $io->urine_count.'×' : '' }}</td>
            @endfor
        </tr>

        <tr class="io-row">
            <td class="io-label-word-cell" rowspan="3">STOOL</td>
            <td class="io-shift-cell">7-3</td>
            @for($d = 1; $d <= 31; $d++)
                @php 
                    $io = $ioByDay[$d]['7-3'] ?? null;
                    $val = $io?->stool_count ? $io->stool_count.'×' : '';
                    if ($io?->stool_type) $val .= ' '.strtoupper(substr($io->stool_type,0,1));
                @endphp
                <td colspan="3" class="io-data-cell">{{ $val }}</td>
            @endfor
        </tr>
        <tr class="io-row">
            <td class="io-shift-cell">3-11</td>
            @for($d = 1; $d <= 31; $d++)
                @php 
                    $io = $ioByDay[$d]['3-11'] ?? null;
                    $val = $io?->stool_count ? $io->stool_count.'×' : '';
                    if ($io?->stool_type) $val .= ' '.strtoupper(substr($io->stool_type,0,1));
                @endphp
                <td colspan="3" class="io-data-cell">{{ $val }}</td>
            @endfor
        </tr>
        <tr class="io-row">
            <td class="io-shift-cell">11-7</td>
            @for($d = 1; $d <= 31; $d++)
                @php 
                    $io = $ioByDay[$d]['11-7'] ?? null;
                    $val = $io?->stool_count ? $io->stool_count.'×' : '';
                    if ($io?->stool_type) $val .= ' '.strtoupper(substr($io->stool_type,0,1));
                @endphp
                <td colspan="3" class="io-data-cell">{{ $val }}</td>
            @endfor
        </tr>
    </table>

    <div style="margin-top:10px;font-size:8pt;display:flex;gap:24px;justify-content:center;flex-wrap:wrap;">
        <span style="display:flex;align-items:center;gap:6px;">
            <span style="display:inline-block;width:26px;height:10px;background:#cc0000;border-radius:2px;"></span> Temperature (°C)
        </span>
        <span style="display:flex;align-items:center;gap:6px;">
            <span style="display:inline-block;width:26px;height:10px;background:#dd8800;border-radius:2px;"></span> Pulse Rate
        </span>
        <span style="display:flex;align-items:center;gap:6px;">
            <span style="display:inline-block;width:26px;height:10px;background:#2266aa;border-radius:2px;"></span> Respiratory Rate
        </span>
    </div>

</div>
</body>
</html>
