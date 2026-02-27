<x-app-layout>
    <x-slot name="title">Live Scoring</x-slot>

    <div x-data="liveScoring()" class="pb-28">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        {{-- Match Header --}}
        <div class="bg-white shadow-sm sm:rounded-lg mb-4">
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-indigo-600 font-medium">{{ $cricketMatch->tournament->name }}</p>
                        <h2 class="text-lg font-bold text-gray-800">{{ $cricketMatch->getTitle() }}</h2>
                        <p class="text-sm text-gray-500">🔴 LIVE — {{ $cricketMatch->overs_per_side }} overs</p>
                    </div>
                    <a href="{{ route('school.matches.show', $cricketMatch) }}" class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">← Back</a>
                </div>
                @foreach($cricketMatch->innings->sortBy('inning_number') as $inn)
                    <div class="mt-1 text-sm {{ $inn->is_completed ? 'text-gray-400' : 'text-gray-900 font-semibold' }}">
                        {{ $inn->battingSchool->school_name }}: {{ $inn->total_runs }}/{{ $inn->total_wickets }} ({{ $inn->total_overs }} ov)
                        @if($inn->is_completed) <span class="text-xs">— completed</span> @endif
                    </div>
                @endforeach
            </div>
        </div>

        @if($currentInning)
            <form method="POST" action="{{ route('school.matches.score.update', $cricketMatch) }}" id="scoreForm">
                @csrf

                {{-- Live Score Board --}}
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white sm:rounded-lg mb-4 p-4">
                    <div class="text-center">
                        <p class="text-xs uppercase tracking-wider opacity-80">{{ $currentInning->battingSchool->school_name }} — {{ $currentInning->inning_number == 1 ? '1st' : '2nd' }} Innings</p>
                        <div class="text-4xl font-black mt-1">
                            <span x-text="totalRuns">{{ $currentInning->total_runs }}</span>/<span x-text="totalWickets">{{ $currentInning->total_wickets }}</span>
                        </div>
                        <p class="text-sm opacity-80 mt-1">
                            Overs: <span x-text="totalOvers">{{ $currentInning->total_overs }}</span>
                            | Extras: <span x-text="extras">{{ $currentInning->extras }}</span>
                        </p>
                    </div>

                    {{-- Hidden inputs for totals --}}
                    <input type="hidden" name="total_runs" :value="totalRuns">
                    <input type="hidden" name="total_wickets" :value="totalWickets">
                    <input type="hidden" name="total_overs" x-model="totalOvers">
                    <input type="hidden" name="extras" :value="extras">

                    {{-- Extras manual edit --}}
                    <div class="flex justify-center gap-4 mt-3">
                        <div class="flex items-center gap-2">
                            <label class="text-xs uppercase opacity-70">Extras</label>
                            <input type="number" x-model.number="extras" min="0" @input="recalcTotals()" class="w-16 text-center rounded bg-white/20 border-white/30 text-white text-sm placeholder-white/50 focus:ring-white/50">
                        </div>
                    </div>
                </div>

                {{-- ======= OVER TRACKER (NEW) ======= --}}
                <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4">
                        {{-- State 1: No over in progress --}}
                        <template x-if="!overInProgress">
                            <div class="text-center py-6">
                                <div class="mb-4">
                                    <h4 class="text-sm font-bold text-gray-700 uppercase mb-2">🎯 Select Bowler & Start Over</h4>
                                    <select x-model="currentBowlerId" class="w-full md:w-1/2 mx-auto rounded border-gray-300 text-sm font-semibold">
                                        <option value="">— Select Bowler —</option>
                                        @foreach($currentInning->bowlerScores as $bw)
                                            <option value="{{ $bw->id }}">{{ $bw->player->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button"
                                    @click="startOver()"
                                    :disabled="!currentBowlerId"
                                    class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg text-lg hover:bg-green-700 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                    ▶ Start Over
                                </button>
                            </div>
                        </template>

                        {{-- State 2 & 3: Over in progress / completed --}}
                        <template x-if="overInProgress">
                            <div>
                                {{-- Current bowler name --}}
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-bold text-gray-700 uppercase">
                                        🎯 Over <span x-text="currentOverNumber"></span>
                                        — <span x-text="getCurrentBowlerName()" class="text-indigo-600"></span>
                                    </h4>
                                    <template x-if="currentBowlerId && bowlers[currentBowlerId]">
                                        <div class="text-xs text-gray-500 flex gap-3">
                                            <span>O: <strong x-text="bowlers[currentBowlerId].overs"></strong></span>
                                            <span>R: <strong x-text="bowlers[currentBowlerId].runs_conceded"></strong></span>
                                            <span>W: <strong x-text="bowlers[currentBowlerId].wickets"></strong></span>
                                        </div>
                                    </template>
                                </div>

                                {{-- 6 Ball Indicators --}}
                                <div class="flex items-center justify-center gap-2 mb-4">
                                    <template x-for="(ball, idx) in 6" :key="idx">
                                        <div class="relative">
                                            {{-- Legal ball slot --}}
                                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all"
                                                :class="getBallClass(idx)">
                                                <span x-text="getBallDisplay(idx)"></span>
                                            </div>
                                            {{-- Current ball arrow --}}
                                            <div x-show="idx === legalBallsInOver && !overComplete" class="absolute -bottom-4 left-1/2 -translate-x-1/2 text-indigo-500 text-xs">▲</div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Extra balls (wides/no-balls) shown as small badges --}}
                                <template x-if="extraBallsInOver.length > 0">
                                    <div class="flex items-center justify-center gap-1 mb-3">
                                        <span class="text-xs text-gray-400 mr-1">Extras:</span>
                                        <template x-for="(eb, idx) in extraBallsInOver" :key="'eb'+idx">
                                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold"
                                                :class="eb.type === 'wide' ? 'bg-orange-100 text-orange-600 border border-orange-300' : 'bg-yellow-100 text-yellow-700 border border-yellow-300'">
                                                <span x-text="eb.display"></span>
                                            </span>
                                        </template>
                                    </div>
                                </template>

                                {{-- Over Complete State --}}
                                <template x-if="overComplete">
                                    <div class="text-center py-4 bg-green-50 rounded-lg border border-green-200 mb-3">
                                        <p class="text-green-700 font-bold text-lg mb-1">✅ Over Complete!</p>
                                        <p class="text-sm text-green-600 mb-3">
                                            This over: <span x-text="overRunsThisOver"></span> runs
                                        </p>
                                        <div class="mb-3">
                                            <label class="text-sm text-gray-600 block mb-1">Select next bowler:</label>
                                            <select x-model="nextBowlerId" class="w-full md:w-1/2 mx-auto rounded border-gray-300 text-sm font-semibold">
                                                <option value="">— Select Next Bowler —</option>
                                                @foreach($currentInning->bowlerScores as $bw)
                                                    <option value="{{ $bw->id }}">{{ $bw->player->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button"
                                            @click="startNextOver()"
                                            :disabled="!nextBowlerId"
                                            class="px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                            ▶ Start Next Over
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Active Batters Selection --}}
                <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4">
                        <h4 class="text-sm font-bold text-gray-700 uppercase mb-3">🏏 At the Crease</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Striker --}}
                            <div class="border-2 rounded-lg p-3 cursor-pointer"
                                :class="selectedBatter === 'striker' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300'"
                                @click="selectedBatter = 'striker'">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold uppercase" :class="selectedBatter === 'striker' ? 'text-red-600' : 'text-gray-500'">⭐ Striker</span>
                                    <span class="w-3 h-3 rounded-full" :class="selectedBatter === 'striker' ? 'bg-red-500' : 'bg-gray-300'"></span>
                                </div>
                                <select x-model="strikerId" @change="onBatterChange('striker')" class="w-full rounded border-gray-300 text-sm font-semibold">
                                    <option value="">— Select Striker —</option>
                                    <template x-for="opt in availableBatters()" :key="'s'+opt.id">
                                        <option :value="opt.id" x-text="opt.name"></option>
                                    </template>
                                </select>
                                <template x-if="strikerId && batters[strikerId]">
                                    <div class="mt-2 text-center">
                                        <span class="text-2xl font-black text-gray-800" x-text="batters[strikerId].runs"></span>
                                        <span class="text-sm text-gray-500">(<span x-text="batters[strikerId].balls_faced"></span>b)</span>
                                        <div class="text-xs text-gray-400 mt-1">
                                            4s: <span x-text="batters[strikerId].fours"></span> | 6s: <span x-text="batters[strikerId].sixes"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Non-Striker --}}
                            <div class="border-2 rounded-lg p-3 cursor-pointer"
                                :class="selectedBatter === 'non-striker' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'"
                                @click="selectedBatter = 'non-striker'">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold uppercase" :class="selectedBatter === 'non-striker' ? 'text-blue-600' : 'text-gray-500'">Non-Striker</span>
                                    <span class="w-3 h-3 rounded-full" :class="selectedBatter === 'non-striker' ? 'bg-blue-500' : 'bg-gray-300'"></span>
                                </div>
                                <select x-model="nonStrikerId" @change="onBatterChange('non-striker')" class="w-full rounded border-gray-300 text-sm font-semibold">
                                    <option value="">— Select Non-Striker —</option>
                                    <template x-for="opt in availableBatters()" :key="'ns'+opt.id">
                                        <option :value="opt.id" x-text="opt.name"></option>
                                    </template>
                                </select>
                                <template x-if="nonStrikerId && batters[nonStrikerId]">
                                    <div class="mt-2 text-center">
                                        <span class="text-2xl font-black text-gray-800" x-text="batters[nonStrikerId].runs"></span>
                                        <span class="text-sm text-gray-500">(<span x-text="batters[nonStrikerId].balls_faced"></span>b)</span>
                                        <div class="text-xs text-gray-400 mt-1">
                                            4s: <span x-text="batters[nonStrikerId].fours"></span> | 6s: <span x-text="batters[nonStrikerId].sixes"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Swap button --}}
                        <div class="text-center mt-3">
                            <button type="button" @click="swapStrike()" class="px-4 py-1 text-sm bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition">🔄 Swap Strike</button>
                        </div>
                    </div>
                </div>

                {{-- Quick Score Buttons --}}
                <div class="bg-white shadow-sm sm:rounded-lg mb-4"
                    :class="{ 'opacity-40 pointer-events-none': !overInProgress || overComplete }">
                    <div class="p-4">
                        <h4 class="text-sm font-bold text-gray-700 uppercase mb-1">⚡ Score This Ball</h4>
                        <p class="text-xs text-gray-400 mb-3" x-show="overInProgress && !overComplete">
                            Ball <span x-text="legalBallsInOver + 1"></span> of 6 — scoring to
                            <span x-text="selectedBatter === 'striker' ? 'Striker' : 'Non-Striker'" class="font-semibold text-indigo-600"></span>
                        </p>
                        <p class="text-xs text-red-400 mb-3" x-show="!overInProgress">Start an over first to score balls.</p>

                        {{-- Run buttons --}}
                        <div class="grid grid-cols-4 md:grid-cols-7 gap-2">
                            <button type="button" @click="scoreBall(0, 'run')" class="py-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-lg transition active:scale-95">0</button>
                            <button type="button" @click="scoreBall(1, 'run')" class="py-3 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold text-lg transition active:scale-95">1</button>
                            <button type="button" @click="scoreBall(2, 'run')" class="py-3 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold text-lg transition active:scale-95">2</button>
                            <button type="button" @click="scoreBall(3, 'run')" class="py-3 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold text-lg transition active:scale-95">3</button>
                            <button type="button" @click="scoreBall(4, 'run')" class="py-3 rounded-lg bg-green-500 hover:bg-green-600 text-white font-bold text-lg transition active:scale-95">4</button>
                            <button type="button" @click="scoreBall(6, 'run')" class="py-3 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white font-bold text-lg transition active:scale-95">6</button>
                            <button type="button" @click="scoreBall(0, 'wicket')" class="py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white font-bold text-lg transition active:scale-95">OUT</button>
                        </div>

                        {{-- Extras & Undo --}}
                        <div class="grid grid-cols-4 gap-2 mt-2">
                            <button type="button" @click="scoreExtra('wide')" class="py-2 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-700 text-sm font-semibold transition">Wide +1</button>
                            <button type="button" @click="scoreExtra('noball')" class="py-2 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-700 text-sm font-semibold transition">No Ball +1</button>
                            <button type="button" @click="scoreExtra('bye')" class="py-2 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-700 text-sm font-semibold transition">Bye +1</button>
                            <button type="button" @click="undoLastBall()" class="py-2 rounded-lg bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-semibold transition">↩ Undo</button>
                        </div>
                    </div>
                </div>

                {{-- Over History --}}
                <template x-if="overHistory.length > 0">
                    <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                        <div class="p-4">
                            <button type="button" @click="showOverHistory = !showOverHistory" class="flex items-center justify-between w-full text-left">
                                <h4 class="text-sm font-bold text-gray-700 uppercase">📋 Over History</h4>
                                <svg :class="showOverHistory ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="showOverHistory" x-transition class="mt-3 space-y-2">
                                <template x-for="(ov, oidx) in overHistory" :key="'oh'+oidx">
                                    <div class="flex items-center gap-3 py-2 border-b border-gray-100 text-sm">
                                        <span class="text-gray-500 font-mono w-14" x-text="'Over ' + (oidx + 1)"></span>
                                        <span class="text-gray-700 font-medium" x-text="ov.bowlerName"></span>
                                        <div class="flex gap-1">
                                            <template x-for="(b, bidx) in ov.balls" :key="'ohb'+bidx">
                                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold"
                                                    :class="getOverHistoryBallClass(b)">
                                                    <span x-text="b.display"></span>
                                                </span>
                                            </template>
                                        </div>
                                        <span class="text-gray-400 text-xs ml-auto" x-text="ov.runs + ' runs'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Full Batting Scorecard (collapsible) --}}
                <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4">
                        <button type="button" @click="showBatting = !showBatting" class="flex items-center justify-between w-full text-left">
                            <h4 class="text-sm font-bold text-gray-700 uppercase">📊 Full Batting Scorecard</h4>
                            <svg :class="showBatting ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="showBatting" x-transition class="mt-3 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Batter</th>
                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">R</th>
                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">B</th>
                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">4s</th>
                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">6s</th>
                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">Status</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500">Dismissal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($currentInning->batterScores->sortBy('batting_position') as $bs)
                                        <tr :class="{ 'bg-yellow-50': batters[{{ $bs->id }}] && batters[{{ $bs->id }}].status === 'batting' }">
                                            <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">{{ $bs->player->full_name }}</td>
                                            <td class="px-2 py-2"><input type="number" name="batters[{{ $bs->id }}][runs]" x-model.number="batters[{{ $bs->id }}].runs" min="0" class="w-14 text-center rounded border-gray-300 text-sm" @input="recalcTotals()"></td>
                                            <td class="px-2 py-2"><input type="number" name="batters[{{ $bs->id }}][balls_faced]" x-model.number="batters[{{ $bs->id }}].balls_faced" min="0" class="w-14 text-center rounded border-gray-300 text-sm"></td>
                                            <td class="px-2 py-2"><input type="number" name="batters[{{ $bs->id }}][fours]" x-model.number="batters[{{ $bs->id }}].fours" min="0" class="w-12 text-center rounded border-gray-300 text-sm"></td>
                                            <td class="px-2 py-2"><input type="number" name="batters[{{ $bs->id }}][sixes]" x-model.number="batters[{{ $bs->id }}].sixes" min="0" class="w-12 text-center rounded border-gray-300 text-sm"></td>
                                            <td class="px-2 py-2">
                                                <select name="batters[{{ $bs->id }}][status]" x-model="batters[{{ $bs->id }}].status" class="rounded border-gray-300 text-xs" @change="recalcTotals()">
                                                    <option value="yet_to_bat">Yet to bat</option>
                                                    <option value="batting">Batting *</option>
                                                    <option value="out">Out</option>
                                                    <option value="not_out">Not Out</option>
                                                    <option value="retired">Retired</option>
                                                </select>
                                            </td>
                                            <td class="px-2 py-2"><input type="text" name="batters[{{ $bs->id }}][dismissal_info]" x-model="batters[{{ $bs->id }}].dismissal_info" placeholder="c X b Y" class="w-32 rounded border-gray-300 text-xs"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Full Bowling Scorecard (collapsible) --}}
                <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4">
                        <button type="button" @click="showBowling = !showBowling" class="flex items-center justify-between w-full text-left">
                            <h4 class="text-sm font-bold text-gray-700 uppercase">🎯 Full Bowling Scorecard</h4>
                            <svg :class="showBowling ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="showBowling" x-transition class="mt-3 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Bowler</th>
                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">O</th>
                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">M</th>
                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">R</th>
                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">W</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($currentInning->bowlerScores as $bw)
                                        <tr :class="{ 'bg-indigo-50': currentBowlerId == {{ $bw->id }} }">
                                            <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">{{ $bw->player->full_name }}</td>
                                            <td class="px-2 py-2"><input type="text" name="bowlers[{{ $bw->id }}][overs]" x-model="bowlers[{{ $bw->id }}].overs" class="w-14 text-center rounded border-gray-300 text-sm"></td>
                                            <td class="px-2 py-2"><input type="number" name="bowlers[{{ $bw->id }}][maidens]" x-model.number="bowlers[{{ $bw->id }}].maidens" min="0" class="w-12 text-center rounded border-gray-300 text-sm"></td>
                                            <td class="px-2 py-2"><input type="number" name="bowlers[{{ $bw->id }}][runs_conceded]" x-model.number="bowlers[{{ $bw->id }}].runs_conceded" min="0" class="w-14 text-center rounded border-gray-300 text-sm"></td>
                                            <td class="px-2 py-2"><input type="number" name="bowlers[{{ $bw->id }}][wickets]" x-model.number="bowlers[{{ $bw->id }}].wickets" min="0" class="w-12 text-center rounded border-gray-300 text-sm"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Floating Save Bar --}}
                <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50 p-3">
                    <div class="max-w-4xl mx-auto flex items-center justify-between gap-3">
                        <div class="text-sm font-bold text-gray-700">
                            <span x-text="totalRuns">{{ $currentInning->total_runs }}</span>/<span x-text="totalWickets">{{ $currentInning->total_wickets }}</span>
                            (<span x-text="totalOvers">{{ $currentInning->total_overs }}</span> ov)
                        </div>
                        <div class="flex gap-2">
                            @if($currentInning->inning_number === 1)
                                <button type="button" @click="if(confirm('Switch to 2nd innings? 1st innings will be marked completed.')) document.getElementById('switchForm').submit();" class="px-4 py-2 bg-orange-500 text-white text-sm rounded-md hover:bg-orange-600 font-semibold">
                                    🔄 Switch
                                </button>
                            @endif
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 font-semibold">
                                💾 Save Scores
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            @if($currentInning->inning_number === 1)
                <form method="POST" action="{{ route('school.matches.switch-innings', $cricketMatch) }}" id="switchForm" class="hidden">
                    @csrf
                </form>
            @endif

        @else
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-500">No active innings. The match may have been completed.</p>
            </div>
        @endif
    </div>

    @if($currentInning)
    @push('scripts')
    <script>
        function liveScoring() {
            // Bowler name map for display
            const bowlerNames = {
                @foreach($currentInning->bowlerScores as $bw)
                {{ $bw->id }}: '{{ $bw->player->full_name }}',
                @endforeach
            };

            const battersData = {
                @foreach($currentInning->batterScores->sortBy('batting_position') as $bs)
                {{ $bs->id }}: {
                    runs: {{ $bs->runs }},
                    balls_faced: {{ $bs->balls_faced }},
                    fours: {{ $bs->fours }},
                    sixes: {{ $bs->sixes }},
                    status: '{{ $bs->status }}',
                    dismissal_info: `{{ str_replace('`', '', $bs->dismissal_info ?? '') }}`,
                },
                @endforeach
            };

            const bowlersData = {
                @foreach($currentInning->bowlerScores as $bw)
                {{ $bw->id }}: {
                    overs: '{{ $bw->overs }}',
                    maidens: {{ $bw->maidens }},
                    runs_conceded: {{ $bw->runs_conceded }},
                    wickets: {{ $bw->wickets }},
                },
                @endforeach
            };

            // Auto-detect current striker/non-striker
            let initStriker = '';
            let initNonStriker = '';
            const battingPlayers = Object.entries(battersData).filter(([_, b]) => b.status === 'batting');
            if (battingPlayers.length >= 1) initStriker = battingPlayers[0][0];
            if (battingPlayers.length >= 2) initNonStriker = battingPlayers[1][0];

            const batterList = [
                @foreach($currentInning->batterScores->sortBy('batting_position') as $bs)
                { id: '{{ $bs->id }}', name: '{{ $bs->player->full_name }}' },
                @endforeach
            ];

            return {
                batters: battersData,
                bowlers: bowlersData,
                bowlerNames: bowlerNames,
                batterList: batterList,
                strikerId: initStriker,
                nonStrikerId: initNonStriker,
                currentBowlerId: '',
                nextBowlerId: '',
                selectedBatter: 'striker',
                totalRuns: {{ $currentInning->total_runs }},
                totalWickets: {{ $currentInning->total_wickets }},
                totalOvers: '{{ $currentInning->total_overs }}',
                extras: {{ $currentInning->extras }},
                showBatting: false,
                showBowling: false,
                showOverHistory: true,

                // Over tracking
                overInProgress: false,
                overComplete: false,
                legalBallsInOver: 0,
                legalBalls: [],       // array of {runs, type, display} for 6 legal deliveries
                extraBallsInOver: [], // wides/no-balls this over
                overHistory: [],      // completed overs
                overRunsThisOver: 0,
                currentOverNumber: 1,
                undoStack: [],

                // Compute current over number from totalOvers
                init() {
                    let parts = String(this.totalOvers).split('.');
                    let completedOvers = parseInt(parts[0]) || 0;
                    this.currentOverNumber = completedOvers + 1;
                },

                getCurrentBowlerName() {
                    return this.bowlerNames[this.currentBowlerId] || 'Unknown';
                },

                getActiveBatterId() {
                    return this.selectedBatter === 'striker' ? this.strikerId : this.nonStrikerId;
                },

                // ======= Over Management =======

                startOver() {
                    if (!this.currentBowlerId) {
                        alert('Please select a bowler first.');
                        return;
                    }
                    this.overInProgress = true;
                    this.overComplete = false;
                    this.legalBallsInOver = 0;
                    this.legalBalls = [];
                    this.extraBallsInOver = [];
                    this.overRunsThisOver = 0;
                    this.undoStack = [];
                },

                startNextOver() {
                    if (!this.nextBowlerId) {
                        alert('Please select the next bowler.');
                        return;
                    }
                    // Swap strike at end of over
                    this.swapStrike();

                    this.currentBowlerId = this.nextBowlerId;
                    this.nextBowlerId = '';
                    this.currentOverNumber++;
                    this.startOver();
                },

                completeOver() {
                    this.overComplete = true;

                    // Save to over history
                    this.overHistory.push({
                        bowlerName: this.getCurrentBowlerName(),
                        bowlerId: this.currentBowlerId,
                        balls: [...this.legalBalls, ...this.extraBallsInOver],
                        runs: this.overRunsThisOver,
                    });

                    // Check if this over was a maiden (0 runs off the bat, no wides/no-balls scoring runs)
                    if (this.overRunsThisOver === 0) {
                        if (this.currentBowlerId && this.bowlers[this.currentBowlerId]) {
                            this.bowlers[this.currentBowlerId].maidens += 1;
                        }
                    }
                },

                // ======= Ball Scoring =======

                scoreBall(runs, type) {
                    if (!this.overInProgress || this.overComplete) return;

                    const batterId = this.getActiveBatterId();
                    if (!batterId || !this.batters[batterId]) {
                        alert('Please select a ' + (this.selectedBatter === 'striker' ? 'striker' : 'non-striker') + ' first.');
                        return;
                    }

                    const batter = this.batters[batterId];
                    let display = '';

                    if (type === 'wicket') {
                        // Wicket
                        display = 'W';
                        this.undoStack.push({
                            kind: 'wicket',
                            batterId: batterId,
                            bowlerId: this.currentBowlerId,
                            prevStatus: batter.status,
                            prevStrikerId: this.strikerId,
                            prevNonStrikerId: this.nonStrikerId,
                            legal: true,
                        });

                        batter.status = 'out';
                        batter.balls_faced += 1;

                        if (this.currentBowlerId && this.bowlers[this.currentBowlerId]) {
                            this.bowlers[this.currentBowlerId].wickets += 1;
                            this.bowlers[this.currentBowlerId].runs_conceded += 0;
                        }

                        // Clear dismissed batter
                        if (this.selectedBatter === 'striker') {
                            this.strikerId = '';
                        } else {
                            this.nonStrikerId = '';
                        }
                    } else {
                        // Normal runs
                        display = runs === 0 ? '•' : String(runs);
                        this.undoStack.push({
                            kind: 'run',
                            batterId: batterId,
                            bowlerId: this.currentBowlerId,
                            runs: runs,
                            prevStrikerId: this.strikerId,
                            prevNonStrikerId: this.nonStrikerId,
                            legal: true,
                        });

                        batter.runs += runs;
                        batter.balls_faced += 1;
                        if (batter.status !== 'batting') batter.status = 'batting';
                        if (runs === 4) batter.fours += 1;
                        if (runs === 6) batter.sixes += 1;

                        if (this.currentBowlerId && this.bowlers[this.currentBowlerId]) {
                            this.bowlers[this.currentBowlerId].runs_conceded += runs;
                        }

                        this.overRunsThisOver += runs;

                        // Swap strike on odd runs
                        if (runs % 2 === 1) this.swapStrike();
                    }

                    // Record as legal delivery
                    this.legalBalls.push({ runs: runs, type: type, display: display });
                    this.legalBallsInOver++;

                    // Increment bowler overs (per ball: +0.1)
                    if (this.currentBowlerId && this.bowlers[this.currentBowlerId]) {
                        this.bowlers[this.currentBowlerId].overs = this.incrementOvers(this.bowlers[this.currentBowlerId].overs);
                    }
                    // Increment total overs
                    this.totalOvers = this.incrementOvers(this.totalOvers);

                    this.recalcTotals();

                    // Check if over is complete
                    if (this.legalBallsInOver >= 6) {
                        this.completeOver();
                    }
                },

                scoreExtra(type) {
                    if (!this.overInProgress || this.overComplete) return;

                    let display = '';
                    let runsToExtras = 1;

                    if (type === 'wide') {
                        display = 'Wd';
                    } else if (type === 'noball') {
                        display = 'Nb';
                    } else if (type === 'bye') {
                        display = 'B';
                    }

                    this.undoStack.push({
                        kind: 'extra',
                        type: type,
                        runs: runsToExtras,
                        bowlerId: this.currentBowlerId,
                        legal: (type === 'bye'), // byes count as legal delivery
                    });

                    this.extras += runsToExtras;
                    this.overRunsThisOver += runsToExtras;

                    // Bowler charged for wides and no-balls
                    if (this.currentBowlerId && this.bowlers[this.currentBowlerId]) {
                        if (type === 'wide' || type === 'noball') {
                            this.bowlers[this.currentBowlerId].runs_conceded += runsToExtras;
                        }
                    }

                    if (type === 'bye') {
                        // Bye is a legal delivery
                        this.legalBalls.push({ runs: 0, type: 'bye', display: display });
                        this.legalBallsInOver++;

                        // Increment overs for legal delivery
                        if (this.currentBowlerId && this.bowlers[this.currentBowlerId]) {
                            this.bowlers[this.currentBowlerId].overs = this.incrementOvers(this.bowlers[this.currentBowlerId].overs);
                        }
                        this.totalOvers = this.incrementOvers(this.totalOvers);

                        if (this.legalBallsInOver >= 6) {
                            this.completeOver();
                        }
                    } else {
                        // Wide & No-ball: NOT legal, add to extra balls display
                        this.extraBallsInOver.push({ type: type, display: display });
                    }

                    this.recalcTotals();
                },

                // ======= Undo =======

                undoLastBall() {
                    if (this.undoStack.length === 0) {
                        alert('Nothing to undo.');
                        return;
                    }

                    // If over was just completed, un-complete it
                    if (this.overComplete) {
                        this.overComplete = false;
                        // Remove from history
                        if (this.overHistory.length > 0 && this.overHistory[this.overHistory.length - 1].bowlerId === this.currentBowlerId) {
                            const removed = this.overHistory.pop();
                            // Undo maiden if applied
                            if (removed.runs === 0 && this.currentBowlerId && this.bowlers[this.currentBowlerId]) {
                                this.bowlers[this.currentBowlerId].maidens = Math.max(0, this.bowlers[this.currentBowlerId].maidens - 1);
                            }
                        }
                    }

                    const action = this.undoStack.pop();

                    if (action.kind === 'run') {
                        const b = this.batters[action.batterId];
                        if (b) {
                            b.runs -= action.runs;
                            b.balls_faced -= 1;
                            if (action.runs === 4) b.fours -= 1;
                            if (action.runs === 6) b.sixes -= 1;
                        }
                        if (action.bowlerId && this.bowlers[action.bowlerId]) {
                            this.bowlers[action.bowlerId].runs_conceded -= action.runs;
                        }
                        this.overRunsThisOver -= action.runs;
                        this.strikerId = action.prevStrikerId;
                        this.nonStrikerId = action.prevNonStrikerId;

                        // Remove last legal ball
                        this.legalBalls.pop();
                        this.legalBallsInOver--;

                        // Decrement overs
                        if (action.bowlerId && this.bowlers[action.bowlerId]) {
                            this.bowlers[action.bowlerId].overs = this.decrementOvers(this.bowlers[action.bowlerId].overs);
                        }
                        this.totalOvers = this.decrementOvers(this.totalOvers);

                    } else if (action.kind === 'wicket') {
                        const b = this.batters[action.batterId];
                        if (b) {
                            b.status = action.prevStatus || 'batting';
                            b.balls_faced -= 1;
                        }
                        if (action.bowlerId && this.bowlers[action.bowlerId]) {
                            this.bowlers[action.bowlerId].wickets -= 1;
                        }
                        this.strikerId = action.prevStrikerId;
                        this.nonStrikerId = action.prevNonStrikerId;

                        this.legalBalls.pop();
                        this.legalBallsInOver--;

                        if (action.bowlerId && this.bowlers[action.bowlerId]) {
                            this.bowlers[action.bowlerId].overs = this.decrementOvers(this.bowlers[action.bowlerId].overs);
                        }
                        this.totalOvers = this.decrementOvers(this.totalOvers);

                    } else if (action.kind === 'extra') {
                        this.extras -= action.runs;
                        this.overRunsThisOver -= action.runs;

                        if (action.bowlerId && this.bowlers[action.bowlerId]) {
                            if (action.type === 'wide' || action.type === 'noball') {
                                this.bowlers[action.bowlerId].runs_conceded -= action.runs;
                            }
                        }

                        if (action.legal) {
                            // Bye: was legal
                            this.legalBalls.pop();
                            this.legalBallsInOver--;
                            if (action.bowlerId && this.bowlers[action.bowlerId]) {
                                this.bowlers[action.bowlerId].overs = this.decrementOvers(this.bowlers[action.bowlerId].overs);
                            }
                            this.totalOvers = this.decrementOvers(this.totalOvers);
                        } else {
                            // Wide/No-ball: was not legal
                            this.extraBallsInOver.pop();
                        }
                    }

                    this.recalcTotals();
                },

                // ======= Helpers =======

                availableBatters() {
                    return this.batterList.filter(opt => {
                        const b = this.batters[opt.id];
                        return b && b.status !== 'out' && b.status !== 'retired';
                    });
                },

                swapStrike() {
                    const tmp = this.strikerId;
                    this.strikerId = this.nonStrikerId;
                    this.nonStrikerId = tmp;
                },

                onBatterChange(role) {
                    if (this.strikerId && this.strikerId === this.nonStrikerId) {
                        if (role === 'striker') this.nonStrikerId = '';
                        else this.strikerId = '';
                    }
                    const id = role === 'striker' ? this.strikerId : this.nonStrikerId;
                    if (id && this.batters[id]) {
                        this.batters[id].status = 'batting';
                    }
                },

                recalcTotals() {
                    let runs = 0;
                    let wickets = 0;
                    Object.values(this.batters).forEach(b => {
                        runs += (parseInt(b.runs) || 0);
                        if (b.status === 'out') wickets++;
                    });
                    this.totalRuns = runs + (parseInt(this.extras) || 0);
                    this.totalWickets = wickets;
                },

                incrementOvers(current) {
                    let str = String(current);
                    let parts = str.split('.');
                    let overs = parseInt(parts[0]) || 0;
                    let balls = parseInt(parts[1]) || 0;
                    balls++;
                    if (balls >= 6) { overs++; balls = 0; }
                    return balls === 0 ? String(overs) : overs + '.' + balls;
                },

                decrementOvers(current) {
                    let str = String(current);
                    let parts = str.split('.');
                    let overs = parseInt(parts[0]) || 0;
                    let balls = parseInt(parts[1]) || 0;
                    balls--;
                    if (balls < 0) { overs = Math.max(0, overs - 1); balls = 5; }
                    if (overs === 0 && balls === 0) return '0';
                    return balls === 0 ? String(overs) : overs + '.' + balls;
                },

                // Ball display helpers
                getBallClass(idx) {
                    if (idx < this.legalBalls.length) {
                        const ball = this.legalBalls[idx];
                        if (ball.type === 'wicket') return 'bg-red-500 text-white border-red-600';
                        if (ball.runs === 4) return 'bg-green-500 text-white border-green-600';
                        if (ball.runs === 6) return 'bg-yellow-500 text-white border-yellow-600';
                        if (ball.runs === 0 || ball.type === 'bye') return 'bg-gray-200 text-gray-600 border-gray-300';
                        return 'bg-blue-500 text-white border-blue-600';
                    }
                    if (idx === this.legalBallsInOver) return 'bg-white border-indigo-400 text-indigo-400 border-dashed';
                    return 'bg-gray-50 border-gray-200 text-gray-300';
                },

                getBallDisplay(idx) {
                    if (idx < this.legalBalls.length) return this.legalBalls[idx].display;
                    return idx + 1;
                },

                getOverHistoryBallClass(ball) {
                    if (ball.type === 'wicket') return 'bg-red-100 text-red-600 border border-red-300';
                    if (ball.type === 'wide') return 'bg-orange-100 text-orange-600 border border-orange-300';
                    if (ball.type === 'noball') return 'bg-yellow-100 text-yellow-700 border border-yellow-300';
                    if (ball.runs === 4) return 'bg-green-100 text-green-700 border border-green-300';
                    if (ball.runs === 6) return 'bg-yellow-100 text-yellow-700 border border-yellow-300';
                    if (ball.runs === 0) return 'bg-gray-100 text-gray-500 border border-gray-200';
                    return 'bg-blue-100 text-blue-700 border border-blue-300';
                },
            };
        }
    </script>
    @endpush
    @endif
</x-app-layout>
