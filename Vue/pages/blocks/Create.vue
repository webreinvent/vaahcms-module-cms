<script src="./CreateJs.js"></script>
<template>

    <div class="column" v-if="page.assets">

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
                                              @click="is_textarea_disable = true"
                                              :disabled="is_textarea_disable">
                                        TextArea
                                    </b-button>
                                </p>
                                <p  class="control">
                                    <b-button type="is-light"
                                              @click="is_textarea_disable = false"
                                              :disabled="!is_textarea_disable">
                                        Editor
                                    </b-button>
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="card-content">

                        <ContentFieldAll v-if="is_textarea_disable"
                                         field_slug="textarea"
                                         :labelPosition="labelPosition"
                                         v-model="new_item.content"
                                         @onInput=""
                                         @onChange=""
                                         @onBlur=""
                                         @onFocus="">
                        </ContentFieldAll>

                        <ContentFieldAll v-else
                                         field_slug="editor"
                                         :labelPosition="labelPosition"
                                         v-model="new_item.content"
                                         @onInput=""
                                         @onChange=""
                                         @onBlur=""
                                         @onFocus="">
                        </ContentFieldAll>

                    </div>


                </div>


            </div>



            <div class="column is-4">

                <div class="card">

                    <!--header-->
                    <header class="card-header">

                        <div class="card-header-title">
                            <span>Create New Block</span>
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
                                              :to="{name: 'blocks.list'}"
                                              icon-left="times">
                                    </b-button>
                                </p>



                            </div>


                        </div>

                    </header>
                    <!--/header-->

                    <!--content-->
                    <div class="card-content">
                        <div class="block">

                            <b-field label="Name" :label-position="labelPosition">
                                <b-input v-model="new_item.name"></b-input>
                            </b-field>

                            <b-field label="Slug" :label-position="labelPosition">
                                <b-input v-model="new_item.slug"></b-input>
                            </b-field>

                            <b-field label="Themes"
                                     :label-position="labelPosition">

                                <b-select v-model="new_item.vh_theme_id" @input="setActiveTheme">
                                    <option value="">Select a Theme</option>
                                    <option v-for="(theme, index) in assets.themes"
                                            :value="theme.id"
                                    >{{theme.name}}</option>
                                </b-select>


                            </b-field>

                            <b-field label="Locations"
                                     v-if="page.active_theme"
                                     :label-position="labelPosition">
                                <b-select v-model="new_item.vh_theme_location_id"
                                          expanded>
                                    <option value="">Select a Location</option>
                                    <option v-for="(location, index) in page.active_theme.locations"
                                            :value="location.id"
                                    >{{location.name}}</option>
                                </b-select>
                            </b-field>

                            <b-field label="Is Published" :label-position="labelPosition">
                                <b-radio-button v-model="new_item.is_published"
                                                :native-value=1>
                                    <span>Yes</span>
                                </b-radio-button>

                                <b-radio-button type="is-danger"
                                                v-model="new_item.is_published"
                                                :native-value=0>
                                    <span>No</span>
                                </b-radio-button>
                            </b-field>

                        </div>
                    </div>
                    <!--/content-->





                </div>




            </div>
        </div>
    </div>

</template>
