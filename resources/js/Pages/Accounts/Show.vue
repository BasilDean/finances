<script setup>
import { Head } from '@inertiajs/vue3';
import List from '@/Components/Finanses/List.vue';

import {
    ArrowUturnLeftIcon,
    PresentationChartLineIcon,
} from '@heroicons/vue/24/outline/index.js';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import FlatLink from '@/Components/FlatLink.vue';

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
</script>

<template>
    <Head :title="account.title" />

    <DashboardLayout>
        <div class="max-w-8xl mx-auto space-y-6 px-2">
            <div class="bg-gray-800 py-3 sm:py-4">
                <div
                    class="relative mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8"
                >
                    <div class="absolute left-6 top-2 text-white">
                        <FlatLink :href="route('accounts.index')">
                            <ArrowUturnLeftIcon class="size-3 text-white" />
                        </FlatLink>
                    </div>
                    <h2
                        class="text-center text-base/7 font-semibold text-white"
                    >
                        {{ account.title }} - {{ account.amount }}
                        {{ $t(account.currency) }}
                    </h2>
                    <div class="absolute right-6 top-2 text-white">
                        <FlatLink :href="route('accounts.stats', account.slug)">
                            <PresentationChartLineIcon
                                class="size-5 text-white"
                            />
                        </FlatLink>
                    </div>
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
    </DashboardLayout>
</template>
