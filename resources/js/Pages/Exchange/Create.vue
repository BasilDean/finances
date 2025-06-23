<script setup>
import { Head } from '@inertiajs/vue3';
import Create from '@/Components/Exchanges/Form.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    fields: {
        required: true,
        type: Object,
    },
    currencies: {
        required: false,
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head :title="$t('create new exchange')" />

    <DashboardLayout>
        <Create
            :currencies="currencies"
            :fields="fields"
            :status="$attrs.flash.status"
            :title="$t('create new exchange')"
            type="exchanges"
        />
    </DashboardLayout>
</template>
