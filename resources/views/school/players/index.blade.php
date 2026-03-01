<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Players') }}
            </h2>
            <a href="{{ route('school.players.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                + Add New Player
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'U15' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('school.players.index') }}" class="flex items-end gap-4">
                        <div class="flex-1">
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
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Filter</button>
                            <a href="{{ route('school.players.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Total --}}
            <p class="text-sm text-gray-500 mb-4">Total: <strong class="text-gray-800">{{ $totalPlayers }}</strong> players</p>

            {{-- Age Category Tabs --}}
            <div class="mb-4 border-b border-gray-200">
                <nav class="flex gap-1" aria-label="Tabs">
                    @foreach(['U15', 'U17', 'U19'] as $age)
                        <button
                            @click="activeTab = '{{ $age }}'"
                            :class="activeTab === '{{ $age }}'
                                ? 'border-indigo-500 text-indigo-600 bg-white'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="px-5 py-3 text-sm font-semibold border-b-2 rounded-t-lg transition">
                            {{ $age }}
                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full"
                                :class="activeTab === '{{ $age }}' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500'">
                                {{ $playersByAge[$age]->count() }}
                            </span>
                        </button>
                    @endforeach
                </nav>
            </div>

            {{-- Tab Content --}}
            @foreach(['U15', 'U17', 'U19'] as $age)
                <div x-show="activeTab === '{{ $age }}'" x-transition>
                    @if($playersByAge[$age]->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4">{{ $age }} Players</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Player</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Player Category</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Login Credentials</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batting</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bowling</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($playersByAge[$age] as $idx => $player)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-sm text-gray-400">{{ $loop->iteration }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $player->full_name }}</div>
                                                        <div class="text-xs text-gray-400">Jersey #{{ $player->jersey_number ?? 'N/A' }}</div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">{{ $player->player_category }}</span>
                                                    </td>
                                                    <td class="px-4 py-3" x-data="{ show: false }">
                                                        @if($player->username)
                                                            <button @click="show = !show" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                                                <span x-show="!show">🔑 Show</span>
                                                                <span x-show="show">🔒 Hide</span>
                                                            </button>
                                                            <div x-show="show" x-transition class="mt-1 bg-gray-50 border border-gray-200 rounded p-2 text-xs font-mono">
                                                                <p><span class="text-gray-500">User:</span> {{ $player->username }}</p>
                                                                <p><span class="text-gray-500">Pass:</span> {{ $player->plain_password }}</p>
                                                            </div>
                                                        @else
                                                            <span class="text-xs text-gray-400">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $player->batting_style }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $player->bowling_style }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium space-x-2">
                                                        <a href="{{ route('school.players.show', $player) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                        <a href="{{ route('school.players.edit', $player) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                                        <form action="{{ route('school.players.destroy', $player) }}" method="POST" class="inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this player?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-center">
                                <p class="text-gray-400">No {{ $age }} players found.</p>
                                <a href="{{ route('school.players.create') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-900 text-sm">+ Add Player</a>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
