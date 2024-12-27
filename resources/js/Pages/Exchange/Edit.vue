<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';

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

    <AuthenticatedLayout>
        <Edit :fields="fields" :item="item" type="exchanges" />
    </AuthenticatedLayout>
</template>
