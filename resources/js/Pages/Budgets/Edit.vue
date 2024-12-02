<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';

const props = defineProps({
    budget: {
        type: Object
    },
    status: {
        type: String
    },
    total: {
        type: String
    },
    fields: {
        required: true,
        type: Object
    }
});
</script>

<template>
    <Head :title="'Редактировать ' + budget.title " />

    <AuthenticatedLayout>
        <Edit :fields="fields" :item="budget" type="budgets" />
    </AuthenticatedLayout>
</template>
