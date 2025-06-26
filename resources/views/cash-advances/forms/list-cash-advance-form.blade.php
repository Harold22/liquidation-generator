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
                        hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        title="Add Cash Advance">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 8v8"></path>
                        <path d="M8 12h8"></path>
                    </svg>
                    {{ __('Add') }}
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
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border">
            <thead class="bg-gray-100 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Special Disbursing Officer</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Cash Advance Amount</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Cash Advance Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Program</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200"></th>
                </tr>
            </thead>
            <tbody id="table-body" class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                 <template x-if="cashAdvancesList.length === 0">
                    <tr>
                        <td colspan="999" class="text-center text-red-500 py-4">
                            No records found.
                        </td>
                    </tr>
                </template>
                <template x-for="list in cashAdvancesList" x-bind:key="list.id">
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 border-b">
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100 capitalize" x-text="list.special_disbursing_officer"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100" x-text="'₱' +(parseFloat(list.cash_advance_amount)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100" x-text="list.cash_advance_date"></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100" x-text="list.program_abbreviation"></td>
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
                            <div class="flex items-center gap-3">
                                <!-- Allocate Fund -->
                                <div x-data="{ tooltip: false }" class="relative">
                                    <button @click="allocateFund(list); loading = true"
                                        @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                        class="flex items-center justify-center w-8 h-8 text-gray-500 hover:text-green-600 hover:bg-green-100 dark:hover:bg-green-900 rounded-full transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                                        </svg>
                                    </button>
                                    <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                        Allocate Fund
                                    </span>
                                </div>

                                <!-- Refund -->
                                <div x-data="{ tooltip: false }" class="relative">
                                    <button @click="refundModalData(list); loading = true"
                                        @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                        class="flex items-center justify-center w-8 h-8 text-gray-500 hover:text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-full transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                    <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                        Refund
                                    </span>
                                </div>

                                <!-- Update -->
                                <div x-data="{ tooltip: false }" class="relative">
                                    <button @click="updateModalData(list)"
                                        @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                        class="flex items-center justify-center w-8 h-8 text-gray-500 hover:text-yellow-600 hover:bg-yellow-100 dark:hover:bg-yellow-900 rounded-full transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        </svg>
                                    </button>
                                    <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                        Update
                                    </span>
                                </div>

                                <!-- Delete -->
                                <div x-data="{ tooltip: false }" class="relative">
                                    <button @click="deleteModalData(list)"
                                        @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                        class="flex items-center justify-center w-8 h-8 text-gray-500 hover:text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-full transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                    <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
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
        <!-- Fund Allocation Modal -->
       <div 
            x-show="allocateFundModal && loading === false"
            x-cloak
            x-transition
            class="fixed inset-0 z-[999] bg-black bg-opacity-50 overflow-y-auto"
        >
            <div class="min-h-screen flex flex-col justify-start items-center py-10 px-4">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl"> 
                    <!-- header -->
                    <header class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Cash Advance Allocation
                        </h2>
                        <button @click="allocateFundModal = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                <path d="M6 18L18 6M6 6l12 12" />
                            </svg>  
                        </button>
                    </header>

                    <!-- content -->
                    @include('cash-advances.fund-allocation.add-allocation-cash-advance')
                </div>
            </div>
        </div>


        <!-- Refund Modal -->
        <div 
            x-show="refundCashAdvanceModal && loading === false" 
            x-cloak 
            x-transition 
            class="fixed inset-0 z-[999] overflow-y-auto bg-black bg-opacity-50"
        >
            <div class="min-h-screen flex flex-col items-center justify-start py-10 px-4">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                    <!-- Modal Header -->
                    <header class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Cash Advance Refund</h2>
                        <button 
                            @click="refundCashAdvanceModal = false" 
                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                <path d="M6 18L18 6M6 6l12 12" />
                            </svg>  
                        </button>
                    </header>

                    <!-- Modal Content -->
                    @include('cash-advances.forms.refund-cash-advance-form')

                </div>
            </div>
        </div>


        <!-- Update Cash Advance Modal -->
        <div 
            x-show="updateCashAdvanceModal" 
            x-cloak 
            x-transition 
            class="fixed inset-0 z-[999] overflow-y-auto bg-black bg-opacity-50"
        >
            <div class="min-h-screen flex flex-col items-center justify-start py-10 px-4">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                    <!-- Modal Header -->
                    <header class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Cash Advance</h2>
                        <button 
                            @click="updateCashAdvanceModal = false" 
                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                <path d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </header>

                    <!-- Modal Body -->
                    @include('cash-advances.forms.update-cash-advance-form')

                </div>
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
