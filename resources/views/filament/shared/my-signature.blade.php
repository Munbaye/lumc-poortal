<x-filament-panels::page>

@push('styles')
<style>
:root {
    --sig-accent:   {{ $accentColor }};
    --sig-accent10: {{ $accentLight }};
    --sig-accent30: {{ $accentMid }};
}

.sig-page { max-width: 860px; margin: 0 auto; padding: 1.5rem 1rem 4rem; }

.sig-header {
    display: flex; align-items: center; gap: 1rem;
    background: #fff; border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
    margin-bottom: 1.75rem;
}
html.dark .sig-header { background:#1e293b; border-color:#334155; }
.sig-header-icon {
    width:52px; height:52px; border-radius:.875rem; flex-shrink:0;
    background: var(--sig-accent10);
    display:flex; align-items:center; justify-content:center;
}
.sig-header-icon svg { width:26px; height:26px; color:var(--sig-accent); }
.sig-header h1 { font-size:1.25rem; font-weight:700; color:#111827; line-height:1.2; margin:0; }
html.dark .sig-header h1 { color:#f1f5f9; }
.sig-header p { font-size:.83rem; color:#6b7280; margin:.25rem 0 0; }
html.dark .sig-header p { color:#94a3b8; }

/* ── Tabs ── */
.sig-tabs { display:flex; gap:.5rem; margin-bottom:1.25rem; }
.sig-tab {
    flex:1; padding:.7rem 1rem; border-radius:.7rem; font-size:.875rem;
    font-weight:600; border:2px solid #e5e7eb; background:#fff; cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:.5rem;
    color:#6b7280; transition: all .18s ease;
}
html.dark .sig-tab { background:#1e293b; border-color:#334155; color:#94a3b8; }
.sig-tab:hover { border-color:var(--sig-accent30); color:var(--sig-accent); }
.sig-tab.active { background:var(--sig-accent10); border-color:var(--sig-accent); color:var(--sig-accent); }
.sig-tab svg { width:16px; height:16px; flex-shrink:0; }

/* ── Panel wrapper ── */
.sig-panel {
    background:#fff; border-radius:1rem; border:1px solid #e5e7eb;
    box-shadow:0 1px 6px rgba(0,0,0,.05);
    margin-bottom:1.25rem;
}
html.dark .sig-panel { background:#1e293b; border-color:#334155; }
.sig-panel-body { padding:1.5rem; }

/* ══════════════════════════════════════════════════════════
   FIXED INTERACTION ZONE — always 300px tall
══════════════════════════════════════════════════════════ */
.sig-zone {
    width: 100%;
    height: 300px;
    position: relative;
    border-radius: .875rem;
    overflow: hidden;
}

.sig-canvas-wrap {
    position: absolute; inset: 0;
    border: 2px dashed #d1d5db;
    border-radius: .875rem;
    background: #fafafa;
    cursor: crosshair;
    overflow: hidden;
}
html.dark .sig-canvas-wrap { border-color:#475569; background:#0f172a; }
.sig-canvas-wrap.has-strokes { border-style:solid; border-color:var(--sig-accent30); }

#sig-canvas {
    display: block;
    width: 100%;
    height: 100%;
    touch-action: none;
}

.sig-canvas-hint {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    pointer-events: none; gap: .5rem;
    transition: opacity .2s;
}
.sig-canvas-hint.hidden { opacity: 0; }
.sig-canvas-hint svg { width: 40px; height: 40px; color: #d1d5db; }
.sig-canvas-hint span { font-size: .85rem; color: #9ca3af; }

.sig-upload-zone {
    position: absolute; inset: 0;
    border: 2px dashed #d1d5db;
    border-radius: .875rem;
    background: #fafafa;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: .85rem; cursor: pointer;
    transition: all .18s;
}
html.dark .sig-upload-zone { border-color:#475569; background:#0f172a; }
.sig-upload-zone:hover, .sig-upload-zone.dragover {
    border-color: var(--sig-accent); background: var(--sig-accent10);
}
.sig-upload-zone svg { width: 48px; height: 48px; color: #d1d5db; transition: color .18s; }
.sig-upload-zone:hover svg, .sig-upload-zone.dragover svg { color: var(--sig-accent); }
.sig-upload-zone p { font-size:.875rem; color:#6b7280; margin:0; text-align:center; line-height:1.5; }
.sig-upload-zone strong { color:#374151; }
html.dark .sig-upload-zone strong { color:#e2e8f0; }

.sig-processing-overlay {
    position: absolute; inset: 0;
    border-radius: .875rem;
    background: var(--sig-accent10);
    border: 2px solid var(--sig-accent30);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 1rem;
}
.sig-processing-text {
    font-size: .9rem; color: var(--sig-accent); font-weight: 600;
}

.sig-preview-grid {
    position: absolute; inset: 0;
    display: grid; grid-template-columns: 1fr 1fr; gap: 0;
    border-radius: .875rem; overflow: hidden;
    border: 1px solid #e5e7eb;
}
html.dark .sig-preview-grid { border-color:#334155; }
.sig-preview-cell {
    display: flex; flex-direction: column;
    padding: .75rem;
    overflow: hidden;
}
.sig-preview-cell:first-child { border-right: 1px solid #e5e7eb; }
html.dark .sig-preview-cell:first-child { border-color:#334155; }
.sig-preview-cell-label {
    font-size: .72rem; font-weight: 700; color: #9ca3af;
    text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: .5rem; flex-shrink: 0;
    display: flex; align-items: center; gap: .35rem;
}
.sig-preview-cell-label .check { color: #10b981; }
.sig-preview-img-wrap {
    flex: 1; min-height: 0;
    border-radius: .5rem; overflow: hidden;
    background: repeating-conic-gradient(#f0f0f0 0% 25%, #fff 0% 50%) 0 0 / 14px 14px;
    display: flex; align-items: center; justify-content: center;
}
html.dark .sig-preview-img-wrap {
    background: repeating-conic-gradient(#1e293b 0% 25%, #0f172a 0% 50%) 0 0 / 14px 14px;
}
.sig-preview-img-wrap img {
    max-width: 100%; max-height: 100%; object-fit: contain;
}

/* ── Controls below the zone ── */
.sig-controls-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 0 0; flex-wrap:wrap; gap:.5rem;
}
.sig-pen-sizes { display:flex; gap:.4rem; align-items:center; }
.sig-pen-sizes span { font-size:.75rem; color:#6b7280; margin-right:.25rem; }
.sig-pen-btn {
    width:32px; height:32px; border-radius:50%;
    border:2px solid #e5e7eb; background:#fff;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    transition: border-color .15s;
}
html.dark .sig-pen-btn { background:#0f172a; border-color:#475569; }
.sig-pen-btn.active { border-color:var(--sig-accent); }
.sig-pen-dot { border-radius:50%; background:#111827; }
html.dark .sig-pen-dot { background:#f1f5f9; }

.sig-btn {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.45rem .9rem; border-radius:.5rem;
    font-size:.8rem; font-weight:600;
    border:1.5px solid #e5e7eb; background:transparent;
    color:#6b7280; cursor:pointer; transition: all .15s;
}
html.dark .sig-btn { border-color:#475569; color:#94a3b8; }
.sig-btn:hover { border-color:#9ca3af; color:#374151; }
html.dark .sig-btn:hover { color:#e2e8f0; }
.sig-btn svg { width:14px; height:14px; }

/* ── Spinner ── */
.sig-spinner {
    width: 28px; height: 28px;
    border: 3px solid var(--sig-accent30);
    border-top-color: var(--sig-accent);
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Preview (draw tab, below zone) ── */
.sig-draw-preview { margin-top: 1rem; }
.sig-draw-preview p { font-size:.78rem; color:#6b7280; font-weight:600; margin:0 0 .5rem; }
.sig-thumb-wrap {
    border: 1px solid #e5e7eb; border-radius: .75rem; overflow: hidden;
    background: repeating-conic-gradient(#f0f0f0 0% 25%, #fff 0% 50%) 0 0 / 14px 14px;
    display: flex; align-items: center; justify-content: center;
    min-height: 100px; position: relative;
}
html.dark .sig-thumb-wrap {
    border-color:#334155;
    background: repeating-conic-gradient(#1e293b 0% 25%, #0f172a 0% 50%) 0 0 / 14px 14px;
}
.sig-thumb-wrap img { max-height: 90px; max-width: 100%; object-fit: contain; }

/* ── Info note ── */
.sig-note {
    display:flex; gap:.6rem; padding:.75rem 1rem; border-radius:.65rem;
    background:#f8fafc; border:1px solid #e5e7eb; margin-top:1rem;
}
html.dark .sig-note { background:#0f172a; border-color:#1e293b; }
.sig-note svg { width:15px; height:15px; color:#9ca3af; flex-shrink:0; margin-top:.1rem; }
.sig-note p { font-size:.78rem; color:#9ca3af; margin:0; line-height:1.5; }

/* ── Current signature card ── */
.sig-current-card {
    background:#fff; border-radius:1rem; border:1px solid #e5e7eb;
    box-shadow:0 1px 6px rgba(0,0,0,.05); overflow:hidden; margin-bottom:1.25rem;
}
html.dark .sig-current-card { background:#1e293b; border-color:#334155; }
.sig-current-header {
    padding:.9rem 1.25rem; border-bottom:1px solid #f3f4f6;
    display:flex; align-items:center; gap:.6rem;
}
html.dark .sig-current-header { border-color:#334155; }
.sig-current-header span { font-size:.875rem; font-weight:700; color:#374151; }
html.dark .sig-current-header span { color:#e2e8f0; }
.sig-status-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.sig-status-dot.has  { background:#10b981; }
.sig-status-dot.none { background:#d1d5db; }
.sig-current-body { padding:1.25rem; }
.sig-current-preview {
    border:1px solid #e5e7eb; border-radius:.75rem; overflow:hidden;
    background: repeating-conic-gradient(#f0f0f0 0% 25%, #fff 0% 50%) 0 0 / 14px 14px;
    display:flex; align-items:center; justify-content:center; min-height:90px;
}
html.dark .sig-current-preview {
    border-color:#334155;
    background: repeating-conic-gradient(#1e293b 0% 25%, #0f172a 0% 50%) 0 0 / 14px 14px;
}
.sig-current-preview img { max-height:80px; max-width:100%; object-fit:contain; }

/* ── Save / action button row ── */
.sig-action-row {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .6rem;
    margin-top: 0;
}

.sig-cancel-btn {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.55rem 1.1rem; border-radius:.65rem;
    font-size:.83rem; font-weight:600;
    border:1.5px solid #e5e7eb; background:transparent;
    color:#6b7280; cursor:pointer; transition: all .15s;
}
html.dark .sig-cancel-btn { border-color:#475569; color:#94a3b8; }
.sig-cancel-btn:hover { border-color:#9ca3af; color:#374151; background:#f9fafb; }
html.dark .sig-cancel-btn:hover { color:#e2e8f0; background:#1e293b; }
.sig-cancel-btn svg { width:14px; height:14px; }

.sig-save-btn {
    display:inline-flex; align-items:center; gap:.45rem;
    padding:.6rem 1.4rem; border-radius:.65rem;
    background:var(--sig-accent); color:#fff; border:none; cursor:pointer;
    font-size:.875rem; font-weight:700; letter-spacing:.02em;
    transition: opacity .15s, transform .1s;
    box-shadow: 0 2px 10px var(--sig-accent30);
}
.sig-save-btn:hover  { opacity:.9; transform:translateY(-1px); }
.sig-save-btn:active { transform:translateY(0); }
.sig-save-btn:disabled { opacity:.45; cursor:not-allowed; transform:none; }
.sig-save-btn svg { width:15px; height:15px; }
.sig-save-btn .btn-spinner {
    width:15px; height:15px;
    border:2px solid rgba(255,255,255,.35);
    border-top-color:#fff;
    border-radius:50%;
    animation:spin .7s linear infinite;
}

/* ── Alerts ── */
.sig-alert {
    padding:.75rem 1rem; border-radius:.75rem;
    font-size:.83rem; font-weight:600; margin-bottom:1.25rem;
    display:flex; align-items:center; gap:.5rem;
}
.sig-alert-success { background:#ecfdf5; border:1px solid #a7f3d0; color:#059669; }
.sig-alert-error   { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }
html.dark .sig-alert-success { background:#064e3b; border-color:#065f46; color:#6ee7b7; }
html.dark .sig-alert-error   { background:#450a0a; border-color:#7f1d1d; color:#fca5a5; }
.sig-alert svg { width:16px; height:16px; flex-shrink:0; }
</style>
@endpush

<div class="sig-page" x-data="signaturePage()" x-init="init()">

    {{-- Alerts --}}
    @if(session('sig_success'))
    <div class="sig-alert sig-alert-success">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('sig_success') }}
    </div>
    @endif
    @if(session('sig_error'))
    <div class="sig-alert sig-alert-error">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        {{ session('sig_error') }}
    </div>
    @endif

    {{-- Page header --}}
    <div class="sig-header">
        <div class="sig-header-icon">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
        </div>
        <div>
            <h1>My Signature</h1>
            <p>Draw or upload your official signature — used on clinical documents &amp; printed forms.</p>
        </div>
    </div>

    {{-- Current signature status --}}
    <div class="sig-current-card">
        <div class="sig-current-header">
            <div class="sig-status-dot {{ $currentSignature ? 'has' : 'none' }}"></div>
            <span>{{ $currentSignature ? 'Signature on file' : 'No signature saved yet' }}</span>
        </div>
        @if($currentSignature)
        <div class="sig-current-body">
            <div class="sig-current-preview">
                <img src="{{ $currentSignature }}" alt="Your current signature">
            </div>
            <p style="font-size:.75rem;color:#9ca3af;margin:.65rem 0 0;">
                This is how your signature currently appears on printed forms.
            </p>
        </div>
        @endif
    </div>

    {{-- Tab switcher --}}
    <div class="sig-tabs">
        <button class="sig-tab" :class="{active: activeTab==='draw'}" @click="switchTab('draw')">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            Draw Signature
        </button>
        <button class="sig-tab" :class="{active: activeTab==='upload'}" @click="switchTab('upload')">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload Image
        </button>
    </div>

    {{-- Main panel --}}
    <div class="sig-panel">
        <div class="sig-panel-body">

            <div class="sig-zone" id="sig-zone">

                {{-- DRAW: canvas --}}
                <div class="sig-canvas-wrap" :class="{'has-strokes': hasStrokes}"
                     x-show="activeTab==='draw'" id="canvas-wrap">
                    <canvas id="sig-canvas"></canvas>
                    <div class="sig-canvas-hint" :class="{hidden: hasStrokes}">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        <span>Sign here with your mouse or finger</span>
                    </div>
                </div>

                {{-- UPLOAD: drop zone (no file yet) --}}
                <div class="sig-upload-zone"
                     :class="{dragover: dragging}"
                     x-show="activeTab==='upload' && !uploadOriginal && !processing"
                     @click="$refs.fileInput.click()"
                     @dragover.prevent="dragging=true"
                     @dragleave="dragging=false"
                     @drop.prevent="handleDrop($event)">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    <p><strong>Click to browse</strong> or drag &amp; drop your signature image</p>
                    <p style="font-size:.78rem;">JPG, PNG — with background or already transparent, we'll handle it</p>
                </div>
                <input type="file" x-ref="fileInput" accept="image/*" style="display:none"
                       @change="handleFileSelect($event)">

                {{-- UPLOAD: processing overlay --}}
                <div class="sig-processing-overlay"
                     x-show="activeTab==='upload' && processing">
                    <div class="sig-spinner"></div>
                    <div class="sig-processing-text" x-text="processingMsg"></div>
                </div>

                {{-- UPLOAD: before/after preview --}}
                <div class="sig-preview-grid"
                     x-show="activeTab==='upload' && uploadOriginal && !processing">
                    <div class="sig-preview-cell">
                        <div class="sig-preview-cell-label">Original</div>
                        <div class="sig-preview-img-wrap">
                            <img :src="uploadOriginal" alt="original">
                        </div>
                    </div>
                    <div class="sig-preview-cell">
                        <div class="sig-preview-cell-label">
                            Processed
                            <span class="check">&#10003;</span>
                        </div>
                        <div class="sig-preview-img-wrap">
                            <img :src="uploadProcessed" alt="processed">
                        </div>
                    </div>
                </div>

            </div>{{-- /.sig-zone --}}

            {{-- Draw controls (below zone, draw tab only) --}}
            <div class="sig-controls-row" x-show="activeTab==='draw'">
                <div class="sig-pen-sizes">
                    <span>Pen size</span>
                    <button class="sig-pen-btn" :class="{active: penSize===1.5}" @click="penSize=1.5; updatePen()">
                        <div class="sig-pen-dot" style="width:3px;height:3px;"></div>
                    </button>
                    <button class="sig-pen-btn" :class="{active: penSize===2.5}" @click="penSize=2.5; updatePen()">
                        <div class="sig-pen-dot" style="width:5px;height:5px;"></div>
                    </button>
                    <button class="sig-pen-btn" :class="{active: penSize===4}" @click="penSize=4; updatePen()">
                        <div class="sig-pen-dot" style="width:8px;height:8px;"></div>
                    </button>
                </div>
                <button class="sig-btn" @click="clearCanvas()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Clear
                </button>
            </div>

            {{-- Upload controls (below zone, upload tab, after processing) --}}
            <div class="sig-controls-row" x-show="activeTab==='upload' && uploadOriginal && !processing">
                <span style="font-size:.78rem;color:#9ca3af;">
                    Background detected &amp; removed automatically
                </span>
                <button class="sig-btn" @click="resetUpload()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Use different image
                </button>
            </div>

            {{-- Draw preview (thumbnail, draw tab only) --}}
            <div class="sig-draw-preview" x-show="activeTab==='draw' && drawPreview">
                <p>Preview — transparent background:</p>
                <div class="sig-thumb-wrap">
                    <img :src="drawPreview" alt="preview">
                </div>
            </div>

            {{-- Info note --}}
            <div class="sig-note">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>
                    Your signature is saved with a transparent background and normalised to a fixed
                    400×150 px size — it will appear consistently across all clinical documents and forms.
                </p>
            </div>

        </div>
    </div>

    {{-- Save / Cancel button row — compact, right-aligned --}}
    <div class="sig-action-row">
        <button class="sig-cancel-btn"
                @click="activeTab==='draw' ? clearCanvas() : resetUpload()"
                :disabled="(activeTab==='draw' && !hasStrokes) || (activeTab==='upload' && !uploadOriginal) || saving"
                x-show="(activeTab==='draw' && hasStrokes) || (activeTab==='upload' && uploadOriginal)">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Cancel
        </button>
        <button class="sig-save-btn"
                @click="activeTab==='draw' ? saveDrawn() : saveUploaded()"
                :disabled="(activeTab==='draw' && !hasStrokes) || (activeTab==='upload' && !uploadProcessed) || saving">
            <template x-if="saving">
                <span class="btn-spinner"></span>
            </template>
            <template x-if="!saving">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </template>
            <span x-text="saving ? 'Saving…' : (activeTab==='draw' ? 'Save Drawn Signature' : 'Save Uploaded Signature')"></span>
        </button>
    </div>

</div>

@push('scripts')
<script>
function signaturePage() {
    return {
        activeTab:       'draw',
        hasStrokes:      false,
        drawing:         false,
        penSize:         2.5,
        canvas:          null,
        ctx:             null,
        drawPreview:     null,
        dragging:        false,
        processing:      false,
        processingMsg:   'Processing image…',
        uploadOriginal:  null,
        uploadProcessed: null,
        saving:          false,

        init() {
            this.$nextTick(() => this.initCanvas());
        },

        switchTab(tab) {
            this.activeTab = tab;
            if (tab === 'draw') {
                this.$nextTick(() => this.initCanvas());
            }
        },

        /* ── Canvas ── */
        initCanvas() {
            this.canvas = document.getElementById('sig-canvas');
            if (!this.canvas) return;
            const wrap = document.getElementById('canvas-wrap');
            if (!wrap) return;
            const w = wrap.offsetWidth  || 700;
            const h = wrap.offsetHeight || 300;
            if (this.canvas.width !== w || this.canvas.height !== h) {
                this.canvas.width  = w;
                this.canvas.height = h;
            }
            this.ctx = this.canvas.getContext('2d');
            this.ctx.lineCap     = 'round';
            this.ctx.lineJoin    = 'round';
            this.ctx.lineWidth   = this.penSize;
            this.ctx.strokeStyle = document.documentElement.classList.contains('dark')
                ? '#f1f5f9' : '#111827';

            const old = this.canvas;
            const neo = old.cloneNode(true);
            old.parentNode.replaceChild(neo, old);
            this.canvas = neo;
            this.ctx = neo.getContext('2d');
            this.ctx.lineCap   = 'round';
            this.ctx.lineJoin  = 'round';
            this.ctx.lineWidth = this.penSize;
            this.ctx.strokeStyle = document.documentElement.classList.contains('dark')
                ? '#f1f5f9' : '#111827';

            neo.addEventListener('mousedown',  e => this.startDraw(e));
            neo.addEventListener('mousemove',  e => this.draw(e));
            neo.addEventListener('mouseup',    () => this.stopDraw());
            neo.addEventListener('mouseleave', () => this.stopDraw());
            neo.addEventListener('touchstart', e => { e.preventDefault(); this.startDraw(e.touches[0]); }, {passive:false});
            neo.addEventListener('touchmove',  e => { e.preventDefault(); this.draw(e.touches[0]); },  {passive:false});
            neo.addEventListener('touchend',   () => this.stopDraw());
        },

        getPos(e) {
            const r = this.canvas.getBoundingClientRect();
            return {
                x: (e.clientX - r.left) * (this.canvas.width  / r.width),
                y: (e.clientY - r.top)  * (this.canvas.height / r.height),
            };
        },

        startDraw(e) {
            this.drawing = true;
            const {x, y} = this.getPos(e);
            this.ctx.beginPath();
            this.ctx.moveTo(x, y);
        },

        draw(e) {
            if (!this.drawing) return;
            const {x, y} = this.getPos(e);
            this.ctx.lineTo(x, y);
            this.ctx.stroke();
            this.hasStrokes = true;
            clearTimeout(this._pvTimer);
            this._pvTimer = setTimeout(() => this.updateDrawPreview(), 150);
        },

        stopDraw() {
            if (this.drawing) { this.drawing = false; this.updateDrawPreview(); }
        },

        updateDrawPreview() {
            if (!this.hasStrokes) return;
            const t = this.trimCanvas(this.canvas);
            const o = document.createElement('canvas');
            o.width = 400; o.height = 150;
            const oc = o.getContext('2d');
            const s = Math.min(400 / t.w, 150 / t.h, 1);
            oc.drawImage(t.canvas, (400 - t.w * s) / 2, (150 - t.h * s) / 2, t.w * s, t.h * s);
            this.drawPreview = o.toDataURL('image/png');
        },

        clearCanvas() {
            if (!this.ctx) return;
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.hasStrokes = false;
            this.drawPreview = null;
        },

        updatePen() {
            if (this.ctx) this.ctx.lineWidth = this.penSize;
        },

        trimCanvas(src) {
            const ctx = src.getContext('2d');
            const d = ctx.getImageData(0, 0, src.width, src.height);
            let top = src.height, bottom = 0, left = src.width, right = 0;
            for (let y = 0; y < src.height; y++) {
                for (let x = 0; x < src.width; x++) {
                    if (d.data[(y * src.width + x) * 4 + 3] > 10) {
                        if (y < top)    top    = y;
                        if (y > bottom) bottom = y;
                        if (x < left)   left   = x;
                        if (x > right)  right  = x;
                    }
                }
            }
            if (top > bottom || left > right) return { canvas: src, w: src.width, h: src.height };
            const pad = 14;
            top    = Math.max(0, top - pad);
            bottom = Math.min(src.height - 1, bottom + pad);
            left   = Math.max(0, left - pad);
            right  = Math.min(src.width - 1, right + pad);
            const w = right - left + 1, h = bottom - top + 1;
            const tmp = document.createElement('canvas');
            tmp.width = w; tmp.height = h;
            tmp.getContext('2d').putImageData(ctx.getImageData(left, top, w, h), 0, 0);
            return { canvas: tmp, w, h };
        },

        removeBackground(imgEl) {
            return new Promise(resolve => {
                const src = document.createElement('canvas');
                src.width  = imgEl.naturalWidth  || imgEl.width;
                src.height = imgEl.naturalHeight || imgEl.height;
                const sc = src.getContext('2d');
                sc.drawImage(imgEl, 0, 0);
                const data = sc.getImageData(0, 0, src.width, src.height);
                const d = data.data;

                let transparentCount = 0;
                for (let i = 3; i < d.length; i += 4) {
                    if (d[i] < 30) transparentCount++;
                }
                const transparencyRatio = transparentCount / (src.width * src.height);

                if (transparencyRatio > 0.25) {
                    for (let i = 0; i < d.length; i += 4) {
                        const a = d[i + 3];
                        if (a < 20) {
                            d[i + 3] = 0;
                        } else {
                            const brightness = (d[i] * 299 + d[i+1] * 587 + d[i+2] * 114) / 1000;
                            if (brightness < 200) {
                                d[i] = 25; d[i+1] = 25; d[i+2] = 25; d[i+3] = 255;
                            } else {
                                d[i+3] = Math.round(a * Math.pow(1 - brightness / 255, 0.5));
                            }
                        }
                    }
                } else {
                    const pts = [
                        [0, 0], [src.width - 1, 0],
                        [0, src.height - 1], [src.width - 1, src.height - 1],
                        [Math.floor(src.width / 2), 0],
                        [Math.floor(src.width / 2), src.height - 1],
                        [0, Math.floor(src.height / 2)],
                        [src.width - 1, Math.floor(src.height / 2)],
                    ];
                    let rS = 0, gS = 0, bS = 0;
                    pts.forEach(([x, y]) => {
                        const i = (y * src.width + x) * 4;
                        rS += d[i]; gS += d[i+1]; bS += d[i+2];
                    });
                    const bgR = rS / pts.length, bgG = gS / pts.length, bgB = bS / pts.length;
                    const isBright = (bgR + bgG + bgB) / 3 > 128;

                    for (let i = 0; i < d.length; i += 4) {
                        const r = d[i], g = d[i+1], b = d[i+2];
                        const dist = Math.sqrt((r-bgR)**2 + (g-bgG)**2 + (b-bgB)**2);
                        if (isBright) {
                            const br = (r * 299 + g * 587 + b * 114) / 1000;
                            if (br > 220 && dist < 55) {
                                d[i+3] = 0;
                            } else if (br > 180 && dist < 85) {
                                d[i+3] = Math.max(0, Math.round(255 * (1 - (br - 180) / 75)));
                            } else {
                                d[i] = 25; d[i+1] = 25; d[i+2] = 25;
                            }
                        } else {
                            const br = (r * 299 + g * 587 + b * 114) / 1000;
                            if (br < 35 && dist < 55) {
                                d[i+3] = 0;
                            } else if (br < 75 && dist < 85) {
                                d[i+3] = Math.max(0, Math.round(255 * (br - 35) / 40));
                            }
                            if (d[i+3] > 10) { d[i] = 25; d[i+1] = 25; d[i+2] = 25; }
                        }
                    }
                }

                sc.putImageData(data, 0, 0);
                const t = this.trimCanvas(src);
                const o = document.createElement('canvas');
                o.width = 400; o.height = 150;
                const oc = o.getContext('2d');
                const s  = Math.min(400 / t.w, 150 / t.h, 1);
                oc.drawImage(t.canvas, (400 - t.w * s) / 2, (150 - t.h * s) / 2, t.w * s, t.h * s);
                resolve(o.toDataURL('image/png'));
            });
        },

        handleFileSelect(e) {
            const f = e.target.files[0];
            if (f) this.processFile(f);
            e.target.value = '';
        },

        handleDrop(e) {
            this.dragging = false;
            const f = e.dataTransfer.files[0];
            if (f && f.type.startsWith('image/')) this.processFile(f);
        },

        processFile(file) {
            this.processing      = true;
            this.processingMsg   = 'Loading image…';
            this.uploadOriginal  = null;
            this.uploadProcessed = null;
            const reader = new FileReader();
            reader.onload = ev => {
                this.uploadOriginal = ev.target.result;
                this.processingMsg  = 'Detecting ink & removing background…';
                const img = new Image();
                img.onload = async () => {
                    this.processingMsg   = 'Finalising signature…';
                    this.uploadProcessed = await this.removeBackground(img);
                    this.processing      = false;
                };
                img.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        },

        resetUpload() {
            this.uploadOriginal  = null;
            this.uploadProcessed = null;
            this.processing      = false;
        },

        async saveDrawn() {
            if (!this.hasStrokes) return;
            this.updateDrawPreview();
            await this.$nextTick();
            this.submitSignature(this.drawPreview);
        },

        async saveUploaded() {
            if (!this.uploadProcessed) return;
            this.submitSignature(this.uploadProcessed);
        },

        submitSignature(dataUrl) {
            this.saving = true;
            const form  = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ $saveUrl }}';
            const csrf  = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
            const sig   = document.createElement('input');
            sig.type = 'hidden'; sig.name = 'signature'; sig.value = dataUrl;
            form.appendChild(csrf);
            form.appendChild(sig);
            document.body.appendChild(form);
            form.submit();
        },
    };
}
</script>
@endpush

</x-filament-panels::page> 