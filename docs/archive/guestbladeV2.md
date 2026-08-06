<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'RUANG — Masuk')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        :root{
            --bg:#0D0B09;
            --surface:rgba(255,255,255,.04);
            --surface-2:rgba(255,255,255,.08);
            --primary:#FFB000;
            --primary-glow:#FF6A00;
            --text:#F7F3ED;
            --text-soft:#A89B8C;
            --border:rgba(255,176,0,.18);
        }

        html,body{
            font-family:"Plus Jakarta Sans",sans-serif;
            background:var(--bg);
            color:var(--text);
            min-height:100vh;
            overflow-x:hidden;
        }

        .font-display{ font-family:"Space Grotesk",sans-serif; }

        /* Background */
        .bg-auth{
            position:fixed;
            inset:0;
            z-index:-10;
            perspective:1200px;
            overflow:hidden;
        }

        .bg-auth .layer{
            position:absolute;
            inset:-20%;
            background:
                radial-gradient(circle at 20% 30%,rgba(255,176,0,.16),transparent 35%),
                radial-gradient(circle at 80% 20%,rgba(255,106,0,.12),transparent 30%),
                radial-gradient(circle at 50% 80%,rgba(255,200,80,.08),transparent 40%);
            transform-style:preserve-3d;
            transition:transform .15s ease-out;
        }

        .bg-auth .grid{
            position:absolute;
            inset:-50%;
            background-image:
                linear-gradient(rgba(255,176,0,.04) 1px,transparent 1px),
                linear-gradient(90deg,rgba(255,176,0,.04) 1px,transparent 1px);
            background-size:120px 120px;
            transform:rotateX(60deg) translateZ(-200px);
            mask-image:radial-gradient(circle at 50% 50%,black 30%,transparent 80%);
            -webkit-mask-image:radial-gradient(circle at 50% 50%,black 30%,transparent 80%);
        }

        .orb{
            position:absolute;
            border-radius:50%;
            filter:blur(80px);
            opacity:.25;
        }
        .orb.one{ width:500px; height:500px; background:#FFB000; top:10%; left:10%; }
        .orb.two{ width:400px; height:400px; background:#FF6A00; top:40%; right:5%; }
        .orb.three{ width:350px; height:350px; background:#FFC333; bottom:10%; left:35%; }

        /* Mascot */
        .mascot-wrap{
            position:fixed;
            left:-120px;
            top:50%;
            transform:translateY(-50%);
            width:520px;
            z-index:-1;
            opacity:.9;
            pointer-events:none;
            filter:drop-shadow(0 40px 80px rgba(0,0,0,.5));
            transition:transform .15s ease-out;
        }

        @media(max-width:1200px){ .mascot-wrap{ display:none; } }

        /* Layout */
        .auth-main{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:flex-end;
            padding:40px 6vw;
        }

        .auth-card{
            width:100%;
            max-width:480px;
            background:rgba(255,255,255,.03);
            backdrop-filter:blur(30px);
            border:1px solid rgba(255,255,255,.08);
            border-radius:32px;
            padding:48px;
            box-shadow:0 40px 100px rgba(0,0,0,.4);
        }

        @media(max-width:640px){
            .auth-main{ justify-content:center; padding:24px; }
            .auth-card{ padding:32px 24px; border-radius:24px; }
        }

        /* Form elements */
        .input-group{ margin-bottom:24px; }
        .input-label{
            display:block;
            margin-bottom:10px;
            font-size:13px;
            font-weight:600;
            color:var(--text-soft);
        }
        .input-field{
            width:100%;
            height:54px;
            padding:0 18px;
            border-radius:14px;
            border:1px solid rgba(255,255,255,.1);
            background:rgba(255,255,255,.04);
            color:var(--text);
            font-size:15px;
            outline:none;
            transition:.3s;
        }
        .input-field::placeholder{ color:rgba(168,155,140,.6); }
        .input-field:focus{
            border-color:var(--border);
            background:rgba(255,255,255,.07);
            box-shadow:0 0 20px rgba(255,176,0,.1);
        }

        .password-wrap{ position:relative; }
        .toggle-pass{
            position:absolute;
            right:16px;
            top:50%;
            transform:translateY(-50%);
            background:none;
            border:none;
            color:var(--text-soft);
            cursor:pointer;
            font-size:20px;
            transition:.3s;
        }
        .toggle-pass:hover{ color:var(--primary); }

        .btn-primary{
            width:100%;
            height:54px;
            border:none;
            border-radius:14px;
            background:linear-gradient(135deg,var(--primary-glow),var(--primary));
            color:#0D0B09;
            font-size:15px;
            font-weight:800;
            cursor:pointer;
            box-shadow:0 0 30px rgba(255,176,0,.35);
            transition:.35s cubic-bezier(.34,1.56,.64,1);
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }
        .btn-primary:hover{
            transform:translateY(-3px);
            box-shadow:0 0 50px rgba(255,176,0,.55);
        }

        .link{ color:var(--primary); text-decoration:none; font-weight:700; transition:.3s; }
        .link:hover{ text-decoration:underline; }

        .divider{
            display:flex;
            align-items:center;
            gap:16px;
            margin:28px 0;
            color:var(--text-soft);
            font-size:12px;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.15em;
        }
        .divider::before,.divider::after{ content:""; flex:1; height:1px; background:rgba(255,255,255,.1); }

        .alert{
            padding:14px 18px;
            border-radius:12px;
            font-size:13px;
            margin-bottom:20px;
        }
        .alert-success{ background:rgba(16,185,129,.12); border:1px solid rgba(16,185,129,.25); color:#34D399; }
        .alert-error{ background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.25); color:#F87171; }

        .logo-top{
            position:fixed;
            top:28px;
            left:40px;
            display:flex;
            align-items:center;
            gap:10px;
            font-family:"Space Grotesk",sans-serif;
            font-size:22px;
            font-weight:700;
            color:var(--text);
            text-decoration:none;
            z-index:100;
        }
        .logo-top i{ color:var(--primary); font-size:24px; }
        @media(max-width:640px){ .logo-top{ left:24px; top:20px; font-size:18px; } }
    </style>
</head>
<body>

    <a href="{{ url('/') }}" class="logo-top">
        <i class="ph-fill ph-house-line"></i>
        ruang.
    </a>

    <div class="bg-auth" id="bgAuth">
        <div class="layer" id="bgLayer">
            <div class="grid"></div>
            <div class="orb one"></div>
            <div class="orb two"></div>
            <div class="orb three"></div>
        </div>
    </div>

    <img src="{{ asset('images/duck.png') }}" class="mascot-wrap" id="mascot" alt="RUANG Mascot">

    <main class="auth-main">
        <div class="auth-card">
            {{ $slot }}
        </div>
    </main>

    <script>
        const bgLayer = document.getElementById('bgLayer');
        const mascot = document.getElementById('mascot');
        let ticking = false;

        document.addEventListener('mousemove', (e) => {
            if(ticking) return;
            ticking = true;
            requestAnimationFrame(() => {
                const x = (e.clientX / window.innerWidth - .5) * 2;
                const y = (e.clientY / window.innerHeight - .5) * 2;
                if(bgLayer) bgLayer.style.transform = `rotateY(${x * 3}deg) rotateX(${-y * 3}deg)`;
                if(mascot) mascot.style.transform = `translateY(-50%) rotateY(${x * 8}deg) rotateX(${-y * 5}deg)`;
                ticking = false;
            });
        });
    </script>
</body>
</html>
