<script setup>
defineProps({
    value: {
        type: String,
        required: false,
    },
    target: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <label
        :for="target"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
    >
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
