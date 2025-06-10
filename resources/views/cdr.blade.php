<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Liquidation Generator') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
          <style>
            @media print {
                .no-print {
                    display: none;
                }

                table {
                    width: 100%;
                }

                thead, tfoot {
                    display: table-row-group;
                }

                tr {
                    page-break-inside: avoid;
                }

                .no-print {
                    display: none !important;
                }

                .page-break {
                    page-break-after: always;
                }
                th, td {
                    padding: 2px !important; 
                    font-size: 8px !important; 
                }
                .payee-column {
                    width: 250px !important; 
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div 
    x-data="{ showWarning: true }"
    x-show="showWarning"
    x-transition
    class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-4"
    role="alert"
>
    <div class="flex items-center justify-between">
        <p class="font-semibold">Warning:</p>
        <p class="ml-2">This page is currently under creation. Features may not work as expected.</p>
        <button @click="showWarning = false" class="ml-auto text-yellow-700 hover:text-yellow-900 font-bold text-xl">&times;</button>
    </div>
</div>
    </body>
</html>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cdr', () => ({
            sdos_id: null,
            file_list: [],
            file_data: [],
            cash_advances: [],
            sdo_info: [],
            loading: false,
            selectedMonth: '',
            year: null,
           
            async handleSelectMonth() {
                try {
                    await this.filterData(); 
                    await this.getCashAdvancePerMonth();
                    await this.getFirstMonth();
                } catch (error) {
                    console.error("Error loading data:", error);
                } finally {
                    setTimeout(() => {
                        this.loading = false;  
                        console.log("Loading ended...");
                    }, 500);
                }
            },

            //KUHA SA ID SA URL
            getUrlId() {
                const pathSegments = window.location.pathname.split('/');
                const id = pathSegments[pathSegments.length - 1];
                if (id) {
                    this.sdos_id = id;
                } else {
                    console.log('ID not found in the URL path');
                }
            },

            // KUHA SA DETAILS SA CASH ADVANCE
            async getAllCashAdvanceDetails(year) {
                this.loading = true;
                this.cash_advances = [];

                if (!this.sdos_id) {
                    console.log('No sdo id');
                    this.loading = false;
                    return;
                }

                try {
                    const response = await axios.get(`/sdo/get-cash-advances/${this.sdos_id}/${year}`);
                    const sdo =  response.data;

                    if (sdo) {
                        const middlename = sdo.middlename;
                        const middleInitial = middlename ? middlename.charAt(0) + '.' : '';

                        this.sdo_info = {
                            special_disbursing_officer: [
                                sdo.firstname,
                                middleInitial,
                                sdo.lastname,
                                sdo.extension_name
                            ].filter(Boolean).join(' '),
                            position: sdo.position,
                            designation: sdo.designation,
                            station: sdo.station,
                        };

                                           
                        if (Array.isArray(sdo.cash_advances)) {
                            let allFiles = [];

                            this.cash_advances = sdo.cash_advances.map(ca => {
                                const files = Array.isArray(ca.files) ? ca.files.map(file => ({
                                    file_id: file.id,
                                    file_data: file.file_data ?? null,
                                    cash_advance_id: ca.id 
                                })) : [];

                                allFiles.push(...files);

                                return {
                                    id: ca.id,
                                    check_number: ca.check_number,
                                    cash_advance_amount: parseFloat(ca.cash_advance_amount).toFixed(2),
                                    cash_advance_date: ca.cash_advance_date,
                                    dv_number: ca.dv_number,
                                    ors_burs_number: ca.ors_burs_number,
                                    responsibility_code: ca.responsibility_code,
                                    uacs_code: ca.uacs_code,
                                    status: ca.status,
                                    files: files
                                };
                            });

                            this.file_data = allFiles;
                            console.log('yawa na data',this.file_data);
                        } else {
                            this.cash_advances = [];
                            this.file_data = [];
                            console.log('No cash advances array found.');
                        }

                    } else {
                        console.log('No SDO data found.');
                        this.cash_advances = [];
                    }

                } catch (error) {
                    console.error('Error fetching cash advance details:', error);
                    this.cash_advances = [];
                } finally {
                    this.loading = false;
                }
            },

            filtered_file_data: [],

            filterData() {
                if (!this.file_data || this.file_data.length === 0) {
                    console.log("No file data available for filtering.");
                    return;
                }

                if (!this.selectedMonth) {
                    console.log("No month selected.");
                    this.filtered_file_data = [];
                    return;
                }

                // Filter data based on selected month
                this.filtered_file_data = this.file_data.filter(item => {
                    let date = new Date(item.date_time_claimed);
                    let monthYear = date.toLocaleString("default", { month: "long" });
                    return monthYear === this.selectedMonth;
                });
                console.log("Filtered Data:", this.filtered_file_data);
            },

            //gamit sad sa dropdownd sa select months
            months: [],
            getMonthsTotalAmount(data) {
                let totals = {};

                data.forEach(item => {
                    let date = new Date(item.date_time_claimed);
                    let month = date.toLocaleString('default', { month: 'long'});

                    if (!totals[month]) {
                        totals[month] = 0;
                    }
                    totals[month] += item.amount;
                });

                this.months = Object.entries(totals).map(([month, total]) => ({ month, total }));
                console.log('months:', this.months);
            },

            calculateBalance(index) {
                let runningBalance = this.cash_advance_remaining;
                for (let i = 0; i <= index; i++) {
                    if (this.filtered_file_data[i]) {
                        runningBalance -= this.filtered_file_data[i].amount;
                    }
                }
                return runningBalance === 0 ? "-" : runningBalance.toLocaleString(); 
            },

            cash_advance_remaining: '',
            getCashAdvancePerMonth() {  

                if (!this.selectedMonth) {
                    this.cash_advance_remaining = this.mapped_cash_advance_details.cash_advance_amount;
                    console.log("cash advance remaining", this.cash_advance_remaining);
                    return;
                }

                let selectedIndex = this.months.findIndex(m => m.month === this.selectedMonth);

                if (selectedIndex === -1) {
                    console.log("Invalid month selected");
                    return;
                }
                let totalDeduction = this.months
                    .slice(0, selectedIndex)
                    .reduce((sum, month) => sum + month.total, 0);

                this.cash_advance_remaining = this.mapped_cash_advance_details.cash_advance_amount - totalDeduction;

                console.log("Remaining Cash Advance:", this.cash_advance_remaining);
            },

            firstMonth: "",  

            getFirstMonth() {
                if (this.months.length === 0) {
                    console.log("No months available.");
                    this.firstMonth = "";
                    return;
                }
                if(!this.selectedMonth){
                    this.firstMonth = "";
                    return;
                }
                
                if (this.selectedMonth === this.months[0].month) {
                    this.firstMonth = this.selectedMonth;
                    console.log('dandka:',this.firstMonth);
                } else {
                    this.firstMonth = "";
                    console.log('dandka:', this.firstMonth);
                }
            },

            convertMonthToDigit(monthName) {
                const months = {
                    "January": 1, "February": 2, "March": 3, "April": 4,
                    "May": 5, "June": 6, "July": 7, "August": 8,
                    "September": 9, "October": 10, "November": 11, "December": 12
                };

                return months[monthName] || null; 
            },
            getYearFromDate(dateString) {
                if (!dateString) return null;

                const parts = dateString.split("-");
                return parts.length === 3 ? parts[0] : null; 
            },

            init() {
                this.getUrlId();  
                this.year = new Date().getFullYear(); 
                this.getAllCashAdvanceDetails(this.year);
            }
        }));
    });
</script>


