<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>

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

            {{-- Statistics Cards --}}
            <div class="stats-grid">
                {{-- Pending Schools --}}
                <div class="stat-card yellow">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-label">Pending Schools</div>
                    <div class="stat-value">{{ $pendingSchoolsCount }}</div>
                </div>

                {{-- Approved Schools --}}
                <div class="stat-card green">
                    <div class="stat-icon">✓</div>
                    <div class="stat-label">Approved Schools</div>
                    <div class="stat-value">{{ $approvedSchoolsCount }}</div>
                </div>

                {{-- Rejected Schools --}}
                <div class="stat-card red">
                    <div class="stat-icon">✗</div>
                    <div class="stat-label">Rejected Schools</div>
                    <div class="stat-value">{{ $rejectedSchoolsCount }}</div>
                </div>

                {{-- Total Players --}}
                <div class="stat-card purple">
                    <div class="stat-icon">👥</div>
                    <div class="stat-label">Total Players</div>
                    <div class="stat-value">{{ $totalPlayersCount }}</div>
                </div>
            </div>

            {{-- Quick Links --}}
            <h3 class="section-title">Quick Actions</h3>
            <div class="stats-grid mb-8">
                <a href="{{ route('admin.schools.pending') }}" class="modern-card" style="text-decoration: none; padding: 24px;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">⏳</div>
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e3a8a; margin-bottom: 8px;">Pending Approvals</h3>
                    <p style="color: #64748b; font-size: 0.875rem;">Review and approve school registrations</p>
                </a>

                <a href="{{ route('admin.schools.index') }}" class="modern-card" style="text-decoration: none; padding: 24px;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">🏫</div>
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e3a8a; margin-bottom: 8px;">All Schools</h3>
                    <p style="color: #64748b; font-size: 0.875rem;">View and manage all registered schools</p>
                </a>

                <a href="{{ route('admin.players.index') }}" class="modern-card" style="text-decoration: none; padding: 24px;">
                    <div style="font-size: 2rem; margin-bottom: 12px;">🏏</div>
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e3a8a; margin-bottom: 8px;">All Players</h3>
                    <p style="color: #64748b; font-size: 0.875rem;">View all registered players</p>
                </a>
            </div>

            {{-- Pending Schools Table --}}
            <h3 class="section-title">Recent Pending Schools</h3>
            <div class="modern-table-container">
                <div style="padding: 24px;">
                    @if($pendingSchools->count() > 0)
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>School Name</th>
                                    <th>District</th>
                                    <th>Type</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingSchools as $school)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600; color: #1e293b;">{{ $school->school_name }}</div>
                                            <div style="font-size: 0.875rem; color: #64748b;">{{ $school->user->email }}</div>
                                        </td>
                                        <td>{{ $school->district }}</td>
                                        <td>{{ $school->school_type }}</td>
                                        <td>{{ $school->created_at->diffForHumans() }}</td>
                                        <td>
                                            <div style="display: flex; gap: 8px;">
                                                <a href="{{ route('admin.schools.show', $school) }}" class="btn btn-info" style="padding: 6px 12px; font-size: 0.8rem;">View</a>
                                                <form action="{{ route('admin.schools.approve', $school) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" style="padding: 6px 12px; font-size: 0.8rem;">Approve</button>
                                                </form>
                                                <form action="{{ route('admin.schools.reject', $school) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.8rem;">Reject</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert info">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            No pending schools at the moment.
                        </div>
                    @endif
                </div>
            </div>
    </div>
</x-app-layout>
