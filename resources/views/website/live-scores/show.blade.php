<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cricketMatch->getTitle() }} - Live Scores - Find11</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/live-scores.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="nav-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Find11 Logo" class="logo-img">
            </a>
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                <li><a href="{{ route('about') }}" class="nav-link">About</a></li>
                <li><a href="{{ route('live-scores.index') }}" class="nav-link active">Live Scores</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    <li><a href="{{ route('register') }}" class="nav-link btn-primary">Register</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}" class="nav-link btn-primary">Dashboard</a></li>
                @endguest
            </ul>
        </div>
    </nav>

    <!-- Match Header -->
    <section class="scorecard-header">
        <div class="container">
            <a href="{{ route('live-scores.index') }}" class="back-link">← Back to Live Scores</a>
            <div class="scorecard-header__tournament">{{ $cricketMatch->tournament->name }}</div>
            <h1 class="scorecard-header__title">{{ $cricketMatch->getTitle() }}</h1>
            <div class="scorecard-header__info">
                <span>📍 {{ $cricketMatch->venue ?? 'TBD' }}</span>
                <span>📅 {{ \Carbon\Carbon::parse($cricketMatch->match_date)->format('M d, Y') }}</span>
                <span>🏏 {{ $cricketMatch->overs_per_side }} overs</span>
            </div>

            @if($cricketMatch->status === 'live')
                <div class="live-badge">🔴 LIVE</div>
            @elseif($cricketMatch->status === 'completed')
                <div class="completed-badge">✅ Completed</div>
            @endif

            @if($cricketMatch->tossWinner)
                <div class="toss-info">Toss: {{ $cricketMatch->tossWinner->school_name }} won and chose to {{ $cricketMatch->toss_decision }}</div>
            @endif

            {{-- Score Summary --}}
            <div id="score-summary" class="score-summary">
                @foreach($cricketMatch->innings->sortBy('inning_number') as $inning)
                    <div class="score-summary__item {{ !$inning->is_completed ? 'score-summary__item--active' : '' }}">
                        <span class="score-summary__team">{{ $inning->battingSchool->school_name }}</span>
                        <span class="score-summary__score">{{ $inning->total_runs }}/{{ $inning->total_wickets }} ({{ $inning->total_overs }} ov)</span>
                    </div>
                @endforeach
            </div>

            @if($cricketMatch->result_summary)
                <div class="result-summary">{{ $cricketMatch->result_summary }}</div>
            @endif
        </div>
    </section>

    <!-- Scorecards -->
    <section class="scorecards">
        <div class="container" id="scorecards-container">
            @foreach($cricketMatch->innings->sortBy('inning_number') as $inning)
                <div class="innings-card">
                    <div class="innings-card__header">
                        <h2>{{ $inning->battingSchool->school_name }} — {{ $inning->inning_number == 1 ? '1st' : '2nd' }} Innings</h2>
                        <div class="innings-card__total">
                            {{ $inning->total_runs }}/{{ $inning->total_wickets }} ({{ $inning->total_overs }} ov)
                            @if($inning->extras > 0) <small>Extras: {{ $inning->extras }}</small> @endif
                        </div>
                    </div>

                    {{-- Batting Table --}}
                    <div class="score-table-wrap">
                        <table class="score-table">
                            <thead>
                                <tr>
                                    <th class="text-left">Batter</th>
                                    <th></th>
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
                                                @if($bs->status === 'batting') * @endif
                                                @if($bs->status === 'not_out') * @endif
                                            </td>
                                            <td class="dismissal-text">{{ $bs->status === 'out' ? $bs->dismissal_info : ($bs->status === 'batting' ? 'batting' : ($bs->status === 'not_out' ? 'not out' : $bs->status)) }}</td>
                                            <td class="text-center font-bold">{{ $bs->runs }}</td>
                                            <td class="text-center">{{ $bs->balls_faced }}</td>
                                            <td class="text-center">{{ $bs->fours }}</td>
                                            <td class="text-center">{{ $bs->sixes }}</td>
                                            <td class="text-center">{{ $bs->getStrikeRate() }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Bowling Table --}}
                    @php $activeBowlers = $inning->bowlerScores->filter(fn($b) => $b->overs !== '0' || $b->wickets > 0); @endphp
                    @if($activeBowlers->count())
                        <div class="score-table-wrap" style="margin-top: 1rem;">
                            <table class="score-table">
                                <thead>
                                    <tr>
                                        <th class="text-left">Bowler</th>
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
                                            <td class="text-center">{{ $bw->overs }}</td>
                                            <td class="text-center">{{ $bw->maidens }}</td>
                                            <td class="text-center">{{ $bw->runs_conceded }}</td>
                                            <td class="text-center font-bold">{{ $bw->wickets }}</td>
                                            <td class="text-center">{{ $bw->getEconomyRate() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach

            @if($cricketMatch->innings->isEmpty())
                <div class="empty-state">
                    <p>Match has not started yet. Scorecard will appear once the match begins.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Find11</h3>
                <p>Empowering cricket schools to discover and nurture young talent across the nation.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('live-scores.index') }}">Live Scores</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: info@find11.com</p>
                <p>Phone: +94 11 234 5678</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Find11. All rights reserved.</p>
        </div>
    </footer>

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
                        const activeClass = !inn.is_completed ? 'score-summary__item--active' : '';
                        summaryHtml += `
                            <div class="score-summary__item ${activeClass}">
                                <span class="score-summary__team">${inn.batting_team}</span>
                                <span class="score-summary__score">${inn.total_runs}/${inn.total_wickets} (${inn.total_overs} ov)</span>
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
                                    <td class="text-center font-bold">${b.runs}</td>
                                    <td class="text-center">${b.balls}</td>
                                    <td class="text-center">${b.fours}</td>
                                    <td class="text-center">${b.sixes}</td>
                                    <td class="text-center">${b.sr}</td>
                                </tr>`;
                            }
                        });

                        let bowlHtml = '';
                        inn.bowlers.forEach(bw => {
                            bowlHtml += `<tr>
                                <td class="batter-name">${bw.name}</td>
                                <td class="text-center">${bw.overs}</td>
                                <td class="text-center">${bw.maidens}</td>
                                <td class="text-center">${bw.runs}</td>
                                <td class="text-center font-bold">${bw.wickets}</td>
                                <td class="text-center">${bw.econ}</td>
                            </tr>`;
                        });

                        const inningLabel = inn.inning_number == 1 ? '1st' : '2nd';
                        cardsHtml += `
                        <div class="innings-card">
                            <div class="innings-card__header">
                                <h2>${inn.batting_team} — ${inningLabel} Innings</h2>
                                <div class="innings-card__total">${inn.total_runs}/${inn.total_wickets} (${inn.total_overs} ov)${inn.extras > 0 ? ' <small>Extras: '+inn.extras+'</small>' : ''}</div>
                            </div>
                            <div class="score-table-wrap">
                                <table class="score-table">
                                    <thead><tr><th class="text-left">Batter</th><th></th><th>R</th><th>B</th><th>4s</th><th>6s</th><th>SR</th></tr></thead>
                                    <tbody>${batHtml}</tbody>
                                </table>
                            </div>
                            ${bowlHtml ? `
                            <div class="score-table-wrap" style="margin-top: 1rem;">
                                <table class="score-table">
                                    <thead><tr><th class="text-left">Bowler</th><th>O</th><th>M</th><th>R</th><th>W</th><th>Econ</th></tr></thead>
                                    <tbody>${bowlHtml}</tbody>
                                </table>
                            </div>` : ''}
                        </div>`;
                    });

                    document.getElementById('scorecards-container').innerHTML = cardsHtml || '<div class="empty-state"><p>Scorecard will appear once the match begins.</p></div>';

                    // If match is no longer live, stop polling
                    if (data.status !== 'live') {
                        clearInterval(pollInterval);
                        if (data.result_summary) {
                            location.reload();
                        }
                    }
                })
                .catch(err => console.error('Score refresh failed:', err));
        }

        // Poll every 15 seconds
        const pollInterval = setInterval(refreshScores, 15000);
    </script>
    @endif
</body>
</html>
