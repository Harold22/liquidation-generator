<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div x-data="sdo()" class="py-8">
        <div x-show="loading">
            <x-spinner />
        </div> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            @include('error-messages.messages')
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                <!-- User Registration Form -->
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-700 p-6 rounded-lg shadow self-start">
                    <h3 class="text-lg font-semibold mb-4 text-blue-500">Register New Special Disbursing Officer</h3>
                    <form method="POST" action="{{ route('sdo.store') }}">
                        @csrf
                        <!-- Firstname -->
                        <div x-init="$watch('form.firstname', () => validateField('firstname'))">
                            <x-input-label for="firstname">
                                {{ __('Firstname') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.firstname">
                                <span class="underline cursor-help" x-text="errors.firstname"
                                    :title="errors.firstname"></span>
                            </p>
                            <x-text-input id="firstname" name="firstname" type="text"
                                        class="mt-1 block w-full" required x-model="form.firstname"/>
                        </div>

                        <!-- Middlename -->
                         
                        <div class="mt-4" x-init="$watch('form.middlename', () => validateField('middlename'))">
                            <x-input-label for="middlename">
                                {{ __('Middlename') }} <span class="text-sm text-gray-400">(optional)</span>
                            </x-input-label>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.middlename">
                                <span class="underline cursor-help" x-text="errors.middlename"
                                    :title="errors.middlename"></span>
                            </p>
                            <x-text-input id="middlename" name="middlename" type="text"
                                        class="mt-1 block w-full"  x-model="form.middlename"/>
                        </div>
                        <!-- Lastname -->
                        <div class="mt-4" x-init="$watch('form.lastname', () => validateField('lastname'))">
                            <x-input-label for="lastname">
                                {{ __('Lastname') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.lastname">
                                <span class="underline cursor-help" x-text="errors.lastname"
                                    :title="errors.lastname"></span>
                            </p>
                            <x-text-input id="lastname" name="lastname" type="text"
                                        class="mt-1 block w-full" required x-model="form.lastname"/>
                        </div>

                        <!-- Extension Name -->
                        <div class="mt-4" x-init="$watch('form.extension_name', () => validateField('extension_name'))">
                            <div class="flex items-center">
                                <x-input-label for="extension_name" :value="__('Extension Name')" />
                                <span class="text-sm text-gray-400 ml-1">(optional)</span>
                            </div>

                            <!-- Error message -->
                            <p class="text-red-500 text-xs mt-1" x-show="errors.extension_name">
                                <span class="underline cursor-help" x-text="errors.extension_name" :title="errors.extension_name"></span>
                            </p>

                            <!-- Select -->
                            <select id="extension_name" name="extension_name"
                                x-model="form.extension_name"
                                class="block mt-1 w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring focus:ring-blue-300">
                                <option value="">-- Select Extension --</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                       <!-- Position -->
                        <div class="mt-4" x-init="$watch('form.position', () => validateField('position'))">
                            <div class="flex">
                                <x-input-label for="position">
                                    {{ __('Position') }} <span class="text-red-500">*</span>
                                </x-input-label>
                            </div>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.position">
                                <span class="underline cursor-help" x-text="errors.position" :title="errors.position"></span>
                            </p>
                            <x-text-input id="position" name="position" type="text"
                                x-model="form.position"
                                class="block mt-1 w-full" required />
                        </div>

                        <!-- Designation -->
                        <div class="mt-4" x-init="$watch('form.designation', () => validateField('designation'))">
                            <div class="flex">
                                <x-input-label for="designation">
                                    {{ __('Designation') }} <span class="text-red-500">*</span>
                                </x-input-label>
                            </div>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.designation">
                                <span class="underline cursor-help" x-text="errors.designation" :title="errors.designation"></span>
                            </p>
                            <x-text-input id="designation" name="designation" type="text"
                                x-model="form.designation"
                                class="block mt-1 w-full" required />
                        </div>

                        <!-- Station -->
                        <div class="mt-4" x-init="$watch('form.station', () => validateField('station'))">
                            <div class="flex">
                                <x-input-label for="station">
                                    {{ __('Station') }} <span class="text-red-500">*</span>
                                </x-input-label>
                            </div>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.station">
                                <span class="underline cursor-help" x-text="errors.station" :title="errors.station"></span>
                            </p>
                            <x-text-input id="station" name="station" type="text"
                                x-model="form.station"
                                class="block mt-1 w-full" required />
                        </div>

                        <!-- Status -->
                        <div hidden class="mt-4">
                            <div class="flex">
                                <x-input-label for="status"  />
                                <span class="text-red-500">*</span>
                            </div>
                            <x-text-input id="status" class="block mt-1 w-full" type="text" name="status" value="Active" required />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="w-full flex justify-center"
                                x-bind:disabled="Object.keys(errors).length > 0"
                                x-bind:class="Object.keys(errors).length > 0 ? 'opacity-50 cursor-not-allowed' : ''"
                                >
                                
                                {{ __('Register') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- User List -->
                <div class="w-full lg:w-2/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-blue-500">List of Special Disbursing Officer</h3>
                            <input 
                                type="text" 
                                placeholder="Search..." 
                                x-model="searchSdo"
                                @input.debounce.500ms="getSDO"
                                class="px-4 py-1.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100 w-48"
                            />
                        </div>

                        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
                            <thead class="bg-gray-100 dark:bg-gray-600">
                                <tr class="border">
                                    <th class="px-4 py-2">Name</th>
                                    <th class="px-4 py-2">Position</th>
                                    <th class="px-4 py-2">Designation</th>
                                    <th class="px-4 py-2">Station</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="sdo in sdos" :key="sdo.id">
                                    <tr class="border-b">
                                        <td class="px-4 py-2" x-text="`${sdo.firstname} ${sdo.middlename ?? ''} ${sdo.lastname} ${sdo.extension_name ?? ''}`.trim()"></td>
                                        <td class="px-4 py-2" x-text="sdo.position"></td>
                                        <td class="px-4 py-2" x-text="sdo.designation"></td>
                                        <td class="px-4 py-2" x-text="sdo.station"></td>
                                        <td class="px-4 py-2" x-text="sdo.status"></td>
                                        <td class="px-4 py-2">
                                            <div class="flex space-x-2">
                                                <div x-data="{ tooltipEdit: false, tooltipDelete: false, tooltipCdr: false}" class="relative inline-flex space-x-2 text-left">
                                                    <!-- Edit Button -->
                                                    <div class="relative">
                                                        <button  @click="$nextTick(() => window.open('{{ route('cdr', ['id' => ':id']) }}'.replace(':id', sdo.id), '_blank'))" 
                                                            @mouseenter="tooltipCdr = true" 
                                                            @mouseleave="tooltipCdr = false" 
                                                            class="py-2 pr-2 text-green-600 hover:text-green-700 focus:outline-none transition duration-200 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                                            </svg>
                                                        </button>
                                                        <span x-show="tooltipCdr"
                                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
                                                            x-transition.opacity>
                                                            Print CDR
                                                        </span>
                                                    </div>
                                                    <div class="relative">
                                                        <button @click="editSdo(sdo)"
                                                            @mouseenter="tooltipEdit = true" 
                                                            @mouseleave="tooltipEdit = false" 
                                                            class="py-2 pr-2 text-yellow-400 hover:text-yellow-600 focus:outline-none transition duration-200 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                            </svg>

                                                        </button>
                                                        <span x-show="tooltipEdit"
                                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
                                                            x-transition.opacity>
                                                            Update
                                                        </span>
                                                    </div>
                                                    <!-- Delete Button -->
                                                    <div class="relative">
                                                        <button @click="confirmDeleteSdo(sdo)"
                                                            @mouseenter="tooltipDelete = true" 
                                                            @mouseleave="tooltipDelete = false" 
                                                            class="p-2 text-gray-500 hover:text-red-600 focus:outline-none transition duration-200 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>

                                                        </button>
                                                        <span x-show="tooltipDelete"
                                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
                                                            x-transition.opacity>
                                                            Delete
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- modal for update -->
                        <div x-show="updateSdoModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                                <header class="flex justify-between items-center">
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Special Disbursing Officer</h2>
                                    <button @click="updateSdoModal = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                            <path d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </header>
                                @include('sdo.update-sdo')
                            </div>
                        </div>
                         <!-- Delete Confirmation Modal -->
                            <div x-show="deleteSdoModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
                                    <header class="flex justify-between items-center mb-4">
                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                                        <button @click="deleteSdoModal = false" 
                                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                <path d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </header>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                                        Are you sure you want to delete Special Disbursing Officer
                                        <strong x-text="`${sdoToDelete?.firstname} ${sdoToDelete?.middlename ?? ''} ${sdoToDelete?.lastname} ${sdoToDelete?.extension_name ?? ''}`.trim()"></strong>?
                                    </p>
                                    <div class="flex justify-end space-x-3">
                                        <button @click="deleteSdoModal = false" class="px-4 py-2 bg-gray-200 rounded text-sm">Cancel</button>
                                        <button @click="deleteSdoConfirmed" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">Delete</button>
                                    </div>
                                </div>
                            </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-center mt-6">
                            <div class="space-x-2">
                                <button @click="changePageSdo(sdoCurrentPage - 1)" :disabled="sdoCurrentPage === 1"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    &laquo; Prev
                                </button>
                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                    Page <span x-text="sdoCurrentPage"></span> of <span x-text="sdoTotalPages"></span>
                                </span>
                                <button @click="changePageSdo(sdoCurrentPage + 1)" :disabled="sdoCurrentPage === sdoTotalPages"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    Next &raquo;
                                </button>
                            </div>

                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm">Show</label>
                                <select x-model="perPageSdo" @change="updateSdoPerPage(perPageSdo)"
                                    class="px-6 py-2 text-sm rounded">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                                <span class="text-sm">entries</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>       
</x-app-layout>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('sdo', () => ({
        loading: false,
        sdos: [],
        sdoToDelete: null,
        sdoToReset: null,
        sdoToUpdate: [],
        sdoCurrentPage: 1,
        sdoTotalPages: 1,
        perPageSdo: 10,
        searchSdo: '',
        sortBy: 'created_at',        
        sortOrder: 'asc',      
        filterBy: '',          
        updateSdoModal: false,
        deleteSdoModal: false,

        init() {
            this.getSDO();
        },

        async getSDO(page = 1) {
            this.loading = true;
            try {
                let url = `/getSDO/index?page=${page}&perPage=${this.perPageSdo}&sortBy=${this.sortBy}&sortOrder=${this.sortOrder}&filterBy=${this.filterBy}`;

                if (this.searchSdo) {
                    url += `&search=${encodeURIComponent(this.searchSdo)}`;
                }

                const response = await axios.get(url);
                const data = response.data;

                this.sdos = data.data;
                this.sdoCurrentPage = data.current_page;
                this.sdoTotalPages = data.last_page;
            } catch (error) {
                console.error("Error fetching SDOs:", error);
            } finally {
                this.loading = false;
            }
        },

        changePageSdo(page) {
            if (page < 1 || page > this.sdoTotalPages) return;
            this.sdoCurrentPage = page;
            this.getSDO(page);
        },

        updateSdoPerPage(limit) {
            this.perPageSdo = limit;
            this.getSDO();
        },

        editSdo(sdo) {
            this.sdoToUpdate = sdo;
            this.updateSdoModal = true;
        },

        sdoToDelete: null,
        confirmDeleteSdo(sdo) {
            this.sdoToDelete = sdo;
            this.deleteSdoModal = true;
            
        },

        deleteSdoConfirmed() {
            if (!this.sdoToDelete) return;

            axios.delete(`/sdo/delete/${this.sdoToDelete.id}`)
                .then(response => {
                    alert('SDO deleted successfully!');
                    this.getSDO();
                })
                .catch(error => {
                    alert('Error Deletion of SDO');
                    console.error('Error deleting SDO:', error);
                })
                .finally(() => {
                    this.deleteSdoModal = false;
                    this.sdoToDelete = null;
                });
        },

        isAdmin(sdo) {
            return sdo.roles?.some(role => role.name === 'Admin');
        },

         form: {
            id: '', 
            firstname: '',
            middlename: '',
            lastname: '',
            extension_name: '',
            position: '',
            designation: '',
            station: '',
            status: 'Active',
        },
        errors: {},

        get hasErrors() {
            return Object.keys(this.errors).length > 0;
        },

        isValidString(value, pattern) {
            return pattern.test(value.trim());
        },

        validateField(field) {
            const val = this.form[field] ?? '';
            const namePattern = /^[A-Za-z\- ]+$/;
            const extensionPattern = /^[A-Za-z\-\. ]+$/;
            const maxLengths = {
                firstname: 100,
                middlename: 100,
                lastname: 100,
                extension_name: 20,
                position: 100,
                designation: 100,
                station: 100,
            };

            switch (field) {
                case 'firstname':
                case 'lastname':
                    if (!val.trim()) {
                        this.errors[field] = 'This field is required.';
                    } else if (!this.isValidString(val, namePattern)) {
                        this.errors[field] = 'Invalid characters.';
                    } else if (val.length > maxLengths[field]) {
                        this.errors[field] = `Must not exceed ${maxLengths[field]} characters.`;
                    } else {
                        delete this.errors[field];
                    }
                    break;

                case 'middlename':
                    if (val.trim() && !this.isValidString(val, namePattern)) {
                        this.errors[field] = 'Invalid characters.';
                    } else if (val.length > maxLengths[field]) {
                        this.errors[field] = `Must not exceed ${maxLengths[field]} characters.`;
                    } else {
                        delete this.errors[field];
                    }
                    break;

                case 'extension_name':
                    if (val.trim() && !this.isValidString(val, extensionPattern)) {
                        this.errors[field] = 'Invalid characters.';
                    } else if (val.length > maxLengths[field]) {
                        this.errors[field] = `Must not exceed ${maxLengths[field]} characters.`;
                    } else {
                        delete this.errors[field];
                    }
                    break;

                case 'position':
                case 'designation':
                case 'station':
                    if (!val.trim()) {
                        this.errors[field] = 'This field is required.';
                    } else if (val.length > maxLengths[field]) {
                        this.errors[field] = `Must not exceed ${maxLengths[field]} characters.`;
                    } else {
                        delete this.errors[field];
                    }
                    break;

                case 'status':
                    if (!val.trim()) {
                        this.errors.status = 'Status is required.';
                    } else if (!['Active', 'Inactive'].includes(val)) {
                        this.errors.status = 'Invalid status.';
                    } else {
                        delete this.errors.status;
                    }
                    break;
            }
        },

        validateAllFields() {
            ['firstname', 'middlename', 'lastname', 'extension_name', 'position', 'designation', 'station', 'status']
                .forEach(field => this.validateField(field));
        },

        submitForm() {
            this.validateAllFields();

            if (!this.hasErrors) {
                $el.submit(); // if using Alpine's `$el` reference
            }
        },

    }));
});
</script>




