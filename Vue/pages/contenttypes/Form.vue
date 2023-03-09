<script setup>
import {onMounted, ref, watch} from "vue";
import { useContentTypeStore } from '../../stores/store-contenttypes'

import VhField from './../../vaahvue/vue-three/primeflex/VhField.vue'
import {useRoute} from 'vue-router';


const store = useContentTypeStore();
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
                            v-if="store.item && store.item.id"
                            data-testid="contenttypes-save"
                            @click="store.itemAction('save')"
                            icon="pi pi-save"/>

                    <Button label="Create & New"
                            v-else
                            @click="store.itemAction('create-and-new')"
                            data-testid="contenttypes-create-and-new"
                            icon="pi pi-save"/>


                    <!--form_menu-->
                    <Button
                        type="button"
                        @click="toggleFormMenu"
                        data-testid="contenttypes-form-menu"
                        icon="pi pi-angle-down"
                        aria-haspopup="true"/>

                    <Menu ref="form_menu"
                          :model="store.form_menu_list"
                          :popup="true" />
                    <!--/form_menu-->


                    <Button class="p-button-primary"
                            icon="pi pi-times"
                            data-testid="contenttypes-to-list"
                            @click="store.toList()">
                    </Button>
                </div>



            </template>


            <div v-if="store.item">

                <VhField label="Name">
                    <InputText class="w-full"
                               name="contenttypes-name"
                               data-testid="contenttypes-name"
                               v-model="store.item.name"/>
                </VhField>

                <VhField label="Slug">
                    <InputText class="w-full"
                               name="contenttypes-slug"
                               data-testid="contenttypes-slug"
                               v-model="store.item.slug"/>
                </VhField>

                <VhField label="Content Plural Name">
                    <InputText class="w-full"
                               name="contenttypes-plural_name"
                               data-testid="contenttypes-plural_name"
                               v-model="store.item.plural"/>
                </VhField>

                <VhField label="Content Plural Slug">
                    <InputText class="w-full"
                               name="contenttypes-plural_slug"
                               data-testid="contenttypes-plural_slug"
                               v-model="store.item.plural_slug"/>
                </VhField>

                <VhField label="Content Singular Name">
                    <InputText class="w-full"
                               name="contenttypes-singular"
                               data-testid="contenttypes-singular_name"
                               v-model="store.item.singular"/>
                </VhField>

                <VhField label="Content Singular Slug">
                    <InputText class="w-full"
                               name="contenttypes-singular_slug"
                               data-testid="contenttypes-singular_slug"
                               v-model="store.item.singular_slug"/>
                </VhField>

                <VhField label="Excerpt">
                    <Textarea  class="w-full"
                               rows="5" cols="30"
                               name="contenttypes-excerpt"
                               data-testid="contenttypes-excerpt"
                               v-model="store.item.excerpt"/>
                </VhField>

                <VhField label="Is Published">
                    <InputSwitch v-bind:false-value="0"
                                 v-bind:true-value="1"
                                 name="contenttypes-is_published"
                                 data-testid="contenttypes-is_published"
                                 v-model="store.item.is_published"/>
                </VhField>
                <VhField label="Is Comments Allowed">
                    <InputSwitch v-bind:false-value="0"
                                 v-bind:true-value="1"
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
                    <template #item="{element}">
                        <div class="p-inputgroup mb-3">
                            <Button icon="pi pi-bars" class="p-button-sm p-button-secondary"></Button>
                            <Button :label="element.title" class="p-button-secondary p-button-sm"></Button>
                        </div>
                    </template>
                </draggable>
                <VhField label="New Status">
                    <InputText class="w-full"
                               name="contenttypes-new_status"
                               data-testid="contenttypes-new_status"
                               v-model="store.new_status"
                               @input="store.addStatus"/>
                </VhField>

            </div>
        </Panel>

    </div>

</template>
