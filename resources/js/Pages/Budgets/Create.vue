<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Create from '@/Components/Finanses/Create.vue';

const props = defineProps({
    status: {
        type: String
    },
    username: {
        type: String
    },
    fillableFields: {
        required: true,
        type: Object
    },
    fields: {
        required: true,
        type: Object
    }
});
</script>

<template>
    <Head :title="$t('create new budget')" />

    <AuthenticatedLayout>
        <Create :fields="fields" :fillable-fields="fillableFields" type="budgets" />
    </AuthenticatedLayout>
</template>
