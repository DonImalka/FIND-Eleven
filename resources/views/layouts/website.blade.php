<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FindEleven — School Cricket Rankings')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Bebas+Neue&family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/website.css') }}">
    @stack('styles')
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
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
        <li>
            <a href="{{ route('live-scores.index') }}" class="{{ request()->routeIs('live-scores.*') ? 'active' : '' }}" style="display:flex; align-items:center; gap:8px;">
                <span class="nav-live-badge">
                    @if(isset($globalLiveCount) && $globalLiveCount > 0)<span class="live-dot"></span>@endif
                    Live Scores
                </span>
            </a>
        </li>
        <li><a href="{{ route('rankings.index') }}" class="{{ request()->routeIs('rankings.*') ? 'active' : '' }}">Rankings</a></li>
        <li><a href="{{ route('help-posts.index') }}" class="{{ request()->routeIs('help-posts.*') ? 'active' : '' }}">Help Posts</a></li>
    </ul>

    <div class="nav-actions">
        @guest
            <a href="{{ route('login') }}" class="btn-login">Login</a>
        @else
            <a href="{{ route('dashboard') }}" class="btn-dashboard">Dashboard</a>
        @endguest
    </div>
</nav>

@yield('content')

<!-- ── FOOTER ── -->
<div class="footer-band">
    <span class="footer-copy">&copy; {{ date('Y') }} FindEleven. All rights reserved.</span>
    <span class="footer-powered">Powered by <span>FindEleven</span></span>
</div>

@stack('scripts')
</body>
</html>
