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
         :aria-id="'content-fields-group-'+g_index"
         :class="g_index === 0 ? 'pt-4 pb-3' : g_index !== groups.length - 1 ? 'py-3' : 'pt-3'"
    >
        <div  v-for="(group,index) in arr_groups"
              class="border-1 border-gray-200 border-round-md p-3 relative"
              :class="index === 0 && arr_groups.length > 1 ? 'mb-5' : index !== arr_groups.length - 1 ? 'my-5' : ''"
        >
            <div class="absolute left-0 top-0 p-2 flex justify-content-between align-items-center w-full mb-2"
                 style="transform: translateY(-50%)"
            >
                <h2 class="font-semibold text-lg bg-white px-2">{{group.name}}</h2>
                <div v-if="index === 0" class="p-inputgroup w-max bg-white px-2">
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
                <div v-else class="p-inputgroup w-max">
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

            <div class="card">
                <div v-if="group.fields.length > 0"
                     v-for="(field, f_index) in group.fields"
                     :key="f_index">
                    <div class="flex flex-column gap-1">
                        <label class="font-medium line-height-1 text-xs">{{ field.name }}</label>
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
                                        v-if="field.content.length > 1"
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
                                @click="store.addField(field)" class="p-button-sm">
                            Add Field
                        </Button>
                    </div>
                </div>
            </div>

            <div class="flex justify-content-center">
                <Button v-if="group.is_repeatable
                        && arr_groups.length - 1 === index"
                        @click="store.addGroup(arr_groups,group)" class="p-button-sm">
                    Add Group
                </Button>
            </div>
        </div>
    </div>
</template>
