{{--
    Consent to Care — NUR-002-1
    resources/views/forms/consent-to-care.blade.php
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Consent to Care — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size:8.5in 14in portrait; margin:0.70in 0.80in 0.70in 0.80in; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Times New Roman', Times, serif; font-size:11.5pt; color:#000; background:#c9c9c9; }

        @media screen {
            body  { padding:52px 0 56px; }
            .paper { width:8.5in; min-height:14in; margin:0 auto; background:#fff; box-shadow:0 4px 28px rgba(0,0,0,.30); padding:0.70in 0.80in; }
        }
        @media print {
            body  { background:#fff; padding:0; }
            .paper { width:100%; padding:0; box-shadow:none; }
            .no-print { display:none !important; }
            [contenteditable] { outline:none !important; box-shadow:none !important; background:transparent !important; border-bottom-color:#000 !important; }
        }

        /* ── Toolbar ── */
        .toolbar { position:fixed; top:0; left:0; right:0; height:46px; background:#1e3a5f; color:#fff; font-family:'Segoe UI',system-ui,sans-serif; font-size:12px; display:flex; align-items:center; padding:0 22px; gap:14px; z-index:9999; box-shadow:0 2px 10px rgba(0,0,0,.35); }
        .toolbar .lbl   { font-size:13px; font-weight:700; }
        .toolbar .tag   { background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:3px; padding:2px 9px; font-size:10px; letter-spacing:.05em; text-transform:uppercase; }
        .toolbar .spacer { flex:1; }
        .toolbar .pt-info { font-size:11px; color:rgba(255,255,255,.8); }
        .btn-print{background:#fff;color:#1e3a5f;border:none;padding:6px 16px;border-radius:4px;font-size:12px;font-weight:700;cursor:pointer;font-family:inherit;display:inline-flex;align-items:center;gap:6px;}
.btn-print:hover{background:#dbeafe;}
        .btn-save  { background:#059669; color:#fff; border:none; padding:6px 22px; border-radius:4px; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; }
        .btn-save:hover  { background:#047857; }
        .btn-save:disabled { opacity:.6; cursor:not-allowed; }

        /* ── Header ── */
        .header { display:flex; align-items:center; gap:12px; padding-bottom:10px; border-bottom:2.5px solid #000; }
        .logo-box { width:72px; height:72px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
        .logo-box img { width:72px; height:72px; object-fit:contain; }
        .logo-ph { width:72px; height:72px; flex-shrink:0; border:1.5px dashed #bbb; display:flex; align-items:center; justify-content:center; font-size:7.5pt; color:#bbb; text-align:center; line-height:1.4; }
        .header-center { flex:1; text-align:center; line-height:1.35; }
        .h-rep  { font-size:9pt; letter-spacing:.02em; }
        .h-prov { font-size:10.5pt; font-weight:bold; text-transform:uppercase; letter-spacing:.04em; }
        .h-mun  { font-size:9pt; }
        .h-hosp { font-size:16pt; font-weight:bold; text-transform:uppercase; letter-spacing:.07em; margin-top:3px; }

        /* ── Title ── */
        .title-band { text-align:center; margin:18px 0 24px; }
        .title-band h1 { display:inline-block; font-size:15pt; font-weight:bold; text-transform:uppercase; letter-spacing:.14em; border-bottom:1.5px solid #000; padding-bottom:4px; }

        /* ── Body text ── */
        .body-para { font-size:11.5pt; line-height:1.95; text-align:justify; margin-bottom:16px; }
        .tab { display:inline-block; width:36pt; }

        /* ── Editable fields ── */
        .field {
            display:inline-block; border-bottom:1px solid #000; vertical-align:bottom;
            min-height:18px; line-height:18px; padding:0 4px;
            font-family:'Times New Roman',Times,serif; font-size:11.5pt; color:#000;
            outline:none; cursor:text; white-space:nowrap; overflow:visible;
            text-align:center; text-transform:uppercase;
        }
        @media screen {
            .field:focus { background:#fefce8; border-bottom:2px solid #1d4ed8; }
            .field:hover:not(:focus) { border-bottom-color:#555; }
        }
        .f-sm   { min-width:110px; }
        .f-md   { min-width:190px; }
        .f-lg   { min-width:260px; }
        .f-dr   { min-width:235px; }
        .f-date { min-width:160px; }

        /* ── Locked fields ── */
        .field-locked {
            cursor:default !important;
            pointer-events:none !important;
        }
        @media screen {
            .field-locked:focus,
            .field-locked:hover { background:transparent !important; border-bottom-color:#000 !important; }
        }

        /* ── Signature blocks ── */
        .sig-grid-2  { display:grid; grid-template-columns:1fr 1fr; gap:40px; margin-bottom:28px; }
        .date-right  { display:flex; justify-content:flex-end; margin-bottom:10px; }
        .date-right .sig-block { width:215px; }
        .sig-block   { display:flex; flex-direction:column; }
        .sig-line    { border-bottom:1px solid #000; height:46px; width:100%; display:flex; align-items:flex-end; padding-bottom:3px; }
        .sig-cap     { font-size:9.5pt; text-align:center; font-style:italic; margin-top:3px; line-height:1.3; }

        /* ── Divider ── */
        .divider { border:none; border-top:1.5px solid #000; margin:22px 0 20px; }

        /* ── Note box ── */
        .note-box { border:1px solid #555; padding:9px 14px; font-size:10.5pt; line-height:1.75; text-align:justify; font-style:italic; margin-bottom:22px; background:#fafafa; }
        .note-box strong { font-style:normal; }

        /* ── Screen tip ── */
        .screen-tip { font-family:'Segoe UI',system-ui,sans-serif; font-size:10px; color:#374151; background:#eff6ff; border:1px solid #bfdbfe; border-radius:4px; padding:6px 14px; margin-bottom:14px; line-height:1.6; }
        @media print { .screen-tip { display:none; } }

        /* ── Toast ── */
        #toast { position:fixed; bottom:22px; right:22px; background:#059669; color:#fff; padding:12px 22px; border-radius:8px; font-family:'Segoe UI',sans-serif; font-size:13px; font-weight:600; box-shadow:0 4px 16px rgba(0,0,0,.25); display:none; z-index:99999; }
        #toast.error { background:#dc2626; }

        /* ── Readonly mode ── */
        body.is-readonly .field { cursor:default; pointer-events:none; }
        body.is-readonly .field:focus,
        body.is-readonly .field:hover { background:transparent; border-bottom-color:#000; }
    </style>
</head>
<body class="{{ $readonly ? 'is-readonly' : '' }}">

<div id="toast"></div>

<span id="_patientName"    style="display:none">{{ $patientName ?? '' }}</span>
<span id="_doctorName"     style="display:none">{{ $doctorName ?? '' }}</span>
<span id="_today"          style="display:none">{{ $today ?? '' }}</span>
<span id="_saveRoute"      style="display:none">{{ route('forms.consent-to-care.save', ['visit' => $visit->id]) }}</span>
<span id="_activeSection"  style="display:none">{{ $consent?->active_section ?? 0 }}</span>

{{-- ── Toolbar — always visible, Save button hidden in readonly ── --}}
<div class="toolbar no-print">
    <span class="lbl">Consent to Care</span>
    <span class="tag">NUR-002-1</span>
    <span class="pt-info">{{ $patient->full_name }} &nbsp;·&nbsp; {{ $patient->case_no }}</span>
    <span class="spacer"></span>
    <button class="btn-print" onclick="window.print()"><svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v7H6v-7z" /></svg>Print / Save as PDF</button>
    @if(!$readonly)
    <button id="btnSave" class="btn-save" onclick="saveAndContinue()">Save &amp; Continue</button>
    @endif
</div>

<div class="paper">

    @if(!$readonly)
    <div class="screen-tip no-print">
        💡 Fill <strong>either</strong> Section 1 (patient signs) <em>or</em> Section 2 (guardian signs) — not both.
        Type in the <u>Witness</u> field to activate Section 1, or type the
        <u>Guardian name</u> to activate Section 2. All text is automatically CAPITALISED.
        Click <strong>Save &amp; Continue</strong> when done.
    </div>
    @endif

    {{-- ── Hospital header ── --}}
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
        @else
            <div class="logo-ph">LUMC<br>Logo</div>
        @endif
    </div>

    {{-- ── Form title ── --}}
    <div class="title-band"><h1>Consent to Care</h1></div>

    {{-- ── SECTION 1 — ADULT / PATIENT CONSENT ── --}}
    <p class="body-para">
        <span class="tab"></span>I hereby authorize
        Dr.&nbsp;<span class="field f-dr field-locked" id="doc1" spellcheck="false"
            >{{ $consent?->doctor_name_sec1 ?? ($consent ? '' : $doctorName) }}</span>&nbsp;and the staff of
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
        <div class="sig-block">
            <div class="sig-line">
                <span class="field" id="witness1"
                      contenteditable="{{ $readonly ? 'false' : 'true' }}" spellcheck="false"
                      style="width:100%;border:none;"
                    >{{ $consent?->witness_sec1 ?? '' }}</span>
            </div>
            <div class="sig-cap">Name &amp; Signature of Witness</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">
                <span class="field field-locked" id="patientName" spellcheck="false"
                      style="width:100%;border:none;"
                    >{{ $consent?->patient_name ?? ($consent ? '' : $patientName) }}</span>
            </div>
            <div class="sig-cap">Name &amp; Signature of Patient</div>
        </div>
    </div>

    <div class="date-right">
        <div class="sig-block">
            <div class="sig-line">
                <span class="field f-date field-locked" id="date1" spellcheck="false"
                      style="width:100%;border:none;"
                    >{{ $consent?->signed_date_sec1 ?? ($consent ? '' : $today) }}</span>
            </div>
            <div class="sig-cap">Date</div>
        </div>
    </div>

    <hr class="divider">

    {{-- ── SECTION 2 — GUARDIAN / NEXT OF KIN ── --}}
    <div class="note-box">
        <strong>NOTE:</strong>&nbsp; This AUTHORIZATION MUST be signed by the parents
        or by the nearest of kin of patient if of a minor age or when the patient is
        physically or mentally incompetent or incapacitated.
    </div>

    <p class="body-para">
        <span class="tab"></span>I&nbsp;<span class="field f-lg" id="guardianName"
              contenteditable="{{ $readonly ? 'false' : 'true' }}" spellcheck="false"
            >{{ $consent?->guardian_name ?? '' }}</span>&nbsp;being
        the&nbsp;<span class="field f-sm" id="beingThe"
              contenteditable="{{ $readonly ? 'false' : 'true' }}" spellcheck="false"
            >{{ $consent?->being_the ?? '' }}</span>&nbsp;thereby
        <strong>AUTHORIZE</strong> DR.&nbsp;<span class="field f-dr field-locked" id="doc2" spellcheck="false"
            >{{ $consent?->doctor_name_sec2 ?? '' }}</span>&nbsp;and
        the staff of LUMC to perform any treatment or procedure
        necessary for his / her medical care during admission.
    </p>

    <div class="sig-grid-2">
        <div class="sig-block">
            <div class="sig-line">
                <span class="field" id="witness2"
                      contenteditable="{{ $readonly ? 'false' : 'true' }}" spellcheck="false"
                      style="width:100%;border:none;"
                    >{{ $consent?->witness_sec2 ?? '' }}</span>
            </div>
            <div class="sig-cap">Name &amp; Signature of Witness</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">
                <span class="field" id="nokSigName"
                      contenteditable="{{ $readonly ? 'false' : 'true' }}" spellcheck="false"
                      style="width:100%;border:none;"
                    >{{ $consent?->nok_sig_name ?? '' }}</span>
            </div>
            <div class="sig-cap">Name &amp; Signature of Next of Kin</div>
        </div>
    </div>

    <div class="sig-grid-2">
        <div class="sig-block">
            <div class="sig-line">
                <span class="field f-date field-locked" id="date2" spellcheck="false"
                      style="width:100%;border:none;"
                    >{{ $consent?->signed_date_sec2 ?? '' }}</span>
            </div>
            <div class="sig-cap">Date</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">
                <span class="field" id="relationToPatient"
                      contenteditable="{{ $readonly ? 'false' : 'true' }}" spellcheck="false"
                      style="width:100%;border:none;"
                    >{{ $consent?->relation_to_patient ?? '' }}</span>
            </div>
            <div class="sig-cap">Relation to Patient</div>
        </div>
    </div>

</div>{{-- /.paper --}}

<script>
(function () {
    'use strict';

    var PATIENT        = document.getElementById('_patientName').textContent.trim();
    var DOCTOR         = document.getElementById('_doctorName').textContent.trim();
    var TODAY          = document.getElementById('_today').textContent.trim();
    var SAVE_ROUTE     = document.getElementById('_saveRoute').textContent.trim();
    var SAVED_SECTION  = parseInt(document.getElementById('_activeSection').textContent.trim(), 10);
    var IS_READONLY    = {{ $readonly ? 'true' : 'false' }};

    var EL = {
        witness1         : document.getElementById('witness1'),
        patientName      : document.getElementById('patientName'),
        doc1             : document.getElementById('doc1'),
        date1            : document.getElementById('date1'),
        guardianName     : document.getElementById('guardianName'),
        nokSigName       : document.getElementById('nokSigName'),
        beingThe         : document.getElementById('beingThe'),
        witness2         : document.getElementById('witness2'),
        relationToPatient: document.getElementById('relationToPatient'),
        doc2             : document.getElementById('doc2'),
        date2            : document.getElementById('date2'),
    };

    function txt(el) { return el ? el.textContent.trim() : ''; }

    var _busy = false;
    function write(el, value) {
        if (!el) return;
        _busy = true;
        el.textContent = value;
        _busy = false;
    }

    function activateSec1() {
        if (!txt(EL.patientName)) write(EL.patientName, PATIENT);
        if (!txt(EL.doc1))        write(EL.doc1,        DOCTOR);
        if (!txt(EL.date1))       write(EL.date1,       TODAY);
    }

    function clearSec1() {
        write(EL.witness1,    '');
        write(EL.patientName, '');
        write(EL.doc1,        '');
        write(EL.date1,       '');
    }

    function activateSec2() {
        if (!txt(EL.doc2))  write(EL.doc2,  DOCTOR);
        if (!txt(EL.date2)) write(EL.date2, TODAY);
    }

    function clearSec2() {
        write(EL.guardianName,      '');
        write(EL.nokSigName,        '');
        write(EL.beingThe,          '');
        write(EL.witness2,          '');
        write(EL.relationToPatient, '');
        write(EL.doc2,              '');
        write(EL.date2,             '');
    }

    if (SAVED_SECTION === 2) {
        activateSec2();
    } else {
        activateSec1();
    }

    if (IS_READONLY) return;

    function enforceUppercase(el) {
        if (!el) return;
        el.addEventListener('input', function () {
            if (_busy) return;
            var raw = el.textContent;
            var up  = raw.toUpperCase();
            if (raw === up) return;
            var sel    = window.getSelection();
            var range  = sel && sel.rangeCount > 0 ? sel.getRangeAt(0) : null;
            var offset = range ? range.startOffset : 0;
            _busy = true;
            el.textContent = up;
            _busy = false;
            if (el.firstChild) {
                var nr = document.createRange();
                try {
                    nr.setStart(el.firstChild, Math.min(offset, el.firstChild.length));
                    nr.collapse(true);
                    if (sel) { sel.removeAllRanges(); sel.addRange(nr); }
                } catch (e) {}
            }
        });
    }
    [
        EL.witness1,
        EL.guardianName, EL.nokSigName, EL.beingThe, EL.witness2, EL.relationToPatient
    ].forEach(enforceUppercase);

    EL.witness1.addEventListener('input', function () {
        if (_busy) return;
        if (txt(EL.witness1).length > 0) {
            activateSec1();
            clearSec2();
        } else {
            write(EL.patientName, '');
            write(EL.doc1,        '');
            write(EL.date1,       '');
        }
    });

    function sec2HasContent() {
        return (
            txt(EL.guardianName).length      > 0 ||
            txt(EL.nokSigName).length        > 0 ||
            txt(EL.beingThe).length          > 0 ||
            txt(EL.witness2).length          > 0 ||
            txt(EL.relationToPatient).length > 0
        );
    }

    function onSec2Input() {
        if (_busy) return;
        if (sec2HasContent()) {
            activateSec2();
            clearSec1();
        } else {
            write(EL.doc2,  '');
            write(EL.date2, '');
        }
    }

    [EL.guardianName, EL.nokSigName, EL.beingThe, EL.witness2, EL.relationToPatient]
        .forEach(function (el) { el.addEventListener('input', onSec2Input); });

    var nokIndependent      = false;
    var guardianIndependent = false;
    var relIndependent      = false;
    var beingIndependent    = false;

    EL.guardianName.addEventListener('input', function () {
        if (_busy) return;
        if (!nokIndependent) write(EL.nokSigName, EL.guardianName.textContent);
        if (nokIndependent)  guardianIndependent = true;
    });
    EL.nokSigName.addEventListener('input', function () {
        if (_busy) return;
        nokIndependent = true;
        if (!guardianIndependent) write(EL.guardianName, EL.nokSigName.textContent);
    });

    EL.beingThe.addEventListener('input', function () {
        if (_busy) return;
        if (!relIndependent) write(EL.relationToPatient, EL.beingThe.textContent);
        if (relIndependent)  beingIndependent = true;
    });
    EL.relationToPatient.addEventListener('input', function () {
        if (_busy) return;
        relIndependent = true;
        if (!beingIndependent) write(EL.beingThe, EL.relationToPatient.textContent);
    });

    document.querySelectorAll('[contenteditable]').forEach(function (el) {
        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') e.preventDefault();
        });
    });

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

    function getActiveSection() {
        return sec2HasContent() ? 2 : 1;
    }

    window.saveAndContinue = async function () {
        var btn = document.getElementById('btnSave');
        if (btn) { btn.disabled = true; btn.textContent = 'Saving…'; }

        var section = getActiveSection();
        var payload = {
            active_section      : section,
            patient_name        : txt(EL.patientName),
            doctor_name_sec1    : txt(EL.doc1),
            witness_sec1        : txt(EL.witness1),
            signed_date_sec1    : txt(EL.date1),
            guardian_name       : txt(EL.guardianName),
            nok_sig_name        : txt(EL.nokSigName),
            being_the           : txt(EL.beingThe),
            doctor_name_sec2    : txt(EL.doc2),
            witness_sec2        : txt(EL.witness2),
            signed_date_sec2    : txt(EL.date2),
            relation_to_patient : txt(EL.relationToPatient),
        };

        try {
            var res  = await fetch(SAVE_ROUTE, {
                method : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept'      : 'application/json',
                },
                body: JSON.stringify(payload),
            });
            var json = await res.json();

            if (json.success) {
                showToast('✔ Consent to Care saved — advancing…');
                if (btn) btn.textContent = '✔ Saved';
                window.parent.postMessage({ type: 'consentSaved' }, '*');
            } else {
                showToast('Save failed: ' + (json.message ?? 'Unknown error'), true);
                if (btn) { btn.disabled = false; btn.textContent = 'Save & Continue'; }
            }
        } catch (e) {
            showToast('Network error — check connection.', true);
            if (btn) { btn.disabled = false; btn.textContent = 'Save & Continue'; }
        }
    };

    function showToast(msg, isError) {
        var t = document.getElementById('toast');
        t.textContent   = msg;
        t.className     = isError ? 'error' : '';
        t.style.display = 'block';
        setTimeout(function () { t.style.display = 'none'; }, 4500);
    }

})();
</script>
</body>
</html>