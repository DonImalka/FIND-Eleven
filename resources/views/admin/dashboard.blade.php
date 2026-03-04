<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>

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

        {{-- STAT CARDS --}}
        <div class="sec-head">
            <span class="sec-head-title">Overview</span>
            <div class="sec-head-line"></div>
            <span class="sec-head-sub">Season {{ date('Y') }}</span>
        </div>

        <div class="stat-row">
            {{-- Pending --}}
            <div class="stat-card card-pending">
                <div class="stat-card-top">
                    <div class="stat-card-label">Pending Schools</div>
                    <div class="stat-card-val">{{ $pendingSchoolsCount }}</div>
                    <div class="stat-card-sub">Awaiting approval</div>
                    <div class="stat-card-icon">⏳</div>
                </div>
                <a href="{{ route('admin.schools.pending') }}" class="stat-card-footer">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 6h8M6 2l4 4-4 4"/></svg>
                    Review Queue
                </a>
            </div>

            {{-- Approved --}}
            <div class="stat-card card-approved">
                <div class="stat-card-top">
                    <div class="stat-card-label">Approved Schools</div>
                    <div class="stat-card-val">{{ $approvedSchoolsCount }}</div>
                    <div class="stat-card-sub">Active this season</div>
                    <div class="stat-card-icon">✓</div>
                </div>
                <a href="{{ route('admin.schools.index') }}" class="stat-card-footer">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 6h8M6 2l4 4-4 4"/></svg>
                    View All Schools
                </a>
            </div>

            {{-- Rejected --}}
            <div class="stat-card card-rejected">
                <div class="stat-card-top">
                    <div class="stat-card-label">Rejected Schools</div>
                    <div class="stat-card-val">{{ $rejectedSchoolsCount }}</div>
                    <div class="stat-card-sub">Requires follow-up</div>
                    <div class="stat-card-icon">✗</div>
                </div>
                <a href="{{ route('admin.schools.index') }}" class="stat-card-footer">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 6h8M6 2l4 4-4 4"/></svg>
                    Review Rejected
                </a>
            </div>

            {{-- Total Players --}}
            <div class="stat-card card-total">
                <div class="stat-card-top">
                    <div class="stat-card-label">Total Players</div>
                    <div class="stat-card-val">{{ $totalPlayersCount }}</div>
                    <div class="stat-card-sub">Registered island-wide</div>
                    <div class="stat-card-icon">👥</div>
                </div>
                <a href="{{ route('admin.players.index') }}" class="stat-card-footer">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 6h8M6 2l4 4-4 4"/></svg>
                    Manage Players
                </a>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="sec-head">
            <span class="sec-head-title">Quick Actions</span>
            <div class="sec-head-line"></div>
        </div>

        <div class="quick-grid">
            <a href="{{ route('admin.schools.pending') }}" class="qa-card">
                <div class="qa-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#C8973A" stroke-width="1.5">
                        <circle cx="11" cy="11" r="9"/>
                        <path d="M11 6v5l3 3"/>
                    </svg>
                </div>
                <div>
                    <div class="qa-title">Pending Approvals</div>
                    <div class="qa-desc">Review and approve school registrations for the current season.</div>
                </div>
                <span class="qa-arrow">→</span>
            </a>

            <a href="{{ route('admin.schools.index') }}" class="qa-card">
                <div class="qa-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#1A7A4A" stroke-width="1.5">
                        <rect x="2" y="5" width="18" height="14" rx="1"/>
                        <path d="M7 2v4M15 2v4M2 10h18"/>
                    </svg>
                </div>
                <div>
                    <div class="qa-title">All Schools</div>
                    <div class="qa-desc">View and manage all registered schools across the island.</div>
                </div>
                <span class="qa-arrow">→</span>
            </a>

            <a href="{{ route('admin.players.index') }}" class="qa-card">
                <div class="qa-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#1A3A6A" stroke-width="1.5">
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M2 20c0-4.4 3.1-8 7-8M18 14l-4 4 2 4 6-8z"/>
                    </svg>
                </div>
                <div>
                    <div class="qa-title">All Players</div>
                    <div class="qa-desc">View all registered players and manage their profiles and categories.</div>
                </div>
                <span class="qa-arrow">→</span>
            </a>

            <a href="{{ route('admin.tournaments.index') }}" class="qa-card">
                <div class="qa-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#9B1D20" stroke-width="1.5">
                        <path d="M11 2l2.5 5.5 6 .8-4.3 4.2 1 6-5.2-2.7L5.8 18.5l1-6L2.5 8.3l6-.8z"/>
                    </svg>
                </div>
                <div>
                    <div class="qa-title">Tournaments</div>
                    <div class="qa-desc">Create and manage tournaments, schedules, and bracket draws.</div>
                </div>
                <span class="qa-arrow">→</span>
            </a>

            <a href="{{ route('admin.player-categories.index') }}" class="qa-card">
                <div class="qa-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#6B3FA0" stroke-width="1.5">
                        <path d="M3 8h16M3 12h12M3 16h8"/>
                    </svg>
                </div>
                <div>
                    <div class="qa-title">Player Categories</div>
                    <div class="qa-desc">Configure ranking categories: Batsman, Spinner, Fast Bowler and more.</div>
                </div>
                <span class="qa-arrow">→</span>
            </a>

            <a href="/" class="qa-card">
                <div class="qa-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#1A7A6A" stroke-width="1.5">
                        <path d="M5 3h12v14l-6-3-6 3V3z"/>
                        <path d="M9 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <div class="qa-title">Island Rankings</div>
                    <div class="qa-desc">View and update the island-wide school cricket rankings table.</div>
                </div>
                <span class="qa-arrow">→</span>
            </a>
        </div>

        {{-- RECENT ACTIVITY --}}
        <div class="sec-head">
            <span class="sec-head-title">Recent Activity</span>
            <div class="sec-head-line"></div>
        </div>

        <div class="bottom-grid">
            {{-- Pending Schools --}}
            <div class="table-card">
                <div class="table-card-head">
                    <span class="tc-title">
                        Recent Pending Schools
                        <span class="tc-count">{{ $pendingSchoolsCount }}</span>
                    </span>
                    <a href="{{ route('admin.schools.pending') }}" class="tc-action">View All →</a>
                </div>

                @if($pendingSchools->count() > 0)
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>School</th>
                                <th>District</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingSchools as $school)
                                <tr>
                                    <td>
                                        <div class="ml-name">{{ $school->school_name }}</div>
                                        <div class="ml-sub">{{ $school->user->email }}</div>
                                    </td>
                                    <td>{{ $school->district }}</td>
                                    <td style="color: var(--text-muted);">{{ $school->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div style="display: flex; gap: 6px;">
                                            <a href="{{ route('admin.schools.show', $school) }}" class="btn btn-info" style="padding: 5px 10px; font-size: 9px;">View</a>
                                            <form action="{{ route('admin.schools.approve', $school) }}" method="POST" style="display:inline;">@csrf
                                                <button type="submit" class="btn btn-success" style="padding: 5px 10px; font-size: 9px;">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.schools.reject', $school) }}" method="POST" style="display:inline;">@csrf
                                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 9px;">Reject</button>
                                            </form>
                                        </div>
                                    </td>
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
                        No pending schools at the moment.
                    </div>
                @endif
            </div>

            {{-- Recent Schools Summary --}}
            <div class="table-card">
                <div class="table-card-head">
                    <span class="tc-title">
                        All Schools
                        <span class="tc-count">{{ $approvedSchoolsCount + $rejectedSchoolsCount + $pendingSchoolsCount }}</span>
                    </span>
                    <a href="{{ route('admin.schools.index') }}" class="tc-action">Manage →</a>
                </div>

                @if($pendingSchools->count() > 0 || $approvedSchoolsCount > 0)
                    <div class="info-bar">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="8" cy="8" r="6.5"/>
                            <path d="M8 7v5M8 5v.5"/>
                        </svg>
                        {{ $approvedSchoolsCount }} approved · {{ $pendingSchoolsCount }} pending · {{ $rejectedSchoolsCount }} rejected
                    </div>
                @else
                    <div class="info-bar">
                        No schools registered yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
