<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Files') }}
        </h2>
    </x-slot>

    <div x-data="importFiles()" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Card Container -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <!-- Loading Indicator -->
                <div x-show="loading" class="mb-4">
                    <div class="h-2 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 animate-pulse rounded-full shadow-lg overflow-hidden"></div>
                </div>

                <!-- Error Messages -->
                @include('error-messages.messages')

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Section: Upload Form -->
                    <div class="col-span-1 space-y-6 mt-4">
                        <!-- Header for Left Section -->
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Upload File') }}
                        </h2>
                        <form method="POST" action="{{ route('files.upload') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <!-- SDO Dropdown -->
                            <div>
                                <x-input-label for="cash_advance" class="text-sm">
                                    {{ __('Select Special Disbursing Officer') }}<span class="text-red-500">*</span>
                                </x-input-label>
                                <select 
                                    id="cash_advance" 
                                    name="cash_advance" 
                                    x-model="selectedSdo" 
                                    @change="fetchCashAdvanceData(), getFileList(selectedSdo), getAllFile(selectedSdo), loading = true" 
                                    class="block w-full mt-1 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500"
                                    required>
                                    <option value="">{{ __('Select SDO') }}</option>
                                    <template x-for="sdo in sdo_list" :key="sdo.id">
                                        <option :value="sdo.id" x-text="sdo.special_disbursing_officer"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Cash Advance Details -->
                            <template x-if="cashAdvanceDetails">
                                <div class="space-y-2">
                                    <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
                                        <p class="text-sm font-semibold">{{ __('Cash Advance Date:') }}</p>
                                        <p class="text-lg text-green-600" x-text="formatDate(cashAdvanceDetails.date)"></p>
                                    </div>
                                    <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
                                        <p class="text-sm font-semibold">{{ __('Cash Advance Amount:') }}</p>
                                        <p class="text-lg text-green-600" x-text="'₱' + parseFloat(cashAdvanceDetails.amount).toLocaleString()"></p>
                                    </div>
                                </div>
                            </template>

                            <!-- File Upload -->
                            <div>
                                <x-input-label for="file" class="text-sm">
                                    {{ __('Select File to Import') }}<span class="text-red-500">*</span>
                                </x-input-label>
                                <input 
                                    id="file" 
                                    name="file" 
                                    type="file" 
                                    accept=".csv" 
                                    class="block w-full mt-2 p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md text-sm focus:ring-blue-500 dark:focus:ring-blue-600" 
                                    required>
                                <button 
                                    type="submit" 
                                    class="mt-4 w-full lg:w-auto inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    {{ __('Upload') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right Section: Imported Files -->
                    <div class="col-span-2 space-y-4 lg:mt-4">
                        <!-- Header for Right Section -->
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Imported Files') }}
                        </h2>
                        <div class="space-y-2 md:space-y-0 md:flex md:justify-between md:gap-4">
                            <!-- Left  -->
                            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow w-full md:w-1/2">
                                <p class="text-sm font-semibold">{{ __('Overall Total Imported Amount:') }}</p>
                                <p class="text-lg text-green-600" x-text="'₱' + (file_list_total?.overall_total_amount || 0).toLocaleString()"></p>
                            </div>

                            <!-- Right -->
                            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow w-full md:w-1/2 mt-2 md:mt-0">
                                <p class="text-sm font-semibold">{{ __('Overall Total Imported Beneficiaries:') }}</p>
                                <p class="text-lg text-green-600" x-text="(file_list_total?.overall_total_beneficiaries || 0).toLocaleString()"></p>
                            </div>
                        </div>


                        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
                            <thead class="bg-gray-100 dark:bg-gray-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3">{{ __('File Name') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Date Imported') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Beneficiaries') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Amount') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="file in file_list" :key="file.id">
                                    <tr class="border-t">
                                        <td class="px-6 py-4" x-text="file.file_name"></td>
                                        <td class="px-6 py-4" x-text="formatDate(file.created_at)"></td>
                                        <td class="px-6 py-4" x-text="file.total_beneficiary.toLocaleString()"></td>
                                        <td class="px-6 py-4" x-text="file.total_amount.toLocaleString()"></td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <butto @click="deleteFileModal = true; fileToDelete = file.id"
                                                class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                </svg>
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
                                                        <button @click="deleteFileModal = false; fileToDelete = null" type="button"
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
                                    <td colspan="2" class="px-6 py-3 font-semibold">{{ __('Overall Total') }}</td>
                                    <td class="px-6 py-3" x-text="file_list.reduce((total, file) => total + file.total_beneficiary, 0).toLocaleString()"></td>
                                    <td class="px-6 py-3" x-text="file_list.reduce((total, file) => total + file.total_amount, 0).toLocaleString()"></td>
                                    <td class="px-6 py-3"></td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function importFiles() {
            return {
                deleteFileModal: false,
                sdo_list: [],
                selectedSdo: null,
                cashAdvanceDetails: null,
                file_list: [],
                file_list_total: [],
                currentPage: 1,
                totalPages: 1,
                loading: true,

                async getFileList(selectedSdo, page = 1) {
                    if (!selectedSdo || selectedSdo === null || selectedSdo === '') {
                        this.file_list = []; 
                        this.currentPage = 1; 
                        this.totalPages = 1;  
                        this.loading = false;
                        return;
                    }
                    
                    try {
                        const response = await axios.get(`/files/show/${selectedSdo}/?page=${page}`);
                        this.file_list = [];
                        this.file_list = response.data.data;
                        this.currentPage = response.data.current_page;
                        this.totalPages = response.data.last_page;
                    } catch (error) {
                        console.error('Error fetching file list:', error);
                    }
                },

                fetchCashAdvanceData() {
                    if (this.selectedSdo) {
                        const selected = this.sdo_list.find(sdo => sdo.id == this.selectedSdo);
                        this.cashAdvanceDetails = selected
                            ? {
                                date: selected.cash_advance_date,
                                amount: selected.cash_advance_amount,
                            }
                            : null;
                    } else {
                        this.cashAdvanceDetails = null;
                    }
                },

                async getSdoList() {
                    try {
                        const response = await axios.get('/cash-advance/sdo');
                        this.sdo_list = response.data;
                        this.loading = false;
                    } catch (error) {
                        console.error('Error fetching SDO list:', error);
                    }
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', {
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric',
                    });
                },

                changePage(page) {
                    if (page < 1 || page > this.totalPages) return;
                    if (!this.selectedSdo) {
                        console.error('No SDO selected');
                        return;
                    }
                    this.getFileList(this.selectedSdo, page);
                },
                deleteFile(id) {
                    this.loading = true;

                    axios.post(`/files/delete/${id}`)
                        .then(response => {
                            this.file_list = this.file_list.filter(file => file.id !== id); 
                            alert('File deleted successfully!');    
                            this.loading = false;
                            this.deleteFileModal = false; 
                        })
                        .catch(error => {
                            this.loading = false;
                            console.error("Error deleting file:", error);
                            alert('Error deleting file!');
                        });
                },

                async getAllFile(selectedSdo)
                {
                    if (!selectedSdo || selectedSdo === null || selectedSdo === '') {
                        this.file_list_total = []; 
                        return;
                    }
                    try {
                        const response = await axios.get(`/files/getSdoTotal/${selectedSdo}`);
                        this.file_list_total = [];
                        this.file_list_total = response.data;
                        console.log('for:' + this.file_list_total);
                        this.loading = false;
                    } catch (error) {
                        this.loading = false;
                        console.error('Error fetching file list for total:', error);
                    }

                },

                init() {
                    this.getSdoList();
                },
            };
        }

    </script>
</x-app-layout>
