<x-app-layout>
    <x-slot name="title">School Dashboard</x-slot>

    <div>
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert success">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="8" cy="8" r="6.5"/>
                    <path d="M5.5 8l2 2 3.5-3.5"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- School Info Card --}}
        <div class="info-card" style="margin-bottom: 32px;">
            <div class="info-card-header">
                <div>
                    <h3 class="info-title">{{ $school->school_name }}</h3>
                    <p class="info-subtitle">📍 {{ $school->district }}, {{ $school->province }}</p>
                    <p class="info-subtitle">🏫 {{ $school->school_type }} School</p>
                </div>
                <span class="badge success">✓ Approved</span>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="sec-head">
            <span class="sec-head-title">Squad Overview</span>
            <div class="sec-head-line"></div>
            <span class="sec-head-sub">Season {{ date('Y') }}</span>
        </div>

        <div class="stats-grid" style="margin-bottom: 40px;">
            <div class="stat-card card-total">
                <div class="stat-card-top">
                    <div class="stat-card-label">Total Players</div>
                    <div class="stat-card-val">{{ $totalPlayers }}</div>
                    <div class="stat-card-sub">All categories</div>
                    <div class="stat-card-icon">👥</div>
                </div>
                <a href="{{ route('school.players.index') }}" class="stat-card-footer">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 6h8M6 2l4 4-4 4"/></svg>
                    View All
                </a>
            </div>

            @php
                $catColors = ['U13' => 'indigo', 'U15' => 'purple', 'U17' => 'green', 'U19' => 'yellow'];
            @endphp

            @foreach($catColors as $category => $color)
                <div class="stat-card {{ $color }}">
                    <div class="stat-card-top">
                        <div class="stat-card-label">{{ $category }} Players</div>
                        <div class="stat-card-val">{{ $playersByCategory[$category] ?? 0 }}</div>
                        <div class="stat-card-sub">{{ $category }} age group</div>
                        <div class="stat-card-icon">🏏</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="sec-head">
            <span class="sec-head-title">Quick Actions</span>
            <div class="sec-head-line"></div>
        </div>

        <div class="quick-grid" style="margin-bottom: 40px;">
            <a href="{{ route('school.players.create') }}" class="qa-card" style="background: var(--navy); color: var(--cream);">
                <div class="qa-icon-wrap" style="background: rgba(200,151,58,0.15); border-color: rgba(200,151,58,0.3);">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#C8973A" stroke-width="1.5">
                        <circle cx="11" cy="11" r="9"/>
                        <path d="M11 7v8M7 11h8"/>
                    </svg>
                </div>
                <div>
                    <div class="qa-title" style="color: var(--gold-light);">Add New Player</div>
                    <div class="qa-desc" style="color: rgba(255,255,255,0.6);">Register a new player for your school</div>
                </div>
                <span class="qa-arrow" style="color: var(--gold-light);">→</span>
            </a>

            <a href="{{ route('school.players.index') }}" class="qa-card">
                <div class="qa-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#1A3A6A" stroke-width="1.5">
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M2 20c0-4.4 3.1-8 7-8M18 14l-4 4 2 4 6-8z"/>
                    </svg>
                </div>
                <div>
                    <div class="qa-title">Manage Players</div>
                    <div class="qa-desc">View and manage all your registered players</div>
                </div>
                <span class="qa-arrow">→</span>
            </a>

            <a href="{{ route('school.profile.index') }}" class="qa-card">
                <div class="qa-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#1A7A4A" stroke-width="1.5">
                        <rect x="2" y="2" width="18" height="18" rx="1"/>
                        <path d="M2 8h18M8 8v12"/>
                    </svg>
                </div>
                <div>
                    <div class="qa-title">School Profile</div>
                    <div class="qa-desc">View and update school information</div>
                </div>
                <span class="qa-arrow">→</span>
            </a>
        </div>

        {{-- RECENT PLAYERS TABLE --}}
        <div class="sec-head">
            <span class="sec-head-title">Recent Players</span>
            <div class="sec-head-line"></div>
        </div>

        <div class="table-card">
            <div class="table-card-head">
                <span class="tc-title">
                    Latest Registrations
                    <span class="tc-count">{{ $recentPlayers->count() }}</span>
                </span>
                <a href="{{ route('school.players.index') }}" class="tc-action">View All →</a>
            </div>

            @if($recentPlayers->count() > 0)
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age Category</th>
                            <th>Player Category</th>
                            <th>Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPlayers as $player)
                            <tr>
                                <td>
                                    <a href="{{ route('school.players.show', $player) }}" class="ml-name" style="text-decoration: none;">
                                        {{ $player->full_name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge success">{{ $player->age_category }}</span>
                                </td>
                                <td>{{ $player->player_category }}</td>
                                <td style="color: var(--text-muted);">{{ $player->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="info-bar">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="8" cy="8" r="6.5"/>
                        <path d="M8 7v5M8 5v.5"/>
                    </svg>
                    No players registered yet.
                    <a href="{{ route('school.players.create') }}" style="color: var(--gold); font-weight: 600; margin-left: 4px;">Add your first player →</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
