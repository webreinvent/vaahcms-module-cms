<script src="./ListLargeViewJs.js"></script>
<template>
    <div>
        <b-table :data="page.list_is_empty ? [] : page.list.data"
                 :checkable="true"
                 :checked-rows.sync="page.bulk_action.selected_items"
                 checkbox-position="left"
                 :hoverable="true"
                 :row-class="setRowClass">

            <template>
                <b-table-column v-slot="props" width="85" field="id" label="ID"   >
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column v-slot="props" field="name" label="Name">
                    {{ props.row.name }}
                </b-table-column>

                <b-table-column v-slot="props" width="170" field="permalink" label="Permalink">
                    <b-tooltip :label="props.row.permalink" type="is-dark">
                        {{$vaah.limitString(props.row.permalink, 22)}}
                    </b-tooltip>
                </b-table-column>

                <b-table-column v-slot="props" width="150" field="status" label="Status">


                    <div v-if="props.row.deleted_at">

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

                    </div>
                    <div v-else>
                        <b-tooltip label="Change Status" type="is-dark">

                            <b-dropdown aria-role="list" size="is-small" v-model="props.row.status"
                                        @input="changeStatus(props.row.id,props.row.status)">

                                <p v-if="props.row.status === 'published'"
                                   class="tag is-success"
                                   slot="trigger"
                                   role="button" slot-scope="{ active }">
                                    <span>{{ props.row.status }}</span>
                                    <b-icon :icon="active? 'chevron-up' : 'chevron-down'"></b-icon>
                                </p>

                                <p v-else
                                   class="tag is-dark"
                                   slot="trigger"
                                   role="button" slot-scope="{ active }">
                                    <span>{{ props.row.status }}</span>
                                    <b-icon :icon="active? 'chevron-up' : 'chevron-down'"></b-icon>
                                </p>

                                <span v-for="item in page.status_list">
                                <b-dropdown-item :value="item" aria-role="listitem">{{item}}</b-dropdown-item>
                            </span >

                            </b-dropdown>
                        </b-tooltip>
                    </div>



                </b-table-column>


                <b-table-column v-slot="props" width="150px" field="updated_at" label="Updated At">
                    <span>
                        {{$vaah.fromNow(props.row.updated_at)}}
                    </span>
                </b-table-column>

                <b-table-column v-slot="props" field="actions" label=""
                                width="120">

                    <b-tooltip label="Edit" type="is-dark">
                        <b-button size="is-small"
                                  @click="toEdit(props.row)"
                                  icon-left="pencil-alt">
                        </b-button>
                    </b-tooltip>

                    <b-tooltip label="Details" type="is-dark">
                        <b-button size="is-small"
                                  @click="setActiveItem(props.row)"
                                  icon-left="chevron-right">
                        </b-button>
                    </b-tooltip>

                    <b-tooltip label="View" type="is-dark">
                        <b-button size="is-small"
                                  tag="a"
                                  target="_blank"
                                  :href="props.row.link_prefix+props.row.permalink"
                                  icon-left="external-link-alt">
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

