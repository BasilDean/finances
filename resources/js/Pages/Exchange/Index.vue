<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';
import CreateButton from '@/Components/Finanses/CreateButton.vue';

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
    <Head title="Личные счета" />

    <AuthenticatedLayout>
        <template #header>
            <div class="align-center flex justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Переводы
                </h2>
                <CreateButton :route="route(type + '.create')" />
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <List
                    :fields="fields"
                    :filters="false"
                    :items="exchanges"
                    :show-detail-page="false"
                    :type="type"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
