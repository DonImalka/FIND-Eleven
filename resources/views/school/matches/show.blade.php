<x-app-layout>
    <x-slot name="title">Match Details</x-slot>

    <div>
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        {{-- Match Header --}}
        <div class="bg-white shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <p class="text-sm text-indigo-600 font-medium">{{ $cricketMatch->tournament->name }}</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $cricketMatch->getTitle() }}</h2>
                <div class="mt-2 text-sm text-gray-500 space-y-1">
                    <p>📅 {{ $cricketMatch->match_date->format('F d, Y') }} | 📍 {{ $cricketMatch->venue }} | 🏏 {{ $cricketMatch->overs_per_side }} overs</p>
                    @if($cricketMatch->tossWinner)
                        <p>🪙 {{ $cricketMatch->tossWinner->school_name }} won the toss and chose to {{ $cricketMatch->toss_decision }}</p>
                    @endif
                </div>
                @if($cricketMatch->result_summary)
                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                        <p class="text-sm font-semibold text-green-800">{{ $cricketMatch->result_summary }}</p>
                    </div>
                @endif

                <div class="mt-4 flex gap-2">
                    @if($cricketMatch->isUpcoming())
                        <a href="{{ route('school.matches.squad', $cricketMatch) }}" class="px-4 py-2 bg-yellow-500 text-white text-sm rounded-md hover:bg-yellow-600">Manage Squad</a>
                    @endif
                    @if($cricketMatch->isLive() && $cricketMatch->home_school_id === auth()->user()->school->id)
                        <a href="{{ route('school.matches.score', $cricketMatch) }}" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">📝 Live Scoring</a>
                    @endif
                    <a href="{{ route('school.matches.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">← Back</a>
                </div>
            </div>
        </div>

        {{-- My Squad --}}
        <div class="bg-white shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">My Squad ({{ $mySquad->count() }} players, {{ $myPlayingXI->count() }}/11 Playing XI)</h3>
                @if($mySquad->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($mySquad as $entry)
                            <div class="flex items-center gap-2 p-2 rounded {{ $entry->is_playing_xi ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                <span class="text-sm font-medium text-gray-800">{{ $entry->player->full_name }}</span>
                                <span class="text-xs text-gray-500">({{ $entry->player->player_category }})</span>
                                @if($entry->is_playing_xi)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">XI</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No squad selected yet. <a href="{{ route('school.matches.squad', $cricketMatch) }}" class="text-indigo-600 hover:underline">Select squad</a></p>
                @endif
            </div>
        </div>

        {{-- Innings Scorecards --}}
        @foreach($cricketMatch->innings->sortBy('inning_number') as $inning)
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $inning->battingSchool->school_name }} — {{ $inning->inning_number == 1 ? '1st' : '2nd' }} Innings
                        </h3>
                        <span class="text-2xl font-bold text-gray-800">{{ $inning->total_runs }}/{{ $inning->total_wickets }} <span class="text-sm text-gray-500 font-normal">({{ $inning->total_overs }} ov)</span></span>
                    </div>

                    {{-- Batting --}}
                    <table class="min-w-full divide-y divide-gray-200 mb-4">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Batter</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">R</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">B</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">4s</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">6s</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">SR</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($inning->batterScores->sortBy('batting_position') as $bs)
                                <tr class="{{ $bs->status === 'batting' ? 'bg-yellow-50' : '' }}">
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $bs->player->full_name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">
                                        @if($bs->status === 'out') {{ $bs->dismissal_info ?: 'out' }}
                                        @elseif($bs->status === 'not_out') not out
                                        @elseif($bs->status === 'batting') batting
                                        @else -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center text-sm font-semibold">{{ $bs->runs }}</td>
                                    <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bs->balls_faced }}</td>
                                    <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bs->fours }}</td>
                                    <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bs->sixes }}</td>
                                    <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bs->getStrikeRate() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Bowling --}}
                    @php $activeBowlers = $inning->bowlerScores->filter(fn($b) => $b->overs !== '0' || $b->wickets > 0); @endphp
                    @if($activeBowlers->count() > 0)
                        <h4 class="text-sm font-semibold text-gray-600 uppercase mb-2 mt-4">Bowling</h4>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Bowler</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">O</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">M</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">R</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">W</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Econ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($activeBowlers as $bw)
                                    <tr>
                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $bw->player->full_name }}</td>
                                        <td class="px-4 py-2 text-center text-sm">{{ $bw->overs }}</td>
                                        <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bw->maidens }}</td>
                                        <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bw->runs_conceded }}</td>
                                        <td class="px-4 py-2 text-center text-sm font-semibold">{{ $bw->wickets }}</td>
                                        <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bw->getEconomyRate() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
