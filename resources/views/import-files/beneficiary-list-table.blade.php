
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
                    <!-- update button -->
                    <button @click="updateBeneficiaryData(beneficiary)"
                        class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19H4v-3L16.5 3.5z" />
                        </svg>
                        Update
                    </button>

                    <div x-show="updateBeneficiaryModal" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-20">
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
                    <button @click="deleteBeneficiaryModal = true; beneToDelete = beneficiary.id"
                        class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Delete
                    </button>

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
</div>
 