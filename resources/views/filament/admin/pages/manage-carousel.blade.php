<x-filament-panels::page>
<style>
/* ══════════════════════════════════════════════════════════
   CAROUSEL MANAGER — LUMC palette. No purple.
   ══════════════════════════════════════════════════════════ */
.cm { max-width: 1100px; display: flex; flex-direction: column; gap: 20px; }

/* ── CARD ──────────────────────────────────────────────── */
.cm-card { background:#fff; border:1px solid #e5e7eb; border-radius:18px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.05); }
.dark .cm-card { background:rgb(31 41 55); border-color:rgb(55 65 81); }
.cm-card-hd { padding:18px 22px 0; }
.cm-card-title { font-size:14.5px; font-weight:800; color:#1e3a8a; margin:0 0 3px; }
.dark .cm-card-title { color:#93c5fd; }
.cm-card-sub { font-size:11px; color:#9ca3af; margin:0; }

/* ── DROP ZONE ─────────────────────────────────────────── */
.cm-drop { margin:16px 22px 0; border:2.5px dashed #3b82f6; border-radius:14px; background:linear-gradient(135deg,rgba(59,130,246,.05),rgba(29,78,216,.03)); min-height:120px; position:relative; transition:all .2s; cursor:pointer; overflow:hidden; }
.cm-drop:hover { border-color:#1d4ed8; background:linear-gradient(135deg,rgba(59,130,246,.1),rgba(29,78,216,.07)); box-shadow:0 0 0 4px rgba(59,130,246,.07); }
.cm-drop.drag  { border-color:#1d4ed8; box-shadow:0 0 0 5px rgba(59,130,246,.1); transform:scale(1.003); }
.cm-drop.ready { border-color:#16a34a; border-style:solid; background:linear-gradient(135deg,rgba(22,163,74,.07),rgba(20,83,45,.03)); }
.cm-drop.done  { border-color:#16a34a; border-style:solid; background:linear-gradient(135deg,rgba(22,163,74,.1),rgba(20,83,45,.05)); }
.cm-drop-input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; z-index:2; }
.cm-drop-inner { padding:22px 20px; text-align:center; pointer-events:none; }
.cm-drop-ico { width:46px; height:46px; border-radius:13px; background:linear-gradient(135deg,#1e3a8a,#2563eb); display:inline-flex; align-items:center; justify-content:center; margin-bottom:10px; box-shadow:0 5px 16px rgba(30,58,138,.24); transition:transform .2s; }
.cm-drop:hover .cm-drop-ico { transform:translateY(-2px) scale(1.05); }
.cm-drop.ready .cm-drop-ico,.cm-drop.done .cm-drop-ico { background:linear-gradient(135deg,#15803d,#16a34a); }
.cm-drop-title { font-size:14px; font-weight:800; color:#1e3a8a; margin:0 0 3px; }
.dark .cm-drop-title { color:#93c5fd; }
.cm-drop-sub { font-size:11px; color:#6b7280; margin:0; }

/* ── PREVIEW STRIP ─────────────────────────────────────── */
.cm-previews { display:flex; flex-wrap:wrap; gap:9px; padding:14px 22px 0; }
.cm-prev-item { position:relative; width:84px; height:84px; border-radius:10px; overflow:hidden; border:2px solid #e5e7eb; background:#f1f5f9; flex-shrink:0; animation:cmPop .22s cubic-bezier(.34,1.56,.64,1); }
.dark .cm-prev-item { border-color:rgb(55 65 81); background:rgb(17 24 39); }
@keyframes cmPop { from{opacity:0;transform:scale(.75)} to{opacity:1;transform:scale(1)} }
.cm-prev-item img { width:100%; height:100%; object-fit:cover; display:block; }
.cm-prev-name { position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,.65); color:#fff; font-size:7px; font-weight:700; padding:3px 4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.cm-prev-size { position:absolute; top:3px; right:3px; background:rgba(0,0,0,.55); color:#fff; font-size:6.5px; font-weight:700; padding:2px 4px; border-radius:3px; }
.cm-prev-rm { position:absolute; top:2px; left:2px; width:17px; height:17px; background:rgba(220,38,38,.88); border-radius:50%; color:#fff; font-size:9px; font-weight:900; display:flex; align-items:center; justify-content:center; cursor:pointer; border:none; opacity:0; transition:opacity .15s; }
.cm-prev-item:hover .cm-prev-rm { opacity:1; }
.cm-prev-ok { position:absolute; inset:0; background:rgba(22,163,74,.22); display:flex; align-items:center; justify-content:center; }

/* ── UPLOAD ROW ────────────────────────────────────────── */
.cm-upload-row { display:flex; flex-wrap:wrap; align-items:flex-end; gap:10px; padding:14px 22px 20px; }
.cm-upload-row .cm-lfield { flex:1; min-width:180px; }
.cm-upload-row .cm-lfield label { display:block; font-size:9px; font-weight:800; color:#9ca3af; letter-spacing:.2em; text-transform:uppercase; margin-bottom:5px; }
.cm-inp { width:100%; border:1.5px solid #e5e7eb; border-radius:9px; padding:8px 11px; font-size:13px; background:#f9fafb; color:#111827; font-family:inherit; outline:none; transition:.18s; }
.dark .cm-inp { background:rgb(17 24 39); border-color:rgb(55 65 81); color:#f9fafb; }
.cm-inp:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.1); background:#fff; }
.dark .cm-inp:focus { background:rgb(17 24 39); }

/* ── BUTTONS ───────────────────────────────────────────── */
.cm-btn { display:inline-flex; align-items:center; gap:7px; font-size:13px; font-weight:800; padding:9px 18px; border-radius:9px; border:none; cursor:pointer; transition:all .18s; font-family:inherit; white-space:nowrap; }
.cm-btn-blue  { background:linear-gradient(135deg,#1e3a8a,#2563eb); color:#fff; box-shadow:0 4px 12px rgba(29,78,216,.28); }
.cm-btn-blue:hover  { transform:translateY(-1px); box-shadow:0 7px 20px rgba(29,78,216,.38); }
.cm-btn-red   { background:linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; box-shadow:0 4px 12px rgba(220,38,38,.28); }
.cm-btn-red:hover   { transform:translateY(-1px); box-shadow:0 7px 20px rgba(220,38,38,.38); }
.cm-btn-slate { background:linear-gradient(135deg,#334155,#475569); color:#fff; box-shadow:0 4px 12px rgba(51,65,85,.22); }
.cm-btn-slate:hover { transform:translateY(-1px); box-shadow:0 7px 20px rgba(51,65,85,.32); }
.cm-btn-amber { background:linear-gradient(135deg,#d97706,#b45309); color:#fff; box-shadow:0 4px 12px rgba(217,119,6,.28); }
.cm-btn-amber:hover { transform:translateY(-1px); box-shadow:0 7px 20px rgba(217,119,6,.38); }
.cm-btn-ghost { background:transparent; color:#6b7280; border:1.5px solid #d1d5db; }
.dark .cm-btn-ghost { border-color:rgb(55 65 81); color:#9ca3af; }
.cm-btn-ghost:hover { border-color:#94a3b8; color:#374151; background:#f8fafc; }
.dark .cm-btn-ghost:hover { background:rgb(30 41 59); color:#e5e7eb; }
.cm-btn:disabled { opacity:.4; cursor:not-allowed; transform:none !important; }

/* ── GALLERY ───────────────────────────────────────────── */
.cm-gallery-hd { padding:18px 22px 14px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:8px; }
.cm-gallery-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(195px,1fr)); gap:14px; padding:0 22px 22px; }

/* Bulk action bar — slides in when items selected */
.cm-bulk-bar {
    margin:0 22px 14px;
    padding:10px 16px;
    background:linear-gradient(135deg,rgba(30,58,138,.06),rgba(37,99,235,.04));
    border:1.5px solid rgba(30,58,138,.18);
    border-radius:11px;
    display:flex; flex-wrap:wrap; align-items:center; gap:10px;
    animation:cmPop .22s cubic-bezier(.34,1.56,.64,1);
}
.dark .cm-bulk-bar { background:rgba(30,58,138,.15); border-color:rgba(59,130,246,.25); }
.cm-bulk-count { font-size:12.5px; font-weight:800; color:#1e3a8a; flex:1; }
.dark .cm-bulk-count { color:#93c5fd; }

/* Gallery card */
.cm-img-card { border-radius:12px; overflow:hidden; border:2px solid #e5e7eb; background:#fff; transition:all .2s; display:flex; flex-direction:column; position:relative; }
.dark .cm-img-card { background:rgb(17 24 39); border-color:rgb(55 65 81); }
.cm-img-card:hover { box-shadow:0 6px 24px rgba(0,0,0,.09); transform:translateY(-2px); border-color:#93c5fd; }
.cm-img-card.hidden-card { opacity:.5; border-style:dashed; border-color:#fbbf24; }
.cm-img-card.selected-card { border-color:#2563eb; border-width:2.5px; box-shadow:0 0 0 3px rgba(37,99,235,.15); transform:none !important; }
.cm-img-card.drag-over-card { border-color:#3b82f6; border-style:dashed; background:rgba(59,130,246,.04); }
.cm-img-card[draggable="true"] { cursor:grab; }
.cm-img-card[draggable="true"]:active { cursor:grabbing; }
.cm-img-card.dragging { opacity:.4; transform:scale(.97); }

/* Select checkbox overlay */
.cm-select-cb {
    position:absolute; top:7px; left:7px; z-index:3;
    width:20px; height:20px;
    background:#fff; border:2px solid #d1d5db;
    border-radius:5px; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    transition:all .15s;
    box-shadow:0 1px 4px rgba(0,0,0,.1);
}
.dark .cm-select-cb { background:rgb(31 41 55); border-color:rgb(75 85 99); }
.cm-select-cb:hover { border-color:#3b82f6; background:#eff6ff; }
.cm-img-card.selected-card .cm-select-cb { background:#2563eb; border-color:#2563eb; }
.cm-select-cb svg { display:none; }
.cm-img-card.selected-card .cm-select-cb svg { display:block; }

/* Drag handle */
.cm-drag-handle { position:absolute; top:7px; right:7px; z-index:3; width:22px; height:22px; background:rgba(255,255,255,.85); border-radius:5px; display:flex; align-items:center; justify-content:center; cursor:grab; color:#94a3b8; transition:all .15s; box-shadow:0 1px 4px rgba(0,0,0,.1); }
.dark .cm-drag-handle { background:rgba(31,41,55,.85); }
.cm-drag-handle:hover { color:#1e3a8a; background:#fff; }
.dark .cm-drag-handle:hover { color:#93c5fd; background:rgb(31 41 55); }

/* Image preview */
.cm-img-prev { position:relative; width:100%; height:148px; display:flex; align-items:center; justify-content:center; background:transparent; flex-shrink:0; overflow:hidden; }
.cm-img-prev img { max-width:100%; max-height:100%; object-fit:contain; padding:10px; display:block; filter:drop-shadow(0 4px 12px rgba(0,0,0,.18)); }
.cm-hid-badge { position:absolute; bottom:6px; right:6px; font-size:8px; font-weight:800; padding:2px 7px; border-radius:5px; text-transform:uppercase; letter-spacing:.07em; background:rgba(251,191,36,.92); color:#78350f; }

.cm-img-lbl { padding:8px 10px 0; flex:1; }
.cm-img-lbl-inp { width:100%; font-size:12px; border:1.5px solid transparent; border-radius:7px; padding:4px 7px; background:transparent; color:#374151; transition:.15s; font-family:inherit; }
.dark .cm-img-lbl-inp { color:#e5e7eb; }
.cm-img-lbl-inp::placeholder { color:#d1d5db; }
.cm-img-lbl-inp:hover { border-color:#e2e8f0; background:#f8fafc; }
.dark .cm-img-lbl-inp:hover { border-color:#4b5563; background:rgb(17 24 39); }
.cm-img-lbl-inp:focus { outline:none; border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,.1); }
.dark .cm-img-lbl-inp:focus { background:rgb(17 24 39); }

.cm-img-ft { display:flex; align-items:center; justify-content:space-between; padding:6px 8px 9px; }
.cm-num { font-size:9px; font-weight:800; color:#cbd5e1; text-transform:uppercase; letter-spacing:.1em; }
.cm-ico-row { display:flex; gap:2px; }
.cm-ico { width:26px; height:26px; border-radius:6px; display:flex; align-items:center; justify-content:center; border:none; cursor:pointer; background:transparent; color:#94a3b8; transition:all .14s; }
.cm-ico:hover { background:#f1f5f9; color:#1e3a8a; }
.dark .cm-ico:hover { background:rgb(51 65 85); color:#93c5fd; }
.cm-ico:disabled { opacity:.2; cursor:not-allowed; }
.cm-ico.eye-on { color:#16a34a; }
.cm-ico.eye-on:hover { background:#dcfce7; }
.cm-ico.del:hover { background:#fee2e2; color:#dc2626; }
.dark .cm-ico.del:hover { background:rgba(220,38,38,.12); }

/* ── MODALS ────────────────────────────────────────────── */
.cm-overlay { position:fixed; inset:0; z-index:99999; display:flex; align-items:center; justify-content:center; background:rgba(2,8,28,.72); backdrop-filter:blur(5px); animation:cmFd .15s ease; }
@keyframes cmFd { from{opacity:0} to{opacity:1} }
.cm-modal { width:calc(100% - 28px); max-width:400px; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 20px 56px rgba(0,0,0,.32); animation:cmSl .2s cubic-bezier(.34,1.56,.64,1); }
.dark .cm-modal { background:rgb(15 23 42); }
@keyframes cmSl { from{opacity:0;transform:translateY(16px) scale(.97)} to{opacity:1;transform:none} }
.cm-modal-stripe { height:4px; }
.s-blue  { background:linear-gradient(90deg,#1e3a8a,#3b82f6,#1e3a8a); background-size:200% auto; animation:sM 3s linear infinite; }
.s-red   { background:linear-gradient(90deg,#b91c1c,#ef4444,#b91c1c); background-size:200% auto; animation:sM 3s linear infinite; }
.s-slate { background:linear-gradient(90deg,#334155,#64748b,#334155); background-size:200% auto; animation:sM 3s linear infinite; }
.s-amber { background:linear-gradient(90deg,#d97706,#fbbf24,#d97706); background-size:200% auto; animation:sM 3s linear infinite; }
@keyframes sM { 0%{background-position:0%} 100%{background-position:200%} }
.cm-modal-body { padding:20px 20px 18px; text-align:center; }
.cm-modal-ico { width:46px; height:46px; border-radius:13px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:11px; }
.ico-b { background:linear-gradient(135deg,#1e3a8a,#2563eb); }
.ico-r { background:linear-gradient(135deg,#dc2626,#b91c1c); }
.ico-s { background:linear-gradient(135deg,#334155,#475569); }
.ico-a { background:linear-gradient(135deg,#d97706,#b45309); }
.cm-modal-title { font-size:16px; font-weight:900; color:#0f172a; margin:0 0 6px; }
.dark .cm-modal-title { color:#f1f5f9; }
.cm-modal-desc { font-size:12.5px; color:#64748b; line-height:1.55; margin:0 0 16px; }
.dark .cm-modal-desc { color:#94a3b8; }
.cm-modal-desc strong { color:#0f172a; font-weight:700; }
.dark .cm-modal-desc strong { color:#e2e8f0; }
.cm-actions { display:flex; gap:8px; justify-content:center; }
.cm-spin { display:inline-block; width:13px; height:13px; border:2.5px solid rgba(255,255,255,.3); border-top-color:#fff; border-radius:50%; animation:cmSpin .6s linear infinite; }
@keyframes cmSpin { to{transform:rotate(360deg)} }
.cm-empty { text-align:center; padding:44px 20px; }
.cm-empty svg { width:48px; height:48px; margin:0 auto 12px; color:#cbd5e1; display:block; }
.cm-empty p { font-size:13.5px; font-weight:700; color:#94a3b8; margin:0 0 3px; }
.cm-empty span { font-size:11.5px; color:#cbd5e1; }
</style>

<div class="cm">

    {{-- PAGE HEADER --}}
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h1 style="font-size:21px;font-weight:900;margin:0 0 3px;" class="text-gray-900 dark:text-white">Hero Carousel</h1>
            <p style="font-size:12.5px;color:#94a3b8;margin:0;">Manage the rotating images in the landing page hero section. Changes go live instantly.</p>
        </div>
        <button type="button" wire:click="askRestore" class="cm-btn cm-btn-slate" style="font-size:12px;padding:8px 14px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Restore to Defaults
        </button>
    </div>

    {{-- UPLOAD CARD --}}
    <div class="cm-card">
        <div class="cm-card-hd">
            <p class="cm-card-title">Upload New Images</p>
            <p class="cm-card-sub">Select or drag & drop images. Preview appears instantly. Click "Upload to Carousel" when ready.</p>
        </div>

        {{-- Drop zone --}}
        <div class="cm-drop" id="cmDrop" wire:ignore.self>
            <div wire:ignore class="cm-drop-inner" id="cmDropInner">
                <div class="cm-drop-ico" id="cmDropIco">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <p class="cm-drop-title" id="cmDropTitle">Click to browse or drag & drop images here</p>
                <p class="cm-drop-sub" id="cmDropSub">JPG · PNG · GIF · WEBP · Max 10 MB each</p>
            </div>
            <input type="file" wire:model="upload" class="cm-drop-input" id="cmFileIn" multiple accept="image/*" onchange="cmFiles(this)">
        </div>

        {{-- JS-managed: wire:ignore so Livewire never wipes these --}}
        <div wire:ignore>
            <div class="cm-previews" id="cmPreviews" style="display:none;"></div>
            <div id="cmProg" style="display:none;padding:10px 22px 0;">
                <div style="display:flex;align-items:center;gap:9px;">
                    <svg style="width:14px;height:14px;color:#3b82f6;animation:cmSpin .8s linear infinite;flex-shrink:0;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path style="opacity:.85" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <p style="font-size:11.5px;color:#6b7280;font-weight:600;margin:0;">Processing your image — this may take a moment.</p>
                </div>
                <p style="font-size:10.5px;color:#9ca3af;margin:4px 0 0 23px;">You can click Upload to Carousel at any time.</p>
            </div>
        </div>

        @error('upload.*') <p style="color:#dc2626;font-size:11.5px;font-weight:700;padding:8px 22px 0;">⚠ {{ $message }}</p> @enderror
        @error('upload')   <p style="color:#dc2626;font-size:11.5px;font-weight:700;padding:8px 22px 0;">⚠ {{ $message }}</p> @enderror

        {{-- Label + Upload button --}}
        <div class="cm-upload-row">
            <div class="cm-lfield">
                <label>Label / Alt Text (optional)</label>
                <input type="text" wire:model="newLabel" placeholder="e.g. LUMC Awarding Ceremony 2024" class="cm-inp">
            </div>
            {{-- wire:ignore: prevents Livewire re-render from resetting disabled=true --}}
            <div wire:ignore style="flex-shrink:0;">
                <button type="button" onclick="window.__cmUpload()" class="cm-btn cm-btn-blue" id="cmUpBtn" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Upload to Carousel
                </button>
            </div>
        </div>
    </div>

    {{-- GALLERY CARD --}}
    <div class="cm-card">
        <div class="cm-gallery-hd">
            <div>
                <p class="cm-card-title" style="margin-bottom:2px;">Current Gallery</p>
                @php $images = $this->getImages(); @endphp
                <p class="cm-card-sub">
                    {{ $images->count() }} image{{ $images->count()!==1?'s':'' }}
                    &nbsp;·&nbsp;<span style="color:#16a34a;font-weight:700;">{{ $images->where('is_active',true)->count() }} visible</span>
                    &nbsp;·&nbsp;<span style="color:#f59e0b;font-weight:700;">{{ $images->where('is_active',false)->count() }} hidden</span>
                    @if(count($selectedIds) > 0)
                    &nbsp;·&nbsp;<span style="color:#2563eb;font-weight:700;">{{ count($selectedIds) }} selected</span>
                    @endif
                </p>
            </div>
            <span style="font-size:10px;font-weight:700;color:#94a3b8;background:#f1f5f9;border-radius:7px;padding:4px 9px;" class="dark:bg-gray-700">
                Drag cards to reorder · Select cards for bulk actions
            </span>
        </div>

        {{-- Bulk action bar (visible when items are selected) --}}
        @if(count($selectedIds) > 0)
        <div class="cm-bulk-bar">
            <span class="cm-bulk-count">
                {{ count($selectedIds) }} image{{ count($selectedIds)!==1?'s':'' }} selected
            </span>
            <button type="button" wire:click="openBulkAction('show')" class="cm-btn cm-btn-blue" style="font-size:11.5px;padding:6px 13px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Show All
            </button>
            <button type="button" wire:click="openBulkAction('hide')" class="cm-btn cm-btn-amber" style="font-size:11.5px;padding:6px 13px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                Hide All
            </button>
            <button type="button" wire:click="openBulkAction('delete')" class="cm-btn cm-btn-red" style="font-size:11.5px;padding:6px 13px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete All
            </button>
            <button type="button" wire:click="clearSelection" class="cm-btn cm-btn-ghost" style="font-size:11.5px;padding:6px 13px;">Clear</button>
        </div>
        @endif

        @if($images->isEmpty())
        <div class="cm-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p>No images yet.</p>
            <span>Upload your first image above.</span>
        </div>
        @else
        <div class="cm-gallery-grid" id="cmGalleryGrid">
            @foreach($images as $i => $img)
            @php $isSelected = in_array($img->id, $selectedIds); @endphp
            <div class="cm-img-card {{ !$img->is_active ? 'hidden-card' : '' }} {{ $isSelected ? 'selected-card' : '' }}"
                 data-id="{{ $img->id }}"
                 draggable="true">

                {{-- Select checkbox --}}
                <div class="cm-select-cb" wire:click="toggleSelect({{ $img->id }})" title="Select">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                {{-- Drag handle --}}
                <div class="cm-drag-handle" title="Drag to reorder">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="9" cy="6" r="1.5"/><circle cx="15" cy="6" r="1.5"/>
                        <circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/>
                        <circle cx="9" cy="18" r="1.5"/><circle cx="15" cy="18" r="1.5"/>
                    </svg>
                </div>

                {{-- Preview --}}
                <div class="cm-img-prev">
                    @if(!$img->is_active)<span class="cm-hid-badge">Hidden</span>@endif
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('carousel/' . $img->filename) }}"
                         alt="{{ $img->label ?? '' }}"
                         loading="lazy"
                         onerror="this.parentElement.innerHTML='<span style=\'font-size:11px;color:#94a3b8;font-weight:600;\'>Not found</span>'">
                </div>

                {{-- Editable label --}}
                <div class="cm-img-lbl">
                    <input type="text"
                           class="cm-img-lbl-inp"
                           value="{{ $img->label ?? '' }}"
                           placeholder="Add a label..."
                           wire:change="updateLabel({{ $img->id }}, $event.target.value)">
                </div>

                {{-- Footer --}}
                <div class="cm-img-ft">
                    <span class="cm-num"># {{ $i + 1 }}</span>
                    <div class="cm-ico-row">
                        <button wire:click="moveUp({{ $img->id }})" @if($i===0) disabled @endif class="cm-ico" title="Move up">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                        </button>
                        <button wire:click="moveDown({{ $img->id }})" @if($i===$images->count()-1) disabled @endif class="cm-ico" title="Move down">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <button wire:click="toggleActive({{ $img->id }})" class="cm-ico {{ $img->is_active ? 'eye-on' : '' }}" title="{{ $img->is_active ? 'Hide' : 'Show' }}">
                            @if($img->is_active)
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            @endif
                        </button>
                        <button wire:click="confirmDeleteImage({{ $img->id }})" class="cm-ico del" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ══ UPLOAD CONFIRM MODAL ══ --}}
@if($showUploadConfirm)
<div class="cm-overlay" wire:click.self="cancelUpload">
    <div class="cm-modal">
        <div class="cm-modal-stripe s-blue"></div>
        <div class="cm-modal-body">
            <div class="cm-modal-ico ico-b">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:23px;height:23px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            </div>
            <p class="cm-modal-title">Upload {{ count($upload) }} Image{{ count($upload)>1?'s':'' }}?</p>
            <p class="cm-modal-desc">
                @if($newLabel)Label: <strong>"{{ $newLabel }}"</strong><br>@endif
                {{ count($upload) === 1 ? 'This image' : 'These images' }} will be added to the carousel and appear on the landing page immediately.
            </p>
            <div class="cm-actions">
                <button wire:click="cancelUpload" type="button" class="cm-btn cm-btn-ghost">Cancel</button>
                <button wire:click="confirmUpload" type="button" class="cm-btn cm-btn-blue">
                    <div wire:loading wire:target="confirmUpload" class="cm-spin"></div>
                    <span wire:loading.remove wire:target="confirmUpload">Yes, Upload</span>
                    <span wire:loading wire:target="confirmUpload">Uploading...</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══ DELETE CONFIRM MODAL ══ --}}
@if($showDeleteModal)
<div class="cm-overlay" wire:click.self="cancelDelete">
    <div class="cm-modal">
        <div class="cm-modal-stripe s-red"></div>
        <div class="cm-modal-body">
            <div class="cm-modal-ico ico-r">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:23px;height:23px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <p class="cm-modal-title">Delete Image?</p>
            <p class="cm-modal-desc">
                <strong>"{{ $pendingDeleteLabel }}"</strong> will be permanently removed from the carousel and from storage. This cannot be undone.
            </p>
            <div class="cm-actions">
                <button wire:click="cancelDelete" type="button" class="cm-btn cm-btn-ghost">Cancel</button>
                <button wire:click="executeDelete" type="button" class="cm-btn cm-btn-red">
                    <div wire:loading wire:target="executeDelete" class="cm-spin"></div>
                    <span wire:loading.remove wire:target="executeDelete">Yes, Delete</span>
                    <span wire:loading wire:target="executeDelete">Deleting...</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══ BULK ACTION CONFIRM MODAL ══ --}}
@if($showBulkModal)
<div class="cm-overlay" wire:click.self="cancelBulk">
    <div class="cm-modal">
        <div class="cm-modal-stripe {{ $bulkAction === 'delete' ? 's-red' : ($bulkAction === 'hide' ? 's-amber' : 's-blue') }}"></div>
        <div class="cm-modal-body">
            <div class="cm-modal-ico {{ $bulkAction === 'delete' ? 'ico-r' : ($bulkAction === 'hide' ? 'ico-a' : 'ico-b') }}">
                @if($bulkAction === 'delete')
                <svg xmlns="http://www.w3.org/2000/svg" style="width:23px;height:23px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                @elseif($bulkAction === 'hide')
                <svg xmlns="http://www.w3.org/2000/svg" style="width:23px;height:23px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" style="width:23px;height:23px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                @endif
            </div>
            <p class="cm-modal-title">
                @if($bulkAction === 'delete') Delete {{ count($selectedIds) }} Images?
                @elseif($bulkAction === 'hide') Hide {{ count($selectedIds) }} Images?
                @else Show {{ count($selectedIds) }} Images?
                @endif
            </p>
            <p class="cm-modal-desc">
                @if($bulkAction === 'delete')
                    <strong>{{ count($selectedIds) }} selected image{{ count($selectedIds)!==1?'s':'' }}</strong> will be permanently deleted from storage. This cannot be undone.
                @elseif($bulkAction === 'hide')
                    <strong>{{ count($selectedIds) }} selected image{{ count($selectedIds)!==1?'s':'' }}</strong> will be hidden from the landing page carousel.
                @else
                    <strong>{{ count($selectedIds) }} selected image{{ count($selectedIds)!==1?'s':'' }}</strong> will be made visible in the landing page carousel.
                @endif
            </p>
            <div class="cm-actions">
                <button wire:click="cancelBulk" type="button" class="cm-btn cm-btn-ghost">Cancel</button>
                <button wire:click="executeBulk" type="button" class="cm-btn {{ $bulkAction === 'delete' ? 'cm-btn-red' : ($bulkAction === 'hide' ? 'cm-btn-amber' : 'cm-btn-blue') }}">
                    <div wire:loading wire:target="executeBulk" class="cm-spin"></div>
                    <span wire:loading.remove wire:target="executeBulk">Confirm</span>
                    <span wire:loading wire:target="executeBulk">Working...</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══ RESTORE CONFIRM MODAL ══ --}}
@if($showRestoreModal)
<div class="cm-overlay" wire:click.self="cancelRestore">
    <div class="cm-modal">
        <div class="cm-modal-stripe s-slate"></div>
        <div class="cm-modal-body">
            <div class="cm-modal-ico ico-s">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:23px;height:23px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <p class="cm-modal-title">Restore to Defaults?</p>
            <p class="cm-modal-desc">
                All <strong>{{ $this->getImages()->count() }} current image{{ $this->getImages()->count()!==1?'s':'' }}</strong> will be deleted and replaced with the original 4 default images. This cannot be undone.
            </p>
            <div class="cm-actions">
                <button wire:click="cancelRestore" type="button" class="cm-btn cm-btn-ghost">Cancel</button>
                <button wire:click="executeRestore" type="button" class="cm-btn cm-btn-slate">
                    <div wire:loading wire:target="executeRestore" class="cm-spin"></div>
                    <span wire:loading.remove wire:target="executeRestore">Yes, Restore</span>
                    <span wire:loading wire:target="executeRestore">Restoring...</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<script>
(function () {
    var files  = [];
    var isDone = false;

    function fmt(b) {
        if (b < 1024) return b + ' B';
        if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
        return (b/1048576).toFixed(1) + ' MB';
    }

    function setZone(state) {
        var zone  = document.getElementById('cmDrop');
        var title = document.getElementById('cmDropTitle');
        var sub   = document.getElementById('cmDropSub');
        if (!zone) return;
        zone.classList.remove('ready','done','drag');
        if (state === 'ready') {
            zone.classList.add('ready');
            if (title) title.textContent = files.length + ' file' + (files.length>1?'s':'') + ' selected — ready to upload';
            if (sub)   sub.textContent   = 'Click "Upload to Carousel" below when ready.';
        } else if (state === 'done') {
            zone.classList.add('done');
            if (title) title.textContent = '✓ Uploaded! Images are now in the gallery.';
            if (sub)   sub.textContent   = 'Select more images to upload another batch.';
        } else {
            if (title) title.textContent = 'Click to browse or drag & drop images here';
            if (sub)   sub.textContent   = 'JPG · PNG · GIF · WEBP · Max 10 MB each';
        }
    }

    function showProcessing(v) {
        var w = document.getElementById('cmProg');
        if (w) w.style.display = v ? 'block' : 'none';
    }

    function render() {
        var strip = document.getElementById('cmPreviews');
        var btn   = document.getElementById('cmUpBtn');
        if (!strip) return;
        if (files.length === 0) {
            strip.style.display = 'none';
            if (btn) btn.disabled = true;
            setZone('idle');
            return;
        }
        strip.style.display = 'flex';
        strip.innerHTML = '';
        files.forEach(function (file, idx) {
            var item = document.createElement('div');
            item.className = 'cm-prev-item';
            var szEl = document.createElement('span'); szEl.className='cm-prev-size'; szEl.textContent=fmt(file.size); item.appendChild(szEl);
            var nmEl = document.createElement('span'); nmEl.className='cm-prev-name'; nmEl.textContent=file.name; item.appendChild(nmEl);
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img'); img.src=e.target.result; img.alt=file.name;
                item.insertBefore(img, szEl);
                if (isDone) {
                    var ok = document.createElement('div'); ok.className='cm-prev-ok';
                    ok.innerHTML='<svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;color:#16a34a;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
                    item.appendChild(ok);
                }
            };
            reader.readAsDataURL(file);
            if (!isDone) {
                var rm = document.createElement('button'); rm.type='button'; rm.className='cm-prev-rm'; rm.innerHTML='&times;'; rm.title='Remove';
                rm.addEventListener('click', function(e){ e.stopPropagation(); files.splice(idx,1); isDone=false; render(); });
                item.appendChild(rm);
            }
            strip.appendChild(item);
        });
        if (btn) btn.disabled = false;
        setZone(isDone ? 'done' : 'ready');
    }

    window.cmFiles = function(input) {
        if (!input.files || input.files.length === 0) return;
        files  = Array.from(input.files);
        isDone = false;
        render();
        showProcessing(true);
        var hide = setTimeout(function(){ showProcessing(false); }, 10000);
        document.addEventListener('livewire:upload-finish', function h(){
            clearTimeout(hide); showProcessing(false);
            document.removeEventListener('livewire:upload-finish', h);
        });
    };

    /* Livewire bridge for upload button (inside wire:ignore) */
    window.__cmUpload = function() {
        var el = document.querySelector('[wire\\:id]');
        var id = el ? el.getAttribute('wire:id') : null;
        var comp = id && window.Livewire ? window.Livewire.find(id) : null;
        if (comp) { comp.call('prepareUpload'); return; }
        if (window.Livewire && window.Livewire.all) {
            var all = window.Livewire.all();
            if (all.length > 0) all[0].call('prepareUpload');
        }
    };

    window.addEventListener('carousel-upload-done', function() {
        isDone = true;
        showProcessing(false);
        render();
        setTimeout(function(){ files=[]; isDone=false; render(); }, 5000);
    });

    /* ── DRAG & DROP on file input ──────────────────────── */
    document.addEventListener('DOMContentLoaded', function() {
        var zone = document.getElementById('cmDrop');
        if (zone) {
            zone.addEventListener('dragover',  function(e){ e.preventDefault(); zone.classList.add('drag'); });
            zone.addEventListener('dragleave', function(){  zone.classList.remove('drag'); });
            zone.addEventListener('drop', function(e){
                e.preventDefault(); zone.classList.remove('drag');
                var inp = document.getElementById('cmFileIn');
                if (!inp || !e.dataTransfer.files.length) return;
                try {
                    var dt = new DataTransfer();
                    Array.from(e.dataTransfer.files).forEach(function(f){ dt.items.add(f); });
                    inp.files = dt.files; cmFiles(inp);
                    inp.dispatchEvent(new Event('change',{bubbles:true}));
                } catch(err){}
            });
        }

        /* ── GALLERY DRAG-TO-REORDER ──────────────────── */
        initGalleryDrag();
    });

    /* Re-init drag after Livewire re-renders gallery */
    document.addEventListener('livewire:update', function(){ setTimeout(initGalleryDrag, 80); });

    var dragSrc = null;

    function initGalleryDrag() {
        var grid = document.getElementById('cmGalleryGrid');
        if (!grid) return;

        var cards = grid.querySelectorAll('.cm-img-card[draggable="true"]');
        cards.forEach(function(card) {
            /* Prevent re-binding */
            if (card.dataset.dragBound) return;
            card.dataset.dragBound = '1';

            card.addEventListener('dragstart', function(e) {
                dragSrc = card;
                card.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', card.dataset.id);
            });
            card.addEventListener('dragend', function() {
                card.classList.remove('dragging');
                grid.querySelectorAll('.cm-img-card').forEach(function(c){
                    c.classList.remove('drag-over-card');
                });
                dragSrc = null;
                /* Save new order to server */
                saveOrder(grid);
            });
            card.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                if (dragSrc && dragSrc !== card) {
                    grid.querySelectorAll('.cm-img-card').forEach(function(c){ c.classList.remove('drag-over-card'); });
                    card.classList.add('drag-over-card');
                }
            });
            card.addEventListener('drop', function(e) {
                e.preventDefault();
                card.classList.remove('drag-over-card');
                if (!dragSrc || dragSrc === card) return;
                /* Re-order DOM */
                var allCards = Array.from(grid.querySelectorAll('.cm-img-card'));
                var srcIdx   = allCards.indexOf(dragSrc);
                var tgtIdx   = allCards.indexOf(card);
                if (srcIdx < tgtIdx) {
                    grid.insertBefore(dragSrc, card.nextSibling);
                } else {
                    grid.insertBefore(dragSrc, card);
                }
            });
        });
    }

    function saveOrder(grid) {
        var ids = Array.from(grid.querySelectorAll('.cm-img-card[data-id]'))
            .map(function(c){ return parseInt(c.dataset.id, 10); });
        /* Call Livewire reorder method */
        var el   = document.querySelector('[wire\\:id]');
        var wid  = el ? el.getAttribute('wire:id') : null;
        var comp = wid && window.Livewire ? window.Livewire.find(wid) : null;
        if (comp) {
            comp.call('reorder', ids);
        } else if (window.Livewire && window.Livewire.all) {
            var all = window.Livewire.all();
            if (all.length > 0) all[0].call('reorder', ids);
        }
    }

})();
</script>

</x-filament-panels::page>