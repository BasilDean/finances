<script setup>
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    category: {
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
    <Head :title="'Редактировать ' + category.title" />

    <DashboardLayout>
        <Edit :fields="fields" :item="category" type="categories" />
    </DashboardLayout>
</template>
