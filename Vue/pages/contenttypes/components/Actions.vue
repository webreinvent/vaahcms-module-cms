<script  setup>
import {ref, reactive, watch, onMounted} from 'vue';
import { useContentTypeStore } from '../../../stores/store-contenttypes'

import Filters from './Filters.vue'

const store = useContentTypeStore();

onMounted(async () => {
    store.getListSelectedMenu();
    store.getListBulkMenu();
});

//--------selected_menu_state
const selected_menu_state = ref();
const toggleSelectedMenuState = (event) => {
    selected_menu_state.value.toggle(event);
};
//--------/selected_menu_state

//--------bulk_menu_state
const bulk_menu_state = ref();
const toggleBulkMenuState = (event) => {
    bulk_menu_state.value.toggle(event);
};
//--------/bulk_menu_state
</script>

<template>
    <div>

        <!--actions-->
        <div :class="{'flex justify-content-between': store.isViewLarge()}" class="mt-2 mb-2">

            <!--left-->
            <div v-if="store.view === 'large'">

                <!--selected_menu-->
                <Button
                    type="button"
                    @click="toggleSelectedMenuState"
                    data-testid="contenttypes-actions-menu"
                    aria-haspopup="true"
                    aria-controls="overlay_menu"
                    class="p-button-sm"
                >
                    <i class="pi pi-angle-down"></i>
                    <Badge v-if="store.action.items.length > 0"
                           :value="store.action.items.length" />
                </Button>
                <Menu ref="selected_menu_state"
                      :model="store.list_selected_menu"
                      :popup="true" />
                <!--/selected_menu-->

                <!--bulk_menu-->
                <Button
                    type="button"
                    @click="toggleBulkMenuState"
                    data-testid="contenttypes-actions-bulk-menu"
                    aria-haspopup="true"
                    aria-controls="bulk_menu_state"
                    class="ml-1 p-button-sm">
                    <i class="pi pi-ellipsis-h"></i>
                </Button>
                <Menu ref="bulk_menu_state"
                      :model="store.list_bulk_menu"
                      :popup="true" />
                <!--/bulk_menu-->

            </div>
            <!--/left-->

            <!--right-->
            <div >


                <div class="grid p-fluid">


                    <div class="col-12">
                        <div class="p-inputgroup ">

                            <InputText v-model="store.query.filter.q"
                                       @keyup.enter="store.delayedSearch()"
                                       @keyup.enter.native="store.delayedSearch()"
                                       @keyup.13="store.delayedSearch()"
                                       data-testid="contenttypes-actions-search"
                                       placeholder="Search"
                                       class="p-inputtext-sm"
                            />

                            <Button @click="store.delayedSearch()"
                                    data-testid="contenttypes-actions-search-button"
                                    icon="pi pi-search"
                                    class="p-button-sm"
                            />
                            <Button
                                type="button"
                                v-if="store.isContentStructure()"
                                class="p-button-sm"
                                data-testid="contenttypes-actions-show-filters"
                                @click="store.show_filters = true">
                                Filters
                                <Badge v-if="store.count_filters > 0" :value="store.count_filters"></Badge>
                            </Button>

                            <Button
                                type="button"
                                v-if="store.isContentStructure()"
                                icon="pi pi-filter-slash"
                                data-testid="contenttypes-actions-reset-filters"
                                class="p-button-sm"
                                label="Reset"
                                @click="store.resetQuery()" />

                        </div>
                    </div>


                    <Filters/>

                </div>

            </div>
            <!--/right-->

        </div>
        <!--/actions-->

    </div>
</template>
