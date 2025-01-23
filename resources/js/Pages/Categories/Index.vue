<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { ref } from 'vue';
import CategoriesDraggable from '@/Components/Categories/CategoriesDraggable.vue';
import {
    CheckCircleIcon,
    PlusCircleIcon,
} from '@heroicons/vue/24/outline/index.js';

const props = defineProps({
    categories: {
        type: Array,
        required: true,
    },
    fields: {
        type: Object,
        required: true,
    },
});

const cats = ref([...props.categories]);

// Handle tree updates
const onTreeUpdate = (updatedBranch) => {
    // Helper function to find and update the node in the original tree
    const updateTree = (nodes, updatedNode) => {
        return nodes.map((node) => {
            // Check if the node being updated matches the current node
            if (node.id === updatedNode.id) {
                return { ...updatedNode }; // Replace the node with the updated branch
            }

            // Recursively search for the node in `children` if it exists
            if (node.children && node.children.length) {
                return {
                    ...node,
                    children: updateTree(node.children, updatedNode),
                };
            }

            return node; // Return the node if it's not being updated
        });
    };

    // Call the helper function with the topmost tree and the updated branch
    cats.value = updateTree(cats.value, updatedBranch);
};

const updateCategories = () => {
    const form = useForm({ categories: cats.value });
    form.post(route('categories.update-order'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Категории" />
    <DashboardLayout>
        <div class="max-w-8xl mx-auto min-h-screen space-y-6 sm:px-2 lg:px-3">
            <div class="min-h-screen py-6">
                <div class="align-center flex justify-between">
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                    >
                        Категории
                    </h2>
                    <div class="flex gap-2">
                        <Link
                            :href="route('categories.create')"
                            class="mb-2 mr-auto flex select-none items-center gap-2 rounded-lg border bg-gray-900 px-2 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        >
                            {{ $t('create') }}
                            <PlusCircleIcon class="size-6" />
                        </Link>
                        <button
                            class="mb-2 mr-auto flex select-none items-center gap-2 rounded-lg border bg-gray-900 px-2 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            type="submit"
                            @click.prevent="updateCategories"
                        >
                            {{ $t('save') }}
                            <CheckCircleIcon class="size-6" />
                        </button>
                    </div>
                </div>
                <CategoriesDraggable
                    v-model="cats"
                    @update-tree="onTreeUpdate"
                />
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped></style>
