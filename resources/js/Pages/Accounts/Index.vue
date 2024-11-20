<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import AccountIndex from '@/Components/Accounts/Index.vue'
import NavLink from '@/Components/NavLink.vue';

defineProps({
    accounts: {
        type: Object,
    },
    status: {
        type: String,
    }
});
</script>

<template>
    <Head title="Личные счета" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between align-center">
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Личные счета
            </h2>

                <NavLink
                    :href="route('accounts.create', budget)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Создать
                </NavLink>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <AccountIndex :items="accounts"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
