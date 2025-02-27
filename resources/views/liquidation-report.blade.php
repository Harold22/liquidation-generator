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
                    <div x-show="loading" class="w-full mt-4 flex justify-center items-center">
                        <div class="w-16 h-16 border-4 border-t-transparent border-blue-500 border-solid rounded-full animate-spin"></div>
                    </div>
                  <!-- Header -->
                    <div class="flex border border-black ">
                        <div class="w-3/4 p-6 border-r border-black">
                            <div class="text-center mb-4">
                                <h2 class="text-lg font-bold uppercase">Liquidation Report</h2>
                                <p class="text-sm">Period Covered: December 18, 2024 - December 19, 2024</p>
                            </div>

                            <!-- Entity Details -->
                            <div>
                                <p><strong>Entity Name:</strong> Department of Social Welfare and Development FO XI</p>
                                <p><strong>Fund Cluster:</strong> ______101______</p>
                            </div>
                        </div>
                        <div class="w-1/4 text-sm no-wrap">
                            <div class="px-4 pt-4">Serial No.: <span class="border-b border-black px-12 inline-block"></span></div>
                            <div class="px-4 py-4">Date: <span class="border-b border-black px-12 inline-block"></span></div>
                            <div class="border-b border-black w-full"></div>
                            <div class="px-4 pb-5">Responsibility Code:</div>
                            <div class="px-4 pb-4">
                                <span class="border-b border-black w-full block"></span>
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
                                Partial Liquidation for Assistance to Individual in Crisis Situation (AICS) in Davao City, 
                                as per supporting documents attached hereinto the amount of
                            </p>
                            <p class="text-lg font-bold">THREE MILLION PESOS ONLY</p>
                            <p class="text-lg font-bold">₱3,000,000.00</p>
                        </div>
                        <div class="w-1/4 p-6 flex items-start justify-center">
                            <h3 class="font-semibold text-center">₱3,000,000.00</h3>
                        </div>
                    </div>

                    <div class="flex border-x border-black">
                        <div class="w-3/4 border-r border-black p-1">
                            <h3 class="font-semibold ml-5">TOTAL AMOUNT SPENT</h3>
                        </div>
                        <div class="w-1/4 border-black p-1">
                            <h3 class="font-semibold text-center">₱3,000,000.00</h3>
                        </div>
                    </div>
                    <div class="flex items-stretch border border-black">
                        <div class="w-3/4 px-6 py-2 border-r border-black flex flex-col">
                            <p><strong>TOTAL AMOUNT OF CASH ADVANCE PER</strong></p>
                            <p><strong>DV NO#:</strong> 21.12.13906</p>
                            <p><strong>CHECK #:</strong> 117203</p>
                            <p><strong>DATED:</strong> 12/04/2024</p>
                        </div>
                        <div class="w-1/4 px-6 py-2 flex items-start justify-center">
                            <h3 class="font-semibold text-center">₱3,000,000.00</h3>
                        </div>
                    </div>

                      <!-- Table Header -->
                    <div class="flex border-x border-b border-black">
                        <div class="w-3/4 border-r px-6 border-black p-1">
                            <h3>AMOUNT REFUNDED PER OR NO.______________________DTD:</h3>
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
                            <p class=" border-black text-center font-semibold">JENNIFER AMISCUA</p>
                        </div>
                        <div class="flex-1 border-r border-black px-6 p-2">
                            <p class=" border-black text-center font-semibold">GEMMA D. DELA CRUZ</p>
                        </div>
                        <div class="flex-1 px-6 p-2">
                            <p class=" border-black text-center font-semibold">JEANY MAE C. BAUTISTA</p>
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
            loading: true,

            getUrlId() {
                const pathSegments = window.location.pathname.split('/');
                const id = pathSegments[pathSegments.length - 1];
                if (id) {
                    this.cash_advance_id = id;
                    this.loading = false;
                } else {
                    console.log('ID not found in the URL path');
                }       
            },

            init() {
                this.getUrlId();  
            }
        }));
    });
</script>


