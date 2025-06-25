<form method="POST" action="{{ route('file.update') }}">
    <div class="mt-4 space-y-4">
        @csrf
        <!-- First Name -->

        <div class="hidden">        
            <x-text-input id="file_id" name="file_id" class="mt-1 block w-full" x-bind:value="selectedFile.id" />
        </div>
        <!-- File Name Display -->
        <div>
            <x-input-label for="file_name" class="text-sm">
                {{ __('File Name') }}
            </x-input-label>
            <x-text-input id="file_name" name="file_name" class="mt-1 block w-full text-sm bg-gray-100 dark:bg-gray-800" x-bind:value="selectedFile.file_name" disabled />
        </div>
         <!-- Date Imported Display -->
         <div>
            <x-input-label for="date_imported" class="text-sm">
                {{ __('Date Imported') }}
            </x-input-label>
            <x-text-input id="date_imported" name="date_imported" class="mt-1 block w-full text-sm bg-gray-100 dark:bg-gray-800"
                x-bind:value="new Date(selectedFile.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })"
                disabled />
        </div>
        <div>
            <x-input-label for="location" class="text-sm">
                {{ __('Location') }} <span class="text-red-500">*</span>
            </x-input-label>
            <select id="location" name="location" class="block w-full mt-1 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500" required>
                <option value="onsite" :selected="selectedFile.location === 'onsite'">Onsite</option>
                <option value="offsite" :selected="selectedFile.location === 'offsite'">Offsite</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4">
            <x-primary-button class="text-sm px-4 py-2">
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </div>
</form>
