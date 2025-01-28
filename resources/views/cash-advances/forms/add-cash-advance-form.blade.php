<section x-data="{ loading: false }">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add Cash Advance Details') }}
        </h2>
    </header>
    @include('error-messages.messages')
    <form method="POST" action="{{ route('cash_advance.store') }}"  class="mt-6 space-y-6">
        @csrf
        <div>
            <x-input-label for="special_disbursing_officer">
                {{ __('Special Disbursing Officer') }} <span class="text-red-500">*</span>
            </x-input-label>            
            <x-text-input id="special_disbursing_officer" name="special_disbursing_officer" type="text" class="mt-1 block w-full" required />
            <!-- <x-input-error :messages="$errors->get('special_disbursing_officer')" class="mt-2" /> -->
        </div>
        <div>
            <x-input-label for="position">
                {{ __('Position') }} <span class="text-red-500">*</span>
            </x-input-label>            
            <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" required />
            <!-- <x-input-error :messages="$errors->get('special_disbursing_officer')" class="mt-2" /> -->
        </div>
    
        <div>
            <x-input-label for="cash_advance_amount">
                {{ __('Cash Advance Amount') }} <span class="text-red-500">*</span>
            </x-input-label>            
            <x-text-input id="cash_advance_amount" name="cash_advance_amount" type="number" class="mt-1 block w-full" required/>
            <!-- <x-input-error :messages="$errors->get('amount')" class="mt-2" /> -->
        </div>

        <div>
            <x-input-label for="cash_advance_date">
                {{ __('Cash Advance Date') }} <span class="text-red-500">*</span>
            </x-input-label>            
            <x-text-input id="cash_advance_date" name="cash_advance_date" type="date" class="mt-1 block w-full" required/>
            <!-- <x-input-error :messages="$errors->get('cash_advance_date')" class="mt-2" /> -->
        </div>

        <div>
            <x-input-label for="dv_number">
                {{ __('DV Number') }} <span class="text-red-500">*</span>
            </x-input-label>            
            <x-text-input id="dv_number" name="dv_number" type="text" class="mt-1 block w-full" required/>
            <!-- <x-input-error :messages="$errors->get('dv_number')" class="mt-2" /> -->
        </div>

        <div>
            <x-input-label for="ors_burs_number">
                {{ __('ORS/BURS Number') }} <span class="text-red-500">*</span>
            </x-input-label>            
            <x-text-input id="ors_burs_number" name="ors_burs_number" type="text" class="mt-1 block w-full" required/>
            <!-- <x-input-error :messages="$errors->get('ors_burs_number')" class="mt-2" /> -->
        </div>

        <div>
            <x-input-label for="responsibility_code">
                {{ __('Responsibility Code') }} <span class="text-red-500">*</span>
            </x-input-label>            
            <x-text-input id="responsibility_code" name="responsibility_code" type="text" class="mt-1 block w-full" required/>
            <!-- <x-input-error :messages="$errors->get('responsibility_code')" class="mt-2" /> -->
        </div>

        <div>
            <x-input-label for="uacs_code">
                {{ __('UACS Object Code') }} <span class="text-red-500">*</span>
            </x-input-label>            
            <x-text-input id="uacs_code" name="uacs_code" type="text" class="mt-1 block w-full" required/>
            <!-- <x-input-error :messages="$errors->get('uacs_code')" class="mt-2" /> -->
        </div>
        <div class="hidden">            
            <x-text-input id="status" name="status" type="text" class="mt-1 block w-full" value="Unliquidated"/>
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>
                {{ __('Add Cash Advance') }}
            </x-primary-button>
        </div>
    </form>
    <div x-show="loading" class="w-full mt-4">
        <div class="h-2 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 animate-pulse rounded-full shadow-lg overflow-hidden">
            <div class="h-full w-1/4 bg-blue-400 rounded-full"></div>
        </div>
    </div>

</section>

