<div class="w-full overflow-x-auto">
    <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
        <thead class="bg-gray-100 dark:bg-gray-600">
            <tr class="border">
                <th class="px-2 py-2">{{ __('File Name') }}</th>
                <th class="px-2 py-2">{{ __('Date Imported') }}</th>
                <th class="px-2 py-2">{{ __('Location') }}</th>
                <th class="px-2 py-2">{{ __('Beneficiaries') }}</th>
                <th class="px-2 py-2">{{ __('Amount') }}</th>
                <th class="px-2 py-2">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="file in file_list" :key="file.id">
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td class="px-2 py-3" x-text="file.file_name"></td>
                    <td class="px-2 py-3" x-text="formatDate(file.created_at)"></td>
                    <td class="px-2 py-3 capitalize" x-text="file.location"></td>
                    <td class="px-2 py-3" x-text="file.total_beneficiary.toLocaleString()"></td>
                    <td class="px-2 py-3" x-text="file.total_amount.toLocaleString()"></td>
                    <td class="px-2 py-3 flex items-center justify-center space-x-2">
                        <!-- Edit Button -->
                        <div x-data="{ tooltip: false }" class="relative">
                            <button @click="updateFileLocation(file)"
                                    @mouseenter="tooltip = true" 
                                    @mouseleave="tooltip = false"
                                    class="p-2 text-yellow-400 hover:text-yellow-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                                    <path d="M21.28 6.4L11.74 15.94C10.79 16.89 7.97 17.33 7.34 16.7C6.71 16.07 7.14 13.25 8.09 12.3L17.64 2.75C18.84 1.55 21.02 3.71 19.82 4.91L21.28 6.4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11 4H6C4.94 4 3.92 4.42 3.17 5.17C2.42 5.92 2 6.94 2 8V18C2 19.06 2.42 20.08 3.17 20.83C3.92 21.58 4.94 22 6 22H17C19.21 22 20 20.2 20 18V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                Update
                            </span>
                        </div>

                        <!-- View List -->
                        <div x-data="{ tooltip: false }" class="relative">
                            <button @click="importedFilesTable = false; beneficiaryListTable = true; getFileDataPerFile(file.id); loading = true; file_name = file.file_name"
                                    @mouseenter="tooltip = true" 
                                    @mouseleave="tooltip = false"
                                    class="p-2 text-gray-600 hover:text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                            <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                View List
                            </span>
                        </div>

                        <!-- Delete Button -->
                        <div x-data="{ tooltip: false }" class="relative">
                            <button @click="deleteFile(file.id)"
                                    @mouseenter="tooltip = true" 
                                    @mouseleave="tooltip = false"
                                    class="p-2 text-gray-600 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M3 6h18M8 6V4h8v2M10 11v6M14 11v6M5 6l1 14h12l1-14" />
                                </svg>
                            </button>
                            <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                Delete File
                            </span>
                        </div>
                    </td>
                </tr>
            </template>
        </tbody>
        <tfoot class="bg-gray-100 dark:bg-gray-600">
            <tr>
                <td colspan="3" class="px-6 py-3 font-semibold">{{ __('TOTAL') }}</td>
                <td class="px-2 py-3" x-text="file_list.reduce((total, file) => total + file.total_beneficiary, 0).toLocaleString()"></td>
                <td class="px-2 py-3" x-text="file_list.reduce((total, file) => total + file.total_amount, 0).toLocaleString()"></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <!-- Pagination -->
    <div class="flex flex-wrap items-center justify-center mt-6 space-y-2 sm:space-y-0 sm:space-x-4">
        <div class="flex items-center space-x-2">
            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 disabled:bg-gray-300 disabled:cursor-not-allowed">
                &laquo; Previous
            </button>
            <span class="text-sm text-gray-600">
                Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
            </span>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 disabled:bg-gray-300 disabled:cursor-not-allowed">
                Next &raquo;
            </button>
        </div>

        <!-- Items per page dropdown -->
        <div class="flex items-center space-x-2">
            <label for="perPage" class="text-sm font-medium text-gray-700 dark:text-gray-300">Show</label>
            <div class="relative">
                <select id="perPage" x-model="perPage" @change="updateImportedPerPage(perPage)"
                        class="px-4 py-2 pr-8 text-sm font-medium text-gray-700 bg-white border rounded-md shadow-sm appearance-none focus:outline-none">
                    <option value="5" selected>5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <div class="absolute inset-y-0 right-2 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">entries</span>
        </div>
    </div>

    <!-- Update File Modal -->
    <div x-show="updateFileModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
            <header class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Location</h2>
                <button @click="updateFileModal = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </header>
            @include('import-files.update-file-modal')
        </div>
    </div>

    <!-- Delete File Modal -->
    <div x-show="deleteFileModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md shadow-lg">
            <header class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                <button @click="cancelFileDeletion()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </header>
            <p class="mt-4 text-gray-600 dark:text-gray-300">Are you sure you want to delete this file?</p>
            <div class="mt-6 flex justify-end space-x-4">
                <button @click="cancelFileDeletion()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md">Cancel</button>
                <button @click="confirmedFileDeletion()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">Confirm</button>
            </div>
        </div>
    </div>
</div>
