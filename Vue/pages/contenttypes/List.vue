<script setup>
import {onMounted, reactive, ref} from "vue";
import {useRoute} from 'vue-router';

import {useContentTypeStore} from '../../stores/store-contenttypes'

import Actions from "./components/Actions.vue";
import Table from "./components/Table.vue";

const store = useContentTypeStore();
const route = useRoute();

import { useConfirm } from "primevue/useconfirm";
const confirm = useConfirm();


onMounted(async () => {

    document.title = 'Content Types - CMS';
    /**
     * call onLoad action when List view loads
     */
    await store.onLoad(route);

    /**
     * watch routes to update view, column width
     * and get new item when routes get changed
     */
    await store.watchRoutes(route);

    /**
     * watch states like `query.filter` to
     * call specific actions if a state gets
     * changed
     */
    await store.watchStates();

    /**
     * fetch assets required for the crud
     * operation
     */
    await store.getAssets();

    /**
     * fetch list of records
     */
    await store.getList();
});

</script>
<template>

    <div class="grid" v-if="store.assets">

        <div :class="'col-'+store.list_view_width">
            <Panel class="is-small">

                <template class="p-1" #header>

                    <div class="flex flex-row">
                        <div >
                            <b class="mr-1">Content Types</b>
                            <Badge v-if="store.list && store.list.total > 0"
                                   :value="store.list.total">
                            </Badge>
                        </div>

                    </div>

                </template>

                <template #icons>

                    <Button data-testid="contenttypes-list-create"
                            @click="store.toForm()"
                            class="p-button-sm"
                            icon="pi pi-plus mr-1"
                            label="Create"
                    >
                    </Button>

                </template>

                <Actions/>

                <Table/>

            </Panel>
        </div>

        <RouterView/>

    </div>


</template>
