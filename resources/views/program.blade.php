<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Programs') }}
        </h2>
    </x-slot>
   @include('error-messages.messages')
    <div x-data="program()" class="py-8">
        <div x-show="loading">
            <x-spinner />
        </div> 
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6"> 
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                <!-- User Registration Form -->
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-700 p-6 rounded-lg shadow self-start">
                    <h3 class="text-lg font-semibold mb-2 text-blue-500">Register New Program</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                        Fill out the form below to register a new program in the system.
                    </p>
                    <form method="POST" action="{{ route('program.store') }}">
                        @csrf

                        <!-- Program Name -->
                        <div x-init="$watch('form.program_name', () => validateField('program_name'))">
                            <x-input-label for="program_name">
                                {{ __('Program Name') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.program_name">
                                <span class="underline cursor-help" x-text="errors.program_name" :title="errors.program_name"></span>
                            </p>
                            <x-text-input id="program_name" name="program_name" type="text"
                                class="mt-1 block w-full" required x-model="form.program_name" />
                        </div>

                        <!-- Program Abbreviation -->
                        <div class="mt-4" x-init="$watch('form.program_abbreviation', () => validateField('program_abbreviation'))">
                            <x-input-label for="program_abbreviation">
                                {{ __('Program Abbreviation') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.program_abbreviation">
                                <span class="underline cursor-help" x-text="errors.program_abbreviation" :title="errors.program_abbreviation"></span>
                            </p>
                            <x-text-input id="program_abbreviation" name="program_abbreviation" type="text"
                                class="mt-1 block w-full" required x-model="form.program_abbreviation" />
                        </div>

                        <!-- Origin Office -->
                        <div class="mt-4" x-init="$watch('form.origin_office', () => validateField('origin_office'))">
                            <x-input-label for="origin_office">
                                {{ __('Origin Office') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.origin_office">
                                <span class="underline cursor-help" x-text="errors.origin_office" :title="errors.origin_office"></span>
                            </p>
                            <x-text-input id="origin_office" name="origin_office" type="text"
                                class="mt-1 block w-full" required x-model="form.origin_office" />
                        </div>

                        <!-- Status (hidden default) -->
                        <div class="mt-4" hidden>
                            <x-input-label for="status">{{ __('Status') }}</x-input-label>
                            <x-text-input id="status" name="status" type="text" value="Active" x-model="form.status" />
                        </div>

                        <!-- Submit Button -->
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
                            <h3 class="text-xl font-bold text-blue-500">List of Programs </h3>
                            <input 
                                type="text" 
                                placeholder="Search..." 
                                x-model="searchProgram"
                                @input.debounce.500ms="getPrograms"
                                class="px-4 py-1.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100 w-48"
                            />
                        </div>
                        <div class="w-full overflow-x-auto py-2">
                            <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
                                <thead class="bg-gray-100 dark:bg-gray-600">
                                    <tr class="border">
                                        <th class="px-4 py-2">Program Name</th>
                                        <th class="px-4 py-2">Program Abbreviation</th>
                                        <th class="px-4 py-2">Origin Office</th>
                                        <th class="px-4 py-2">Status</th>
                                        <th class="px-4 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-if="programs.length === 0">
                                        <tr>
                                            <td colspan="999" class="text-center text-red-500 py-4">
                                                No programs found.
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="program in programs" :key="program.id">
                                        <tr class="border-b">
                                            <td class="px-4 py-2 capitalize" x-text="program.program_name"></td>
                                            <td class="px-4 py-2" x-text="program.program_abbreviation"></td>
                                            <td class="px-4 py-2" x-text="program.origin_office"></td>
                                            <td 
                                                class="px-4 py-2 font-medium"
                                                :class="program.status === 'Active' 
                                                    ? 'text-green-500' 
                                                    : 'text-red-500'"
                                                x-text="program.status">
                                            </td>

                                            <td class="px-4 py-2">
                                                <div class="flex items-center gap-3" x-data="{ tooltipEdit: false, tooltipReset: false, tooltipDelete: false }">
                                                    
                                                    <!-- Edit Button -->
                                                    <div class="relative">
                                                        <button @click="editProgram(program)"
                                                            @mouseenter="tooltipEdit = true" 
                                                            @mouseleave="tooltipEdit = false"
                                                            class="flex items-center justify-center w-8 h-8 hover:text-yellow-600 hover:bg-yellow-100 dark:hover:bg-yellow-900 rounded-full transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                                            </svg>
                                                        </button>
                                                        <span x-show="tooltipEdit"
                                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md"
                                                            x-transition.opacity>
                                                            Update
                                                        </span>
                                                    </div>
                                                    <!-- Delete Button -->
                                                    <div class="relative">
                                                        <button @click="confirmDeleteProgram(program)"
                                                            @mouseenter="tooltipDelete = true" 
                                                            @mouseleave="tooltipDelete = false"
                                                            class="flex items-center justify-center w-8 h-8 hover:text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-full transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                        <span x-show="tooltipDelete"
                                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md"
                                                            x-transition.opacity>
                                                            Delete
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>


                                        </tr>
                                    </template>
                                </tbody>
                                <!-- modal for update -->
                                <div x-show="updateProgramModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                                        <header class="flex justify-between items-center">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Program Details</h2>
                                            <button @click="updateProgramModal = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </header>
                                        @include('programs.update-program-form')
                                    </div>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div x-show="deleteProgramModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
                                        <header class="flex justify-between items-center mb-4">
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                                            <button @click="deleteProgramModal = false" 
                                                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </header>
                                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                                            Are you sure you want to delete Program <strong x-text="programToDelete?.program_name"></strong>?
                                        </p>
                                        <div class="flex justify-end space-x-3">
                                            <button @click="deleteProgramModal = false" class="px-4 py-2 bg-gray-200 rounded text-sm">Cancel</button>
                                            <button @click="deleteProgramConfirmed" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-center mt-6">
                            <div class="space-x-2">
                                <button @click="changePageProgram(programCurrentPage - 1)" :disabled="programCurrentPage === 1"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    &laquo; Prev
                                </button>
                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                    Page <span x-text="programCurrentPage"></span> of <span x-text="programTotalPages"></span>
                                </span>
                                <button @click="changePageProgram(programCurrentPage + 1)" :disabled="programCurrentPage === programTotalPages"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    Next &raquo;
                                </button>
                            </div>

                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm">Show</label>
                                <select x-model="perPageProgram" @change="updateProgramPerPage(perPageProgram)"
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
    Alpine.data('program', () => ({
        loading: false,
        programs: [],
        programCurrentPage: 1,
        programTotalPages: 1,
        perPageProgram: 5,
        searchProgram: '',

        init() {
            this.getPrograms();
        },

        async getPrograms() {
            this.loading = true;
            try {
                const response = await axios.get('/program/index', {
                    params: {
                        page: this.programCurrentPage,
                        perPage: this.perPageProgram,
                        search: this.searchProgram,
                    }
                });

                this.programs = response.data.data;
                this.programCurrentPage = response.data.current_page;
                this.programTotalPages = response.data.last_page;
            } catch (error) {
                console.error('Error fetching programs:', error);
            } finally {
                this.loading = false;
            }
        },

        changePageProgram(page) {
            if (page < 1 || page > this.programTotalPages) return;
            this.programCurrentPage = page;
            this.getPrograms();
        },

        updateProgramPerPage(perPage) {
            this.perPageProgram = perPage;
            this.programCurrentPage = 1;
            this.getPrograms();
        },
          
        updateProgramModal: false,
        selectedProgram: [],
        editProgram(program) {
             console.log('program:',program);
            this.selectedProgram = {
                id: program.id,
                program_name: program.program_name,
                program_abbreviation: program.program_abbreviation,
                origin_office: program.origin_office,
                status: program.status,
            };
            console.log('yawas',this.selectedProgram);
            this.updateProgramModal = true;
        },

        programToDelete: null,
        deleteProgramModal: false,
        confirmDeleteProgram(program) {
            this.programToDelete = program;
            this.deleteProgramModal = true;
            
        },

       deleteProgramConfirmed() {
            if (!this.programToDelete) return;

            axios.delete(`/program/delete/${this.programToDelete.id}`)
                .then(response => {
                    alert(response.data.message); // Optional feedback
                    this.getPrograms();
                })
                .catch(error => {
                    alert('Error deleting program.');
                    console.error('Error:', error.response?.data || error);
                })
                .finally(() => {
                    this.deleteProgramModal = false;
                    this.programToDelete = null;
                });
        },

        form: {
            id: '', 
            program_name: '',
            program_abbreviation: '',
            origin_office: '',
            status: 'Active',
        },
        errors: {},

        get hasErrors() {
            return Object.keys(this.errors).length > 0;
        },

        validateField(field) {
            const val = this.form[field] ?? '';
            const programPattern = /^[A-Za-z0-9.\-\/ ]+$/;

            const maxLengths = {
                program_name: 255,
                program_abbreviation: 50,
                origin_office: 255,
            };

            switch (field) {
                case 'program_name':
                case 'program_abbreviation':
                case 'origin_office':
                    if (!val.trim()) {
                        this.errors[field] = 'This field is required.';
                    } else if (!this.isValidString(val, programPattern)) {
                        this.errors[field] = 'Only letters, numbers, dots, dashes, slashes, and spaces are allowed.';
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

        isValidString(str, pattern) {
            return pattern.test(str);
        },
        validateAllFields() {
            ['program_name', 'program_abbreviation', 'origin_office', 'status']
                .forEach(field => this.validateField(field));
        }
    }));
});

</script>



