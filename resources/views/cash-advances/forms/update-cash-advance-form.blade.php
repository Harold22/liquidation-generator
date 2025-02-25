<form method="POST" action="{{ route('cash_advance.update') }}">
    <div class="mt-4 space-y-4">
        @csrf
        <div class="hidden">        
            <x-text-input id="id" name="id" class="mt-1 block w-full" x-bind:value="selectedList.id" />
        </div>

        <!-- Special Disbursing Officer (Full Width) -->
        <div>
            <x-input-label for="special_disbursing_officer" class="text-sm">
                {{ __('Special Disbursing Officer') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input readonly id="special_disbursing_officer" name="special_disbursing_officer" type="text" class="mt-1 block w-full text-sm" x-bind:value="selectedList.special_disbursing_officer" />
        </div>

        <!-- Left and Right Column Layout -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Left Column -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="position" class="text-sm">
                        {{ __('Position') }} <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="position" name="position" type="text" class="mt-1 block w-full text-sm" x-bind:value="selectedList.position" />
                </div>

                <div>
                    <x-input-label for="cash_advance_amount" class="text-sm">
                        {{ __('Cash Advance Amount') }} <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="cash_advance_amount" name="cash_advance_amount" type="number" class="mt-1 block w-full text-sm" x-bind:value="selectedList.cash_advance_amount" />
                </div>

                <div>
                    <x-input-label for="dv_number" class="text-sm">
                        {{ __('DV Number') }} <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="dv_number" name="dv_number" type="text" class="mt-1 block w-full text-sm" x-bind:value="selectedList.dv_number" />
                </div>

                <div>
                    <x-input-label for="responsibility_code" class="text-sm">
                        {{ __('Responsibility Code') }} <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="responsibility_code" name="responsibility_code" type="text" class="mt-1 block w-full text-sm" x-bind:value="selectedList.responsibility_code" />
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="station" class="text-sm">
                        {{ __('Station') }} <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="station" name="station" type="text" class="mt-1 block w-full text-sm" x-bind:value="selectedList.station" />
                </div>

                <div>
                    <x-input-label for="cash_advance_date" class="text-sm">
                        {{ __('Cash Advance Date') }} <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="cash_advance_date" name="cash_advance_date" type="date" class="mt-1 block w-full text-sm" x-bind:value="selectedList.cash_advance_date"/>
                </div>

                <div>
                    <x-input-label for="ors_burs_number" class="text-sm">
                        {{ __('ORS/BURS Number') }} <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="ors_burs_number" name="ors_burs_number" type="text" class="mt-1 block w-full text-sm" x-bind:value="selectedList.ors_burs_number" />
                </div>

                <div>
                    <x-input-label for="uacs_code" class="text-sm">
                        {{ __('UACS Code') }} <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="uacs_code" name="uacs_code" type="text" class="mt-1 block w-full text-sm" x-bind:value="selectedList.uacs_code" />
                </div>
            </div>
        </div>

        <!-- Check Number and Status (Full Width) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
            <div>
                <x-input-label for="check_number" class="text-sm">
                    {{ __('Check Number') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="check_number" name="check_number" type="text" class="mt-1 block w-full text-sm" x-bind:value="selectedList.check_number" />
            </div>

            <div>
                <x-input-label for="status" class="text-sm">
                    {{ __('Status') }} <span class="text-red-500">*</span>
                </x-input-label>
                <select id="status" name="status" class="mt-1 block w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" x-model="selectedList.status">
                    <option value="Liquidated" x-bind:selected="selectedList.status === 'Liquidated'">Liquidated</option>
                    <option value="Unliquidated" x-bind:selected="selectedList.status === 'Unliquidated'">Unliquidated</option>
                </select>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4 mt-4">
            <x-primary-button class="text-sm px-4 py-2">{{ __('Update Cash Advance') }}</x-primary-button>
        </div>
    </div>
</form>
