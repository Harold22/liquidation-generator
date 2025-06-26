
<div class="mt-4">      
    <form method="POST" action="{{ route('office.update') }}">
        @csrf
        <input type="hidden" name="id" :value="selectedOffice.id">

        <!-- Office Name (read-only) -->
        <div class="mt-4">
            <div class="flex">
                <x-input-label for="office_name" :value="__('Office Name')" />
            </div>
            <x-text-input readonly x-model="selectedOffice.office_name" id="office_name" class="block mt-1 w-full" type="text" name="office_name" />
        </div>

        <!-- Office Location -->
        <div class="mt-4">
            <div class="flex">
                <x-input-label for="office_location" :value="__('Office Location (Municipality/City only)')" />
                <span class="text-red-500">*</span>
            </div>
            <x-text-input x-model="selectedOffice.office_location" id="office_location" class="block mt-1 w-full" type="text" name="office_location"  required />
        </div>

        <!-- SWADO -->
        <div class="mt-4">
            <div class="flex">
                <x-input-label for="swado" :value="__('Swado / Team Leader')" />
                <span class="text-red-500">*</span>
            </div>
            <x-text-input x-model="selectedOffice.swado" id="swado" class="block mt-1 w-full" type="text" name="swado" :value="old('swado')" required />
        </div>
         <div class="flex justify-end mt-6">
            <x-primary-button class="text-sm px-4 py-2">
                Update 
            </x-primary-button>
        </div>
    </form>
</div>
        