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
                .no-wrap {
                    white-space: nowrap !important; /* Prevents text from wrapping */
                    overflow: hidden;
                    text-overflow: ellipsis; /* Optional: Shows "..." if the text is too long */
                }
            }

        </style>

    </head>
    <body class="font-sans antialiased">
        <div x-data="report()" class="min-h-screen dark:bg-gray-900">
            <div class="p-6 text-sm ">
                <div class="max-w-4xl mx-auto">
                    <!-- loading -->
                    <div x-show="loading">
                        <x-spinner />
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center md:justify-start gap-2 print:hidden my-2">
                        <label for="liquidationType" class="text-sm font-medium text-gray-700 md:w-auto">
                            Liquidation Type:
                        </label>
                        <select id="liquidationType" name="liquidationType" 
                            x-model="liquidationType" 
                            class="text-sm border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 w-full md:w-48">
                            <option value="Full">Full</option>
                            <option value="Partial">Partial</option>
                        </select>
                    </div>
                  <!-- Header -->
                    <div class="flex border border-black ">
                        <div class="w-3/4 p-6 border-r border-black">
                            <div class="text-center mb-4">
                                <h2 class="text-lg font-bold uppercase">Liquidation Report</h2>
                                <p class="text-sm">Period Covered: <span x-text="firstDate === lastDate ? firstDate : firstDate + ' to ' + lastDate"></span></p>
                            </div>

                            <!-- Entity Details -->
                            <div>
                                <p><strong>Entity Name:</strong> Department of Social Welfare and Development FO XI</p>
                                <p><strong>Fund Cluster:</strong> <span class="inline-block border-b border-black min-w-[150px]">101</span></p>


                            </div>
                        </div>
                        <div class="w-1/4 text-sm no-wrap">
                            <div class="px-4 pt-4">Serial No.: <span class="border-b border-black px-12 inline-block"></span></div>
                            <div class="px-4 py-4">Date: <span class="border-b border-black px-12 inline-block"></span></div>
                            <div class="border-b border-black w-full"></div>
                            <div class="px-4 pb-5">Responsibility Code:</div>
                            <div class="px-4 pb-4">
                                <div class="border-b border-black w-full flex justify-center">
                                    <span x-text="mapped_cash_advance_details.responsibility_code"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Header -->
                    <div class="flex border-x border-black">
                        <div class="w-3/4 border-r border-black p-1">
                            <h3 class="font-semibold text-center">PARTICULARS</h3>
                        </div>
                        <div class="w-1/4 border-black p-1">
                            <h3 class="font-semibold text-center">AMOUNT</h3>
                        </div>
                    </div>

                    <!-- Table Content -->
                    <div class="flex items-stretch border border-black h-96">
                        <div class="w-3/4 p-6 border-r border-black flex flex-col">
                            <p class="mt-2">
                                <span x-text="liquidationType"></span> Liquidation for Assistance to Individual in Crisis Situation (AICS) in Davao City, 
                                as per supporting documents attached hereinto the amount of
                            </p>
                            <p class="font-bold" x-text="numberToWords(
                                Object.values(file_data).flat().reduce((sum, file) => sum + file.amount, 0)
                            ) + ' PESOS ONLY'">
                            </p>
                            <p class="font-bold" x-text="'₱ ' + (
                                    Object.values(file_data).flat().reduce((sum, file) => sum + file.amount, 0) 
                                ).toLocaleString() + '.00'">
                            </p>
                        </div>
                        <div class="w-1/4 p-6 flex items-start justify-center">
                            <h3 class="font-semibold text-center" x-text="'₱ ' + (
                                    Object.values(file_data).flat().reduce((sum, file) => sum + file.amount, 0) 
                                ).toLocaleString() + '.00'">
                            </h3>
                        </div>
                    </div>

                    <div class="flex border-x border-black">
                        <div class="w-3/4 border-r border-black p-1">
                            <h3 class="font-semibold ml-5">TOTAL AMOUNT SPENT</h3>
                        </div>
                        <div class="w-1/4 border-black p-1">
                            <h3 class="font-semibold text-center" x-text="'₱ ' + (
                                    Object.values(file_data).flat().reduce((sum, file) => sum + file.amount, 0) 
                                ).toLocaleString() + '.00'"></h3>
                        </div>
                    </div>
                    <div class="flex items-stretch border border-black">
                        <div class="w-3/4 px-6 py-2 border-r border-black flex flex-col">
                            <p><strong>TOTAL AMOUNT OF CASH ADVANCE PER</strong></p>
                            <p><strong>DV NO#: </strong><span x-text="mapped_cash_advance_details.dv_number"></span></p>
                            <p><strong>CHECK #: </strong><span x-text="mapped_cash_advance_details.check_number"></span></p>
                            <p><strong>DATED: </strong><span x-text="new Date(mapped_cash_advance_details.cash_advance_date).toLocaleDateString('en-US')"></span></p>
                        </div>
                        <div class="w-1/4 px-6 py-2 flex items-start justify-center">
                            <h3 class="font-semibold text-center" x-text="'₱ '+ Number(mapped_cash_advance_details.cash_advance_amount).toLocaleString() + '.00'"></h3>
                        </div>
                    </div>

                      <!-- Table Header -->
                    <div class="flex border-x border-b border-black">
                        <div class="w-3/4 border-r px-6 border-black p-1">
                            <h3>
                                AMOUNT REFUNDED PER OR NO.:
                                <span 
                                    class="uppercase font-semibold border-b border-black inline-block min-w-[120px]" 
                                    x-text="official_receipt ? official_receipt : '\u00A0'">
                                </span> 
                                DTD:
                                <span 
                                    class="uppercase font-semibold border-b border-black inline-block min-w-[120px]" 
                                    x-text="date_refunded ? date_refunded : '\u00A0'">
                                </span>
                            </h3>


                        </div>
                        <div class="w-1/4 border-black p-1">
                            <h3 class="font-semibold text-center" x-text="amount_refunded != 0 ?  '₱ ' + Number(amount_refunded).toLocaleString() + '.00' : '' "></h3>
                        </div>
                    </div>
                    <div class="flex border-x border-black">
                        <div class="w-3/4 px-6 border-r border-black p-1">
                            <h3>AMOUNT TO BE REIMBURSED</h3>
                        </div>
                    </div>

                    <div class="flex w-full border border-black">
                        <div class="flex-1 border-r border-black px-6 p-2 space-y-8">
                            <p><span class="font-semibold">A.</span> Certified: Correctness of the above data</p>
                            <p class="border-b border-black text-center w-3/4 mx-auto">&nbsp;</p>
                        </div>
                        <div class="flex-1 border-r border-black px-6 p-2 space-y-8">
                            <p><span class="font-semibold">B.</span> Certified: Purpose of travel / cash advance duly accomplished</p>
                            <p class="border-b border-black text-center w-3/4 mx-auto">&nbsp;</p>
                        </div>
                        <div class="flex-1 px-6 p-2 space-y-8">
                            <p><span class="font-semibold">C.</span> Certified: Supporting documents complete and proper</p>
                            <p class="border-b border-black text-center w-3/4 mx-auto">&nbsp;</p>
                        </div>
                    </div>

                    <div class="flex w-full border-x border-black">
                        <div class="flex-1 border-r border-black px-6 p-2">
                            <p class=" border-black text-center font-semibold" x-text="mapped_cash_advance_details.special_disbursing_officer"></p>
                        </div>
                        <div class="flex-1 border-r border-black px-6 p-2">
                            <p class=" border-black text-center font-semibold">GEMMA D. DELA CRUZ</p>
                        </div>
                        <div class="flex-1 px-6 p-2">
                            <p class=" border-black text-center font-semibold">JEANLY MAE C. BAUTISTA</p>
                        </div>
                    </div>
                    <div class="flex w-full border border-black">
                        <div class="flex-1 border-r border-black px-6 pb-4 pt-2 flex flex-col justify-between space-y-4">
                            <p class="text-center">SPECIAL DISBURSING OFFICER</p>   
                            <div class="flex items-center w-3/4 mx-auto gap-3 mt-auto">
                                <span>Date:</span>
                                <p class="border-b border-black flex-grow text-center">&nbsp;</p>
                            </div>
                        </div>
                        <div class="flex-1 border-r border-black px-6 pb-4 pt-2 flex flex-col justify-between space-y-4">
                            <p class="text-center">SWO V/PSD CHIEF</p>
                            <div class="flex items-center w-3/4 mx-auto gap-3 mt-auto">
                                <span>Date:</span>
                                <p class="border-b border-black flex-grow text-center">&nbsp;</p>
                            </div>
                        </div>
                        <div class="flex-1 px-6 pb-4 pt-2 flex flex-col justify-between space-y-4">
                            <p class="text-center">ACCOUNTANT</p>
                            <div class="flex items-center w-3/4 mx-auto gap-3">
                                <span>JEV No.:</span>
                                <p class="border-b border-black flex-grow text-center">&nbsp;</p>
                            </div>
                            <div class="flex items-center w-3/4 mx-auto gap-3 mt-auto">
                                <span>Date:</span>
                                <p class="border-b border-black flex-grow text-center">&nbsp;</p>
                            </div>
                        </div>
                    </div>

                <!-- Print Button -->
                <div class="flex justify-end mt-6 print:hidden">
                    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none">
                        Print
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('report', () => ({
        cash_advance_id: null,
        mapped_cash_advance_details: {},
        loading: true,
        file_data: [],
        liquidationType: 'Full',
        refund_id: null,
        amount_refunded: 0,
        date_refunded: null,
        official_receipt: null,
        firstDate: '',
        lastDate: '',

        getUrlId() {
            const pathSegments = window.location.pathname.split('/').filter(Boolean);
            const id = pathSegments[pathSegments.length - 1];

            if (id) {
                this.cash_advance_id = id;
                this.loading = false;
            } else {
                console.log('ID not found in the URL path');
            }
        },

        async getCashAdvanceDetails() {
            if (!this.cash_advance_id) return console.log('No cash advance ID');

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
                        check_number: details.check_number,
                        status: details.status,
                    };
                } else {
                    console.log('No cash advance details found.');
                }
            } catch (error) {
                console.error('Error fetching CA details:', error);
            }
        },

        async getRefundList() {
            if (!this.cash_advance_id) {
                this.resetRefundDetails();
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
                    this.resetRefundDetails();
                }
            } catch (error) {
                console.error('Error fetching Refund Data:', error);
            }
        },

        resetRefundDetails() {
            this.refund_id = null;
            this.amount_refunded = 0;
            this.date_refunded = null;
            this.official_receipt = null;
        },

        async getFileList() {
            if (!this.cash_advance_id) return console.log('No cash advance ID');

            try {
                const response = await axios.get(`/files/rcd/${this.cash_advance_id}`);
                this.file_list = response.data;
                
                if (this.file_list.length > 0) {
                    await this.getFileData(this.file_list);
                }
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
                
                this.file_data = Object.values(this.file_data)
                    .flat()
                    .sort((a, b) => new Date(a.date_time_claimed) - new Date(b.date_time_claimed));

                this.getDates();
                this.loading = false;
            } catch (error) {
                console.error('Error fetching file data:', error);
            }
        },

        getDates() {
            const dates = Object.values(this.file_data).flat().map(file => new Date(file.date_time_claimed));
            
            if (dates.length > 0) {
                dates.sort((a, b) => a - b);
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                this.firstDate = dates[0].toLocaleDateString('en-US', options);
                this.lastDate = dates[dates.length - 1].toLocaleDateString('en-US', options);
            } else {
                this.firstDate = '';
                this.lastDate = '';
            }
        },

        numberToWords(num) {
            if (num === 0) return "ZERO";

            const belowTwenty = ["", "ONE", "TWO", "THREE", "FOUR", "FIVE", "SIX", "SEVEN", "EIGHT", "NINE", "TEN", "ELEVEN", "TWELVE", "THIRTEEN", "FOURTEEN", "FIFTEEN", "SIXTEEN", "SEVENTEEN", "EIGHTEEN", "NINETEEN"];
            const tens = ["", "", "TWENTY", "THIRTY", "FORTY", "FIFTY", "SIXTY", "SEVENTY", "EIGHTY", "NINETY"];
            const thousands = ["", "THOUSAND", "MILLION", "BILLION"];

            function convertChunk(n) {
                if (n < 20) return belowTwenty[n];
                if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 !== 0 ? " " + belowTwenty[n % 10] : "");
                return belowTwenty[Math.floor(n / 100)] + " HUNDRED" + (n % 100 !== 0 ? " " + convertChunk(n % 100) : "");
            }

            let word = "";
            let i = 0;

            while (num > 0) {
                if (num % 1000 !== 0) {
                    word = convertChunk(num % 1000) + " " + thousands[i] + (word ? " " + word : "");
                }
                num = Math.floor(num / 1000);
                i++;
            }

            return word.trim();
        },

        init() {
            this.getUrlId();
            this.getCashAdvanceDetails();
            this.getRefundList();
            this.getFileList();
        }
    }));
});

</script>


