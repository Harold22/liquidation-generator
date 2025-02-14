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
                <div x-show="loading" x-transition.opacity class="fixed top-0 left-0 w-full h-2 bg-gray-900 bg-opacity-10 backdrop-blur-md z-50">
                    <div class="h-2 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 animate-pulse rounded-full shadow-lg"></div>
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
                                    @change="fetchCashAdvanceData(), getFileList(selectedSdo), getAllFile(selectedSdo), loading = true, importedFilesTable = true, beneficiaryListTable = false" 
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

                        <div x-show="importedFilesTable">
                            @include('import-files.imported-files-table')
                        </div>

                        <div class="space-y-4" x-show="beneficiaryListTable">
                            <div class="flex justify-between items-center">
                                <!-- Button -->
                                <button @click="fetchCashAdvanceData(), getFileList(selectedSdo), getAllFile(selectedSdo), loading = true, importedFilesTable = true, beneficiaryListTable = false"
                                    class="flex items-center justify-center gap-1 px-3 py-2 text-sm font-medium text-white bg-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Imported File List
                                </button>

                                <!-- Search Input & Button -->
                                <div class="flex items-center space-x-2">
                                    <input 
                                        type="text" 
                                        placeholder="Search..." 
                                        x-model="searchQuery"
                                        @input.debounce.500ms="getFileDataPerFile(fileId, 1)"
                                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100"
                                    />


                                </div>
                            </div>

                            @include('import-files.beneficiary-list-table')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function importFiles() {
            return {
                importedFilesTable: true,
                beneficiaryListTable: false,
                deleteFileModal: false,
                deleteBeneficiaryModal: false,
                sdo_list: [],
                selectedSdo: null,
                cashAdvanceDetails: null,
                file_list: [],
                file_list_total: [],
                currentPage: 1,
                totalPages: 1,
                loading: true,
                beneficiaryList: [],
                beneCurrentPage: 1,
                beneTotalPages: 1,
                fileId: null,
                searchQuery: null,

                async getFileDataPerFile(fileId, page_bene = 1) {
                    this.fileId = fileId;
                    if (!this.fileId || this.fileId.length === 0) {
                        console.log('No file IDs to fetch data for.');
                        this.beneficiaryList = [];
                        this.beneCurrentPage = 1; 
                        this.beneTotalPages = 1;     
                        this.loading = false;
                        return;
                    }

                    this.loading = true; 
                    try {
                        const params = { page: page_bene };
                        if (this.searchQuery && this.searchQuery.trim() !== '') {
                            params.search = this.searchQuery;
                        }

                        const response = await axios.get(`/files/list/${this.fileId}`, { params });

                        this.beneficiaryList = response.data.data;
                        this.beneCurrentPage = response.data.current_page;
                        this.beneTotalPages = response.data.last_page;
                    } catch (error) {
                        console.error('Error fetching file data:', error);
                    } finally {
                        this.loading = false; 
                    }
                },


                async getFileList(selectedSdo, page = 1) {
                    if (!selectedSdo || selectedSdo === null || selectedSdo === '') {
                        this.file_list = []; 
                        this.currentPage = 1; 
                        this.totalPages = 1;  
                        this.loading = false;
                        return;
                    }
                    
                    try {
                        const response = await axios.get(`/files/index/${selectedSdo}/?page=${page}`);
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

                changePageBene(page_bene) {
                    if (page_bene < 1 || page_bene > this.beneTotalPages) return;
                    if (!this.fileId) 
                        return;
                    this.getFileDataPerFile(this.fileId, page_bene); 
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
                        this.loading = false;
                    } catch (error) {
                        this.loading = false;
                        console.error('Error fetching file list for total:', error);
                    }

                },

                deleteBeneficiary(id){
                    this.loading = true;
                    axios.post(`/data/delete/${id}`)
                    .then(response => {
                        this.beneficiaryList = this.beneficiaryList.filter(beneficiary => beneficiary.id !== id); 
                        alert('Beneficiary deleted successfully!');  
                        this.loading = false;
                        this.deleteBeneficiaryModal = false; 
                    })
                    .catch(error => {
                        this.loading = false;
                        console.error("Error deleting bene:", error);
                        alert('Error deleting file!');
                    })
                    .finally(() => {
                        this.loading = false;
                        this.getAllFile(this.selectedSdo); 
                    });
                },

                updateBeneficiaryModal: false,
                selectedBeneficiary: [],
                updateBeneficiaryData(beneficiary){
                    this.selectedBeneficiary = { ...beneficiary };
                    this.updateBeneficiaryModal = true;

                },

                init() {
                    this.getSdoList();
                },
            };
        }

    </script>
</x-app-layout>
