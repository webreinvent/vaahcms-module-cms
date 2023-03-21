<script>
import VhField from './../../primeflex/VhField.vue'
export default {
    props:{
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
    },
    data()
    {
        let obj = {

        };

        return obj;
    },

    created() {

    },
    watch: {
    },
    mounted() {
        //----------------------------------------------------
        //----------------------------------------------------
    },
    methods: {
        //----------------------------------------------------
        emitOnInput: function (data) {
            this.$emit('input', data);
        },
        //----------------------------------------------------
    },
}
</script>
<template>

    <VhField :label="props.label">
        <InputText
                 v-model="props.content"
                 :class="props.custom_class"
                 :placeholder="props.placeholder"
        />
    </VhField>


</template>

