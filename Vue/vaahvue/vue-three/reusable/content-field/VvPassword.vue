<template>

    <Vhfield :label="label"
             :labelPosition="labelPosition">
        <Password
                 :value="content"
                 :size="size"
                 :class="custom_class"
                 toggleMask
                 :placeholder="placeholder"
                 autocomplete="new-password"
                 @input="emitOnInput"
        />
    </Vhfield>


</template>

<script>

export default {
    props:{
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

