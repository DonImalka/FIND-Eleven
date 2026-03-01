@extends('layouts.website')

@section('title', 'Player Rankings — FindEleven')

@section('content')

<!-- ── PAGE HERO ── -->
<div class="page-hero">
    <div class="page-hero-bg"></div>
    <span class="page-hero-year">{{ $ageCategory }}</span>
    <div class="page-hero-content">
        <div class="hero-eyebrow">{{ $ageCategory }} · {{ $rankingCategory }}</div>
        <h1 class="page-hero-title">Player <span>Rankings</span></h1>
        <p class="page-hero-desc">
            Sri Lanka school cricket rankings — tracking performance across all provinces and districts.
        </p>
    </div>
</div>

<!-- ── AGE CATEGORY TABS ── -->
<div class="section-divider">
    <span class="divider-text">Age Group</span>
    <div class="divider-line"></div>
</div>

<div class="rankings-page">
    <div class="cat-tabs" style="margin-bottom: 20px;">
        @foreach(['U15', 'U17', 'U19'] as $age)
            <a href="{{ route('rankings.index', array_merge(request()->query(), ['age' => $age])) }}"
               class="cat-tab {{ $ageCategory === $age ? 'active' : '' }}"
               style="text-decoration:none;">
                {{ $age }}
            </a>
        @endforeach
    </div>

    <!-- ── RANKING CATEGORY TABS ── -->
    @php
        $icons = ['Batsman'=>'🏏','Power Hitter'=>'💥','Spinner'=>'🌀','Fast Bowler'=>'⚡','Spin All-Rounder'=>'🔄','Fast Bowling All-Rounder'=>'🎯'];
    @endphp
    <div class="cat-tabs" style="margin-bottom: 24px; flex-wrap: wrap;">
        @foreach($rankingCategories as $cat)
            <a href="{{ route('rankings.index', array_merge(request()->query(), ['category' => $cat])) }}"
               class="cat-tab {{ $rankingCategory === $cat ? 'active' : '' }}"
               style="text-decoration:none;">
                {{ $icons[$cat] ?? '🏅' }} {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- ── SCOPE FILTERS ── -->
    <div class="filter-bar">
        <form method="GET" action="{{ route('rankings.index') }}" id="filterForm" style="display:flex; flex-wrap:wrap; align-items:flex-end; gap:16px; width:100%;">
            <input type="hidden" name="age" value="{{ $ageCategory }}">
            <input type="hidden" name="category" value="{{ $rankingCategory }}">

            <div class="filter-group">
                <label>Scope</label>
                <select name="scope" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="all_island" {{ $scope === 'all_island' ? 'selected' : '' }}>🌏 All Island</option>
                    <option value="province" {{ $scope === 'province' ? 'selected' : '' }}>🏛️ Province</option>
                    <option value="district" {{ $scope === 'district' ? 'selected' : '' }}>📍 District</option>
                </select>
            </div>

            @if($scope === 'province')
                <div class="filter-group">
                    <label>Province</label>
                    <select name="scope_value" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                        <option value="">All Provinces</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov }}" {{ $scopeValue === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif($scope === 'district')
                <div class="filter-group">
                    <label>District</label>
                    <select name="scope_value" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                        <option value="">All Districts</option>
                        @foreach($districts as $dist)
                            <option value="{{ $dist }}" {{ $scopeValue === $dist ? 'selected' : '' }}>{{ $dist }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <span class="filter-count">{{ $players->count() }} player{{ $players->count() !== 1 ? 's' : '' }} found</span>
        </form>
    </div>

    <!-- ── RANKINGS TABLE ── -->
    @if($players->isEmpty())
        <div class="empty-state" style="padding: 80px 24px;">
            <span class="empty-state-icon">🏅</span>
            <h3>No Rankings Available</h3>
            <p>No players found for {{ $ageCategory }} {{ $rankingCategory }} in the selected scope.</p>
        </div>
    @else
        @php
            $isBatter = in_array($rankingCategory, ['Batsman', 'Power Hitter']);
            $isBowler = in_array($rankingCategory, ['Fast Bowler', 'Spinner']);
            $isAllRounder = in_array($rankingCategory, ['Spin All-Rounder', 'Fast Bowling All-Rounder']);
        @endphp

        <div class="rankings-full-table">
            <table class="rank-table">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Player</th>
                        <th>School</th>
                        @if($isBatter || $isAllRounder)
                            <th class="text-center">Runs</th>
                            <th class="text-center">Avg</th>
                            <th class="text-center">SR</th>
                        @endif
                        @if($isBowler || $isAllRounder)
                            <th class="text-center">Wkts</th>
                            <th class="text-center">Econ</th>
                        @endif
                        <th class="text-center">Catches</th>
                        <th class="text-center">Matches</th>
                        <th class="text-right" style="width:100px;">Points</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($players as $index => $player)
                        @php
                            $rank = $index + 1;
                            $s = $player->stats;
                        @endphp
                        <tr>
                            <td>
                                <span class="rank-badge {{ $rank === 1 ? 'gold' : ($rank === 2 ? 'silver' : ($rank === 3 ? 'bronze' : 'normal')) }}">
                                    {{ $rank }}
                                </span>
                            </td>
                            <td>
                                <div class="rank-player-cell">
                                    <div class="rank-player-avatar">{{ strtoupper(substr($player->full_name, 0, 1)) }}</div>
                                    <div>
                                        <div class="rank-player-name">{{ $player->full_name }}</div>
                                        <div class="rank-player-meta">{{ $player->batting_style ?? '' }} · #{{ $player->jersey_number ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="rank-school-name">{{ $player->school->school_name ?? '—' }}</div>
                                <div class="rank-school-loc">{{ $player->school->district ?? '' }}{{ $player->school->district && $player->school->province ? ', ' : '' }}{{ $player->school->province ?? '' }}</div>
                            </td>
                            @if($isBatter || $isAllRounder)
                                <td class="text-center rank-stat">{{ $s->batting_runs ?? 0 }}</td>
                                <td class="text-center rank-stat">{{ number_format($s->batting_average ?? 0, 1) }}</td>
                                <td class="text-center rank-stat">{{ number_format($s->batting_strike_rate ?? 0, 1) }}</td>
                            @endif
                            @if($isBowler || $isAllRounder)
                                <td class="text-center rank-stat">{{ $s->bowling_wickets ?? 0 }}</td>
                                <td class="text-center rank-stat">{{ number_format($s->bowling_economy ?? 0, 1) }}</td>
                            @endif
                            <td class="text-center rank-stat">{{ $s->fielding_catches ?? 0 }}</td>
                            <td class="text-center rank-stat">{{ $s->batting_matches ?? 0 }}</td>
                            <td class="text-right">
                                <span class="rank-points {{ $rank <= 3 ? 'top' : '' }}">{{ number_format($s->ranking_points ?? 0) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ── POINTS LEGEND ── -->
        <div class="points-legend">
            <h4>📋 Points System</h4>
            <div class="points-legend-grid">
                <div class="points-legend-col">
                    <h5>🏏 Batting</h5>
                    <ul>
                        <li>1 run = 1 pt</li>
                        <li>50 runs = +10 bonus</li>
                        <li>100 runs = +25 bonus</li>
                        <li>200 runs = +50 bonus</li>
                        <li>1 four = 2 pts</li>
                        <li>1 six = 3 pts</li>
                        <li>SR 100+ = +10 bonus</li>
                    </ul>
                </div>
                <div class="points-legend-col">
                    <h5>🎯 Bowling</h5>
                    <ul>
                        <li>1 wicket = 20 pts</li>
                        <li>3 wickets = +10 bonus</li>
                        <li>5 wickets = +25 bonus</li>
                        <li>Maiden over = +10 bonus</li>
                        <li>Economy &lt;5 = +10 bonus</li>
                        <li>Dot ball = 1 pt</li>
                    </ul>
                </div>
                <div class="points-legend-col">
                    <h5>🧤 Fielding</h5>
                    <ul>
                        <li>1 catch = 1 pt</li>
                    </ul>
                    <h5 style="margin-top:16px;">🔄 All-Rounders</h5>
                    <ul>
                        <li>Batting + Bowling + Fielding combined</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
