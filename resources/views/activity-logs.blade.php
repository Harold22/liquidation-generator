<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Activity Logs') }}
        </h2>
    </x-slot>
    @include('error-messages.messages')
    <div x-data="logs()" class="py-8">
        <div x-show="loading">
            <x-spinner />
        </div> 
         <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">
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
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- When no activity logs exist -->
                                <template x-if="activity_logs.length === 0">
                                    <tr class="border-b">
                                        <td colspan="6" class="px-4 py-2 text-red-500 font-semibold text-center">
                                            No activity logs for today, but you can still search logs on previous dates.
                                        </td>
                                    </tr>
                                </template>

                                <!-- When activity logs exist -->
                                <template x-for="log in activity_logs" :key="log.id">
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2" x-text="log.causer?.name ?? 'System'"></td>
                                        <td class="px-4 py-2" x-text="log.causer?.email ?? 'System'"></td>
                                        <td class="px-4 py-2" x-text="log.event"></td>
                                        <td class="px-4 py-2"
                                            x-text="
                                                log.subject_type?.split('\\').pop() === 'CashAdvance' ? 'Activity on Cash Advance' :
                                                log.subject_type?.split('\\').pop() === 'File' ? 'Activity on Imported File' :
                                                log.subject_type?.split('\\').pop() === 'FileData' ? 'Activity on File Data' :
                                                log.subject_type?.split('\\').pop() === 'Refund' ? 'Activity on Refund' :
                                                log.subject_type?.split('\\').pop() === 'User' ? 'Activity on User' : '-'">
                                        </td>
                                        <td class="px-4 py-2" x-text="new Date(log.created_at).toLocaleString()"></td>
                                        <td class="px-4 py-2">
                                            <div x-data="{ tooltip: false }" class="relative flex items-center justify-center">
                                                <button 
                                                    @click="showModal(log)"
                                                    @mouseenter="tooltip = true" 
                                                    @mouseleave="tooltip = false"
                                                    class="flex items-center justify-center w-8 h-8 rounded-full text-gray-600 hover:bg-green-100 hover:text-green-600 transition duration-200 ease-in-out focus:outline-none"
                                                    aria-label="View Details"
                                                >
                                                    <!-- Table/List Icon -->
                                                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>

                                                </button>

                                                <!-- Tooltip -->
                                                <span 
                                                    x-show="tooltip" 
                                                    x-transition.opacity 
                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md z-50"
                                                >
                                                    View Details
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>

                            <!-- Modal Overlay -->
                            <div 
                                x-show="properties_modal"
                                x-cloak 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-[999] flex items-center justify-center bg-black bg-opacity-50"
                                role="dialog" 
                                aria-modal="true"
                                aria-labelledby="modal-title"
                            >
                                <!-- Modal Box -->
                                <div 
                                    @click.away="properties_modal = false"
                                    class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden"
                                >
                                    <!-- Sticky Header -->
                                    <header 
                                        class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 sticky top-0 z-10"
                                    >
                                        <h2 id="modal-title" class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                            Activity Log Properties
                                        </h2>
                                        <button 
                                            @click="properties_modal = false"
                                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition"
                                            aria-label="Close Modal"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </header>

                                    <!-- Scrollable Content -->
                                    <div class="px-6 py-4 overflow-y-auto flex-1">
                                        @include('activity-log.activity-log-modal')
                                    </div>

                                    <!-- Sticky Footer -->
                                    <footer 
                                        class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 sticky bottom-0 z-10"
                                    >
                                        <div class="flex justify-end">
                                            <x-secondary-button
                                                @click="properties_modal = false" 
                                            >
                                                Close
                                            </x-secondary-button>
                                        </div>
                                    </footer>
                                </div>
                            </div>

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




