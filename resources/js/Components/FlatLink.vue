<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
    classes: {
        type: String,
    },
});
</script>

<template>
    <Link :class="classes" :href="href">
        <slot />
    </Link>
</template>
