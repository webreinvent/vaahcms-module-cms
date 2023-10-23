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
         v-for="(arr_groups,g_index) in groups"
         :key="'content-fields-group-'+g_index"
         :aria-id="'content-fields-group-'+g_index">
        <Divider v-if="g_index > 0 && g_index < groups.length" class="p-0 border-bottom-1 border-gray-200" />
        <div  v-for="(group,index) in arr_groups">
            <Divider v-if="arr_groups.length && index > 0 && index < arr_groups.length" class="p-0 border-bottom-1 border-gray-200" />
            <div class="flex justify-content-between align-items-center w-full mb-2">
                <h2 v-if="index === 0" class="font-semibold text-lg">{{group.name}}</h2>
                <div v-if="index === 0" class="p-inputgroup w-max">
                    <Button v-if="arr_groups.length > 1"
                            @click="store.copyGroupCode(group,index,'template')"
                            icon="pi pi-file"
                            data-testid="content-copy_group_code"
                            class="p-button-sm "/>
                    <Button @click="store.copyGroupCode(group,null,'template')"
                            icon="pi pi-copy"
                            data-testid="content-copy_group_code"
                            class="p-button-sm "/>
                </div>
            </div>

            <div class="card flex flex-column gap-2">
                <div v-if="index > 0"
                     class="flex justify-content-between align-items-center w-full mb-2">
                    <h2 class="font-semibold text-lg">{{group.name}}</h2>
                    <div class="p-inputgroup w-max">
                        <Button
                                @click="store.copyGroupCode(group,index,'template')"
                                icon="pi pi-file"
                                data-testid="content-copy_group_code"
                                class="p-button-sm "/>
                        <Button @click="store.removeGroup(arr_groups,group,index)"
                                icon="pi pi-times"
                                data-testid="content-copy_group_code"
                                class="p-button-sm "/>
                    </div>
                </div>

                <div v-if="group.fields.length > 0"
                     v-for="(field, f_index) in group.fields"
                     :key="f_index">
                    <div >
                        <div v-if="!field.content || typeof field.content === 'string'
                        || typeof field.content === 'number'
                        || store.assets.non_repeatable_fields.includes(field.type.slug)"
                             class="flex justify-content-between align-items-start gap-3 w-full">
                            <div class="flex-grow-1">
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
                            <div>
                                <Button @click="store.copyCode(group, field,index,null,'template')"
                                        icon="pi pi-copy"
                                        data-testid="content-copy_code"
                                        class="p-button-sm "/>
                            </div>
                        </div>
                        <div v-else v-for="(content,key) in field.content"
                             class="flex justify-content-between align-items-center gap-3 w-full">
                            <div class="flex-grow-1">
                                <ContentFieldAll :field_type="field.type"
                                                 :field_slug="field.type.slug"
                                                 :label="key === 0
                                             || field.type.slug === 'image'
                                             || field.type.slug === 'media'
                                             ? field.name : ''"
                                                 :meta="field.meta"
                                                 :placeholder="field.name"
                                                 :app_url="field.type.slug === 'relation'
                                        ? store.ajax_url+'/getRelationsInTree' : ''"
                                                 v-model="field.content[key]"
                                                 @onInput=""
                                                 @onChange=""
                                                 @onBlur=""
                                                 @onFocus="">
                                </ContentFieldAll>
                            </div>
                            <div v-if="key === 0">
                                <div class="p-inputgroup w-max">
                                    <Button
                                        @click="store.copyCode(group, field,index,key,'template')"
                                        icon="pi pi-file"
                                        data-testid="content-copy_group_code"
                                        class="p-button-sm "/>
                                    <Button @click="store.copyCode(group, field,index,null,'template')"
                                            icon="pi pi-copy"
                                            data-testid="content-copy_group_code"
                                            class="p-button-sm "/>
                                </div>
                            </div>
                            <div v-else>
                                <div class="p-inputgroup w-max">
                                    <Button
                                        @click="store.copyCode(group, field,index,key,'template')"
                                        icon="pi pi-file"
                                        data-testid="content-copy_group_code"
                                        class="p-button-sm "/>
                                    <Button @click="store.removeField(field,key)"
                                            icon="pi pi-minus"
                                            data-testid="content-copy_group_code"
                                            class="p-button-sm "/>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="w-full flex justify-content-center mt-2">
                        <Button v-if="field.is_repeatable && !store.assets.non_repeatable_fields.includes(field.type.slug)"
                                @click="store.addField(field)">
                            Add Field
                        </Button>
                    </div>
                </div>
            </div>

            <div class="flex justify-content-center">
                <Button v-if="group.is_repeatable
                        && arr_groups.length - 1 === index"
                        @click="store.addGroup(arr_groups,group)">
                    Add Group
                </Button>
            </div>
        </div>
    </div>
</template>
