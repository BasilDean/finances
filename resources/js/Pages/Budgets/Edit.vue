<script setup>
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    budget: {
        type: Object,
    },
    status: {
        type: String,
    },
    total: {
        type: String,
    },
    fields: {
        required: true,
        type: Object,
    },
});
</script>

<template>
    <Head :title="'Редактировать ' + budget.title" />

    <DashboardLayout>
        <Edit :fields="fields" :item="budget" type="budgets" />
    </DashboardLayout>
</template>
