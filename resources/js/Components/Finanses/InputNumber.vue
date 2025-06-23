<script setup>
import InputNumber from 'primevue/inputnumber';
import FloatLabel from 'primevue/floatlabel';
import Message from 'primevue/message';

defineProps({
    fieldKey: {
        required: true,
        type: String,
    },
    maxFractionDigits: {
        required: false,
        type: Number,
        default: 5,
    },
    minFractionDigits: {
        required: false,
        type: Number,
        default: 0,
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
        type: [Number, String],
        required: true,
    },
    'value',
);
</script>

<template>
    <div>
        <FloatLabel variant="on">
            <InputNumber
                v-model="value"
                :maxFractionDigits="maxFractionDigits"
                :minFractionDigits="minFractionDigits"
                :name="fieldKey"
                fluid
            />
            <label :for="fieldKey">{{ $t(fieldKey) }}</label>
        </FloatLabel>

        <Message v-if="isValid" severity="error" size="small" variant="simple"
            >{{ errorMessage }}
        </Message>
    </div>
</template>
