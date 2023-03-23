<script setup>
import {reactive, ref, watch } from 'vue';
import {vaah} from '../../../pinia/vaah'
import { v4 as uuidv4 } from 'uuid';

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

function generateUuid (){
    this.content = uuidv4();
    this.emitOnInput(this.content);
}

function emitOnInput  (data) {
    this.$emit('input', data);
}
</script>
<template>
        <InputText
                 v-model="props.content"
                 :class="custom_class"
                 :placeholder="placeholder"
                 @input="emitOnInput"/>
        <p class="control">
            <Button icon="pi pi-sync"
                    iconPos="left"
                    @click="this.generateUuid()"/>
        </p>
</template>

