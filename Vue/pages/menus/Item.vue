<script setup>
import {onMounted, ref, watch} from "vue";
import {useRoute} from 'vue-router';

import { useMenuStore } from '../../stores/store-menus'

import VhViewRow from '../../vaahvue/vue-three/primeflex/VhViewRow.vue';
import draggable from 'vuedraggable'
import NestedDraggable from './components/NestedDraggable.vue'

const store = useMenuStore();
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
    if(!store.item || Object.keys(store.item).length < 1)
    {
        await store.getItem(route.params.id);
    }

    /**
     * Watch if url record id is changed, if changed
     * then fetch the new records from database
     */
    /*watch(route, async (newVal,oldVal) =>
        {
            if(newVal.params && !newVal.params.id
                && newVal.name === 'articles.view')
            {
                store.toList();

            }
            await store.getItem(route.params.id);
        }, { deep: true }
    )*/

});

//--------toggle item menu
const item_menu_state = ref();
const toggleItemMenu = (event) => {
    item_menu_state.value.toggle(event);
};
//--------/toggle item menu

</script>
<template>


    <div class="col-6 " >

        <Panel class="draggable-menu is-small">

            <template class="p-1" #header>


                <div class="flex flex-row">
                    <div class="p-panel-title">
                        <span v-if="store.item && store.item.id && store.title">
                            {{ store.title }}
                        </span>
                    </div>

                </div>


            </template>

            <template #icons>


                <div class="p-inputgroup">
                    <Button label="Save"
                            class="p-button-sm"
                            v-if="store.item && store.item.id"
                            data-testid="menus-save"
                            @click="store.storeItem"
                            icon="pi pi-save"/>
                    <!--/form_menu-->
                    <Button class="p-button-primary p-button-sm"
                            icon="pi pi-cog"
                            v-tooltip.top="'Settings'"
                            data-testid="menus-item_delete"
                            @click="store.menu_settings = !store.menu_settings"/>

                    <Button class="p-button-primary p-button-sm"
                            icon="pi pi-trash"
                            v-tooltip.top="'Delete'"
                            data-testid="menus-item_delete"
                            @click="store.deleteItem()"/>
                </div>
            </template>
            <div v-if="store.menu_settings">
                <div class="col-12 mb-2 px-0" v-if="store.item && store.item.id">
                    <InputText class="w-full mb-2 p-inputtext-sm"
                               v-model="store.item.name"
                               Placeholder="Name"
                               data-testid="menus-item_name"/>
                    <InputText class="w-full mb-2 p-inputtext-sm"
                               v-model="store.item.attr_id"
                               Placeholder="Menu Id"
                               data-testid="menus-item_id"/>
                    <InputText class="w-full p-inputtext-sm"
                               v-model="store.item.attr_class"
                               Placeholder="Menu Class"
                               data-testid="menus-item_class"/>
                </div>
                <div v-if="store.active_menu_items" >
                    <NestedDraggable :tasks="store.active_menu_items" />
                </div>
            </div>

        </Panel>

    </div>

    <div class="col-3">
        <Card class="is-small">
            <template #header>
                <b class="py-1">Content</b>
            </template>
            <template #content>
                <div class="py-1">
                    <Panel
                        header="Contents"
                        :toggleable="true"
                        class="mb-2 is-small"
                        :pt="{
                            title: {
                                class: 'font-semibold text-xs'
                            },
                            header: {
                                class: 'py-1'
                            },
                            toggler: {
                                style: 'height: 1.5rem; width: 1.5rem'
                            },
                            togglerIcon: {
                                style: 'height: 0.7rem; width: 0.7rem'
                            }
                        }"
                    >

                        <InputText class="w-full my-2 p-inputtext-sm"
                                   v-model="store.content_search"
                                   data-testid="menus-content_search"
                                   @input="store.searchContent()"
                                   placeholder="Search content"/>

                        <draggable :list="store.filtered_content_list"
                                   class="dragArea"
                                   :clone="store.cloneField"
                                   item-key="name"
                                   :group="{ name: 'menu_items', pull: 'clone', put: false }"  >
                            <template #item="{element, index}">
                                <div class="p-inputgroup"
                                     :class="index < store.filtered_content_list.length - 1 ? 'mb-2' : ''"
                                >
                                    <Button :label="element.name"
                                            icon="pi pi-bars mr-2"
                                            class="p-button-secondary p-button-sm"
                                            :pt="{
                                            label: 'font-normal'
                                        }"
                                    />
                                </div>
                            </template>
                        </draggable>
                    </Panel>
                    <Panel v-if="store.menu_types"
                           header="Custom"
                           :toggleable="true"
                           class="is-small"
                           :pt="{
                               title: {
                                   class: 'font-semibold text-xs'
                               },
                                header: {
                                    class: 'py-1'
                                },
                                toggler: {
                                    style: 'height: 1.5rem; width: 1.5rem'
                                },
                                togglerIcon: {
                                    style: 'height: 0.7rem; width: 0.7rem'
                                }
                           }"
                    >

                        <draggable :list="store.menu_types"
                                   class="dragArea"
                                   :clone="store.customCloneField"
                                   item-key="name"
                                   :group="{ name: 'menu_items', pull: 'clone', put: false }"  >
                            <template #item="{element, index}">
                                <div class="p-inputgroup"
                                     :class="index < store.menu_types.length - 1 ? 'mb-2' : ''"
                                >
                                    <Button :label="element.name"
                                            icon="pi pi-bars mr-2"
                                            class="p-button-secondary p-button-sm"
                                            :pt="{
                                            label: 'font-normal'
                                        }"
                                    />
                                </div>
                            </template>
                        </draggable>
                    </Panel>
                </div>
            </template>
        </Card>
    </div>

</template>
