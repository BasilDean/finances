<script setup>
import { Link, router, useForm } from '@inertiajs/vue3';
import { mapValues } from 'lodash';
import { reactive, ref } from 'vue';
import { Form } from '@primevue/forms';
import Button from 'primevue/button';
import FloatLabel from 'primevue/floatlabel';
import Select from 'primevue/select';
import DatePicker from '@/Components/NewDatePicker.vue';
import { useI18n } from 'vue-i18n';
import Message from 'primevue/message';
import ButtonGroup from 'primevue/buttongroup';
import NumberField from '@/Components/Finanses/InputNumber.vue';
import AccountSelector from '@/Components/Finanses/accountSelector.vue';

const { t } = useI18n(); // Enable translations in the script

const props = defineProps({
    fields: {
        required: true,
        type: Object,
    },
    type: {
        required: true,
        type: String,
    },
    title: {
        required: false,
        type: String,
    },
    currencies: {
        required: false,
        type: Array,
        default: () => [],
    },
    status: {
        required: false,
        type: String,
    },
    item: {
        required: false,
        type: Object,
        default: () => ({}),
    },
});
const formData = reactive(
    mapValues(props.fields, (field) => {
        switch (field.type) {
            case 'number':
                return 0; // Default value for number
            case 'list':
                return field.values[0]; // Default value for list
            case 'relation':
                return field.values[0]; // Default value for relation
            case 'date':
                return new Date().toLocaleString();
            case 'boolean':
                return false;
            case 'string':
            default:
                return ''; // Default value for text and any other type
        }
    }),
);

const form = useForm(formData);

const createItem = () => {
    form.post(route(props.type + '.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            if (action.value === 'save') {
                router.visit(route(props.type + '.index'));
            } else if (action.value === 'saveAndAddNew') {
                form['amount_from'] = 0;
                form['amount_to'] = 0;
                form['account_to'] = props.fields.account_to.values.find(
                    (item) => item.id === form['account_to']?.id,
                );
                form['account_from'] = props.fields.account_to.values.find(
                    (item) => item.id === form['account_from']?.id,
                );
            }
        },
    });
};
const resolver = ({ values }) => {
    const errors = {};

    if (!values.amount_from || values.amount_from <= 0) {
        errors.amount_from = [
            { message: t('amount_from') + ' должно быть больше 0.' },
        ];
    }

    if (!values.amount_to || values.amount_to <= 0) {
        errors.amount_to = [
            { message: t('amount_to') + ' должно быть больше 0.' },
        ];
    }

    if (!values.account_from) {
        errors.account_from = [
            { message: t('account_from') + ' обязательно для заполнения.' },
        ];
    }

    if (!values.account_to) {
        errors.account_to = [
            { message: t('account_to') + ' обязательно для заполнения.' },
        ];
    }

    if (!values.date) {
        errors.date = [{ message: t('date') + ' обязательно для заполнения.' }];
    }

    if (!values.user) {
        errors.user = [{ message: t('user') + ' обязательно для заполнения.' }];
    }

    return {
        values, // (Optional) Used to pass current form values to submit event.
        errors,
    };
};
const action = ref('save');
</script>

<template>
    <div class="py-2">
        <div class="max-w-8xl mx-auto space-y-2 sm:px-1 lg:px-2">
            <div class="bg-gray-800 py-6 sm:py-4">
                <div class="max-w-8xl lg:max-w-8xl mx-auto px-6 lg:px-4">
                    <h2
                        class="flex w-full items-center justify-center space-x-2 text-center text-base/7 font-semibold text-white"
                    >
                        <span>{{ title }}</span>
                    </h2>
                    <Form
                        v-slot="$form"
                        :initialValues="form"
                        :resolver
                        class="flex w-full flex-col gap-4"
                        @submit="createItem"
                    >
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-3">
                                <div class="mt-4 flex flex-col gap-x-6 gap-y-3">
                                    <div class="mt-4 columns-2">
                                        <div>
                                            <NumberField
                                                v-model="form.amount_from"
                                                :error-message="
                                                    $form.amount_from?.error
                                                        ?.message
                                                "
                                                :is-valid="
                                                    $form.amount_from?.invalid
                                                "
                                                field-key="amount_from"
                                            />

                                            <account-selector
                                                v-model="form.account_from"
                                                :accounts="
                                                    fields.account_from.values
                                                "
                                                :error-message="
                                                    $form.account_from?.error
                                                        ?.message
                                                "
                                                :is-valid="
                                                    $form.account_from?.invalid
                                                "
                                                field-key="account_from"
                                            />
                                        </div>
                                        <div>
                                            <NumberField
                                                v-model="form.amount_to"
                                                :error-message="
                                                    $form.amount_to?.error
                                                        ?.message
                                                "
                                                :is-valid="
                                                    $form.amount_to?.invalid
                                                "
                                                field-key="amount_to"
                                            />
                                            <account-selector
                                                v-model="form.account_to"
                                                :accounts="
                                                    fields.account_to.values
                                                "
                                                :error-message="
                                                    $form.amount_to?.error
                                                        ?.message
                                                "
                                                :is-valid="
                                                    $form.account_to?.invalid
                                                "
                                                field-key="account_to"
                                            />
                                        </div>
                                    </div>
                                    <div class="column-1 mt-4">
                                        <div>
                                            <FloatLabel variant="on">
                                                <date-picker
                                                    id="date"
                                                    v-model="form['date']"
                                                    :model-value="form['date']"
                                                    name="date"
                                                />
                                                <label for="date">{{
                                                    $t('date')
                                                }}</label>
                                            </FloatLabel>
                                            <Message
                                                v-if="$form.date?.invalid"
                                                severity="error"
                                                size="small"
                                                variant="simple"
                                                >{{ $form.date.error?.message }}
                                            </Message>
                                        </div>
                                    </div>
                                    <div class="column-1 mt-4">
                                        <div>
                                            <FloatLabel variant="on">
                                                <Select
                                                    v-model="form['user']"
                                                    :options="
                                                        fields.user.values
                                                    "
                                                    class="w-full"
                                                    name="user"
                                                    optionLabel="name"
                                                />
                                                <label for="user">{{
                                                    $t('user')
                                                }}</label>
                                            </FloatLabel>
                                            <Message
                                                v-if="$form.user?.invalid"
                                                severity="error"
                                                size="small"
                                                variant="simple"
                                                >{{ $form.date.user?.message }}
                                            </Message>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-end">
                            <ButtonGroup button.gap="3">
                                <Button
                                    :label="$t('cancel')"
                                    aria-keyshortcuts=""
                                    icon="pi pi-check"
                                    raised
                                    rounded
                                    severity="secondary"
                                    variant="outlined"
                                >
                                    <Link
                                        :href="route('exchanges.index')"
                                        class=""
                                        type="button"
                                    >
                                        {{ $t('cancel') }}
                                    </Link>
                                </Button>
                                <Button
                                    :disabled="form.processing"
                                    :label="$t('save')"
                                    icon="pi pi-trash"
                                    raised
                                    rounded
                                    variant="outlined"
                                    @click="action = 'save'"
                                >
                                    <button type="submit">
                                        {{ $t('save') }}
                                    </button>
                                </Button>
                                <Button
                                    :disabled="form.processing"
                                    :label="$t('save and add')"
                                    icon="pi pi-times"
                                    raised
                                    rounded
                                    variant="outlined"
                                    @click="action = 'saveAndAddNew'"
                                >
                                    <button type="submit">
                                        {{ $t('save and add') }}
                                    </button>
                                </Button>
                            </ButtonGroup>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>
