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
});
</script>

<template>
    <Head title="Создать новый счёт" />

    <AuthenticatedLayout>
        <Create
            :fields="fields"
            :title="$t('create new income')"
            type="income"
        />
    </AuthenticatedLayout>
</template>
