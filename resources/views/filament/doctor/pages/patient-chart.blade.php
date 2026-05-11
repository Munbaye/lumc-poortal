<x-filament-panels::page>

    <style>
    /* ══ Doctor Chart — Design Tokens (Teal) ════════════════════════════ */
        :root {
        --hdr-teal-50:  #f0fdfa;
        --hdr-teal-100: #ccfbf1;
        --hdr-teal-400: #2dd4bf;
        --hdr-teal-500: #14b8a6;
        --hdr-teal-600: #0d9488;
        --hdr-teal-700: #0f766e;
        --hdr-teal-800: #115e59;
        --hdr-teal-900: #134e4a;
        --hdr-slate-200: #e2e8f0;
        --hdr-slate-400: #94a3b8;
        --hdr-slate-800: #1e293b;
        --hdr-radius-sm:   6px;
        --hdr-radius:      10px;
        --hdr-radius-lg:   14px;
        --hdr-radius-full: 9999px;
        --hdr-transition: 150ms cubic-bezier(0.4,0,0.2,1);
        }
        .dark {
        --hdr-slate-200: #374151;
        --hdr-slate-400: #6b7280;
        --hdr-slate-800: #1f2937;
        }

        /* ── Outer page wrapper ─────────────────────────────────────────────── */
        .chart-page {
        display: flex; flex-direction: column;
        border: 1px solid #e5e7eb; border-radius: 12px;
        overflow: hidden; background: #fff;
        box-shadow: 0 4px 20px rgba(13,148,136,.07);
        }
        .dark .chart-page { background: #111827; border-color: #374151; box-shadow: 0 4px 20px rgba(0,0,0,.3); }

        /* ── Chart header wrapper ───────────────────────────────────────────── */
        .chart-header { padding: 0; border-bottom: none; background: transparent; }

        /* ── Hero band ──────────────────────────────────────────────────────── */
        .chart-hdr-hero {
        background: linear-gradient(145deg, #134e4a 0%, #0f766e 40%, #0d9488 75%, #14b8a6 100%);
        padding: 20px 24px 30px;
        position: relative; overflow: hidden;
        }
        .dark .chart-hdr-hero {
        background: linear-gradient(145deg, #042f2e 0%, #134e4a 40%, #0f766e 75%, #0d9488 100%);
        }
        .chart-hdr-hero::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 15% 40%, rgba(255,255,255,.09) 0%, transparent 55%),
            radial-gradient(ellipse 50% 40% at 85% 65%, rgba(255,255,255,.05) 0%, transparent 50%);
        pointer-events: none;
        }

        /* ── Top row: name left, buttons right ─────────────────────── */
        .chart-hdr-top-row {
        display: flex; align-items: flex-start;
        justify-content: space-between;
        gap: 16px; flex-wrap: wrap;
        position: relative; z-index: 1;
        }

        /* ── Patient name ───────────────────────────────────────────────────── */
        .pt-name-big {
        font-size: clamp(1.1rem, 2.5vw, 1.45rem);
        font-weight: 800; color: #fff;
        letter-spacing: -.02em; line-height: 1.2;
        text-shadow: 0 1px 3px rgba(0,0,0,.18); margin: 0;
        }
        .pt-case-big {
        font-size: .73rem; color: rgba(255,255,255,.75);
        margin-top: 7px;
        display: flex; align-items: center; gap: 9px; flex-wrap: wrap;
        line-height: 1.4;
        }
        .svc-pill {
        display: inline-flex; align-items: center;
        background: rgba(255,255,255,.14); color: #fff;
        border: 1px solid rgba(255,255,255,.25);
        font-size: .65rem; font-weight: 700;
        padding: 3px 12px; border-radius: var(--hdr-radius-full);
        white-space: nowrap; letter-spacing: .03em;
        }
        .readonly-badge-hero {
        display: inline-flex; align-items: center; gap: 4px;
        background: #fef9c3; color: #92400e;
        border: 1.5px solid #fde68a;
        font-size: .62rem; font-weight: 700;
        padding: 2px 10px; border-radius: var(--hdr-radius-full);
        white-space: nowrap;
        }
        .readonly-badge-hero::before {
        content: ''; display: inline-block;
        width: 5px; height: 5px;
        background: #f59e0b; border-radius: 50%; flex-shrink: 0;
        }

        /* ── Header action buttons ──────────────────────────────────────────── */
        .chart-header-actions {
        display: flex; gap: 8px; align-items: center;
        flex-shrink: 0; flex-wrap: wrap;
        }
        .btn-back-hdr {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.11);
        border: 1.5px solid rgba(255,255,255,.22);
        color: rgba(255,255,255,.9);
        font-size: .76rem; font-weight: 600;
        padding: 8px 15px; border-radius: var(--hdr-radius-sm);
        text-decoration: none; flex-shrink: 0; cursor: pointer;
        white-space: nowrap;
        transition: background var(--hdr-transition), border-color var(--hdr-transition), transform var(--hdr-transition);
        }
        .btn-back-hdr:hover {
        background: rgba(255,255,255,.21);
        border-color: rgba(255,255,255,.38);
        color: #fff; transform: translateY(-1px);
        }
        .btn-back-hdr:active { transform: translateY(0); }
        .btn-back-hdr svg { width: 14px; height: 14px; flex-shrink: 0; opacity: .85; }

        /* ── Info strip — floats out of hero ────────────────────────── */
        .chart-hdr-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        background: #fff;
        position: relative; z-index: 2;
        margin: -14px 22px 8px;
        border-radius: var(--hdr-radius-lg);
        border-top: 2px solid rgba(13,148,136,.1);
        box-shadow:
            0 2px 4px rgba(0,0,0,.04),
            0 6px 12px rgba(0,0,0,.05),
            0 12px 24px rgba(0,0,0,.03);
        overflow: hidden;
        }
        .dark .chart-hdr-strip {
        background: #1f2937;
        border-top: 2px solid rgba(45,212,191,.12);
        box-shadow: 0 2px 4px rgba(0,0,0,.2), 0 6px 12px rgba(0,0,0,.15);
        }
        .chart-hdr-strip::before {
        content: '';
        position: absolute; left: 0; top: 10px; bottom: 10px; width: 3px;
        background: linear-gradient(to bottom, #0f766e, #2dd4bf);
        border-radius: 0 3px 3px 0; opacity: .5;
        }
        .dark .chart-hdr-strip::before { opacity: .4; }

        /* Info cells */
        .hdr-info-cell {
        padding: 14px 18px;
        display: flex; flex-direction: column; justify-content: center; gap: 5px;
        position: relative; min-height: 64px;
        }
        .hdr-info-cell:first-child { padding-left: 22px; }
        .hdr-info-cell:not(:last-child)::after {
        content: '';
        position: absolute; right: 0; top: 50%;
        transform: translateY(-50%);
        width: 1px; height: 48%;
        background: linear-gradient(to bottom,
            transparent,
            var(--hdr-slate-200) 20%,
            var(--hdr-slate-200) 80%,
            transparent);
        }
        .hdr-info-cell:last-child::after { display: none; }
        .pl {
        font-size: .59rem; text-transform: uppercase;
        letter-spacing: .09em; color: var(--hdr-slate-400);
        font-weight: 700; margin: 0; white-space: nowrap; line-height: 1.2;
        }
        .pv {
        font-size: .84rem; font-weight: 700;
        color: var(--hdr-slate-800); line-height: 1.35; margin: 0;
        word-break: break-word;
        }
        .dark .pv { color: #f3f4f6; }

        /* NICU badge */
        .nicu-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: #fef3c7; color: #92400e;
        border: 1.5px solid #fde68a;
        padding: 3px 10px; border-radius: var(--hdr-radius-full);
        font-size: .65rem; font-weight: 700; white-space: nowrap;
        }
        .nicu-badge::before {
        content: ''; display: inline-block;
        width: 5px; height: 5px;
        background: #f59e0b; border-radius: 50%; flex-shrink: 0;
        }

        /* ── Tab bar ────────────────────────────────────────────────────────── */
        .chart-tabs-wrap {
        position: relative; overflow-x: auto; overflow-y: visible;
        background: #fff; border-bottom: 1px solid #e5e7eb;
        margin-top: 2px;
        }
        .dark .chart-tabs-wrap { background: #1f2937; border-bottom-color: #374151; }
        .chart-tabs-wrap::-webkit-scrollbar { display: none; }
        .chart-tabs {
        display: flex; align-items: center; justify-content: center;
        flex-wrap: nowrap; padding: 0 20px;
        overflow-x: auto; -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;
        }
        .chart-tabs::-webkit-scrollbar { display: none; }
        .chart-tab {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 12px 15px; font-size: .78rem; font-weight: 600;
        color: #6b7280; cursor: pointer; border: none; background: none;
        border-bottom: 2.5px solid transparent; margin-bottom: -1px;
        white-space: nowrap; flex-shrink: 0;
        transition: color var(--hdr-transition), border-color var(--hdr-transition);
        }
        .chart-tab:hover { color: #374151; }
        .dark .chart-tab { color: #9ca3af; }
        .dark .chart-tab:hover { color: #e5e7eb; }
        .chart-tab.active { color: #0d9488; border-bottom-color: #0d9488; font-weight: 700; }
        .dark .chart-tab.active { color: #2dd4bf; border-bottom-color: #2dd4bf; }
        .chart-tab svg { width: 14px; height: 14px; flex-shrink: 0; opacity: .78; transition: opacity var(--hdr-transition); }
        .chart-tab:hover svg, .chart-tab.active svg { opacity: 1; }
        .chart-tab:focus-visible { outline: 2px solid #0d9488; outline-offset: 2px; }

        /* ── Tab badges ── */
        .tab-badge {
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .59rem; font-weight: 800;
        padding: 1px 6px; border-radius: var(--hdr-radius-full);
        min-width: 18px; text-align: center; line-height: 1.5;
        border: 1.5px solid transparent;
        transition: all var(--hdr-transition);
        }
        /* Teal — default */
        .tab-badge          { background: var(--hdr-teal-50); color: var(--hdr-teal-700); border-color: var(--hdr-teal-100); }
        .chart-tab.active .tab-badge { background: var(--hdr-teal-700); color: #fff; border-color: var(--hdr-teal-700); }
        /* Green */
        .tab-badge-green    { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .chart-tab.active .tab-badge-green { background: #15803d; color: #fff; border-color: #15803d; }
        /* Blue */
        .tab-badge-blue     { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .chart-tab.active .tab-badge-blue { background: #1d4ed8; color: #fff; border-color: #1d4ed8; }

        /* ── Responsive ─────────────────────────────────────────────────────── */
        @media (max-width: 900px) {
        .chart-hdr-strip { grid-template-columns: repeat(2, 1fr); margin: -12px 14px 6px; }
        .hdr-info-cell:nth-child(even)::after { display: none; }
        .hdr-info-cell { padding: 12px 14px; min-height: 54px; }
        .hdr-info-cell:first-child { padding-left: 18px; }
        }
        @media (max-width: 600px) {
        .chart-hdr-hero { padding: 14px 14px 22px; }
        .chart-hdr-top-row { flex-direction: column; gap: 12px; }
        .chart-header-actions { width: 100%; }
        .chart-hdr-strip { grid-template-columns: 1fr 1fr; margin: -10px 10px 4px; border-radius: var(--hdr-radius); }
        .pt-name-big { font-size: 1.1rem; }
        .chart-tabs { justify-content: flex-start; padding: 0 12px; }
        .chart-tab { padding: 10px 11px; font-size: .75rem; }
        }
        @media (min-width: 1280px) {
        .chart-hdr-hero { padding: 22px 32px 32px; }
        .chart-hdr-strip { margin: -14px 28px 10px; }
        .hdr-info-cell { padding: 16px 22px; }
        .hdr-info-cell:first-child { padding-left: 26px; }
        .chart-tabs { padding: 0 32px; }
        }
        @media (prefers-reduced-motion: reduce) {
        .btn-back-hdr, .chart-tab, .tab-badge { transition: none; }
        .btn-back-hdr:hover { transform: none; }
        }

        /* ── Tab content ─────────────────────────────────────────────────── */
        .chart-content {
            padding: 24px 28px;
            background: #f9fafb;
            min-height: 420px;
        }

        .dark .chart-content {
            background: #111827;
        }

        /* ── Section heading ─────────────────────────────────────────────── */
        .sec-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .dark .sec-head {
            border-bottom-color: #374151;
        }

        .sec-title {
            font-size: .95rem;
            font-weight: 700;
            color: #111827;
        }

        .dark .sec-title {
            color: #f3f4f6;
        }

        .placeholder-card {
            text-align: center;
            padding: 52px 24px;
            background: #fff;
            border: 1.5px dashed #e5e7eb;
            border-radius: 8px;
        }

        .dark .placeholder-card {
            background: #1f2937;
            border-color: #374151;
        }

        .placeholder-card .ph-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            margin: 0 auto 10px;
        }

        .placeholder-card .ph-icon svg {
            width: 100%;
            height: 100%;
        }

        .placeholder-card .ph-title {
            font-size: .92rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 4px;
        }

        .dark .placeholder-card .ph-title {
            color: #e5e7eb;
        }

        .placeholder-card .ph-sub {
            font-size: .8rem;
            color: #9ca3af;
        }

        /* ── Forms tab ───────────────────────────────────────────────────── */
        .form-section {
            margin-bottom: 32px;
        }

        .form-section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .form-section-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #6b7280;
            white-space: nowrap;
        }

        .form-section-line {
            flex: 1;
            border-top: 1px solid #e5e7eb;
        }

        .dark .form-section-line {
            border-top-color: #374151;
        }

        .form-section-badge {
            font-size: .65rem;
            font-weight: 700;
            padding: 1px 8px;
            border-radius: 9999px;
            white-space: nowrap;
        }

        .form-section-badge-saved {
            background: #d1fae5;
            color: #065f46;
        }

        .form-section-badge-missing {
            background: #fef3c7;
            color: #92400e;
        }

        .form-iframe-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        }

        .dark .form-iframe-wrap {
            border-color: #374151;
        }

        .form-iframe-wrap iframe {
            display: block;
            width: 100%;
            border: none;
        }

        .form-missing-card {
            background: #fff;
            border: 1.5px dashed #e5e7eb;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
        }

        .dark .form-missing-card {
            background: #1f2937;
            border-color: #374151;
        }

        .form-missing-icon {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .form-missing-text {
            font-size: .82rem;
            color: #9ca3af;
        }

        /* ── Document link cards ─────────────────────────────────────────── */
        .doc-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .dark .doc-card {
            background: #1f2937;
            border-color: #374151;
        }

        .doc-card:hover {
            border-color: #1d4ed8;
            box-shadow: 0 2px 12px rgba(29, 78, 216, .12);
        }

        .doc-card-icon {
            font-size: 2rem;
            flex-shrink: 0;
        }

        .doc-card-body {
            flex: 1;
        }

        .doc-card-title {
            font-size: .92rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 3px;
        }

        .dark .doc-card-title {
            color: #f3f4f6;
        }

        .doc-card-meta {
            font-size: .75rem;
            color: #6b7280;
        }

        .doc-card-arrow {
            font-size: 1.1rem;
            color: #9ca3af;
            flex-shrink: 0;
        }

        .doc-card-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 4px;
            margin-bottom: 4px;
            display: inline-block;
        }

        .doc-card-label-blue {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .doc-card-label-green {
            background: #f0fdf4;
            color: #065f46;
        }

        .doc-card-label-purple {
            background: #faf5ff;
            color: #6d28d9;
        }

        /* Vitals */
        .vitals-wrap {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: auto;
        }

        .dark .vitals-wrap {
            background: #1f2937;
            border-color: #374151;
        }

        .vitals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        .vitals-table th {
            background: #f3f4f6;
            padding: 8px 11px;
            text-align: left;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }

        .dark .vitals-table th {
            background: #111827;
            color: #9ca3af;
            border-bottom-color: #374151;
        }

        .vitals-table td {
            padding: 9px 11px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }

        .dark .vitals-table td {
            border-bottom-color: #1f2937;
            color: #d1d5db;
        }

        .vitals-table tr:last-child td {
            border-bottom: none;
        }

        .vitals-table tr:hover td {
            background: #f9fafb;
        }

        .dark .vitals-table tr:hover td {
            background: rgba(255, 255, 255, .03);
        }

        .abnormal {
            color: #dc2626 !important;
            font-weight: 700;
        }

        /* ── Orders ─────────────────────────────────────────────────────── */
        .order-group-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .order-group-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6b7280;
            white-space: nowrap;
        }

        .order-group-line {
            flex: 1;
            border-top: 1px solid #e5e7eb;
        }

        .dark .order-group-line {
            border-top-color: #374151;
        }

        .order-group-doc {
            font-size: .7rem;
            color: #9ca3af;
            white-space: nowrap;
        }

        .order-item {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            padding: 11px 14px;
            margin-bottom: 7px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: start;
        }

        .dark .order-item {
            background: #1f2937;
            border-color: #374151;
        }

        .order-item:hover {
            border-color: #d1d5db;
        }

        .order-num {
            font-size: .68rem;
            color: #9ca3af;
            font-family: monospace;
            margin-bottom: 2px;
        }

        .order-text {
            font-size: .875rem;
            color: #111827;
            font-weight: 500;
            line-height: 1.4;
        }

        .dark .order-text {
            color: #f3f4f6;
        }

        .order-meta {
            font-size: .7rem;
            color: #9ca3af;
            margin-top: 3px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: .68rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-carried {
            background: #d1fae5;
            color: #065f46;
        }

        .status-discontinued {
            background: #fee2e2;
            color: #991b1b;
        }

        .order-text-discontinued {
            text-decoration: line-through;
            opacity: .6;
        }

        .btn-discontinue {
            font-size: .7rem;
            color: #9ca3af;
            background: none;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 3px 9px;
            cursor: pointer;
            margin-top: 4px;
        }

        .btn-discontinue:hover {
            border-color: #dc2626;
            color: #dc2626;
        }

        /* ── Order form ──────────────────────────────────────────────────── */
        .order-form-wrap {
            background: #fff;
            border: 1.5px solid #3b82f6;
            border-radius: 10px;
            padding: 20px 22px;
            margin-bottom: 20px;
        }

        .dark .order-form-wrap {
            background: #1f2937;
            border-color: #2563eb;
        }

        .order-form-title {
            font-size: .85rem;
            font-weight: 700;
            color: #1d4ed8;
            margin-bottom: 6px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eff6ff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .dark .order-form-title {
            border-bottom-color: #1e3a5f;
        }

        .order-form-subtitle {
            font-size: .75rem;
            color: #6b7280;
            font-weight: 400;
            margin-bottom: 10px;
        }

        .quick-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 12px;
        }

        .quick-chip {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            font-size: .73rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
            line-height: 1.4;
            transition: background .12s, border-color .12s;
        }

        .quick-chip:hover {
            background: #dbeafe;
            border-color: #93c5fd;
        }

        .dark .quick-chip {
            background: #1e3a5f;
            border-color: #1e40af;
            color: #93c5fd;
        }

        .dark .quick-chip:hover {
            background: #1e40af;
        }

        .order-textarea {
            width: 100%;
            min-height: 200px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: .92rem;
            line-height: 1.8;
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #111827;
            background: #fff;
            outline: none;
            resize: vertical;
            box-sizing: border-box;
        }

        .dark .order-textarea {
            background: #111827;
            border-color: #374151;
            color: #f3f4f6;
        }

        .order-textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .order-textarea::placeholder {
            color: #9ca3af;
        }

        .order-textarea-hint {
            font-size: .72rem;
            color: #9ca3af;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Buttons */
        .btn-primary {
            background: #1d4ed8;
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 9px 22px;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary:hover {
            background: #1e40af;
        }

        .btn-secondary {
            background: #fff;
            color: #374151;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            padding: 9px 18px;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
        }

        .dark .btn-secondary {
            background: #374151;
            color: #e5e7eb;
            border-color: #4b5563;
        }

        .btn-secondary:hover {
            background: #f3f4f6;
        }

        .btn-write {
            background: #059669;
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

        .btn-write:hover {
            background: #047857;
        }

        .btn-write.is-cancel {
            background: #6b7280;
        }

        .btn-write.is-cancel:hover {
            background: #4b5563;
        }

        .btn-lab {
            background: #7c3aed;
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

        .btn-lab:hover {
            background: #6d28d9;
        }

        /* ═══ RESULT LIST ══════════════════════════════════════════════════ */
        .results-section {
            margin-bottom: 28px;
        }

        .results-section-title {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .results-section-line {
            flex: 1;
            border-top: 1px solid #e5e7eb;
        }

        .dark .results-section-line {
            border-top-color: #374151;
        }

        .results-badge {
            font-size: .68rem;
            padding: 2px 8px;
            border-radius: 9999px;
            font-weight: 700;
        }

        .results-badge-lab {
            background: #d1fae5;
            color: #065f46;
        }

        .results-badge-rad {
            background: #f5f3ff;
            color: #5b21b6;
        }

        .result-req-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 10px;
            border-left: 3px solid #059669;
            cursor: pointer;
            transition: border-color .15s, box-shadow .15s;
        }

        .dark .result-req-card {
            background: #1f2937;
            border-color: #374151;
        }

        .result-req-card:hover {
            border-color: #059669;
            box-shadow: 0 2px 10px rgba(5, 150, 105, .12);
        }

        .result-req-card-rad {
            border-left-color: #6d28d9;
        }

        .result-req-card-rad:hover {
            border-color: #6d28d9;
            box-shadow: 0 2px 10px rgba(109, 40, 217, .12);
        }

        .rrc-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 8px;
        }

        .rrc-req-no {
            font-family: monospace;
            font-size: .78rem;
            font-weight: 700;
            color: #059669;
        }

        .rrc-req-no-rad {
            color: #6d28d9;
        }

        .rrc-badge {
            font-size: .68rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 9999px;
            background: #f0fdf4;
            color: #065f46;
        }

        .rrc-badge-rad {
            background: #f5f3ff;
            color: #5b21b6;
        }

        .rrc-diag {
            font-size: .82rem;
            color: #374151;
            font-weight: 600;
            margin-top: 3px;
        }

        .dark .rrc-diag {
            color: #e5e7eb;
        }

        .rrc-meta {
            font-size: .72rem;
            color: #6b7280;
            margin-top: 3px;
        }

        .rrc-files {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .rrc-file-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .72rem;
            background: #f3f4f6;
            color: #374151;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .dark .rrc-file-chip {
            background: #374151;
            color: #d1d5db;
        }

        .rrc-arrow {
            font-size: .9rem;
            color: #9ca3af;
        }

        .rrc-tests {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 6px;
        }

        .rrc-test-chip {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 1px 7px;
            font-size: .68px;
            font-weight: 500;
        }

        .pending-req-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .dark .pending-req-card {
            background: #1f2937;
            border-color: #374151;
        }

        .pending-req-no {
            font-family: monospace;
            font-size: .75rem;
            color: #6b7280;
            font-weight: 700;
        }

        .pending-req-info {
            flex: 1;
        }

        .pending-req-diag {
            font-size: .83rem;
            color: #374151;
        }

        .dark .pending-req-diag {
            color: #d1d5db;
        }

        .pending-badge {
            background: #fef3c7;
            color: #92400e;
            font-size: .68rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 9999px;
        }

        .pending-badge-stat {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ── Result detail view ──────────────────────────────────────────── */
        .rv-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .82rem;
            color: #6b7280;
            background: none;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 16px;
            padding: 7px 14px;
        }

        .rv-back-btn:hover {
            border-color: #1d4ed8;
            color: #1d4ed8;
        }

        .rv-header-lab {
            background: linear-gradient(135deg, #7c2d12 0%, #f97316 100%);
            border-radius: 10px;
            padding: 16px 22px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .rv-header-rad {
            background: linear-gradient(135deg, #3b0764 0%, #6d28d9 100%);
            border-radius: 10px;
            padding: 16px 22px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .rv-req-no {
            font-family: monospace;
            font-size: 1.2rem;
            font-weight: 900;
            color: #fff;
        }

        .rv-patient {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            margin-top: 3px;
        }

        .rv-case {
            font-family: monospace;
            font-size: .78rem;
            color: #fed7aa;
            margin-top: 1px;
        }

        .rv-case-rad {
            color: #c4b5fd;
        }

        .rv-pill {
            padding: 5px 16px;
            border-radius: 9999px;
            font-size: .78rem;
            font-weight: 700;
            background: #16a34a;
            color: #fff;
        }

        .rv-modality {
            background: rgba(255, 255, 255, .2);
            color: #fff;
            padding: 4px 14px;
            border-radius: 9999px;
            font-size: .82rem;
            font-weight: 700;
        }

        .rv-paper {
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.45in 0.55in;
            margin-bottom: 16px;
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-size: 10pt;
            color: #000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        .rv-paper-rad {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            padding: 0.55in 0.65in;
        }

        .rv-hdr {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 7px;
            border-bottom: 2px solid #000;
            margin-bottom: 7px;
        }

        .rv-hdr-rad {
            border-bottom-width: 2.5px;
            gap: 14px;
        }

        .rv-logo {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            border: 1.5px dashed #bbb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6.5pt;
            color: #bbb;
            text-align: center;
            line-height: 1.3;
            border-radius: 3px;
        }

        .rv-logo img {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .rv-logo-rad {
            width: 60px;
            height: 60px;
        }

        .rv-logo-rad img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .rv-center {
            flex: 1;
            text-align: center;
            line-height: 1.25;
        }

        .rv-h-name {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .rv-h-name-rad {
            font-size: 14pt;
            letter-spacing: .06em;
        }

        .rv-h-sub {
            font-size: 10pt;
            font-weight: 600;
            color: #444;
            margin-top: 2px;
        }

        .rv-h-sub-rad {
            font-weight: bold;
        }

        .rv-h-ref {
            font-size: 7.5pt;
            color: #666;
            margin-top: 2px;
            font-family: monospace;
        }

        .rv-h-addr {
            font-size: 8pt;
            color: #444;
            margin-top: 2px;
        }

        .rv-g4 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 8px;
            margin-bottom: 5px;
        }

        .rv-g6 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 2fr;
            gap: 8px;
            margin-bottom: 5px;
        }

        .rv-g3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            margin-bottom: 7px;
        }

        .rv-g2x {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 10px;
            margin-bottom: 8px;
        }

        .rv-fg {
            margin-bottom: 0;
        }

        .rv-fl {
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #777;
            display: block;
            margin-bottom: 1px;
        }

        .rv-fl-rad {
            font-size: 8pt;
            letter-spacing: .05em;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .rv-val {
            font-size: 9.5pt;
            font-weight: 600;
            color: #000;
            border-bottom: 1px solid #bbb;
            padding: 1px 2px;
            min-height: 18px;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rv-val-rad {
            font-size: 10.5pt;
            font-weight: 500;
        }

        .rv-area-val {
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 5px 7px;
            font-size: 10.5pt;
            line-height: 1.65;
            min-height: 50px;
            color: #000;
            white-space: pre-wrap;
        }

        .rv-divider {
            border: none;
            border-top: 1px solid #000;
            margin: 5px 0;
        }

        .rv-divider-thick {
            border: none;
            border-top: 2px solid #000;
            margin: 8px 0;
        }

        .rv-phys {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 7px 12px;
            margin-bottom: 8px;
        }

        .rv-phys-label {
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #374151;
            margin-bottom: 5px;
        }

        .rv-phys-name {
            font-size: 10pt;
            font-weight: bold;
            color: #000;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            min-height: 22px;
        }

        .rv-sig-line {
            border-bottom: 1px solid #000;
            height: 28px;
            margin-top: 16px;
        }

        .rv-sig-cap {
            font-size: 8pt;
            text-align: center;
            font-style: italic;
            margin-top: 2px;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .rv-tests-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 6px;
            margin: 6px 0;
        }

        .rv-test-sec {
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        .rv-test-head {
            padding: 3px 7px;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .rv-test-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .rv-test-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 2px 7px;
            margin: 1px 4px;
            border-radius: 2px;
        }

        .rv-test-item.checked {
            background: #eef2ff;
        }

        .rv-cb {
            width: 11px;
            height: 11px;
            border: 1.5px solid #bbb;
            border-radius: 2px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .rv-test-item.checked .rv-cb {
            background: #4f46e5;
            border-color: #4f46e5;
        }

        .rv-test-item.checked .rv-cb::after {
            content: '';
            display: block;
            width: 5px;
            height: 3px;
            border-left: 1.5px solid #fff;
            border-bottom: 1.5px solid #fff;
            transform: rotate(-45deg) translate(1px, -1px);
        }

        .rv-test-name {
            font-size: 8pt;
            color: #374151;
            line-height: 1.3;
        }

        .rv-test-item.checked .rv-test-name {
            color: #3730a3;
            font-weight: 600;
        }

        .rv-micro-val {
            font-size: 8pt;
            color: #374151;
            border-bottom: 1px dashed #bbb;
            min-height: 16px;
            padding: 1px 2px;
        }

        .rv-footer5 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
            gap: 8px;
            margin-top: 8px;
        }

        .rv-footer4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 8px;
        }

        .rv-modality-row {
            display: flex;
            align-items: center;
            justify-content: space-around;
            border: 1.5px solid #000;
            padding: 8px 24px;
            margin-bottom: 8px;
        }

        .rv-modality-opt {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 12pt;
            font-weight: bold;
        }

        .rv-radio {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rv-radio.on {
            background: #000;
        }

        .rv-radio.on::after {
            content: '';
            width: 6px;
            height: 6px;
            background: #fff;
            border-radius: 50%;
            display: block;
        }

        .rv-source-row {
            display: flex;
            align-items: center;
            justify-content: space-around;
            border: 1px solid #ddd;
            background: #fafafa;
            padding: 6px 10px;
            margin-bottom: 8px;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .rv-source-opt {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10pt;
            font-weight: 600;
        }

        .rv-checkbox {
            width: 12px;
            height: 12px;
            border: 1.5px solid #555;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rv-checkbox.on {
            background: #000;
        }

        .rv-checkbox.on::after {
            content: '\2713';
            font-size: 8px;
            color: #fff;
            line-height: 1;
        }

        .rv-interp-area {
            border: 1.5px solid #000;
            padding: 8px 10px;
            min-height: 140px;
            font-size: 10.5pt;
            line-height: 1.75;
            color: #000;
            white-space: pre-wrap;
        }

        .rv-sec-label {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #444;
            margin-bottom: 5px;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .rv-result-box {
            background: #f0fdf4;
            border: 1.5px solid #22c55e;
            border-radius: 8px;
            padding: 18px 20px;
            margin-bottom: 16px;
        }

        .rv-result-title {
            font-size: .82rem;
            font-weight: 700;
            color: #15803d;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .rv-file-link-lab {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #1d4ed8;
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            padding: 5px 12px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
        }

        .rv-file-link-lab:hover {
            background: #dbeafe;
        }

        .rv-file-link-rad {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #6d28d9;
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            padding: 5px 12px;
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 6px;
        }

        .rv-file-link-rad:hover {
            background: #ede9fe;
        }

        .rv-notes-box {
            background: #fff;
            border: 1px solid #d1fae5;
            border-radius: 6px;
            padding: 10px 14px;
            margin-top: 12px;
        }

        .rv-notes-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #059669;
            margin-bottom: 5px;
        }

        .rv-interp-box {
            background: #fff;
            border: 1.5px solid #c4b5fd;
            border-radius: 8px;
            padding: 14px 16px;
            margin-top: 14px;
        }

        .rv-interp-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6d28d9;
            margin-bottom: 8px;
        }

        .rv-interp-text {
            font-family: 'Times New Roman', serif;
            font-size: .95rem;
            line-height: 1.8;
            color: #111827;
            white-space: pre-wrap;
        }

        /* History tab styles */
        .hx-table-wrap {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .dark .hx-table-wrap {
            background: #1f2937;
            border-color: #374151;
        }

        .hx-table {
            width: 100%;
            min-width: 780px;
            border-collapse: collapse;
            font-size: .83rem;
        }

        .hx-table thead {
            background: #f9fafb;
        }

        .dark .hx-table thead {
            background: #111827;
        }

        .hx-table th {
            padding: 10px 14px;
            text-align: left;
            font-size: .64rem;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #9ca3af;
            font-weight: 700;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
        }

        .dark .hx-table th {
            border-bottom-color: #374151;
            color: #6b7280;
        }

        .hx-table td {
            padding: 11px 14px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }

        .dark .hx-table td {
            border-bottom-color: #1f2937;
            color: #d1d5db;
        }

        .hx-table tr:last-child td {
            border-bottom: none;
        }

        /* Fixed column widths — prevent date from wrapping into 3 lines */
        .hx-table .col-date   { width: 160px; min-width: 160px; }
        .hx-table .col-type   { width: 90px;  min-width: 90px; }
        .hx-table .col-cc     { width: 17%;   min-width: 130px; }
        .hx-table .col-dx     { width: 17%;   min-width: 130px; }
        .hx-table .col-dr     { width: 140px; min-width: 120px; }
        .hx-table .col-status { width: 110px; min-width: 100px; }
        .hx-table .col-days   { width: 72px;  min-width: 60px; text-align: center; }
        .hx-table .col-rec    { width: 110px; min-width: 100px; }
        .hx-table .col-arrow  { width: 32px;  min-width: 32px; text-align: center; }

        .hx-row {
            cursor: pointer;
            transition: background .1s;
        }

        .hx-row:hover td {
            background: #f0fdfa;
        }

        .dark .hx-row:hover td {
            background: rgba(13, 148, 136, .06);
        }

        .hx-badge {
            display: inline-block;
            padding: 2px 9px;
            border-radius: 9999px;
            font-size: .68rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .hx-badge-opd {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .hx-badge-er {
            background: #fee2e2;
            color: #991b1b;
        }

        .hx-badge-admitted {
            background: #d1fae5;
            color: #065f46;
        }

        .hx-badge-discharged {
            background: #f3f4f6;
            color: #374151;
        }

        .hx-badge-referred {
            background: #fef3c7;
            color: #92400e;
        }

        .hx-badge-assessed {
            background: #e0f2fe;
            color: #0c4a6e;
        }

        .hx-badge-registered {
            background: #fef9c3;
            color: #854d0e;
        }

        .hx-date {
            font-family: monospace;
            font-size: .78rem;
            color: #374151;
            font-weight: 600;
        }

        .dark .hx-date {
            color: #d1d5db;
        }

        .hx-ago {
            font-size: .68rem;
            color: #9ca3af;
        }

        .hx-cc {
            font-size: .82rem;
            color: #374151;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .hx-cc {
            color: #e5e7eb;
        }

        .hx-dx {
            font-size: .78rem;
            color: #6b7280;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hx-arrow {
            font-size: 1rem;
            color: #9ca3af;
        }

        .hx-row:hover .hx-arrow {
            color: #1d4ed8;
        }

        .hx-banner {
            border-radius: 10px;
            padding: 16px 22px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }

        .hx-banner-opd {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
        }

        .hx-banner-er {
            background: linear-gradient(135deg, #7f1d1d, #dc2626);
        }

        .hx-banner-other {
            background: linear-gradient(135deg, #064e3b, #059669);
        }

        .hx-ban-title {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
        }

        .hx-ban-case {
            font-family: monospace;
            font-size: .75rem;
            color: rgba(255, 255, 255, .75);
            margin-top: 1px;
        }

        .hx-ban-pill {
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .28);
            border-radius: 6px;
            padding: 4px 12px;
            font-size: .75rem;
            color: #fff;
            font-weight: 600;
        }

        .hx-section-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #6b7280;
            margin: 20px 0 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hx-section-line {
            flex: 1;
            border-top: 1px solid #e5e7eb;
        }

        .dark .hx-section-line {
            border-top-color: #374151;
        }

        .hx-iframe-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            margin-bottom: 6px;
        }

        .hx-iframe-wrap iframe {
            display: block;
            width: 100%;
            border: none;
        }

        .btn-hx-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .82rem;
            color: #6b7280;
            background: none;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 16px;
            padding: 7px 14px;
        }

        .btn-hx-back:hover {
            border-color: #1d4ed8;
            color: #1d4ed8;
        }

        .hx-file-link-lab {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #1d4ed8;
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            padding: 5px 12px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            margin: 3px;
        }

        .hx-file-link-lab:hover {
            background: #dbeafe;
        }

        .hx-file-link-rad {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #6d28d9;
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            padding: 5px 12px;
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 6px;
            margin: 3px;
        }

        .hx-file-link-rad:hover {
            background: #ede9fe;
        }

        /* Ballard Score Card */
        .ballard-result-card {
            transition: box-shadow 0.2s;
        }

        .ballard-result-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .dark .ballard-result-card {
            background: #1f2937;
            border-color: #374151;
        }

        .dark .ballard-result-header {
            border-bottom-color: #374151;
        }
    </style>

    @if($visit && $visit->patient)
    @php
    $patient = $visit->patient;
    $history = $visit->medicalHistory;
    $allOrders = $visit->doctorsOrders ?? collect();
    $allVitals = $visit->vitals ?? collect();
    $pendingCnt = $allOrders->where('status', 'pending')->count();
    $service = $visit->admitted_service ?? $history?->service ?? '—';
    $labResults = $this->labResults;
    $radResults = $this->radResults;
    $totalResults = $labResults->count() + $radResults->count();
    $pastCount = $this->pastVisitsCount;

    // Which forms exist for this visit
    $hasErRecord = (bool) $visit->erRecord;
    $hasAdmRecord = (bool) $visit->admissionRecord;
    $hasConsent = (bool) $visit->consentRecord;
    $hasHistory = (bool) $history;
    $isErVisit = $visit->visit_type === 'ER';
    @endphp

    <div class="chart-page">
{{-- ════ HEADER ════════════════════════════════════════════════ --}}
        <div class="chart-header">
            <div class="chart-hdr-hero">
                <div class="chart-hdr-top-row">
                    <div>
                        @php
                        $isTemporary = (bool) ($patient->is_provisional ?? false) || (bool) ($visit?->isProvisionalNicu() ?? false);
                        if ($isTemporary) {
                            [$patientNameMain, $patientNameMeta] = array_pad(explode(' - ', $patient->full_name ?? '', 2), 2, null);
                            $patientNameMain = trim((string) $patientNameMain);
                            $patientNameMeta = $patientNameMeta !== null ? trim((string) $patientNameMeta) : null;
                        } else {
                            $patientNameMain = $patient->full_name ?? '';
                            $patientNameMeta = null;
                        }
                        @endphp
                        <p class="pt-name-big">{{ $patientNameMain }}</p>
                        @if($isTemporary && $patientNameMeta)
                        <p style="font-size:.78rem;font-weight:700;color:rgba(255,255,255,.8);margin-top:4px;text-transform:uppercase;letter-spacing:.04em;">{{ $patientNameMeta }}</p>
                        @endif
                        <p class="pt-case-big">
                            <span style="font-family:monospace;opacity:.8;letter-spacing:.02em;">{{ $patient->case_no }}</span>
                            <span class="svc-pill">{{ $service }}</span>
                            @if($this->isReadonly)
                            <span class="readonly-badge-hero">Discharged — Read Only</span>
                            @endif
                        </p>
                    </div>
                    <div class="chart-header-actions">
                        @if ($visit->status === 'admitted' && !$this->isReadonly)
                        <a href="{{ \App\Filament\Doctor\Pages\DischargeSummaryPage::getUrl(['visitId' => $visit->id]) }}"
                            class="btn-back-hdr"
                            style="background:rgba(5,150,105,.25);border-color:rgba(5,150,105,.45);">
                            <x-heroicon-o-clipboard-document-list class="w-3.5 h-3.5" />
                            Discharge Patient
                        </a>
                        @elseif($visit->status === 'discharged' && $visit->dischargeSummary)
                        <a href="{{ \App\Filament\Doctor\Pages\DischargeSummaryPage::getUrl(['visitId' => $visit->id]) }}"
                            class="btn-back-hdr">
                            <x-heroicon-o-clipboard-document-list class="w-3.5 h-3.5" />
                            View Summary
                        </a>
                        @endif
                        <a href="{{ \App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index') }}" class="btn-back-hdr">
                            <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
                            Patient List
                        </a>
                    </div>
                </div>
            </div>

            {{-- Floating info strip --}}
            <div class="chart-hdr-strip">
                <div class="hdr-info-cell">
                    <p class="pl">Age / Sex</p>
                    <p class="pv">{{ $patient->age_display }} · {{ $patient->sex }}</p>
                </div>
                <div class="hdr-info-cell" style="flex:2;min-width:180px;">
                    <p class="pl">Admitting Diagnosis</p>
                    <p class="pv" style="font-size:.82rem;font-weight:600;color:#374151;">{{ $visit->admitting_diagnosis ?? $history?->diagnosis ?? '—' }}</p>
                </div>
                <div class="hdr-info-cell">
                    <p class="pl">Admitted</p>
                    <p class="pv" style="font-family:monospace;font-size:.8rem;">
                        @if($visit->clerk_admitted_at)
                        {{ $visit->clerk_admitted_at->timezone('Asia/Manila')->format('M j, Y H:i') }}
                        @elseif($visit->doctor_admitted_at)
                        {{ $visit->doctor_admitted_at->timezone('Asia/Manila')->format('M j, Y H:i') }}
                        <span style="display:inline-flex;align-items:center;gap:3px;font-size:.6rem;background:#fef3c7;color:#92400e;padding:1px 6px;border-radius:9999px;font-weight:700;margin-top:3px;">Pending Clerk</span>
                        @else
                        —
                        @endif
                    </p>
                </div>
                @if($history?->doctor)
                <div class="hdr-info-cell">
                    <p class="pl">Physician</p>
                    <p class="pv" style="font-size:.8rem;">{{ Str::startsWith($history->doctor->name, ['Dr.','Dr ']) ? $history->doctor->name : 'Dr. '.$history->doctor->name }}</p>
                </div>
                @endif
                @if($visit->visit_type === 'NICU')
                <div class="hdr-info-cell">
                    <p class="pl">Type</p>
                    <p class="pv"><span class="nicu-badge">NICU</span></p>
                </div>
                @endif
            </div>
        </div>

        {{-- ════ TABS ═══════════════════════════════════════════════════ --}}
        <div class="chart-tabs-wrap">
        <div class="chart-tabs">
            <button wire:click="setTab('profile')" type="button"
                    class="chart-tab {{ $activeTab==='profile' ? 'active':'' }}">
                <x-heroicon-o-document-text class="w-4 h-4" />
                Patient Forms
            </button>
            <button wire:click="setTab('orders')" type="button"
                    class="chart-tab {{ $activeTab==='orders' ? 'active':'' }}">
                <x-heroicon-o-clipboard-document-list class="w-4 h-4" />
                Doctor's Orders
                @if($pendingCnt > 0)<span class="tab-badge">{{ $pendingCnt }}</span>@endif
            </button>
            <button wire:click="setTab('history')" type="button"
                    class="chart-tab {{ $activeTab==='history' ? 'active':'' }}">
                <x-heroicon-o-clock class="w-4 h-4" />
                Visit History
                @if($pastCount > 0)<span class="tab-badge tab-badge-blue">{{ $pastCount }}</span>@endif
            </button>
            <button wire:click="setTab('results')" type="button"
                    class="chart-tab {{ $activeTab==='results' ? 'active':'' }}">
                <x-heroicon-o-beaker class="w-4 h-4" />
                Lab / Radiology
                @if($totalResults > 0)<span class="tab-badge tab-badge-green">{{ $totalResults }}</span>@endif
            </button>
            @if($this->isNicu)
            <button wire:click="setTab('ballard')" type="button"
                    class="chart-tab {{ $activeTab==='ballard' ? 'active':'' }}">
                <x-heroicon-o-document-chart-bar class="w-4 h-4" />
                Ballard Score
                @if($this->hasBallardScore)<span class="tab-badge tab-badge-green">✓</span>@endif
            </button>
            @endif
        </div>
        </div>{{-- /chart-tabs-wrap --}}

        <div class="chart-content">

            {{-- ══ PATIENT FORMS ═════ --}}
            @if($activeTab === 'profile')

            <div class="sec-head">
                <h2 class="sec-title"><x-heroicon-o-document class="w-5 h-5 inline mr-2" />Patient Forms</h2>
                <span style="font-size:.78rem;color:#6b7280;">All forms for this visit — read-only view</span>
            </div>

            {{-- ── 1. ER Record (ER visits only) ──────────────────────── --}}
            @if($isErVisit)
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-label"><x-heroicon-o-building-library class="w-4 h-4 inline mr-1" /> ER Record (ER-001)</span>
                    <div class="form-section-line"></div>
                    <span class="form-section-badge {{ $hasErRecord ? 'form-section-badge-saved' : 'form-section-badge-missing' }}">
                        {{ $hasErRecord ? 'Saved' : 'Not yet filled' }}
                    </span>
                </div>
                @if($hasErRecord)
                <div class="form-iframe-wrap">
                    <iframe src="{{ route('forms.er-record', ['visit' => $visit->id]) }}?readonly=1"
                        title="ER Record"
                        style="width:100%;min-height:1100px;border:none;display:block;"
                        loading="lazy"></iframe>
                </div>
                @else
                <div class="form-missing-card">
                    <div class="form-missing-icon"><x-heroicon-o-document-text class="w-8 h-8" /></div>
                    <p class="form-missing-text">ER Record has not been filled out by the clerk yet.</p>
                </div>
                @endif
            </div>
            @endif

            {{-- ── 2. Admission & Discharge Record ────────────────────── --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-label"><x-heroicon-o-document-text class="w-4 h-4 inline mr-1" /> Admission &amp; Discharge Record (ADM-001)</span>
                    <div class="form-section-line"></div>
                    <span class="form-section-badge {{ $hasAdmRecord ? 'form-section-badge-saved' : 'form-section-badge-missing' }}">
                        {{ $hasAdmRecord ? 'Saved' : 'Not yet filled' }}
                    </span>
                </div>
                @if($hasAdmRecord)
                <div class="form-iframe-wrap">
                    <iframe src="{{ route('forms.adm-record', ['visit' => $visit->id]) }}?readonly=1"
                        title="Admission & Discharge Record"
                        style="width:100%;min-height:1100px;border:none;display:block;"
                        loading="lazy"></iframe>
                </div>
                @else
                <div class="form-missing-card">
                    <div class="form-missing-icon"><x-heroicon-o-document-text class="w-8 h-8" /></div>
                    <p class="form-missing-text">Admission &amp; Discharge Record has not been filled out by the clerk yet.</p>
                </div>
                @endif
            </div>

            {{-- ── 3. Consent to Care ──────────────────────────────────── --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-label"><x-heroicon-o-document class="w-4 h-4 inline mr-1" /> Consent to Care (NUR-002-1)</span>
                    <div class="form-section-line"></div>
                    <span class="form-section-badge {{ $hasConsent ? 'form-section-badge-saved' : 'form-section-badge-missing' }}">
                        {{ $hasConsent ? 'Saved' : 'Not yet filled' }}
                    </span>
                </div>
                @if($hasConsent)
                <div class="form-iframe-wrap">
                    <iframe src="{{ route('forms.consent-to-care', ['visit' => $visit->id]) }}?readonly=1"
                        title="Consent to Care"
                        style="width:100%;min-height:780px;border:none;display:block;"
                        loading="lazy"></iframe>
                </div>
                @else
                <div class="form-missing-card">
                    <div class="form-missing-icon"><x-heroicon-o-document class="w-8 h-8" /></div>
                    <p class="form-missing-text">Consent to Care has not been filled out by the clerk yet.</p>
                </div>
                @endif
            </div>

            {{-- ── 4. History Form ─────────────────────────────────────── --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-label"><x-heroicon-o-pencil-square class="w-4 h-4 inline mr-1" /> History Form (NUR-006)</span>
                    <div class="form-section-line"></div>
                    <span class="form-section-badge {{ $hasHistory ? 'form-section-badge-saved' : 'form-section-badge-missing' }}">
                        {{ $hasHistory ? 'Filled' : 'Not yet assessed' }}
                    </span>
                </div>
                @if($hasHistory)
                <div class="form-iframe-wrap">
                    <iframe src="{{ route('forms.history-form', ['visit' => $visit->id]) }}"
                        title="History Form"
                        style="width:100%;min-height:1200px;border:none;display:block;"
                        loading="lazy"></iframe>
                </div>
                @else
                <div class="form-missing-card">
                    <div class="form-missing-icon"><x-heroicon-o-pencil-square class="w-8 h-8" /></div>
                    <p class="form-missing-text">History Form will appear here once the patient has been assessed by a doctor.</p>
                </div>
                @endif
            </div>

            {{-- ── 5. Physical Examination Form ───────────────────────── --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-label"><x-heroicon-o-clipboard-document-list class="w-4 h-4 inline mr-1" /> Physical Examination Form (NUR-005)</span>
                    <div class="form-section-line"></div>
                    <span class="form-section-badge {{ $hasHistory ? 'form-section-badge-saved' : 'form-section-badge-missing' }}">
                        {{ $hasHistory ? 'Filled' : 'Not yet assessed' }}
                    </span>
                </div>
                @if($hasHistory)
                <div class="form-iframe-wrap">
                    <iframe src="{{ route('forms.physical-exam-form', ['visit' => $visit->id]) }}"
                        title="Physical Examination Form"
                        style="width:100%;min-height:1200px;border:none;display:block;"
                        loading="lazy"></iframe>
                </div>
                @else
                <div class="form-missing-card">
                    <div class="form-missing-icon"><x-heroicon-o-clipboard-document-list class="w-8 h-8" /></div>
                    <p class="form-missing-text">Physical Examination Form will appear here once the patient has been assessed by a doctor.</p>
                </div>
                @endif
            </div>

            {{-- ── 6. Vital Sign Monitoring Sheet (NUR-014) ───────────── --}}
            @php
            $vsCount = \App\Models\Vital::where('visit_id', $visit->id)->count();
            @endphp
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-label"><x-heroicon-o-chart-bar class="w-4 h-4 inline mr-1" /> Vital Sign Monitoring Sheet (NUR-014)</span>
                    <div class="form-section-line"></div>
                    <span class="form-section-badge"
                        style="{{ $vsCount > 0 ? 'background:#dbeafe;color:#1e40af;' : 'background:#f3f4f6;color:#6b7280;' }}">
                        {{ $vsCount > 0 ? $vsCount . ' entr' . ($vsCount === 1 ? 'y' : 'ies') : 'No entries yet' }}
                    </span>
                </div>
                @if($vsCount > 0)
                <div class="form-iframe-wrap">
                    <iframe src="{{ route('forms.vital-sign-monitoring-sheet', ['visit' => $visit->id]) }}"
                        title="Vital Sign Monitoring Sheet"
                        style="width:100%;min-height:900px;border:none;display:block;"
                        loading="lazy"></iframe>
                </div>
                @else
                <div class="form-missing-card">
                    <div class="form-missing-icon"><x-heroicon-o-chart-bar class="w-8 h-8" /></div>
                    <p class="form-missing-text">No vital signs recorded yet. Vitals are entered by the nurse from the Nurse panel.</p>
                </div>
                @endif
            </div>

            {{-- ── 7. IV / Blood Transfusion Sheet (NUR-012) ──────────── --}}
            @php
            $ivCount = \App\Models\IvFluidEntry::where('visit_id', $visit->id)->count();
            @endphp
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-label"><x-heroicon-o-beaker class="w-4 h-4 inline mr-1" /> IV / Blood Transfusion Sheet (NUR-012)</span>
                    <div class="form-section-line"></div>
                    <span class="form-section-badge"
                        style="{{ $ivCount > 0 ? 'background:#ccfbf1;color:#0f766e;' : 'background:#f3f4f6;color:#6b7280;' }}">
                        {{ $ivCount > 0 ? $ivCount . ' entr' . ($ivCount === 1 ? 'y' : 'ies') : 'No entries yet' }}
                    </span>
                </div>
                @if($ivCount > 0)
                <div class="form-iframe-wrap">
                    <iframe src="{{ route('forms.iv-bt-sheet', ['visit' => $visit->id]) }}"
                        title="IV / Blood Transfusion Sheet"
                        style="width:100%;min-height:900px;border:none;display:block;"
                        loading="lazy"></iframe>
                </div>
                @else
                <div class="form-missing-card">
                    <div class="form-missing-icon"><x-heroicon-o-beaker class="w-8 h-8" /></div>
                    <p class="form-missing-text">No IV / blood transfusion entries yet. These are entered by the nurse from the Nurse panel.</p>
                </div>
                @endif
            </div>

            {{-- ══ VISIT HISTORY ════════════════════════════════════════════ --}}
            @elseif($activeTab === 'history')
            @php $pastVisits = $this->pastVisits; @endphp

            @if($viewingHistoryVisitId)
            @php
            $hv = $this->historyVisit;
            $hvHx = $hv?->medicalHistory;
            $hvVitals = $hv?->vitals ?? collect();
            $hvOrders = $hv?->doctorsOrders ?? collect();
            $hvLab = $this->getHistoryLabResults($viewingHistoryVisitId);
            $hvRad = $this->getHistoryRadResults($viewingHistoryVisitId);
            $hvLabByReq = $hvLab->groupBy('request_id');
            $hvRadByReq = $hvRad->groupBy('request_id');
            $hvTypeClass = match ($hv?->visit_type) { 'ER' => 'hx-banner-er', default => ($hv?->status === 'admitted' ? 'hx-banner-other' : 'hx-banner-opd')};
            $hvDays = ($hv?->clerk_admitted_at && $hv?->discharged_at) ? $hv->clerk_admitted_at->diffInDays($hv->discharged_at) : null;
            @endphp
            <button type="button" wire:click="closeHistoryView" class="btn-hx-back"><x-heroicon-o-chevron-left class="w-4 h-4 inline" /> Back to Visit History</button>
            @if($hv)
            <div class="hx-banner {{ $hvTypeClass }}">
                <div>
                    <p class="hx-ban-title">@if($hv->visit_type === 'ER') <x-heroicon-o-truck class="w-5 h-5 inline mr-1" /> ER Visit @else <x-heroicon-o-document-text class="w-5 h-5 inline mr-1" /> {{ ucfirst($hv->visit_type) }} Visit @endif — {{ $hv->registered_at->timezone('Asia/Manila')->format('F j, Y') }}</p>
                    <p class="hx-ban-case">{{ $hv->registered_at->timezone('Asia/Manila')->format('H:i') }} · {{ $hv->registered_at->diffForHumans() }}@if($hvDays !== null) · {{ $hvDays }} day(s) admitted @endif</p>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:7px;align-items:center;">
                    @if($hvHx?->doctor)<span class="hx-ban-pill">{{ Str::startsWith($hvHx->doctor->name, ['Dr.', 'Dr ']) ? $hvHx->doctor->name : 'Dr. ' . $hvHx->doctor->name }}</span>@endif
                    @if($hv->admitted_service ?? $hvHx?->service)<span class="hx-ban-pill">{{ $hv->admitted_service ?? $hvHx->service }}</span>@endif
                    @if($hv->payment_class)<span class="hx-ban-pill">{{ $hv->payment_class }}</span>@endif
                    <span class="hx-ban-pill" style="background:rgba(255,255,255,.3);">{{ ucfirst(str_replace('_', ' ', $hv->status)) }}</span>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px;">
                @if($hv->chief_complaint)<div style="background:#fff;border:1px solid #e5e7eb;border-radius:7px;padding:12px 16px;">
                    <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:4px;">Chief Complaint</p>
                    <p style="font-size:.88rem;color:#111827;font-weight:600;">{{ $hv->chief_complaint }}</p>
                </div>@endif
                @if($hv->admitting_diagnosis ?? $hvHx?->diagnosis)<div style="background:#fff;border:1px solid #e5e7eb;border-radius:7px;padding:12px 16px;">
                    <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:4px;">Admitting / Final Diagnosis</p>
                    <p style="font-size:.88rem;color:#111827;font-weight:600;">{{ $hv->admitting_diagnosis ?? $hvHx?->diagnosis ?? '—' }}</p>
                </div>@endif
            </div>
            @if($hv->erRecord)<div class="hx-section-label"><span><x-heroicon-o-building-library class="w-4 h-4 inline mr-1" />Emergency Room Record (ER-001)</span>
                <div class="hx-section-line"></div><span style="background:#d1fae5;color:#065f46;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">Saved</span>
            </div>
            <div class="hx-iframe-wrap"><iframe src="{{ $this->getPastVisitErUrl($hv->id) }}" title="ER Record — Past Visit" style="width:100%;min-height:1100px;border:none;display:block;" loading="lazy"></iframe></div>@endif
            @if($hv->admissionRecord)<div class="hx-section-label" style="margin-top:28px;"><span><x-heroicon-o-document-text class="w-4 h-4 inline mr-1" />Admission &amp; Discharge Record (ADM-001)</span>
                <div class="hx-section-line"></div><span style="background:#d1fae5;color:#065f46;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">Saved</span>
            </div>
            <div class="hx-iframe-wrap"><iframe src="{{ $this->getPastVisitAdmUrl($hv->id) }}" title="ADM Record — Past Visit" style="width:100%;min-height:1100px;border:none;display:block;" loading="lazy"></iframe></div>@endif
            @if($hv->consentRecord)<div class="hx-section-label" style="margin-top:28px;"><span><x-heroicon-o-document class="w-4 h-4 inline mr-1" />Consent to Care (NUR-002-1)</span>
                <div class="hx-section-line"></div><span style="background:#d1fae5;color:#065f46;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">Saved</span>
            </div>
            <div class="hx-iframe-wrap"><iframe src="{{ $this->getPastVisitConsentUrl($hv->id) }}" title="Consent — Past Visit" style="width:100%;min-height:780px;border:none;display:block;" loading="lazy"></iframe></div>@endif
            @if($hvVitals->isNotEmpty())<div class="hx-section-label" style="margin-top:28px;"><span><x-heroicon-o-chart-bar class="w-4 h-4 inline mr-1" />Vital Signs ({{ $hvVitals->count() }} recording(s))</span>
                <div class="hx-section-line"></div>
            </div>
            <div class="vitals-wrap">
                <table class="vitals-table">
                    <thead>
                        <tr>
                            <th>Date / Time</th>
                            <th>Nurse</th>
                            <th>BP</th>
                            <th>PR (bpm)</th>
                            <th>RR (/min)</th>
                            <th>Temp (°C)</th>
                            <th>O₂ Sat (%)</th>
                            <th>Pain /10</th>
                            <th>Wt (kg)</th>
                            <th>Ht (cm)</th>
                        </tr>
                    </thead>
                    <tbody>@foreach($hvVitals as $v)<tr>
                            <td style="white-space:nowrap;font-family:monospace;font-size:.76rem;">{{ $v->taken_at->timezone('Asia/Manila')->format('M j, Y H:i') }}</td>
                            <td style="font-size:.78rem;">{{ $v->nurse_name }}</td>
                            <td>{{ $v->blood_pressure ?? '—' }}</td>
                            <td class="{{ ($v->pulse_rate && ($v->pulse_rate < 60 || $v->pulse_rate > 100)) ? 'abnormal' : '' }}">{{ $v->pulse_rate ?? '—' }}</td>
                            <td class="{{ ($v->respiratory_rate && ($v->respiratory_rate < 12 || $v->respiratory_rate > 20)) ? 'abnormal' : '' }}">{{ $v->respiratory_rate ?? '—' }}</td>
                            <td class="{{ ($v->temperature && ($v->temperature < 36.0 || $v->temperature > 37.5)) ? 'abnormal' : '' }}">{{ $v->temperature ?? '—' }}</td>
                            <td class="{{ ($v->o2_saturation && $v->o2_saturation < 95) ? 'abnormal' : '' }}">{{ $v->o2_saturation ?? '—' }}</td>
                            <td class="{{ ($v->pain_scale !== null && (int) $v->pain_scale >= 7) ? 'abnormal' : '' }}">{{ $v->pain_scale ?? '—' }}</td>
                            <td>{{ $v->weight_kg ?? '—' }}</td>
                            <td>{{ $v->height_cm ?? '—' }}</td>
                        </tr>@endforeach</tbody>
                </table>
            </div>@endif
            @if($hvOrders->isNotEmpty())<div class="hx-section-label" style="margin-top:28px;"><span><x-heroicon-o-pencil-square class="w-4 h-4 inline mr-1" />Doctor's Orders ({{ $hvOrders->count() }})</span>
                <div class="hx-section-line"></div>
            </div>@foreach($hvOrders->groupBy(fn($o) => $o->order_date?->timezone('Asia/Manila')->format('Y-m-d H:i')) as $dateKey => $group)<div style="margin-bottom:18px;">
                <div class="order-group-header">
                    <p class="order-group-label">{{ \Carbon\Carbon::parse($dateKey)->timezone('Asia/Manila')->format('F j, Y H:i') }}</p>
                    <div class="order-group-line"></div>@if($group->first()->doctor)<p class="order-group-doc">{{ Str::startsWith($group->first()->doctor->name, ['Dr.', 'Dr ']) ? $group->first()->doctor->name : 'Dr. ' . $group->first()->doctor->name }}</p>@endif
                </div>@foreach($group as $i => $order)<div class="order-item">
                    <div>
                        <p class="order-num">{{ $i + 1 }}.</p>
                        <p class="order-text {{ $order->isDiscontinued() ? 'order-text-discontinued' : '' }}"> aufidhsfdhg{{ $order->order_text }}</p>
                        <p class="order-meta">{{ $order->order_date?->timezone('Asia/Manila')->format('M j, Y H:i') }}</p>
                    </div><span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>@endforeach
            </div>@endforeach@endif
            @if($hvLabByReq->isNotEmpty() || $hvRadByReq->isNotEmpty())<div class="hx-section-label" style="margin-top:28px;"><span><x-heroicon-o-beaker class="w-4 h-4 inline mr-1" />Lab &amp; Radiology Results</span>
                <div class="hx-section-line"></div><span style="background:#d1fae5;color:#065f46;font-size:.65rem;font-weight:700;padding:1px 8px;border-radius:9999px;">{{ $hvLabByReq->count() + $hvRadByReq->count() }} request(s) completed</span>
            </div>@foreach($hvLabByReq as $reqId => $uploads)@php $lReq = \App\Models\LabRequest::find($reqId); @endphp<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:12px 16px;margin-bottom:8px;border-left:3px solid #059669;">
                <p style="font-family:monospace;font-size:.75rem;color:#059669;font-weight:700;margin-bottom:3px;"><x-heroicon-o-beaker class="w-3 h-3 inline mr-1" />{{ $lReq?->request_no ?? 'Lab' }}</p>@if($lReq?->clinical_diagnosis)<p style="font-size:.8rem;color:#374151;margin-bottom:6px;">{{ $lReq->clinical_diagnosis }}</p>@endif<div style="display:flex;flex-wrap:wrap;">@foreach($uploads as $u)<a href="{{ $u->file_url }}" target="_blank" class="hx-file-link-lab"><x-dynamic-component :component="$u->file_type_icon" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;" /> {{ $u->file_name }}</a>@endforeach</div>
            </div>@endforeach@foreach($hvRadByReq as $reqId => $uploads)@php $rReq = $uploads->first()?->radRequest; @endphp<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:12px 16px;margin-bottom:8px;border-left:3px solid #6d28d9;">
                <p style="font-family:monospace;font-size:.75rem;color:#6d28d9;font-weight:700;margin-bottom:3px;"><x-heroicon-o-cube class="w-3 h-3 inline mr-1" />{{ $rReq?->request_no ?? 'Radiology' }}</p>@if($rReq?->examination_desired)<p style="font-size:.8rem;color:#374151;margin-bottom:6px;">{{ $rReq->examination_desired }}</p>@endif<div style="display:flex;flex-wrap:wrap;">@foreach($uploads as $u)<a href="{{ $u->file_url }}" target="_blank" class="hx-file-link-rad"><x-dynamic-component :component="$u->file_type_icon" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;" /> {{ $u->file_name }}</a>@endforeach</div>@if($rReq?->radiologist_interpretation)<div style="margin-top:8px;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:6px;padding:8px 12px;">
                    <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#6d28d9;margin-bottom:4px;">Radiologist Interpretation</p>
                    <p style="font-size:.85rem;color:#374151;line-height:1.6;white-space:pre-wrap;">{{ $rReq->radiologist_interpretation }}</p>
                </div>@endif
            </div>@endforeach@endif
            @if(!$hv->erRecord && !$hv->admissionRecord && !$hv->consentRecord && $hvVitals->isEmpty() && $hvOrders->isEmpty() && $hvLabByReq->isEmpty() && $hvRadByReq->isEmpty())<div class="placeholder-card">
                <div class="ph-icon"><x-heroicon-o-folder class="w-12 h-12 inline" /></div>
                <p class="ph-title">No detailed records for this visit</p>
                <p class="ph-sub">This visit may have been registered but not completed before the system was updated.</p>
            </div>@endif
            @endif {{-- $hv --}}

            @else
            <div class="sec-head">
                <h2 class="sec-title">Visit History</h2><span style="font-size:.78rem;color:#6b7280;">{{ $pastVisits->count() }} previous visit(s)</span>
            </div>
            @if($pastVisits->isEmpty())
            <div class="placeholder-card">
                <div class="ph-icon"><x-heroicon-o-folder-open class="w-12 h-12 inline" /></div>
                <p class="ph-title">No previous visits on record</p>
                <p class="ph-sub">This is the patient's first visit to La Union Medical Center.</p>
            </div>
            @else
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:7px;padding:10px 16px;margin-bottom:14px;font-size:.82rem;color:#1e40af;"><x-heroicon-o-information-circle class="w-4 h-4 inline mr-2" /> Click any row to view the full medical chart for that visit — including all forms, vitals, orders, and lab results.</div>
            <div class="hx-table-wrap" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="hx-table" style="min-width: 900px;">
                    <thead>
                        <tr>
                            <th class="col-date">Date</th>
                            <th class="col-type">Type</th>
                            <th class="col-cc">Chief Complaint</th>
                            <th class="col-dx">Admitting / Final Dx</th>
                            <th class="col-dr">Physician</th>
                            <th class="col-status">Outcome</th>
                            <th class="col-days">Days</th>
                            <th class="col-rec">Records</th>
                            <th class="col-arrow"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pastVisits as $pv)
                        @php
                        $pvHx = $pv->medicalHistory;
                        $pvDays = ($pv->clerk_admitted_at && $pv->discharged_at) ? $pv->clerk_admitted_at->diff($pv->discharged_at)->days : null;                        $pvDx = $pv->admitting_diagnosis ?? $pvHx?->diagnosis ?? null;
                        $pvBadge = match ($pv->status) { 'admitted' => 'hx-badge-admitted', 'discharged' => 'hx-badge-discharged', 'referred' => 'hx-badge-referred', 'assessed' => 'hx-badge-assessed', 'registered' => 'hx-badge-registered', default => 'hx-badge-discharged'};
                        @endphp
                        <tr class="hx-row" wire:click="viewHistoryVisit({{ $pv->id }})" wire:key="hxv-{{ $pv->id }}">
                            <td class="col-date">
                                <p class="hx-date">{{ $pv->registered_at->timezone('Asia/Manila')->format('M j, Y') }}</p>
                                <p class="hx-ago">{{ $pv->registered_at->timezone('Asia/Manila')->format('H:i') }} · {{ $pv->registered_at->diffForHumans() }}</p>
                            </td>
                            <td>
                                <span class="hx-badge {{ $pv->visit_type === 'ER' ? 'hx-badge-er' : 'hx-badge-opd' }}">
                                    @if($pv->visit_type === 'ER')
                                    <x-heroicon-o-truck class="w-3 h-3 inline mr-1" />ER
                                    @else
                                    <x-heroicon-o-document-text class="w-3 h-3 inline mr-1" />OPD
                                    @endif
                                </span>
                                @if($pv->admitted_service ?? $pvHx?->service)
                                <p style="font-size:.68rem;color:#9ca3af;margin-top:3px;">{{ $pv->admitted_service ?? $pvHx->service }}</p>
                                @endif
                            </td>
                            <td>
                                <p class="hx-cc">{{ $pv->chief_complaint ?? '—' }}</p>
                            </td>
                            <td>
                                <p class="hx-dx" title="{{ $pvDx }}">{{ $pvDx ? \Str::limit($pvDx, 45) : '—' }}</p>
                            </td>
                            <td>
                                @if($pvHx?->doctor?->name)
                                <p style="font-size:.78rem;color:#374151;font-weight:600;">{{ Str::startsWith($pvHx->doctor->name, ['Dr.', 'Dr ']) ? $pvHx->doctor->name : 'Dr. ' . $pvHx->doctor->name }}</p>
                                @else
                                <p style="font-size:.72rem;color:#9ca3af;">—</p>
                                @endif
                            </td>
                            <td>
                                <span class="hx-badge {{ $pvBadge }}">{{ ucfirst(str_replace('_', ' ', $pv->status)) }}</span>
                                @if($pv->payment_class)
                                <p style="font-size:.68rem;color:#9ca3af;margin-top:3px;">{{ $pv->payment_class }}</p>
                                @endif
                            </td>
                            <td style="text-align:center;white-space:nowrap;width:72px;min-width:60px;">
                                @if($pvDays !== null)
                                    <span style="font-size:.92rem;font-weight:800;color:#374151;">{{ (int)$pvDays }}</span>
                                    <span style="font-size:.64rem;color:#9ca3af;display:block;margin-top:1px;">day{{ (int)$pvDays !== 1 ? 's' : '' }}</span>
                                @else
                                    <span style="color:#d1d5db;font-size:.8rem;">—</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:4px;flex-wrap:wrap;">
                                    @if($pv->erRecord)
                                    <span style="font-size:.62rem;background:#f0fdf4;color:#065f46;padding:1px 5px;border-radius:4px;font-weight:700;">ER</span>
                                    @endif
                                    @if($pv->admissionRecord)
                                    <span style="font-size:.62rem;background:#eff6ff;color:#1d4ed8;padding:1px 5px;border-radius:4px;font-weight:700;">ADM</span>
                                    @endif
                                    @if($pv->consentRecord)
                                    <span style="font-size:.62rem;background:#fffbeb;color:#92400e;padding:1px 5px;border-radius:4px;font-weight:700;">CTC</span>
                                    @endif
                                    @if($pv->vitals->isNotEmpty())
                                    <span style="font-size:.62rem;background:#f3f4f6;color:#374151;padding:1px 5px;border-radius:4px;font-weight:700;">VS</span>
                                    @endif
                                    @if($pv->doctorsOrders->isNotEmpty())
                                    <span style="font-size:.62rem;background:#f5f3ff;color:#5b21b6;padding:1px 5px;border-radius:4px;font-weight:700;">Rx</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="hx-arrow"><x-heroicon-o-chevron-right class="w-4 h-4 inline" /></span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p style="font-size:.72rem;color:#9ca3af;margin-top:8px;">Record tags: <strong>ER</strong> = ER-001, <strong>ADM</strong> = Admission Record, <strong>CTC</strong> = Consent to Care, <strong>VS</strong> = Vital Signs, <strong>Rx</strong> = Doctor's Orders</p>
            @endif
            @endif {{-- viewingHistoryVisitId --}}


            {{-- ══ DOCTOR'S ORDERS ══════════════════════════════════════════ --}}
            @elseif($activeTab === 'orders')

            <div class="sec-head">
                <h2 class="sec-title">Doctor's Orders</h2>
                <div style="display:flex;gap:8px;align-items:center;">
                    <span style="font-size:.78rem;color:#6b7280;">{{ $allOrders->count() }} total · {{ $pendingCnt }} pending</span>
                    <button wire:click="setTab('results')" type="button" class="btn-lab"><x-heroicon-o-beaker class="w-4 h-4 inline mr-1" /> Request Lab / Radiology</button>
                    <button wire:click="toggleWriteOrders" type="button" class="btn-write {{ $writingOrders ? 'is-cancel' : '' }}">
                        @if($writingOrders) <x-heroicon-o-x-mark class="w-4 h-4 inline mr-1" /> Cancel @else <x-heroicon-o-pencil-square class="w-4 h-4 inline mr-1" /> Write Orders @endif
                    </button>
                </div>
            </div>

            @if($writingOrders)
            <div class="order-form-wrap">
                <div class="order-form-title">
                    <span><x-heroicon-o-pencil-square class="w-5 h-5 inline mr-2" /> New Doctor's Orders</span>
                    <span style="font-size:.75rem;color:#6b7280;font-weight:400;">
                        {{ now()->timezone('Asia/Manila')->format('F j, Y · H:i') }}
                        &nbsp;·&nbsp; Dr. {{ auth()->user()->name }}
                    </span>
                </div>
                <p class="order-form-subtitle">
                    Type one order per line — press <kbd style="background:#f3f4f6;border:1px solid #e5e7eb;border-radius:3px;padding:1px 5px;font-size:.75rem;">Enter</kbd> to start a new order.
                    Each line will be saved as a separate item the nurse can mark individually.
                </p>
                <div class="quick-chips">
                    @foreach([
                    'IVF D5LR 1L @ 30 gtts/min',
                    'IVF PNSS 1L @ 20 gtts/min',
                    'NPO',
                    'DAT (Diet as Tolerated)',
                    'O2 via nasal cannula @ 2 LPM',
                    'O2 via mask @ 6 LPM',
                    'Refer to Cardiologist',
                    'CBC, Urinalysis',
                    'Chest X-Ray, PA view',
                    'ECG',
                    'Monitor vital signs q4h',
                    'Monitor I&O',
                    'Elevate HOB 30°',
                    'Strict aspiration precautions',
                    'Hook to cardiac monitor',
                    ] as $chip)
                    <button type="button" wire:click="quickInsert('{{ addslashes($chip) }}')" class="quick-chip">+ {{ $chip }}</button>
                    @endforeach
                </div>
                <textarea wire:model="orderText" class="order-textarea"
                    placeholder="Type your orders here — one per line.&#10;&#10;Example:&#10;IVF D5LR 1L @ 30 gtts/min&#10;Paracetamol 500mg IV q6h PRN for fever > 38.5°C&#10;CBC, Urinalysis, BUN, Creatinine STAT&#10;Chest X-Ray, PA view&#10;NPO for now&#10;Monitor vital signs q4h&#10;O2 via nasal cannula @ 2 LPM; titrate to keep SpO2 > 95%"
                    rows="12"></textarea>
                <div class="order-textarea-hint"><span><x-heroicon-o-information-circle class="w-4 h-4 inline" /></span><span>Each line = one order the nurse marks individually.</span></div>
                <div style="display:flex;gap:10px;margin-top:14px;">
                    <button wire:click="saveOrders" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button" class="btn-primary">
                        <span wire:loading.remove wire:target="saveOrders"><x-heroicon-o-document-check class="w-4 h-4 inline mr-1" /> Save Orders</span>
                        <span wire:loading wire:target="saveOrders">Saving…</span>
                    </button>
                    <button wire:click="toggleWriteOrders" type="button" class="btn-secondary">Cancel</button>
                </div>
            </div>
            @endif

            @if($allOrders->isEmpty() && !$writingOrders)
            <div class="placeholder-card">
                <div class="ph-icon"><x-heroicon-o-pencil-square class="w-12 h-12" /></div>
                <p class="ph-title">No orders written yet</p>
                <p class="ph-sub">Click "Write Orders" above to write your first set of orders.</p>
            </div>
            @else
            @foreach($allOrders->groupBy(fn($o) => $o->order_date?->timezone('Asia/Manila')->format('Y-m-d H:i')) as $dateKey => $group)
            <div style="margin-bottom:22px;">
                <div class="order-group-header">
                    <p class="order-group-label">{{ \Carbon\Carbon::parse($dateKey)->timezone('Asia/Manila')->format('F j, Y H:i') }}</p>
                    <div class="order-group-line"></div>
                    @if($group->first()->doctor)<p class="order-group-doc">{{ Str::startsWith($group->first()->doctor->name, ['Dr.', 'Dr ']) ? $group->first()->doctor->name : 'Dr. ' . $group->first()->doctor->name }}</p>@endif
                </div>
                @foreach($group as $i => $order)
                <div class="order-item">
                    <div>
                        <p class="order-num">{{ $i + 1 }}.</p>
                        <p class="order-text {{ $order->isDiscontinued() ? 'order-text-discontinued' : '' }}">{{ $order->order_text }}</p>
                        <p class="order-meta">{{ $order->order_date?->timezone('Asia/Manila')->format('M j, Y H:i') }}@if($order->isCarried() && $order->completed_at) · Carried {{ $order->completed_at->timezone('Asia/Manila')->format('H:i') }} @endif</p>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                        <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                        @if($order->isPending())
                        @if($confirmDiscontinueId === $order->id)
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;margin-top:4px;">
                            <p style="font-size:.68rem;color:#dc2626;font-weight:600;">Discontinue this order?</p>
                            <div style="display:flex;gap:4px;">
                                <button wire:click="discontinueOrder({{ $order->id }})" type="button" style="font-size:.7rem;background:#dc2626;color:#fff;border:none;border-radius:4px;padding:3px 9px;cursor:pointer;">Yes</button>
                                <button wire:click="$set('confirmDiscontinueId', null)" type="button" style="font-size:.7rem;background:#e5e7eb;color:#374151;border:none;border-radius:4px;padding:3px 8px;cursor:pointer;">Cancel</button>
                            </div>
                        </div>
                        @else
                        <button wire:click="$set('confirmDiscontinueId', {{ $order->id }})" type="button" class="btn-discontinue">Discontinue</button>
                        @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
            @endif

            {{-- ══ LAB / RADIOLOGY ═══════════════════════════════════════════ --}}
            @elseif($activeTab === 'results')

            @if($viewingLabRequestId)
            @php
            $vlr = \App\Models\LabRequest::with(['visit.patient', 'doctor', 'results.uploadedBy'])->find($viewingLabRequestId);
            $vlrPatient = $vlr?->visit?->patient ?? $vlr?->patient;
            $vlrUploads = $vlr?->results()->with('uploadedBy')->latest()->get() ?? collect();
            $vlrNotes = $vlrUploads->pluck('notes')->filter()->unique()->values();
            $vlrUploader = $vlrUploads->first()?->uploadedBy?->name ?? '—';
            $vlrSelected = $vlr?->tests ?? [];
            $vc1 = [['style' => 'background:#dbeafe;color:#1e40af', 'dot' => '#3b82f6', 'label' => 'Hematology', 'tests' => ['Complete Blood Count (CBC)', 'Reticulocyte Count', 'Peripheral Blood Smear', 'Malarial Smear', 'Clotting / Bleeding Time', 'Prothrombin Time (PT-PA)', 'APTT', 'ESR']], ['style' => 'background:#f3f4f6;color:#374151', 'dot' => '#6b7280', 'label' => 'Blood Typing', 'tests' => ['Blood Typing', 'Crossmatching']], ['style' => 'background:#ede9fe;color:#5b21b6', 'dot' => '#8b5cf6', 'label' => 'Serology', 'tests' => ['Dengue NS1 + IgM/IgG (Combo)', 'Typhidot', 'ASTO — Qualitative', 'ASTO — Semi-Quantitative', 'CRP — Qualitative', 'CRP — Semi-Quantitative', 'Rheumatoid Factor — Qualitative', 'HBsAg — Rapid', 'HBsAg — EIA', 'Anti-HCV — Rapid', 'VDRL/RPR — Rapid', 'Referral HIV (HACT)']]];
            $vc2 = [['style' => 'background:#dcfce7;color:#166534', 'dot' => '#22c55e', 'label' => 'Clinical Chemistry', 'tests' => ['Fasting Blood Sugar', 'Random Blood Sugar', 'OGTT', '2-hr Post-prandial BG', 'HbA1c', 'Uric Acid', 'Amylase', 'LDH']], ['style' => 'background:#fee2e2;color:#991b1b', 'dot' => '#ef4444', 'label' => 'Lipid Profile', 'tests' => ['Total Cholesterol', 'Total, HDL & LDL Cholesterol', 'Triglycerides', 'Complete Lipid Profile']], ['style' => 'background:#fce7f3;color:#9d174d', 'dot' => '#ec4899', 'label' => 'Serum Electrolytes', 'tests' => ['Sodium, Potassium, Chloride', 'Phosphorus', 'Magnesium', 'Calcium — Total', 'Calcium — Ionized']], ['style' => 'background:#e0f2fe;color:#0c4a6e', 'dot' => '#0ea5e9', 'label' => 'Renal Profile', 'tests' => ['BUN', 'Creatinine', 'Creatinine Clearance', 'Sodium, Potassium, Chloride', 'Total Protein', 'Albumin']], ['style' => 'background:#d1fae5;color:#065f46', 'dot' => '#10b981', 'label' => 'HBT Profile', 'tests' => ['AST / SGOT', 'ALT / SGPT', 'Alkaline Phosphatase', 'Total Protein', 'Albumin', 'Total Bilirubin', 'Total, Direct & Indirect Bili.', 'PT-PA', 'Troponin-T']]];
            $vc3 = [['id' => 'micro', 'style' => 'background:#fef9c3;color:#854d0e', 'dot' => '#eab308', 'label' => 'Clinical Microscopy', 'tests' => ['Routine Urinalysis', 'Urine Ketones', 'Pregnancy Test — Urine', 'Pregnancy Test — Serum', 'Seminal Fluid Analysis', 'Body Fluid Analysis', 'Cell Count / Differential', 'Routine Fecalysis', 'Fecalysis with Concentration', 'Fecal Occult Blood']], ['id' => 'mbio', 'style' => 'background:#ffedd5;color:#9a3412', 'dot' => '#f97316', 'label' => 'Microbiology', 'tests' => ['Gram Stain', 'Acid Fast Stain (AFB)', 'India Ink Stain', 'KOH Preparation', 'Culture and Sensitivity']]];
            @endphp
            <button type="button" wire:click="closeResultView" class="rv-back-btn"><x-heroicon-o-chevron-left class="w-4 h-4 inline" /> Back to Results</button>
            <div class="rv-header-lab">
                <div>
                    <p class="rv-req-no">{{ $vlr?->request_no }}</p>
                    <p class="rv-patient">{{ $vlrPatient?->full_name ?? '—' }}</p>
                    <p class="rv-case">{{ $vlrPatient?->case_no ?? '' }} · {{ $vlrPatient?->age_display ?? '' }} · {{ $vlrPatient?->sex ?? '' }}</p>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;"><span class="rv-pill"><x-heroicon-o-check-circle class="w-4 h-4" /> Completed</span>@if($vlr?->request_type === 'stat')<span style="background:#dc2626;color:#fff;padding:3px 10px;border-radius:9999px;font-size:.72rem;font-weight:800;"><x-heroicon-o-bolt class="w-4 h-4" /> STAT</span>@endif</div>
            </div>
            <div class="rv-paper">
                <div class="rv-hdr">@if(file_exists(public_path('images/lumc-logo.png'))) <div class="rv-logo"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC"></div> @else <div class="rv-logo">LUMC<br>Logo</div> @endif<div class="rv-center">
                        <div class="rv-h-name">La Union Medical Center</div>
                        <div class="rv-h-sub">Clinical Laboratory Request Form</div>
                        <div class="rv-h-ref">LAB-001-1 Rev. 1 &nbsp;·&nbsp; Brgy. Nazareno, Agoo, La Union &nbsp;·&nbsp; (072) 607-5541 loc. 117/118</div>
                    </div>@if(file_exists(public_path('images/province-logo.png'))) <div class="rv-logo"><img src="{{ asset('images/province-logo.png') }}" alt="Province"></div> @else <div class="rv-logo">Province<br>Seal</div> @endif</div>
                <div class="rv-g4">
                    <div class="rv-fg"><span class="rv-fl">Date of Request</span><span class="rv-val">{{ $vlr?->date_requested?->format('Y-m-d') ?? $vlr?->created_at->format('Y-m-d') }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">Hospital No.</span><span class="rv-val">{{ $vlrPatient?->case_no ?? '—' }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">Receipt No.</span><span class="rv-val" style="font-family:monospace;font-weight:bold;">{{ $vlr?->request_no }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">Ward / Service</span><span class="rv-val">{{ $vlr?->ward ?? '—' }}</span></div>
                </div>
                <div class="rv-g4">
                    <div class="rv-fg"><span class="rv-fl">Surname</span><span class="rv-val">{{ strtoupper($vlrPatient?->family_name ?? '—') }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">First Name</span><span class="rv-val">{{ strtoupper($vlrPatient?->first_name ?? '—') }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">Middle Name</span><span class="rv-val">{{ strtoupper($vlrPatient?->middle_name ?? '—') }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">Address</span><span class="rv-val">{{ $vlrPatient?->address ?? '—' }}</span></div>
                </div>
                <div class="rv-g6">
                    <div class="rv-fg"><span class="rv-fl">Birth Date</span><span class="rv-val">{{ $vlrPatient?->birthday?->format('Y-m-d') ?? '—' }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">Age</span><span class="rv-val">{{ $vlrPatient?->age_display ?? $vlrPatient?->current_age ?? '—' }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">Sex</span><span class="rv-val">{{ $vlrPatient?->sex ?? '—' }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">Civil Status</span><span class="rv-val">—</span></div>
                    <div class="rv-fg"><span class="rv-fl">Request Type</span><span class="rv-val" style="font-weight:bold;color:{{ $vlr?->request_type === 'stat' ? '#dc2626' : '#000' }};">{{ strtoupper($vlr?->request_type ?? 'ROUTINE') }}</span></div>
                    <div class="rv-fg"><span class="rv-fl">Clinical Diagnosis</span><span class="rv-val">{{ $vlr?->clinical_diagnosis ?? '—' }}</span></div>
                </div>
                <hr class="rv-divider">
                <div class="rv-phys">
                    <div class="rv-phys-label">Requesting Physician</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div><span class="rv-fl">Name</span>
                            <div class="rv-phys-name">{{ $vlr?->requesting_physician ?? ($vlr?->doctor ? (Str::startsWith($vlr->doctor->name, ['Dr.', 'Dr ']) ? $vlr->doctor->name : 'Dr. ' . $vlr->doctor->name) : '—') }}</div>
                        </div>
                        <div style="display:flex;flex-direction:column;justify-content:flex-end;">
                            <div style="border-bottom:1px solid #000;height:28px;"></div>
                            <div class="rv-sig-cap">Signature / PRC No. &amp; Date</div>
                        </div>
                    </div>
                </div>
                <div class="rv-tests-grid">
                    <div style="display:flex;flex-direction:column;gap:5px;">@foreach($vc1 as $sec)<div class="rv-test-sec">
                            <div class="rv-test-head" style="{{ $sec['style'] }};"><span class="rv-test-dot" style="background:{{ $sec['dot'] }};"></span>{{ $sec['label'] }}</div>@foreach($sec['tests'] as $t)<div class="rv-test-item {{ in_array($t, $vlrSelected) ? 'checked' : '' }}">
                                <div class="rv-cb"></div><span class="rv-test-name">{{ $t }}</span>
                            </div>@endforeach
                        </div>@endforeach</div>
                    <div style="display:flex;flex-direction:column;gap:5px;">@foreach($vc2 as $sec)<div class="rv-test-sec">
                            <div class="rv-test-head" style="{{ $sec['style'] }};"><span class="rv-test-dot" style="background:{{ $sec['dot'] }};"></span>{{ $sec['label'] }}</div>@foreach($sec['tests'] as $t)<div class="rv-test-item {{ in_array($t, $vlrSelected) ? 'checked' : '' }}">
                                <div class="rv-cb"></div><span class="rv-test-name">{{ $t }}</span>
                            </div>@endforeach
                        </div>@endforeach</div>
                    <div style="display:flex;flex-direction:column;gap:5px;">@foreach($vc3 as $sec)<div class="rv-test-sec">
                            <div class="rv-test-head" style="{{ $sec['style'] }};"><span class="rv-test-dot" style="background:{{ $sec['dot'] }};"></span>{{ $sec['label'] }}</div>@foreach($sec['tests'] as $t)<div class="rv-test-item {{ in_array($t, $vlrSelected) ? 'checked' : '' }}">
                                <div class="rv-cb"></div><span class="rv-test-name">{{ $t }}</span>
                            </div>@endforeach@if(($sec['id'] ?? '') === 'mbio')<div style="padding:4px 7px;border-top:1px solid #e5e7eb;"><span style="font-size:7pt;color:#888;text-transform:uppercase;letter-spacing:.04em;">Specimen</span>
                                <div class="rv-micro-val">{{ $vlr?->specimen ?? '' }}</div>
                            </div>
                            <div style="padding:4px 7px;border-top:1px solid #e5e7eb;"><span style="font-size:7pt;color:#888;text-transform:uppercase;letter-spacing:.04em;">Antibiotics / Duration</span>
                                <div class="rv-micro-val">{{ $vlr?->antibiotics_taken ?? '' }}</div>
                            </div>@endif
                        </div>@endforeach@if($vlr?->other_tests)<div class="rv-test-sec">
                            <div class="rv-test-head" style="background:#f3f4f6;color:#374151;"><span class="rv-test-dot" style="background:#9ca3af;"></span>Others</div>
                            <div style="padding:5px 7px;font-size:8pt;color:#374151;">{{ $vlr->other_tests }}</div>
                        </div>@endif</div>
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
                    <div>
                        <div class="rv-sig-line"></div>
                        <div class="rv-sig-cap">Requesting Physician — Signature / PRC No.</div>
                    </div>
                    <div>
                        <div class="rv-sig-line"></div>
                        <div class="rv-sig-cap">Verified by (Lab Staff) / Date</div>
                    </div>
                </div>
            </div>
            <div class="rv-result-box">
                <p class="rv-result-title"><x-heroicon-o-check-circle class="w-4 h-4" /> {{ $vlrUploads->count() }} Result File(s) — {{ $vlr?->request_no }}</p>
                <p style="font-size:.82rem;color:#f97316;font-weight:600;margin:-4px 0 12px 2px;">Uploaded by {{ $vlrUploader }}</p>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:{{ $vlrNotes->isNotEmpty() ? '4px' : '0' }};">@foreach($vlrUploads as $u)<div><a href="{{ $u->file_url }}" target="_blank" class="rv-file-link-lab"><x-dynamic-component :component="$u->file_type_icon" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;" /> {{ $u->file_name }} <span style="font-size:.7rem;font-weight:400;color:#6b7280;">({{ $u->file_size_human }})</span></a></div>@endforeach</div>@if($vlrNotes->isNotEmpty())<div class="rv-notes-box">
                    <p class="rv-notes-label"><x-heroicon-o-pencil-square class="w-4 h-4 inline mr-1" /> Tech Notes</p>@foreach($vlrNotes as $note)<p style="font-size:.875rem;color:#374151;line-height:1.6;">{{ $note }}</p>@endforeach
                </div>@endif
            </div>

            @elseif($viewingRadRequestId)
            @php
            $vrr = \App\Models\RadiologyRequest::with(['visit.patient', 'doctor', 'results.uploadedBy'])->find($viewingRadRequestId);
            $vrrPatient = $vrr?->visit?->patient ?? $vrr?->patient;
            $vrrUploads = $vrr?->results()->with('uploadedBy')->latest()->get() ?? collect();
            $vrrUploader = $vrrUploads->first()?->uploadedBy?->name ?? '—';
            $vrrInterp = $vrr?->radiologist_interpretation ?? $vrrUploads->firstWhere('interpretation', '!=', null)?->interpretation ?? null;
            $vrrSrc = strtoupper($vrr?->source ?? '');
            @endphp
            <button type="button" wire:click="closeResultView" class="rv-back-btn"><x-heroicon-o-chevron-left class="w-4 h-4 inline" /> Back to Results</button>
            <div class="rv-header-rad">
                <div>
                    <p class="rv-req-no">{{ $vrr?->request_no }}</p>
                    <p class="rv-patient">{{ $vrrPatient?->full_name ?? '—' }}</p>
                    <p class="rv-case rv-case-rad">{{ $vrrPatient?->case_no ?? '' }} · {{ $vrrPatient?->age_display ?? '' }} · {{ $vrrPatient?->sex ?? '' }}</p>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">@if($vrr?->modality)<span class="rv-modality">{{ $vrr->modality }}</span>@endif<span class="rv-pill"><x-heroicon-o-check-circle class="w-4 h-4" /> Completed</span></div>
            </div>
            <div class="rv-paper rv-paper-rad">
                <div class="rv-hdr rv-hdr-rad">@if(file_exists(public_path('images/lumc-logo.png'))) <div class="rv-logo rv-logo-rad"><img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC"></div> @else <div class="rv-logo rv-logo-rad">LUMC<br>Logo</div> @endif<div class="rv-center">
                        <div class="rv-h-name rv-h-name-rad">La Union Medical Center</div>
                        <div class="rv-h-sub rv-h-sub-rad">Radiology Request Form</div>
                        <div class="rv-h-addr">Brgy. Nazareno, Agoo, La Union &nbsp;·&nbsp; (072) 607-5541</div>
                    </div>@if(file_exists(public_path('images/province-logo.png'))) <div class="rv-logo rv-logo-rad"><img src="{{ asset('images/province-logo.png') }}" alt="Province"></div> @else <div class="rv-logo rv-logo-rad">Province<br>Seal</div> @endif</div>
                <hr class="rv-divider-thick" style="margin-top:10px;">
                <div class="rv-modality-row">@foreach(['X-RAY', 'ULTRASOUND', 'CT SCAN'] as $mod)<div class="rv-modality-opt">
                        <div class="rv-radio {{ $vrr?->modality === $mod ? 'on' : '' }}"></div>&nbsp;{{ $mod }}
                    </div>@endforeach</div>
                <div class="rv-g4" style="margin-bottom:7px;">
                    <div class="rv-fg"><span class="rv-fl rv-fl-rad">Date</span><span class="rv-val rv-val-rad">{{ $vrr?->date_requested?->format('Y-m-d') ?? $vrr?->created_at->format('Y-m-d') }}</span></div>
                    <div class="rv-fg"><span class="rv-fl rv-fl-rad">RAD File No.</span><span class="rv-val rv-val-rad" style="font-family:monospace;font-weight:bold;">{{ $vrr?->request_no }}</span></div>
                    <div class="rv-fg"><span class="rv-fl rv-fl-rad">Hospital No.</span><span class="rv-val rv-val-rad">{{ $vrrPatient?->case_no ?? '—' }}</span></div>
                    <div class="rv-fg"><span class="rv-fl rv-fl-rad">Service / Ward</span><span class="rv-val rv-val-rad">{{ $vrr?->ward ?? '—' }}</span></div>
                </div>
                <div class="rv-source-row">@foreach(['OPD', 'ER', 'PRIVATE', 'PHIC', 'CHARITY / INDIGENT'] as $s)@php $match = $vrrSrc === strtoupper($s) || ($vrrSrc === 'CHARITY' && str_contains($s, 'CHARITY')); @endphp<div class="rv-source-opt">
                        <div class="rv-checkbox {{ $match ? 'on' : '' }}"></div>&nbsp;{{ $s }}
                    </div>@endforeach</div>
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
                <div style="margin-bottom:7px;"><span class="rv-fl rv-fl-rad">Examination Desired</span>
                    <div class="rv-area-val">{{ $vrr?->examination_desired ?? '—' }}</div>
                </div>
                <div style="margin-bottom:7px;"><span class="rv-fl rv-fl-rad">Clinical Diagnosis</span>
                    <div class="rv-area-val">{{ $vrr?->clinical_diagnosis ?? '—' }}</div>
                </div>
                <div style="margin-bottom:9px;"><span class="rv-fl rv-fl-rad">Pertinent / Brief Clinical Findings</span>
                    <div class="rv-area-val">{{ $vrr?->clinical_findings ?? '—' }}</div>
                </div>
                <hr class="rv-divider">
                <div style="margin-top:10px;margin-bottom:12px;">
                    <div class="rv-sec-label">Requesting Physician</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:end;">
                        <div><span class="rv-fl rv-fl-rad">Name</span>
                            <div class="rv-val rv-val-rad" style="font-size:11pt;font-weight:bold;">{{ $vrr?->requesting_physician ?? ($vrr?->doctor ? (Str::startsWith($vrr->doctor->name, ['Dr.', 'Dr ']) ? $vrr->doctor->name : 'Dr. ' . $vrr->doctor->name) : '—') }}</div>
                        </div>
                        <div>
                            <div class="rv-sig-line"></div>
                            <div class="rv-sig-cap">Signature over Printed Name / PRC No.</div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="rv-sec-label">Radiologist Interpretation / Findings</div>
                    <div class="rv-interp-area">{{ $vrr?->radiologist_interpretation ?? '' }}</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:6px;">
                        <div>
                            <div class="rv-sig-line"></div>
                            <div class="rv-sig-cap">Radiologist — Signature / PRC No.</div>
                        </div>
                        <div>
                            <div class="rv-sig-line"></div>
                            <div class="rv-sig-cap">Date &amp; Time Reported</div>
                        </div>
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
            <div class="rv-result-box">
                <p class="rv-result-title"><x-heroicon-o-check-circle class="w-4 h-4" /> {{ $vrrUploads->count() }} Result File(s) — {{ $vrr?->request_no }}</p>
                <p style="font-size:.82rem;color:#6d28d9;font-weight:600;margin:-4px 0 12px 2px;">Uploaded by {{ $vrrUploader }}</p>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:4px;">@foreach($vrrUploads as $u)<div><a href="{{ $u->file_url }}" target="_blank" class="rv-file-link-rad"><x-dynamic-component :component="$u->file_type_icon" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;" /> {{ $u->file_name }} <span style="font-size:.7rem;font-weight:400;color:#6b7280;">({{ $u->file_size_human }})</span></a></div>@endforeach</div>@if($vrrInterp)<div class="rv-interp-box">
                    <p class="rv-interp-label"><x-heroicon-o-document-text class="w-4 h-4 inline mr-1" />Radiologist Interpretation</p>
                    <div class="rv-interp-text">{{ $vrrInterp }}</div>
                </div>@endif
            </div>

            @else
            <div class="sec-head">
                <h2 class="sec-title">Lab &amp; Radiology</h2><span style="font-size:.78rem;color:#6b7280;">{{ $this->labRequestsCount + $this->radRequestsCount }} request(s) &nbsp;·&nbsp; <span style="color:#059669;font-weight:700;">{{ $totalResults }} result(s) ready</span></span>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:22px;">
                <a href="{{ route('forms.lab-request', ['visit' => $visit->id]) }}" target="_blank" rel="noopener" class="doc-card"><span class="doc-card-icon"><x-heroicon-o-beaker class="w-5 h-5" /></span>
                    <div class="doc-card-body">
                        <p class="doc-card-label doc-card-label-green">LAB-001-1</p>
                        <p class="doc-card-title">New Lab Request</p>
                        <p class="doc-card-meta">CBC · Chemistry · Serology · Microscopy · Microbiology</p>
                    </div><span class="doc-card-arrow">↗</span>
                </a>
                <a href="{{ route('forms.radiology-request', ['visit' => $visit->id]) }}" target="_blank" rel="noopener" class="doc-card"><span class="doc-card-icon"><x-heroicon-o-cube class="w-5 h-5" /></span>
                    <div class="doc-card-body">
                        <p class="doc-card-label doc-card-label-purple">RAD</p>
                        <p class="doc-card-title">New Radiology Request</p>
                        <p class="doc-card-meta">X-Ray · Ultrasound · CT Scan</p>
                    </div><span class="doc-card-arrow">↗</span>
                </a>
            </div>
            @php $labByRequest = $labResults->groupBy('request_id');
            $radByRequest = $radResults->groupBy('request_id'); @endphp
            @if($labByRequest->isNotEmpty())<div class="results-section">
                <div class="results-section-title"><span><x-heroicon-o-beaker class="w-4 h-4 inline mr-1" /> Laboratory Results</span>
                    <div class="results-section-line"></div><span class="results-badge results-badge-lab">{{ $labByRequest->count() }} ready</span>
                </div>@foreach($labByRequest as $reqId => $uploads)@php $lReq = \App\Models\LabRequest::find($reqId);
                $firstUpload = $uploads->first(); @endphp<div class="result-req-card" wire:click="viewLabResult({{ $reqId }})" wire:key="lab-req-{{ $reqId }}">
                    <div class="rrc-top">
                        <div style="flex:1;"><span class="rrc-req-no">{{ $lReq?->request_no ?? 'LAB' }}</span>@if($lReq?->request_type === 'stat')<span style="background:#fee2e2;color:#991b1b;font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:9999px;margin-left:5px;"><x-heroicon-o-bolt class="w-4 h-4" /> STAT</span>@endif<p class="rrc-diag">{{ $lReq?->clinical_diagnosis ?? 'Laboratory Result' }}</p>
                            <p class="rrc-meta">Uploaded by {{ $firstUpload?->uploadedBy?->name ?? 'Tech' }} · {{ $firstUpload?->created_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</p>@if($lReq && $lReq->tests && count($lReq->tests))<div class="rrc-tests" style="margin-top:5px;">@foreach(array_slice($lReq->tests, 0, 5) as $t)<span class="rrc-test-chip">{{ $t }}</span>@endforeach @if(count($lReq->tests) > 5)<span class="rrc-test-chip" style="background:#e5e7eb;color:#6b7280;">+{{ count($lReq->tests) - 5 }} more</span>@endif</div>@endif
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;"><span class="rrc-badge">Lab Result</span><span class="rrc-arrow"><x-heroicon-o-chevron-right class="w-4 h-4 inline" /></span></div>
                    </div>
                    <div class="rrc-files">@foreach($uploads as $u)<span class="rrc-file-chip"><x-dynamic-component :component="$u->file_type_icon" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;" /> {{ $u->file_name }}</span>@endforeach</div>
                </div>@endforeach
            </div>@endif
            @if($radByRequest->isNotEmpty())<div class="results-section">
                <div class="results-section-title"><span><x-heroicon-o-cube class="w-4 h-4 inline mr-1" /> Radiology Results</span>
                    <div class="results-section-line"></div><span class="results-badge results-badge-rad">{{ $radByRequest->count() }} ready</span>
                </div>@foreach($radByRequest as $reqId => $uploads)@php $rReq = $uploads->first()?->radRequest;
                $firstUpload = $uploads->first(); @endphp<div class="result-req-card result-req-card-rad" wire:click="viewRadResult({{ $reqId }})" wire:key="rad-req-{{ $reqId }}">
                    <div class="rrc-top">
                        <div style="flex:1;"><span class="rrc-req-no rrc-req-no-rad">{{ $rReq?->request_no ?? 'RAD' }}</span>@if($rReq?->modality)<span style="background:#f5f3ff;color:#5b21b6;font-size:.68rem;font-weight:700;padding:1px 7px;border-radius:9999px;margin-left:5px;">{{ $rReq->modality }}</span>@endif<p class="rrc-diag">{{ $rReq?->examination_desired ?? 'Radiology Result' }}</p>@if($rReq?->clinical_diagnosis)<p class="rrc-meta">Dx: {{ $rReq->clinical_diagnosis }}</p>@endif<p class="rrc-meta">Uploaded by {{ $firstUpload?->uploadedBy?->name ?? 'Tech' }} · {{ $firstUpload?->created_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</p>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;"><span class="rrc-badge rrc-badge-rad">{{ $rReq?->modality ?? 'Radiology' }}</span><span class="rrc-arrow"><x-heroicon-o-chevron-right class="w-4 h-4 inline" /></span></div>
                    </div>
                    <div class="rrc-files">@foreach($uploads as $u)<span class="rrc-file-chip"><x-dynamic-component :component="$u->file_type_icon" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;" /> {{ $u->file_name }}</span>@endforeach</div>
                </div>@endforeach
            </div>@endif
            @php $pendingLabReqs = \App\Models\LabRequest::where('visit_id', $visit->id)->whereIn('status', ['pending', 'in_progress'])->orderBy('created_at', 'desc')->get();
            $pendingRadReqs = \App\Models\RadiologyRequest::where('visit_id', $visit->id)->whereIn('status', ['pending', 'in_progress'])->orderBy('created_at', 'desc')->get(); @endphp
            @if($pendingLabReqs->isNotEmpty() || $pendingRadReqs->isNotEmpty())<div class="results-section" style="margin-top:4px;">
                <div class="results-section-title"><span><x-heroicon-o-clock class="w-4 h-4 inline mr-1" />Pending / In Progress</span>
                    <div class="results-section-line"></div><span class="results-badge" style="background:#fef3c7;color:#92400e;">{{ $pendingLabReqs->count() + $pendingRadReqs->count() }} waiting</span>
                </div>@foreach($pendingLabReqs as $req)<div class="pending-req-card">
                    <div class="pending-req-info">
                        <p class="pending-req-no">{{ $req->request_no }}</p>
                        <p class="pending-req-diag"><x-heroicon-o-beaker class="w-3 h-3 inline mr-1" /> Lab &nbsp;·&nbsp;@if($req->tests && count($req->tests)){{ implode(', ', array_slice($req->tests, 0, 3)) }}@if(count($req->tests) > 3) <span style="color:#9ca3af;">+{{ count($req->tests) - 3 }}</span>@endif@else—@endif</p>
                        <p style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Requested {{ $req->created_at->timezone('Asia/Manila')->diffForHumans() }}</p>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;"><span class="pending-badge {{ $req->request_type === 'stat' ? 'pending-badge-stat' : '' }}">{{ strtoupper($req->request_type ?? 'ROUTINE') }}</span><span style="font-size:.68rem;background:#f3f4f6;color:#6b7280;padding:2px 7px;border-radius:9999px;font-weight:600;">{{ ucfirst(str_replace('_', ' ', $req->status)) }}</span></div>
                </div>@endforeach@foreach($pendingRadReqs as $req)<div class="pending-req-card">
                    <div class="pending-req-info">
                        <p class="pending-req-no" style="color:#6d28d9;">{{ $req->request_no }}</p>
                        <p class="pending-req-diag"><x-heroicon-o-cube class="w-3 h-3 inline mr-1" /> {{ $req->modality ?? 'Radiology' }} &nbsp;·&nbsp;{{ \Str::limit($req->examination_desired ?? '—', 55) }}</p>
                        <p style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Requested {{ $req->created_at->timezone('Asia/Manila')->diffForHumans() }}</p>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">@if($req->modality)<span style="background:#f5f3ff;color:#5b21b6;font-size:.68rem;font-weight:700;padding:2px 7px;border-radius:9999px;">{{ $req->modality }}</span>@endif<span style="font-size:.68rem;background:#f3f4f6;color:#6b7280;padding:2px 7px;border-radius:9999px;font-weight:600;">{{ ucfirst(str_replace('_', ' ', $req->status)) }}</span></div>
                </div>@endforeach
            </div>@endif
            @if($labResults->isEmpty() && $radResults->isEmpty() && $pendingLabReqs->isEmpty() && $pendingRadReqs->isEmpty())<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:4px;">
                <div class="placeholder-card">
                    <div class="ph-icon"><x-heroicon-o-beaker class="w-8 h-8" /></div>
                    <p class="ph-title">No lab requests yet</p>
                    <p class="ph-sub">Click "New Lab Request" above to submit a request to the lab.</p>
                </div>
                <div class="placeholder-card">
                    <div class="ph-icon"><x-heroicon-o-cube class="w-8 h-8" /></div>
                    <p class="ph-title">No radiology requests yet</p>
                    <p class="ph-sub">Click "New Radiology Request" above to submit an imaging request.</p>
                </div>
            </div>@endif
            @endif {{-- viewing rad --}}


            {{-- ══ BALLARD SCORE ═══════════════════════════════════════════════ --}}
            @elseif($activeTab === 'ballard')
            @php $ballardExams = $this->ballardExams; @endphp

            <div class="sec-head">
                <h2 class="sec-title"><x-heroicon-o-document-chart-bar class="w-5 h-5 inline mr-2" />Ballard Maturity Score (Gestational Age Assessment)</h2>
                @if($ballardExams->where('exam_number', 1)->isEmpty())
                <a href="{{ \App\Filament\Doctor\Pages\BallardScore::getUrl(['visitId' => $visit->id]) }}"
                    target="_blank"
                    class="btn-primary"
                    style="padding: 6px 14px; font-size: 0.75rem;">
                    + Record 1st Exam
                </a>
                @endif
            </div>

            @if($ballardExams->isEmpty())
            <div class="placeholder-card">
                <div class="ph-icon"><x-heroicon-o-document-chart-bar class="w-8 h-8" /></div>
                <p class="ph-title">No Ballard Score recorded yet</p>
                <p class="ph-sub">Gestational age assessment not yet performed.</p>
                <a href="{{ \App\Filament\Doctor\Pages\BallardScore::getUrl(['visitId' => $visit->id]) }}"
                    target="_blank"
                    style="margin-top: 12px; display: inline-block; background: #059669; color: #fff; padding: 8px 20px; border-radius: 6px; text-decoration: none; font-weight: 600;">
                    + Record First Exam
                </a>
            </div>
            @else
            @foreach($ballardExams as $exam)
            <div class="ballard-result-card" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 20px; overflow: hidden;">
                <div class="ballard-result-header" style="background: {{ $exam->exam_number == 1 ? '#f0fdf4' : '#fffbeb' }}; border-bottom: 1px solid #e5e7eb; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-weight: 700; font-size: 0.9rem;">
                            {{ $exam->exam_number == 1 ? '1st Exam' : '2nd Exam' }}
                        </span>
                        <span style="font-size: 0.7rem; color: #6b7280; margin-left: 10px;">
                            {{ $exam->exam_datetime ? \Carbon\Carbon::parse($exam->exam_datetime)->format('M d, Y h:i A') : 'Date not set' }}
                        </span>
                    </div>
                    <div>
                        <a href="{{ \App\Filament\Doctor\Pages\BallardScore::getUrl(['visitId' => $visit->id]) }}"
                            target="_blank"
                            style="font-size: 0.7rem; color: #1d4ed8; text-decoration: none;">
                            Edit / View
                        </a>
                    </div>
                </div>
                <div class="ballard-result-body" style="padding: 16px 20px;">
                    <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; margin-bottom: 16px;">
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Posture</span><br><strong>{{ $exam->nm_posture ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Square Window</span><br><strong>{{ $exam->nm_square_window ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Arm Recoil</span><br><strong>{{ $exam->nm_arm_recoil ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Popliteal Angle</span><br><strong>{{ $exam->nm_popliteal_angle ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Scarf Sign</span><br><strong>{{ $exam->nm_scarf_sign ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Heel to Ear</span><br><strong>{{ $exam->nm_heel_to_ear ?? '—' }}</strong></div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; margin-bottom: 16px;">
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Skin</span><br><strong>{{ $exam->pm_skin ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Lanugo</span><br><strong>{{ $exam->pm_lanugo ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Plantar Surface</span><br><strong>{{ $exam->pm_plantar_surface ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Breast</span><br><strong>{{ $exam->pm_breast ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Eye/Ear</span><br><strong>{{ $exam->pm_eye_ear ?? '—' }}</strong></div>
                        <div><span style="font-size: 0.7rem; color: #6b7280;">Genitals</span><br><strong>{{ $exam->pm_genitals ?? '—' }}</strong></div>
                    </div>
                    <div style="background: #eff6ff; border-radius: 8px; padding: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <span style="font-size: 0.7rem; color: #6b7280;">Neuromuscular Subtotal</span>
                                <p style="font-weight: 700; margin: 0;">{{ $exam->nm_subtotal }}</p>
                            </div>
                            <div>
                                <span style="font-size: 0.7rem; color: #6b7280;">Physical Subtotal</span>
                                <p style="font-weight: 700; margin: 0;">{{ $exam->pm_subtotal }}</p>
                            </div>
                            <div>
                                <span style="font-size: 0.7rem; color: #6b7280;">TOTAL SCORE</span>
                                <p style="font-size: 1.5rem; font-weight: 800; margin: 0; color: #1d4ed8;">{{ $exam->total_score ?? '—' }}</p>
                            </div>
                            <div>
                                <span style="font-size: 0.7rem; color: #6b7280;">Gestational Age</span>
                                <p style="font-size: 1.2rem; font-weight: 800; margin: 0; color: #059669;">{{ $exam->estimated_ga_weeks ? $exam->estimated_ga_weeks . ' weeks' : '—' }}</p>
                            </div>
                            @if($exam->estimated_ga_weeks)
                            <div>
                                @php $ga = $exam->estimated_ga_weeks; @endphp
                                @if($ga < 37)
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600;">Preterm</span>
                                    @elseif($ga > 42)
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600;">Post-term</span>
                                    @else
                                    <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600;">Term</span>
                                    @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @if($ballardExams->where('exam_number', 2)->isEmpty())
            <div style="margin-top: 12px; text-align: right;">
                <a href="{{ \App\Filament\Doctor\Pages\BallardScore::getUrl(['visitId' => $visit->id, 'exam' => 2]) }}"
                    target="_blank"
                    class="btn-secondary"
                    style="padding: 6px 14px; font-size: 0.75rem;">
                    + Record 2nd Exam
                </a>
            </div>
            @endif
            @endif
            @endif {{-- End of activeTab check --}}

        </div>{{-- /.chart-content --}}
    </div>{{-- /.chart-page --}}

    @else
    <div style="text-align:center;padding:60px 20px;">
        <p style="color:#9ca3af;margin-bottom:10px;">Visit not found or not accessible.</p>
        <a href="{{ \App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index') }}" style="color:#1d4ed8;font-size:.875rem;"><x-heroicon-o-chevron-left class="w-4 h-4 inline" /> Back to Admitted Patients</a>
    </div>
    @endif

<script>
(function () {
    function initChartTabScroll() {
        const wrap = document.querySelector('.chart-tabs-wrap');
        const bar  = document.querySelector('.chart-tabs');
        if (!wrap || !bar) return;
        const active = bar.querySelector('.chart-tab.active');
        if (active) {
            bar.scrollLeft = active.offsetLeft - (bar.clientWidth / 2) + (active.offsetWidth / 2);
        }
        function updateFades() {
            wrap.classList.toggle('scrolled', bar.scrollLeft > 4);
            wrap.classList.toggle('at-end', bar.scrollLeft + bar.clientWidth >= bar.scrollWidth - 4);
        }
        bar.addEventListener('scroll', updateFades, { passive: true });
        window.addEventListener('resize', updateFades);
        updateFades();
        document.addEventListener('livewire:updated', function () {
            setTimeout(function () {
                const a = bar.querySelector('.chart-tab.active');
                if (a) bar.scrollTo({ left: a.offsetLeft - (bar.clientWidth / 2) + (a.offsetWidth / 2), behavior: 'smooth' });
                updateFades();
            }, 60);
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChartTabScroll);
    } else {
        initChartTabScroll();
    }
})();
</script>
</x-filament-panels::page>