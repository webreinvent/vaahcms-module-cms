<script src="./ListLargeViewJs.js"></script>
<template>
    <div>
        <b-table :data="page.list_is_empty ? [] : page.list.data"
                 :checkable="true"
                 :checked-rows.sync="page.bulk_action.selected_items"
                 checkbox-position="left"
                 :hoverable="true"
                 :row-class="setRowClass">

            <template  >
                <b-table-column v-slot="props" field="id" label="ID" width="85" >
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column v-slot="props" field="name" label="Name">

                    <b-tooltip label="Copy Block" type="is-dark">
                        <vh-copy class="text-copyable"
                                 :label="props.row.name"
                                 @copied="copyCode(props.row)"
                        >
                            <b-icon icon="copy"></b-icon>
                        </vh-copy>

                    </b-tooltip>
                </b-table-column>



                <b-table-column v-slot="props" width="250"
                                field="theme" label="Theme / Location">

                    <b-taglist attached>
                        <b-tag v-if="props.row.theme" type="is-dark">
                            {{props.row.theme.name}}
                        </b-tag>
                        <b-tag v-if="props.row.theme_location" type="is-info">
                            {{props.row.theme_location.name}}
                        </b-tag>
                        <b-tag v-if="props.row.theme_location||props.row.theme" type="is-default">
                            <b-tooltip label="Copy Location Blocks" type="is-dark">
                                <vh-copy class="text-copyable"
                                         @copied="copyCode(props.row,true)"
                                >
                                    <b-icon icon="copy"></b-icon>
                                </vh-copy>
                            </b-tooltip>
                        </b-tag>
                    </b-taglist>


                </b-table-column>

                <b-table-column v-slot="props" width="100"
                                field="is_published" label="Is Published">

                    <div v-if="props.row.deleted_at">

                        <b-button v-if="props.row.is_published === 1"
                                  disabled rounded size="is-small"
                                  type="is-success">
                            Yes
                        </b-button>
                        <b-button v-else rounded size="is-small" disabled type="is-danger">
                            No
                        </b-button>

                    </div>

                    <div v-else>
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
                    </div>


                </b-table-column>


                <b-table-column v-slot="props" width="150px" field="updated_at" label="Updated At">
                    <span>
                        {{$vaah.fromNow(props.row.updated_at)}}
                    </span>
                </b-table-column>


                <b-table-column v-slot="props" field="actions" label=""
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

