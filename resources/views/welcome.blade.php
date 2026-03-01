<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Union Medical Center | Official Website</title>
    <link rel="icon" type="image/png" href="{{ asset('images/lumc-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; scroll-behavior: smooth; overflow-x: hidden; }

        /* HERO */
        .hero-gradient { background: linear-gradient(140deg, rgba(8,20,65,.96) 0%, rgba(20,50,130,.88) 55%, rgba(10,28,80,.94) 100%); }

        /* ANIMATIONS */
        @keyframes fadeUp   { from{opacity:0;transform:translateY(26px);} to{opacity:1;transform:translateY(0);} }
        @keyframes fadeIn   { from{opacity:0;} to{opacity:1;} }
        @keyframes float    { 0%,100%{transform:translateY(0) rotate(0);} 40%{transform:translateY(-20px) rotate(1.5deg);} 70%{transform:translateY(-9px) rotate(-1deg);} }
        @keyframes shimmer  { 0%{background-position:-200% center;} 100%{background-position:200% center;} }
        @keyframes blink    { 0%,100%{opacity:1;} 50%{opacity:.3;} }
        @keyframes pulse-ring { 0%{transform:scale(1);opacity:.5;} 100%{transform:scale(1.7);opacity:0;} }
        @keyframes slideUp  { from{opacity:0;transform:translateY(40px);} to{opacity:1;transform:translateY(0);} }

        /* Modal-specific animations */
        @keyframes modalIn  { from{opacity:0;transform:translateY(24px) scale(.97);} to{opacity:1;transform:translateY(0) scale(1);} }
        @keyframes stripeScan { 0%{background-position:0% center;} 100%{background-position:200% center;} }
        @keyframes orbPulse { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.6;transform:scale(1.1);} }

        .anim-fu   { animation: fadeUp .6s ease both; }
        .anim-fu-1 { animation: fadeUp .6s .1s ease both; }
        .anim-fu-2 { animation: fadeUp .6s .2s ease both; }
        .anim-fu-3 { animation: fadeUp .6s .32s ease both; }
        .anim-fi   { animation: fadeIn 1.1s ease both; }
        .anim-float{ animation: float 8s ease-in-out infinite; }

        .shimmer-txt {
            background: linear-gradient(90deg,#facc15 0%,rgba(255,255,255,.65) 40%,#facc15 60%,#fbbf24 100%);
            background-size: 200% auto;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
        }
        .live-dot { animation: blink 2s ease-in-out infinite; }

        /* STAFF PORTAL BUTTON */
        .staff-portal-btn {
            display: inline-flex; align-items: center; gap: 7px;
            background: linear-gradient(135deg,#1e3a8a,#2563eb);
            color:#fff; font-weight:800; font-size:.72rem;
            letter-spacing:.1em; text-transform:uppercase;
            padding: 9px 18px; border-radius: 9px;
            border: 1.5px solid rgba(255,255,255,.22);
            text-decoration: none;
            box-shadow: 0 4px 18px rgba(37,99,235,.4);
            transition: all .22s;
            white-space: nowrap;
        }
        .staff-portal-btn:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(37,99,235,.55); }
        .staff-portal-btn .pill { background:rgba(255,255,255,.18); border-radius:4px; padding:1px 6px; font-size:.55rem; letter-spacing:.12em; }

        /* LOGIN BUTTON PULSE */
        .login-wrap { position:relative; display:inline-block; }
        .login-wrap::after {
            content:''; position:absolute; inset:0; border-radius:9px;
            border:2px solid rgba(220,38,38,.45);
            animation: pulse-ring 2.2s ease-out infinite;
            pointer-events:none;
        }

        /* SCROLL REVEAL */
        .reveal { opacity:0; transform:translateY(28px); transition:opacity .7s ease,transform .7s ease; }
        .reveal.in { opacity:1; transform:translateY(0); }

        /* CARD HOVER */
        .hov-card { transition:transform .3s cubic-bezier(.34,1.56,.64,1),box-shadow .3s ease; cursor:default; }
        .hov-card:hover { transform:translateY(-5px) scale(1.015); box-shadow:0 20px 40px rgba(26,58,138,.12); }

        /* HEADER */
        header { transition:box-shadow .3s; }
        .hdr-scrolled { box-shadow:0 4px 24px rgba(26,58,138,.13) !important; }

        /* ── PATIENT MODAL ── */
        .patient-modal-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 9999;
            align-items: center; justify-content: center;
            background: rgba(6,14,46,.82);
            backdrop-filter: blur(10px);
        }
        .patient-modal-overlay.open { display: flex; }

        .patient-modal {
            position: relative;
            width: 100%; max-width: 420px;
            margin: 16px;
            background: rgba(255,255,255,.055);
            border: 1.5px solid rgba(255,255,255,.12);
            backdrop-filter: blur(32px);
            border-radius: 24px;
            overflow: hidden;
            animation: modalIn .38s cubic-bezier(.34,1.56,.64,1);
            box-shadow:
                0 32px 80px rgba(0,0,0,.55),
                0 0 0 1px rgba(255,255,255,.06),
                inset 0 1px 0 rgba(255,255,255,.08);
        }

        .modal-stripe {
            height: 3px;
            background: linear-gradient(90deg,#1d4ed8,#3b82f6,#dc2626,#3b82f6,#1d4ed8);
            background-size: 200% auto;
            animation: stripeScan 4s linear infinite;
        }

        .modal-orb { position: absolute; border-radius: 50%; pointer-events: none; }
        .modal-orb-1 {
            width: 200px; height: 200px;
            background: rgba(29,78,216,.18); filter: blur(40px);
            right: -60px; bottom: -40px;
            animation: orbPulse 8s ease-in-out infinite;
        }
        .modal-orb-2 {
            width: 150px; height: 150px;
            background: rgba(220,38,38,.12); filter: blur(35px);
            left: -40px; top: -30px;
            animation: orbPulse 10s 2s ease-in-out infinite;
        }

        .modal-body { position: relative; z-index: 1; padding: 32px 36px 30px; }

        .modal-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .modal-head-left { display: flex; align-items: center; gap: 14px; }
        .modal-head-logo {
            width: 48px; height: 48px; object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,.5));
        }
        .modal-head-super {
            font-size: 9px; font-weight: 800; letter-spacing: .28em;
            text-transform: uppercase; color: #facc15; margin-bottom: 3px;
        }
        .modal-head-title {
            font-size: 1.25rem; font-weight: 900; color: #fff; line-height: 1;
        }
        .modal-close {
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(255,255,255,.09);
            border: 1px solid rgba(255,255,255,.15);
            color: rgba(255,255,255,.7); font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .2s; flex-shrink: 0;
        }
        .modal-close:hover { background: rgba(255,255,255,.18); color: #fff; }

        .modal-divider { height: 1px; background: rgba(255,255,255,.08); margin-bottom: 22px; }

        .modal-err {
            background: rgba(127,29,29,.5);
            border: 1px solid rgba(185,28,28,.5);
            color: #fca5a5; border-radius: 12px;
            padding: 11px 15px; margin-bottom: 18px;
            display: flex; align-items: center; gap: 9px;
            font-size: .84rem; font-weight: 600;
        }

        .modal-label {
            display: block; font-size: 9.5px; font-weight: 800;
            color: rgba(255,255,255,.35); letter-spacing: .25em;
            text-transform: uppercase; margin-bottom: 8px;
        }

        .modal-field {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,.07);
            border: 1.5px solid rgba(255,255,255,.12);
            border-radius: 13px; padding: 14px 16px;
            transition: border-color .2s, background .2s, box-shadow .2s;
            margin-bottom: 18px;
        }
        .modal-field:focus-within {
            border-color: rgba(99,150,255,.75);
            background: rgba(255,255,255,.10);
            box-shadow: 0 0 0 4px rgba(59,130,246,.12);
        }
        .modal-field input {
            background: transparent; border: none; outline: none;
            color: #fff; font-size: .9rem; font-weight: 500;
            font-family: 'DM Sans', sans-serif; width: 100%;
        }
        .modal-field input::placeholder { color: rgba(255,255,255,.2); }
        .modal-field input:-webkit-autofill,
        .modal-field input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #0f1d45 inset !important;
            -webkit-text-fill-color: #fff !important;
            caret-color: #fff;
        }
        .modal-field-icon { color: rgba(255,255,255,.22); font-size: .88rem; flex-shrink: 0; }
        .modal-eye {
            background: none; border: none; padding: 0; cursor: pointer;
            color: rgba(255,255,255,.22); font-size: .88rem; flex-shrink: 0;
            transition: color .2s;
        }
        .modal-eye:hover { color: rgba(255,255,255,.65); }

        .modal-meta {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 22px;
        }
        .modal-remember {
            display: flex; align-items: center; gap: 8px; cursor: pointer;
            font-size: .85rem; color: rgba(255,255,255,.35); font-weight: 500;
        }
        .modal-remember input { accent-color: #3b82f6; width: 14px; height: 14px; }
        .modal-forgot {
            font-size: .78rem; color: #60a5fa; font-weight: 700;
            text-decoration: none; transition: color .2s;
        }
        .modal-forgot:hover { color: #93c5fd; }

        .modal-submit {
            width: 100%;
            background: linear-gradient(135deg,#dc2626,#b91c1c);
            color: #fff; font-size: .92rem; font-weight: 800;
            letter-spacing: .06em; padding: 15px;
            border-radius: 13px; border: none; cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            box-shadow: 0 6px 24px rgba(220,38,38,.3);
            transition: all .25s;
        }
        .modal-submit:hover {
            background: linear-gradient(135deg,#b91c1c,#991b1b);
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(220,38,38,.42);
        }
        .modal-submit:active { transform: translateY(0); }

        .modal-footer-note {
            margin-top: 18px; padding-top: 18px;
            border-top: 1px solid rgba(255,255,255,.08);
            text-align: center; font-size: .8rem;
            color: rgba(255,255,255,.25);
        }
        .modal-footer-note a {
            color: rgba(96,165,250,.8); font-weight: 700;
            text-decoration: none; transition: color .2s;
        }
        .modal-footer-note a:hover { color: #93c5fd; }

        /* ── HERO IMAGE CAROUSEL ──────────────────────────────────────── */
        /*
         * Fully transparent — no background, no border, no frame.
         * Every image is forced to fill the SAME fixed display size
         * (width: 100%, height: 100%) using object-fit: contain so
         * all images — regardless of their original pixel dimensions —
         * appear at the exact same large size. No image is smaller or
         * larger than another.
         */
        .hero-carousel {
            position: relative;
            width: 100%;
            height: 420px;
        }

        .carousel-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s cubic-bezier(.4, 0, .2, 1);
        }
        .carousel-slide.active {
            opacity: 1;
        }

        /*
         * The image wrapper forces a fixed display box — every image
         * stretches to fill this box with object-fit: contain, so all
         * images render at the same apparent size no matter their
         * native resolution or aspect ratio.
         */
        .carousel-slide .img-wrap {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .carousel-slide .img-wrap img {
            /* Force every image to the same display size */
            width: 100%;
            height: 100%;
            /* contain = whole image visible, no cropping, no distortion */
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 16px 40px rgba(0,0,0,.35));
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

{{-- ═══════════ HEADER ═══════════ --}}
<header id="hdr" class="bg-white sticky top-0 z-50 shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-5 py-3 flex items-center justify-between gap-4">

        <a href="/" class="flex items-center gap-3 group flex-shrink-0" style="text-decoration:none;">
            <img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC Logo"
                class="w-11 h-11 object-contain transition-transform group-hover:scale-105"
                onerror="this.outerHTML='<div class=\'w-11 h-11 bg-blue-900 rounded-full flex items-center justify-center flex-shrink-0\'><i class=\'fas fa-hospital text-white\'></i></div>'">
            <div>
                <div class="text-sm font-black text-blue-900 tracking-tight leading-tight">LA UNION MEDICAL CENTER</div>
                <div class="text-[9px] text-gray-400 font-semibold tracking-widest uppercase">Agoo, La Union · Agkaysa!</div>
            </div>
        </a>

        <nav class="hidden lg:flex items-center gap-5 text-[13px] font-semibold">
            <a href="#home"     class="text-gray-500 hover:text-blue-900 transition">Home</a>
            <a href="#about"    class="text-gray-500 hover:text-blue-900 transition">About</a>
            <a href="#mission"  class="text-gray-500 hover:text-blue-900 transition">Mission & Vision</a>
            <a href="#services" class="text-gray-500 hover:text-blue-900 transition">Departments</a>
            <a href="#contact"  class="text-gray-500 hover:text-blue-900 transition">Contact</a>

            <div class="login-wrap">
                <button onclick="openLoginModal()"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-bold text-[13px] transition shadow">
                    <i class="fas fa-sign-in-alt mr-1.5"></i>Patient Login
                </button>
            </div>

            <a href="/staff" class="staff-portal-btn">
                <i class="fas fa-id-badge text-sm"></i>
                Staff Portal
                <span class="pill">EMPLOYEES</span>
            </a>

            <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
                <img src="{{ asset('images/province-logo.png') }}" alt="Province of La Union"
                    class="w-9 h-9 object-contain" onerror="this.style.display='none'">
                <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" alt="Bagong Pilipinas"
                    class="w-9 h-9 object-contain" onerror="this.style.display='none'">
            </div>
        </nav>

        <div class="flex lg:hidden items-center gap-2">
            <button onclick="openLoginModal()" class="bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-black">
                <i class="fas fa-sign-in-alt mr-1"></i>Login
            </button>
            <a href="/staff" class="bg-blue-900 text-white px-3 py-2 rounded-lg text-xs font-black">
                <i class="fas fa-id-badge mr-1"></i>Staff
            </a>
        </div>
    </div>
</header>

{{-- ═══════════ HERO ═══════════ --}}
<section id="home" class="relative min-h-[570px] flex items-center text-white overflow-hidden">
    <div class="absolute inset-0 z-0 bg-[#081441]">
        <div class="absolute inset-0 hero-gradient"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-blue-700/20 blur-3xl"></div>
        <div class="absolute top-0 right-1/4 w-72 h-72 rounded-full bg-red-700/10 blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-5 relative z-10 py-20 w-full">
        <div class="grid lg:grid-cols-5 gap-10 items-center">

            <div class="lg:col-span-3 space-y-5">
                <div class="anim-fu inline-flex items-center gap-2 bg-white/10 backdrop-blur px-4 py-1.5 rounded-full border border-white/20 text-[11px] font-bold tracking-widest uppercase">
                    <span class="live-dot w-2 h-2 rounded-full bg-green-400"></span>
                    Established April 08, 2002
                </div>

                <h2 class="anim-fu-1 text-4xl md:text-5xl lg:text-6xl font-black leading-[1.08]">
                    Level 2 Tertiary<br>
                    <span class="shimmer-txt">Provincial Hospital</span>
                </h2>

                <p class="anim-fu-2 text-[15px] text-white/65 max-w-lg leading-relaxed">
                    A <strong class="text-white font-black">₱650 million</strong> healthcare facility donated by the
                    European Union, serving La Union with the spirit of
                    <em class="text-red-400 font-black not-italic">Agkaysa!</em>
                </p>

                <div class="anim-fu-3 flex flex-wrap gap-3 pt-1">
                    <a href="#about" class="bg-red-600 hover:bg-red-700 px-7 py-3 rounded-lg font-bold transition shadow-xl text-sm flex items-center gap-2">
                        Our History <i class="fas fa-history text-xs"></i>
                    </a>
                    <a href="#services" class="bg-white/10 hover:bg-white/18 border border-white/25 backdrop-blur px-7 py-3 rounded-lg font-bold transition text-sm flex items-center gap-2">
                        Departments <i class="fas fa-stethoscope text-xs"></i>
                    </a>
                </div>
            </div>

            {{-- ── HERO IMAGE CAROUSEL ──
                 Every image is forced to width:100% height:100% with
                 object-fit:contain — all images render at the same large
                 display size regardless of their native pixel dimensions. --}}
            <div class="lg:col-span-2 hidden lg:flex items-center justify-center anim-fi">
                <div class="hero-carousel">
                    @php
                        $slides = [
                            'lumc-logo.png',
                            'province-logo.png',
                            'bagong-pilipinas-logo.png',
                            'agkaysa.png',
                        ];
                    @endphp
                    @foreach($slides as $i => $img)
                    <div class="carousel-slide {{ $i === 0 ? 'active' : '' }}">
                        <div class="img-wrap">
                            <img src="{{ asset('images/' . $img) }}"
                                alt="LUMC Photo {{ $i + 1 }}"
                                onerror="this.closest('.carousel-slide').style.display='none'">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════ STATS ═══════════ --}}
<section class="bg-blue-900 py-10">
    <div class="max-w-7xl mx-auto px-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="reveal hov-card text-center p-5 rounded-2xl bg-white/5 border border-white/10">
                <p class="text-yellow-400 font-black text-4xl mb-1">100</p>
                <p class="text-white/50 text-[10px] uppercase tracking-widest font-bold">Bed Capacity</p>
            </div>
            <div class="reveal hov-card text-center p-5 rounded-2xl bg-white/5 border border-white/10">
                <p class="text-yellow-400 font-black text-4xl mb-1">294</p>
                <p class="text-white/50 text-[10px] uppercase tracking-widest font-bold">Total Staff</p>
            </div>
            <div class="reveal hov-card text-center p-5 rounded-2xl bg-white/5 border border-white/10">
                <p class="text-yellow-400 font-black text-4xl mb-1">628k+</p>
                <p class="text-white/50 text-[10px] uppercase tracking-widest font-bold">Patients Served</p>
            </div>
            <div class="reveal hov-card text-center p-5 rounded-2xl bg-white/5 border border-white/10">
                <p class="text-yellow-400 font-black text-4xl mb-1">27</p>
                <p class="text-white/50 text-[10px] uppercase tracking-widest font-bold">Total Buildings</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ ABOUT ═══════════ --}}
<section id="about" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-5">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="reveal">
                <span class="text-[10px] font-black text-red-600 tracking-[.3em] uppercase">Legacy of Care</span>
                <h2 class="text-4xl font-black text-blue-900 mt-3 mb-6 leading-tight">Our Journey &<br>Transformation</h2>
                <div class="space-y-4 text-gray-500 leading-relaxed text-[15px]">
                    <p>Established to replace the earthquake-damaged Doña Gregoria Memorial Hospital, LUMC opened on
                        <strong class="text-gray-800">April 08, 2002</strong> — a landmark gift from the European Union.</p>
                    <p>Through <strong class="text-gray-800">Republic Act 9259</strong>, we became the first Provincial Hospital
                        in the Philippines converted into a non-stock, non-profit local government-controlled corporation.</p>
                    <p>Under the Board of Trustees chaired by the Provincial Governor, we serve over
                        <strong class="text-gray-800">740,000 residents</strong> of La Union.</p>
                </div>
            </div>
            <div class="reveal grid grid-cols-2 gap-4">
                <div class="hov-card bg-gray-50 rounded-2xl p-7 flex flex-col items-center text-center border border-gray-100">
                    <i class="fas fa-hand-holding-heart text-3xl text-red-500 mb-3"></i>
                    <div class="font-black text-blue-900 text-xl">48%</div>
                    <p class="text-xs text-gray-400 mt-1">Charity care<br>for indigent patients</p>
                </div>
                <div class="hov-card bg-blue-700 rounded-2xl p-7 flex flex-col items-center text-center">
                    <i class="fas fa-laptop-medical text-3xl text-yellow-300 mb-3"></i>
                    <div class="font-black text-white text-xl">Digital</div>
                    <p class="text-xs text-blue-200 mt-1">Automated<br>E-NGAS Systems</p>
                </div>
                <div class="hov-card bg-red-500 rounded-2xl p-7 flex flex-col items-center text-center text-white col-span-2">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-2 text-red-100">Social Service Classification</p>
                    <div class="text-2xl font-black">CLASS A TO D</div>
                    <p class="text-xs text-red-100 mt-1">Fair access based on capacity to pay</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ MISSION & VISION ═══════════ --}}
