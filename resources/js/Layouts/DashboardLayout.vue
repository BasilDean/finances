<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';
import {
    BanknotesIcon,
    CalendarDaysIcon,
    MinusCircleIcon,
    PlusCircleIcon,
    TableCellsIcon,
    UserCircleIcon,
} from '@heroicons/vue/24/outline';
import Notifications from '@/Components/Dashboard/Notifications.vue';
import { PaperAirplaneIcon } from '@heroicons/vue/24/outline/index.js';

const logo = {
    icon: BanknotesIcon,
};

const links = [
    {
        name: 'Расход',
        description: 'добавить новый расход',
        href: route('expense.create'),
        icon: MinusCircleIcon,
    },
    {
        name: 'Доход',
        description: 'добавить новое поступление денег на счёт',
        href: route('income.create'),
        icon: PlusCircleIcon,
    },
    {
        name: 'Перевод',
        description: 'добавить перевод',
        href: route('exchanges.create'),
        icon: PaperAirplaneIcon,
    },
    {
        name: 'Счёт',
        description: 'добавить новый счёт в ваш бюджет',
        href: route('accounts.create'),
        icon: UserCircleIcon,
    },
    {
        name: 'Регулярный платёж',
        description: 'добавить новое напоминание о регулярном платеже',
        href: '#',
        icon: CalendarDaysIcon,
    },
];

const menus = [
    {
        id: 0,
        name: 'Расходы',
        link: route('expense.index'),
        icon: MinusCircleIcon,
        children: [
            {
                id: 1,
                name: 'Просмотреть все',
                link: route('expense.index'),
            },
            {
                id: 2,
                name: 'Создать новый',
                link: route('expense.create'),
            },
        ],
    },
    {
        id: 1,
        name: 'Доходы',
        link: route('income.index'),
        icon: PlusCircleIcon,
        children: [
            {
                id: 1,
                name: 'Просмотреть все',
                link: route('income.index'),
            },
            {
                id: 2,
                name: 'Создать новый',
                link: route('income.create'),
            },
        ],
    },
    {
        id: 2,
        name: 'Переводы',
        link: route('exchanges.index'),
        icon: PaperAirplaneIcon,
        children: [
            {
                id: 1,
                name: 'Просмотреть все',
                link: route('exchanges.index'),
            },
            {
                id: 2,
                name: 'Создать новый',
                link: route('exchanges.create'),
            },
        ],
    },
    {
        id: 3,
        name: 'Счета',
        link: route('accounts.index'),
        icon: UserCircleIcon,
        children: [
            {
                id: 1,
                name: 'Просмотреть все',
                link: route('accounts.index'),
            },
            {
                id: 2,
                name: 'Создать новый',
                link: route('accounts.create'),
            },
        ],
    },
    {
        id: 4,
        name: 'Категории',
        link: route('categories.index'),
        icon: TableCellsIcon,
        children: [
            {
                id: 1,
                name: 'Просмотреть все',
                link: route('categories.index'),
            },
            {
                id: 2,
                name: 'Создать новую',
                link: route('categories.create'),
            },
        ],
    },
];

const showSidebar = () => {
    const sidebar = document.querySelector('#drawer-navigation');
    if (sidebar) {
        sidebar.classList.toggle('-translate-x-full');
    }
};

const showSubmenu = (id) => {
    const submenu = document.querySelector(`#submenu-${id}`);
    if (submenu) {
        submenu.classList.toggle('hidden');
    }
};
</script>

