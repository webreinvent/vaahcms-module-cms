<template>
    <draggable
        class="dragArea drag-list"
        tag="ul"
        :list="tasks"
        :group="{ name: 'menu_items' }"
        item-key="name"
    >
        <template #item="{ element,index }">
            <li >
                <div class="p-inputgroup mb-3">
                    <InputText v-if="element.content"
                               class="w-2" :model-value="element.content.name" disabled/>
                    <InputText class="w-6"
                               v-model="element.name"
                               data-testid="menus-item_field_name"
                               placeholder="Field Name"/>
                    <Button icon="pi pi-home p-button-sm"
                            :severity="element.is_home ?'success':''"
                            :disabled="!element.id"
                            @click="store.setAsHomePage(element.id)"
                            v-tooltip.top="'Set as Home Page'"
                            data-testid="menus-item_field_name"/>
                    <Button icon="pi pi-cog p-button-sm"
                            v-tooltip.top="'Settings'"
                            data-testid="menus-item_field_settings"
                            @click="element.menu_options = !element.menu_options"/>
                    <Button icon="pi pi-trash p-button-sm"
                            v-tooltip.top="'Delete'"
                            data-testid="menus-item_field_remove"
                            @click="store.removeAt(this.tasks,index)"/>
                </div>
                <div class="menu-options m-3" v-if="element.menu_options">
                    <div class="mb-5">
                        <Checkbox v-model="element.attr_target_blank"
                                  data-testid="menus-item_as_target_blank"
                                  :binary="true"
                                  :inputId="'check-'+index"></Checkbox>
                        <label :for="'check-'+index" class="text-xs font-semibold ml-1">Open in new Page</label>
                    </div>
                    <div v-if="element.type == 'internal-link'" class="p-float-label mb-5">
                        <InputText v-model="element.uri"
                                   data-testid="menus-item_internal_link"
                                   placeholder="/about-us"
                                   class="w-full p-inputtext-sm" id="item-id"/>
                        <label for="item-id">URL</label>
                    </div>
                    <div v-if="element.type == 'external-link'" class="p-float-label mb-5">
                        <InputText v-model="element.uri"
                                   data-testid="menus-item_external_link"
                                   placeholder="https://www.google.com"
                                   class="w-full p-inputtext-sm" id="item-id"/>
                        <label for="item-id">URL</label>
                    </div>
                    <div class="p-float-label mb-5">
                        <InputText v-model="element.attr_id"
                                   data-testid="menus-item_id"
                                   class="w-full p-inputtext-sm" id="item-id"/>
                        <label for="item-id">Menu Item Id</label>
                    </div>
                    <div class="p-float-label mb-5">
                        <InputText v-model="element.attr_class"
                                   data-testid="menus-item_class"
                                   class="w-full p-inputtext-sm" id="item-class"/>
                        <label for="item-class">Menu Item Class</label>
                    </div>
                </div>
                <NestedDraggable v-if="element.child" :tasks="element.child" />
            </li>
        </template>
    </draggable>
</template>

<script setup>
import {reactive, ref, watch } from 'vue';
import {vaah} from '../../../vaahvue/pinia/vaah'
import { useMenuStore} from '../../../stores/store-menus'

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
</style>
