<form method="POST" action="{{ route('program.update') }}">
    @csrf
    <div class="mt-4 space-y-4">
        <!-- Hidden ID -->
        <input type="hidden" name="id" :value="selectedProgram.id">

        <!-- Name -->
        <div>
            <x-input-label for="program_name" class="text-sm">Program Name 
                <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input 
                id="program_name" name="program_name" type="text"
                x-bind:value="selectedProgram.program_name"
                class="mt-1 block w-full text-sm" />
        </div>
        <!-- Abbreviation -->
        <div>
            <x-input-label for="program_abbreviation" class="text-sm">Program Abbreviation
                 <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input 
                id="program_abbreviation" name="program_abbreviation" type="text"
                x-bind:value="selectedProgram.program_abbreviation"
                class="mt-1 block w-full text-sm" />
        </div>
        <!-- Origin office -->
        <div>
            <x-input-label for="origin_office" class="text-sm">Office of Origin
                 <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input 
                id="origin_office" name="origin_office" type="text"
                x-bind:value="selectedProgram.origin_office"
                class="mt-1 block w-full text-sm" />
        </div>
  
      <!-- Activation Dropdown -->
        <div>
            <x-input-label for="status" class="text-sm">Status</x-input-label>
            <select 
                id="status"
                name="status"
                x-model="selectedProgram.status"
                class="block w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4">
            <x-primary-button class="text-sm px-4 py-2">
                Update Program
            </x-primary-button>
        </div>
    </div>
</form>
