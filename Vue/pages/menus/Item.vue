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

        <Panel class="draggable-menu">

            <template class="p-1" #header>


                <div class="flex flex-row">
                    <div class="p-panel-title">
                        <span v-if="store.item && store.item.id">
                            {{ store.item.name }}
                        </span>
                    </div>

                </div>


            </template>

            <template #icons>


                <div class="p-inputgroup">
                    <Button label="Save"
                            v-if="store.item && store.item.id"
                            data-testid="menus-save"
                            @click="store.storeItem"
                            icon="pi pi-save"/>
                    <!--/form_menu-->
                    <Button class="p-button-primary"
                            icon="pi pi-cog"
                            v-tooltip.top="'Settings'"
                            data-testid="menus-item_delete"
                            @click="store.menu_settings = !store.menu_settings"/>

                    <Button class="p-button-primary"
                            icon="pi pi-trash"
                            v-tooltip.top="'Delete'"
                            data-testid="menus-item_delete"
                            @click="store.deleteItem()"/>
                </div>
            </template>
            <div v-if="store.menu_settings">
                <div class="col-12 mb-2" v-if="store.item && store.item.id">
                    <InputText class="w-full mb-1"
                               v-model="store.item.name"
                               Placeholder="Name"
                               data-testid="menus-item_name"/>
                    <InputText class="w-full mb-1"
                               v-model="store.item.attr_id"
                               Placeholder="Menu Id"
                               data-testid="menus-item_id"/>
                    <InputText class="w-full mb-1"
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
        <Card>
            <template #header>
                <h2 class="font-semibold text-lg">Content</h2>
            </template>
            <template #content>
                <Panel header="Contents" :toggleable="true" class="mb-4">

                    <InputText class="w-full mb-3"
                               v-model="store.content_search"
                               data-testid="menus-content_search"
                               @input="store.searchContent()"
                               placeholder="Search content"/>

                    <draggable :list="store.filtered_content_list"
                               class="dragArea"
                               :clone="store.cloneField"
                               item-key="name"
                               :group="{ name: 'menu_items', pull: 'clone', put: false }"  >
                    <template #item="{element}">
                            <div class="p-inputgroup mb-3">
                                <Button icon="pi pi-bars"
                                        class="p-button-secondary p-button-sm"/>
                                <Button :label="element.name"
                                        class="p-button-secondary p-button-sm"/>
                            </div>
                        </template>
                    </draggable>
                </Panel>
                <Panel v-if="store.menu_types" header="Custom" :toggleable="true">

                    <draggable :list="store.menu_types"
                               class="dragArea"
                               :clone="store.customCloneField"
                               item-key="name"
                               :group="{ name: 'menu_items', pull: 'clone', put: false }"  >
                        <template #item="{element}">
                            <div class="p-inputgroup mb-3">
                                <Button icon="pi pi-bars"
                                        class="p-button-secondary p-button-sm"/>
                                <Button :label="element.name"
                                        class="p-button-secondary p-button-sm"/>
                            </div>
                        </template>
                    </draggable>
                </Panel>
            </template>
        </Card>
    </div>

</template>
