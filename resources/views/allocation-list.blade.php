<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cash Advance List') }}
        </h2>
    </x-slot>
    <div x-data="allocation()" class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-full">
                        @include('allocation.allocation-table')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>


<script>
    const officeId = "{{ Auth::user()->office_id }}";
    document.addEventListener('alpine:init', () => {
        Alpine.data('allocation', () => ({
            officeName: null,
            officeId: officeId,
            allocations: [],
            loading: false,
            updateStatusModal: false,
            currentPage: 1,
            totalPages: 1,
            perPage: 5,
            searchQuery: null,

            init() {
                this.fetchOfficeName();
                this.fetchAllocations();
            },

            async fetchOfficeName() {
                try {
                    const response = await axios.get(`/user/officeName/${this.officeId}`);
                    this.officeName = response.data.office_name;
                } catch (error) {
                    console.error("Error fetching office name:", error);
                    this.officeName = 'Unknown Office';
                }
            },

             async fetchAllocations() {
                this.loading = true;
                try {
                    const response = await axios.get(`/allocated/cash-advance/${this.officeId}`, {
                        params: {
                            search: this.searchQuery,
                            per_page: this.perPage,
                            page: this.currentPage
                        }
                    });

                    this.allocations = response.data.data;
                    console.log('allocations: ',this.allocations);
                    this.totalPages = response.data.last_page;
                    this.currentPage = response.data.current_page;
                } catch (error) {
                    console.error('Fetch error:', error);
                    this.allocations = [];
                } finally {
                    this.loading = false;
                }
            },

            changePage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                    this.fetchAllocations();
                }
            },

            updatePerPage(value) {
                this.perPage = parseInt(value);
                this.currentPage = 1;
                this.fetchAllocations();
            },

            search() {
                this.currentPage = 1;
                this.fetchAllocations();
            },
            selectedAllocation: null,
            updateAllocationStatus(allocation_id){
                this.updateStatusModal = true;
                this.selectedAllocation = allocation_id;
            },
        }));
    });
</script>


