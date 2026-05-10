{{-- ═══════════════════════════════════════════════════════════════════════
     LUMC — My Account Modal  (staff + patient panels)
     Clean light/white design · Panel-themed accent · Light + Dark mode
     ═══════════════════════════════════════════════════════════════════════ --}}
@php
    $user = auth()->user();
    if (!$user) return;

    $panelId  = filament()->getCurrentPanel()?->getId() ?? 'admin';
    $fullName = $user->full_name ?: $user->name;

    $accents = [
        'admin'   => ['hex' => '#1d4ed8', 'rgb' => '29,78,216',   'dark' => '#60a5fa',  'light' => '#eff6ff', 'border' => '#bfdbfe'],
        'doctor'  => ['hex' => '#0d9488', 'rgb' => '13,148,136',  'dark' => '#2dd4bf',  'light' => '#f0fdfa', 'border' => '#99f6e4'],
        'nurse'   => ['hex' => '#e11d48', 'rgb' => '225,29,72',   'dark' => '#fb7185',  'light' => '#fff1f2', 'border' => '#fecdd3'],
        'clerk'   => ['hex' => '#d97706', 'rgb' => '217,119,6',   'dark' => '#fbbf24',  'light' => '#fffbeb', 'border' => '#fde68a'],
        'tech'    => ['hex' => '#ea580c', 'rgb' => '234,88,12',   'dark' => '#fb923c',  'light' => '#fff7ed', 'border' => '#fed7aa'],
        'patient' => ['hex' => '#16a34a', 'rgb' => '22,163,74',   'dark' => '#4ade80',  'light' => '#f0fdf4', 'border' => '#bbf7d0'],
    ];
    $ac = $accents[$panelId] ?? $accents['admin'];

    $avatarData  = $user->avatar ?? null;
    $isPhoto     = $avatarData && str_starts_with($avatarData, 'data:image');
    $isIcon      = $avatarData && str_starts_with($avatarData, 'icon:');
    $iconKey     = $isIcon ? substr($avatarData, 5) : null;
    $customInit  = $user->avatar_initials;

    $nameParts   = array_filter(explode(' ', trim($fullName)));
    $defaultInit = strtoupper(
        (isset($nameParts[0]) ? $nameParts[0][0] : '') .
        (count($nameParts) > 1 ? end($nameParts)[0] : '')
    ) ?: 'U';
    $displayInit = $customInit ?: $defaultInit;

    $icons = [
        'user-m'   => ['label' => 'Male',   'path' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'],
        'user-f'   => ['label' => 'Female', 'path' => 'M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z'],
        'user-doc' => ['label' => 'Doctor', 'path' => 'M5.25 8.25h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 5.25a2.25 2.25 0 0 0-4.5 0v11.25a2.25 2.25 0 0 0 4.5 0V5.25Z'],
        'user-nrs' => ['label' => 'Nurse',  'path' => 'M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z'],
        'shield'   => ['label' => 'Shield', 'path' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z'],
        'star'     => ['label' => 'Star',   'path' => 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z'],
        'heart'    => ['label' => 'Heart',  'path' => 'M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z'],
    ];
@endphp

<style>
/* ════════════════════════════════════════════════════════════
   LUMC ACCOUNT MODAL — CSS variables + global overrides
   ════════════════════════════════════════════════════════════ */
:root {
    --am-accent:      {{ $ac['hex'] }};
    --am-accent-rgb:  {{ $ac['rgb'] }};
    --am-accent-dark: {{ $ac['dark'] }};
    --am-accent-lt:   {{ $ac['light'] }};
    --am-accent-bd:   {{ $ac['border'] }};
}

/* ── Brand logo size ── */
.fi-logo { display: flex !important; align-items: center !important; }
.fi-logo > a, .fi-logo > div { display: flex !important; align-items: center !important; }
.fi-logo .lumc-brand-lumc { font-size: 1rem !important; }
.fi-logo .lumc-brand-role { font-size: .875rem !important; }

/* ── Hide "Enable system theme" (3rd button in .fi-theme-switcher) ── */
.fi-theme-switcher > button:last-child,
.fi-theme-switcher > button:nth-child(3) { display: none !important; }

/* ══ MY ACCOUNT LINK — unique href target, works in any Filament version ══ */
a[href="#account"] {
    display: flex !important;
    align-items: center !important;
    gap: .5rem !important;
    padding: .42rem .75rem !important;
    margin: 2px 0 !important;
    border-radius: 6px !important;
    font-weight: 700 !important;
    font-size: .8rem !important;
    transition: all .15s !important;
    /* Light mode */
    background: var(--am-accent-lt) !important;
    border: 1.5px solid var(--am-accent-bd) !important;
    color: var(--am-accent) !important;
    width: 100% !important;
    box-sizing: border-box !important;
}
a[href="#account"] svg { color: var(--am-accent) !important; }
a[href="#account"]:hover {
    filter: brightness(.96) !important;
    transform: translateX(2px) !important;
}
/* Dark mode */
html.dark a[href="#account"] {
    background: rgba(var(--am-accent-rgb), .18) !important;
    border-color: rgba(var(--am-accent-rgb), .35) !important;
    color: var(--am-accent-dark) !important;
}
html.dark a[href="#account"] svg { color: var(--am-accent-dark) !important; }
html.dark a[href="#account"]:hover {
    background: rgba(var(--am-accent-rgb), .26) !important;
}

/* ══ INJECTED USER NAME HEADER IN DROPDOWN ══ */
.am-user-header {
    display: flex !important;
    align-items: center !important;
    gap: .625rem !important;
    padding: .875rem 1rem .75rem !important;
    border-bottom: 1px solid #f1f5f9 !important;
    pointer-events: none !important;
    user-select: none !important;
}
html.dark .am-user-header { border-color: rgba(255,255,255,.08) !important; }
.am-uh-avatar {
    width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--am-accent) 0%, rgba(var(--am-accent-rgb),.6) 100%);
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 800; color: #fff; overflow: hidden;
    border: 2px solid rgba(var(--am-accent-rgb),.25);
}
.am-uh-name {
    font-size: .82rem; font-weight: 700;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;
    /* inherit from Filament's dropdown text color */
}
.am-uh-role { font-size: .68rem; color: #9ca3af; margin-top: 1px; }

/* ══ MODAL OVERLAY ══ */
#am-overlay {
    position: fixed; inset: 0; z-index: 99999;
    display: flex; align-items: center; justify-content: center;
    background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
    padding: 1rem;
    opacity: 0; pointer-events: none;
    transition: opacity .2s ease;
}
#am-overlay.open { opacity: 1; pointer-events: all; }

/* ══ MODAL CARD ══ */
#am-card {
    position: relative; width: 100%; max-width: 440px;
    background: #fff; border: 1px solid #e5e7eb; border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,.14), 0 4px 16px rgba(0,0,0,.08);
    transform: translateY(16px) scale(.97);
    transition: transform .25s cubic-bezier(.34,1.56,.64,1);
    overflow: hidden; display: flex; flex-direction: column;
    max-height: 92vh;
}
html.dark #am-card {
    background: #1e293b; border-color: #334155;
    box-shadow: 0 20px 60px rgba(0,0,0,.45);
}
#am-overlay.open #am-card { transform: translateY(0) scale(1); }

