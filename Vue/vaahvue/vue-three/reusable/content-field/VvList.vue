<template>

    <VhField :label="label"
             :labelPosition="labelPosition">

        <VhField v-if="contents.length > 0" >
            <VhField v-for="(content_item, index) in contents" :key="index">
                <InputText @blur="emitOnInput"
                           v-model="contents[index]">
                </InputText>
                <p class="control">
                    <Button @click="removeNewContentItem(index)"
                            icon="pi pi-trash"
                            iconPos="left" />
                </p>
            </VhField>

        </VhField>

        <VhField >
            <InputText v-model="item"
                     :size="size"
                     :class="custom_class"
                     placeholder="Add New Item"
                     @keyup.enter.native="addNewContentItem"
            ></InputText>
            <p class="control">
                <Button @click="addNewContentItem()"
                        icon="pi pi-plus"
                        iconPos="left"/>
            </p>

        </VhField>

    </VhField>


</template>

<script>

export default {
    props:{
        content: {
            type: Array,
            default: function () {
                return []
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

            item:null,
            contents:[],

        };

        return obj;
    },

    created() {

    },
    watch: {
        content(newVal, oldVal) {
            if(newVal){
                this.contents = newVal;
            }else{
                this.contents=[];
            }
        },
    },
    mounted() {
        //----------------------------------------------------
        if(this.content)
        {
            this.contents = this.content;
        }
        //----------------------------------------------------
    },
    methods: {
        //----------------------------------------------------
        addNewContentItem: function()
        {
            this.contents.push(this.item);
            this.item = null;
            this.emitOnInput();
        },
        //----------------------------------------------------
        removeNewContentItem: function(content_item_index)
        {
            this.contents.splice(content_item_index, 1);
            this.emitOnInput();
        },
        //----------------------------------------------------
        emitOnInput: function () {
            this.$emit('input', this.contents);
        },
        //----------------------------------------------------
    },
}
</script>

