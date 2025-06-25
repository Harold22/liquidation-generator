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
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">
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
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-3 border border-gray-300 dark:border-gray-700">
                    <div class="flex items-center gap-3 justify-center">
                        <span class="text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </span>
                        <h2 class="text-lg font-medium text-blue-600 dark:text-blue-400">Total Beneficiaries</h2>
                    </div>
                    <div class="flex justify-center">
                        <p class="mt-2 text-2xl font-medium text-gray-900 dark:text-gray-100 text-center overflow-x-auto whitespace-nowrap"
                        x-text="Number(totalBeneficiaries).toLocaleString()">0</p>
                    </div>
                </div>

                <!-- Total Cash Advances -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-3 border border-gray-300 dark:border-gray-700">
                    <div class="flex items-center gap-3 justify-center">
                        <span class="text-yellow-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                        <h2 class="text-lg font-medium text-yellow-600 dark:text-yellow-400">Total CA Amount</h2>
                    </div>
                    <div class="flex justify-center">
                        <p class="mt-2 text-2xl font-medium text-gray-900 dark:text-gray-100 text-center overflow-x-auto whitespace-nowrap"
                        x-text="'₱' + Number(totalCashAdvances).toLocaleString()">₱0</p>
                    </div>
                </div>

                <!-- Liquidated Amount -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-3 border border-gray-300 dark:border-gray-700">
                    <div class="flex items-center gap-3 justify-center">
                        <span class="text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </span>
                        <h2 class="text-lg font-medium text-green-600 dark:text-green-400">Liquidated Amount</h2>
                    </div>
                    <div class="flex justify-center">
                        <p class="mt-2 text-2xl font-medium text-gray-900 dark:text-gray-100 text-center overflow-x-auto whitespace-nowrap"
                        x-text="'₱' + Number(totalLiquidatedCashAdvance).toLocaleString()">₱0</p>
                    </div>
                </div>

                <!-- Unliquidated Amount -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-3 border border-gray-300 dark:border-gray-700">
                    <div class="flex items-center gap-3 justify-center">
                        <span class="text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                        </span>
                        <h2 class="text-lg font-medium text-red-600 dark:text-red-400">Unliquidated Amount</h2>
                    </div>
                    <div class="flex justify-center">
                        <p class="mt-2 text-2xl font-medium text-gray-900 dark:text-gray-100 text-center overflow-x-auto whitespace-nowrap"
                        x-text="'₱' + Number(totalUnliquidatedCashAdvance).toLocaleString()">₱0</p>
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
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

                    if(!year){
                        return;
                    }
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
                        const response = await axios.get(`/dashboard/get-beneficiaries/${year}`);
                        const data = await response.data;

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
                        const response = await axios.get(`/dashboard/get-cash-advances/${year}`);
                        const data = await response.data;

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
                        const response = await axios.get(`/dashboard/get-sdo-status/${year}`);
                        const data = await response.data;

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
                        const response = await axios.get(`/dashboard/get-total-beneficiaries/${year}`);
                        const data = await response.data;
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
                        const response = await axios.get(`/dashboard/get-total-cash-advances/${year}`);
                        const data = await response.data;
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
                        const response = await axios.get(`/dashboard/cash-advances-summary/${year}`);
                        const data = await response.data;

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
