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
let email_validation_message = ref('');
function emitOnInput (data) {
    this.$emit('input', data);
}
function validateEmail(data) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(data)) {
        email_validation_message = 'Please enter a valid email address';
    } else {
        email_validation_message = null;
    }
}
</script>
<template>
    <div class="p-inputgroup">
        <InputText
            v-model="props.content"
            :class="custom_class"
            :placeholder="placeholder"
            @input="validateEmail(props.content)"/>
        <span v-if="email_validation_message"
              class="text-xs text-red-500">
            {{ email_validation_message}}
        </span>
    </div>

    <div>

    </div>

</template>

