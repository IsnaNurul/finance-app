<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import TableToolbar from '@/Components/TableToolbar.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, computed, watch } from 'vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';
import useSwall from '@/Composable/useSwall';

const props = defineProps({
    categories: Object, // Paginated data
});

const search = ref('');
const swall = useSwall();
const page = usePage();

// Watch untuk search
watch(search, () => {
    router.get(route('categories.index'), {
        search: search.value,
        page: 1 // Reset ke halaman pertama saat search
    }, {
        preserveState: true,
        preserveScroll: false,
        replace: true
    });
}, { deep: true });

const filteredCategories = computed(() => {
    return props.categories.data;
});

function destroy(id) {
    const category = props.categories.data.find(cat => cat.id === id);
    swall.confirmDelete(() => {
        router.visit(route('categories.destroy', id), {
            method: 'delete',
            onSuccess: () => {
                console.log('Delete successful');
                // Show success message manually
                swall.successToast('Category deleted successfully.');
            },
            onError: (errors) => {
                console.log('Delete error:', errors);
                swall.errorToast('Failed to delete category.');
            }
        });
    });
}
</script>

<template>
    <Head title="Category" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Category
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <!-- Toolbar -->
                        <TableToolbar 
                            :addRoute="route('categories.create')" 
                            addLabel="Add Category"
                            v-model:search="search"
                        />

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse bg-white rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                                        <th class="px-6 py-3 text-left">ID</th>
                                        <th class="px-6 py-3 text-left">Name</th>
                                        <th class="px-6 py-3 text-left">Created At</th>
                                        <th class="px-6 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(category, index) in filteredCategories" :key="category.id" 
                                        class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition">
                                        <td class="px-6 py-3 text-sm text-gray-700">{{ index + 1 }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-700">{{ category.name }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-500">{{ new Date(category.created_at).toLocaleDateString() }}</td>
                                        <td class="px-6 py-3 text-center flex justify-center gap-3">
                                            <!-- Edit -->
                                            <a :href="route('categories.edit', category.id)" 
                                                class="p-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                <PencilSquareIcon class="w-5 h-5" />
                                            </a>
                                            <!-- Delete -->
                                            <button @click="destroy(category.id)" 
                                                    class="p-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                                <TrashIcon class="w-5 h-5" />
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredCategories.length === 0">
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No categories found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <Pagination 
                            :links="categories.links"
                            :from="categories.from"
                            :to="categories.to"
                            :total="categories.total"
                        />

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
