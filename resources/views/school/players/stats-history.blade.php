<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📜 Stats History — {{ $player->full_name }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('school.players.stats.create', $player) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-semibold">
                    ➕ Add New Performance
                </a>
                <a href="{{ route('school.players.show', $player) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                    ← Back to Player
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
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
                        · {{ $performances->total() }} match{{ $performances->total() !== 1 ? 'es' : '' }} recorded
                    </p>
                </div>
            </div>

            @if($performances->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-8 text-center">
                    <div class="text-5xl mb-3">📭</div>
                    <p class="text-gray-400 mb-4">No match performances recorded yet.</p>
                    <a href="{{ route('school.players.stats.create', $player) }}" class="inline-block px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-semibold">
                        ➕ Add First Performance
                    </a>
                </div>
            @else
                {{-- Performance Cards --}}
                <div class="space-y-4">
                    @foreach($performances as $index => $perf)
                        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                            <div class="p-5">
                                {{-- Header Row --}}
                                <div class="flex flex-wrap items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-bold text-gray-600">
                                            #{{ $performances->total() - (($performances->currentPage() - 1) * $performances->perPage()) - $index }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800">
                                                {{ $perf->match_date->format('M d, Y') }}
                                                @if($perf->opponent)
                                                    <span class="text-gray-500 font-normal">vs</span> {{ $perf->opponent }}
                                                @endif
                                            </h4>
                                            @if($perf->match_description)
                                                <p class="text-xs text-gray-400">{{ $perf->match_description }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <form action="{{ route('school.players.stats.destroy', [$player, $perf]) }}" method="POST"
                                        onsubmit="return confirm('Delete this performance entry? Career stats will be recalculated.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs font-medium">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </div>

                                {{-- Stats Grid --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @php $sections = \App\Models\PlayerMatchPerformance::getFieldsForCategory($player->player_category); @endphp

                                    @foreach($sections as $sectionTitle => $fields)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h5 class="text-xs font-bold text-gray-500 uppercase mb-2 flex items-center gap-1">
                                                @if($sectionTitle === 'Batting')
                                                    🏏
                                                @elseif($sectionTitle === 'Bowling')
                                                    🎯
                                                @else
                                                    🧤
                                                @endif
                                                {{ $sectionTitle }}
                                            </h5>
                                            <div class="space-y-1">
                                                @foreach($fields as $fieldName => $meta)
                                                    <div class="flex justify-between text-sm">
                                                        <span class="text-gray-500">{{ $meta['label'] }}</span>
                                                        <span class="font-semibold text-gray-800">
                                                            @if($meta['type'] === 'checkbox')
                                                                @if($perf->{$fieldName})
                                                                    <span class="text-green-600">✓ Yes</span>
                                                                @else
                                                                    <span class="text-gray-400">No</span>
                                                                @endif
                                                            @else
                                                                {{ $perf->{$fieldName} }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $performances->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
