@extends('layouts.website')

@section('title', 'School Registration — FindEleven')

@section('content')

<div class="auth-page" style="padding: 48px 24px;">
    <div class="auth-page-bg"></div>

    <div class="auth-card auth-card-wide">
        <div class="auth-card-header">
            <h2>School Registration</h2>
            <p>Register your school for the FindEleven Cricket Platform</p>
        </div>

        <div class="auth-card-body">
            @if(session('success'))
                <div class="flash-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('school.register') }}">
                @csrf

                <!-- Account Information -->
                <div class="auth-section-label">Account Information</div>

                <div class="form-group">
                    <label for="name" class="form-label">Admin / Contact Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-input" required autofocus autocomplete="name" placeholder="Full name">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Official School Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required autocomplete="username" placeholder="school@example.com">
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" class="form-input" required autocomplete="new-password" placeholder="••••••••">
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required autocomplete="new-password" placeholder="••••••••">
                    </div>
                </div>

                <!-- School Information -->
                <div class="auth-section-label">School Information</div>

                <div class="form-group">
                    <label for="school_name" class="form-label">School Name</label>
                    <input id="school_name" type="text" name="school_name" value="{{ old('school_name') }}" class="form-input" required placeholder="e.g. Royal College Colombo">
                    @error('school_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="school_type" class="form-label">School Type</label>
                    <select id="school_type" name="school_type" class="form-select" required>
                        <option value="">Select Type</option>
                        @foreach($schoolTypes as $type)
                            <option value="{{ $type }}" {{ old('school_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('school_type')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label for="district" class="form-label">District</label>
                        <input id="district" type="text" name="district" value="{{ old('district') }}" class="form-input" required placeholder="e.g. Colombo">
                        @error('district')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="province" class="form-label">Province</label>
                        <input id="province" type="text" name="province" value="{{ old('province') }}" class="form-input" required placeholder="e.g. Western">
                        @error('province')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="school_address" class="form-label">School Address</label>
                    <textarea id="school_address" name="school_address" class="form-textarea" required placeholder="Full school address">{{ old('school_address') }}</textarea>
                    @error('school_address')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="contact_number" class="form-label">School Contact Number</label>
                    <input id="contact_number" type="text" name="contact_number" value="{{ old('contact_number') }}" class="form-input" required placeholder="+94 XX XXX XXXX">
                    @error('contact_number')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <!-- Cricket Incharge -->
                <div class="auth-section-label">Cricket Incharge Details</div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label for="cricket_incharge_name" class="form-label">Cricket Incharge Name</label>
                        <input id="cricket_incharge_name" type="text" name="cricket_incharge_name" value="{{ old('cricket_incharge_name') }}" class="form-input" required placeholder="Full name">
                        @error('cricket_incharge_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="cricket_incharge_contact" class="form-label">Cricket Incharge Contact</label>
                        <input id="cricket_incharge_contact" type="text" name="cricket_incharge_contact" value="{{ old('cricket_incharge_contact') }}" class="form-input" required placeholder="+94 XX XXX XXXX">
                        @error('cricket_incharge_contact')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="auth-note">
                    <strong>Note:</strong> Your registration will be reviewed by the admin. You will be able to login once your school is approved.
                </div>

                <div class="auth-footer-row" style="margin-top:24px;">
                    <a href="{{ route('login') }}" class="auth-link">Already registered? Log in</a>
                    <button type="submit" class="btn-gold">Register School</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
