<script  setup>
import {reactive, ref, watch } from 'vue';
import {vaah} from '../../../vaahvue/pinia/vaah'
import { useContentStore } from '../../../stores/store-contents'
import ContentFieldAll from '../../../vaahvue/vue-three/reusable/content-field/All.vue'


const store = useContentStore();


const props = defineProps({
    groups: {
        type: Array,
        required: true
    },

});
</script>

<template>
    <div v-if="groups.length > 0"
         v-for="(arr_groups,g_index) in groups">
        <div  v-for="(group,index) in arr_groups">
            <div class="flex justify-content-between align-items-center w-full">
                <h2 class="font-semibold text-lg">{{group.name}}</h2>
                <div class="p-inputgroup w-max">
                    <Button
                            @click="store.copyGroupCode(group,index)"
                            icon="pi pi-copy"
                            data-testid="content-copy_group_code"
                            class="p-button-sm "/>
                </div>
            </div>
            <div v-if="group.fields.length>0"
                 v-for="(field, f_index) in group.fields"
                 class="flex justify-content-between align-items-center w-full">
                <div class="col-12">
                    <ContentFieldAll :field_type="field.type"
                                     :field_slug="field.type.slug"
                                     :label="field.name"
                                     :meta="field.meta"
                                     :placeholder="field.name"
                                     :app_url="field.type.slug === 'relation'
                                                                         ? store.ajax_url+'/getRelationsInTree' : ''"
                                     v-model="field.content"
                                     @onInput=""
                                     @onChange=""
                                     @onBlur=""
                                     @onFocus="">
                    </ContentFieldAll>
                </div>
            </div>
        </div>
    </div>
    <div v-else >
        <div>
            <div class="flex justify-content-between align-items-center w-full">
                <h2 class="font-semibold text-lg">Test</h2>
                <div class="p-inputgroup w-max">
                    <Button
                        @click="store.copyGroupCode(store.field.type)"
                        icon="pi pi-copy"
                        data-testid="content-copy_group_code"
                        class="p-button-sm "/>
                </div>
            </div>
            <div class="flex justify-content-between align-items-center w-full">
                <div class="col-12">
<!--                    <ContentFieldAll :field_type="store.field.type.name"
                                     :field_slug="store.field.type.slug"
                                     :label="store.field.name"
                                     :meta="store.field.meta"
                                     :placeholder="store.field.name"
                                     :app_url="store.field.type.slug === 'relation'
                                               ? ajax_url+'/getRelationsInTree' : ''"
                                     :value="store.field.content"
                                     custom_class="w-full"
                                     :is_simple="store.field.is_simple"
                                     @onInput=""
                                     @onChange=""
                                     @onBlur=""
                                     @onFocus="">
                    </ContentFieldAll>-->
                </div>
            </div>
        </div>
    </div>
</template>