/* Accent top bar */
#am-topbar {
    height: 4px; flex-shrink: 0;
    background: linear-gradient(90deg, var(--am-accent), rgba(var(--am-accent-rgb),.4), var(--am-accent));
    background-size: 200% auto; animation: amBar 3s linear infinite;
}
@keyframes amBar { 0%{background-position:0%} 100%{background-position:200%} }

/* ══ HEADER ══ */
#am-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: .75rem 1.25rem .7rem;
    border-bottom: 1px solid #f1f5f9; flex-shrink: 0;
}
html.dark #am-header { border-color: #334155; }
.am-head-left { display: flex; align-items: center; gap: .65rem; }
.am-logo { width: 32px; height: 32px; object-fit: contain; flex-shrink: 0; }
.am-head-tag { font-size: 10.5px; font-weight: 800; letter-spacing: .18em; text-transform: uppercase; color: var(--am-accent); line-height: 1; }
.am-head-title { display: none; }
.am-head-sub { display: none; }
.am-close {
    width: 30px; height: 30px; border-radius: 50%;
    background: #f1f5f9; border: 1px solid #e5e7eb;
    color: #6b7280; display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0; transition: all .15s;
}
html.dark .am-close { background: #334155; border-color: #475569; color: #94a3b8; }
.am-close:hover { background: #e5e7eb; color: #111827; }
html.dark .am-close:hover { background: #475569; color: #f1f5f9; }
.am-close svg { width: 14px; height: 14px; }

/* ══ TABS ══ */
#am-tabs { display: flex; border-bottom: 1px solid #f1f5f9; flex-shrink: 0; padding: 0 1.25rem; }
html.dark #am-tabs { border-color: #334155; }
.am-tab {
    flex: 1; padding: .6rem .5rem; font-size: .775rem; font-weight: 700;
    color: #9ca3af; cursor: pointer; border: none; background: transparent;
    border-bottom: 2px solid transparent; margin-bottom: -1px;
    display: flex; align-items: center; justify-content: center; gap: .35rem;
    transition: color .15s, border-color .15s;
}
.am-tab svg { width: 13px; height: 13px; flex-shrink: 0; }
.am-tab:hover { color: #374151; }
html.dark .am-tab:hover { color: #e2e8f0; }
.am-tab.active { color: var(--am-accent); border-bottom-color: var(--am-accent); }

/* ══ BODY ══ */
#am-body { overflow-y: auto; flex: 1; }
.am-panel { padding: 1rem 1.25rem; display: none; }
.am-panel.active { display: block; }

/* ══ AVATAR PREVIEW ══ */
.am-av-wrap { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
.am-av-circle {
    width: 64px; height: 64px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--am-accent) 0%, rgba(var(--am-accent-rgb),.65) 100%);
    border: 3px solid var(--am-accent-bd);
    box-shadow: 0 0 0 3px var(--am-accent-lt);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; font-weight: 800; color: #fff;
    overflow: hidden; transition: all .2s;
}
.am-av-circle img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
.am-av-circle svg { width: 28px; height: 28px; color: #fff; }
.am-av-info { flex: 1; min-width: 0; }
.am-av-name { font-size: .875rem; font-weight: 700; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
html.dark .am-av-name { color: #f1f5f9; }
.am-av-role { font-size: .72rem; color: #6b7280; margin-top: 1px; }

/* ══ SECTION LABELS ══ */
.am-lbl { font-size: 8.5px; font-weight: 800; letter-spacing: .22em; text-transform: uppercase; color: #9ca3af; margin-bottom: .45rem; display: block; }

/* ══ INITIALS ══ */
.am-init-row { display: flex; align-items: center; gap: .625rem; margin-bottom: .875rem; }
.am-init-inp {
    width: 58px; text-align: center; letter-spacing: .12em; text-transform: uppercase;
    background: #f9fafb; border: 1.5px solid #e5e7eb;
    border-radius: 8px; padding: .45rem .4rem;
    color: #111827; font-size: .9rem; font-weight: 800; outline: none;
    transition: border-color .15s;
}
html.dark .am-init-inp { background: #0f172a; border-color: #334155; color: #f1f5f9; }
.am-init-inp:focus { border-color: var(--am-accent); box-shadow: 0 0 0 2px var(--am-accent-lt); }
.am-init-hint { font-size: .72rem; color: #9ca3af; flex: 1; line-height: 1.4; }
html.dark .am-init-hint { color: #64748b; }

/* ══ ICON GRID ══ */
.am-icon-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: .4rem; margin-bottom: .875rem; }
.am-icon-btn {
    aspect-ratio: 1; border-radius: 8px;
    background: #f9fafb; border: 1.5px solid #e5e7eb;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .15s; padding: 0;
}
html.dark .am-icon-btn { background: #0f172a; border-color: #334155; }
.am-icon-btn:hover { border-color: var(--am-accent); background: var(--am-accent-lt); }
.am-icon-btn.selected { border-color: var(--am-accent); background: var(--am-accent-lt); box-shadow: 0 0 0 2px var(--am-accent-bd); }
.am-icon-btn svg { width: 17px; height: 17px; color: #6b7280; }
html.dark .am-icon-btn svg { color: #94a3b8; }
.am-icon-btn:hover svg, .am-icon-btn.selected svg { color: var(--am-accent); }
.am-icon-btn.am-icon-ab { font-size: .5rem; font-weight: 800; color: #9ca3af; line-height: 1.1; letter-spacing: .03em; }
html.dark .am-icon-btn.am-icon-ab { color: #64748b; }
.am-icon-btn.am-icon-ab.selected { color: var(--am-accent); }

/* ══ DIVIDER ══ */
.am-div { height: 1px; background: #f1f5f9; margin: .75rem 0; }
html.dark .am-div { background: #334155; }

/* ══ UPLOAD ZONE ══ */
.am-upload {
    border: 1.5px dashed #d1d5db; border-radius: 10px; padding: .75rem;
    text-align: center; cursor: pointer; transition: all .18s; background: #fafafa;
}
html.dark .am-upload { border-color: #334155; background: #0f172a; }
.am-upload:hover { border-color: var(--am-accent); background: var(--am-accent-lt); }
.am-upload svg { width: 22px; height: 22px; color: #d1d5db; margin: 0 auto 4px; display: block; }
html.dark .am-upload svg { color: #475569; }
.am-upload:hover svg { color: var(--am-accent); }
.am-upload p { font-size: .72rem; color: #9ca3af; margin: 0; }
.am-upload strong { color: #374151; }
html.dark .am-upload strong { color: #e2e8f0; }

/* ══ FORM FIELDS ══ */
.am-field { margin-bottom: .75rem; }
.am-field-lbl { display: block; font-size: 8.5px; font-weight: 800; letter-spacing: .2em; text-transform: uppercase; color: #9ca3af; margin-bottom: .35rem; }
.am-field-wrap {
    display: flex; align-items: center; gap: .5rem;
    background: #f9fafb; border: 1.5px solid #e5e7eb;
    border-radius: 8px; padding: .4rem .65rem;
    transition: border-color .15s, box-shadow .15s;
}
html.dark .am-field-wrap { background: #0f172a; border-color: #334155; }
.am-field-wrap:focus-within { border-color: var(--am-accent); box-shadow: 0 0 0 3px var(--am-accent-lt); }
.am-field-wrap.err { border-color: #ef4444; }
.am-field-ico { color: #d1d5db; flex-shrink: 0; width: 14px; height: 14px; }
html.dark .am-field-ico { color: #475569; }
.am-field-inp { background: transparent; border: none; outline: none !important; box-shadow: none !important; color: #111827; font-size: .78rem; font-weight: 500; flex: 1; font-family: inherit; -webkit-appearance: none; }
html.dark .am-field-inp { color: #f1f5f9; }
.am-field-inp::placeholder { color: #d1d5db; }
html.dark .am-field-inp::placeholder { color: #475569; }
.am-field-eye { background: none; border: none; cursor: pointer; padding: 0; color: #d1d5db; transition: color .15s; display: flex; align-items: center; }
html.dark .am-field-eye { color: #475569; }
.am-field-eye:hover { color: #6b7280; }
.am-field-eye svg { width: 14px; height: 14px; }
.am-field-err { font-size: .7rem; color: #ef4444; margin-top: .25rem; display: flex; align-items: center; gap: .25rem; }
.am-field-err svg { width: 11px; height: 11px; flex-shrink: 0; }

/* ══ FOOTER ══ */
#am-footer {
    display: flex; align-items: center; justify-content: flex-end; gap: .5rem;
    padding: .75rem 1.25rem;
    border-top: 1px solid #f1f5f9; flex-shrink: 0; background: #fafafa;
}
html.dark #am-footer { border-color: #334155; background: #0f172a; }
.am-btn-cancel {
    padding: .5rem 1rem; border-radius: 8px;
    background: #fff; border: 1.5px solid #e5e7eb;
    color: #6b7280; font-size: .78rem; font-weight: 600;
    cursor: pointer; transition: all .15s;
}
html.dark .am-btn-cancel { background: #1e293b; border-color: #334155; color: #94a3b8; }
.am-btn-cancel:hover { border-color: #9ca3af; color: #374151; }
html.dark .am-btn-cancel:hover { color: #e2e8f0; }
.am-btn-save {
    padding: .5rem 1.1rem; border-radius: 8px;
    background: var(--am-accent); border: none; color: #fff;
    font-size: .78rem; font-weight: 700; cursor: pointer;
    letter-spacing: .02em;
    box-shadow: 0 2px 8px rgba(var(--am-accent-rgb),.3);
    transition: opacity .15s, transform .1s;
    display: flex; align-items: center; gap: .35rem;
}
.am-btn-save:hover { opacity: .88; transform: translateY(-1px); }
.am-btn-save:active { transform: translateY(0); }
.am-btn-save svg { width: 13px; height: 13px; }

/* ══ REMOVE PHOTO ══ */
.am-remove-photo {
    width: 100%; padding: .45rem; border-radius: 7px;
    background: #fef2f2; border: 1.5px solid #fecaca;
    color: #ef4444; font-size: .72rem; font-weight: 600;
    cursor: pointer; margin-top: .5rem; transition: all .15s;
}
html.dark .am-remove-photo { background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.25); }
.am-remove-photo:hover { background: #fee2e2; }

/* ══ TOAST ══ */
#am-toast {
    position: fixed; bottom: 1.25rem; right: 1.25rem; z-index: 999999;
    background: #fff; border: 1px solid #e5e7eb;
    border-left: 3px solid var(--am-accent);
    border-radius: 10px; padding: .75rem 1rem;
    display: flex; align-items: center; gap: .625rem;
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
    font-size: .8rem; color: #374151; font-weight: 600;
    transform: translateY(6px); opacity: 0;
    transition: all .28s cubic-bezier(.34,1.56,.64,1);
    pointer-events: none; min-width: 200px;
}
html.dark #am-toast { background: #1e293b; border-color: #334155; color: #e2e8f0; }
#am-toast.show { transform: translateY(0); opacity: 1; pointer-events: all; }
#am-toast svg { width: 15px; height: 15px; color: var(--am-accent); flex-shrink: 0; }

/* ══ LOGOUT MODAL ══ */
#am-logout-overlay {
    position: fixed; inset: 0; z-index: 999998;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,.5); padding: 1rem;
    opacity: 0; pointer-events: none; transition: opacity .18s;
}
#am-logout-overlay.open { opacity: 1; pointer-events: all; }
#am-logout-card {
    background: #fff; border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,.18);
    width: 100%; max-width: 26rem; overflow: hidden;
    transform: scale(.96); transition: transform .22s cubic-bezier(.34,1.56,.64,1);
    position: relative;
}
html.dark #am-logout-card { background: #1e293b; }
#am-logout-overlay.open #am-logout-card { transform: scale(1); }
.am-lo-close {
    position: absolute; top: .75rem; right: .75rem;
    padding: .25rem; border-radius: .375rem; color: #9ca3af;
    cursor: pointer; background: transparent; border: none; line-height: 1;
    transition: background .15s, color .15s;
}
.am-lo-close:hover { background: #f3f4f6; color: #374151; }
html.dark .am-lo-close:hover { background: #334155; color: #e2e8f0; }
.am-lo-header { padding: 1.75rem 1.5rem 1rem; text-align: center; border-bottom: 1px solid #f3f4f6; }
html.dark .am-lo-header { border-color: #334155; }
.am-lo-icon { width: 48px; height: 48px; border-radius: 50%; background: #fee2e2; border: 2px solid #fecaca; display: flex; align-items: center; justify-content: center; margin: 0 auto .875rem; }
.am-lo-icon svg { width: 22px; height: 22px; color: #dc2626; }
.am-lo-title { font-size: 1rem; font-weight: 700; color: #111827; margin: 0 0 .25rem; }
html.dark .am-lo-title { color: #f1f5f9; }
.am-lo-sub { font-size: .8125rem; color: #6b7280; margin: 0; line-height: 1.5; }
html.dark .am-lo-sub { color: #94a3b8; }
.am-lo-footer { display: flex; gap: .625rem; padding: 1rem 1.5rem; border-top: 1px solid #f3f4f6; background: #f9fafb; align-items: center; }
html.dark .am-lo-footer { border-color: #334155; background: #0f172a; }
.am-lo-btn-stay { flex: 1; padding: .6rem; border-radius: 8px; background: #fff; border: 1.5px solid #d1d5db; color: #374151; font-size: .82rem; font-weight: 600; cursor: pointer; transition: all .15s; text-align: center; }
html.dark .am-lo-btn-stay { background: #1e293b; border-color: #334155; color: #d1d5db; }
.am-lo-btn-stay:hover { background: #f9fafb; }
html.dark .am-lo-btn-stay:hover { background: #334155; }
.am-lo-btn-out { flex: 1; padding: .6rem; border-radius: 8px; background: #dc2626; border: none; color: #fff; font-size: .82rem; font-weight: 700; cursor: pointer; transition: opacity .15s; text-align: center; width: 100%; }
.am-lo-btn-out:hover { opacity: .88; }

@media (max-width: 480px) {
    #am-card { border-radius: 12px; max-height: 95vh; }
    .am-icon-grid { grid-template-columns: repeat(4, 1fr); }
}
</style>

{{-- ══ MY ACCOUNT MODAL ══ --}}
<div id="am-overlay" onclick="amOverlayClick(event)">
    <div id="am-card">
        <div id="am-topbar"></div>

        <div id="am-header">
            <div class="am-head-left">
                <img src="{{ asset('images/lumc-logo.png') }}" class="am-logo"
                     onerror="this.style.display='none'" alt="LUMC">
                <div class="am-head-info">
                    <div class="am-head-tag">La Union Medical Center</div>
                </div>
            </div>
            <button class="am-close" onclick="amClose()" aria-label="Close">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div id="am-tabs">
            <button class="am-tab active" id="am-tab-appearance" onclick="amTab('appearance')">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42"/>
                </svg>
                Appearance
            </button>
            <button class="am-tab" id="am-tab-security" onclick="amTab('security')">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                </svg>
                Security
            </button>
        </div>

        <div id="am-body">

            {{-- ── APPEARANCE TAB ── --}}
            <div class="am-panel active" id="am-panel-appearance">

                <div class="am-av-wrap">
                    <div class="am-av-circle" id="am-av-circle">
                        @if($isPhoto)
                            <img src="{{ $avatarData }}" alt="avatar">
                        @elseif($isIcon && isset($icons[$iconKey]))
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$iconKey]['path'] }}"/>
                            </svg>
                        @else
                            <span>{{ $displayInit }}</span>
                        @endif
                    </div>
                    <div class="am-av-info">
                        <div class="am-av-name">{{ $fullName }}</div>
                        <div class="am-av-role">{{ ucfirst($panelId) }} &middot; Customise your avatar below</div>
                    </div>
                </div>

                <span class="am-lbl">Custom Initials</span>
                <div class="am-init-row">
                    <input type="text" id="am-init-inp" class="am-init-inp"
                           maxlength="2" placeholder="AB"
                           value="{{ $customInit ?? '' }}"
                           oninput="amInitChange(this.value)">
                    <span class="am-init-hint">
                        Up to 2 letters. Blank uses your name's default
                        <strong style="color:#374151;">({{ $defaultInit }})</strong>.
                    </span>
                </div>

                <div class="am-div"></div>

                <span class="am-lbl">Choose an Icon</span>
                <div class="am-icon-grid">
                    @foreach($icons as $key => $icon)
                    <button type="button"
                            class="am-icon-btn {{ ($isIcon && $iconKey === $key) ? 'selected' : '' }}"
                            title="{{ $icon['label'] }}"
                            data-icon-key="{{ $key }}"
                            onclick="amSelectIcon('{{ $key }}')">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon['path'] }}"/>
                        </svg>
                    </button>
                    @endforeach
                    <button type="button"
                            class="am-icon-btn am-icon-ab {{ (!$isIcon) ? 'selected' : '' }}"
                            title="Use Initials"
                            onclick="amClearIcon()">A<br>B</button>
                </div>

                <div class="am-div"></div>

                <span class="am-lbl">Upload Photo</span>
                <div class="am-upload"
                     onclick="document.getElementById('am-file-inp').click()"
                     ondragover="event.preventDefault()"
                     ondrop="amDrop(event)">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                    <p><strong>Click to browse</strong> or drag &amp; drop</p>
                    <p>JPG, PNG &mdash; cropped to circle</p>
                </div>
                <input type="file" id="am-file-inp" accept="image/*" style="display:none"
                       onchange="amHandleFile(event)">

                <div id="am-remove-wrap" style="{{ $isPhoto ? '' : 'display:none' }}">
                    <button type="button" class="am-remove-photo" onclick="amRemovePhoto()">
                        Remove current photo
                    </button>
                </div>

                <form id="am-appearance-form" method="POST" action="{{ route('avatar.save') }}" style="display:none;">
                    @csrf
                    <input type="hidden" name="avatar"          id="am-av-val"   value="{{ $avatarData ?? '' }}">
                    <input type="hidden" name="avatar_initials" id="am-init-val" value="{{ $customInit ?? '' }}">
                </form>

            </div>{{-- /appearance --}}

            {{-- ── SECURITY TAB ── --}}
            <div class="am-panel" id="am-panel-security">
                <form id="am-pwd-form" method="POST" action="{{ route('account.change.password') }}">
                    @csrf

                    <div class="am-field">
                        <label class="am-field-lbl">Current Password</label>
                        <div class="am-field-wrap {{ $errors->has('current_password') ? 'err' : '' }}">
                            <svg class="am-field-ico" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                            <input type="password" name="current_password" id="am-p1"
                                   class="am-field-inp" placeholder="Your current password"
                                   autocomplete="current-password">
                            <button type="button" class="am-field-eye" onclick="amTogglePwd('am-p1','am-p1i')">
                                <svg id="am-p1i" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                        <div class="am-field-err">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="am-field">
                        <label class="am-field-lbl">New Password <span style="font-weight:400;opacity:.6;">(min 8 chars)</span></label>
                        <div class="am-field-wrap {{ $errors->has('new_password') ? 'err' : '' }}">
                            <svg class="am-field-ico" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z"/>
                            </svg>
                            <input type="password" name="new_password" id="am-p2"
                                   class="am-field-inp" placeholder="Choose a strong password"
                                   autocomplete="new-password">
                            <button type="button" class="am-field-eye" onclick="amTogglePwd('am-p2','am-p2i')">
                                <svg id="am-p2i" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('new_password')
                        <div class="am-field-err">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="am-field">
                        <label class="am-field-lbl">Confirm New Password</label>
                        <div class="am-field-wrap {{ $errors->has('confirm_password') ? 'err' : '' }}">
                            <svg class="am-field-ico" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                            </svg>
                            <input type="password" name="confirm_password" id="am-p3"
                                   class="am-field-inp" placeholder="Re-enter new password"
                                   autocomplete="new-password">
                            <button type="button" class="am-field-eye" onclick="amTogglePwd('am-p3','am-p3i')">
                                <svg id="am-p3i" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('confirm_password')
                        <div class="am-field-err">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </form>
            </div>{{-- /security --}}

        </div>{{-- /am-body --}}

        <div id="am-footer">
            <button type="button" class="am-btn-cancel" onclick="amClose()">Cancel</button>
            <button type="button" class="am-btn-save" id="am-save-btn" onclick="amSave()">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span id="am-save-label">Save Changes</span>
            </button>
        </div>

    </div>{{-- /am-card --}}
</div>{{-- /am-overlay --}}

{{-- ══ LOGOUT MODAL ══ --}}
<div id="am-logout-overlay" onclick="if(event.target===this)amLoCancel()">
    <div id="am-logout-card">
        <button class="am-lo-close" onclick="amLoCancel()" title="Close">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:1.1rem;height:1.1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="am-lo-header">
            <div class="am-lo-icon">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                </svg>
            </div>
            <p class="am-lo-title">Sign out of LUMC?</p>
            <p class="am-lo-sub">You'll be returned to the login page.<br>Any unsaved work may be lost.</p>
        </div>
        <div class="am-lo-footer">
            <button class="am-lo-btn-stay" onclick="amLoCancel()">Stay</button>
            <form id="am-logout-form" method="POST" action="{{ route('logout') }}" style="flex:1;">
                @csrf
                <button type="submit" class="am-lo-btn-out">Yes, Sign Out</button>
            </form>
        </div>
    </div>
</div>

{{-- ══ TOAST ══ --}}
<div id="am-toast">
    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
    </svg>
    <span id="am-toast-msg">Saved successfully.</span>
</div>

{{-- ══ JAVASCRIPT ══ --}}
<script>
(function () {
    /* ─── STATE ─── */
    let amMode    = @js($isPhoto ? 'photo' : ($isIcon ? 'icon' : 'initials'));
    let amIconKey = @js($isIcon ? $iconKey : null);
    let amPhoto   = @js($isPhoto ? $avatarData : null);
    let amCurTab  = 'appearance';

    const AM_FULL_NAME    = @js($fullName);
    const AM_PANEL_LABEL  = @js(ucfirst($panelId));
    const AM_DEFAULT_INIT = @js($defaultInit);
    const AM_DISPLAY_INIT = @js($displayInit);

    /* ─── OPEN / CLOSE ─── */
    window.amOpen = function (tab) {
        tab = tab || 'appearance';
        amTab(tab);
        document.getElementById('am-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.amClose = function () {
        document.getElementById('am-overlay').classList.remove('open');
        document.body.style.overflow = '';
    };
    window.amOverlayClick = function (e) {
        if (e.target === document.getElementById('am-overlay')) amClose();
    };

    /* ─── TABS ─── */
    window.amTab = function (tab) {
        amCurTab = tab;
        document.querySelectorAll('.am-tab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.am-panel').forEach(p => p.classList.remove('active'));
        const t = document.getElementById('am-tab-' + tab);
        const p = document.getElementById('am-panel-' + tab);
        if (t) t.classList.add('active');
        if (p) p.classList.add('active');
        const lbl = document.getElementById('am-save-label');
        if (lbl) lbl.textContent = tab === 'security' ? 'Change Password' : 'Save Changes';
    };

    /* ─── SAVE DISPATCHER ─── */
    window.amSave = function () {
        if (amCurTab === 'security') {
            document.getElementById('am-pwd-form').submit();
        } else {
            amSyncHidden();
            document.getElementById('am-appearance-form').submit();
        }
    };

    /* ─── AVATAR PREVIEW (modal circle) ─── */
    function amRefresh () {
        const circle = document.getElementById('am-av-circle');
        if (!circle) return;
        circle.innerHTML = '';

        if (amMode === 'photo' && amPhoto) {
            const img = document.createElement('img');
            img.src = amPhoto; img.alt = 'avatar';
            circle.appendChild(img);
        } else if (amMode === 'icon' && amIconKey) {
            const btn = document.querySelector(`.am-icon-btn[data-icon-key="${amIconKey}"]`);
            if (btn) {
                const svg = btn.querySelector('svg').cloneNode(true);
                svg.style.cssText = 'width:28px;height:28px;color:#fff;flex-shrink:0;';
                circle.appendChild(svg);
            }
        } else {
            const raw = (document.getElementById('am-init-inp')?.value || '').trim().toUpperCase();
            const span = document.createElement('span');
            span.textContent = raw || AM_DEFAULT_INIT;
            circle.appendChild(span);
        }

        amUpdateTopbar();
        amSyncHidden();
    }

    /* ─── UPDATE TOPBAR AVATAR BUTTON ─── */
    function amUpdateTopbar () {
        const topImg = document.querySelector('img.fi-user-avatar');
        if (!topImg) return;
        const topBtn = topImg.closest('button');
        if (!topBtn) return;

        /* Apply accent ring styling */
        Object.assign(topBtn.style, {
            background:   `linear-gradient(135deg, var(--am-accent) 0%, rgba(var(--am-accent-rgb),.7) 100%)`,
            border:       `2px solid rgba(var(--am-accent-rgb),.3)`,
            boxShadow:    `0 0 0 3px rgba(var(--am-accent-rgb),.12), 0 2px 6px rgba(var(--am-accent-rgb),.2)`,
            borderRadius: '9999px',
            overflow:     'hidden',
            display:      'flex',
            alignItems:   'center',
            justifyContent: 'center',
            flexShrink:   '0',
            width:        '2rem',
            height:       '2rem',
            cursor:       'pointer',
            transition:   'box-shadow .2s, transform .18s',
        });

        if (amMode === 'photo' && amPhoto) {
            topBtn.innerHTML = `<img src="${amPhoto}" style="width:100%;height:100%;object-fit:cover;border-radius:9999px;" alt="avatar">`;
        } else if (amMode === 'icon' && amIconKey) {
            const iconBtn = document.querySelector(`.am-icon-btn[data-icon-key="${amIconKey}"]`);
            if (iconBtn) {
                const svg = iconBtn.querySelector('svg').cloneNode(true);
                svg.style.cssText = 'width:58%;height:58%;color:#fff;display:block;margin:auto;pointer-events:none;flex-shrink:0;';
                topBtn.innerHTML = '';
                topBtn.appendChild(svg);
            }
        } else {
            const raw = (document.getElementById('am-init-inp')?.value || '').trim().toUpperCase();
            topBtn.innerHTML = `<span style="font-size:.65rem;font-weight:800;color:#fff;pointer-events:none;line-height:1;letter-spacing:.04em;">${raw || AM_DISPLAY_INIT}</span>`;
        }
    }

    /* ─── SYNC HIDDEN FORM INPUTS ─── */
    function amSyncHidden () {
        const avVal   = document.getElementById('am-av-val');
        const initVal = document.getElementById('am-init-val');
        const initInp = document.getElementById('am-init-inp');
        const initStr = (initInp?.value || '').trim().toUpperCase();

        if (amMode === 'photo') {
            if (avVal)   avVal.value   = amPhoto || '';
            if (initVal) initVal.value = '';
        } else if (amMode === 'icon') {
            if (avVal)   avVal.value   = amIconKey ? 'icon:' + amIconKey : '';
            if (initVal) initVal.value = initStr;
        } else {
            if (avVal)   avVal.value   = '';
            if (initVal) initVal.value = initStr;
        }
    }

    /* ─── INITIALS ─── */
    window.amInitChange = function (val) {
        if (amMode !== 'photo') amMode = 'initials';
        document.querySelectorAll('.am-icon-btn').forEach(b => b.classList.remove('selected'));
        document.querySelector('.am-icon-ab')?.classList.add('selected');
        amRefresh();
    };

    /* ─── ICON ─── */
    window.amSelectIcon = function (key) {
        amMode = 'icon'; amIconKey = key; amPhoto = null;
        document.querySelectorAll('.am-icon-btn').forEach(b => b.classList.remove('selected'));
        document.querySelector(`.am-icon-btn[data-icon-key="${key}"]`)?.classList.add('selected');
        const rw = document.getElementById('am-remove-wrap');
        if (rw) rw.style.display = 'none';
        amRefresh();
    };
    window.amClearIcon = function () {
        amMode = 'initials'; amIconKey = null; amPhoto = null;
        document.querySelectorAll('.am-icon-btn').forEach(b => b.classList.remove('selected'));
        document.querySelector('.am-icon-ab')?.classList.add('selected');
        const rw = document.getElementById('am-remove-wrap');
        if (rw) rw.style.display = 'none';
        amRefresh();
    };

    /* ─── PHOTO ─── */
    window.amHandleFile = function (e) {
        const f = e.target.files[0];
        if (f) amProcessPhoto(f);
        e.target.value = '';
    };
    window.amDrop = function (e) {
        e.preventDefault();
        const f = e.dataTransfer.files[0];
        if (f && f.type.startsWith('image/')) amProcessPhoto(f);
    };
    function amProcessPhoto (file) {
        const reader = new FileReader();
        reader.onload = ev => {
            const img = new Image();
            img.onload = () => {
                const size = 200, c = document.createElement('canvas');
                c.width = c.height = size;
                const ctx = c.getContext('2d');
                ctx.beginPath(); ctx.arc(size/2, size/2, size/2, 0, Math.PI*2); ctx.clip();
                const dim = Math.min(img.width, img.height);
                ctx.drawImage(img, (img.width-dim)/2, (img.height-dim)/2, dim, dim, 0, 0, size, size);
                amPhoto = c.toDataURL('image/png');
                amMode  = 'photo';
                document.querySelectorAll('.am-icon-btn').forEach(b => b.classList.remove('selected'));
                const rw = document.getElementById('am-remove-wrap');
                if (rw) rw.style.display = '';
                amRefresh();
            };
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
    window.amRemovePhoto = function () {
        amPhoto = null; amMode = 'initials';
        const rw = document.getElementById('am-remove-wrap');
        if (rw) rw.style.display = 'none';
        document.querySelectorAll('.am-icon-btn').forEach(b => b.classList.remove('selected'));
        document.querySelector('.am-icon-ab')?.classList.add('selected');
        amRefresh();
    };

    /* ─── PASSWORD TOGGLE ─── */
    window.amTogglePwd = function (inputId, iconId) {
        const el = document.getElementById(inputId);
        el.type = el.type === 'password' ? 'text' : 'password';
        const icon = document.getElementById(iconId);
        if (el.type === 'text') {
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>`;
        } else {
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>`;
        }
    };

    /* ─── LOGOUT ─── */
    window.amLoShow = function () {
        document.getElementById('am-logout-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.amLoCancel = function () {
        document.getElementById('am-logout-overlay').classList.remove('open');
        document.body.style.overflow = '';
    };

    /* ─── TOAST ─── */
    window.amToast = function (msg) {
        const t = document.getElementById('am-toast');
        document.getElementById('am-toast-msg').textContent = msg || 'Saved successfully.';
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3500);
    };

    /* ─── INJECT USER NAME HEADER INTO FILAMENT DROPDOWN ─── */
    function amBuildUserHeader () {
        const wrap = document.createElement('div');
        wrap.className = 'am-user-header';

        // Mini avatar
        const av = document.createElement('div');
        av.className = 'am-uh-avatar';
        if (amMode === 'photo' && amPhoto) {
            av.innerHTML = `<img src="${amPhoto}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
        } else if (amMode === 'icon' && amIconKey) {
            const iconBtn = document.querySelector(`.am-icon-btn[data-icon-key="${amIconKey}"]`);
            if (iconBtn) {
                const svg = iconBtn.querySelector('svg').cloneNode(true);
                svg.style.cssText = 'width:60%;height:60%;color:#fff;';
                av.appendChild(svg);
            } else {
                av.textContent = AM_DISPLAY_INIT;
            }
        } else {
            const raw = (document.getElementById('am-init-inp')?.value || '').trim().toUpperCase();
            av.textContent = raw || AM_DISPLAY_INIT;
        }

        const info = document.createElement('div');
        info.innerHTML = `<div class="am-uh-name">${AM_FULL_NAME}</div><div class="am-uh-role">${AM_PANEL_LABEL}</div>`;

        wrap.appendChild(av);
        wrap.appendChild(info);
        return wrap;
    }

    function amPatchDropdown (panel) {
        // ① Inject name header (once)
        if (!panel.querySelector('.am-user-header')) {
            panel.prepend(amBuildUserHeader());
        }

        // ② Patch My Account link
        panel.querySelectorAll('a[href="#account"]').forEach(link => {
            if (link._amPatched) return;
            link._amPatched = true;
            link.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                // Dismiss dropdown via a synthetic click outside, then open modal
                setTimeout(() => amOpen('appearance'), 80);
            }, true);
        });

        // ③ Style + patch logout buttons
        panel.querySelectorAll('button, a').forEach(el => {
            if (el._amLogoutPatched) return;
            const txt = (el.textContent || '').trim().toLowerCase();
            const href = el.getAttribute('href') || '';
            const form = el.closest('form');
            const action = form ? (form.getAttribute('action') || '') : '';
            const isLogout = txt === 'log out' || txt === 'sign out'
                          || href.includes('logout') || action.includes('logout');
            if (!isLogout) return;
            el._amLogoutPatched = true;
            // Apply red styling
            el.style.color = '#ef4444';
            el.querySelectorAll('svg').forEach(s => { s.style.color = '#ef4444'; });
            // Intercept click
            el.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                amLoShow();
                return false;
            }, true);
        });

        // ④ Also intercept form submit (belt-and-suspenders)
        panel.querySelectorAll('form').forEach(form => {
            if (form._amPatched) return;
            const action = form.getAttribute('action') || '';
            if (!action.includes('logout')) return;
            form._amPatched = true;
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                amLoShow();
                return false;
            }, true);
        });
    }

    /* ─── MUTATION OBSERVER — watches for dropdown opening ─── */
    function amStartObserver () {
        const observer = new MutationObserver(function () {
            // Filament v3 dropdown panel (Alpine x-show toggle)
            document.querySelectorAll(
                '.fi-user-menu .fi-dropdown-panel, .fi-dropdown-panel'
            ).forEach(panel => {
                const cs = window.getComputedStyle(panel);
                if (cs.display === 'none' || panel.hidden) return;
                amPatchDropdown(panel);
            });
        });
        observer.observe(document.body, {
            childList: true, subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });
    }

    /* ─── GLOBAL CAPTURE-PHASE FALLBACKS ─── */
    // My Account — catches any click even if MutationObserver missed it
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href="#account"]');
        if (link && !link.closest('#am-overlay')) {
            e.preventDefault();
            e.stopImmediatePropagation();
            setTimeout(() => amOpen('appearance'), 80);
            return false;
        }
    }, true);

    // Logout — form submit (most reliable)
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!form) return;
        const action = form.getAttribute('action') || '';
        if (action.includes('logout') && !form.closest('#am-logout-overlay')) {
            e.preventDefault();
            e.stopImmediatePropagation();
            amLoShow();
            return false;
        }
    }, true);

    // Logout — button/link click
    document.addEventListener('click', function (e) {
        if (e.target.closest('#am-logout-overlay') || e.target.closest('#am-overlay')) return;
        const el = e.target.closest('button, a');
        if (!el) return;
        const txt    = (el.textContent || '').trim().toLowerCase();
        const href   = el.getAttribute('href') || '';
        const form   = el.closest('form');
        const action = form ? (form.getAttribute('action') || '') : '';
        if (txt === 'log out' || txt === 'sign out'
            || href.includes('logout') || action.includes('logout')) {
            e.preventDefault();
            e.stopImmediatePropagation();
            amLoShow();
            return false;
        }
    }, true);

    /* ─── ESCAPE KEY ─── */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { amClose(); amLoCancel(); }
    });

    /* ─── INITIALISE ─── */
    function amBootstrap () {
        amUpdateTopbar();
        amStartObserver();

        @if($errors->hasAny(['current_password','new_password','confirm_password']) || session('open_security_tab'))
        setTimeout(() => amOpen('security'), 150);
        @endif

        @if(session('avatar_success'))
        amToast(@js(session('avatar_success')));
        @endif
    }

    // Run after DOM + Alpine are ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => setTimeout(amBootstrap, 150));
    } else {
        setTimeout(amBootstrap, 150);
    }
    // Extra safety: re-run after Alpine initialises
    document.addEventListener('alpine:initialized', () => { amUpdateTopbar(); amStartObserver(); });

})();
</script>