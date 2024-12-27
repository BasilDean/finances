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
    <Head title="Создать новую покупку" />

    <AuthenticatedLayout>
        <Create
            :fields="fields"
            :title="$t('create new purchase')"
            type="purchase"
        />
    </AuthenticatedLayout>
</template>
