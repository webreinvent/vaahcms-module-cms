<script src="./EditJs.js"></script>
<template>
    <div class="column" v-if="assets && item && groups">

        <div class="columns">
            <div class="column">




                <div class="card has-margin-bottom-15" v-if="groups.length > 0"
                     v-for="(group,index) in groups">

                    <div class="card-header">

                        <div class="card-header-title">
                            <span>{{group.name}}</span>
                        </div>

                    </div>

                    <div class="card-content">

                        <div class="content-structure-fields">

                            <div v-if="group.fields.length>0"
                             v-for="(field, f_index) in group.fields"
                             :class="'field-type field-'+field.type.slug"
                             :key="f_index">

                                {{field.name}}
                                <hr/>

                                <ContentFieldAll :field_type="field.type"
                                                 :field_slug="field.type.slug"
                                                 :label="field.name"
                                                 :placeholder="field.name"
                                                 :labelPosition="labelPosition"
                                                 :upload_url="root.assets.urls.upload"
                                                 :currency_options="assets.currency_codes"
                                                 :app_url="root.assets.urls.public"
                                                 v-model="field.content"
                                                 @onInput=""
                                                 @onChange=""
                                                 @onBlur=""
                                                 @onFocus=""/>

                                <hr/>

                                {{field.content}}


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
                            <span>Edit</span>
                        </div>


                        <div class="card-header-buttons">

                            <div class="field has-addons is-pulled-right">

                                <p class="control">
                                    <b-button icon-left="save"
                                              type="is-light"
                                              :loading="is_btn_loading"
                                              @click="store()">
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
                            <b-input v-model="item.name"></b-input>
                        </b-field>


                    </div>
                    <!--/content-->


                </div>

            </div>
        </div>


    </div>
</template>
