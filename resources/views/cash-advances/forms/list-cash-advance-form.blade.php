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
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100 capitalize" x-text="list.special_disbursing_officer"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100" x-text="'₱' +(parseFloat(list.cash_advance_amount)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100" x-text="list.cash_advance_date"></td>
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
                                <!-- Refund Button with Tooltip -->
                                <div x-data="{ tooltip: false }" class="relative flex items-center">
                                    <button @click="refundModalData(list), loading = true"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false"
                                        class="p-2 text-gray-600 hover:text-blue-600  focus:outline-none transition duration-200 ease-in-out">
                                        
                                        <!-- Refund Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                                <path fill="none" d="M0 0h24v24H0z"/>
                                                <path d="M5.671 4.257c3.928-3.219 9.733-2.995 13.4.672 3.905 3.905 3.905 10.237 0 14.142-3.905 3.905-10.237 3.905-14.142 0A9.993 9.993 0 0 1 2.25 9.767l.077-.313 1.934.51a8 8 0 1 0 3.053-4.45l-.221.166 1.017 1.017-4.596 1.06 1.06-4.596 1.096 1.096zM13 6v2h2.5v2H10a.5.5 0 0 0-.09.992L10 11h4a2.5 2.5 0 1 1 0 5h-1v2h-2v-2H8.5v-2H14a.5.5 0 0 0 .09-.992L14 13h-4a2.5 2.5 0 1 1 0-5h1V6h2z"/>
                                            </g>
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
                                        class="p-2 text-gray-600 hover:text-yellow-500 focus:outline-none transition duration-200 ease-in-out">
                                        
                                        <!-- New Edit Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                            <path d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
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
                                        class="p-2 text-gray-600 hover:text-red-600 focus:outline-none transition duration-200 ease-in-out">
                                        
                                        <!-- Trash Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18"></path>
                                            <path d="M8 6V4h8v2"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                            <path d="M5 6l1 14h12l1-14"></path>
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
