<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Create from '@/Components/Finanses/Create.vue';

const props = defineProps({
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

    <AuthenticatedLayout>
        <Create
            :fields="fields"
            :title="$t('create new exchange')"
            type="exchanges"
        />
    </AuthenticatedLayout>
</template>
