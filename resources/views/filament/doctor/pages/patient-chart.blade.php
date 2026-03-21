<x-filament-panels::page>

<style>
/* ── Page wrapper ─────────────────────────────────────────────────── */
.chart-page { display:flex;flex-direction:column;gap:0;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;background:#fff; }
.dark .chart-page { background:#111827;border-color:#374151; }

/* ── Patient header ───────────────────────────────────────────────── */
.chart-header { background:linear-gradient(135deg,#1e3a5f 0%,#1d4ed8 100%);padding:16px 24px;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap; }
.chart-header-left { flex:1;min-width:200px; }
.chart-header-left .patient-name { font-size:1.1rem;font-weight:800;color:#fff;letter-spacing:.02em; }
.chart-header-left .case-no { font-family:monospace;font-size:.78rem;color:#93c5fd;margin-top:2px; }
.chart-header-pills { display:flex;flex-wrap:wrap;gap:10px;align-items:center; }
.h-pill { background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:6px;padding:5px 14px;text-align:center; }
.h-pill .pill-label { font-size:.6rem;text-transform:uppercase;letter-spacing:.06em;color:#93c5fd; }
.h-pill .pill-value { font-size:.82rem;font-weight:700;color:#fff; }
.h-service-badge { background:#059669;color:#fff;font-size:.72rem;font-weight:700;padding:4px 14px;border-radius:9999px; }
.btn-back-header { display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;font-size:.78rem;font-weight:600;padding:7px 14px;border-radius:6px;text-decoration:none; }
.btn-back-header:hover { background:rgba(255,255,255,.25); }

/* ── Tabs ─────────────────────────────────────────────────────────── */
.chart-tabs { display:flex;border-bottom:2px solid #e5e7eb;background:#fff;padding:0 20px;overflow-x:auto; }
.dark .chart-tabs { background:#1f2937;border-bottom-color:#374151; }
.chart-tab { display:inline-flex;align-items:center;gap:7px;padding:13px 18px;font-size:.83rem;font-weight:600;color:#6b7280;cursor:pointer;border:none;background:none;border-bottom:2.5px solid transparent;margin-bottom:-2px;white-space:nowrap;transition:color .15s,border-color .15s; }
.chart-tab:hover { color:#374151; }
.dark .chart-tab { color:#9ca3af; }
.chart-tab.active { color:#1d4ed8;border-bottom-color:#1d4ed8;font-weight:700; }
.dark .chart-tab.active { color:#60a5fa;border-bottom-color:#60a5fa; }
.tab-icon { font-size:.95rem; }
.tab-badge { background:#ef4444;color:#fff;font-size:.62rem;font-weight:700;padding:1px 5px;border-radius:9999px;min-width:18px;text-align:center; }
.tab-badge-warn { background:#f59e0b; }
.tab-badge-green { background:#059669; }

/* ── Tab content ─────────────────────────────────────────────────── */
.chart-content { padding:24px 28px;background:#f9fafb;min-height:420px; }
.dark .chart-content { background:#111827; }

/* ── Section heading ─────────────────────────────────────────────── */
.sec-head { display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;padding-bottom:10px;border-bottom:1px solid #e5e7eb; }
.dark .sec-head { border-bottom-color:#374151; }
.sec-title { font-size:.95rem;font-weight:700;color:#111827; }
.dark .sec-title { color:#f3f4f6; }

.placeholder-card { text-align:center;padding:52px 24px;background:#fff;border:1.5px dashed #e5e7eb;border-radius:8px; }
.dark .placeholder-card { background:#1f2937;border-color:#374151; }
.placeholder-card .ph-icon { font-size:2.6rem;margin-bottom:10px; }
.placeholder-card .ph-title { font-size:.92rem;font-weight:700;color:#374151;margin-bottom:4px; }
.dark .placeholder-card .ph-title { color:#e5e7eb; }
.placeholder-card .ph-sub { font-size:.8rem;color:#9ca3af; }

/* ── Document link cards ─────────────────────────────────────────── */
.doc-card { background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:20px 22px;display:flex;align-items:center;gap:16px;cursor:pointer;text-decoration:none;transition:border-color .15s,box-shadow .15s; }
.dark .doc-card { background:#1f2937;border-color:#374151; }
.doc-card:hover { border-color:#1d4ed8;box-shadow:0 2px 12px rgba(29,78,216,.12); }
.doc-card-icon { font-size:2rem;flex-shrink:0; }
.doc-card-body { flex:1; }
.doc-card-title { font-size:.92rem;font-weight:700;color:#111827;margin-bottom:3px; }
.dark .doc-card-title { color:#f3f4f6; }
.doc-card-meta { font-size:.75rem;color:#6b7280; }
.doc-card-arrow { font-size:1.1rem;color:#9ca3af;flex-shrink:0; }
.doc-card-label { font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:2px 8px;border-radius:4px;margin-bottom:4px;display:inline-block; }
.doc-card-label-blue   { background:#eff6ff;color:#1d4ed8; }
.doc-card-label-green  { background:#f0fdf4;color:#065f46; }
.doc-card-label-purple { background:#faf5ff;color:#6d28d9; }

/* Vitals */
.vitals-wrap { background:#fff;border:1px solid #e5e7eb;border-radius:8px;overflow:auto; }
.dark .vitals-wrap { background:#1f2937;border-color:#374151; }
.vitals-table { width:100%;border-collapse:collapse;font-size:.82rem; }
.vitals-table th { background:#f3f4f6;padding:8px 11px;text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;border-bottom:1px solid #e5e7eb; }
.dark .vitals-table th { background:#111827;color:#9ca3af;border-bottom-color:#374151; }
.vitals-table td { padding:9px 11px;border-bottom:1px solid #f3f4f6;color:#374151; }
.dark .vitals-table td { border-bottom-color:#1f2937;color:#d1d5db; }
.vitals-table tr:last-child td { border-bottom:none; }
.vitals-table tr:hover td { background:#f9fafb; }
.dark .vitals-table tr:hover td { background:rgba(255,255,255,.03); }
.abnormal { color:#dc2626 !important;font-weight:700; }

/* ── Orders ─────────────────────────────────────────────────────── */
.order-group-header { display:flex;align-items:center;gap:10px;margin-bottom:8px; }
.order-group-label { font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;white-space:nowrap; }
.order-group-line { flex:1;border-top:1px solid #e5e7eb; }
.dark .order-group-line { border-top-color:#374151; }
.order-group-doc { font-size:.7rem;color:#9ca3af;white-space:nowrap; }
.order-item { background:#fff;border:1px solid #e5e7eb;border-radius:7px;padding:11px 14px;margin-bottom:7px;display:grid;grid-template-columns:1fr auto;gap:10px;align-items:start; }
.dark .order-item { background:#1f2937;border-color:#374151; }
.order-item:hover { border-color:#d1d5db; }
.order-num { font-size:.68rem;color:#9ca3af;font-family:monospace;margin-bottom:2px; }
.order-text { font-size:.875rem;color:#111827;font-weight:500;line-height:1.4; }
.dark .order-text { color:#f3f4f6; }
.order-meta { font-size:.7rem;color:#9ca3af;margin-top:3px; }
.status-badge { display:inline-block;padding:2px 10px;border-radius:9999px;font-size:.68rem;font-weight:700;white-space:nowrap; }
.status-pending      { background:#fef3c7;color:#92400e; }
.status-carried      { background:#d1fae5;color:#065f46; }
.status-discontinued { background:#fee2e2;color:#991b1b; }
.order-text-discontinued { text-decoration:line-through;opacity:.6; }
.btn-discontinue { font-size:.7rem;color:#9ca3af;background:none;border:1px solid #e5e7eb;border-radius:5px;padding:3px 9px;cursor:pointer;margin-top:4px; }
.btn-discontinue:hover { border-color:#dc2626;color:#dc2626; }

/* ── Write-order form ────────────────────────────────────────────── */
.order-form-wrap { background:#fff;border:1.5px solid #3b82f6;border-radius:8px;padding:18px 20px;margin-bottom:20px; }
.dark .order-form-wrap { background:#1f2937;border-color:#2563eb; }
.order-form-title { font-size:.83rem;font-weight:700;color:#1d4ed8;margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid #eff6ff; }
.dark .order-form-title { border-bottom-color:#1e3a5f; }
.order-line-row { display:grid;grid-template-columns:1fr auto;gap:8px;align-items:center;margin-bottom:7px; }
.ol-input { width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 11px;font-size:.85rem;background:#fff;color:#111827;outline:none; }
.dark .ol-input { background:#374151;border-color:#4b5563;color:#f3f4f6; }
.ol-input:focus { border-color:#3b82f6;box-shadow:0 0 0 2px rgba(59,130,246,.15); }
.btn-remove-line { background:none;border:1px solid #e5e7eb;color:#9ca3af;border-radius:5px;padding:7px 10px;cursor:pointer;font-size:.78rem;flex-shrink:0;line-height:1; }
.btn-remove-line:hover { border-color:#dc2626;color:#dc2626; }
.btn-add-line { background:none;border:1px dashed #9ca3af;border-radius:6px;padding:7px 14px;font-size:.8rem;color:#6b7280;cursor:pointer;width:100%;margin:8px 0 0; }
.btn-add-line:hover { border-color:#3b82f6;color:#3b82f6; }
.btn-primary { background:#1d4ed8;color:#fff;border:none;border-radius:7px;padding:9px 22px;font-size:.85rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px; }
.btn-primary:hover { background:#1e40af; }
.btn-secondary { background:#fff;color:#374151;border:1px solid #e5e7eb;border-radius:7px;padding:9px 18px;font-size:.85rem;font-weight:600;cursor:pointer; }
.dark .btn-secondary { background:#374151;color:#e5e7eb;border-color:#4b5563; }
.btn-secondary:hover { background:#f3f4f6; }
.btn-write { background:#059669;color:#fff;border:none;border-radius:7px;padding:9px 18px;font-size:.83rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px; }
.btn-write:hover { background:#047857; }
.btn-write.is-cancel { background:#6b7280; }
.btn-write.is-cancel:hover { background:#4b5563; }
.btn-lab { background:#7c3aed;color:#fff;border:none;border-radius:7px;padding:9px 18px;font-size:.83rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px; }
.btn-lab:hover { background:#6d28d9; }

/* ═══ RESULT LIST ══════════════════════════════════════════════════ */
.results-section { margin-bottom:28px; }
.results-section-title { font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;display:flex;align-items:center;gap:8px;margin-bottom:10px; }
.results-section-line { flex:1;border-top:1px solid #e5e7eb; }
.dark .results-section-line { border-top-color:#374151; }
.results-badge { font-size:.68rem;padding:2px 8px;border-radius:9999px;font-weight:700; }
.results-badge-lab { background:#d1fae5;color:#065f46; }
.results-badge-rad { background:#f5f3ff;color:#5b21b6; }

/* Clickable result card (per request, not per upload) */
.result-req-card { background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:14px 16px;margin-bottom:10px;border-left:3px solid #059669;cursor:pointer;transition:border-color .15s,box-shadow .15s; }
.dark .result-req-card { background:#1f2937;border-color:#374151; }
.result-req-card:hover { border-color:#059669;box-shadow:0 2px 10px rgba(5,150,105,.12); }
.result-req-card-rad { border-left-color:#6d28d9; }
.result-req-card-rad:hover { border-color:#6d28d9;box-shadow:0 2px 10px rgba(109,40,217,.12); }
.rrc-top { display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:8px; }
.rrc-req-no { font-family:monospace;font-size:.78rem;font-weight:700;color:#059669; }
.rrc-req-no-rad { color:#6d28d9; }
.rrc-badge { font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:9999px;background:#f0fdf4;color:#065f46; }
.rrc-badge-rad { background:#f5f3ff;color:#5b21b6; }
.rrc-diag { font-size:.82rem;color:#374151;font-weight:600;margin-top:3px; }
.dark .rrc-diag { color:#e5e7eb; }
.rrc-meta { font-size:.72rem;color:#6b7280;margin-top:3px; }
.rrc-files { display:flex;flex-wrap:wrap;gap:6px;margin-top:8px; }
.rrc-file-chip { display:inline-flex;align-items:center;gap:4px;font-size:.72rem;background:#f3f4f6;color:#374151;padding:2px 8px;border-radius:4px; }
.dark .rrc-file-chip { background:#374151;color:#d1d5db; }
.rrc-arrow { font-size:.9rem;color:#9ca3af; }
.rrc-tests { display:flex;flex-wrap:wrap;gap:4px;margin-bottom:6px; }
.rrc-test-chip { background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:4px;padding:1px 7px;font-size:.68rem;font-weight:500; }

/* Pending */
.pending-req-card { background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:12px 16px;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;gap:12px; }
.dark .pending-req-card { background:#1f2937;border-color:#374151; }
.pending-req-no { font-family:monospace;font-size:.75rem;color:#6b7280;font-weight:700; }
.pending-req-info { flex:1; }
.pending-req-diag { font-size:.83rem;color:#374151; }
.dark .pending-req-diag { color:#d1d5db; }
.pending-badge { background:#fef3c7;color:#92400e;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:9999px; }
.pending-badge-stat { background:#fee2e2;color:#991b1b; }

/* ═══ RESULT DETAIL VIEW (embedded paper form) ══════════════════════ */
.rv-back-btn { display:inline-flex;align-items:center;gap:6px;font-size:.82rem;color:#6b7280;background:none;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;margin-bottom:16px;padding:7px 14px; }
.rv-back-btn:hover { border-color:#1d4ed8;color:#1d4ed8; }

/* Status header */
.rv-header-lab { background:linear-gradient(135deg,#7c2d12 0%,#f97316 100%);border-radius:10px;padding:16px 22px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px; }
.rv-header-rad { background:linear-gradient(135deg,#3b0764 0%,#6d28d9 100%);border-radius:10px;padding:16px 22px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px; }
.rv-req-no { font-family:monospace;font-size:1.2rem;font-weight:900;color:#fff; }
.rv-patient { font-size:1rem;font-weight:800;color:#fff;margin-top:3px; }
.rv-case { font-family:monospace;font-size:.78rem;color:#fed7aa;margin-top:1px; }
.rv-case-rad { color:#c4b5fd; }
.rv-pill { padding:5px 16px;border-radius:9999px;font-size:.78rem;font-weight:700;background:#16a34a;color:#fff; }
.rv-modality { background:rgba(255,255,255,.2);color:#fff;padding:4px 14px;border-radius:9999px;font-size:.82rem;font-weight:700; }

/* Paper document */
.rv-paper { background:#fff;border:1px solid #d1d5db;border-radius:8px;padding:0.45in 0.55in;margin-bottom:16px;font-family:'Segoe UI',system-ui,sans-serif;font-size:10pt;color:#000;box-shadow:0 2px 8px rgba(0,0,0,.08); }
.rv-paper-rad { font-family:'Times New Roman',Times,serif;font-size:11pt; padding:0.55in 0.65in; }
.rv-hdr { display:flex;align-items:center;gap:12px;padding-bottom:7px;border-bottom:2px solid #000;margin-bottom:7px; }
.rv-hdr-rad { border-bottom-width:2.5px; gap:14px; }
.rv-logo { width:52px;height:52px;flex-shrink:0;border:1.5px dashed #bbb;display:flex;align-items:center;justify-content:center;font-size:6.5pt;color:#bbb;text-align:center;line-height:1.3;border-radius:3px; }
.rv-logo img { width:52px;height:52px;object-fit:contain; }
.rv-logo-rad { width:60px;height:60px; }
.rv-logo-rad img { width:60px;height:60px;object-fit:contain; }
.rv-center { flex:1;text-align:center;line-height:1.25; }
.rv-h-name { font-size:12pt;font-weight:bold;text-transform:uppercase;letter-spacing:.05em; }
.rv-h-name-rad { font-size:14pt;letter-spacing:.06em; }
.rv-h-sub  { font-size:10pt;font-weight:600;color:#444;margin-top:2px; }
.rv-h-sub-rad { font-weight:bold; }
.rv-h-ref  { font-size:7.5pt;color:#666;margin-top:2px;font-family:monospace; }
.rv-h-addr { font-size:8pt;color:#444;margin-top:2px; }

.rv-g4 { display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;margin-bottom:5px; }
.rv-g6 { display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr 2fr;gap:8px;margin-bottom:5px; }
.rv-g3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:7px; }
.rv-g2x { display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:10px;margin-bottom:8px; }
.rv-fg { margin-bottom:0; }
.rv-fl { font-size:7.5pt;text-transform:uppercase;letter-spacing:.04em;color:#777;display:block;margin-bottom:1px; }
.rv-fl-rad { font-size:8pt;letter-spacing:.05em;font-family:'Segoe UI',system-ui,sans-serif; }
.rv-val { font-size:9.5pt;font-weight:600;color:#000;border-bottom:1px solid #bbb;padding:1px 2px;min-height:18px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.rv-val-rad { font-size:10.5pt;font-weight:500; }
.rv-area-val { background:#f9f9f9;border:1px solid #ccc;padding:5px 7px;font-size:10.5pt;line-height:1.65;min-height:50px;color:#000;white-space:pre-wrap; }
.rv-divider { border:none;border-top:1px solid #000;margin:5px 0; }
.rv-divider-thick { border:none;border-top:2px solid #000;margin:8px 0; }

/* Physician block */
.rv-phys { background:#f9fafb;border:1px solid #e5e7eb;border-radius:5px;padding:7px 12px;margin-bottom:8px; }
.rv-phys-label { font-size:8pt;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#374151;margin-bottom:5px; }
.rv-phys-name { font-size:10pt;font-weight:bold;color:#000;border-bottom:1px solid #000;padding-bottom:2px;min-height:22px; }
.rv-sig-line { border-bottom:1px solid #000;height:28px;margin-top:16px; }
.rv-sig-cap { font-size:8pt;text-align:center;font-style:italic;margin-top:2px;font-family:'Segoe UI',system-ui,sans-serif; }

/* Test grid (read-only) */
.rv-tests-grid { display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;margin:6px 0; }
.rv-test-sec { border:1px solid #ddd;border-radius:4px;overflow:hidden; }
.rv-test-head { padding:3px 7px;font-size:7.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;display:flex;align-items:center;gap:5px; }
.rv-test-dot { width:6px;height:6px;border-radius:50%;flex-shrink:0; }
.rv-test-item { display:flex;align-items:center;gap:6px;padding:2px 7px;margin:1px 4px;border-radius:2px; }
.rv-test-item.checked { background:#eef2ff; }
.rv-cb { width:11px;height:11px;border:1.5px solid #bbb;border-radius:2px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:#fff; }
.rv-test-item.checked .rv-cb { background:#4f46e5;border-color:#4f46e5; }
.rv-test-item.checked .rv-cb::after { content:'';display:block;width:5px;height:3px;border-left:1.5px solid #fff;border-bottom:1.5px solid #fff;transform:rotate(-45deg) translate(1px,-1px); }
.rv-test-name { font-size:8pt;color:#374151;line-height:1.3; }
.rv-test-item.checked .rv-test-name { color:#3730a3;font-weight:600; }
.rv-micro-val { font-size:8pt;color:#374151;border-bottom:1px dashed #bbb;min-height:16px;padding:1px 2px; }

/* Footer grids */
.rv-footer5 { display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr;gap:8px;margin-top:8px; }
.rv-footer4 { display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:8px; }

/* Modality / source (read-only visual) */
.rv-modality-row { display:flex;align-items:center;justify-content:space-around;border:1.5px solid #000;padding:8px 24px;margin-bottom:8px; }
.rv-modality-opt { display:inline-flex;align-items:center;gap:7px;font-size:12pt;font-weight:bold; }
.rv-radio { width:14px;height:14px;border-radius:50%;border:2px solid #000;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
.rv-radio.on { background:#000; }
.rv-radio.on::after { content:'';width:6px;height:6px;background:#fff;border-radius:50%; display:block; }
.rv-source-row { display:flex;align-items:center;justify-content:space-around;border:1px solid #ddd;background:#fafafa;padding:6px 10px;margin-bottom:8px;font-family:'Segoe UI',system-ui,sans-serif; }
.rv-source-opt { display:inline-flex;align-items:center;gap:5px;font-size:10pt;font-weight:600; }
.rv-checkbox { width:12px;height:12px;border:1.5px solid #555;border-radius:2px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
.rv-checkbox.on { background:#000; }
.rv-checkbox.on::after { content:'✓';font-size:8px;color:#fff;line-height:1; }
.rv-interp-area { border:1.5px solid #000;padding:8px 10px;min-height:140px;font-size:10.5pt;line-height:1.75;color:#000;white-space:pre-wrap; }
.rv-sec-label { font-size:8.5pt;font-weight:bold;text-transform:uppercase;letter-spacing:.06em;color:#444;margin-bottom:5px;font-family:'Segoe UI',system-ui,sans-serif; }

/* Result box (green, same as tech view) */
.rv-result-box { background:#f0fdf4;border:1.5px solid #22c55e;border-radius:8px;padding:18px 20px;margin-bottom:16px; }
.rv-result-title { font-size:.82rem;font-weight:700;color:#15803d;margin-bottom:12px;display:flex;align-items:center;gap:7px; }
.rv-file-link-lab { display:inline-flex;align-items:center;gap:5px;color:#1d4ed8;font-size:.82rem;font-weight:600;text-decoration:none;padding:5px 12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px; }
.rv-file-link-lab:hover { background:#dbeafe; }
.rv-file-link-rad { display:inline-flex;align-items:center;gap:5px;color:#6d28d9;font-size:.82rem;font-weight:600;text-decoration:none;padding:5px 12px;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:6px; }
.rv-file-link-rad:hover { background:#ede9fe; }
.rv-notes-box { background:#fff;border:1px solid #d1fae5;border-radius:6px;padding:10px 14px;margin-top:12px; }
.rv-notes-label { font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#059669;margin-bottom:5px; }
.rv-interp-box { background:#fff;border:1.5px solid #c4b5fd;border-radius:8px;padding:14px 16px;margin-top:14px; }
.rv-interp-label { font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#6d28d9;margin-bottom:8px; }
.rv-interp-text { font-family:'Times New Roman',serif;font-size:.95rem;line-height:1.8;color:#111827;white-space:pre-wrap; }
</style>

@if($visit && $visit->patient)
@php
    $patient    = $visit->patient;
    $history    = $visit->medicalHistory;
    $allOrders  = $visit->doctorsOrders ?? collect();
    $allVitals  = $visit->vitals ?? collect();
    $pendingCnt = $allOrders->where('status', 'pending')->count();
    $service    = $visit->admitted_service ?? $history?->service ?? '—';
    $admittedAt = $visit->clerk_admitted_at
        ? $visit->clerk_admitted_at->timezone('Asia/Manila')->format('M j, Y H:i')
        : '—';
    $labResults   = $this->labResults;
    $radResults   = $this->radResults;
    $totalResults = $labResults->count() + $radResults->count();
@endphp

<div class="chart-page">

    <div class="chart-header">
        <div class="chart-header-left">
            <p class="patient-name">{{ $patient->full_name }}</p>
            <p class="case-no">{{ $patient->case_no }}</p>
        </div>
        <div class="chart-header-pills">
            <div class="h-pill"><p class="pill-label">Age / Sex</p><p class="pill-value">{{ $patient->age_display }} · {{ $patient->sex }}</p></div>
            <div class="h-pill"><p class="pill-label">Admitting Diagnosis</p><p class="pill-value" style="font-size:.78rem;max-width:200px;white-space:normal;line-height:1.3;">{{ $visit->admitting_diagnosis ?? $history?->diagnosis ?? '—' }}</p></div>
            <span class="h-service-badge">{{ $service }}</span>
            <div class="h-pill"><p class="pill-label">Admitted</p><p class="pill-value">{{ $admittedAt }}</p></div>
            @if($history?->doctor)<div class="h-pill"><p class="pill-label">Physician</p><p class="pill-value">Dr. {{ $history->doctor->name }}</p></div>@endif
        </div>
        <div><a href="{{ \App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index') }}" class="btn-back-header">← Admitted Patients</a></div>
    </div>

    <div class="chart-tabs">
        <button wire:click="setTab('profile')"  class="chart-tab {{ $activeTab==='profile'  ? 'active':'' }}"><span class="tab-icon">👤</span> Profile</button>
        <button wire:click="setTab('vitals')"   class="chart-tab {{ $activeTab==='vitals'   ? 'active':'' }}"><span class="tab-icon">📊</span> Vital Signs @if($allVitals->count() > 0)<span class="tab-badge tab-badge-warn">{{ $allVitals->count() }}</span>@endif</button>
        <button wire:click="setTab('history')"  class="chart-tab {{ $activeTab==='history'  ? 'active':'' }}"><span class="tab-icon">📋</span> History &amp; Assessment</button>
        <button wire:click="setTab('orders')"   class="chart-tab {{ $activeTab==='orders'   ? 'active':'' }}"><span class="tab-icon">📝</span> Doctor's Orders @if($pendingCnt > 0)<span class="tab-badge">{{ $pendingCnt }}</span>@endif</button>
        <button wire:click="setTab('results')"  class="chart-tab {{ $activeTab==='results'  ? 'active':'' }}"><span class="tab-icon">🔬</span> Lab / Radiology @if($totalResults > 0)<span class="tab-badge tab-badge-green">{{ $totalResults }}</span>@endif</button>
    </div>

    <div class="chart-content">

        @if($activeTab === 'profile')
        <div class="sec-head"><h2 class="sec-title">Patient Profile</h2></div>
        <div class="placeholder-card"><div class="ph-icon">📄</div><p class="ph-title">Patient Profile Form</p><p class="ph-sub">Softcopy of the patient registration form will appear here.</p></div>

        @elseif($activeTab === 'vitals')
        <div class="sec-head"><h2 class="sec-title">Vital Signs</h2><span style="font-size:.78rem;color:#6b7280;">{{ $allVitals->count() }} recording(s)</span></div>
        @if($allVitals->isEmpty())
        <div class="placeholder-card"><div class="ph-icon">📊</div><p class="ph-title">No vital signs recorded yet</p><p class="ph-sub">Vitals are recorded by the nurse from the Nurse panel.</p></div>
        @else
        <div class="vitals-wrap"><table class="vitals-table"><thead><tr><th>Date / Time</th><th>Nurse</th><th>BP</th><th>PR (bpm)</th><th>RR (/min)</th><th>Temp (°C)</th><th>O₂ Sat (%)</th><th>Pain /10</th><th>Wt (kg)</th><th>Ht (cm)</th></tr></thead><tbody>
        @foreach($allVitals as $v)
        <tr><td style="white-space:nowrap;font-family:monospace;font-size:.76rem;">{{ $v->taken_at->timezone('Asia/Manila')->format('M j, Y H:i') }}</td><td style="font-size:.78rem;">{{ $v->nurse_name }}</td><td>{{ $v->blood_pressure ?? '—' }}</td><td class="{{ ($v->pulse_rate && ($v->pulse_rate < 60 || $v->pulse_rate > 100)) ? 'abnormal':'' }}">{{ $v->pulse_rate ?? '—' }}</td><td class="{{ ($v->respiratory_rate && ($v->respiratory_rate < 12 || $v->respiratory_rate > 20)) ? 'abnormal':'' }}">{{ $v->respiratory_rate ?? '—' }}</td><td class="{{ ($v->temperature && ($v->temperature < 36.0 || $v->temperature > 37.5)) ? 'abnormal':'' }}">{{ $v->temperature ?? '—' }}</td><td class="{{ ($v->o2_saturation && $v->o2_saturation < 95) ? 'abnormal':'' }}">{{ $v->o2_saturation ?? '—' }}</td><td class="{{ ($v->pain_scale !== null && (int)$v->pain_scale >= 7) ? 'abnormal':'' }}">{{ $v->pain_scale ?? '—' }}</td><td>{{ $v->weight_kg ?? '—' }}</td><td>{{ $v->height_cm ?? '—' }}</td></tr>
        @endforeach
        </tbody></table></div>
        @endif

        @elseif($activeTab === 'history')
        <div class="sec-head"><h2 class="sec-title">History &amp; Assessment Forms</h2><span style="font-size:.78rem;color:#6b7280;">Opens in new tab · Print from inside the form</span></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
            <a href="{{ route('forms.history-form', ['visit' => $visit->id]) }}" target="_blank" rel="noopener" class="doc-card"><span class="doc-card-icon">📝</span><div class="doc-card-body"><p class="doc-card-label doc-card-label-blue">NUR-006</p><p class="doc-card-title">History Form</p><p class="doc-card-meta">Chief complaint · HPI · Past medical · Family history · Allergies</p></div><span class="doc-card-arrow">↗</span></a>
            <a href="{{ route('forms.physical-exam-form', ['visit' => $visit->id]) }}" target="_blank" rel="noopener" class="doc-card"><span class="doc-card-icon">🩺</span><div class="doc-card-body"><p class="doc-card-label doc-card-label-blue">NUR-005</p><p class="doc-card-title">Physical Examination Form</p><p class="doc-card-meta">Head-to-toe findings · Admitting impression</p></div><span class="doc-card-arrow">↗</span></a>
        </div>
        <div class="placeholder-card" style="padding:28px;"><div class="ph-icon">🗂</div><p class="ph-title">Clinical Face Sheet</p><p class="ph-sub">Summary sheet with diagnosis, disposition, and management plan. Coming soon.</p></div>

        @elseif($activeTab === 'orders')
        <div class="sec-head">
            <h2 class="sec-title">Doctor's Orders</h2>
            <div style="display:flex;gap:8px;align-items:center;">
                <span style="font-size:.78rem;color:#6b7280;">{{ $allOrders->count() }} total · {{ $pendingCnt }} pending</span>
                <button wire:click="setTab('results')" type="button" class="btn-lab">🔬 Request Lab / Radiology</button>
                <button wire:click="toggleWriteOrders" type="button" class="btn-write {{ $writingOrders ? 'is-cancel':'' }}">@if($writingOrders) ✕ Cancel @else ✏️ Write New Orders @endif</button>
            </div>
        </div>
        @if($writingOrders)
        <div class="order-form-wrap">
            <p class="order-form-title">✏️ New Doctor's Orders &nbsp;·&nbsp;<span style="font-weight:400;color:#6b7280;">{{ now()->timezone('Asia/Manila')->format('F j, Y H:i') }} &nbsp;· Dr. {{ auth()->user()->name }}</span></p>
            @foreach($orderLines as $i => $line)<div class="order-line-row"><input type="text" wire:model="orderLines.{{ $i }}.text" placeholder="Order {{ $i + 1 }} — e.g., IVF D5W 500ml @ 20 gtts/min" class="ol-input"><button wire:click="removeOrderLine({{ $i }})" type="button" class="btn-remove-line" title="Remove">✕</button></div>@endforeach
            <button wire:click="addOrderLine" type="button" class="btn-add-line">+ Add Another Order Line</button>
            <div style="display:flex;gap:10px;margin-top:14px;"><button wire:click="saveOrders" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button" class="btn-primary"><span wire:loading.remove wire:target="saveOrders">💾 Save Orders</span><span wire:loading wire:target="saveOrders">Saving…</span></button><button wire:click="toggleWriteOrders" type="button" class="btn-secondary">Cancel</button></div>
        </div>
        @endif
        @if($allOrders->isEmpty() && !$writingOrders)
        <div class="placeholder-card"><div class="ph-icon">📝</div><p class="ph-title">No orders written yet</p><p class="ph-sub">Click "Write New Orders" above to write your first set of orders.</p></div>
        @else
        @foreach($allOrders->groupBy(fn($o) => $o->order_date?->timezone('Asia/Manila')->format('Y-m-d H:i')) as $dateKey => $group)
        <div style="margin-bottom:22px;"><div class="order-group-header"><p class="order-group-label">{{ \Carbon\Carbon::parse($dateKey)->timezone('Asia/Manila')->format('F j, Y H:i') }}</p><div class="order-group-line"></div>@if($group->first()->doctor)<p class="order-group-doc">Dr. {{ $group->first()->doctor->name }}</p>@endif</div>
        @foreach($group as $i => $order)
        <div class="order-item"><div><p class="order-num">{{ $i + 1 }}.</p><p class="order-text {{ $order->isDiscontinued() ? 'order-text-discontinued':'' }}">{{ $order->order_text }}</p><p class="order-meta">{{ $order->order_date?->timezone('Asia/Manila')->format('M j, Y H:i') }}@if($order->isCarried() && $order->completed_at) · Carried {{ $order->completed_at->timezone('Asia/Manila')->format('H:i') }} @endif</p></div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;"><span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>@if($order->isPending())@if($confirmDiscontinueId === $order->id)<div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;margin-top:4px;"><p style="font-size:.68rem;color:#dc2626;font-weight:600;">Discontinue this order?</p><div style="display:flex;gap:4px;"><button wire:click="discontinueOrder({{ $order->id }})" type="button" style="font-size:.7rem;background:#dc2626;color:#fff;border:none;border-radius:4px;padding:3px 9px;cursor:pointer;">Yes</button><button wire:click="$set('confirmDiscontinueId', null)" type="button" style="font-size:.7rem;background:#e5e7eb;color:#374151;border:none;border-radius:4px;padding:3px 8px;cursor:pointer;">Cancel</button></div></div>@else<button wire:click="$set('confirmDiscontinueId', {{ $order->id }})" type="button" class="btn-discontinue">Discontinue</button>@endif@endif</div></div>
        @endforeach</div>
        @endforeach
        @endif

        {{-- ══════════════════════════════════════════════════════════════════
             LAB / RADIOLOGY TAB
        ══════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'results')

        {{-- ── RESULT DETAIL VIEW — Lab ────────────────────────────────── --}}
        @if($viewingLabRequestId)
        @php
            $vlr = \App\Models\LabRequest::with(['visit.patient','doctor','results.uploadedBy'])->find($viewingLabRequestId);
            $vlrPatient  = $vlr?->visit?->patient ?? $vlr?->patient;
            $vlrUploads  = $vlr?->results()->with('uploadedBy')->latest()->get() ?? collect();
            $vlrNotes    = $vlrUploads->pluck('notes')->filter()->unique()->values();
            $vlrUploader = $vlrUploads->first()?->uploadedBy?->name ?? '—';
            $vlrSelected = $vlr?->tests ?? [];

            $vc1 = [
                ['style'=>'background:#dbeafe;color:#1e40af','dot'=>'#3b82f6','label'=>'Hematology','tests'=>['Complete Blood Count (CBC)','Reticulocyte Count','Peripheral Blood Smear','Malarial Smear','Clotting / Bleeding Time','Prothrombin Time (PT-PA)','APTT','ESR']],
                ['style'=>'background:#f3f4f6;color:#374151','dot'=>'#6b7280','label'=>'Blood Typing','tests'=>['Blood Typing','Crossmatching']],
                ['style'=>'background:#ede9fe;color:#5b21b6','dot'=>'#8b5cf6','label'=>'Serology','tests'=>['Dengue NS1 + IgM/IgG (Combo)','Typhidot','ASTO — Qualitative','ASTO — Semi-Quantitative','CRP — Qualitative','CRP — Semi-Quantitative','Rheumatoid Factor — Qualitative','HBsAg — Rapid','HBsAg — EIA','Anti-HCV — Rapid','VDRL/RPR — Rapid','Referral HIV (HACT)']],
            ];
            $vc2 = [
                ['style'=>'background:#dcfce7;color:#166534','dot'=>'#22c55e','label'=>'Clinical Chemistry','tests'=>['Fasting Blood Sugar','Random Blood Sugar','OGTT','2-hr Post-prandial BG','HbA1c','Uric Acid','Amylase','LDH']],
                ['style'=>'background:#fee2e2;color:#991b1b','dot'=>'#ef4444','label'=>'Lipid Profile','tests'=>['Total Cholesterol','Total, HDL & LDL Cholesterol','Triglycerides','Complete Lipid Profile']],
                ['style'=>'background:#fce7f3;color:#9d174d','dot'=>'#ec4899','label'=>'Serum Electrolytes','tests'=>['Sodium, Potassium, Chloride','Phosphorus','Magnesium','Calcium — Total','Calcium — Ionized']],
                ['style'=>'background:#e0f2fe;color:#0c4a6e','dot'=>'#0ea5e9','label'=>'Renal Profile','tests'=>['BUN','Creatinine','Creatinine Clearance','Sodium, Potassium, Chloride','Total Protein','Albumin']],
                ['style'=>'background:#d1fae5;color:#065f46','dot'=>'#10b981','label'=>'HBT Profile','tests'=>['AST / SGOT','ALT / SGPT','Alkaline Phosphatase','Total Protein','Albumin','Total Bilirubin','Total, Direct & Indirect Bili.','PT-PA','Troponin-T']],
            ];
            $vc3 = [
                ['id'=>'micro','style'=>'background:#fef9c3;color:#854d0e','dot'=>'#eab308','label'=>'Clinical Microscopy','tests'=>['Routine Urinalysis','Urine Ketones','Pregnancy Test — Urine','Pregnancy Test — Serum','Seminal Fluid Analysis','Body Fluid Analysis','Cell Count / Differential','Routine Fecalysis','Fecalysis with Concentration','Fecal Occult Blood']],
                ['id'=>'mbio','style'=>'background:#ffedd5;color:#9a3412','dot'=>'#f97316','label'=>'Microbiology','tests'=>['Gram Stain','Acid Fast Stain (AFB)','India Ink Stain','KOH Preparation','Culture and Sensitivity']],
            ];
        @endphp

        <button type="button" wire:click="closeResultView" class="rv-back-btn">← Back to Results</button>

        {{-- Status bar --}}
        <div class="rv-header-lab">
            <div>
                <p class="rv-req-no">{{ $vlr?->request_no }}</p>
                <p class="rv-patient">{{ $vlrPatient?->full_name ?? '—' }}</p>
                <p class="rv-case">{{ $vlrPatient?->case_no ?? '' }} · {{ $vlrPatient?->age_display ?? '' }} · {{ $vlrPatient?->sex ?? '' }}</p>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                <span class="rv-pill">✅ Completed</span>
                @if($vlr?->request_type === 'stat')<span style="background:#dc2626;color:#fff;padding:3px 10px;border-radius:9999px;font-size:.72rem;font-weight:800;">⚡ STAT</span>@endif
            </div>
        </div>

        {{-- Paper form (read-only) --}}
        <div class="rv-paper">
            <div class="rv-hdr">
                @if(file_exists(public_path('images/lumc-logo.png'))) <div class="rv-logo"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC"></div> @else <div class="rv-logo">LUMC<br>Logo</div> @endif
                <div class="rv-center"><div class="rv-h-name">La Union Medical Center</div><div class="rv-h-sub">Clinical Laboratory Request Form</div><div class="rv-h-ref">LAB-001-1 Rev. 1 &nbsp;·&nbsp; Brgy. Nazareno, Agoo, La Union &nbsp;·&nbsp; (072) 607-5541 loc. 117/118</div></div>
                @if(file_exists(public_path('images/province-logo.png'))) <div class="rv-logo"><img src="{{ asset('images/province-logo.png') }}" alt="Province"></div> @else <div class="rv-logo">Province<br>Seal</div> @endif
            </div>
            <div class="rv-g4"><div class="rv-fg"><span class="rv-fl">Date of Request</span><span class="rv-val">{{ $vlr?->date_requested?->format('Y-m-d') ?? $vlr?->created_at->format('Y-m-d') }}</span></div><div class="rv-fg"><span class="rv-fl">Hospital No.</span><span class="rv-val">{{ $vlrPatient?->case_no ?? '—' }}</span></div><div class="rv-fg"><span class="rv-fl">Receipt No.</span><span class="rv-val" style="font-family:monospace;font-weight:bold;">{{ $vlr?->request_no }}</span></div><div class="rv-fg"><span class="rv-fl">Ward / Service</span><span class="rv-val">{{ $vlr?->ward ?? '—' }}</span></div></div>
            <div class="rv-g4"><div class="rv-fg"><span class="rv-fl">Surname</span><span class="rv-val">{{ strtoupper($vlrPatient?->family_name ?? '—') }}</span></div><div class="rv-fg"><span class="rv-fl">First Name</span><span class="rv-val">{{ strtoupper($vlrPatient?->first_name ?? '—') }}</span></div><div class="rv-fg"><span class="rv-fl">Middle Name</span><span class="rv-val">{{ strtoupper($vlrPatient?->middle_name ?? '—') }}</span></div><div class="rv-fg"><span class="rv-fl">Address</span><span class="rv-val">{{ $vlrPatient?->address ?? '—' }}</span></div></div>
            <div class="rv-g6"><div class="rv-fg"><span class="rv-fl">Birth Date</span><span class="rv-val">{{ $vlrPatient?->birthday?->format('Y-m-d') ?? '—' }}</span></div><div class="rv-fg"><span class="rv-fl">Age</span><span class="rv-val">{{ $vlrPatient?->age_display ?? $vlrPatient?->current_age ?? '—' }}</span></div><div class="rv-fg"><span class="rv-fl">Sex</span><span class="rv-val">{{ $vlrPatient?->sex ?? '—' }}</span></div><div class="rv-fg"><span class="rv-fl">Civil Status</span><span class="rv-val">—</span></div><div class="rv-fg"><span class="rv-fl">Request Type</span><span class="rv-val" style="font-weight:bold;color:{{ $vlr?->request_type === 'stat' ? '#dc2626' : '#000' }};">{{ strtoupper($vlr?->request_type ?? 'ROUTINE') }}</span></div><div class="rv-fg"><span class="rv-fl">Clinical Diagnosis</span><span class="rv-val">{{ $vlr?->clinical_diagnosis ?? '—' }}</span></div></div>
            <hr class="rv-divider">
            <div class="rv-phys"><div class="rv-phys-label">Requesting Physician</div><div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;"><div><span class="rv-fl">Name</span><div class="rv-phys-name">{{ $vlr?->requesting_physician ?? ($vlr?->doctor ? 'Dr. '.$vlr->doctor->name : '—') }}</div></div><div style="display:flex;flex-direction:column;justify-content:flex-end;"><div style="border-bottom:1px solid #000;height:28px;"></div><div class="rv-sig-cap">Signature / PRC No. &amp; Date</div></div></div></div>

            {{-- Test grid --}}
            <div class="rv-tests-grid">
                <div style="display:flex;flex-direction:column;gap:5px;">
                @foreach($vc1 as $sec)
                <div class="rv-test-sec"><div class="rv-test-head" style="{{ $sec['style'] }};"><span class="rv-test-dot" style="background:{{ $sec['dot'] }};"></span>{{ $sec['label'] }}</div>
                @foreach($sec['tests'] as $t)<div class="rv-test-item {{ in_array($t, $vlrSelected) ? 'checked' : '' }}"><div class="rv-cb"></div><span class="rv-test-name">{{ $t }}</span></div>@endforeach
                </div>@endforeach
                </div>
                <div style="display:flex;flex-direction:column;gap:5px;">
                @foreach($vc2 as $sec)
                <div class="rv-test-sec"><div class="rv-test-head" style="{{ $sec['style'] }};"><span class="rv-test-dot" style="background:{{ $sec['dot'] }};"></span>{{ $sec['label'] }}</div>
                @foreach($sec['tests'] as $t)<div class="rv-test-item {{ in_array($t, $vlrSelected) ? 'checked' : '' }}"><div class="rv-cb"></div><span class="rv-test-name">{{ $t }}</span></div>@endforeach
                </div>@endforeach
                </div>
                <div style="display:flex;flex-direction:column;gap:5px;">
                @foreach($vc3 as $sec)
                <div class="rv-test-sec"><div class="rv-test-head" style="{{ $sec['style'] }};"><span class="rv-test-dot" style="background:{{ $sec['dot'] }};"></span>{{ $sec['label'] }}</div>
                @foreach($sec['tests'] as $t)<div class="rv-test-item {{ in_array($t, $vlrSelected) ? 'checked' : '' }}"><div class="rv-cb"></div><span class="rv-test-name">{{ $t }}</span></div>@endforeach
                @if(($sec['id'] ?? '') === 'mbio')<div style="padding:4px 7px;border-top:1px solid #e5e7eb;"><span style="font-size:7pt;color:#888;text-transform:uppercase;letter-spacing:.04em;">Specimen</span><div class="rv-micro-val">{{ $vlr?->specimen ?? '' }}</div></div><div style="padding:4px 7px;border-top:1px solid #e5e7eb;"><span style="font-size:7pt;color:#888;text-transform:uppercase;letter-spacing:.04em;">Antibiotics / Duration</span><div class="rv-micro-val">{{ $vlr?->antibiotics_taken ?? '' }}</div></div>@endif
                </div>@endforeach
                @if($vlr?->other_tests)<div class="rv-test-sec"><div class="rv-test-head" style="background:#f3f4f6;color:#374151;"><span class="rv-test-dot" style="background:#9ca3af;"></span>Others</div><div style="padding:5px 7px;font-size:8pt;color:#374151;">{{ $vlr->other_tests }}</div></div>@endif
                </div>
            </div>

            <hr class="rv-divider" style="margin:6px 0;">
            <div class="rv-footer5">
                <div class="rv-fg"><span class="rv-fl">Date</span><span class="rv-val">{{ $vlr?->date_requested?->format('Y-m-d') ?? '—' }}</span></div>
                <div class="rv-fg"><span class="rv-fl">Request Received</span><span class="rv-val">{{ $vlr?->request_received_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
                <div class="rv-fg"><span class="rv-fl">Specimen Collected</span><span class="rv-val">{{ $vlr?->specimen_collected ?? '' }}</span></div>
                <div class="rv-fg"><span class="rv-fl">Test Started</span><span class="rv-val">{{ $vlr?->test_started_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
                <div class="rv-fg"><span class="rv-fl">Test Done</span><span class="rv-val">{{ $vlr?->test_done_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:10px;">
                <div><div class="rv-sig-line"></div><div class="rv-sig-cap">Requesting Physician — Signature / PRC No.</div></div>
                <div><div class="rv-sig-line"></div><div class="rv-sig-cap">Verified by (Lab Staff) / Date</div></div>
            </div>
        </div>

        {{-- Result box --}}
        <div class="rv-result-box">
            <p class="rv-result-title">✅ {{ $vlrUploads->count() }} Result File(s) — {{ $vlr?->request_no }}</p>
            <p style="font-size:.82rem;color:#f97316;font-weight:600;margin:-4px 0 12px 2px;">Uploaded by {{ $vlrUploader }}</p>
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:{{ $vlrNotes->isNotEmpty() ? '4px' : '0' }};">
                @foreach($vlrUploads as $u)
                <div>
                    <a href="{{ $u->file_url }}" target="_blank" class="rv-file-link-lab">{{ $u->file_type_icon }} {{ $u->file_name }} <span style="font-size:.7rem;font-weight:400;color:#6b7280;">({{ $u->file_size_human }})</span></a>
                </div>
                @endforeach
            </div>
            @if($vlrNotes->isNotEmpty())
            <div class="rv-notes-box">
                <p class="rv-notes-label">📝 Tech Notes</p>
                @foreach($vlrNotes as $note)<p style="font-size:.875rem;color:#374151;line-height:1.6;">{{ $note }}</p>@endforeach
            </div>
            @endif
        </div>

        {{-- ── RESULT DETAIL VIEW — Radiology ─────────────────────────── --}}
        @elseif($viewingRadRequestId)
        @php
            $vrr = \App\Models\RadiologyRequest::with(['visit.patient','doctor','results.uploadedBy'])->find($viewingRadRequestId);
            $vrrPatient  = $vrr?->visit?->patient ?? $vrr?->patient;
            $vrrUploads  = $vrr?->results()->with('uploadedBy')->latest()->get() ?? collect();
            $vrrUploader = $vrrUploads->first()?->uploadedBy?->name ?? '—';
            $vrrInterp   = $vrr?->radiologist_interpretation ?? $vrrUploads->firstWhere('interpretation', '!=', null)?->interpretation ?? null;
            $vrrSrc      = strtoupper($vrr?->source ?? '');
        @endphp

        <button type="button" wire:click="closeResultView" class="rv-back-btn">← Back to Results</button>

        {{-- Status bar --}}
        <div class="rv-header-rad">
            <div>
                <p class="rv-req-no">{{ $vrr?->request_no }}</p>
                <p class="rv-patient">{{ $vrrPatient?->full_name ?? '—' }}</p>
                <p class="rv-case rv-case-rad">{{ $vrrPatient?->case_no ?? '' }} · {{ $vrrPatient?->age_display ?? '' }} · {{ $vrrPatient?->sex ?? '' }}</p>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                @if($vrr?->modality)<span class="rv-modality">{{ $vrr->modality }}</span>@endif
                <span class="rv-pill">✅ Completed</span>
            </div>
        </div>

        {{-- Paper form (read-only) --}}
        <div class="rv-paper rv-paper-rad">
            <div class="rv-hdr rv-hdr-rad">
                @if(file_exists(public_path('images/lumc-logo.png'))) <div class="rv-logo rv-logo-rad"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC"></div> @else <div class="rv-logo rv-logo-rad">LUMC<br>Logo</div> @endif
                <div class="rv-center"><div class="rv-h-name rv-h-name-rad">La Union Medical Center</div><div class="rv-h-sub rv-h-sub-rad">Radiology Request Form</div><div class="rv-h-addr">Brgy. Nazareno, Agoo, La Union &nbsp;·&nbsp; (072) 607-5541</div></div>
                @if(file_exists(public_path('images/province-logo.png'))) <div class="rv-logo rv-logo-rad"><img src="{{ asset('images/province-logo.png') }}" alt="Province"></div> @else <div class="rv-logo rv-logo-rad">Province<br>Seal</div> @endif
            </div>
            <hr class="rv-divider-thick" style="margin-top:10px;">

            <div class="rv-modality-row">
                @foreach(['X-RAY','ULTRASOUND','CT SCAN'] as $mod)<div class="rv-modality-opt"><div class="rv-radio {{ $vrr?->modality === $mod ? 'on' : '' }}"></div>&nbsp;{{ $mod }}</div>@endforeach
            </div>

            <div class="rv-g4" style="margin-bottom:7px;">
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Date</span><span class="rv-val rv-val-rad">{{ $vrr?->date_requested?->format('Y-m-d') ?? $vrr?->created_at->format('Y-m-d') }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">RAD File No.</span><span class="rv-val rv-val-rad" style="font-family:monospace;font-weight:bold;">{{ $vrr?->request_no }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Hospital No.</span><span class="rv-val rv-val-rad">{{ $vrrPatient?->case_no ?? '—' }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Service / Ward</span><span class="rv-val rv-val-rad">{{ $vrr?->ward ?? '—' }}</span></div>
            </div>

            <div class="rv-source-row">
                @foreach(['OPD','ER','PRIVATE','PHIC','CHARITY / INDIGENT'] as $s)
                @php $match = $vrrSrc === strtoupper($s) || ($vrrSrc === 'CHARITY' && str_contains($s,'CHARITY')); @endphp
                <div class="rv-source-opt"><div class="rv-checkbox {{ $match ? 'on' : '' }}"></div>&nbsp;{{ $s }}</div>
                @endforeach
            </div>

            <div class="rv-sec-label">Patient Name</div>
            <div class="rv-g3">
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Family Name</span><span class="rv-val rv-val-rad">{{ strtoupper($vrrPatient?->family_name ?? '—') }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Given Name</span><span class="rv-val rv-val-rad">{{ strtoupper($vrrPatient?->first_name ?? '—') }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Middle Name</span><span class="rv-val rv-val-rad">{{ strtoupper($vrrPatient?->middle_name ?? '—') }}</span></div>
            </div>
            <div class="rv-g2x">
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Address</span><span class="rv-val rv-val-rad">{{ $vrrPatient?->address ?? '—' }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Date of Birth</span><span class="rv-val rv-val-rad">{{ $vrrPatient?->birthday?->format('Y-m-d') ?? '—' }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Age</span><span class="rv-val rv-val-rad">{{ $vrrPatient?->age_display ?? $vrrPatient?->current_age ?? '—' }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Sex</span><span class="rv-val rv-val-rad">{{ $vrrPatient?->sex ?? '—' }}</span></div>
            </div>
            <hr class="rv-divider">
            <div style="margin-bottom:7px;"><span class="rv-fl rv-fl-rad">Examination Desired</span><div class="rv-area-val">{{ $vrr?->examination_desired ?? '—' }}</div></div>
            <div style="margin-bottom:7px;"><span class="rv-fl rv-fl-rad">Clinical Diagnosis</span><div class="rv-area-val">{{ $vrr?->clinical_diagnosis ?? '—' }}</div></div>
            <div style="margin-bottom:9px;"><span class="rv-fl rv-fl-rad">Pertinent / Brief Clinical Findings</span><div class="rv-area-val">{{ $vrr?->clinical_findings ?? '—' }}</div></div>
            <hr class="rv-divider">

            <div style="margin-top:10px;margin-bottom:12px;">
                <div class="rv-sec-label">Requesting Physician</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:end;">
                    <div><span class="rv-fl rv-fl-rad">Name</span><div class="rv-val rv-val-rad" style="font-size:11pt;font-weight:bold;">{{ $vrr?->requesting_physician ?? ($vrr?->doctor ? 'Dr. '.$vrr->doctor->name : '—') }}</div></div>
                    <div><div class="rv-sig-line"></div><div class="rv-sig-cap">Signature over Printed Name / PRC No.</div></div>
                </div>
            </div>

            <div>
                <div class="rv-sec-label">Radiologist Interpretation / Findings</div>
                <div class="rv-interp-area">{{ $vrr?->radiologist_interpretation ?? '' }}</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:6px;">
                    <div><div class="rv-sig-line"></div><div class="rv-sig-cap">Radiologist — Signature / PRC No.</div></div>
                    <div><div class="rv-sig-line"></div><div class="rv-sig-cap">Date &amp; Time Reported</div></div>
                </div>
            </div>
            <hr class="rv-divider" style="margin-top:12px;">
            <div class="rv-footer4">
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Date Requested</span><span class="rv-val rv-val-rad">{{ $vrr?->date_requested?->format('Y-m-d') ?? '—' }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Request Received</span><span class="rv-val rv-val-rad" style="font-size:9pt;">{{ $vrr?->request_received_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Exam Started</span><span class="rv-val rv-val-rad" style="font-size:9pt;">{{ $vrr?->exam_started_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
                <div class="rv-fg"><span class="rv-fl rv-fl-rad">Exam Done</span><span class="rv-val rv-val-rad" style="font-size:9pt;">{{ $vrr?->exam_done_at?->timezone('Asia/Manila')->format('M j, Y g:i A') ?? '' }}</span></div>
            </div>
        </div>

        {{-- Result box --}}
        <div class="rv-result-box">
            <p class="rv-result-title">✅ {{ $vrrUploads->count() }} Result File(s) — {{ $vrr?->request_no }}</p>
            <p style="font-size:.82rem;color:#6d28d9;font-weight:600;margin:-4px 0 12px 2px;">Uploaded by {{ $vrrUploader }}</p>
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:4px;">
                @foreach($vrrUploads as $u)
                <div><a href="{{ $u->file_url }}" target="_blank" class="rv-file-link-rad">{{ $u->file_type_icon }} {{ $u->file_name }} <span style="font-size:.7rem;font-weight:400;color:#6b7280;">({{ $u->file_size_human }})</span></a></div>
                @endforeach
            </div>
            @if($vrrInterp)
            <div class="rv-interp-box">
                <p class="rv-interp-label">📋 Radiologist Interpretation</p>
                <div class="rv-interp-text">{{ $vrrInterp }}</div>
            </div>
            @endif
        </div>

        {{-- ── RESULTS LIST VIEW ──────────────────────────────────────── --}}
        @else

        <div class="sec-head">
            <h2 class="sec-title">Lab &amp; Radiology</h2>
            <span style="font-size:.78rem;color:#6b7280;">{{ $this->labRequestsCount + $this->radRequestsCount }} request(s) &nbsp;·&nbsp; <span style="color:#059669;font-weight:700;">{{ $totalResults }} result(s) ready</span></span>
        </div>

        {{-- New request buttons --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:22px;">
            <a href="{{ route('forms.lab-request', ['visit' => $visit->id]) }}" target="_blank" rel="noopener" class="doc-card"><span class="doc-card-icon">🧪</span><div class="doc-card-body"><p class="doc-card-label doc-card-label-green">LAB-001-1</p><p class="doc-card-title">New Lab Request</p><p class="doc-card-meta">CBC · Chemistry · Serology · Microscopy · Microbiology</p></div><span class="doc-card-arrow">↗</span></a>
            <a href="{{ route('forms.radiology-request', ['visit' => $visit->id]) }}" target="_blank" rel="noopener" class="doc-card"><span class="doc-card-icon">🩻</span><div class="doc-card-body"><p class="doc-card-label doc-card-label-purple">RAD</p><p class="doc-card-title">New Radiology Request</p><p class="doc-card-meta">X-Ray · Ultrasound · CT Scan</p></div><span class="doc-card-arrow">↗</span></a>
        </div>

        {{-- Completed results — grouped per request, clickable --}}
        @php
            $labByRequest = $labResults->groupBy('request_id');
            $radByRequest = $radResults->groupBy('request_id');
        @endphp

        @if($labByRequest->isNotEmpty())
        <div class="results-section">
            <div class="results-section-title"><span>🧪 Laboratory Results</span><div class="results-section-line"></div><span class="results-badge results-badge-lab">{{ $labByRequest->count() }} ready</span></div>
            @foreach($labByRequest as $reqId => $uploads)
            @php
                $lReq = \App\Models\LabRequest::find($reqId);
                $firstUpload = $uploads->first();
            @endphp
            <div class="result-req-card" wire:click="viewLabResult({{ $reqId }})" wire:key="lab-req-{{ $reqId }}">
                <div class="rrc-top">
                    <div style="flex:1;">
                        <span class="rrc-req-no">{{ $lReq?->request_no ?? 'LAB' }}</span>
                        @if($lReq?->request_type === 'stat')<span style="background:#fee2e2;color:#991b1b;font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:9999px;margin-left:5px;">⚡ STAT</span>@endif
                        <p class="rrc-diag">{{ $lReq?->clinical_diagnosis ?? 'Laboratory Result' }}</p>
                        <p class="rrc-meta">Uploaded by {{ $firstUpload?->uploadedBy?->name ?? 'Tech' }} · {{ $firstUpload?->created_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</p>
                        @if($lReq && $lReq->tests && count($lReq->tests))
                        <div class="rrc-tests" style="margin-top:5px;">@foreach(array_slice($lReq->tests, 0, 5) as $t)<span class="rrc-test-chip">{{ $t }}</span>@endforeach @if(count($lReq->tests)>5)<span class="rrc-test-chip" style="background:#e5e7eb;color:#6b7280;">+{{ count($lReq->tests)-5 }} more</span>@endif</div>
                        @endif
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                        <span class="rrc-badge">Lab Result</span>
                        <span class="rrc-arrow">→</span>
                    </div>
                </div>
                <div class="rrc-files">
                    @foreach($uploads as $u)<span class="rrc-file-chip">{{ $u->file_type_icon }} {{ $u->file_name }}</span>@endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if($radByRequest->isNotEmpty())
        <div class="results-section">
            <div class="results-section-title"><span>🩻 Radiology Results</span><div class="results-section-line"></div><span class="results-badge results-badge-rad">{{ $radByRequest->count() }} ready</span></div>
            @foreach($radByRequest as $reqId => $uploads)
            @php
                $rReq = $uploads->first()?->radRequest;
                $firstUpload = $uploads->first();
            @endphp
            <div class="result-req-card result-req-card-rad" wire:click="viewRadResult({{ $reqId }})" wire:key="rad-req-{{ $reqId }}">
                <div class="rrc-top">
                    <div style="flex:1;">
                        <span class="rrc-req-no rrc-req-no-rad">{{ $rReq?->request_no ?? 'RAD' }}</span>
                        @if($rReq?->modality)<span style="background:#f5f3ff;color:#5b21b6;font-size:.68rem;font-weight:700;padding:1px 7px;border-radius:9999px;margin-left:5px;">{{ $rReq->modality }}</span>@endif
                        <p class="rrc-diag">{{ $rReq?->examination_desired ?? 'Radiology Result' }}</p>
                        @if($rReq?->clinical_diagnosis)<p class="rrc-meta">Dx: {{ $rReq->clinical_diagnosis }}</p>@endif
                        <p class="rrc-meta">Uploaded by {{ $firstUpload?->uploadedBy?->name ?? 'Tech' }} · {{ $firstUpload?->created_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</p>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                        <span class="rrc-badge rrc-badge-rad">{{ $rReq?->modality ?? 'Radiology' }}</span>
                        <span class="rrc-arrow">→</span>
                    </div>
                </div>
                <div class="rrc-files">
                    @foreach($uploads as $u)<span class="rrc-file-chip">{{ $u->file_type_icon }} {{ $u->file_name }}</span>@endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Pending requests --}}
        @php
            $pendingLabReqs = \App\Models\LabRequest::where('visit_id', $visit->id)->whereIn('status', ['pending', 'in_progress'])->orderBy('created_at', 'desc')->get();
            $pendingRadReqs = \App\Models\RadiologyRequest::where('visit_id', $visit->id)->whereIn('status', ['pending', 'in_progress'])->orderBy('created_at', 'desc')->get();
        @endphp
        @if($pendingLabReqs->isNotEmpty() || $pendingRadReqs->isNotEmpty())
        <div class="results-section" style="margin-top:4px;">
            <div class="results-section-title"><span>⏳ Pending / In Progress</span><div class="results-section-line"></div><span class="results-badge" style="background:#fef3c7;color:#92400e;">{{ $pendingLabReqs->count() + $pendingRadReqs->count() }} waiting</span></div>
            @foreach($pendingLabReqs as $req)
            <div class="pending-req-card">
                <div class="pending-req-info"><p class="pending-req-no">{{ $req->request_no }}</p><p class="pending-req-diag">🧪 Lab &nbsp;·&nbsp;@if($req->tests && count($req->tests)){{ implode(', ', array_slice($req->tests, 0, 3)) }}@if(count($req->tests) > 3) <span style="color:#9ca3af;">+{{ count($req->tests) - 3 }}</span>@endif@else—@endif</p><p style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Requested {{ $req->created_at->timezone('Asia/Manila')->diffForHumans() }}</p></div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;"><span class="pending-badge {{ $req->request_type === 'stat' ? 'pending-badge-stat' : '' }}">{{ strtoupper($req->request_type ?? 'ROUTINE') }}</span><span style="font-size:.68rem;background:#f3f4f6;color:#6b7280;padding:2px 7px;border-radius:9999px;font-weight:600;">{{ ucfirst(str_replace('_', ' ', $req->status)) }}</span></div>
            </div>
            @endforeach
            @foreach($pendingRadReqs as $req)
            <div class="pending-req-card">
                <div class="pending-req-info"><p class="pending-req-no" style="color:#6d28d9;">{{ $req->request_no }}</p><p class="pending-req-diag">🩻 {{ $req->modality ?? 'Radiology' }} &nbsp;·&nbsp;{{ \Str::limit($req->examination_desired ?? '—', 55) }}</p><p style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Requested {{ $req->created_at->timezone('Asia/Manila')->diffForHumans() }}</p></div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">@if($req->modality)<span style="background:#f5f3ff;color:#5b21b6;font-size:.68rem;font-weight:700;padding:2px 7px;border-radius:9999px;">{{ $req->modality }}</span>@endif<span style="font-size:.68rem;background:#f3f4f6;color:#6b7280;padding:2px 7px;border-radius:9999px;font-weight:600;">{{ ucfirst(str_replace('_', ' ', $req->status)) }}</span></div>
            </div>
            @endforeach
        </div>
        @endif

        @if($labResults->isEmpty() && $radResults->isEmpty() && $pendingLabReqs->isEmpty() && $pendingRadReqs->isEmpty())
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:4px;">
            <div class="placeholder-card"><div class="ph-icon">📋</div><p class="ph-title">No lab requests yet</p><p class="ph-sub">Click "New Lab Request" above to submit a request to the lab.</p></div>
            <div class="placeholder-card"><div class="ph-icon">🖼</div><p class="ph-title">No radiology requests yet</p><p class="ph-sub">Click "New Radiology Request" above to submit an imaging request.</p></div>
        </div>
        @endif

        @endif {{-- end if viewing / else list --}}

        @endif {{-- end activeTab results --}}

    </div>{{-- /.chart-content --}}
</div>{{-- /.chart-page --}}

@else
<div style="text-align:center;padding:60px 20px;">
    <p style="color:#9ca3af;margin-bottom:10px;">Visit not found or not accessible.</p>
    <a href="{{ \App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index') }}" style="color:#1d4ed8;font-size:.875rem;">← Back to Admitted Patients</a>
</div>
@endif

</x-filament-panels::page>