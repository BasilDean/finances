<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';
import {
    BanknotesIcon,
    CalendarDaysIcon,
    MinusCircleIcon,
    PlusCircleIcon,
    UserCircleIcon
} from '@heroicons/vue/24/outline';


const logo = {
    icon: BanknotesIcon
};

const links = [
    {
        name: 'Расход',
        description: 'добавить новую покупку или платёж',
        href: route('expense.index'),
        icon: MinusCircleIcon
    },
    {
        name: 'Доход',
        description: 'добавить новое поступление денег на счёт',
        href: route('income.create'),
        icon: PlusCircleIcon
    },
    {
        name: 'Счёт',
        description: 'добавить новый счёт в ваш бюджет',
        href: route('accounts.create'),
        icon: UserCircleIcon
    },
    {
        name: 'Регулярный платёж',
        description: 'добавить новое напоминание о регулярном платеже',
        href: '#',
        icon: CalendarDaysIcon
    }
];

const showingNavigationDropdown = ref(false);
</script>

<template>

    <div class="w-full">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav
                class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link href="/">
                                    <component :is="logo.icon"
                                               aria-hidden="true"
                                               class="size-6 text-white group-hover:text-indigo-600" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="space-x-8 -my-px ms-10 flex"
                            >
                                <Popover class="relative flex">
                                    <PopoverButton
                                        class="inline-flex items-center gap-x-1 text-sm/6 font-semibold text-white">
                                        <span>{{ $t('add') }}</span>
                                        <ChevronDownIcon aria-hidden="true" class="size-5" />
                                    </PopoverButton>

                                    <transition enter-active-class="transition ease-out duration-200"
                                                enter-from-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-from-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                        <PopoverPanel
                                            class="absolute left-24 xl:left-1/2 z-10 mt-16 flex w-screen max-w-max -translate-x-1/2 px-4">
                                            <div
                                                class="w-screen max-w-md flex-auto overflow-hidden rounded-3xl bg-white text-sm/6 shadow-lg ring-1 ring-gray-900/5">
                                                <div class="p-4">
                                                    <div v-for="item in links" :key="item.name"
                                                         class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                                        <div
                                                            class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                                            <component :is="item.icon"
                                                                       aria-hidden="true"
                                                                       class="size-6 text-gray-600 group-hover:text-indigo-600" />
                                                        </div>
                                                        <div>
                                                            <Link :href="item.href" class="font-semibold text-gray-900">
                                                                {{ item.name }}
                                                                <span class="absolute inset-0" />
                                                            </Link>
                                                            <p class="mt-1 text-gray-600">{{ item.description }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 divide-x divide-gray-900/5 bg-gray-50">
                                                    <a v-for="item in callsToAction" :key="item.name" :href="item.href"
                                                       class="flex items-center justify-center gap-x-2.5 p-3 font-semibold text-gray-900 hover:bg-gray-100">
                                                        <component :is="item.icon"
                                                                   aria-hidden="true"
                                                                   class="size-5 flex-none text-gray-400" />
                                                        {{ item.name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </PopoverPanel>
                                    </transition>
                                </Popover>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                                                type="button"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        clip-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        fill-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            {{ $t('profile') }}
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('budgets.index')" v-html="$t('change budget')" />
                                        <DropdownLink
                                            :href="route('logout')"
                                            as="button"
                                            method="post"
                                        >
                                            {{ $t('Log Out') }}
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                            >
                                <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        d="M4 6h16M4 12h16M4 18h16"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        d="M6 18L18 6M6 6l12 12"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600"
                    >

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                as="button"
                                method="post"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                v-if="$slots.header"
                class="bg-white shadow dark:bg-gray-800"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
