<form method="POST" action="{{ route('users.update.status') }}">
    @csrf
    <div class="mt-4 space-y-4">
        <!-- Hidden ID -->
        <input type="hidden" name="id" :value="selectedUser.id">

        <!-- Name (read-only) -->
        <div>
            <x-input-label for="name" class="text-sm">Name</x-input-label>
            <x-text-input 
                readonly id="name" name="name" type="text"
                x-bind:value="selectedUser.name"
                class="mt-1 block w-full text-sm" />
        </div>

      <!-- Activation Dropdown -->
        <div>
            <x-input-label for="is_active" class="text-sm">Activation Status</x-input-label>
            <select 
                name="is_active"
                x-model="selectedUser.is_active"
                class="block w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="1">Activated</option>
                <option value="0">Not Activated</option>
            </select>
        </div>

        <!-- Role Dropdown -->
        <div>
            <x-input-label for="role" class="text-sm">Role</x-input-label>
            <select 
                name="role"
                x-model="selectedUser.role"
                class="block w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4">
            <x-primary-button class="text-sm px-4 py-2">
                Update User
            </x-primary-button>
        </div>
    </div>
</form>
