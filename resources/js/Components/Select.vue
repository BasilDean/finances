<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    options: {
        required: true,
        type: Array,
    },
});

const model = defineModel({
    type: [String, Boolean],
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        ref="input"
        v-model="model"
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
    >
        <option v-for="option in options" :key="option" :value="option">
            {{ $t(option) }}
        </option>
    </select>
</template>
