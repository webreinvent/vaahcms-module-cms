<script setup>
import {onMounted, ref, watch} from "vue";
import { useContentStore } from '../../stores/store-contents'
import ContentFields from "./components/ContentFields.vue";
import TemplateFields from "./components/TemplateFields.vue";
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
        <Card class="is-small py-1">
            <template #header>
                <div class="flex justify-content-between align-items-center w-full">
                    <b>Content Structure</b>
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
                <div class="col-12 p-0">
                            <div v-if="store.item">
                                <div class="p-inputgroup">
                                    <InputText class="w-full p-inputtext-sm mb-2"
                                               name="contents-name"
                                               data-testid="contents-name"
                                               placeholder="Perma link"
                                               v-model="store.item.permalink"/>
                                    <Button v-if="store.item.id"
                                            data-testid="contents-to_external_link"
                                            @click="store.toExternalLink(store.item)"
                                            class="p-button-sm mb-2" icon="pi pi-external-link"/>
                                </div>
                                <div class="mb-2">
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
                                <Accordion :multiple="true"
                                           :activeIndex="store.active_index" id="accordionTabContainer"
                                           class="is-small"
                                >
                                    <AccordionTab :pt="{ root: 'mb-2' }">
                                        <template class="p-3" #header>
                                            <div class="w-full">
                                                <div>
                                                    <h5 class="font-semibold text-xs">Content Fields</h5>
                                                </div>
                                            </div>
                                        </template>
                                        <Message severity="info"
                                                 class="my-0"
                                                 :pt="{
                                                    wrapper: {
                                                        class: 'justify-content-between py-1'
                                                    },
                                                    text: {
                                                        class: 'flex-grow-1'
                                                    }
                                                 }"
                                        >
                                            <span class="font-normal">These fields can be managed from "Content Types" sections.</span>
                                        </Message>

                                        <div v-if="store.item.content_form_groups">
                                            <ContentFields :groups="store.item.content_form_groups"/>
                                        </div>


                                    </AccordionTab>
                                    <AccordionTab >
                                        <template #header>
                                            <div class="w-full">
                                                <div>
                                                    <h5 class="font-semibold text-xs">Template Fields</h5>
                                                </div>
                                            </div>
                                        </template>
                                        <Message severity="info"
                                                 class="my-0"
                                                 :pt="{
                                                    wrapper: {
                                                        class: 'justify-content-between py-1'
                                                    },
                                                    text: {
                                                        class: 'flex-grow-1'
                                                    }
                                                 }"
                                        >
                                            <span class="font-normal">These fields required for the theme page template.</span>
                                        </Message>

                                        <div v-if="store.item.template_form_groups">
                                            <TemplateFields :groups="store.item.template_form_groups"/>
                                        </div>
                                    </AccordionTab>
                                </Accordion>
                            </div>
                        </div>
            </template>
        </Card>
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
                        class="p-button-sm"
                        @click="toggleFormMenu"
                        data-testid="contents-form-menu"
                        icon="pi pi-angle-down"
                        aria-haspopup="true"/>

                    <Menu ref="form_menu"
                          :model="store.form_menu_list"
                          :popup="true" />
                    <!--/form_menu-->


                    <Button class="p-button-primary p-button-sm"
                            icon="pi pi-times"
                            data-testid="contents-to-list"
                            @click="store.toList()">
                    </Button>
                </div>
            </template>
            <div v-if="store.item" class="py-2">

                <VhField label="Name" class="mb-2" labelClass="text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="contents-name"
                               data-testid="contents-name"
                               v-model="store.item.name"/>
                </VhField>
                <VhField label="Status" class="mb-2" labelClass="text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <Dropdown v-model="store.item.status"
                              :options="store.assets.content_type.content_statuses"
                              optionLabel="name"
                              optionValue="name"
                              placeholder="Select status"
                              data-testid="contents-status"
                              class="w-full is-small" />
                </VhField>
                <VhField label="Theme" class="mb-2" labelClass="text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <Dropdown v-model="store.item.vh_theme_id"
                              :options="store.assets.themes"
                              optionLabel="name"
                              optionValue="id"
                              placeholder="Select Theme"
                              data-testid="contents-theme"
                              @change="store.setActiveTheme"
                              class="w-full is-small" />
                </VhField>
                <VhField label="Template"
                         class="mb-0"
                         v-if="store.active_theme && store.item.vh_theme_id"
                         labelClass="text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <Dropdown v-model="store.item.vh_theme_template_id"
                              :options="store.active_theme.templates"
                              optionLabel="name"
                              optionValue="id"
                              @change="store.setActiveTemplate"
                              placeholder="Select Template"
                              data-testid="contents-template"
                              class="w-full is-small" />
                </VhField>

            </div>
        </Panel>
    </div>

</template>
