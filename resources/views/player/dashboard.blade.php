<x-app-layout>
    <x-slot name="title">Player Dashboard</x-slot>

    <div>
            {{-- Welcome Card --}}
            <div class="welcome-card mb-8">
                <h3>Welcome, {{ auth()->user()->name }}! 🏏</h3>
                <p style="margin-top: 12px;">
                    This is your player dashboard. Players are registered and managed by their respective schools.
                </p>
                <p style="margin-top: 8px;">
                    Contact your school's cricket incharge for any updates to your profile.
                </p>
            </div>

            {{-- Account Details Card --}}
            <div class="info-card">
                <h3 class="info-title" style="margin-bottom: 24px;">Your Account Details</h3>
                <div class="details-list">
                    <dl style="display: grid; grid-template-columns: auto 1fr; gap: 16px 24px;">
                        <dt>👤 Name:</dt>
                        <dd>{{ auth()->user()->name }}</dd>
                        
                        <dt>✉️ Email:</dt>
                        <dd>{{ auth()->user()->email }}</dd>
                        
                        <dt>🎯 Role:</dt>
                        <dd>
                            <span class="badge success" style="font-size: 0.875rem;">
                                {{ strtoupper(auth()->user()->role) }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>
    </div>
</x-app-layout>
