<script setup>
import Select from 'primevue/select';

const props = defineProps({
    values: {
        type: Array,
    },
});

const selectedValue = defineModel(
    {
        type: [String],
        required: true,
    },
    'date',
);
</script>

<template>
    <Select
        v-model="selectedValue"
        :options="props.values"
        class="w-full md:w-56"
        filter
        optionLabel="name"
        placeholder="Select a Country"
    >
        <template #value="slotProps">
            <div v-if="slotProps.value" class="flex items-center">
                <img
                    :alt="slotProps.value.label"
                    :class="`flag mr-2 flag-${slotProps.value.code.toLowerCase()}`"
                    src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                    style="width: 18px"
                />
                <div>{{ slotProps.value.name }}</div>
            </div>
            <span v-else>
                {{ slotProps.placeholder }}
            </span>
        </template>
        <template #option="slotProps">
            <div class="flex items-center">
                <div>{{ slotProps.option.name }}</div>
            </div>
        </template>
    </Select>
</template>
