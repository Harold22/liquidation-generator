<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mr-5">
            {{ __('Add Cash Advances') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="w-full">
                        @include('cash-advances.forms.add-cash-advance-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
