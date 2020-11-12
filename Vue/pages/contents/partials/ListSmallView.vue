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
                <b-table-column field="id" label="ID" width="5px">
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column field="name" label="Name">
                    <b-tooltip label="Edit" type="is-dark">
                        <a @click="toEdit(props.row)">
                            {{$vaah.limitString(props.row.name, 15)}}
                        </a>
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

