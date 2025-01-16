<script setup>
import { TrashIcon } from '@heroicons/vue/24/outline/index.js';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    type: {
        required: true,
        type: String,
    },
    slug: {
        required: true,
        type: String,
    },
    title: {
        required: true,
        type: String,
    },
});

const getDeleteRoute = (type, slug) => {
    return route(type + '.destroy', slug);
};

const deleteForm = useForm({});

const deleteItem = (type, slug, title) => {
    console.log(
        `title = ${title} slug = ${slug} type = ${type} getDeleteRoute(type, slug) = ${getDeleteRoute(type, slug)}`,
    );
    const confirmation = prompt('Чтобы удалить запись введите её название');
    // noinspection EqualityComparisonWithCoercionJS
    if (confirmation == title) {
        deleteForm.delete(getDeleteRoute(type, slug));
    } else {
        alert('Название введено не верно!');
    }
};
</script>

<template>
    <button
        :title="$t('delete')"
        class="w-15 flex items-center justify-center rounded-2xl border px-3 py-1 font-medium text-white hover:text-gray-300 lg:w-10"
        @click.prevent="deleteItem(props.type, props.slug, props.title)"
    >
        <TrashIcon class="size-3 sm:size-4" />
    </button>
</template>

<style scoped></style>
