<script setup>
import Button from 'primevue/button';
import Message from 'primevue/message';
import Popover from 'primevue/popover';
import FloatLabel from 'primevue/floatlabel';
import Select from 'primevue/select';
import SelectButton from 'primevue/selectbutton';
import { computed, ref } from 'vue';

const props = defineProps({
    accounts: {
        type: Array,
        required: true,
    },
    fieldKey: {
        type: String,
        required: true,
    },
    isValid: {
        required: true,
        type: Boolean,
        default: true,
    },
    errorMessage: {
        required: false,
        type: String,
        default: '',
    },
});

const value = defineModel(
    {
        type: Object,
        required: true,
    },
    'value',
);

const currencies = computed(() => {
    return props.accounts.reduce((acc, account) => {
        if (!acc.includes(account.currency)) {
            acc.push(account.currency);
        }
        return acc;
    }, []);
});

const currency = ref(null);

const filteredAccounts = ref(
    computed(() => {
        // Show all options if no currency is selected
        if (!currency.value) {
            return props.accounts;
        }
        // Otherwise, filter accounts by the chosen currency
        return props.accounts.filter(
            (account) => account.currency === currency.value,
        );
    }),
);

const op = ref();
const toggle = (event) => {
    op.value.toggle(event);
};
</script>

<template>
    <div class="mt-3">
        <FloatLabel variant="on">
            <Select
                v-model="value"
                :name="fieldKey"
                :options="filteredAccounts"
                class="w-full"
                optionLabel="title"
                placeholder="Выберите счет"
            />
            <label :for="fieldKey">{{ $t(fieldKey) }}</label>
        </FloatLabel>
        <Message v-if="isValid" severity="error" size="small" variant="simple"
            >{{ errorMessage }}
        </Message>
        <Button label="фильтр" type="button" @click="toggle" />

        <Popover ref="op">
            <SelectButton
                v-model="currency"
                :options="currencies"
                class="mt-2"
                size="small"
            >
                <template #option="slotProps">
                    {{ slotProps.option }}
                </template>
            </SelectButton>
        </Popover>
    </div>
</template>
