<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Activity Logs') }}
        </h2>
    </x-slot>

    <div x-data="logs()" class="py-8">
        <div x-show="loading">
            <x-spinner />
        </div> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
        @include('error-messages.messages')
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                <!-- User List -->
                <div class="w-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-blue-500">List of Activity Logs</h3>
                            <input 
                                type="text" 
                                placeholder="Search Email/Name" 
                                x-model="search"
                                @input.debounce.500ms="getLogs"
                                ...
                                class="px-4 py-1.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100 w-48"
                            />

                        </div>

                        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
                            <thead class="bg-gray-100 dark:bg-gray-600">
                                <tr class="border">
                                    <th class="px-4 py-2">Resource Name</th>
                                    <th class="px-4 py-2">Resource Email</th>
                                    <th class="px-4 py-2">Event</th>
                                    <th class="px-4 py-2">Model</th>
                                    <th class="px-4 py-2">Date</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                           <tbody>
                                <template x-for="log in activity_logs" :key="log.id">
                                    <tr class="border-b">
                                        <td class="px-4 py-2" x-text="log.causer?.name ?? 'System'"></td>
                                        <td class="px-4 py-2" x-text="log.causer?.email ?? 'System'"></td>
                                        <td class="px-4 py-2" x-text="log.event"></td>
                                        <td class="px-4 py-2" 
                                            x-text="
                                                log.subject_type.split('\\').pop() === 'CashAdvance' ? 'Activity on Cash Advance' :
                                                log.subject_type.split('\\').pop() === 'File' ? 'Activity on Imported File' :
                                                log.subject_type.split('\\').pop() === 'FileData' ? 'Activity on File Data' :
                                                log.subject_type.split('\\').pop() === 'Refund' ? 'Activity on Refund' :
                                                log.subject_type.split('\\').pop() === 'User' ? 'Activity on User' :
                                                '-'">
                                        </td>
                                        <td class="px-4 py-2" x-text="new Date(log.created_at).toLocaleString()"></td>
                                        <td class="px-4 py-2">
                                            <div x-data="{ tooltip: false }" class="relative flex items-center">
                                                <button @click="showModal(log)" 
                                                    @mouseenter="tooltip = true" 
                                                    @mouseleave="tooltip = false" 
                                                    class="p-2 text-gray-600 hover:text-green-500 focus:outline-none transition duration-200 ease-in-out">
                                                    
                                                 <!-- Eye Icon -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                                        <path d="M1.5 12C1.5 12 5.25 4.5 12 4.5C18.75 4.5 22.5 12 22.5 12C22.5 12 18.75 19.5 12 19.5C5.25 19.5 1.5 12 1.5 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>

                                                </button>
                                                <!-- Tooltip -->
                                                <span x-show="tooltip" 
                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md"
                                                    x-transition.opacity>
                                                    Details
                                                </span>
                                            </div>
                                           <!-- Modal Overlay -->
                                            <div x-show="properties_modal"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-20 px-4 sm:px-6">

                                                <div @click.away="properties_modal = false"
                                                    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">

                                                    <!-- Sticky Header -->
                                                    <header class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 sticky top-0 z-10">
                                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Activity Log Properties</h2>
                                                        <button @click="properties_modal = false"
                                                                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                            </svg>
                                                        </button>
                                                    </header>

                                                    <!-- Scrollable Content -->
                                                    <div class="px-6 overflow-y-auto">
                                                        @include('activity-log.activity-log-modal')
                                                    </div>

                                                    <!-- Sticky Footer -->
                                                    <footer class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 sticky bottom-0 z-10">
                                                        <button @click="properties_modal = false" class="btn btn-primary">Close</button>
                                                    </footer>
                                                </div>
                                            </div>


                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="flex items-center justify-center mt-6">
                            <div class="space-x-2">
                                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    &laquo; Prev
                                </button>
                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                    Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                                </span>
                                <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    Next &raquo;
                                </button>
                            </div>

                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm">Show</label>
                                <select x-model="perPage" @change="updatePerPage(perPage)"
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
    Alpine.data('logs', () => ({
        loading: false,
        activity_logs: [],
        search: '',
        perPage: 10,
        currentPage: 1,
        totalPages: 1,
        properties_modal: false,

        init() {
            this.getLogs();
        },

        async getLogs() {
            this.loading = true;
            try {
                const response = await axios.get('/get-activity-logs', {
                    params: {
                        page: this.currentPage,
                        perPage: this.perPage,
                        search: this.search,
                    }
                });
                this.activity_logs = response.data.data;
                this.currentPage = response.data.current_page;
                this.totalPages = response.data.last_page;
            } catch (error) {
                console.error('Error Fetching Logs:', error);
            } finally {
                this.loading = false;
            }
        },

        changePage(page) {
            if (page < 1 || page > this.totalPages) return;
            this.currentPage = page;
            this.getLogs();
        },

        updatePerPage(value) {
            this.perPage = value;
            this.currentPage = 1;
            this.getLogs();
        },
        log_properties:{},
        showModal(log){
            this.properties_modal = true;
            this.log_properties = log.properties;
        }
    }));
});
</script>




