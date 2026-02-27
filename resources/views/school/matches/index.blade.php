<x-app-layout>
    <x-slot name="title">Matches</x-slot>

    <div>
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        <h2 class="text-2xl font-bold text-gray-800 mb-4">My Matches</h2>

        {{-- Status Filter --}}
        <div class="mb-6 flex space-x-3">
            <a href="{{ route('school.matches.index') }}" class="px-3 py-1 rounded-md text-sm {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">All</a>
            <a href="{{ route('school.matches.index', ['status' => 'upcoming']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') === 'upcoming' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">Upcoming</a>
            <a href="{{ route('school.matches.index', ['status' => 'live']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') === 'live' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">Live</a>
            <a href="{{ route('school.matches.index', ['status' => 'completed']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') === 'completed' ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">Completed</a>
        </div>

        @if($matches->count() > 0)
            <div class="space-y-4">
                @foreach($matches as $match)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-indigo-600 font-medium">{{ $match->tournament->name }}</p>
                                <h3 class="text-lg font-bold text-gray-800 mt-1">{{ $match->getTitle() }}</h3>
                                <p class="text-sm text-gray-500 mt-1">📅 {{ $match->match_date->format('M d, Y') }} | 📍 {{ $match->venue }} | 🏏 {{ $match->overs_per_side }} overs</p>

                                @if($match->isLive() || $match->isCompleted())
                                    <p class="text-sm font-medium text-gray-700 mt-2">{{ $match->getScoreSummary() }}</p>
                                @endif
                                @if($match->result_summary)
                                    <p class="text-sm text-green-700 font-medium mt-1">{{ $match->result_summary }}</p>
                                @endif
                            </div>
                            <div class="text-right flex flex-col items-end gap-2">
                                @if($match->status === 'upcoming')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Upcoming</span>
                                @elseif($match->status === 'live')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">🔴 LIVE</span>
                                @elseif($match->status === 'completed')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Completed</span>
                                @endif

                                <div class="flex gap-2 mt-2">
                                    <a href="{{ route('school.matches.show', $match) }}" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">View</a>
                                    @if($match->isUpcoming())
                                        <a href="{{ route('school.matches.squad', $match) }}" class="px-3 py-1 bg-yellow-500 text-white text-sm rounded-md hover:bg-yellow-600">Squad</a>
                                    @endif
                                    @if($match->isLive() && $match->home_school_id === auth()->user()->school->id)
                                        <a href="{{ route('school.matches.score', $match) }}" class="px-3 py-1 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">Score</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $matches->appends(request()->query())->links() }}</div>
        @else
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-500">No matches found.</p>
            </div>
        @endif
    </div>
</x-app-layout>
