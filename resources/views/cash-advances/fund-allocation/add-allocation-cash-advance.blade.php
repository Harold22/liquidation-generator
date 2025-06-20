<form 
    method="POST" 
    action="{{ route('allocation.store') }}" 
    @submit.prevent="submitForm"
>
    @csrf

    <div class="mt-4 space-y-4">
        <!-- Hidden ID -->
        <x-text-input id="cash_advance_id" name="cash_advance_id" type="hidden"
              x-bind:value="selectedAllocationList.id" />

        <!-- Cash Advance Date and Check Number (TOP) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="cash_advance_date" class="text-sm">
                    {{ __('Cash Advance Date') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input 
                    readonly  
                    id="cash_advance_date" 
                    name="cash_advance_date" 
                    type="date" 
                    x-model="form.cash_advance_date" 
                    class="mt-1 block w-full text-sm" 
                    x-bind:value="selectedAllocationList.cash_advance_date" 
                />
            </div>
            <div>
                <x-input-label for="check_number" class="text-sm">
                    {{ __('Check Number') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input 
                    readonly 
                    id="check_number" 
                    name="check_number" 
                    type="text" 
                    x-model="form.check_number" 
                    class="mt-1 block w-full text-sm" 
                    x-bind:value="selectedAllocationList.check_number" 
                />
            </div>
        </div>

        <!-- Special Disbursing Officer -->
        <div>
            <x-input-label for="special_disbursing_officer" class="text-sm">
                {{ __('Special Disbursing Officer') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input readonly type="text" class="mt-1 block w-full text-sm" 
                          x-bind:value="selectedAllocationList.special_disbursing_officer" />
        </div>

        <!-- Cash Advance Amount -->
        <div>
            <x-input-label for="cash_advance_amount" class="text-sm">
                {{ __('Cash Advance Amount') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input 
                readonly 
                type="text" 
                class="mt-1 block w-full text-sm" 
                x-bind:value="selectedAllocationList.cash_advance_amount 
                    ? '₱' + parseFloat(selectedAllocationList.cash_advance_amount)
                        .toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) 
                    : '₱0.00'" 
            />
        </div>

        <!-- Office Allocations -->
        <div class="space-y-2 mt-4">
            <x-input-label for="allocations" class="text-sm">
                {{ __('Allocate to Office(s)') }} <span class="text-red-500">*</span>
            </x-input-label>

            <template x-for="(allocation, index) in allocations" :key="index">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 relative  py-2 rounded-md group">
                    
                    <!-- Hidden ID input -->
                    <input type="hidden" :name="`allocations[${index}][id]`" :value="allocation.id || ''">
                    <input type="hidden" :name="`allocations[${index}][cash_advance_id]`" :value="allocation.cash_advance_id || ''">

                    <!-- Office -->
                    <select
                        :name="`allocations[${index}][office_id]`"
                        @change="allocation.office_id = $event.target.value"
                        class="block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required
                    >
                        <option value="">Select Office</option>
                        <template x-for="office in offices" :key="office.id">
                            <option
                                :value="office.id"
                                x-text="office.office_name"
                                :selected="office.id === allocation.office_id"
                            ></option>
                        </template>
                    </select>

                    <!-- Amount -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm pointer-events-none">₱</span>
                        <input 
                            :name="`allocations[${index}][amount]`" 
                            x-model="allocation.amount" 
                            type="number" 
                            step="0.01" 
                            min="0" 
                            class="block w-full text-sm pl-7 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                            placeholder="0.00" 
                            required 
                        />
                    </div>


                    <!-- Remove Button -->
                    <button 
                        type="button"
                        @click="allocations.splice(index, 1);
                                hasRemovedAllocation = true;
                        "
                        x-show="allocations.length > 1"
                        class="absolute -top-2 -right-2 bg-white border border-red-500 text-red-500 hover:bg-red-500 hover:text-white transition-all rounded-full w-6 h-6 flex items-center justify-center shadow-md"
                        title="Remove this allocation"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2 h-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>
            </template>


            <!-- Add Office Button -->
            <div class="flex items-center justify-between mt-2">
                <button 
                    type="button" 
                    @click="allocations.push({ office_id: '', amount: '', status: '' })"
                    class="text-sm text-blue-600 hover:underline"
                >
                    + Add Office Allocation
                </button>

                <!-- Totals -->
                <div class="text-sm font-medium text-right space-y-1">
                    <div>
                        Total Allocated: 
                        <span 
                            x-text="'₱' + totalAllocated.toLocaleString(undefined, {minimumFractionDigits: 2})"
                            x-bind:class="overAllocated ? 'text-red-600 font-bold' : 'text-green-600'"
                        ></span>
                    </div>
                    <div>
                        Remaining to Allocate:
                        <span 
                            x-text="'₱' + remainingAmount.toLocaleString(undefined, {minimumFractionDigits: 2})"
                            x-bind:class="overAllocated ? 'text-red-600 font-bold' : 'text-gray-700'"
                        ></span>
                    </div>
                </div>
            </div>

            <!-- Over Allocation Warning -->
            <div x-show="overAllocated" class="text-red-600 text-sm mt-1">
                ⚠️ Allocated amount exceeds the available cash advance.
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4 mt-4">
            <x-primary-button 
                class="text-sm px-4 py-2" 
                x-bind:disabled="overAllocated"
                x-bind:class="overAllocated ? 'opacity-50 cursor-not-allowed' : ''">
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </div>
</form>
<!-- Confirmation Modal -->
<div
    x-show="showConfirmModal"
    x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-medium text-gray-800 mb-4">Confirm Allocation Removal</h2>
        <p class="text-sm text-gray-600 mb-6">
            You've removed one or more office allocations. Are you sure you want to continue saving?
        </p>
        <div class="flex justify-end gap-3">
            <button
                type="button"
                @click="showConfirmModal = false"
                class="px-4 py-2 text-sm bg-gray-200 text-gray-800 rounded hover:bg-gray-300"
            >
                Cancel
            </button>
            <button
                type="button"
                @click="confirmSubmission"
                class="px-4 py-2 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700"
            >
                Confirm and Save
            </button>
        </div>
    </div>
</div>
