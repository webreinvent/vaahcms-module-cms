<script setup>
import {onMounted, ref, watch} from "vue";
import { useBlockStore } from '../../stores/store-blocks'

import VhField from './../../vaahvue/vue-three/primeflex/VhField.vue'
import {useRoute} from 'vue-router';

import 'jodit/build/jodit.min.css'
import { JoditEditor } from 'jodit-vue'



const store = useBlockStore();
const route = useRoute();

onMounted(async () => {
    if(route.params && route.params.id)
    {
        await store.getItem(route.params.id);
    }

    await store.watchItem();
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

        <Panel class="is-small">

            <template #header>


                <div class="flex flex-row py-1">
                    <div class="p-panel-title">
                        <span>
                            Content
                        </span>
                    </div>

                </div>


            </template>

            <div v-if="store.item" class="py-1">
                <jodit-editor name="blocks-content"
                              data-testid="blocks-content"
                              v-model="store.item.content" :buttons="buttons" />

            </div>
        </Panel>

    </div>

    <div class="col-3" >

        <Panel class="is-small">

            <template class="p-1" #header>


                <div class="flex flex-row">
                    <div class="p-panel-title">
                        <span v-if="store.item && store.item.id">
                            Update
                        </span>
                        <span v-else>
                            Create
                        </span>
                    </div>

                </div>


            </template>

            <template #icons>


                <div class="p-inputgroup">
                    <Button label="Save"
                            class="p-button-sm"
                            v-if="store.item && store.item.id"
                            data-testid="blocks-save"
                            @click="store.itemAction('save')"
                            icon="pi pi-save"/>

                    <Button label="Create & New"
                            class="p-button-sm"
                            v-else
                            @click="store.itemAction('create-and-new')"
                            data-testid="blocks-create-and-new"
                            icon="pi pi-save"/>


                    <!--form_menu-->
                    <Button
                        type="button"
                        class="p-button-sm"
                        @click="toggleFormMenu"
                        data-testid="blocks-form-menu"
                        icon="pi pi-angle-down"
                        aria-haspopup="true"/>

                    <Menu ref="form_menu"
                          :model="store.form_menu_list"
                          :popup="true" />
                    <!--/form_menu-->


                    <Button class="p-button-primary p-button-sm"
                            icon="pi pi-times"
                            data-testid="blocks-to-list"
                            @click="store.toList()">
                    </Button>
                </div>



            </template>


            <div v-if="store.item" class="py-2">

                <VhField label="Name" class="mb-2" labelClass="text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="blocks-name"
                               data-testid="blocks-name"
                               v-model="store.item.name"/>
                </VhField>

                <VhField label="Slug" class="mb-2" labelClass="text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="blocks-slug"
                               data-testid="blocks-slug"
                               v-model="store.item.slug"/>
                </VhField>

                <VhField label="Theme" class="mb-2" labelClass="text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <Dropdown v-model="store.item.vh_theme_id"
                              name="blocks-select_theme"
                              @change="store.setActiveTheme"
                              data-testid="blocks-select_theme"
                              :options="store.assets.themes"
                              optionLabel="name"
                              optionValue="id"
                              placeholder="Select a Theme"
                              class="w-full is-small"  />

                </VhField>

                <VhField label="Location" class="mb-2" labelClass="text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <div class="p-inputgroup">
                        <Dropdown v-model="store.item.vh_theme_location_id"
                              name="blocks-select_location"
                              data-testid="blocks-select_location"
                              :options="store.active_theme.locations" optionLabel="name"
                              optionValue="id" placeholder="Select a Location"
                                  class="is-small"
                        />
                        <Button :disabled="!store.item.vh_theme_location_id
                                || !store.active_theme.locations"
                                @click="store.copyLocationCode"
                                icon="pi pi-copy"
                                class="p-button-sm"/>
                    </div>

                </VhField>



                <VhField label="Sort" class="mb-2" labelClass="text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputNumber name="blocks-sort"
                                 data-testid="blocks-sort"
                                 v-model="store.item.sort"
                                 class="w-full p-inputtext-sm" />
                </VhField>

                <VhField label="Is Published" class="mb-0" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputSwitch v-bind:false-value="0"
                                 v-bind:true-value="1"
                                 class="is-small"
                                 name="blocks-publish"
                                 data-testid="blocks-publish"
                                 v-model="store.item.is_published"/>
                </VhField>

            </div>
        </Panel>

    </div>

</template>
