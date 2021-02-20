<script src="./CreateJs.js"></script>
<template>
    <div class="column" v-if="page.assets">

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

                        <b-select v-model="new_item.vh_theme_id">
                            <option value="">Select a Theme</option>
                            <option v-for="(theme, index) in assets.themes"
                                    :value="theme.id"
                            >{{theme.name}}</option>
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
</template>
