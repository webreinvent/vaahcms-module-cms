<script src="./ListSmallViewJs.js"></script>
<template>
    <div>
        <b-table :data="page.list_is_empty ? [] : page.list.data"
                 :hoverable="true"
                 :row-class="setRowClass">

            <template  >

                <b-table-column v-slot="props" field="name" label="Name">
                    <b-tooltip :label="props.row.name" type="is-dark">
                        <a @click="toEdit(props.row)">
                            {{$vaah.limitString(props.row.name, 22)}}
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

