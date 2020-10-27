<script src="./ListSmallViewJs.js"></script>
<template>
    <div>
        <b-table :data="page.list_is_empty ? [] : page.list.data"
                 :checkable="true"
                 :checked-rows.sync="page.bulk_action.selected_items"
                 checkbox-position="left"
                 :hoverable="true"
                 :row-class="setRowClass">

            <template  slot-scope="props">
                <b-table-column field="id" label="ID" width="40" numeric>
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column field="name" label="Name">
                    {{ props.row.name }}
                </b-table-column>

                <b-table-column v-if="props.row.deleted_at" field="is_active" label="Is Active">

                    <b-button v-if="props.row.is_published === 1" disabled rounded size="is-small"
                              type="is-success">
                        Yes
                    </b-button>
                    <b-button v-else rounded size="is-small" disabled type="is-danger">
                        No
                    </b-button>

                </b-table-column>

                <b-table-column v-else field="is_active" label="Is Active">
                    <b-tooltip label="Change Status" type="is-dark">
                        <b-button v-if="props.row.is_published === 1" rounded size="is-small"
                                  type="is-success" @click="changeStatus(props.row.id)">
                            Yes
                        </b-button>
                        <b-button v-else rounded size="is-small" type="is-danger"
                                  @click="changeStatus(props.row.id)">
                            No
                        </b-button>
                    </b-tooltip>
                </b-table-column>

                <b-table-column field="actions" label=""
                                width="80">

                    <b-tooltip label="Content Structure" type="is-dark">
                        <b-button size="is-small"
                                  @click="toContentStructure(props.row)"
                                  icon-left="align-left">
                        </b-button>
                    </b-tooltip>

                    <b-tooltip label="View" type="is-dark">
                        <b-button size="is-small"
                                  @click="setActiveItem(props.row)"
                                  icon-left="chevron-right">
                        </b-button>
                    </b-tooltip>


                </b-table-column>
            </template>

            <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>Nothing here.</p>
                    </div>
                </section>
            </template>

        </b-table>
    </div>
</template>

