<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import EditButton from '@/Components/Finanses/EditButton.vue';
import DeleteButton from '@/Components/Finanses/DeleteButton.vue';
import ShortList from '@/Components/Finanses/ShortList.vue';

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
                                <span>{{ budget.title }} - {{ budget.balance }} {{ $t(budget.currency) }}</span>
                            </h2>

                            <div class="flex gap-4">
                                <EditButton :slug="budget.slug" type="budgets" />
                                <DeleteButton :slug="budget.slug" :title="budget.title" :type="'budgets'" />
                            </div>
                        </div>
                        <div class="mt-4 grid gap-4 sm:mt-6 lg:grid-cols-2 lg:grid-rows-3 max-lg:row-start-2">
                            <ShortList :items="accounts" class=" lg:rounded-tl-[2rem]"
                                       subtitle="Все счета (включая наличку)." title="Счета" type="accounts" />
                            <ShortList :items="incomes"
                                       class="lg:row-start-2 max-lg:row-start-4 max-lg:rounded-b-[2rem]"
                                       subtitle="Последние доходы." title="Доходы" type="income" />
                            <ShortList :items="{}"
                                       class="max-lg:row-start-3 lg:col-start-1 lg:row-start-3 lg:rounded-bl-[2rem]"
                                       subtitle="Регулярные платежи и кредиты." title="Платежи и задолженности"
                                       type="payments" />
                            <ShortList :items="expenses"
                                       class="lg:row-span-3 max-lg:row-start-1 max-lg:rounded-t-[2rem] lg:rounded-r-[2rem]"
                                       subtitle="Список последних расходов." title="Расходы"
                                       type="expense" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
