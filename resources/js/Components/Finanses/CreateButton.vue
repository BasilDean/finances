<script setup>
import { PlusCircleIcon } from '@heroicons/vue/24/outline/index.js';
import { Link } from '@inertiajs/vue3';

const props = defineProps(
    {
        route: {
            required: true,
            type: String
        }
    }
);
</script>

<template>
    <Link :href="route" :title="$t('add')"
          class="flex select-none items-center gap-3 rounded-lg bg-gray-900 py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none border"
          type="button">
        <PlusCircleIcon class="size-6 text-white" />
        {{ $t('add') }}
    </Link>
</template>

