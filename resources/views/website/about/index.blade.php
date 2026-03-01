@extends('layouts.website')

@section('title', 'About — FindEleven')

@section('content')

<!-- ── PAGE HERO ── -->
<div class="page-hero">
    <div class="page-hero-bg"></div>
    <span class="page-hero-year">ABOUT</span>
    <div class="page-hero-content">
        <div class="hero-eyebrow">Our Story</div>
        <h1 class="page-hero-title">About <span>FindEleven</span></h1>
        <p class="page-hero-desc">
            Dedicated to revolutionizing school cricket management in Sri Lanka — connecting schools, coaches, and young cricket talent across the island.
        </p>
    </div>
</div>

<!-- ── MISSION & VISION ── -->
<div class="section-divider">
    <span class="divider-text">Who We Are</span>
    <div class="divider-line"></div>
    <span class="divider-num">01</span>
</div>

<div class="about-grid">
    <div class="about-card">
        <div class="about-card-icon">🎯</div>
        <h3>Our Mission</h3>
        <p>FindEleven is dedicated to revolutionizing school cricket management in Sri Lanka. We provide a comprehensive platform that connects schools, coaches, and young cricket talent, making it easier to identify, nurture, and develop the next generation of cricket stars.</p>
        <p style="margin-top:12px;">Our mission is to create a unified ecosystem where every school cricket program can thrive, every talented player can be discovered, and every cricket enthusiast can contribute to the sport's growth.</p>
    </div>

    <div class="about-card">
        <div class="about-card-icon">🔭</div>
        <h3>Our Vision</h3>
        <p>We envision a future where every talented young cricketer in Sri Lanka has the opportunity to be discovered and nurtured. Through FindEleven, we're building a transparent, efficient, and accessible platform that bridges the gap between school cricket programs and professional opportunities.</p>
        <p style="margin-top:12px;">From the cricket grounds of Galle to the pitches of Jaffna — every aspiring cricketer deserves to be seen.</p>
    </div>
</div>

<!-- ── WHAT WE DO ── -->
<div class="section-divider">
    <span class="divider-text">What We Do</span>
    <div class="divider-line"></div>
    <span class="divider-num">02</span>
</div>

<div class="about-grid">
    <div class="about-card" style="grid-column: 1 / -1;">
        <div class="about-card-icon">📋</div>
        <h3>The FindEleven Platform</h3>
        <p>FindEleven serves as the central hub for school cricket management, offering:</p>
        <ul>
            <li>School registration and admin verification system</li>
            <li>Comprehensive player profile management</li>
            <li>Age-based categorization (U15, U17, U19)</li>
            <li>Player specialization tracking — batsmen, bowlers, all-rounders</li>
            <li>Live match scoring with real-time updates</li>
            <li>Island-wide ranking system across 25 districts</li>
            <li>Performance monitoring and analytics</li>
            <li>Talent discovery for scouts and academies</li>
        </ul>
    </div>
</div>

<!-- ── WHY CHOOSE US ── -->
<div class="section-divider">
    <span class="divider-text">Why FindEleven</span>
    <div class="divider-line"></div>
    <span class="divider-num">03</span>
</div>

<div class="about-features" style="margin-bottom:80px;">
    <div class="about-feat">
        <div class="about-feat-icon">🔒</div>
        <h4>Secure & Verified</h4>
        <p>Admin-verified school registrations ensure data authenticity and trust across the platform.</p>
    </div>
    <div class="about-feat">
        <div class="about-feat-icon">📱</div>
        <h4>Easy to Use</h4>
        <p>Intuitive interface designed for schools, coaches, and cricket administrators.</p>
    </div>
    <div class="about-feat">
        <div class="about-feat-icon">🚀</div>
        <h4>Innovative</h4>
        <p>Modern technology meets traditional cricket development — real-time scoring, rankings, and analytics.</p>
    </div>
    <div class="about-feat">
        <div class="about-feat-icon">🏏</div>
        <h4>Live Scoring</h4>
        <p>Ball-by-ball live scoring with automatic stat updates and ranking point calculations.</p>
    </div>
    <div class="about-feat">
        <div class="about-feat-icon">🏆</div>
        <h4>Rankings System</h4>
        <p>Transparent points-based ranking across multiple player categories and age groups.</p>
    </div>
    <div class="about-feat">
        <div class="about-feat-icon">🌏</div>
        <h4>Island-wide Coverage</h4>
        <p>Covering all 9 provinces and 25 districts — from Colombo to Kilinochchi.</p>
    </div>
</div>

@endsection
