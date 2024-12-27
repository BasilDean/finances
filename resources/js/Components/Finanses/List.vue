<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import Paginator from '@/Components/Finanses/Paginator.vue';
import { onMounted, ref, watch } from 'vue';
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import debounce from 'lodash/debounce';
import EditButton from '@/Components/Finanses/EditButton.vue';
import DeleteButton from '@/Components/Finanses/DeleteButton.vue';

const props = defineProps({
    type: {
        required: true,
        type: String,
        default: '',
    },
    items: {
        required: true,
        type: Object,
    },
    fields: {
        required: true,
        type: Object,
    },
    showDetailPage: {
        required: true,
        type: Boolean,
        default: false,
    },

    filterByDate: {
        required: false,
        type: Boolean,
        default: false,
    },
    showSearch: {
        required: false,
        type: Boolean,
        default: true,
    },
    filters: {
        required: false,
        type: Object,
    },
});

const getRoute = (type, action, slug = '') => {
    if (slug !== '') {
        return route(type + '.' + action, slug);
    }
    return route(type + '.' + action);
};

const filterDates = [
    {
        id: 1,
        name: 'За всё время',
    },
    {
        id: 2,
        name: 'За последнюю неделю',
    },
    {
        id: 3,
        name: 'За последний месяц',
    },
    {
        id: 4,
        name: 'За последний год',
    },
    {
        id: 5,
        name: 'За произвольный период',
    },
];

const selected = ref(filterDates[0]);
const search = ref('');

const { url } = usePage();

if (props.type) {
    const updateSearch = debounce(() => {
        router.visit(url, {
            method: 'get',
            data: { search: search.value },
            preserveState: true,
            replace: true,
        });
    }, 500);

    onMounted(() => {
        search.value = props.filters.search || '';
    });

    // Watch for changes in search and trigger the debounced update
    watch(search, updateSearch);
}
</script>

<template>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div
            v-if="filters"
            class="flex-column flex flex-wrap items-center justify-between space-y-4 pb-4 sm:flex-row sm:space-y-0"
        >
            <div v-if="filterByDate && filterDates.length > 0" class="w-80">
                <Listbox v-model="selected" as="div">
                    <div class="relative mt-2">
                        <ListboxButton
                            class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm/6"
                        >
                            <span class="flex items-center">
                                <span class="ml-3 block truncate">{{
                                    selected.name
                                }}</span>
                            </span>
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2"
                            >
                                <ChevronUpDownIcon
                                    aria-hidden="true"
                                    class="size-5 text-gray-400"
                                />
                            </span>
                        </ListboxButton>

                        <transition
                            leave-active-class="transition ease-in duration-100"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <ListboxOptions
                                class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                            >
                                <ListboxOption
                                    v-for="person in filterDates"
                                    :key="person.id"
                                    v-slot="{ active, selected }"
                                    :value="person"
                                    as="template"
                                >
                                    <li
                                        :class="[
                                            active
                                                ? 'bg-indigo-600 text-white'
                                                : 'text-gray-900',
                                            'relative cursor-default select-none py-2 pl-3 pr-9',
                                        ]"
                                    >
                                        <div class="flex items-center">
                                            <span
                                                :class="[
                                                    selected
                                                        ? 'font-semibold'
                                                        : 'font-normal',
                                                    'ml-3 block truncate',
                                                ]"
                                                >{{ person.name }}</span
                                            >
                                        </div>

                                        <span
                                            v-if="selected"
                                            :class="[
                                                active
                                                    ? 'text-white'
                                                    : 'text-indigo-600',
                                                'absolute inset-y-0 right-0 flex items-center pr-4',
                                            ]"
                                        >
                                            <CheckIcon
                                                aria-hidden="true"
                                                class="size-5"
                                            />
                                        </span>
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
            </div>
            <div v-if="showSearch">
                <label class="sr-only" for="table-search">{{
                    $t('search')
                }}</label>
                <div class="relative">
                    <div
                        class="rtl:inset-r-0 pointer-events-none absolute inset-y-0 left-0 flex items-center ps-3 rtl:right-0"
                    >
                        <svg
                            aria-hidden="true"
                            class="h-5 w-5 text-gray-500 dark:text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                clip-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                fill-rule="evenodd"
                            ></path>
                        </svg>
                    </div>
                    <input
                        v-if="props.type"
                        id="table-search"
                        v-model="search"
                        :placeholder="$t('search')"
                        class="block w-80 rounded-lg border border-gray-300 bg-gray-50 p-2 ps-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        name="search"
                        type="text"
                        @input="updateSearch"
                    />
                </div>
            </div>
        </div>
        <table
            class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400"
        >
            <thead
                class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400"
            >
                <tr>
                    <th
                        v-for="(params, key) in fields"
                        v-show="params.show"
                        :class="
                            params.hideOnMobile
                                ? 'hidden px-1 py-1 sm:table-cell sm:px-3 sm:py-3'
                                : 'px-1 py-1 sm:px-3 sm:py-3'
                        "
                        scope="col"
                    >
                        {{ $t(key) }}
                    </th>
                    <th class="hidden px-1 py-1 sm:flex sm:px-3 sm:py-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(item, key) in items.data"
                    :key="key"
                    class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600"
                >
                    <td
                        v-for="(params, key) in fields"
                        v-show="params.show"
                        :key="key"
                        :class="
                            params.hideOnMobile ? 'hidden sm:table-cell' : ''
                        "
                    >
                        <Link
                            v-if="showDetailPage"
                            :href="
                                getRoute(item.kind ?? type, 'show', item.slug)
                            "
                            class="block px-3 py-1 sm:px-3 sm:py-3"
                            v-html="$t(item[key])"
                        />
                        <span
                            v-else
                            class="block px-3 py-1 sm:px-3 sm:py-3"
                            v-html="$t(item[key])"
                        />
                        <!--                    <span v-else class="px-3 sm:px-3 py-1 sm:py-3 block" v-html="item[key]" />-->
                    </td>
                    <td class="hidden sm:block">
                        <div
                            class="flex justify-end gap-4 px-1 py-1 sm:px-3 sm:py-3"
                        >
                            <EditButton
                                :slug="item.slug"
                                :type="item.kind ?? type"
                            />
                            <DeleteButton
                                :slug="item.slug"
                                :title="item.title"
                                :type="item.kind ?? type"
                            />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Paginator
        v-if="items.per_page < items.total"
        :from="items.from"
        :links="items.links"
        :to="items.to"
        :total="items.total"
    />
</template>

<style scoped></style>
