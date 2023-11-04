<script setup>
import { vaah } from '../../../vaahvue/pinia/vaah'
import { useContentTypeStore } from '../../../stores/store-contenttypes'

const store = useContentTypeStore();
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
                    responsiveLayout="scroll"
         >
             <Column selectionMode="multiple"
                     v-if="store.isContentStructure()"
                     headerStyle="width: 3em"
             />

            <Column field="id" v-if="store.isContentStructure()" header="ID" :style="{width: '90px'}" :sortable="true"
            />

            <Column field="name" header="Name"
                    :sortable="true"
            >
                <template #body="prop">
                    <Badge v-if="prop.data.deleted_at"
                           value="Trashed"
                           severity="danger"
                    />
                    {{ prop.data.name }}
                </template>
            </Column>

             <Column field="plural"
                     header="Plural"
                     v-if="store.isContentStructure()"
                     :sortable="true"
             >
                 <template #body="prop">
                     {{ prop.data.plural }}
                 </template>
             </Column>

             <Column field="singular"
                     header="Singular"
                     v-if="store.isContentStructure()"
                     :sortable="true"
             >
                 <template #body="prop">
                     {{ prop.data.singular }}
                 </template>
             </Column>


             <Column field="updated_at"
                     header="Updated"
                     v-if="store.isContentStructure()"
                     style="width:90px;"
                     :sortable="true"
             >
                 <template #body="prop">
                     {{ useVaah.ago(prop.data.updated_at) }}
                 </template>
             </Column>

            <Column field="is_published"
                    v-if="store.isContentStructure()"
                    :sortable="true"
                    style="width:150px;"
                    header="Is Published"
            >
                <template #body="prop">
                    <InputSwitch v-model.bool="prop.data.is_published"
                                 data-testid="contenttypes-table-is-active"
                                 v-bind:false-value="0"  v-bind:true-value="1"
                                 class="p-inputswitch-sm"
                                 @input="store.toggleIsActive(prop.data)"
                    />
                </template>
            </Column>

            <Column field="actions" style="width:150px;"
                    :style="{width: store.getActionWidth() }"
                    :header="store.getActionLabel()"
            >
                <template #body="prop">
                    <div class="p-inputgroup">
                        <Button class="p-button-tiny p-button-text"
                                data-testid="contenttypes-table-to-view"
                                v-tooltip.top="'Content Structure'"
                                @click="store.toContentStructure(prop.data)"
                                icon="pi pi-align-left"
                        />

                        <Button class="p-button-tiny p-button-text"
                                data-testid="contenttypes-table-to-view"
                                v-tooltip.top="'View'"
                                @click="store.toView(prop.data)"
                                icon="pi pi-eye"
                        />
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
        />
        <!--/paginator-->
    </div>
</template>
