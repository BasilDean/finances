<script setup>
import { Head } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';
import CreateButton from '@/Components/Finanses/CreateButton.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    budgets: {
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
    filters: {
        type: Object,
        default: () => {},
    },
});
const type = 'budgets';
</script>

<template>
    <Head :title="$t('budgets')" />

    <DashboardLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="align-center flex justify-between">
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                    >
                        {{ $t('budgets') }}
                    </h2>

                    <CreateButton :route="route(type + '.create')" />
                </div>
                <List
                    :fields="fields"
                    :filters="filters"
                    :items="budgets"
                    :show-detail-page="true"
                    :type="type"
                />
            </div>
        </div>
    </DashboardLayout>
</template>
