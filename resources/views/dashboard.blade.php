<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div x-data="dashboard()" class="py-12">
        <!-- Loading Indicator -->
        <div x-show="loading" x-transition.opacity class="fixed top-0 left-0 w-full h-2 bg-gray-900 bg-opacity-10 backdrop-blur-md z-50">
                    <div class="h-2 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 animate-pulse rounded-full shadow-lg"></div>
                </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
             <!-- Title and Year Filter -->
             <div class="flex justify-between items-center bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Dashboard Overview</h3>
                <div class="flex items-center gap-3">
                    <label for="year" class="text-md text-gray-700 dark:text-gray-300">Select Year:</label>
                    <input type="number" id="year" x-model="year" @change="handleLoadingCharts(year)" 
                        class="border dark:bg-gray-700 dark:text-white px-2 py-1 rounded-md w-24">
                </div>
            </div>
             <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                  <!-- Total Beneficiaries -->
                <div class="bg-green-500 text-white shadow-sm sm:rounded-lg p-4 text-center">
                    <h2 class="text-lg font-medium">Total Beneficiaries</h2>
                    <p class="text-2xl font-bold" x-text="Number(totalBeneficiaries).toLocaleString()"></p>
                </div>
                <!-- Total Cash Advances -->
                <div class="bg-blue-500 text-white shadow-sm sm:rounded-lg p-4 text-center">
                    <h2 class="text-lg font-medium">Total Cash Advances</h2>
                    <p class="text-2xl font-bold" x-text="'₱'+ Number(totalCashAdvances).toLocaleString()"></p>
                </div>

                <!-- Total Liquidated -->
                <div class="bg-yellow-500 text-white shadow-sm sm:rounded-lg p-4 text-center">
                    <h2 class="text-lg font-medium">Liquidated Amount</h2>
                    <p class="text-2xl font-bold" x-text="'₱'+ Number(totalLiquidatedCashAdvance).toLocaleString()">0</p>
                </div>

                <!-- Total Liquidated -->
                <div class="bg-red-500 text-white shadow-sm sm:rounded-lg p-4 text-center">
                    <h2 class="text-lg font-medium">Unliquidated Amount</h2>
                    <p class="text-2xl font-bold" x-text="'₱'+ Number(totalUnliquidatedCashAdvance).toLocaleString()">0</p>
                </div>
            </div>
            <!-- Charts Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 2xl:grid-cols-3 gap-6">
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

                <!-- Pie Chart -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Liquidation of Cash Advances</h2>
                    <canvas id="pieChart"></canvas>
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
                    const ctx = document.getElementById('pieChart').getContext('2d');

                    if (this.pieChartInstance) {
                        this.pieChartInstance.destroy(); // Destroy previous instance
                    }

                    this.pieChartInstance = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Liquidated', 'Unliquidated'],
                            datasets: [
                                {
                                    label: 'Cash Advance Status',
                                    data: [this.sdoStatus.liquidated, this.sdoStatus.unliquidated],
                                    backgroundColor: ['#14B8A6', '#EAB308'],
                                },
                            ],
                        },
                        options: {
                            responsive: true,
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

            }));
        });
    </script>

</x-app-layout>
