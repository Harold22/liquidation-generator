<section>
    <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
        <!-- Header -->
        <h2 class="text-xl font-semibold text-blue-500 dark:text-gray-200">
            {{ __('Cash Advance List') }}
        </h2>

        <div class="flex items-center gap-3 flex-wrap">
            <!-- Filter Label -->
            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                Filter:
            </span>
            <div class="flex flex-wrap gap-2">
                <button 
                    @click="toggleSort('cash_advance_amount')" 
                    class="flex items-center gap-2 px-3 py-1.5 text-sm border rounded-lg shadow 
                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Amount 
                    <span 
                        x-show="sortBy === 'cash_advance_amount'" 
                        x-text="sortOrder === 'ASC' ? '↑' : '↓'" 
                        class="text-green-500">
                    </span>
                </button>

                <!-- Date Sort Button -->
                <button 
                    @click="toggleSort('cash_advance_date')" 
                    class="flex items-center gap-2 px-3 py-1.5 text-sm border rounded-lg shadow 
                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Date 
                    <span 
                        x-show="sortBy === 'cash_advance_date'" 
                        x-text="sortOrder === 'ASC' ? '↑' : '↓'" 
                        class="text-green-500">
                    </span>
                </button>

                <!-- Filter Buttons -->
                <button 
                    @click="applyFilter('Liquidated')" 
                    class="flex items-center gap-2 px-3 py-1.5 text-sm border rounded-lg shadow 
                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Liquidated
                </button>
                
                <button 
                    @click="applyFilter('Unliquidated')" 
                    class="flex items-center gap-2 px-3 py-1.5 text-sm border rounded-lg shadow 
                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Unliquidated
                </button>

                <!-- All Button (Resets Filters & Sorting) -->
                <button 
                    @click="resetFilters()" 
                    class="flex items-center gap-2 px-3 py-1.5 text-sm border  rounded-lg shadow 
                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    All
                </button>
            </div>

            <!-- Search Input -->
            <input 
                type="text" 
                placeholder="Search..." 
                x-model="searchCashAdvance"
                @input.debounce.500ms="getCashAdvancesList(1)"
                class="px-4 py-1.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 
                    dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100 w-48"
            />

            <!-- Action Buttons (Add + Import) -->
            <div class="flex gap-2">
                <a href="{{ route('cash-advance.add') }}" 
                    class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg shadow 
                        hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 8v8"></path>
                        <path d="M8 12h8"></path>
                    </svg>
                    {{ __('Add') }}
                </a>

                <a href="{{ route('import-files') }}" 
                    class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg shadow 
                        hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 16v4h16v-4"></path>
                        <path d="M12 3v12"></path>
                        <path d="M8 7l4-4 4 4"></path>
                    </svg>
                    {{ __('Import') }}
                </a>
            </div>
        </div>
    </div>

    @include('error-messages.messages')

    <!-- Loading Indicator -->
    <div x-show="loading">
        <x-spinner />
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
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100 capitalize" x-text="list.special_disbursing_officer"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100" x-text="'₱' +(parseFloat(list.cash_advance_amount)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100" x-text="list.cash_advance_date"></td>
                        <td class="px-6 py-4 text-xs font-medium">
                            <span 
                                :class="{
                                    'text-green-600 bg-green-100 px-2 py-1 rounded-lg': list.status === 'Liquidated',
                                    'text-orange-600 bg-orange-100 px-2 py-1 rounded-lg': list.status === 'Unliquidated'
                                }"
                                x-text="list.status">
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                            <div class="flex space-x-2">
                                <div x-data="{ open: false, tooltip: false }" class="relative inline-block text-left">
                                    <!-- Main Button with Tooltip -->
                                    <div class="relative flex items-center">
                                        <button @click="open = !open" 
                                            @mouseenter="tooltip = true"
                                            @mouseleave="tooltip = false"
                                            class="p-2 text-green-500 hover:text-green-700 focus:outline-none transition duration-200 ease-in-out">
                                            
                                            <!-- Liquidate Icon (Clipboard with Checkmark) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 12l2 2 4-4"></path>
                                                <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                                                <path d="M9 4V2h6v2"></path>
                                            </svg>
                                        </button>

                                        <!-- Tooltip -->
                                        <span x-show="tooltip"
                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md"
                                            x-transition.opacity>
                                            Liquidate
                                        </span>
                                    </div>

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
                                        </ul>
                                    </div>
                                </div>
                                <!-- Refund Button with Tooltip -->
                                <div x-data="{ tooltip: false }" class="relative flex items-center">
                                    <button @click="refundModalData(list), loading = true"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false"
                                        class="p-2 text-gray-500 hover:text-blue-600  focus:outline-none transition duration-200 ease-in-out">
                                        
                                        <!-- Refund Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75h4.875a2.625 2.625 0 0 1 0 5.25H12M8.25 9.75 10.5 7.5M8.25 9.75 10.5 12m9-7.243V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185Z" />
                                        </svg>

                                    </button>

                                    <!-- Tooltip -->
                                    <span x-show="tooltip"
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md"
                                        x-transition.opacity>
                                        Refund
                                    </span>
                                </div>
                                <!-- Update Button with Tooltip -->
                                <div x-data="{ tooltip: false }" class="relative flex items-center">
                                    <button @click="updateModalData(list)" 
                                        @mouseenter="tooltip = true" 
                                        @mouseleave="tooltip = false" 
                                        class="py-2 pr-2 text-gray-500 hover:text-yellow-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <!-- Tooltip -->
                                    <span x-show="tooltip" 
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md"
                                        x-transition.opacity>
                                        Update
                                    </span>
                                </div>
                                <!-- Delete Button with Tooltip -->
                                <div x-data="{ tooltip: false }" class="relative flex items-center">
                                    <button @click="deleteModalData(list)" 
                                        @mouseenter="tooltip = true" 
                                        @mouseleave="tooltip = false" 
                                        class="p-2 text-gray-500 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>

                                    <!-- Tooltip -->
                                    <span x-show="tooltip" 
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md"
                                        x-transition.opacity>
                                        Delete
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
                        class="px-6 py-2 text-sm font-medium text-gray-700 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 appearance-none">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    <!-- Custom Dropdown Arrow -->
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">entries</span>
            </div>
        </div>
        <!-- Refund Modal -->
        <div x-show="refundCashAdvanceModal && loading == false" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
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

        <!-- Update cash advance modal -->
         <div x-show="updateCashAdvanceModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
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
        <!-- Delete Cash advance modal -->
        <div x-show="deleteCashAdvanceModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
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
    </div>
</section>
