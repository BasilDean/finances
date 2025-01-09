<script setup>
import { Head } from '@inertiajs/vue3';
import Edit from '@/Components/Finanses/Edit.vue';

import ItemList from '@/Components/Purchases/ItemList.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    expense: {
        type: Object,
        required: true,
    },
    status: {
        type: String,
    },
    fields: {
        required: true,
        type: Object,
    },
    itemFields: {
        required: true,
        type: Object,
    },
});
</script>

<template>
    <Head :title="'Редактировать ' + expense.title" />

    <DashboardLayout>
        <div v-if="expense.has_items">
            <ItemList
                :currency="expense.currency"
                :fields="itemFields"
                :items="expense.items"
                :purchase="expense.slug"
                :total="expense.amount"
                :total_calculated="expense.amount_calculated"
            />
        </div>
        <Edit :fields="fields" :item="expense" type="expense" />
    </DashboardLayout>
</template>
