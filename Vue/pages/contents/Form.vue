<script setup>
import {onMounted, ref, watch} from "vue";
import { useContentStore } from '../../stores/store-contents'
import ContentFields from "./components/ContentFields.vue";
import VhField from './../../vaahvue/vue-three/primeflex/VhField.vue'
import {useRoute} from 'vue-router';


const store = useContentStore();
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
    <div class="col-6">
        <Card>
            <template #header>
                <div class="flex justify-content-between align-items-center w-full">
                    <h2 class="font-semibold text-lg">Content Structure</h2>
                    <div class="p-inputgroup w-max">
                        <Button label="Expand All"
                                @click="store.expandAll"
                                icon="pi pi-angle-double-down"
                                data-testid="content-expand_all"
                                class="p-button-sm mr-1"/>
                        <Button label="Collapse All"
                                @click="store.collapseAll"
                                icon="pi pi-angle-double-up"
                                data-testid="content-collapse_all"
                                class="p-button-sm"/>
                    </div>
                </div>
            </template>
            <template #content>
                <div class="col-12">
                            <div v-if="store.item">
                                <div class="p-inputgroup">
                                    <InputText class="w-full mb-2"
                                               name="contents-name"
                                               data-testid="contents-name"
                                               placeholder="Perma link"
                                               v-model="store.item.permalink"/>
                                </div>
                                <div class="p-inputgroup mb-2">
                                    <AutoComplete v-model="store.selected_user_id"
                                                  class="w-full"
                                                  :suggestions="store.user_list"
                                                  @change="store.setUserId()"
                                                  @complete="store.searchUser($event)"
                                                  optionLabel="name"
                                                  optionValue="email"
                                                  placeholder="Search..."
                                                  inputClass="p-inputtext-sm"/>
                                </div>
                                <Accordion :multiple="true" :activeIndex="store.active_index" id="accordionTabContainer">
                                    <AccordionTab>
                                        <template #header>
                                            <div class="w-full">
                                                <div>
                                                    <h5 class="font-semibold text-sm">Content Fields</h5>
                                                </div>
                                            </div>
                                        </template>
                                        <Message severity="info">
                                            These fields can be managed from "Content Types" sections.
                                        </Message>

                                        <div v-if="store.item.content_form_groups">
                                            <ContentFields :groups="store.item.content_form_groups"/>
                                        </div>


                                    </AccordionTab>
                                    <AccordionTab >
                                        <template #header>
                                            <div class="w-full">
                                                <div>
                                                    <h5 class="font-semibold text-sm">Template Fields</h5>
                                                </div>
                                            </div>
                                        </template>
                                    </AccordionTab>
                                </Accordion>
                            </div>
                        </div>
            </template>
        </Card>
    </div>
    <div class="col-3" >
        <Panel >
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
                            data-testid="contents-save"
                            @click="store.itemAction('save')"
                            icon="pi pi-save"/>

                    <Button label="Create & New"
                            class="p-button-sm"
                            v-else
                            @click="store.itemAction('create-and-new')"
                            data-testid="contents-create-and-new"
                            icon="pi pi-save"/>


                    <!--form_menu-->
                    <Button
                        type="button"
                        @click="toggleFormMenu"
                        data-testid="contents-form-menu"
                        icon="pi pi-angle-down"
                        aria-haspopup="true"/>

                    <Menu ref="form_menu"
                          :model="store.form_menu_list"
                          :popup="true" />
                    <!--/form_menu-->


                    <Button class="p-button-primary"
                            icon="pi pi-times"
                            data-testid="contents-to-list"
                            @click="store.toList()">
                    </Button>
                </div>
            </template>
            <div v-if="store.item">

                <VhField label="Name">
                    <InputText class="w-full"
                               name="contents-name"
                               data-testid="contents-name"
                               v-model="store.item.name"/>
                </VhField>
                <VhField label="Status">
                    <Dropdown v-model="store.item.status"
                              :options="store.assets.content_type.content_statuses"
                              optionLabel="name"
                              optionValue="name"
                              placeholder="Select status"
                              data-testid="contents-status"
                              class="w-full md:w-14rem" />
                </VhField>
                <VhField label="Theme">
                    <Dropdown v-model="store.item.vh_theme_id"
                              :options="store.assets.themes"
                              optionLabel="name"
                              optionValue="id"
                              placeholder="Select Theme"
                              data-testid="contents-theme"
                              @change="store.setActiveTheme"
                              class="w-full md:w-14rem" />
                </VhField>
                <VhField label="Template" v-if="store.active_theme">
                    <Dropdown v-model="store.item.vh_theme_template_id"
                              :options="store.active_theme.templates"
                              optionLabel="name"
                              optionValue="id"
                              placeholder="Select Template"
                              data-testid="contents-template"
                              class="w-full md:w-14rem" />
                </VhField>

            </div>
        </Panel>
    </div>

</template>
