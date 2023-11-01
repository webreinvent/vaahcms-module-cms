<script setup>
import {onMounted, ref, watch} from "vue";
import {useRoute} from 'vue-router';

import {useBlockStore} from '../../stores/store-blocks'

import VhViewRow from '../../vaahvue/vue-three/primeflex/VhViewRow.vue';

const store = useBlockStore();
const route = useRoute();

onMounted(async () => {

    /**
     * If record id is not set in url then
     * redirect user to list view
     */
    if (route.params && !route.params.id) {
        store.toList();
        return false;
    }

    /**
     * Fetch the record from the database
     */
    if (!store.item || Object.keys(store.item).length < 1) {
        await store.getItem(route.params.id);
    }

    /**
     * Watch if url record id is changed, if changed
     * then fetch the new records from database
     */
    /*watch(route, async (newVal,oldVal) =>
        {
            if(newVal.params && !newVal.params.id
                && newVal.name === 'articles.view')
            {
                store.toList();

            }
            await store.getItem(route.params.id);
        }, { deep: true }
    )*/

});

//--------toggle item menu
const item_menu_state = ref();
const toggleItemMenu = (event) => {
    item_menu_state.value.toggle(event);
};
//--------/toggle item menu

</script>
<template>

    <div class="col-6">

        <Panel v-if="store && store.item" class="is-small">

            <template class="p-1" #header>


                <div class="flex flex-row py-1">
                    <div class="p-panel-title">
                        <span>
                            Content
                        </span>
                    </div>

                </div>


            </template>

            <div class="content" style="min-height: 150px" v-html="store.item.content"></div>
        </Panel>
    </div>

    <div class="col-3">

        <Panel v-if="store && store.item" class="is-small">

            <template class="p-1" #header>

                <div class="flex flex-row">

                    <div class="p-panel-title">
                        #{{store.item.id}}
                    </div>

                </div>

            </template>

            <template #icons>


                <div class="p-inputgroup">
                    <Button label="Edit"
                            @click="store.toEdit(store.item)"
                            data-testid="blocks-item-to-edit"
                            icon="pi pi-save"
                            class="p-button-sm"/>

                    <!--item_menu-->
                    <Button
                        type="button"
                        @click="toggleItemMenu"
                        data-testid="blocks-item-menu"
                        icon="pi pi-angle-down"
                        aria-haspopup="true"
                        class="p-button-sm"/>

                    <Menu ref="item_menu_state"
                          :model="store.item_menu_list"
                          :popup="true"/>
                    <!--/item_menu-->

                    <Button class="p-button-primary p-button-sm"
                            icon="pi pi-times"
                            data-testid="blocks-item-to-list"
                            @click="store.toList()"/>

                </div>


            </template>


            <div v-if="store.item">

                <Message severity="error"
                         class="p-container-message"
                         :closable="false"
                         icon="pi pi-trash"
                         v-if="store.item.deleted_at">

                    <div class="flex align-items-center justify-content-between">

                        <div class="">
                            Deleted {{store.item.deleted_at}}
                        </div>

                        <div class="">
                            <Button label="Restore"
                                    class="p-button-sm"
                                    data-testid="blocks-item-restore"
                                    @click="store.itemAction('restore')">
                            </Button>
                        </div>

                    </div>

                </Message>

                <div class="p-datatable p-component p-datatable-responsive-scroll p-datatable-striped p-datatable-sm overflow-auto">
                    <table class="p-datatable-table">
                        <tbody class="p-datatable-tbody">
                        <template v-for="(value, column) in store.item ">

                            <template v-if="column === 'created_by'
                        || column === 'updated_by' || column === 'theme'
                        || column === 'theme_location'">
                            </template>

                            <template v-else-if="column === 'id' || column === 'uuid'">
                                <VhViewRow :label="column"
                                           label_class="line-height-2"
                                           :value="value"
                                           :can_copy="true"
                                />
                            </template>

                            <template
                                v-else-if="(column === 'created_by_user' || column === 'updated_by_user'  || column === 'deleted_by_user') && (typeof value === 'object' && value !== null)">
                                <VhViewRow :label="column"
                                           label_class="line-height-2"
                                           :value="value"
                                           type="user"
                                />
                            </template>

                            <template v-else-if="column === 'is_active'">
                                <VhViewRow :label="column"
                                           label_class="line-height-2"
                                           :value="value"
                                           type="yes-no"
                                />
                            </template>

                            <template v-else-if="column === 'vh_theme_id'">
                                <tr>
                                    <td class="line-height-2"><b>Theme</b></td>
                                    <td v-if="store.item.theme" colspan="2">
                                        <Tag class="font-normal"
                                             :value="store.item.theme.title"
                                             severity="primary"></Tag>
                                    </td>
                                </tr>
                            </template>

                            <template v-else-if="column === 'vh_theme_location_id'">
                                <tr>
                                    <td class="line-height-2"><b>Theme Location</b></td>
                                    <td v-if="store.item.theme_location" colspan="2">
                                        <Tag class="font-normal"
                                             :value="store.item.theme_location.name"
                                             severity="primary"></Tag>
                                    </td>
                                </tr>
                            </template>

                            <template v-else>
                                <VhViewRow :label="column"
                                           label_class="line-height-2"
                                           :value="value"
                                />
                            </template>


                        </template>
                        </tbody>

                    </table>

                </div>
            </div>
        </Panel>

    </div>

</template>
