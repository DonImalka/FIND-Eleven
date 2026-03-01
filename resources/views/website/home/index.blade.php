<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanka School Cricket — Island Rankings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Bebas+Neue&family=IBM+Plex+Sans:wght@300;400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

<!-- ── NAVIGATION ── -->
<nav class="site-nav">
    <a href="{{ route('home') }}" class="nav-brand">
        <!-- SL Inspired Crest -->
        <svg class="crest" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="22" cy="22" r="20" stroke="#C8973A" stroke-width="1.5"/>
            <circle cx="22" cy="22" r="16" stroke="rgba(200,151,58,0.35)" stroke-width="0.5"/>
            <path d="M14 26 Q12 22 15 19 Q17 16 20 17 Q22 15 24 16 Q27 15 29 18 Q32 22 30 26 Q28 30 22 31 Q16 30 14 26Z" fill="rgba(200,151,58,0.15)" stroke="#C8973A" stroke-width="0.8"/>
            <path d="M19 20 Q21 18 23 19 Q25 20 24 22 Q23 24 21 24 Q19 24 19 22Z" fill="#C8973A" opacity="0.6"/>
            <line x1="22" y1="12" x2="22" y2="32" stroke="rgba(200,151,58,0.5)" stroke-width="0.5"/>
            <line x1="15" y1="22" x2="29" y2="22" stroke="rgba(200,151,58,0.5)" stroke-width="0.5"/>
            <circle cx="16" cy="15" r="1" fill="#C8973A" opacity="0.8"/>
            <circle cx="22" cy="12" r="1" fill="#C8973A" opacity="0.8"/>
            <circle cx="28" cy="15" r="1" fill="#C8973A" opacity="0.8"/>
        </svg>
        <div class="brand-text">
            <span class="brand-top">Lanka Cricket</span>
            <span class="brand-sub">School Island Rankings</span>
        </div>
    </a>

    <ul class="nav-links">
        <li><a href="{{ route('home') }}" class="active">Home</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li>
            <a href="{{ route('live-scores.index') }}" style="color:inherit; opacity:1; display:flex; align-items:center; gap:8px;">
                <span class="nav-live-badge">
                    @if($liveMatches->isNotEmpty())<span class="live-dot"></span>@endif
                    Live Scores
                </span>
            </a>
        </li>
        <li><a href="{{ route('rankings.index') }}">Rankings</a></li>
    </ul>

    @guest
        <a href="{{ route('login') }}" class="btn-login">Login</a>
    @else
        <a href="{{ route('dashboard') }}" class="btn-dashboard">Dashboard</a>
    @endguest
</nav>

