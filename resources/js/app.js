import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import Lara from '@primeuix/themes/lara';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import PrimeVue from 'primevue/config';
import { createApp, h } from 'vue';
import { createI18n } from 'vue-i18n';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/src/js/index.js';
import ru from './locales/ru.json';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Load translation messages
const messages = {
    ru: ru,
    // other languages go here
};

// Create i18n instance with options
const i18n = createI18n({
    locale: 'ru', // set the default locale
    fallbackLocale: 'ru', // set fallback locale
    messages, // set locale messages
    silentTranslationWarn: true,
});

// noinspection JSIgnoredPromiseFromCall
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n) // Use i18n in your app
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: Lara,
                },
                options: {},
                ripple: true,
                locale: {
                    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                    monthNames: [
                        'Январь',
                        'Февраль',
                        'Март',
                        'Апрель',
                        'Май',
                        'Июнь',
                        'Июль',
                        'Август',
                        'Сентябрь',
                        'Октябрь',
                        'Ноябрь',
                        'Декабрь',
                    ],
                    monthNamesShort: [
                        'Янв',
                        'Фев',
                        'Мар',
                        'Апр',
                        'Май',
                        'Июн',
                        'Июл',
                        'Авг',
                        'Сен',
                        'Окт',
                        'Ноя',
                        'Дек',
                    ],
                    firstDayOfWeek: 1,
                    today: 'Сегодня',
                    clear: 'Очистить',
                },
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
