<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Players') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.players.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700">School</label>
                            <select name="school_id" id="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Schools</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="age_category" class="block text-sm font-medium text-gray-700">Age Category</label>
                            <select name="age_category" id="age_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Ages</option>
                                @foreach($ageCategories as $category)
                                    <option value="{{ $category }}" {{ request('age_category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="player_category" class="block text-sm font-medium text-gray-700">Player Category</label>
                            <select name="player_category" id="player_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Categories</option>
                                @foreach($playerCategories as $category)
                                    <option value="{{ $category }}" {{ request('player_category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Filter
                            </button>
                            <a href="{{ route('admin.players.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Total Players Count --}}
            <div class="mb-4 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Total: <span class="font-semibold text-gray-800">{{ $totalPlayers }}</span> players across
                    <span class="font-semibold text-gray-800">{{ $playersBySchool->count() }}</span> schools
                </p>
            </div>

            {{-- Players Grouped by School --}}
            @if($playersBySchool->count() > 0)
                @foreach($playersBySchool as $schoolId => $players)
                    @php $school = $players->first()->school; @endphp
                    <div x-data="{ open: true }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        {{-- School Header --}}
                        <button @click="open = !open" class="w-full flex items-center justify-between p-5 bg-[#FEF9EE] hover:bg-[#f5ead5] transition text-left">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($school->school_name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">{{ $school->school_name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $school->district }}, {{ $school->province }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-[#FEF9EE] text-[#C8973A] text-sm font-semibold rounded-full">
                                    {{ $players->count() }} {{ Str::plural('player', $players->count()) }}
                                </span>
                                <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        {{-- Players Table --}}
                        <div x-show="open" x-transition class="p-5">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Player</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age Category</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Player Category</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batting</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bowling</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($players->sortBy('full_name') as $index => $player)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-400">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $player->full_name }}</div>
                                                <div class="text-xs text-gray-400">Jersey #{{ $player->jersey_number ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#FEF9EE] text-[#C8973A]">
                                                    {{ $player->age_category }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $player->player_category }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $player->batting_style }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $player->bowling_style }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.players.show', $player) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-500">No players found.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
