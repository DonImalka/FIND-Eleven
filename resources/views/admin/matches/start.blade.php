<x-app-layout>
    <x-slot name="title">Start Match</x-slot>

    <div class="max-w-3xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Start Match</h2>
        <p class="text-gray-600 mb-6">{{ $cricketMatch->homeSchool->school_name }} vs {{ $cricketMatch->awaySchool->school_name }}</p>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.matches.start', $cricketMatch) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Toss Won By</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center mr-6">
                                <input type="radio" name="toss_winner_school_id" value="{{ $cricketMatch->home_school_id }}" class="text-indigo-600 focus:ring-indigo-500" {{ old('toss_winner_school_id') == $cricketMatch->home_school_id ? 'checked' : '' }} required>
                                <span class="ml-2 text-sm">{{ $cricketMatch->homeSchool->school_name }}</span>
                            </label>
                            <br>
                            <label class="inline-flex items-center">
                                <input type="radio" name="toss_winner_school_id" value="{{ $cricketMatch->away_school_id }}" class="text-indigo-600 focus:ring-indigo-500" {{ old('toss_winner_school_id') == $cricketMatch->away_school_id ? 'checked' : '' }}>
                                <span class="ml-2 text-sm">{{ $cricketMatch->awaySchool->school_name }}</span>
                            </label>
                        </div>
                        @error('toss_winner_school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chose to</label>
                        <div class="space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="toss_decision" value="bat" class="text-indigo-600 focus:ring-indigo-500" {{ old('toss_decision') === 'bat' ? 'checked' : '' }} required>
                                <span class="ml-2 text-sm">Bat</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="toss_decision" value="bowl" class="text-indigo-600 focus:ring-indigo-500" {{ old('toss_decision') === 'bowl' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm">Bowl</span>
                            </label>
                        </div>
                        @error('toss_decision') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">▶ Start Match</button>
                        <a href="{{ route('admin.matches.show', $cricketMatch) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
