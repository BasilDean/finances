<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { onMounted } from 'vue';

const props = defineProps({
    range: {
        type: Boolean,
        default: false,
    },
});

const date = defineModel(
    {
        type: [Date, Array],
        required: true,
    },
    'date',
);
console.log(date.value);

onMounted(() => {
    if (props.range) {
        const startDate = new Date(date.value);
        const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
        date.value = [startDate, endDate];
    } else {
        date.value = new Date(date.value);
    }
});

// Custom format function to display date as DD-MM-YYYY
const formatDate = (date) => {
    if (props.range) return;
    if (!date) return '';
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // months are 0-indexed
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`; // Format as DD-MM-YYYY
};
</script>

<template>
    {{ test }}
    <div>
        <VueDatePicker v-model="date" :range locale="ru" @change="formatDate" />
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
