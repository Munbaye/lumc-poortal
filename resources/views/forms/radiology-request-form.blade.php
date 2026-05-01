{{--
    Radiology Request Form
    Non-editable fields:
      - Requesting Physician (auto-filled from doctor session)
      - Radiologist Interpretation / Findings (filled by tech after exam — read-only placeholder)
      - Footer: Date Requested, Request Received, Exam Started, Exam Done
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Radiology Request {{ $requestNo }} — LA UNION MEDICAL CENTER</title>
    <style>
        @page { size: 8.5in 14in portrait; margin: 0.55in 0.65in 0.55in 0.65in; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; background: #c9c9c9; }
        @media screen { body { padding: 52px 0 56px; } .paper { width: 8.5in; min-height: 14in; margin: 0 auto; background: #fff; box-shadow: 0 4px 28px rgba(0,0,0,.28); padding: 0.55in 0.65in; } }
        @media print { body { background: #fff; padding: 0; } .paper { width: 100%; padding: 0; box-shadow: none; } .no-print { display: none !important; } input, select, textarea { border-color: #000 !important; background: transparent !important; outline: none !important; -webkit-print-color-adjust: exact; } }

        .toolbar { position: fixed; top: 0; left: 0; right: 0; height: 46px; background: #1e3a5f; color: #fff; font-family: 'Segoe UI', system-ui, sans-serif; font-size: 12px; display: flex; align-items: center; padding: 0 22px; gap: 14px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,.35); }
        .toolbar .lbl { font-size: 13px; font-weight: 700; } .toolbar .tag { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); border-radius: 3px; padding: 2px 9px; font-size: 10px; letter-spacing: .05em; text-transform: uppercase; }
        .toolbar .tag-no { background: rgba(16,185,129,.25); border-color: rgba(16,185,129,.5); font-family: monospace; font-size: 11px; font-weight: 700; }
        .toolbar .hint { opacity: .5; font-size: 11px; } .toolbar .spacer { flex: 1; }
        .btn-print { background: #fff; color: #1e3a5f; border: none; padding: 6px 18px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; } .btn-print:hover { background: #dbeafe; }
        .btn-submit { background: #059669; color: #fff; border: none; padding: 6px 18px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; } .btn-submit:hover { background: #047857; } .btn-submit:disabled { opacity: .6; cursor: not-allowed; }

        .header { display: flex; align-items: center; gap: 14px; padding-bottom: 8px; border-bottom: 2.5px solid #000; }
        .logo-box { width: 64px; height: 64px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .logo-box img { width: 64px; height: 64px; object-fit: contain; }
        .logo-ph { width: 64px; height: 64px; flex-shrink: 0; border: 1.5px dashed #bbb; display: flex; align-items: center; justify-content: center; font-size: 7pt; color: #bbb; text-align: center; line-height: 1.3; }
        .header-center { flex: 1; text-align: center; line-height: 1.3; }
        .header-center .h-name { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; }
        .header-center .h-sub  { font-size: 10pt; font-weight: bold; margin-top: 4px; }
        .header-center .h-addr { font-size: 8pt; color: #444; margin-top: 2px; }

        .divider { border: none; border-top: 1px solid #000; margin: 8px 0; }
        .divider-thick { border: none; border-top: 2px solid #000; margin: 8px 0; }
        .fl { font-size: 8pt; text-transform: uppercase; letter-spacing: .05em; color: #555; display: block; margin-bottom: 1px; }

        /* Editable field */
        .fi { width: 100%; border: none; border-bottom: 1px solid #999; outline: none; font-family: 'Times New Roman', Times, serif; font-size: 10.5pt; padding: 2px 3px; background: transparent; color: #000; }
        .fi:focus { background: #fefce8; border-bottom-color: #1d4ed8; }

        /* Read-only field — same visual, non-interactive */
        .fi-ro { width: 100%; border: none; border-bottom: 1px solid #999; padding: 2px 3px; background: transparent; font-family: 'Times New Roman', Times, serif; font-size: 10.5pt; color: #000; display: block; min-height: 22px; cursor: default; user-select: none; }

        .fg { margin-bottom: 7px; }
        .g3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
        .g4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px; }

        .modality-row { display: flex; align-items: center; justify-content: space-around; border: 1.5px solid #000; padding: 9px 24px; margin-bottom: 8px; }
        .modality-row label { display: inline-flex; align-items: center; gap: 7px; font-size: 12pt; font-weight: bold; cursor: pointer; }
        .modality-row input[type="radio"] { width: 15px; height: 15px; accent-color: #000; cursor: pointer; }

        .source-row { display: flex; align-items: center; justify-content: space-around; border: 1px solid #ddd; background: #fafafa; padding: 6px 10px; margin-bottom: 8px; }
        .source-row label { display: inline-flex; align-items: center; gap: 5px; font-size: 10pt; font-weight: 600; cursor: pointer; }
        .source-row input[type="radio"] { width: 13px; height: 13px; accent-color: #000; cursor: pointer; }

        .area { width: 100%; border: 1px solid #999; outline: none; font-family: 'Times New Roman', Times, serif; font-size: 10.5pt; padding: 5px 7px; resize: vertical; background: transparent; line-height: 1.65; }
        .area:focus { background: #fefce8; border-color: #1d4ed8; }

        /* Read-only area — mimics a textarea but non-editable */
        .area-ro { width: 100%; border: 1.5px solid #ccc; padding: 5px 7px; font-family: 'Times New Roman', Times, serif; font-size: 10.5pt; line-height: 1.65; min-height: 170px; background: #f9f9f9; color: #888; cursor: default; user-select: none; font-style: italic; }

        .sec-label { font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; color: #444; margin-bottom: 5px; }
        .sig-line { border-bottom: 1px solid #000; height: 36px; }
        .sig-cap  { font-size: 8.5pt; text-align: center; font-style: italic; margin-top: 2px; }

        .screen-tip { font-family: 'Segoe UI', system-ui, sans-serif; font-size: 10px; color: #374151; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 6px 14px; margin-bottom: 12px; line-height: 1.6; }
        @media print { .screen-tip { display: none; } }

        #toast { position: fixed; bottom: 22px; right: 22px; background: #059669; color: #fff; padding: 12px 22px; border-radius: 8px; font-family: 'Segoe UI', system-ui, sans-serif; font-size: 13px; font-weight: 600; box-shadow: 0 4px 16px rgba(0,0,0,.25); display: none; z-index: 99999; }
        #toast.error { background: #dc2626; }
    </style>
</head>
<body>

<div id="toast"></div>

<div class="toolbar no-print">
    <span class="lbl">LUMC · Radiology Request</span>
    <span class="tag">RAD</span>
    <span class="tag tag-no">{{ $requestNo }}</span>
    @isset($patient)<span class="tag" style="background:rgba(16,185,129,.22);border-color:rgba(16,185,129,.45);">{{ $patient->case_no }}</span>@endisset
    <span class="hint">Fill in all fields · Submit saves to system · Print produces the hardcopy</span>
    <span class="spacer"></span>
    <button id="btnSubmit" class="btn-submit" onclick="submitForm()">✔ Submit Request</button>
    &nbsp;
    <button class="btn-print" onclick="window.print()">🖨️ Print / PDF</button>
</div>

<div class="paper">

    <div class="screen-tip no-print">
        💡 Pre-filled fields can be edited. Click <strong>Submit Request</strong> to save, then <strong>Print / PDF</strong> for the hardcopy.
    </div>

    <div class="header">
        @if(file_exists(public_path('images/lumc-logo.png')))
            <div class="logo-box"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC"></div>
        @else
            <div class="logo-ph">LUMC<br>Logo</div>
        @endif
        <div class="header-center">
            <div class="h-name">La Union Medical Center</div>
            <div class="h-sub">Radiology Request Form</div>
            <div class="h-addr">Brgy. Nazareno, Agoo, La Union &nbsp;·&nbsp; (072) 607-5541</div>
        </div>
        @if(file_exists(public_path('images/province-logo.png')))
            <div class="logo-box"><img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union"></div>
        @else
            <div class="logo-ph">Province<br>Seal</div>
        @endif
    </div>

    <div class="divider-thick" style="margin-top:10px;"></div>

    <div class="modality-row">
        <label><input type="radio" name="modality" value="X-RAY"      id="mod_xray"> &nbsp;X-RAY</label>
        <label><input type="radio" name="modality" value="ULTRASOUND" id="mod_us">   &nbsp;ULTRASOUND</label>
        <label><input type="radio" name="modality" value="CT SCAN"    id="mod_ct">   &nbsp;CT SCAN</label>
    </div>

    <div class="g4" style="margin-bottom:7px;">
        <div class="fg"><span class="fl">Date</span><input type="date" id="f_date" class="fi" value="{{ $today ?? '' }}"></div>
        <div class="fg"><span class="fl">RAD File No.</span><input type="text" id="f_rad_file" class="fi" value="{{ $requestNo }}" style="font-family:monospace;font-weight:bold;"></div>
        <div class="fg"><span class="fl">Hospital No. (Case No.)</span><input type="text" id="f_hospital_no" class="fi" value="{{ $hospitalNo ?? '' }}"></div>
        <div class="fg"><span class="fl">Service / Ward</span><input type="text" id="f_ward" class="fi" value="{{ $ward ?? '' }}"></div>
    </div>

    @php $pc = strtoupper($paymentClass ?? ''); @endphp
    <div class="source-row">
        <label><input type="radio" name="source" id="src_opd"     value="OPD"     {{ ($visit->visit_type ?? '') === 'OPD'     ? 'checked':'' }}> &nbsp;OPD</label>
        <label><input type="radio" name="source" id="src_er"      value="ER"      {{ ($visit->visit_type ?? '') === 'ER'      ? 'checked':'' }}> &nbsp;ER</label>
        <label><input type="radio" name="source" id="src_private" value="PRIVATE" {{ $pc === 'PRIVATE'                       ? 'checked':'' }}> &nbsp;PRIVATE</label>
        <label><input type="radio" name="source" id="src_phic"    value="PHIC"> &nbsp;PHIC</label>
        <label><input type="radio" name="source" id="src_charity" value="CHARITY" {{ $pc === 'CHARITY'                       ? 'checked':'' }}> &nbsp;CHARITY / INDIGENT</label>
    </div>

    <div class="sec-label">Patient Name</div>
    <div class="g3" style="margin-bottom:7px;">
        <div class="fg"><span class="fl">Family Name</span><input type="text" id="f_family" class="fi" value="{{ $familyName ?? '' }}"></div>
        <div class="fg"><span class="fl">Given Name</span><input type="text" id="f_first" class="fi" value="{{ $firstName ?? '' }}"></div>
        <div class="fg"><span class="fl">Middle Name</span><input type="text" id="f_middle" class="fi" value="{{ $middleName ?? '' }}"></div>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:10px;margin-bottom:8px;">
        <div class="fg"><span class="fl">Address</span><input type="text" id="f_address" class="fi" value="{{ $address ?? '' }}"></div>
        <div class="fg"><span class="fl">Date of Birth</span><input type="date" id="f_dob" class="fi" value="{{ $dateOfBirth ?? '' }}"></div>
        <div class="fg"><span class="fl">Age</span><input type="text" id="f_age" class="fi" value="{{ $age ?? '' }}"></div>
        <div class="fg">
            <span class="fl">Sex</span>
            <select id="f_sex" class="fi" style="border:none;border-bottom:1px solid #999;">
                <option value="">—</option>
                <option {{ ($sex ?? '') === 'Male'   ? 'selected':'' }}>Male</option>
                <option {{ ($sex ?? '') === 'Female' ? 'selected':'' }}>Female</option>
            </select>
        </div>
    </div>

    <div class="divider"></div>

    <div class="fg" style="margin-bottom:7px;"><span class="fl">Examination Desired</span><textarea id="f_exam" rows="3" class="area" placeholder="Specify exam — e.g., Chest X-Ray PA, Whole Abdomen Ultrasound, CT Brain non-contrast…"></textarea></div>
    <div class="fg" style="margin-bottom:7px;"><span class="fl">Clinical Diagnosis</span><textarea id="f_clin_diag" rows="2" class="area">{{ $clinicalDiagnosis ?? '' }}</textarea></div>
    <div class="fg" style="margin-bottom:9px;"><span class="fl">Pertinent / Brief Clinical Findings</span><textarea id="f_findings" rows="3" class="area" placeholder="Relevant history, symptoms, and clinical findings…"></textarea></div>

    <div class="divider"></div>

    {{-- ── REQUESTING PHYSICIAN — read-only (auto-filled) ── --}}
    <div style="margin-top:10px;margin-bottom:12px;">
        <div class="sec-label">Requesting Physician</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:end;">
            <div class="fg" style="margin-bottom:0;">
                <span class="fl">Name</span>
                {{-- NON-EDITABLE: shown as bold text with same underline visual --}}
                <div class="fi-ro" style="font-size:11pt;font-weight:bold;">{{ $requestingPhysician ?? '—' }}</div>
            </div>
            <div>
                <div class="sig-line"></div>
                <div class="sig-cap">Signature over Printed Name / PRC No.</div>
            </div>
        </div>
    </div>

    {{-- ── RADIOLOGIST INTERPRETATION — read-only placeholder ── --}}
    <div>
        <div class="sec-label">Radiologist Interpretation / Findings</div>
        {{-- NON-EDITABLE: The tech fills this after the exam via the tech dashboard.
             On the doctor's form it is shown as a blank (grayed) area so it prints
             as a blank space for the radiologist to write on the hardcopy. --}}
        <div class="area-ro">To be completed by the radiologist / tech after the exam.</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:6px;">
            <div><div class="sig-line" style="margin-top:24px;"></div><div class="sig-cap">Radiologist — Signature / PRC No.</div></div>
            <div><div class="sig-line" style="margin-top:24px;"></div><div class="sig-cap">Date &amp; Time Reported</div></div>
        </div>
    </div>

    <div class="divider" style="margin-top:12px;"></div>

    {{-- ── FOOTER TIMESTAMPS — ALL NON-EDITABLE ── --}}
    {{--   Date Requested → auto-filled (shown as text)
           Request Received, Exam Started, Exam Done → filled by tech; blank lines here  --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:6px;">
        <div class="fg">
            <span class="fl">Date Requested</span>
            <div class="fi-ro">{{ $today ?? '' }}</div>
        </div>
        <div class="fg">
            <span class="fl">Request Received</span>
            <div class="fi-ro">&nbsp;</div>
        </div>
        <div class="fg">
            <span class="fl">Exam Started</span>
            <div class="fi-ro">&nbsp;</div>
        </div>
        <div class="fg">
            <span class="fl">Exam Done</span>
            <div class="fi-ro">&nbsp;</div>
        </div>
    </div>

</div>

<script>
async function submitForm() {
    const btn = document.getElementById('btnSubmit'); btn.disabled = true; btn.textContent = 'Saving…';
    const modality = document.querySelector('input[name="modality"]:checked')?.value ?? '';
    const source   = document.querySelector('input[name="source"]:checked')?.value ?? '';
    const payload = {
        request_no: document.getElementById('f_rad_file').value.trim(), modality, source,
        ward: document.getElementById('f_ward').value.trim(),
        examination_desired: document.getElementById('f_exam').value.trim(),
        clinical_diagnosis: document.getElementById('f_clin_diag').value.trim(),
        clinical_findings: document.getElementById('f_findings').value.trim(),
        radiologist_interpretation: '',   // not filled by doctor
        requesting_physician: '{{ $requestingPhysician ?? '' }}',
        date_requested: document.getElementById('f_date').value,
    };
    try {
        const res = await fetch('{{ route("forms.radiology-request.store", ["visit" => $visit->id]) }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }, body: JSON.stringify(payload) });
        const json = await res.json();
        if (json.success) { showToast('✔ ' + json.message, false); btn.textContent = '✔ Saved'; } else { showToast('⚠ Save failed. Please try again.', true); btn.disabled = false; btn.textContent = '✔ Submit Request'; }
    } catch (err) { showToast('⚠ Network error — check connection.', true); btn.disabled = false; btn.textContent = '✔ Submit Request'; }
}
function showToast(msg, isError) { const t = document.getElementById('toast'); t.textContent = msg; t.className = isError ? 'error' : ''; t.style.display = 'block'; setTimeout(() => { t.style.display = 'none'; }, 4000); }
</script>
</body>
</html>