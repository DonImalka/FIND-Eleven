@extends('layouts.website')

@section('title', 'Live Scores — FindEleven')

@section('content')

<!-- ── PAGE HERO ── -->
<div class="page-hero">
    <div class="page-hero-bg"></div>
    <span class="page-hero-year">LIVE</span>
    <div class="page-hero-content">
        <div class="hero-eyebrow">Match Centre</div>
        <h1 class="page-hero-title">Live <span>Scores</span></h1>
        <p class="page-hero-desc">
            Follow school cricket matches live — ball-by-ball updates, full scorecards, and real-time results from across the island.
        </p>
    </div>
</div>

<!-- ── LIVE MATCHES ── -->
@if($liveMatches->count())
<div class="section-divider">
    <span class="divider-text">Live Now</span>
    <div class="divider-line"></div>
    <span class="divider-num">{{ str_pad($liveMatches->count(), 2, '0', STR_PAD_LEFT) }}</span>
</div>

<div class="matches-section">
    <h2 class="matches-section-title"><span class="live-dot"></span> Live <span>Matches</span></h2>
    <div class="match-grid">
        @foreach($liveMatches as $match)
            <a href="{{ route('live-scores.show', $match) }}" class="score-card live-card" style="text-decoration:none; color:inherit;">
                <div class="sc-top">
                    <span class="sc-tournament">{{ $match->tournament->name ?? 'Tournament' }} · {{ $match->overs_per_side }}-over</span>
                    <span class="sc-status-live">
                        <span class="live-dot"></span>
                        Live
                        @php $ci = $match->innings->where('is_completed', false)->first(); @endphp
                        @if($ci) · {{ $ci->total_overs }} ov @endif
                    </span>
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
                        @if($hi)
                            <span class="sc-score-val {{ $hb ? 'batting' : '' }}">{{ $hi->total_runs }}/{{ $hi->total_wickets }} <span class="sc-overs">({{ $hi->total_overs }})</span></span>
                        @else
                            <span class="sc-score-val">Yet to bat</span>
                        @endif
                    </div>
                    <div class="sc-team-row">
                        @php $ab = $cbsId === $match->away_school_id; $ai = $innings->where('batting_school_id', $match->away_school_id)->first(); @endphp
                        <span class="sc-team-name {{ $ab ? 'batting' : '' }}">{{ $match->awaySchool->school_name ?? 'TBD' }}</span>
                        @if($ai)
                            <span class="sc-score-val {{ $ab ? 'batting' : '' }}">{{ $ai->total_runs }}/{{ $ai->total_wickets }} <span class="sc-overs">({{ $ai->total_overs }})</span></span>
                        @else
                            <span class="sc-score-val">Yet to bat</span>
                        @endif
                    </div>
                </div>
                <div class="sc-footer">
                    @php
                        $topBat = $cb ? $cb->batterScores->where('status','!=','yet_to_bat')->sortByDesc('runs')->first() : null;
                        $compInn = $innings->where('is_completed', true)->first();
                        $topBwl = $compInn ? $compInn->bowlerScores->sortByDesc('wickets')->first() : null;
                    @endphp
                    @if($topBat)<strong>{{ $topBat->player->full_name ?? '—' }} {{ $topBat->runs }}*({{ $topBat->balls_faced }})</strong>@endif
                    @if($topBwl) {{ $topBwl->player->full_name ?? '—' }} {{ $topBwl->wickets }}/{{ $topBwl->runs_conceded }}@endif
                    @if(!$topBat && !$topBwl) Match in progress @endif
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- ── UPCOMING MATCHES ── -->
@if($upcomingMatches->count())
<div class="section-divider">
    <span class="divider-text">Upcoming</span>
    <div class="divider-line"></div>
    <span class="divider-num">{{ str_pad($upcomingMatches->count(), 2, '0', STR_PAD_LEFT) }}</span>
</div>

<div class="matches-section">
    <h2 class="matches-section-title">Upcoming <span>Fixtures</span></h2>
    <div class="match-grid">
        @foreach($upcomingMatches as $match)
            <div class="score-card">
                <div class="sc-top">
                    <span class="sc-tournament">{{ $match->tournament->name ?? 'Tournament' }} · {{ $match->overs_per_side }}-over</span>
                    <span class="sc-status-done" style="color:var(--gold);">⏱ {{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}</span>
                </div>
                <div class="sc-upcoming">
                    <div class="sc-upcoming-teams">
                        <span class="sc-team-name" style="flex:1;">{{ $match->homeSchool->school_name ?? 'TBD' }}</span>
                        <span class="sc-upcoming-vs">VS</span>
                        <span class="sc-team-name" style="flex:1; text-align:right;">{{ $match->awaySchool->school_name ?? 'TBD' }}</span>
                    </div>
                    @if($match->venue)
                        <div class="sc-upcoming-venue">📍 {{ $match->venue }}</div>
                    @endif
                </div>
                <div class="sc-footer" style="text-align:center;">{{ $match->overs_per_side }}-over match</div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- ── RECENT RESULTS ── -->
@if($recentMatches->count())
<div class="section-divider">
    <span class="divider-text">Recent Results</span>
    <div class="divider-line"></div>
    <span class="divider-num">{{ str_pad($recentMatches->count(), 2, '0', STR_PAD_LEFT) }}</span>
</div>

<div class="matches-section" style="margin-bottom: 80px;">
    <h2 class="matches-section-title">Recent <span>Results</span></h2>
    <div class="match-grid">
        @foreach($recentMatches as $match)
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
                @if($match->result_summary)
                    <div class="sc-footer"><strong>{{ $match->result_summary }}</strong></div>
                @endif
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- ── EMPTY STATE ── -->
@if($liveMatches->count() === 0 && $upcomingMatches->count() === 0 && $recentMatches->count() === 0)
<div class="section" style="padding-top: 40px; padding-bottom: 80px;">
    <div class="empty-state">
        <span class="empty-state-icon">🏏</span>
        <h3>No Matches Yet</h3>
        <p>Check back soon for live scores and upcoming matches!</p>
    </div>
</div>
@endif

@endsection