<section id="mission" class="py-24 bg-blue-50">
    <div class="max-w-7xl mx-auto px-5">
        <div class="text-center mb-14 reveal">
            <span class="text-[10px] font-black text-red-600 tracking-[.3em] uppercase">Who We Are</span>
            <h2 class="text-4xl font-black text-blue-900 mt-3">Mission & Vision</h2>
            <div class="h-1 w-14 bg-red-600 mx-auto mt-4 rounded-full"></div>
        </div>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="hov-card bg-white p-10 rounded-2xl shadow-sm border border-gray-100 reveal">
                <div class="w-12 h-12 bg-blue-900 text-white rounded-xl flex items-center justify-center mb-5 shadow">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="text-lg font-black text-blue-900 mb-4 uppercase tracking-tight">Vision</h3>
                <p class="text-gray-500 leading-relaxed text-sm italic">
                    "The La Union Medical Center shall be the center-point for the delivery of quality tertiary
                    medical/surgical care for the people especially in La Union provided in an atmosphere of
                    competent, affordable, compassionate friendly and caring hospital environment."
                </p>
            </div>
            <div class="hov-card bg-white p-10 rounded-2xl shadow-sm border border-gray-100 reveal">
                <div class="w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center mb-5 shadow">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="text-lg font-black text-blue-900 mb-4 uppercase tracking-tight">Mission</h3>
                <ul class="space-y-3 text-gray-500 text-sm">
                    <li class="flex gap-3"><i class="fas fa-check-circle text-red-500 mt-0.5 flex-shrink-0"></i>Comprehensive family medicine with emphasis on preventive and curative care.</li>
                    <li class="flex gap-3"><i class="fas fa-check-circle text-red-500 mt-0.5 flex-shrink-0"></i>Multi-specialty focus towards diagnostic and specialized therapeutic cases.</li>
                    <li class="flex gap-3"><i class="fas fa-check-circle text-red-500 mt-0.5 flex-shrink-0"></i>Training center for medical and paramedical health providers.</li>
                    <li class="flex gap-3"><i class="fas fa-check-circle text-red-500 mt-0.5 flex-shrink-0"></i>Research center for locally based public health concerns.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ DEPARTMENTS ═══════════ --}}
