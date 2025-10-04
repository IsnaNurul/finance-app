<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    coas: Array, // relasi ke Chart of Account
});

const form = useForm({
    date: '',
    chart_of_account_id: '',
    description: '',
    type: 'debit', // default pilih debit
    debit: 0,
    credit: 0,
});

function submit() {
    // jaga konsistensi hanya salah satu yang bernilai
    if (form.type === 'debit') {
        form.credit = 0;
    } else {
        form.debit = 0;
    }
    form.post(route('transactions.store'));
}
</script>

<template>
    <Head title="Add Transaction" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Add Transaction
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Date -->
                        <div>
                            <InputLabel for="date" value="Date" />
                            <TextInput id="date" type="date" class="mt-1 block w-full"
                                v-model="form.date" autofocus />
                            <InputError class="mt-2" :message="form.errors.date" />
                        </div>

                        <!-- Chart of Account -->
                        <div>
                            <InputLabel for="chart_of_account_id" value="Account" />
                            <select id="chart_of_account_id"
                                    v-model="form.chart_of_account_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Select Account --</option>
                                <option v-for="coa in coas" :key="coa.id" :value="coa.id">
                                    {{ coa.code }} - {{ coa.name }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.chart_of_account_id" />
                        </div>

                        <!-- Description -->
                        <div>
                            <InputLabel for="description" value="Description" />
                            <TextInput id="description" type="text" class="mt-1 block w-full"
                                v-model="form.description" />
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <!-- Type -->
                        <div>
                            <InputLabel for="type" value="Transaction Type" />
                            <select id="type"
                                    v-model="form.type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>
                            </select>
                        </div>

                        <!-- Debit -->
                        <div v-if="form.type === 'debit'">
                            <InputLabel for="debit" value="Debit" />
                            <TextInput id="debit" type="number" class="mt-1 block w-full"
                                v-model="form.debit" min="0" />
                            <InputError class="mt-2" :message="form.errors.debit" />
                        </div>

                        <!-- Credit -->
                        <div v-if="form.type === 'credit'">
                            <InputLabel for="credit" value="Credit" />
                            <TextInput id="credit" type="number" class="mt-1 block w-full"
                                v-model="form.credit" min="0" />
                            <InputError class="mt-2" :message="form.errors.credit" />
                        </div>

                        <!-- Action -->
                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
