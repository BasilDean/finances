<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';
import ItemList from '@/Components/Purchases/ItemList.vue';

const props = defineProps({
    purchase: {
        type: Object,
        required: true
    },
    status: {
        type: String
    },
    fields: {
        required: true,
        type: Object
    },
    itemFields: {
        required: true,
        type: Object
    },
});
</script>

<template>
    <Head :title="'Редактировать ' + purchase.title " />

    <AuthenticatedLayout>
        <ItemList :fields="itemFields" :items="purchase.items" />
        <Edit :fields="fields" :item="purchase" type="purchase" />
    </AuthenticatedLayout>
</template>
