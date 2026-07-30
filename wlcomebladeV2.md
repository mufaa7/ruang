<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUANG — Workspace Belajar Modern</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        :root{
            --bg:#0D0B09;
            --bg-2:#15120F;
            --surface:rgba(255,255,255,.04);
            --surface-2:rgba(255,255,255,.08);
            --primary:#FFB000;
            --primary-glow:#FF6A00;
            --text:#F7F3ED;
            --text-soft:#A89B8C;
            --border:rgba(255,176,0,.18);
        }

        html { scroll-behavior:smooth; }

        body{
            background:var(--bg);
            color:var(--text);
            font-family:"Plus Jakarta Sans",sans-serif;
            overflow-x:hidden;
            line-height:1.6;
        }

        /* ===== BACKGROUND 3D ===== */
        .bg-3d{
            position:fixed;
            inset:0;
            z-index:-10;
            perspective:1200px;
            overflow:hidden;
        }

        .bg-3d .layer{
            position:absolute;
            inset:-20%;
            background:
                radial-gradient(circle at 20% 30%,rgba(255,176,0,.18),transparent 35%),
                radial-gradient(circle at 80% 20%,rgba(255,106,0,.14),transparent 30%),
                radial-gradient(circle at 50% 80%,rgba(255,200,80,.10),transparent 40%);
            transform-style:preserve-3d;
            transition:transform .1s ease-out;
        }

        .bg-3d .grid{
            position:absolute;
            inset:-50%;
            background-image:
                linear-gradient(rgba(255,176,0,.05) 1px,transparent 1px),
                linear-gradient(90deg,rgba(255,176,0,.05) 1px,transparent 1px);
            background-size:120px 120px;
            transform:rotateX(60deg) translateZ(-200px);
            transform-style:preserve-3d;
            will-change:transform;
        }

        @media(prefers-reduced-motion:reduce){
            .bg-3d .grid{ animation:none; }
        }

        .orb{
            position:absolute;
            border-radius:50%;
            filter:blur(80px);
            opacity:.25;
            will-change:transform;
        }

        .orb.one{ width:500px; height:500px; background:#FFB000; top:10%; left:10%; animation-delay:0s; }
        .orb.two{ width:400px; height:400px; background:#FF6A00; top:40%; right:5%; animation-delay:-3s; }
        .orb.three{ width:350px; height:350px; background:#FFC333; bottom:10%; left:35%; animation-delay:-5s; }

        @keyframes orbFloat{
            0%,100%{ transform:translate(0,0) scale(1); }
            50%{ transform:translate(30px,-40px) scale(1.1); }
        }

        /* ===== PARTICLES ===== */
        .particles{
            position:fixed;
            inset:0;
            z-index:-5;
            pointer-events:none;
        }

        .particle{
            position:absolute;
            width:3px;
            height:3px;
            background:var(--primary);
            border-radius:50%;
            opacity:.4;
            animation:particleFloat 20s infinite linear;
            will-change:transform,opacity;
        }

        @keyframes particleFloat{
            0%{ transform:translateY(110vh) scale(0); opacity:0; }
            15%{ opacity:.5; }
            85%{ opacity:.5; }
            100%{ transform:translateY(-10vh) scale(1); opacity:0; }
        }

        /* ===== NAVBAR ===== */
        nav{
            position:fixed;
            top:24px;
            left:50%;
            transform:translateX(-50%);
            width:min(1200px,92%);
            height:68px;
            padding:0 24px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            background:rgba(13,11,9,.72);
            backdrop-filter:blur(24px);
            border:1px solid var(--surface-2);
            border-radius:999px;
            z-index:999;
            transition:.4s;
        }

        nav.scrolled{ top:14px; border-color:var(--border); }

        .logo{
            display:flex;
            align-items:center;
            gap:10px;
            font-family:"Space Grotesk",sans-serif;
            font-size:24px;
            font-weight:700;
            letter-spacing:-1px;
        }

        .logo i{ font-size:26px; color:var(--primary); }

        .menu{ display:flex; gap:32px; }
        .menu a{
            text-decoration:none;
            font-size:14px;
            font-weight:600;
            color:var(--text-soft);
            transition:.3s;
        }
        .menu a:hover{ color:var(--primary); }

        .nav-right{ display:flex; gap:14px; align-items:center; }

        .login{
            color:var(--text);
            font-weight:600;
            text-decoration:none;
            font-size:14px;
            transition:.3s;
        }
        .login:hover{ color:var(--primary); }

        .button{
            height:46px;
            padding:0 24px;
            border:none;
            border-radius:999px;
            background:linear-gradient(135deg,var(--primary-glow),var(--primary));
            color:#0D0B09;
            font-size:14px;
            font-weight:800;
            cursor:pointer;
            box-shadow:0 0 30px rgba(255,176,0,.35);
            transition:.35s cubic-bezier(.34,1.56,.64,1);
        }

        .button:hover{
            transform:translateY(-3px);
            box-shadow:0 0 50px rgba(255,176,0,.55);
        }

        .button.ghost{
            background:transparent;
            color:var(--text);
            border:1px solid var(--surface-2);
            box-shadow:none;
        }
        .button.ghost:hover{ border-color:var(--primary); color:var(--primary); box-shadow:0 0 20px rgba(255,176,0,.2); }

        /* ===== HERO ===== */
        .hero{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
            padding-top:100px;
            padding-bottom:60px;
            overflow:hidden;
        }

        .hero-content{
            text-align:center;
            max-width:900px;
            position:relative;
            z-index:2;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            gap:10px;
            padding:10px 20px;
            border-radius:999px;
            background:var(--surface);
            border:1px solid var(--border);
            color:var(--primary);
            font-size:13px;
            font-weight:700;
            margin-bottom:32px;
            box-shadow:0 0 30px rgba(255,176,0,.15);
        }

        .badge i{ font-size:18px; }

        .hero h1{
            font-family:"Space Grotesk",sans-serif;
            font-size:84px;
            line-height:.95;
            font-weight:700;
            letter-spacing:-3px;
        }

        .hero h1 span{
            background:linear-gradient(135deg,#FF6A00 0%,#FFB000 50%,#FFE082 100%);
            -webkit-background-clip:text;
            background-clip:text;
            color:transparent;
        }

        .hero p{
            margin-top:28px;
            font-size:18px;
            color:var(--text-soft);
            max-width:560px;
            margin-inline:auto;
            line-height:1.8;
        }

        .hero-buttons{
            display:flex;
            gap:16px;
            justify-content:center;
            margin-top:40px;
            flex-wrap:wrap;
        }

        /* ===== 3D MASCOT ===== */
        .mascot-stage{
            position:relative;
            width:420px;
            height:420px;
            margin:50px auto 0;
            perspective:1000px;
            transform-style:preserve-3d;
        }

        .mascot-ring{
            position:absolute;
            inset:0;
            border-radius:50%;
            border:1px solid rgba(255,176,0,.2);
            transform:rotateX(70deg);
            animation:ringSpin 12s linear infinite;
        }

        .mascot-ring.two{ inset:40px; border-color:rgba(255,106,0,.15); animation-duration:18s; animation-direction:reverse; }
        .mascot-ring.three{ inset:80px; border-color:rgba(255,200,80,.12); animation-duration:24s; }

        @keyframes ringSpin{
            from{ transform:rotateX(70deg) rotateZ(0deg); }
            to{ transform:rotateX(70deg) rotateZ(360deg); }
        }

        .mascot-glow{
            position:absolute;
            top:50%;
            left:50%;
            width:300px;
            height:300px;
            background:radial-gradient(circle,rgba(255,176,0,.35),transparent 70%);
            transform:translate(-50%,-50%) translateZ(-60px);
            filter:blur(40px);
            border-radius:50%;
            animation:glowPulse 4s ease-in-out infinite;
        }

        @keyframes glowPulse{
            0%,100%{ transform:translate(-50%,-50%) translateZ(-60px) scale(1); opacity:.7; }
            50%{ transform:translate(-50%,-50%) translateZ(-60px) scale(1.2); opacity:1; }
        }

        .mascot{
            position:absolute;
            top:50%;
            left:50%;
            width:280px;
            transform:translate(-50%,-50%) translateZ(40px);
            filter:drop-shadow(0 40px 80px rgba(0,0,0,.5));
            z-index:5;
            transition:transform .15s ease-out;
            animation:mascotFloat 6s ease-in-out infinite;
            will-change:transform;
        }

        @keyframes mascotFloat{
            0%,100%{ transform:translate(-50%,-50%) translateZ(40px) translateY(0); }
            50%{ transform:translate(-50%,-50%) translateZ(40px) translateY(-12px); }
        }

        .floating-widget{
            position:absolute;
            background:rgba(255,255,255,.06);
            backdrop-filter:blur(20px);
            border:1px solid rgba(255,255,255,.12);
            border-radius:20px;
            padding:18px 22px;
            box-shadow:0 20px 50px rgba(0,0,0,.3);
            z-index:10;
            transition:.4s cubic-bezier(.34,1.56,.64,1);
        }

        .floating-widget:hover{ transform:scale(1.08) translateZ(30px); border-color:var(--border); }

        .fw-playlist{ top:60px; left:-60px; animation:fwFloat1 8s ease-in-out infinite; }
        .fw-progress{ bottom:80px; right:-40px; animation:fwFloat2 9s ease-in-out infinite; }
        .fw-ai{ top:30px; right:-20px; animation:fwFloat3 10s ease-in-out infinite; }

        @keyframes fwFloat1{ 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-10px); } }
        @keyframes fwFloat2{ 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(10px); } }
        @keyframes fwFloat3{ 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-8px); } }

        .bar{
            margin-top:12px;
            height:4px;
            background:rgba(255,255,255,.1);
            border-radius:999px;
            overflow:hidden;
        }
        .bar span{ display:block; width:65%; height:100%; background:linear-gradient(90deg,var(--primary-glow),var(--primary)); border-radius:999px; }

        /* ===== SECTIONS ===== */
        section{ position:relative; padding:120px 0; }

        .container{ width:min(1200px,92%); margin:auto; }

        .section-title{
            text-align:center;
            max-width:700px;
            margin:0 auto 70px;
        }

        .section-title h2{
            font-family:"Space Grotesk",sans-serif;
            font-size:52px;
            font-weight:700;
            line-height:1.05;
            letter-spacing:-2px;
        }

        .section-title p{
            margin-top:18px;
            color:var(--text-soft);
            font-size:17px;
            line-height:1.8;
        }

        /* ===== FEATURES ===== */
        .features-grid{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:24px;
        }

        .feature-card{
            background:var(--surface);
            border:1px solid rgba(255,255,255,.08);
            border-radius:28px;
            padding:40px;
            transition:.4s cubic-bezier(.34,1.56,.64,1);
            position:relative;
            overflow:hidden;
        }

        .feature-card::before{
            content:"";
            position:absolute;
            inset:0;
            background:radial-gradient(circle at 50% 0%,rgba(255,176,0,.12),transparent 70%);
            opacity:0;
            transition:.4s;
        }

        .feature-card:hover{
            transform:translateY(-8px);
            border-color:var(--border);
            box-shadow:0 20px 40px rgba(0,0,0,.35);
        }
        .feature-card:hover::before{ opacity:1; }

        .feature-card > *{ position:relative; z-index:1; }

        .feature-card .icon{
            width:60px;
            height:60px;
            border-radius:16px;
            background:linear-gradient(135deg,rgba(255,106,0,.2),rgba(255,176,0,.15));
            display:flex;
            align-items:center;
            justify-content:center;
            margin-bottom:24px;
            transition:.4s;
        }

        .feature-card:hover .icon{ transform:scale(1.08) rotate(-3deg); box-shadow:0 0 20px rgba(255,176,0,.15); }
        .feature-card .icon i{ font-size:28px; color:var(--primary); }

        .feature-card h3{
            font-family:"Space Grotesk",sans-serif;
            font-size:26px;
            font-weight:700;
            margin-bottom:12px;
        }

        .feature-card p{ color:var(--text-soft); line-height:1.8; }

        /* ===== SHOWCASE ===== */
        .showcase{
            background:var(--bg-2);
            position:relative;
            overflow:hidden;
        }

        .showcase::before{
            content:"";
            position:absolute;
            top:50%;
            left:50%;
            width:800px;
            height:800px;
            background:radial-gradient(circle,rgba(255,176,0,.1),transparent 60%);
            transform:translate(-50%,-50%);
            filter:blur(100px);
        }

        .showcase-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:60px;
            align-items:center;
            position:relative;
            z-index:1;
        }

        .showcase-visual{
            position:relative;
            height:500px;
            display:flex;
            align-items:center;
            justify-content:center;
            perspective:1000px;
        }

        .showcase-visual img{
            width:320px;
            filter:drop-shadow(0 30px 60px rgba(0,0,0,.45));
            animation:mascotFloat 7s ease-in-out infinite;
            transition:transform .15s ease-out;
            will-change:transform;
        }

        .showcase-content h2{
            font-family:"Space Grotesk",sans-serif;
            font-size:48px;
            font-weight:700;
            line-height:1.05;
            letter-spacing:-2px;
            margin-bottom:24px;
        }

        .showcase-content p{
            color:var(--text-soft);
            font-size:17px;
            line-height:1.9;
            margin-bottom:32px;
        }

        .showcase-list{ display:flex; flex-direction:column; gap:18px; }

        .showcase-item{
            display:flex;
            gap:16px;
            align-items:center;
            padding:18px 22px;
            background:var(--surface);
            border:1px solid rgba(255,255,255,.08);
            border-radius:18px;
            transition:.3s;
        }

        .showcase-item:hover{ border-color:var(--border); transform:translateX(8px); }

        .showcase-item i{ font-size:24px; color:var(--primary); }
        .showcase-item span{ font-weight:600; }

        /* ===== CTA ===== */
        .cta{
            text-align:center;
            position:relative;
            overflow:hidden;
        }

        .cta::before{
            content:"";
            position:absolute;
            top:50%;
            left:50%;
            width:600px;
            height:600px;
            background:radial-gradient(circle,rgba(255,176,0,.15),transparent 60%);
            transform:translate(-50%,-50%);
            filter:blur(80px);
        }

        .cta h2{
            font-family:"Space Grotesk",sans-serif;
            font-size:64px;
            font-weight:700;
            line-height:1;
            letter-spacing:-2px;
            position:relative;
            z-index:1;
        }

        .cta p{
            margin-top:24px;
            color:var(--text-soft);
            font-size:18px;
            position:relative;
            z-index:1;
        }

        .cta .button{ margin-top:36px; position:relative; z-index:1; height:54px; padding:0 36px; font-size:16px; }

        /* ===== FOOTER ===== */
        footer{
            padding:60px 0 30px;
            border-top:1px solid rgba(255,255,255,.08);
        }

        .footer-content{
            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:24px;
        }

        .footer-logo{
            display:flex;
            align-items:center;
            gap:10px;
            font-family:"Space Grotesk",sans-serif;
            font-size:24px;
            font-weight:700;
        }

        .footer-logo i{ color:var(--primary); }

        footer p{ color:var(--text-soft); font-size:14px; }

        .footer-links{ display:flex; gap:28px; }
        .footer-links a{ color:var(--text-soft); text-decoration:none; font-size:14px; transition:.3s; }
        .footer-links a:hover{ color:var(--primary); }

        /* ===== REVEAL ===== */
        .reveal{
            opacity:0;
            transform:translateY(30px);
            transition:.7s cubic-bezier(.34,1.56,.64,1);
        }
        .reveal.visible{ opacity:1; transform:translateY(0); }

        /* ===== RESPONSIVE ===== */
        @media(max-width:900px){
            .menu{ display:none; }
            .hero h1{ font-size:56px; }
            .features-grid{ grid-template-columns:1fr; }
            .showcase-grid{ grid-template-columns:1fr; }
            .showcase-visual{ order:-1; height:360px; }
            .showcase-visual img{ width:240px; }
            .mascot-stage{ width:340px; height:340px; }
            .mascot{ width:220px; }
            .floating-widget{ display:none; }
            .cta h2{ font-size:44px; }
        }

        @media(max-width:600px){
            .hero h1{ font-size:42px; letter-spacing:-1px; }
            .section-title h2{ font-size:38px; }
            .showcase-content h2{ font-size:36px; }
            .footer-content{ flex-direction:column; text-align:center; }
        }
    </style>
