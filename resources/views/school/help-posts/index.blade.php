<x-app-layout>
    <x-slot name="title">Player Help Posts</x-slot>

    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.25rem; font-weight: 600;">
                Player Help Posts
                @if($pendingCount > 0)
                    <span style="background: #FEF3C7; color: #92400E; font-size: 0.75rem; padding: 2px 8px; border-radius: 10px; margin-left: 8px;">{{ $pendingCount }} pending</span>
                @endif
            </h2>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Status Filter --}}
        <div style="display: flex; gap: 8px; margin-bottom: 24px;">
            <a href="{{ route('school.help-posts.index') }}" 
               style="padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; text-decoration: none;
                   {{ !request('status') ? 'background: #4F46E5; color: white;' : 'background: #F3F4F6; color: #374151;' }}">
                All
            </a>
            <a href="{{ route('school.help-posts.index', ['status' => 'pending']) }}"
               style="padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; text-decoration: none;
                   {{ request('status') === 'pending' ? 'background: #D97706; color: white;' : 'background: #F3F4F6; color: #374151;' }}">
                Pending
            </a>
            <a href="{{ route('school.help-posts.index', ['status' => 'approved']) }}"
               style="padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; text-decoration: none;
                   {{ request('status') === 'approved' ? 'background: #059669; color: white;' : 'background: #F3F4F6; color: #374151;' }}">
                Approved
            </a>
            <a href="{{ route('school.help-posts.index', ['status' => 'rejected']) }}"
               style="padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; text-decoration: none;
                   {{ request('status') === 'rejected' ? 'background: #DC2626; color: white;' : 'background: #F3F4F6; color: #374151;' }}">
                Rejected
            </a>
        </div>

        @if($helpPosts->isEmpty())
            <div class="info-card" style="text-align: center; padding: 48px;">
                <p style="font-size: 2rem; margin-bottom: 12px;">📋</p>
                <p style="color: #6B7280;">No help posts found.</p>
            </div>
        @else
            <div style="display: grid; gap: 12px;">
                @foreach($helpPosts as $post)
                    <a href="{{ route('school.help-posts.show', $post) }}" class="info-card" style="padding: 16px; text-decoration: none; display: block; transition: box-shadow 0.2s;"
                       onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                                    <h3 style="font-size: 1rem; font-weight: 600; color: #111827;">{{ $post->title }}</h3>
                                    <span style="font-size: 0.7rem; padding: 2px 8px; border-radius: 10px;
                                        @if($post->status === 'approved') background: #DEF7EC; color: #03543F;
                                        @elseif($post->status === 'rejected') background: #FDE8E8; color: #9B1C1C;
                                        @else background: #FEF3C7; color: #92400E;
                                        @endif">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                                <p style="font-size: 0.8rem; color: #6B7280;">
                                    By <strong>{{ $post->player->full_name }}</strong> · {{ $post->player->age_category }} · {{ $post->player->player_category }}
                                </p>
                                <p style="font-size: 0.8rem; color: #9CA3AF; margin-top: 4px;">
                                    {{ Str::limit($post->description, 120) }}
                                </p>
                            </div>
                            <div style="text-align: right; flex-shrink: 0; margin-left: 16px;">
                                <p style="font-size: 0.7rem; color: #9CA3AF;">{{ $post->created_at->diffForHumans() }}</p>
                                @if($post->proof_document)
                                    <p style="font-size: 0.7rem; color: #6B7280; margin-top: 4px;">📎 Document</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
