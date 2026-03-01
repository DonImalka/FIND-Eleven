<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FindEleven') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Bebas+Neue&family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/website.css') }}">

        <!-- Scripts (Breeze components still need Tailwind) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Override Breeze/Tailwind for dark theme compatibility */
            body { background: var(--deep-navy) !important; color: var(--cream) !important; }
            .guest-wrap { min-height: calc(100vh - 72px - 73px); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 48px 24px; position: relative; }
            .guest-wrap::before { content:''; position:absolute; inset:0; background: radial-gradient(ellipse 60% 60% at 50% 40%, rgba(200,151,58,0.05) 0%, transparent 70%); pointer-events:none; }
            .guest-card { position: relative; z-index:2; background: rgba(255,255,255,0.025); border: 1px solid rgba(200,151,58,0.2); width: 100%; max-width: 440px; overflow: hidden; }
            .guest-card::before { content:''; position:absolute; top:0;left:0;right:0; height:3px; background: linear-gradient(90deg, #9B1D20, #C8973A, #9B1D20); }
            .guest-card-inner { padding: 32px; }
            /* Tailwind overrides for dark bg */
            .guest-card label, .guest-card .text-sm { color: var(--muted) !important; }
            .guest-card input[type="text"], .guest-card input[type="email"], .guest-card input[type="password"] {
                background: rgba(255,255,255,0.04) !important; border-color: rgba(255,255,255,0.1) !important; color: var(--cream) !important;
            }
            .guest-card input:focus { border-color: #C8973A !important; box-shadow: none !important; --tw-ring-color: transparent !important; }
            .guest-card .text-gray-600, .guest-card .text-gray-400, .guest-card .text-gray-500 { color: var(--muted) !important; }
            .guest-card .text-gray-800, .guest-card .text-gray-900, .guest-card .text-gray-700 { color: var(--cream) !important; }
            .guest-card .bg-gray-100 { background: transparent !important; }
            .guest-card .bg-gray-50 { background: rgba(200,151,58,0.05) !important; }
            .guest-card .border-gray-200, .guest-card .border-gray-300 { border-color: rgba(200,151,58,0.2) !important; }
            .guest-card a { color: #C8973A !important; }
            .guest-card button[type="submit"], .guest-card .inline-flex.items-center.px-4 {
                background: #C8973A !important; color: #060E1A !important; border: none !important; border-radius: 0 !important;
                font-family: 'IBM Plex Sans', sans-serif !important; font-weight: 700 !important; letter-spacing: 0.1em !important; text-transform: uppercase !important; font-size: 11px !important;
            }
            .guest-card button[type="submit"]:hover { background: #E8B84B !important; }
            .guest-card .text-green-600, .guest-card .text-green-700 { color: #22C55E !important; }
            .guest-card .bg-green-100 { background: rgba(34,197,94,0.1) !important; border-color: rgba(34,197,94,0.25) !important; }
            .guest-card .text-red-600 { color: #EF4444 !important; }
            .guest-card .bg-red-100 { background: rgba(239,68,68,0.1) !important; border-color: rgba(239,68,68,0.25) !important; }
            .guest-card .bg-yellow-50 { background: rgba(200,151,58,0.08) !important; }
            .guest-card .border-yellow-200 { border-color: rgba(200,151,58,0.2) !important; }
            .guest-card .text-yellow-800 { color: #F5D98A !important; }
            .guest-card select { background: rgba(255,255,255,0.04) !important; border-color: rgba(255,255,255,0.1) !important; color: var(--cream) !important; }
            .guest-card select:focus { border-color: #C8973A !important; box-shadow: none !important; --tw-ring-color: transparent !important; }
            .guest-card textarea { background: rgba(255,255,255,0.04) !important; border-color: rgba(255,255,255,0.1) !important; color: var(--cream) !important; }
            .guest-card textarea:focus { border-color: #C8973A !important; box-shadow: none !important; --tw-ring-color: transparent !important; }
            .guest-card input[type="checkbox"] { accent-color: #C8973A; }
        </style>
    </head>
    <body>
        <!-- ── NAVIGATION ── -->
        <nav class="site-nav">
            <a href="{{ route('home') }}" class="nav-brand">
                <svg class="crest" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="22" cy="22" r="20" stroke="#C8973A" stroke-width="1.5"/>
                    <circle cx="22" cy="22" r="16" stroke="rgba(200,151,58,0.35)" stroke-width="0.5"/>
                    <path d="M14 26 Q12 22 15 19 Q17 16 20 17 Q22 15 24 16 Q27 15 29 18 Q32 22 30 26 Q28 30 22 31 Q16 30 14 26Z" fill="rgba(200,151,58,0.15)" stroke="#C8973A" stroke-width="0.8"/>
                    <path d="M19 20 Q21 18 23 19 Q25 20 24 22 Q23 24 21 24 Q19 24 19 22Z" fill="#C8973A" opacity="0.6"/>
                    <line x1="22" y1="12" x2="22" y2="32" stroke="rgba(200,151,58,0.5)" stroke-width="0.5"/>
                    <line x1="15" y1="22" x2="29" y2="22" stroke="rgba(200,151,58,0.5)" stroke-width="0.5"/>
                    <circle cx="16" cy="15" r="1" fill="#C8973A" opacity="0.8"/>
                    <circle cx="22" cy="12" r="1" fill="#C8973A" opacity="0.8"/>
                    <circle cx="28" cy="15" r="1" fill="#C8973A" opacity="0.8"/>
                </svg>
                <div class="brand-text">
                    <span class="brand-top">FindEleven</span>
                    <span class="brand-sub">School Island Rankings</span>
                </div>
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About</a></li>
                <li><a href="{{ route('live-scores.index') }}">Live Scores</a></li>
                <li><a href="{{ route('rankings.index') }}">Rankings</a></li>
            </ul>
            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn-login">Login</a>
            </div>
        </nav>

        <div class="guest-wrap">
            <div class="guest-card">
                <div class="guest-card-inner">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- ── FOOTER ── -->
        <div class="footer-band">
            <span class="footer-copy">&copy; {{ date('Y') }} FindEleven. All rights reserved.</span>
            <span class="footer-powered">Powered by <span>FindEleven</span></span>
        </div>
    </body>
</html>
