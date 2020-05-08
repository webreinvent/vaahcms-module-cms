<script src="./CreateJs.js"></script>
<template>
    <div class="column" v-if="page.assets">

        <div class="columns">
            <div class="column">

                <div class="card">

                    <!--header-->
                    <header class="card-header">

                        <div class="card-header-title">
                            <span>Fill Content</span>
                        </div>

                    </header>
                    <!--/header-->

                    <!--content-->
                    <div class="card-content">

                        Content


                    </div>
                    <!--/content-->





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

                        asdf


                    </div>
                    <!--/content-->





                </div>

            </div>
        </div>







    </div>
</template>
