<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';
import useSwall from '@/Composable/useSwall';

const props = defineProps({
    transactions: Object, // Paginated data
    coas: Array, // list COA untuk filter
    filters: Object, // current filters
});

const search = ref(props.filters?.search || '');
const filterCoa = ref(props.filters?.filter_coa || '');
const dateFrom = ref(props.filters?.date_from || '');
const dateTo = ref(props.filters?.date_to || '');
const swall = useSwall();
const page = usePage();

// Watch untuk filter dan search
watch([search, filterCoa, dateFrom, dateTo], () => {
    router.get(route('transactions.index'), {
        search: search.value,
        filter_coa: filterCoa.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
        page: 1 // Reset ke halaman pertama saat filter
    }, {
        preserveState: true,
        preserveScroll: false,
        replace: true
    });
}, { deep: true });

const filteredTransactions = computed(() => {
    return props.transactions.data;
});

function clearFilters() {
    search.value = '';
    filterCoa.value = '';
    dateFrom.value = '';
    dateTo.value = '';
}

function goToPage(url) {
    // Extract page number from URL
    const urlObj = new URL(url);
    const page = urlObj.searchParams.get('page') || 1;
    
    // Navigate with current filters and new page
    router.get(route('transactions.index'), {
        search: search.value,
        filter_coa: filterCoa.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
        page: page
    }, {
        preserveState: true,
        preserveScroll: false,
        replace: true
    });
}

function destroy(id) {
    const transaction = props.transactions.data.find(trx => trx.id === id);
    swall.confirmDelete(() => {
        router.visit(route('transactions.destroy', id), {
            method: 'delete',
            onSuccess: () => {
                console.log('Delete successful');
                // Show success message manually
                swall.successToast('Transaction deleted successfully.');
            },
            onError: (errors) => {
                console.log('Delete error:', errors);
                swall.errorToast('Failed to delete transaction.');
            }
        });
    });
}
</script>

<template>
<AuthenticatedLayout>
    <Head title="Transactions" />
    <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Transactions
        </h2>
    </template>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Toolbar -->
                    <div class="mb-6">
                        <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                            <!-- Left side - Add button -->
                            <div>
                                <a :href="route('transactions.create')" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Add Transaction
                                </a>
                            </div>

                            <!-- Right side - Filters -->
                            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                <!-- Search -->
                                <div class="relative">
                                    <input 
                                        v-model="search"
                                        type="text" 
                                        placeholder="Search transactions..."
                                        class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>

                                <!-- Date From -->
                                <div class="relative">
                                    <input 
                                        v-model="dateFrom"
                                        type="date" 
                                        placeholder="From Date"
                                        class="w-full sm:w-40 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>

                                <!-- Date To -->
                                <div class="relative">
                                    <input 
                                        v-model="dateTo"
                                        type="date" 
                                        placeholder="To Date"
                                        class="w-full sm:w-40 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>

                                <!-- COA Filter -->
                                <div class="relative">
                                    <select 
                                        v-model="filterCoa"
                                        class="w-full sm:w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        <option value="">All Accounts</option>
                                        <option v-for="coa in coas" :key="coa.id" :value="coa.id">
                                            {{ coa.name }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Clear Filters -->
                                <button 
                                    @click="clearFilters"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse bg-white rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                                        <th class="px-6 py-3 text-left">No</th>
                                        <th class="px-6 py-3 text-left">Date</th>
                                        <th class="px-6 py-3 text-left">Account</th>
                                        <th class="px-6 py-3 text-left">Description</th>
                                        <th class="px-6 py-3 text-right">Debit</th>
                                        <th class="px-6 py-3 text-right">Credit</th>
                                        <th class="px-6 py-3 text-left">Created At</th>
                                        <th class="px-6 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                            <tbody>
                                <tr v-for="(transaction, index) in filteredTransactions" :key="transaction.id" 
                                    class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition">
                                    <td class="px-6 py-3 text-sm text-gray-700">{{ index + 1 }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">{{ transaction.date }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">{{ transaction.chart_of_account?.name }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">{{ transaction.description || '-' }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-700 text-right">{{ transaction.debit }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-700 text-right">{{ transaction.credit }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-500">{{ new Date(transaction.created_at).toLocaleDateString() }}</td>
                                    <td class="px-6 py-3 text-center flex justify-center gap-3">
                                        <a :href="route('transactions.edit', transaction.id)" 
                                            class="p-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                            <PencilSquareIcon class="w-5 h-5" />
                                        </a>
                                        <button @click="destroy(transaction.id)" 
                                                class="p-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                            <TrashIcon class="w-5 h-5" />
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="filteredTransactions.length === 0">
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        No Transactions found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="transactions.links && transactions.links.length > 3" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                            <div class="flex flex-1 justify-between sm:hidden">
                                <!-- Mobile pagination -->
                                <button
                                    v-if="transactions.links[0].url"
                                    @click="goToPage(transactions.links[0].url)"
                                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    Previous
                                </button>
                                <button
                                    v-if="transactions.links[transactions.links.length - 1].url"
                                    @click="goToPage(transactions.links[transactions.links.length - 1].url)"
                                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    Next
                                </button>
                            </div>
                            
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium">{{ transactions.from }}</span>
                                        to
                                        <span class="font-medium">{{ transactions.to }}</span>
                                        of
                                        <span class="font-medium">{{ transactions.total }}</span>
                                        results
                                    </p>
                                </div>
                                <div>
                                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                        <!-- Previous Page Link -->
                                        <button
                                            v-if="transactions.links[0].url"
                                            @click="goToPage(transactions.links[0].url)"
                                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                        >
                                            <span class="sr-only">Previous</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <span
                                            v-else
                                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed"
                                        >
                                            <span class="sr-only">Previous</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                            </svg>
                                        </span>

                                        <!-- Page Numbers -->
                                        <template v-for="(link, index) in transactions.links" :key="index">
                                            <button
                                                v-if="link.url && index > 0 && index < transactions.links.length - 1"
                                                @click="goToPage(link.url)"
                                                v-html="link.label"
                                                :class="[
                                                    link.active
                                                        ? 'relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'
                                                        : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'
                                                ]"
                                            />
                                            <span
                                                v-else-if="!link.url && index > 0 && index < transactions.links.length - 1"
                                                v-html="link.label"
                                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed"
                                            />
                                        </template>

                                        <!-- Next Page Link -->
                                        <button
                                            v-if="transactions.links[transactions.links.length - 1].url"
                                            @click="goToPage(transactions.links[transactions.links.length - 1].url)"
                                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                        >
                                            <span class="sr-only">Next</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <span
                                            v-else
                                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed"
                                        >
                                            <span class="sr-only">Next</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </nav>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>
