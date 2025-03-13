<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div x-data="dashboard()" class="py-8">
        <!-- Loading Indicator -->
        <div x-show="loading">
            <x-spinner />
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
             <!-- Title and Year Filter -->
             <div class="flex justify-between items-center bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                <h3 class="text-xl font-bold text-blue-500">Dashboard Overview</h3>
                <div class="flex items-center gap-3">
                    <label for="year" class="text-md text-gray-700 dark:text-gray-300">Select Year:</label>
                    <input type="number" id="year" x-model="year" @change="handleLoadingCharts(year)" 
                        class="border dark:bg-gray-700 dark:text-white px-2 py-1 rounded-md w-24">
                </div>
            </div>
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

              <!-- Total Beneficiaries -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 flex items-center gap-4 border border-gray-300 dark:border-gray-700">
                    <svg class="w-16 h-16 text-blue-500 dark:text-blue-400 flex-shrink-0" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path d="M8.96991 12.89C10.6599 12.89 12.0299 11.5558 12.0299 9.91C12.0299 8.2642 10.6599 6.93 8.96991 6.93C7.27992 6.93 5.90991 8.2642 5.90991 9.91C5.90991 11.5558 7.27992 12.89 8.96991 12.89Z"
                                stroke="currentColor" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                            <path d="M12.93 13.1C14.37 14.18 15.32 15.84 15.42 17.72C15.43 17.91 15.27 18.07 15.08 18.07H2.84996C2.64996 18.07 2.48996 17.91 2.49996 17.72C2.60996 15.84 3.55997 14.18 5.00997 13.1"
                                stroke="currentColor" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                            <path d="M11.03 12.11H11.02" stroke="currentColor" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                            <path d="M17.1299 14.56C18.2787 14.56 19.2099 13.6556 19.2099 12.54C19.2099 11.4244 18.2787 10.52 17.1299 10.52C15.9812 10.52 15.0499 11.4244 15.0499 12.54C15.0499 13.6556 15.9812 14.56 17.1299 14.56Z"
                                stroke="currentColor" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                            <path d="M20.3 15.13C20.99 15.84 21.44 16.79 21.5 17.83C21.51 17.97 21.4 18.07 21.26 18.07H17.38"
                                stroke="currentColor" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                        </g>
                    </svg>          
                    <div>
                        <h2 class="text-lg font-medium text-blue-600 dark:text-blue-400">Total Beneficiaries</h2>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100" x-text="Number(totalBeneficiaries).toLocaleString()"></p>
                    </div>
                </div>


                <!-- Total Cash Advances -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 flex items-center gap-4 border border-gray-300 dark:border-gray-700">
                    <svg class="w-12 h-12 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="5" width="18" height="14" rx="2" ry="2" stroke-linecap="round" stroke-linejoin="round"></rect>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M12 5v10"></path>
                    </svg>

                    <div>
                        <h2 class="text-lg font-medium text-green-600 dark:text-green-400">Total CA Amount</h2>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100" x-text="'₱' + Number(totalCashAdvances).toLocaleString()"></p>
                    </div>
                </div>

                <!-- Liquidated Amount -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 flex items-center gap-4 border border-gray-300 dark:border-gray-700">
                    <svg class="w-12 h-12 text-yellow-600 dark:yellow-green-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" 
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="10" cy="10" r="8" stroke-linecap="round" stroke-linejoin="round"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h4M8 10h4M10 4v12"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 14l2 2 4-4"></path>
                    </svg>
                        
                    <div>
                        <h2 class="text-lg font-medium text-yellow-600 dark:text-yellow-400">Liquidated Amount</h2>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100" x-text="'₱' + Number(totalLiquidatedCashAdvance).toLocaleString()">0</p>
                    </div>
                </div>

                <!-- Unliquidated Amount -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 flex items-center gap-4 border border-gray-300 dark:border-gray-700">
                    <svg class="w-12 h-12 text-red-500 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.38 0 2.5 1.12 2.5 2.5S13.38 13 12 13s-2.5-1.12-2.5-2.5S10.62 8 12 8zM12 3c5 0 9 4 9 9s-4 9-9 9-9-4-9-9 4-9 9-9zM16.5 15h-9"></path>
                    </svg>
                    <div>
                        <h2 class="text-lg font-medium text-red-600 dark:text-red-400">Unliquidated Amount</h2>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100" x-text="'₱' + Number(totalUnliquidatedCashAdvance).toLocaleString()">0</p>
                    </div>
                </div>

            </div>

          <!-- Charts Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 2xl:grid-cols-2 gap-6">
                <!-- Summary Cards -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 flex flex-col gap-3">
                    
                    <!-- Number of Cash Advances -->
                    <div class="bg-white shadow-md rounded-lg p-3 flex items-center justify-between border border-gray-200 dark:border-gray-700 h-16 md:h-20 flex-wrap overflow-hidden">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-500 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M9 21V9m4 12V3m4 18v-6"></path>
                            </svg>
                            <h2 class="text-md md:text-lg font-medium text-gray-600 dark:text-gray-300 min-w-[150px] text-left">
                                Number of Cash Advances
                            </h2>
                        </div>
                        <p class="text-xl font-bold text-gray-800 dark:text-gray-100 w-28 text-center" x-text="totalCashAdvancesNumber"></p>
                        <span class="text-blue-500 font-semibold text-xl min-w-fit whitespace-nowrap">
                            100%
                        </span>
                    </div>

                    <!-- Unliquidated Cash Advances -->
                    <div class="bg-white shadow-md rounded-lg p-3 flex items-center justify-between border border-gray-200 dark:border-gray-700 h-16 md:h-20 flex-wrap overflow-hidden">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <h2 class="text-md md:text-lg font-medium text-gray-600 dark:text-gray-300 min-w-[150px] text-left">
                                Unliquidated Cash Advances
                            </h2>
                        </div>
                        <p class="text-xl font-bold text-gray-800 dark:text-gray-100 w-28 text-center" x-text="totalUnliquidatedNumber"></p>
                        <span class="text-red-500 font-semibold text-xl min-w-fit whitespace-nowrap">
                            <span x-text="unliquidatedPercentage + '%'"></span>
                            <span class="inline-block" x-show="unliquidatedPercentage > 0 && unliquidatedPercentage < 100">▼</span>
                            <span class="inline-block" x-show="unliquidatedPercentage == 0 || unliquidatedPercentage == 100">—</span>
                        </span>
                    </div>

                    <!-- Liquidated Cash Advances -->
                    <div class="bg-white shadow-md rounded-lg p-3 flex items-center justify-between border border-gray-200 dark:border-gray-700 h-16 md:h-20 flex-wrap overflow-hidden">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <h2 class="text-md md:text-lg font-medium text-gray-600 dark:text-gray-300 min-w-[150px] text-left">
                                Liquidated Cash Advances
                            </h2>
                        </div>
                        <p class="text-xl font-bold text-gray-800 dark:text-gray-100 w-28 text-center" x-text="totalLiquidatedNumber"></p>
                        <span class="text-green-500 font-semibold text-xl min-w-fit whitespace-nowrap">
                            <span x-text="liquidatedPercentage + '%'"></span>
                            <span class="inline-block" x-show="liquidatedPercentage > 0">▲</span>
                            <span class="inline-block" x-show="liquidatedPercentage == 0">—</span>
                        </span>
                    </div>

                </div>

                <!-- Pie Chart -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Liquidation of Cash Advances</h2>
                    <div class="h-48 w-full">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>

                <!-- Bar Chart -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Number of Beneficiaries</h2>
                    <canvas id="barChart"></canvas>
                </div>

                <!-- Line Chart -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Total Cash Advances</h2>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboard', () => ({
                
                year: new Date().getFullYear(),
                loading: false,

                beneficiaries: [],
                beneficiariesInstance: null,

                cashAdvances: [],
                cashAdvanceInstance: null,

                init() {
                    this.handleLoadingCharts(this.year);
                },

                async handleLoadingCharts(year) {
                    this.loading = true; 

                    await Promise.all([
                        this.getCashAdvancePerMonth(year),
                        this.getBeneficiariesPerMonth(year),
                        this.getSDOStatusPerYear(year),
                        this.fetchTotalBeneficiaries(year),
                        this.fetchTotalCashAdvances(year),
                        this.getCashAdvanceSummary(year),
                    ]);

                    this.loading = false; 
                },

                // START SA BENEFICIARIES
                async getBeneficiariesPerMonth(year) {
                    try {
                        const response = await fetch(`/dashboard/get-beneficiaries/${year}`);
                        const data = await response.json();

                        this.beneficiaries = this.beneficiariesMonths(data);
                        this.beneficiariesChart();
                    } catch (error) {
                        console.error('Error fetching beneficiaries data:', error);
                    }
                },

                beneficiariesMonths(data) {
                    const allMonths = [
                        'January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ];

                    const dataMap = Object.fromEntries(data.map(item => [item.month, item.beneficiaries_count]));

                    return allMonths.map(month => ({
                        month,
                        beneficiaries_count: dataMap[month] || 0
                    }));
                },

                beneficiariesChart() {
                    const ctx = document.getElementById('barChart').getContext('2d');

                    if (this.beneficiariesInstance) {
                        this.beneficiariesInstance.destroy();
                        this.beneficiariesInstance = null;
                    }

                    this.beneficiariesInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: this.beneficiaries.map(item => item.month),
                            datasets: [{
                                label: 'Total Number of Beneficiaries',
                                data: this.beneficiaries.map(item => item.beneficiaries_count),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: true } },
                            scales: { y: { beginAtZero: true } }
                        },
                    });
                },
                // END SA BENEFICIARIES

                // SUGOD SA CASH ADVANCE
                async getCashAdvancePerMonth(year) {
                    try {
                        const response = await fetch(`/dashboard/get-cash-advances/${year}`);
                        const data = await response.json();

                        this.cashAdvances = this.cashAdvanceMonths(data);
                        this.cashAdvanceChart();
                    } catch (error) {
                        console.error('Error fetching cash advances data:', error);
                    }
                },

                cashAdvanceMonths(data) {
                    const allMonths = [
                        'January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ];

                    const dataMap = Object.fromEntries(data.map(item => [item.month, item.total_amount]));

                    return allMonths.map(month => ({
                        month,
                        total_amount: dataMap[month] || 0
                    }));
                },

                cashAdvanceChart() {
                    const ctx = document.getElementById('lineChart').getContext('2d');

                    if (this.cashAdvanceInstance) {
                        this.cashAdvanceInstance.destroy();
                        this.cashAdvanceInstance = null;
                    }

                    this.cashAdvanceInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.cashAdvances.map(item => item.month),
                            datasets: [{
                                label: 'Total Amount Cash Advances',
                                data: this.cashAdvances.map(item => item.total_amount),
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                tension: 0.4,
                                fill: true,
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: true } },
                            scales: { y: { beginAtZero: true } }
                        },
                    });
                },
                // END SA CASH ADVANCE

                // START SDO STATUS
                async getSDOStatusPerYear(year) {
                    try {
                        const response = await fetch(`/dashboard/get-sdo-status/${year}`);
                        const data = await response.json();

                        // Aggregate totals for the whole year
                        const totalLiquidated = data.reduce((sum, item) => sum + parseInt(item.liquidated || 0), 0);
                        const totalUnliquidated = data.reduce((sum, item) => sum + parseInt(item.unliquidated || 0), 0);

                        this.sdoStatus = { liquidated: totalLiquidated, unliquidated: totalUnliquidated };
                        this.sdoStatusChart();
                    } catch (error) {
                        console.error('Error fetching SDO status data:', error);
                    }
                },

                sdoStatus: { liquidated: 0, unliquidated: 0 },
                pieChartInstance: null,

                sdoStatusChart() {
                const ctx = document.getElementById('doughnutChart').getContext('2d');

                if (this.pieChartInstance) {
                    this.pieChartInstance.destroy(); // Destroy previous instance
                }

                this.pieChartInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Liquidated', 'Unliquidated'],
                        datasets: [
                            {
                                label: 'Cash Advance Status',
                                data: [this.sdoStatus.liquidated, this.sdoStatus.unliquidated],
                                backgroundColor: ['#4CAF50', '#F44336'], // Updated colors
                                borderColor: ['#388E3C', '#D32F2F'], // Slightly darker border for better distinction
                                borderWidth: 1.5
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true },
                        },
                    },
                });
            },

                //END SDO STATUS
                
                totalBeneficiaries: 0,

                async fetchTotalBeneficiaries(year) {
                    try {
                        const response = await fetch(`/dashboard/get-total-beneficiaries/${year}`);
                        const data = await response.json();
                        this.totalBeneficiaries = data.total_beneficiaries; // Update Alpine property
                    } catch (error) {
                        console.error("Error fetching total beneficiaries:", error);
                    }
                },

                totalCashAdvances: 0,
                totalLiquidatedCashAdvance: 0,
                totalUnliquidatedCashAdvance: 0,

                async fetchTotalCashAdvances(year) {
                    try {
                        const response = await fetch(`/dashboard/get-total-cash-advances/${year}`);
                        const data = await response.json();
                        this.totalCashAdvances = data.total_cash_advances;
                        this.totalLiquidatedCashAdvance = data.total_liquidated;
                        this.totalUnliquidatedCashAdvance = data.total_unliquidated;
                    } catch (error) {
                        console.error("Error fetching total cash advances:", error);
                    }
                },

                totalCashAdvancesNumber: 0,
                totalLiquidatedNumber: 0,
                totalUnliquidatedNumber: 0,
                liquidatedPercentage: 0,
                unliquidatedPercentage: 0,

                async getCashAdvanceSummary(year) {
                    try {
                        const response = await fetch(`/dashboard/cash-advances-summary/${year}`);
                        const data = await response.json();

                        this.totalCashAdvancesNumber = data.total_cash_advances_number;
                        this.totalLiquidatedNumber = data.total_liquidated_number;
                        this.totalUnliquidatedNumber = data.total_unliquidated_number;
                        this.liquidatedPercentage = data.liquidated_percentage;
                        this.unliquidatedPercentage = data.unliquidated_percentage;
                    } catch (error) {
                        console.error('Error fetching cash advance summary:', error);
                    }
                },

            }));
        });
    </script>

</x-app-layout>
