<script setup>
import {
    ArchiveBoxArrowDownIcon,
    PlusIcon,
    TrashIcon,
} from '@heroicons/vue/24/outline/index.js';
import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';
import { useForm } from '@inertiajs/vue3'; // Create a local reactive copy of `items`
import { reactive } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    fields: {
        required: true,
        type: Object,
    },
    items: {
        required: true,
        type: Object,
    },
    purchase: {
        required: true,
        type: String,
    },
    total: {
        required: true,
        type: Number,
    },
    total_calculated: {
        required: true,
        type: Number,
    },
    currency: {
        required: true,
        type: String,
    },
});

const { t } = useI18n(); // Provides access to the translation method `t`
const localItems = reactive([...props.items]);

const addItem = () => {
    localItems.push(
        Object.fromEntries(
            Object.entries(props.fields).map(([key, field]) => [
                key,
                field.type === 'number' ? 0 : '',
            ]),
        ),
    );
};

const saveItems = () => {
    const form = reactive(useForm({ localItems: localItems }));
    for (const item of localItems) {
        for (const [key, value] of Object.entries(item)) {
            if (!['title', 'price', 'quantity'].includes(key)) continue;
            if (
                value === '' ||
                value === null ||
                value === undefined ||
                value === 0
            ) {
                alert(`Поле "${t(key)}" обязательно для заполнения.`);
                return;
            }
        }
    }
    form.post(route('purchase.items', props.purchase));
};

const deleteForm = useForm({});

const getDeleteRoute = (id) => {
    return route('purchaseItem.destroy', id);
};

function deleteItem(localIndex, id) {
    if (id) {
        if (confirm('Вы уверены, что хотите удалить этот элемент?')) {
            localItems.splice(localIndex, 1);
            deleteForm.delete(getDeleteRoute(id));
        }
    } else {
        localItems.splice(localIndex, 1);
    }
}
</script>

<template>
    <div class="pt-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-gray-800 pt-3 sm:pt-4 lg:px-8">
                <div class="mx-auto">
                    <h2
                        class="w-full items-center justify-center space-x-2 pb-5 text-center text-base/7 font-semibold text-white"
                    >
                        <span>{{ $t('edit items') }}</span>
                        <span
                            :class="
                                total_calculated === total
                                    ? 'text-green-500'
                                    : 'text-red-500'
                            "
                            >{{
                                ' ' +
                                total_calculated +
                                ' / ' +
                                total +
                                ' ' +
                                t(currency)
                            }}
                        </span>
                    </h2>
                    <section class="">
                        <div class="">
                            <!-- Start coding here -->
                            <div
                                class="relative overflow-hidden bg-white shadow-md sm:rounded-lg dark:bg-gray-800"
                            >
                                <div class="overflow-x-auto">
                                    <table
                                        class="w-full text-left text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        <thead
                                            class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400"
                                        >
                                            <tr>
                                                <th
                                                    v-for="(
                                                        item, key
                                                    ) in fields"
                                                    v-show="item.show"
                                                    :key="key"
                                                    class="px-4 py-3"
                                                    scope="col"
                                                >
                                                    {{ $t(key) }}
                                                </th>
                                                <th
                                                    class="px-4 py-3"
                                                    scope="col"
                                                >
                                                    <span class="sr-only"
                                                        >Actions</span
                                                    >
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    item, index
                                                ) in localItems"
                                                :key="index"
                                                class="border-b dark:border-gray-700"
                                            >
                                                <th
                                                    v-for="(
                                                        field, key
                                                    ) in fields"
                                                    v-show="field.show"
                                                    :key="key"
                                                    class="whitespace-nowrap p-0 font-medium text-gray-900 dark:text-white"
                                                    scope="row"
                                                >
                                                    <NumberInput
                                                        v-if="
                                                            field.type ===
                                                            'number'
                                                        "
                                                        :id="key"
                                                        v-model="item[key]"
                                                        :model-value="item[key]"
                                                        :name="key"
                                                        :placeholder="$t(key)"
                                                        required
                                                    />
                                                    <TextInput
                                                        v-else
                                                        :id="key"
                                                        v-model="item[key]"
                                                        :model-value="item[key]"
                                                        :name="key"
                                                        :placeholder="$t(key)"
                                                        required
                                                    />
                                                    <!--                                <InputError v-if="form.errors[key]"-->
                                                    <!--                                            :message="$t(key) +  form.errors[key]" />-->
                                                </th>
                                                <td
                                                    class="flex items-center justify-center px-4 py-2"
                                                >
                                                    <button
                                                        class="flex items-center justify-center align-middle"
                                                        @click.prevent="
                                                            deleteItem(
                                                                index,
                                                                item.id,
                                                            )
                                                        "
                                                    >
                                                        <TrashIcon
                                                            class="size-6"
                                                        />
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0"
                                >
                                    <div class="flex w-full">
                                        <button
                                            class="flex select-none items-center gap-3 rounded-lg border bg-gray-900 px-4 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                            type="button"
                                            @click.prevent="addItem()"
                                        >
                                            {{ $t('Add product') }}
                                            <PlusIcon class="size-6" />
                                        </button>
                                        <button
                                            class="ml-auto flex select-none items-center gap-3 rounded-lg border bg-gray-900 px-4 py-2 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                            type="button"
                                            @click.prevent="saveItems()"
                                        >
                                            {{ $t('save') }}
                                            <ArchiveBoxArrowDownIcon
                                                class="size-6"
                                            />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>
