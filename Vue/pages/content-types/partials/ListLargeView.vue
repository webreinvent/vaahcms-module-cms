<script src="./ListLargeViewJs.js"></script>
<template>
    <div>
        <b-table checkbox-position="left">

            <template >
                <b-table-column field="id" label="ID" width="40" numeric>
                    ID
                </b-table-column>

                <b-table-column field="actions" label=""
                                width="40">

                    <b-tooltip label="View" type="is-dark">
                        view
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

