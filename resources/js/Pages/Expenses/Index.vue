<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';

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

    <AuthenticatedLayout>
        <template #header>
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
        </template>

        <div class="py-6">
            <div
                v-if="expenses"
                class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8"
            >
                <List
                    :fields="fields"
                    :filters="filters"
                    :items="expenses"
                    :show-detail-page="false"
                    type="expense"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
