<x-app-layout>
    <x-slot name="title">Create Match</x-slot>

    <div class="max-w-3xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Match — {{ $tournament->name }}</h2>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.matches.store') }}">
                    @csrf
                    <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">

                    <div class="mb-4">
                        <label for="home_school_id" class="block text-sm font-medium text-gray-700">Home Team</label>
                        <select name="home_school_id" id="home_school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select Home Team</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('home_school_id') == $school->id ? 'selected' : '' }}>{{ $school->school_name }} ({{ $school->district }})</option>
                            @endforeach
                        </select>
                        @error('home_school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="away_school_id" class="block text-sm font-medium text-gray-700">Away Team</label>
                        <select name="away_school_id" id="away_school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select Away Team</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('away_school_id') == $school->id ? 'selected' : '' }}>{{ $school->school_name }} ({{ $school->district }})</option>
                            @endforeach
                        </select>
                        @error('away_school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="match_date" class="block text-sm font-medium text-gray-700">Match Date</label>
                            <input type="date" name="match_date" id="match_date" value="{{ old('match_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('match_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="overs_per_side" class="block text-sm font-medium text-gray-700">Overs Per Side</label>
                            <input type="number" name="overs_per_side" id="overs_per_side" value="{{ old('overs_per_side', 20) }}" min="1" max="50" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('overs_per_side') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
                        <input type="text" name="venue" id="venue" value="{{ old('venue') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('venue') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Create Match</button>
                        <a href="{{ route('admin.tournaments.show', $tournament) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
