<script src="./TemplateFieldsJs.js"></script>
<template>
    <div class="block is-marginless">

        <b-collapse :open="false" aria-id="template_fields">

            <div class="level has-padding-top-10 has-padding-bottom-10" slot="trigger"
                 slot-scope="props"
                 aria-controls="template_fields">

                <div class="label-left">
                    <h4 class="title is-5">Template Fields</h4>
                    <h2 class="subtitle is-6">These fields required for the theme page template.</h2>
                </div>
                <div class="label-right is-hidden-mobile">
                    <b-button v-text="props.open ? 'Collapse' : 'Expand'">
                    </b-button>
                </div>

            </div>


            <div class="block has-margin-top-10 ">

                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="block">

                            <div v-if="groups.length > 0"
                                 v-for="(group,index) in groups"
                                 :key="'content-fields-group-'+index"
                                 :aria-id="'content-fields-group-'+index">

                                <h4 class="title is-5">{{group.name}}</h4>


                                <div class="block has-margin-top-10 has-padding-bottom-10" v-if="group.fields.length>0"
                                     v-for="(field, f_index) in group.fields"
                                     :class="'field-type field-'+field.type.slug"
                                     :key="f_index">



                                    <div class="columns">
                                        <div class="column ">
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
                                        <div class="column is-1">
                                            <b-button icon-left="copy"
                                                      @click="copyCode(group, field)">
                                            </b-button>
                                        </div>
                                    </div>


                                </div>




                            </div>

                        </div>

                    </div>



                </div>

            </div>


        </b-collapse>

        <hr class="is-marginless"/>
    </div>
</template>

