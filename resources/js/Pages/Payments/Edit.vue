<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';

defineProps({
    expense: {
        type: Object,
        required: true,
    },
    status: {
        type: String,
    },
    fields: {
        required: true,
        type: Object,
    },
});
</script>

<template>
    <Head :title="'Редактировать ' + expense.title" />

    <AuthenticatedLayout>
        <Edit :fields="fields" :item="expense" type="expense" />
    </AuthenticatedLayout>
</template>
