    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LUMC — Staff Portal</title>
        <link rel="icon" type="image/png" href="{{ asset('images/lumc-logo.png') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700;9..40,800;9..40,900&display=swap" rel="stylesheet">
        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            html { font-family: 'DM Sans', sans-serif; }

            body {
                background-color: #0c1a3d;
                background-image:
                    radial-gradient(ellipse 80% 65% at 18% 50%,  rgba(29,78,216,.25) 0%, transparent 60%),
                    radial-gradient(ellipse 60% 50% at 83% 12%,  rgba(220,38,38,.13) 0%, transparent 55%),
                    radial-gradient(ellipse 50% 45% at 72% 90%,  rgba(29,78,216,.15) 0%, transparent 50%);
                color: #fff;
                overflow-x: hidden;
                min-height: 100vh;
            }

            /* ── FIXED BACKGROUND LAYERS ─────────────────────────────────────── */

            /* Ambient orbs */
            .bg-orb {
                position: fixed; border-radius: 50%;
                pointer-events: none; z-index: 0; filter: blur(80px);
            }
            .bg-orb-1 { width:550px; height:550px; background:rgba(29,78,216,.16); left:-160px; bottom:-140px; animation:orbPulse 9s ease-in-out infinite; }
            .bg-orb-2 { width:380px; height:380px; background:rgba(220,38,38,.09); right:4%; top:-80px; animation:orbPulse 11s 2s ease-in-out infinite; }

            /*
            * WATERMARKS — just 2, balanced left/right.
            * Large LUMC logo: far right, vertically centred, half off-screen.
            * Province logo:   far left,  vertically centred, half off-screen.
            * Both desaturated white, opacity ~0.035 — barely there, like a ghost.
            * Different float speeds so movement feels organic.
            */
            .wm-right {
                position: fixed;
                right: -80px;
                top: 50%;
                transform: translateY(-50%);
                width: clamp(260px, 30vw, 380px);
                height: clamp(260px, 30vw, 380px);
                object-fit: contain;
                opacity: 0.035;
                pointer-events: none; user-select: none; z-index: 0;
                filter: grayscale(100%) brightness(2.2);
                animation: wmFloatR 14s ease-in-out infinite;
            }
            .wm-left {
                position: fixed;
                left: -70px;
                top: 50%;
                transform: translateY(-50%);
                width: clamp(200px, 22vw, 300px);
                height: clamp(200px, 22vw, 300px);
                object-fit: contain;
                opacity: 0.03;
                pointer-events: none; user-select: none; z-index: 0;
                filter: grayscale(100%) brightness(2.2);
                animation: wmFloatL 17s ease-in-out infinite;
            }

            /* ── KEYFRAMES ───────────────────────────────────────────────────── */
            @keyframes wmFloatR {
                0%,100% { transform:translateY(-50%) rotate(0deg); }
                38%      { transform:translateY(calc(-50% - 22px)) rotate(1.5deg); }
                68%      { transform:translateY(calc(-50% - 10px)) rotate(-1deg); }
            }
            @keyframes wmFloatL {
                0%,100% { transform:translateY(-50%) rotate(0deg); }
                42%      { transform:translateY(calc(-50% - 18px)) rotate(-1.5deg); }
                72%      { transform:translateY(calc(-50% - 8px))  rotate(1deg); }
            }
            @keyframes orbPulse {
                0%,100% { opacity:1; transform:scale(1); }
                50%      { opacity:.6; transform:scale(1.1); }
            }

            @keyframes fadeUp {
                from { opacity:0; transform:translateY(20px); }
                to   { opacity:1; transform:translateY(0); }
            }
            @keyframes shimmer {
                0%   { background-position:-220% center; }
                100% { background-position: 220% center; }
            }
            @keyframes blink {
                0%,100% { opacity:1; } 50% { opacity:.2; }
            }
            @keyframes logoPop {
                0%   { opacity:0; transform:scale(.65) rotate(-8deg); }
                65%  { transform:scale(1.07) rotate(1.5deg); }
                100% { opacity:1; transform:scale(1) rotate(0deg); }
            }
            @keyframes stripeScan {
                0%   { background-position:0% center; }
                100% { background-position:200% center; }
            }

            .fu0 { animation:fadeUp .52s .00s ease both; }
            .fu1 { animation:fadeUp .52s .07s ease both; }
            .fu2 { animation:fadeUp .52s .14s ease both; }
            .fu3 { animation:fadeUp .52s .22s ease both; }
            .fu4 { animation:fadeUp .52s .30s ease both; }
            .fu5 { animation:fadeUp .52s .38s ease both; }

            .shimmer {
                background:linear-gradient(90deg, #facc15 0%, rgba(255,255,255,.75) 40%, #facc15 60%, #fbbf24 100%);
                background-size:220% auto;
                -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
                animation:shimmer 3s linear infinite;
            }
            .blink { animation:blink 2.2s ease-in-out infinite; display:inline-block; }

            /* ── PAGE LAYOUT ───────────────────────────────────────────────── */
            .page {
                position: relative; z-index: 1;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* ── TOP NAV ───────────────────────────────────────────────────── */
            .top-nav {
                flex-shrink: 0;
                display: flex; align-items: center; justify-content: space-between; gap: 12px;
                padding: 14px 28px;
                border-bottom: 1px solid rgba(255,255,255,.07);
            }
            .nav-left { display:flex; align-items:center; gap:16px; }
            .nav-brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
            .nav-brand img { width:38px; height:38px; object-fit:contain; transition:transform .25s; }
            .nav-brand:hover img { transform:scale(1.1); }
            .brand-name { color:#fff; font-weight:800; font-size:.84rem; letter-spacing:.01em; line-height:1.2; }
            .brand-sub  { color:rgba(255,255,255,.25); font-size:.58rem; font-weight:600; letter-spacing:.18em; text-transform:uppercase; }

            .gov-logos { display:flex; align-items:center; gap:9px; padding-left:14px; border-left:1px solid rgba(255,255,255,.10); }
            .gov-logos img { width:30px; height:30px; object-fit:contain; opacity:.5; transition:opacity .2s, transform .2s; }
            .gov-logos img:hover { opacity:.85; transform:scale(1.08); }
            .gov-logos .agk { width:auto; height:22px; }

            .back-btn {
                display:inline-flex; align-items:center; gap:7px;
                background:rgba(255,255,255,.09); border:1.5px solid rgba(255,255,255,.18);
                color:rgba(255,255,255,.8); font-size:.73rem; font-weight:700;
                letter-spacing:.07em; text-transform:uppercase;
                padding:8px 16px; border-radius:8px; text-decoration:none;
                transition:all .2s; white-space:nowrap;
            }
            .back-btn:hover { background:rgba(255,255,255,.16); color:#fff; border-color:rgba(255,255,255,.38); }

            /* ── CENTER ────────────────────────────────────────────────────── */
            .center {
                flex: 1;
                display: flex; align-items: center; justify-content: center;
                padding: clamp(16px, 2.5vh, 32px) 20px;
            }

            .shell { width:100%; max-width:480px; }

            /* ── LOGIN HEADER ──────────────────────────────────────────────── */
            .login-header { text-align:center; margin-bottom: clamp(14px, 2vh, 22px); }

            .badge {
                display:inline-flex; align-items:center; gap:8px;
                background:rgba(255,255,255,.09); border:1px solid rgba(255,255,255,.16);
                padding:6px 18px; border-radius:999px;
                font-size:10px; font-weight:800; letter-spacing:.22em; text-transform:uppercase;
                color:rgba(255,255,255,.6);
                margin-bottom: clamp(10px, 1.5vh, 18px);
            }

            .lumc-logo {
                width: clamp(60px, 8vh, 80px);
                height: clamp(60px, 8vh, 80px);
                object-fit: contain;
                display: block; margin: 0 auto clamp(10px, 1.5vh, 16px);
                filter: drop-shadow(0 8px 22px rgba(0,0,0,.55));
                animation: logoPop .7s .1s cubic-bezier(.34,1.56,.64,1) both;
            }

            .page-title {
                font-size: clamp(1.8rem, 3.5vh, 2.5rem);
                font-weight:900; line-height:1.08;
                margin-bottom: clamp(4px, .7vh, 8px);
            }
            .page-sub { color:rgba(255,255,255,.32); font-size:.82rem; font-weight:500; }

            /* ── CARD ──────────────────────────────────────────────────────── */
            .card {
                background:rgba(255,255,255,.055);
                border:1.5px solid rgba(255,255,255,.11);
                backdrop-filter:blur(30px);
                border-radius:22px; overflow:hidden;
            }
            .card-stripe {
                height:3px;
                background:linear-gradient(90deg,#1d4ed8,#3b82f6,#dc2626,#3b82f6,#1d4ed8);
                background-size:200% auto;
                animation:stripeScan 4s linear infinite;
            }
            .card-body { padding: clamp(24px, 3.5vh, 38px) clamp(24px, 4vw, 42px); }

            /* ── FIELDS ────────────────────────────────────────────────────── */
            .field { margin-bottom: clamp(14px, 2vh, 20px); }
            .f-label {
                display:block; font-size:9.5px; font-weight:800;
                color:rgba(255,255,255,.35); letter-spacing:.25em; text-transform:uppercase;
                margin-bottom:8px;
            }
            .f-box {
                display:flex; align-items:center; gap:12px;
                background:rgba(255,255,255,.07); border:1.5px solid rgba(255,255,255,.12);
                border-radius:13px; padding: clamp(12px, 1.8vh, 15px) 16px;
                transition:border-color .2s, background .2s, box-shadow .2s;
            }
            .f-box:focus-within {
                border-color:rgba(99,150,255,.75);
                background:rgba(255,255,255,.10);
                box-shadow:0 0 0 4px rgba(59,130,246,.12);
            }
            .f-box input {
                background:transparent; border:none; outline:none;
                color:#fff; font-size:.9rem; font-weight:500;
                font-family:'DM Sans',sans-serif; width:100%;
            }
            .f-box input::placeholder { color:rgba(255,255,255,.18); }
            .f-box input:-webkit-autofill,
            .f-box input:-webkit-autofill:focus {
                -webkit-box-shadow:0 0 0 1000px #0f1d45 inset !important;
                -webkit-text-fill-color:#fff !important; caret-color:#fff;
            }
            .f-icon { color:rgba(255,255,255,.22); font-size:.88rem; flex-shrink:0; }
            .eye-btn {
                background:none; border:none; padding:0; cursor:pointer;
                color:rgba(255,255,255,.22); font-size:.88rem; flex-shrink:0; transition:color .2s;
            }
            .eye-btn:hover { color:rgba(255,255,255,.65); }

            /* ── META ROW ──────────────────────────────────────────────────── */
            .meta-row {
                display:flex; align-items:center; justify-content:space-between;
                margin-bottom: clamp(16px, 2.2vh, 24px);
            }
            .remember { display:flex; align-items:center; gap:8px; cursor:pointer; }
            .remember input { accent-color:#3b82f6; width:14px; height:14px; }
            .remember span  { font-size:.84rem; color:rgba(255,255,255,.35); font-weight:500; }
            .forgot { font-size:.78rem; color:#60a5fa; font-weight:700; text-decoration:none; transition:color .2s; }
            .forgot:hover { color:#93c5fd; }

            /* ── SUBMIT ────────────────────────────────────────────────────── */
            .submit {
                width:100%;
                background:linear-gradient(135deg,#dc2626,#b91c1c);
                color:#fff; font-size:.92rem; font-weight:800;
                letter-spacing:.06em; padding: clamp(13px, 1.8vh, 16px);
                border-radius:13px; border:none; cursor:pointer;
                font-family:'DM Sans',sans-serif;
                box-shadow:0 6px 24px rgba(220,38,38,.3);
                transition:all .25s;
            }
            .submit:hover { background:linear-gradient(135deg,#b91c1c,#991b1b); transform:translateY(-2px); box-shadow:0 10px 32px rgba(220,38,38,.42); }
            .submit:active { transform:translateY(0); }

            /* ── BOTTOM NOTE ───────────────────────────────────────────────── */
            .bottom {
                text-align:center;
                margin-top: clamp(12px, 1.8vh, 20px);
                font-size:.8rem; color:rgba(255,255,255,.22);
            }
            .bottom a { color:rgba(96,165,250,.8); font-weight:700; text-decoration:none; transition:color .2s; }
            .bottom a:hover { color:#93c5fd; }

            /* ── ERROR ─────────────────────────────────────────────────────── */
            .err-box {
                background:rgba(127,29,29,.5); border:1px solid rgba(185,28,28,.5);
                color:#fca5a5; border-radius:13px; padding:12px 16px;
                margin-bottom: clamp(12px, 1.8vh, 20px);
                display:flex; align-items:center; gap:10px;
                font-size:.875rem; font-weight:600;
            }

            /* ── FOOTER ────────────────────────────────────────────────────── */
            .page-footer {
                flex-shrink: 0;
                text-align:center; padding:12px 20px;
                border-top:1px solid rgba(255,255,255,.05);
                font-size:.62rem; font-weight:700;
                letter-spacing:.18em; text-transform:uppercase;
                color:rgba(255,255,255,.12);
            }
        </style>
    </head>
    <body>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- BACKGROUND LAYER — fixed, z-index:0, never affects layout  --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}

    {{-- Ambient glow orbs --}}
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    {{-- Logo watermarks — 2 only, balanced left/right, centred vertically --}}
    <img src="{{ asset('images/lumc-logo.png') }}"      class="wm-right" alt="" onerror="this.style.display='none'">
    <img src="{{ asset('images/province-logo.png') }}"  class="wm-left"  alt="" onerror="this.style.display='none'">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- PAGE CONTENT — z-index:1, sits above all watermarks         --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="page">

        {{-- ══ NAV ══ --}}
        <nav class="top-nav fu0">
            <div class="nav-left">
                <a href="/" class="nav-brand">
                    <img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC"
                        onerror="this.outerHTML='<div style=\'width:38px;height:38px;background:#1e3a8a;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;\'><i class=\'fas fa-hospital\' style=\'color:#fff;font-size:1rem;\'></i></div>'">
                    <div>
                        <div class="brand-name">LA UNION MEDICAL CENTER</div>
                        <div class="brand-sub">Hospital Information System</div>
                    </div>
                </a>
                <div class="gov-logos">
                    <img src="{{ asset('images/province-logo.png') }}"         alt="Province" onerror="this.style.display='none'">
                    <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" alt="Bagong Pilipinas" onerror="this.style.display='none'">
                    <img src="{{ asset('images/agkaysa.png') }}"               alt="Agkaysa" class="agk" onerror="this.style.display='none'">
                </div>
            </div>
            <a href="/" class="back-btn">
                <i class="fas fa-arrow-left" style="font-size:.62rem;"></i>
                Back to Website
            </a>
        </nav>

        {{-- ══ CENTER ══ --}}
        <div class="center">
            <div class="shell">

                <div class="login-header">
                    <div class="badge fu1">
                        <span class="blink" style="width:7px;height:7px;border-radius:50%;background:#4ade80;"></span>
                        Authorized Personnel Only
                    </div>

                    <img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC" class="lumc-logo fu2"
                        onerror="this.style.display='none'">

                    <h1 class="page-title fu2">
                        Staff <span class="shimmer">Portal</span>
                    </h1>
                    <p class="page-sub fu3">Sign in to access your hospital dashboard</p>
                </div>

                @if(session('error'))
                <div class="err-box fu2">
                    <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <div class="card fu3">
                    <div class="card-stripe"></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.login.submit') }}">
                            @csrf

                            <div class="field">
                                <label class="f-label">Email Address</label>
                                <div class="f-box">
                                    <i class="fas fa-envelope f-icon"></i>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        placeholder="your@lumc.gov.ph" required autofocus>
                                </div>
                                @error('email')
                                <p style="color:#f87171;font-size:.73rem;margin-top:6px;display:flex;align-items:center;gap:5px;">
                                    <i class="fas fa-exclamation-circle" style="font-size:.62rem;"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="field">
                                <label class="f-label">Password</label>
                                <div class="f-box">
                                    <i class="fas fa-lock f-icon"></i>
                                    <input type="password" name="password" id="staffPwd"
                                        placeholder="••••••••" required autocomplete="current-password">
                                    <button type="button" class="eye-btn" onclick="togglePwd()">
                                        <i id="eyeIcon" class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                <p style="color:#f87171;font-size:.73rem;margin-top:6px;display:flex;align-items:center;gap:5px;">
                                    <i class="fas fa-exclamation-circle" style="font-size:.62rem;"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="meta-row">
                                <label class="remember">
                                    <input type="checkbox" name="remember">
                                    <span>Remember me</span>
                                </label>
                                <a href="/forgot-password" class="forgot">Forgot password?</a>
                            </div>

                            <button type="submit" class="submit">
                                <i class="fas fa-sign-in-alt" style="margin-right:8px;"></i>SIGN IN
                            </button>
                        </form>
                    </div>
                </div>

                <p class="bottom fu4">
                    Not a staff member?
                    <a href="/">Visit the LUMC website →</a>
                </p>

            </div>
        </div>

        {{-- ══ FOOTER ══ --}}
        <footer class="page-footer fu5">
            &copy; {{ date('Y') }} La Union Medical Center &nbsp;·&nbsp; Agoo, La Union &nbsp;·&nbsp; Agkaysa!
        </footer>

    </div>

    <script>
        function togglePwd() {
            const i = document.getElementById('staffPwd');
            const ic = document.getElementById('eyeIcon');
            i.type = i.type === 'password' ? 'text' : 'password';
            ic.classList.toggle('fa-eye');
            ic.classList.toggle('fa-eye-slash');
        }
    </script>
    </body>
    </html>