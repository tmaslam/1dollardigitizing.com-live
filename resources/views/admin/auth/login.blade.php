<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>1Dollar Admin Login</title>
    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --accent: #169fe6;
            --accent-dark: #0d6ea3;
            --accent-soft: #e8f5fd;
            --line: rgba(15, 23, 42, 0.08);
            --line-strong: rgba(15, 23, 42, 0.18);
            --shadow: 0 4px 24px rgba(15, 23, 42, 0.08), 0 1px 4px rgba(15, 23, 42, 0.04);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: "Avenir Next", "Segoe UI", sans-serif;
            color: var(--ink);
            overflow-x: hidden;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        .shell {
            width: min(980px, 100%);
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.18), 0 4px 16px rgba(15, 23, 42, 0.08);
        }
        .hero {
            padding: clamp(28px, 4vw, 54px);
            background: #0f172a;
            color: #fff;
        }
        .hero span {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(22, 159, 230, 0.18);
            color: #7dd3fc;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .hero h1 { margin: 18px 0 12px; line-height: 1; }
        .hero p { margin: 0; max-width: 480px; color: #94a3b8; line-height: 1.75; }
        .hero ul { margin: 30px 0 0; padding: 0; list-style: none; display: grid; gap: 14px; color: #cbd5e1; }
        .hero li::before { content: "•"; color: var(--accent); font-weight: 900; margin-right: 10px; }
        .panel { padding: clamp(22px, 3vw, 34px); background: #fff; }
        .card {
            border-radius: 18px;
            padding: 24px;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
        }
        .card + .card { margin-top: 18px; }
        h2 { margin: 0 0 8px; font-size: 1.25rem; color: var(--ink); }
        .muted { color: var(--muted); line-height: 1.6; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
        .link-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--line-strong);
            background: #f8fafc;
            color: var(--ink);
            font-weight: 700;
            text-decoration: none;
            transition: background 0.15s;
        }
        .link-btn:hover { background: var(--accent-soft); }
        .stack { display: grid; gap: 14px; margin-top: 20px; }
        label { font-size: 0.86rem; font-weight: 700; color: var(--muted); }
        input {
            width: 100%;
            margin-top: 8px;
            border: 1px solid var(--line-strong);
            border-radius: 12px;
            padding: 13px 14px;
            background: #f8fafc;
            color: var(--ink);
            min-height: 48px;
            line-height: 1.35;
            font-family: inherit;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        input:focus {
            outline: none;
            border-color: rgba(22, 159, 230, 0.6);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(22, 159, 230, 0.14);
        }
        button[type=submit] {
            border: 0;
            border-radius: 12px;
            padding: 13px 16px;
            min-height: 46px;
            width: 100%;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            font-weight: 800;
            font-family: inherit;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1.1;
            box-shadow: 0 4px 12px rgba(13, 110, 163, 0.22);
            transition: opacity 0.15s;
        }
        button[type=submit]:hover { opacity: 0.92; }
        .alert {
            margin-bottom: 16px;
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(180, 35, 24, 0.08);
            color: #9d2d17;
            border: 1px solid rgba(180, 35, 24, 0.18);
        }
        .alert-success {
            background: rgba(21, 115, 71, 0.08);
            color: #1d6f46;
            border-color: rgba(21, 115, 71, 0.16);
        }
        @media (max-width: 920px) {
            .shell { grid-template-columns: 1fr; }
            .hero, .panel { padding: 26px; }
        }
        @media (max-width: 640px) {
            body { padding: 16px; }
            .shell { border-radius: 18px; }
            .card { border-radius: 14px; padding: 18px; }
            .hero ul { gap: 10px; }
            .actions > * { width: 100%; }
            .link-btn { width: 100%; }
        }
    </style>
</head>
<body>
<div class="shell">
    <section class="hero">
        <span>Secure Admin Access</span>
        <h1 style="display:flex;flex-direction:column;align-items:center;gap:4px;">
            <img src="{{ url('/images/logo.png') }}" alt="1 Dollar Digitizing" style="height:clamp(48px,7vw,72px);width:auto;display:block;">
            <span style="font-size:clamp(2rem,4vw,3.2rem);letter-spacing:-0.05em;line-height:1;">Admin</span>
        </h1>
        <p>Authorized access only.</p>
        <ul>
            <li>Admin portal</li>
            <li>Authorized users only</li>
            <li>Secure sign-in</li>
        </ul>
    </section>

    <section class="panel">
        @if ($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <h2>Admin Login</h2>
            <p class="muted">Use your admin username and password.</p>
            <form method="post" action="{{ url('/v/login') }}" class="stack" onsubmit="const btn=this.querySelector('button[type=submit]');if(btn.dataset.submitting)return false;btn.dataset.submitting='1';btn.disabled=true;btn.textContent='Signing in…';return true;">
                @csrf
                <div>
                    <label for="txtLogin">Username</label>
                    <input id="txtLogin" type="text" name="txtLogin" value="{{ old('txtLogin') }}" autocomplete="username" required>
                </div>
                <div>
                    <label for="txtPassword">Password</label>
                    <input id="txtPassword" type="password" name="txtPassword" autocomplete="current-password" required>
                </div>
                @include('shared.turnstile')
                <button type="submit">Sign In</button>
            </form>
            <div class="actions">
                <a class="link-btn" href="{{ url('/') }}">Back To Portal</a>
                <a class="link-btn" href="{{ url('/team') }}">Team Login</a>
                <a class="link-btn" href="{{ url('/v/forgot-password') }}">Forgot Password</a>
            </div>
        </div>
    </section>
</div>
</body>
</html>
