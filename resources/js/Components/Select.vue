<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps(
    {
        options: {
            required: true,
            type: Array
        }
    }
);

const model = defineModel({
    type: String,
    required: true
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
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 w-full"
    >
        <option v-for="option in options" :value="option">
            {{ option }}
        </option>
    </select>
</template>
