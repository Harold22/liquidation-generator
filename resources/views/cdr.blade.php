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
                thead {
                    display: table-header-group;
                }
                
                th {
                    position: sticky;
                    top: 0;
                    background-color: white;
                    z-index: 2;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div x-data="rcd()" class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div x-show="loading" class="w-full mt-4 flex justify-center items-center">
                <div class="w-16 h-16 border-4 border-t-transparent border-blue-500 border-solid rounded-full animate-spin"></div>
            </div>
            <div class="p-6 text-sm">
                <div class="max-w-4xl mx-auto">
                    <div class="flex justify-center">
                        <h1 class="text-xl font-bold">CASH DISBURSEMENT RECORD</h1>
                    </div>
                    <div class="flex justify-center">
                        <h1 class="text-md font-bold">
                            Period Covered: 
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
                                <label for="sheet_no">Sheet No.:</label>
                                <span class="text-sm ml-2">1</span>
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
                                <tr>
                                    <th class="border border-black py-2" colspan="1" x-text="mapped_cash_advance_details.cash_advance_date"></th>
                                    <th class="border border-black py-2" colspan="1" x-text="mapped_cash_advance_details.ors_burs_number"></th>
                                    <th class="border border-black py-2" colspan="1" x-text="mapped_cash_advance_details.special_disbursing_officer"></th>
                                    <th class="border border-black py-2" colspan="1" x-text="mapped_cash_advance_details.uacs_code"></th>
                                    <th class="border border-black py-2" colspan="1"></th>
                                    <th class="border border-black py-2" colspan="1" x-text="(Number(mapped_cash_advance_details.cash_advance_amount) || 0).toLocaleString()"></th>
                                    <th class="border border-black py-2" colspan="1"></th>
                                    <th class="border border-black py-2" colspan="1" x-text="(Number(mapped_cash_advance_details.cash_advance_amount) || 0).toLocaleString()"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(fileList, fileId) in file_data" :key="fileId">
                                    <template x-for="file in fileList" :key="file.id">
                                        <tr>
                                            <td class="border border-black px-2 py-2 text-center" x-text="new Date(file.date_time_claimed).toLocaleDateString('en-US')"></td>
                                            <td class="border border-black px-2 py-2 text-center" x-text="mapped_cash_advance_details.dv_number"></td>
                                            <td class="border border-black px-2 py-2 text-center">
                                                <span x-text="`${file.lastname || ''}, ${file.firstname || ''} ${file.middlename || ''} ${file.extension_name || ''}`.trim().replace(/\s+/g, ' ').replace(/\?/g, 'Ñ')"></span>
                                            </td>
                                            <td class="border border-black px-2 py-2 text-center" x-text="mapped_cash_advance_details.uacs_code"></td>
                                            <td class="border border-black px-2 py-2 text-center uppercase" x-text="file.assistance_type"></td>
                                            <td class="border border-black px-2 py-2 text-center" x-text="mapped_cash_advance_details.ors_burs_number"></td>
                                            <td class="border border-black px-2 py-2 text-center" x-text="file.amount"></td>
                                            <td class="border border-black px-2 py-2 text-center" x-text="file.remaining_balance"></td>
                                        </tr>
                                    </template>
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
                        <button onclick="window.print()" class="print:hidden">
                            <span class="fas fa-print mr-2"></span>
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script>
    function rcd() {
        return {
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
           
            async getFileList() {
                if (!this.cash_advance_id) {
                    console.log('No cash advance id');
                    return;
                }
                try {
                    const response = await axios.get(`/files/rcd/${this.cash_advance_id}`);
                    this.file_list = response.data; 
                    console.log("File List", this.file_list);
                    
                    await this.getFileData(this.file_list);
                } catch (error) {
                    console.error('Error fetching file list:', error);
                }
            },

            // async getFileData(fileIds) {
            //     if (!fileIds || fileIds.length === 0) {
            //         console.log('No file IDs to fetch data for.');
            //         return;
            //     }
            //     try {
            //         const response = await axios.get(`/files/data/${fileIds.join(',')}`);
            //         this.file_data = response.data; 
            //         this.loading = false;
            //         console.log("File Data", this.file_data);
            //     } catch (error) {
            //         console.error('Error fetching file data:', error);
            //     }
            // },
            async getFileData(fileIds) {
                if (!fileIds || fileIds.length === 0) {
                    console.log('No file IDs to fetch data for.');
                    return;
                }

                try {
                    const response = await axios.get(`/files/data/${fileIds.join(',')}`);
                    console.log("Raw API Response:", response);

                    const fileData = Array.isArray(response.data)
                        ? response.data
                        : Object.values(response.data || {});

                    console.log("File Data Before Transformation:", fileData);

                    if (!fileData.length) {
                        console.log("No file data found.");
                        this.file_data = [];
                        return;
                    }

                    const cashAdvanceAmount = parseFloat(this.mapped_cash_advance_details.cash_advance_amount);

                    if (isNaN(cashAdvanceAmount)) {
                        console.error("Cash advance amount is missing or invalid.");
                        return;
                    }

                    let runningBalance = cashAdvanceAmount;

                    const transformedData = fileData.map(record => {
                        const amount = parseFloat(record.amount) || 0; 
                        const remainingBalance = runningBalance - amount; 
                        runningBalance = remainingBalance; 

                        return {
                            ...record,
                            remaining_balance: runningBalance,
                        };
                    });

                    this.file_data = transformedData;
                    console.log("Processed File Data with Remaining Balance:", this.file_data);

                    this.loading = false;
                } catch (error) {
                    console.error('Error fetching file data:', error);
                    this.file_data = []; 
                }
            },

            init() {
                this.getUrlId();  
                this.getFileList();  
                this.getCashAdvanceDetails();
            }
        };
    }
</script>


