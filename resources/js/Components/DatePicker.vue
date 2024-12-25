<script setup>

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref } from 'vue';

const props = defineProps(
    {
        range: {
            required: false,
            type: Boolean,
            default: false
        }
    }
);

const date = ref(new Date());

const model = defineModel({
    type: Date,
    required: true
});
console.log(model.value);

const displayName = ({ title, name }) => {
    return `${title || ''} ${name || ''}`.trim();
};
</script>

<template>
    <div>
        <VueDatePicker v-model="model"></VueDatePicker>
    </div>
</template>


<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