<section id="services" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-5">
        <div class="text-center mb-14 reveal">
            <span class="text-[10px] font-black text-red-600 tracking-[.3em] uppercase">What We Offer</span>
            <h2 class="text-4xl font-black text-blue-900 mt-3">Clinical Departments</h2>
            <div class="h-1 w-14 bg-red-600 mx-auto mt-4 rounded-full"></div>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">

            <div class="hov-card bg-gray-50 p-6 rounded-2xl border border-gray-100 reveal">
                <div class="w-10 h-10 bg-blue-900 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-scissors text-white text-sm"></i>
                </div>
                <h4 class="font-black text-blue-900 mb-3 text-xs uppercase tracking-widest">Surgery</h4>
                <ul class="text-xs space-y-1.5 text-gray-400 font-medium">
                    <li>· Orthopedic</li>
                    <li>· Ophthalmology</li>
                    <li>· Otorhinolaryngology</li>
                    <li>· Neuro Surgical</li>
                    <li>· Urology</li>
                    <li>· Thoracic & CV Surgery</li>
                </ul>
            </div>

            <div class="hov-card bg-gray-50 p-6 rounded-2xl border border-gray-100 reveal">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-heartbeat text-white text-sm"></i>
                </div>
                <h4 class="font-black text-blue-900 mb-3 text-xs uppercase tracking-widest">Internal Medicine</h4>
                <ul class="text-xs space-y-1.5 text-gray-400 font-medium">
                    <li>· Adult Cardiology</li>
                    <li>· Gastroenterology</li>
                    <li>· Nephrology</li>
                    <li>· Ambulatory Diabetes</li>
                    <li>· DOTS Clinic</li>
                </ul>
            </div>

            <div class="hov-card bg-gray-50 p-6 rounded-2xl border border-gray-100 reveal">
                <div class="w-10 h-10 bg-pink-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-venus text-white text-sm"></i>
                </div>
                <h4 class="font-black text-blue-900 mb-3 text-xs uppercase tracking-widest">OB-Gynecology</h4>
                <ul class="text-xs space-y-1.5 text-gray-400 font-medium">
                    <li>· Gynecologic Oncology</li>
                    <li>· Maternity Care</li>
                    <li>· Reproductive Health</li>
                    <li>· Family Planning</li>
                </ul>
            </div>

            <div class="hov-card bg-gray-50 p-6 rounded-2xl border border-gray-100 reveal">
                <div class="w-10 h-10 bg-teal-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-child text-white text-sm"></i>
                </div>
                <h4 class="font-black text-blue-900 mb-3 text-xs uppercase tracking-widest">Pediatrics</h4>
                <ul class="text-xs space-y-1.5 text-gray-400 font-medium">
                    <li>· Pediatric Cardiology</li>
                    <li>· Child Wellness</li>
                    <li>· Immunization</li>
                    <li>· Neonatal Intensive Care</li>
                </ul>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════ FOOTER ═══════════ --}}
