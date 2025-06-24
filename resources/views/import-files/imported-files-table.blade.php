<div class="w-full overflow-x-auto">
    <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
        <thead class="bg-gray-100 dark:bg-gray-600">
            <tr class="border">
                <th class="px-2 py-2">{{ __('File Name') }}</th>
                <th class="px-2 py-2">{{ __('Date Imported') }}</th>
                <th class="px-2 py-2">{{ __('Location') }}</th>
                <th class="px-2 py-2">{{ __('Beneficiaries') }}</th>
                <th class="px-2 py-2">{{ __('Amount') }}</th>
                <th class="px-2 py-2">{{ __('') }}</th>
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
                    <td class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-100">
                        <div class="flex items-center gap-3">

                            <!-- Update File Location -->
                            <div x-data="{ tooltip: false }" class="relative">
                                <button @click="updateFileLocation(file)"
                                    @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                    class="flex items-center justify-center w-8 h-8 hover:text-yellow-600 hover:bg-yellow-100 dark:hover:bg-yellow-900 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                </button>
                                <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                    Update
                                </span>
                            </div>

                            <!-- View List -->
                            <div x-data="{ tooltip: false }" class="relative">
                                <button @click="importedFilesTable = false; beneficiaryListTable = true; getFileDataPerFile(file.id); loading = true; file_name = file.file_name"
                                    @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                    class="flex items-center justify-center w-8 h-8 hover:text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </button>
                                <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                    View List
                                </span>
                            </div>

                            <!-- Delete File -->
                            <div x-data="{ tooltip: false }" class="relative">
                                <button @click="deleteFile(file.id)"
                                    @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                    class="flex items-center justify-center w-8 h-8 hover:text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                                <span x-show="tooltip" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md" x-transition.opacity>
                                    Delete File
                                </span>
                            </div>
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
                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" >
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
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
