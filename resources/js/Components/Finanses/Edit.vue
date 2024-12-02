<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';
import InputError from '@/Components/InputError.vue';
import Select from '@/Components/Select.vue';

import { ArrowLeftCircleIcon, CheckCircleIcon } from '@heroicons/vue/24/outline/index.js';


const props = defineProps({
    item: {
        required: true,
        type: Object
    },
    fillableFields: {
        required: true,
        type: Object
    },
    fields: {
        required: true,
        type: Object
    },
    type: {
        required: true,
        type: String
    }
});

const form = useForm(
    Object.fromEntries(props.fillableFields.map(field => [field, props.item[field]]))
);


const createBudget = () => {
    form.patch(route(props.type + '.update', props.item.slug));
};
</script>

<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-gray-800 py-6 sm:py-8">
                <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
                    <h2 class="text-center text-base/7 font-semibold text-white flex justify-center items-center space-x-2 w-full">
                        <span>{{ $t('create new budget') }}</span>
                    </h2>
                    <form @submit.prevent="createBudget()">
                        <div class="space-y-12">

                            <div class="border-b border-gray-900/10 pb-12">

                                <div class="mt-10 flex flex-col gap-x-6 gap-y-8">
                                    <div v-for="field in fillableFields">
                                        <InputLabel :target="field" :value="$t(field)" />
                                        <div class="mt-2">
                                            <NumberInput v-if="fields.numbers.includes(field)" :id="field"
                                                         v-model="form[field]"
                                                         :model-value="form[field]" :name="field" />

                                            <Select v-else-if="fields.lists[field]" v-model="form[field]"
                                                    :model-value="form[field]" :options="fields.lists[field]" />
                                            <TextInput v-else :id="field"
                                                       v-model="form[field]"
                                                       :model-value="form[field]" :name="field" />
                                            <InputError v-if="form.errors[field]"
                                                        :message="$t(field) +  form.errors[field]" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <Link :href="route('budgets.index')"
                                  class="flex select-none items-center gap-3 rounded-lg bg-gray-900 py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none border"
                                  type="button">
                                <ArrowLeftCircleIcon class="size-6" />
                                {{ $t('cancel') }}
                            </Link>
                            <button
                                class="flex select-none items-center gap-3 rounded-lg bg-gray-900 py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none border"
                                type="submit">
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
