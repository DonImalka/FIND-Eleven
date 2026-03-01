<x-app-layout>
    <x-slot name="title">My Help Posts</x-slot>

    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.25rem; font-weight: 600;">My Help Posts</h2>
            <a href="{{ route('player.help-posts.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                + Create New Post
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($helpPosts->isEmpty())
            <div class="info-card" style="text-align: center; padding: 48px;">
                <p style="font-size: 2rem; margin-bottom: 12px;">📝</p>
                <p style="color: #6B7280;">You haven't created any help posts yet.</p>
                <a href="{{ route('player.help-posts.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm" style="display: inline-block; margin-top: 16px;">
                    Create Your First Post
                </a>
            </div>
        @else
            <div style="display: grid; gap: 16px;">
                @foreach($helpPosts as $post)
                    <div class="info-card" style="padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <h3 style="font-size: 1.1rem; font-weight: 600;">{{ $post->title }}</h3>
                            <span class="badge {{ $post->status === 'approved' ? 'success' : ($post->status === 'rejected' ? 'danger' : 'warning') }}" 
                                  style="font-size: 0.75rem; padding: 4px 10px; border-radius: 12px; white-space: nowrap;
                                    @if($post->status === 'approved') background: #DEF7EC; color: #03543F;
                                    @elseif($post->status === 'rejected') background: #FDE8E8; color: #9B1C1C;
                                    @else background: #FEF3C7; color: #92400E;
                                    @endif">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>

                        <p style="color: #6B7280; font-size: 0.875rem; margin-bottom: 12px;">
                            {{ Str::limit($post->description, 200) }}
                        </p>

                        @if($post->proof_document)
                            <p style="font-size: 0.8rem; color: #9CA3AF; margin-bottom: 8px;">
                                📎 Proof document attached
                            </p>
                        @endif

                        @if($post->isRejected() && $post->rejection_reason)
                            <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                                <p style="font-size: 0.8rem; color: #991B1B;"><strong>Rejection Reason:</strong> {{ $post->rejection_reason }}</p>
                            </div>
                        @endif

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px; padding-top: 12px; border-top: 1px solid #E5E7EB;">
                            <span style="font-size: 0.75rem; color: #9CA3AF;">{{ $post->created_at->diffForHumans() }}</span>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('player.help-posts.show', $post) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                @if($post->isPending())
                                    <form method="POST" action="{{ route('player.help-posts.destroy', $post) }}" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
