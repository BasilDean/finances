<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    text: {
        required: true,
        type: String,
    },
    link: {
        required: true,
        type: [String, null],
    },
    active: {
        required: true,
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <li>
        <Link
            v-if="link"
            :aria-current="active ? 'page' : ''"
            :class="
                active
                    ? 'flex h-8 items-center justify-center border border-gray-300 bg-blue-50 px-3 text-blue-600 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                    : 'flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'
            "
            :href="link"
        >
            <span v-html="text" />
        </Link>
        <span
            v-else
            :aria-current="active ? 'page' : ''"
            :class="
                active
                    ? 'flex h-8 items-center justify-center border border-gray-300 bg-blue-50 px-3 text-blue-600 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                    : 'flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'
            "
            v-html="text"
        ></span>
    </li>
</template>

<style scoped></style>
