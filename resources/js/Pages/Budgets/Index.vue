<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import BudgetIndex from '@/Components/Budgets/List.vue'
import NavLink from '@/Components/NavLink.vue';
import FlatLink from '@/Components/FlatLink.vue';

defineProps({
    budgets: {
        type: Object,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Бюджеты" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between align-center">
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Бюджеты
            </h2>

                <FlatLink
                    :href="route('budgets.create')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Создать
                </FlatLink>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <BudgetIndex :items="budgets"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
