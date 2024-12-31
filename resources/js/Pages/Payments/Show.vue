<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    budgets: {
        type: Object
    },
    account: {
        type: Object
    },
    status: {
        type: String
    }
});
</script>

<template>
    <Head :title="'Account ' + account.title" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-gray-50 py-6 sm:py-8">
                    <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
                        <h2 class="text-center text-base/7 font-semibold text-indigo-600">{{ account.title }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
