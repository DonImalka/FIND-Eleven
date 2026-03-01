@extends('layouts.website')

@section('title', 'Register — FindEleven')

@section('content')

<!-- ── PAGE HERO ── -->
<div class="page-hero">
    <div class="page-hero-bg"></div>
    <span class="page-hero-year">JOIN</span>
    <div class="page-hero-content">
        <div class="hero-eyebrow">Get Started</div>
        <h1 class="page-hero-title">Choose Your <span>Role</span></h1>
        <p class="page-hero-desc">Select the role that best describes you to proceed with registration.</p>
    </div>
</div>

<div class="section-divider">
    <span class="divider-text">Registration</span>
    <div class="divider-line"></div>
</div>

<div class="register-selection" style="padding-bottom: 80px;">
    <div class="role-cards">
        <!-- School Registration -->
        <a href="{{ route('school.register') }}" class="role-card">
            <div class="role-card-icon">🏫</div>
            <h3>School</h3>
            <p>Register your school to manage cricket programs and register student-athletes.</p>
            <span class="btn-gold" style="width:100%; text-align:center;">Register as School</span>
        </a>

        <!-- Player (disabled) -->
        <div class="role-card disabled">
            <div class="role-card-icon">🏏</div>
            <h3>Player</h3>
            <p>Players are registered by their respective schools. Contact your school's cricket incharge.</p>
            <span class="btn-gold" style="width:100%; text-align:center; background:rgba(255,255,255,0.1); color:var(--muted); cursor:not-allowed;">Registered by Schools</span>
        </div>

        <!-- Admin (disabled) -->
        <div class="role-card disabled">
            <div class="role-card-icon">👨‍💼</div>
            <h3>Admin</h3>
            <p>Admin accounts are created by system administrators only. Contact support for assistance.</p>
            <span class="btn-gold" style="width:100%; text-align:center; background:rgba(255,255,255,0.1); color:var(--muted); cursor:not-allowed;">By Invitation Only</span>
        </div>
    </div>

    <div style="text-align:center; margin-top:32px;">
        <a href="{{ route('login') }}" class="auth-link">Already have an account? Log in →</a>
    </div>
</div>

@endsection
