<x-app-layout>
    <x-slot name="title">Review Help Post</x-slot>

    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.25rem; font-weight: 600;">Review Help Post</h2>
            <a href="{{ route('school.help-posts.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to Help Posts
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

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
            </div>

            {{-- Player Info --}}
            <div style="background: #F3F4F6; border-radius: 8px; padding: 14px; margin-bottom: 20px;">
                <p style="font-size: 0.85rem; color: #374151;">
                    <strong>Player:</strong> {{ $helpPost->player->full_name }}
                    · {{ $helpPost->player->age_category }}
                    · {{ $helpPost->player->player_category }}
                    @if($helpPost->player->jersey_number) · #{{ $helpPost->player->jersey_number }} @endif
                </p>
            </div>

            {{-- Title --}}
            <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 16px; color: #111827;">{{ $helpPost->title }}</h3>

            {{-- Description --}}
            <div style="background: #F9FAFB; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                <p style="white-space: pre-wrap; font-size: 0.9rem; color: #374151; line-height: 1.7;">{{ $helpPost->description }}</p>
            </div>

            {{-- Proof Document --}}
            @if($helpPost->proof_document)
                <div style="margin-bottom: 20px;">
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 8px;">📎 Proof Document</h4>
                    <a href="{{ asset('storage/' . $helpPost->proof_document) }}" target="_blank"
                       style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; color: #1D4ED8; font-size: 0.85rem; text-decoration: none;">
                        📄 View Document
                    </a>
                </div>
            @endif

            {{-- Rejection Reason (if already rejected) --}}
            @if($helpPost->isRejected() && $helpPost->rejection_reason)
                <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 14px; margin-bottom: 20px;">
                    <p style="font-size: 0.85rem; color: #991B1B;"><strong>Rejection Reason:</strong> {{ $helpPost->rejection_reason }}</p>
                </div>
            @endif

            {{-- Meta --}}
            <div style="padding-top: 16px; border-top: 1px solid #E5E7EB; margin-bottom: 24px;">
                <p style="font-size: 0.75rem; color: #9CA3AF;">
                    Submitted {{ $helpPost->created_at->format('d M Y, h:i A') }} · {{ $helpPost->created_at->diffForHumans() }}
                    @if($helpPost->approved_at)
                        · Approved {{ $helpPost->approved_at->format('d M Y') }}
                    @endif
                </p>
            </div>

            {{-- Action Buttons --}}
            @if($helpPost->isPending())
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    {{-- Approve --}}
                    <form method="POST" action="{{ route('school.help-posts.approve', $helpPost) }}" onsubmit="return confirm('Approve this help post? It will be visible on the public website.');">
                        @csrf
                        <button type="submit" style="padding: 10px 24px; background: #059669; color: white; border: none; border-radius: 8px; font-size: 0.875rem; cursor: pointer; font-weight: 500;">
                            ✅ Approve Post
                        </button>
                    </form>

                    {{-- Reject (with reason) --}}
                    <div x-data="{ showReject: false }">
                        <button @click="showReject = !showReject" style="padding: 10px 24px; background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; border-radius: 8px; font-size: 0.875rem; cursor: pointer; font-weight: 500;">
                            ❌ Reject Post
                        </button>

                        <div x-show="showReject" x-transition style="margin-top: 16px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 16px;">
                            <form method="POST" action="{{ route('school.help-posts.reject', $helpPost) }}">
                                @csrf
                                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #991B1B; margin-bottom: 6px;">Rejection Reason</label>
                                <textarea name="rejection_reason" rows="3" required
                                          style="width: 100%; padding: 10px; border: 1px solid #FECACA; border-radius: 6px; font-size: 0.85rem; resize: vertical;"
                                          placeholder="Explain why this post is being rejected..."></textarea>
                                @error('rejection_reason')
                                    <p style="color: #DC2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                                <button type="submit" style="margin-top: 10px; padding: 8px 20px; background: #DC2626; color: white; border: none; border-radius: 6px; font-size: 0.85rem; cursor: pointer;">
                                    Confirm Rejection
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif($helpPost->isApproved())
                <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 8px; padding: 14px;">
                    <p style="font-size: 0.85rem; color: #065F46;">✅ This post is approved and visible on the website.</p>
                </div>
            @elseif($helpPost->isRejected())
                {{-- Allow re-approval --}}
                <form method="POST" action="{{ route('school.help-posts.approve', $helpPost) }}" onsubmit="return confirm('Re-approve this help post?');">
                    @csrf
                    <button type="submit" style="padding: 10px 24px; background: #059669; color: white; border: none; border-radius: 8px; font-size: 0.875rem; cursor: pointer; font-weight: 500;">
                        ✅ Re-Approve Post
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
