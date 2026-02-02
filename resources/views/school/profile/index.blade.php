<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Profile') }}
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- School Information --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">School Information</h3>
                            <a href="{{ route('school.profile.edit') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                Edit
                            </a>
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

                {{-- Cricket Incharge Information --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cricket Incharge</h3>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Incharge Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->cricket_incharge_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Incharge Contact</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $school->cricket_incharge_contact }}</dd>
                            </div>
                        </dl>

                        <hr class="my-6">

                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $school->status }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
