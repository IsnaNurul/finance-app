<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LineChart from '@/Components/LineChart.vue';
import DoughnutChart from '@/Components/DoughnutChart.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    dashboardData: Object
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
};

const formatNumber = (number) => {
    return new Intl.NumberFormat('id-ID').format(number);
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Income Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Income</p>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        {{ formatCurrency(dashboardData?.summary?.current_income || 0) }}
                                    </p>
                                    <p class="text-sm" :class="dashboardData?.summary?.income_change >= 0 ? 'text-green-600' : 'text-red-600'">
                                        {{ dashboardData?.summary?.income_change >= 0 ? '+' : '' }}{{ dashboardData?.summary?.income_change || 0 }}% from last month
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expense Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Expense</p>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        {{ formatCurrency(dashboardData?.summary?.current_expense || 0) }}
                                    </p>
                                    <p class="text-sm" :class="dashboardData?.summary?.expense_change >= 0 ? 'text-red-600' : 'text-green-600'">
                                        {{ dashboardData?.summary?.expense_change >= 0 ? '+' : '' }}{{ dashboardData?.summary?.expense_change || 0 }}% from last month
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profit Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Net Profit</p>
                                    <p class="text-2xl font-semibold" :class="(dashboardData?.summary?.current_profit || 0) >= 0 ? 'text-green-600' : 'text-red-600'">
                                        {{ formatCurrency(dashboardData?.summary?.current_profit || 0) }}
                                    </p>
                                    <p class="text-sm text-gray-500">This month</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Transactions</p>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        {{ formatNumber(dashboardData?.summary?.total_transactions || 0) }}
                                    </p>
                                    <p class="text-sm text-gray-500">This month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Monthly Transactions Chart -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Transactions</h3>
                            <LineChart 
                                :data="dashboardData?.monthly_transactions || []" 
                                title="Income vs Expense Trend"
                            />
                        </div>
                    </div>

                    <!-- Top Categories Chart -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Top Expense Categories</h3>
                            <DoughnutChart 
                                :data="dashboardData?.top_categories || []" 
                                title="Expense Distribution"
                            />
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions and Account Balances -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Transactions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Transactions</h3>
                            <div class="space-y-4">
                                <div v-for="transaction in dashboardData?.recent_transactions || []" :key="transaction.id" 
                                     class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ transaction.description }}</p>
                                        <p class="text-xs text-gray-500">{{ transaction.account_name }} • {{ transaction.category_name }}</p>
                                        <p class="text-xs text-gray-400">{{ new Date(transaction.date).toLocaleDateString('id-ID') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p v-if="transaction.debit > 0" class="text-sm font-medium text-red-600">
                                            -{{ formatCurrency(transaction.debit) }}
                                        </p>
                                        <p v-if="transaction.credit > 0" class="text-sm font-medium text-green-600">
                                            +{{ formatCurrency(transaction.credit) }}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="!dashboardData?.recent_transactions?.length" class="text-center py-8 text-gray-500">
                                    No recent transactions
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Balances -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Balances</h3>
                            <div class="space-y-3">
                                <div v-for="account in dashboardData?.account_balances || []" :key="account.id" 
                                     class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ account.name }}</p>
                                        <p class="text-xs text-gray-500">{{ account.category_name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium" :class="account.balance >= 0 ? 'text-green-600' : 'text-red-600'">
                                            {{ formatCurrency(account.balance) }}
                                        </p>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                              :class="account.type === 'income' ? 'bg-green-100 text-green-800' : 
                                                      account.type === 'expense' ? 'bg-red-100 text-red-800' : 
                                                      'bg-blue-100 text-blue-800'">
                                            {{ account.type }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="!dashboardData?.account_balances?.length" class="text-center py-8 text-gray-500">
                                    No account data available
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
