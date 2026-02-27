<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Rankings — Find11</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .rank-gold { background: linear-gradient(135deg, #fef3c7, #fde68a); }
        .rank-silver { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); }
        .rank-bronze { background: linear-gradient(135deg, #fed7aa, #fdba74); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen" x-data="rankingApp()">

    {{-- Header --}}
    <header class="bg-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <a href="{{ route('home') }}" class="text-indigo-200 hover:text-white text-sm">← Back to Home</a>
                    <h1 class="text-2xl font-black mt-1">🏆 Player Rankings</h1>
                    <p class="text-indigo-200 text-sm mt-1">Sri Lanka School Cricket Rankings</p>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white/10 rounded-lg text-sm hover:bg-white/20">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-white/10 rounded-lg text-sm hover:bg-white/20">Login</a>
                @endauth
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Age Category Tabs --}}
        <div class="flex gap-2 mb-6">
            @foreach(['U15', 'U17', 'U19'] as $age)
                <a href="{{ route('rankings.index', array_merge(request()->query(), ['age' => $age])) }}"
                   class="px-6 py-3 rounded-lg font-bold text-sm transition-all
                   {{ $ageCategory === $age ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                    {{ $age }}
                </a>
            @endforeach
        </div>

        {{-- Ranking Category Tabs --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($rankingCategories as $cat)
                @php
                    $icons = [
                        'Batsman' => '🏏',
                        'Power Hitter' => '💥',
                        'Spinner' => '🌀',
                        'Fast Bowler' => '⚡',
                        'Spin All-Rounder' => '🔄',
                        'Fast Bowling All-Rounder' => '🎯',
                    ];
                @endphp
                <a href="{{ route('rankings.index', array_merge(request()->query(), ['category' => $cat])) }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all
                   {{ $rankingCategory === $cat ? 'bg-gray-800 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                    {{ $icons[$cat] ?? '🏅' }} {{ $cat }}
                </a>
            @endforeach
        </div>

        {{-- Scope Filters --}}
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('rankings.index') }}" class="flex flex-wrap items-end gap-4">
                <input type="hidden" name="age" value="{{ $ageCategory }}">
                <input type="hidden" name="category" value="{{ $rankingCategory }}">

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Scope</label>
                    <select name="scope" onchange="this.form.submit()"
                        class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all_island" {{ $scope === 'all_island' ? 'selected' : '' }}>🌏 All Island</option>
                        <option value="province" {{ $scope === 'province' ? 'selected' : '' }}>🏛️ Province</option>
                        <option value="district" {{ $scope === 'district' ? 'selected' : '' }}>📍 District</option>
                    </select>
                </div>

                @if($scope === 'province')
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Province</label>
                        <select name="scope_value" onchange="this.form.submit()"
                            class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Provinces</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov }}" {{ $scopeValue === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($scope === 'district')
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">District</label>
                        <select name="scope_value" onchange="this.form.submit()"
                            class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Districts</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist }}" {{ $scopeValue === $dist ? 'selected' : '' }}>{{ $dist }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="text-sm text-gray-400">
                    {{ $players->count() }} player{{ $players->count() !== 1 ? 's' : '' }} found
                </div>
            </form>
        </div>

        {{-- Rankings Table --}}
        @if($players->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <div class="text-5xl mb-3">🏅</div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">No Rankings Available</h3>
                <p class="text-gray-400 text-sm">No players found for {{ $ageCategory }} {{ $rankingCategory }} in the selected scope.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase w-16">Rank</th>
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Player</th>
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">School</th>
                                @php
                                    $isBatter = in_array($rankingCategory, ['Batsman', 'Power Hitter']);
                                    $isBowler = in_array($rankingCategory, ['Fast Bowler', 'Spinner']);
                                    $isAllRounder = in_array($rankingCategory, ['Spin All-Rounder', 'Fast Bowling All-Rounder']);
                                @endphp
                                @if($isBatter || $isAllRounder)
                                    <th class="text-center py-3 px-3 text-xs font-bold text-gray-500 uppercase">Runs</th>
                                    <th class="text-center py-3 px-3 text-xs font-bold text-gray-500 uppercase">Avg</th>
                                    <th class="text-center py-3 px-3 text-xs font-bold text-gray-500 uppercase">SR</th>
                                @endif
                                @if($isBowler || $isAllRounder)
                                    <th class="text-center py-3 px-3 text-xs font-bold text-gray-500 uppercase">Wkts</th>
                                    <th class="text-center py-3 px-3 text-xs font-bold text-gray-500 uppercase">Econ</th>
                                @endif
                                <th class="text-center py-3 px-3 text-xs font-bold text-gray-500 uppercase">Catches</th>
                                <th class="text-center py-3 px-4 text-xs font-bold text-gray-500 uppercase">Matches</th>
                                <th class="text-right py-3 px-4 text-xs font-bold text-indigo-600 uppercase">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $index => $player)
                                @php
                                    $rank = $index + 1;
                                    $s = $player->stats;
                                    $rowClass = match($rank) {
                                        1 => 'rank-gold',
                                        2 => 'rank-silver',
                                        3 => 'rank-bronze',
                                        default => ($rank % 2 === 0 ? 'bg-gray-50' : 'bg-white'),
                                    };
                                @endphp
                                <tr class="{{ $rowClass }} border-b border-gray-100 hover:bg-indigo-50/30 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full font-black text-sm
                                            {{ $rank === 1 ? 'bg-yellow-400 text-yellow-900' : ($rank === 2 ? 'bg-gray-300 text-gray-700' : ($rank === 3 ? 'bg-orange-400 text-orange-900' : 'bg-gray-100 text-gray-500')) }}">
                                            @if($rank <= 3)
                                                {{ ['🥇', '🥈', '🥉'][$rank - 1] }}
                                            @else
                                                {{ $rank }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($player->full_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800 text-sm">{{ $player->full_name }}</div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $player->batting_style }}
                                                    · #{{ $player->jersey_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-sm text-gray-700">{{ $player->school->school_name ?? '—' }}</div>
                                        <div class="text-xs text-gray-400">{{ $player->school->district ?? '' }}, {{ $player->school->province ?? '' }}</div>
                                    </td>
                                    @if($isBatter || $isAllRounder)
                                        <td class="text-center py-3 px-3 font-semibold text-gray-800 text-sm">{{ $s->batting_runs ?? 0 }}</td>
                                        <td class="text-center py-3 px-3 text-gray-600 text-sm">{{ number_format($s->batting_average ?? 0, 1) }}</td>
                                        <td class="text-center py-3 px-3 text-gray-600 text-sm">{{ number_format($s->batting_strike_rate ?? 0, 1) }}</td>
                                    @endif
                                    @if($isBowler || $isAllRounder)
                                        <td class="text-center py-3 px-3 font-semibold text-gray-800 text-sm">{{ $s->bowling_wickets ?? 0 }}</td>
                                        <td class="text-center py-3 px-3 text-gray-600 text-sm">{{ number_format($s->bowling_economy ?? 0, 1) }}</td>
                                    @endif
                                    <td class="text-center py-3 px-3 text-gray-600 text-sm">{{ $s->fielding_catches ?? 0 }}</td>
                                    <td class="text-center py-3 px-4 text-gray-600 text-sm">{{ $s->batting_matches ?? 0 }}</td>
                                    <td class="text-right py-3 px-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-black
                                            {{ $rank <= 3 ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700' }}">
                                            {{ number_format($s->ranking_points ?? 0) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Points Breakdown Legend --}}
            <div class="mt-6 bg-white rounded-lg shadow-sm p-5">
                <h4 class="text-sm font-bold text-gray-700 mb-3">📋 Points System</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-500">
                    <div>
                        <span class="font-bold text-gray-700">🏏 Batting</span>
                        <ul class="mt-1 space-y-0.5">
                            <li>1 run = 1 pt</li>
                            <li>50 runs = +10 bonus</li>
                            <li>100 runs = +25 bonus</li>
                            <li>200 runs = +50 bonus</li>
                            <li>1 four = 2 pts</li>
                            <li>1 six = 3 pts</li>
                            <li>SR 100+ = +10 bonus</li>
                        </ul>
                    </div>
                    <div>
                        <span class="font-bold text-gray-700">🎯 Bowling</span>
                        <ul class="mt-1 space-y-0.5">
                            <li>1 wicket = 20 pts</li>
                            <li>3 wickets = +10 bonus</li>
                            <li>5 wickets = +25 bonus</li>
                            <li>Maiden over = +10 bonus</li>
                            <li>Economy &lt;5 = +10 bonus</li>
                            <li>Dot ball = 1 pt</li>
                        </ul>
                    </div>
                    <div>
                        <span class="font-bold text-gray-700">🧤 Fielding</span>
                        <ul class="mt-1 space-y-0.5">
                            <li>1 catch = 1 pt</li>
                        </ul>
                        <div class="mt-3">
                            <span class="font-bold text-gray-700">🔄 All-Rounders</span>
                            <ul class="mt-1 space-y-0.5">
                                <li>Batting + Bowling + Fielding combined</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-400 mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm">
            © {{ date('Y') }} Find11 — School Cricket Rankings
        </div>
    </footer>

    <script>
        function rankingApp() { return {}; }
    </script>
</body>
</html>
