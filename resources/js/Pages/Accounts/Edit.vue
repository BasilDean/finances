<script setup>
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    account: {
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
    <Head :title="'Редактировать ' + account.name" />

    <DashboardLayout>
        <Edit :fields="fields" :item="account" type="accounts" />
    </DashboardLayout>
</template>
