<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Player Details') }}
            </h2>
            <a href="{{ route('school.players.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to Players
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Player Information --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Player Information</h3>
                            <a href="{{ route('school.players.edit', $player) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                Edit
                            </a>
                        </div>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->date_of_birth->format('F d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Age</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->getAge() }} years old</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Age Category</dt>
                                <dd class="mt-1">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $player->age_category }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jersey Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->jersey_number ?? 'Not assigned' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Playing Style --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Playing Style</h3>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Player Category</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->player_category }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Batting Style</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->batting_style }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Bowling Style</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->bowling_style }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Registered On</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->created_at->format('F d, Y') }}</dd>
                            </div>
                        </dl>

                        {{-- Action Buttons --}}
                        <div class="mt-6 flex space-x-4">
                            <a href="{{ route('school.players.edit', $player) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                                Edit Player
                            </a>
                            <form action="{{ route('school.players.destroy', $player) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this player?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    Delete Player
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
