<script setup>
import { Head, Link } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    expenses: {
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
    <Head title="Расходы" />

    <DashboardLayout>
        <div class="max-w-8xl mx-auto min-h-screen space-y-6 sm:px-2 lg:px-3">
            <div class="min-h-screen py-6">
                <div class="align-center flex justify-between">
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                    >
                        Расходы
                    </h2>
                    <div class="flex gap-2">
                        <Link
                            :href="route('expense.create')"
                            class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700"
                        >
                            Создать расход
                        </Link>
                    </div>
                </div>
                <List
                    v-if="expenses"
                    :fields="fields"
                    :filterByDate="true"
                    :filters="filters"
                    :items="expenses"
                    :show-detail-page="false"
                    class="min-h-screen"
                    type="expense"
                />
            </div>
        </div>
    </DashboardLayout>
</template>