<footer id="contact" class="bg-[#060e2e] text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-5">
        <div class="grid lg:grid-cols-3 gap-10 mb-12">

            <div>
                <div class="flex items-center gap-3 mb-5">
                    <img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC" class="w-11 h-11 object-contain"
                        onerror="this.style.display='none'">
                    <div>
                        <div class="font-black text-base leading-tight">LA UNION MEDICAL CENTER</div>
                        <div class="text-white/25 text-[9px] tracking-widest uppercase font-semibold">Official Hospital Portal</div>
                    </div>
                </div>
                <p class="text-sm text-white/35 leading-relaxed mb-5 max-w-xs">
                    A center of excellence in healthcare, training, and research serving La Union since 2002.
                </p>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/province-logo.png') }}" class="w-9 h-9 object-contain opacity-50" onerror="this.style.display='none'" alt="Province">
                    <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" class="w-9 h-9 object-contain opacity-50" onerror="this.style.display='none'" alt="Bagong Pilipinas">
                    <img src="{{ asset('images/agkaysa.png') }}" class="h-7 object-contain opacity-50" onerror="this.style.display='none'" alt="Agkaysa">
                </div>
            </div>

            <div>
                <h4 class="font-black text-xs mb-5 border-l-4 border-red-600 pl-3 uppercase tracking-widest">Contact Info</h4>
                <ul class="space-y-3 text-sm text-white/40">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-pin text-yellow-400 w-4 flex-shrink-0 mt-0.5"></i>
                        <span>Barangay Nazareno, Agoo, La Union, 2504</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-phone text-yellow-400 w-4 flex-shrink-0 mt-0.5"></i>
                        <span>(072) 607-5541 | (072) 607-5939</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-envelope text-yellow-400 w-4 flex-shrink-0 mt-0.5"></i>
                        <span>pglu_lumc@launion.gov.ph</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-clock text-yellow-400 w-4 flex-shrink-0 mt-0.5"></i>
                        <span>Emergency: 0927-728-6330 (24/7)</span>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-black text-xs mb-5 border-l-4 border-red-600 pl-3 uppercase tracking-widest">Governing Body</h4>
                <p class="text-sm text-white/35 mb-4 leading-relaxed">
                    Board of Trustees chaired by the Incumbent Governor of La Union.
                    Established under <strong class="text-white/50">Republic Act 9259</strong>.
                </p>
                <div class="bg-white/5 p-4 rounded-xl border border-white/10 text-center">
                    <p class="text-[9px] text-yellow-400 uppercase font-black tracking-widest italic mb-1">Agkaysa!</p>
                    <p class="text-xs text-white/40">Province of La Union</p>
                </div>
            </div>

        </div>

        <div class="border-t border-white/10 pt-6 text-center text-[9px] text-white/15 font-bold tracking-widest uppercase">
            &copy; {{ date('Y') }} La Union Medical Center · Official Portal · Bagong Pilipinas
        </div>
    </div>
