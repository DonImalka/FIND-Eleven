@extends('layouts.website')
@section('title', $helpPost->title . ' — FindEleven')

@php
    $player = $helpPost->player;
    $stats = $player->stats ?? null;
    $hasStats = $stats && $stats->hasInitialStats();
    $cat = $player->player_category;
    $isBatter = in_array($cat, [\App\Models\Player::CATEGORY_TOP_ORDER_BATTER, \App\Models\Player::CATEGORY_POWER_HITTER]);
    $isBowler = in_array($cat, [\App\Models\Player::CATEGORY_FAST_BOWLER, \App\Models\Player::CATEGORY_MEDIUM_BOWLER, \App\Models\Player::CATEGORY_FINGER_SPIN_BOWLER, \App\Models\Player::CATEGORY_WRIST_SPIN_BOWLER]);
    $isAllRounder = in_array($cat, [\App\Models\Player::CATEGORY_FAST_BOWLING_ALLROUNDER, \App\Models\Player::CATEGORY_SPIN_ALLROUNDER]);
@endphp

@section('content')
<!-- Minimal Hero -->
<div style="padding: 100px 24px 20px; text-align: center;">
    <a href="{{ route('help-posts.index') }}" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.15em; font-size: 0.75rem; color: var(--gold); text-decoration: none;">← BACK TO HELP POSTS</a>
</div>

