<section>
    <div class="mt-2 flex justify-between items-center gap-4">
        <div class="flex items-center">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Cash Advances List') }}
            </h2>
        </div>
        <div class="flex items-center gap-2">
            <input 
                type="text" 
                placeholder="Search..." 
                x-model="searchCashAdvance"
                @input.debounce.500ms="getCashAdvancesList(1)"
                class="px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100"
            />

            <a href="{{ route('cash-advance.add') }}" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                {{ __('Add New') }}
            </a>
            <a href="{{ route('import-files') }}" 
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto w-full mt-2 sm:mt-0"
            >
                {{ __('Import') }}
            </a>
        </div>
    </div>
    @include('error-messages.messages')

    <!-- Loading Indicator -->
    <div x-show="loading" x-transition.opacity class="fixed top-0 left-0 w-full h-2 bg-gray-900 bg-opacity-10 backdrop-blur-md z-50">
        <div class="h-2 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 animate-pulse rounded-full shadow-lg"></div>
    </div>
    <div class="mt-4">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Special Disbursing Officer</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Cash Advance Amount</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Cash Advance Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Action</th>
                </tr>
            </thead>
            <tbody id="table-body" class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <template x-for="list in cashAdvancesList" x-bind:key="list.id">
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100" x-text="list.special_disbursing_officer"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100" x-text="'₱' +(parseFloat(list.cash_advance_amount)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100" x-text="list.cash_advance_date"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                            <span x-text="list.status"></span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                            <div class="flex space-x-2">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <!-- Main Button -->
                                    <button @click="open = !open" 
                                        class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                                        Liquidate
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div 
                                        x-show="open" 
                                        @mouseover="open = true" 
                                        @mouseleave="open = false" 
                                        @click.away="open = false" 
                                        class="absolute bottom-full left-0 mb-2 w-56 bg-white z-[9999] rounded-md shadow-lg ring-1 ring-black/5 dark:bg-gray-700 dark:ring-gray-600 transition duration-300 ease-in-out">
                                        
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                            <li>
                                                <a x-bind:href="'{{ route('liquidation-report', ['id' => ':id']) }}'.replace(':id', list.id)"
                                                 target="_blank" 
                                                 class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    Liquidation Report
                                                </a>
                                            </li>
                                            <li>
                                                <a x-bind:href="'{{ route('rcd', ['id' => ':id']) }}'.replace(':id', list.id)" 
                                                target="_blank"  
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    Report Cash Disbursements
                                                </a>
                                            </li>
                                            <li>
                                                <a x-bind:href="'{{ route('cdr', ['id' => ':id']) }}'.replace(':id', list.id)" 
                                                target="_blank"  
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    Cash Disbursement Record
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                 
                                <!-- Refund Button -->
                                <button @click="refundModalData(list), loading = true" class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                                    Refund  
                                </button>
                                <div x-show="refundCashAdvanceModal && loading == false" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-20">
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                                        <header class="flex justify-between items-center">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Cash Advance Refund</h2>
                                            <button @click="refundCashAdvanceModal = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </header>
                                        @include('cash-advances.forms.refund-cash-advance-form')
                                    </div>
                                </div>
                                <!-- Update Button -->
                                <button @click="updateModalData(list)" 
                                    class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-yellow-500 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition duration-200 ease-in-out">
                                    Update
                                </button>
                                <div x-show="updateCashAdvanceModal" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-20">
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                                        <header class="flex justify-between items-center">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Cash Advance</h2>
                                            <button @click="updateCashAdvanceModal = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </header>
                                        @include('cash-advances.forms.update-cash-advance-form')
                                    </div>
                                </div>
                            
                                <!-- Delete Button -->
                                <button @click="deleteModalData(list)" 
                                    class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                                    Delete
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div x-show="deleteCashAdvanceModal" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-20">
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
                                        <header class="flex justify-between items-center">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                                            <button @click="deleteCashAdvanceModal = false" 
                                                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </header>
                                        @include('cash-advances.forms.delete-cash-advance-form')
                                    </div>
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

            <span class="text-sm text-gray-600">
                Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
            </span>

            <button 
                @click="changePage(currentPage + 1)" 
                :disabled="currentPage === totalPages" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:bg-gray-300 disabled:cursor-not-allowed">
                Next &raquo;
            </button>
        </div>
    </div>
</section>
