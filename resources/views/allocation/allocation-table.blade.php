<section>
    <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
        <!-- Header -->
        <div>
            <h2 class="text-xl font-semibold text-blue-500 dark:text-gray-200">
                {{ __('Office: ') }}<span x-text="officeName"></span>
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Note: The amounts reflected in the tables are those allocated to your office.
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <!-- Search Input -->
            <input 
                type="text" 
                placeholder="Search..."
                x-model="searchQuery"
                @input.debounce.500ms="search"
                class="px-4 py-1.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 
                    dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100 w-48"
            />
        </div>
    </div>


    @include('error-messages.messages')

    <!-- Loading Indicator -->
    <div x-show="loading">
        <x-spinner />
    </div>
    <div class="w-full overflow-x-auto py-2 mt-4">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border">
            <thead class="bg-gray-100 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Special Disbursing Officer</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Cash Advance Amount</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Cash Advance Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Allocated Amount</th>
                    <!-- <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Amount Imported</th> -->
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200"></th>
                </tr>
            </thead>
            <tbody id="table-body" class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <template x-if="allocations.length === 0">
                    <tr>
                        <td colspan="999" class="text-center text-red-500 py-4">
                            No records found.
                        </td>
                    </tr>
                </template>
                <template x-for="allocation in allocations" :key="allocation.id">
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100 capitalize" x-text="allocation.sdo_name"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100" x-text="'₱' +(parseFloat(allocation.cash_advance_amount)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100" x-text="allocation.cash_advance_date"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100" x-text="'₱' +(parseFloat(allocation.amount)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></td>
                        <!-- <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100" x-text="'₱' +(parseFloat(allocation.amount)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></td> -->
                        <td class="px-6 py-4 text-sm font-medium">
                            <span 
                                :class="{
                                    'text-green-600 bg-green-100 px-2 py-1 rounded-lg': allocation.status === 'liquidated',
                                    'text-orange-600 bg-orange-100 px-2 py-1 rounded-lg': allocation.status === 'unliquidated'
                                }"
                                x-text="allocation.status === 'unliquidated' ? 'Unliquidated' : 'Liquidated'">
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <!-- Liquidation Report Button -->
                                <div x-data="{ tooltip: false }" class="relative">
                                    <a 
                                        :href="'{{ route('liquidation-report', ['id' => '__ID__']) }}'.replace('__ID__', allocation.id)" 
                                        target="_blank"
                                        @mouseenter="tooltip = true" 
                                        @mouseleave="tooltip = false"
                                        class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-green-100 dark:hover:bg-green-900 hover:text-green-800 transition duration-200 ease-in-out"
                                        aria-label="View Liquidation Report"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                                        </svg>
                                    </a>
                                    <span x-show="tooltip" x-transition.opacity
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md z-50">
                                        Liquidation Report
                                    </span>
                                </div>

                                <!-- RCD Button -->
                                <div x-data="{ tooltip: false }" class="relative">
                                    <a 
                                        :href="'{{ route('rcd', ['id' => '__ID__']) }}'.replace('__ID__', allocation.id)" 
                                        target="_blank"
                                        @mouseenter="tooltip = true" 
                                        @mouseleave="tooltip = false"
                                        class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-800 transition duration-200 ease-in-out"
                                        aria-label="View Report of Cash Disbursements"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </a>
                                    <span x-show="tooltip" x-transition.opacity
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md z-50">
                                        Report Cash Disbursements
                                    </span>
                                </div>

                                <!-- Update Status Button -->
                                <div x-data="{ tooltip: false }" class="relative">
                                    <button 
                                        @click="updateAllocationStatus(allocation.id)" 
                                        @mouseenter="tooltip = true" 
                                        @mouseleave="tooltip = false"
                                        class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-yellow-100 dark:hover:bg-yellow-900 hover:text-yellow-800 transition duration-200 ease-in-out"
                                        aria-label="Change Status"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                    <span x-show="tooltip" x-transition.opacity
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md z-50">
                                        Change Status
                                    </span>
                                </div>
                            </div>
                        </td>


                    </tr>
                </template>
            </tbody>
        </table>

            <!-- Pagination -->
        <div class="flex items-center justify-center mt-6 space-x-4 mb-4">
            <button 
                @click="changePage(currentPage - 1)" 
                :disabled="currentPage === 1" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:bg-gray-300 disabled:cursor-not-allowed">
                &laquo; Previous
            </button>

            <!-- Page Indicator -->
            <span class="text-sm text-gray-600">
                Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
            </span>

            <!-- Next Button -->
            <button 
                @click="changePage(currentPage + 1)" 
                :disabled="currentPage === totalPages" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:bg-gray-300 disabled:cursor-not-allowed">
                Next &raquo;
            </button>

            <!-- Items per Page Dropdown -->
            <div class="flex items-center space-x-2">
                <label for="perPage" class="text-sm font-medium text-gray-700 dark:text-gray-300">Show</label>
                <div class="relative">
                    <select 
                        id="perPage" 
                        x-model="perPage"
                        @change="updatePerPage(perPage)"
                        class="px-4 py-2 pr-8 text-sm font-medium text-gray-700 bg-white border rounded-md shadow-sm appearance-none focus:outline-none">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    <!-- Custom Dropdown Arrow -->
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" >
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">entries</span>
            </div>
        </div>

        <!-- Update Cash Advance Modal -->
        <div 
            x-show="updateStatusModal" 
            x-cloak 
            x-transition 
            class="fixed inset-0 z-[999] bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto"
        >
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg mx-4 my-10 shadow-xl">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Confirm Status Update
                    </h2>
                    <button 
                        @click="updateStatusModal = false" 
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" 
                            viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Description -->
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                    Are you sure you want to set this record to <strong>Liquidated</strong>?
                </p>

                <p class="text-xs text-red-600 dark:text-red-400 mb-4">
                    <strong>Note:</strong> Only administrators can revert the status back to <em>Unliquidated</em>.
                </p>


                <!-- Modal Body -->
                @include('allocation.update-allocation-status')
            </div>
        </div>

             

        </div>
    </div>
</section>
