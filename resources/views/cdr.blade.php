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
        <div x-data="cdr()" class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="p-6 text-sm">
                <div class="max-w-7xl mx-auto">
                    <!-- filtering -->
                    <div class="my-3 border rounded-lg p-3 no-print">
                        <p class="text-sm text-gray-500 mb-3">
                            Note: CDR is a monthly reporting.
                        </p>
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <!-- Group Month and Year on left -->
                            <div class="flex flex-wrap items-center gap-4">
                                  <!-- Year Selector -->
                                <div class="flex flex-col md:flex-row items-start md:items-center gap-2 w-full md:w-auto">
                                    <label for="year" class="text-sm font-medium text-gray-700">
                                        Enter Year:
                                    </label>
                                    <input
                                        type="number"
                                        id="year"
                                        name="year"
                                        x-model="year"
                                        @input="validateAndFetchYear()"
                                        placeholder="Enter Year"
                                        class="capitalize text-sm border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 w-full md:w-48"
                                    />
                                     <template x-if="errorMessage">
                                        <p class="text-sm text-red-500 mt-1" x-text="errorMessage"></p>
                                    </template>

                                </div>
                                <!-- Month Selector -->
                                 <div class="flex flex-col md:flex-row items-start md:items-center gap-2 w-full md:w-auto">
                                    <label for="month" class="text-sm font-medium text-gray-700">Select Month:</label>
                                    <select id="month" x-model="selectedMonth" @change="filterByMonth"
                                            class="text-sm border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-48">
                                        <template x-for="month in availableMonths" :key="month.key">
                                            <option :value="month.key" x-text="month.label"></option>
                                        </template>
                                    </select>
                                </div>

                            </div>

                            <!-- Print Button on Right -->
                            <div class="ml-auto">
                                <button onclick="window.print()"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md flex items-center gap-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 print:hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 8h-2V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2V10a2 2 0 00-2-2zm-6 0V6h-4v2H5v6h14V8h-4z" />
                                    </svg>
                                    <span class="text-sm font-medium">Print</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto my-10">
                    <!-- loading ni -->
                    <div x-show="loading">
                        <x-spinner />
                    </div>
                    <div class="flex justify-center">
                        <h1 class="text-xl font-bold">CASH DISBURSEMENT RECORD</h1>
                    </div>
                    <div class="flex justify-center">
                        <h1 class="text-md font-bold">
                            Period Covered: <span class="uppercase" x-text="selectedMonthLabelOnly"></span>
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
                                    <th class="items-center border-r border-black" colspan="3"><span class="uppercase underline" x-text="sdo_info.special_disbursing_officer"></span></th>
                                    <th class="border-r border-black" colspan="3"><span class="uppercase underline" x-text="sdo_info.position"></span></th>
                                    <th colspan="2"><span class="uppercase underline" x-text="sdo_info.station"></span></th>
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
                            </thead>
                            <tbody class="uppercase text-xs">
                                <template x-if="filteredList.length > 0">
                                    <template x-for="(row, index) in filteredList" :key="index">
                                        <tr :class="{ 'font-bold': row.isAdvance }">
                                            <!-- Date -->
                                            <td class="border border-black px-2 py-2 text-center"
                                                x-text="row.isAdvance && row.nature === 'BALANCE FORWARDED'
                                                    ? new Date(row.monthKey + '-01').toLocaleDateString('en-US')
                                                    : (row.isAdvance 
                                                        ? new Date(row.cash_advance_date).toLocaleDateString('en-US') 
                                                        : new Date(row.date_time_claimed).toLocaleDateString('en-US'))">
                                            </td>
                                            <!-- DV/ADA/etc -->
                                            <td class="border border-black px-2 py-2 text-center" x-text="row.dv_number"></td>

                                            <!-- Payee -->
                                            <td class="border border-black px-2 py-2 text-center uppercase w-[250px] break-words">
                                                <template x-if="row.isAdvance && row.nature === 'BALANCE FORWARDED'">
                                                    <span>BALANCE FORWARDED</span>
                                                </template>
                                                <template x-if="row.isAdvance && row.nature !== 'BALANCE FORWARDED'">
                                                    <span x-text="row.payee"></span>
                                                </template>
                                                <template x-if="!row.isAdvance">
                                                    <span x-text="`${row.lastname || ''}, ${row.firstname || ''} ${row.middlename || ''} ${row.extension_name || ''}`.trim().replace(/\s+/g, ' ').replace(/\?/g, 'Ñ')"></span>
                                                </template>
                                            </td>

                                            <!-- UACS -->
                                            <td class="border border-black px-2 py-2 text-center" x-text="(row.isAdvance && row.nature === 'BALANCE FORWARDED') ? '' : row.uacs_code"></td>

                                            <!-- Nature -->
                                            <td class="border border-black px-2 py-2 text-center uppercase" x-text="(row.isAdvance && row.nature === 'BALANCE FORWARDED') ? '' : (row.isAdvance ? row.nature : row.assistance_type)"></td>

                                            <!-- Cash Advance Received -->
                                            <td class="border border-black px-2 py-2 text-center" x-text="(row.ca_amount && row.nature !== 'BALANCE FORWARDED') ? row.ca_amount.toLocaleString() : ''"></td>

                                            <!-- Disbursement -->
                                            <td class="border border-black px-2 py-2 text-center" x-text="(row.disbursed && row.nature !== 'BALANCE FORWARDED') ? row.disbursed.toLocaleString() : ''"></td>

                                            <!-- Balance -->
                                            <td class="border border-black px-2 py-2 text-center" x-text="row.balance.toLocaleString()"></td>
                                        </tr>
                                    </template>
                                </template>

                                <template x-if="filteredList.length === 0 && lists.length > 0">
                                    <template x-for="n in 7" :key="n">
                                        <tr>
                                            <template x-if="n === 4">
                                                <td colspan="8" class="border border-black px-2 py-2 text-center text-red-500 font-semibold">
                                                    No transaction this month.
                                                </td>
                                            </template>
                                            <template x-if="n !== 4">
                                                <template x-for="i in 8" :key="i">
                                                    <td class="border border-black px-2 py-2 text-center text-gray-300">—</td>
                                                </template>
                                            </template>
                                        </tr>
                                    </template>
                                </template>

                                <template x-if="lists.length === 0">
                                    <tr>
                                        <td colspan="8" class="border border-black px-2 py-2 text-center text-red-500 font-semibold">
                                            No records found for the selected year.
                                        </td>
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
                        <span class="uppercase font-semibold" x-text="sdo_info.special_disbursing_officer"></span>
                        <div class="mt-2 border-t border-black w-1/2 mx-auto"></div> 
                        <p>Name and Signature of Disbursing Officer/Cashier</p>
                    </div>
                    <div class="flex mt-10 justify-between text-center px-18 mx-40">
                        <div class="w-1/3">
                            <span class="uppercase font-semibold" x-text="sdo_info.position"></span>
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
        sdos_id: null,
        lists: [],
        filteredList: [],
        cash_advances: [],
        sdo_info: [],
        loading: false,
        year: null,
        errorMessage: '',
        selectedMonth: '',
        selectedMonthLabel: '',
        availableMonths: [],

        get selectedMonthLabelOnly() {
            if (!this.selectedMonthLabel) return '';
            return this.selectedMonthLabel.split(' ')[0];
        },

        getUrlId() {
            const pathSegments = window.location.pathname.split('/');
            this.sdos_id = pathSegments[pathSegments.length - 1];
        },

        validateAndFetchYear() {
            const yearStr = this.year.toString().trim();
            if (!/^\d{4}$/.test(yearStr)) {
                this.errorMessage = 'Invalid Year.';
                return;
            }
            this.errorMessage = '';
            this.getAllCashAdvanceDetails(Number(yearStr));
        },

        async getAllCashAdvanceDetails(year) {
            this.loading = true;
            this.cash_advances = [];
            this.lists = [];
            if (!this.sdos_id) return;

            try {
                const response = await axios.get(`/sdo/get-cash-advances/${this.sdos_id}/${year}`);
                const sdo = response.data;
                const middleInitial = sdo.middlename ? sdo.middlename.charAt(0) + '.' : '';

                this.sdo_info = {
                    special_disbursing_officer: [sdo.firstname, middleInitial, sdo.lastname, sdo.extension_name].filter(Boolean).join(' '),
                    position: sdo.position,
                    station: sdo.station
                };

                let allMonths = {};

                sdo.cash_advances.forEach((ca) => {
                    let caAmount = parseFloat(ca.cash_advance_amount);
                    let balance = caAmount;
                    let disbursements = [];

                    ca.sorted_file_data.forEach(file => {
                        const claimDate = new Date(file.date_time_claimed);
                        const monthKey = `${claimDate.getFullYear()}-${String(claimDate.getMonth() + 1).padStart(2, '0')}`;

                        disbursements.push({
                            isAdvance: false,
                            date_time_claimed: file.date_time_claimed,
                            dv_number: ca.dv_number,
                            lastname: file.lastname,
                            firstname: file.firstname,
                            middlename: file.middlename,
                            extension_name: file.extension_name,
                            uacs_code: ca.uacs_code,
                            assistance_type: file.assistance_type,
                            ca_amount: 0,
                            disbursed: file.amount,
                            balance: null,
                            ca_id: ca.id,
                            monthKey
                        });
                    });

                    disbursements.sort((a, b) => new Date(a.date_time_claimed) - new Date(b.date_time_claimed));

                    let monthUsed = new Set();

                    disbursements.forEach(d => {
                        const mKey = d.monthKey;
                        if (!monthUsed.has(mKey)) {
                            monthUsed.add(mKey);
                            if (!allMonths[mKey]) allMonths[mKey] = [];
                            const isFirstMonth = monthUsed.size === 1;
                            allMonths[mKey].unshift({
                                isAdvance: true,
                                cash_advance_date: ca.cash_advance_date,
                                dv_number: ca.ors_burs_number,
                                payee: this.sdo_info.special_disbursing_officer,
                                uacs_code: ca.uacs_code,
                                nature: isFirstMonth ? 'CASH ADVANCE RE-AICS GRANT' : 'BALANCE FORWARDED',
                                ca_amount: isFirstMonth ? caAmount : 0,
                                disbursed: 0,
                                balance: isFirstMonth ? caAmount : balance,
                                monthKey: mKey
                            });
                        }

                        balance -= d.disbursed;
                        d.balance = balance;
                        allMonths[mKey].push(d);
                    });
                });

                const sortedKeys = Object.keys(allMonths).sort();
                let finalList = [];
                let runningBalance = 0;

                sortedKeys.forEach(monthKey => {
                    allMonths[monthKey].forEach(entry => {
                        if (entry.isAdvance && entry.nature === 'CASH ADVANCE RE-AICS GRANT') {
                            runningBalance += entry.ca_amount;
                        } else if (!entry.isAdvance) {
                            runningBalance -= entry.disbursed;
                        }
                        entry.balance = runningBalance;
                        finalList.push(entry);
                    });
                });

                this.lists = finalList;

                // Always show 12 months
                const fullMonthList = Array.from({ length: 12 }, (_, i) => {
                    const date = new Date(this.year, i);
                    const key = `${date.getFullYear()}-${String(i + 1).padStart(2, '0')}`;
                    const label = date.toLocaleString('default', { month: 'long', year: 'numeric' });
                    return { key, label };
                });

                this.availableMonths = fullMonthList;

                if (this.availableMonths.length > 0) {
                    this.selectedMonth = this.availableMonths[0].key;
                }

                this.filterByMonth();

            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },

        filterByMonth() {
            if (!this.selectedMonth) {
                this.filteredList = [];
                this.selectedMonthLabel = '';
                return;
            }

            this.filteredList = this.lists.filter(item => item.monthKey === this.selectedMonth);

            const match = this.availableMonths.find(m => m.key === this.selectedMonth);
            this.selectedMonthLabel = match ? match.label : '';
        },

        init() {
            this.getUrlId();
            this.year = new Date().getFullYear();
            this.getAllCashAdvanceDetails(this.year);
        }

    }));
});
</script>



