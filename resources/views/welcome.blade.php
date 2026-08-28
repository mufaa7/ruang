<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ruang.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root{
            --paper:#000000;
            --text:#ffffff;
            --muted:#94a3b8;
            --secondary:#e2e8f0;
            --accent:#fbbf24;
        }

        html, body{
            margin: 0;
            background: #000000;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* ORNAMENT */
        .ornament-container {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
            background: #000000;
        }
        .ornament-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 40%, rgba(255, 255, 255, 0.04), transparent 70%);
        }

        @keyframes floatGuitar {
            0%, 100% { transform: translateY(-50%) rotate(0deg); }
            50% { transform: translateY(-52%) rotate(1deg); }
        }

        .ornament-guitar {
            position: absolute;
            right: -250px;
            top: 50%;
            transform: translateY(-50%);
            width: 950px;
            opacity: 0.14;
            mix-blend-mode: lighten;
            pointer-events: none;
            animation: floatGuitar 10s ease-in-out infinite;
        }

        .ornament-guitar img {
            width: 100%;
            height: auto;
            object-fit: contain;
            transform: scaleX(-1) rotate(-15deg);
        }

        @media (max-width: 640px) {
            .ornament-guitar {
                right: -150px;
                width: 700px;
            }
        }

        /* INTRO */
        #intro-screen{
            position:fixed;
            inset:0;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#000000;
            z-index:999;
            transition:0.8s ease;
        }

        #intro-screen.hide{
            opacity:0;
            visibility:hidden;
            pointer-events:none;
        }

        .intro-logo{
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(64px,10vw,120px);
            letter-spacing:.02em;
            margin: 0;
            color: #ffffff;
        }

        /* HERO */
        #hero{
            min-height:100vh;
            display:flex;
            align-items:center;
            position: relative;
            z-index: 1;
        }

        .hero-container{
            width:min(1200px,90%);
            margin:auto;
            display:grid;
            grid-template-columns:1.2fr .8fr;
            gap:120px;
        }

        .hero-label{
            text-transform:uppercase;
            letter-spacing:.35em;
            font-size:11px;
            font-weight: 600;
            color:var(--accent);
        }

        .hero-divider{
            width:170px;
            height:1px;
            background:rgba(255,255,255,.2);
            margin:32px 0 50px;
        }

        .hero-title{
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(72px,9vw,130px);
            line-height:.9;
            font-weight:600;
            margin: 0;
            color: #ffffff;
        }

        .hero-ritual{
            display:flex;
            flex-direction:column;
            gap:16px;
            margin-top:60px;
        }

        .hero-ritual span{
            font-size:24px;
            color: #e2e8f0;
            font-weight: 300;
        }

        .playing{
            color: #ffffff !important;
            font-weight: 500 !important;
            animation:pulse 2.2s infinite;
        }

        @keyframes pulse{
            0%,100%{ opacity:1; }
            50%{ opacity:.6; }
        }

        .hero-button{
            display:inline-block;
            margin-top:72px;
            text-decoration:none;
            color:#ffffff;
            font-weight: 500;
            border-bottom:1px solid rgba(255,255,255,0.4);
            padding-bottom:6px;
            transition: 0.3s;
        }

        .hero-button:hover {
            letter-spacing: .08em;
            color: var(--accent);
            border-color: var(--accent);
        }

        .hero-right{
            display:flex;
            flex-direction:column;
            justify-content:flex-end;
        }

        .hero-right blockquote{
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(42px,4vw,64px);
            line-height:1.15;
            color:rgba(255,255,255,0.85);
            margin:0;
            font-style: italic;
        }

        .hero-song{
            margin-top:90px;
            padding-top:24px;
            border-top:1px solid rgba(255,255,255,.15);
        }

        .song-widget {
            display: inline-flex;
            align-items: center;
            gap: 20px;
            padding: 20px 28px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
            transition: 0.3s;
        }

        .song-widget:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
            border-color: rgba(255,255,255,0.25);
        }

        .equalizer {
            display: inline-flex;
            align-items: flex-end;
            height: 10px;
            gap: 2px;
            margin-right: 8px;
        }

        .equalizer span {
            display: inline-block;
            width: 3px;
            background-color: var(--accent);
            border-radius: 2px;
            animation: eq 1s ease-in-out infinite alternate;
        }

        .equalizer span:nth-child(1) { height: 4px; animation-delay: 0.1s; }
        .equalizer span:nth-child(2) { height: 10px; animation-delay: 0.4s; }
        .equalizer span:nth-child(3) { height: 6px; animation-delay: 0.2s; }

        @keyframes eq {
            0% { height: 2px; }
            100% { height: 10px; }
        }

        .hero-song small{
            display:flex;
            align-items: center;
            letter-spacing:.25em;
            text-transform:uppercase;
            color:var(--accent);
            margin-bottom:12px;
            font-size: 10px;
            font-weight: 600;
        }

        .hero-song h3{
            font-family:"Cormorant Garamond",serif;
            font-size:32px;
            margin:0;
            line-height: 1;
            color: #ffffff;
        }

        .hero-song p{
            margin-top:6px;
            color:#cbd5e1;
            margin-bottom: 0;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .hero-container {
                grid-template-columns: 1fr;
                gap: 80px;
                padding: 80px 0;
            }
            .hero-song {
                margin-top: 40px;
            }
            .hero-song h3 {
                font-size: 24px;
            }
            .hero-song p {
                font-size: 14px;
            }
            .hero-song small {
                font-size: 10px;
                margin-bottom: 12px;
            }
        }

        /* ==========================
           INSIDE
        ========================== */

        #inside{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:140px 0;
            text-align:center;
            position: relative;
            z-index: 1;
        }

        .inside-container{
            width:min(720px,90%);
        }

        .inside-label{
            display:inline-block;
            font-size:14px;
            text-transform:uppercase;
            letter-spacing:.35em;
            color:var(--accent);
            font-weight: 600;
        }

        .inside-title{
            margin-top:42px;
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(56px,6vw,82px);
            line-height:1.05;
            font-weight:600;
            color:#ffffff;
        }

        .inside-text{
            margin-top:32px;
            font-size:20px;
            line-height:2;
            color:#e2e8f0;
        }

        .features-grid {
            margin-top: 64px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            text-align: left;
        }

        @media (max-width: 768px) {
            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            padding: 36px 32px;
            transition: .4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .feature-card:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.6);
            border-color: rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.07);
        }

        .feature-card i {
            font-size: 28px;
            color: var(--accent);
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid rgba(251, 191, 36, 0.25);
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 24px;
        }

        .feature-card h3 {
            font-family:"Cormorant Garamond",serif;
            font-size: 32px;
            margin: 0 0 12px;
            color: #ffffff;
            line-height: 1.1;
        }

        .feature-card p {
            font-size: 15px;
            line-height: 1.6;
            color: #cbd5e1;
            margin: 0;
            text-align: justify;
        }

        #inside .inside-label,
        #inside .inside-title,
        #inside .inside-text,
        #inside .feature-card {
            opacity:0;
            transform:translateY(40px);
            transition:.8s;
        }

        #inside.show .inside-label{
            opacity:1;
            transform:none;
        }

        #inside.show .inside-title{
            opacity:1;
            transform:none;
            transition-delay:.2s;
        }

        #inside.show .inside-text{
            opacity:1;
            transform:none;
            transition-delay:.45s;
        }
        
        #inside.show .feature-card:nth-child(1) { opacity: 1; transform: none; transition-delay: .3s; }
        #inside.show .feature-card:nth-child(2) { opacity: 1; transform: none; transition-delay: .4s; }
        #inside.show .feature-card:nth-child(3) { opacity: 1; transform: none; transition-delay: .5s; }
        #inside.show .feature-card:nth-child(4) { opacity: 1; transform: none; transition-delay: .6s; }

        .inside-divider{
            width:160px;
            height:1px;
            margin:80px auto 0;
            background:rgba(255,255,255,.15);
        }

        .inside-scroll{
            margin-top:36px;
            display:flex;
            flex-direction:column;
            gap:8px;
            align-items:center;
            color:var(--muted);
            letter-spacing:.25em;
            text-transform:uppercase;
            font-size:11px;
        }

        .inside-scroll span{
            animation:bounce 2s infinite;
        }

        @keyframes bounce{
            0%,100%{
                transform:translateY(0);
            }
            50%{
                transform:translateY(8px);
            }
        }

        /* ==========================
           DISCOVER
        ========================== */
        #discover{
            width:min(1100px,90%);
            margin:0 auto;
            padding:180px 0;
            opacity: 0;
            transform: translateY(40px);
            transition: 0.8s;
            position: relative;
            z-index: 1;
        }

        #discover.show {
            opacity: 1;
            transform: none;
        }

        .discover-header{
            margin-bottom:60px;
        }

        .discover-header span{
            text-transform:uppercase;
            letter-spacing:.35em;
            color:var(--accent);
            font-size:11px;
            font-weight: 600;
        }

        .discover-header h2{
            margin-top:24px;
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(42px,5vw,64px);
            line-height:1.05;
            color: #ffffff;
        }

        .discover-item{
            position:relative;
            padding:48px 0;
            border-top:1px solid rgba(255,255,255,.15);
            transition:.45s;
        }

        .discover-item:last-child{
            border-bottom:1px solid rgba(255,255,255,.15);
        }

        .number{
            position:absolute;
            right:0;
            top:40px;
            font-size:140px;
            font-family:"Cormorant Garamond",serif;
            color:rgba(255,255,255,.10);
            user-select:none;
            transition:.45s;
        }

        .discover-item h3{
            font-family:"Cormorant Garamond",serif;
            font-size:48px;
            margin-bottom:24px;
            color: #ffffff;
            transition:.45s;
        }

        .discover-item p{
            width:360px;
            line-height:2;
            color:#cbd5e1;
        }

        .discover-item:hover{
            padding-left:30px;
        }

        .discover-item:hover .number{
            color:rgba(252, 211, 77, 0.2);
            transform: scale(1.05) translateX(-20px) rotate(2deg);
        }

        .discover-item:hover h3{
            letter-spacing:.04em;
            color: var(--accent);
        }

        /* ==========================
           ABOUT
        ========================== */
        #about{
            padding:220px 0;
            opacity: 0;
            transform: translateY(40px);
            transition: 0.8s;
            position: relative;
            z-index: 1;
        }

        #about.show {
            opacity: 1;
            transform: none;
        }

        .about-container{
            width:min(720px,90%);
            margin:0 auto;
        }

        .section-label{
            display:block;
            text-transform:uppercase;
            font-size:11px;
            letter-spacing:.35em;
            color:var(--accent);
            font-weight: 600;
            margin-bottom:60px;
        }

        .about-content p{
            font-size:20px;
            line-height:2;
            color:#e2e8f0;
            margin-bottom:32px;
        }

        .about-signature{
            margin-top:100px;
            display:flex;
            gap:10px;
            align-items:center;
            flex-wrap:wrap;
            font-size:15px;
            color:var(--muted);
            letter-spacing:.08em;
        }

        .about-signature span{
            font-family:"Cormorant Garamond",serif;
            font-size:24px;
            color:#ffffff;
        }

        @media (max-width: 900px) {
            .hero-container {
                gap: 60px;
            }
        }

        /* ==========================
           ENDING
        ========================== */
        #ending{
            min-height:90vh;
            display:flex;
            justify-content:center;
            align-items:center;
            text-align:center;
            opacity: 0;
            transform: translateY(40px);
            transition: 0.8s;
            position: relative;
            z-index: 1;
        }

        #ending.show {
            opacity: 1;
            transform: none;
        }

        .ending-container{
            width:min(760px,90%);
        }

        .ending-container h2{
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(56px,6vw,86px);
            line-height:1.08;
            margin-bottom:90px;
            color:#ffffff;
        }

        .ending-button{
            display:inline-block;
            text-decoration:none;
            color:#ffffff;
            font-weight: 500;
            border-bottom:1px solid rgba(255,255,255,.4);
            padding-bottom:8px;
            transition:.3s;
        }

        .ending-button:hover{
            letter-spacing:.08em;
            color: var(--accent);
            border-color: var(--accent);
        }

        /* ==========================
           FOOTER
        ========================== */
        .site-footer {
            padding: 40px 0 80px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .footer-logo {
            font-family: "Cormorant Garamond", serif;
            font-size: 32px;
            color: #ffffff;
        }

        .footer-signature {
            margin-top: 16px;
            font-size: 13px;
            color: var(--muted);
            letter-spacing: .08em;
            line-height: 1.8;
        }
    </style>
</head>
<body>

    <!-- ORNAMENT -->
    <div class="ornament-container">
        <div class="ornament-bg"></div>
        <div class="ornament-guitar">
            <img src="{{ asset('images/guitar.png') }}" alt="Guitar Background">
        </div>
    </div>

    {{-- INTRO --}}
    <div id="intro-screen">
        <h1 class="intro-logo">ruang.</h1>
    </div>

    {{-- HERO --}}
    <x-landing.hero />

    {{-- INSIDE --}}
    <x-landing.inside />

    {{-- DISCOVER --}}
    <x-landing.discover />

    {{-- ABOUT --}}
    <x-landing.about />

    {{-- ENDING --}}
    <x-landing.ending />

    {{-- FOOTER --}}
    <x-landing.footer />

    <script>
        const hideIntro = () => {
            const intro = document.getElementById("intro-screen");
            if (intro) intro.classList.add("hide");
        };

        window.addEventListener("load", () => {
            setTimeout(hideIntro, 800);
        });

        // Fallback in case load already fired
        setTimeout(hideIntro, 1200);

        const observer = new IntersectionObserver(entries=>{
            entries.forEach(entry=>{
                if(entry.isIntersecting){
                    entry.target.classList.add("show");
                }
            });
        },{
            threshold: 0.05
        });

        const revealElements = document.querySelectorAll("#inside, #discover, #about, #ending");
        revealElements.forEach(el => observer.observe(el));
    </script>

</body>
</html>
