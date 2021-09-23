<script src="./CreateJs.js"></script>
<template>
    <div class="column" v-if="assets && page.active_theme">

        <div class="columns">
            <div class="column">

                <div class="card has-margin-bottom-15">
                    <div class="card-header">

                        <div class="card-header-title">
                            <span>Content</span>
                        </div>

                        <div class="card-header-buttons">
                            <div class="field has-addons is-pulled-right">
                                <p  class="control">
                                    <b-button type="is-light"
                                              @click="expandAll()"
                                              icon-left="angle-double-down">
                                        Expand All
                                    </b-button>
                                </p>
                                <p  class="control">
                                    <b-button type="is-light"
                                              @click="collapseAll()"
                                              icon-left="angle-double-up">
                                        Collapse All
                                    </b-button>
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="card-content">

                        <b-field labelPosition='on-border'
                                 label="Permalink">
                            <b-input placeholder="Permalink"
                                     v-model="new_item.permalink"
                                     maxlength="100"
                                     expanded></b-input>
                        </b-field>

                        <b-field labelPosition='on-border'
                                 label="Author">
                            <AutoCompleteUsers @onSelect="setAuthor" >
                            </AutoCompleteUsers>
                        </b-field>

                        <ContentFields :groups="assets.content_type.form_groups"></ContentFields>
                        <TemplateFields :groups="page.active_template_groups"></TemplateFields>
                    </div>


                </div>


            </div>
            <div class="column is-4">

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

                        <b-field label="Status"
                                 v-if="assets.content_type.content_statuses
                                 && assets.content_type.content_statuses.length>0"
                                 :label-position="labelPosition">
                            <b-select v-model="new_item.status">
                                <option value="">Select a Status</option>
                                <option v-for="(status, index) in assets.content_type.content_statuses"
                                        :value="status"
                                >{{status}}</option>
                            </b-select>
                        </b-field>

                        <b-field label="Themes"
                                 :label-position="labelPosition">

                            <b-select v-model="new_item.vh_theme_id">
                                <option value="">Select a Theme</option>
                                <option v-for="(theme, index) in assets.themes"
                                        :value="theme.id"
                                >{{theme.name}}</option>
                            </b-select>


                        </b-field>

                        <b-field label="Templates"
                                 :label-position="labelPosition">

                            <b-select v-model="new_item.vh_theme_template_id"
                                      placeholder="Select a Template"
                                      @input="setActiveTemplate">
                                <option value="">Select a Template</option>
                                <option v-for="(template, index) in page.active_theme.templates"
                                        :value="template.id"
                                >{{template.name}}</option>
                            </b-select>

                        </b-field>


                    </div>
                    <!--/content-->





                </div>

            </div>
        </div>

    </div>
</template>
