<script setup>
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    income: {
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
    <Head :title="'Редактировать ' + income.title" />

    <DashboardLayout>
        <Edit :fields="fields" :item="income" type="income" />
    </DashboardLayout>
</template>
