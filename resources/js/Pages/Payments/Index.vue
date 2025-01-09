<script setup>
import { Head, Link } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    items: {
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
        <div class="py-6">
            <div class="align-center flex justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Платежи
                </h2>
                <div class="flex gap-2">
                    <Link
                        :href="route('payments.create')"
                        class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700"
                    >
                        Создать платеж
                    </Link>
                </div>
            </div>
            <div
                v-if="items"
                class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8"
            >
                <List
                    :fields="fields"
                    :filters="filters"
                    :items="items"
                    :show-detail-page="false"
                    type="payments"
                />
            </div>
        </div>
    </DashboardLayout>
</template>
