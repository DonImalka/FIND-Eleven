@extends('layouts.website')
@section('title', $helpPost->title . ' — FindEleven')

@section('content')
<!-- Page Hero -->
<div class="page-hero" style="padding-bottom: 32px;">
    <span class="page-hero-year">HELP POST</span>
    <h1 class="page-hero-title" style="font-size: clamp(1.5rem, 4vw, 2.2rem);">{{ $helpPost->title }}</h1>
</div>

<div style="max-width: 900px; margin: 0 auto; padding: 0 24px 80px;">

    {{-- Player Info Card --}}
    <div style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; margin-bottom: 32px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(200,151,58,0.15); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--gold); font-size: 1.2rem; flex-shrink: 0;">
            {{ strtoupper(substr($helpPost->player->full_name, 0, 1)) }}
        </div>
        <div>
            <div style="font-weight: 600; font-size: 1rem; color: var(--cream);">{{ $helpPost->player->full_name }}</div>
            <div style="font-size: 0.8rem; color: var(--muted); margin-top: 2px;">
                {{ $helpPost->player->school->school_name ?? '—' }} · {{ $helpPost->player->age_category }} · {{ $helpPost->player->player_category }}
            </div>
        </div>
        <div style="margin-left: auto; text-align: right;">
            <span style="font-size: 0.7rem; color: var(--muted);">Posted {{ $helpPost->approved_at->diffForHumans() }}</span>
        </div>
    </div>

    {{-- Post Content --}}
    <div style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 32px; margin-bottom: 32px;">
        <p style="white-space: pre-wrap; font-size: 0.95rem; color: var(--cream); line-height: 1.8; opacity: 0.9;">{{ $helpPost->description }}</p>
    </div>

    {{-- Proof Document --}}
    @if($helpPost->proof_document)
        <div style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; margin-bottom: 32px;">
            <h4 style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.1em; font-size: 0.9rem; color: var(--gold); margin-bottom: 12px;">PROOF DOCUMENT</h4>
            @php
                $ext = pathinfo($helpPost->proof_document, PATHINFO_EXTENSION);
                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
            @endphp
            @if($isImage)
                <img src="{{ asset('storage/' . $helpPost->proof_document) }}" alt="Proof document" style="max-width: 100%; border-radius: 8px; border: 1px solid var(--card-border);">
            @else
                <a href="{{ asset('storage/' . $helpPost->proof_document) }}" target="_blank"
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(200,151,58,0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--gold); font-size: 0.85rem; text-decoration: none;">
                    📄 View Document ({{ strtoupper($ext) }})
                </a>
            @endif
        </div>
    @endif

    {{-- School Verification Badge --}}
    <div style="background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2); border-radius: 12px; padding: 16px; margin-bottom: 40px; display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 1.2rem;">✅</span>
        <p style="font-size: 0.85rem; color: #4ade80;">
            This help post has been verified and approved by <strong>{{ $helpPost->player->school->school_name ?? 'the school' }}</strong>.
        </p>
    </div>

    {{-- Back Link --}}
    <a href="{{ route('help-posts.index') }}" style="color: var(--gold); font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        ← Back to Help Posts
    </a>

    {{-- Related Posts --}}
    @if($relatedPosts->isNotEmpty())
        <div style="margin-top: 56px;">
            <div class="section-divider">
                <span class="divider-text">More Help Posts</span>
                <div class="divider-line"></div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 24px;">
                @foreach($relatedPosts as $related)
                    <a href="{{ route('help-posts.show', $related) }}"
                       style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; padding: 18px; text-decoration: none; color: inherit; transition: border-color 0.3s;"
                       onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--card-border)'">
                        <h4 style="font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--cream); margin-bottom: 8px;">{{ $related->title }}</h4>
                        <p style="font-size: 0.8rem; color: var(--muted); margin-bottom: 8px;">{{ Str::limit($related->description, 100) }}</p>
                        <span style="font-size: 0.7rem; color: var(--gold);">{{ $related->player->full_name }} · {{ $related->player->school->school_name ?? '—' }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
