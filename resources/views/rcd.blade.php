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
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div x-data="rcd()" class="min-h-screen dark:bg-gray-900">
            <div class="p-6 text-sm">
                <div class="max-w-4xl mx-auto">
                    <!-- filter -->
                    <div class="w-full no-print border border-gray-200 p-6 rounded-lg shadow-sm bg-white">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Liquidation Type -->
                            <div class="w-full md:w-1/6">
                                <label for="liquidationType" class="block text-sm font-medium text-gray-700 mb-1">Liquidation Type</label>
                                <select id="liquidationType" name="liquidationType" x-model="liquidationType" class="text-sm mt-1 block w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <option value="Full">Full</option>
                                    <option value="Partial">Partial</option>
                                </select>
                            </div>

                            <!-- Liquidation Mode -->
                            <div class="w-full md:w-1/4">
                                <label for="liquidation_mode" class="block text-sm font-medium text-gray-700 mb-1">Liquidation Mode</label>
                                <select id="liquidationMode" name="liquidationMode" x-model="liquidationMode" @click="filteredData()" class="text-sm mt-1 block w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <option value="Overall">Overall</option>
                                    <option value="Bundle">Per Bundle</option>
                                </select>
                            </div>
                            <!-- Conditional Inputs for Bundle Mode -->
                            <template x-if="liquidationMode === 'Bundle'">
                                <div class="w-full md:w-1/4 relative" x-data="{ searchFrom: '', filteredNames: [] }" @click.away="filteredNames = [], filteredData()">
                                    <label for="nameFrom" class="block text-sm font-medium text-gray-700 mb-1">
                                        From (Names) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nameFrom" name="nameFrom" required x-model="searchFrom" 
                                        @input="
                                            filteredNames = file_data.filter(file => 
                                                (file.lastname + ' ' + (file.firstname || '') + ' ' + (file.middlename || '')).toLowerCase().includes(searchFrom.toLowerCase())
                                            );
                                            if (!searchFrom.trim()) { 
                                                nameFrom = ''; 
                                                filteredData(); 
                                            }
                                        "
                                        class="text-sm mt-1 block w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                        placeholder="Type to search..." />


                                    <ul class="absolute z-50 border border-gray-300 mt-1 rounded-lg shadow-md bg-white max-h-40 overflow-y-auto w-full"
                                        x-show="filteredNames.length > 0">
                                        <template x-for="file in filteredNames" :key="file.id">
                                            <li @click="nameFrom = file.id; searchFrom = file.lastname + ', ' + (file.firstname || '') + (file.middlename ? ' ' + file.middlename : ''); filteredNames = []; filteredData()"
                                                class="p-2 cursor-pointer hover:bg-gray-100">
                                                <span x-text="file.lastname + ', ' + (file.firstname || '') + (file.middlename ? ' ' + file.middlename : '')"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>

                            <template x-if="liquidationMode === 'Bundle'">
                                <div class="w-full md:w-1/4" x-data="{ searchTo: '', filteredNamesTo: [] }" @click.away="filteredNamesTo = [], filteredData()">
                                    <label for="nameTo" class="block text-sm font-medium text-gray-700 mb-1">
                                        To (Names) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nameTo" name="nameTo" required x-model="searchTo"
                                        @input="
                                            filteredNamesTo = file_data.filter(file => 
                                                (file.lastname + ' ' + (file.firstname || '') + ' ' + (file.middlename || '')).toLowerCase().includes(searchTo.toLowerCase())
                                            );
                                            if (!searchTo.trim()) { 
                                                nameTo = ''; 
                                                filteredData(); 
                                            }
                                        "
                                        class="text-sm mt-1 block w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                        placeholder="Type to search..." />

                                    <ul class="absolute z-50 border border-gray-300 mt-1 rounded-lg shadow-md bg-white max-h-40 overflow-y-auto w-full"
                                        x-show="filteredNamesTo.length > 0">
                                        <template x-for="file in filteredNamesTo" :key="file.id">
                                            <li @click="nameTo = file.id; searchTo = file.lastname + ', ' + (file.firstname || '') + (file.middlename ? ' ' + file.middlename : ''); filteredNamesTo = []; filteredData()"
                                                class="p-2 cursor-pointer hover:bg-gray-100">
                                                <span x-text="file.lastname + ', ' + (file.firstname || '') + (file.middlename ? ' ' + file.middlename : '')"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="loading" class="w-full mt-4 flex justify-center items-center">
                        <div class="w-16 h-16 border-4 border-t-transparent border-blue-500 border-solid rounded-full animate-spin"></div>
                    </div>

                    <!-- start sa rcd -->
                    <div class="rounded p-4 overflow-hidden mt-4">
                        <div class="flex justify-center mt-2">
                            <h1 class="text-xl font-bold">REPORT OF CASH DISBURSEMENTS</h1>
                        </div>
                        <div class="flex justify-center">
                            <h1 class="text-md font-bold">
                                Period Covered: 
                                <span class="font-semibold" 
                                    x-text="firstDate === lastDate ? firstDate : firstDate + ' to ' + lastDate">
                                </span>
                            </h1>
                        </div>
                        <div class="mt-7">
                            <div class="flex justify-between items-center">
                                <div class="flex w-3/4">
                                    <label for="entity_name">Entity Name: DSWD Field Office XI</label>
                                </div>
                                <div class="flex w-1/4">
                                    <label for="sheet_no">Report No.:</label>
                                    <span class="text-sm ml-2"></span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="flex w-3/4">
                                    <label for="fund_cluster">Fund Cluster: AICS (FUND 101)</label>
                                </div>
                                <div class="flex w-1/4">
                                    <label for="sheet_no">Sheet No.:</label>
                                    <span class="text-sm ml-2"></span>
                                </div>
                            </div>
                        </div>
                        <div x-show="loading == false" class="pb-5 overflow-auto max-w-full">
                            <table class="bg-white border border-gray-200 border-collapse mt-1 max-w-full text-xs">
                                <thead class="text-center">
                                    <tr>
                                        <th class="border border-black" colspan="1">Date</th>
                                        <th class="border border-black" colspan="1">ADA/Check/DV/
                                            Payroll/Reference No.</th>
                                        <th class="border border-black" colspan="1">ORS/BURS No.</th>
                                        <th class="border border-black" colspan="1">Responsibility Center Code</th>
                                        <th class="border border-black" colspan="1">Payee</th>
                                        <th class="border border-black" colspan="1">UACS Object Code</th>
                                        <th class="border border-black" colspan="1">Nature of Payment</th>
                                        <th class="border border-black" colspan="1">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template  x-for="file in filtered_file_data" :key="file.id">
                                        <tr>
                                            <td class="border border-black px-1 py-1 text-center" x-text="new Date(file.date_time_claimed).toLocaleDateString('en-US')"></td>
                                            <td class="border border-black px-1 py-1 text-center" x-text="mapped_cash_advance_details.dv_number"></td>
                                            <td class="border border-black px-1 py-1 text-center" x-text="mapped_cash_advance_details.ors_burs_number"></td>
                                            <td class="border border-black px-1 py-1 text-center" x-text="mapped_cash_advance_details.responsibility_code"></td>
                                            <td class="border border-black px-1 py-1 text-center">
                                                <span x-text="`${file.lastname || ''}, ${file.firstname || ''} ${file.middlename || ''} ${file.extension_name || ''}`.trim().replace(/\s+/g, ' ').replace(/\?/g, 'Ñ')"></span>
                                            </td>

                                            <td class="border border-black px-1 py-1 text-center" x-text="mapped_cash_advance_details.uacs_code"></td>
                                            <td class="border border-black px-1 py-1 text-center" x-text="file.assistance_type"></td>
                                            <td class="border border-black px-1 py-1 text-center" x-text="file.amount.toLocaleString()"></td>   
                                        </tr> 
                                    </template>
                                    <tr x-show="liquidationMode === 'Overall' && amount_refunded != 0">
                                        <td class="border border-black px-1 py-1 text-center" x-text="new Date(date_refunded).toLocaleDateString('en-US')"></td>
                                        <td class="border border-black px-1 py-1 text-center uppercase" x-text="official_receipt"></td>
                                        <td class="border border-black px-1 py-1 text-center"></td>
                                        <td class="border border-black px-1 py-1 text-center"></td>
                                        <td class="border border-black px-1 py-1 text-center">BUREAU OF TREASURY</td>
                                        <td class="border border-black px-1 py-1 text-center"></td>
                                        <td class="border border-black px-1 py-1 text-center">REFUND</td>
                                        <td class="border border-black px-1 py-1 text-center" x-text="parseInt(amount_refunded).toLocaleString()"></td>
                                    </tr>
                                </tbody>
                         
                                <tfoot>
                                    <tr x-show="liquidationMode === 'Bundle'">
                                        <td class="border border-black px-4 py-2 text-right" colspan="7"><strong>TOTAL</strong></td>
                                        <td class="border border-black px-4 py-2 text-center font-semibold" 
                                            x-text="(
                                                Object.values(filtered_file_data).flat().reduce((sum, file) => sum + file.amount, 0) 
                                            ).toLocaleString() + '.00'">
                                        </td>
                                    </tr>
                    
                                    <tr x-show="liquidationMode === 'Overall'">
                                        <td class="border border-black px-4 py-2 text-right" colspan="7"><strong>TOTAL</strong></td>
                                        <td class="border border-black px-4 py-2 text-center font-semibold" 
                                            x-text="(
                                                Object.values(filtered_file_data).flat().reduce((sum, file) => sum + file.amount, 0) 
                                                + parseInt(amount_refunded)
                                            ).toLocaleString() + '.00'">
                                        </td>
                                    </tr>
                            </tfoot>

                            </table>
                        </div>
                        <div class="mt-8 text-center px-20">
                            <p class="text-lg font-bold">CERTIFICATION</p>
                            <p class="mt-4">
                                I hereby certify on my official oath that this Report of Cash Disbursements in ___sheet(s)
                                is a <span x-text="liquidationType"></span>, true and correct statement of all cash disbursements during the period stated
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
                            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 print:hidden">
                                <span class="fas fa-print mr-2"></span>
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script>
    function rcd() {
        return {
            liquidationType: 'Full',
            liquidationMode: 'Overall',
            nameFrom: '',
            nameTo: '',
            cash_advance_id: null,
            file_list: [],
            file_data: [],
            cash_advance_details: {},
            mapped_cash_advance_details: {},
            loading: true,

            getUrlId() {
                const pathSegments = window.location.pathname.split('/');
                const id = pathSegments[pathSegments.length - 1];
                if (id) {
                    this.cash_advance_id = id;
                } else {
                    console.log('ID not found in the URL path');
                }       
            },

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
                    console.log(this.file_data);
                    this.filteredData();
                    this.getDates();
                    this.loading = false;
                } catch (error) {
                    console.error('Error fetching file data:', error);
                }
            },

            filtered_file_data: [],

            filteredData() {
                console.log();
                if(this.liquidationMode === 'Overall'){
                    this.filtered_file_data = this.file_data;
                    return;
                }

                if (!this.file_data || this.file_data.length === 0) {
                    console.log('No file data available for filtering.');
                    return;
                }

                if (!this.nameFrom && !this.nameTo) {
                    console.log('Showing all data since nameFrom or nameTo is empty.');
                    this.filtered_file_data = this.file_data;
                    return;
                }

                let fromIndex = -1;
                let toIndex = -1;

                const nameFromId = parseInt(this.nameFrom);
                const nameToId = parseInt(this.nameTo);

                this.file_data.forEach((file, index) => {
                    if (file.id === nameFromId) fromIndex = index;
                    if (file.id === nameToId) toIndex = index;
                });

                if (fromIndex !== -1 && toIndex !== -1 && fromIndex <= toIndex) {
                    this.filtered_file_data = this.file_data.slice(fromIndex, toIndex + 1);
                } else {
                    this.filtered_file_data = [];
                }

                console.log('Filtered data:', this.filtered_file_data);
            },

            firstDate: '',
            lastDate: '',

            getDates() {
                this.firstDate = '';
                this.lastDate = '';
                let dates = Object.values(this.file_data).flat().map(file => new Date(file.date_time_claimed));

                dates.sort((a, b) => a - b);

                let options = { year: 'numeric', month: 'long', day: 'numeric' };
                this.firstDate = dates[0].toLocaleDateString('en-US', options);
                this.lastDate = dates[dates.length - 1].toLocaleDateString('en-US', options);

            },

            refund_id: null,
            amount_refunded: 0,
            date_refunded: null,
            official_receipt: null,

            async getRefundList() {
                if (!this.cash_advance_id) {
                    this.refund_id = null;
                    this.amount_refunded = 0;
                    this.date_refunded = null;
                    this.official_receipt = null;
                    return;
                }
                try {
                    const response = await axios.get(`/refund/show/${this.cash_advance_id}`);
                    
                    if (Array.isArray(response.data) && response.data.length > 0) {
                        const refund = response.data[0]; 
                        this.refund_id = refund.id;
                        this.amount_refunded = refund.amount_refunded;
                        this.date_refunded = refund.date_refunded;
                        this.official_receipt = refund.official_receipt;
                    } else {
                        this.refund_id = null;
                        this.amount_refunded = 0;
                        this.date_refunded = null;
                        this.official_receipt = null;
                    }
                } catch (error) {
                    console.error('Error fetching Refund Data:', error);
                }
            },

            // search sa dropdown sa per  bundle

            searchQuery: '',
            filteredNames: [],
            showDropdown: false,

            // Function to filter names based on user input
            filterNames() {
                if (!this.searchQuery) {
                    this.filteredNames = [];
                    this.showDropdown = false;
                } else {
                    this.filteredNames = this.file_data.filter(file => 
                        (file.lastname + ' ' + (file.firstname || '') + ' ' + (file.middlename || '') + ' ' + (file.extension_name || ''))
                            .toLowerCase()
                            .includes(this.searchQuery.toLowerCase())
                    );
                    this.showDropdown = true;
                }
            },

            // Function to select a name from the dropdown
            selectName(file) {
                this.searchQuery = file.lastname + ', ' + (file.firstname || '') + ' ' + (file.middlename || '') + ' ' + (file.extension_name || '');
                this.nameFrom = file.id;
                this.showDropdown = false;
            },

            init() {
                this.getUrlId();  
                this.getFileList();  
                this.getCashAdvanceDetails();
                this.getRefundList();
            }
        };
    }
</script>


