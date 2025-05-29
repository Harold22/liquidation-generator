<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        @include('error-messages.messages')
        <!-- Name -->
        <div>
            <div class="flex">
                <x-input-label for="name" :value="__('Name')"/>
                <span class="text-red-500">*</span>
            </div>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <!-- <x-input-error :messages="$errors->get('name')" class="mt-2" /> -->
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <div class="flex">
                <x-input-label for="email" :value="__('Email')" />
                <span class="text-red-500">*</span>
            </div>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <!-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> -->
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ show: false }">
            <div class="flex">
                <x-input-label for="password" :value="__('Password')" />
                <span class="text-red-500">*</span>
            </div>

            <div class="relative">
                <input :type="show ? 'text' : 'password'"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="block mt-1 w-full pr-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />

                <!-- Eye toggle -->
                <button type="button"
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 focus:outline-none">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.975 9.975 0 012.378-3.568m3.236-2.06A9.99 9.99 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.976 9.976 0 01-4.122 5.152M15 12a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 3" />
                    </svg>
                </button>
            </div>
        </div>

       <div class="mt-4" x-data="{ showConfirm: false }">
            <div class="flex">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <span class="text-red-500">*</span>
            </div>

            <div class="relative">
                <input :type="showConfirm ? 'text' : 'password'"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="block mt-1 w-full pr-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />

                <!-- Eye toggle -->
                <button type="button"
                        @click="showConfirm = !showConfirm"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 focus:outline-none">
                    <!-- Show eye icon -->
                    <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <!-- Hide eye icon -->
                    <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.975 9.975 0 012.378-3.568m3.236-2.06A9.99 9.99 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.976 9.976 0 01-4.122 5.152M15 12a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 3" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-center">
            {!! NoCaptcha::display() !!}
        </div>
        <div class="flex items-center justify-end mt-4">
            @guest
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            @endguest

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
     {!! NoCaptcha::renderJs() !!}
</x-guest-layout>
