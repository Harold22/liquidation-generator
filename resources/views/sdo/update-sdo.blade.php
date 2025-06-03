<form method="POST" action="{{ route('sdo.update') }}">
    @csrf
    <input type="hidden" name="id" :value="sdoToUpdate.id">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

        <!-- LEFT COLUMN -->
        <div class="space-y-4">
            <!-- Firstname -->
            <div>
                <x-input-label for="firstname" class="text-sm">Firstname</x-input-label>
                <x-text-input 
                    id="firstname" name="firstname" type="text"
                    x-bind:value="sdoToUpdate.firstname"
                    class="mt-1 block w-full text-sm" />
            </div>

            <!-- Middlename -->
            <div>
                <x-input-label for="middlename" class="text-sm">Middlename</x-input-label>
                <x-text-input 
                    id="middlename" name="middlename" type="text"
                    x-model="sdoToUpdate.middlename"
                    class="mt-1 block w-full text-sm" />
            </div>

            <!-- Lastname -->
            <div>
                <x-input-label for="lastname" class="text-sm">Lastname</x-input-label>
                <x-text-input 
                    id="lastname" name="lastname" type="text"
                    x-bind:value="sdoToUpdate.lastname"
                    class="mt-1 block w-full text-sm" />
            </div>

           <!-- Extension Name -->
            <div>
                <x-input-label for="extension_name" class="text-sm">Extension Name</x-input-label>
                <select
                    id="extension_name"
                    name="extension_name"
                    x-model="sdoToUpdate.extension_name"
                    class="mt-1 block w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                >
                    <option value=""></option>
                    <option value="Jr.">Jr.</option>
                    <option value="Sr.">Sr.</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                </select>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-4">
            <!-- Position -->
            <div>
                <x-input-label for="position" class="text-sm">Position</x-input-label>
                <x-text-input 
                    id="position" name="position" type="text"
                    x-bind:value="sdoToUpdate.position"
                    class="mt-1 block w-full text-sm" />
            </div>

            <!-- Station -->
            <div>
                <x-input-label for="station" class="text-sm">Station</x-input-label>
                <x-text-input 
                    id="station" name="station" type="text"
                    x-bind:value="sdoToUpdate.station"
                    class="mt-1 block w-full text-sm" />
            </div>

            <!-- Designation -->
            <div>
                <x-input-label for="designation" class="text-sm">Designation</x-input-label>
                <x-text-input 
                    id="designation" name="designation" type="text"
                    x-bind:value="sdoToUpdate.designation"
                    class="mt-1 block w-full text-sm" />
            </div>

           <!-- Activation Status -->
            <div>
                <x-input-label for="status" class="text-sm">Activation Status</x-input-label>
                <select
                    id="status"
                    name="status"
                    x-model="sdoToUpdate.status"
                    class="mt-1 block w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">

                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end mt-6">
        <x-primary-button class="text-sm px-4 py-2">
            Update 
        </x-primary-button>
    </div>
</form>
