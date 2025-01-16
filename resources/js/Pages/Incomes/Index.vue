<script setup>
import { Head, Link } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

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

    <DashboardLayout>
        <div class="max-w-8xl mx-auto min-h-screen space-y-6 sm:px-2 lg:px-3">
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
            <List
                :fields="fields"
                :filters="filters"
                :items="incomes"
                :show-detail-page="false"
                type="income"
            />
        </div>
    </DashboardLayout>
</template>
