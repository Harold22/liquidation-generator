<section x-data="{ loading: false }" class="w-full">
    <header class="mb-6 text-center">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add Cash Advance Details') }}
        </h2>
    </header>
    @include('error-messages.messages')
    <form method="POST" action="{{ route('cash_advance.store') }}" class="max-w-4xl mx-auto space-y-6 px-4">
        @csrf
        <!-- Use a grid layout for responsive alignment -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Special Disbursing Officer -->
            <div>
                <x-input-label for="special_disbursing_officer">
                    {{ __('Special Disbursing Officer') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="special_disbursing_officer" name="special_disbursing_officer" type="text" class="mt-1 block w-full capitalize" required />
            </div>

            <!-- Position -->
            <div>
                <x-input-label for="position">
                    {{ __('Position') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="position" name="position" type="text" class="mt-1 block w-full capitalize" required />
            </div>

            <!-- Station -->
            <div>
                <x-input-label for="station">
                    {{ __('Station') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="station" name="station" type="text" class="mt-1 block w-full capitalize" required />
            </div>

            <!-- Cash Advance Amount -->
            <div>
                <x-input-label for="cash_advance_amount">
                    {{ __('Cash Advance Amount') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="cash_advance_amount" name="cash_advance_amount" type="number" class="mt-1 block w-full capitalize" required />
            </div>

            <!-- Cash Advance Date -->
            <div>
                <x-input-label for="cash_advance_date">
                    {{ __('Cash Advance Date') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="cash_advance_date" name="cash_advance_date" type="date" class="mt-1 block w-full" required />
            </div>

            <!-- DV Number -->
            <div>
                <x-input-label for="dv_number">
                    {{ __('DV Number') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="dv_number" name="dv_number" type="text" class="mt-1 block w-full" required />
            </div>

            <!-- ORS/BURS Number -->
            <div>
                <x-input-label for="ors_burs_number">
                    {{ __('ORS/BURS Number') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="ors_burs_number" name="ors_burs_number" type="text" class="mt-1 block w-full" required />
            </div>

            <!-- Responsibility Code -->
            <div>
                <x-input-label for="responsibility_code">
                    {{ __('Responsibility Code') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="responsibility_code" name="responsibility_code" type="text" class="mt-1 block w-full" required />
            </div>

            <!-- UACS Object Code -->
            <div>
                <x-input-label for="uacs_code">
                    {{ __('UACS Object Code') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="uacs_code" name="uacs_code" type="text" class="mt-1 block w-full" required />
            </div>
        </div>

        <!-- Hidden Status Field -->
        <div class="hidden">
            <x-text-input id="status" name="status" type="text" class="mt-1 block w-full" value="Unliquidated" />
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-4 mt-6">
            <x-primary-button>
                {{ __('Add Cash Advance') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Loading Indicator -->
    <div x-show="loading" class="w-full mt-4">
        <div class="h-2 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 animate-pulse rounded-full shadow-lg overflow-hidden">
            <div class="h-full w-1/4 bg-blue-400 rounded-full"></div>
        </div>
    </div>
</section>
