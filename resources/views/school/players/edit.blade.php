<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Player') }}
            </h2>
            <a href="{{ route('school.players.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to Players
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('school.players.update', $player) }}">
                        @csrf
                        @method('PUT')

                        {{-- Full Name --}}
                        <div class="mb-4">
                            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="full_name" id="full_name" 
                                   value="{{ old('full_name', $player->full_name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('full_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date of Birth --}}
                        <div class="mb-4">
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" 
                                   value="{{ old('date_of_birth', $player->date_of_birth->format('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Current age category: <strong>{{ $player->age_category }}</strong>. Will be recalculated if date changes.</p>
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Player Category --}}
                        <div class="mb-4">
                            <label for="player_category" class="block text-sm font-medium text-gray-700">Player Category (Primary Role)</label>
                            <select name="player_category" id="player_category" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Select Category</option>
                                @foreach($playerCategories as $category)
                                    <option value="{{ $category }}" {{ old('player_category', $player->player_category) == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('player_category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Batting Style --}}
                        <div class="mb-4">
                            <label for="batting_style" class="block text-sm font-medium text-gray-700">Batting Style</label>
                            <select name="batting_style" id="batting_style" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Select Batting Style</option>
                                @foreach($battingStyles as $style)
                                    <option value="{{ $style }}" {{ old('batting_style', $player->batting_style) == $style ? 'selected' : '' }}>
                                        {{ $style }}
                                    </option>
                                @endforeach
                            </select>
                            @error('batting_style')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bowling Style --}}
                        <div class="mb-4">
                            <label for="bowling_style" class="block text-sm font-medium text-gray-700">Bowling Style</label>
                            <select name="bowling_style" id="bowling_style" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Select Bowling Style</option>
                                @foreach($bowlingStyles as $style)
                                    <option value="{{ $style }}" {{ old('bowling_style', $player->bowling_style) == $style ? 'selected' : '' }}>
                                        {{ $style }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bowling_style')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jersey Number --}}
                        <div class="mb-4">
                            <label for="jersey_number" class="block text-sm font-medium text-gray-700">Jersey Number (Optional)</label>
                            <input type="text" name="jersey_number" id="jersey_number" 
                                   value="{{ old('jersey_number', $player->jersey_number) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('jersey_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-end">
                            <a href="{{ route('school.players.index') }}" class="mr-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Update Player
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
