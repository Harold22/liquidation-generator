    <section class="w-full flex flex-col lg:flex-row gap-8" x-data="cashAdvanceForm()">
        <!-- Preview Section -->
        <div class="w-full lg:w-1/3 flex flex-col transition-all duration-300 hover:shadow-2xl self-start min-h-[300px] bg-white rounded-2xl shadow-lg border border-gray-300 p-6">
            <header class="mb-4 border-b pb-2">
                <h2 class="text-xl font-semibold text-blue-600">
                    {{ __('Cash Advance Preview') }}
                </h2>
            </header>

            <div class="space-y-4 text-sm">
                <div class="flex justify-between items-center border-b pb-2">
                    <strong class="text-gray-700 dark:text-gray-300">SDO:</strong>
                    <span class="font-medium text-green-600 dark:text-gray-100" x-text="form.officer || '-'"></span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <strong class="text-gray-700 dark:text-gray-300">Position:</strong>
                    <span class="font-medium text-green-600 dark:text-gray-100" x-text="form.position || '-'"></span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <strong class="text-gray-700 dark:text-gray-300">Station:</strong>
                    <span class="font-medium text-green-600 dark:text-gray-100" x-text="form.station || '-'"></span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <strong class="text-gray-700 dark:text-gray-300">Check Number:</strong>
                    <span class="font-medium text-green-600 dark:text-gray-100" x-text="form.check_number || '-'"></span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <strong class="text-gray-700 dark:text-gray-300">Cash Amount:</strong>
                    <span class="font-semibold text-green-600 dark:text-green-400" x-text="form.cash_amount ? Number(form.cash_amount).toLocaleString() : '-'"></span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <strong class="text-gray-700 dark:text-gray-300">Check Date:</strong>
                    <span class="font-medium text-green-600 dark:text-gray-100" x-text="form.cash_date || '-'"></span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <strong class="text-gray-700 dark:text-gray-300">DV Number:</strong>
                    <span class="font-medium text-green-600 dark:text-gray-100" x-text="form.dv_number || '-'"></span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <strong class="text-gray-700 dark:text-gray-300">ORS/BURS Number:</strong>
                    <span class="font-medium text-green-600 dark:text-gray-100" x-text="form.ors_number || '-'"></span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <strong class="text-gray-700 dark:text-gray-300">Responsibility Code:</strong>
                    <span class="font-medium text-green-600 dark:text-gray-100" x-text="form.resp_code || '-'"></span>
                </div>
                <div class="flex justify-between items-center">
                    <strong class="text-gray-700 dark:text-gray-300">UACS Code:</strong>
                    <span class="font-medium text-green-600 dark:text-gray-100" x-text="form.uacs_code || '-'"></span>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="w-full lg:w-2/3 p-2">
            <header class="mb-6 ml-4">
                <h2 class="text-lg font-medium text-blue-500">
                    {{ __('Add Cash Advance Details') }}
                </h2>
            </header>
            @include('error-messages.messages')
            <form method="POST" action="{{ route('cash_advance.store') }}" class="space-y-6 px-4">
                @csrf
                <!-- Grid layout for input fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Special Disbursing Officer -->
                    <div>
                        <x-input-label for="special_disbursing_officer">
                            {{ __('Special Disbursing Officer') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="special_disbursing_officer" name="special_disbursing_officer" type="text" class="mt-1 block w-full" required x-model="form.officer"/>
                    </div>

                    <!-- Position -->
                    <div>
                        <x-input-label for="position">
                            {{ __('Position') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" required x-model="form.position"/>
                    </div>

                    <!-- Station -->
                    <div>
                        <x-input-label for="station">
                            {{ __('Station') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="station" name="station" type="text" class="mt-1 block w-full" required x-model="form.station"/>
                    </div>

                    <!-- Check Number -->
                    <div>
                        <x-input-label for="check_number">
                            {{ __('Check Number') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="check_number" name="check_number" type="text" class="mt-1 block w-full" required x-model="form.check_number"/>
                    </div>

                    <!-- Cash Advance Amount -->
                    <div>
                        <x-input-label for="cash_advance_amount">
                            {{ __('Cash Advance Amount') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="cash_advance_amount" name="cash_advance_amount" type="number" class="mt-1 block w-full" required x-model="form.cash_amount"/>
                    </div>

                    <!-- Cash Advance Date -->
                    <div>
                        <x-input-label for="cash_advance_date">
                            {{ __('Check Date') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="cash_advance_date" name="cash_advance_date" type="date" class="mt-1 block w-full" required x-model="form.cash_date"/>
                    </div>

                    <!-- DV Number -->
                    <div>
                        <x-input-label for="dv_number">
                            {{ __('DV Number') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="dv_number" name="dv_number" type="text" class="mt-1 block w-full" required x-model="form.dv_number"/>
                    </div>

                    <!-- ORS/BURS Number -->
                    <div>
                        <x-input-label for="ors_burs_number">
                            {{ __('ORS/BURS Number') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="ors_burs_number" name="ors_burs_number" type="text" class="mt-1 block w-full" required x-model="form.ors_number"/>
                    </div>

                    <!-- Responsibility Code -->
                    <div>
                        <x-input-label for="responsibility_code">
                            {{ __('Responsibility Code') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="responsibility_code" name="responsibility_code" type="text" class="mt-1 block w-full" required x-model="form.resp_code"/>
                    </div>

                    <!-- UACS Object Code -->
                    <div>
                        <x-input-label for="uacs_code">
                            {{ __('UACS Object Code') }} <span class="text-red-500">*</span>
                        </x-input-label>
                        <x-text-input id="uacs_code" name="uacs_code" type="text" class="mt-1 block w-full" required x-model="form.uacs_code"/>
                    </div>
                </div>

                <!-- Hidden Status Field -->
                <input type="hidden" id="status" name="status" value="Unliquidated"/>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 mt-6">
                    <x-primary-button>
                        {{ __('Add Cash Advance') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cashAdvanceForm', () => ({
                form: {
                    officer: '',
                    position: '',
                    station: '',
                    check_number: '',
                    cash_amount: '',
                    cash_date: '',
                    dv_number: '',
                    ors_number: '',
                    resp_code: '',
                    uacs_code: ''
                }
        
            }));
        });

    </script>
