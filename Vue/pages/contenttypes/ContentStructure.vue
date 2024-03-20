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
        <Card class="is-small py-1"
            :pt="{
                footer: 'pt-0'
            }">
            <template #header>
                <div class="flex justify-content-between align-items-center w-full">
                    <b>Content Structure</b>
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
                      v-for="(item,idx) in store.item.groups"
                      class="mb-3 is-small py-2"
                >
                    <template #content>
                        <div class="w-full flex justify-content-between align-items-center mb-2">
                            <h4 class="font-semibold">{{item.name}}</h4>
                            <div class="flex align-items-center">
                                <InputSwitch v-model="item.is_repeatable"
                                             class="is-small"
                                             v-bind:false-value="0"
                                             v-bind:true-value="1"
                                             data-testid="contetntypes-is_repeatable"
                                />

                                <p class="ml-1 mr-2 text-xs font-semibold">Is Repeatable</p>
                                <div class="w-max p-inputgroup align-items-center">

                                    <Button @click="store.getCopy(item.slug)"
                                            data-testid="contetntypes-copy_slug"
                                            class="p-button-sm"
                                            :label="'#' + useVaah.convertToStr(item.id)"
                                    />

                                    <Button icon="pi pi-trash"
                                            class="p-button-sm"
                                            data-testid="contetntypes-remove_group"
                                            @click="store.removeGroup(item,idx)"
                                    />
                                </div>
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
                                    <div class="p-inputgroup" :class="index < item.fields.length - 1 ? 'mb-2' : ''">
                                        <InputText class="w-2 p-inputtext-sm" :model-value="element.type.name" disabled/>
                                        <InputText class="w-6 p-inputtext-sm"
                                                   v-model="element.name"
                                                   data-testid="contenttype-group_field_name"
                                                   placeholder="Field Name"/>
                                        <Button :label="element.id ? '#' + useVaah.convertToStr(element.id) : '&nbsp;&nbsp;'"
                                                :disabled="!element.id"
                                                @click="store.getCopy(element.slug)"
                                                data-testid="contenttype-group_field_slug"
                                                class="p-button-sm"/>
                                        <Button icon="pi pi-cog"
                                                data-testid="contenttype-group_field_cog"
                                                @click="element.content_settings_status = !element.content_settings_status"
                                                class="p-button-sm">
                                        </Button>
                                        <Button icon="pi pi-trash"
                                                class="p-button-sm"
                                                data-testid="contenttype-group_field_remove"
                                                @click="store.removeField(index,idx)"/>
                                    </div>
                                    <div v-if="element.content_settings_status"
                                         class="my-2">
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
                                                        <td class="py-1">
                                                            Is searchable
                                                        </td>

                                                        <td class="py-1">
                                                            <InputSwitch  v-bind:false-value="0"
                                                                         v-bind:true-value="1"
                                                                         data-testid="contenttype-group_field_searchable"
                                                                         v-model="element.is_searchable"
                                                                         class="is-small"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-1">
                                                            Excerpt
                                                        </td>

                                                        <td class="py-1">
                                                            <Textarea data-testid="contenttype-group_field_excerpt"
                                                                      v-model="element.excerpt"
                                                                      class="w-full"
                                                            />
                                                        </td>
                                                    </tr>

                                                    <template v-if="element.meta" v-for="(meta_item, meta_index) in element.meta">
                                                        <tr
                                                            v-if="meta_index !== 'filter' && ((meta_index !== 'container_opening_tag'
                                                                    && meta_index !== 'container_closing_tag')
                                                                    || (store && store.assets && store.assets.non_repeatable_fields
                                                                     && store.assets.non_repeatable_fields.includes(element.type.slug))
                                                                    || element.is_repeatable)"
                                                        >
                                                            <td v-if="meta_index !== 'filter_id'
                                                                && meta_index !== 'display_column'
                                                                && meta_index !== 'options'"
                                                                class="py-1"
                                                            >
                                                                <span v-html="useVaah.toLabel(meta_index)"></span>
                                                            </td>

                                                            <td v-if="meta_index !== 'filter_id'
                                                                    && meta_index !== 'display_column'
                                                                    && meta_index !== 'options'"
                                                                class="py-1"
                                                            >
                                                                <template v-if="meta_index.includes('is_')">
                                                                    <Checkbox :id="meta_index"
                                                                              :data-testid="'contenttype-group_field_meta_' + meta_index"
                                                                              v-model="element.meta[meta_index]"
                                                                              :inputId="meta_index"
                                                                              :binary="true"
                                                                    />

                                                                    <label :for="meta_index" class="ml-2">
                                                                        {{ useVaah.toLabel(meta_index) }}
                                                                    </label>
                                                                </template>

                                                                <template v-else-if="meta_index === 'option'">
                                                                    <Chips  v-model="element.meta[meta_index]"
                                                                            placeholder="Add a tag"
                                                                            aria-close-label="Delete this option"
                                                                            class="w-full"
                                                                            inputClass="p-inputtext-sm"
                                                                    />
                                                                </template>

                                                                <template v-else-if="meta_index === 'type'
                                                                && (store && store.assets && store.assets.content_relations)">
                                                                    <Dropdown v-model="element.meta[meta_index]"
                                                                              :options="store.assets.content_relations"
                                                                              optionLabel="name"
                                                                              optionValue="name"
                                                                              placeholder="Select"
                                                                              class="w-full md:w-14rem mt-3"
                                                                    />

                                                                    <TreeSelect  v-if="element.meta[meta_index]
                                                                        && useVaah.findInArrayByKey(
                                                                        store.assets.content_relations,'name', element.meta[meta_index])
                                                                        && useVaah.findInArrayByKey(
                                                                        store.assets.content_relations,'name', element.meta[meta_index])['options']"
                                                                                 class="w-full"
                                                                                v-model="element.meta['filter']"
                                                                                 :metaKeySelection="false"
                                                                                 @node-select="store.selectType($event,element)"
                                                                                :options="useVaah.findInArrayByKey(
                                                                        store.assets.content_relations,'name', element.meta[meta_index])['options']"
                                                                                placeholder="Select..."
                                                                    />
                                                                </template>

                                                                <template v-else>
                                                                    <InputText v-model="element.meta[meta_index]"
                                                                               type="text"
                                                                               class="w-full p-inputtext-sm"
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
                    <InputText v-model="store.new_group.name"
                               class="p-inputtext-sm">
                    </InputText>
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
              class="is-small py-1"
        >
            <template #header>
                <b>Content Fields</b>
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
                </div>
            </template>
        </Card>
    </div>

</template>
