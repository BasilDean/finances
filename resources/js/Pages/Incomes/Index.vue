<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import IncomeIndex from '@/Components/Income/Index.vue';
import NavLink from '@/Components/NavLink.vue';
import FlatLink from '@/Components/FlatLink.vue';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    incomes: {
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
    source: '',
    amount: '',
    account_id: '',
});


// Assuming `accounts` is passed as a prop and contains the list of all possible accounts.
const accountQuery = ref('');
const filteredAccounts = ref(props.accounts);

const IncomesQuery = ref('');
const suggestions = ref('');


const getAutocompleteResults = async () => {
    form.title = IncomesQuery.value;
    if (IncomesQuery.value.length >= 3) {
        try {
            const response = await axios.get(route('income.autocomplete'), { params: { query: IncomesQuery.value } });
            suggestions.value = response.data;
        } catch (error) {
            console.error("There was an error fetching the autocomplete results:", error);
        }
    } else {
        suggestions.value = [];
    }
};

watch(IncomesQuery, getAutocompleteResults);

const filterAccounts = () => {
    const query = accountQuery.value.toLowerCase().trim();
    filteredAccounts.value = props.accounts.filter(account =>
        account.title.toLowerCase().includes(query)
    );
};

const setTitle = (value) => {
    form.title = value;
    IncomesQuery.value = value;
    setTimeout(() => {
        document.getElementsByName('amount')[0].focus();
    }, 0);
}

const createIncome = () => {
    form.post(route('income.store'), {

        onSuccess: () => {
            openCreateModal.value = false;
            form.reset('title', 'amount', 'source');
            IncomesQuery.value = '';
        }
    })
}
</script>

<template>
    <Head title="Доходы" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between align-center">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Доходы
                </h2>

                <button
                    @click="openCreateModal = true"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Создать
                </button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <IncomeIndex :items="incomes" />
            </div>
        </div>

        <Modal v-if="openCreateModal" :show="openCreateModal" @close="closeModal">
            <div class="p-6">
                <div class="text-white">
                    <form @submit.prevent="createIncome()">
                        <div class="space-y-1">
                            <div class="border-b border-gray-900/10 pb-2">
                                <h2 class="text-base/7 font-semibold">Добавить новый доход</h2>
                            </div>

                            <div class="border-b border-gray-900/10 pb-4">

                                <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
                                    <div class="sm:col-span-2">
                                        <InputLabel for="title" class="block text-sm/6 font-medium text-white">Имя
                                        </InputLabel>
                                        <div class="mt-2">
                                            <input type="text" name="title" id="title" v-model="IncomesQuery" @input="getAutocompleteResults" class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 sm:text-sm/6 z-2" />
                                            <ul class="bg-white text-black p-1 rounded-b-md top-[-5px] relative z-1">
                                                <li v-for="suggestion in suggestions" :key="suggestion" @click="setTitle(suggestion)" class="cursor-pointer">
                                                    <a href="#" @click="setTitle(suggestion)">{{ suggestion }}</a>
                                                </li>
                                            </ul>
                                            <input type="hidden" name="title" id="title" v-model="form.title" class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <InputLabel for="source" class="block text-sm/6 font-medium text-white">
                                            Сумма
                                        </InputLabel>
                                        <div class="mt-2">
                                            <input type="text" name="amount" id="amount" v-model="form.amount"
                                                   class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <InputLabel for="source" class="block text-sm/6 font-medium text-white">
                                            Источник
                                        </InputLabel>
                                        <div class="mt-2">
                                            <input type="text" name="source" id="source" v-model="form.source"
                                                   class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                                        </div>
                                    </div>


                                    <div class="sm:col-span-2">
                                        <label for="country" class="block text-sm/6 font-medium text-white">Счет</label>
                                        <div class="mt-2 relative">
                                            <input type="text" v-model="accountQuery"
                                                   @input="filterAccounts" placeholder="Начните вводить..."
                                                   class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                            />
                                            <div
                                                class="absolute top-0 mt-0 w-full rounded-md bg-white shadow-lg z-10">
                                                <select id="account" name="account" v-model="form.account_id"
                                                        class="block w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm/6">

                                                    <option v-for="account in filteredAccounts"
                                                            :key="account.id" :value="account.id">{{ account.title }}
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
