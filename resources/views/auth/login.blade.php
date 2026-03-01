@extends('layouts.website')

@section('title', 'Login — FindEleven')

@section('content')

<div class="auth-page">
    <div class="auth-page-bg"></div>

    <div class="auth-card">
        <div class="auth-card-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your FindEleven account</p>
        </div>

        <div class="auth-card-body">
            {{-- Session Status --}}
            @if(session('status'))
                <div class="flash-success">{{ session('status') }}</div>
            @endif

            {{-- Success Message --}}
            @if(session('success'))
                <div class="flash-success">{{ session('success') }}</div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="flash-error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required autofocus autocomplete="username" placeholder="your@email.com">
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-input" required autocomplete="current-password" placeholder="••••••••">
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>

                <div class="auth-footer-row">
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
                    @endif
                    <button type="submit" class="btn-gold">Log In</button>
                </div>
            </form>

            <div class="auth-divider">
                <p>Don't have an account?</p>
                <a href="{{ route('register') }}" class="btn-outline">Register Now</a>
            </div>
        </div>
    </div>
</div>

@endsection
