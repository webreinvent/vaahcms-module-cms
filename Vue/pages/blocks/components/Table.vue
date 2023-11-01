<script setup>
import { vaah } from '../../../vaahvue/pinia/vaah'
import { useBlockStore } from '../../../stores/store-blocks'

const store = useBlockStore();
const useVaah = vaah();

</script>

<template>

    <div v-if="store.list">
        <!--table-->
         <DataTable :value="store.list.data"
                    dataKey="id"
                   class="p-datatable-sm"
                   v-model:selection="store.action.items"
                   stripedRows
                   responsiveLayout="scroll">

            <Column selectionMode="multiple"
                    v-if="store.isViewLarge()"
                    headerStyle="width: 3em">
            </Column>

            <Column field="id" header="ID"
                    v-if="store.isViewLarge()"
                    :style="{width: store.getIdWidth()}"
                    :sortable="true">
            </Column>

             <Column field="name" header="Name"
                     :sortable="true"
                     class="flex align-items-center"
             >
                 <template #body="prop">
                     {{ prop.data.name }}

                     <Button class="p-button-tiny p-button-text"
                             :data-testid="'block-copy_block_'+prop.data.id"
                             v-tooltip.top="'Copy Block'"
                             @click="store.copyBlockCode(prop.data.slug)"
                             icon="pi pi-copy">
                     </Button>

                 </template>
             </Column>

             <Column field="theme-location" header="Theme / Location"
                     v-if="store.isViewLarge()"
                     :sortable="true"
             >
                 <template #body="prop">

                     <div class="p-inputgroup">
                         <Tag v-if="prop.data.theme"
                              class="mr-0 font-normal border-round-left-sm"
                              :value="prop.data.theme.name"
                              severity="info"
                              aria-label="Table Primary Tag"
                              tabindex="0"></Tag>
                         <Tag v-if="prop.data.theme_location"
                              class="mr-0 font-normal border-round-right-sm"
                              :value="prop.data.theme_location.name"
                              aria-label="Table Primary Tag"
                              tabindex="0"></Tag>
                         <Button class="p-button-tiny p-button-text"
                                 :data-testid="'block-copy_block_'+prop.data.id"
                                 v-tooltip.top="'Copy Location Blocks'"
                                 @click="store.copyLocationCode(prop.data.theme_location.slug)"
                                 icon="pi pi-copy">
                         </Button>
                     </div>

                 </template>
             </Column>

            <Column field="updated_at" header="Updated"
                    v-if="store.isViewLarge()"
                    style="width:150px;"
                    :sortable="true">

                <template #body="prop">
                    {{useVaah.ago(prop.data.updated_at)}}
                </template>

            </Column>

            <Column field="is_published" v-if="store.isViewLarge()"
                    :sortable="true"
                    style="width:150px;"
                    header="Is Published">

                <template #body="prop">
                    <InputSwitch v-model.bool="prop.data.is_published"
                                 data-testid="blocks-is_published"
                                 v-bind:false-value="0"  v-bind:true-value="1"
                                 class="p-inputswitch-sm"
                                 @input="store.toggleIsPublished(prop.data)">
                    </InputSwitch>
                </template>

            </Column>

            <Column field="actions" style="width:150px;"
                    :style="{width: store.getActionWidth() }"
                    :header="store.getActionLabel()">

                <template #body="prop">
                    <div class="p-inputgroup ">

                        <Button class="p-button-tiny p-button-text"
                                data-testid="blocks-table-to-view"
                                v-tooltip.top="'View'"
                                @click="store.toView(prop.data)"
                                icon="pi pi-eye" />

                        <Button class="p-button-tiny p-button-text"
                                data-testid="blocks-table-to-edit"
                                v-tooltip.top="'Update'"
                                @click="store.toEdit(prop.data)"
                                icon="pi pi-pencil" />

                        <Button class="p-button-tiny p-button-danger p-button-text"
                                data-testid="blocks-table-action-trash"
                                v-if="store.isViewLarge() && !prop.data.deleted_at"
                                @click="store.itemAction('trash', prop.data)"
                                v-tooltip.top="'Trash'"
                                icon="pi pi-trash" />


                        <Button class="p-button-tiny p-button-success p-button-text"
                                data-testid="blocks-table-action-restore"
                                v-if="store.isViewLarge() && prop.data.deleted_at"
                                @click="store.itemAction('restore', prop.data)"
                                v-tooltip.top="'Restore'"
                                icon="pi pi-replay" />


                    </div>

                </template>


            </Column>


        </DataTable>
        <!--/table-->

        <!--paginator-->
        <Paginator v-model:rows="store.query.rows"
                   :totalRecords="store.list.total"
                   @page="store.paginate($event)"
                   :rowsPerPageOptions="store.rows_per_page"
                   class="bg-white-alpha-0 py-2"
                   template="PrevPageLink PageLinks NextPageLink RowsPerPageDropdown"
                   :pt="{ root: 'p-0 pt-1'}"
        >
        </Paginator>
        <!--/paginator-->

    </div>

</template>
