<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';

defineProps({
    incomes: {
        type: Object,
    },
    fields: {
        type: Object,
        required: true,
    },
    status: {
        type: String,
    },
    filters: {
        type: Object,
    },
});
</script>

<template>
    <Head title="Доходы" />

    <AuthenticatedLayout>
        <template #header>
            <div class="align-center flex justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Доходы
                </h2>

                <Link
                    :href="route('income.create')"
                    class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700"
                >
                    Создать
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <List
                    :fields="fields"
                    :filters="filters"
                    :items="incomes"
                    :show-detail-page="false"
                    type="income"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
