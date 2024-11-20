<script setup>
import NavLink from '@/Components/NavLink.vue';

const props = defineProps(
    {
        items: {
            type: Object,
            required: true
        }
    }
)
</script>

<template>
    <ul role="list" class="divide-y divide-gray-100">
        <li v-for="item in items" :key="item.id" class="gap-x-6 py-5">
            <NavLink :href="route('budgets.show', item.slug)" class="flex justify-between w-full hover:no-underline">
                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm/6 font-semibold text-white">{{ item.title }}</p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                    <p class="text-sm/6 text-gray-900 text-white">{{ item.balance }} {{ item.main_currency}}</p>
                </div>
            </NavLink>
        </li>
    </ul>
</template>


<style scoped>

</style>
