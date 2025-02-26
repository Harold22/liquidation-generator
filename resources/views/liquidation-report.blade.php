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
        <div x-data="report()" class="min-h-screen dark:bg-gray-900">
            <div class="p-6 text-sm">
                <div class="max-w-4xl mx-auto">

                    <div x-show="loading" class="w-full mt-4 flex justify-center items-center">
                        <div class="w-16 h-16 border-4 border-t-transparent border-blue-500 border-solid rounded-full animate-spin"></div>
                    </div>

                    <!-- start sa rcd -->
                    <div class="rounded p-4 overflow-hidden mt-4 border border-black">
                        <div class="flex justify-end">
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


