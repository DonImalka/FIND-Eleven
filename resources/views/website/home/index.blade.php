@extends('layouts.website')

@section('title', 'FindEleven — Island Rankings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

<!-- ── LIVE TICKER ── -->
@if($tickerMatches->isNotEmpty())
<div class="live-ticker">
    <div class="live-label">
        <span class="live-dot"></span>
        Live
    </div>
    <div class="ticker-scroll-wrap">
        <div class="ticker-scroll">
            @foreach($tickerMatches as $tm)
                <div class="ticker-match">
                    <span class="ticker-teams">{{ $tm->homeSchool->school_name ?? 'TBD' }} vs {{ $tm->awaySchool->school_name ?? 'TBD' }}</span>
                    @php $lastInning = $tm->innings->sortByDesc('inning_number')->first(); @endphp
                    @if($lastInning)
                        <span class="ticker-score">{{ $lastInning->total_runs }}/{{ $lastInning->total_wickets }} ({{ $lastInning->total_overs }})</span>
                    @else
                        <span class="ticker-score">Match in progress</span>
                    @endif
                    @if($tm->venue)
                        <span class="ticker-sep">·</span>
                        <span class="ticker-venue">{{ $tm->venue }}</span>
                    @endif
                </div>
            @endforeach
            @foreach($tickerMatches as $tm)
                <div class="ticker-match">
                    <span class="ticker-teams">{{ $tm->homeSchool->school_name ?? 'TBD' }} vs {{ $tm->awaySchool->school_name ?? 'TBD' }}</span>
                    @php $lastInning = $tm->innings->sortByDesc('inning_number')->first(); @endphp
                    @if($lastInning)
                        <span class="ticker-score">{{ $lastInning->total_runs }}/{{ $lastInning->total_wickets }} ({{ $lastInning->total_overs }})</span>
                    @else
                        <span class="ticker-score">Match in progress</span>
                    @endif
                    @if($tm->venue)
                        <span class="ticker-sep">·</span>
                        <span class="ticker-venue">{{ $tm->venue }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@else
<div class="live-ticker-empty"></div>
@endif

<!-- ── HERO ── -->
<div class="hero">
    <div class="hero-bg"></div>
    <div class="hero-year">{{ date('Y') }}</div>

    <div class="hero-left">
        <div class="hero-eyebrow">{{ date('Y') }} Season — Island Rankings</div>
        <h1 class="hero-title">
            Ceylon's<br>
            <span>Finest</span>
            Cricketers
        </h1>
        <p class="hero-desc">
            The definitive island-wide ranking system for Sri Lankan school cricket.
            Tracking every run, wicket and catch across all 25 districts.
        </p>
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-num">{{ $totalPlayers }}</div>
                <div class="stat-label">Players Ranked</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ $totalSchools }}</div>
                <div class="stat-label">Schools</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ $totalCategories }}</div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
    </div>

    <div class="hero-right">
        @if($featuredPlayer)
            @php $fp = $featuredPlayer; $fs = $fp->stats; @endphp
            <div class="featured-card">
                <div class="fc-header">
                    <span class="fc-tag">⭐ No.1 Ranked — Batsman</span>
                    <span class="fc-rank">#1</span>
                </div>
                <div class="fc-body">
                    <div class="player-avatar">
                        <span class="player-avatar-text">{{ strtoupper(substr($fp->full_name, 0, 1)) }}</span>
                    </div>
                    <div class="player-info">
                        <div class="player-name">{{ $fp->full_name }}</div>
                        <div class="player-school">{{ $fp->school->school_name ?? '—' }}</div>
                        <div class="player-category">{{ $fp->player_category }}</div>
                    </div>
                </div>
                <div class="fc-stats">
                    <div class="fc-stat">
                        <div class="fc-stat-val">{{ number_format($fs->ranking_points ?? 0) }}</div>
                        <div class="fc-stat-label">Rating</div>
                    </div>
                    <div class="fc-stat">
                        <div class="fc-stat-val">{{ number_format($fs->batting_average ?? 0, 1) }}</div>
                        <div class="fc-stat-label">Avg</div>
                    </div>
                    <div class="fc-stat">
                        <div class="fc-stat-val">{{ $fs->batting_innings ?? 0 }}</div>
                        <div class="fc-stat-label">Innings</div>
                    </div>
                </div>
            </div>
        @else
            <div class="featured-card">
                <div class="fc-header">
                    <span class="fc-tag">⭐ No.1 Ranked — Batsman</span>
                    <span class="fc-rank">#1</span>
                </div>
                <div class="fc-body">
                    <div class="player-avatar">
                        <svg viewBox="0 0 40 40" fill="none"><circle cx="20" cy="12" r="6" fill="#C8973A" opacity="0.5"/><path d="M8 36 C8 26 12 22 20 22 C28 22 32 26 32 36" fill="#C8973A" opacity="0.3"/></svg>
                    </div>
                    <div class="player-info">
                        <div class="player-name">—</div>
                        <div class="player-school">No rankings yet</div>
                        <div class="player-category">Batsman</div>
                    </div>
                </div>
                <div class="fc-stats">
                    <div class="fc-stat"><div class="fc-stat-val">—</div><div class="fc-stat-label">Rating</div></div>
                    <div class="fc-stat"><div class="fc-stat-val">—</div><div class="fc-stat-label">Avg</div></div>
                    <div class="fc-stat"><div class="fc-stat-val">—</div><div class="fc-stat-label">Innings</div></div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- ── LIVE SCORE CARDS ── -->
