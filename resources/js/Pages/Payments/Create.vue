<script setup>
import { Head } from '@inertiajs/vue3';
import Create from '@/Components/Finanses/Create.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    status: {
        type: String,
    },
    fields: {
        required: true,
        type: Object,
    },
    resetFields: {
        required: false,
        type: Array,
    },
});
</script>

<template>
    <Head :title="$t('create new payment')" />

    <DashboardLayout>
        <Create
            :fields="fields"
            :resetFields="resetFields"
            :title="$t('create new payment')"
            type="expense"
        />
    </DashboardLayout>
</template>
