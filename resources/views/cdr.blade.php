<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Liquidation Generator') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
        <div x-data="cdr()" class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="p-6 text-sm">
                <div class="max-w-4xl mx-auto">
                    <!-- filtering -->
                    <div class="my-3  border rounded-lg p-3 no-print">
                        <p class="text-sm text-gray-500 mb-3">
                            Note: CDR is a monthly reporting.
                        </p>
                        <div class="flex flex-col md:flex-row items-center gap-4">
                            <label for="month" class="text-sm font-medium text-gray-700 w-full md:w-auto">
                                Select Month:
                            </label>
                            <select id="month" name="month" x-model="selectedMonth" @change="handleSelectMonth(), loading = true"
                                class="capitalize text-sm border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 w-full md:w-1/4">
                                <option value="">Select Month</option>
                                <template x-for="month in months" :key="month.month">
                                    <option x-text="month.month" :value="month.month"></option>
                                </template>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="max-w-4xl mx-auto my-10">
                    <!-- loading ni -->
                    <div x-show="loading">
                        <x-spinner />
                    </div>
                    <div class="flex justify-center">
                        <h1 class="text-xl font-bold">CASH DISBURSEMENT RECORD</h1>
                    </div>
                    <div class="flex justify-center">
                        <h1 class="text-md font-bold">
                            Period Covered: <span class="uppercase" x-text="selectedMonth"></span>
                        </h1>
                    </div>
                    <div class="mt-7">
                        <div class="flex justify-between items-center">
                            <div class="flex w-full">
                                <label for="entity_name">Entity Name: DSWD Field Office XI</label>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex w-3/4">
                                <label for="fund_cluster">Fund Cluster: AICS (FUND 101)</label>
                            </div>
                            <div class="flex w-1/4">
                                <label for="sheet_no"></label>
                                <span class="text-sm ml-2"></span>
                            </div>
                        </div>
                    </div>
                    <div x-show="loading == false" class="pb-5 overflow-auto max-w-full">
                        <table class="bg-white border border-gray-200 border-collapse mt-1 max-w-full">
                            <thead class="text-center">
                                <tr class="border-black border-t border-x">
                                    <th class="items-center border-r border-black" colspan="3"><span class="uppercase underline" x-text="mapped_cash_advance_details.special_disbursing_officer"></span></th>
                                    <th class="border-r border-black" colspan="3"><span class="uppercase underline" x-text="mapped_cash_advance_details.position"></span></th>
                                    <th colspan="2"><span class="uppercase underline" x-text="mapped_cash_advance_details.station"></span></th>
                                </tr>
                                <tr class="border-x border-black ">
                                    <th class="items-center border-r border-black" colspan="3">Accountable Officer</th>
                                    <th class="border-r border-black" colspan="3">Official Designation</th>
                                    <th colspan="2">Station</th>
                                </tr>
                                <tr>
                                    <th class="border border-black" colspan="1">Date</th>
                                    <th class="border border-black" colspan="1">ADA/Check/DV/ Payroll/Reference</th>
                                    <th class="border border-black" colspan="1">Payee</th>
                                    <th class="border border-black" colspan="1">UACS Object Code</th>
                                    <th class="border border-black" colspan="1">Nature of Payment</th>
                                    <th class="border border-black" colspan="1">Cash Advance Recieved/(Refunded)</th>
                                    <th class="border border-black" colspan="1">Disbursements</th>
                                    <th class="border border-black" colspan="1">Cash Advance Balance    </th>
                                </tr>
                                <template x-if="firstMonth">
                                    <tr>
                                        <th class="border border-black py-2" colspan="1" x-text="mapped_cash_advance_details.cash_advance_date"></th>
                                        <th class="border border-black py-2" colspan="1" x-text="mapped_cash_advance_details.ors_burs_number"></th>
                                        <th class="border border-black py-2 uppercase" colspan="1" x-text="mapped_cash_advance_details.special_disbursing_officer"></th>
                                        <th class="border border-black py-2" colspan="1" x-text="mapped_cash_advance_details.uacs_code"></th>
                                        <th class="border border-black py-2" colspan="1">CASH ADVANCE RE-AICS GRANT</th>
                                        <th class="border border-black py-2" colspan="1" x-text="Number(cash_advance_remaining ? cash_advance_remaining : mapped_cash_advance_details.cash_advance_amount).toLocaleString()"></th>
                                        <th class="border border-black py-2" colspan="1"></th>
                                        <th class="border border-black py-2" colspan="1" x-text="Number(cash_advance_remaining ? cash_advance_remaining : mapped_cash_advance_details.cash_advance_amount).toLocaleString()"></th>
                                    </tr>
                                </template>
                                <template x-if="!firstMonth && selectedMonth">
                                    <tr>
                                        <th class="border border-black py-2" colspan="1" x-text="convertMonthToDigit(selectedMonth) + '/' + '01' + '/' +  getYearFromDate(mapped_cash_advance_details.cash_advance_date)"></th>
                                        <th class="border border-black py-2" colspan="1" x-text="mapped_cash_advance_details.ors_burs_number"></th>
                                        <th class="border border-black py-2 uppercase" colspan="4">Balance Forwarded</th>
                                        <th class="border border-black py-2" colspan="1"></th>
                                        <th class="border border-black py-2" colspan="1" x-text="Number(cash_advance_remaining ? cash_advance_remaining : mapped_cash_advance_details.cash_advance_amount).toLocaleString()"></th>
                                    </tr>
                                </template>
                            </thead>
                            <tbody>
                                <template x-if="filtered_file_data.length != 0">
                                    <template x-for="(file, index) in filtered_file_data" :key="index">
                                        <tr>
                                            <td class="border border-black px-2 py-2 text-center" x-text="new Date(file.date_time_claimed).toLocaleDateString('en-US')"></td>
                                            <td class="border border-black px-2 py-2 text-center" x-text="mapped_cash_advance_details.dv_number"></td>
                                            <td class="border border-black px-2 py-2 text-center uppercase w-[250px] break-words">
                                                <span x-text="`${file.lastname || ''}, ${file.firstname || ''} ${file.middlename || ''} ${file.extension_name || ''}`.trim().replace(/\s+/g, ' ').replace(/\?/g, 'Ñ')"></span>
                                            </td>
                                            <td class="border border-black px-2 py-2 text-center" x-text="mapped_cash_advance_details.uacs_code"></td>
                                            <td class="border border-black px-2 py-2 text-center uppercase" x-text="file.assistance_type"></td>
                                            <td class="border border-black px-2 py-2 text-center"></td>
                                            <td class="border border-black px-2 py-2 text-center" x-text="file.amount.toLocaleString()"></td>
                                            <td class="border border-black px-2 py-2 text-center" x-text="calculateBalance(index)"></td>
                                        </tr>
                                    </template>
                                </template>
                                <template x-if="filtered_file_data.length === 0">
                                    <tr>
                                        <td class="border border-black px-2 py-2 text-center"></td>
                                        <td class="border border-black px-2 py-2 text-center"></td>
                                        <td class="border border-black px-2 py-2 text-center uppercase w-[250px] break-words font-bold text-red-500">Select Month to show data</td>
                                        <td class="border border-black px-2 py-2 text-center"></td>
                                        <td class="border border-black px-2 py-2 text-center"></td>
                                        <td class="border border-black px-2 py-2 text-center"></td>
                                        <td class="border border-black px-2 py-2 text-center"></td>
                                        <td class="border border-black px-2 py-2 text-center"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8 text-center px-20">
                        <p class="text-lg font-bold">CERTIFICATION</p>
                        <p class="mt-4">
                            I hereby certify on my official oath that this Report of Cash Disbursements in sheet(s)
                            is a full, true and correct statement of all cash disbursements during the period stated
                            above actually made by me in payment for obligations shown in pertinent disbursement
                            vouchers/payroll.
                        </p>
                    </div>
                    <div class="mt-10 text-center px-5">
                        <span class="uppercase font-semibold" x-text="mapped_cash_advance_details.special_disbursing_officer"></span>
                        <div class="mt-2 border-t border-black w-1/2 mx-auto"></div> 
                        <p>Name and Signature of Disbursing Officer/Cashier</p>
                    </div>
                    <div class="flex mt-10 justify-between text-center px-18 mx-40">
                        <div class="w-1/3">
                            <span class="uppercase font-semibold" x-text="mapped_cash_advance_details.position"></span>
                            <div class="mt-2 border-t border-black w-full mx-auto"></div>
                            <p>Official Designation</p>
                        </div>
                        <div class="w-1/3">
                            <span class="uppercase font-semibold">{{ now()->format('F j, Y') }}</span>
                            <div class="mt-2 border-t border-black w-full mx-auto"></div> 
                            <p>Date</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-3">
                        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-md flex items-center gap-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 print:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 8h-2V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2V10a2 2 0 00-2-2zm-6 0V6h-4v2H5v6h14V8h-4z" />
                            </svg>
                            <span class="text-sm font-medium">Print</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cdr', () => ({
            cash_advance_id: null,
            file_list: [],
            file_data: [],
            cash_advance_details: {},
            mapped_cash_advance_details: {},
            loading: false,
            selectedMonth: '',
           
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
                    this.cash_advance_id = id;
                } else {
                    console.log('ID not found in the URL path');
                }
            },

            //KUHA SA DETAILS SA CASH ADVANCE
            async getCashAdvanceDetails() {
                if (!this.cash_advance_id) {
                    console.log('No cash advance id');
                    return;
                }
                try {
                    const response = await axios.get(`/cash-advance/details/${this.cash_advance_id}`);
                    const details = response.data[0]; 
                    if (details) {
                        this.mapped_cash_advance_details = {
                            special_disbursing_officer: details.special_disbursing_officer,
                            position: details.position,
                            station: details.station,
                            cash_advance_amount: parseFloat(details.cash_advance_amount).toFixed(2),
                            cash_advance_date: details.cash_advance_date,
                            dv_number: details.dv_number,
                            ors_burs_number: details.ors_burs_number,
                            responsibility_code: details.responsibility_code,
                            uacs_code: details.uacs_code,
                            status: details.status,
                        };
                        console.log('Mapped details:', this.mapped_cash_advance_details);
                    } else {
                        console.log('No cash advance details found.');
                    }
                } catch (error) {
                    console.error('Error fetching CA details:', error);
                }
            },
            
            //KUHA OG LIST SA FILES SA ISA KA CASH ADVANCE
            async getFileList() {
                if (!this.cash_advance_id) {
                    console.log('No cash advance id');
                    return;
                }
                try {
                    const response = await axios.get(`/files/rcd/${this.cash_advance_id}`);
                    this.file_list = response.data; 
                    console.log('file list ni', this.file_list);
                    await this.getFileData(this.file_list);
                } catch (error) {
                    console.error('Error fetching file list:', error);
                }
            },

            //KUHA OG DATA SA PER ID/FILE SA FILE DATA
            async getFileData(fileIds) {
                if (!fileIds || fileIds.length === 0) {
                    console.log('No file IDs to fetch data for.');
                    this.loading = false;
                    return;
                }
                try {
                    const response = await axios.get(`/files/data/${fileIds.join(',')}`);
                    this.file_data = response.data;
                    const combinedData = Object.values(this.file_data).flat();

                    const sortedData = combinedData.sort((a, b) => {
                        const dateA = new Date(a.date_time_claimed).setHours(0, 0, 0, 0);
                        const dateB = new Date(b.date_time_claimed).setHours(0, 0, 0, 0);
                        return dateA - dateB; 
                    });

                    this.file_data = sortedData;
                    this.getMonthsTotalAmount(this.file_data);
                    this.filterData();
                    this.loading = false;
                } catch (error) {
                    console.error('Error fetching file data:', error);
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
                this.loading = true;
                this.getUrlId();  
                this.getFileList();  
                this.getCashAdvanceDetails();
            }
        }));
    });
</script>


