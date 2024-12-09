<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Create from '@/Components/Finanses/Create.vue';

const props = defineProps({
    status: {
        type: String
    },
    fields: {
        required: true,
        type: Object
    },
    resetFields: {
        required: false,
        type: Array
    }
});

const form = useForm({
    title: '',
    amount: 0,
    currency: 'RUB',
    type: 'expense'
});
</script>

<template>
    <Head title="Создать новый расход" />

    <AuthenticatedLayout>
        <Create :fields="fields" :resetFields="resetFields" :title="$t('create new expense')" type="expense" />
    </AuthenticatedLayout>
</template>
