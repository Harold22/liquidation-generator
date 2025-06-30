
<div class="mt-4 space-y-4">
    <div class="flex gap-4">
        <!-- Special Disbursing Officer (Left Column) -->
        <div class="w-1/2">
            <x-input-label for="special_disbursing_officer" class="text-sm">
                {{ __('Special Disbursing Officer') }}
            </x-input-label>
            <x-text-input 
                readonly 
                type="text" 
                class="mt-1 block w-full text-sm" 
                x-bind:value="viewDataList.special_disbursing_officer" 
            />
        </div>
        <div class="w-1/2">
            <x-input-label for="cash_advance_amount" class="text-sm">
                {{ __('Cash Advance Amount') }}
            </x-input-label>
            <x-text-input 
                readonly 
                type="text" 
                class="mt-1 block w-full text-sm" 
                x-bind:value="'₱' + (parseFloat(viewDataList.cash_advance_amount || 0)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"
            />

        </div>
    </div>

    <div class="flex gap-4">
        <!-- Special Disbursing Officer (Left Column) -->
        <div class="w-1/2">
            <x-input-label for="total_amount_imported" class="text-sm">
                {{ __('Overall Imported Total Amount') }}
            </x-input-label>
            <x-text-input 
                readonly 
                type="text" 
                class="mt-1 block w-full text-sm" 
                x-bind:value="aggregatedData.total_imported_amount !== undefined ? '₱' + parseFloat(aggregatedData.total_imported_amount).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '₱0.00'"

            />
        </div>
        <div class="w-1/2">
            <x-input-label class="text-sm">
                {{ __('Overall Total Beneficiaries') }}
            </x-input-label>
            <x-text-input 
                readonly 
                type="text" 
                class="mt-1 block w-full text-sm" 
                x-bind:value="Number(aggregatedData.total_imported_beneficiaries) ? Number(aggregatedData.total_imported_beneficiaries).toLocaleString() : '0'" 
            />

        </div>
    </div>


    <div class="mt-4">
        <x-input-label for="allocations_summary" class="text-sm mb-2 block">
            {{ __('Allocation Summary') }}
        </x-input-label>

        <div class="overflow-x-auto border rounded-md">
            <table class="min-w-full text-sm text-left border-separate border-spacing-y-2">
                <thead class="text-gray-700">
                    <tr>
                        <th class="px-2 py-2 border-b">Office</th>
                        <th class="px-2 py-2 border-b">Allocated</th>
                        <th class="px-2 py-2 border-b">Status</th>
                        <th class="px-2 py-2 border-b">Total Amount</th>
                        <th class="px-2 py-2 border-b">Beneficiaries</th>
                    </tr>
                </thead>

                <tbody>
                     <template x-if="aggregatedData.allocations_summary?.length === 0">
                        <tr>
                            <td colspan="999" class="text-center text-red-500 py-4">
                                No Allocation Yet.
                            </td>
                        </tr>
                    </template>
                    <template x-for="allocation in aggregatedData.allocations_summary" :key="allocation.allocation_id">
                        <tr class="bg-white dark:bg-gray-800 rounded shadow">
                            <td class="px-2 py-1" x-text="allocation.office_name"></td>
                            <td class="px-2 py-1" x-text="'₱' + parseFloat(allocation.allocation_amount).toLocaleString(undefined, { minimumFractionDigits: 2 })"></td>
                            <td class="px-2 py-1 capitalize" x-text="allocation.allocation_status"></td>
                            <td class="px-2 py-1" x-text="'₱' + parseFloat(allocation.total_imported_amount).toLocaleString(undefined, { minimumFractionDigits: 2 })"></td>
                            <td class="px-2 py-1" x-text="Number(allocation.total_imported_beneficiaries) ? Number(allocation.total_imported_beneficiaries).toLocaleString() : '0'"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Submit Button -->
    <div class="flex items-center justify-end gap-4 mt-4">
         <x-secondary-button @click="viewDataModal = false">
            {{ __('Close') }}
        </x-secondary-button>
    </div>
</div>



