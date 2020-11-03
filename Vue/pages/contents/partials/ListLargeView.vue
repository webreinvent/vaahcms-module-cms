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

                <b-table-column field="status" label="Status">
                    <span v-if="selected_id !== props.row.id">
                        <b-button v-if="props.row.status === 'published'" rounded size="is-small"
                                  @click="selected_id = props.row.id"
                                  type="is-success">
                        {{ props.row.status }}
                    </b-button>
                    <b-button v-else rounded size="is-small"
                              @click="selected_id = props.row.id"
                              type="is-dark">
                        {{ props.row.status }}
                    </b-button>
                    </span>

                    <b-select placeholder="- Select a filter -"
                              v-if="selected_id === props.row.id"
                              v-model="props.row.status" @input="changeStatus(props.row.id,props.row.status)">

                        <option v-for="item in page.status_list" :value=item>
                            {{item}}
                        </option>

                    </b-select>

                </b-table-column>

                <b-table-column field="updated_at" label="Last Updated At">
                    <span>
                        {{$vaah.fromNow(props.row.updated_at)}}
                    </span>
                </b-table-column>

                <b-table-column field="actions" label=""
                                width="80">

                    <b-tooltip label="Edit" type="is-dark">
                        <b-button size="is-small"
                                  @click="toEdit(props.row)"
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

