<script setup>
import { Head } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';
import CreateButton from '@/Components/Finanses/CreateButton.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    fields: {
        required: true,
        type: Object,
    },
    filters: {
        type: Object,
        default: () => {},
    },
    exchanges: {
        type: Object,
    },
    status: {
        type: String,
    },
});
const type = 'exchanges';
</script>

<template>
    <Head title="Личные переводы" />

    <DashboardLayout>
        <div class="max-w-8xl mx-auto min-h-screen space-y-6 sm:px-2 lg:px-3">
            <div class="align-center flex justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Переводы
                </h2>
                <CreateButton :route="route(type + '.create')" />
            </div>
            <List
                :fields="fields"
                :filters="false"
                :items="exchanges"
                :show-detail-page="false"
                :type="type"
            />
        </div>
    </DashboardLayout>
</template>
