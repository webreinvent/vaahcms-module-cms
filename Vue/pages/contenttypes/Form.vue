<script setup>
import {onMounted, ref, watch} from "vue";
import { useContentTypeStore } from '../../stores/store-contenttypes'
import draggable from 'vuedraggable'
import VhField from './../../vaahvue/vue-three/primeflex/VhField.vue'
import {useRoute} from 'vue-router';
import { vaah } from "../../vaahvue/pinia/vaah";


const store = useContentTypeStore();
const route = useRoute();
const useVaah = vaah();

onMounted(async () => {
    store.enable_status_editing_indexes = [];
    if(route.params && route.params.id)
    {
        await store.getItem(route.params.id);
    }

    await store.watchItem();

    await store.getFormMenu();
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
                    <Button :label=" '#' + store.item.id "
                            class="p-button-sm"
                            @click="useVaah.copy(store.item.id)"
                            data-testid="contenttypes-item_id"
                            v-if="store && store.item && store.item.id"
                    />

                    <Button label="Save"
                            class="p-button-sm"
                            v-if="store && store.item && store.item.id"
                            data-testid="contenttypes-save"
                            @click="store.itemAction('save')"
                            icon="pi pi-save"/>

                    <Button label="Create & New"
                            class="p-button-sm"
                            v-else
                            @click="store.itemAction('create-and-new')"
                            data-testid="contenttypes-create-and-new"
                            icon="pi pi-save"/>


                    <!--form_menu-->
                    <Button
                        v-if="store && store.item && store.item.id"
                        class="p-button-sm"
                        type="button"
                        @click="toggleFormMenu"
                        data-testid="contenttypes-form-menu"
                        icon="pi pi-angle-down"
                        aria-haspopup="true"/>

                    <Menu ref="form_menu"
                          v-if="store && store.item && store.item.id"
                          :model="store.form_menu_list"
                          :popup="true" />
                    <!--/form_menu-->


                    <Button class="p-button-sm"
                            icon="pi pi-times"
                            data-testid="contenttypes-to-list"
                            @click="store.toList()">
                    </Button>
                </div>



            </template>


            <div v-if="store.item" class="py-1" >

                <VhField label="Name" class="mb-2" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="contenttypes-name"
                               data-testid="contenttypes-name"
                               v-model="store.item.name"/>
                </VhField>

                <VhField label="Slug" class="mb-2" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="contenttypes-slug"
                               data-testid="contenttypes-slug"
                               v-model="store.item.slug"/>
                </VhField>

                <VhField label="Content Plural Name" class="mb-2" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="contenttypes-plural_name"
                               data-testid="contenttypes-plural_name"
                               v-model="store.item.plural"/>
                </VhField>

                <VhField label="Content Plural Slug" class="mb-2" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="contenttypes-plural_slug"
                               data-testid="contenttypes-plural_slug"
                               v-model="store.item.plural_slug"/>
                </VhField>

                <VhField label="Content Singular Name" class="mb-2" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="contenttypes-singular"
                               data-testid="contenttypes-singular_name"
                               v-model="store.item.singular"/>
                </VhField>

                <VhField label="Content Singular Slug" class="mb-2" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="contenttypes-singular_slug"
                               data-testid="contenttypes-singular_slug"
                               v-model="store.item.singular_slug"/>
                </VhField>

                <VhField label="Excerpt" class="mb-2" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <Textarea  class="w-full"
                               rows="5" cols="30"
                               name="contenttypes-excerpt"
                               data-testid="contenttypes-excerpt"
                               v-model="store.item.excerpt"/>
                </VhField>

                <VhField label="Is Published" class="mb-2" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputSwitch v-bind:false-value="0"
                                 v-bind:true-value="1"
                                 class="is-small"
                                 name="contenttypes-is_published"
                                 data-testid="contenttypes-is_published"
                                 v-model="store.item.is_published"/>
                </VhField>
                <VhField label="Is Comments Allowed" class="mb-2" labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputSwitch v-bind:false-value="0"
                                 v-bind:true-value="1"
                                 class="is-small"
                                 name="contenttypes-is_commentable"
                                 data-testid="contenttypes-is_commentable"
                                 v-model="store.item.is_commentable"/>
                </VhField>
                <draggable
                    v-model="store.item.content_statuses"
                    class="dragArea list-group"
                    :group="{ name: 'content-types', pull: 'clone', put: false }"
                    @start="drag=true"
                    @end="drag=false"
                    item-key="id">
                    <template #item="{element,index}">
                        <div class="p-inputgroup mb-2">
                            <Button icon="pi pi-bars" class="p-button-sm p-button-secondary"/>
                            <InputText class="w-full p-inputtext-sm"
                                       name="contenttypes-statuses_name"
                                       data-testid="contenttypes-statuses_name"
                                       :disabled="!store.enable_status_editing_indexes.includes(index)"
                                       v-model="store.item.content_statuses[index]"/>
                            <Button icon="pi pi-pencil"
                                    data-testid="contenttypes-statuses_name_edit"
                                    @click="store.toggleEditStatus(index)"
                                    class="p-button-sm p-button-secondary"/>
                            <Button icon="pi pi-times"
                                    data-testid="contenttypes-statuses_name_edit"
                                    @click="store.removeStatus(index)"
                                    class="p-button-sm p-button-secondary"/>
                        </div>
                    </template>
                </draggable>
                <VhField label="New Status" class="mb-0"
                         labelClass="line-height-2 text-xs col-12 mb-2 md:col-3 md:mb-0"
                         valueClass="col-12 md:col-9">
                    <InputText class="w-full p-inputtext-sm"
                               name="contenttypes-new_status"
                               data-testid="contenttypes-new_status"
                               v-model="store.new_status_item"
                               v-on:keyup.enter="store.addStatus"/>
                </VhField>

            </div>
        </Panel>

    </div>

</template>
