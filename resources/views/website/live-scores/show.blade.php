@extends('layouts.website')

@section('title', $cricketMatch->getTitle() . ' — FindEleven')

@section('content')

<!-- ── SCORECARD HERO ── -->
<div class="scorecard-hero">
    <div class="scorecard-hero-bg"></div>
    <div class="scorecard-hero-content">
        <a href="{{ route('live-scores.index') }}" class="back-link">← Back to Live Scores</a>

        <div class="scorecard-tournament">{{ $cricketMatch->tournament->name ?? 'Tournament' }}</div>
        <h1 class="scorecard-title">{{ $cricketMatch->getTitle() }}</h1>

        <div class="scorecard-meta">
            <span>📍 {{ $cricketMatch->venue ?? 'TBD' }}</span>
            <span>📅 {{ \Carbon\Carbon::parse($cricketMatch->match_date)->format('d M Y') }}</span>
            <span>🏏 {{ $cricketMatch->overs_per_side }} overs</span>
        </div>

        <div class="scorecard-badges">
            @if($cricketMatch->status === 'live')
                <span class="badge-live"><span class="live-dot"></span> LIVE</span>
            @elseif($cricketMatch->status === 'completed')
                <span class="badge-completed">✓ Completed</span>
            @elseif($cricketMatch->status === 'upcoming')
                <span class="badge-completed">⏱ Upcoming</span>
            @endif
        </div>

        @if($cricketMatch->tossWinner)
            <div class="toss-info-bar">
                Toss: {{ $cricketMatch->tossWinner->school_name }} won and chose to {{ $cricketMatch->toss_decision }}
            </div>
        @endif

        <!-- Score Summary -->
        <div id="score-summary" class="score-summary-row">
            @foreach($cricketMatch->innings->sortBy('inning_number') as $inning)
                <div class="score-summary-item {{ !$inning->is_completed ? 'active-inning' : '' }}">
                    <span class="score-summary-team">{{ $inning->battingSchool->school_name }}</span>
                    <span class="score-summary-score">{{ $inning->total_runs }}/{{ $inning->total_wickets }} <small style="font-size:11px; color:var(--muted);">({{ $inning->total_overs }} ov)</small></span>
                </div>
            @endforeach
        </div>

        @if($cricketMatch->result_summary)
            <div class="result-banner">{{ $cricketMatch->result_summary }}</div>
        @endif
    </div>
</div>

