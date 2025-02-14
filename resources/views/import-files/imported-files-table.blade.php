<table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
    <thead class="bg-gray-100 dark:bg-gray-600">
        <tr>
            <th scope="col" class="px-2 py-2">{{ __('File Name') }}</th>
            <th scope="col" class="px-2 py-2">{{ __('Date Imported') }}</th>
            <th scope="col" class="px-2 py-2">{{ __('Location') }}</th>
            <th scope="col" class="px-2 py-2">{{ __('Beneficiaries') }}</th>
            <th scope="col" class="px-2 py-2">{{ __('Amount') }}</th>
            <th scope="col" class="px-2 py-2">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        <template x-for="file in file_list" :key="file.id">
            <tr class="border-t">
                <td class="px-2 py-3" x-text="file.file_name"></td>
                <td class="px-2 py-3" x-text="formatDate(file.created_at)"></td>
                <td class="px-2 py-3 capitalize" x-text="file.location"></td>
                <td class="px-2 py-3" x-text="file.total_beneficiary.toLocaleString()"></td>
                <td class="px-2 py-3" x-text="file.total_amount.toLocaleString()"></td>
                <td class="px-2 py-3 flex items-center justify-center space-x-2">
                    <button @click="updateFileLocation(file)"
                        class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                        Edit
                    </button>

                    <div x-show="updateFileModal" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-20">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                            <header class="flex justify-between items-center">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Location</h2>
                                <button @click="updateFileModal = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                        <path d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </header>
                            @include('import-files.update-file-modal')
                        </div>
                    </div>
                    <!-- View Button -->
                    <button @click="importedFilesTable = false, beneficiaryListTable = true; getFileDataPerFile(file.id), loading = true"
                        class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                        List
                    </button>

                    <button @click="deleteFileModal = true; fileToDelete = file.id"
                        class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                        Delete
                    </button>

                    <!-- Delete Confirmation Modal -->
                    <div x-show="deleteFileModal && fileToDelete === file.id" 
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md shadow-lg">
                            <header class="flex justify-between items-center">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                                <button @click="deleteFileModal = false; fileToDelete = null" 
                                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                        <path d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </header>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">
                                Are you sure you want to delete this file?
                            </p>
                            <div class="mt-6 flex justify-end space-x-4">
                                <button @click="deleteFileModal = false, fileToDelete = null" type="button"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-md">
                                    Cancel
                                </button>
                                <button @click="deleteFile(file.id); deleteFileModal = false; fileToDelete = null"
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
    <tfoot class="bg-gray-100 dark:bg-gray-600">
        <tr>
            <td colspan="3" class="px-6 py-3 font-semibold">{{ __('Overall Total') }}</td>
            <td class="px-2 py-3" x-text="file_list.reduce((total, file) => total + file.total_beneficiary, 0).toLocaleString()"></td>
            <td class="px-2 py-3" x-text="file_list.reduce((total, file) => total + file.total_amount, 0).toLocaleString()"></td>
            <td class="px-2 py-3"></td>
        </tr>
    </tfoot>
    
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