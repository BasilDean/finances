<script setup>

import { TrashIcon } from '@heroicons/vue/24/outline/index.js';
import { useForm } from '@inertiajs/vue3';

const props = defineProps(
    {
        type: {
            required: true,
            type: String
        },
        slug: {
            required: true,
            type: String
        },
        title: {
            required: true,
            type: String
        }
    }
);

const getDeleteRoute = (type, slug) => {
    console.log(route(type + '.destroy', slug));
    return route(type + '.destroy', slug);
};

const deleteForm = useForm({});

const deleteItem = (type, slug, title) => {
    const confirmation = prompt('Чтобы удалить запись введите её название');
    if (confirmation === title) {
        deleteForm.delete(getDeleteRoute(props.type, props.slug));
    } else {
        alert('Название введено не верно!');
    }
};
</script>

<template>
    <button :title="$t('delete')"
            class="font-medium text-white hover:text-gray-300 border flex rounded-2xl justify-center px-3 py-1 w-15 lg:w-10 items-center"
            @click.prevent="deleteItem(props.type, props.slug, props.title)">
        <TrashIcon class="size-3 sm:size-4" />
    </button>
</template>

<style scoped>

</style>
