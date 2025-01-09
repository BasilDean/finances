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
    <Head title="Создать новый счёт" />

    <DashboardLayout>
        <Create
            :fields="fields"
            :title="$t('create new account')"
            type="accounts"
        />
    </DashboardLayout>
</template>
