<script setup>
import { ref, watch } from 'vue';
import draggable from 'vuedraggable';
import CategoriesDraggable from '@/Components/Categories/CategoriesDraggable.vue';
import { EyeIcon, WrenchIcon } from '@heroicons/vue/24/outline/index.js';
import { Link } from '@inertiajs/vue3';
import DeleteButton from '@/Components/Finanses/DeleteButton.vue';

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
});
const emit = defineEmits(['update:modelValue', 'update-tree']);

const categories = ref([...props.modelValue]);

// Watch the modelValue prop for changes and update categories
watch(
    () => props.modelValue,
    (newValue) => {
        categories.value = [...newValue];
    },
);

// Emit when the tree has changed
const onCategoryChange = () => {
    emit('update:modelValue', JSON.parse(JSON.stringify(categories.value)));
    emit('update-tree', categories.value);
};

// Emit tree update for recursive updates
// const emitUpdateTree = (newValue) => {
//     emit('update-tree', newValue);
// };

const onMove = (evt) => {
    const { draggedContext, relatedContext } = evt;

    // Ensure dragged item is not a duplicate
    if (draggedContext && draggedContext.element && draggedContext.element.id) {
        const draggedItem = draggedContext.element;

        // Remove the dragged item from its original position
        const originalList = draggedContext.list;
        originalList.splice(draggedContext.index, 1);

        // Insert the item into the drop target at the specified position
        const targetList = relatedContext.list;
        targetList.splice(relatedContext.index, 0, draggedItem);
    }
};

const onDragEnd = () => {
    // Ensure tree consistency by removing duplicates
    categories.value = cleanTree(categories.value);
};

const cleanTree = (list) => {
    const ids = new Set();
    return list
        .filter((item) => {
            if (ids.has(item.id)) return false;
            ids.add(item.id);
            return true;
        })
        .map((item) => {
            if (item.children && item.children.length > 0) {
                item.children = cleanTree(item.children);
            }
            return item;
        });
};
</script>

<template>
    <div class="w-100 bg-gray-800">
        <!-- Render draggable list -->
        <draggable
            v-model="categories"
            :animation="200"
            :fallbackOnBody="true"
            :fallbackTolerance="10"
            :group="{ name: 'categories', pull: true, put: true }"
            item-key="id"
            @change="onCategoryChange"
            @move="onMove"
        >
            <template #item="{ element }">
                <div class="category-item p-2">
                    <div
                        class="flex h-full w-full items-center rounded-md border-gray-700 bg-gray-900 p-1 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <div class="w-6/12 p-2">
                            {{ element.title }}
                        </div>
                        <div class="w-5/12">
                            использовано: {{ element.usage_count }}
                        </div>
                        <div class="w-1/12">
                            <div class="flex justify-evenly gap-2">
                                <Link
                                    v-if="false"
                                    :href="
                                        route('categories.show', element.slug)
                                    "
                                    class="flex select-none items-center rounded-lg border bg-gray-900 px-2 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                    type="button"
                                >
                                    <EyeIcon class="size-4" />
                                </Link>
                                <Link
                                    :href="
                                        route('categories.edit', element.slug)
                                    "
                                    class="flex select-none items-center gap-2 rounded-lg border bg-gray-900 px-2 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                    type="button"
                                >
                                    <WrenchIcon class="size-4" />
                                </Link>
                                <DeleteButton
                                    :slug="element.slug"
                                    :title="element.title"
                                    type="categories"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- Render children if present -->
                    <div
                        v-if="element.children && element.children.length"
                        class="ml-5"
                    >
                        <CategoriesDraggable
                            v-model="element.children"
                            @update-tree="onCategoryChange"
                        />
                    </div>
                </div>
            </template>
        </draggable>
    </div>
</template>

<style scoped></style>
