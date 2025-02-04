<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import Paginator from '@/Components/Finanses/Paginator.vue';
import { onMounted, ref, watch } from 'vue';

import { useI18n } from 'vue-i18n';
import EditButton from '@/Components/Finanses/EditButton.vue';
import DeleteButton from '@/Components/Finanses/DeleteButton.vue';
import { round } from 'lodash';
import DatePicker from '@/Components/DatePicker.vue';
import debounce from 'lodash/debounce.js';

const { t } = useI18n(); // Enable translations in the script

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
        type: [Object, Boolean],
    },
});

const formatValue = (value, currency = null, type = '', key = '') => {
    if (key === 'credit_percent' || key === 'exchange_rate') {
        return round(value, 2);
    }
    if (type === 'number' && key !== 'credit_percent') {
        return new Intl.NumberFormat('ru-RU', {
            style: 'currency',
            currency: currency ?? 'RUB',
            minimumFractionDigits: 1,
        }).format(value);
    }
    if (typeof value === 'boolean') {
        if (value) {
            return t('yes');
        } else {
            return t('no');
        }
    }
    return t(value); // Translate text keys
    // return value; // Translate text keys
};

const getRoute = (type, action, slug = '') => {
    if (slug !== '') {
        return route(type + '.' + action, slug);
    }
    return route(type + '.' + action);
};

const filterDates = [
    {
        id: 1,
        name: 'За последнюю неделю',
        value: 'week',
    },
    {
        id: 2,
        name: 'За последний месяц',
        value: 'month',
    },
    {
        id: 3,
        name: 'За последний год',
        value: 'year',
    },
    {
        id: 4,
        name: 'За всё время',
        value: 'all',
    },
    {
        id: 5,
        name: 'За произвольный период',
        value: 'custom',
    },
];

const search = ref('');
const period = ref('week');
const range = ref([]);

const { url } = usePage();

if (props.type) {
    const updateFilters = debounce(() => {
        const data = {
            search: search.value,
            period: period.value,
        };
        if (period.value === 'custom') {
            data.range = range.value;
        }
        router.visit(url, {
            method: 'get',
            data: data,
            preserveState: true,
            replace: true,
        });
    }, 500);

    onMounted(() => {
        search.value = props.filters.search || '';
        period.value = props.filters.period || 'week';
        range.value = props.filters.range || [];
    });

    // Watch for changes in search and trigger the debounced update
    watch(search, updateFilters);
    watch(period, updateFilters);
    watch(range, updateFilters);
}
</script>

<template>
    <div
        class="relative min-h-screen min-w-full overflow-x-auto shadow-md sm:rounded-lg"
        v-bind="$attrs"
    >
        <div
            v-if="filters"
            class="flex-column flex flex-wrap items-start justify-start space-x-3 space-y-0 pb-4 sm:flex-row sm:space-y-0"
        >
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
            <div v-if="filterByDate && filterDates.length > 0" class="w-60">
                <select v-model="period" class="w-100 rounded" name="period">
                    <option
                        v-for="item in filterDates"
                        :key="item.id"
                        :selected="filters.period === item.value"
                        :value="item.value"
                    >
                        {{ item.name }}
                    </option>
                </select>
            </div>
            <div v-if="period === 'custom'" class="w-80">
                <DatePicker v-model="range" range />
            </div>
        </div>
        <table
            class="min-h-full w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400"
        >
            <thead
                class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400"
            >
                <tr>
                    <th
                        v-for="(params, key) in fields"
                        :key="key"
                        :class="
                            params.hideOnMobile
                                ? 'hidden px-1 py-1 sm:table-cell sm:px-3 sm:py-3'
                                : 'px-1 py-1 sm:px-2 sm:py-2'
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
                            class="block px-2 py-1 sm:px-2 sm:py-2"
                        >
                            {{
                                formatValue(
                                    item[key],
                                    key === 'amount_from'
                                        ? item.currency_from
                                        : key === 'amount_to'
                                          ? item.currency_to
                                          : item.currency,
                                    params.type,
                                    key,
                                )
                            }}
                        </Link>
                        <span v-else class="block px-1 py-1 sm:px-2 sm:py-2">
                            {{
                                formatValue(
                                    item[key],
                                    key === 'amount_from'
                                        ? item.currency_from
                                        : key === 'amount_to'
                                          ? item.currency_to
                                          : item.currency,
                                    params.type,
                                    key,
                                )
                            }}
                        </span>
                    </td>

                    <td class="hidden sm:block">
                        <div
                            class="flex justify-end gap-2 px-1 py-1 sm:px-2 sm:py-2"
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
