<x-filament-panels::page>
    <style>
        /* ══════════════════════════════════════════════════════════
   PAGE CONTENT MANAGER — LUMC palette
   ══════════════════════════════════════════════════════════ */
        .pc {
            max-width: 940px;
        }

        /* ── TABS ─────────────────────────────────────────────── */
        .pc-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 22px;
        }

        .pc-tab {
            padding: 7px 16px;
            border-radius: 9px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            border: 1.5px solid #e5e7eb;
            transition: all .17s;
            background: transparent;
            color: #6b7280;
            font-family: inherit;
        }

        .dark .pc-tab {
            border-color: rgb(55 65 81);
            color: #9ca3af;
        }

        .pc-tab:hover {
            border-color: #93c5fd;
            color: #1e3a8a;
            background: rgba(59, 130, 246, .05);
        }

        .dark .pc-tab:hover {
            color: #93c5fd;
        }

        .pc-tab.active {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 3px 12px rgba(29, 78, 216, .26);
        }

        /* ── PANELS ──────────────────────────────────────────── */
        .pc-panel {
            display: none;
        }

        .pc-panel.active {
            display: block;
        }

        /* ── CARD ────────────────────────────────────────────── */
        .pc-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px 22px;
            margin-bottom: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
        }

        .dark .pc-card {
            background: rgb(31 41 55);
            border-color: rgb(55 65 81);
        }

        .pc-card-title {
            font-size: 12.5px;
            font-weight: 800;
            color: #1e3a8a;
            margin: 0 0 2px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .dark .pc-card-title {
            color: #93c5fd;
        }

        .pc-card-sub {
            font-size: 11px;
            color: #9ca3af;
            margin: 0 0 14px;
        }

        /* ── GRID ─────────────────────────────────────────────── */
        .pc-g2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        @media(max-width:580px) {
            .pc-g2 {
                grid-template-columns: 1fr;
            }
        }

        /* ── FIELDS ───────────────────────────────────────────── */
        .pc-f {
            margin-bottom: 12px;
        }

        .pc-lbl {
            display: block;
            font-size: 9px;
            font-weight: 800;
            color: #9ca3af;
            letter-spacing: .2em;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .pc-in,
        .pc-ta {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 9px;
            padding: 8px 12px;
            font-size: 13.5px;
            background: #f9fafb;
            color: #111827;
            font-family: inherit;
            outline: none;
            transition: .17s;
        }

        .dark .pc-in,
        .dark .pc-ta {
            background: rgb(17 24 39);
            border-color: rgb(55 65 81);
            color: #f9fafb;
        }

        .pc-in:focus,
        .pc-ta:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
            background: #fff;
        }

        .dark .pc-in:focus,
        .dark .pc-ta:focus {
            background: rgb(17 24 39);
        }

        .pc-ta {
            resize: vertical;
            min-height: 82px;
        }

        .pc-hint {
            font-size: 9.5px;
            color: #9ca3af;
            margin-top: 3px;
        }

        /* ── BUTTONS ─────────────────────────────────────────── */
        .pc-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 800;
            padding: 9px 18px;
            border-radius: 9px;
            border: none;
            cursor: pointer;
            transition: all .17s;
            font-family: inherit;
            white-space: nowrap;
        }

        .pc-btn-blue {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: #fff;
            box-shadow: 0 4px 12px rgba(29, 78, 216, .28);
        }

        .pc-btn-blue:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 20px rgba(29, 78, 216, .38);
        }

        .pc-btn-slate {
            background: linear-gradient(135deg, #334155, #475569);
            color: #fff;
            box-shadow: 0 4px 11px rgba(51, 65, 85, .22);
        }

        .pc-btn-slate:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 18px rgba(51, 65, 85, .32);
        }

        .pc-btn-ghost {
            background: transparent;
            color: #6b7280;
            border: 1.5px solid #d1d5db;
        }

        .dark .pc-btn-ghost {
            border-color: rgb(55 65 81);
            color: #9ca3af;
        }

        .pc-btn-ghost:hover {
            border-color: #94a3b8;
            color: #374151;
        }

        /* ── SAVE BAR ─────────────────────────────────────────── */
        .pc-bar {
            position: sticky;
            bottom: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .97);
            backdrop-filter: blur(12px);
            border-top: 1px solid #e5e7eb;
            margin-top: 6px;
            padding: 11px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 9px;
        }

        .dark .pc-bar {
            background: rgba(17, 24, 39, .97);
            border-color: rgb(55 65 81);
        }

        .pc-dirty {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            font-weight: 700;
            color: #f59e0b;
        }

        .pc-dirty-dot {
            width: 7px;
            height: 7px;
            background: #f59e0b;
            border-radius: 50%;
            animation: pcBk 1.4s infinite;
            display: inline-block;
        }

        @keyframes pcBk {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .15
            }
        }

        /* ── MODALS — compact, centered, no wasted space ──────── */
        .pc-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(2, 8, 28, .72);
            backdrop-filter: blur(5px);
            animation: pcFd .15s ease;
        }

        @keyframes pcFd {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        .pc-modal {
            width: calc(100% - 28px);
            max-width: 390px;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 52px rgba(0, 0, 0, .32);
            animation: pcSl .2s cubic-bezier(.34, 1.56, .64, 1);
        }

        .dark .pc-modal {
            background: rgb(15 23 42);
        }

        @keyframes pcSl {
            from {
                opacity: 0;
                transform: translateY(16px) scale(.97)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .pc-modal-stripe {
            height: 4px;
        }

        .s-blue {
            background: linear-gradient(90deg, #1e3a8a, #3b82f6, #1e3a8a);
            background-size: 200% auto;
            animation: sMov 3s linear infinite;
        }

        .s-slate {
            background: linear-gradient(90deg, #334155, #64748b, #334155);
            background-size: 200% auto;
            animation: sMov 3s linear infinite;
        }

        @keyframes sMov {
            0% {
                background-position: 0%
            }

            100% {
                background-position: 200%
            }
        }

        .pc-modal-body {
            padding: 20px 20px 18px;
            text-align: center;
        }

        .pc-modal-ico {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .ico-b {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
        }

        .ico-s {
            background: linear-gradient(135deg, #334155, #475569);
        }

        .pc-modal-title {
            font-size: 15.5px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 6px;
        }

        .dark .pc-modal-title {
            color: #f1f5f9;
        }

        .pc-modal-desc {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.55;
            margin: 0 0 16px;
        }

        .dark .pc-modal-desc {
            color: #94a3b8;
        }

        .pc-modal-desc strong {
            color: #0f172a;
            font-weight: 700;
        }

        .dark .pc-modal-desc strong {
            color: #e2e8f0;
        }

        .pc-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .pc-spin {
            display: inline-block;
            width: 13px;
            height: 13px;
            border: 2.5px solid rgba(255, 255, 255, .3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: pcSpin .6s linear infinite;
        }

        @keyframes pcSpin {
            to {
                transform: rotate(360deg)
            }
        }
    </style>

    <div class="pc" id="pcRoot" x-data="{ dirty: false }" @input="dirty = true">

        {{-- HEADER --}}
        <div
            style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:20px;">
            <div>
                <h1 style="font-size:21px;font-weight:900;margin:0 0 3px;" class="text-gray-900 dark:text-white">Landing
                    Page Content</h1>
                <p style="font-size:12.5px;color:#94a3b8;margin:0;">Edit every section of the public landing page. Save
                    when ready — changes go live instantly.</p>
            </div>
            <button type="button" wire:click="askRestore" class="pc-btn pc-btn-slate"
                style="font-size:12px;padding:7px 13px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Restore All to Default
            </button>
        </div>

        {{-- TABS --}}
        <div class="pc-tabs">
            <button type="button" class="pc-tab active" onclick="pcTab('hero',this)">🏠 Hero</button>
            <button type="button" class="pc-tab" onclick="pcTab('stats',this)">📊 Stats</button>
            <button type="button" class="pc-tab" onclick="pcTab('about',this)">🏥 About</button>
            <button type="button" class="pc-tab" onclick="pcTab('mission',this)">🎯 Mission & Vision</button>
            <button type="button" class="pc-tab" onclick="pcTab('depts',this)">🔬 Departments</button>
            <button type="button" class="pc-tab" onclick="pcTab('contact',this)">📞 Contact & Footer</button>
        </div>

        {{-- ══ HERO ══ --}}
        <div id="pc-hero" class="pc-panel active">
            <div class="pc-card">
                <p class="pc-card-title">Hero Headline</p>
                <p class="pc-card-sub">The large animated text block on the left side of the hero section.</p>
                <div class="pc-f"><label class="pc-lbl">Badge (animated pill above headline)</label><input type="text"
                        wire:model="hero_badge" class="pc-in"></div>
                <div class="pc-g2">
                    <div class="pc-f"><label class="pc-lbl">Heading Line 1 (white)</label><input type="text"
                            wire:model="hero_heading_1" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Heading Line 2 (shimmer gold)</label><input type="text"
                            wire:model="hero_heading_2" class="pc-in"></div>
                </div>
                <div class="pc-f"><label class="pc-lbl">Description Paragraph</label><textarea
                        wire:model="hero_description" class="pc-ta"></textarea></div>
                <div class="pc-g2">
                    <div class="pc-f"><label class="pc-lbl">Donation Amount (bold white)</label><input type="text"
                            wire:model="hero_amount" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Spirit Word (italic red)</label><input type="text"
                            wire:model="hero_spirit_word" class="pc-in"></div>
                </div>
            </div>
        </div>

        {{-- ══ STATS ══ --}}
        <div id="pc-stats" class="pc-panel">
            <div class="pc-card">
                <p class="pc-card-title">Statistics Bar</p>
                <p class="pc-card-sub">4 yellow-number boxes in the dark blue band below the hero.</p>
                <div class="pc-g2">
                    <div class="pc-f"><label class="pc-lbl">Stat 1 — Value</label><input type="text"
                            wire:model="stat_beds" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Stat 1 — Label</label><input type="text"
                            wire:model="stat_beds_label" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Stat 2 — Value</label><input type="text"
                            wire:model="stat_staff" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Stat 2 — Label</label><input type="text"
                            wire:model="stat_staff_label" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Stat 3 — Value</label><input type="text"
                            wire:model="stat_patients" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Stat 3 — Label</label><input type="text"
                            wire:model="stat_patients_label" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Stat 4 — Value</label><input type="text"
                            wire:model="stat_buildings" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Stat 4 — Label</label><input type="text"
                            wire:model="stat_buildings_label" class="pc-in"></div>
                </div>
            </div>
        </div>

        {{-- ══ ABOUT ══ --}}
        <div id="pc-about" class="pc-panel">
            <div class="pc-card">
                <p class="pc-card-title">About Section — Text</p>
                <p class="pc-card-sub">The "Our Journey & Transformation" section with 3 paragraphs.</p>
                <div class="pc-g2">
                    <div class="pc-f"><label class="pc-lbl">Section Tag (tiny red)</label><input type="text"
                            wire:model="about_section_tag" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Section Heading</label><input type="text"
                            wire:model="about_heading" class="pc-in"></div>
                </div>
                <div class="pc-f"><label class="pc-lbl">Paragraph 1</label><textarea wire:model="about_para_1"
                        class="pc-ta"></textarea></div>
                <div class="pc-f"><label class="pc-lbl">Paragraph 2</label><textarea wire:model="about_para_2"
                        class="pc-ta"></textarea></div>
                <div class="pc-f"><label class="pc-lbl">Paragraph 3</label><textarea wire:model="about_para_3"
                        class="pc-ta"></textarea></div>
            </div>
            <div class="pc-card">
                <p class="pc-card-title">About — Highlight Cards (right side)</p>
                <p class="pc-card-sub">3 small stat cards on the right of the about section.</p>
                <div class="pc-g2">
                    <div class="pc-f"><label class="pc-lbl">Card 1 — Value</label><input type="text"
                            wire:model="about_card_pct" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Card 1 — Sub-text</label><input type="text"
                            wire:model="about_card_pct_sub" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Card 2 — Title (blue)</label><input type="text"
                            wire:model="about_card_digital" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Card 2 — Sub-text</label><input type="text"
                            wire:model="about_card_digital_sub" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Card 3 — Title (red full-width)</label><input type="text"
                            wire:model="about_card_class" class="pc-in"></div>
                    <div class="pc-f"><label class="pc-lbl">Card 3 — Sub-text</label><input type="text"
                            wire:model="about_card_class_sub" class="pc-in"></div>
                </div>
            </div>
        </div>

        {{-- ══ MISSION & VISION ══ --}}
        <div id="pc-mission" class="pc-panel">
            <div class="pc-card">
                <p class="pc-card-title">Vision Statement</p>
                <p class="pc-card-sub">Left card of the Mission & Vision section.</p>
                <div class="pc-f"><label class="pc-lbl">Vision Text</label><textarea wire:model="vision_text"
                        class="pc-ta" style="min-height:108px;"></textarea></div>
            </div>
            <div class="pc-card">
                <p class="pc-card-title">Mission — 4 Checklist Points</p>
                <p class="pc-card-sub">Right card. Leave blank to hide that item.</p>
                <div class="pc-f"><label class="pc-lbl">Point 1</label><input type="text" wire:model="mission_1"
                        class="pc-in"></div>
                <div class="pc-f"><label class="pc-lbl">Point 2</label><input type="text" wire:model="mission_2"
                        class="pc-in"></div>
                <div class="pc-f"><label class="pc-lbl">Point 3</label><input type="text" wire:model="mission_3"
                        class="pc-in"></div>
                <div class="pc-f"><label class="pc-lbl">Point 4</label><input type="text" wire:model="mission_4"
                        class="pc-in"></div>
            </div>
        </div>

        {{-- ══ DEPARTMENTS ══ --}}
        <div id="pc-depts" class="pc-panel">
            <div class="pc-card">
                <p class="pc-card-title">Clinical Departments</p>
                <p class="pc-card-sub">One specialty per line — each becomes a bullet point in the department card on
                    the landing page.</p>
                <div class="pc-f"><label class="pc-lbl">Surgery</label><textarea wire:model="dept_surgery_items"
                        class="pc-ta" style="min-height:116px;"></textarea>
                    <p class="pc-hint">One item per line: Orthopedic ↵ Ophthalmology ↵ Urology</p>
                </div>
                <div class="pc-f"><label class="pc-lbl">Internal Medicine</label><textarea
                        wire:model="dept_medicine_items" class="pc-ta" style="min-height:105px;"></textarea></div>
                <div class="pc-f"><label class="pc-lbl">OB-Gynecology</label><textarea wire:model="dept_obgyn_items"
                        class="pc-ta" style="min-height:96px;"></textarea></div>
                <div class="pc-f"><label class="pc-lbl">Pediatrics</label><textarea wire:model="dept_pedia_items"
                        class="pc-ta" style="min-height:96px;"></textarea></div>
            </div>
        </div>

        {{-- ══ CONTACT & FOOTER ══ --}}
        <div id="pc-contact" class="pc-panel">
            <div class="pc-card">
                <p class="pc-card-title">Contact Information</p>
                <p class="pc-card-sub">Shown in the footer contact column.</p>
                <div class="pc-f"><label class="pc-lbl">Address</label><input type="text" wire:model="contact_address"
                        class="pc-in"></div>
                <div class="pc-f"><label class="pc-lbl">Phone Number(s)</label><input type="text"
                        wire:model="contact_phones" class="pc-in"></div>
                <div class="pc-f"><label class="pc-lbl">Email Address</label><input type="text"
                        wire:model="contact_email" class="pc-in"></div>
                <div class="pc-f"><label class="pc-lbl">Emergency Hotline</label><input type="text"
                        wire:model="contact_emergency" class="pc-in"></div>
            </div>
            <div class="pc-card">
                <p class="pc-card-title">Footer Text</p>
                <p class="pc-card-sub">Text for the 3 footer columns at the bottom of the landing page.</p>
                <div class="pc-f"><label class="pc-lbl">About Blurb (left column)</label><textarea
                        wire:model="footer_tagline" class="pc-ta"></textarea></div>
                <div class="pc-f"><label class="pc-lbl">Governing Body Text (right column)</label><textarea
                        wire:model="footer_about" class="pc-ta"></textarea></div>
                <div class="pc-f"><label class="pc-lbl">Gov Body Card Label (italic yellow)</label><input type="text"
                        wire:model="footer_gov_body" class="pc-in"></div>
            </div>
        </div>

        {{-- ══ STICKY SAVE BAR — NO loading spinner ══ --}}
        <div class="pc-bar">
            <div>
                <div class="pc-dirty" x-show="dirty"><span class="pc-dirty-dot"></span> Unsaved changes</div>
                <p style="font-size:11px;color:#9ca3af;margin:0;" x-show="!dirty">Saved content goes live on the landing
                    page immediately.</p>
            </div>
            <button type="button" wire:click="askSave" class="pc-btn pc-btn-blue" @click="dirty = false">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Save All Changes
            </button>
        </div>
    </div>

    {{-- ══ SAVE CONFIRM MODAL ══ --}}
    @if($showSaveConfirm)
        <div class="pc-overlay" wire:click.self="cancelSave">
            <div class="pc-modal">
                <div class="pc-modal-stripe s-blue"></div>
                <div class="pc-modal-body">
                    <div class="pc-modal-ico ico-b">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#fff;" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                    </div>
                    <p class="pc-modal-title">Save All Changes?</p>
                    <p class="pc-modal-desc">Your edits will be written to the database and become <strong>publicly visible
                            on the landing page immediately</strong>.</p>
                    <div class="pc-actions">
                        <button wire:click="cancelSave" type="button" class="pc-btn pc-btn-ghost">Cancel</button>
                        <button wire:click="save" type="button" class="pc-btn pc-btn-blue">
                            <div wire:loading wire:target="save" class="pc-spin"></div>
                            <span wire:loading.remove wire:target="save">Yes, Save</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ══ RESTORE CONFIRM MODAL ══ --}}
    @if($showRestoreModal)
        <div class="pc-overlay" wire:click.self="cancelRestore">
            <div class="pc-modal">
                <div class="pc-modal-stripe s-slate"></div>
                <div class="pc-modal-body">
                    <div class="pc-modal-ico ico-s">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#fff;" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <p class="pc-modal-title">Restore All Defaults?</p>
                    <p class="pc-modal-desc">All your current edits will be <strong>permanently overwritten</strong> with
                        the original default content. This cannot be undone.</p>
                    <div class="pc-actions">
                        <button wire:click="cancelRestore" type="button" class="pc-btn pc-btn-ghost">Cancel</button>
                        <button wire:click="executeRestore" type="button" class="pc-btn pc-btn-slate">
                            <div wire:loading wire:target="executeRestore" class="pc-spin"></div>
                            <span wire:loading.remove wire:target="executeRestore">Yes, Restore</span>
                            <span wire:loading wire:target="executeRestore">Restoring...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function pcTab(name, btn) {
            document.querySelectorAll('.pc-panel').forEach(function (p) { p.classList.remove('active'); });
            document.querySelectorAll('.pc-tab').forEach(function (b) { b.classList.remove('active'); });
            var p = document.getElementById('pc-' + name);
            if (p) p.classList.add('active');
            btn.classList.add('active');
        }
    </script>

</x-filament-panels::page>