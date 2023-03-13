<script setup>
import {onMounted, ref, watch} from "vue";
import {useRoute} from 'vue-router';
import draggable from 'vuedraggable'

import { useContentTypeStore } from '../../stores/store-contenttypes'

import VhViewRow from '../../vaahvue/vue-three/primeflex/VhViewRow.vue';
const store = useContentTypeStore();
const route = useRoute();

onMounted(async () => {

    /**
     * If record id is not set in url then
     * redirect user to list view
     */
    if(route.params && !route.params.id)
    {
        store.toList();
        return false;
    }

    /**
     * Fetch the record from the database
     */
    // if(!store.item || Object.keys(store.item).length < 1){
        await store.getContentStrucutre(route.params.id);
    // }

});

//--------toggle item menu
const item_menu_state = ref();
const toggleItemMenu = (event) => {
    item_menu_state.value.toggle(event);
};
//--------/toggle item menu

</script>
<template>
    <div class="col-6" >
        <Card>
            <template #header>
                <div class="flex justify-content-between align-items-center w-full">
                    <h2 class="font-semibold text-lg">Content Structure</h2>
                    <div class="p-inputgroup w-max">
                        <Button label="Save"
                                icon="pi pi-save"
                                data-testid="contetntypes-store_group"
                                @click="store.storeGroups(route.params.id)"
                                class="p-button-sm"/>
                        <Button icon="pi pi-times"
                                @click="store.toList()"
                                data-testid="contetntypes-close_content_structure"
                                class="p-button-sm"/>
                    </div>
                </div>
            </template>
            <template #content>
                <Card v-if="store.item.groups && store.item.groups.length > 0"
                      v-for="(item,idx) in store.item.groups" class="mb-3">
                    <template #content>
                        <div class="w-full flex justify-content-between align-items-center mb-4">
                            <h4 class="font-semibold">{{item.name}}</h4>
                            <div class="w-max p-inputgroup align-items-center">
                                <InputSwitch v-model="item.is_repeatable"
                                             v-bind:false-value="0"
                                             v-bind:true-value="1"
                                             data-testid="contetntypes-is_repeatable"/>
                                <p class="ml-1 mr-3 text-xs font-semibold">Is Repeatable</p>
                                <Button icon="pi pi-hashtag"
                                        @click="store.getCopy(item.slug)"
                                        data-testid="contetntypes-copy_slug"
                                        class="p-button-sm"/>
                                <Button icon="pi pi-trash"
                                        class="p-button-sm"
                                        data-testid="contetntypes-remove_group"
                                        @click="store.removeGroup(item,idx)"/>
                            </div>
                            <draggable
                                v-model="item.fields"
                                class="dragArea list-group"
                                group="content-types"
                                @start="drag=true"
                                @end="drag=false"
                                item-key="id">
                                <template #item="{element,index}">
                                    <div>
                                        <div class="p-inputgroup mb-3">
                                            <InputText class="w-2" :model-value="element.type.name" disabled/>
                                            <InputText class="w-6"
                                                       v-model="element.name"
                                                       data-testid="contenttype-group_field_name"
                                                       placeholder="Field Name"/>
                                            <Button icon="pi pi-hashtag p-button-sm"
                                                    @click="store.getCopy(element.slug)"
                                                    data-testid="contenttype-group_field_slug"/>
                                            <Button icon="pi pi-cog p-button-sm"
                                                    data-testid="contenttype-group_field_cog"
                                                    @click="element.content_settings_status = !element.content_settings_status"></Button>
                                            <Button icon="pi pi-trash p-button-sm"
                                                    data-testid="contenttype-group_field_remove"
                                                    @click="store.removeField(index,idx)"/>
                                        </div>
                                        <div v-if="element.content_settings_status">
                                            <DataTable :value="element.content_settings" stripedRows class="p-datatable-sm">
                                                <Column>
                                                    <template #body="slotProps">
                                                        {{slotProps.data.label}}
                                                    </template>
                                                </Column>
                                                <Column>
                                                    <template #body="slotProps">
                                                        <InputSwitch v-if="slotProps.data.is_repeatable === 'repeatable'"
                                                                     v-bind:false-value="0"
                                                                     v-bind:true-value="1"
                                                                     data-testid="contenttype-group_field_repeatable"
                                                                     v-model="slotProps.data.is_repeatable"/>
                                                        <InputSwitch v-if="slotProps.data.is_searchable === 'searchable'"
                                                                     v-bind:false-value="0"
                                                                     v-bind:true-value="1"
                                                                     data-testid="contenttype-group_field_searchable"
                                                                     v-model="slotProps.data.is_searchable"/>
                                                        <Textarea v-if="slotProps.data.excerpt === 'excerpt'"
                                                                  data-testid="contenttype-group_field_excerpt"
                                                                  v-model="slotProps.data.excerpt"
                                                                  class="w-full"/>
                                                        <InputText v-if="slotProps.data.container_opening_tag === 'opening-tag'"
                                                                   data-testid="contenttype-group_field_opening_tag"
                                                                   v-model="slotProps.data.container_opening_tag"
                                                                   class="w-full"/>
                                                        <InputText v-if="slotProps.data.container_closing_tag === 'closing-tag'"
                                                                   data-testid="contenttype-group_field_closing_tag"
                                                                   v-model="slotProps.data.container_closing_tag"
                                                                   class="w-full"/>
                                                        <div v-if="slotProps.data.display_column === 'hidden'">
                                                            <Checkbox id="hidden"
                                                                      data-testid="contenttype-group_field_hidden"
                                                                      v-model="slotProps.data.display_column"/>
                                                            <label for="hidden" class="font-semibold text-xs ml-1">Is Hidden</label>
                                                        </div>
                                                    </template>
                                                </Column>
                                            </DataTable>
                                        </div>
                                    </div>
                                </template>
                            </draggable>
                        </div>
                    </template>
                </Card>
            </template>
            <template #footer>
                <div class="p-inputgroup w-6 m-auto">
                    <InputText v-model="store.new_group.name"></InputText>
                    <Button label="Add Group"
                            class="p-button-sm"
                            data-testid="contetntypes-add_to_group"
                            @click="store.addNewGroup"/>
                </div>
            </template>
        </Card>
    </div>
    <div class="col-2" >
        <Card>
            <template #header>
                <h2 class="font-semibold text-lg">Content Fields</h2>
            </template>

            <template #content>
                <div v-if="store.assets && store.assets.field_types">
                    <draggable
                        v-model="store.assets.field_types"
                        class="dragArea list-group"
                        :group="{ name: 'content-types', pull: 'clone', put: false }"
                        @start="drag=true"
                        @end="drag=false"
                        item-key="id">
                        <template #item="{element,index}">
                            <div class="p-inputgroup mb-3">
                                <Button icon="pi pi-bars" class="p-button-sm p-button-secondary"/>

                                <Button :label="element.name"
                                        data-testid="contenttypes-statuses_name_edit"
                                        class="p-button-sm p-button-secondary"/>
                            </div>
                        </template>
                    </draggable>
                </div>
            </template>
        </Card>
    </div>

</template>
