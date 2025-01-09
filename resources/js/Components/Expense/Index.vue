<script setup>
import FlatLink from '@/Components/FlatLink.vue';

defineProps({
    items: {
        required: true,
        type: Object,
    },
});
</script>

<template>
    <table v-if="items" class="min-w-full leading-normal text-white">
        <thead>
            <tr class="bg-gray-800">
                <th
                    class="border-b-2 border-blue-400 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                >
                    Наименование
                </th>
                <th
                    class="border-b-2 border-blue-400 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                >
                    Сумма
                </th>
                <th
                    class="hidden border-b-2 border-blue-400 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider sm:table-cell"
                >
                    Кто
                </th>
                <th
                    class="border-b-2 border-blue-400 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                >
                    Категория
                </th>
                <th
                    class="hidden border-b-2 border-blue-400 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider sm:table-cell"
                >
                    Счёт
                </th>
            </tr>
        </thead>
        <tbody v-if="items">
            <tr
                v-for="(item, index) in items.data"
                :key="item.id || index"
                :class="index % 2 === 0 ? 'bg-gray-500' : 'bg-gray-800'"
            >
                <td class="border-b border-blue-400 px-3 py-2 text-sm">
                    {{ item.title }}
                </td>
                <td class="border-b border-blue-400 px-3 py-2 text-sm">
                    {{ item.amount }}
                </td>
                <td
                    class="hidden border-b border-blue-400 px-3 py-2 text-sm sm:table-cell"
                >
                    {{ item.user.name }}
                </td>
                <td class="border-b border-blue-400 px-3 py-2 text-sm">
                    {{ item.categories[0].title }}
                </td>
                <td
                    class="hidden border-b border-blue-400 px-3 py-2 text-sm sm:table-cell"
                >
                    {{ item.account.title }}
                </td>
            </tr>
        </tbody>
    </table>

    <div
        v-if="items && (items.next_page_url || items.prev_page_url)"
        class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6"
    >
        <div
            class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"
        >
            <div>
                <p class="text-sm text-gray-700">
                    Показывает с
                    {{ ' ' }}
                    <span class="font-medium">{{ items.from }} </span>
                    {{ ' ' }}
                    по
                    {{ ' ' }}
                    <span class="font-medium">{{ items.to }} </span>
                    {{ ' ' }}
                    из
                    {{ ' ' }}
                    <span class="font-medium">{{ items.total }}</span>
                    {{ ' ' }}
                    записей
                </p>
            </div>
            <div>
                <nav
                    aria-label="Pagination"
                    class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                >
                    <FlatLink
                        v-for="(link, index) in items.links"
                        :key="link.url || index"
                        :href="link.url"
                        aria-current="page"
                        class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        {{ link.label }}
                    </FlatLink>
                </nav>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
