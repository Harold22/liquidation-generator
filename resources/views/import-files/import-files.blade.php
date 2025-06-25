<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Files') }}
        </h2>
    </x-slot>

    <div x-data="importFiles()" class="py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Card Container -->
            <div class="dark:bg-gray-800  sm:rounded-lg">
                <!-- Loading Indicator -->
                <div x-show="loading">
                    <x-spinner />
                </div>
                <!-- Error Messages -->
                @include('error-messages.messages')

                <!-- Separated Layout -->
                <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                    <!-- Left Section: Upload Form -->
                    <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 space-y-6 self-start">
                        <h2 class="text-xl font-semibold text-blue-500 dark:text-gray-200">
                            {{ __('Upload File') }}
                        </h2>
                        <form method="POST" action="{{ route('files.upload') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <!-- SDO Dropdown -->
                            <div>
                                <x-input-label for="cash_advance" class="text-sm">
                                    {{ __('Special Disbursing Officer') }}<span class="text-red-500">*</span>
                                </x-input-label>
                                <select 
                                    id="cash_advance_allocation_id" 
                                    name="cash_advance_allocation_id" 
                                    x-model="selectedSdo" 
                                    @change="handleSdoChange()"
                                    class="uppercase block w-full mt-1 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500"
                                    required>
                                    <option value="">{{ __('Select SDO') }}</option>
                                    <template x-for="sdo in sdo_list" :key="sdo.id">
                                        <option :value="sdo.id" x-text="sdo.sdo_name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Cash Advance Details -->
                            <div class="space-y-2">
                                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
                                    <p class="text-sm font-semibold">{{ __('Cash Advance Date:') }}</p>
                                    <p class="text-lg text-green-600" x-text="cashAdvanceDetails ? formatDate(cashAdvanceDetails.date) : 'mm/dd/yyyy'"></p>
                                </div>
                                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
                                    <p class="text-sm font-semibold">{{ __('Allocated Amount:') }}</p>
                                    <p class="text-lg text-green-600" 
                                        x-text="cashAdvanceDetails ? '₱' + parseFloat(cashAdvanceDetails.amount).toLocaleString() : '₱0'">
                                    </p>
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <x-input-label for="location" class="text-sm">
                                    {{ __('Location') }}<span class="text-red-500">*</span>
                                </x-input-label>
                                <select 
                                    id="location" 
                                    name="location" 
                                    class="block w-full mt-1 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500"
                                    required>
                                    <option value="">{{ __('Select Location') }}</option>
                                    <option value="onsite">Onsite</option>
                                    <option value="offsite">Offsite</option>
                                </select>
                            </div>

                            <!-- File Upload -->
                            <div>
                                <x-input-label for="file" class="text-sm">
                                    {{ __('File to Import') }}<span class="text-red-500">*</span>
                                </x-input-label>
                                <input 
                                    id="file" 
                                    name="file" 
                                    type="file" 
                                    accept=".csv" 
                                    class="block w-full mt-2 p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md text-sm focus:ring-blue-500 dark:focus:ring-blue-600" 
                                    required>
                                <x-primary-button class="mt-4 w-full flex justify-center">{{ __('Upload') }}</x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Right Section: Imported Files -->
                    <div class="w-full lg:w-2/3 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 space-y-4">
                        <h2 class="text-xl font-semibold text-blue-500 dark:text-gray-200">
                            {{ __('Imported Files') }}
                        </h2>

                        <!-- Totals -->
                        <div class="space-y-2 md:space-y-0 md:flex md:justify-between md:gap-4">
                            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow w-full md:w-1/2">
                                <p class="text-sm font-semibold">{{ __('Overall Total Imported Amount:') }}</p>
                                <p class="text-lg text-green-600" x-text="'₱' + (file_list_total?.overall_total_amount || 0).toLocaleString()"></p>
                            </div>
                            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow w-full md:w-1/2 mt-2 md:mt-0">
                                <p class="text-sm font-semibold">{{ __('Overall Total Imported Beneficiaries:') }}</p>
                                <p class="text-lg text-green-600" x-text="(file_list_total?.overall_total_beneficiaries || 0).toLocaleString()"></p>
                            </div>
                        </div> 

                        <!-- File List Table -->
                        <div class="space-y-4" x-show="importedFilesTable">
                            <div class="flex justify-between items-center">
                                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">List of Files</h2>
                                <input 
                                    type="text" 
                                    placeholder="Search..." 
                                    x-model="searchFile"
                                    @input.debounce.500ms="getFileList(selectedSdo, 1)"
                                    class="px-4 py-1.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100"
                                />
                            </div>
                            @include('import-files.imported-files-table')
                        </div>

                        <!-- Beneficiary List Table -->
                        <div class="space-y-4" x-show="beneficiaryListTable">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">List of Beneficiaries</h2>
                                    <span class="text-gray-600 italic text-sm" x-text="'File: ' + file_name"></span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
                                    <button @click="handleClick"
                                        class="flex items-center justify-center gap-2 px-4 py-1.5 text-sm font-semibold text-gray-700 border border-gray-400 rounded-lg shadow-sm 
                                            hover:bg-blue-100 hover:border-blue-500 hover:text-blue-600 hover:shadow-md 
                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                                            transition-all duration-200 ease-in-out active:scale-95 w-full sm:w-auto">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span>File List</span>
                                    </button>
                                    <input 
                                        type="text" 
                                        placeholder="Search..." 
                                        x-model="searchQuery"
                                        @input.debounce.500ms="getFileDataPerFile(fileId, 1)"
                                        class="px-4 py-1.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 
                                            dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100 w-full sm:w-auto"
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
</x-app-layout>


