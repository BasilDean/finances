<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    type: {
        required: true,
        type: String,
    },
    items: {
        required: true,
        type: Object,
    },
    title: {
        required: false,
        type: String,
    },
    subtitle: {
        required: false,
        type: String,
    },
    link: {
        required: true,
        type: String,
    },
});
</script>

<template>
    <div class="relative inset-px rounded-lg bg-slate-700">
        <div class="relative flex h-full flex-col overflow-hidden">
            <div class="px-6 pt-8 sm:px-8 sm:pt-4">
                <Link
                    :href="route(`${type}.index`)"
                    class="mt-2 block text-lg font-medium tracking-tight text-white max-lg:text-center"
                >
                    {{ title }}
                </Link>
                <p
                    class="mt-2 max-w-lg text-sm/6 text-slate-400 max-lg:text-center"
                >
                    {{ subtitle }}
                </p>
            </div>
            <div
                class="flex flex-1 flex-col items-center justify-center px-6 max-lg:pb-4 max-lg:pt-4 sm:px-8 lg:pb-2"
            >
                <dl
                    v-for="item in items"
                    :key="item.slug"
                    class="flex w-full flex-col divide-y divide-gray-100"
                >
                    <Link
                        :href="route(`${type}.${link}`, item.slug)"
                        class="px-1 py-1 sm:grid sm:grid-cols-2 sm:gap-3 sm:px-0"
                    >
                        <dt
                            class="text-sm/6 font-medium text-white sm:col-span-1"
                        >
                            {{ $t(item.title) }}
                        </dt>
                        <dd class="mt-1 text-sm/6 text-white sm:mt-0">
                            {{
                                new Intl.NumberFormat('ru-RU', {
                                    style: 'currency', // Use currency format
                                    currency: item.currency, // Adjust currency code as needed
                                    minimumFractionDigits: 2, // Always show two decimal places
                                }).format(item.amount)
                            }}
                            {{ $t(item.currency) }}
                        </dd>
                    </Link>
                </dl>
            </div>
        </div>
        <div
            class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5"
        />
    </div>
</template>

<style scoped></style>
