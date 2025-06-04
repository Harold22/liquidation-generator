<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cash Advances List') }}
        </h2>
    </x-slot>
    <div x-data="cashAdvances()" class="py-8">
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
   document.addEventListener('alpine:init', () => {
    Alpine.data('cashAdvances', () => ({
        fields: [
            { id: 'sdos_id', name: 'sdos_id', label: 'Special Disbursing Officer', type: 'text', value: '' },
            { id: 'check_number', name: 'check_number', label: 'Check Number', type: 'text', value: '' },
            { id: 'cash_advance_amount', name: 'cash_advance_amount', label: 'Cash Advance Amount', type: 'number', value: '' },
            { id: 'cash_advance_date', name: 'cash_advance_date', label: 'Cash Advance Date', type: 'date', value: '' },
            { id: 'dv_number', name: 'dv_number', label: 'DV Number', type: 'text', value: '' },
            { id: 'ors_burs_number', name: 'ors_burs_number', label: 'ORS/BURS Number', type: 'text', value: '' },
            { id: 'responsibility_code', name: 'responsibility_code', label: 'Responsibility Code', type: 'text', value: '' },
            { id: 'uacs_code', name: 'uacs_code', label: 'UACS Object Code', type: 'text', value: '' }
        ],

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
            perPage: 5,
            
            sortBy: null,
            sortOrder: 'ASC',
            filterBy: null,

            async getCashAdvancesList(page = 1) {
                this.loading = true;
                try {
                    
                    let url = `/cash-advance/index?page=${page}&perPage=${this.perPage}&sortBy=${this.sortBy}&sortOrder=${this.sortOrder}&filterBy=${this.filterBy}`;

                    if (this.searchCashAdvance) {
                        url += `&search=${encodeURIComponent(this.searchCashAdvance)}`;
                    }

                    const response = await axios.get(url);
                    const data = await response.data;

                    this.cashAdvancesList = data.data;
                    this.currentPage = data.current_page;
                    this.totalPages = data.last_page;
                } catch (error) {
                    console.error("Error fetching cash advances:", error);
                } finally {
                    this.loading = false;
                }
            },

            toggleSort(field) {
                if (this.sortBy === field) {
                    this.sortOrder = this.sortOrder === 'ASC' ? 'DESC' : 'ASC'; 
                } else {
                    this.sortBy = field;
                    this.sortOrder = 'ASC';
                }
                this.getCashAdvancesList(1);
            },

            applyFilter(filter) {
                this.filterBy = filter;
                this.getCashAdvancesList(1);
            },

            resetFilters() {
                this.sortBy = null;
                this.sortOrder = 'ASC';
                this.filterBy = null;
                this.getCashAdvancesList(1);
            },

            changePage(page) {
                if (page < 1 || page > this.totalPages) return;
                this.getCashAdvancesList(page); 
            },

            updatePerPage(value) {
                this.perPage = parseInt(value);
                this.currentPage = 1; 
                this.getCashAdvancesList(1, this.perPage); 
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
            },

        form: {
            check_number: '',
            cash_advance_amount: '',
            cash_advance_date: '',
            dv_number: '',
            ors_burs_number: '',
            responsibility_code: '',
            uacs_code: ''
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
            const maxAmount = 75000000;

            switch (field) {
                case 'check_number':
                    if (!val) {
                        delete this.errors[field];
                        break;
                    }
                    if (!this.isValidString(val, codePattern)) {
                        this.errors[field] = 'Invalid characters used.';
                    } else if (val.length > 255) {
                        this.errors[field] = 'Must not exceed 255 characters.';
                    } else {
                        delete this.errors[field];
                    }
                    break;

                case 'cash_advance_amount':
                    if (!val) {
                        delete this.errors.cash_advance_amount
                        break;
                    }
                    if (isNaN(val)) {
                        this.errors.cash_advance_amount = 'Must be a valid number.';
                    } else if (+val < 0.01) {
                        this.errors.cash_advance_amount = 'Minimum is ₱0.01.';
                    } else if (+val > maxAmount) {
                        this.errors.cash_advance_amount = `Maximum is ₱${maxAmount.toLocaleString()}.`;
                    } else {
                        delete this.errors.cash_advance_amount;
                    }
                    break;

                case 'cash_advance_date':
                    if (!val) {
                        delete this.errors.cash_advance_date;
                        break;
                    }
                    if (val > today) {
                        this.errors.cash_advance_date = 'Date cannot be in the future.';
                    } else {
                        delete this.errors.cash_advance_date;
                    }
                    break;

                case 'dv_number':
                case 'ors_burs_number':
                case 'responsibility_code':
                case 'uacs_code':
                    if (!val) {
                        delete this.errors[field];
                        break;
                    }
                    if (!this.isValidString(val, codePattern)) {
                        this.errors[field] = 'Invalid characters used.';
                    } else if (val.length > 255) {
                        this.errors[field] = 'Must not exceed 255 characters.';
                    } else {
                        delete this.errors[field];
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