</footer>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- PATIENT LOGIN MODAL — dark staff-portal style          --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div id="loginModal" class="patient-modal-overlay" onclick="handleOverlayClick(event)">
    <div class="patient-modal">

        <div class="modal-orb modal-orb-1"></div>
        <div class="modal-orb modal-orb-2"></div>
        <div class="modal-stripe"></div>

        <div class="modal-body">

            <div class="modal-head">
                <div class="modal-head-left">
                    <img src="{{ asset('images/lumc-logo.png') }}" alt="LUMC" class="modal-head-logo"
                        onerror="this.style.display='none'">
                    <div class="modal-head-text">
                        <div class="modal-head-super">La Union Medical Center</div>
                        <div class="modal-head-title">Patient Sign In</div>
                    </div>
                </div>
                <button class="modal-close" onclick="closeLoginModal()" aria-label="Close">&times;</button>
            </div>

            <div class="modal-divider"></div>

            @if(session('error'))
            <div class="modal-err">
                <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('patient.login.submit') }}">
                @csrf

                <label class="modal-label">Email Address</label>
                <div class="modal-field">
                    <i class="fas fa-envelope modal-field-icon"></i>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="your@email.com" required autofocus>
                </div>
                @error('email')
                <p style="color:#f87171;font-size:.73rem;margin-top:-10px;margin-bottom:14px;display:flex;align-items:center;gap:5px;">
                    <i class="fas fa-exclamation-circle" style="font-size:.62rem;"></i>{{ $message }}
                </p>
                @enderror

                <label class="modal-label">Password</label>
                <div class="modal-field">
                    <i class="fas fa-lock modal-field-icon"></i>
                    <input type="password" name="password" id="patientPwd"
                        placeholder="••••••••" required autocomplete="current-password">
                    <button type="button" class="modal-eye" onclick="togglePwd('patientPwd','patientPwdIcon')">
                        <i id="patientPwdIcon" class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                <p style="color:#f87171;font-size:.73rem;margin-top:-10px;margin-bottom:14px;display:flex;align-items:center;gap:5px;">
                    <i class="fas fa-exclamation-circle" style="font-size:.62rem;"></i>{{ $message }}
                </p>
                @enderror

                <div class="modal-meta">
                    <label class="modal-remember">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="/forgot-password" class="modal-forgot">Forgot password?</a>
                </div>

                <button type="submit" class="modal-submit">
                    <i class="fas fa-sign-in-alt" style="margin-right:8px;"></i>SIGN IN AS PATIENT
                </button>
            </form>

            <div class="modal-footer-note">
                Hospital employee?
                <a href="/staff">Use the Staff Portal →</a>
            </div>

        </div>
    </div>
