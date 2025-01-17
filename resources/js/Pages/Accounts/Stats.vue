<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ArrowUturnLeftIcon } from '@heroicons/vue/24/outline/index.js';
import { Chart } from 'highcharts-vue';
import FlatLink from '@/Components/FlatLink.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n(); // Enable translations in the script

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    items: {
        type: Array,
        required: true,
    },
    slug: {
        type: String,
        required: true,
    },
});

const chartOptions = {
    title: {
        text: props.title,
    },
    xAxis: {
        type: 'datetime',
        categories: props.items.map((item) => item.date),
    },
    yAxis: {
        title: {
            text: t('balance'),
        },
    },
    tooltip: {
        formatter: function () {
            return `<b>${this.series.name}</b><br/>
              <b>${t('balance')}:</b> ${this.y}<br/>
              <b>${t('operations')}:</b><br> ${this.point.customInfo.join('\n').replace(/\n/g, '<br/>')}`;
        },
    },
    series: [
        {
            name: props.title,
            data: props.items.map((item) => item.data),
        },
    ],
};
</script>

<template>
    <Head :title="'test'" />
    <DashboardLayout>
        <div class="max-w-8xl mx-auto space-y-6 px-2">
            <div class="bg-gray-800 py-3">
                <div class="max-w-8xl relative mx-auto px-6 lg:px-8">
                    <FlatLink
                        :href="route('accounts.show', slug)"
                        class="text-white"
                    >
                        <ArrowUturnLeftIcon class="size-3 text-white" />
                    </FlatLink>
                </div>
            </div>
            <Chart :options="chartOptions" />
        </div>
    </DashboardLayout>
</template>

<style scoped></style>
