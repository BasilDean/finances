<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    budget: {
        type: Object
    },
    status: {
        type: String
    },
    total: {
        type: String
    }
});

const form = useForm({
    slug: props.budget.slug,
    title: props.budget.title,
    main_currency: props.budget.main_currency
});

const updateBudget = () => {
    form.patch(route('budgets.update', props.budget.slug));
};
</script>

<template>
    <Head :title="'Редактировать ' + budget.title " />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-gray-50 py-6 sm:py-8">
                    <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
                        <h2 class="text-center text-base/7 font-semibold text-indigo-600 flex justify-center items-center space-x-2 w-full">
                            <span>Редактировать {{ budget.title }}</span>
                        </h2>

                        <form @submit.prevent="updateBudget()">
                            {{ form.errors }}
                            <div class="space-y-12">

                                <div class="border-b border-gray-900/10 pb-12">

                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm/6 font-medium text-gray-900" for="region">Название</label>
                                            <div class="mt-2">
                                                <input id="title" v-model="form.title"
                                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                                       name="title"
                                                       type="text" />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm/6 font-medium text-gray-900"
                                                   for="region">Ярлык</label>
                                            <div class="mt-2">
                                                <div
                                                    class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                                    <input id="slug" v-model="form.slug" autocomplete="slug"
                                                           class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm/6"
                                                           name="slug"
                                                           type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm/6 font-medium text-gray-900" for="country">Основная
                                                валюта</label>
                                            <div class="mt-2">
                                                <select id="mainCurrency" v-model="form.main_currency"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm/6 w-full"
                                                        name="mainCurrency">
                                                    <option value="USD">USD</option>
                                                    <option value="RUB">RUB</option>
                                                    <option value="EUR">EUR</option>
                                                    <option value="GEL">GEL</option>
                                                    <option value="TRY">TRY</option>
                                                    <option value="UZS">UZS</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <Link :href="route('budgets.show', budget.slug)"
                                      class="text-sm/6 font-semibold text-gray-900"
                                      href="" type="button">Cancel
                                </Link>
                                <button
                                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                    type="submit">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
