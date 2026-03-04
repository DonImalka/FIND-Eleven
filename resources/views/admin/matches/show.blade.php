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
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-indigo-600 font-medium">{{ $cricketMatch->tournament->name }}</p>
                        <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $cricketMatch->getTitle() }}</h2>
                        <div class="mt-2 text-sm text-gray-500 space-y-1">
                            <p>📅 {{ $cricketMatch->match_date->format('F d, Y') }}</p>
                            <p>📍 {{ $cricketMatch->venue }}</p>
                            <p>🏏 {{ $cricketMatch->overs_per_side }} overs per side</p>
                            @if($cricketMatch->tossWinner)
                                <p>🪙 {{ $cricketMatch->tossWinner->school_name }} won the toss and chose to {{ $cricketMatch->toss_decision }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        @if($cricketMatch->status === 'upcoming')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-[#FEF9EE] text-[#C8973A]">Upcoming</span>
                        @elseif($cricketMatch->status === 'live')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">🔴 LIVE</span>
                        @elseif($cricketMatch->status === 'completed')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Completed</span>
                        @endif
                    </div>
                </div>

                @if($cricketMatch->result_summary)
                    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-md">
                        <p class="text-sm font-semibold text-green-800">Result: {{ $cricketMatch->result_summary }}</p>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="mt-4 flex gap-2">
                    @if($cricketMatch->isUpcoming())
                        <a href="{{ route('admin.matches.start-form', $cricketMatch) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">▶ Start Match</a>
                    @endif
                    @if($cricketMatch->isLive())
                        <form method="POST" action="{{ route('admin.matches.complete', $cricketMatch) }}" class="inline" onsubmit="return confirm('Mark this match as completed?');">
                            @csrf
                            <div class="flex gap-2">
                                <input type="text" name="result_summary" placeholder="e.g. Royal College won by 5 wickets" class="rounded-md border-gray-300 text-sm" required style="min-width: 300px;">
                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-900 text-sm">Complete Match</button>
                            </div>
                        </form>
                    @endif
                    <a href="{{ route('admin.tournaments.show', $cricketMatch->tournament) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">← Back to Tournament</a>
                </div>
            </div>
        </div>

        {{-- Squad Status --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            @foreach([['school' => $cricketMatch->homeSchool, 'label' => 'Home'], ['school' => $cricketMatch->awaySchool, 'label' => 'Away']] as $item)
                @php
                    $school = $item['school'];
                    $label = $item['label'];
                    $squad = $cricketMatch->squads->where('school_id', $school->id);
                    $xi = $squad->where('is_playing_xi', true);
                @endphp
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $school->school_name }} <span class="text-sm text-gray-400">({{ $label }})</span></h3>
                        <p class="text-sm text-gray-500 mt-1">Squad: {{ $squad->count() }} players | Playing XI: {{ $xi->count() }}/11</p>
                        @if($xi->count() === 11)
                            <span class="mt-2 inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✓ XI Ready</span>
                        @else
                            <span class="mt-2 inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">⏳ XI Pending</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Innings Scorecards --}}
        @foreach($cricketMatch->innings->sortBy('inning_number') as $inning)
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $inning->battingSchool->school_name }} — {{ $inning->inning_number == 1 ? '1st' : '2nd' }} Innings
                        </h3>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-gray-800">{{ $inning->total_runs }}/{{ $inning->total_wickets }}</span>
                            <span class="text-sm text-gray-500 ml-2">({{ $inning->total_overs }} ov)</span>
                            @if($inning->is_completed)
                                <span class="ml-2 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Completed</span>
                            @endif
                        </div>
                    </div>

                    @if($inning->extras > 0)
                        <p class="text-sm text-gray-500 mb-3">Extras: {{ $inning->extras }}</p>
                    @endif

                    {{-- Batting --}}
                    <h4 class="text-sm font-semibold text-gray-600 uppercase mb-2">Batting</h4>
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
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                        {{ $bs->player->full_name }}
                                        @if($bs->status === 'batting') <span class="text-yellow-600">*</span> @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">
                                        @if($bs->status === 'out') {{ $bs->dismissal_info ?: 'out' }}
                                        @elseif($bs->status === 'not_out') not out
                                        @elseif($bs->status === 'batting') batting
                                        @elseif($bs->status === 'retired') retired
                                        @else -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center text-sm font-semibold {{ $bs->status !== 'yet_to_bat' ? 'text-gray-900' : 'text-gray-400' }}">{{ $bs->runs }}</td>
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
                        <h4 class="text-sm font-semibold text-gray-600 uppercase mb-2">Bowling</h4>
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
                                        <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bw->overs }}</td>
                                        <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bw->maidens }}</td>
                                        <td class="px-4 py-2 text-center text-sm text-gray-500">{{ $bw->runs_conceded }}</td>
                                        <td class="px-4 py-2 text-center text-sm font-semibold text-gray-900">{{ $bw->wickets }}</td>
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
