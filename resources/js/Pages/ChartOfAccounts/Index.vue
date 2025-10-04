<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';
import TableToolbar from '@/Components/TableToolbar.vue';
import Pagination from '@/Components/Pagination.vue';
import useSwall from '@/Composable/useSwall';

const props = defineProps({
    coas: Object, // Paginated data
    categories: Array, 
});


const search = ref('');
const filterCategory = ref('');
const swall = useSwall();
const page = usePage();

// Watch untuk filter dan search
watch([search, filterCategory], () => {
    router.get(route('chart-of-accounts.index'), {
        search: search.value,
        filter_category: filterCategory.value,
        page: 1 // Reset ke halaman pertama saat filter
    }, {
        preserveState: true,
        preserveScroll: false,
        replace: true
    });
}, { deep: true });

const filteredCoas = computed(() => {
    return props.coas.data;
});

function destroy(id) {
    const coa = props.coas.data.find(coa => coa.id === id);
    swall.confirmDelete(() => {
        router.visit(route('chart-of-accounts.destroy', id), {
            method: 'delete',
            onSuccess: () => {
                console.log('Delete successful');
                // Show success message manually
                swall.successToast('Chart of Account deleted successfully.');
            },
            onError: (errors) => {
                console.log('Delete error:', errors);
                swall.errorToast('Failed to delete chart of account.');
            }
        });
    });
}
</script>

<template>
<AuthenticatedLayout>
    <Head title="Chart of Account" />
    <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Chart of Account
        </h2>
    </template>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Toolbar -->
                    <TableToolbar 
                        :addRoute="route('chart-of-accounts.create')" 
                        addLabel="Add COA"
                        :filterOptions="categories || []"
                        showFilter
                        v-model:search="search"
                        v-model:filter="filterCategory"
                    />

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse bg-white rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                                        <th class="px-6 py-3 text-left">ID</th>
                                        <th class="px-6 py-3 text-left">Code</th>
                                        <th class="px-6 py-3 text-left">Name</th>
                                        <th class="px-6 py-3 text-left">Category</th>
                                        <th class="px-6 py-3 text-left">Created At</th>
                                        <th class="px-6 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                            <tbody>
                                <tr v-for="(coa, index) in filteredCoas" :key="coa.id" 
                                    class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition">
                                    <td class="px-6 py-3 text-sm text-gray-700">{{ index + 1 }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">{{ coa.code }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">{{ coa.name }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">{{ coa.category?.name }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-500">{{ new Date(coa.created_at).toLocaleDateString() }}</td>
                                    <td class="px-6 py-3 text-center flex justify-center gap-3">
                                        <a :href="route('chart-of-accounts.edit', coa.id)" 
                                            class="p-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                            <PencilSquareIcon class="w-5 h-5" />
                                        </a>
                                        <button @click="destroy(coa.id)" 
                                                class="p-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                            <TrashIcon class="w-5 h-5" />
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="filteredCoas.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No Chart of Account found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>

                        <!-- Pagination -->
                        <Pagination 
                            :links="coas.links"
                            :from="coas.from"
                            :to="coas.to"
                            :total="coas.total"
                        />

                </div>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>
