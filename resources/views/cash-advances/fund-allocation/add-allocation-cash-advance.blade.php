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
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

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
        </div>


     <!-- Office Allocations -->
<div class="mt-4">
    <x-input-label for="allocations" class="text-sm mb-2 block">
        {{ __('Allocate to Office(s)') }} <span class="text-red-500">*</span>
    </x-input-label>

    <div class="overflow-x-auto border rounded-md">
        <!-- Table -->
        <table class="min-w-full text-sm text-left border-separate border-spacing-y-2">
            <thead class="text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-2 text-left">Office</th>
                    <th class="px-2 text-left">Amount</th>
                    <th class="px-2 text-left">Status <span class="text-xs text-red-400">(Readonly)</span></th>
                    <th class="px-2 w-8"></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(allocation, index) in allocations" :key="index">
                    <tr class="bg-white dark:bg-gray-800 rounded shadow">
                        <!-- Hidden Inputs -->
                        <input type="hidden" :name="`allocations[${index}][id]`" :value="allocation.id || ''">
                        <input type="hidden" :name="`allocations[${index}][cash_advance_id]`" :value="allocation.cash_advance_id || ''">

                        <!-- Office -->
                        <td class="px-2">
                            <select
                                :name="`allocations[${index}][office_id]`"
                                @change="allocation.office_id = $event.target.value"
                                class="w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
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
                        </td>

                        <!-- Amount -->
                        <td class="px-2 py-2">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500">₱</span>
                                <input 
                                    :name="`allocations[${index}][amount]`" 
                                    x-model="allocation.amount" 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    class="pl-6 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                    placeholder="0.00" 
                                    required 
                                />
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-2 py-2">
                            <select
                                :name="`allocations[${index}][status]`"
                                x-model="allocation.status"
                                class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option value="unliquidated">Unliquidated</option>
                                <option value="liquidated">Liquidated</option>
                            </select>
                        </td>

                        <!-- Remove Button -->
                        <td class="px-2 py-2">
                            <button 
                                type="button"
                                @click="allocations.splice(index, 1); hasRemovedAllocation = true"
                                x-show="allocations.length > 1"
                                class="text-red-500 hover:text-red-700"
                                title="Remove"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Add Office Button -->
    <div class="flex items-center justify-between mt-2">
        <button 
            type="button" 
            @click="allocations.push({ office_id: '', amount: '', status: 'unliquidated' })"
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
