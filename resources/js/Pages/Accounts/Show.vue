<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import List from '@/Components/Finanses/List.vue';

import { ArrowUturnLeftIcon } from '@heroicons/vue/24/outline/index.js';

defineProps({
    items: {
        type: Object,
    },
    account: {
        type: Object,
    },
    fields: {
        type: Object,
    },
    status: {
        type: String,
    },
    search: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head :title="account.title" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-3 lg:px-4">
                <div class="bg-gray-800 py-3 sm:py-4">
                    <div
                        class="relative mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8"
                    >
                        <div
                            class="absolute left-6 top-2 text-white"
                            @click="goBack()"
                        >
                            <ArrowUturnLeftIcon class="size-3 text-white" />
                        </div>
                        <h2
                            class="text-center text-base/7 font-semibold text-white"
                        >
                            {{ account.title }} - {{ account.amount }}
                            {{ $t(account.currency) }}
                        </h2>
                    </div>
                </div>
                <List
                    :fields="fields"
                    :filters="filters"
                    :items="items"
                    :show-detail-page="false"
                    type="income"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
