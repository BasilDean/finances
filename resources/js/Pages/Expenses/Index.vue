<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';

const props = defineProps({
    expenses: {
        type: Object
    },
    fields: {
        type: Object,
        required: true
    },
    status: {
        type: String
    },
    filters: {
        type: Object
    }
});

const form = useForm({
    title: '',
    category: '',
    amount: '',
    account_id: ''
});


</script>

<template>
    <Head title="Расходы" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between align-center">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Расходы
                </h2>

                <Link :href="route('expense.create')"
                      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Создать
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div v-if="expenses" class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <List :fields="fields" :filters="filters" :items="expenses" :show-detail-page="false" type="expense" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
