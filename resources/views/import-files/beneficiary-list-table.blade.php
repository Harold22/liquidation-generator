<div class="w-full overflow-x-auto">
    <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
        <thead class="bg-gray-100 dark:bg-gray-600">
            <tr class="border">
                <th scope="col" class="px-4 py-2">{{ __('Name') }}</th>
                <th scope="col" class="px-4 py-2">{{ __('Assistance') }}</th>
                <th scope="col" class="px-4 py-2">{{ __('Amount') }}</th>
                <th scope="col" class="px-4 py-2">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody x-show="loading === false">
            <template x-for="beneficiary in beneficiaryList" :key="beneficiary.id">
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 uppercase" 
                        x-text="(beneficiary.lastname) + ', ' + 
                                (beneficiary.firstname) + ' ' + 
                                (beneficiary.middlename ?? '') + ' ' + 
                                (beneficiary.extension_name ?? '')">
                    </td>
                    <td class="px-4 py-3" x-text="beneficiary.assistance_type"></td>
                    <td class="px-4 py-3" x-text="beneficiary.amount"></td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100">
                        <div class="flex items-center gap-3">

                            <!-- Update Beneficiary -->
                            <div x-data="{ tooltip: false }" class="relative">
                                <button @click="updateBeneficiaryData(beneficiary)"
                                    @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                    class="flex items-center justify-center w-8 h-8 hover:text-yellow-600 hover:bg-yellow-100 dark:hover:bg-yellow-900 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                </button>
                                <span x-show="tooltip"
                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
                                    x-transition.opacity>
                                    Update
                                </span>
                            </div>

                            <!-- Delete Beneficiary -->
                            <div x-data="{ tooltip: false }" class="relative">
                                <button @click="deleteBene(beneficiary)"
                                    @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                    class="flex items-center justify-center w-8 h-8 hover:text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                                <span x-show="tooltip"
                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
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
            @click="changePageBene(beneCurrentPage - 1)" 
            :disabled="beneCurrentPage === 1" 
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:bg-gray-300 disabled:cursor-not-allowed">
            &laquo; Previous
        </button>
        <span class="text-sm text-gray-600">
            Page <span x-text="beneCurrentPage"></span> of <span x-text="beneTotalPages"></span>
        </span>
        <button 
            @click="changePageBene(beneCurrentPage + 1)" 
            :disabled="beneCurrentPage === beneTotalPages" 
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:bg-gray-300 disabled:cursor-not-allowed">
            Next &raquo;
        </button>
        <!-- Items per Page Dropdown -->
        <div class="flex items-center space-x-2">
            <label for="perPage" class="text-sm font-medium text-gray-700 dark:text-gray-300">Show</label>
            <div class="relative">
                <select 
                    id="perPageBene" 
                    x-model="perPageBene"
                    @change="updateBenePerPage(perPageBene)"
                    class="px-6 py-2 text-sm font-medium text-gray-700 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 appearance-none">
                    <option value="5" selected>5</option>
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

    <!-- Update beneficiary -->
    <div x-show="updateBeneficiaryModal" 
        x-cloak 
        x-transition 
        class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
            <header class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Beneficiary Data</h2>
                <button @click="updateBeneficiaryModal = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </header>
            @include('import-files.update-beneficiary')
        </div>
    </div>
    <!-- Delete bene -->
    <div x-show="deleteBeneficiaryModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md shadow-lg">
            <header class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                <button @click="cancelBeneDeletion()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </header>
            <p class="mt-4 text-gray-600 dark:text-gray-300">
                Are you sure you want to delete <span class="font-bold  text-red-500" x-text="(selectedBeneToDelete.lastname) + ', ' + 
                (selectedBeneToDelete.firstname) + ' ' + 
                (selectedBeneToDelete.middlename ?? '') + ' ' + 
                (selectedBeneToDelete.extension_name ?? '')"></span>?
            </p>
            <div class="mt-6 flex justify-end space-x-4">
                <button @click="cancelBeneDeletion()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md">Cancel</button>
                <button @click="confirmedBeneDeletion()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">Confirm</button>
            </div>
        </div>
    </div>



</div>