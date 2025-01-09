<script setup>
import { PencilSquareIcon } from '@heroicons/vue/24/outline/index.js';
import { Link } from '@inertiajs/vue3';

defineProps({
    slug: {
        required: true,
        type: String,
    },
    type: {
        required: true,
        type: String,
    },
});
const getCreateRoute = (type, slug = '') => {
    return route(type + '.edit', slug);
};
</script>

<template>
    <Link
        :href="getCreateRoute(type, slug)"
        :title="$t('edit')"
        class="w-15 flex items-center justify-center rounded-2xl border px-3 py-1 font-medium text-white hover:text-gray-300 lg:w-10"
    >
        <PencilSquareIcon class="size-3 sm:size-4" />
    </Link>
</template>

<style scoped></style>
