<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Scores - Find11</title>
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

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>🏏 Live Scores</h1>
            <p>Follow school cricket matches live</p>
        </div>
    </section>

    <!-- Live Matches -->
    @if($liveMatches->count())
    <section class="matches-section">
        <div class="container">
            <h2 class="section-heading"><span class="live-dot"></span> Live Now</h2>
            <div class="matches-grid">
                @foreach($liveMatches as $match)
                    <a href="{{ route('live-scores.show', $match) }}" class="match-card match-card--live">
                        <div class="match-card__tournament">{{ $match->tournament->name }}</div>
                        <div class="match-card__teams">
                            <div class="match-card__team">
                                <span class="team-name">{{ $match->homeSchool->school_name }}</span>
                                @php $homeInning = $match->innings->where('batting_school_id', $match->home_school_id)->first(); @endphp
                                @if($homeInning)
                                    <span class="team-score">{{ $homeInning->total_runs }}/{{ $homeInning->total_wickets }} <small>({{ $homeInning->total_overs }} ov)</small></span>
                                @else
                                    <span class="team-score team-score--pending">Yet to bat</span>
                                @endif
                            </div>
                            <div class="match-card__vs">vs</div>
                            <div class="match-card__team">
                                <span class="team-name">{{ $match->awaySchool->school_name }}</span>
                                @php $awayInning = $match->innings->where('batting_school_id', $match->away_school_id)->first(); @endphp
                                @if($awayInning)
                                    <span class="team-score">{{ $awayInning->total_runs }}/{{ $awayInning->total_wickets }} <small>({{ $awayInning->total_overs }} ov)</small></span>
                                @else
                                    <span class="team-score team-score--pending">Yet to bat</span>
                                @endif
                            </div>
                        </div>
                        <div class="match-card__status match-card__status--live">🔴 LIVE</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Upcoming Matches -->
    @if($upcomingMatches->count())
    <section class="matches-section">
        <div class="container">
            <h2 class="section-heading">📅 Upcoming</h2>
            <div class="matches-grid">
                @foreach($upcomingMatches as $match)
                    <div class="match-card">
                        <div class="match-card__tournament">{{ $match->tournament->name }}</div>
                        <div class="match-card__teams">
                            <div class="match-card__team">
                                <span class="team-name">{{ $match->homeSchool->school_name }}</span>
                            </div>
                            <div class="match-card__vs">vs</div>
                            <div class="match-card__team">
                                <span class="team-name">{{ $match->awaySchool->school_name }}</span>
                            </div>
                        </div>
                        <div class="match-card__meta">
                            <span>📍 {{ $match->venue ?? 'TBD' }}</span>
                            <span>📅 {{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y') }}</span>
                            <span>🏏 {{ $match->overs_per_side }} overs</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Recent/Completed Matches -->
    @if($recentMatches->count())
    <section class="matches-section">
        <div class="container">
            <h2 class="section-heading">✅ Recent Results</h2>
            <div class="matches-grid">
                @foreach($recentMatches as $match)
                    <a href="{{ route('live-scores.show', $match) }}" class="match-card">
                        <div class="match-card__tournament">{{ $match->tournament->name }}</div>
                        <div class="match-card__teams">
                            <div class="match-card__team">
                                <span class="team-name">{{ $match->homeSchool->school_name }}</span>
                                @php $homeInning = $match->innings->where('batting_school_id', $match->home_school_id)->first(); @endphp
                                @if($homeInning)
                                    <span class="team-score">{{ $homeInning->total_runs }}/{{ $homeInning->total_wickets }}</span>
                                @endif
                            </div>
                            <div class="match-card__vs">vs</div>
                            <div class="match-card__team">
                                <span class="team-name">{{ $match->awaySchool->school_name }}</span>
                                @php $awayInning = $match->innings->where('batting_school_id', $match->away_school_id)->first(); @endphp
                                @if($awayInning)
                                    <span class="team-score">{{ $awayInning->total_runs }}/{{ $awayInning->total_wickets }}</span>
                                @endif
                            </div>
                        </div>
                        @if($match->result_summary)
                            <div class="match-card__result">{{ $match->result_summary }}</div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($liveMatches->count() === 0 && $upcomingMatches->count() === 0 && $recentMatches->count() === 0)
    <section class="matches-section">
        <div class="container">
            <div class="empty-state">
                <div class="empty-state__icon">🏏</div>
                <h3>No Matches Yet</h3>
                <p>Check back soon for live scores and upcoming matches!</p>
            </div>
        </div>
    </section>
    @endif

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
</body>
</html>
