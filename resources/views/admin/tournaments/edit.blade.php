<x-app-layout>
    <x-slot name="title">Edit Tournament</x-slot>

    <div class="max-w-3xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Tournament</h2>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.tournaments.update', $tournament) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Tournament Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $tournament->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                        <input type="number" name="year" id="year" value="{{ old('year', $tournament->year) }}" min="2000" max="2100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $tournament->description) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="upcoming" {{ old('status', $tournament->status) === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ old('status', $tournament->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ old('status', $tournament->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update</button>
                        <a href="{{ route('admin.tournaments.show', $tournament) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
