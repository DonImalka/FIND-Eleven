@extends('layouts.website')
@section('title', 'Player Help Posts — FindEleven')

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <span class="page-hero-year">SUPPORT</span>
    <h1 class="page-hero-title">Player <span>Help Posts</span></h1>
    <p class="page-hero-sub">Supporting young cricketers in need. These posts are verified and approved by their respective schools.</p>
</div>

<div class="section" style="max-width: 1100px; margin: 0 auto; padding: 0 24px 80px;">

    @if($helpPosts->isEmpty())
        <div style="text-align: center; padding: 80px 0;">
            <div style="font-size: 3rem; margin-bottom: 16px;">🤝</div>
            <p style="color: var(--muted); font-size: 1rem;">No help posts at the moment. Check back later.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px; margin-top: 40px;">
            @foreach($helpPosts as $post)
                <a href="{{ route('help-posts.show', $post) }}" 
                   style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 24px; text-decoration: none; color: inherit; transition: border-color 0.3s, transform 0.2s;"
                   onmouseover="this.style.borderColor='var(--gold)'; this.style.transform='translateY(-2px)';" 
                   onmouseout="this.style.borderColor='var(--card-border)'; this.style.transform='none';">
                    
                    {{-- Player Info --}}
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(200,151,58,0.15); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--gold); font-size: 1rem;">
                            {{ strtoupper(substr($post->player->full_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: var(--cream);">{{ $post->player->full_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--muted);">{{ $post->player->school->school_name ?? '—' }} · {{ $post->player->age_category }}</div>
                        </div>
                    </div>

                    {{-- Post Title --}}
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 700; color: var(--cream); margin-bottom: 10px; line-height: 1.3;">
                        {{ $post->title }}
                    </h3>

                    {{-- Description Preview --}}
                    <p style="font-size: 0.85rem; color: var(--muted); line-height: 1.6; margin-bottom: 16px;">
                        {{ Str::limit($post->description, 150) }}
                    </p>

                    {{-- Footer --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid var(--card-border);">
                        <span style="font-size: 0.7rem; color: var(--muted);">{{ $post->approved_at->diffForHumans() }}</span>
                        @if($post->proof_document)
                            <span style="font-size: 0.7rem; color: var(--gold);">📎 Verified</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($helpPosts->hasPages())
            <div style="margin-top: 48px; display: flex; justify-content: center; gap: 8px;">
                @if($helpPosts->onFirstPage())
                    <span style="padding: 8px 16px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 6px; color: var(--muted); font-size: 0.85rem;">← Previous</span>
                @else
                    <a href="{{ $helpPosts->previousPageUrl() }}" style="padding: 8px 16px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 6px; color: var(--gold); font-size: 0.85rem; text-decoration: none;">← Previous</a>
                @endif

                <span style="padding: 8px 16px; font-size: 0.85rem; color: var(--muted);">
                    Page {{ $helpPosts->currentPage() }} of {{ $helpPosts->lastPage() }}
                </span>

                @if($helpPosts->hasMorePages())
                    <a href="{{ $helpPosts->nextPageUrl() }}" style="padding: 8px 16px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 6px; color: var(--gold); font-size: 0.85rem; text-decoration: none;">Next →</a>
                @else
                    <span style="padding: 8px 16px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 6px; color: var(--muted); font-size: 0.85rem;">Next →</span>
                @endif
            </div>
        @endif
    @endif
</div>
@endsection