<div style="margin-top:56px;">
    <div class="section-divider">
        <span class="divider-text">Live Scorecards</span>
        <div class="divider-line"></div>
        <span class="divider-num">{{ str_pad($allMatches->count(), 2, '0', STR_PAD_LEFT) }} Matches</span>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Today's <span>Matches</span></h2>
            <a href="{{ route('live-scores.index') }}" class="see-all">All Fixtures</a>
        </div>

        <div class="score-grid">
            @forelse($allMatches as $match)
                @if($match->status === 'live')
                    <a href="{{ route('live-scores.show', $match) }}" class="score-card live-card" style="text-decoration:none; color:inherit;">
                        <div class="sc-top">
                            <span class="sc-tournament">{{ $match->tournament->name ?? 'Tournament' }} · {{ $match->overs_per_side }}-over</span>
                            <span class="sc-status-live"><span class="live-dot"></span> Live @php $ci = $match->innings->where('is_completed', false)->first(); @endphp @if($ci) · {{ $ci->total_overs }} ov @endif</span>
                        </div>
                        <div class="sc-match">
                            @php
                                $innings = $match->innings->sortBy('inning_number');
                                $cb = $match->innings->where('is_completed', false)->first();
                                $cbsId = $cb ? $cb->batting_school_id : null;
                            @endphp
                            <div class="sc-team-row">
                                @php $hb = $cbsId === $match->home_school_id; $hi = $innings->where('batting_school_id', $match->home_school_id)->first(); @endphp
                                <span class="sc-team-name {{ $hb ? 'batting' : '' }}">{{ $match->homeSchool->school_name ?? 'TBD' }}</span>
                                @if($hi)<span class="sc-score-val {{ $hb ? 'batting' : '' }}">{{ $hi->total_runs }}/{{ $hi->total_wickets }} <span class="sc-overs">({{ $hi->total_overs }})</span></span>@else<span class="sc-score-val">Yet to bat</span>@endif
                            </div>
                            <div class="sc-team-row">
                                @php $ab = $cbsId === $match->away_school_id; $ai = $innings->where('batting_school_id', $match->away_school_id)->first(); @endphp
                                <span class="sc-team-name {{ $ab ? 'batting' : '' }}">{{ $match->awaySchool->school_name ?? 'TBD' }}</span>
                                @if($ai)<span class="sc-score-val {{ $ab ? 'batting' : '' }}">{{ $ai->total_runs }}/{{ $ai->total_wickets }} <span class="sc-overs">({{ $ai->total_overs }})</span></span>@else<span class="sc-score-val">Yet to bat</span>@endif
                            </div>
                        </div>
                        <div class="sc-footer">
                            @php
                                $topBat = $cb ? $cb->batterScores->where('status','!=','yet_to_bat')->sortByDesc('runs')->first() : null;
                                $compInn = $innings->where('is_completed',true)->first();
                                $topBwl = $compInn ? $compInn->bowlerScores->sortByDesc('wickets')->first() : null;
                            @endphp
                            @if($topBat)<strong>{{ $topBat->player->full_name ?? '—' }} {{ $topBat->runs }}*({{ $topBat->balls_faced }})</strong>@endif
                            @if($topBwl) {{ $topBwl->player->full_name ?? '—' }} {{ $topBwl->wickets }}/{{ $topBwl->runs_conceded }}@endif
                            @if(!$topBat && !$topBwl) Match in progress @endif
                        </div>
                    </a>
                @elseif($match->status === 'completed')
                    <a href="{{ route('live-scores.show', $match) }}" class="score-card" style="text-decoration:none; color:inherit;">
                        <div class="sc-top">
                            <span class="sc-tournament">{{ $match->tournament->name ?? 'Tournament' }} · {{ $match->overs_per_side }}-over</span>
                            <span class="sc-status-done">✓ Result</span>
                        </div>
                        <div class="sc-match">
                            @php $innings = $match->innings->sortBy('inning_number'); @endphp
                            <div class="sc-team-row">
                                @php $hi = $innings->where('batting_school_id', $match->home_school_id)->first(); @endphp
                                <span class="sc-team-name">{{ $match->homeSchool->school_name ?? 'TBD' }}</span>
                                <span class="sc-score-val">{{ $hi ? $hi->total_runs.'/'.$hi->total_wickets : '—' }} @if($hi)<span class="sc-overs">({{ $hi->total_overs }})</span>@endif</span>
                            </div>
                            <div class="sc-team-row">
                                @php $ai = $innings->where('batting_school_id', $match->away_school_id)->first(); @endphp
                                <span class="sc-team-name">{{ $match->awaySchool->school_name ?? 'TBD' }}</span>
                                <span class="sc-score-val">{{ $ai ? $ai->total_runs.'/'.$ai->total_wickets : '—' }} @if($ai)<span class="sc-overs">({{ $ai->total_overs }})</span>@endif</span>
                            </div>
                        </div>
                        <div class="sc-footer"><strong>{{ $match->result_summary ?? 'Match completed' }}</strong></div>
                    </a>
                @elseif($match->status === 'upcoming')
                    <div class="score-card">
                        <div class="sc-top">
                            <span class="sc-tournament">{{ $match->tournament->name ?? 'Tournament' }} · {{ $match->overs_per_side }}-over</span>
                            <span class="sc-status-done" style="color:var(--gold);">⏱ {{ $match->match_date->format('d M') }}</span>
                        </div>
                        <div class="sc-upcoming">
                            <div class="sc-upcoming-teams">
                                <span class="sc-team-name" style="flex:1;">{{ $match->homeSchool->school_name ?? 'TBD' }}</span>
                                <span class="sc-upcoming-vs">VS</span>
                                <span class="sc-team-name" style="flex:1; text-align:right;">{{ $match->awaySchool->school_name ?? 'TBD' }}</span>
                            </div>
                            @if($match->venue)<div class="sc-upcoming-venue">{{ $match->venue }}</div>@endif
                        </div>
                        <div class="sc-footer" style="text-align:center;">Upcoming fixture</div>
                    </div>
                @endif
            @empty
                <div class="no-matches-msg"><span class="empty-icon">🏏</span><p>No matches scheduled at the moment. Check back soon!</p></div>
            @endforelse
        </div>
    </div>
