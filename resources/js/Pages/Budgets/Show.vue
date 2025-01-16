<script setup>
import { Head } from '@inertiajs/vue3';
import EditButton from '@/Components/Finanses/EditButton.vue';
import DeleteButton from '@/Components/Finanses/DeleteButton.vue';
import ShortList from '@/Components/Finanses/ShortList.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
    budget: {
        type: Object,
    },
    accounts: {
        type: Object,
    },
    status: {
        type: String,
    },
    incomes: {
        Type: Object,
    },
    expenses: {
        Type: Object,
    },
});
</script>

<template>
    <Head title="Budgets" />

    <DashboardLayout>
        <div class="py-0">
            <div class="max-w-9xl mx-auto space-y-1 sm:px-1 lg:px-1">
                <div class="rounded-lg bg-slate-800 py-3 sm:py-5">
                    <div class="lg:max-w-8xl max-w-8xl mx-auto px-3 lg:px-4">
                        <div class="flex justify-between">
                            <h2
                                class="flex w-full items-center justify-start space-x-2 text-center text-base/7 font-semibold text-white"
                            >
                                <span
                                    >{{ budget.title }} -
                                    {{
                                        new Intl.NumberFormat('ru-RU', {
                                            style: 'currency', // Use currency format
                                            currency: budget.currency, // Adjust currency code as needed
                                            minimumFractionDigits: 2, // Always show two decimal places
                                        }).format(budget.balance)
                                    }}
                                    {{ $t(budget.currency) }}</span
                                >
                            </h2>

                            <div class="flex gap-4">
                                <EditButton
                                    :slug="budget.slug"
                                    type="budgets"
                                />
                                <DeleteButton
                                    :slug="budget.slug"
                                    :title="budget.title"
                                    :type="'budgets'"
                                />
                            </div>
                        </div>
                        <div
                            class="mt-4 grid gap-4 max-lg:row-start-2 sm:mt-6 lg:grid-cols-2 lg:grid-rows-3"
                        >
                            <ShortList
                                :items="accounts"
                                class="lg:rounded-tl-[2rem]"
                                link="show"
                                subtitle="Все счета (включая наличку)."
                                title="Счета"
                                type="accounts"
                            />
                            <ShortList
                                :items="incomes"
                                class="max-lg:row-start-4 max-lg:rounded-b-[2rem] lg:row-start-2"
                                link="edit"
                                subtitle="Последние доходы."
                                title="Доходы"
                                type="income"
                            />
                            <ShortList
                                :items="{}"
                                class="max-lg:row-start-3 lg:col-start-1 lg:row-start-3 lg:rounded-bl-[2rem]"
                                link="edit"
                                subtitle="Регулярные платежи и кредиты."
                                title="Платежи и задолженности"
                                type="payments"
                            />
                            <ShortList
                                :items="expenses"
                                class="max-lg:row-start-1 max-lg:rounded-t-[2rem] lg:row-span-3 lg:rounded-r-[2rem]"
                                link="edit"
                                subtitle="Список последних расходов."
                                title="Расходы"
                                type="expense"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
