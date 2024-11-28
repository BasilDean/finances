<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';


const props = defineProps({
    status: {
        type: String
    },
    username: {
        type: String
    }
});

const form = useForm({
    title: '',
    main_currency: ''
});


const createBudget = () => {
    form.post(route('budgets.store'));
};
</script>

<template>
    <Head title="Создать новый бюджет" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-gray-50 py-6 sm:py-8">
                    <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
                        <h2 class="text-center text-base/7 font-semibold text-indigo-600 flex justify-center items-center space-x-2 w-full">
                            <span>Создать новый бюджет</span>
                        </h2>

                        <form @submit.prevent="createBudget()">
                            <div class="space-y-12">
                                {{ form.errors }}

                                <div class="border-b border-gray-900/10 pb-12">

                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-4">
                                            <label class="block text-sm/6 font-medium text-gray-900" for="region">Название</label>
                                            <div class="mt-2">
                                                <input id="title" v-model="form.title" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                                       name="title"
                                                       type="text" />
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
                                <NavLink :href="route('budgets.index')" class="text-sm/6 font-semibold text-gray-900"
                                         type="button">Отмена
                                </NavLink>
                                <button class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                        type="submit">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
