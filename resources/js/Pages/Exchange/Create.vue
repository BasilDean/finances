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
});
</script>

<template>
    <Head title="Создать новый перевод" />

    <DashboardLayout>
        <Create
            :fields="fields"
            :title="$t('create new exchange')"
            type="exchanges"
        />
    </DashboardLayout>
</template>
