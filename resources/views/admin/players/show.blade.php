<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Player Details') }}
            </h2>
            <a href="{{ route('admin.players.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Player Information</h3>

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
                        </dl>
                    </div>
                </div>

                {{-- School Information --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">School Information</h3>

                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">School Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->school->school_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">District</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->school->district }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Province</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $player->school->province }}</dd>
                            </div>
                        </dl>

                        <div class="mt-4">
                            <a href="{{ route('admin.schools.show', $player->school) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                View School Details →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
