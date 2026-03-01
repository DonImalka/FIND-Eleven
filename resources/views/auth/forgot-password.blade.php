@extends('layouts.website')

@section('title', 'Forgot Password — FindEleven')

@section('content')

<div class="auth-page">
    <div class="auth-page-bg"></div>

    <div class="auth-card">
        <div class="auth-card-header">
            <h2>Forgot Password</h2>
            <p>We'll send you a reset link</p>
        </div>

        <div class="auth-card-body">
            <p style="font-size:13px; color:var(--muted); margin-bottom:20px; line-height:1.7;">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
            </p>

            @if(session('status'))
                <div class="flash-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required autofocus placeholder="your@email.com">
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="auth-footer-row">
                    <a href="{{ route('login') }}" class="auth-link">← Back to login</a>
                    <button type="submit" class="btn-gold">Send Reset Link</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