<div style="max-width: 680px; margin: 0 auto; padding: 0 16px 80px;">

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  MAIN POST CARD (Facebook-style)               --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden;">

        {{-- ── Header: Avatar + Player Info ── --}}
        <div style="padding: 18px 22px 14px; display: flex; align-items: center; gap: 14px;">
            <div style="width: 52px; height: 52px; border-radius: 50%; background: linear-gradient(135deg, rgba(200,151,58,0.28), rgba(200,151,58,0.08)); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--gold); font-size: 1.3rem; flex-shrink: 0; border: 2px solid rgba(200,151,58,0.35);">
                {{ strtoupper(substr($player->full_name, 0, 1)) }}
            </div>
            <div style="flex: 1;">
                <div style="font-weight: 700; font-size: 1.05rem; color: var(--cream);">{{ $player->full_name }}</div>
                <div style="font-size: 0.78rem; color: var(--muted); margin-top: 3px; display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                    <span>{{ $player->school->school_name ?? '—' }}</span>
                    <span style="opacity: 0.35;">·</span>
                    <span>{{ $player->age_category }}</span>
                    <span style="opacity: 0.35;">·</span>
                    <span>{{ $player->player_category }}</span>
                </div>
                <div style="font-size: 0.7rem; color: var(--muted); margin-top: 4px; opacity: 0.7;">
                    Posted {{ $helpPost->approved_at->diffForHumans() }}
                </div>
            </div>
        </div>

        {{-- ── Post Title ── --}}
        <div style="padding: 0 22px 14px;">
            <h1 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: var(--cream); line-height: 1.35; margin: 0;">
                {{ $helpPost->title }}
            </h1>
        </div>

        {{-- ── Post Content ── --}}
        <div style="padding: 0 22px 20px;">
            <p style="white-space: pre-wrap; font-size: 0.92rem; color: var(--cream); line-height: 1.8; opacity: 0.88;">{{ $helpPost->description }}</p>
        </div>

        {{-- ── Contact Details ── --}}
        @if($helpPost->contact_number || $helpPost->contact_email)
            <div style="margin: 0 22px 16px; padding: 14px 18px; background: rgba(200,151,58,0.06); border: 1px solid rgba(200,151,58,0.15); border-radius: 10px;">
                <div style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.1em; font-size: 0.72rem; color: var(--gold); margin-bottom: 10px;">📞 CONTACT DETAILS</div>
                <div style="display: flex; gap: 24px; flex-wrap: wrap;">
                    @if($helpPost->contact_number)
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 1rem;">📱</span>
                            <div>
                                <div style="font-size: 0.68rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em;">Phone</div>
                                <a href="tel:{{ $helpPost->contact_number }}" style="font-size: 0.88rem; color: var(--cream); text-decoration: none; font-weight: 500;">{{ $helpPost->contact_number }}</a>
                            </div>
                        </div>
                    @endif
                    @if($helpPost->contact_email)
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 1rem;">✉️</span>
                            <div>
                                <div style="font-size: 0.68rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em;">Email</div>
                                <a href="mailto:{{ $helpPost->contact_email }}" style="font-size: 0.88rem; color: var(--gold); text-decoration: none; font-weight: 500;">{{ $helpPost->contact_email }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ── Proof Image (full-width, Facebook style) ── --}}
        @if($helpPost->proof_document)
            @php
                $ext = pathinfo($helpPost->proof_document, PATHINFO_EXTENSION);
                $proofIsImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']);
            @endphp
            @if($proofIsImage)
                <div style="width: 100%; border-top: 1px solid var(--card-border); border-bottom: 1px solid var(--card-border);">
                    <img src="{{ asset('storage/' . $helpPost->proof_document) }}" alt="Proof document" style="width: 100%; display: block;">
                </div>
            @else
                <div style="padding: 0 22px 16px;">
                    <a href="{{ asset('storage/' . $helpPost->proof_document) }}" target="_blank"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: rgba(200,151,58,0.06); border: 1px solid rgba(200,151,58,0.15); border-radius: 10px; color: var(--gold); font-size: 0.85rem; text-decoration: none; transition: background 0.2s;"
                       onmouseover="this.style.background='rgba(200,151,58,0.12)'" onmouseout="this.style.background='rgba(200,151,58,0.06)'">
                        📄 View Proof Document ({{ strtoupper($ext) }})
                    </a>
                </div>
            @endif
        @endif

        {{-- ── School Verification ── --}}
        <div style="margin: 0 22px; padding: 12px 16px; background: rgba(34,197,94,0.06); border: 1px solid rgba(34,197,94,0.15); border-radius: 10px; display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
            <span style="font-size: 1.1rem;">✅</span>
            <p style="font-size: 0.82rem; color: #4ade80; margin: 0;">
                Verified and approved by <strong>{{ $player->school->school_name ?? 'the school' }}</strong>
            </p>
        </div>

        {{-- ── Reactions / Social Strip ── --}}
        <div style="padding: 10px 22px 12px; border-top: 1px solid var(--card-border); display: flex; align-items: center; gap: 20px;">
            <span style="font-size: 0.8rem; color: var(--muted); display: flex; align-items: center; gap: 5px;">🙏 Support this player</span>
            @if($helpPost->proof_document)
                <span style="font-size: 0.8rem; color: var(--muted); display: flex; align-items: center; gap: 5px;">📎 Proof attached</span>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  PLAYER STATS CARD                             --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @if($hasStats)
        <div style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; margin-top: 16px;">

            {{-- Stats Header --}}
            <div style="padding: 16px 22px 12px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.12em; font-size: 0.85rem; color: var(--gold);">📊 CAREER STATISTICS</div>
                    <div style="font-size: 0.72rem; color: var(--muted); margin-top: 2px;">{{ $player->full_name }} · {{ $cat }}</div>
                </div>
                @if($stats->ranking_points)
                    <div style="background: rgba(200,151,58,0.1); border: 1px solid rgba(200,151,58,0.2); padding: 6px 14px; border-radius: 20px;">
                        <span style="font-family: 'IBM Plex Mono', monospace; font-size: 0.82rem; color: var(--gold); font-weight: 700;">⭐ {{ number_format($stats->ranking_points) }} pts</span>
                    </div>
                @endif
            </div>

            {{-- Batting Stats --}}
            @if($isBatter || $isAllRounder)
                <div style="padding: 0 22px 14px;">
                    <div style="font-size: 0.68rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; font-weight: 600;">🏏 Batting</div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(90px, 1fr)); gap: 4px;">
                        @php
                            $battingData = [
                                ['val' => $stats->batting_matches ?? 0, 'label' => 'Matches'],
                                ['val' => $stats->batting_innings ?? 0, 'label' => 'Innings'],
                                ['val' => $stats->batting_runs ?? 0, 'label' => 'Runs'],
                                ['val' => $stats->batting_average ?? '0.00', 'label' => 'Average'],
                                ['val' => $stats->batting_strike_rate ?? '0.00', 'label' => 'Strike Rate'],
                                ['val' => $stats->batting_highest_score ?? 0, 'label' => 'Highest'],
                                ['val' => $stats->batting_fifties ?? 0, 'label' => '50s'],
                                ['val' => $stats->batting_hundreds ?? 0, 'label' => '100s'],
                                ['val' => $stats->batting_fours ?? 0, 'label' => 'Fours'],
                                ['val' => $stats->batting_sixes ?? 0, 'label' => 'Sixes'],
                            ];
                        @endphp
                        @foreach($battingData as $d)
                            <div style="background: rgba(200,151,58,0.05); border-radius: 8px; padding: 10px 8px; text-align: center;">
                                <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1.05rem; font-weight: 700; color: var(--cream);">{{ $d['val'] }}</div>
                                <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 2px;">{{ $d['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Bowling Stats --}}
            @if($isBowler || $isAllRounder)
                <div style="padding: 0 22px 14px;">
                    <div style="font-size: 0.68rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; font-weight: 600;">🎳 Bowling</div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(90px, 1fr)); gap: 4px;">
                        @php
                            $bowlingData = [
                                ['val' => $stats->bowling_matches ?? 0, 'label' => 'Matches'],
                                ['val' => $stats->bowling_innings ?? 0, 'label' => 'Innings'],
                                ['val' => $stats->bowling_overs ?? 0, 'label' => 'Overs'],
                                ['val' => $stats->bowling_wickets ?? 0, 'label' => 'Wickets'],
                                ['val' => $stats->bowling_average ?? '0.00', 'label' => 'Average'],
                                ['val' => $stats->bowling_economy ?? '0.00', 'label' => 'Economy'],
                                ['val' => $stats->bowling_strike_rate ?? '0.00', 'label' => 'Strike Rate'],
                                ['val' => ($stats->bowling_best_wickets ?? 0) . '/' . ($stats->bowling_best_runs ?? 0), 'label' => 'Best Figures'],
                                ['val' => $stats->bowling_five_wickets ?? 0, 'label' => '5-Wickets'],
                                ['val' => $stats->bowling_maidens ?? 0, 'label' => 'Maidens'],
                            ];
                        @endphp
                        @foreach($bowlingData as $d)
                            <div style="background: rgba(155,29,32,0.05); border-radius: 8px; padding: 10px 8px; text-align: center;">
                                <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1.05rem; font-weight: 700; color: var(--cream);">{{ $d['val'] }}</div>
                                <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 2px;">{{ $d['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Fielding Stats --}}
            <div style="padding: 0 22px 16px;">
                <div style="font-size: 0.68rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; font-weight: 600;">🧤 Fielding</div>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;">
                    <div style="background: rgba(200,151,58,0.03); border-radius: 8px; padding: 10px 8px; text-align: center;">
                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1.05rem; font-weight: 700; color: var(--cream);">{{ $stats->fielding_catches ?? 0 }}</div>
                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 2px;">Catches</div>
                    </div>
                    <div style="background: rgba(200,151,58,0.03); border-radius: 8px; padding: 10px 8px; text-align: center;">
                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1.05rem; font-weight: 700; color: var(--cream);">{{ $stats->fielding_run_outs ?? 0 }}</div>
                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 2px;">Run Outs</div>
                    </div>
                    <div style="background: rgba(200,151,58,0.03); border-radius: 8px; padding: 10px 8px; text-align: center;">
                        <div style="font-family: 'IBM Plex Mono', monospace; font-size: 1.05rem; font-weight: 700; color: var(--cream);">{{ $stats->fielding_stumpings ?? 0 }}</div>
                        <div style="font-size: 0.58rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 2px;">Stumpings</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  RELATED POSTS                                 --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @if($relatedPosts->isNotEmpty())
        <div style="margin-top: 48px;">
            <div class="section-divider">
                <span class="divider-text">More Help Posts</span>
                <div class="divider-line"></div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px; margin-top: 20px;">
                @foreach($relatedPosts as $related)
                    @php $rPlayer = $related->player; @endphp
                    <a href="{{ route('help-posts.show', $related) }}" style="text-decoration: none;">
                        <div style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; padding: 16px 18px; display: flex; gap: 12px; align-items: flex-start; transition: border-color 0.3s;"
                             onmouseover="this.style.borderColor='rgba(200,151,58,0.3)'" onmouseout="this.style.borderColor='var(--card-border)'">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, rgba(200,151,58,0.2), rgba(200,151,58,0.06)); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--gold); font-size: 0.9rem; flex-shrink: 0; border: 1px solid rgba(200,151,58,0.25);">
                                {{ strtoupper(substr($rPlayer->full_name, 0, 1)) }}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 600; font-size: 0.85rem; color: var(--cream); margin-bottom: 3px;">{{ $rPlayer->full_name }}</div>
                                <div style="font-family: 'Playfair Display', serif; font-size: 0.95rem; color: var(--cream); line-height: 1.3; margin-bottom: 5px;">{{ $related->title }}</div>
                                <div style="font-size: 0.72rem; color: var(--muted);">{{ $rPlayer->school->school_name ?? '—' }} · {{ $related->approved_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
