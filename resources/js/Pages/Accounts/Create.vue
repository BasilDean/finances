<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import BudgetIndex from '@/Components/Budgets/List.vue';
import NavLink from '@/Components/NavLink.vue';
import { computed, watch } from 'vue';

const props = defineProps({
    status: {
        type: String
    }
});

const form = useForm({
    title: '',
    amount: 0,
    currency: 'RUB',
    type: 'account',
});


const createAccount = () => {
    form.post(route('accounts.store'))
}
</script>

<template>
    <Head title="Создать новый счёт" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-gray-50 py-6 sm:py-8">
                    <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
                        <h2 class="text-center text-base/7 font-semibold text-indigo-600 flex justify-center items-center space-x-2 w-full">
                            <span>Создать новый счёт</span>
                        </h2>

                        <form @submit.prevent="createAccount()">
                            <div class="space-y-12">
                                {{ form.errors}}

                                <div class="border-b border-gray-900/10 pb-12">

                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
                                        <div class="sm:col-span-2">
                                            <label for="region" class="block text-sm/6 font-medium text-gray-900">Название</label>
                                            <div class="mt-2">
                                                <input type="text" name="title" id="title"
                                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" v-model="form.title" />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="country" class="block text-sm/6 font-medium text-gray-900">Валюта</label>
                                            <div class="mt-2">
                                                <select id="mainCurrency" name="mainCurrency"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm/6 w-full" v-model="form.currency">
                                                    <option value="RUB">RUB</option>
                                                    <option value="USD">USD</option>
                                                    <option value="EUR">EUR</option>
                                                    <option value="GEL">GEL</option>
                                                    <option value="TRY">TRY</option>
                                                    <option value="UZS">UZS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="region" class="block text-sm/6 font-medium text-gray-900">Баланс</label>
                                            <div class="mt-2">
                                                <input type="text" name="title" id="title"
                                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" v-model="form.amount" />
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="country" class="block text-sm/6 font-medium text-gray-900">Валюта</label>
                                            <div class="mt-2">
                                                <select id="mainCurrency" name="mainCurrency"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm/6 w-full" v-model="form.type">
                                                    <option value="cash">Наличка</option>
                                                    <option value="account">Банковский счёт (карта)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <NavLink :href="route('accounts.index')" type="button" class="text-sm/6 font-semibold text-gray-900">Отмена</NavLink>
                                <button type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
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
