<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref } from 'vue';

defineProps({
    range: {
        required: false,
        type: Boolean,
        default: false,
    },
});

// Define the model value as a date
const model = ref(new Date());

// Convert a custom formatted string to a Date object
const parseStringToDate = (dateString) => {
    const [time, date] = dateString.split(' ');
    const [hour, minute] = time.split(':').map(Number);
    const [day, month, year] = date.split('-').map(Number);

    return new Date(year, month - 1, day, hour, minute);
};

// Watch or initialize model value in case input is a preformatted string
if (typeof model.value === 'string') {
    model.value = parseStringToDate(model.value); // Convert the string to a Date object
}
</script>

<template>
    <div>
        <VueDatePicker v-model="model"></VueDatePicker>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
