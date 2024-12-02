<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Create from '@/Components/Finanses/Create.vue';

const props = defineProps({
    status: {
        type: String
    },
    fillableFields: {
        required: true,
        type: Object
    },
    fields: {
        required: true,
        type: Object
    }
});

const form = useForm({
    title: '',
    amount: 0,
    currency: 'RUB',
    type: 'account'
});


const createAccount = () => {
    form.post(route('expense.store'));
};
</script>

<template>
    <Head title="Создать новый счёт" />

    <AuthenticatedLayout>
        <Create :fields="fields" :fillable-fields="fillableFields" :title="$t('create new account')" type="accounts" />
    </AuthenticatedLayout>
</template>
