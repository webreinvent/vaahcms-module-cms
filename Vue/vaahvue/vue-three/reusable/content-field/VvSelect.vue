<template>

    <VhField :label="label">
        <Dropdown  :placeholder="placeholder"
                  v-model="value"
                  :class="custom_class"
                  :size="size"
                  @change="emitOnInput"
                  :multiple="is_multiple"
                   :options="options"
                   :optionLabel="optionLabel"
                   :optionValue="optionValue"
                  expanded/>
    </VhField>


</template>

<script>

export default {
    props:{
        content: {
            type: Array|Object|String,
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
        optionLabel: {
            type: String,
            default: null,
        },
        optionValue: {
            type: String,
            default: null,
        },

        is_multiple: {
            type: Boolean,
            default: false,
        },
    },
    data()
    {
        let obj = {
            value:this.is_multiple?[]:null
        };

        return obj;
    },

    created() {

    },
    watch: {
        content(newVal, oldVal) {
            if(newVal){
                this.setValue(newVal);
            }else{
                this.value=this.is_multiple?[]:null;
            }
        },
    },
    mounted() {

        if(this.content){
            //----------------------------------------------------
            this.setValue(this.content);
            //----------------------------------------------------

        }


        //----------------------------------------------------
    },
    methods: {
        //----------------------------------------------------
        emitOnInput: function (data) {
            this.$emit('input', data);
        },
        //----------------------------------------------------
        setValue: function (val) {
            this.value = val;
            //----------------------------------------------------
            if(this.is_multiple && typeof val === 'string'){

                this.value = [val];

            }
        },
        //----------------------------------------------------
    },
}
</script>

