<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Offices') }}
        </h2>
    </x-slot>

    <div x-data="offices()" class="py-8">
        <div x-show="loading">
            <x-spinner />
        </div> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            @include('error-messages.messages')
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                <!-- User Registration Form -->
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-700 p-6 rounded-lg shadow self-start">
                    <h3 class="text-lg font-semibold mb-4 text-blue-500">Register New Office</h3>
                    <form method="POST" action="{{ route('office.store') }}">
                        @csrf
                        <div>
                            <div class="flex">
                                <x-input-label for="office_name" :value="__('Office Name')" />
                                <span class="text-red-500">*</span>
                            </div>
                            <x-text-input id="office_name" class="block mt-1 w-full" type="text" name="office_name" :value="old('office_name')" required autofocus />
                        </div>

                        <div class="mt-4">
                            <div class="flex">
                                <x-input-label for="office_location" :value="__('Office Location')" />
                                <span class="text-red-500">*</span>
                            </div>
                            <x-text-input id="office_location" class="block mt-1 w-full" type="text" name="office_location" :value="old('office_location')" required />
                        </div>
                        <div class="mt-4">
                            <div class="flex">
                                <x-input-label for="swado" :value="__('Swado / Team Leader')" />
                                <span class="text-red-500">*</span>
                            </div>
                            <x-text-input id="swado" class="block mt-1 w-full" type="text" name="swado" :value="old('swado')" required />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="w-full flex justify-center">
                                {{ __('Register') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- User List -->
                <div class="w-full lg:w-2/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-blue-500">List of Offices</h3>
                            <input 
                                type="text" 
                                placeholder="Search..." 
                                x-model="searchOffice"
                                @input.debounce.500ms="getOffices"
                                class="px-4 py-1.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100 w-48"
                            />
                        </div>

                        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
                            <thead class="bg-gray-100 dark:bg-gray-600">
                                <tr class="border">
                                    <th class="px-4 py-2">Office Name</th>
                                    <th class="px-4 py-2">Office Location</th>
                                    <th class="px-4 py-2">Swado / Team Leader</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                 <template x-if="offices.length === 0">
                                    <tr>
                                        <td colspan="999" class="text-center text-red-500 py-4">
                                            No records found.
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="office in offices" :key="office.id">
                                    <tr class="border-b capitalize">
                                        <td class="px-4 py-2 capitalize" x-text="office.office_name"></td>
                                        <td class="px-4 py-2" x-text="office.office_location"></td>
                                        <td class="px-4 py-2" x-text="office.swado"></td>
                                        <td class="px-4 py-2">
                                            <div class="flex justify-center space-x-2">
                                                <div x-data="{ tooltipEdit: false, tooltipDelete: false }" class="relative flex space-x-2">
                                                    
                                                    <!-- Edit Button -->
                                                    <div class="relative">
                                                        <button 
                                                            @click="editOffice(office)"
                                                            @mouseenter="tooltipEdit = true" 
                                                            @mouseleave="tooltipEdit = false" 
                                                            class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-yellow-100 hover:text-yellow-600 transition duration-200 ease-in-out focus:outline-none"
                                                            aria-label="Edit Office"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                                            </svg>
                                                        </button>
                                                        <span x-show="tooltipEdit" x-transition.opacity class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap z-50">
                                                            Update
                                                        </span>
                                                    </div>

                                                    <!-- Delete Button -->
                                                    <div class="relative">
                                                        <button 
                                                            @click="confirmDeleteOffice(office)"
                                                            @mouseenter="tooltipDelete = true" 
                                                            @mouseleave="tooltipDelete = false" 
                                                            class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-red-100 hover:text-red-600 transition duration-200 ease-in-out focus:outline-none"
                                                            aria-label="Delete Office"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                        <span x-show="tooltipDelete" x-transition.opacity class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap z-50">
                                                            Delete
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>


                                    </tr>
                                </template>
                            </tbody>
                            <!-- modal for update -->
                             <div x-show="updateOfficeModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                                    <header class="flex justify-between items-center">
                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Office</h2>
                                        <button @click="updateOfficeModal = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                <path d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </header>
                             @include('offices.update-office-form')
                                </div>
                            </div>

                              <!-- Delete Confirmation Modal -->
                            <div x-show="deleteOfficeModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
                                    <header class="flex justify-between items-center mb-4">
                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                                        <button @click="deleteOfficeModal = false" 
                                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                <path d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </header>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                                        Are you sure you want to delete Office <strong x-text="officeToDelete?.office_name"></strong>?
                                    </p>
                                    <div class="flex justify-end space-x-3">
                                        <button @click="deleteOfficeModal = false" class="px-4 py-2 bg-gray-200 rounded text-sm">Cancel</button>
                                        <button @click="deleteConfirmed" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </table>

                        <!-- Pagination -->
                        <div class="flex items-center justify-center mt-6">
                            <div class="space-x-2">
                                <button @click="changePageOffice(officeCurrentPage - 1)" :disabled="officeCurrentPage === 1"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    &laquo; Prev
                                </button>
                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                    Page <span x-text="officeCurrentPage"></span> of <span x-text="officeTotalPages"></span>
                                </span>
                                <button @click="changePageOffice(officeCurrentPage + 1)" :disabled="officeCurrentPage === officeTotalPages"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    Next &raquo;
                                </button>
                            </div>

                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm">Show</label>
                                <select x-model="perPageOffice" @change="updateOfficePerPage(perPageOffice)"
                                    class="px-6 py-2 text-sm rounded">
                                    <option value="5">5</option>
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
    Alpine.data('offices', () => ({
        loading: false,
        offices: [],
        officeCurrentPage: 1,
        officeTotalPages: 1,
        perPageOffice: 5,
        searchOffice: '',

        init() {
            this.getOffices();
        },

        async getOffices() {
            this.loading = true;
            try {
                const response = await axios.get('/getOffices', {
                    params: {
                        page: this.officeCurrentPage,
                        perPage: this.perPageOffice,
                        search: this.searchOffice,
                    }
                });
                this.offices = response.data.data;
                this.officeCurrentPage = response.data.current_page;
                this.officeTotalPages = response.data.last_page;
            } catch (error) {
                console.error('Error fetching offices:', error);
            } finally {
                this.loading = false;
            }
        },

        changePageOffice(page) {
            if (page < 1 || page > this.officeTotalPages) return;
            this.officeCurrentPage = page;
            this.getOffices();
        },

        updateOfficePerPage(perPage) {
            this.perPageOffice = perPage;
            this.officeCurrentPage = 1;
            this.getOffices();
        },

        updateOfficeModal: false,
        selectedOffice: {},
        editOffice(office) {
            this.selectedOffice = {
                id: office.id,
                office_name: office.office_name,
                office_location: office.office_location,
                swado: office.swado
            };
            this.updateOfficeModal = true;
        },

        deleteOfficeModal: false,
        officeToDelete: null,
        confirmDeleteOffice(office) {
            this.officeToDelete = office;
            this.deleteOfficeModal = true;
        },

        deleteConfirmed() {
            if (!this.officeToDelete) return;

            axios.delete(`/offices/delete/${this.officeToDelete.id}`)
                .then(() => {
                    alert('Office deleted successfully!');
                    this.getOffices();
                })
                .catch(error => {
                    alert('Error deleting office.');
                    console.error(error);
                })
                .finally(() => {
                    this.deleteOfficeModal = false;
                    this.officeToDelete = null;
                });
        },
    }));
});
</script>



