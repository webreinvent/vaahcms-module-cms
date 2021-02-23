<script src="./ListSmallViewJs.js"></script>
<template>
    <div>

        <b-table :data="page.list_is_empty ? [] : page.list.data"
                 :hoverable="true"
                 :row-class="setRowClass">

            <template  >

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

