<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('School Details') }}
            </h2>
            <a href="{{ route('admin.schools.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to Schools
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- School Information --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">School Information</h3>
                            @if($school->status === 'PENDING')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($school->status === 'APPROVED')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                            @endif
                        </div>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">School Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->school_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">School Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->school_type }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">District</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->district }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Province</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->province }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->school_address }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Contact Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->contact_number }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Account Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cricket Incharge Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->cricket_incharge_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cricket Incharge Contact</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->cricket_incharge_contact }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Registered On</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->created_at->format('F d, Y \a\t h:i A') }}</dd>
                            </div>
                        </dl>

                        {{-- Action Buttons --}}
                        @if($school->status === 'PENDING')
                            <div class="mt-6 flex space-x-4">
                                <form action="{{ route('admin.schools.approve', $school) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        Approve School
                                    </button>
                                </form>
                                <form action="{{ route('admin.schools.reject', $school) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                        Reject School
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Players List --}}
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Registered Players ({{ $school->players->count() }})</h3>

                    @if($school->players->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Player Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batting</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bowling</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($school->players as $player)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $player->full_name }}</div>
                                            <div class="text-sm text-gray-500">Jersey #{{ $player->jersey_number ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $player->age_category }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $player->player_category }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $player->batting_style }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $player->bowling_style }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">No players registered for this school yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