</div>

<!-- ── RANKINGS ── -->
<div class="section-divider">
    <span class="divider-text">Island Rankings {{ date('Y') }}</span>
    <div class="divider-line"></div>
    <span class="divider-num">{{ str_pad(count($topRankings), 2, '0', STR_PAD_LEFT) }} Categories</span>
</div>

<div class="rankings-area">
    <div class="rankings-header">
        <h2 class="section-title">Island <span>Rankings</span></h2>
        <div class="cat-tabs">
            <button class="cat-tab active" onclick="filterRankings('all', this)">All</button>
            <button class="cat-tab" onclick="filterRankings('batting', this)">Batting</button>
            <button class="cat-tab" onclick="filterRankings('bowling', this)">Bowling</button>
            <button class="cat-tab" onclick="filterRankings('all-rounders', this)">All-Rounders</button>
        </div>
    </div>

    <div class="rankings-grid" id="rankingsGrid">
        @php
            $categoryIcons = ['Batsman'=>'🏏','Power Hitter'=>'⚡','Spinner'=>'🌀','Fast Bowler'=>'🚀','Spin All-Rounder'=>'🔄','Fast Bowling All-Rounder'=>'💥'];
            $categoryGroups = ['Batsman'=>'batting','Power Hitter'=>'batting','Spinner'=>'bowling','Fast Bowler'=>'bowling','Spin All-Rounder'=>'all-rounders','Fast Bowling All-Rounder'=>'all-rounders'];
        @endphp
        @foreach($topRankings as $category => $players)
            <div class="rank-panel" data-group="{{ $categoryGroups[$category] ?? 'all' }}">
                <div class="rp-header"><span class="rp-category">{{ $categoryIcons[$category] ?? '🏅' }} {{ $category }}</span></div>
                @if($players->isEmpty())
                    <div class="rp-empty"><span class="rp-empty-icon">🏅</span> No rankings available yet</div>
                @else
                    <table class="rp-table">
                        <thead><tr><th>#</th><th>Player</th><th></th><th>Pts</th></tr></thead>
                        <tbody>
                            @foreach($players as $i => $player)
                                @php $rank = $i + 1; $s = $player->stats; @endphp
                                <tr>
                                    <td class="rank-num {{ $rank === 1 ? 'gold-rank' : '' }}">{{ $rank }}</td>
                                    <td><div class="player-cell"><span class="player-cell-name">{{ $player->full_name }}</span><span class="player-cell-school">{{ $player->school->school_name ?? '—' }}</span></div></td>
                                    <td><span class="change-same">—</span></td>
                                    <td class="pts-cell">{{ number_format($s->ranking_points ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
    function filterRankings(group, btn) {
        document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.rank-panel').forEach(p => {
            if (group === 'all') p.classList.remove('hidden');
            else p.classList.toggle('hidden', p.dataset.group !== group);
        });
    }
</script>
@endpush
