<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-900">School Registration</h2>
        <p class="text-sm text-gray-600 mt-1">Register your school for Find11 Cricket Platform</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('school.register') }}">
        @csrf

        <div class="bg-gray-50 -mx-4 px-4 py-3 mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Account Information</h3>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Admin/Contact Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Official School Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="bg-gray-50 -mx-4 px-4 py-3 mb-4 mt-6">
            <h3 class="text-sm font-semibold text-gray-700">School Information</h3>
        </div>

        <!-- School Name -->
        <div>
            <x-input-label for="school_name" :value="__('School Name')" />
            <x-text-input id="school_name" class="block mt-1 w-full" type="text" name="school_name" :value="old('school_name')" required />
            <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
        </div>

        <!-- School Type -->
        <div class="mt-4">
            <x-input-label for="school_type" :value="__('School Type')" />
            <select id="school_type" name="school_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">Select Type</option>
                @foreach($schoolTypes as $type)
                    <option value="{{ $type }}" {{ old('school_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('school_type')" class="mt-2" />
        </div>

        <!-- District -->
        <div class="mt-4">
            <x-input-label for="district" :value="__('District')" />
            <x-text-input id="district" class="block mt-1 w-full" type="text" name="district" :value="old('district')" required />
            <x-input-error :messages="$errors->get('district')" class="mt-2" />
        </div>

        <!-- Province -->
        <div class="mt-4">
            <x-input-label for="province" :value="__('Province')" />
            <x-text-input id="province" class="block mt-1 w-full" type="text" name="province" :value="old('province')" required />
            <x-input-error :messages="$errors->get('province')" class="mt-2" />
        </div>

        <!-- School Address -->
        <div class="mt-4">
            <x-input-label for="school_address" :value="__('School Address')" />
            <textarea id="school_address" name="school_address" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('school_address') }}</textarea>
            <x-input-error :messages="$errors->get('school_address')" class="mt-2" />
        </div>

        <!-- Contact Number -->
        <div class="mt-4">
            <x-input-label for="contact_number" :value="__('School Contact Number')" />
            <x-text-input id="contact_number" class="block mt-1 w-full" type="text" name="contact_number" :value="old('contact_number')" required />
            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
        </div>

        <div class="bg-gray-50 -mx-4 px-4 py-3 mb-4 mt-6">
            <h3 class="text-sm font-semibold text-gray-700">Cricket Incharge Details</h3>
        </div>

        <!-- Cricket Incharge Name -->
        <div>
            <x-input-label for="cricket_incharge_name" :value="__('Cricket Incharge Name')" />
            <x-text-input id="cricket_incharge_name" class="block mt-1 w-full" type="text" name="cricket_incharge_name" :value="old('cricket_incharge_name')" required />
            <x-input-error :messages="$errors->get('cricket_incharge_name')" class="mt-2" />
        </div>

        <!-- Cricket Incharge Contact -->
        <div class="mt-4">
            <x-input-label for="cricket_incharge_contact" :value="__('Cricket Incharge Contact')" />
            <x-text-input id="cricket_incharge_contact" class="block mt-1 w-full" type="text" name="cricket_incharge_contact" :value="old('cricket_incharge_contact')" required />
            <x-input-error :messages="$errors->get('cricket_incharge_contact')" class="mt-2" />
        </div>

        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>Note:</strong> Your registration will be reviewed by the admin. You will be able to login once your school is approved.
            </p>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Register School') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
