@php
use App\Helpers\WHOGrowthChart;
@endphp


<x-filament-panels::page>

<style>
/* ══════════════════════════════════════════════════════════════════
   NURSE CHART STYLES
   ══════════════════════════════════════════════════════════════════ */

.chart-page { display:flex; flex-direction:column; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; background:#fff; }
.dark .chart-page { background:#111827; border-color:#374151; }

.chart-header { background:linear-gradient(135deg,#881337 0%,#f43f5e 100%); padding:16px 24px; display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap; }
.chart-header-left { flex:1; min-width:200px; }
.pt-name-big { font-size:1.1rem; font-weight:800; color:#fff; letter-spacing:.02em; }
.pt-case-big { font-family:monospace; font-size:.78rem; color:#fda4af; margin-top:2px; }
.header-pills { display:flex; flex-wrap:wrap; gap:10px; align-items:center; }
.h-pill { background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.22); border-radius:6px; padding:5px 14px; text-align:center; }
.h-pill .pl { font-size:.6rem; text-transform:uppercase; letter-spacing:.06em; color:#fda4af; }
.h-pill .pv { font-size:.82rem; font-weight:700; color:#fff; }
.svc-pill { background:#be123c; color:#fff; font-size:.72rem; font-weight:700; padding:4px 14px; border-radius:9999px; }
.btn-back-hdr { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.3); color:#fff; font-size:.78rem; font-weight:600; padding:7px 14px; border-radius:6px; text-decoration:none; flex-shrink:0; cursor:pointer; }
.btn-back-hdr:hover { background:rgba(255,255,255,.25); }

.chart-tabs { display:flex; border-bottom:2px solid #e5e7eb; background:#fff; padding:0 16px; overflow-x:auto; }
.dark .chart-tabs { background:#1f2937; border-bottom-color:#374151; }
.chart-tab { display:inline-flex; align-items:center; gap:6px; padding:11px 14px; font-size:.8rem; font-weight:600; color:#6b7280; cursor:pointer; border:none; background:none; border-bottom:2.5px solid transparent; margin-bottom:-2px; white-space:nowrap; transition:color .15s,border-color .15s; }
.chart-tab:hover { color:#374151; }
.dark .chart-tab { color:#9ca3af; }
.chart-tab.active { color:#f43f5e; border-bottom-color:#f43f5e; font-weight:700; }
.dark .chart-tab.active { color:#fb7185; border-bottom-color:#fb7185; }
.tab-badge { background:#ef4444; color:#fff; font-size:.62rem; font-weight:700; padding:1px 5px; border-radius:9999px; min-width:17px; text-align:center; }
.tab-badge-green { background:#059669; }
.tab-badge-blue  { background:#2563eb; }
.tab-badge-teal  { background:#0d9488; }

.chart-content { padding:22px 26px; background:#f9fafb; min-height:480px; }
.dark .chart-content { background:#111827; }

.sec-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid #e5e7eb; }
.dark .sec-head { border-bottom-color:#374151; }
.sec-title { font-size:.95rem; font-weight:700; color:#111827; }
.dark .sec-title { color:#f3f4f6; }

/* ── Orders ──────────────────────────────────────────────────────── */
.order-group-wrap { margin-bottom:24px; }
.order-group-hdr { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
.order-group-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#6b7280; white-space:nowrap; }
.order-group-line { flex:1; border-top:1px solid #e5e7eb; }
.dark .order-group-line { border-top-color:#374151; }
.order-group-doc { font-size:.7rem; color:#9ca3af; white-space:nowrap; }
.order-row { display:grid; grid-template-columns:28px 1fr auto; gap:12px; align-items:start; background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin-bottom:8px; transition:border-color .12s; }
.dark .order-row { background:#1f2937; border-color:#374151; }
.order-row.is-pending { border-left:3px solid #f59e0b; }
.order-row.is-carried { border-left:3px solid #059669; opacity:.8; }
.order-row.is-discontinued { border-left:3px solid #dc2626; opacity:.6; }
.order-num-circle { width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.68rem; font-weight:800; flex-shrink:0; margin-top:1px; }
.order-num-pending { background:#fef3c7; color:#92400e; }
.order-num-carried { background:#d1fae5; color:#065f46; }
.order-num-discontinued { background:#fee2e2; color:#991b1b; }
.order-body { flex:1; min-width:0; }
.order-text-main { font-size:.9rem; color:#111827; font-weight:500; line-height:1.5; word-break:break-word; }
.dark .order-text-main { color:#f3f4f6; }
.order-text-main.struck { text-decoration:line-through; opacity:.6; }
.order-carry-meta { font-size:.7rem; color:#059669; margin-top:4px; font-style:italic; }
.order-disc-meta { font-size:.7rem; color:#dc2626; margin-top:4px; font-style:italic; }
.order-written-meta { font-size:.7rem; color:#9ca3af; margin-top:3px; }
.carry-action { display:flex; flex-direction:column; align-items:flex-end; gap:5px; flex-shrink:0; }
.status-badge { display:inline-block; padding:2px 10px; border-radius:9999px; font-size:.68rem; font-weight:700; white-space:nowrap; }
.status-pending { background:#fef3c7; color:#92400e; }
.status-carried { background:#d1fae5; color:#065f46; }
.status-discontinued { background:#fee2e2; color:#991b1b; }
.btn-carry { background:#059669; color:#fff; border:none; border-radius:6px; padding:6px 14px; font-size:.78rem; font-weight:700; cursor:pointer; }
.btn-carry:hover { background:#047857; }
.btn-carry-confirm { background:#dc2626; color:#fff; border:none; border-radius:6px; padding:6px 14px; font-size:.78rem; font-weight:700; cursor:pointer; }
.btn-cancel-sm { background:#e5e7eb; color:#374151; border:none; border-radius:6px; padding:6px 10px; font-size:.75rem; cursor:pointer; }
.dark .btn-cancel-sm { background:#374151; color:#e5e7eb; }
.mark-all-banner { background:#f0fdf4; border:1.5px solid #86efac; border-radius:8px; padding:12px 16px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.dark .mark-all-banner { background:#022c22; border-color:#16a34a; }
.mark-all-text { font-size:.82rem; color:#15803d; }
.dark .mark-all-text { color:#4ade80; }
.btn-mark-all { background:#059669; color:#fff; border:none; border-radius:7px; padding:8px 18px; font-size:.83rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.btn-mark-all:hover { background:#047857; }

/* ── FDAR Notes ──────────────────────────────────────────────────── */
.soap-form { background:#fff; border:1.5px solid #f43f5e; border-radius:8px; padding:18px 20px; margin-bottom:20px; }
.dark .soap-form { background:#1f2937; border-color:#be123c; }
.soap-form-title { font-size:.85rem; font-weight:700; color:#be123c; margin-bottom:14px; padding-bottom:8px; border-bottom:1px solid #ffe4e6; }
.soap-row { display:grid; grid-template-columns:80px 1fr; gap:10px; margin-bottom:10px; align-items:start; }
.soap-letter { font-size:1.1rem; font-weight:900; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
.soap-f { background:#dbeafe; color:#1e40af; }
.soap-d { background:#dcfce7; color:#166534; }
.soap-a { background:#fef9c3; color:#854d0e; }
.soap-r { background:#ede9fe; color:#5b21b6; }
.soap-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; display:block; margin-bottom:3px; }
.soap-textarea { width:100%; border:1px solid #e5e7eb; border-radius:6px; padding:8px 10px; font-size:.85rem; resize:vertical; font-family:inherit; color:#111827; background:#fff; outline:none; line-height:1.6; }
.dark .soap-textarea { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.soap-textarea:focus { border-color:#f43f5e; box-shadow:0 0 0 2px rgba(244,63,94,.12); }
.note-card { background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:14px 16px; margin-bottom:10px; }
.dark .note-card { background:#1f2937; border-color:#374151; }
.note-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; padding-bottom:8px; border-bottom:1px solid #f3f4f6; }
.dark .note-header { border-bottom-color:#374151; }
.note-nurse { font-size:.82rem; font-weight:700; color:#374151; }
.dark .note-nurse { color:#e5e7eb; }
.note-time { font-size:.72rem; color:#9ca3af; }
.note-soap-row { display:grid; grid-template-columns:28px 1fr; gap:8px; margin-bottom:7px; align-items:start; }
.note-soap-letter { font-size:.75rem; font-weight:900; width:22px; height:22px; border-radius:5px; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; }
.note-soap-text { font-size:.83rem; color:#374151; line-height:1.55; }
.dark .note-soap-text { color:#d1d5db; }
.note-soap-label { font-size:.65rem; font-weight:700; text-transform:uppercase; color:#9ca3af; }

/* ══════════════════════════════════════════════════════════════════
   VITAL SIGNS MONITORING SHEET
   ══════════════════════════════════════════════════════════════════ */

.vs-entry-form { background:#fff; border:1.5px solid #2563eb; border-radius:10px; padding:18px 20px; margin-bottom:20px; }
.dark .vs-entry-form { background:#1f2937; border-color:#1d4ed8; }
.vs-form-title { font-size:.85rem; font-weight:700; color:#1d4ed8; margin-bottom:14px; padding-bottom:8px; border-bottom:1px solid #dbeafe; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.dark .vs-form-title { border-bottom-color:#1e3a5f; }

.vs-single-row { display:grid; grid-template-columns:180px repeat(5,1fr) 1fr 1fr 1fr; gap:10px; align-items:end; margin-bottom:14px; }
@media(max-width:900px) { .vs-single-row { grid-template-columns:repeat(4,1fr); } }

.vs-field { display:flex; flex-direction:column; gap:4px; }
.vs-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; }
.dark .vs-label { color:#9ca3af; }
.vs-input { border:1px solid #d1d5db; border-radius:7px; padding:8px 10px; font-size:.875rem; background:#fff; color:#111827; outline:none; width:100%; }
.dark .vs-input { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.vs-input:focus { border-color:#2563eb; box-shadow:0 0 0 2px rgba(37,99,235,.12); }
.vs-input::placeholder { color:#9ca3af; }
.vs-textarea { border:1px solid #d1d5db; border-radius:7px; padding:8px 10px; font-size:.83rem; background:#fff; color:#111827; outline:none; width:100%; resize:vertical; font-family:inherit; line-height:1.5; }
.dark .vs-textarea { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.vs-textarea:focus { border-color:#2563eb; box-shadow:0 0 0 2px rgba(37,99,235,.12); }
.vs-textarea::placeholder { color:#9ca3af; }
.vs-help { font-size:.67rem; color:#9ca3af; margin-top:1px; }
.vs-select { border:1px solid #d1d5db; border-radius:7px; padding:8px 10px; font-size:.875rem; background:#fff; color:#111827; outline:none; width:100%; }
.dark .vs-select { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.vs-select:focus { border-color:#2563eb; }

/* Table */
.vs-sheet-wrap { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:auto; }
.dark .vs-sheet-wrap { background:#1f2937; border-color:#374151; }
.vs-table { width:100%; border-collapse:collapse; font-size:.8rem; min-width:820px; }
.vs-table thead tr { background:#f3f4f6; }
.dark .vs-table thead tr { background:#111827; }
.vs-table th { padding:9px 10px; text-align:center; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; border-bottom:2px solid #e5e7eb; white-space:nowrap; }
.dark .vs-table th { border-bottom-color:#374151; color:#9ca3af; }
.vs-table th.col-left { text-align:left; min-width:120px; }
.vs-table th.col-wide { min-width:140px; }
.vs-table th.col-narrow { min-width:58px; }
.vs-table td { padding:10px 10px; text-align:center; border-bottom:1px solid #f3f4f6; color:#374151; vertical-align:top; line-height:1.45; }
.dark .vs-table td { border-bottom-color:#1f2937; color:#d1d5db; }
.vs-table td.col-left { text-align:left; }
.vs-table td.col-text { text-align:left; }
.vs-table tr:last-child td { border-bottom:none; }
.vs-table tr:hover td { background:#f9fafb; }
.dark .vs-table tr:hover td { background:rgba(255,255,255,.025); }

/* Registration row tint */
.vs-row-reg td { background:#fffbeb !important; }
.dark .vs-row-reg td { background:rgba(234,179,8,.05) !important; }
.vs-reg-badge { display:inline-block; font-size:.6rem; font-weight:700; padding:1px 6px; border-radius:9999px; background:#fef3c7; color:#92400e; margin-top:3px; }

/* Abnormal highlight */
.vs-abnormal { color:#dc2626 !important; font-weight:700; }
.dark .vs-abnormal { color:#f87171 !important; }

.vs-val { font-size:.88rem; font-weight:600; color:#111827; }
.dark .vs-val { color:#f3f4f6; }
.vs-val-na { color:#d1d5db; font-size:.78rem; }
.dark .vs-val-na { color:#4b5563; }
.vs-nurse-tag { display:inline-flex; align-items:center; gap:3px; font-size:.67rem; color:#6b7280; margin-top:3px; }

/* ══════════════════════════════════════════════════════════════════
   IV FLUID / BLOOD TRANSFUSION SHEET
   ══════════════════════════════════════════════════════════════════ */

/* Form card */
.iv-entry-form {
    background: #fff;
    border: 1.5px solid #0d9488;
    border-radius: 10px;
    padding: 18px 20px;
    margin-bottom: 20px;
}
.dark .iv-entry-form { background:#1f2937; border-color:#0f766e; }

.iv-form-title {
    font-size: .85rem;
    font-weight: 700;
    color: #0f766e;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 1px solid #ccfbf1;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.dark .iv-form-title { border-bottom-color:#134e4a; }

/* Form grid — add mode */
.iv-form-grid {
    display: grid;
    grid-template-columns: 160px 120px 100px 1fr 200px 1fr;
    gap: 12px;
    align-items: end;
    margin-bottom: 14px;
}
@media(max-width:1100px) {
    .iv-form-grid { grid-template-columns: repeat(3,1fr); }
}
@media(max-width:700px) {
    .iv-form-grid { grid-template-columns: 1fr 1fr; }
}

/* Edit form grid — narrower, only editable cols */
.iv-edit-grid {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 12px;
    align-items: end;
    margin-bottom: 14px;
}
@media(max-width:700px) {
    .iv-edit-grid { grid-template-columns: 1fr; }
}

.iv-field { display:flex; flex-direction:column; gap:4px; }
.iv-label {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #6b7280;
}
.dark .iv-label { color:#9ca3af; }

.iv-input {
    border: 1px solid #d1d5db;
    border-radius: 7px;
    padding: 8px 10px;
    font-size: .875rem;
    background: #fff;
    color: #111827;
    outline: none;
    width: 100%;
    font-family: inherit;
}
.dark .iv-input { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.iv-input:focus { border-color:#0d9488; box-shadow:0 0 0 2px rgba(13,148,136,.12); }
.iv-input::placeholder { color:#9ca3af; }

.iv-input[readonly] {
    background: #f9fafb;
    color: #6b7280;
    cursor: not-allowed;
}
.dark .iv-input[readonly] { background:#111827; color:#4b5563; }

.iv-textarea {
    border: 1px solid #d1d5db;
    border-radius: 7px;
    padding: 8px 10px;
    font-size: .83rem;
    background: #fff;
    color: #111827;
    outline: none;
    width: 100%;
    resize: vertical;
    font-family: inherit;
    line-height: 1.5;
    min-height: 60px;
}
.dark .iv-textarea { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.iv-textarea:focus { border-color:#0d9488; box-shadow:0 0 0 2px rgba(13,148,136,.12); }
.iv-textarea::placeholder { color:#9ca3af; }

.iv-help {
    font-size: .67rem;
    color: #9ca3af;
    margin-top: 1px;
    line-height: 1.4;
}

/* Table */
.iv-sheet-wrap {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: auto;
}
.dark .iv-sheet-wrap { background:#1f2937; border-color:#374151; }

.iv-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .8rem;
    min-width: 900px;
}
.iv-table thead tr { background:#f0fdfa; }
.dark .iv-table thead tr { background:#042f2e; }

.iv-table th {
    padding: 10px 12px;
    text-align: left;
    font-size: .67rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #0f766e;
    border-bottom: 2px solid #99f6e4;
    white-space: nowrap;
}
.dark .iv-table th { color:#5eead4; border-bottom-color:#134e4a; }

.iv-table th.col-center { text-align:center; }
.iv-table th.col-narrow { min-width:80px; }
.iv-table th.col-medium { min-width:120px; }
.iv-table th.col-wide   { min-width:180px; }
.iv-table th.col-action { min-width:90px; text-align:center; }

.iv-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #f0fdfa;
    color: #374151;
    vertical-align: top;
    line-height: 1.5;
}
.dark .iv-table td { border-bottom-color:#042f2e; color:#d1d5db; }
.iv-table tr:last-child td { border-bottom: none; }
.iv-table tr:hover td { background:#f0fdfa; }
.dark .iv-table tr:hover td { background:rgba(13,148,136,.04); }

.iv-table td.col-center { text-align:center; }

/* Bottle number badge */
.iv-bottle-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #ccfbf1;
    color: #0f766e;
    font-size: .8rem;
    font-weight: 800;
}
.dark .iv-bottle-badge { background:#134e4a; color:#5eead4; }

/* Solution chip */
.iv-solution-text {
    font-size: .85rem;
    font-weight: 600;
    color: #111827;
    line-height: 1.5;
}
.dark .iv-solution-text { color:#f3f4f6; }

/* Consumed cell */
.iv-consumed-yes { font-size:.82rem; color:#059669; font-weight:600; }
.iv-consumed-no  { font-size:.75rem; color:#d1d5db; font-style:italic; }
.dark .iv-consumed-no { color:#374151; }

/* Nurse sig */
.iv-nurse-sig {
    font-size: .75rem;
    color: #374151;
    font-style: italic;
}
.dark .iv-nurse-sig { color:#9ca3af; }
.iv-edit-meta {
    font-size: .65rem;
    color: #9ca3af;
    margin-top: 3px;
}

/* Edit inline row */
.iv-table tr.is-editing td { background:#f0fdfa !important; }
.dark .iv-table tr.is-editing td { background:rgba(13,148,136,.06) !important; }
.iv-inline-edit { display:flex; flex-direction:column; gap:6px; }

/* Buttons */
.btn-add-iv {
    background: #0d9488;
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 9px 18px;
    font-size: .83rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-add-iv:hover { background:#0f766e; }

.btn-primary-teal {
    background: #0d9488;
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 8px 20px;
    font-size: .83rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.btn-primary-teal:hover { background:#0f766e; }
.btn-primary-teal:disabled { opacity:.6; cursor:not-allowed; }

.btn-edit-iv {
    background: #f0fdfa;
    color: #0f766e;
    border: 1px solid #99f6e4;
    border-radius: 6px;
    padding: 5px 12px;
    font-size: .75rem;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}
.btn-edit-iv:hover { background:#ccfbf1; }
.dark .btn-edit-iv { background:#134e4a; color:#5eead4; border-color:#0f766e; }
.dark .btn-edit-iv:hover { background:#0f766e; }

/* General shared buttons */
.btn-add-vital { background:#2563eb; color:#fff; border:none; border-radius:7px; padding:9px 18px; font-size:.83rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.btn-add-vital:hover { background:#1d4ed8; }
.btn-primary-blue { background:#2563eb; color:#fff; border:none; border-radius:7px; padding:9px 22px; font-size:.85rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.btn-primary-blue:hover { background:#1d4ed8; }
.btn-primary { background:#f43f5e; color:#fff; border:none; border-radius:7px; padding:9px 22px; font-size:.85rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.btn-primary:hover { background:#e11d48; }
.btn-secondary { background:#fff; color:#374151; border:1px solid #e5e7eb; border-radius:7px; padding:9px 18px; font-size:.85rem; font-weight:600; cursor:pointer; }
.dark .btn-secondary { background:#374151; color:#e5e7eb; border-color:#4b5563; }
.btn-secondary:hover { background:#f3f4f6; }
.btn-add-note { background:#f43f5e; color:#fff; border:none; border-radius:7px; padding:9px 18px; font-size:.83rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.btn-add-note:hover { background:#e11d48; }
.btn-add-note.is-cancel { background:#6b7280; }
.btn-add-note.is-cancel:hover { background:#4b5563; }

.empty-state { text-align:center; padding:48px 20px; background:#fff; border:1.5px dashed #e5e7eb; border-radius:8px; }
.dark .empty-state { background:#1f2937; border-color:#374151; }
.empty-icon { font-size:2.5rem; margin-bottom:9px; }
.empty-title { font-size:.9rem; font-weight:700; color:#374151; margin-bottom:3px; }
.dark .empty-title { color:#e5e7eb; }
.empty-sub { font-size:.78rem; color:#9ca3af; }
.placeholder-card { background:#fff; border:1.5px dashed #e5e7eb; border-radius:10px; padding:32px 20px; text-align:center; }
.dark .placeholder-card { background:#1f2937; border-color:#374151; }
.ph-icon { font-size:2.4rem; margin-bottom:10px; }
.ph-title { font-size:.92rem; font-weight:700; color:#374151; margin-bottom:5px; }
.dark .ph-title { color:#e5e7eb; }
.ph-desc { font-size:.8rem; color:#9ca3af; margin-bottom:16px; line-height:1.6; }

/* ── MAR (Medication Administration Record) ──────────────────────── */
.mar-wrap { overflow-x: auto; background:#fff; border:1px solid #e5e7eb; border-radius:10px; }
.dark .mar-wrap { background:#1f2937; border-color:#374151; }
 
.mar-table { border-collapse:collapse; font-size:.78rem; min-width:700px; }
 
/* Sticky medication column */
.mar-table .col-med {
    position: sticky;
    left: 0;
    z-index: 2;
    background: #f8fafc;
    min-width: 160px;
    max-width: 200px;
    border-right: 2px solid #d1d5db !important;
}
.dark .mar-table .col-med { background:#1e2937; border-right-color:#374151 !important; }
 
/* Sticky shift column */
.mar-table .col-shift {
    position: sticky;
    left: 160px;          /* same as col-med min-width */
    z-index: 2;
    min-width: 48px;
    max-width: 52px;
    border-right: 1.5px solid #d1d5db !important;
}
.dark .mar-table .col-shift { border-right-color:#374151 !important; }
 
.mar-table th, .mar-table td {
    border: 1px solid #e5e7eb;
    padding: 0;
    vertical-align: middle;
    text-align: center;
}
.dark .mar-table th, .dark .mar-table td { border-color:#374151; }
 
.mar-table thead th {
    background: #f3f4f6;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #6b7280;
    padding: 7px 6px;
    white-space: nowrap;
}
.dark .mar-table thead th { background:#111827; color:#9ca3af; }
 
/* Shift badge cells */
.mar-shift-73   { background:#eff6ff; color:#1d4ed8; font-size:.7rem; font-weight:700; padding:4px 6px; }
.mar-shift-311  { background:#f5f3ff; color:#5b21b6; font-size:.7rem; font-weight:700; padding:4px 6px; }
.mar-shift-117  { background:#f0fdfa; color:#0f766e; font-size:.7rem; font-weight:700; padding:4px 6px; }
.dark .mar-shift-73  { background:#1e3a5f; color:#93c5fd; }
.dark .mar-shift-311 { background:#2e1065; color:#c4b5fd; }
.dark .mar-shift-117 { background:#042f2e; color:#5eead4; }
 
/* Medication group: top border thicker between groups */
.mar-table tr.mar-group-start td { border-top: 2px solid #d1d5db !important; }
.dark .mar-table tr.mar-group-start td { border-top-color:#4b5563 !important; }
 
/* Inline inputs */
.mar-cell-input {
    width: 100%;
    min-width: 52px;
    height: 30px;
    border: none;
    background: transparent;
    text-align: center;
    font-size: .78rem;
    font-family: monospace;
    color: #111827;
    outline: none;
    padding: 2px 3px;
}
.dark .mar-cell-input { color:#f3f4f6; }
.mar-cell-input:focus {
    background: #fffbeb;
    border: 1.5px solid #f59e0b !important;
    border-radius: 3px;
}
.dark .mar-cell-input:focus { background:#292524; }
 
/* Medication name input */
.mar-med-input {
    width: 100%;
    border: none;
    background: transparent;
    font-size: .78rem;
    font-weight: 600;
    color: #111827;
    padding: 6px 8px;
    outline: none;
    font-family: inherit;
    text-align: left;
}
.dark .mar-med-input { color:#f3f4f6; }
.mar-med-input:focus { background:#fffbeb; border-radius:3px; }
.dark .mar-med-input:focus { background:#292524; }
 
/* "Add date" mini row */
.mar-add-date-row td { background:#f0fdf4; border-top:2px solid #86efac; }
.dark .mar-add-date-row td { background:#022c22; }
 
/* Add med button */
.btn-mar-add-med {
    background:#f43f5e; color:#fff; border:none; border-radius:7px;
    padding:7px 16px; font-size:.78rem; font-weight:700; cursor:pointer;
    display:inline-flex; align-items:center; gap:5px;
}
.btn-mar-add-med:hover { background:#e11d48; }
 
.btn-mar-del {
    background:none; border:none; color:#d1d5db; cursor:pointer;
    font-size:.75rem; padding:2px 5px; border-radius:3px;
}
.btn-mar-del:hover { color:#ef4444; background:#fee2e2; }
.dark .btn-mar-del:hover { background:#450a0a; color:#fca5a5; }
 
.mar-date-header { font-size:.67rem; font-weight:700; color:#374151; white-space:nowrap; }
.dark .mar-date-header { color:#e5e7eb; }
.mar-date-sub { font-size:.58rem; color:#9ca3af; display:block; }
</style>

@if($visit && $visit->patient)
@php
    $patient     = $visit->patient;
    $history     = $visit->medicalHistory;
    $allOrders   = $visit->doctorsOrders ?? collect();
    $allNotes    = $visit->nursesNotes   ?? collect();
    $pendingCnt  = $allOrders->where('status', 'pending')->count();
    $service     = $visit->admitted_service ?? $history?->service ?? '—';
    $admittedAt  = $visit->clerk_admitted_at
        ? $visit->clerk_admitted_at->timezone('Asia/Manila')->format('M j, Y H:i')
        : '—';
    $vitalsCount   = $this->vitalsCount;
    $ivCount       = $this->ivEntriesCount;
    $isReadonly    = $this->isReadonly;
@endphp

<div class="chart-page">

    {{-- ════ HEADER ════════════════════════════════════════════════ --}}
    <div class="chart-header">
        <div class="chart-header-left">
            <p class="pt-name-big">{{ $patient->full_name }}</p>
            <p class="pt-case-big">{{ $patient->case_no }}</p>
        </div>
        <div class="header-pills">
            <div class="h-pill"><p class="pl">Age / Sex</p><p class="pv">{{ $patient->age_display }} · {{ $patient->sex }}</p></div>
            <div class="h-pill"><p class="pl">Admitting Diagnosis</p><p class="pv" style="font-size:.76rem;max-width:200px;white-space:normal;line-height:1.3;">{{ $visit->admitting_diagnosis ?? $history?->diagnosis ?? '—' }}</p></div>
            <span class="svc-pill">{{ $service }}</span>
            <div class="h-pill"><p class="pl">Admitted</p><p class="pv">{{ $admittedAt }}</p></div>
            @if($history?->doctor)<div class="h-pill"><p class="pl">Physician</p><p class="pv">Dr. {{ $history->doctor->name }}</p></div>@endif
            <a href="{{ $this->getPatientHistoryUrl() }}" class="btn-back-hdr">🗂️ All Visits →</a>
        </div>
        <button wire:click="goBack" type="button" class="btn-back-hdr">← Patient List</button>
    </div>

    @if($isReadonly)
    <div style="background:#fef9c3;border-bottom:2px solid #f59e0b;padding:10px 24px;display:flex;align-items:center;gap:8px;font-size:.82rem;font-weight:600;color:#92400e;">
        📂 Past Visit — Read Only &nbsp;·&nbsp; <span style="font-weight:400;">This visit is completed. No changes can be made.</span>
    </div>
    @endif

    {{-- ════ TABS ═══════════════════════════════════════════════════ --}}
    @if(!$isReadonly)
    <div class="chart-tabs">
        <button wire:click="setTab('orders')"   class="chart-tab {{ $activeTab==='orders'   ? 'active':'' }}">
            📋 Doctor's Orders
            @if($pendingCnt > 0)<span class="tab-badge">{{ $pendingCnt }}</span>
            @elseif($allOrders->count() > 0)<span class="tab-badge tab-badge-green">✓</span>@endif
        </button>
        <button wire:click="setTab('notes')"    class="chart-tab {{ $activeTab==='notes'    ? 'active':'' }}">
            📝 Nurse's Notes
            @if($allNotes->count() > 0)<span class="tab-badge" style="background:#6366f1;">{{ $allNotes->count() }}</span>@endif
        </button>
        <button wire:click="setTab('vitals')"   class="chart-tab {{ $activeTab==='vitals'   ? 'active':'' }}">
            📊 Vital Signs
            @if($vitalsCount > 0)<span class="tab-badge tab-badge-blue">{{ $vitalsCount }}</span>@endif
        </button>
        <button wire:click="setTab('iv')"       class="chart-tab {{ $activeTab==='iv'       ? 'active':'' }}">
            💧 IV Fluid / Blood
            @if($ivCount > 0)<span class="tab-badge tab-badge-teal">{{ $ivCount }}</span>@endif
        </button>
        {{-- Only show Breastfeeding tab for NICU patients --}}
        @if($visit->visit_type === 'NICU')
        <button wire:click="setTab('breastfeeding')" class="chart-tab {{ $activeTab==='breastfeeding' ? 'active':'' }}">
            <span class="tab-icon">🍼</span> Breastfeeding
            @if($this->breastfeedingObservationsCount > 0)
                <span class="tab-badge tab-badge-green">{{ $this->breastfeedingObservationsCount }}</span>
            @endif
        </button>
        <button wire:click="setTab('growth')" class="chart-tab {{ $activeTab==='growth' ? 'active':'' }}">
            <span class="tab-icon">📈</span> Growth Chart
        </button>
        @endif
        </button>
        <button wire:click="setTab('forms')"    class="chart-tab {{ $activeTab==='forms'    ? 'active':'' }}">📄 Patient Forms</button>
        <button wire:click="setTab('mar')"      class="chart-tab {{ $activeTab==='mar'      ? 'active':'' }}">💊 MAR</button>
        <button wire:click="setTab('tpr')" class="chart-tab {{ $activeTab==='tpr' ? 'active':'' }}">🌡️ TPR Record</button>
        <button wire:click="setTab('io')"       class="chart-tab {{ $activeTab==='io'       ? 'active':'' }}">📏 I &amp; O</button>
        <button wire:click="setTab('handover')" class="chart-tab {{ $activeTab==='handover' ? 'active':'' }}">🔄 Handover</button>
    </div>
    @endif{{-- !isReadonly --}}

    <div class="chart-content">

        {{-- ══ DOCTOR'S ORDERS ══════════════════════════════════════ --}}
        @if($activeTab === 'orders')
        <div class="sec-head">
            <h2 class="sec-title">Doctor's Orders</h2>
            <span style="font-size:.78rem;color:#6b7280;">{{ $allOrders->count() }} order(s) &nbsp;·&nbsp; <span style="color:#d97706;font-weight:700;">{{ $pendingCnt }} pending</span> &nbsp;·&nbsp; {{ $allOrders->where('status','carried')->count() }} carried</span>
        </div>
        @if($allOrders->isEmpty())
        <div class="empty-state"><div class="empty-icon">📋</div><p class="empty-title">No orders written yet</p><p class="empty-sub">Doctor's orders will appear here once written from the Doctor panel.</p></div>
        @else
        @if($pendingCnt > 0)
        <div class="mark-all-banner">
            <div class="mark-all-text"><strong>{{ $pendingCnt }} pending order(s)</strong> — click "Mark All as Carried" to carry all at once, or mark each individually below.</div>
            <button wire:click="carryAllOrders" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button" class="btn-mark-all">
                <span wire:loading.remove wire:target="carryAllOrders">✅ Mark All as Carried</span>
                <span wire:loading wire:target="carryAllOrders">Marking…</span>
            </button>
        </div>
        @endif
        @foreach($allOrders->groupBy(fn($o) => $o->order_date?->timezone('Asia/Manila')->format('Y-m-d H:i')) as $dateKey => $group)
        <div class="order-group-wrap">
            <div class="order-group-hdr"><p class="order-group-label">{{ \Carbon\Carbon::parse($dateKey)->timezone('Asia/Manila')->format('F j, Y · H:i') }}</p><div class="order-group-line"></div>@if($group->first()->doctor)<p class="order-group-doc">Dr. {{ $group->first()->doctor->name }}</p>@endif</div>
            @foreach($group as $i => $order)
            <div class="order-row is-{{ $order->status }}" wire:key="n-order-{{ $order->id }}">
                <div class="order-num-circle order-num-{{ $order->status }}">{{ $i + 1 }}</div>
                <div class="order-body">
                    <p class="order-text-main {{ $order->isDiscontinued() ? 'struck':'' }}">{{ $order->order_text }}</p>
                    <p class="order-written-meta">Written {{ $order->order_date?->timezone('Asia/Manila')->format('M j, Y H:i') }}</p>
                    @if($order->isCarried() && $order->completed_at)<p class="order-carry-meta">✓ Carried by {{ $order->completedBy?->name ?? 'Nurse' }} at {{ $order->completed_at->timezone('Asia/Manila')->format('M j, Y H:i') }}</p>@endif
                    @if($order->isDiscontinued() && $order->completed_at)<p class="order-disc-meta">✕ Discontinued at {{ $order->completed_at->timezone('Asia/Manila')->format('M j, Y H:i') }}</p>@endif
                </div>
                <div class="carry-action">
                    <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                    @if($order->isPending())
                        @if($confirmCarryId === $order->id)
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;margin-top:2px;">
                            <p style="font-size:.68rem;color:#059669;font-weight:700;">Mark as carried?</p>
                            <div style="display:flex;gap:5px;"><button wire:click="carryOrder({{ $order->id }})" wire:loading.attr="disabled" type="button" class="btn-carry-confirm"><span wire:loading.remove wire:target="carryOrder({{ $order->id }})">✓ Yes</span><span wire:loading wire:target="carryOrder({{ $order->id }})">…</span></button><button wire:click="$set('confirmCarryId', null)" type="button" class="btn-cancel-sm">No</button></div>
                        </div>
                        @else
                        <button wire:click="$set('confirmCarryId', {{ $order->id }})" type="button" class="btn-carry" style="margin-top:2px;">✓ Mark as Carried</button>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
        @endif

        {{-- ══ NURSE'S NOTES ════════════════════════════════════════ --}}
        @elseif($activeTab === 'notes')
        <div class="sec-head">
            <h2 class="sec-title">Nurse's Notes</h2>
            <button wire:click="toggleAddNote" type="button"
                    class="btn-add-note {{ $addingNote ? 'is-cancel':'' }}">
                @if($addingNote) ✕ Cancel @else ✏️ Add FDAR Note @endif
            </button>
        </div>
 
        @if($addingNote)
        <div class="soap-form">
            <p class="soap-form-title">
                New FDAR Note &nbsp;·&nbsp;
                <span style="font-weight:400;color:#6b7280;">
                    {{ now()->timezone('Asia/Manila')->format('F j, Y H:i') }}
                    &nbsp;·&nbsp; {{ auth()->user()->name }}
                </span>
            </p>
 
            {{-- ── Shift selector ───────────────────────────────────── --}}
            <div style="margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid #ffe4e6;">
                <span class="soap-label" style="display:block;margin-bottom:6px;">
                    Shift *
                    <span style="font-size:.68rem;color:#ef4444;font-weight:600;">(required)</span>
                </span>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach(['7-3' => '7AM – 3PM', '3-11' => '3PM – 11PM', '11-7' => '11PM – 7AM'] as $val => $label)
                    <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;
                                  background:{{ $fdarShift === $val ? '#ffe4e6' : '#f9fafb' }};
                                  border:2px solid {{ $fdarShift === $val ? '#f43f5e' : '#e5e7eb' }};
                                  border-radius:7px;padding:7px 16px;font-size:.82rem;font-weight:700;
                                  color:{{ $fdarShift === $val ? '#be123c' : '#374151' }};
                                  transition:border-color .12s,background .12s;">
                        <input type="radio"
                               wire:model="fdarShift"
                               value="{{ $val }}"
                               style="accent-color:#f43f5e;">
                        {{ $val }} &nbsp;<span style="font-weight:400;font-size:.75rem;">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
                @if(filled($fdarShift))
                <p style="font-size:.7rem;color:#059669;margin-top:5px;font-weight:600;">
                    ✓ Shift {{ $fdarShift }} selected
                </p>
                @endif
            </div>
 
            {{-- ── FDAR fields ─────────────────────────────────────── --}}
            <div class="soap-row">
                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
                    <div class="soap-letter soap-f">F</div>
                    <span style="font-size:.62rem;color:#1e40af;font-weight:700;">Focus</span>
                </div>
                <div>
                    <span class="soap-label">Focus — nursing diagnosis or patient problem / concern</span>
                    <textarea wire:model="fdarF" rows="2" class="soap-textarea"
                        placeholder="e.g., Acute pain related to hypertensive crisis."></textarea>
                </div>
            </div>
 
            <div class="soap-row">
                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
                    <div class="soap-letter soap-d">D</div>
                    <span style="font-size:.62rem;color:#166534;font-weight:700;">Data</span>
                </div>
                <div>
                    <span class="soap-label">Data — supporting subjective and objective findings</span>
                    <textarea wire:model="fdarD" rows="3" class="soap-textarea"
                        placeholder="e.g., Patient c/o headache 8/10. BP 185/100, PR 98, Temp 37.1°C, O2 95%."></textarea>
                </div>
            </div>
 
            <div class="soap-row">
                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
                    <div class="soap-letter soap-a">A</div>
                    <span style="font-size:.62rem;color:#854d0e;font-weight:700;">Action</span>
                </div>
                <div>
                    <span class="soap-label">Action — nursing interventions performed</span>
                    <textarea wire:model="fdarA" rows="3" class="soap-textarea"
                        placeholder="e.g., Administered Captopril 25mg SL as ordered. O2 via face mask applied. Monitoring BP q1h."></textarea>
                </div>
            </div>
 
            <div class="soap-row">
                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
                    <div class="soap-letter soap-r">R</div>
                    <span style="font-size:.62rem;color:#5b21b6;font-weight:700;">Response</span>
                </div>
                <div>
                    <span class="soap-label">Response — patient's response to interventions</span>
                    <textarea wire:model="fdarR" rows="3" class="soap-textarea"
                        placeholder="e.g., BP decreased to 160/95 after 30 minutes. Headache improved to 5/10. Patient resting comfortably."></textarea>
                </div>
            </div>
 
            <div style="display:flex;gap:10px;margin-top:14px;">
                <button wire:click="saveNote"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60"
                        type="button" class="btn-primary">
                    <span wire:loading.remove wire:target="saveNote">💾 Save Note</span>
                    <span wire:loading wire:target="saveNote">Saving…</span>
                </button>
                <button wire:click="toggleAddNote" type="button" class="btn-secondary">Cancel</button>
            </div>
        </div>
        @endif
 
        @if($allNotes->isEmpty() && !$addingNote)
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <p class="empty-title">No nurse's notes yet</p>
            <p class="empty-sub">Click "Add FDAR Note" above to write the first nursing note.</p>
        </div>
        @else
        @foreach($allNotes as $note)
        <div class="note-card" wire:key="note-{{ $note->id }}">
            <div class="note-header">
                <div>
                    <p class="note-nurse">{{ $note->nurse?->name ?? 'Unknown Nurse' }}</p>
                    <p class="note-time">
                        {{ $note->noted_at?->timezone('Asia/Manila')->format('F j, Y · H:i') }}
                        &nbsp;·&nbsp; {{ $note->noted_at?->diffForHumans() }}
                    </p>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    @if($note->shift)
                    <span style="font-size:.7rem;font-weight:700;background:#fef3c7;color:#92400e;padding:2px 9px;border-radius:9999px;border:1px solid #fde68a;">
                        Shift {{ $note->shift }}
                    </span>
                    @endif
                    <span style="font-size:.7rem;background:#f3f4f6;padding:2px 8px;border-radius:4px;color:#6b7280;font-weight:700;">FDAR</span>
                </div>
            </div>
            @if($note->focus)
            <div class="note-soap-row">
                <div class="note-soap-letter soap-f" style="font-size:.68rem;">F</div>
                <div><p class="note-soap-label">Focus</p><p class="note-soap-text">{{ $note->focus }}</p></div>
            </div>
            @endif
            @if($note->data)
            <div class="note-soap-row">
                <div class="note-soap-letter soap-d" style="font-size:.68rem;">D</div>
                <div><p class="note-soap-label">Data</p><p class="note-soap-text">{{ $note->data }}</p></div>
            </div>
            @endif
            @if($note->action)
            <div class="note-soap-row">
                <div class="note-soap-letter soap-a" style="font-size:.68rem;">A</div>
                <div><p class="note-soap-label">Action</p><p class="note-soap-text">{{ $note->action }}</p></div>
            </div>
            @endif
            @if($note->response)
            <div class="note-soap-row">
                <div class="note-soap-letter soap-r" style="font-size:.68rem;">R</div>
                <div><p class="note-soap-label">Response</p><p class="note-soap-text">{{ $note->response }}</p></div>
            </div>
            @endif
        </div>
        @endforeach
        @endif

        {{-- ══ VITAL SIGNS MONITORING SHEET══════════════════════════════════════════ --}}
        @elseif($activeTab === 'vitals')
        @php $allVitals = $this->allVitals; @endphp

        <div class="sec-head">
            <h2 class="sec-title">📊 Vital Signs Monitoring Sheet</h2>
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:.78rem;color:#6b7280;">{{ $allVitals->count() }} entr{{ $allVitals->count() === 1 ? 'y':'ies' }}</span>
                @if(!$addingVital)
                <button wire:click="openAddVital" type="button" class="btn-add-vital">➕ Add New Entry</button>
                @endif
            </div>
        </div>

        {{-- ── ADD ENTRY FORM ─────────────────────────────────────── --}}
        @if($addingVital)
        <div class="vs-entry-form">
            <p class="vs-form-title">➕ New Entry <span style="font-weight:400;color:#6b7280;font-size:.78rem;">{{ auth()->user()->name }}</span></p>
            <div class="vs-single-row">
                <div class="vs-field"><label class="vs-label">Date &amp; Time *</label><input type="datetime-local" wire:model="vitalTakenAt" class="vs-input"></div>
                <div class="vs-field"><label class="vs-label">SpO₂ (%)</label><input type="number" wire:model="vitalSpO2" min="0" max="100" class="vs-input"></div>
                <div class="vs-field"><label class="vs-label">CR (bpm)</label><input type="number" wire:model="vitalCR" min="0" max="300" class="vs-input"></div>
                <div class="vs-field"><label class="vs-label">PR /min</label><input type="number" wire:model="vitalPR" min="0" max="300" class="vs-input"></div>
                <div class="vs-field"><label class="vs-label">RR /min</label><input type="number" wire:model="vitalRR" min="0" max="80" class="vs-input"></div>
                <div class="vs-field"><label class="vs-label">Temp. (°C)</label><input type="number" step="0.1" wire:model="vitalTemp" min="30" max="45" class="vs-input"></div>
                <div class="vs-field"><label class="vs-label">Neurological VS</label><textarea wire:model="vitalNeuroVS" rows="2" class="vs-textarea"></textarea></div>
                <div class="vs-field"><label class="vs-label">Others</label><textarea wire:model="vitalOthers" rows="2" class="vs-textarea"></textarea></div>
                <div class="vs-field"><label class="vs-label">Remarks</label><textarea wire:model="vitalRemarks" rows="2" class="vs-textarea"></textarea></div>
            </div>
            <div style="display:flex;gap:10px;align-items:center;padding-top:12px;border-top:1px solid #dbeafe;">
                <button wire:click="saveVital" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button" class="btn-primary-blue">
                    <span wire:loading.remove wire:target="saveVital">💾 Save Entry</span>
                    <span wire:loading wire:target="saveVital">Saving…</span>
                </button>
                <button wire:click="cancelAddVital" type="button" class="btn-secondary">Cancel</button>
            </div>
        </div>
        @endif

        {{-- ── MONITORING SHEET TABLE ─────────────────────────────── --}}
        @if($allVitals->isEmpty())
        <div class="empty-state"><div class="empty-icon">📊</div><p class="empty-title">No vital signs recorded yet</p><p class="empty-sub">Click "Add New Entry" to record the first vital signs.</p></div>
        @else
        <div class="vs-sheet-wrap">
            <table class="vs-table">
                <thead>
                    <tr>
                        <th class="col-left">Date &amp; Time<br><span style="font-size:.6rem;font-weight:400;text-transform:none;color:#9ca3af;">Nurse / Recorder</span></th>
                        <th class="col-narrow">Sp<br><span style="font-size:.6rem;font-weight:400;text-transform:none;">(SpO₂%)</span></th>
                        <th class="col-narrow">CR<br><span style="font-size:.6rem;font-weight:400;text-transform:none;">(bpm)</span></th>
                        <th class="col-narrow">PR<br><span style="font-size:.6rem;font-weight:400;text-transform:none;">(/min)</span></th>
                        <th class="col-narrow">RR<br><span style="font-size:.6rem;font-weight:400;text-transform:none;">(/min)</span></th>
                        <th class="col-narrow">Temp.<br><span style="font-size:.6rem;font-weight:400;text-transform:none;">(°C)</span></th>
                        <th class="col-wide">Neurological<br>Vital Sign</th>
                        <th class="col-wide">Others</th>
                        <th class="col-wide">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($allVitals as $idx => $v)
                @php
                    $isReg   = $idx === 0 && ($v->recorder?->panel === 'clerk' || !$v->recorder);
                    $abnO2   = $v->o2_saturation !== null && $v->o2_saturation < 95;
                    $abnPR   = $v->pulse_rate    !== null && ($v->pulse_rate < 60 || $v->pulse_rate > 100);
                    $abnRR   = $v->respiratory_rate !== null && ($v->respiratory_rate < 12 || $v->respiratory_rate > 20);
                    $abnTemp = $v->temperature   !== null && ($v->temperature < 36.0 || $v->temperature > 37.5);
                @endphp
                <tr class="{{ $isReg ? 'vs-row-reg' : '' }}" wire:key="vs-{{ $v->id }}">
                    <td class="col-left">
                        <p style="font-family:monospace;font-size:.76rem;color:#6b7280;">{{ $v->taken_at->timezone('Asia/Manila')->format('M j, Y') }}</p>
                        <p style="font-family:monospace;font-size:.9rem;font-weight:700;color:#111827;margin-top:1px;">{{ $v->taken_at->timezone('Asia/Manila')->format('H:i') }}</p>
                        <p class="vs-nurse-tag">🧑‍⚕️ {{ $v->nurse_name }}</p>
                        @if($isReg)<span class="vs-reg-badge">Triage / Registration</span>@endif
                    </td>
                    <td>@if($v->o2_saturation !== null)<span class="vs-val {{ $abnO2 ? 'vs-abnormal':'' }}">{{ $v->o2_saturation }}</span>@else<span class="vs-val-na">—</span>@endif</td>
                    <td>@if($v->cardiac_rate)<span class="vs-val">{{ $v->cardiac_rate }}</span>@else<span class="vs-val-na">—</span>@endif</td>
                    <td>@if($v->pulse_rate)<span class="vs-val {{ $abnPR ? 'vs-abnormal':'' }}">{{ $v->pulse_rate }}</span>@else<span class="vs-val-na">—</span>@endif</td>
                    <td>@if($v->respiratory_rate)<span class="vs-val {{ $abnRR ? 'vs-abnormal':'' }}">{{ $v->respiratory_rate }}</span>@else<span class="vs-val-na">—</span>@endif</td>
                    <td>@if($v->temperature)<span class="vs-val {{ $abnTemp ? 'vs-abnormal':'' }}">{{ $v->temperature }}</span>@if($v->temperature_site)<p style="font-size:.6rem;color:#9ca3af;margin-top:1px;">{{ $v->temperature_site }}</p>@endif@else<span class="vs-val-na">—</span>@endif</td>
                    <td class="col-text">@if($v->neurological_vs)<span style="font-size:.78rem;white-space:pre-line;color:#374151;line-height:1.5;">{{ $v->neurological_vs }}</span>@else<span class="vs-val-na">—</span>@endif</td>
                    <td class="col-text">@if($v->others_vs)<span style="font-size:.78rem;white-space:pre-line;color:#374151;line-height:1.5;">{{ $v->others_vs }}</span>@else<span class="vs-val-na">—</span>@endif</td>
                    <td class="col-text">@if($v->notes)<span style="font-size:.78rem;white-space:pre-line;color:#374151;line-height:1.5;">{{ $v->notes }}</span>@else<span class="vs-val-na">—</span>@endif</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:8px;font-size:.69rem;color:#9ca3af;align-items:center;padding:6px 2px;">
            <span><span style="display:inline-block;width:10px;height:10px;background:#fffbeb;border:1px solid #fde68a;border-radius:2px;margin-right:4px;vertical-align:middle;"></span>Triage / Registration row</span>
            <span><span style="color:#dc2626;font-weight:700;">Red</span> = abnormal (SpO₂ &lt;95% · PR &lt;60 or &gt;100 · RR &lt;12 or &gt;20 · Temp &lt;36.0 or &gt;37.5°C)</span>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════
             IV FLUID / BLOOD TRANSFUSION SHEET
        ══════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'iv')
        @php $allIvEntries = $this->allIvEntries; @endphp

        <div class="sec-head">
            <h2 class="sec-title">💧 Intravenous Fluid Sheet / Blood Transfusion Sheet</h2>
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:.78rem;color:#6b7280;">{{ $allIvEntries->count() }} entr{{ $allIvEntries->count() === 1 ? 'y':'ies' }}</span>
                @if(!$addingIv && $editingIvId === null)
                <button wire:click="openAddIv" type="button" class="btn-add-iv">➕ Add New Entry</button>
                @endif
            </div>
        </div>

        {{-- ── ADD ENTRY FORM ─────────────────────────────────────── --}}
        @if($addingIv)
        <div class="iv-entry-form">
            <p class="iv-form-title">
                ➕ New IV / Blood Transfusion Entry
                <span style="font-weight:400;color:#6b7280;font-size:.78rem;">{{ auth()->user()->name }}</span>
            </p>

            <div class="iv-form-grid">

                <div class="iv-field">
                    <label class="iv-label">Date Started *</label>
                    <input type="date"
                           wire:model="ivDateStarted"
                           class="iv-input">
                    <span class="iv-help">YYYY-MM-DD</span>
                </div>

                <div class="iv-field">
                    <label class="iv-label">Time Started *</label>
                    <input type="time"
                           wire:model="ivTimeStarted"
                           class="iv-input">
                    <span class="iv-help">24-hr format</span>
                </div>

                <div class="iv-field">
                    <label class="iv-label">Bottle / Bag #</label>
                    <input type="number"
                           wire:model="ivBottleNumber"
                           min="1" max="999"
                           class="iv-input">
                </div>

                <div class="iv-field" style="grid-column: span 1;">
                    <label class="iv-label">IV Solution / Blood Product, Amount &amp; Rate *</label>
                    <input type="text"
                           wire:model="ivSolution"
                           class="iv-input"
                           placeholder="e.g. D5LR 1L @ 30 gtts/min (125 mL/hr) or PRBC 350mL">
                    <span class="iv-help">Include volume and regulation rate</span>
                </div>

                <div class="iv-field">
                    <label class="iv-label">Date &amp; Time Consumed</label>
                    <input type="datetime-local"
                           wire:model="ivConsumedAt"
                           class="iv-input">
                    <span class="iv-help">Leave blank if still running</span>
                </div>

                <div class="iv-field">
                    <label class="iv-label">Remarks</label>
                    <textarea wire:model="ivRemarks" rows="2"
                              class="iv-textarea"
                              placeholder="e.g. Site: R forearm, 21G; patient tolerated well"></textarea>
                </div>

            </div>

            <div style="display:flex;gap:10px;align-items:center;padding-top:12px;border-top:1px solid #ccfbf1;">
                <button wire:click="saveIvEntry"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60"
                        type="button" class="btn-primary-teal">
                    <span wire:loading.remove wire:target="saveIvEntry">💾 Save Entry</span>
                    <span wire:loading wire:target="saveIvEntry">Saving…</span>
                </button>
                <button wire:click="cancelAddIv" type="button" class="btn-secondary">Cancel</button>
            </div>
        </div>
        @endif

        {{-- ── SHEET TABLE ────────────────────────────────────────── --}}
        @if($allIvEntries->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">💧</div>
            <p class="empty-title">No IV / Blood Transfusion entries yet</p>
            <p class="empty-sub">Click "Add New Entry" to record the first IV fluid or blood product.</p>
        </div>
        @else

        <div class="iv-sheet-wrap">
            <table class="iv-table">
                <thead>
                    <tr>
                        <th class="col-narrow col-center">Bottle<br>#</th>
                        <th class="col-medium">Date Started</th>
                        <th class="col-narrow col-center">Time<br>Started</th>
                        <th class="col-wide">IV Solution / Blood Product<br>Amount &amp; Regulation Rate</th>
                        <th class="col-medium">Date &amp; Time<br>Consumed</th>
                        <th class="col-wide">Remarks</th>
                        <th class="col-medium">Nurse Signature</th>
                        <th class="col-action col-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($allIvEntries as $entry)
                @php $isEditing = $editingIvId === $entry->id; @endphp
                <tr wire:key="iv-{{ $entry->id }}" class="{{ $isEditing ? 'is-editing' : '' }}">

                    {{-- Bottle # --}}
                    <td class="col-center">
                        <span class="iv-bottle-badge">{{ $entry->bottle_number }}</span>
                    </td>

                    {{-- Date Started (immutable) --}}
                    <td>
                        <span style="font-family:monospace;font-size:.83rem;font-weight:600;color:#111827;">
                            {{ $entry->date_started->format('M j, Y') }}
                        </span>
                        <p style="font-family:monospace;font-size:.67rem;color:#9ca3af;">
                            {{ $entry->date_started->format('D') }}
                        </p>
                    </td>

                    {{-- Time Started (immutable) --}}
                    <td class="col-center">
                        <span style="font-family:monospace;font-size:.88rem;font-weight:700;color:#0f766e;">
                            {{ \Carbon\Carbon::parse($entry->time_started)->format('H:i') }}
                        </span>
                    </td>

                    {{-- IV Solution (immutable) --}}
                    <td>
                        <span class="iv-solution-text">{{ $entry->iv_solution }}</span>
                    </td>

                    {{-- Date & Time Consumed — EDITABLE --}}
                    <td>
                        @if($isEditing)
                        <div class="iv-inline-edit">
                            <input type="datetime-local"
                                   wire:model="ivConsumedAt"
                                   class="iv-input"
                                   style="font-size:.8rem;padding:6px 8px;">
                            <span class="iv-help">Leave blank = still running</span>
                        </div>
                        @else
                            @if($entry->consumed_at)
                            <span class="iv-consumed-yes">
                                {{ $entry->consumed_at->timezone('Asia/Manila')->format('M j, Y') }}<br>
                                {{ $entry->consumed_at->timezone('Asia/Manila')->format('H:i') }}
                            </span>
                            @else
                            <span class="iv-consumed-no">Still running</span>
                            @endif
                        @endif
                    </td>

                    {{-- Remarks — EDITABLE --}}
                    <td>
                        @if($isEditing)
                        <div class="iv-inline-edit">
                            <textarea wire:model="ivRemarks" rows="2"
                                      class="iv-textarea"
                                      style="font-size:.8rem;min-height:50px;"
                                      placeholder="Remarks…"></textarea>
                        </div>
                        @else
                            @if($entry->remarks)
                            <span style="font-size:.8rem;white-space:pre-line;color:#374151;line-height:1.5;">{{ $entry->remarks }}</span>
                            @else
                            <span class="vs-val-na">—</span>
                            @endif
                        @endif
                    </td>

                    {{-- Nurse Signature --}}
                    <td>
                        <span class="iv-nurse-sig">{{ $entry->nurse_name }}</span>
                        <p style="font-size:.67rem;color:#9ca3af;margin-top:2px;">
                            {{ $entry->created_at->timezone('Asia/Manila')->format('M j, Y H:i') }}
                        </p>
                        @if($entry->editor_name)
                        <p class="iv-edit-meta">
                            ✎ Edited by {{ $entry->editor_name }}<br>
                            {{ $entry->edited_at?->timezone('Asia/Manila')->format('M j, Y H:i') }}
                        </p>
                        @endif
                    </td>

                    {{-- Action --}}
                    <td class="col-center">
                        @if($isEditing)
                        <div style="display:flex;flex-direction:column;gap:5px;align-items:center;">
                            <button wire:click="saveIvEdit"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-60"
                                    type="button" class="btn-primary-teal"
                                    style="padding:5px 12px;font-size:.75rem;">
                                <span wire:loading.remove wire:target="saveIvEdit">💾 Save</span>
                                <span wire:loading wire:target="saveIvEdit">…</span>
                            </button>
                            <button wire:click="cancelEditIv" type="button"
                                    class="btn-cancel-sm" style="font-size:.72rem;">
                                Cancel
                            </button>
                        </div>
                        @elseif($editingIvId === null)
                        <button wire:click="openEditIv({{ $entry->id }})" type="button"
                                class="btn-edit-iv">
                            ✎ Edit
                        </button>
                        @endif
                    </td>

                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Legend --}}
        <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:8px;font-size:.69rem;color:#9ca3af;padding:6px 2px;">
            <span>✎ Edit allows updating <strong>Date &amp; Time Consumed</strong> and <strong>Remarks</strong> only.</span>
            <span>Other columns are locked after saving to preserve the original record.</span>
        </div>
        @endif

        {{-- ══ BREASTFEEDING OBSERVATIONS ═══════════════════════════════════ --}}
        @elseif($activeTab === 'breastfeeding')
        @php $observations = $this->breastfeedingObservations; @endphp

        <style>
            /* ── Breastfeeding observation card ───────────────────────────────────── */
            .bfobs-card {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                margin-bottom: 14px;
                overflow: hidden;
            }
            .dark .bfobs-card { background: #1f2937; border-color: #374151; }

            .bfobs-header {
                background: #f9fafb;
                border-bottom: 1px solid #e5e7eb;
                padding: 9px 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 8px;
            }
            .dark .bfobs-header { background: #111827; border-color: #374151; }

            .bfobs-meta { font-size: 0.8rem; font-weight: 700; color: #1e293b; }
            .dark .bfobs-meta { color: #f1f5f9; }
            .bfobs-time { font-size: 0.7rem; color: #6b7280; margin-left: 8px; }

            .bfobs-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 2px 10px;
                border-radius: 20px;
                font-size: 0.68rem;
                font-weight: 700;
            }
            .bfobs-badge.well { background: #dcfce7; color: #166534; }
            .bfobs-badge.diff { background: #fee2e2; color: #991b1b; }
            .bfobs-badge.none { background: #f1f5f9; color: #64748b; }

            .bfobs-body {
                padding: 12px 16px;
            }

            /* 5-column section grid */
            .bfobs-sections {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 10px;
            }
            @media (max-width: 900px) {
                .bfobs-sections { grid-template-columns: repeat(2, 1fr); }
            }

            .bfobs-section { }

            .bfobs-section-title {
                font-size: 0.62rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #9ca3af;
                margin-bottom: 5px;
                padding-bottom: 3px;
                border-bottom: 1px solid #f1f5f9;
            }
            .dark .bfobs-section-title { color: #6b7280; border-color: #374151; }

            .bfobs-item {
                display: flex;
                align-items: flex-start;
                gap: 5px;
                font-size: 0.72rem;
                color: #374151;
                line-height: 1.35;
                padding: 2px 0;
            }
            .dark .bfobs-item { color: #d1d5db; }

            .bfobs-item.well .dot  { color: #16a34a; flex-shrink: 0; }
            .bfobs-item.diff .dot  { color: #dc2626; flex-shrink: 0; }
            .bfobs-item.well       { color: #166534; }
            .bfobs-item.diff       { color: #991b1b; }
            .dark .bfobs-item.well { color: #4ade80; }
            .dark .bfobs-item.diff { color: #f87171; }

            .bfobs-empty-section {
                font-size: 0.68rem;
                color: #d1d5db;
                font-style: italic;
            }
            .dark .bfobs-empty-section { color: #4b5563; }
        </style>

        <div class="sec-head">
            <h2 class="sec-title">🍼 Breastfeeding Observations (NUR-044-0)</h2>
            <a href="{{ \App\Filament\Nurse\Pages\BreastfeedingObservation::getUrl(['visitId' => $visit->id]) }}"
            target="_blank"
            class="btn-primary"
            style="padding: 6px 14px; font-size: 0.75rem;">
                + New Observation
            </a>
        </div>

        @if($observations->isEmpty())
        <div class="placeholder-card">
            <div class="ph-icon">🍼</div>
            <p class="ph-title">No breastfeeding observations recorded yet</p>
            <p class="ph-sub">Click "New Observation" to record a breastfeeding assessment.</p>
        </div>
        @else

        @foreach($observations as $obs)
        @php
            // ── Section data: label, well fields, diff fields ─────────────────
            $sections = [
                'General' => [
                    'well' => [
                        'general_mother_healthy' => 'Mother healthy',
                        'general_mother_relaxed' => 'Mother relaxed',
                        'general_mother_bonding' => 'Bonding signs',
                        'general_baby_healthy'   => 'Baby healthy',
                        'general_baby_calm'      => 'Baby calm',
                        'general_baby_roots'     => 'Baby roots',
                    ],
                    'diff' => [
                        'general_mother_ill'           => 'Mother ill/depressed',
                        'general_mother_tense'         => 'Mother tense',
                        'general_mother_no_eye_contact' => 'No eye contact',
                        'general_baby_sleepy_ill'      => 'Baby sleepy/ill',
                        'general_baby_restless_crying' => 'Baby restless/crying',
                        'general_baby_no_root'         => 'Baby not rooting',
                    ],
                ],
                'Breast' => [
                    'well' => [
                        'breast_healthy'       => 'Breast healthy',
                        'breast_no_pain'       => 'No pain',
                        'breast_fingers_away'  => 'Fingers away',
                    ],
                    'diff' => [
                        'breast_red_swollen_sore'  => 'Red/swollen/sore',
                        'breast_painful'           => 'Painful',
                        'breast_fingers_on_areola' => 'Fingers on areola',
                    ],
                ],
                'Position' => [
                    'well' => [
                        'position_head_body_line'  => 'Head/body aligned',
                        'position_held_close'      => 'Held close',
                        'position_body_supported'  => 'Body supported',
                        'position_nose_to_nipple'  => 'Nose to nipple',
                    ],
                    'diff' => [
                        'position_neck_twisted'    => 'Neck twisted',
                        'position_not_held_close'  => 'Not held close',
                        'position_head_neck_only'  => 'Head/neck only',
                        'position_chin_to_nipple'  => 'Chin to nipple',
                    ],
                ],
                'Attachment' => [
                    'well' => [
                        'attachment_more_areola_above'   => 'More areola above',
                        'attachment_mouth_open_wide'     => 'Mouth open wide',
                        'attachment_lip_turned_out'      => 'Lip turned out',
                        'attachment_chin_touches_breast' => 'Chin touches',
                    ],
                    'diff' => [
                        'attachment_more_areola_below'      => 'More areola below',
                        'attachment_mouth_not_wide'         => 'Mouth not wide',
                        'attachment_lips_forward_turned_in' => 'Lips turned in',
                        'attachment_chin_not_touching'      => 'Chin not touching',
                    ],
                ],
                'Suckling' => [
                    'well' => [
                        'suckling_slow_deep_pauses' => 'Slow, deep pauses',
                        'suckling_cheeks_round'     => 'Cheeks round',
                        'suckling_baby_releases'    => 'Baby releases',
                        'suckling_oxytocin_reflex'  => 'Oxytocin reflex',
                    ],
                    'diff' => [
                        'suckling_rapid_shallow'    => 'Rapid/shallow',
                        'suckling_cheeks_pulled_in' => 'Cheeks pulled in',
                        'suckling_mother_takes_off' => 'Mother takes off',
                        'suckling_no_oxytocin_reflex' => 'No oxytocin reflex',
                    ],
                ],
            ];
        @endphp

        <div class="bfobs-card">
            {{-- Card header --}}
            <div class="bfobs-header">
                <div>
                    <span class="bfobs-meta">
                        {{ \Carbon\Carbon::parse($obs->observation_date)->format('M d, Y') }}
                    </span>
                    <span class="bfobs-time">at {{ \Carbon\Carbon::parse($obs->observation_time)->format('h:i A') }}</span>
                    <span class="bfobs-time">· {{ $obs->observer?->name ?? '—' }}</span>
                </div>
                <div style="display: flex; gap: 6px; align-items: center;">
                    @if($obs->going_well_count > 0)
                        <span class="bfobs-badge well">✅ {{ $obs->going_well_count }} going well</span>
                    @endif
                    @if($obs->difficulty_count > 0)
                        <span class="bfobs-badge diff">⚠️ {{ $obs->difficulty_count }} difficulty</span>
                    @elseif($obs->difficulty_count === 0)
                        <span class="bfobs-badge none">No difficulties noted</span>
                    @endif
                </div>
            </div>

            {{-- Card body: 5-section grid --}}
            <div class="bfobs-body">
                <div class="bfobs-sections">
                    @foreach($sections as $sectionName => $fields)
                    @php
                        $wellItems = collect($fields['well'])->filter(fn($label, $field) => $obs->{$field});
                        $diffItems = collect($fields['diff'])->filter(fn($label, $field) => $obs->{$field});
                        $hasAny = $wellItems->isNotEmpty() || $diffItems->isNotEmpty();
                    @endphp
                    <div class="bfobs-section">
                        <div class="bfobs-section-title">{{ $sectionName }}</div>
                        @if(!$hasAny)
                            <div class="bfobs-empty-section">None noted</div>
                        @else
                            @foreach($wellItems as $label)
                                <div class="bfobs-item well"><span class="dot">✓</span><span>{{ $label }}</span></div>
                            @endforeach
                            @foreach($diffItems as $label)
                                <div class="bfobs-item diff"><span class="dot">✗</span><span>{{ $label }}</span></div>
                            @endforeach
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
        @endif

        {{-- ══ GROWTH CHART (WHO) ═══════════════════════════════════════════ --}}
        @elseif($activeTab === 'growth')
        @php
            $gender = $visit->patient->sex === 'Male' ? 'boy' : 'girl';
            $measurements = $this->growthMeasurements;
            $currentMeasurements = $measurements[$growthChartType] ?? [];
            $allMeasurements = $measurements[$growthChartType] ?? [];
        @endphp

        <div class="sec-head">
            <h2 class="sec-title">📈 WHO Growth Chart - {{ ucfirst($growthChartType) }}-for-Age ({{ $gender === 'boy' ? 'Boys' : 'Girls' }})</h2>
            <div style="display: flex; gap: 8px;">
                <button wire:click="setGrowthChartType('length')" 
                    class="btn-secondary" 
                    style="padding: 6px 14px; font-size: 0.75rem; {{ $growthChartType === 'length' ? 'background:#1d4ed8; color:#fff;' : '' }}">
                    📏 Length
                </button>
                <button wire:click="setGrowthChartType('weight')" 
                    class="btn-secondary" 
                    style="padding: 6px 14px; font-size: 0.75rem; {{ $growthChartType === 'weight' ? 'background:#1d4ed8; color:#fff;' : '' }}">
                    ⚖️ Weight
                </button>
            </div>
        </div>

        <div class="growth-chart-container" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 24px; overflow-x: auto;">
            {!! WHOGrowthChart::renderLegend() !!}
            <div style="overflow-x: auto; text-align: center;">
                {!! WHOGrowthChart::renderChart($growthChartType, $gender, $currentMeasurements) !!}
            </div>
        </div>

        {{-- Measurement History Log --}}
        @if(!empty($allMeasurements))
        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px;">
            <p style="font-weight: 700; font-size: 0.85rem; margin-bottom: 12px; color: #374151; display: flex; align-items: center; gap: 8px;">
                📋 Measurement History Log
                <span style="font-size: 0.7rem; font-weight: normal; color: #6b7280;">({{ count($allMeasurements) }} records)</span>
            </p>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem;">
                    <thead>
                        <tr style="background: #f3f4f6; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 8px 12px; text-align: left;">Date</th>
                            <th style="padding: 8px 12px; text-align: left;">Age (months)</th>
                            <th style="padding: 8px 12px; text-align: left;">{{ $growthChartType === 'length' ? 'Length (cm)' : 'Weight (kg)' }}</th>
                            <th style="padding: 8px 12px; text-align: left;">Z-Score</th>
                            <th style="padding: 8px 12px; text-align: left;">Recorded By</th>
                            <th style="padding: 8px 12px; text-align: left;">Recorded At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allMeasurements as $m)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 12px;">{{ \Carbon\Carbon::parse($m['date'])->format('M d, Y') }}</td>
                            <td style="padding: 8px 12px;">{{ $m['age_months'] }}</td>
                            <td style="padding: 8px 12px; font-weight: 600;">{{ $m['value'] }}</td>
                            <td style="padding: 8px 12px;">
                                @if($m['z_score'] !== null)
                                    <span style="display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; 
                                        {{ $m['z_score'] < -2 ? 'background: #fee2e2; color: #991b1b;' : '' }}
                                        {{ $m['z_score'] > 2 ? 'background: #fee2e2; color: #991b1b;' : '' }}
                                        {{ $m['z_score'] >= -2 && $m['z_score'] <= 2 ? 'background: #d1fae5; color: #065f46;' : '' }}">
                                        {{ $m['z_score'] }}
                                    </span>
                                @else
                                    <span style="color: #9ca3af;">—</span>
                                @endif
                            </td>
                            <td style="padding: 8px 12px;">
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                    <span>👩‍⚕️</span> {{ $m['recorded_by'] ?? 'Unknown' }}
                                </span>
                            </td>
                            <td style="padding: 8px 12px; font-size: 0.7rem; color: #6b7280;">
                                {{ isset($m['recorded_at']) ? \Carbon\Carbon::parse($m['recorded_at'])->format('M d, Y h:i A') : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Add Measurement Form --}}
        <div class="measurement-form" style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 10px; padding: 16px 20px; margin-top: 20px;">
            <p style="font-weight: 700; font-size: 0.85rem; margin-bottom: 12px; color: #166534;">
                ➕ Add New Measurement
            </p>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: end;">
                <div>
                    <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: #6b7280;">Date</label>
                    <input type="date" wire:model="measurementDate" class="form-input" style="margin-top: 4px;">
                </div>
                <div>
                    <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: #6b7280;">Weight (kg)</label>
                    <input type="number" step="0.01" wire:model="newWeight" placeholder="e.g., 3.5" class="form-input" style="margin-top: 4px;">
                </div>
                <div>
                    <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: #6b7280;">Length (cm)</label>
                    <input type="number" step="0.1" wire:model="newLength" placeholder="e.g., 52.0" class="form-input" style="margin-top: 4px;">
                </div>
                <div>
                    <button wire:click="saveGrowthMeasurement" wire:loading.attr="disabled" class="btn-primary" style="width: 100%;">
                        <span wire:loading.remove>💾 Save Measurement</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
            </div>
            <p style="font-size: 0.7rem; color: #6b7280; margin-top: 8px;">
                💡 Tip: You can enter weight OR length, or both. The chart will update automatically.
            </p>
        </div>

        {{-- ══ PATIENT FORMS ══════════════════════════════════════ --}}
        @elseif($activeTab === 'forms')
        @php
            $hasErRecord  = (bool) $visit->erRecord;
            $hasAdmRecord = (bool) $visit->admissionRecord;
            $hasConsent   = (bool) $visit->consentRecord;
            $hasHistory   = (bool) $visit->medicalHistory;
            $isErVisit    = $visit->visit_type === 'ER';
        @endphp
 
        <div class="sec-head">
            <h2 class="sec-title">Patient Forms</h2>
            <span style="font-size:.78rem;color:#6b7280;">All forms for this visit — read-only view</span>
        </div>
 
        {{-- 1. ER Record --}}
        @if($isErVisit)
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">🏥 ER Record (ER-001)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $hasErRecord ? 'background:#d1fae5;color:#065f46;' : 'background:#fef3c7;color:#92400e;' }}">
                    {{ $hasErRecord ? 'Saved' : 'Not yet filled' }}
                </span>
            </div>
            @if($hasErRecord)
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.er-record', ['visit' => $visit->id]) }}?readonly=1"
                    title="ER Record" style="width:100%;min-height:1100px;border:none;display:block;" loading="lazy"></iframe>
            </div>
            @else
            <div style="background:#fff;border:1.5px dashed #e5e7eb;border-radius:8px;padding:24px;text-align:center;">
                <p style="font-size:.82rem;color:#9ca3af;">ER Record has not been filled out by the clerk yet.</p>
            </div>
            @endif
        </div>
        @endif
 
        {{-- 2. Admission & Discharge Record --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">📋 Admission &amp; Discharge Record (ADM-001)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $hasAdmRecord ? 'background:#d1fae5;color:#065f46;' : 'background:#fef3c7;color:#92400e;' }}">
                    {{ $hasAdmRecord ? 'Saved' : 'Not yet filled' }}
                </span>
            </div>
            @if($hasAdmRecord)
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.adm-record', ['visit' => $visit->id]) }}?readonly=1"
                    title="Admission & Discharge Record" style="width:100%;min-height:1100px;border:none;display:block;" loading="lazy"></iframe>
            </div>
            @else
            <div style="background:#fff;border:1.5px dashed #e5e7eb;border-radius:8px;padding:24px;text-align:center;">
                <p style="font-size:.82rem;color:#9ca3af;">Admission &amp; Discharge Record has not been filled out by the clerk yet.</p>
            </div>
            @endif
        </div>
 
        {{-- 3. Consent to Care --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">📄 Consent to Care (NUR-002-1)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $hasConsent ? 'background:#d1fae5;color:#065f46;' : 'background:#fef3c7;color:#92400e;' }}">
                    {{ $hasConsent ? 'Saved' : 'Not yet filled' }}
                </span>
            </div>
            @if($hasConsent)
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.consent-to-care', ['visit' => $visit->id]) }}?readonly=1"
                    title="Consent to Care" style="width:100%;min-height:780px;border:none;display:block;" loading="lazy"></iframe>
            </div>
            @else
            <div style="background:#fff;border:1.5px dashed #e5e7eb;border-radius:8px;padding:24px;text-align:center;">
                <p style="font-size:.82rem;color:#9ca3af;">Consent to Care has not been filled out by the clerk yet.</p>
            </div>
            @endif
        </div>
 
        {{-- 4. History Form --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">📝 History Form (NUR-006)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $hasHistory ? 'background:#d1fae5;color:#065f46;' : 'background:#fef3c7;color:#92400e;' }}">
                    {{ $hasHistory ? 'Filled' : 'Not yet assessed' }}
                </span>
            </div>
            @if($hasHistory)
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.history-form', ['visit' => $visit->id]) }}"
                    title="History Form" style="width:100%;min-height:1200px;border:none;display:block;" loading="lazy"></iframe>
            </div>
            @else
            <div style="background:#fff;border:1.5px dashed #e5e7eb;border-radius:8px;padding:24px;text-align:center;">
                <p style="font-size:.82rem;color:#9ca3af;">History Form will appear here once the patient has been assessed by a doctor.</p>
            </div>
            @endif
        </div>
 
        {{-- 5. Physical Examination Form --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">🩺 Physical Examination Form (NUR-005)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $hasHistory ? 'background:#d1fae5;color:#065f46;' : 'background:#fef3c7;color:#92400e;' }}">
                    {{ $hasHistory ? 'Filled' : 'Not yet assessed' }}
                </span>
            </div>
            @if($hasHistory)
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.physical-exam-form', ['visit' => $visit->id]) }}"
                    title="Physical Examination Form" style="width:100%;min-height:1200px;border:none;display:block;" loading="lazy"></iframe>
            </div>
            @else
            <div style="background:#fff;border:1.5px dashed #e5e7eb;border-radius:8px;padding:24px;text-align:center;">
                <p style="font-size:.82rem;color:#9ca3af;">Physical Examination Form will appear here once the patient has been assessed by a doctor.</p>
            </div>
            @endif
        </div>
 
        {{-- 6. Vital Sign Monitoring Sheet (NUR-014) --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">📊 Vital Sign Monitoring Sheet (NUR-014)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                @php $vsCount = $this->vitalsCount; @endphp
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $vsCount > 0 ? 'background:#dbeafe;color:#1e40af;' : 'background:#f3f4f6;color:#6b7280;' }}">
                    {{ $vsCount > 0 ? $vsCount . ' entr' . ($vsCount === 1 ? 'y' : 'ies') : 'No entries yet' }}
                </span>
                <a href="{{ route('forms.vital-sign-monitoring-sheet', ['visit' => $visit->id]) }}"
                   target="_blank"
                   style="font-size:.72rem;font-weight:700;color:#2563eb;text-decoration:none;display:inline-flex;align-items:center;gap:4px;background:#eff6ff;border:1px solid #bfdbfe;padding:3px 10px;border-radius:5px;">
                    🖨️ Open / Print
                </a>
            </div>
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.vital-sign-monitoring-sheet', ['visit' => $visit->id]) }}"
                    title="Vital Sign Monitoring Sheet"
                    style="width:100%;min-height:900px;border:none;display:block;"
                    loading="lazy"></iframe>
            </div>
        </div>
 
        {{-- 7. IV / Blood Transfusion Sheet (NUR-012) --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">💧 IV / Blood Transfusion Sheet (NUR-012)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                @php $ivCnt = $this->ivEntriesCount; @endphp
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $ivCnt > 0 ? 'background:#ccfbf1;color:#0f766e;' : 'background:#f3f4f6;color:#6b7280;' }}">
                    {{ $ivCnt > 0 ? $ivCnt . ' entr' . ($ivCnt === 1 ? 'y' : 'ies') : 'No entries yet' }}
                </span>
                <a href="{{ route('forms.iv-bt-sheet', ['visit' => $visit->id]) }}"
                   target="_blank"
                   style="font-size:.72rem;font-weight:700;color:#0f766e;text-decoration:none;display:inline-flex;align-items:center;gap:4px;background:#f0fdfa;border:1px solid #99f6e4;padding:3px 10px;border-radius:5px;">
                    🖨️ Open / Print
                </a>
            </div>
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.iv-bt-sheet', ['visit' => $visit->id]) }}"
                    title="IV / Blood Transfusion Sheet"
                    style="width:100%;min-height:900px;border:none;display:block;"
                    loading="lazy"></iframe>
            </div>
        </div>
 
        {{-- 8. Nurse's Notes (NUR-010) ──────────────────────── --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">📝 Nurse's Notes (NUR-010)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                @php $notesCount = $allNotes->count(); @endphp
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $notesCount > 0 ? 'background:#ede9fe;color:#5b21b6;' : 'background:#f3f4f6;color:#6b7280;' }}">
                    {{ $notesCount > 0 ? $notesCount . ' note' . ($notesCount === 1 ? '' : 's') : 'No notes yet' }}
                </span>
                <a href="{{ route('forms.nurses-notes', ['visit' => $visit->id]) }}"
                   target="_blank"
                   style="font-size:.72rem;font-weight:700;color:#5b21b6;text-decoration:none;display:inline-flex;align-items:center;gap:4px;background:#faf5ff;border:1px solid #ddd6fe;padding:3px 10px;border-radius:5px;">
                    🖨️ Open / Print
                </a>
            </div>
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.nurses-notes', ['visit' => $visit->id]) }}"
                    title="Nurse's Notes"
                    style="width:100%;min-height:900px;border:none;display:block;"
                    loading="lazy"></iframe>
            </div>
        </div>

        {{-- 9. Medication Administration Record (NUR-011) --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">💊 Medication Administration Record (NUR-011)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                @php $marCount = $this->marEntriesCount; @endphp
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $marCount > 0 ? 'background:#fff1f2;color:#be123c;' : 'background:#f3f4f6;color:#6b7280;' }}">
                    {{ $marCount > 0 ? $marCount . ' medication' . ($marCount === 1 ? '' : 's') : 'No entries yet' }}
                </span>
                <a href="{{ route('forms.medication-records', ['visit' => $visit->id]) }}"
                   target="_blank"
                   style="font-size:.72rem;font-weight:700;color:#be123c;text-decoration:none;display:inline-flex;align-items:center;gap:4px;background:#fff1f2;border:1px solid #fecdd3;padding:3px 10px;border-radius:5px;white-space:nowrap;">
                    🖨️ Open / Print
                </a>
            </div>
            @if($marCount > 0)
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.medication-records', ['visit' => $visit->id]) }}"
                    title="Medication Administration Record"
                    style="width:100%;min-height:900px;border:none;display:block;"
                    loading="lazy"></iframe>
            </div>
            @else
            <div style="background:#fff;border:1.5px dashed #e5e7eb;border-radius:8px;padding:24px;text-align:center;">
                <p style="font-size:.82rem;color:#9ca3af;">No medications recorded yet. Go to the 💊 MAR tab to add medications and date columns.</p>
            </div>
            @endif
        </div>

        {{-- 10. Breastfeeding Observation Job Aid (NUR-044-0) — NICU only --}}
        @if($visit->visit_type === 'NICU')
        <div style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;white-space:nowrap;">🍼 Breastfeeding Observation Job Aid (NUR-044-0)</span>
                <div style="flex:1;border-top:1px solid #e5e7eb;"></div>
                @php $bfCount = $this->breastfeedingObservationsCount; @endphp
                <span style="font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;white-space:nowrap;{{ $bfCount > 0 ? 'background:#dcfce7;color:#166534;' : 'background:#f3f4f6;color:#6b7280;' }}">
                    {{ $bfCount > 0 ? $bfCount . ' observation' . ($bfCount === 1 ? '' : 's') : 'No observations yet' }}
                </span>
                <a href="{{ route('forms.breastfeeding-observation', ['visit' => $visit->id]) }}"
                   target="_blank"
                   style="font-size:.72rem;font-weight:700;color:#166534;text-decoration:none;display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;border:1px solid #bbf7d0;padding:3px 10px;border-radius:5px;white-space:nowrap;">
                    🖨️ Open / Print
                </a>
            </div>
            @if($bfCount > 0)
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <iframe src="{{ route('forms.breastfeeding-observation', ['visit' => $visit->id]) }}"
                    title="Breastfeeding Observation Job Aid"
                    style="width:100%;min-height:900px;border:none;display:block;"
                    loading="lazy"></iframe>
            </div>
            @else
            <div style="background:#fff;border:1.5px dashed #e5e7eb;border-radius:8px;padding:24px;text-align:center;">
                <p style="font-size:.82rem;color:#9ca3af;">No breastfeeding observations recorded yet. Go to the 🍼 Breastfeeding tab to add observations.</p>
            </div>
            @endif
        </div>
        @endif

        {{-- ══ MAR TAB CONTENT ══════════════════════════════════════════ --}}
        @elseif($activeTab === 'mar')
        @php
            $marDates   = $this->marDateColumns->dates ?? [];
            $marEntries = $this->marEntries;
        @endphp
 
        <div class="sec-head">
            <h2 class="sec-title">💊 Medication Administration Record (MAR)</h2>
            <span style="font-size:.78rem;color:#6b7280;">
                {{ count($marDates) }} date col{{ count($marDates) === 1 ? '' : 's' }}
                &nbsp;·&nbsp; {{ $marEntries->count() }} medication{{ $marEntries->count() === 1 ? '' : 's' }}
            </span>
        </div>
 
        {{-- ── Date column manager ─────────────────────────────── --}}
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;flex-wrap:wrap;">
            <span style="font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.04em;">Date Columns:</span>
            @foreach($marDates as $d)
            <span style="display:inline-flex;align-items:center;gap:4px;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:6px;padding:3px 10px;font-size:.75rem;font-weight:600;color:#374151;font-family:monospace;">
                {{ \Carbon\Carbon::parse($d)->format('M j') }}
                <button wire:click="marRemoveDate('{{ $d }}')"
                        type="button"
                        style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:.7rem;padding:0 2px;line-height:1;"
                        title="Remove date column">✕</button>
            </span>
            @endforeach
            <div style="display:flex;align-items:center;gap:6px;">
                <input type="date"
                       wire:model="marNewDate"
                       style="border:1px solid #d1d5db;border-radius:6px;padding:5px 8px;font-size:.78rem;color:#111827;background:#fff;outline:none;">
                <button wire:click="marAddDate" type="button"
                        style="background:#059669;color:#fff;border:none;border-radius:6px;padding:6px 14px;font-size:.78rem;font-weight:700;cursor:pointer;">
                    + Add Date
                </button>
            </div>
        </div>
 
        @if(empty($marDates))
        <div class="empty-state">
            <div class="empty-icon">💊</div>
            <p class="empty-title">No date columns yet</p>
            <p class="empty-sub">Use the date picker above to add the first date column, then add medication rows below.</p>
        </div>
        @else
 
        {{-- ── MAR Grid ────────────────────────────────────────── --}}
        <div class="mar-wrap">
            <table class="mar-table">
                <thead>
                    <tr>
                        <th class="col-med" style="text-align:left;padding:8px 10px;">Medication</th>
                        <th class="col-shift">Shift</th>
                        @foreach($marDates as $d)
                        <th style="min-width:56px;padding:5px 3px;">
                            <span class="mar-date-header">{{ \Carbon\Carbon::parse($d)->format('M j') }}</span>
                            <span class="mar-date-sub">{{ \Carbon\Carbon::parse($d)->format('D') }}</span>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
 
                @if($marEntries->isEmpty())
                <tr>
                    <td colspan="{{ 2 + count($marDates) }}"
                        style="padding:32px;text-align:center;color:#9ca3af;font-size:.82rem;font-style:italic;">
                        No medications yet — click "➕ Add Medication Row" below to add the first row.
                    </td>
                </tr>
                @else
 
                @foreach($marEntries as $entry)
                @foreach(['7-3', '3-11', '11-7'] as $shiftIdx => $shift)
                @php
                    $isFirst    = $shiftIdx === 0;
                    $shiftClass = match($shift) { '7-3' => 'mar-shift-73', '3-11' => 'mar-shift-311', '11-7' => 'mar-shift-117' };
                @endphp
                <tr wire:key="mar-{{ $entry->id }}-{{ $shift }}"
                    class="{{ $isFirst ? 'mar-group-start' : '' }}">
 
                    {{-- Medication name — editable inline, rowspan 3 --}}
                    @if($isFirst)
                    <td class="col-med" rowspan="3" style="text-align:left;vertical-align:middle;">
                        <div style="display:flex;align-items:center;gap:2px;">
                            <input type="text"
                                   class="mar-med-input"
                                   value="{{ $entry->medication_name }}"
                                   placeholder="Type medication here…"
                                   wire:key="medname-{{ $entry->id }}"
                                   @blur="$wire.marUpdateMedName({{ $entry->id }}, $event.target.value)"
                                   title="Click to type medication name">
                            <button wire:click="marDeleteMed({{ $entry->id }})"
                                    type="button"
                                    class="btn-mar-del"
                                    title="Remove this medication row">🗑</button>
                        </div>
                    </td>
                    @endif
 
                    {{-- Shift label --}}
                    <td class="col-shift {{ $shiftClass }}">{{ $shift }}</td>
 
                    {{-- Date cells — time input, saves on blur --}}
                    @foreach($marDates as $d)
                    @php $cellVal = $entry->getTime($d, $shift); @endphp
                    <td style="padding:0;">
                        <input type="time"
                               class="mar-cell-input"
                               value="{{ $cellVal }}"
                               wire:key="cell-{{ $entry->id }}-{{ $d }}-{{ $shift }}"
                               @blur="$wire.marSaveCell({{ $entry->id }}, '{{ $d }}', '{{ $shift }}', $event.target.value)"
                               title="{{ $d }} / {{ $shift }}">
                    </td>
                    @endforeach
 
                </tr>
                @endforeach
                @endforeach
 
                @endif
 
                </tbody>
            </table>
        </div>
 
        @endif {{-- empty dates --}}
 
        {{-- ── Add medication row button — always visible ──────── --}}
        <div style="margin-top:14px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <button wire:click="marAddMedication"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60"
                    type="button"
                    class="btn-mar-add-med">
                <span wire:loading.remove wire:target="marAddMedication">➕ Add Medication Row</span>
                <span wire:loading wire:target="marAddMedication">Adding…</span>
            </button>
            <span style="font-size:.72rem;color:#9ca3af;">
                Click multiple times to add several rows at once, then fill in the medication names.
            </span>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════════
            TPR GRAPHIC RECORD TAB CONTENT
        ══════════════════════════════════════════════════════════════════════════ --}}

        @elseif($activeTab === 'tpr')

        @php
            $tprVitals    = $this->allVitals;
            $admittedAt   = $visit->clerk_admitted_at;
            $tprIoEntries = $this->tprIoEntries;

            $labels      = [];
            $tempPoints  = [];
            $pulsePoints = [];
            $rrPoints    = [];

            foreach ($tprVitals as $v) {
                $labels[]      = $v->taken_at->timezone('Asia/Manila')->format('M j H:i');
                $tempPoints[]  = $v->temperature      !== null ? (float) $v->temperature      : null;
                $pulsePoints[] = $v->pulse_rate       !== null ? (float) $v->pulse_rate       : null;
                $rrPoints[]    = $v->respiratory_rate !== null ? (float) $v->respiratory_rate : null;
            }

            $uid       = 'tpr-' . $visit->id;
            $chartJson = json_encode([
                'labels' => $labels,
                'temp'   => $tempPoints,
                'pulse'  => $pulsePoints,
                'rr'     => $rrPoints,
            ], JSON_NUMERIC_CHECK);
        @endphp

        {{-- ── Styles (unchanged) ──────────────────────────────────────────────── --}}
        <style>
        .tpr-chart-wrap { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:16px 18px; margin-bottom:16px; }
        .dark .tpr-chart-wrap { background:#1f2937; border-color:#374151; }
        .tpr-chart-title { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#6b7280; margin-bottom:8px; display:flex; align-items:center; gap:8px; }
        .tpr-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; display:inline-block; }
        .tpr-num-table-wrap { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow-x:auto; margin-bottom:24px; }
        .dark .tpr-num-table-wrap { background:#1f2937; border-color:#374151; }
        .tpr-num-table { width:100%; min-width:600px; border-collapse:collapse; font-size:.78rem; }
        .tpr-num-table th { background:#f9fafb; padding:8px 12px; text-align:center; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; border-bottom:2px solid #e5e7eb; white-space:nowrap; }
        .dark .tpr-num-table th { background:#111827; border-bottom-color:#374151; color:#9ca3af; }
        .tpr-num-table td { padding:8px 12px; border-bottom:1px solid #f3f4f6; text-align:center; color:#374151; }
        .dark .tpr-num-table td { border-bottom-color:#374151; color:#d1d5db; }
        .tpr-num-table tr:last-child td { border-bottom:none; }
        .tpr-val-abnormal { color:#dc2626 !important; font-weight:700; }
        .tpr-val-normal { color:#059669; font-weight:600; }
        .tpr-val-na { color:#d1d5db; font-size:.72rem; }
        .tpr-io-section { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
        .dark .tpr-io-section { background:#1f2937; border-color:#374151; }
        .tpr-io-head { display:flex; align-items:center; justify-content:space-between; padding:14px 18px; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
        .dark .tpr-io-head { background:#111827; border-bottom-color:#374151; }
        .tpr-io-form { background:#f0fdf4; border:1.5px solid #86efac; border-radius:8px; padding:16px 18px; margin:14px 18px; }
        .dark .tpr-io-form { background:#022c22; border-color:#16a34a; }
        .tpr-io-form-grid { display:grid; grid-template-columns:150px 140px 120px 120px 140px 1fr; gap:12px; align-items:end; margin-bottom:12px; }
        @media(max-width:900px){ .tpr-io-form-grid { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:600px){ .tpr-io-form-grid { grid-template-columns:1fr 1fr; } }
        .tpr-io-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; display:block; margin-bottom:4px; }
        .tpr-io-input { border:1px solid #d1d5db; border-radius:7px; padding:8px 10px; font-size:.875rem; background:#fff; color:#111827; outline:none; width:100%; }
        .dark .tpr-io-input { background:#374151; border-color:#4b5563; color:#f3f4f6; }
        .tpr-io-input:focus { border-color:#059669; box-shadow:0 0 0 2px rgba(5,150,105,.12); }
        .tpr-io-table { width:100%; border-collapse:collapse; font-size:.8rem; }
        .tpr-io-table th { padding:9px 14px; text-align:left; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#6b7280; background:#f9fafb; border-bottom:1px solid #e5e7eb; white-space:nowrap; }
        .dark .tpr-io-table th { background:#111827; color:#9ca3af; border-bottom-color:#374151; }
        .tpr-io-table td { padding:10px 14px; border-bottom:1px solid #f3f4f6; color:#374151; vertical-align:middle; }
        .dark .tpr-io-table td { border-bottom-color:#374151; color:#d1d5db; }
        .tpr-io-table tr:last-child td { border-bottom:none; }
        .tpr-io-table tbody tr:hover td { background:#f0fdf4; }
        .tpr-shift-badge { font-size:.68rem; font-weight:700; padding:2px 8px; border-radius:9999px; white-space:nowrap; }
        .tpr-shift-73  { background:#eff6ff; color:#1d4ed8; }
        .tpr-shift-311 { background:#f5f3ff; color:#5b21b6; }
        .tpr-shift-117 { background:#f0fdfa; color:#0f766e; }
        .btn-tpr-add  { background:#059669; color:#fff; border:none; border-radius:7px; padding:8px 16px; font-size:.8rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; }
        .btn-tpr-add:hover { background:#047857; }
        .btn-tpr-save { background:#059669; color:#fff; border:none; border-radius:7px; padding:8px 20px; font-size:.83rem; font-weight:700; cursor:pointer; }
        .btn-tpr-save:hover { background:#047857; }
        .btn-tpr-del  { background:none; border:none; color:#d1d5db; cursor:pointer; font-size:.75rem; padding:3px 6px; border-radius:4px; }
        .btn-tpr-del:hover { color:#ef4444; background:#fee2e2; }
        </style>

        {{-- ── Section header ──────────────────────────────────────────── --}}
        <div class="sec-head">
            <h2 class="sec-title">🌡️ TPR Graphic Record</h2>
            <span style="font-size:.78rem;color:#6b7280;">
                {{ $tprVitals->count() }} vital reading{{ $tprVitals->count() !== 1 ? 's' : '' }}
                @if($admittedAt)
                    &nbsp;·&nbsp; Day {{ (int) $admittedAt->diffInDays(now()) + 1 }} of admission
                @endif
            </span>
        </div>

        @if($tprVitals->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">🌡️</div>
            <p class="empty-title">No vital signs recorded yet</p>
            <p class="empty-sub">Go to the 📊 Vital Signs tab to add the first vital signs entry.</p>
        </div>
        @else

        @php
        /**
        * Render a line chart as an inline SVG string.
        * Zero JS dependencies — pure PHP/SVG.
        */
        function tprSvgChart(
            array  $labels,
            array  $values,
            string $color,
            float  $yMin,
            float  $yMax,
            float  $yStep,
            string $unit
        ): string {
            $svgW  = 700; $svgH  = 210;
            $padL  = 54;  $padR  = 20;
            $padT  = 18;  $padB  = 50;
            $cW    = $svgW - $padL - $padR;
            $cH    = $svgH - $padT - $padB;
            $range = $yMax - $yMin;
            $n     = count($labels);

            /** Map a data-value → SVG Y pixel */
            $toY = static function (float $v) use ($padT, $cH, $yMin, $range): float {
                return round($padT + $cH - (($v - $yMin) / $range * $cH), 2);
            };

            /** Map a label index → SVG X pixel */
            $toX = static function (int $i) use ($padL, $cW, $n): float {
                return round($padL + ($n > 1 ? ($i / ($n - 1)) * $cW : $cW / 2), 2);
            };

            $hex  = htmlspecialchars($color, ENT_QUOTES);
            $out  = "<svg viewBox='0 0 {$svgW} {$svgH}' xmlns='http://www.w3.org/2000/svg'"
                . " style='width:100%;height:100%;display:block;'>";

            // ── background ──────────────────────────────────────────────
            $out .= "<rect x='0' y='0' width='{$svgW}' height='{$svgH}' fill='transparent'/>";

            // ── horizontal grid lines + Y-axis labels ───────────────────
            $steps = (int) round(($yMax - $yMin) / $yStep);
            for ($s = 0; $s <= $steps; $s++) {
                $val  = $yMin + $s * $yStep;
                $yPos = $toY((float) $val);
                $out .= "<line x1='{$padL}' y1='{$yPos}' x2='" . ($svgW - $padR) . "' y2='{$yPos}'"
                    . " stroke='#e5e7eb' stroke-width='1' stroke-dasharray='3 3'/>";
                $label = (strpos((string) $yStep, '.') !== false)
                    ? number_format((float) $val, 1)
                    : (int) $val;
                $out .= "<text x='" . ($padL - 6) . "' y='{$yPos}'"
                    . " text-anchor='end' dominant-baseline='middle'"
                    . " font-size='10' fill='#9ca3af'>{$label}</text>";
            }

            // ── vertical grid lines + X-axis labels ─────────────────────
            for ($i = 0; $i < $n; $i++) {
                $x    = $toX($i);
                $xBot = $padT + $cH;
                $out .= "<line x1='{$x}' y1='{$padT}' x2='{$x}' y2='{$xBot}'"
                    . " stroke='#f3f4f6' stroke-width='1'/>";
                $lbl  = htmlspecialchars($labels[$i], ENT_QUOTES);
                $ty   = $xBot + 12;
                $out .= "<text x='{$x}' y='{$ty}'"
                    . " font-size='9' fill='#9ca3af' text-anchor='end'"
                    . " transform='rotate(-38 {$x} {$ty})'>{$lbl}</text>";
            }

            // ── axes ─────────────────────────────────────────────────────
            $axisBot = $padT + $cH;
            $axisR   = $svgW - $padR;
            $out .= "<line x1='{$padL}' y1='{$padT}' x2='{$padL}' y2='{$axisBot}'"
                . " stroke='#d1d5db' stroke-width='1.5'/>";
            $out .= "<line x1='{$padL}' y1='{$axisBot}' x2='{$axisR}' y2='{$axisBot}'"
                . " stroke='#d1d5db' stroke-width='1.5'/>";

            // ── collect non-null points ───────────────────────────────────
            $pts = [];
            for ($i = 0; $i < $n; $i++) {
                if ($values[$i] !== null) {
                    $pts[] = ['x' => $toX($i), 'y' => $toY((float) $values[$i]),
                            'v' => $values[$i], 'lbl' => $labels[$i]];
                }
            }

            if (count($pts) >= 2) {
                // Area fill
                $baseY = $padT + $cH;
                $area  = "M {$pts[0]['x']} {$baseY} L {$pts[0]['x']} {$pts[0]['y']}";
                foreach (array_slice($pts, 1) as $p) {
                    $area .= " L {$p['x']} {$p['y']}";
                }
                $area .= ' L ' . end($pts)['x'] . " {$baseY} Z";
                $out  .= "<path d='{$area}' fill='{$hex}' fill-opacity='0.09'/>";

                // Line
                $line = "M {$pts[0]['x']} {$pts[0]['y']}";
                foreach (array_slice($pts, 1) as $p) {
                    $line .= " L {$p['x']} {$p['y']}";
                }
                $out .= "<path d='{$line}' fill='none' stroke='{$hex}'"
                    . " stroke-width='2.5' stroke-linejoin='round' stroke-linecap='round'/>";
            } elseif (count($pts) === 1) {
                // Single point — just draw the dot
            }

            // ── dots + value labels ───────────────────────────────────────
            foreach ($pts as $p) {
                $tip  = htmlspecialchars("{$p['lbl']}: {$p['v']} {$unit}", ENT_QUOTES);
                $out .= "<circle cx='{$p['x']}' cy='{$p['y']}' r='5'"
                    . " fill='{$hex}' stroke='white' stroke-width='2'>"
                    . "<title>{$tip}</title></circle>";
                $vy   = round($p['y'] - 11, 2);
                $out .= "<text x='{$p['x']}' y='{$vy}'"
                    . " text-anchor='middle' font-size='10' font-weight='700'"
                    . " fill='{$hex}'>{$p['v']}</text>";
            }

            // ── "no data" message ─────────────────────────────────────────
            if (empty($pts)) {
                $cx   = round($svgW / 2, 0);
                $cy   = round($svgH / 2, 0);
                $out .= "<text x='{$cx}' y='{$cy}' text-anchor='middle'"
                    . " font-size='13' fill='#d1d5db'>No data</text>";
            }

            $out .= '</svg>';
            return $out;
        }
        @endphp

        {{-- ── Temperature chart ─────────────────────────────────────── --}}
        <div class="tpr-chart-wrap">
            <div class="tpr-chart-title">
                <span class="tpr-dot" style="background:#ef4444;"></span>
                Temperature (°C)
                <span style="font-size:.67rem;color:#9ca3af;font-weight:400;margin-left:auto;">Normal: 36.0–37.5°C</span>
            </div>
            <div style="position:relative;height:220px;">
                {!! tprSvgChart($labels, $tempPoints, '#ef4444', 34, 42, 0.5, '°C') !!}
            </div>
        </div>

        {{-- ── Pulse Rate chart ───────────────────────────────────────── --}}
        <div class="tpr-chart-wrap">
            <div class="tpr-chart-title">
                <span class="tpr-dot" style="background:#f97316;"></span>
                Pulse Rate (/min)
                <span style="font-size:.67rem;color:#9ca3af;font-weight:400;margin-left:auto;">Normal: 60–100 bpm</span>
            </div>
            <div style="position:relative;height:220px;">
                {!! tprSvgChart($labels, $pulsePoints, '#f97316', 40, 180, 20, '/min') !!}
            </div>
        </div>

        {{-- ── Respiratory Rate chart ─────────────────────────────────── --}}
        <div class="tpr-chart-wrap">
            <div class="tpr-chart-title">
                <span class="tpr-dot" style="background:#3b82f6;"></span>
                Respiratory Rate (/min)
                <span style="font-size:.67rem;color:#9ca3af;font-weight:400;margin-left:auto;">Normal: 12–20 /min</span>
            </div>
            <div style="position:relative;height:220px;">
                {!! tprSvgChart($labels, $rrPoints, '#3b82f6', 8, 40, 4, '/min') !!}
            </div>
        </div>

        @endif {{-- tprVitals not empty --}}

        {{-- ════════════════════════════════════════════════════════════════
            URINE & STOOL RECORDING
        ════════════════════════════════════════════════════════════════ --}}
        <div class="tpr-io-section">

            <div class="tpr-io-head">
                <div>
                    <span style="font-size:.88rem;font-weight:700;color:#111827;">🚽 Urine &amp; Stool Output</span>
                    <span style="font-size:.72rem;color:#9ca3af;margin-left:8px;">Per shift · Per day</span>
                </div>
                @if(!$tprAddingIo && !$tprIoEditId)
                <button wire:click="tprOpenAddIo" type="button" class="btn-tpr-add">
                    ➕ Add Entry
                </button>
                @endif
            </div>

            {{-- ── Add / Edit form ─────────────────────────────────────── --}}
            @if($tprAddingIo || $tprIoEditId)
            <div class="tpr-io-form" x-data="{ stoolCount: @js($tprIoStool) }">
                <p style="font-size:.82rem;font-weight:700;color:#065f46;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid #bbf7d0;">
                    {{ $tprIoEditId ? '✎ Edit Entry' : '➕ New Urine & Stool Entry' }}
                </p>

                <div class="tpr-io-form-grid">

                    <div>
                        <label class="tpr-io-label">Date *</label>
                        <input type="date" wire:model="tprIoDate" class="tpr-io-input">
                    </div>

                    <div>
                        <label class="tpr-io-label">Shift *</label>
                        <select wire:model="tprIoShift" class="tpr-io-input" style="cursor:pointer;">
                            <option value="">— Select —</option>
                            <option value="7-3">7-3 (7AM–3PM)</option>
                            <option value="3-11">3-11 (3PM–11PM)</option>
                            <option value="11-7">11-7 (11PM–7AM)</option>
                        </select>
                    </div>

                    <div>
                        <label class="tpr-io-label">Urine (times)</label>
                        <input type="number" wire:model="tprIoUrine" min="0" max="99"
                            class="tpr-io-input" placeholder="0">
                    </div>

                    <div>
                        <label class="tpr-io-label">Stool Count</label>
                        <input type="number" 
                            x-model="stoolCount"
                            wire:model.live="tprIoStool" 
                            min="0" max="99"
                            class="tpr-io-input" placeholder="0">
                    </div>

                    <!-- Stool Type -->
                    <div x-show="stoolCount && stoolCount > 0" x-transition>
                        <label class="tpr-io-label">Stool Type</label>
                        <select wire:model="tprIoStoolType" class="tpr-io-input" style="cursor:pointer;">
                            <option value="">— Type —</option>
                            <option value="formed">Formed</option>
                            <option value="loose">Loose</option>
                            <option value="watery">Watery</option>
                            <option value="bloody">Bloody</option>
                            <option value="none">None</option>
                        </select>
                    </div>

                    <div>
                        <label class="tpr-io-label">Notes</label>
                        <input type="text" wire:model="tprIoNotes" class="tpr-io-input"
                            placeholder="Optional remarks…">
                    </div>

                </div>

                <div style="display:flex;gap:10px;">
                    <button wire:click="tprSaveIo"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60"
                            type="button" class="btn-tpr-save">
                        <span wire:loading.remove wire:target="tprSaveIo">💾 Save</span>
                        <span wire:loading wire:target="tprSaveIo">Saving…</span>
                    </button>
                    <button wire:click="tprCancelIo" type="button" class="btn-secondary">Cancel</button>
                </div>
            </div>
            @endif

            {{-- ── Entries table ───────────────────────────────────────── --}}
            @if($tprIoEntries->isEmpty())
            <div style="text-align:center;padding:36px 24px;">
                <div style="font-size:2rem;margin-bottom:8px;">🚽</div>
                <p style="font-size:.88rem;font-weight:700;color:#374151;margin-bottom:4px;">No urine &amp; stool entries yet</p>
                <p style="font-size:.78rem;color:#9ca3af;">Click "Add Entry" above to record per-shift output.</p>
            </div>
            @else
            <table class="tpr-io-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>Urine (times)</th>
                        <th>Stool</th>
                        <th>Stool Type</th>
                        <th style="text-align:left;">Nurse</th>
                        <th style="text-align:left;">Notes</th>
                        <th style="width:60px;text-align:center;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tprIoEntries as $io)
                    @php
                        $shiftClass = match($io->shift) {
                            '7-3'  => 'tpr-shift-73',
                            '3-11' => 'tpr-shift-311',
                            '11-7' => 'tpr-shift-117',
                            default => '',
                        };
                    @endphp
                    <tr wire:key="tprio-{{ $io->id }}">
                        <td style="font-family:monospace;font-size:.78rem;font-weight:600;">
                            {{ $io->date->format('M j, Y') }}
                        </td>
                        <td><span class="tpr-shift-badge {{ $shiftClass }}">{{ $io->shift }}</span></td>
                        <td style="font-weight:700;color:#0284c7;">
                            {{ $io->urine_count !== null ? $io->urine_count . '×' : '—' }}
                        </td>
                        <td style="font-weight:700;color:#7c3aed;">
                            {{ $io->stool_count !== null ? $io->stool_count . '×' : '—' }}
                        </td>
                        <td>
                            @if($io->stool_count && $io->stool_count > 0 && $io->stool_type)
                                <span style="font-size:.72rem;background:#f3f4f6;padding:1px 7px;border-radius:4px;color:#374151;font-weight:600;">
                                    {{ $io->stool_type_label }}
                                </span>
                            @else
                                <span style="color:#d1d5db;font-size:.72rem;">—</span>
                            @endif
                        </td>
                        <td style="font-size:.75rem;color:#6b7280;">{{ $io->nurse_name }}</td>
                        <td style="font-size:.75rem;color:#6b7280;max-width:140px;">
                            {{ $io->notes ? \Str::limit($io->notes, 35) : '—' }}
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;gap:3px;justify-content:center;">
                                @if(!$tprAddingIo && !$tprIoEditId)
                                <button wire:click="tprOpenEditIo({{ $io->id }})" type="button"
                                        style="font-size:.7rem;color:#6b7280;background:#f3f4f6;border:none;border-radius:4px;padding:3px 7px;cursor:pointer;">✎</button>
                                <button wire:click="tprDeleteIo({{ $io->id }})" type="button"
                                        class="btn-tpr-del">🗑</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Daily totals --}}
            @php
                $dailyTotals = $tprIoEntries->groupBy(fn($io) => $io->date->format('Y-m-d'));
            @endphp
            @if($dailyTotals->count() > 0)
            <div style="padding:10px 18px;border-top:1px solid #f3f4f6;display:flex;flex-wrap:wrap;gap:12px;background:#f9fafb;">
                <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">24-hr Totals:</span>
                @foreach($dailyTotals as $date => $entries)
                @php
                    $totalUrine = $entries->sum('urine_count');
                    $totalStool = $entries->sum('stool_count');
                @endphp
                <span style="font-size:.75rem;color:#374151;background:#fff;border:1px solid #e5e7eb;padding:2px 10px;border-radius:6px;">
                    <strong>{{ \Carbon\Carbon::parse($date)->format('M j') }}</strong>
                    &nbsp;·&nbsp;
                    🚱 <span style="color:#0284c7;font-weight:700;">{{ $totalUrine }}×</span>
                    &nbsp;·&nbsp;
                    🚽 <span style="color:#7c3aed;font-weight:700;">{{ $totalStool }}×</span>
                </span>
                @endforeach
            </div>
            @endif
            @endif

        </div>

        {{-- ══ PLACEHOLDER TABS ════════════════════════════════════ --}}

        @elseif($activeTab === 'io')
        @include('filament.nurse.pages.partials.placeholder', ['icon'=>'📏','title'=>'Intake & Output Record','desc'=>'Monitor all fluid intake (oral, IV, NG) and output (urine, drain, emesis, stool) with shift and 24-hour totals.','full'=>true])

        @elseif($activeTab === 'handover')
        @include('filament.nurse.pages.partials.placeholder', ['icon'=>'🔄','title'=>'Nursing Handover / Endorsement','desc'=>'Structured shift-to-shift endorsement using SBAR format for safe patient handover.','full'=>true])

        @endif

    </div>{{-- /.chart-content --}}

</div>{{-- /.chart-page --}}

@else
<div style="text-align:center;padding:60px 20px;">
    <p style="color:#9ca3af;margin-bottom:10px;">Visit not found or not accessible.</p>
    <button wire:click="goBack" type="button" style="color:#f43f5e;font-size:.875rem;background:none;border:none;cursor:pointer;">← Back to Patient List</button>
</div>
@endif

</x-filament-panels::page>