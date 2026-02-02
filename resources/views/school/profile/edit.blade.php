<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit School Profile') }}
            </h2>
            <a href="{{ route('school.profile.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('school.profile.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- School Name --}}
                        <div class="mb-4">
                            <label for="school_name" class="block text-sm font-medium text-gray-700">School Name</label>
                            <input type="text" name="school_name" id="school_name" 
                                   value="{{ old('school_name', $school->school_name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('school_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- School Type --}}
                        <div class="mb-4">
                            <label for="school_type" class="block text-sm font-medium text-gray-700">School Type</label>
                            <select name="school_type" id="school_type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                @foreach($schoolTypes as $type)
                                    <option value="{{ $type }}" {{ old('school_type', $school->school_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- District --}}
                        <div class="mb-4">
                            <label for="district" class="block text-sm font-medium text-gray-700">District</label>
                            <input type="text" name="district" id="district" 
                                   value="{{ old('district', $school->district) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('district')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Province --}}
                        <div class="mb-4">
                            <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                            <input type="text" name="province" id="province" 
                                   value="{{ old('province', $school->province) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('province')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- School Address --}}
                        <div class="mb-4">
                            <label for="school_address" class="block text-sm font-medium text-gray-700">School Address</label>
                            <textarea name="school_address" id="school_address" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      required>{{ old('school_address', $school->school_address) }}</textarea>
                            @error('school_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contact Number --}}
                        <div class="mb-4">
                            <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" 
                                   value="{{ old('contact_number', $school->contact_number) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('contact_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cricket Incharge Name --}}
                        <div class="mb-4">
                            <label for="cricket_incharge_name" class="block text-sm font-medium text-gray-700">Cricket Incharge Name</label>
                            <input type="text" name="cricket_incharge_name" id="cricket_incharge_name" 
                                   value="{{ old('cricket_incharge_name', $school->cricket_incharge_name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('cricket_incharge_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cricket Incharge Contact --}}
                        <div class="mb-4">
                            <label for="cricket_incharge_contact" class="block text-sm font-medium text-gray-700">Cricket Incharge Contact</label>
                            <input type="text" name="cricket_incharge_contact" id="cricket_incharge_contact" 
                                   value="{{ old('cricket_incharge_contact', $school->cricket_incharge_contact) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('cricket_incharge_contact')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
