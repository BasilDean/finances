<script setup>
const notifications = [
    {
        message: "Hey, what's up? All set for the presentation?",
        time: 'a few moments ago',
    },
];

const showNotifications = () => {
    // document.getElementById('notification-dropdown').classList.toggle('hidden');
};
</script>

<template>
    <button
        class="mr-1 rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:ring-4 focus:ring-gray-300 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-600"
        data-dropdown-toggle="notification-dropdown"
        type="button"
        @click.prevent="showNotifications()"
    >
        <span class="sr-only">View notifications</span>
        <!-- Bell icon -->
        <svg
            aria-hidden="true"
            class="h-6 w-6"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
            ></path>
        </svg>
    </button>
    <div
        id="notification-dropdown"
        class="z-50 my-4 hidden max-w-sm list-none divide-y divide-gray-100 overflow-hidden rounded rounded-xl bg-white text-base shadow-lg dark:divide-gray-600 dark:bg-gray-700"
    >
        <div
            class="block bg-gray-50 px-4 py-2 text-center text-base font-medium text-gray-700 dark:bg-gray-600 dark:text-gray-300"
        >
            Notifications
        </div>
        <div>
            <a
                v-for="(notification, index) in notifications"
                :key="index"
                class="flex border-b px-4 py-3 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600"
                href="#"
            >
                <div class="w-full pl-3">
                    <div
                        class="mb-1.5 text-sm font-normal text-gray-500 dark:text-gray-400"
                    >
                        {{ notification.message }}
                    </div>
                    <div class="text-xs font-medium text-black dark:text-white">
                        {{ notification.time }}
                    </div>
                </div>
            </a>
        </div>
        <a
            class="text-md block bg-gray-50 py-2 text-center font-medium text-gray-900 hover:bg-gray-100 dark:bg-gray-600 dark:text-white dark:hover:underline"
            href="#"
        >
            <div class="inline-flex items-center">
                <svg
                    aria-hidden="true"
                    class="mr-2 h-4 w-4 text-gray-500 dark:text-gray-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                    <path
                        clip-rule="evenodd"
                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                        fill-rule="evenodd"
                    ></path>
                </svg>
                View all
            </div>
        </a>
    </div>
</template>

<style scoped></style>
