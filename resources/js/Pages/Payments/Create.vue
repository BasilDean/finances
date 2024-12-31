<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Create from '@/Components/Finanses/Create.vue';

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

    <AuthenticatedLayout>
        <Create
            :fields="fields"
            :resetFields="resetFields"
            :title="$t('create new payment')"
            type="expense"
        />
    </AuthenticatedLayout>
</template>
