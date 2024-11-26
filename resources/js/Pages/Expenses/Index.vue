<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import expenseIndex from '@/Components/Expense/Index.vue';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    expenses: {
        type: Object
    },
    status: {
        type: String
    },
    accounts: {
        type: Object
    }
});

const openCreateModal = ref(false);


const closeModal = () => {
    openCreateModal.value = false;

    form.clearErrors();
    form.reset();
};
const form = useForm({
    title: '',
    category: '',
    amount: '',
    account_id: ''
});


// Assuming `accounts` is passed as a prop and contains the list of all possible accounts.
const accountQuery = ref('');
const filteredAccounts = ref(props.accounts);

const expensesQuery = ref('');
const categoryQuery = ref('');
const suggestions = ref('');
const categories = ref([]);


const getAutocompleteResults = async () => {
    form.title = expensesQuery.value;
    if (expensesQuery.value.length >= 3) {
        try {
            const response = await axios.get(route('expense.autocomplete.title'), { params: { query: expensesQuery.value } });
            suggestions.value = response.data;
        } catch (error) {
            console.error('There was an error fetching the autocomplete results:', error);
        }
    } else {
        suggestions.value = [];
    }
};
const getCategory = async () => {
    form.category = categoryQuery.value;
    if (categoryQuery.value.length >= 2) {
        try {
            const response = await axios.get(route('expense.autocomplete.category'), { params: { query: categoryQuery.value } });
            categories.value = response.data;
        } catch (error) {
            console.error('There was an error fetching the autocomplete results:', error);
        }
    } else {
        categories.value = [];
    }
};

watch(expensesQuery, getAutocompleteResults);
watch(categoryQuery, getCategory);

const filterAccounts = () => {
    const query = accountQuery.value.toLowerCase().trim();
    filteredAccounts.value = props.accounts.filter(account =>
        account.title.toLowerCase().includes(query)
    );
};

const setTitle = (value) => {
    form.title = value;
    expensesQuery.value = value;
    setTimeout(() => {
        document.getElementsByName('amount')[0].focus();
    }, 0);
};

const setCategory = (value) => {
    form.category = value;
    categoryQuery.value = value;
    setTimeout(() => {
        document.getElementsByName('account_id')[0].focus();
    }, 0);
};

const createexpense = () => {
    form.post(route('expense.store'), {

        onSuccess: () => {
            form.reset('title', 'amount');
            expensesQuery.value = '';
        }
    });
};
</script>

<template>
    <Head title="Расходы" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between align-center">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Расходы
                </h2>

                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    @click="openCreateModal = true">
                    Создать
                </button>
            </div>
        </template>

        <div class="py-6">
            <div v-if="expenses" class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <expenseIndex :items="expenses" />
            </div>
        </div>

        <Modal v-if="openCreateModal" :show="openCreateModal" @close="closeModal">
            <div class="p-6">
                <div class="text-white">
                    <form @submit.prevent="createexpense()">
                        <div class="space-y-1">
                            <div class="border-b border-gray-900/10 pb-2">
                                <h2 class="text-base/7 font-semibold">Добавить новый расход</h2>
                            </div>

                            <div class="border-b border-gray-900/10 pb-4">

                                <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
                                    <div class="sm:col-span-2">
                                        <InputLabel class="block text-sm/6 font-medium text-white" for="title">Имя
                                        </InputLabel>
                                        <div class="mt-2">
                                            <input id="title" v-model="expensesQuery"
                                                   class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 sm:text-sm/6 z-2"
                                                   name="title"
                                                   type="text"
                                            />
                                            <ul class="bg-white text-black p-1 rounded-b-md top-[-5px] relative z-1">
                                                <li v-for="suggestion in suggestions" :key="suggestion"
                                                    class="cursor-pointer" @click="setTitle(suggestion)">
                                                    <a href="#" @click="setTitle(suggestion)">{{ suggestion }}</a>
                                                </li>
                                            </ul>
                                            <input id="title" v-model="form.title"
                                                   class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                                   name="title"
                                                   type="hidden" />
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <InputLabel class="block text-sm/6 font-medium text-white" for="source">
                                            Сумма
                                        </InputLabel>
                                        <div class="mt-2">
                                            <input id="amount" v-model="form.amount"
                                                   class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                                   name="amount"
                                                   type="number" />
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <InputLabel class="block text-sm/6 font-medium text-white" for="source">
                                            Категория
                                        </InputLabel>
                                        <div class="mt-2">

                                            <input v-model="categoryQuery"
                                                   class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 sm:text-sm/6 z-2"
                                                   name="category"
                                                   type="text"
                                            />
                                            <ul class="bg-white text-black p-1 rounded-b-md top-[-5px] relative z-1">
                                                <li v-for="suggestion in categories" :key="suggestion"
                                                    class="cursor-pointer" @click="setCategory(suggestion)">
                                                    <a href="#" @click="setCategory(suggestion)">{{ suggestion }}</a>
                                                </li>
                                            </ul>
                                            <input id="category" v-model="form.category"
                                                   class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                                   name="category"
                                                   type="hidden" />
                                        </div>
                                    </div>


                                    <div class="sm:col-span-2">
                                        <label class="block text-sm/6 font-medium text-white" for="country">Счет</label>
                                        <div class="mt-2 relative">
                                            <!--                                            <input type="text" v-model="accountQuery" @input="filterAccounts" placeholder="Начните вводить..." class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"/>-->
                                            <div
                                                class="absolute top-0 mt-0 w-full rounded-md bg-white shadow-lg z-10">
                                                <select id="account" v-model="form.account_id"
                                                        class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm/6"
                                                        name="account_id">

                                                    <option v-for="account in filteredAccounts"
                                                            :key="account.id" :value="account.id">{{ account.title }}
                                                        ({{ account.currency }})
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">
                                Отменить
                            </SecondaryButton>

                            <PrimaryButton
                                class="ms-3 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Создать
                            </PrimaryButton>
                        </div>
                    </form>

                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
