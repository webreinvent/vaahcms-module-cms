<script src="./ListLargeViewJs.js"></script>
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

                <b-table-column v-if="props.row.deleted_at" width="150px" field="status" label="Status">

                    <b-dropdown aria-role="list" disabled size="is-small" v-model="props.row.status"
                                    @input="changeStatus(props.row.id,props.row.status)">

                            <p v-if="props.row.status === 'published'"
                                class="tag is-success"
                                slot="trigger"
                                role="button" slot-scope="{ active }">
                                <span>{{ props.row.status }}</span>

                            </p>

                            <p v-else
                                class="tag is-dark"
                                slot="trigger"
                                role="button" slot-scope="{ active }">
                                <span>{{ props.row.status }}</span>

                            </p>

                            <span v-for="item in page.status_list">
                                <b-dropdown-item :value="item" aria-role="listitem">{{item}}</b-dropdown-item>
                            </span >

                        </b-dropdown>

                </b-table-column>


                <b-table-column v-else width="150px" field="status" label="Status">
                    <b-tooltip label="Change Status" type="is-dark">

                        <b-dropdown aria-role="list" size="is-small" v-model="props.row.status"
                                    @input="changeStatus(props.row.id,props.row.status)">

                            <p v-if="props.row.status === 'published'"
                                class="tag is-success"
                                slot="trigger"
                                role="button" slot-scope="{ active }">
                                <span>{{ props.row.status }}</span>
                                <b-icon icon="ellipsis-v"></b-icon>
                            </p>

                            <p v-else
                                class="tag is-dark"
                                slot="trigger"
                                role="button" slot-scope="{ active }">
                                <span>{{ props.row.status }}</span>
                                <b-icon icon="ellipsis-v"></b-icon>
                            </p>

                            <span v-for="item in page.status_list">
                                <b-dropdown-item :value="item" aria-role="listitem">{{item}}</b-dropdown-item>
                            </span >

                        </b-dropdown>
                    </b-tooltip>

                </b-table-column>

                <b-table-column width="150px" field="updated_at" label="Updated At">
                    <span>
                        {{$vaah.fromNow(props.row.updated_at)}}
                    </span>
                </b-table-column>

                <b-table-column field="actions" label=""
                                width="80">

                    <b-tooltip label="Edit" type="is-dark">
                        <b-button size="is-small"
                                  @click="toEdit(props.row)"
                                  icon-left="pencil-alt">
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

