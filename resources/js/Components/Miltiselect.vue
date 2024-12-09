<script setup>
import Multiselect from 'vue-multiselect';

const props = defineProps(
    {
        options: {
            required: true,
            type: Array
        },
        name: {
            required: false,
            type: String
        },
        placeholder: {
            required: false,
            type: String
        },
        searchable: {
            required: false,
            type: Boolean,
            default: false
        },
        closeOnSelect: {
            required: false,
            type: Boolean,
            default: true
        },
        showLabels: {
            required: false,
            type: Boolean,
            default: false
        },
        deselectLabel: {
            required: false,
            type: String
        },
        allowEmpty: {
            required: false,
            type: Boolean,
            default: false
        },
        trackBy: {
            required: false,
            type: String
        },
        label: {
            required: false,
            type: String
        },
        multiple: {
            required: false,
            type: Boolean,
            default: false
        }
    }
);

const model = defineModel({
    type: Object,
    required: true
});

const displayName = ({ title, name }) => {
    return `${title || ''} ${name || ''}`.trim();
};
</script>

<template>
    <div class="custom">
        <multiselect
            v-model="model"
            :allow-empty="allowEmpty"
            :customLabel="displayName"
            :deselect-label="$t('field is required')"
            :multiple="multiple"
            :name="name"
            :options="options"
            :placeholder="placeholder"
            :select-label="$t('select label')"
            :selected-label="$t('selected label')"
            :trackBy="trackBy"
            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 w-full pointer"
            Ж />
    </div>
</template>


<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

