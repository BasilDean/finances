<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const model = defineModel({
    type: Date,
    required: true,
});

// Custom format function to display date as DD-MM-YYYY
const formatDate = (date) => {
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
    <div>
        <VueDatePicker v-model="model" :format="formatDate"></VueDatePicker>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
