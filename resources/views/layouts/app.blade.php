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
            }
        </style>
    </head>
    <body x-data="{ sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') || 'true') }"
        x-init="$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val))"
        class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <x-layouts.sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col transition-all duration-300"
                :class="sidebarOpen ? 'ml-64' : 'ml-20'">
                
                <!-- Top Navigation (pass pageTitle here) -->
                <x-layouts.navigation>
                      {{ $header }}
                </x-layouts.navigation>

                <!-- Page Content -->
                <main class="flex-1 mt-12 p-4 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>


</html>



  