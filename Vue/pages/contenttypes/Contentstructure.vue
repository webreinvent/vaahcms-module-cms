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
                <Card v-if="store.item && store.item.groups && store.item.groups.length > 0"
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
                                        <div class="p-datatable p-component p-datatable-responsive-stack
                                            p-datatable-striped p-datatable-sm"
                                             data-scrollselectors=".p-datatable-wrapper" pv_id_6="">
                                            <!----><!----><!----><!----><!---->
                                            <div class="p-datatable-wrapper">
                                                <table role="table" class="p-datatable-table">
                                                    <thead class="p-datatable-thead" role="rowgroup">
                                                    <tr role="row">
                                                        <th class="" role="cell"><!---->
                                                            <div class="p-column-header-content"><!----><!---->
                                                                <!----><!----><!----><!----></div>
                                                        </th>
                                                        <th class="" role="cell"><!---->
                                                            <div class="p-column-header-content"><!----><!---->
                                                                <!----><!----><!----><!----></div>
                                                        </th>
                                                    </tr><!----></thead><!---->
                                                    <tbody class="p-datatable-tbody" role="rowgroup"><!---->
                                                    <tr class="" role="row" draggable="false">
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span>Is repeatable
                                                        </td>
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span>
                                                            <div class="p-inputswitch p-component">
                                                                <div class="p-hidden-accessible"><input
                                                                    type="checkbox" role="switch" class=""
                                                                    aria-checked="false"></div>
                                                                <span class="p-inputswitch-slider"></span></div>
                                                            <!--v-if--><!--v-if--><!--v-if--><!--v-if--><!--v-if-->
                                                        </td>
                                                    </tr><!----><!----><!---->
                                                    <tr class="" role="row">
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span>Is Searchable
                                                        </td>
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span><!--v-if-->
                                                            <div class="p-inputswitch p-component">
                                                                <div class="p-hidden-accessible"><input
                                                                    type="checkbox" role="switch" class=""
                                                                    aria-checked="false"></div>
                                                                <span class="p-inputswitch-slider"></span></div>
                                                            <!--v-if--><!--v-if--><!--v-if--><!--v-if--></td>
                                                    </tr><!----><!----><!---->
                                                    <tr class="" role="row">
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span>Excerpt
                                                        </td>
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span><!--v-if-->
                                                            <!--v-if--><textarea
                                                                class="p-inputtextarea p-inputtext p-component w-full"></textarea>
                                                            <!--v-if--><!--v-if--><!--v-if--></td>
                                                    </tr><!----><!----><!---->
                                                    <tr class="" role="row">
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span>Opening Tag
                                                        </td>
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span><!--v-if--><!--v-if-->
                                                            <!--v-if--><input
                                                                class="p-inputtext p-component w-full"><!--v-if-->
                                                            <!--v-if--></td>
                                                    </tr><!----><!----><!---->
                                                    <tr class="" role="row">
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span>Closing Tag
                                                        </td>
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span><!--v-if--><!--v-if-->
                                                            <!--v-if--><!--v-if--><input
                                                                class="p-inputtext p-component w-full"><!--v-if-->
                                                        </td>
                                                    </tr><!----><!----><!---->
                                                    <tr class="" role="row">
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span>Is Hidden
                                                        </td>
                                                        <td class="" role="cell"><span
                                                            class="p-column-title"></span><!--v-if--><!--v-if-->
                                                            <!--v-if--><!--v-if--><!--v-if-->
                                                            <div>
                                                                <div class="p-checkbox p-component" id="hidden">
                                                                    <div class="p-hidden-accessible"><input
                                                                        type="checkbox" class=""></div>
                                                                    <div class="p-checkbox-box"><span
                                                                        class="p-checkbox-icon"></span></div>
                                                                </div>
                                                                <label for="hidden"
                                                                       class="font-semibold text-xs ml-1">Is
                                                                    Hidden</label></div>
                                                        </td>
                                                    </tr><!----><!----></tbody><!----></table>
                                            </div><!----><!---->
                                            <div class="p-column-resizer-helper" style="display: none;"></div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </draggable>
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
                        :clone="store.cloneField"
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
