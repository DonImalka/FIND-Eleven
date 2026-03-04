<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Existing Career Stats — {{ $player->full_name }}
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

            {{-- Player Info --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                    {{ strtoupper(substr($player->full_name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $player->full_name }}</h3>
                    <p class="text-sm text-gray-500">
                        {{ $player->player_category }} ·
                        <span class="px-2 py-0.5 text-xs rounded-full bg-[#FEF9EE] text-[#C8973A]">{{ $player->age_category }}</span>
                        · Jersey #{{ $player->jersey_number ?? 'N/A' }}
                    </p>
                </div>
            </div>

            {{-- Info Banner --}}
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">💡</span>
                    <div>
                        <h4 class="font-semibold text-amber-800 text-sm">Existing Career Stats</h4>
                        <p class="text-amber-700 text-xs mt-1">
                            Enter the player's career stats from <strong>before</strong> they were registered in the system.
                            These will serve as the base stats. New match performances added later will be added on top of these.
                            Averages and strike rates will be calculated automatically.
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('school.players.stats.initial.update', $player) }}">
                @csrf
                @method('PUT')

                @php $initialStats = $player->stats?->initial_stats ?? []; @endphp

                @foreach($initialSections as $sectionTitle => $fields)
                    <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                        <div class="p-5">
                            <h3 class="text-md font-bold text-gray-800 mb-4 flex items-center gap-2">
                                @if(str_contains($sectionTitle, 'Batting'))
                                    🏏
                                @elseif(str_contains($sectionTitle, 'Bowling'))
                                    🎯
                                @else
                                    🧤
                                @endif
                                {{ $sectionTitle }}
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($fields as $fieldName => $label)
                                    <div>
                                        <label for="{{ $fieldName }}" class="block text-xs font-medium text-gray-500 mb-1">{{ $label }}</label>
                                        @if(str_contains($fieldName, 'overs'))
                                            <input type="number" step="0.1" min="0"
                                                name="{{ $fieldName }}" id="{{ $fieldName }}"
                                                value="{{ old($fieldName, $initialStats[$fieldName] ?? 0) }}"
                                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @else
                                            <input type="number" min="0"
                                                name="{{ $fieldName }}" id="{{ $fieldName }}"
                                                value="{{ old($fieldName, $initialStats[$fieldName] ?? 0) }}"
                                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @endif
                                        @error($fieldName)
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end gap-3 mb-8">
                    <a href="{{ route('school.players.show', $player) }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold text-sm">
                        💾 Save Existing Stats
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
