<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps(
    {
        text: {
            required: true,
            type: Text
        },
        link: {
            required: true,
            type: Text
        },
        active: {
            required: true,
            type: Boolean,
            default: false
        }
    }
);
</script>

<template>
    <li>
        <Link :aria-current="active ? 'page' : ''"
              :class="active ? 'flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
      : 'flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'"
              :href="link" v-html="text"
        ></Link>
    </li>
</template>

<style scoped>

</style>
