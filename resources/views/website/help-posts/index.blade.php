@extends('layouts.website')
@section('title', 'Player Help Posts — FindEleven')

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <span class="page-hero-year">SUPPORT</span>
    <h1 class="page-hero-title">Player <span>Help Posts</span></h1>
    <p class="page-hero-sub">Supporting young cricketers in need. These posts are verified and approved by their respective schools.</p>
</div>

<div style="max-width: 680px; margin: 0 auto; padding: 0 16px 80px;">

    @if($helpPosts->isEmpty())
        <div style="text-align: center; padding: 80px 0;">
            <div style="font-size: 3rem; margin-bottom: 16px;">🤝</div>
            <p style="color: var(--muted); font-size: 1rem;">No help posts at the moment. Check back later.</p>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 40px;">
            @foreach($helpPosts as $post)
                @php
                    $player = $post->player;
                    $stats = $player->stats ?? null;
                    $hasStats = $stats && $stats->hasInitialStats();
                    $cat = $player->player_category;
                    $isBatter = in_array($cat, [\App\Models\Player::CATEGORY_TOP_ORDER_BATTER, \App\Models\Player::CATEGORY_POWER_HITTER]);
                    $isBowler = in_array($cat, [\App\Models\Player::CATEGORY_FAST_BOWLER, \App\Models\Player::CATEGORY_MEDIUM_BOWLER, \App\Models\Player::CATEGORY_FINGER_SPIN_BOWLER, \App\Models\Player::CATEGORY_WRIST_SPIN_BOWLER]);
                    $isAllRounder = in_array($cat, [\App\Models\Player::CATEGORY_FAST_BOWLING_ALLROUNDER, \App\Models\Player::CATEGORY_SPIN_ALLROUNDER]);
                    $proofExt = $post->proof_document ? pathinfo($post->proof_document, PATHINFO_EXTENSION) : null;
                    $isImage = $proofExt && in_array(strtolower($proofExt), ['jpg', 'jpeg', 'png', 'webp']);
                @endphp

                {{-- ═══ FACEBOOK-STYLE POST CARD ═══ --}}
                <div style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; transition: border-color 0.3s;"
                     onmouseover="this.style.borderColor='rgba(200,151,58,0.35)'" onmouseout="this.style.borderColor='var(--card-border)'">

                    {{-- ── Header: Avatar + Name + Meta ── --}}
                    <div style="padding: 16px 20px 12px; display: flex; align-items: center; gap: 12px;">
                        <div style="width: 46px; height: 46px; border-radius: 50%; background: linear-gradient(135deg, rgba(200,151,58,0.28), rgba(200,151,58,0.08)); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--gold); font-size: 1.15rem; flex-shrink: 0; border: 1.5px solid rgba(200,151,58,0.35);">
                            {{ strtoupper(substr($player->full_name, 0, 1)) }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; font-size: 0.95rem; color: var(--cream);">{{ $player->full_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--muted); display: flex; align-items: center; gap: 5px; flex-wrap: wrap; margin-top: 2px;">
                                <span>{{ $player->school->school_name ?? '—' }}</span>
                                <span style="opacity: 0.35;">·</span>
                                <span>{{ $player->age_category }}</span>
                                <span style="opacity: 0.35;">·</span>
                                <span>{{ $player->player_category }}</span>
                                <span style="opacity: 0.35;">·</span>
                                <span>{{ $post->approved_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if($post->proof_document)
                            <span style="font-size: 0.62rem; color: #4ade80; background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); padding: 3px 10px; border-radius: 20px; white-space: nowrap; font-weight: 500;">✅ Verified</span>
                        @endif
                    </div>

                    {{-- ── Post Body: Title + Description ── --}}
                    <div style="padding: 0 20px 16px;">
                        <a href="{{ route('help-posts.show', $post) }}" style="text-decoration: none;">
                            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--cream); margin-bottom: 10px; line-height: 1.35; transition: color 0.2s;" onmouseover="this.style.color='var(--gold-light)'" onmouseout="this.style.color='var(--cream)'">
                                {{ $post->title }}
                            </h3>
                        </a>
                        <p style="font-size: 0.88rem; color: var(--muted); line-height: 1.7;">
                            {{ Str::limit($post->description, 300) }}
                        </p>
                    </div>

                    {{-- ── Proof Image (if image type) ── --}}
                    @if($isImage)
                        <a href="{{ route('help-posts.show', $post) }}">
                            <div style="width: 100%; max-height: 380px; overflow: hidden; border-top: 1px solid var(--card-border); border-bottom: 1px solid var(--card-border);">
                                <img src="{{ asset('storage/' . $post->proof_document) }}" alt="Proof" style="width: 100%; object-fit: cover; display: block; opacity: 0.92; transition: opacity 0.3s, transform 0.3s;" onmouseover="this.style.opacity='1'; this.style.transform='scale(1.02)'" onmouseout="this.style.opacity='0.92'; this.style.transform='scale(1)'">
                            </div>
                        </a>
                    @endif

                    {{-- ── Player Stats Strip ── --}}
                    @if($hasStats)
                        <div style="padding: 14px 20px 10px; border-top: 1px solid var(--card-border);">
                            <div style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.12em; font-size: 0.68rem; color: var(--gold); margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                                📊 CAREER STATS
                            </div>

                            {{-- Batting Stats --}}
                            @if($isBatter || $isAllRounder)
                                <div style="display: flex; gap: 3px; margin-bottom: {{ ($isBowler || $isAllRounder) ? '8px' : '4px' }}; flex-wrap: wrap;">
                                    <div style="flex: 1; min-width: 55px; background: rgba(200,151,58,0.06); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--gold);">{{ $stats->batting_matches ?? 0 }}</div>
                                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">Mat</div>
                                    </div>
                                    <div style="flex: 1; min-width: 55px; background: rgba(200,151,58,0.06); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--cream);">{{ $stats->batting_runs ?? 0 }}</div>
                                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">Runs</div>
                                    </div>
                                    <div style="flex: 1; min-width: 55px; background: rgba(200,151,58,0.06); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--cream);">{{ $stats->batting_average ?? '0.00' }}</div>
                                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">Avg</div>
                                    </div>
                                    <div style="flex: 1; min-width: 55px; background: rgba(200,151,58,0.06); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--cream);">{{ $stats->batting_strike_rate ?? '0.00' }}</div>
                                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">SR</div>
                                    </div>
                                    <div style="flex: 1; min-width: 55px; background: rgba(200,151,58,0.06); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--cream);">{{ $stats->batting_highest_score ?? 0 }}</div>
                                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">HS</div>
                                    </div>
                                </div>
                            @endif

                            {{-- Bowling Stats --}}
                            @if($isBowler || $isAllRounder)
                                <div style="display: flex; gap: 3px; margin-bottom: 4px; flex-wrap: wrap;">
                                    @if($isBowler)
                                        <div style="flex: 1; min-width: 55px; background: rgba(155,29,32,0.07); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                            <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--gold);">{{ $stats->bowling_matches ?? 0 }}</div>
                                            <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">Mat</div>
                                        </div>
                                    @endif
                                    <div style="flex: 1; min-width: 55px; background: rgba(155,29,32,0.07); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--cream);">{{ $stats->bowling_wickets ?? 0 }}</div>
                                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">Wkts</div>
                                    </div>
                                    <div style="flex: 1; min-width: 55px; background: rgba(155,29,32,0.07); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--cream);">{{ $stats->bowling_average ?? '0.00' }}</div>
                                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">Avg</div>
                                    </div>
                                    <div style="flex: 1; min-width: 55px; background: rgba(155,29,32,0.07); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--cream);">{{ $stats->bowling_economy ?? '0.00' }}</div>
                                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">Econ</div>
                                    </div>
                                    <div style="flex: 1; min-width: 55px; background: rgba(155,29,32,0.07); border-radius: 8px; padding: 8px 6px; text-align: center;">
                                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--cream);">{{ $stats->bowling_best_wickets ?? 0 }}/{{ $stats->bowling_best_runs ?? 0 }}</div>
                                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 1px;">Best</div>
                                    </div>
                                </div>
                            @endif

                            {{-- Ranking badge --}}
                            @if($stats->ranking_points)
                                <div style="margin-top: 6px; display: inline-flex; align-items: center; gap: 5px; background: rgba(200,151,58,0.08); border: 1px solid rgba(200,151,58,0.15); padding: 4px 12px; border-radius: 20px;">
                                    <span style="font-size: 0.72rem; color: var(--gold); font-weight: 600;">⭐ {{ number_format($stats->ranking_points) }} Ranking Pts</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- ── Footer: Read More ── --}}
                    <div style="padding: 12px 20px; border-top: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center;">
                        <a href="{{ route('help-posts.show', $post) }}" style="font-size: 0.82rem; color: var(--gold); text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 6px; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                            Read full story →
                        </a>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            @if($post->contact_number || $post->contact_email)
                                <span style="font-size: 0.72rem; color: var(--muted); display: flex; align-items: center; gap: 4px;">📞 Contact available</span>
                            @endif
                            @if($post->proof_document && !$isImage)
                                <span style="font-size: 0.72rem; color: var(--muted); display: flex; align-items: center; gap: 4px;">📎 Document</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($helpPosts->hasPages())
            <div style="margin-top: 48px; display: flex; justify-content: center; gap: 8px;">
                @if($helpPosts->onFirstPage())
                    <span style="padding: 10px 20px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; color: var(--muted); font-size: 0.85rem;">← Previous</span>
                @else
                    <a href="{{ $helpPosts->previousPageUrl() }}" style="padding: 10px 20px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; color: var(--gold); font-size: 0.85rem; text-decoration: none;">← Previous</a>
                @endif
                <span style="padding: 10px 20px; font-size: 0.85rem; color: var(--muted);">Page {{ $helpPosts->currentPage() }} of {{ $helpPosts->lastPage() }}</span>
                @if($helpPosts->hasMorePages())
                    <a href="{{ $helpPosts->nextPageUrl() }}" style="padding: 10px 20px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; color: var(--gold); font-size: 0.85rem; text-decoration: none;">Next →</a>
                @else
                    <span style="padding: 10px 20px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; color: var(--muted); font-size: 0.85rem;">Next →</span>
                @endif
            </div>
        @endif
    @endif
</div>
@endsection
