<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Player Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Welcome, {{ auth()->user()->name }}!</h3>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h4 class="text-blue-800 font-medium mb-2">Player Dashboard</h4>
                        <p class="text-blue-600">
                            This is your player dashboard. Players are registered and managed by their respective schools.
                        </p>
                        <p class="text-blue-600 mt-2">
                            Contact your school's cricket incharge for any updates to your profile.
                        </p>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-gray-900 font-medium mb-2">Your Account Details</h4>
                        <dl class="space-y-2">
                            <div class="flex">
                                <dt class="text-sm font-medium text-gray-500 w-32">Name:</dt>
                                <dd class="text-sm text-gray-900">{{ auth()->user()->name }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="text-sm font-medium text-gray-500 w-32">Email:</dt>
                                <dd class="text-sm text-gray-900">{{ auth()->user()->email }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="text-sm font-medium text-gray-500 w-32">Role:</dt>
                                <dd class="text-sm text-gray-900">{{ auth()->user()->role }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
