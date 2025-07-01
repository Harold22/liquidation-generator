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
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">CA Amount</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-200">CA Date</th>
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
                    <tr class="hover:bg-gray-50 border-b">
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
                                    <button @click="viewData(list), loading = true"
                                        @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                        class="flex items-center justify-center w-8 h-8 text-gray-500 hover:text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-full transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>

                                    </button>
                                    <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                        View Allocations Details
                                    </span>
                                </div>

                                <!-- Allocate Fund -->
                                <div x-data="{ tooltip: false }" class="relative">
                                    <button @click="allocateFund(list); loading = true"
                                        @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                        class="flex items-center justify-center w-8 h-8 text-gray-500 hover:text-green-600 hover:bg-green-100 dark:hover:bg-green-900 rounded-full transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
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
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
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
        <!-- View Data -->
        <div 
            x-show="viewDataModal"
            x-cloak
            x-transition
            class="fixed inset-0 z-[999] bg-black bg-opacity-50 overflow-y-auto"
        >
            <div class="min-h-screen flex flex-col justify-start items-center py-10 px-4">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-2xl"> 
                    <!-- header -->
                    <header class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Cash Advance Full Allocation Details
                        </h2>
                        <button @click="viewDataModal = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                <path d="M6 18L18 6M6 6l12 12" />
                            </svg>  
                        </button>
                    </header>

                    <!-- content -->
                    @include('cash-advances.forms.view-cash-advance-data')
                </div>
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
