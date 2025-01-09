<script setup>
import PagLink from '@/Components/Finanses/PagLink.vue';

defineProps({
    links: {
        required: true,
        type: Object,
    },
    from: {
        required: true,
        type: Number,
    },
    to: {
        required: true,
        type: Number,
    },
    total: {
        required: true,
        type: Number,
    },
});
</script>

<template>
    <nav
        aria-label="Table navigation"
        class="flex-column flex flex-wrap items-center justify-between pt-4 md:flex-row"
    >
        <span
            class="mb-4 block w-full text-sm font-normal text-gray-500 md:mb-0 md:inline md:w-auto dark:text-gray-400"
            >Showing
            <span class="font-semibold text-gray-900 dark:text-white"
                >{{ from }}-{{ to }}</span
            >
            of
            <span class="font-semibold text-gray-900 dark:text-white">{{
                total
            }}</span></span
        >
        <ul
            class="inline-flex h-8 -space-x-px rounded-s-lg text-sm rtl:space-x-reverse"
        >
            <PagLink
                v-for="(link, index) in links"
                :key="index"
                :active="link.active"
                :link="link.url"
                :text="link.label"
            />
        </ul>
    </nav>
</template>

<style scoped></style>
