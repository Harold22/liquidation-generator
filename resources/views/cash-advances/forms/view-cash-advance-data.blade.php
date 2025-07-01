<div class="mt-4 space-y-6">
    <!-- Disbursing Officer & Amount -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="special_disbursing_officer" class="text-sm font-semibold text-gray-700">
                Special Disbursing Officer
            </x-input-label>
            <x-text-input 
                readonly 
                type="text" 
                class="mt-1 w-full text-sm  border-gray-300 rounded-md shadow-sm" 
                x-bind:value="viewDataList.special_disbursing_officer" 
            />
        </div>

        <div>
            <x-input-label for="cash_advance_amount" class="text-sm font-semibold text-gray-700">
                Cash Advance Amount
            </x-input-label>
            <x-text-input 
                readonly 
                type="text" 
                class="mt-1 w-full text-sm  border-gray-300 rounded-md shadow-sm" 
                x-bind:value="'₱' + (parseFloat(viewDataList.cash_advance_amount || 0)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"
            />
        </div>
    </div>

    <!-- Metrics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <x-input-label class="text-sm font-semibold text-gray-700">
                Imported vs CA (%)
            </x-input-label>
            <x-text-input
                readonly
                type="text"
                class="mt-1 w-full text-sm  border-gray-300 rounded-md shadow-sm"
                x-bind:value="(() => {
                    const total = parseFloat(viewDataList.cash_advance_amount || 0);
                    const imported = parseFloat(aggregatedData.total_imported_amount || 0);
                    return total > 0 ? (imported / total * 100).toFixed(2) + '%' : '0%';
                })()"
            />
        </div>

        <div>
            <x-input-label class="text-sm font-semibold text-gray-700">
                Remaining to Liquidate
            </x-input-label>
            <x-text-input
                readonly
                type="text"
                class="mt-1 w-full text-sm  border-gray-300 rounded-md shadow-sm"
                x-bind:value="(() => {
                    const total = parseFloat(viewDataList.cash_advance_amount || 0);
                    const imported = parseFloat(aggregatedData.total_imported_amount || 0);
                    const remaining = Math.max(total - imported, 0);
                    return '₱' + remaining.toLocaleString(undefined, { minimumFractionDigits: 2 });
                })()"
            />
        </div>

        <div>
            <x-input-label class="text-sm font-semibold text-gray-700">
                Remaining (%)
            </x-input-label>
            <x-text-input
                readonly
                type="text"
                class="mt-1 w-full text-sm  border-gray-300 rounded-md shadow-sm"
                x-bind:value="(() => {
                    const total = parseFloat(viewDataList.cash_advance_amount || 0);
                    const imported = parseFloat(aggregatedData.total_imported_amount || 0);
                    return total > 0 ? ((1 - imported / total) * 100).toFixed(2) + '%' : '0%';
                })()"
            />
        </div>
    </div>

    <!-- Totals -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="total_amount_imported" class="text-sm font-semibold text-gray-700">
                Overall Imported Total Amount
            </x-input-label>
            <x-text-input 
                readonly 
                type="text" 
                class="mt-1 w-full text-sm  border-gray-300 rounded-md shadow-sm" 
                x-bind:value="aggregatedData.total_imported_amount !== undefined ? '₱' + parseFloat(aggregatedData.total_imported_amount).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '₱0.00'"
            />
        </div>

        <div>
            <x-input-label class="text-sm font-semibold text-gray-700">
                Overall Total Beneficiaries
            </x-input-label>
            <x-text-input 
                readonly 
                type="text" 
                class="mt-1 w-full text-sm  border-gray-300 rounded-md shadow-sm" 
                x-bind:value="Number(aggregatedData.total_imported_beneficiaries) ? Number(aggregatedData.total_imported_beneficiaries).toLocaleString() : '0'" 
            />
        </div>
    </div>

    <!-- Allocation Summary Table -->
    <div class="mt-6">
        <x-input-label for="allocations_summary" class="text-sm font-semibold text-gray-700 mb-2 block">
            Allocation Summary
        </x-input-label>

        <div class="overflow-x-auto border rounded-md shadow-sm">
            <table class="min-w-full text-sm text-left border-separate border-spacing-y-1">
                <thead class="bg-gray-100 text-gray-700 border-b">
                    <tr>
                        <th class="px-3 py-2 border-b">Office</th>
                        <th class="px-3 py-2 border-b">Allocated</th>
                        <th class="px-3 py-2 border-b">Status</th>
                        <th class="px-3 py-2 border-b">Total Amount</th>
                        <th class="px-3 py-2 border-b">Beneficiaries</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="aggregatedData.allocations_summary?.length === 0">
                        <tr>
                            <td colspan="5" class="text-center text-red-500 py-4">
                                No Allocation Yet.
                            </td>
                        </tr>
                    </template>

                    <template x-for="allocation in aggregatedData.allocations_summary" :key="allocation.allocation_id">
                        <tr class="bg-white hover: dark:bg-gray-800 dark:hover:bg-gray-700 transition rounded shadow-sm">
                            <td class="px-3 py-2 font-medium" x-text="allocation.office_name"></td>
                            <td class="px-3 py-2" x-text="'₱' + parseFloat(allocation.allocation_amount).toLocaleString(undefined, { minimumFractionDigits: 2 })"></td>
                            <td class="px-3 py-2 capitalize" x-text="allocation.allocation_status"></td>
                            <td class="px-3 py-2" x-text="'₱' + parseFloat(allocation.total_imported_amount).toLocaleString(undefined, { minimumFractionDigits: 2 })"></td>
                            <td class="px-3 py-2" x-text="Number(allocation.total_imported_beneficiaries) ? Number(allocation.total_imported_beneficiaries).toLocaleString() : '0'"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Close Button -->
    <div class="flex justify-end mt-6">
        <x-secondary-button @click="viewDataModal = false">
            {{ __('Close') }}
        </x-secondary-button>
    </div>
</div>
