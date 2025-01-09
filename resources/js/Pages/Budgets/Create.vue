<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Create from '@/Components/Finanses/Create.vue';

defineProps({
    status: {
        type: String,
    },
    username: {
        type: String,
    },
    fields: {
        required: true,
        type: Object,
    },
});
</script>

<template>
    <Head :title="$t('create new budget')" />

    <AuthenticatedLayout>
        <Create
            :fields="fields"
            :title="$t('create new budget')"
            type="budgets"
        />
    </AuthenticatedLayout>
</template>
