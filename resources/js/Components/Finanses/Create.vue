<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';
import InputError from '@/Components/InputError.vue';
import Multiselect from '@/Components/Miltiselect.vue';
import Select from '@/Components/Select.vue';

import {
    ArrowLeftCircleIcon,
    CheckCircleIcon,
} from '@heroicons/vue/24/outline/index.js';
import { mapValues } from 'lodash';
import { reactive, watch } from 'vue';
import DatePicker from '@/Components/DatePicker.vue';

const props = defineProps({
    fields: {
        required: true,
        type: Object,
    },
    type: {
        required: true,
        type: String,
    },
    title: {
        required: false,
        type: String,
    },
    resetFields: {
        required: false,
        type: Array,
    },
});
const formData = reactive(
    mapValues(props.fields, (field) => {
        switch (field.type) {
            case 'number':
                return 0; // Default value for number
            case 'list':
                return field.values[0]; // Default value for list
            case 'relation':
                return field.values[0]; // Default value for relation
            case 'date':
                return new Date();
            case 'boolean':
                return false;
            case 'string':
            default:
                return ''; // Default value for text and any other type
        }
    }),
);

const form = useForm(formData);

// Watch for changes to the resetFields prop
watch(
    () => props.resetFields,
    (newFields) => {
        if (newFields) {
            newFields.forEach((field) => {
                if (Object.hasOwn(form, field)) {
                    switch (props.fields[field].type) {
                        case 'number':
                            form[field] = 0;
                            break;
                        case 'list':
                        case 'relation':
                            form[field] = props.fields[field].values[0];
                            break;
                        case 'date':
                            form[field] = new Date();
                            break;
                        case 'string':
                        default:
                            form[field] = '';
                    }
                }
            });
        }
    },
);

// Format a date object to a custom string (if needed for display or API)
// const formatDateToString = (date) => {
//     const pad = (num) => num.toString().padStart(2, '0');
//     return `${pad(date.getHours())}:${pad(date.getMinutes())} ${pad(date.getDate())}-${pad(date.getMonth() + 1)}-${date.getFullYear()}`;
// };

const createItem = () => {
    form.post(route(props.type + '.store'));
};
</script>

<template>
    <div class="py-2">
        <div class="max-w-8xl mx-auto space-y-2 sm:px-1 lg:px-2">
            <div class="bg-gray-800 py-6 sm:py-4">
                <div class="max-w-8xl lg:max-w-8xl mx-auto px-6 lg:px-4">
                    <h2
                        class="flex w-full items-center justify-center space-x-2 text-center text-base/7 font-semibold text-white"
                    >
                        <span>{{ title }}</span>
                    </h2>
                    <form @submit.prevent="createItem()">
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-3">
                                <div class="mt-4 flex flex-col gap-x-6 gap-y-3">
                                    <div
                                        v-for="(params, key) in fields"
                                        v-show="params.editable"
                                        :key="key"
                                    >
                                        <InputLabel
                                            :target="key"
                                            :value="$t(key)"
                                        />
                                        <div class="mt-1">
                                            <NumberInput
                                                v-if="params.type === 'number'"
                                                :id="key"
                                                v-model="form[key]"
                                                :model-value="form[key]"
                                                :name="key"
                                            />

                                            <Select
                                                v-else-if="
                                                    params.type === 'list'
                                                "
                                                v-model="form[key]"
                                                :model-value="form[key]"
                                                :options="params.values"
                                            />
                                            <Select
                                                v-else-if="
                                                    params.type === 'boolean'
                                                "
                                                v-model="form[key]"
                                                :model-value="form[key]"
                                                :options="['true', 'false']"
                                            />
                                            <multiselect
                                                v-else-if="
                                                    params.type === 'relation'
                                                "
                                                v-model="form[key]"
                                                :allow-empty="false"
                                                :name="key"
                                                :options="params.values"
                                                :placeholder="$t(key)"
                                                :track-by="params.showField"
                                            />
                                            <date-picker
                                                v-else-if="
                                                    params.type === 'date'
                                                "
                                                :id="key"
                                                v-model="form[key]"
                                                :model-value="form[key]"
                                                :name="key"
                                            />
                                            <TextInput
                                                v-else
                                                :id="key"
                                                v-model="form[key]"
                                                :model-value="form[key]"
                                                :name="key"
                                            />
                                            <InputError
                                                v-if="form.errors[key]"
                                                :message="
                                                    $t(key) + form.errors[key]
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-end gap-x-3">
                            <Link
                                :href="route('home')"
                                class="flex select-none items-center gap-2 rounded-lg border bg-gray-900 px-2 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button"
                            >
                                <ArrowLeftCircleIcon class="size-6" />
                                {{ $t('cancel') }}
                            </Link>
                            <button
                                class="flex select-none items-center gap-2 rounded-lg border bg-gray-900 px-2 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="submit"
                            >
                                {{ $t('save') }}
                                <CheckCircleIcon class="size-6" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
