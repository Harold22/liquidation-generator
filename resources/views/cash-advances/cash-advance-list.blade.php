<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cash Advances List') }}
        </h2>
    </x-slot>
    <div x-data="cashAdvances()" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-full">
                        @include('cash-advances.forms.list-cash-advance-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function cashAdvances() {
        return {
            updateCashAdvanceModal: false, deleteCashAdvanceModal: false, refundCashAdvanceModal: false,
            cashAdvancesList: [],
            currentPage: 1,
            totalPages: 1,
            loading: true,
            refund_id: null,
            amount_refunded: null,
            date_refunded: null,
            official_receipt: null,
            searchCashAdvance: null,
            
            async getCashAdvancesList(page = 1) {
                this.loading = true; 

                try {
                    const response = await axios.get(`/cash-advance/index`, {
                        params: {
                            page: page,
                            search: this.searchCashAdvance
                        }
                    });

                    const data = response.data;
                    this.cashAdvancesList = data.data;
                    this.currentPage = data.current_page;
                    this.totalPages = data.last_page;
                } catch (error) {
                    console.error("Error fetching cash advances:", error);
                } finally {
                    this.loading = false; // End loading state
                }
            },

            changePage(page) {
                if (page < 1 || page > this.totalPages) return;
                this.getCashAdvancesList(page); 
            },

            selectedList: {},
            updateModalData(list) {
                this.selectedList = list;
                this.updateCashAdvanceModal = true;
            },
            
            toDeleteSelectedList: {},
            deleteModalData(list) {
                this.toDeleteSelectedList = list;
                this.deleteCashAdvanceModal = true;
            },
            refundList: {},
            refundModalData(list) {
                this.refundList = list;
                this.refundCashAdvanceModal = true;
                this.getRefundData(this.refundList.id);

            },
            async getRefundData(cash_advance_id) {
                if (!cash_advance_id) {
                    this.refund_id = null;
                    this.amount_refunded = null;
                    this.date_refunded = null;
                    this.official_receipt = null;
                    return;
                }
                try {
                    const response = await axios.get(`/refund/show/${cash_advance_id}`);
                    
                    if (Array.isArray(response.data) && response.data.length > 0) {
                        const refund = response.data[0]; 
                        this.refund_id = refund.id;
                        this.amount_refunded = refund.amount_refunded;
                        this.date_refunded = refund.date_refunded;
                        this.official_receipt = refund.official_receipt;
                        this.loading = false;
                    } else {
                        this.refund_id = null;
                        this.amount_refunded = null;
                        this.date_refunded = null;
                        this.official_receipt = null;
                        this.loading = false;
                    }
                } catch (error) {
                    console.error('Error fetching Refund Data:', error);
                }
            },

            async deleteRefund() {
                if (!this.refund_id) {
                    Swal.fire("No Refund on this Cash Advance!");
                    return;
                }

                // Show confirmation dialog
                const result = await Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel",
                    reverseButtons: true
                });

                // If user confirms, proceed with deletion
                if (result.isConfirmed) {
                    try {
                        const response = await axios.post(`/refund/delete/${this.refund_id}`);
                        this.refundCashAdvanceModal = false;

                        // Show success message
                        Swal.fire({
                            title: "Deleted!",
                            text: "The refund has been deleted successfully.",
                            icon: "success"
                        });

                    } catch (error) {
                        console.error('Error fetching Refund Data:', error);
                        
                        // Show error message
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong while deleting the refund.",
                            icon: "error"
                        });
                    }
                }
            },


            init() {
                this.getCashAdvancesList();
            }
        }
    }
</script>

