<script setup>
import {onMounted, reactive, ref} from "vue";
import {useRoute} from 'vue-router';

import {useMenuStore} from '../../stores/store-menus'
import VhField from './../../vaahvue/vue-three/primeflex/VhField.vue'

const store = useMenuStore();
const route = useRoute();

import { useConfirm } from "primevue/useconfirm";
const confirm = useConfirm();


onMounted(async () => {
    /**
     * call onLoad action when List view loads
     */
    await store.onLoad(route);

    /**
     * watch routes to update view, column width
     * and get new item when routes get changed
     */
    await store.watchRoutes(route);

    /**
     * watch states like `query.filter` to
     * call specific actions if a state gets
     * changed
     */
    await store.watchStates();

    /**
     * fetch assets required for the crud
     * operation
     */
    await store.getAssets();

    /**
     * fetch list of records
     */
    await store.getList();
});

</script>
<template>

    <div class="grid" v-if="store.assets">

        <div class="col-3">
            <Panel>

                <template class="p-1" #header>

                    <div class="flex flex-row">
                        <div >
                            <b class="mr-1">Choose Menu</b>
                        </div>

                    </div>

                </template>

                <div>

                    <h5>Theme</h5>
                    <Dropdown v-model="store.query.vh_theme_id"
                              :options="store.assets.themes"
                              optionLabel="name" optionValue="id"
                              class="is-full-width"
                              @change="store.setActiveTheme"
                              placeholder="Select a Theme" >
                    </Dropdown>
                </div>

                <div v-if="store.active_theme">
                    <h5>Location</h5>

                    <div class="p-inputgroup">
                        <Dropdown v-model="store.query.vh_theme_location_id"
                                  :options="store.active_theme.locations"
                                  optionLabel="name" optionValue="id"
                                  @change="store.setActiveLocation"
                                  placeholder="Select a Location" >
                        </Dropdown>
                        <Button :disabled="!store.query.vh_theme_location_id
                                || !store.active_theme.locations"
                                @click="store.copyLocationCode"
                                icon="pi pi-copy">
                        </Button>
                    </div>


                </div>

                <div v-if="store.query.vh_theme_id && store.active_location
                && store.active_location.menus.length > 0">

                    <h5>Menu</h5>
                    <Dropdown v-model="store.query.vh_menu_id"
                              :options="store.active_location.menus"
                              optionLabel="name" optionValue="id"
                              class="is-full-width"
                              @change="store.setActiveMenu"
                              placeholder="Select a Theme" >
                    </Dropdown>
                </div>


                <div v-if="store.active_theme && store.active_location">
                    <h5>Create New Menu</h5>

                    <div class="p-inputgroup">
                        <InputText type="text" v-model="store.item.name" >
                        </InputText>
                        <Button @click="store.create"
                                label="Create">
                        </Button>
                    </div>


                </div>


            </Panel>
        </div>

        <RouterView/>

    </div>


</template>