</div>

<script>
    function openLoginModal() {
        document.getElementById('loginModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeLoginModal() {
        document.getElementById('loginModal').classList.remove('open');
        document.body.style.overflow = 'auto';
    }
    function handleOverlayClick(e) {
        if (e.target === document.getElementById('loginModal')) closeLoginModal();
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLoginModal(); });

    @if($errors->any() || session('error'))
        document.addEventListener('DOMContentLoaded', () => openLoginModal());
    @endif

    function togglePwd(inputId, iconId) {
        const el = document.getElementById(inputId);
        const ic = document.getElementById(iconId);
        el.type = el.type === 'password' ? 'text' : 'password';
        ic.classList.toggle('fa-eye');
        ic.classList.toggle('fa-eye-slash');
    }

    // ── HEADER SCROLL ─────────────────────────────────────────────────
    window.addEventListener('scroll', () => {
        document.getElementById('hdr').classList.toggle('hdr-scrolled', window.scrollY > 8);
    });

    // ── SCROLL REVEAL ─────────────────────────────────────────────────
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('in'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // ── HERO CAROUSEL ─────────────────────────────────────────────────
    // All images rendered at identical display size via CSS.
    // Smooth 1.5s crossfade every 5 seconds.
    (function () {
        const DURATION = 5000;
        const carousel = document.querySelector('.hero-carousel');
        if (!carousel) return;

        const slides = Array.from(carousel.querySelectorAll('.carousel-slide'))
            .filter(s => getComputedStyle(s).display !== 'none');

        if (slides.length < 2) return;

        let current = 0;

        setInterval(() => {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, DURATION);
    })();
</script>
</body>
</html>