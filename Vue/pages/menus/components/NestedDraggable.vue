<template>
    <draggable
        class="dragArea drag-list list-none"
        tag="ul"
        :list="tasks"
        :group="{ name: 'menu_items' }"
        item-key="name"
    >
        <template #item="{ element,index }">
            <li class="relative list-item-marker">
                <div class="p-inputgroup mb-2">
                    <InputText v-if="element.content"
                               class="w-2 p-inputtext-sm" :model-value="element.content.name" disabled/>
                    <InputText class="w-6 p-inputtext-sm"
                               v-model="element.name"
                               data-testid="menus-item_field_name"
                               placeholder="Field Name"/>
                    <Button icon="pi pi-home"
                            class="p-button-sm"
                            :severity="element.is_home ?'success':''"
                            :disabled="!element.id"
                            @click="store.setAsHomePage(element.id)"
                            v-tooltip.top="'Set as Home Page'"
                            data-testid="menus-item_field_name"/>
                    <Button icon="pi pi-cog"
                            class="p-button-sm"
                            v-tooltip.top="'Settings'"
                            data-testid="menus-item_field_settings"
                            @click="element.menu_options = !element.menu_options"/>
                    <Button icon="pi pi-trash"
                            class="p-button-sm"
                            v-tooltip.top="'Delete'"
                            data-testid="menus-item_field_remove"
                            @click="store.removeAt(this.tasks,index)"/>
                </div>
                <div class="menu-options px-3" v-if="element.menu_options">
                    <div class="my-2 flex align-items-center">
                        <Checkbox v-model="element.attr_target_blank"
                                  data-testid="menus-item_as_target_blank"
                                  :binary="true"
                                  :inputId="'check-'+index"></Checkbox>
                        <label :for="'check-'+index" class="text-xs font-semibold ml-1">Open in new Page</label>
                    </div>
                    <div v-if="element.type == 'internal-link'" class="mb-2">
                        <InputText v-model="element.uri"
                                   data-testid="menus-item_internal_link"
                                   placeholder="URL(/about-us)"
                                   class="w-full p-inputtext-sm" id="item-id"/>
                    </div>
                    <div v-if="element.type == 'external-link'" class="mb-2">
                        <InputText v-model="element.uri"
                                   data-testid="menus-item_external_link"
                                   placeholder="URL(https://www.google.com)"
                                   class="w-full p-inputtext-sm" id="item-id"/>
                    </div>
                    <div class="mb-2">
                        <InputText v-model="element.attr_id"
                                   data-testid="menus-item_id"
                                   placeholder="Menu Item Id"
                                   class="w-full p-inputtext-sm"
                                   id="item-id"/>
                    </div>
                    <div class="mb-3">
                        <InputText v-model="element.attr_class"
                                   data-testid="menus-item_class"
                                   placeholder="Menu Item Class"
                                   class="w-full p-inputtext-sm" id="item-class"/>
                    </div>
                </div>
                <NestedDraggable v-if="element.child" :tasks="element.child"/>
            </li>
        </template>
    </draggable>
</template>

<script setup>
import {reactive, ref, watch} from 'vue';
import {vaah} from '../../../vaahvue/pinia/vaah'
import {useMenuStore} from '../../../stores/store-menus'

import draggable from 'vuedraggable'

const store = useMenuStore();
const props = defineProps({
    tasks: {
        required: true,
        type: Array
    }
});

</script>

<style scoped>
.dragArea {
}

.drag-list:before {
    border-left: 1px dashed rgba(0, 0, 0, 0.3);
}

.list-item-marker::before {
    content: '';
    display: block;
    width: 10px;
    border-bottom: 1px dashed rgba(0, 0, 0, 0.3);
    position: absolute;
    top: 14px;
    left: -14px;
}
</style>
