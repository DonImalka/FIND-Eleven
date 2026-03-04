<x-app-layout>
    <x-slot name="title">Player Dashboard</x-slot>

    <div>
        {{-- Welcome Card --}}
        <div class="welcome-card" style="margin-bottom: 32px;">
            <h3>Welcome, {{ auth()->user()->name }}! 🏏</h3>
            <p>This is your player dashboard. You can create help posts to request support and share your story.</p>
        </div>

        @if(session('error'))
            <div class="alert danger">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="8" cy="8" r="6.5"/>
                    <path d="M8 5v4M8 11v.5"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($player)
            {{-- Stats Row --}}
            <div class="sec-head">
                <span class="sec-head-title">Your Activity</span>
                <div class="sec-head-line"></div>
            </div>

            <div class="stats-grid" style="margin-bottom: 40px;">
                <div class="stat-card card-total">
                    <div class="stat-card-top">
                        <div class="stat-card-label">Total Help Posts</div>
                        <div class="stat-card-val">{{ $helpPostCount }}</div>
                        <div class="stat-card-sub">All time</div>
                    </div>
                </div>
                <div class="stat-card card-pending">
                    <div class="stat-card-top">
                        <div class="stat-card-label">Pending Approval</div>
                        <div class="stat-card-val">{{ $pendingCount }}</div>
                        <div class="stat-card-sub">Awaiting review</div>
                    </div>
                </div>
                <div class="stat-card card-approved">
                    <div class="stat-card-top">
                        <div class="stat-card-label">Approved & Live</div>
                        <div class="stat-card-val">{{ $approvedCount }}</div>
                        <div class="stat-card-sub">Visible to public</div>
                    </div>
                </div>
            </div>

            {{-- Player Profile Info --}}
            <div class="sec-head">
                <span class="sec-head-title">Your Profile</span>
                <div class="sec-head-line"></div>
            </div>

            <div class="info-card" style="margin-bottom: 32px;">
                <h3 class="info-title" style="margin-bottom: 24px;">Player Details</h3>
                <div class="details-list">
                    <dl style="display: grid; grid-template-columns: auto 1fr; gap: 12px 24px;">
                        <dt>👤 Name:</dt>
                        <dd>{{ $player->full_name }}</dd>

                        <dt>🏫 School:</dt>
                        <dd>{{ $player->school->school_name ?? '—' }}</dd>

                        <dt>🎂 Age Category:</dt>
                        <dd><span class="badge success">{{ $player->age_category }}</span></dd>

                        <dt>🏏 Category:</dt>
                        <dd>{{ $player->player_category }}</dd>

                        <dt>🏏 Batting:</dt>
                        <dd>{{ $player->batting_style }}</dd>

                        <dt>🎯 Bowling:</dt>
                        <dd>{{ $player->bowling_style }}</dd>

                        @if($player->jersey_number)
                            <dt>👕 Jersey:</dt>
                            <dd>#{{ $player->jersey_number }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('player.help-posts.create') }}" class="btn btn-primary">
                    ✍️ Create Help Post
                </a>
                <a href="{{ route('player.help-posts.index') }}" class="btn btn-secondary">
                    📋 My Help Posts
                </a>
            </div>
        @else
            <div class="info-card">
                <p style="color: var(--text-muted);">No player profile is linked to your account. Please contact your school's cricket incharge.</p>
            </div>
        @endif
    </div>
</x-app-layout>
