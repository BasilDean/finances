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
        default: () => ({}),
    },
    accounts: {
        type: Object,
    },
    status: {
        type: String,
    },
});
const type = 'accounts';
</script>

<template>
    <Head title="Личные счета" />

    <DashboardLayout>
        <div class="rounded-l bg-slate-800 py-3 sm:py-5">
            <div class="max-w-8xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div class="align-center flex justify-between">
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                    >
                        Личные счета
                    </h2>
                    <CreateButton :route="route(type + '.create')" />
                </div>
                <List
                    :fields="fields"
                    :filters="filters"
                    :items="accounts"
                    :show-detail-page="true"
                    :type="type"
                />
            </div>
        </div>
    </DashboardLayout>
</template>
