<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';
import InputError from '@/Components/InputError.vue';
import Select from '@/Components/Select.vue';
import { pick } from 'lodash';

import {
    ArrowLeftCircleIcon,
    CheckCircleIcon,
} from '@heroicons/vue/24/outline/index.js';
import DatePicker from '@/Components/DatePicker.vue';
import Multiselect from '@/Components/Miltiselect.vue';

const props = defineProps({
    item: {
        required: true,
        type: Object,
    },
    fields: {
        required: true,
        type: Object,
    },
    type: {
        required: true,
        type: String,
    },
});
const formData = pick(props.item, Object.keys(props.fields));

const form = useForm(formData);

const createBudget = () => {
    form.patch(route(props.type + '.update', props.item.slug));
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
                        <span>{{ $t('edit') }} {{ item.title }}</span>
                    </h2>
                    <form @submit.prevent="createBudget()">
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-3">
                                <div class="mt-4 flex flex-col gap-x-6 gap-y-3">
                                    <div
                                        v-for="(params, key) in fields"
                                        v-show="params.editable"
                                        :key="key"
                                    >
                                        <div v-if="params.editable">
                                            <InputLabel
                                                :target="key"
                                                :value="$t(key)"
                                            />
                                            <div class="mt-2">
                                                <NumberInput
                                                    v-if="
                                                        params.type === 'number'
                                                    "
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
                                                        params.type ===
                                                        'boolean'
                                                    "
                                                    v-model="form[key]"
                                                    :model-value="form[key]"
                                                    :options="['true', 'false']"
                                                />
                                                <multiselect
                                                    v-else-if="
                                                        params.type ===
                                                        'relation'
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
                                                        $t(key) +
                                                        form.errors[key]
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-end gap-x-3">
                            <Link
                                :href="route(type + '.index')"
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
