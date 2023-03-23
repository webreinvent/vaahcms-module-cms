<script setup>
import {reactive, ref, watch } from 'vue';
import {vaah} from '../../../pinia/vaah'
import 'jodit/build/jodit.min.css'
import { JoditEditor } from 'jodit-vue'
let base_url = document.getElementsByTagName("base")[0].getAttribute('href');
// import $ from 'jquery'

let media_upload_url = base_url + "/backend/cms/media/upload";


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
        is_simple: {
            type: Boolean,
            default: false,
        },
    });

    // mounted() {
    //     //----------------------------------------------------
    //     this.editor = this.setupEditor();
    //     this.setEditorContent(this.content);
    //     //----------------------------------------------------
    // },
function setupEditor () {

}

function setEditorContent (content) {
    if (content) {
        this.editorData = content;
    }
}

function emitOnInput () {
    this.$emit('input', this.editorData);
}
</script>
<template>
        <jodit-editor v-if="is_simple"
                      data-testid="content-component_jod_editor"
                      v-model="props.content"
                      @input="emitOnInput"/>

        <jodit-editor v-else
                      data-testid="content-component_jod_editor"
                      v-model="editorData"
                      :config="editorConfig"
                      @input="emitOnInput"/>
</template>



