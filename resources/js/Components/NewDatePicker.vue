<script setup>
import DatePicker from 'primevue/datepicker';

const props = defineProps({
    range: {
        type: Boolean,
        default: false,
    },
});

const date = defineModel(
    {
        type: [Date, Array, String],
        required: true,
    },
    'date',
);
</script>

<template>
    <DatePicker
        v-model="date"
        :manualInput="true"
        :numberOfMonths="1"
        :selectOtherMonths="false"
        :selectionMode="props.type ? 'range' : 'single'"
        :showOnFocus="true"
        :showSeconds="true"
        :stepSecond="10"
        fluid
        hourFormat="24"
        iconDisplay="input"
        inputId="on_label"
        showButtonBar
        showIcon
        showTime
    />
</template>
