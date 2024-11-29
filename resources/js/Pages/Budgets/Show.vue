<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import EditButton from '@/Components/Finanses/EditButton.vue';
import DeleteButton from '@/Components/Finanses/DeleteButton.vue';

const props = defineProps({
    budget: {
        type: Object
    },
    accounts: {
        type: Object
    },
    status: {
        type: String
    },
    total: {
        type: String
    },
    incomes: {
        Type: Object
    },
    expenses: {
        Type: Object
    }
});

const form = useForm({
    id: props.budget.id
});

const deleteItem = () => {
    const confirmation = prompt('Чтобы удалить бюджет введите его название');
    if (confirmation === props.budget.title) {
        form.delete(route('budgets.destroy', props.budget.slug));
    } else {
        alert('Название введено не верно!');
    }
};
</script>

<template>
    <Head title="Budgets" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-slate-800 py-6 sm:py-8 rounded-lg">
                    <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
                        <div class="flex justify-between">
                            <h2 class="text-center text-base/7 font-semibold text-white flex justify-start items-center space-x-2 w-full">
                                <span>{{ budget.title }} - {{ budget.balance }} {{ budget.currency }}</span>
                            </h2>

                            <div class="flex gap-4">
                                <EditButton :slug="budget.slug" type="budgets" />
                                <DeleteButton :slug="budget.slug" :title="budget.title" :type="'budgets'" />
                            </div>

                        </div>
                        <div class="mt-4 grid gap-4 sm:mt-6 lg:grid-cols-3 lg:grid-rows-2">
                            <div class="relative lg:row-span-2">
                                <div class="absolute inset-px rounded-lg bg-slate-700 lg:rounded-l-[2rem]" />
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] lg:rounded-l-[calc(2rem+1px)]">
                                    <div class="px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                                        <Link :href="route('income.index')"
                                              class="mt-2 text-lg font-medium tracking-tight text-white max-lg:text-center block">
                                            Доходы
                                        </Link>
                                        <p class="mt-2 max-w-lg text-sm/6 text-slate-400 max-lg:text-center">Последние
                                            доходы.</p>
                                    </div>
                                    <div
                                        class="flex flex-1 items-start justify-center px-8 max-lg:pb-12 max-lg:pt-10 sm:px-10 lg:pb-2">
                                        <dl class="divide-y divide-gray-100 w-full">
                                            <Link v-for="income in incomes"
                                                  :class="'px-4 py-6 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0'"
                                                  href="#">
                                                <dt class="text-sm/6 font-medium text-white sm:col-span-1">
                                                    {{ income.title }}
                                                </dt>
                                                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-0">{{ income.amount }}
                                                    {{ income.currency }}
                                                </dd>
                                            </Link>
                                        </dl>
                                    </div>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 lg:rounded-l-[2rem]" />
                            </div>
                            <div class="relative max-lg:row-start-1">
                                <div class="absolute inset-px rounded-lg bg-slate-700 max-lg:rounded-t-[2rem]" />
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]">
                                    <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                        <Link
                                            :href="route('accounts.index')"
                                            class="mt-2 text-lg font-medium tracking-tight text-white max-lg:text-center block">
                                            Счета
                                        </Link>
                                        <p class="mt-2 max-w-lg text-sm/6 text-slate-400 max-lg:text-center">Все счета
                                            (включая наличку).</p>
                                    </div>
                                    <div
                                        class="flex flex-1 items-center justify-center px-8 max-lg:pb-12 max-lg:pt-10 sm:px-10 lg:pb-2">
                                        <dl class="divide-y divide-gray-100 w-full">
                                            <Link v-for="account in accounts"
                                                  :href="route('accounts.show', account.slug)"
                                                  class="px-4 py-6 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                                                <dt class="text-sm/6 font-medium text-white sm:col-span-1">
                                                    {{ account.title }}
                                                </dt>
                                                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-0">{{ account.amount }}
                                                    {{ account.currency }}
                                                </dd>
                                            </Link>
                                        </dl>
                                    </div>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 max-lg:rounded-t-[2rem]" />
                            </div>
                            <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
                                <div class="absolute inset-px rounded-lg bg-slate-700" />
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)]">
                                    <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                        <p class="mt-2 text-lg font-medium tracking-tight text-white max-lg:text-center">
                                            Платежи и задолженности</p>
                                        <p class="mt-2 max-w-lg text-sm/6 text-slate-400 max-lg:text-center">Регулярные
                                            платежи и кредиты.</p>
                                    </div>
                                    <div
                                        class="flex flex-1 items-center [container-type:inline-size] max-lg:py-6 lg:pb-2">
                                        <img alt=""
                                             class="h-[min(152px,40cqw)] object-cover object-center"
                                             src="https://tailwindui.com/plus/img/component-images/bento-03-security.png" />
                                    </div>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5" />
                            </div>
                            <div class="relative lg:row-span-2">
                                <div
                                    class="absolute inset-px rounded-lg bg-slate-700 max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]" />
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]">
                                    <div class="px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                                        <Link :href="route('expense.index')"
                                              class="mt-2 text-lg font-medium tracking-tight text-white max-lg:text-center">
                                            Расходы
                                        </Link>
                                        <p class="mt-2 max-w-lg text-sm/6 text-slate-400 max-lg:text-center">Список
                                            последних расходов.</p>
                                    </div>

                                    <div
                                        class="flex flex-1 items-start justify-center px-8 max-lg:pb-12 max-lg:pt-10 sm:px-10 lg:pb-2">
                                        <dl class="divide-y divide-gray-100 w-full">
                                            <Link v-for="expense in expenses"
                                                  :class="'px-4 py-6 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0'"
                                                  href="#">
                                                <dt class="text-sm/6 font-medium text-white sm:col-span-1">
                                                    {{ expense.title }}
                                                </dt>
                                                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-0">{{ expense.amount }}
                                                    {{ expense.currency }}
                                                </dd>
                                            </Link>
                                        </dl>
                                    </div>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
