<script setup>

import { PencilSquareIcon } from '@heroicons/vue/24/outline/index.js';
import { Link } from '@inertiajs/vue3';

const props = defineProps(
    {
        slug: {
            required: true,
            type: String
        },
        type: {
            required: true,
            type: String
        }
    }
);
const getCreateRoute = (type, slug = '') => {
    return route(type + '.edit', slug);

};
</script>

<template>

    <Link :href="getCreateRoute(type, slug)" :title="$t('edit')"
          class="font-medium text-white hover:text-gray-300 border flex rounded-2xl justify-center px-3 py-1 w-15 lg:w-10 items-center">
        <PencilSquareIcon class="size-3 sm:size-4" />
    </Link>
</template>

<style scoped>

</style>
