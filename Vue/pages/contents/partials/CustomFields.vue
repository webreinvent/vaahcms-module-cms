<script src="./CustomFieldsJs.js"></script>
<template>
    <div class="block is-marginless">

        <b-collapse :open="false" aria-id="custom_fields">

            <div class="level has-padding-10" slot="trigger"
                 slot-scope="props"
                 aria-controls="custom_fields">

                <div class="label-left">
                    <h4 class="title is-5">Custom Fields</h4>
                    <h2 class="subtitle is-6">After a successful password update, you will be redirected to the login
                        page where you can log in with your new password.</h2>
                </div>
                <div class="label-right is-hidden-mobile">
                    <b-button v-text="props.open ? 'Collapse' : 'Expand'">
                    </b-button>
                </div>

            </div>


            <div class="block has-margin-top-10 has-padding-10">

                <div class="columns is-multiline">
                    <div class="column is-half">
                        <div class="block has-padding-10">

                            Content Here

                        </div>

                    </div>

                    <div class="column">
                        <div class="block has-padding-left-10 has-padding-right-10">
                            <b-button type="is-primary"  >Save Settings</b-button>
                        </div>
                    </div>

                </div>

            </div>
        </b-collapse>

        <hr class="is-marginless"/>
    </div>
</template>

