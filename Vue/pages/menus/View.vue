<script src="./ViewJs.js"></script>
<template>
    <div class="container" v-if="page && assets && page.active_menu ">


        <div class="columns">

            <div class="column">
                <div class="card">

                    <!--header-->
                    <header class="card-header">

                        <div class="card-header-title">
                            <span>{{page.active_menu.name}}</span>
                        </div>

                        <div class="card-header-buttons">
                            <div class="field has-addons is-pulled-right">

                                <p  class="control">
                                    <b-button type="is-light"
                                              @click="store"
                                              icon-left="save">
                                        Save
                                    </b-button>
                                </p>

                                <p  class="control">
                                    <b-tooltip label="Settings" type="is-dark">
                                        <b-button type="is-light"
                                                  icon-left="cog">
                                        </b-button>
                                    </b-tooltip>

                                </p>

                                <p class="control">
                                    <b-tooltip label="Delete" type="is-dark">
                                        <b-button type="is-light"
                                                  icon-left="trash">
                                        </b-button>
                                    </b-tooltip>
                                </p>

                            </div>
                        </div>

                    </header>
                    <!--/header-->

                    <!--content-->
                    <div class="card-content" >

                        <!--menu-settings-->
                        <div>
                            <b-field label="Name"
                                     :label-position="labelPosition">
                                <b-input v-model="page.active_menu.name"
                                         expanded>
                                </b-input>
                            </b-field>


                            <b-field label="Menu ID"
                                     :label-position="labelPosition">
                                <b-input v-model="page.active_menu.attr_id"
                                         expanded>
                                </b-input>
                            </b-field>

                            <b-field label="Menu Class"
                                     :label-position="labelPosition">
                                <b-input v-model="page.active_menu.attr_class"
                                         expanded>
                                </b-input>
                            </b-field>

                        </div>
                        <!--/menu-settings-->

                        <hr/>

                        <!--menu-items-->
                        <div class="draggable" >
                            <draggable class="dropzone" :list="page.active_menu.items"
                                       :group="{name:'menu_items'}">

                                <div v-if="page.active_menu.items"
                                     v-for="(item, f_index) in page.active_menu.items"
                                     :key="f_index">
                                    <div class="dropzone-field">
                                        <b-field class="is-marginless" >

                                            <p class="control drag">
                                                <span class="button is-static">:::</span>
                                            </p>

                                            <p class="control" v-if="item.content">
                                                <span class="button dropzone-field-label is-static">{{item.content.name}}</span>
                                            </p>

                                            <b-input v-model="item.name" expanded placeholder="Menu Name"></b-input>

                                        </b-field>

                                    </div>
                                </div>

                            </draggable>
                        </div>
                        <!--/menu-items-->


                    </div>
                    <!--/content-->





                </div>
            </div>


            <div class="column is-4">

                <div class="card">


                    <!--header-->
                    <header class="card-header">

                        <div class="card-header-title">
                            <span>Content</span>
                        </div>


                    </header>
                    <!--/header-->

                    <!--content-->
                    <div class="card-content" v-if="page.content_list">

                        <b-input expanded placeholder="Search content"></b-input>

                        <hr/>

                        <div class="draggable" v-if="page.content_list">
                            <draggable :list="page.content_list"
                                       :clone="cloneField"
                                       :group="{name:'menu_items', pull:'clone', put:false}"
                            >

                                <div v-for="(content, index) in page.content_list"
                                     :key="index">

                                    <b-field class="has-margin-bottom-5" expanded>

                                        <p class="control drag">
                                            <span class="button is-static">:::</span>
                                        </p>

                                        <p class="control drag">
                                            <span class="button is-static">{{content.name}}</span>
                                        </p>



                                    </b-field>

                                </div>

                            </draggable>
                        </div>



                    </div>
                    <!--/content-->





                </div>

            </div>


        </div>

    </div>

</template>


