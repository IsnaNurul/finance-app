<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

import { ArrowDownTrayIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
    report: Object
});

function filterYear(e) {
    router.get(route('reports.profit-loss'), { year: e.target.value });
}

// generate 5 tahun terakhir + tahun sekarang + 5 tahun ke depan
const years = Array.from({ length: 11 }, (_, i) => new Date().getFullYear() - 5 + i);
</script>

<template>
    <Head title="Profit & Loss Report" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Profit & Loss Report ({{ report.year }})
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">

                    <!-- Filter & Export -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end mb-6 gap-4">
                        <!-- Select Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Year</label>
                            <select :value="report.year" @change="filterYear"
                                class="rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                            </select>
                        </div>

                        <!-- Export Button -->
                        <a :href="route('reports.profit-loss.export', { year: report.year })"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 transition">
                            <ArrowDownTrayIcon class="w-5 h-5 mr-2" />
                            Export to Excel
                        </a>

                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                            <thead class="bg-gray-100 text-gray-700 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 text-left">Category</th>
                                    <th v-for="month in report.months" :key="month" class="px-4 py-2 text-right">
                                        {{ new Date(2000, month-1, 1).toLocaleString('default', { month: 'short' }) }}
                                    </th>
                                    <th class="px-4 py-2 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Detail kategori -->
                                <tr v-for="(row, category) in report.data" :key="category" 
                                    class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition">
                                    <td class="px-4 py-2 font-medium">{{ category }}</td>
                                    <td v-for="month in report.months" :key="month" class="px-4 py-2 text-right">
                                        {{ row[month]?.toLocaleString() ?? 0 }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-semibold">{{ row.total.toLocaleString() }}</td>
                                </tr>

                                <!-- Total Income -->
                                <tr class="bg-green-100 font-semibold">
                                    <td class="px-4 py-2">Total Income</td>
                                    <td v-for="month in report.months" :key="month" class="px-4 py-2 text-right">
                                        {{ report.summary.income[month]?.toLocaleString() ?? 0 }}
                                    </td>
                                    <td class="px-4 py-2 text-right">{{ report.summary.income.total.toLocaleString() }}</td>
                                </tr>

                                <!-- Total Expense -->
                                <tr class="bg-red-100 font-semibold">
                                    <td class="px-4 py-2">Total Expense</td>
                                    <td v-for="month in report.months" :key="month" class="px-4 py-2 text-right">
                                        {{ report.summary.expense[month]?.toLocaleString() ?? 0 }}
                                    </td>
                                    <td class="px-4 py-2 text-right">{{ report.summary.expense.total.toLocaleString() }}</td>
                                </tr>

                                <!-- Net Income -->
                                <tr class="bg-yellow-100 font-bold">
                                    <td class="px-4 py-2">Net Income</td>
                                    <td v-for="month in report.months" :key="month" class="px-4 py-2 text-right">
                                        {{ report.summary.net[month]?.toLocaleString() ?? 0 }}
                                    </td>
                                    <td class="px-4 py-2 text-right">{{ report.summary.net.total.toLocaleString() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
