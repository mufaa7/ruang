<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ruang.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root{
            --paper:#0f172a;
            --text:#ffffff;
            --muted:#94a3b8;
            --secondary:#cbd5e1;
            --accent:rgba(252, 211, 77, 0.8);
        }

        body{
            margin: 0;
            background:var(--paper);
            color:var(--text);
            font-family: 'Inter', sans-serif;
        }

        /* ORNAMENT */
        .ornament-container {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }
        .ornament-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 15% 30%, rgba(185,28,28,0.06), transparent 35%),
                        radial-gradient(circle at 100% 100%, rgba(217,119,6,0.06), transparent 40%);
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
            opacity: 0.12;
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
            background:var(--paper);
            z-index:999;
            transition:1s;
        }

        #intro-screen.hide{
            opacity:0;
            visibility:hidden;
        }

        .intro-logo{
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(64px,10vw,120px);
            letter-spacing:.02em;
            margin: 0;
        }

        /* HERO */
        #hero{
            min-height:100vh;
            display:flex;
            align-items:center;
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
            color:var(--accent);
        }

        .hero-divider{
            width:170px;
            height:1px;
            background:rgba(31,31,29,.08);
            margin:32px 0 50px;
        }

        .hero-title{
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(72px,9vw,130px);
            line-height:.9;
            font-weight:600;
            margin: 0;
        }

        .hero-ritual{
            display:flex;
            flex-direction:column;
            gap:16px;
            margin-top:60px;
        }

        .hero-ritual span{
            font-size:24px;
            color:var(--secondary);
        }

        .playing{
            animation:pulse 2.2s infinite;
        }

        @keyframes pulse{
            0%,100%{ opacity:1; }
            50%{ opacity:.45; }
        }

        .hero-button{
            display:inline-block;
            margin-top:72px;
            text-decoration:none;
            color:inherit;
            border-bottom:1px solid currentColor;
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
            line-height:1.08;
            color:rgba(255,255,255,.3);
            margin:0;
        }

        .hero-song{
            margin-top:90px;
            padding-top:24px;
            border-top:1px solid rgba(255,255,255,.1);
        }

        .song-widget {
            display: inline-flex;
            align-items: center;
            gap: 20px;
            padding: 20px 28px;
            background: linear-gradient(135deg, rgba(255,255,255,0.04), transparent);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
            transition: 0.4s;
        }

        .song-widget:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.07), transparent);
            transform: translateY(-3px);
            border-color: rgba(255,255,255,0.15);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.2);
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
        }

        .hero-song h3{
            font-family:"Cormorant Garamond",serif;
            font-size:32px;
            margin:0;
            line-height: 1;
            color: var(--text);
        }

        .hero-song p{
            margin-top:6px;
            color:var(--secondary);
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
        }

        .inside-title{
            margin-top:42px;
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(56px,6vw,82px);
            line-height:1.05;
            font-weight:600;
            color:var(--text);
        }

        .inside-text{
            margin-top:32px;
            font-size:20px;
            line-height:2;
            color:var(--secondary);
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
            background: linear-gradient(135deg, rgba(255,255,255,0.05), transparent, transparent);
            backdrop-filter: blur(6px) saturate(120%);
            -webkit-backdrop-filter: blur(6px) saturate(120%);
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 30px 80px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 1px 0 0 rgba(255, 255, 255, 0.1),
                inset -1px 0 0 rgba(255, 255, 255, 0.05),
                inset 0 -1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            padding: 36px 32px;
            transition: .4s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.04), transparent);
            pointer-events: none;
        }

        .feature-card:hover {
            transform: translateY(-4px) !important;
            box-shadow: 
                0 40px 100px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.4),
                inset 1px 0 0 rgba(255, 255, 255, 0.2),
                inset -1px 0 0 rgba(255, 255, 255, 0.1),
                inset 0 -1px 0 rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            background: linear-gradient(135deg, rgba(255,255,255,0.08), transparent, transparent);
        }

        .feature-card i {
            font-size: 28px;
            color: var(--accent);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 24px;
            box-shadow: inset 0 2px 10px rgba(255,255,255,0.05);
            text-shadow: 0 0 15px rgba(252, 211, 77, 0.5);
        }

        .feature-card h3 {
            font-family: "Cormorant Garamond", serif;
            font-size: 32px;
            margin: 0 0 12px;
            color: var(--text);
            line-height: 1.1;
        }

        .feature-card p {
            font-size: 15px;
            line-height: 1.6;
            color: var(--secondary);
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
            background:rgba(31,31,29,.08);
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
        }

        .discover-header h2{
            margin-top:24px;
            font-family:"Cormorant Garamond",serif;
            font-size:clamp(42px,5vw,64px);
            line-height:1.05;
        }

        .discover-item{
            position:relative;
            padding:48px 0;
            border-top:1px solid rgba(255,255,255,.1);
            transition:.45s;
        }

        .discover-item:last-child{
            border-bottom:1px solid rgba(255,255,255,.1);
        }

        .number{
            position:absolute;
            right:0;
            top:40px;
            font-size:140px;
            font-family:"Cormorant Garamond",serif;
            color:rgba(255,255,255,.05);
            user-select:none;
            transition:.45s;
        }

        .discover-item h3{
            font-family:"Cormorant Garamond",serif;
            font-size:48px;
            margin-bottom:24px;
            transition:.45s;
        }

        .discover-item p{
            width:360px;
            line-height:2;
            color:var(--secondary);
        }

        .discover-item:hover{
            padding-left:30px;
        }

        .discover-item:hover .number{
            color:rgba(252, 211, 77, 0.1);
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
            margin-bottom:60px;
        }

        .about-content p{
            font-size:20px;
            line-height:2;
            color:var(--secondary);
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
            color:var(--text);
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
            color:var(--text);
        }

        .ending-button{
            display:inline-block;
            text-decoration:none;
            color:var(--text);
            border-bottom:1px solid rgba(255,255,255,.2);
            padding-bottom:8px;
            transition:.3s;
        }

        .ending-button:hover{
            letter-spacing:.08em;
        }

        /* ==========================
           FOOTER
        ========================== */
        .site-footer {
            padding: 40px 0 80px;
            text-align: center;
        }

        .footer-logo {
            font-family: "Cormorant Garamond", serif;
            font-size: 32px;
            color: var(--text);
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
        window.addEventListener("load", () => {
            setTimeout(() => {
                document
                    .getElementById("intro-screen")
                    .classList.add("hide");
            }, 1800);
        });

        const observer = new IntersectionObserver(entries=>{
            entries.forEach(entry=>{
                if(entry.isIntersecting){
                    entry.target.classList.add("show");
                }
            });
        },{
            threshold: .15
        });

        const revealElements = document.querySelectorAll("#inside, #discover, #about, #ending");
        revealElements.forEach(el => observer.observe(el));
    </script>

</body>
</html>
