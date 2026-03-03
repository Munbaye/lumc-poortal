<x-filament-panels::page>

{{-- Match the login modal style exactly --}}
<style>
    body, .fi-body { background: linear-gradient(140deg, rgba(8,20,65,.98) 0%, rgba(20,50,130,.95) 55%, rgba(10,28,80,.97) 100%) !important; min-height: 100vh; }
    .fi-main, .fi-main-ctn, .fi-page { background: transparent !important; }
    .fi-sidebar { background: rgba(6,14,46,.9) !important; border-right: 1px solid rgba(255,255,255,.08) !important; }
    .fi-topbar { background: rgba(6,14,46,.85) !important; border-bottom: 1px solid rgba(255,255,255,.08) !important; }
    @keyframes stripeScan { 0%{background-position:0% center;} 100%{background-position:200% center;} }
    @keyframes orbPulse { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.6;transform:scale(1.1);} }
    @keyframes modalIn { from{opacity:0;transform:translateY(24px) scale(.97);} to{opacity:1;transform:translateY(0) scale(1);} }
</style>

<div style="display:flex;align-items:center;justify-content:center;min-height:70vh;padding:24px;">
<div style="position:relative;width:100%;max-width:420px;
            background:rgba(255,255,255,.055);
            border:1.5px solid rgba(255,255,255,.12);
            backdrop-filter:blur(32px);
            border-radius:24px;overflow:hidden;
            animation:modalIn .38s cubic-bezier(.34,1.56,.64,1);
            box-shadow:0 32px 80px rgba(0,0,0,.55),0 0 0 1px rgba(255,255,255,.06),inset 0 1px 0 rgba(255,255,255,.08);">

    {{-- Orbs --}}
    <div style="position:absolute;width:200px;height:200px;border-radius:50%;
                background:rgba(29,78,216,.18);filter:blur(40px);
                right:-60px;bottom:-40px;pointer-events:none;
                animation:orbPulse 8s ease-in-out infinite;"></div>
    <div style="position:absolute;width:150px;height:150px;border-radius:50%;
                background:rgba(220,38,38,.12);filter:blur(35px);
                left:-40px;top:-30px;pointer-events:none;
                animation:orbPulse 10s 2s ease-in-out infinite;"></div>

    {{-- Top stripe --}}
    <div style="height:3px;background:linear-gradient(90deg,#1d4ed8,#3b82f6,#dc2626,#3b82f6,#1d4ed8);
                background-size:200% auto;animation:stripeScan 4s linear infinite;"></div>

    <div style="position:relative;z-index:1;padding:32px 36px 30px;">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:14px;">
                <img src="{{ asset('images/lumc-logo.png') }}"
                     style="width:48px;height:48px;object-fit:contain;filter:drop-shadow(0 4px 12px rgba(0,0,0,.5));"
                     onerror="this.style.display='none'">
                <div>
                    <div style="font-size:9px;font-weight:800;letter-spacing:.28em;text-transform:uppercase;color:#facc15;margin-bottom:3px;">
                        La Union Medical Center
                    </div>
                    <div style="font-size:1.15rem;font-weight:900;color:#fff;line-height:1;">
                        Set New Password
                    </div>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div style="height:1px;background:rgba(255,255,255,.08);margin-bottom:22px;"></div>

        @if(auth()->user()->force_password_change)
        <div style="background:rgba(127,29,29,.5);border:1px solid rgba(185,28,28,.5);
                    color:#fca5a5;border-radius:12px;padding:11px 15px;margin-bottom:18px;
                    display:flex;align-items:center;gap:9px;font-size:.84rem;font-weight:600;">
            <i class="fas fa-lock" style="flex-shrink:0;font-size:.8rem;"></i>
            You must change your temporary password before continuing.
        </div>
        @endif

        {{-- Current Password --}}
        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:9.5px;font-weight:800;
                          color:rgba(255,255,255,.35);letter-spacing:.25em;
                          text-transform:uppercase;margin-bottom:8px;">
                Current / Temporary Password
            </label>
            <div style="display:flex;align-items:center;gap:12px;
                        background:rgba(255,255,255,.07);
                        border:1.5px solid {{ $errors->has('currentPassword') ? 'rgba(239,68,68,.7)' : 'rgba(255,255,255,.12)' }};
                        border-radius:13px;padding:14px 16px;
                        transition:border-color .2s;">
                <i class="fas fa-lock" style="color:rgba(255,255,255,.22);font-size:.88rem;flex-shrink:0;"></i>
                <input type="password" wire:model="currentPassword"
                       placeholder="Your temporary password"
                       style="background:transparent;border:none;outline:none;
                              color:#fff;font-size:.9rem;font-weight:500;width:100%;
                              font-family:'DM Sans',sans-serif;"
                       id="curPwd">
                <button type="button" onclick="toggleField('curPwd','curPwdIc')"
                        style="background:none;border:none;padding:0;cursor:pointer;
                               color:rgba(255,255,255,.22);font-size:.88rem;">
                    <i id="curPwdIc" class="fas fa-eye"></i>
                </button>
            </div>
            @error('currentPassword')
                <p style="color:#f87171;font-size:.73rem;margin-top:6px;font-weight:600;">
                    ⚠️ {{ $message }}
                </p>
            @enderror
        </div>

        {{-- New Password --}}
        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:9.5px;font-weight:800;
                          color:rgba(255,255,255,.35);letter-spacing:.25em;
                          text-transform:uppercase;margin-bottom:8px;">
                New Password <span style="opacity:.5;font-weight:400;">(min 8 chars)</span>
            </label>
            <div style="display:flex;align-items:center;gap:12px;
                        background:rgba(255,255,255,.07);
                        border:1.5px solid {{ $errors->has('newPassword') ? 'rgba(239,68,68,.7)' : 'rgba(255,255,255,.12)' }};
                        border-radius:13px;padding:14px 16px;">
                <i class="fas fa-key" style="color:rgba(255,255,255,.22);font-size:.88rem;flex-shrink:0;"></i>
                <input type="password" wire:model="newPassword"
                       placeholder="Choose a strong password"
                       style="background:transparent;border:none;outline:none;
                              color:#fff;font-size:.9rem;font-weight:500;width:100%;
                              font-family:'DM Sans',sans-serif;"
                       id="newPwd">
                <button type="button" onclick="toggleField('newPwd','newPwdIc')"
                        style="background:none;border:none;padding:0;cursor:pointer;
                               color:rgba(255,255,255,.22);font-size:.88rem;">
                    <i id="newPwdIc" class="fas fa-eye"></i>
                </button>
            </div>
            @error('newPassword')
                <p style="color:#f87171;font-size:.73rem;margin-top:6px;font-weight:600;">
                    ⚠️ {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div style="margin-bottom:22px;">
            <label style="display:block;font-size:9.5px;font-weight:800;
                          color:rgba(255,255,255,.35);letter-spacing:.25em;
                          text-transform:uppercase;margin-bottom:8px;">
                Confirm New Password
            </label>
            <div style="display:flex;align-items:center;gap:12px;
                        background:rgba(255,255,255,.07);
                        border:1.5px solid {{ $errors->has('confirmPassword') ? 'rgba(239,68,68,.7)' : 'rgba(255,255,255,.12)' }};
                        border-radius:13px;padding:14px 16px;">
                <i class="fas fa-check-circle" style="color:rgba(255,255,255,.22);font-size:.88rem;flex-shrink:0;"></i>
                <input type="password" wire:model="confirmPassword"
                       placeholder="Re-enter new password"
                       style="background:transparent;border:none;outline:none;
                              color:#fff;font-size:.9rem;font-weight:500;width:100%;
                              font-family:'DM Sans',sans-serif;"
                       id="conPwd">
                <button type="button" onclick="toggleField('conPwd','conPwdIc')"
                        style="background:none;border:none;padding:0;cursor:pointer;
                               color:rgba(255,255,255,.22);font-size:.88rem;">
                    <i id="conPwdIc" class="fas fa-eye"></i>
                </button>
            </div>
            @error('confirmPassword')
                <p style="color:#f87171;font-size:.73rem;margin-top:6px;font-weight:600;">
                    ⚠️ {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Submit --}}
        <button wire:click="save"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-60"
                style="width:100%;
                       background:linear-gradient(135deg,#dc2626,#b91c1c);
                       color:#fff;font-size:.92rem;font-weight:800;
                       letter-spacing:.06em;padding:15px;
                       border-radius:13px;border:none;cursor:pointer;
                       font-family:'DM Sans',sans-serif;
                       box-shadow:0 6px 24px rgba(220,38,38,.3);
                       transition:all .25s;">
            <span wire:loading.remove wire:target="save">
                <i class="fas fa-lock" style="margin-right:8px;"></i>SET NEW PASSWORD
            </span>
            <span wire:loading wire:target="save">⏳ Saving…</span>
        </button>

        <div style="margin-top:18px;padding-top:18px;
                    border-top:1px solid rgba(255,255,255,.08);
                    text-align:center;font-size:.78rem;
                    color:rgba(255,255,255,.22);">
            LUMC Patient Portal · La Union Medical Center
        </div>

    </div>
</div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script>
function toggleField(inputId, iconId) {
    const el = document.getElementById(inputId);
    const ic = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    ic.classList.toggle('fa-eye');
    ic.classList.toggle('fa-eye-slash');
}
</script>

</x-filament-panels::page>