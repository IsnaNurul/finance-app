<script setup>
import { ref, watch, defineEmits, defineProps } from 'vue';
import { PlusCircleIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    addRoute: String,             // route untuk tombol Add
    addLabel: { type: String, default: 'Add' }, // label tombol
    showSearch: { type: Boolean, default: true },
    filterOptions: { type: Array, default: () => [] }, // opsi filter, optional
    showFilter: { type: Boolean, default: false },
});

const emit = defineEmits(['update:search', 'update:filter']);

const search = ref('');
const filter = ref('');

watch(search, val => emit('update:search', val));
watch(filter, val => emit('update:filter', val));
</script>

<template>
<div class="mb-4 flex justify-between items-center">
    <!-- Add Button -->
    <a v-if="addRoute" :href="addRoute" 
        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
        {{ addLabel }}
    </a>

    <!-- Search & Filter -->
    <div class="flex gap-2">
        <div v-if="showSearch" class="relative">
            <MagnifyingGlassIcon class="w-5 h-5 absolute top-1/2 left-3 -translate-y-1/2 text-gray-400" />
            <input 
                v-model="search" 
                type="text" 
                placeholder="Search..." 
                class="pl-10 pr-3 py-2 border rounded-md focus:ring-1 focus:ring-blue-500 focus:outline-none"
            />
        </div>

        <select v-if="showFilter" v-model="filter" class="px-3 py-2 border rounded-md">
            <option value="">All</option>
            <option v-for="opt in filterOptions" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
        </select>
    </div>
</div>
</template>
