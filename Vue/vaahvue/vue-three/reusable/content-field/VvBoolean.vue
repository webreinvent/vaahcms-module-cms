<template>

    <VhField :label="label"
             :labelPosition="labelPosition">
        <InputSwitch  v-model="switch_value"
                      v-bind:false-value="0"
                      v-bind:true-value="1"
                  @input="emitOnInput">
    </VhField>


</template>

<script>

export default {
    props:{
        content: {
            type: Number|String,
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
    },
    data()
    {
        let obj = {

            switch_value: null,

        };

        return obj;
    },

    created() {

    },
    watch: {
        content: function (newVal, oldVal) {
            this.switch_value = newVal;
        }
    },
    mounted() {
        //----------------------------------------------------
        this.switch_value = this.content;
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