<!-- ── INNINGS SCORECARDS ── -->
<div class="innings-section" id="scorecards-container">
    @foreach($cricketMatch->innings->sortBy('inning_number') as $inning)
        <div class="innings-card">
            <div class="innings-card-header">
                <h2>{{ $inning->battingSchool->school_name }} — {{ $inning->inning_number == 1 ? '1st' : '2nd' }} Innings</h2>
                <span class="innings-total">
                    {{ $inning->total_runs }}/{{ $inning->total_wickets }}
                    <small>({{ $inning->total_overs }} ov)@if($inning->extras > 0) · Extras: {{ $inning->extras }}@endif</small>
                </span>
            </div>

            {{-- Batting Table --}}
            <table class="score-table">
                <thead>
                    <tr>
                        <th style="text-align:left;">Batter</th>
                        <th style="text-align:left;"></th>
                        <th>R</th>
                        <th>B</th>
                        <th>4s</th>
                        <th>6s</th>
                        <th>SR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inning->batterScores->sortBy('batting_position') as $bs)
                        @if($bs->status !== 'yet_to_bat')
                            <tr class="{{ $bs->status === 'batting' ? 'batting-now' : '' }}">
                                <td class="batter-name">
                                    {{ $bs->player->full_name }}
                                    @if($bs->status === 'batting' || $bs->status === 'not_out') *@endif
                                </td>
                                <td class="dismissal-text">
                                    {{ $bs->status === 'out' ? $bs->dismissal_info : ($bs->status === 'batting' ? 'batting' : ($bs->status === 'not_out' ? 'not out' : $bs->status)) }}
                                </td>
                                <td class="font-bold" style="text-align:center;">{{ $bs->runs }}</td>
                                <td style="text-align:center;">{{ $bs->balls_faced }}</td>
                                <td style="text-align:center;">{{ $bs->fours }}</td>
                                <td style="text-align:center;">{{ $bs->sixes }}</td>
                                <td style="text-align:center;">{{ $bs->getStrikeRate() }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            {{-- Bowling Table --}}
            @php $activeBowlers = $inning->bowlerScores->filter(fn($b) => $b->overs !== '0' || $b->wickets > 0); @endphp
            @if($activeBowlers->count())
                <div class="bowling-divider"></div>
                <table class="score-table">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Bowler</th>
                            <th>O</th>
                            <th>M</th>
                            <th>R</th>
                            <th>W</th>
                            <th>Econ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeBowlers as $bw)
                            <tr>
                                <td class="batter-name">{{ $bw->player->full_name }}</td>
                                <td style="text-align:center;">{{ $bw->overs }}</td>
                                <td style="text-align:center;">{{ $bw->maidens }}</td>
                                <td style="text-align:center;">{{ $bw->runs_conceded }}</td>
                                <td class="font-bold" style="text-align:center;">{{ $bw->wickets }}</td>
                                <td style="text-align:center;">{{ $bw->getEconomyRate() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endforeach

    @if($cricketMatch->innings->isEmpty())
        <div class="empty-state" style="padding-bottom:80px;">
            <span class="empty-state-icon">🏏</span>
            <h3>Awaiting First Ball</h3>
            <p>Match has not started yet. Scorecard will appear once the match begins.</p>
        </div>
    @endif
</div>

@endsection

@push('scripts')
{{-- Auto-refresh for live matches --}}
@if($cricketMatch->status === 'live')
<script>
    const matchId = {{ $cricketMatch->id }};
    const dataUrl = "{{ route('live-scores.data', $cricketMatch) }}";

    function refreshScores() {
        fetch(dataUrl)
            .then(res => res.json())
            .then(data => {
                // Update score summary
                let summaryHtml = '';
                data.innings.forEach(inn => {
                    const activeClass = !inn.is_completed ? 'active-inning' : '';
                    summaryHtml += `
                        <div class="score-summary-item ${activeClass}">
                            <span class="score-summary-team">${inn.batting_team}</span>
                            <span class="score-summary-score">${inn.total_runs}/${inn.total_wickets} <small style="font-size:11px; color:var(--muted);">(${inn.total_overs} ov)</small></span>
                        </div>`;
                });
                document.getElementById('score-summary').innerHTML = summaryHtml;

                // Update full scorecards
                let cardsHtml = '';
                data.innings.forEach(inn => {
                    let batHtml = '';
                    inn.batters.forEach(b => {
                        if (b.status !== 'yet_to_bat') {
                            const highlight = b.status === 'batting' ? 'batting-now' : '';
                            const star = (b.status === 'batting' || b.status === 'not_out') ? ' *' : '';
                            const how = b.status === 'out' ? b.dismissal : (b.status === 'batting' ? 'batting' : (b.status === 'not_out' ? 'not out' : b.status));
                            batHtml += `<tr class="${highlight}">
                                <td class="batter-name">${b.name}${star}</td>
                                <td class="dismissal-text">${how || ''}</td>
                                <td class="font-bold" style="text-align:center;">${b.runs}</td>
                                <td style="text-align:center;">${b.balls}</td>
                                <td style="text-align:center;">${b.fours}</td>
                                <td style="text-align:center;">${b.sixes}</td>
                                <td style="text-align:center;">${b.sr}</td>
                            </tr>`;
                        }
                    });

                    let bowlHtml = '';
                    inn.bowlers.forEach(bw => {
                        bowlHtml += `<tr>
                            <td class="batter-name">${bw.name}</td>
                            <td style="text-align:center;">${bw.overs}</td>
                            <td style="text-align:center;">${bw.maidens}</td>
                            <td style="text-align:center;">${bw.runs}</td>
                            <td class="font-bold" style="text-align:center;">${bw.wickets}</td>
                            <td style="text-align:center;">${bw.econ}</td>
                        </tr>`;
                    });

                    const inningLabel = inn.inning_number == 1 ? '1st' : '2nd';
                    cardsHtml += `
                    <div class="innings-card">
                        <div class="innings-card-header">
                            <h2>${inn.batting_team} — ${inningLabel} Innings</h2>
                            <span class="innings-total">${inn.total_runs}/${inn.total_wickets} <small>(${inn.total_overs} ov)${inn.extras > 0 ? ' · Extras: '+inn.extras : ''}</small></span>
                        </div>
                        <table class="score-table">
                            <thead><tr><th style="text-align:left;">Batter</th><th style="text-align:left;"></th><th>R</th><th>B</th><th>4s</th><th>6s</th><th>SR</th></tr></thead>
                            <tbody>${batHtml}</tbody>
                        </table>
                        ${bowlHtml ? `
                        <div class="bowling-divider"></div>
                        <table class="score-table">
                            <thead><tr><th style="text-align:left;">Bowler</th><th>O</th><th>M</th><th>R</th><th>W</th><th>Econ</th></tr></thead>
                            <tbody>${bowlHtml}</tbody>
                        </table>` : ''}
                    </div>`;
                });

                document.getElementById('scorecards-container').innerHTML = cardsHtml || '<div class="empty-state"><span class="empty-state-icon">🏏</span><h3>Awaiting First Ball</h3><p>Scorecard will appear once the match begins.</p></div>';

                // Stop polling if match is no longer live
                if (data.status !== 'live') {
                    clearInterval(pollInterval);
                    if (data.result_summary) { location.reload(); }
                }
            })
            .catch(err => console.error('Score refresh failed:', err));
    }

    // Poll every 15 seconds
    const pollInterval = setInterval(refreshScores, 15000);
</script>
@endif
@endpush
