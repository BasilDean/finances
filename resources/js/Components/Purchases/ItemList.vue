<script setup>
import {
    ArchiveBoxArrowDownIcon,
    PlusIcon,
} from '@heroicons/vue/24/outline/index.js';
import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';
import { useForm } from '@inertiajs/vue3';
// Create a local reactive copy of `items`
import { reactive } from 'vue';

const props = defineProps({
    fields: {
        required: true,
        type: Object,
    },
    items: {
        required: true,
        type: Object,
    },
});

const localItems = reactive([...props.items]);

const addItem = () => {
    localItems.push({});
};

const saveItems = () => {
    const form = reactive(useForm({ localItems: localItems }));
    console.log(form);
};
</script>

<template>
    <div class="pt-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-gray-800 pt-3 sm:pt-4">
                <div class="mx-auto">
                    <h2
                        class="flex w-full items-center justify-center space-x-2 pb-5 text-center text-base/7 font-semibold text-white"
                    >
                        <span>{{ $t('edit items') }}</span>
                    </h2>
                    <section class="bg-gray-50 dark:bg-gray-900">
                        <div class="">
                            <!-- Start coding here -->
                            <div
                                class="relative overflow-hidden bg-white shadow-md sm:rounded-lg dark:bg-gray-800"
                            >
                                <div class="overflow-x-auto">
                                    <table
                                        class="w-full text-left text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        <thead
                                            class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400"
                                        >
                                            <tr>
                                                <th
                                                    v-for="(
                                                        item, key
                                                    ) in fields"
                                                    v-show="item.show"
                                                    :key="key"
                                                    scope="col"
                                                    class="px-4 py-3"
                                                >
                                                    {{ $t(key) }}
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-4 py-3"
                                                >
                                                    <span class="sr-only"
                                                        >Actions</span
                                                    >
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    item, index
                                                ) in localItems"
                                                :key="index"
                                                class="border-b dark:border-gray-700"
                                            >
                                                <th
                                                    v-for="(
                                                        field, key
                                                    ) in fields"
                                                    v-show="field.show"
                                                    :key="key"
                                                    scope="row"
                                                    class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white"
                                                >
                                                    <NumberInput
                                                        v-if="
                                                            field.type ===
                                                            'number'
                                                        "
                                                        :id="key"
                                                        v-model="item[key]"
                                                        :model-value="item[key]"
                                                        :name="key"
                                                        :placeholder="$t(key)"
                                                    />
                                                    <TextInput
                                                        v-else
                                                        :id="key"
                                                        v-model="item[key]"
                                                        :model-value="item[key]"
                                                        :name="key"
                                                        :placeholder="$t(key)"
                                                    />
                                                    <!--                                <InputError v-if="form.errors[key]"-->
                                                    <!--                                            :message="$t(key) +  form.errors[key]" />-->
                                                </th>
                                                <td
                                                    class="flex items-center justify-end px-4 py-3"
                                                ></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0"
                                >
                                    <div class="flex w-full">
                                        <button
                                            @click.prevent="addItem()"
                                            type="button"
                                            class="flex select-none items-center gap-3 rounded-lg border bg-gray-900 px-4 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                        >
                                            {{ $t('Add product') }}
                                            <PlusIcon class="size-6" />
                                        </button>
                                        <button @click.prevent="saveItems()"
                                            type="button"
                                            class="ml-auto flex select-none items-center gap-3 rounded-lg border bg-gray-900 px-4 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                        >
                                            {{ $t('save') }}
                                            <ArchiveBoxArrowDownIcon
                                                class="size-6"
                                            />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