<!-- ── LIVE TICKER ── -->
@if($tickerMatches->isNotEmpty())
<div class="live-ticker">
    <div class="live-label">
        <span class="live-dot"></span>
        Live
    </div>
    <div class="ticker-scroll-wrap">
        <div class="ticker-scroll">
            {{-- First pass --}}
            @foreach($tickerMatches as $tm)
                <div class="ticker-match">
                    <span class="ticker-teams">{{ $tm->homeSchool->school_name ?? 'TBD' }} vs {{ $tm->awaySchool->school_name ?? 'TBD' }}</span>
                    @php
                        $lastInning = $tm->innings->sortByDesc('inning_number')->first();
                    @endphp
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
            {{-- Duplicate for seamless loop --}}
            @foreach($tickerMatches as $tm)
                <div class="ticker-match">
                    <span class="ticker-teams">{{ $tm->homeSchool->school_name ?? 'TBD' }} vs {{ $tm->awaySchool->school_name ?? 'TBD' }}</span>
                    @php
                        $lastInning = $tm->innings->sortByDesc('inning_number')->first();
                    @endphp
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
        <!-- Featured Player Card -->
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
            {{-- Static fallback card when no player data --}}
            <div class="featured-card">
                <div class="fc-header">
                    <span class="fc-tag">⭐ No.1 Ranked — Batsman</span>
                    <span class="fc-rank">#1</span>
                </div>
                <div class="fc-body">
                    <div class="player-avatar">
                        <svg viewBox="0 0 40 40" fill="none">
                            <circle cx="20" cy="12" r="6" fill="#C8973A" opacity="0.5"/>
                            <path d="M8 36 C8 26 12 22 20 22 C28 22 32 26 32 36" fill="#C8973A" opacity="0.3"/>
                        </svg>
                    </div>
                    <div class="player-info">
                        <div class="player-name">—</div>
                        <div class="player-school">No rankings yet</div>
                        <div class="player-category">Batsman</div>
                    </div>
                </div>
                <div class="fc-stats">
                    <div class="fc-stat">
                        <div class="fc-stat-val">—</div>
                        <div class="fc-stat-label">Rating</div>
                    </div>
                    <div class="fc-stat">
                        <div class="fc-stat-val">—</div>
                        <div class="fc-stat-label">Avg</div>
                    </div>
                    <div class="fc-stat">
                        <div class="fc-stat-val">—</div>
                        <div class="fc-stat-label">Innings</div>
                    </div>
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
                    {{-- ── LIVE MATCH CARD ── --}}
                    <a href="{{ route('live-scores.show', $match) }}" class="score-card live-card" style="text-decoration:none; color:inherit;">
                        <div class="sc-top">
                            <span class="sc-tournament">{{ $match->tournament->name ?? 'Tournament' }} · {{ $match->overs_per_side }}-over</span>
                            <span class="sc-status-live">
                                <span class="live-dot"></span>
                                Live
                                @php $currentInning = $match->innings->where('is_completed', false)->first(); @endphp
                                @if($currentInning)
                                    · {{ $currentInning->total_overs }} ov
                                @endif
                            </span>
                        </div>
                        <div class="sc-match">
                            @php
                                $innings = $match->innings->sortBy('inning_number');
                                $firstInning = $innings->first();
                                $secondInning = $innings->count() > 1 ? $innings->last() : null;

                                // Determine which team is currently batting
                                $currentBatting = $match->innings->where('is_completed', false)->first();
                                $currentBattingSchoolId = $currentBatting ? $currentBatting->batting_school_id : null;
                            @endphp

                            {{-- Home team row --}}
                            <div class="sc-team-row">
                                @php
                                    $homeIsBatting = $currentBattingSchoolId === $match->home_school_id;
                                    $homeInning = $innings->where('batting_school_id', $match->home_school_id)->first();
                                @endphp
                                <span class="sc-team-name {{ $homeIsBatting ? 'batting' : '' }}">{{ $match->homeSchool->school_name ?? 'TBD' }}</span>
                                @if($homeInning)
                                    <span class="sc-score-val {{ $homeIsBatting ? 'batting' : '' }}">{{ $homeInning->total_runs }}/{{ $homeInning->total_wickets }} <span class="sc-overs">({{ $homeInning->total_overs }})</span></span>
                                @else
                                    <span class="sc-score-val">Yet to bat</span>
                                @endif
                            </div>

                            {{-- Away team row --}}
                            <div class="sc-team-row">
                                @php
                                    $awayIsBatting = $currentBattingSchoolId === $match->away_school_id;
                                    $awayInning = $innings->where('batting_school_id', $match->away_school_id)->first();
                                @endphp
                                <span class="sc-team-name {{ $awayIsBatting ? 'batting' : '' }}">{{ $match->awaySchool->school_name ?? 'TBD' }}</span>
                                @if($awayInning)
                                    <span class="sc-score-val {{ $awayIsBatting ? 'batting' : '' }}">{{ $awayInning->total_runs }}/{{ $awayInning->total_wickets }} <span class="sc-overs">({{ $awayInning->total_overs }})</span></span>
                                @else
                                    <span class="sc-score-val">Yet to bat</span>
                                @endif
                            </div>
                        </div>
                        <div class="sc-footer">
                            @php
                                // Find current top scorer and top bowler
                                $topBatter = null;
                                $topBowler = null;
                                if ($currentBatting) {
                                    $topBatter = $currentBatting->batterScores->where('status', '!=', 'yet_to_bat')->sortByDesc('runs')->first();
                                }
                                $completedInning = $innings->where('is_completed', true)->first();
                                if ($completedInning) {
                                    $topBowler = $completedInning->bowlerScores->sortByDesc('wickets')->first();
                                }
                            @endphp
                            @if($topBatter)
                                <strong>{{ $topBatter->player->full_name ?? '—' }} {{ $topBatter->runs }}*({{ $topBatter->balls_faced }})</strong>
                            @endif
                            @if($topBowler)
                                &nbsp; {{ $topBowler->player->full_name ?? '—' }} {{ $topBowler->wickets }}/{{ $topBowler->runs_conceded }}
                            @endif
                            @if(!$topBatter && !$topBowler)
                                Match in progress
                            @endif
                        </div>
                    </a>

                @elseif($match->status === 'completed')
                    {{-- ── COMPLETED MATCH CARD ── --}}
                    <a href="{{ route('live-scores.show', $match) }}" class="score-card" style="text-decoration:none; color:inherit;">
                        <div class="sc-top">
                            <span class="sc-tournament">{{ $match->tournament->name ?? 'Tournament' }} · {{ $match->overs_per_side }}-over</span>
                            <span class="sc-status-done">✓ Result</span>
                        </div>
                        <div class="sc-match">
                            @php $innings = $match->innings->sortBy('inning_number'); @endphp

                            {{-- Home team --}}
                            <div class="sc-team-row">
                                @php $homeInning = $innings->where('batting_school_id', $match->home_school_id)->first(); @endphp
                                <span class="sc-team-name">{{ $match->homeSchool->school_name ?? 'TBD' }}</span>
                                @if($homeInning)
                                    <span class="sc-score-val">{{ $homeInning->total_runs }}/{{ $homeInning->total_wickets }} <span class="sc-overs">({{ $homeInning->total_overs }})</span></span>
                                @else
                                    <span class="sc-score-val">—</span>
                                @endif
                            </div>

                            {{-- Away team --}}
                            <div class="sc-team-row">
                                @php $awayInning = $innings->where('batting_school_id', $match->away_school_id)->first(); @endphp
                                <span class="sc-team-name">{{ $match->awaySchool->school_name ?? 'TBD' }}</span>
                                @if($awayInning)
                                    <span class="sc-score-val">{{ $awayInning->total_runs }}/{{ $awayInning->total_wickets }} <span class="sc-overs">({{ $awayInning->total_overs }})</span></span>
                                @else
                                    <span class="sc-score-val">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="sc-footer">
                            <strong>{{ $match->result_summary ?? 'Match completed' }}</strong>
                        </div>
                    </a>

                @elseif($match->status === 'upcoming')
                    {{-- ── UPCOMING MATCH CARD ── --}}
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
                            @if($match->venue)
                                <div class="sc-upcoming-venue">{{ $match->venue }}</div>
                            @endif
                        </div>
                        <div class="sc-footer" style="text-align:center;">Upcoming fixture</div>
                    </div>
                @endif
            @empty
                <div class="no-matches-msg">
                    <span class="empty-icon">🏏</span>
                    <p>No matches scheduled at the moment. Check back soon!</p>
                </div>
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
            $categoryIcons = [
                'Batsman' => '🏏',
                'Power Hitter' => '⚡',
                'Spinner' => '🌀',
                'Fast Bowler' => '🚀',
                'Spin All-Rounder' => '🔄',
                'Fast Bowling All-Rounder' => '💥',
            ];
            $categoryGroups = [
                'Batsman' => 'batting',
                'Power Hitter' => 'batting',
                'Spinner' => 'bowling',
                'Fast Bowler' => 'bowling',
                'Spin All-Rounder' => 'all-rounders',
                'Fast Bowling All-Rounder' => 'all-rounders',
            ];
        @endphp

        @foreach($topRankings as $category => $players)
            <div class="rank-panel" data-group="{{ $categoryGroups[$category] ?? 'all' }}">
                <div class="rp-header">
                    <span class="rp-category">{{ $categoryIcons[$category] ?? '🏅' }} {{ $category }}</span>
                </div>

                @if($players->isEmpty())
                    <div class="rp-empty">
                        <span class="rp-empty-icon">🏅</span>
                        No rankings available yet
                    </div>
                @else
                    <table class="rp-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Player</th>
                                <th></th>
                                <th>Pts</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $i => $player)
                                @php $rank = $i + 1; $s = $player->stats; @endphp
                                <tr>
                                    <td class="rank-num {{ $rank === 1 ? 'gold-rank' : '' }}">{{ $rank }}</td>
                                    <td>
                                        <div class="player-cell">
                                            <span class="player-cell-name">{{ $player->full_name }}</span>
                                            <span class="player-cell-school">{{ $player->school->school_name ?? '—' }}</span>
                                        </div>
                                    </td>
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

<!-- ── FOOTER ── -->
<div class="footer-band">
    <span class="footer-copy">&copy; {{ date('Y') }} Lanka School Cricket Rankings. All rights reserved.</span>
    <span class="footer-powered">Powered by <span>Find Eleven</span></span>
</div>

<!-- ── SCRIPTS ── -->
<script>
    function filterRankings(group, btn) {
        // Update active tab
        document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');

        // Filter panels
        const panels = document.querySelectorAll('.rank-panel');
        panels.forEach(panel => {
            if (group === 'all') {
                panel.classList.remove('hidden');
            } else {
                if (panel.dataset.group === group) {
                    panel.classList.remove('hidden');
                } else {
                    panel.classList.add('hidden');
                }
            }
        });
    }
</script>

</body>
</html>
