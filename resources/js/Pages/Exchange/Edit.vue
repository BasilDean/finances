<script setup>
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';
// import Edit from '@/Components/Exchanges/Form.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    item: {
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
    <Head title="Редактировать перевод" />

    <DashboardLayout>
        <Edit :fields="fields" :item="item" type="exchanges" />
    </DashboardLayout>
</template>
