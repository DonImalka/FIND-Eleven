<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ➕ Add Match Performance — {{ $player->full_name }}
            </h2>
            <a href="{{ route('school.players.show', $player) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to Player
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Player Info Card --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                    {{ strtoupper(substr($player->full_name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $player->full_name }}</h3>
                    <p class="text-sm text-gray-500">
                        {{ $player->player_category }} ·
                        <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">{{ $player->age_category }}</span>
                        · Jersey #{{ $player->jersey_number ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('school.players.stats.store', $player) }}">
                @csrf

                {{-- Match Information --}}
                <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                    <div class="p-5">
                        <h3 class="text-md font-bold text-gray-800 mb-4 flex items-center gap-2">
                            📅 Match Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="match_date" class="block text-xs font-medium text-gray-500 mb-1">Match Date <span class="text-red-500">*</span></label>
                                <input type="date" name="match_date" id="match_date"
                                    value="{{ old('match_date', date('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}"
                                    required
                                    class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('match_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="opponent" class="block text-xs font-medium text-gray-500 mb-1">Opponent</label>
                                <input type="text" name="opponent" id="opponent"
                                    value="{{ old('opponent') }}"
                                    placeholder="e.g. Trinity College"
                                    class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="match_description" class="block text-xs font-medium text-gray-500 mb-1">Match Description</label>
                                <input type="text" name="match_description" id="match_description"
                                    value="{{ old('match_description') }}"
                                    placeholder="e.g. Quarter Final, League Match"
                                    class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Performance Sections --}}
                @foreach($formSections as $sectionTitle => $fields)
                    <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                        <div class="p-5">
                            <h3 class="text-md font-bold text-gray-800 mb-4 flex items-center gap-2">
                                @if($sectionTitle === 'Batting')
                                    🏏
                                @elseif($sectionTitle === 'Bowling')
                                    🎯
                                @else
                                    🧤
                                @endif
                                {{ $sectionTitle }} Performance
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($fields as $fieldName => $meta)
                                    @if($meta['type'] === 'checkbox')
                                        <div class="flex items-center gap-2 pt-5">
                                            <input type="checkbox" name="{{ $fieldName }}" id="{{ $fieldName }}" value="1"
                                                {{ old($fieldName) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <label for="{{ $fieldName }}" class="text-sm font-medium text-gray-700">{{ $meta['label'] }}</label>
                                        </div>
                                    @else
                                        <div>
                                            <label for="{{ $fieldName }}" class="block text-xs font-medium text-gray-500 mb-1">{{ $meta['label'] }}</label>
                                            <input type="number" step="{{ $meta['step'] }}" min="0"
                                                name="{{ $fieldName }}" id="{{ $fieldName }}"
                                                value="{{ old($fieldName, 0) }}"
                                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error($fieldName)
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end gap-3 mb-8">
                    <a href="{{ route('school.players.show', $player) }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold text-sm">
                        ✅ Save Match Performance
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
