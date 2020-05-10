<script src="./CreateJs.js"></script>
<template>
    <div class="column" v-if="page.assets">

        <div class="columns">
            <div class="column">


                <div class="card has-margin-bottom-15" v-if="assets.groups.length > 0"
                     v-for="(group,index) in assets.groups">

                    <div class="card-header">

                        <div class="card-header-title">
                            <span>{{group.name}}</span>
                        </div>

                    </div>

                    <div class="card-content">

                        <div class="content-structure-fields">

                            <div v-if="group.fields.length>0"
                                 v-for="(field, f_index) in group.fields"
                                 :class="'field-type '+field.type.slug"
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


            </div>
            <div class="column is-3">

                <div class="card">

                    <!--header-->
                    <header class="card-header">

                        <div class="card-header-title">
                            <span>Create {{assets.content_type.name}}</span>
                        </div>


                        <div class="card-header-buttons">

                            <div class="field has-addons is-pulled-right">

                                <p class="control">
                                    <b-button icon-left="save"
                                              type="is-light"
                                              :loading="is_btn_loading"
                                              @click="create()">
                                        Save
                                    </b-button>
                                </p>

                                <p class="control">
                                    <b-button tag="router-link"
                                              type="is-light"
                                              :to="{name: 'contents.list', params:{slug: page.content_slug}}"
                                              icon-left="times">
                                    </b-button>
                                </p>



                            </div>


                        </div>

                    </header>
                    <!--/header-->

                    <!--content-->
                    <div class="card-content">

                        <b-field label="Name" :label-position="labelPosition">
                            <b-input v-model="new_item.name"></b-input>
                        </b-field>


                    </div>
                    <!--/content-->





                </div>

            </div>
        </div>







    </div>
</template>
