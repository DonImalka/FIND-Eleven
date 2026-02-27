<x-app-layout>
    <x-slot name="title">Select Squad</x-slot>

    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Select Squad & Playing XI</h2>
        <p class="text-gray-500 mb-6">{{ $cricketMatch->getTitle() }} — {{ $cricketMatch->match_date->format('M d, Y') }}</p>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('school.matches.squad.update', $cricketMatch) }}">
                    @csrf
                    @method('PUT')

                    <p class="text-sm text-gray-600 mb-4">
                        ✅ Check <strong>Squad</strong> to include a player in the squad. 
                        Check <strong>Playing XI</strong> to mark them in the starting 11 (exactly 11 required).
                    </p>

                    @if($allPlayers->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Squad</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">XI</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batting</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bowling</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($allPlayers as $player)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <input type="checkbox" name="squad[]" value="{{ $player->id }}" 
                                                   class="squad-cb rounded border-gray-300 text-indigo-600"
                                                   {{ in_array($player->id, $currentSquadIds) ? 'checked' : '' }}
                                                   onchange="if(!this.checked){document.getElementById('xi_{{ $player->id }}').checked=false;}">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="checkbox" name="playing_xi[]" value="{{ $player->id }}" 
                                                   id="xi_{{ $player->id }}"
                                                   class="xi-cb rounded border-gray-300 text-green-600"
                                                   {{ in_array($player->id, $currentXIIds) ? 'checked' : '' }}>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $player->full_name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $player->player_category }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $player->batting_style }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $player->bowling_style }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-2 text-sm text-gray-500">
                            Playing XI selected: <span id="xiCount" class="font-bold">{{ count($currentXIIds) }}</span>/11
                        </div>
                    @else
                        <p class="text-gray-500">No registered players. <a href="{{ route('school.players.create') }}" class="text-indigo-600 hover:underline">Add players</a> first.</p>
                    @endif

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save Squad</button>
                        <a href="{{ route('school.matches.show', $cricketMatch) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const xiCheckboxes = document.querySelectorAll('.xi-cb');
            const counter = document.getElementById('xiCount');
            function updateCount() {
                const count = document.querySelectorAll('.xi-cb:checked').length;
                counter.textContent = count;
                counter.style.color = count === 11 ? 'green' : count > 11 ? 'red' : 'inherit';
            }
            xiCheckboxes.forEach(cb => cb.addEventListener('change', updateCount));
        });
    </script>
</x-app-layout>