<template>
    <div class="h-full min-h-screen bg-gray-50 antialiased dark:bg-gray-900">
        <nav
            class="fixed left-0 right-0 top-0 z-50 border-b border-gray-200 bg-white px-4 py-2.5 dark:border-gray-700 dark:bg-gray-800"
        >
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center justify-start">
                    <button
                        class="mr-2 cursor-pointer rounded-lg p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 md:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:bg-gray-700 dark:focus:ring-gray-700"
                        @click.prevent="showSidebar()"
                    >
                        <svg
                            aria-hidden="true"
                            class="h-6 w-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                clip-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                fill-rule="evenodd"
                            ></path>
                        </svg>
                        <svg
                            aria-hidden="true"
                            class="hidden h-6 w-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                clip-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                fill-rule="evenodd"
                            ></path>
                        </svg>
                        <span class="sr-only">Toggle sidebar</span>
                    </button>

                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link href="/">
                                <component
                                    :is="logo.icon"
                                    aria-hidden="true"
                                    class="size-6 text-white group-hover:text-indigo-600"
                                />
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="-my-px ms-10 flex space-x-8">
                            <Popover class="relative flex">
                                <PopoverButton
                                    class="inline-flex items-center gap-x-1 text-sm/6 font-semibold text-white"
                                >
                                    <span>{{ $t('add') }}</span>
                                    <ChevronDownIcon
                                        aria-hidden="true"
                                        class="size-5"
                                    />
                                </PopoverButton>

                                <transition
                                    enter-active-class="transition ease-out duration-200"
                                    enter-from-class="opacity-0 translate-y-1"
                                    enter-to-class="opacity-100 translate-y-0"
                                    leave-active-class="transition ease-in duration-150"
                                    leave-from-class="opacity-100 translate-y-0"
                                    leave-to-class="opacity-0 translate-y-1"
                                >
                                    <PopoverPanel
                                        class="absolute left-4 z-10 mt-11 flex w-screen max-w-max -translate-x-36 px-4 xl:left-1/2"
                                    >
                                        <div
                                            class="w-screen max-w-md flex-auto overflow-hidden rounded-3xl bg-white text-sm/6 shadow-lg ring-1 ring-gray-900/5"
                                        >
                                            <div class="p-4">
                                                <div
                                                    v-for="item in links"
                                                    :key="item.name"
                                                    class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50"
                                                >
                                                    <div
                                                        class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white"
                                                    >
                                                        <component
                                                            :is="item.icon"
                                                            aria-hidden="true"
                                                            class="size-6 text-gray-600 group-hover:text-indigo-600"
                                                        />
                                                    </div>
                                                    <div>
                                                        <Link
                                                            :href="item.href"
                                                            class="font-semibold text-gray-900"
                                                        >
                                                            {{ item.name }}
                                                            <span
                                                                class="absolute inset-0"
                                                            />
                                                        </Link>
                                                        <p
                                                            class="mt-1 text-gray-600"
                                                        >
                                                            {{
                                                                item.description
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="grid grid-cols-2 divide-x divide-gray-900/5 bg-gray-50"
                                            >
                                                <a
                                                    v-for="item in callsToAction"
                                                    :key="item.name"
                                                    :href="item.href"
                                                    class="flex items-center justify-center gap-x-2.5 p-3 font-semibold text-gray-900 hover:bg-gray-100"
                                                >
                                                    <component
                                                        :is="item.icon"
                                                        aria-hidden="true"
                                                        class="size-5 flex-none text-gray-400"
                                                    />
                                                    {{ item.name }}
                                                </a>
                                            </div>
                                        </div>
                                    </PopoverPanel>
                                </transition>
                            </Popover>
                        </div>
                    </div>
                </div>
                <div class="flex items-center lg:order-2">
                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <Notifications class="hide" />
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
                                    <DropdownLink :href="route('profile.edit')">
                                        {{ $t('profile') }}
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('budgets.index')"
                                    >
                                        {{ $t('change budget') }}
                                    </DropdownLink>
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
                    <!-- Dropdown menu -->
                    <div
                        id="dropdown"
                        class="z-50 my-4 hidden w-56 list-none divide-y divide-gray-100 rounded rounded-xl bg-white text-base shadow dark:divide-gray-600 dark:bg-gray-700"
                    >
                        <div class="px-4 py-3">
                            <span
                                class="block text-sm font-semibold text-gray-900 dark:text-white"
                                >Neil Sims</span
                            >
                            <span
                                class="block truncate text-sm text-gray-900 dark:text-white"
                                >name@flowbite.com</span
                            >
                        </div>
                        <ul
                            aria-labelledby="dropdown"
                            class="py-1 text-gray-700 dark:text-gray-300"
                        >
                            <li>
                                <a
                                    class="block px-4 py-2 text-sm hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="#"
                                    >My profile</a
                                >
                            </li>
                            <li>
                                <a
                                    class="block px-4 py-2 text-sm hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="#"
                                    >Account settings</a
                                >
                            </li>
                        </ul>
                        <ul
                            aria-labelledby="dropdown"
                            class="py-1 text-gray-700 dark:text-gray-300"
                        >
                            <li>
                                <a
                                    class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="#"
                                >
                                    <svg
                                        class="mr-2 h-5 w-5 text-gray-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            clip-rule="evenodd"
                                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                            fill-rule="evenodd"
                                        ></path>
                                    </svg>
                                    My likes</a
                                >
                            </li>
                            <li>
                                <a
                                    class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="#"
                                >
                                    <svg
                                        class="mr-2 h-5 w-5 text-gray-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"
                                        ></path>
                                    </svg>
                                    Collections</a
                                >
                            </li>
                            <li>
                                <a
                                    class="flex items-center justify-between px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="#"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            aria-hidden="true"
                                            class="text-primary-600 dark:text-primary-500 mr-2 h-5 w-5"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                clip-rule="evenodd"
                                                d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                                                fill-rule="evenodd"
                                            ></path>
                                        </svg>
                                        Pro version
                                    </span>
                                    <svg
                                        aria-hidden="true"
                                        class="h-5 w-5 text-gray-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            clip-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            fill-rule="evenodd"
                                        ></path>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                        <ul
                            aria-labelledby="dropdown"
                            class="py-1 text-gray-700 dark:text-gray-300"
                        >
                            <li>
                                <a
                                    class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="#"
                                    >Sign out</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->

        <aside
            id="drawer-navigation"
            class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full border-r border-gray-200 bg-white pt-14 transition-transform md:translate-x-0 dark:border-gray-700 dark:bg-gray-800"
        >
            <div
                class="h-full overflow-y-auto bg-white px-3 py-5 dark:bg-gray-800"
            >
                <ul v-if="menus" class="space-y-2">
                    <li v-for="menu in menus" :key="menu.id">
                        <Link
                            :href="menu.link"
                            class="group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >
                            <component
                                :is="menu.icon"
                                aria-hidden="true"
                                class="size-6 text-white group-hover:text-indigo-600"
                            />
                            <span class="ml-3">{{ menu.name }}</span>

                            <component
                                :is="ChevronDownIcon"
                                v-if="menu.children.length > 0"
                                aria-hidden="true"
                                class="size-6 text-white group-hover:text-indigo-600"
                                @click.prevent="showSubmenu(menu.id)"
                            />
                        </Link>
                        <ul
                            v-if="menu.children.length > 0"
                            :id="`submenu-${menu.id}`"
                            class="hidden space-y-2 py-2"
                        >
                            <li v-for="child in menu.children" :key="child.id">
                                <a
                                    :href="child.link"
                                    class="group flex w-full items-center rounded-lg p-2 pl-11 text-base font-medium text-gray-900 transition duration-75 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                    >{{ child.name }}</a
                                >
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="h-full min-h-screen p-4 pt-20 md:ml-64">
            <slot />
        </main>
    </div>
</template>
