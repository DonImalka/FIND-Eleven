<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- School Info Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $school->school_name }}</h3>
                            <p class="text-gray-600 mt-1">{{ $school->district }}, {{ $school->province }}</p>
                            <p class="text-sm text-gray-500 mt-2">{{ $school->school_type }} School</p>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-blue-600 text-sm font-medium">Total Players</div>
                        <div class="text-3xl font-bold text-blue-700">{{ $totalPlayers }}</div>
                    </div>
                </div>

                @foreach(['U13', 'U15', 'U17', 'U19'] as $category)
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-600 text-sm font-medium">{{ $category }} Players</div>
                            <div class="text-2xl font-bold text-gray-700">{{ $playersByCategory[$category] ?? 0 }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Quick Links --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('school.players.create') }}" class="bg-indigo-600 text-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-indigo-700 transition">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold">+ Add New Player</h3>
                        <p class="text-indigo-100 text-sm mt-1">Register a new player for your school</p>
                    </div>
                </a>

                <a href="{{ route('school.players.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Manage Players</h3>
                        <p class="text-gray-600 text-sm mt-1">View and manage all your players</p>
                    </div>
                </a>

                <a href="{{ route('school.profile.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">School Profile</h3>
                        <p class="text-gray-600 text-sm mt-1">View and update school information</p>
                    </div>
                </a>
            </div>

            {{-- Recent Players --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Players</h3>
                        <a href="{{ route('school.players.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                            View All →
                        </a>
                    </div>

                    @if($recentPlayers->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Player Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentPlayers as $player)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('school.players.show', $player) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                {{ $player->full_name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $player->age_category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $player->player_category }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $player->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">No players registered yet. <a href="{{ route('school.players.create') }}" class="text-indigo-600 hover:text-indigo-900">Add your first player</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
