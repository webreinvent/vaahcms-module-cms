<script setup>
import {reactive, ref, watch } from 'vue';
import {vaah} from '../../../pinia/vaah'
import VhField from './../../primeflex/VhField.vue'

const props = defineProps({
        content: {
            type: String|Number,
            default: null
        },
        type: {
            type: String,
            default: null,
        },
        size: {
            type: String,
            default: null,
        },
        custom_class: {
            type: String,
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
        labelPosition: {
            type: String,
            default: null,
        },
        placeholder: {
            type: String,
            default: null,
        },
    });

// function emitOnInput (data) {
//     this.$emit('input', data);
// }
</script>
<template>

    <VhField :label="label">
        <InputText
                 v-model="props.content"
                 :class="custom_class"
                 :placeholder="placeholder"
        />
    </VhField>


</template>