</head>
<body>

    <div class="bg-3d" id="bg3d">
        <div class="layer" id="bgLayer">
            <div class="grid"></div>
            <div class="orb one"></div>
            <div class="orb two"></div>
            <div class="orb three"></div>
        </div>
    </div>

    <div class="particles" id="particles"></div>

    <nav id="navbar">
        <div class="logo">
            <i class="ph-fill ph-house-line"></i>
            ruang.
        </div>
        <div class="menu">
            <a href="#fitur">Fitur</a>
            <a href="#showcase">Workspace</a>
            <a href="#cta">Mulai</a>
        </div>
        <div class="nav-right">
            <a href="{{ route('login') }}" class="login">Masuk</a>
            <button class="button" onclick="window.location.href='{{ route('register') }}'">Mulai Gratis</button>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="hero-content reveal">
                <div class="badge">
                    <i class="ph-fill ph-sparkle"></i>
                    workspace belajar modern
                </div>
                <h1>
                    Satu ruang untuk<br>
                    <span>belajar & fokus.</span>
                </h1>
                <p>
                    Editor makalah, AI Assistant, playlist fokus, dan workspace produktivitas dalam satu tempat yang nyaman.
                </p>
                <div class="hero-buttons">
                    <button class="button" onclick="window.location.href='{{ route('register') }}'">Mulai Sekarang</button>
                    <button class="button ghost">Lihat Demo</button>
                </div>
            </div>

            <div class="mascot-stage reveal" id="mascotStage">
                <div class="mascot-ring"></div>
                <div class="mascot-ring two"></div>
                <div class="mascot-ring three"></div>
                <div class="mascot-glow"></div>
                <img src="{{ asset('images/duck.png') }}" class="mascot" id="mascot" alt="RUANG Mascot">

                <div class="floating-widget fw-playlist">
                    <small style="color:var(--text-soft);font-weight:700;">NOW PLAYING</small>
                    <h4 style="margin-top:6px;font-size:16px;">Lo-Fi Afternoon</h4>
                    <div class="bar"><span></span></div>
                </div>

                <div class="floating-widget fw-progress">
                    <small style="color:var(--text-soft);font-weight:700;">PROGRESS</small>
                    <h4 style="font-size:32px;margin-top:4px;">78%</h4>
                </div>

                <div class="floating-widget fw-ai">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <i class="ph-fill ph-robot" style="font-size:22px;color:var(--primary);"></i>
                        <strong>AI Ready</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="fitur">
        <div class="container">
            <div class="section-title reveal">
                <h2>Semua yang kamu butuhkan.</h2>
                <p>Tidak perlu pindah-pindah aplikasi. RUANG menggabungkan alat belajar dalam satu dashboard.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card reveal">
                    <div class="icon"><i class="ph-fill ph-file-doc"></i></div>
                    <h3>Editor Makalah</h3>
                    <p>Tulis makalah dengan editor nyaman, autosave, template kampus, sitasi, dan export PDF.</p>
                </div>
                <div class="feature-card reveal">
                    <div class="icon"><i class="ph-fill ph-robot"></i></div>
                    <h3>AI Assistant</h3>
                    <p>Teman belajar yang membantu brainstorming, merangkum, mencari referensi, dan menyusun makalah.</p>
                </div>
                <div class="feature-card reveal">
                    <div class="icon"><i class="ph-fill ph-music-notes"></i></div>
                    <h3>Focus Playlist</h3>
                    <p>Playlist lofi, ambient, dan rain sound untuk menemani sesi fokus dan belajar.</p>
                </div>
                <div class="feature-card reveal">
                    <div class="icon"><i class="ph-fill ph-brain"></i></div>
                    <h3>Flashcard</h3>
                    <p>Belajar lebih cepat dengan kartu hafalan otomatis untuk ujian dan quiz.</p>
                </div>
                <div class="feature-card reveal">
                    <div class="icon"><i class="ph-fill ph-chart-line-up"></i></div>
                    <h3>Progress</h3>
                    <p>Pantau target belajar, streak harian, dan perkembanganmu setiap minggu.</p>
                </div>
                <div class="feature-card reveal">
                    <div class="icon"><i class="ph-fill ph-device-mobile-camera"></i></div>
                    <h3>Responsive</h3>
                    <p>Lanjutkan belajar dari laptop, tablet, atau smartphone kapan saja.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SHOWCASE -->
    <section class="showcase" id="showcase">
        <div class="container">
            <div class="showcase-grid">
                <div class="showcase-visual reveal">
                    <img src="{{ asset('images/duck_laptop.png') }}" id="showcaseMascot" alt="Workspace">
                </div>
                <div class="showcase-content reveal">
                    <h2>Workspace yang terasa seperti meja belajarmu sendiri.</h2>
                    <p>Buka RUANG dan semua kebutuhanmu langsung tersedia. Catatan, playlist, AI, dan tugas dalam satu halaman.</p>
                    <div class="showcase-list">
                        <div class="showcase-item"><i class="ph-fill ph-check-circle"></i><span>Semua data tersimpan otomatis</span></div>
                        <div class="showcase-item"><i class="ph-fill ph-check-circle"></i><span>AI siap membantu 24/7</span></div>
                        <div class="showcase-item"><i class="ph-fill ph-check-circle"></i><span>Sinkron di semua perangkat</span></div>
                        <div class="showcase-item"><i class="ph-fill ph-check-circle"></i><span>Gratis untuk mulai belajar</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta" id="cta">
        <div class="container">
            <h2 class="reveal">Mulai belajar lebih fokus.</h2>
            <p class="reveal">Bangun kebiasaan belajar yang lebih nyaman bersama AI Assistant dan workspace RUANG.</p>
            <button class="button reveal" onclick="window.location.href='{{ route('register') }}'">Mulai Gratis Sekarang</button>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <i class="ph-fill ph-house-line"></i>
                    ruang.
                </div>
                <div class="footer-links">
                    <a href="#">Fitur</a>
                    <a href="#">Tentang</a>
                    <a href="#">FAQ</a>
                    <a href="#">Support</a>
                </div>
                <p>© {{ date('Y') }} RUANG. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // 3D parallax on mouse move
        const bgLayer = document.getElementById('bgLayer');
        const mascot = document.getElementById('mascot');
        const showcaseMascot = document.getElementById('showcaseMascot');
        const mascotStage = document.getElementById('mascotStage');

        let ticking = false;
        document.addEventListener('mousemove', (e) => {
            if(ticking) return;
            ticking = true;
            requestAnimationFrame(() => {
                const x = (e.clientX / window.innerWidth - .5) * 2;
                const y = (e.clientY / window.innerHeight - .5) * 2;

                bgLayer.style.transform = `rotateY(${x * 3}deg) rotateX(${-y * 3}deg)`;

                if(mascot){
                    mascot.style.transform = `translate(-50%,-50%) translateZ(40px) rotateY(${x * 10}deg) rotateX(${-y * 7}deg)`;
                }

                if(showcaseMascot){
                    showcaseMascot.style.transform = `rotateY(${x * 8}deg) rotateX(${-y * 6}deg)`;
                }
                ticking = false;
            });
        });

        // Particles (reduced count)
        const particlesContainer = document.getElementById('particles');
        for(let i = 0; i < 18; i++){
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + '%';
            p.style.animationDuration = (10 + Math.random() * 20) + 's';
            p.style.animationDelay = (Math.random() * 15) + 's';
            p.style.width = (2 + Math.random() * 4) + 'px';
            p.style.height = p.style.width;
            particlesContainer.appendChild(p);
        }

        // Scroll reveal
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting){
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        reveals.forEach(el => observer.observe(el));
    </script>
</body>
</html>
