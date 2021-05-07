<script src="./ViewJs.js"></script>
<template>
    <div class="container" v-if="page && assets && page.active_menu ">


        <div class="columns">

            <div class="column">
                <div class="card">

                    <!--header-->
                    <header class="card-header">

                        <div class="card-header-title">
                            <span>{{$vaah.limitString(page.active_menu.name, 25)}}</span>
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

                                <b-tooltip label="Settings" type="is-dark">
                                    <p  class="control">
                                        <b-button type="is-light"
                                                  @click="showMenuSettings"
                                                  icon-left="cog">
                                        </b-button>
                                    </p>
                                </b-tooltip>

                                <b-tooltip label="Delete" type="is-dark">
                                    <p class="control">
                                        <b-button type="is-light"
                                                  @click="deleteItem"
                                                  icon-left="trash">
                                        </b-button>
                                    </p>
                                </b-tooltip>

                            </div>
                        </div>

                    </header>
                    <!--/header-->

                    <!--content-->
                    <div class="card-content" >

                        <!--menu-settings-->
                        <div class="menu-settings hide">
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
                        <div v-if="Array.isArray(page.active_menu_items)" class="draggable" >
                            <nested-draggable :items="page.active_menu_items"></nested-draggable>
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
                    <div class="card-content is-paddingless" v-if="page.content_list">


                        <b-collapse :open="true" aria-id="menu_type_content">

                            <div class="level has-padding-10" slot="trigger"
                                 slot-scope="props"
                                 aria-controls="menu_type_content">

                                <div class="label-left">
                                    <h4 class="title is-6">Contents</h4>
                                </div>

                                <div class="label-right is-hidden-mobile">
                                    <b-button v-text="props.open ? 'Collapse' : 'Expand'">
                                    </b-button>
                                </div>

                            </div>


                            <div class="block has-margin-top-10 has-padding-10">
                                <b-input v-model="page.query_string.q"
                                         expanded
                                         @input="delayedSearch"
                                         placeholder="Search content"></b-input>

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
                                            <span class="button is-static">
                                                <b class="has-margin-right-5">#{{content.id}}</b>
                                                {{$vaah.limitString(content.name, 15)}}
                                            </span>
                                                </p>



                                            </b-field>

                                        </div>

                                    </draggable>
                                </div>

                            </div>

                        </b-collapse>


                        <b-collapse :open="false" aria-id="menu_type_custom">

                            <div class="level has-padding-10" slot="trigger"
                                 slot-scope="props"
                                 aria-controls="menu_type_content">

                                <div class="label-left">
                                    <h4 class="title is-6">Custom</h4>
                                </div>

                                <div class="label-right is-hidden-mobile">
                                    <b-button v-text="props.open ? 'Collapse' : 'Expand'">
                                    </b-button>
                                </div>

                            </div>


                            <div class="block has-margin-top-10 has-padding-10">

                                <div class="draggable" v-if="page.menu_types">
                                    <draggable :list="page.menu_types"
                                               :clone="customCloneField"
                                               :group="{name:'menu_items', pull:'clone', put:false}">

                                        <div v-for="(menu_type, index) in page.menu_types"
                                             :key="index">

                                            <b-field class="has-margin-bottom-5" expanded>

                                                <p class="control drag">
                                                    <span class="button is-static">:::</span>
                                                </p>

                                                <p class="control drag">
                                                    <span class="button is-static">
                                                        {{menu_type.name}}
                                                    </span>
                                                </p>



                                            </b-field>

                                        </div>

                                    </draggable>
                                </div>

                            </div>

                        </b-collapse>




                    </div>
                    <!--/content-->





                </div>

            </div>


        </div>

    </div>

</template>


