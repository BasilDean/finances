<script setup>
import { PlusCircleIcon } from '@heroicons/vue/24/outline/index.js';
import { Link } from '@inertiajs/vue3';

defineProps({
    route: {
        required: true,
        type: String,
    },
});
</script>

<template>
    <Link
        :href="route"
        :title="$t('add')"
        class="flex select-none items-center gap-3 rounded-lg border bg-gray-900 px-4 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
        type="button"
    >
        <PlusCircleIcon class="size-6 text-white" />
        {{ $t('add') }}
    </Link>
</template>