<script>
         document.addEventListener('alpine:init', () => {
            Alpine.data('importFiles', () => ({
                officeId: "{{ Auth::user()->office_id }}",
                importedFilesTable: true, beneficiaryListTable: false, deleteFileModal: false,
                deleteBeneficiaryModal: false, updateFileModal: false,
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
                searchFile: null,
                perPage: 5,
                perPageBene: 5,
                file_name: null,

                init() {
                    this.getSdoList();
                },

                async handleSdoChange() {
                    if (!this.selectedSdo) {
                        this.cashAdvanceDetails = null;
                        this.file_list = [];
                        this.file_list_total = [];
                        this.importedFilesTable = true;
                        this.beneficiaryListTable = false;
                    } else {
                        this.loading = true;
                        this.importedFilesTable = true;
                        this.beneficiaryListTable = false;

                        await Promise.all([
                            this.fetchCashAdvanceData(),
                            this.getFileList(this.selectedSdo),
                            this.getAllFile(this.selectedSdo)
                        ]);

                        this.loading = false;
                    }
                },

                async handleClick() {
                    this.loading = true;
                    this.importedFilesTable = true;
                    this.beneficiaryListTable = false;

                    await Promise.all([
                        this.fetchCashAdvanceData(),
                        this.getFileList(this.selectedSdo),
                        this.getAllFile(this.selectedSdo)
                    ]);

                    this.loading = false;
                },
                async getSdoList() {
                    try {
                        const response = await axios.get(`/allocated/sdo/${this.officeId}`);
                        this.sdo_list = response.data;
                        this.loading = false;
                    } catch (error) {
                        console.error('Error fetching SDO list:', error);
                    } finally {
                        this.loading = false; 
                    }
                },

                async getFileDataPerFile(fileId, page_bene = 1, perPageBene = this.perPageBene) {
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
                        const params = { page: page_bene, perPageBene: perPageBene };

                        if (this.searchQuery?.trim()) {
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

                async getFileList(selectedSdo, page = 1, perPage = this.perPage) {
                    if (!selectedSdo || selectedSdo === null || selectedSdo === '') {
                        this.file_list = []; 
                        this.currentPage = 1; 
                        this.totalPages = 1;  
                        return;
                    }
                    this.loading = true;
                    
                    try {
                        const params = { page: page, perPage: perPage };

                        if (this.searchFile?.trim()) {
                            params.search = this.searchFile;
                        }

                        const response = await axios.get(`/files/index/${this.selectedSdo}`, { params });

                        this.file_list = response.data.data;
                        this.currentPage = response.data.current_page;
                        this.totalPages = response.data.last_page;
                    } catch (error) {
                        console.error('Error fetching file list:', error);
                    } finally {
                        this.loading = false; 
                    }
                },


                fetchCashAdvanceData() {
                    if (this.selectedSdo) {
                        const selected = this.sdo_list.find(sdo => sdo.id == this.selectedSdo);
                        this.cashAdvanceDetails = selected
                            ? {
                                date: selected.cash_advance_date,
                                amount: selected.amount,
                            }
                            : null;
                    } else {
                        this.cashAdvanceDetails = null;
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

                updateImportedPerPage(value){
                    this.perPage = parseInt(value);
                    this.currentPage = 1; 
                    this.getFileList(this.selectedSdo, 1, this.perPage); 

                },

                changePageBene(page_bene) {
                    if (page_bene < 1 || page_bene > this.beneTotalPages) return;
                    if (!this.fileId) 
                        return;
                    this.getFileDataPerFile(this.fileId, page_bene); 
                },

                updateBenePerPage(value){
                    this.perPageBene = parseInt(value);
                    this.beneCurrentPage = 1; 
                    this.getFileDataPerFile(this.fileId, 1, this.perPageBene); 
                },

                deletion_file_id: null,
                deleteFile(id) {
                    if (!id) return;
                    this.deletion_file_id = id;
                    this.deleteFileModal = true;
                },
                confirmedFileDeletion() {
                    axios.post(`/files/delete/${this.deletion_file_id}`)
                        .then(response => {
                            this.file_list = this.file_list.filter(file => file.id !== this.deletion_file_id);
                            alert('File deleted successfully!');
                            this.getAllFile(this.selectedSdo);
                            this.deleteFileModal = false;
                            this.deletion_file_id = null;
                        })
                        .catch(error => {
                            console.error("Error deleting file:", error);
                            alert('Error deleting file!');
                        });
                },
                cancelFileDeletion(){
                    this.deletion_file_id = null;
                    this.deleteFileModal = false;
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
                    } catch (error) {
                        console.error('Error fetching file list for total:', error);
                    } 
                },

                deletion_bene_id: null,
                selectedBeneToDelete: [],
                deleteBene(beneficiary) {
                    this.selectedBeneToDelete = { ...beneficiary };
                    if (!beneficiary.id) return;
                    this.deletion_bene_id = beneficiary.id;
                    this.deleteBeneficiaryModal = true;
                },
                cancelBeneDeletion(){
                    this.deletion_bene_id = null;
                    this.deleteBeneficiaryModal = false;
                },

                confirmedBeneDeletion(){
                    axios.post(`/data/delete/${this.deletion_bene_id}`)
                    .then(response => {
                        this.beneficiaryList = this.beneficiaryList.filter(beneficiary => beneficiary.id !== this.deletion_bene_id); 
                        alert('Beneficiary deleted successfully!');  
                        this.getAllFile(this.selectedSdo); 
                    })
                    .catch(error => {
                        this.loading = false;
                        console.error("Error deleting bene:", error);
                        alert('Error deleting file!');
                    })
                    .finally(() => {
                        this.loading = false;
                        this.deleteBeneficiaryModal = false; 
                     
                    });
                },

                updateBeneficiaryModal: false,
                selectedBeneficiary: [],
                updateBeneficiaryData(beneficiary){
                    this.selectedBeneficiary = { ...beneficiary };
                    this.updateBeneficiaryModal = true;
                   
                },

                selectedFile: [],
                updateFileLocation(file){
                    this.selectedFile = { ...file };
                    this.updateFileModal = true;

                },

                form: {
                    firstname: '',
                    middlename: '',
                    lastname: '',
                    extension_name: '',
                    assistance_type: '',
                    amount: '',
                },
                errors: {},

                isValidString(value, pattern) {
                    return pattern.test(value);
                },

                validateField(field) {
                    const val = this.form[field];
                    const namePattern = /^[A-Za-zÑñ\s\-.]+$/;
                    const codePattern =  /^[A-Za-z0-9\-\.\/\s]+$/;
                    const today = new Date().toISOString().split('T')[0];
                    const maxAmount = 10000;

                    switch (field) {
                        case 'firstname':
                        case 'middlename':
                        case 'lastname':
                        case 'extension_name':
                            if (!val) {
                                delete this.errors[field];
                                break;
                            }
                            if (!this.isValidString(val, namePattern)) {
                                this.errors[field] = 'Invalid characters used.';
                            } else if (val.length > 255) {
                                this.errors[field] = 'Must not exceed 255 characters.';
                            } else {
                                delete this.errors[field];
                            }
                            break;

                        case 'amount':
                            if (!val) {
                                delete this.errors.amount;
                                break;
                            }
                            if (isNaN(val)) {
                                this.errors.amount = 'Must be a valid number.';
                            } else if (+val < 0.01) {
                                this.errors.amount = 'Minimum is ₱0.01.';
                            } else if (+val > maxAmount) {
                                this.errors.amount = `Maximum is ₱${maxAmount.toLocaleString()}.`;
                            } else {
                                delete this.errors.amount;
                            }
                            break;

                        case 'assistance_type':
                            if (!val) {
                                delete this.errors.assistance_type;
                                break;
                            }
                            if (!this.isValidString(val, codePattern)) {
                                this.errors.assistance_type = 'Invalid characters used.';
                            } else if (val.length > 255) {
                                this.errors.assistance_type = 'Must not exceed 255 characters.';
                            } else {
                                delete this.errors.assistance_type;
                            }
                            break;
                    }
                },

                validateForm() {
                    this.errors = {};
                    for (const field in this.form) {
                        this.validateField(field);
                    }
                    return Object.keys(this.errors).length === 0;
                },
                
            }));
        });

    </script>
