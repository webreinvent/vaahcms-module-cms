<script setup>
import {reactive, ref, watch } from 'vue';
import {vaah} from '../../../pinia/vaah'

const props = defineProps({
        content: {
            type: String,
            default: function () {
                return null
            }
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

function emitOnInput (data) {
    this.$emit('input', data);
}
</script>
<template>
    <InputText
             v-model="props.content"
             :class="custom_class"
             :placeholder="placeholder"
             @input="emitOnInput"/>
</template>

