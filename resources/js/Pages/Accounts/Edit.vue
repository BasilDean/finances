<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';

const props = defineProps({
    account: {
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
    <Head :title="'Редактировать ' + account.name " />

    <AuthenticatedLayout>
        <Edit :fields="fields" :item="account" type="accounts" />
    </AuthenticatedLayout>
</template>
