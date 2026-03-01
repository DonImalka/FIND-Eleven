<x-app-layout>
    <x-slot name="title">Player Dashboard</x-slot>

    <div>
        {{-- Welcome Card --}}
        <div class="welcome-card mb-8">
            <h3>Welcome, {{ auth()->user()->name }}! 🏏</h3>
            <p style="margin-top: 12px;">
                This is your player dashboard. You can create help posts to request support and share your story.
            </p>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats Row --}}
        @if($player)
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
                <div class="info-card" style="text-align: center; padding: 24px;">
                    <div style="font-size: 2rem; font-weight: 700; color: #4F46E5;">{{ $helpPostCount }}</div>
                    <div style="font-size: 0.875rem; color: #6B7280; margin-top: 4px;">Total Help Posts</div>
                </div>
                <div class="info-card" style="text-align: center; padding: 24px;">
                    <div style="font-size: 2rem; font-weight: 700; color: #D97706;">{{ $pendingCount }}</div>
                    <div style="font-size: 0.875rem; color: #6B7280; margin-top: 4px;">Pending Approval</div>
                </div>
                <div class="info-card" style="text-align: center; padding: 24px;">
                    <div style="font-size: 2rem; font-weight: 700; color: #059669;">{{ $approvedCount }}</div>
                    <div style="font-size: 0.875rem; color: #6B7280; margin-top: 4px;">Approved &amp; Live</div>
                </div>
            </div>

            {{-- Player Info Card --}}
            <div class="info-card">
                <h3 class="info-title" style="margin-bottom: 24px;">Your Player Profile</h3>
                <div class="details-list">
                    <dl style="display: grid; grid-template-columns: auto 1fr; gap: 16px 24px;">
                        <dt>👤 Name:</dt>
                        <dd>{{ $player->full_name }}</dd>

                        <dt>🏫 School:</dt>
                        <dd>{{ $player->school->school_name ?? '—' }}</dd>

                        <dt>🎂 Age Category:</dt>
                        <dd>{{ $player->age_category }}</dd>

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
            <div style="margin-top: 24px;">
                <a href="{{ route('player.help-posts.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm" style="display: inline-block;">
                    ✍️ Create Help Post
                </a>
                <a href="{{ route('player.help-posts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm" style="display: inline-block; margin-left: 8px;">
                    📋 My Help Posts
                </a>
            </div>
        @else
            <div class="info-card">
                <p style="color: #9CA3AF;">No player profile is linked to your account. Please contact your school's cricket incharge.</p>
            </div>
        @endif
    </div>
</x-app-layout>
