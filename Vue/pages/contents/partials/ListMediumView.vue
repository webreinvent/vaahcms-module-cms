<script src="./ListMediumViewJs.js"></script>
<template>
    <div>
        <b-table :data="page.list_is_empty ? [] : page.list.data"
                 :checkable="true"
                 :checked-rows.sync="page.bulk_action.selected_items"
                 checkbox-position="left"
                 :hoverable="true"
                 :row-class="setRowClass">

            <template  >
                <b-table-column v-slot="props" width="85" field="id" label="ID" >
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column v-slot="props" field="name" label="Name">
                    {{ props.row.name }}
                </b-table-column>

                <b-table-column v-slot="props" width="100px" field="updated_at" label="Updated At">
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

