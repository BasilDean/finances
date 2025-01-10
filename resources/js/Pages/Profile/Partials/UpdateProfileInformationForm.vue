<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    budgets: {
        type: Object,
    },
    weekdays: {
        type: Object,
    },
    settings: {
        type: Object,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    startOfTheWeek: props.settings.start_of_the_week,
    active_budget: props.settings.active_budget,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $t('profile Information') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{
                    $t(
                        "Update your account's profile information and email address.",
                    )
                }}
            </p>
        </header>

        <form
            class="mt-6 space-y-6"
            @submit.prevent="form.patch(route('profile.update'))"
        >
            <div>
                <InputLabel :value="$t('name')" for="name" />

                <TextInput
                    id="name"
                    v-model="form.name"
                    autocomplete="name"
                    autofocus
                    class="mt-1 block w-full"
                    required
                    type="text"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    v-model="form.email"
                    autocomplete="username"
                    class="mt-1 block w-full"
                    required
                    type="email"
                />

                <InputError :message="form.errors.email" class="mt-2" />
            </div>
            <div>
                <InputLabel :value="$t('budget')" for="active_budget" />
                <select
                    id="active_budget"
                    v-model="form.active_budget"
                    class="mt-1 block w-full"
                    name="active_budget"
                >
                    <option
                        v-for="budget in budgets"
                        :key="budget.id"
                        :selected="budget.slug === form.active_budget"
                        :value="budget.slug"
                    >
                        {{ budget.title }}
                    </option>
                </select>
                <InputError :message="form.errors.active_budget" class="mt-2" />
            </div>

            <div>
                <InputLabel
                    :value="$t('start of the week')"
                    for="start_of_the_week"
                />
                <select
                    id="start_of_the_week"
                    v-model="form.startOfTheWeek"
                    class="mt-1 block w-full"
                    name="start_of_the_week"
                >
                    <option
                        v-for="(day, key) in weekdays"
                        :key="key"
                        :selected="day === form.startOfTheWeek"
                        :value="day"
                    >
                        {{ $t(day) }}
                    </option>
                </select>
                <InputError :message="form.errors.active_budget" class="mt-2" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    {{ $t('Your email address is unverified.') }}
                    <Link
                        :href="route('verification.send')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        method="post"
                    >
                        {{
                            $t('Click here to re-send the verification email.')
                        }}
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    {{
                        $t(
                            'A new verification link has been sent to your email address.',
                        )
                    }}
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing"
                    >{{ $t('save') }}
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
