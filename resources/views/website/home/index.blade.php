@extends('layouts.website')

@section('title', 'FindEleven — Island Rankings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/perfect-eleven.css') }}">
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

<!-- ── UPCOMING THIS WEEK ── -->
<div style="margin-top:56px;">
    <div class="section-divider">
        <span class="divider-text">This Week's Fixtures</span>
        <div class="divider-line"></div>
        <span class="divider-num">{{ str_pad($weeklyUpcoming->count(), 2, '0', STR_PAD_LEFT) }} Matches</span>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Upcoming <span>This Week</span></h2>
            <a href="{{ route('live-scores.index') }}" class="see-all">All Fixtures</a>
        </div>

        <div class="score-grid">
            @forelse($weeklyUpcoming as $match)
                <div class="score-card">
                    <div class="sc-top">
                        <span class="sc-tournament">{{ $match->tournament->name ?? 'Tournament' }} · {{ $match->overs_per_side }}-over</span>
                        <span class="sc-status-done" style="color:var(--gold);">📅 {{ $match->match_date->format('D, d M') }}</span>
                    </div>
                    <div class="sc-upcoming">
                        <div class="sc-upcoming-teams">
                            <span class="sc-team-name" style="flex:1;">{{ $match->homeSchool->school_name ?? 'TBD' }}</span>
                            <span class="sc-upcoming-vs">VS</span>
                            <span class="sc-team-name" style="flex:1; text-align:right;">{{ $match->awaySchool->school_name ?? 'TBD' }}</span>
                        </div>
                        @if($match->venue)<div class="sc-upcoming-venue">📍 {{ $match->venue }}</div>@endif
                    </div>
                    <div class="sc-footer" style="text-align:center;">{{ $match->match_date->isToday() ? 'Today' : ($match->match_date->isTomorrow() ? 'Tomorrow' : $match->match_date->format('l')) }}</div>
                </div>
            @empty
                <div class="no-matches-msg"><span class="empty-icon">🏏</span><p>No upcoming matches this week. Check back soon!</p></div>
            @endforelse
        </div>
    </div>
</div>

<!-- ── PERFECT XI ── -->
<div class="section-divider">
    <span class="divider-text">Perfect XI — Best Island XI</span>
    <div class="divider-line"></div>
    <span class="divider-num">11 Players</span>
</div>

<div class="p11-home-section">
    <div class="p11-home-header">
        <h2 class="section-title">Perfect <span>XI</span></h2>
        <div style="display:flex;align-items:center;gap:16px;">
            <div class="p11-home-tabs">
                @foreach([App\Models\Player::AGE_U15, App\Models\Player::AGE_U17, App\Models\Player::AGE_U19] as $hAge)
                    <button class="p11-home-tab {{ $hAge === App\Models\Player::AGE_U19 ? 'active' : '' }}" onclick="switchHomeAge('{{ $hAge }}', this)">{{ $hAge }}</button>
                @endforeach
            </div>
            <a href="{{ route('perfect-eleven.index') }}" class="see-all">View Full XI</a>
        </div>
    </div>

    @foreach([App\Models\Player::AGE_U15, App\Models\Player::AGE_U17, App\Models\Player::AGE_U19] as $hAge)
        <div class="p11-home-panel {{ $hAge === App\Models\Player::AGE_U19 ? '' : 'hidden' }}" id="home-p11-{{ $hAge }}">
            @php
                $allHomePlayers = collect();
                foreach ($perfectXI[$hAge] as $slot) {
                    foreach ($slot['players'] as $p) { $allHomePlayers->push($p); }
                }
            @endphp

            @if($allHomePlayers->isNotEmpty())
                <div class="p11-squad-card p11-squad-card--home">
                    <div class="p11-squad-title">
                        <span class="p11-squad-badge">🏏</span>
                        <span>{{ $hAge }} Perfect XI</span>
                    </div>
                    <div class="p11-squad-list">
                        @foreach($allHomePlayers as $idx => $player)
                            @php $s = $player->stats; @endphp
                            <div class="p11-squad-row">
                                <div class="p11-squad-num">{{ $idx + 1 }}</div>
                                <div class="p11-squad-avatar">
                                    <span>{{ strtoupper(substr($player->full_name, 0, 1)) }}</span>
                                </div>
                                <div class="p11-squad-info">
                                    <div class="p11-squad-name">{{ $player->full_name }}</div>
                                    <div class="p11-squad-meta">{{ $player->school->school_name ?? '—' }}</div>
                                </div>
                                <div class="p11-squad-role">{{ $player->player_category }}</div>
                                <div class="p11-squad-pts">
                                    <span class="p11-squad-pts-val">{{ number_format($s->ranking_points ?? 0) }}</span>
                                    <span class="p11-squad-pts-lbl">pts</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p11-squad-footer">{{ $allHomePlayers->count() }} of 11 positions filled</div>
                </div>
            @else
                <div class="p11-squad-card p11-squad-card--home">
                    <div class="p11-squad-empty">🏅 No qualifying players yet for {{ $hAge }}</div>
                </div>
            @endif
        </div>
    @endforeach
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

    function switchHomeAge(age, btn) {
        document.querySelectorAll('.p11-home-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.p11-home-panel').forEach(p => p.classList.add('hidden'));
        const panel = document.getElementById('home-p11-' + age);
        if (panel) panel.classList.remove('hidden');
    }
</script>
@endpush
