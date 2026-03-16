{{--
    ╔══════════════════════════════════════════════════════════════════╗
    ║  Consent to Care  —  NUR-002-1                                   ║
    ║  Path : resources/views/forms/consent-to-care.blade.php          ║
    ║  Route: GET /forms/consent-to-care/{visit}                       ║
    ║                                                                  ║
    ║  Variables (from ConsentController):                             ║
    ║    $patientName  string  "JUAN M. DELA CRUZ"  (First MI. FAMILY) ║
    ║    $doctorName   string  "JOSE REYES"  (all caps, no Dr. prefix) ║
    ║    $today        string  "May 14, 2026"                          ║
    ║    $visit        Visit                                           ║
    ║    $patient      Patient                                         ║
    ║                                                                  ║
    ║  JS behaviour:                                                   ║
    ║  ①  Section 1 active by default (patientName, doc1, date1 set)   ║
    ║  ②  patientName typed → activate Sec1, clear ALL Sec2            ║
    ║  ③  patientName cleared → deactivate Sec1 (clear doc1, date1)    ║
    ║  ④  guardianName typed → activate Sec2, clear ALL Sec1           ║
    ║  ⑤  guardianName cleared → deactivate Sec2                       ║
    ║  ⑥  guardianName mirrors live into "Name & Sig of Next of Kin"   ║
    ║  ⑦  beingThe mirrors live into "Relation to Patient" (unless     ║
    ║     the clerk directly edits the latter)                         ║
    ║  ⑧  ALL input is forced to UPPERCASE on every keystroke          ║
    ║  ⑨  Enter is suppressed (no <br> insertion)                      ║
    ║  ⑩  Click a pre-filled field → select-all for easy replacement   ║
    ╚══════════════════════════════════════════════════════════════════╝
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consent to Care — LA UNION MEDICAL CENTER</title>
    <style>

        /* ── PAGE SETUP ─────────────────────────────────────────────────── */
        @page {
            size: 8.5in 14in portrait;
            margin: 0.70in 0.80in 0.70in 0.80in;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11.5pt;
            color: #000;
            background: #c9c9c9;
        }

        /* ── SCREEN: paper card ─────────────────────────────────────────── */
        @media screen {
            body  { padding: 52px 0 56px; }
            .paper {
                width: 8.5in;
                min-height: 14in;
                margin: 0 auto;
                background: #fff;
                box-shadow: 0 4px 28px rgba(0,0,0,.30);
                padding: 0.70in 0.80in;
            }
        }

        /* ── PRINT: clean output ────────────────────────────────────────── */
        @media print {
            body  { background: #fff; padding: 0; }
            .paper { width: 100%; padding: 0; box-shadow: none; }
            .no-print { display: none !important; }
            [contenteditable] {
                outline: none !important;
                box-shadow: none !important;
                background: transparent !important;
                border-bottom-color: #000 !important;
            }
        }

        /* ── TOOLBAR ────────────────────────────────────────────────────── */
        .toolbar {
            position: fixed;
            top: 0; left: 0; right: 0; height: 46px;
            background: #1e3a5f; color: #fff;
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-size: 12px;
            display: flex; align-items: center;
            padding: 0 22px; gap: 14px;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,.35);
        }
        .toolbar .lbl  { font-size: 13px; font-weight: 700; }
        .toolbar .tag  {
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 3px; padding: 2px 9px;
            font-size: 10px; letter-spacing: .05em; text-transform: uppercase;
        }
        .toolbar .hint { opacity: .5; font-size: 11px; }
        .toolbar .spacer { flex: 1; }
        .btn-print {
            background: #fff; color: #1e3a5f;
            border: none; padding: 6px 20px; border-radius: 4px;
            font-size: 12px; font-weight: 700;
            cursor: pointer; font-family: inherit;
        }
        .btn-print:hover { background: #dbeafe; }

        /* ── HEADER ─────────────────────────────────────────────────────── */
        .header {
            display: flex; align-items: center; gap: 12px;
            padding-bottom: 10px;
            border-bottom: 2.5px solid #000;
        }
        .logo-box {
            width: 72px; height: 72px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-box img { width: 72px; height: 72px; object-fit: contain; }
        .logo-ph {
            width: 72px; height: 72px; flex-shrink: 0;
            border: 1.5px dashed #bbb;
            display: flex; align-items: center; justify-content: center;
            font-size: 7.5pt; color: #bbb;
            text-align: center; line-height: 1.4;
        }
        .header-center { flex: 1; text-align: center; line-height: 1.35; }
        .header-center .h-rep  { font-size: 9pt; letter-spacing: .02em; }
        .header-center .h-prov {
            font-size: 10.5pt; font-weight: bold;
            text-transform: uppercase; letter-spacing: .04em;
        }
        .header-center .h-mun  { font-size: 9pt; }
        .header-center .h-hosp {
            font-size: 16pt; font-weight: bold;
            text-transform: uppercase; letter-spacing: .07em;
            margin-top: 3px;
        }

        /* ── TITLE ──────────────────────────────────────────────────────── */
        .title-band { text-align: center; margin: 18px 0 24px; }
        .title-band h1 {
            display: inline-block;
            font-size: 15pt; font-weight: bold;
            text-transform: uppercase; letter-spacing: .14em;
            border-bottom: 1.5px solid #000; padding-bottom: 4px;
        }

        /* ── BODY TEXT ──────────────────────────────────────────────────── */
        .body-para {
            font-size: 11.5pt;
            line-height: 1.95;
            text-align: justify;
            margin-bottom: 16px;
        }
        .tab { display: inline-block; width: 36pt; }

        /* ──────────────────────────────────────────────────────────────────
         * .field — editable underlined blank
         *
         * text-transform: uppercase  forces ALL typed characters to caps.
         * text-align: center         centres content on the underline.
         * vertical-align: bottom     sits on the text baseline.
         *
         * JS additionally calls toUpperCase() on every input event so that
         * textContent (used for mirroring) is also uppercase — CSS alone
         * only affects visual rendering, not the DOM value.
         * ────────────────────────────────────────────────────────────────── */
        .field {
            display: inline-block;
            border-bottom: 1px solid #000;
            vertical-align: bottom;
            min-height: 18px;
            line-height: 18px;
            padding: 0 4px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11.5pt;
            color: #000;
            outline: none;
            cursor: text;
            white-space: nowrap;
            overflow: visible;
            text-align: center;
            text-transform: uppercase; /* ← visual uppercase on screen & print */
        }
        @media screen {
            .field:focus {
                background: #fefce8;
                border-bottom: 2px solid #1d4ed8;
            }
            .field:hover:not(:focus) { border-bottom-color: #555; }
        }

        /* Width variants */
        .f-sm   { min-width: 110px; }
        .f-md   { min-width: 190px; }
        .f-lg   { min-width: 260px; }
        .f-dr   { min-width: 235px; }
        .f-date { min-width: 160px; }

        /* ── SIGNATURE BLOCKS ───────────────────────────────────────────── */
        .sig-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 28px;
        }
        .date-right {
            display: flex; justify-content: flex-end;
            margin-bottom: 10px;
        }
        .date-right .sig-block { width: 215px; }
        .sig-block { display: flex; flex-direction: column; }
        .sig-line {
            border-bottom: 1px solid #000;
            height: 46px; width: 100%;
            display: flex; align-items: flex-end;
            padding-bottom: 3px;
        }
        .sig-cap {
            font-size: 9.5pt;
            text-align: center;
            font-style: italic;
            margin-top: 3px;
            line-height: 1.3;
        }

        /* ── DIVIDER ────────────────────────────────────────────────────── */
        .divider { border: none; border-top: 1.5px solid #000; margin: 22px 0 20px; }

        /* ── NOTE BOX ───────────────────────────────────────────────────── */
        .note-box {
            border: 1px solid #555; padding: 9px 14px;
            font-size: 10.5pt; line-height: 1.75;
            text-align: justify; font-style: italic;
            margin-bottom: 22px; background: #fafafa;
        }
        .note-box strong { font-style: normal; }

        /* ── SCREEN TIP ─────────────────────────────────────────────────── */
        .screen-tip {
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-size: 10px; color: #374151;
            background: #eff6ff; border: 1px solid #bfdbfe;
            border-radius: 4px; padding: 6px 14px;
            margin-bottom: 14px; line-height: 1.6;
        }
        @media print { .screen-tip { display: none; } }

    </style>
</head>
<body>

{{-- ══ TOOLBAR ══════════════════════════════════════════════════════════════ --}}
<div class="toolbar no-print">
    <span class="lbl">LUMC · Consent to Care</span>
    <span class="tag">NUR-002-1</span>
    @isset($patient)
        <span class="tag" style="background:rgba(16,185,129,.22);border-color:rgba(16,185,129,.45);">
            {{ $patient->case_no }}
        </span>
    @endisset
    <span class="hint">Legal 8.5 × 14 in &nbsp;·&nbsp; Click any underlined field to edit &nbsp;·&nbsp; All text is typed in CAPITALS</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()">🖨️&nbsp;&nbsp;Print / Save as PDF</button>
</div>

{{-- ══ PAPER ═════════════════════════════════════════════════════════════════ --}}
<div class="paper">

    <div class="screen-tip no-print">
        💡 <strong>Click any underlined field</strong> to type directly — all text is automatically capitalised.
        &nbsp;·&nbsp;
        Fill <strong>either</strong> Section 1 (patient signs) <em>or</em> Section 2 (guardian signs) — not both.
        Typing a name in one section automatically clears the other.
        &nbsp;·&nbsp;
        When done, click <strong>Print / Save as PDF</strong>.
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         HEADER
         ════════════════════════════════════════════════════════════════════ --}}
    <div class="header">
        @if(file_exists(public_path('images/province-logo.png')))
            <div class="logo-box">
                <img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union">
            </div>
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
            <div class="logo-box">
                <img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC Logo">
            </div>
        @elseif(file_exists(public_path('images/bagong-pilipinas-logo-only.png')))
            <div class="logo-box">
                <img src="{{ asset('images/bagong-pilipinas-logo-only.png') }}" alt="Bagong Pilipinas">
            </div>
        @else
            <div class="logo-ph">LUMC<br>Logo</div>
        @endif
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         TITLE
         ════════════════════════════════════════════════════════════════════ --}}
    <div class="title-band"><h1>Consent to Care</h1></div>

    {{-- ════════════════════════════════════════════════════════════════════
         SECTION 1 — ADULT / PATIENT CONSENT
         Active by default: patientName pre-filled, doc1 pre-filled, date1 pre-filled.
         Entire section is cleared when the clerk types in guardianName.
         ════════════════════════════════════════════════════════════════════ --}}

    <p class="body-para">
        <span class="tab"></span>I hereby authorize
        Dr.&nbsp;<span
            class="field f-dr"
            id="doc1"
            contenteditable="true"
            spellcheck="false"
            aria-label="Doctor name — Section 1"
        >{{ $doctorName ?? '' }}</span>&nbsp;and the staff of
        LA UNION MEDICAL CENTER to perform treatment or procedure
        deemed necessary for my medical care.
    </p>

    <p class="body-para">
        <span class="tab"></span>This is to further authorize the hospital to disclose
        information regarding the medical care extended to me and other relevant data
        from the Medical Records for purpose of claim from any insurance company or
        to my attorney for legal purposes.
    </p>

    <div class="sig-grid-2">

        {{-- Witness 1 — blank; clerk types --}}
        <div class="sig-block">
            <div class="sig-line">
                <span
                    class="field"
                    id="witness1"
                    contenteditable="true"
                    spellcheck="false"
                    aria-label="Witness name — Section 1"
                    style="width:100%; border:none;"
                ></span>
            </div>
            <div class="sig-cap">Name &amp; Signature of Witness</div>
        </div>

        {{-- Patient name — pre-filled with $patientName (First MI. FAMILY, all caps) --}}
        <div class="sig-block">
            <div class="sig-line">
                <span
                    class="field"
                    id="patientName"
                    contenteditable="true"
                    spellcheck="false"
                    aria-label="Patient name and signature"
                    style="width:100%; border:none;"
                >{{ $patientName ?? '' }}</span>
            </div>
            <div class="sig-cap">Name &amp; Signature of Patient</div>
        </div>

    </div>

    {{-- Date 1 — pre-filled; cleared when patientName is emptied --}}
    <div class="date-right">
        <div class="sig-block">
            <div class="sig-line">
                <span
                    class="field f-date"
                    id="date1"
                    contenteditable="true"
                    spellcheck="false"
                    aria-label="Date — Section 1"
                    style="width:100%; border:none;"
                >{{ $today ?? '' }}</span>
            </div>
            <div class="sig-cap">Date</div>
        </div>
    </div>

    <hr class="divider">

    {{-- ════════════════════════════════════════════════════════════════════
         SECTION 2 — GUARDIAN / NEXT OF KIN
         Blank by default.
         Activated when guardianName receives content.
         Cleared when patientName receives content.

         NEW: guardianName mirrors live into #nokSigName (the "Name & Signature
              of Next of Kin" field in the sig-grid below).
         ════════════════════════════════════════════════════════════════════ --}}

    <div class="note-box">
        <strong>NOTE:</strong>&nbsp; This AUTHORIZATION MUST be signed by the parents
        or by the nearest of kin of patient if of a minor age or when the patient is
        physically or mentally incompetent or incapacitated.
    </div>

    <p class="body-para">
        <span class="tab"></span>I&nbsp;<span
            class="field f-lg"
            id="guardianName"
            contenteditable="true"
            spellcheck="false"
            aria-label="Guardian / next-of-kin name"
        ></span>&nbsp;being
        the&nbsp;<span
            class="field f-sm"
            id="beingThe"
            contenteditable="true"
            spellcheck="false"
            aria-label="Relationship to patient (inline)"
        ></span>&nbsp;thereby
        <strong>AUTHORIZE</strong> DR.&nbsp;<span
            class="field f-dr"
            id="doc2"
            contenteditable="true"
            spellcheck="false"
            aria-label="Doctor name — Section 2"
        ></span>&nbsp;and
        the staff of LUMC to perform any treatment or procedure
        necessary for his / her medical care during admission.
    </p>

    <div class="sig-grid-2">

        {{-- Witness 2 — blank; clerk types --}}
        <div class="sig-block">
            <div class="sig-line">
                <span
                    class="field"
                    id="witness2"
                    contenteditable="true"
                    spellcheck="false"
                    aria-label="Witness name — Section 2"
                    style="width:100%; border:none;"
                ></span>
            </div>
            <div class="sig-cap">Name &amp; Signature of Witness</div>
        </div>

        {{--
            "Name & Signature of Next of Kin"
            id="nokSigName" — auto-mirrors #guardianName via JS.
            The clerk can click it to edit independently if needed.
        --}}
        <div class="sig-block">
            <div class="sig-line">
                <span
                    class="field"
                    id="nokSigName"
                    contenteditable="true"
                    spellcheck="false"
                    aria-label="Name and signature of next of kin"
                    style="width:100%; border:none;"
                ></span>
            </div>
            <div class="sig-cap">Name &amp; Signature of Next of Kin</div>
        </div>

    </div>

    {{-- Date 2 + Relation to Patient --}}
    <div class="sig-grid-2">

        {{-- Date 2 — blank by default; set to today when Sec 2 activates --}}
        <div class="sig-block">
            <div class="sig-line">
                <span
                    class="field f-date"
                    id="date2"
                    contenteditable="true"
                    spellcheck="false"
                    aria-label="Date — Section 2"
                    style="width:100%; border:none;"
                ></span>
            </div>
            <div class="sig-cap">Date</div>
        </div>

        {{--
            "Relation to Patient"
            id="relationToPatient" — auto-mirrors #beingThe via JS.
            Clerk can edit directly; once edited directly, mirroring stops.
        --}}
        <div class="sig-block">
            <div class="sig-line">
                <span
                    class="field"
                    id="relationToPatient"
                    contenteditable="true"
                    spellcheck="false"
                    aria-label="Relation to patient"
                    style="width:100%; border:none;"
                ></span>
            </div>
            <div class="sig-cap">Relation to Patient</div>
        </div>

    </div>

</div>{{-- /.paper --}}

{{-- ══ DATA CARRIERS — PHP values as safe element text ═══════════════════════ --}}
<span id="_doctorName" style="display:none">{{ $doctorName ?? '' }}</span>
<span id="_today"      style="display:none">{{ $today ?? '' }}</span>

{{-- ══════════════════════════════════════════════════════════════════════════
     JAVASCRIPT — purely presentational, nothing posted to the server
     ══════════════════════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    /* ── PHP data ─────────────────────────────────────────────────────── */
    var DOCTOR = document.getElementById('_doctorName').textContent;
    var TODAY  = document.getElementById('_today').textContent;

    /* ── Elements ─────────────────────────────────────────────────────── */
    var EL = {
        patientName      : document.getElementById('patientName'),
        guardianName     : document.getElementById('guardianName'),
        nokSigName       : document.getElementById('nokSigName'),       // mirrors guardianName
        beingThe         : document.getElementById('beingThe'),
        doc1             : document.getElementById('doc1'),
        doc2             : document.getElementById('doc2'),
        date1            : document.getElementById('date1'),
        date2            : document.getElementById('date2'),
        witness1         : document.getElementById('witness1'),
        witness2         : document.getElementById('witness2'),
        relationToPatient: document.getElementById('relationToPatient'),
    };

    /* ── Helpers ──────────────────────────────────────────────────────── */
    function txt(el) { return el ? el.textContent.trim() : ''; }

    // Write a value programmatically without re-triggering our own listeners.
    var _writing = false;
    function write(el, value) {
        if (!el) return;
        _writing = true;
        el.textContent = value;
        _writing = false;
    }

    /* ── State flags ──────────────────────────────────────────────────── */
    var nokIndependent      = false; // clerk directly edited nokSigName
    var relationIndependent = false; // clerk directly edited relationToPatient

    /* ── Section helpers ──────────────────────────────────────────────── */

    function activateSec1() {
        if (txt(EL.doc1)  === '') write(EL.doc1,  DOCTOR);
        if (txt(EL.date1) === '') write(EL.date1, TODAY);
    }
    function deactivateSec1() {
        write(EL.doc1,  '');
        write(EL.date1, '');
    }
    function clearSec1() {
        write(EL.patientName, '');
        write(EL.witness1,    '');
        write(EL.doc1,        '');
        write(EL.date1,       '');
    }

    function activateSec2() {
        if (txt(EL.doc2)  === '') write(EL.doc2,  DOCTOR);
        if (txt(EL.date2) === '') write(EL.date2, TODAY);
    }
    function deactivateSec2() {
        write(EL.doc2,  '');
        write(EL.date2, '');
        relationIndependent = false;
        nokIndependent      = false;
    }
    function clearSec2() {
        write(EL.guardianName,      '');
        write(EL.nokSigName,        '');
        write(EL.beingThe,          '');
        write(EL.doc2,              '');
        write(EL.date2,             '');
        write(EL.witness2,          '');
        write(EL.relationToPatient, '');
        relationIndependent = false;
        nokIndependent      = false;
    }

    /* ══════════════════════════════════════════════════════════════════
     * RULE ⑧ — Force ALL input to uppercase
     *
     * CSS text-transform:uppercase changes appearance only.
     * We also normalise the DOM text so that .textContent is uppercase,
     * which is critical for mirroring (guardianName → nokSigName etc.).
     *
     * Algorithm:
     *   1. Save current cursor position (character offset within the node).
     *   2. Replace textContent with its toUpperCase().
     *   3. Restore the cursor to the same character offset.
     *
     * This avoids the cursor jumping to position 0 or end.
     * ══════════════════════════════════════════════════════════════════ */
    function enforceUppercase(el) {
        if (!el) return;
        el.addEventListener('input', function () {
            if (_writing) return;

            var raw = el.textContent;
            var up  = raw.toUpperCase();
            if (raw === up) return; // nothing to do — already uppercase

            // Capture caret position before we overwrite textContent
            var sel   = window.getSelection();
            var range = sel && sel.rangeCount > 0 ? sel.getRangeAt(0) : null;
            var offset = range ? range.startOffset : 0;

            // Overwrite with uppercase (this clears the selection)
            _writing = true;
            el.textContent = up;
            _writing = false;

            // Restore caret
            if (el.firstChild) {
                var newRange = document.createRange();
                var clampedOffset = Math.min(offset, el.firstChild.length);
                try {
                    newRange.setStart(el.firstChild, clampedOffset);
                    newRange.collapse(true);
                    if (sel) {
                        sel.removeAllRanges();
                        sel.addRange(newRange);
                    }
                } catch (e) { /* ignore range errors on edge cases */ }
            }
        });
    }

    // Apply uppercase enforcement to every field
    Object.values(EL).forEach(enforceUppercase);

    /* ══════════════════════════════════════════════════════════════════
     * RULE ②③ — patientName input
     * ══════════════════════════════════════════════════════════════════ */
    EL.patientName.addEventListener('input', function () {
        if (_writing) return;
        if (txt(EL.patientName).length > 0) {
            activateSec1();
            clearSec2();
        } else {
            deactivateSec1();
        }
    });

    /* ══════════════════════════════════════════════════════════════════
     * RULES ④⑤⑥ — guardianName input
     *
     * • Typing  → activate Sec2, clear Sec1, mirror into nokSigName
     * • Clearing → deactivate Sec2, clear nokSigName
     * • Every keystroke mirrors guardianName into nokSigName (⑥)
     * ══════════════════════════════════════════════════════════════════ */
    EL.guardianName.addEventListener('input', function () {
        if (_writing) return;

        if (txt(EL.guardianName).length > 0) {
            // Sec 2 gaining content
            activateSec2();
            clearSec1();
        } else {
            // Guardian name cleared — deactivate Sec 2
            deactivateSec2();
        }

        // Mirror every keystroke into "Name & Signature of Next of Kin"
        if (!nokIndependent) {
            write(EL.nokSigName, EL.guardianName.textContent);
        }
    });

    // If clerk directly focuses + edits nokSigName, stop mirroring
    EL.nokSigName.addEventListener('focus', function () {
        nokIndependent = true;
    });

    /* ══════════════════════════════════════════════════════════════════
     * RULE ⑦ — beingThe → relationToPatient mirror
     * ══════════════════════════════════════════════════════════════════ */
    EL.beingThe.addEventListener('input', function () {
        if (_writing) return;
        if (!relationIndependent) {
            write(EL.relationToPatient, EL.beingThe.textContent);
        }
    });

    EL.relationToPatient.addEventListener('focus', function () {
        relationIndependent = true;
    });

    /* ══════════════════════════════════════════════════════════════════
     * RULE ⑨ — suppress Enter (no <br> insertion)
     * ══════════════════════════════════════════════════════════════════ */
    document.querySelectorAll('[contenteditable]').forEach(function (el) {
        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); }
        });
    });

    /* ══════════════════════════════════════════════════════════════════
     * RULE ⑩ — click pre-filled field → select-all for easy replacement
     * ══════════════════════════════════════════════════════════════════ */
    document.querySelectorAll('[contenteditable]').forEach(function (el) {
        el.addEventListener('click', function () {
            if (txt(el).length === 0) return;
            var range = document.createRange();
            range.selectNodeContents(el);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        });
    });

    /*
     * Initial state on page load:
     *   Sec 1 active: patientName, doc1, date1 all have content (from PHP).
     *   Sec 2 blank:  guardianName, nokSigName, beingThe, doc2, date2,
     *                 witness2, relationToPatient are all empty.
     * No JS needed at startup — PHP already rendered the correct state.
     */

})();
</script>

</body>
</html>