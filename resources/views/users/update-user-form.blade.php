<form method="POST">
    <div class="mt-4 space-y-4">
        @csrf
        <!-- First Name -->

        <div class="">        
                <x-text-input id="id" name="id" class="mt-1 block w-full" x-bind:value="selectedUser.id" />
            </div>
        <div>
            <x-input-label for="name" class="text-sm">
                {{ __('Name') }}
            </x-input-label>
            <x-text-input readonly id="name" name="name" type="text" x-bind:value="selectedUser.name" class="mt-1 block w-full text-sm" placeholder="Enter first name"/>
        </div>


        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4">
            <x-primary-button 
                class="text-sm px-4 py-2">
                {{ __('Update User') }}
            </x-primary-button>
        </div>
    </div>
</form>
