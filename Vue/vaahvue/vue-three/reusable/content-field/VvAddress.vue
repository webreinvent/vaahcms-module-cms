<script setup>
import {reactive, ref, watch } from 'vue';
import {vaah} from '../../../pinia/vaah'

const props = defineProps({
        content: {
            type: Object,
            default: function () {
                return {}
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

// data() {
//     return {
//         list:{
//             "address_line_1": {
//                 name: "Address Line 1",
//                 type: 'text',
//                 maxlength: 50,
//                 content: null
//             },
//             "address_line_2":{
//                 name: "Address Line 2",
//                 type: 'text',
//                 maxlength: 50,
//                 content: null
//             },
//             "landmark":{
//                 name: "Landmark",
//                 type: 'text',
//                 maxlength: 50,
//                 content: null
//             },
//             "city":{
//                 name: "City",
//                 type: 'text',
//                 maxlength: 50,
//                 content: null
//             },
//             "state":{
//                 name: "State",
//                 type: 'text',
//                 maxlength: 50,
//                 content: null
//             },
//             "country":{
//                 name: "Country",
//                 type: 'text',
//                 maxlength: 20,
//                 content: null
//             },
//             "zip_code": {
//                 name: "Zip Code",
//                 type: 'text',
//                 maxlength: 20,
//                 content: null
//             }
//
//         }
//
//     }
// }

watch(content, (newVal, oldVal) => {
    if(!newVal){
        this.list = {
            "address_line_1": {
                name: "Address Line 1",
                type: 'text',
                maxlength: 50,
                content: null
            },
            "address_line_2":{
                name: "Address Line 2",
                type: 'text',
                maxlength: 50,
                content: null
            },
            "landmark":{
                name: "Landmark",
                type: 'text',
                maxlength: 50,
                content: null
            },
            "city":{
                name: "City",
                type: 'text',
                maxlength: 50,
                content: null
            },
            "state":{
                name: "State",
                type: 'text',
                maxlength: 50,
                content: null
            },
            "country":{
                name: "Country",
                type: 'text',
                maxlength: 20,
                content: null
            },
            "zip_code": {
                name: "Zip Code",
                type: 'text',
                maxlength: 20,
                content: null
            }

        };
    }else{
        this.list = newVal;
    }
});


onMounted(() => {
    if(this.content)
    {
        this.list = this.content;
    }
});
function emitOnInput () {
    this.$emit('input', this.list);
}
</script>
<template>
    <h5 class="title is-5">Address</h5>
    <div class="p-inputgroup mb-1" v-for="(item, index) in list">
            <InputText
                     v-model="list[index].content"
                     :class="custom_class"
                     :placeholder="list[index].name"
                     @input="emitOnInput"/>
    </div>

</template>

