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
            --paper:#F7F5F1;
            --text:#1F1F1D;
            --muted:#7C756C;
            --secondary:#4F4A44;
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
            background: radial-gradient(circle at 15% 30%, rgba(168,85,247,0.03), transparent 35%),
                        radial-gradient(circle at 100% 100%, rgba(99,102,241,0.03), transparent 40%);
        }
        .ornament-vinyl {
            position: absolute;
            left: -420px;
            top: 50%;
            width: 1100px;
            height: 1100px;
            opacity: 0.06;
            filter: blur(4px);
            mix-blend-mode: multiply;
            transform: translateY(-50%);
        }
        .ornament-vinyl-inner {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: black;
            box-shadow: 0 0 120px rgba(0,0,0,0.9);
        }
        .ornament-vinyl-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid #262626;
        }
        .ornament-vinyl-center {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 144px;
            height: 144px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            background: #404040;
            border: 14px solid #171717;
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
            color:var(--muted);
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
            color:rgba(31,31,29,.18);
            margin:0;
        }

        .hero-song{
            margin-top:90px;
            padding-top:24px;
            border-top:1px solid rgba(31,31,29,.08);
        }

        .hero-song small{
            display:block;
            letter-spacing:.25em;
            text-transform:uppercase;
            color:var(--muted);
            margin-bottom:18px;
        }

        .hero-song h3{
            font-family:"Cormorant Garamond",serif;
            font-size:34px;
            margin:0;
        }

        .hero-song p{
            margin-top:8px;
            color:var(--secondary);
            margin-bottom: 0;
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
            color:var(--muted);
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
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            text-align: left;
        }

        .feature-card {
            background: #fff;
            border: 1px solid rgba(31,31,29,0.06);
            padding: 36px 32px;
            border-radius: 20px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.02);
            transition: .4s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-4px) !important; /* overrides the initial transform when hovered */
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.04);
            border-color: rgba(31,31,29,0.15);
        }

        .feature-card i {
            font-size: 32px;
            color: var(--text);
            margin-bottom: 24px;
            display: inline-block;
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
        }

        .discover-header{
            margin-bottom:60px;
        }

        .discover-header span{
            text-transform:uppercase;
            letter-spacing:.35em;
            color:var(--muted);
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
            border-top:1px solid rgba(31,31,29,.08);
            transition:.45s;
        }

        .discover-item:last-child{
            border-bottom:1px solid rgba(31,31,29,.08);
        }

        .number{
            position:absolute;
            right:0;
            top:40px;
            font-size:140px;
            font-family:"Cormorant Garamond",serif;
            color:rgba(31,31,29,.10);
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
            color:rgba(31,31,29,.10);
        }

        .discover-item:hover h3{
            letter-spacing:.02em;
        }

        /* ==========================
           ABOUT
        ========================== */
        #about{
            padding:220px 0;
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
            color:var(--muted);
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
            border-bottom:1px solid rgba(31,31,29,.2);
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
        <div class="ornament-vinyl">
            <div class="ornament-vinyl-inner">
                <div class="ornament-vinyl-ring" style="inset: 40px;"></div>
                <div class="ornament-vinyl-ring" style="inset: 80px;"></div>
                <div class="ornament-vinyl-ring" style="inset: 120px;"></div>
                <div class="ornament-vinyl-ring" style="inset: 160px;"></div>
                <div class="ornament-vinyl-ring" style="inset: 200px;"></div>
                <div class="ornament-vinyl-ring" style="inset: 240px;"></div>
                <div class="ornament-vinyl-ring" style="inset: 280px;"></div>
                <div class="ornament-vinyl-center"></div>
            </div>
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

        const inside = document.querySelector("#inside");
        if (inside) {
            const observer = new IntersectionObserver(entries=>{
                entries.forEach(entry=>{
                    if(entry.isIntersecting){
                        inside.classList.add("show");
                    }
                });
            },{
                threshold:.45
            });
            observer.observe(inside);
        }
    </script>

</body>
</html>
