<x-app-layout>
    <x-slot name="title">Tournaments</x-slot>

    <div>
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tournaments</h2>
            <a href="{{ route('admin.tournaments.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                + New Tournament
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($tournaments->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matches</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tournaments as $tournament)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $tournament->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tournament->year }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($tournament->status === 'upcoming')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Upcoming</span>
                                        @elseif($tournament->status === 'ongoing')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ongoing</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Completed</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tournament->matches_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <a href="{{ route('admin.tournaments.show', $tournament) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $tournaments->links() }}</div>
                @else
                    <p class="text-gray-500">No tournaments yet. Create one to get started.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
