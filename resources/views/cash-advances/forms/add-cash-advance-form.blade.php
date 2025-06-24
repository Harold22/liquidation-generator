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
                <span 
                    class="font-medium text-green-600 dark:text-gray-100 break-words max-w-[60%]" 
                    x-text="sdoList.find(s => s.id == form.sdos_id)?.name || '-'">
                </span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <strong class="text-gray-700 dark:text-gray-300">Check Number:</strong>
                <span class="font-medium text-green-600 dark:text-gray-100 break-words max-w-[60%]" x-text="form.check_number || '-'"></span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <strong class="text-gray-700 dark:text-gray-300">Cash Amount:</strong>
                <span class="font-semibold text-green-600 dark:text-green-400 break-words max-w-[60%]" x-text="form.cash_amount ? Number(form.cash_amount).toLocaleString() : '-'"></span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <strong class="text-gray-700 dark:text-gray-300">Check Date:</strong>
                <span class="font-medium text-green-600 dark:text-gray-100 break-words max-w-[60%]" x-text="form.cash_date || '-'"></span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <strong class="text-gray-700 dark:text-gray-300">DV Number:</strong>
                <span class="font-medium text-green-600 dark:text-gray-100 break-words max-w-[60%]" x-text="form.dv_number || '-'"></span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <strong class="text-gray-700 dark:text-gray-300">ORS/BURS Number:</strong>
                <span class="font-medium text-green-600 dark:text-gray-100 break-words max-w-[60%]" x-text="form.ors_number || '-'"></span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <strong class="text-gray-700 dark:text-gray-300">Responsibility Code:</strong>
                <span class="font-medium text-green-600 dark:text-gray-100 break-words max-w-[60%]" x-text="form.resp_code || '-'"></span>
            </div>
            <div class="flex justify-between items-center">
                <strong class="text-gray-700 dark:text-gray-300">UACS Code:</strong>
                <span class="font-medium text-green-600 dark:text-gray-100 break-words max-w-[60%]" x-text="form.uacs_code || '-'"></span>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="w-full lg:w-2/3 p-2">
        <header class="mb-6 flex items-center justify-between px-4">
            <h2 class="text-lg font-medium text-blue-500">
                {{ __('Add Cash Advance Details') }}
            </h2>
            <a href="{{ route('cash-advance.list') }}"
            class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg shadow 
                        hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                View List
            </a>
        </header>
        @include('error-messages.messages')
        <form method="POST" action="{{ route('cash_advance.store') }}" class="space-y-6 px-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Special Disbursing Officer -->
           <div>
                <x-input-label for="sdos_id">
                    {{ __('Special Disbursing Officer') }} <span class="text-red-500">*</span>
                </x-input-label>
                
                <select id="sdos_id" name="sdos_id" required
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        x-model="form.sdos_id">
                    <option value="" disabled selected>Select an officer</option>
                    <template x-for="sdo in sdoList" :key="sdo.id">
                        <option :value="sdo.id" x-text="sdo.name"></option>
                    </template>
                </select>
            </div>


            <!-- Check Number -->
            <div x-init="$watch('form.check_number', () => validateField('check_number'))">
                <x-input-label for="check_number">
                    {{ __('Check Number') }} <span class="text-red-500">*</span>
                </x-input-label>
                <p class="text-red-500 text-xs mt-1" x-show="errors.check_number">
                    <span class="underline cursor-help" x-text="errors.check_number" :title="errors.check_number"></span>
                </p>
                <x-text-input id="check_number" name="check_number" type="text" class="mt-1 block w-full" required
                              x-model="form.check_number"/>
            </div>

            <!-- Cash Advance Amount -->
            <div x-init="$watch('form.cash_amount', () => validateField('cash_amount'))">
                <x-input-label for="cash_advance_amount">
                    {{ __('Cash Advance Amount') }} <span class="text-red-500">*</span>
                </x-input-label>
                <p class="text-red-500 text-xs mt-1" x-show="errors.cash_amount">
                    <span class="underline cursor-help" x-text="errors.cash_amount" :title="errors.cash_amount"></span>
                </p>
                <x-text-input id="cash_advance_amount" name="cash_advance_amount" type="number"
                              class="mt-1 block w-full" required x-model="form.cash_amount"/>
            </div>

            <!-- Cash Advance Date -->
            <div x-init="$watch('form.cash_date', () => validateField('cash_date'))">
                <x-input-label for="cash_advance_date">
                    {{ __('Cash Advance Date') }} <span class="text-red-500">*</span>
                </x-input-label>
                <p class="text-red-500 text-xs mt-1" x-show="errors.cash_date">
                    <span class="underline cursor-help" x-text="errors.cash_date" :title="errors.cash_date"></span>
                </p>
                <x-text-input id="cash_advance_date" name="cash_advance_date" type="date" class="mt-1 block w-full"
                              required x-model="form.cash_date"/>
            </div>

            <!-- DV Number -->
            <div x-init="$watch('form.dv_number', () => validateField('dv_number'))">
                <x-input-label for="dv_number">
                    {{ __('DV Number') }} <span class="text-red-500">*</span>
                </x-input-label>
                <p class="text-red-500 text-xs mt-1" x-show="errors.dv_number">
                    <span class="underline cursor-help" x-text="errors.dv_number" :title="errors.dv_number"></span>
                </p>
                <x-text-input id="dv_number" name="dv_number" type="text" class="mt-1 block w-full" required
                              x-model="form.dv_number"/>
            </div>

            <!-- ORS/BURS Number -->
            <div x-init="$watch('form.ors_number', () => validateField('ors_number'))">
                <x-input-label for="ors_burs_number">
                    {{ __('ORS/BURS Number') }} <span class="text-red-500">*</span>
                </x-input-label>
                <p class="text-red-500 text-xs mt-1" x-show="errors.ors_number">
                    <span class="underline cursor-help" x-text="errors.ors_number" :title="errors.ors_number"></span>
                </p>
                <x-text-input id="ors_burs_number" name="ors_burs_number" type="text" class="mt-1 block w-full"
                              required x-model="form.ors_number"/>
            </div>

            <!-- Responsibility Code -->
            <div x-init="$watch('form.resp_code', () => validateField('resp_code'))">
                <x-input-label for="responsibility_code">
                    {{ __('Responsibility Code') }} <span class="text-red-500">*</span>
                </x-input-label>
                <p class="text-red-500 text-sm xs-1" x-show="errors.resp_code">
                    <span class="underline cursor-help" x-text="errors.resp_code" :title="errors.resp_code"></span>
                </p>
                <x-text-input id="responsibility_code" name="responsibility_code" type="text"
                              class="mt-1 block w-full" required x-model="form.resp_code"/>
            </div>

            <!-- UACS Object Code -->
            <div x-init="$watch('form.uacs_code', () => validateField('uacs_code'))">
                <x-input-label for="uacs_code">
                    {{ __('UACS Object Code') }} <span class="text-red-500">*</span>
                </x-input-label>
                <p class="text-red-500 text-xs   mt-1" x-show="errors.uacs_code">
                    <span class="underline cursor-help" x-text="errors.uacs_code" :title="errors.uacs_code"></span>
                </p>
                <x-text-input id="uacs_code" name="uacs_code" type="text" class="mt-1 block w-full" required
                              x-model="form.uacs_code"/>
            </div>
        </div>

        <!-- Hidden Status Field -->
        <input type="hidden" id="status" name="status" value="Unliquidated"/>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-4 mt-6">
            <x-primary-button 
                x-bind:disabled="Object.keys(errors).length > 0"
                x-bind:class="Object.keys(errors).length > 0 ? 'opacity-50 cursor-not-allowed' : ''"
            >
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
            sdos_id: '',
            check_number: '',
            cash_amount: '',
            cash_date: '',
            dv_number: '',
            ors_number: '',
            resp_code: '',
            uacs_code: ''
        },
        errors: {},

        isValidString(value, pattern) {
            return pattern.test(value);
        },

        validateField(field) {
            const val = this.form[field];
            const codePattern =  /^[A-Za-z0-9\-\.\/\s]+$/;
            const today = new Date().toISOString().split('T')[0];
            const maxAmount = 75000000;

            switch (field) {
                case 'check_number':
                    if (!val) {
                        delete this.errors[field];
                        break;
                    }
                    if (!this.isValidString(val, codePattern)) {
                        this.errors[field] = 'Invalid characters used.';
                    } else if (val.length > 255) {
                        this.errors[field] = 'Must not exceed 255 characters.';
                    } else {
                        delete this.errors[field];
                    }
                    break;

                case 'cash_amount':
                    if (!val) {
                        break;
                    }
                    if (isNaN(val)) {
                        this.errors.cash_amount = 'Must be a valid number.';
                    } else if (+val < 0.01) {
                        this.errors.cash_amount = 'Minimum is ₱0.01.';
                    } else if (+val > maxAmount) {
                        this.errors.cash_amount = `Maximum is ₱${maxAmount.toLocaleString()}.`;
                    } else {
                        delete this.errors.cash_amount;
                    }
                    break;

                case 'cash_date':
                    if (!val) {
                        break;
                    }
                    if (val > today) {
                        this.errors.cash_date = 'Date cannot be in the future.';
                    } else {
                        delete this.errors.cash_date;
                    }
                    break;

                case 'dv_number':
                case 'ors_number':
                case 'resp_code':
                case 'uacs_code':
                    if (!val) {
                        break;
                    }
                    if (!this.isValidString(val, codePattern)) {
                        this.errors[field] = 'Invalid characters used.';
                    } else if (val.length > 255) {
                        this.errors[field] = 'Must not exceed 255 characters.';
                    } else {
                        delete this.errors[field];
                    }
                    break;
            }
        },

        validateForm() {
            this.errors = {};
            for (const field in this.form) {
                this.validateField(field);
            }
            return Object.keys(this.errors).length === 0;
        },

        async getCashAdvancesList(page = 1) {
            this.loading = true;
            try {
                
                let url = `/cash-advance/index?page=${page}&perPage=${this.perPage}&sortBy=${this.sortBy}&sortOrder=${this.sortOrder}&filterBy=${this.filterBy}`;

                if (this.searchCashAdvance) {
                    url += `&search=${encodeURIComponent(this.searchCashAdvance)}`;
                }

                const response = await axios.get(url);
                const data = await response.data;

                this.cashAdvancesList = data.data;
                this.currentPage = data.current_page;
                this.totalPages = data.last_page;
            } catch (error) {
                console.error("Error fetching cash advances:", error);
            } finally {
                this.loading = false;
            }
        },
         sdoList: [],

        init() {
            this.getSDOList();
        },

        getSDOList() {
        axios.get('/getSDOList')
            .then(response => {
                this.sdoList = response.data.map(sdo => {
                    return {
                        id: sdo.id,
                        name: `${sdo.firstname} ${sdo.middlename ?? ''} ${sdo.lastname} ${sdo.extension_name ?? ''}`.trim()
                    };
                });
            })
            .catch(error => console.error('Error fetching SDO list:', error));
    }

    
    }));
});
</script>




