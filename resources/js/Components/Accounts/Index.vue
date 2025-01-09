<script setup>
import NavLink from '@/Components/NavLink.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    items: {
        required: true,
        type: Object,
    },
});

const form = useForm({
    slug: '',
});

const deleteAccount = (slug) => {
    form.slug = slug;
    form.delete(route('accounts.destroy', slug));
};
</script>

<template>
    <ul class="divide-y divide-gray-100" role="list">
        <li v-for="item in items.data" :key="item.id" class="gap-x-6 py-5">
            <NavLink
                :href="route('accounts.show', item.slug)"
                class="flex w-full justify-between hover:no-underline"
            >
                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm/6 font-semibold text-white">
                            {{ item.title }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex hidden shrink-0 sm:flex sm:flex-row sm:items-end"
                >
                    <p class="text-sm/6 text-gray-900 text-white">
                        {{ item.amount }} {{ item.currency }}
                    </p>

                    <button
                        class="ml-3 inline-flex items-center rounded-lg bg-red-500 px-2 py-1 text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                        @click.prevent="deleteAccount(item.slug)"
                    >
                        <svg
                            fill="none"
                            height="14px"
                            stroke="#ffffff"
                            viewBox="0 0 24 24"
                            width="14px"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g
                                id="SVGRepo_tracerCarrier"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            ></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M6 7V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V7M6 7H5M6 7H8M18 7H19M18 7H16M10 11V16M14 11V16M8 7V5C8 3.89543 8.89543 3 10 3H14C15.1046 3 16 3.89543 16 5V7M8 7H16"
                                    stroke="#ffffff"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                ></path>
                            </g>
                        </svg>
                    </button>
                </div>
            </NavLink>
        </li>
    </ul>
</template>

<style scoped></style>
