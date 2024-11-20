<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import BudgetIndex from '@/Components/Budgets/List.vue';
import NavLink from '@/Components/NavLink.vue';
import FlatLink from '@/Components/FlatLink.vue';

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
});

const form = useForm({
    id: props.budget.id
})

const deleteItem = () => {
    const confirmation = prompt('Чтобы удалить бюджет введите его название');
    if (confirmation === props.budget.title) {
        form.delete(route('budgets.destroy', props.budget.slug));
    } else {
        alert('Название введено не верно!')
    }
}
</script>

<template>
    <Head title="Budgets" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-gray-50 py-6 sm:py-8">
                    <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
                        <div class="flex justify-between">
                            <h2 class="text-center text-base/7 font-semibold text-indigo-600 flex justify-start items-center space-x-2 w-full">
                                <span>{{ budget.title }} - {{ budget.balance }} {{ budget.main_currency }}</span>
                                <FlatLink
                                    :href="route('budgets.edit', budget.slug)"
                                    :active="route().current('budgets.index')"
                                    classes="ml-3 inline-flex items-center rounded-lg bg-indigo-500 text-white px-2 py-1 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                    <svg fill="#fff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                         width="10px" height="10px" viewBox="0 0 45.973 45.973"
                                         xml:space="preserve">
                                        <g>
                                            <g>
                                                <path d="M43.454,18.443h-2.437c-0.453-1.766-1.16-3.42-2.082-4.933l1.752-1.756c0.473-0.473,0.733-1.104,0.733-1.774
                                                    c0-0.669-0.262-1.301-0.733-1.773l-2.92-2.917c-0.947-0.948-2.602-0.947-3.545-0.001l-1.826,1.815
                                                    C30.9,6.232,29.296,5.56,27.529,5.128V2.52c0-1.383-1.105-2.52-2.488-2.52h-4.128c-1.383,0-2.471,1.137-2.471,2.52v2.607
                                                    c-1.766,0.431-3.38,1.104-4.878,1.977l-1.825-1.815c-0.946-0.948-2.602-0.947-3.551-0.001L5.27,8.205
                                                    C4.802,8.672,4.535,9.318,4.535,9.978c0,0.669,0.259,1.299,0.733,1.772l1.752,1.76c-0.921,1.513-1.629,3.167-2.081,4.933H2.501
                                                    C1.117,18.443,0,19.555,0,20.935v4.125c0,1.384,1.117,2.471,2.501,2.471h2.438c0.452,1.766,1.159,3.43,2.079,4.943l-1.752,1.763
                                                    c-0.474,0.473-0.734,1.106-0.734,1.776s0.261,1.303,0.734,1.776l2.92,2.919c0.474,0.473,1.103,0.733,1.772,0.733
                                                    s1.299-0.261,1.773-0.733l1.833-1.816c1.498,0.873,3.112,1.545,4.878,1.978v2.604c0,1.383,1.088,2.498,2.471,2.498h4.128
                                                    c1.383,0,2.488-1.115,2.488-2.498v-2.605c1.767-0.432,3.371-1.104,4.869-1.977l1.817,1.812c0.474,0.475,1.104,0.735,1.775,0.735
                                                    c0.67,0,1.301-0.261,1.774-0.733l2.92-2.917c0.473-0.472,0.732-1.103,0.734-1.772c0-0.67-0.262-1.299-0.734-1.773l-1.75-1.77
                                                    c0.92-1.514,1.627-3.179,2.08-4.943h2.438c1.383,0,2.52-1.087,2.52-2.471v-4.125C45.973,19.555,44.837,18.443,43.454,18.443z
                                                     M22.976,30.85c-4.378,0-7.928-3.517-7.928-7.852c0-4.338,3.55-7.85,7.928-7.85c4.379,0,7.931,3.512,7.931,7.85
                                                    C30.906,27.334,27.355,30.85,22.976,30.85z" />
                                            </g>
                                        </g>
                                        </svg>
                                </FlatLink>
                            </h2>

                            <button
                                @click.prevent="deleteItem"
                                class="ml-3 inline-flex items-center rounded-lg bg-red-500 text-white px-2 py-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                                Удалить
                            </button>

                        </div>
                        <div class="mt-4 grid gap-4 sm:mt-6 lg:grid-cols-3 lg:grid-rows-2">
                            <div class="relative lg:row-span-2">
                                <div class="absolute inset-px rounded-lg bg-white lg:rounded-l-[2rem]" />
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] lg:rounded-l-[calc(2rem+1px)]">
                                    <div class="px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                                        <FlatLink :href="route('income.index')" classes="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center block">
                                            Доходы</FlatLink>
                                        <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center">Последние
                                            доходы.</p>
                                    </div>
                                    <div
                                        class="flex flex-1 items-start justify-center px-8 max-lg:pb-12 max-lg:pt-10 sm:px-10 lg:pb-2">
                                        <dl class="divide-y divide-gray-100 w-full">
                                            <FlatLink :href="route('income.show', income.id)" v-for="income in incomes"
                                                      :classes="'px-4 py-6 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0'">
                                                <dt class="text-sm/6 font-medium text-gray-900 sm:col-span-1">
                                                    {{ income.title }}
                                                </dt>
                                                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-0">{{ income.amount }}
                                                    {{ income.currency }}
                                                </dd>
                                            </FlatLink>
                                        </dl>
                                    </div>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 lg:rounded-l-[2rem]" />
                            </div>
                            <div class="relative max-lg:row-start-1">
                                <div class="absolute inset-px rounded-lg bg-white max-lg:rounded-t-[2rem]" />
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]">
                                    <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                        <FlatLink
                                            classes="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center block" :href="route('accounts.index')">
                                                Счета
                                        </FlatLink>
                                        <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center">Все счета
                                            (включая наличку).</p>
                                    </div>
                                    <div
                                        class="flex flex-1 items-center justify-center px-8 max-lg:pb-12 max-lg:pt-10 sm:px-10 lg:pb-2">
                                        <dl class="divide-y divide-gray-100 w-full">
                                            <FlatLink :href="route('accounts.show', account.slug)" v-for="account in accounts"
                                                 class="px-4 py-6 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                                                <dt class="text-sm/6 font-medium text-gray-900 sm:col-span-1">
                                                    {{ account.title }}
                                                </dt>
                                                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-0">{{ account.amount }}
                                                    {{ account.currency }}
                                                </dd>
                                            </FlatLink>
                                        </dl>
                                    </div>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 max-lg:rounded-t-[2rem]" />
                            </div>
                            <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
                                <div class="absolute inset-px rounded-lg bg-white" />
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)]">
                                    <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                        <p class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">
                                            Платежи и задолженности</p>
                                        <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center">Регулярные
                                            платежи и кредиты.</p>
                                    </div>
                                    <div
                                        class="flex flex-1 items-center [container-type:inline-size] max-lg:py-6 lg:pb-2">
                                        <img class="h-[min(152px,40cqw)] object-cover object-center"
                                             src="https://tailwindui.com/plus/img/component-images/bento-03-security.png"
                                             alt="" />
                                    </div>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5" />
                            </div>
                            <div class="relative lg:row-span-2">
                                <div
                                    class="absolute inset-px rounded-lg bg-white max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]" />
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]">
                                    <div class="px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                                        <p class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">
                                            Расходы</p>
                                        <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center">Список
                                            последних расходов.</p>
                                    </div>
                                    <div class="relative min-h-[30rem] w-full grow">
                                        <div
                                            class="absolute bottom-0 left-10 right-0 top-10 overflow-hidden rounded-tl-xl bg-gray-900 shadow-2xl">
                                            <div class="flex bg-gray-800/40 ring-1 ring-white/5">
                                                <div class="-mb-px flex text-sm/6 font-medium text-gray-400">
                                                    <div
                                                        class="border-b border-r border-b-white/20 border-r-white/10 bg-white/5 px-4 py-2 text-white">
                                                        NotificationSetting.jsx
                                                    </div>
                                                    <div class="border-r border-gray-600/10 px-4 py-2">App.jsx</div>
                                                </div>
                                            </div>
                                            <div class="px-6 pb-14 pt-6">
                                                <!-- Your code example -->
                                            </div>
                                        </div>
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
