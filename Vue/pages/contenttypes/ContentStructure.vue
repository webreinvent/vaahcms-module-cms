<script setup>
import {onMounted, ref, watch} from "vue";
import {useRoute} from 'vue-router';
import {vaah} from '../../vaahvue/pinia/vaah'
import draggable from 'vuedraggable'
import { useContentTypeStore } from '../../stores/store-contenttypes'
import VhViewRow from '../../vaahvue/vue-three/primeflex/VhViewRow.vue';

const store = useContentTypeStore();
const route = useRoute();
const useVaah = vaah();

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
        await store.getContentStructure(route.params.id);
    // }


});

//--------toggle item menu
const item_menu_state = ref();
const toggleItemMenu = (event) => {
    item_menu_state.value.toggle(event);
};
//--------/toggle item menu

const onSelectType = (field,data,group_index,field_index) => {
    field.meta['filter_id'] = null;
}

</script>

<template>
    <div class="col-6" >
        <Card
            :pt="{
                content: 'p-0',
                footer: 'pt-2'
            }">
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
                      v-for="(item,idx) in store.item.groups" class="mb-3"
                      :pt="{
                            content: 'p-0'
                      }"
                >
                    <template #content>
                        <div class="w-full flex justify-content-between align-items-center mb-4">
                            <h4 class="font-semibold">{{item.name}}</h4>
                            <div class="w-max p-inputgroup align-items-center">
                                <InputSwitch v-model="item.is_repeatable"
                                             class="is-small"
                                             v-bind:false-value="0"
                                             v-bind:true-value="1"
                                             data-testid="contetntypes-is_repeatable"
                                />

                                <p class="ml-1 mr-3 text-xs font-semibold">Is Repeatable</p>

                                <Button icon="pi pi-hashtag"
                                        @click="store.getCopy(item.slug)"
                                        data-testid="contetntypes-copy_slug"
                                        class="p-button-sm"
                                        :label="useVaah.convertToStr(item.id)"
                                />

                                <Button icon="pi pi-trash"
                                        class="p-button-sm"
                                        data-testid="contetntypes-remove_group"
                                        @click="store.removeGroup(item,idx)"
                                />
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
                                    <div class="p-inputgroup" :class="index < item.fields.length - 1 ? 'mb-3' : ''">
                                        <InputText class="w-2" :model-value="element.type.name" disabled/>
                                        <InputText class="w-6"
                                                   v-model="element.name"
                                                   data-testid="contenttype-group_field_name"
                                                   placeholder="Field Name"/>
                                        <Button icon="pi pi-hashtag p-button-sm"
                                                :label="element.id ? useVaah.convertToStr(element.id) : '&nbsp;&nbsp;'"
                                                :disabled="!element.id"
                                                @click="store.getCopy(element.slug)"
                                                data-testid="contenttype-group_field_slug"/>
                                        <Button icon="pi pi-cog p-button-sm"
                                                data-testid="contenttype-group_field_cog"
                                                @click="element.content_settings_status = !element.content_settings_status"></Button>
                                        <Button icon="pi pi-trash p-button-sm"
                                                data-testid="contenttype-group_field_remove"
                                                @click="store.removeField(index,idx)"/>
                                    </div>
                                    <div v-if="element.content_settings_status" :class="index < item.fields.length - 1 ? 'my-3' : 'mt-3'">
                                        <div class="p-datatable p-component p-datatable-responsive-stack
                                            p-datatable-striped p-datatable-sm"
                                             data-scrollselectors=".p-datatable-wrapper" pv_id_6="">
                                            <div class="p-datatable-wrapper">
                                                <table role="table" class="p-datatable-table p-component p-datatable-striped">

                                                    <tr v-if="!store.assets.non_repeatable_fields.includes(element.type.slug)">
                                                        <td>
                                                            Is repeatable
                                                        </td>

                                                        <td>
                                                            <InputSwitch v-model="element.is_repeatable"
                                                                         class="is-small"
                                                                         v-bind:false-value="0"
                                                                         v-bind:true-value="1" data-testid="contenttype-group_field_repeatable"
                                                            />
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="py-2">
                                                            Is searchable
                                                        </td>

                                                        <td class="py-2">
                                                            <InputSwitch  v-bind:false-value="0"
                                                                         v-bind:true-value="1"
                                                                         data-testid="contenttype-group_field_searchable"
                                                                         v-model="element.is_searchable"
                                                                         class="is-small"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Excerpt
                                                        </td>

                                                        <td>
                                                            <Textarea data-testid="contenttype-group_field_excerpt"
                                                                      v-model="element.excerpt"
                                                                      class="w-full"
                                                            />
                                                        </td>
                                                    </tr>

                                                    <template v-if="element.meta">
                                                        <tr v-for="(meta_item, meta_index) in element.meta"
                                                            v-if="(meta_index !== 'container_opening_tag'
                                                                    && meta_index !== 'container_closing_tag')
                                                                    || (store && store.assets && store.assets.non_repeatable_fields
                                                                     && store.assets.non_repeatable_fields.includes(element.type.slug))
                                                                    || element.is_repeatable"
                                                        >
                                                            <td v-if="meta_index !== 'filter_id'
                                                                && meta_index !== 'display_column'
                                                                && meta_index !== 'options'"
                                                            >
                                                                <span class="mt-3" v-html="useVaah.toLabel(meta_index)"></span>
                                                            </td>

                                                            <td v-if="meta_index !== 'filter_id'
                                                                    && meta_index !== 'display_column'
                                                                    && meta_index !== 'options'"
                                                            >
                                                                <template v-if="meta_index.includes('is_')">
                                                                    <Checkbox :id="meta_index"
                                                                              :data-testid="'contenttype-group_field_meta_' + meta_index"
                                                                              v-model="element.meta[meta_index]"
                                                                              :inputId="meta_index"
                                                                              :binary="true"
                                                                    />

                                                                    <label :for="meta_index" class="mt-4 ml-2">
                                                                        {{ useVaah.toLabel(meta_index) }}
                                                                    </label>
                                                                </template>

                                                                <template v-else-if="meta_index === 'option'">
                                                                    <Chips  v-model="element.meta[meta_index]"
                                                                            placeholder="Add a tag"
                                                                            aria-close-label="Delete this option"
                                                                            class="w-full mt-3"
                                                                            inputClass="p-inputtext-sm"
                                                                    />
                                                                </template>

                                                                <template v-else-if="meta_index === 'type'
                                                                && (store && store.assets && store.assets.content_relations)">
                                                                    <Dropdown v-model="element.meta[meta_index]"
                                                                              :options="store.assets.content_relations"
                                                                              optionLabel="name"
                                                                              placeholder="Select"
                                                                              class="w-full md:w-14rem mt-3"
                                                                    />
                                                                </template>

                                                                <template v-else>
                                                                    <InputText v-model="element.meta[meta_index]"
                                                                               type="text"
                                                                               class="mt-3 w-full p-inputtext-sm"
                                                                    />
                                                                </template>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </table>
                                            </div>
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
    <div class="col-3" >
        <Card style="height:570px; overflow-y: auto"
              :pt="{
                content: 'p-0'
              }"
        >
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
                            <div class="p-inputgroup"
                                 :class="index < store.assets.field_types.length - 1 ? 'mb-2' : ''">
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
