<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Player Details') }}
            </h2>
            <a href="{{ route('school.players.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to Players
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Player Information --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Player Information</h3>
                            <a href="{{ route('school.players.edit', $player) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                        </div>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->date_of_birth->format('F d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Age</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->getAge() }} years old</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Age Category</dt>
                                <dd class="mt-1">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#FEF9EE] text-[#C8973A]">{{ $player->age_category }}</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jersey Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->jersey_number ?? 'Not assigned' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Login Credentials --}}
                @if($player->username)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2" x-data="{ show: false }">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold text-gray-900">🔑 Login Credentials</h3>
                            <button @click="show = !show" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                <span x-show="!show">Show Credentials</span>
                                <span x-show="show">Hide Credentials</span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mb-3">Share these with the player so they can log in to their account.</p>
                        <div x-show="show" x-transition class="bg-gray-50 border border-gray-200 rounded-lg p-4 font-mono text-sm space-y-2">
                            <div>
                                <span class="text-gray-500 text-xs uppercase tracking-wide">Username</span>
                                <p class="text-gray-900 font-medium">{{ $player->username }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 text-xs uppercase tracking-wide">Password</span>
                                <p class="text-gray-900 font-medium">{{ $player->plain_password }}</p>
                            </div>
                        </div>
                        <div x-show="!show" class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center text-gray-400 text-sm">
                            Click "Show Credentials" to reveal
                        </div>
                    </div>
                </div>
                @endif

                {{-- Playing Style --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Playing Style</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Player Category</dt>

                                <dd class="mt-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">{{ $player->player_category }}</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Batting Style</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->batting_style }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Bowling Style</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->bowling_style }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Registered On</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->created_at->format('F d, Y') }}</dd>
                            </div>
                        </dl>

                        <div class="mt-6 flex space-x-3">
                            <a href="{{ route('school.players.edit', $player) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm">Edit Player</a>
                            <form action="{{ route('school.players.destroy', $player) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this player?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">Delete Player</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== STATS SECTION ===== --}}
            @php
                $stats = $player->stats;
                $hasInitial = $stats && $stats->hasInitialStats();
                $performanceCount = $player->matchPerformances()->count();
                $statSections = \App\Models\PlayerStat::getFieldsForCategory($player->player_category);
            @endphp

            <div class="mt-6">
                {{-- STEP 1: No initial stats yet — prompt to enter existing career stats --}}
                @if(!$hasInitial)
                    <div class="bg-white shadow-sm sm:rounded-lg p-8 text-center">
                        <div class="text-5xl mb-3">📋</div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Set Up Career Stats</h3>
                        <p class="text-gray-500 text-sm mb-4 max-w-md mx-auto">
                            Start by entering this player's <strong>existing career stats</strong> from before they were registered in the system.
                            After that, you can add individual match performances.
                        </p>
                        <a href="{{ route('school.players.stats.initial', $player) }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold text-sm">
                            📋 Enter Existing Stats
                        </a>
                    </div>

                {{-- STEP 2: Has initial stats — show career stats + ability to add match performances --}}
                @else
                    <div class="flex flex-wrap items-center justify-between mb-4 gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">📊 Career Statistics</h3>
                            <p class="text-xs text-gray-400 mt-1">
                                Base stats
                                @if($performanceCount > 0)
                                    + {{ $performanceCount }} match performance{{ $performanceCount !== 1 ? 's' : '' }}
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('school.players.stats.initial', $player) }}" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 text-xs font-medium" title="Edit initial stats">
                                ✏️ Edit Base Stats
                            </a>
                            @if($performanceCount > 0)
                                <a href="{{ route('school.players.stats.history', $player) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm font-medium">
                                    📜 History ({{ $performanceCount }})
                                </a>
                            @endif
                            <a href="{{ route('school.players.stats.create', $player) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-semibold">
                                ➕ Add Match Stats
                            </a>
                        </div>
                    </div>

                    {{-- Career Stat Cards --}}
                    @foreach($statSections as $sectionTitle => $fields)
                        <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                            <div class="p-5">
                                <h4 class="text-sm font-bold text-gray-700 uppercase mb-3 flex items-center gap-2">
                                    @if(str_contains($sectionTitle, 'Batting'))
                                        🏏
                                    @elseif(str_contains($sectionTitle, 'Bowling'))
                                        🎯
                                    @else
                                        🧤
                                    @endif
                                    {{ $sectionTitle }}
                                </h4>
                                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                    @foreach($fields as $fieldName => $label)
                                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                                            <div class="text-xl font-black text-gray-800">
                                                @if(str_contains($fieldName, 'average') || str_contains($fieldName, 'strike_rate') || str_contains($fieldName, 'economy'))
                                                    {{ number_format($stats->{$fieldName}, 2) }}
                                                @else
                                                    {{ $stats->{$fieldName} }}
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $label }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Recent Performances Preview --}}
                    @php $recentPerformances = $player->matchPerformances()->take(3)->get(); @endphp
                    @if($recentPerformances->isNotEmpty())
                        <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-bold text-gray-700 uppercase flex items-center gap-2">
                                        🕐 Recent Match Performances
                                    </h4>
                                    <a href="{{ route('school.players.stats.history', $player) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-medium">
                                        View All →
                                    </a>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-200">
                                                <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500">Date</th>
                                                <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500">Opponent</th>
                                                @php $perfSections = \App\Models\PlayerMatchPerformance::getFieldsForCategory($player->player_category); @endphp
                                                @if(isset($perfSections['Batting']))
                                                    <th class="text-center py-2 px-3 text-xs font-semibold text-gray-500">Runs</th>
                                                    <th class="text-center py-2 px-3 text-xs font-semibold text-gray-500">Balls</th>
                                                @endif
                                                @if(isset($perfSections['Bowling']))
                                                    <th class="text-center py-2 px-3 text-xs font-semibold text-gray-500">Overs</th>
                                                    <th class="text-center py-2 px-3 text-xs font-semibold text-gray-500">Wkts</th>
                                                    <th class="text-center py-2 px-3 text-xs font-semibold text-gray-500">Conc.</th>
                                                @endif
                                                @if(isset($perfSections['Fielding']))
                                                    <th class="text-center py-2 px-3 text-xs font-semibold text-gray-500">Catches</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPerformances as $perf)
                                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                    <td class="py-2 px-3 text-gray-700">{{ $perf->match_date->format('M d, Y') }}</td>
                                                    <td class="py-2 px-3 text-gray-700">{{ $perf->opponent ?? '—' }}</td>
                                                    @if(isset($perfSections['Batting']))
                                                        <td class="text-center py-2 px-3 font-semibold {{ $perf->batting_runs >= 50 ? 'text-green-600' : 'text-gray-800' }}">
                                                            {{ $perf->batting_runs }}{{ $perf->batting_not_out ? '*' : '' }}
                                                        </td>
                                                        <td class="text-center py-2 px-3 text-gray-600">{{ $perf->batting_balls_faced }}</td>
                                                    @endif
                                                    @if(isset($perfSections['Bowling']))
                                                        <td class="text-center py-2 px-3 text-gray-600">{{ $perf->bowling_overs }}</td>
                                                        <td class="text-center py-2 px-3 font-semibold {{ $perf->bowling_wickets >= 3 ? 'text-green-600' : 'text-gray-800' }}">
                                                            {{ $perf->bowling_wickets }}
                                                        </td>
                                                        <td class="text-center py-2 px-3 text-gray-600">{{ $perf->bowling_runs_conceded }}</td>
                                                    @endif
                                                    @if(isset($perfSections['Fielding']))
                                                        <td class="text-center py-2 px-3 text-gray-600">{{ $perf->fielding_catches }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
