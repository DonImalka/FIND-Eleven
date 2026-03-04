<x-app-layout>
    <x-slot name="title">{{ $tournament->name }}</x-slot>

    <div>
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        {{-- Tournament Info --}}
        <div class="bg-white shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $tournament->name }}</h2>
                        <p class="text-gray-500 mt-1">Year: {{ $tournament->year }}</p>
                        @if($tournament->description)
                            <p class="text-gray-600 mt-2">{{ $tournament->description }}</p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if($tournament->status === 'upcoming')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-[#FEF9EE] text-[#C8973A]">Upcoming</span>
                        @elseif($tournament->status === 'ongoing')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Ongoing</span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Completed</span>
                        @endif
                        <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="px-3 py-1 bg-yellow-500 text-white text-sm rounded-md hover:bg-yellow-600">Edit</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Matches --}}
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Matches</h3>
            <a href="{{ route('admin.matches.create', ['tournament_id' => $tournament->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                + New Match
            </a>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($tournament->matches->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Match</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Venue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Overs</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tournament->matches->sortByDesc('match_date') as $match)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $match->homeSchool->school_name }}</div>
                                        <div class="text-xs text-gray-500">vs {{ $match->awaySchool->school_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $match->match_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $match->venue }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $match->overs_per_side }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($match->status === 'upcoming')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#FEF9EE] text-[#C8973A]">Upcoming</span>
                                        @elseif($match->status === 'live')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">🔴 LIVE</span>
                                        @elseif($match->status === 'completed')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Completed</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <a href="{{ route('admin.matches.show', $match) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        @if($match->status === 'upcoming')
                                            <a href="{{ route('admin.matches.start-form', $match) }}" class="text-green-600 hover:text-green-900">Start</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No matches yet. Create a match for this tournament.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
