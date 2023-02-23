<script setup>
import {onMounted, ref, watch} from "vue";
import { useMenuStore } from '../../stores/store-menus'
import draggable from 'vuedraggable'

import VhField from './../../vaahvue/vue-three/primeflex/VhField.vue'
import NestedDraggable from './components/NestedDraggable.vue'
import {useRoute} from 'vue-router';


const store = useMenuStore();
const route = useRoute();

onMounted(async () => {

    if(route.params && route.params.id)
    {
        await store.getItem(route.params.id);
    }

});

//--------form_menu
const form_menu = ref();
const toggleFormMenu = (event) => {
    form_menu.value.toggle(event);
};
//--------/form_menu

</script>
<template>

    <div class="col-6" >

        <Panel >

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
                            @click="store.itemAction('save')"
                            icon="pi pi-save">
                    </Button>

                    <Button label="Create & New"
                            v-else
                            @click="store.itemAction('create-and-new')"
                            data-testid="menus-create-and-new"
                            icon="pi pi-save">
                    </Button>


                    <!--form_menu-->
                    <Button
                        type="button"
                        @click="toggleFormMenu"
                        data-testid="menus-form-menu"
                        icon="pi pi-angle-down"
                        aria-haspopup="true">
                    </Button>

                    <Menu ref="form_menu"
                          :model="store.form_menu_list"
                          :popup="true" >
                    </Menu>
                    <!--/form_menu-->


                    <Button class="p-button-primary"
                            icon="pi pi-times"
                            data-testid="menus-to-list"
                            @click="store.toList()">
                    </Button>
                </div>



            </template>


            <div v-if="store.active_menu_items" class="draggable-menu">
                <NestedDraggable :tasks="store.active_menu_items" />
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

                    <AutoComplete input-class="w-full" class="w-full mb-3" placeholder="Search"></AutoComplete>
                    <draggable
                            v-model="store.content_list"
                            class="dragArea"
                            :group="{ name: 'g1', pull: 'clone', put: false }"
                            @start="drag=true"
                            @end="drag=false"
                            item-key="id">
                        <template #item="{element}">
                            <div class="p-inputgroup mb-3">
                                <Button icon="pi pi-bars" class="p-button-secondary p-button-sm"></Button>
                                <Button :label="element.name" class="p-button-secondary p-button-sm"></Button>
                            </div>
                        </template>
                    </draggable>
                </Panel>
                <Panel header="Custom" :toggleable="true">
                    <div class="p-inputgroup mb-3">
                        <Button icon="pi pi-bars" class="p-button-secondary p-button-sm"></Button>
                        <Button label="Internal Link" class="p-button-secondary p-button-sm"></Button>
                    </div>
                    <div class="p-inputgroup mb-3">
                        <Button icon="pi pi-bars" class="p-button-secondary p-button-sm"></Button>
                        <Button label="External Link" class="p-button-secondary p-button-sm"></Button>
                    </div>
                </Panel>
            </template>
        </Card>
    </div>

</template>
