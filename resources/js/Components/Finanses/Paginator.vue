<script setup>
import PagLink from '@/Components/Finanses/PagLink.vue';

const props = defineProps(
    {
        links: {
            required: true,
            type: Object
        },
        from: {
            required: true,
            type: String
        },
        to: {
            required: true,
            type: String
        },
        total: {
            required: true,
            type: String
        }
    }
);
</script>

<template>
    <nav aria-label="Table navigation"
         class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4">
            <span
                class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing <span
                class="font-semibold text-gray-900 dark:text-white">{{ from }}-{{ to }}</span> of <span
                class="font-semibold text-gray-900 dark:text-white">{{ total }}</span></span>
        <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8 rounded-s-lg ">
            <PagLink v-for="link in links" :active="link.active" :link="link.url" :text="link.label" />
        </ul>
    </nav>
</template>

<style scoped>

</style>
