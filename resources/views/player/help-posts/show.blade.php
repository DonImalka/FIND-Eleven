<x-app-layout>
    <x-slot name="title">Help Post Details</x-slot>

    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.25rem; font-weight: 600;">Help Post Details</h2>
            <a href="{{ route('player.help-posts.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to My Posts
            </a>
        </div>

        <div class="info-card" style="padding: 24px;">
            {{-- Status Badge --}}
            <div style="margin-bottom: 20px;">
                <span style="font-size: 0.8rem; padding: 4px 12px; border-radius: 12px;
                    @if($helpPost->status === 'approved') background: #DEF7EC; color: #03543F;
                    @elseif($helpPost->status === 'rejected') background: #FDE8E8; color: #9B1C1C;
                    @else background: #FEF3C7; color: #92400E;
                    @endif">
                    {{ ucfirst($helpPost->status) }}
                </span>
                @if($helpPost->approved_at)
                    <span style="font-size: 0.75rem; color: #9CA3AF; margin-left: 8px;">Approved {{ $helpPost->approved_at->diffForHumans() }}</span>
                @endif
            </div>

            {{-- Title --}}
            <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 16px;">{{ $helpPost->title }}</h3>

            {{-- Description --}}
            <div style="background: #F9FAFB; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                <p style="white-space: pre-wrap; font-size: 0.9rem; color: #374151; line-height: 1.7;">{{ $helpPost->description }}</p>
            </div>

            {{-- Proof Document --}}
            @if($helpPost->proof_document)
                <div style="margin-bottom: 20px;">
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 8px;">📎 Proof Document</h4>
                    <a href="{{ asset('storage/' . $helpPost->proof_document) }}" target="_blank"
                       style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #FEF9EE; border: 1px solid #E8D5A8; border-radius: 8px; color: #C8973A; font-size: 0.85rem; text-decoration: none;">
                        📄 View Document
                    </a>
                </div>
            @endif

            {{-- Rejection Reason --}}
            @if($helpPost->isRejected() && $helpPost->rejection_reason)
                <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 14px; margin-bottom: 20px;">
                    <p style="font-size: 0.85rem; color: #991B1B;"><strong>Rejection Reason:</strong></p>
                    <p style="font-size: 0.85rem; color: #991B1B; margin-top: 4px;">{{ $helpPost->rejection_reason }}</p>
                </div>
            @endif

            {{-- Meta --}}
            <div style="padding-top: 16px; border-top: 1px solid #E5E7EB;">
                <p style="font-size: 0.75rem; color: #9CA3AF;">
                    Created {{ $helpPost->created_at->format('d M Y, h:i A') }} · {{ $helpPost->created_at->diffForHumans() }}
                </p>
            </div>

            {{-- Actions --}}
            @if($helpPost->isPending())
                <div style="margin-top: 16px;">
                    <form method="POST" action="{{ route('player.help-posts.destroy', $helpPost) }}" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 8px 16px; background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; border-radius: 8px; font-size: 0.85rem; cursor: pointer;">
                            🗑 Delete Post
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
