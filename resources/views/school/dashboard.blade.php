<x-app-layout>
    <x-slot name="title">School Dashboard</x-slot>

    <div>
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert success">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- School Info Card --}}
            <div class="info-card mb-8">
                <div class="info-card-header">
                    <div>
                        <h3 class="info-title">{{ $school->school_name }}</h3>
                        <p class="info-subtitle">📍 {{ $school->district }}, {{ $school->province }}</p>
                        <p class="info-subtitle">🏫 {{ $school->school_type }} School</p>
                    </div>
                    <span class="badge success">✓ Approved</span>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-label">Total Players</div>
                    <div class="stat-value">{{ $totalPlayers }}</div>
                </div>

                @foreach(['U13' => 'indigo', 'U15' => 'purple', 'U17' => 'green', 'U19' => 'yellow'] as $category => $color)
                    <div class="stat-card {{ $color }}">
                        <div class="stat-icon">🏏</div>
                        <div class="stat-label">{{ $category }} Players</div>
                        <div class="stat-value">{{ $playersByCategory[$category] ?? 0 }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Quick Links --}}
            <h3 class="section-title">Quick Actions</h3>
            <div class="stats-grid mb-8">
                <a href="{{ route('school.players.create') }}" class="modern-card" style="text-decoration: none; padding: 24px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">➕</div>
                    <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 8px;">Add New Player</h3>
                    <p style="opacity: 0.95; font-size: 0.875rem;">Register a new player for your school</p>
                </a>

                <a href="{{ route('school.players.index') }}" class="modern-card" style="text-decoration: none; padding: 24px;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">🏏</div>
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e3a8a; margin-bottom: 8px;">Manage Players</h3>
                    <p style="color: #64748b; font-size: 0.875rem;">View and manage all your players</p>
                </a>

                <a href="{{ route('school.profile.index') }}" class="modern-card" style="text-decoration: none; padding: 24px;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">⚙️</div>
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e3a8a; margin-bottom: 8px;">School Profile</h3>
                    <p style="color: #64748b; font-size: 0.875rem;">View and update school information</p>
                </a>
            </div>

            {{-- Recent Players --}}
            <h3 class="section-title">Recent Players</h3>
            <div class="modern-table-container">
                <div style="padding: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e3a8a;">Latest Registrations</h3>
                        <a href="{{ route('school.players.index') }}" class="btn btn-primary">View All →</a>
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
                                            <a href="{{ route('school.players.show', $player) }}" style="color: #2563eb; font-weight: 600; text-decoration: none;">
                                                {{ $player->full_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge success" style="font-size: 0.75rem;">
                                                {{ $player->age_category }}
                                            </span>
                                        </td>
                                        <td>{{ $player->player_category }}</td>
                                        <td style="color: #64748b;">{{ $player->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert info">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            No players registered yet. <a href="{{ route('school.players.create') }}" style="color: #2563eb; font-weight: 600;">Add your first player</a>.
                        </div>
                    @endif
                </div>
            </div>
    </div>
</x-app-layout>
