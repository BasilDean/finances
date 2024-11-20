<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
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
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
