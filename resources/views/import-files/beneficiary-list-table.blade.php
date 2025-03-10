
<table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
    <thead class="bg-gray-100 dark:bg-gray-600">
        <tr>
            <th scope="col" class="px-4 py-2">{{ __('Name') }}</th>
            <th scope="col" class="px-4 py-2">{{ __('Assistance') }}</th>
            <th scope="col" class="px-4 py-2">{{ __('Amount') }}</th>
            <th scope="col" class="px-4 py-2">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody x-show="loading === false">
        <template x-for="beneficiary in beneficiaryList" :key="beneficiary.id">
            <tr class="border-t">
                <td class="px-4 py-3" 
                    x-text="(beneficiary.lastname) + ', ' + 
                            (beneficiary.firstname) + ' ' + 
                            (beneficiary.middlename ?? '') + ' ' + 
                            (beneficiary.extension_name ?? '')">
                </td>
                <td class="px-4 py-3" x-text="beneficiary.assistance_type"></td>
                <td class="px-4 py-3" x-text="beneficiary.amount"></td>
                <td class="px-4 py-3 flex space-x-2">
                   <!-- Update Button -->
                    <div x-data="{ tooltip: false }" class="relative">
                        <button @click="updateBeneficiaryData(beneficiary)" 
                            @mouseenter="tooltip = true" 
                            @mouseleave="tooltip = false"
                            class="p-2 text-yellow-400 hover:text-yellow-600 focus:outline-none transition duration-200 ease-in-out">                            
                            
                            <!-- New Edit Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <path d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <!-- Tooltip (inside relative div for proper positioning) -->
                        <span x-show="tooltip"
                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
                            x-transition.opacity>
                            Update
                        </span>
                    </div>

                    <div x-show="updateBeneficiaryModal" x-cloak x-transition.opacity class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-20">
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

                    <!-- delete button -->
                    <div x-data="{ tooltip: false }" class="relative flex items-center">
                        <button @click="deleteBeneficiaryModal = true; beneToDelete = beneficiary.id"
                            @mouseenter="tooltip = true" 
                            @mouseleave="tooltip = false"
                            class="p-2 text-gray-600 hover:text-red-600  focus:outline-none transition duration-200 ease-in-out">
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
                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
                            x-transition.opacity>
                            Delete File
                        </span>
                    </div>

                    <div x-show="deleteBeneficiaryModal && beneToDelete === beneficiary.id" 
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md shadow-lg">
                            <header class="flex justify-between items-center">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                                <button @click="deleteBeneficiaryModal = false; beneToDelete = null" 
                                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                        <path d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </header>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">
                                Are you sure you want to delete <span class="font-bold  text-red-500" x-text="(beneficiary.lastname) + ', ' + 
                                (beneficiary.firstname) + ' ' + 
                                (beneficiary.middlename ?? '') + ' ' + 
                                (beneficiary.extension_name ?? '')"></span>?
                            </p>
                            <div class="mt-6 flex justify-end space-x-4">
                                <button @click="deleteBeneficiaryModal = false, beneToDelete = null" type="button"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-md">
                                    Cancel
                                </button>
                                <button @click="deleteBeneficiary(beneficiary.id); deleteBeneficiaryModal = false; beneToDelete = null"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md">
                                    Confirm
                                </button>
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
                class="px-3 py-2 pr-5  text-sm font-medium text-gray-700 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 appearance-none">
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
 