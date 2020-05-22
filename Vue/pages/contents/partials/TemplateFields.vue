<script src="./TemplateFieldsJs.js"></script>
<template>
    <div class="block is-marginless">

        <b-collapse :open="false" aria-id="template_fields">

            <div class="level has-padding-10" slot="trigger"
                 slot-scope="props"
                 aria-controls="template_fields">

                <div class="label-left">
                    <h4 class="title is-5">Template Fields</h4>
                    <h2 class="subtitle is-6">Manage content fields required for the template you have selected.</h2>
                </div>
                <div class="label-right is-hidden-mobile">
                    <b-button v-text="props.open ? 'Collapse' : 'Expand'">
                    </b-button>
                </div>

            </div>


            <div class="block has-margin-top-10 has-padding-10">

                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="block has-padding-10">

                            <div v-if="groups.length > 0"
                                 v-for="(group,index) in groups"
                                 :key="'content-fields-group-'+index"
                                 :aria-id="'content-fields-group-'+index">

                                <h4 class="title is-5">{{group.name}}</h4>
                                <hr/>



                                <div class="block has-margin-top-10 has-padding-10" v-if="group.fields.length>0"
                                     v-for="(field, f_index) in group.fields"
                                     :class="'field-type field-'+field.type.slug"
                                     :key="f_index">

                                    <ContentFieldAll :field_type="field.type"
                                                     :field_slug="field.type.slug"
                                                     :label="field.name"
                                                     :placeholder="field.name"
                                                     :labelPosition="labelPosition"
                                                     v-model="field.content"
                                                     @onInput=""
                                                     @onChange=""
                                                     @onBlur=""
                                                     @onFocus=""/>


                                </div>




                            </div>

                        </div>

                    </div>

                    <div class="column is-12">
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

