<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                {{-- Pending Schools --}}
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-yellow-600 text-sm font-medium">Pending Schools</div>
                        <div class="text-3xl font-bold text-yellow-700">{{ $pendingSchoolsCount }}</div>
                    </div>
                </div>

                {{-- Approved Schools --}}
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-green-600 text-sm font-medium">Approved Schools</div>
                        <div class="text-3xl font-bold text-green-700">{{ $approvedSchoolsCount }}</div>
                    </div>
                </div>

                {{-- Rejected Schools --}}
                <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-red-600 text-sm font-medium">Rejected Schools</div>
                        <div class="text-3xl font-bold text-red-700">{{ $rejectedSchoolsCount }}</div>
                    </div>
                </div>

                {{-- Total Players --}}
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-blue-600 text-sm font-medium">Total Players</div>
                        <div class="text-3xl font-bold text-blue-700">{{ $totalPlayersCount }}</div>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('admin.schools.pending') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                        <p class="text-gray-600 text-sm mt-1">Review and approve school registrations</p>
                    </div>
                </a>

                <a href="{{ route('admin.schools.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">All Schools</h3>
                        <p class="text-gray-600 text-sm mt-1">View and manage all registered schools</p>
                    </div>
                </a>

                <a href="{{ route('admin.players.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">All Players</h3>
                        <p class="text-gray-600 text-sm mt-1">View all registered players</p>
                    </div>
                </a>
            </div>

            {{-- Pending Schools Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Pending Schools</h3>
                    
                    @if($pendingSchools->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">District</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingSchools as $school)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $school->school_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $school->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $school->district }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $school->school_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $school->created_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.schools.show', $school) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            <form action="{{ route('admin.schools.approve', $school) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.schools.reject', $school) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">No pending schools at the moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
