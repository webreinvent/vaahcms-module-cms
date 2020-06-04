<script src="./EditJs.js"></script>
<template>
    <div class="column" v-if="assets && item && groups">

        <div class="columns">

            <div class="column">

                <div class="card has-margin-bottom-15">
                    <div class="card-header">

                        <div class="card-header-title">
                            <span>Content Structure</span>
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
                        <ContentFields :groups="groups"></ContentFields>
                        <TemplateFields></TemplateFields>
                        <CustomFields></CustomFields>
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


                                    <b-dropdown aria-role="list" position="is-bottom-left">
                                        <button class="button is-light"
                                                slot="trigger">
                                            <b-icon icon="caret-down"></b-icon>
                                        </button>

                                        <b-dropdown-item aria-role="listitem"
                                                         @click="setLocalAction('save-and-close')">
                                            <b-icon icon="check"></b-icon>
                                            Save & Close
                                        </b-dropdown-item>

                                        <b-dropdown-item aria-role="listitem"
                                                         @click="setLocalAction('save-and-new')">
                                            <b-icon icon="plus"></b-icon>
                                            Save & New
                                        </b-dropdown-item>

                                        <b-dropdown-item aria-role="listitem"
                                                         @click="setLocalAction('save-and-clone')">
                                            <b-icon icon="copy"></b-icon>
                                            Save & Clone
                                        </b-dropdown-item>

                                        <b-dropdown-item aria-role="listitem"
                                                         @click="setLocalAction('trash')">
                                            <b-icon icon="trash"></b-icon>
                                            Trash
                                        </b-dropdown-item>

                                    </b-dropdown>


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
