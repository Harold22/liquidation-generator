<form method="POST" action="{{ route('beneficiary.update') }}">
    <div class="mt-4 space-y-4">
        @csrf
        <!-- First Name -->

        <div class="hidden">        
                <x-text-input id="id" name="id" class="mt-1 block w-full" x-bind:value="selectedBeneficiary.id" />
            </div>
        <div>
            <x-input-label for="firstname" class="text-sm">
                {{ __('First Name') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="firstname" name="firstname" type="text" x-bind:value="selectedBeneficiary.firstname" class="mt-1 block w-full text-sm" placeholder="Enter first name" required />
        </div>

        <!-- Middle Name -->
        <div>
            <x-input-label for="middlename" class="text-sm">
                {{ __('Middle Name') }}
            </x-input-label>
            <x-text-input id="middlename" name="middlename" type="text" x-bind:value="selectedBeneficiary.middlename" class="mt-1 block w-full text-sm" placeholder="Enter middle name (optional)" />
        </div>

        <!-- Last Name -->
        <div>
            <x-input-label for="lastname" class="text-sm">
                {{ __('Last Name') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="lastname" name="lastname" type="text"  x-bind:value="selectedBeneficiary.lastname" class="mt-1 block w-full text-sm" placeholder="Enter last name" required />
        </div>

        <!-- Extension Name -->
        <div>
            <x-input-label for="extension_name" class="text-sm">
                {{ __('Extension Name') }}
            </x-input-label>
            <x-text-input id="extension_name" name="extension_name" type="text" x-bind:value="selectedBeneficiary.extension_name" class="mt-1 block w-full text-sm" placeholder="e.g., Jr., Sr., III (optional)" />
        </div>


        <!-- Assistance -->
        <div>
            <x-input-label for="assistance_type" class="text-sm">
                {{ __('Assistance') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="assistance_type"  name="assistance_type" type="text" x-bind:value="selectedBeneficiary.assistance_type" class="mt-1 block w-full text-sm" placeholder="Enter assistance type" required />
        </div>

        <!-- Amount -->
        <div>
            <x-input-label for="amount" class="text-sm">
                {{ __('Amount') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="amount" name="amount" type="number" x-bind:value="selectedBeneficiary.amount" class="mt-1 block w-full text-sm" placeholder="Enter amount" required />
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4">
            <x-primary-button class="text-sm px-4 py-2">
                {{ __('Update Beneficiary') }}
            </x-primary-button>
        </div>
    </div>
</form>
