<script src="./EditJs.js"></script>
<template>
    <div class="column" v-if="assets">

        <div class="card" v-if="is_content_loading">
            <Loader/>
        </div>

        <div class="columns" v-else-if="item">

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
                                <p  class="control">
                                    <b-button type="is-light"
                                              @click="reload"
                                              :loading="is_reload_btn_loading"
                                              icon-left="redo-alt">
                                    </b-button>
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="card-content">

                        <b-field labelPosition='on-border'
                                 label="Permalink">
                            <b-input placeholder="Permalink"
                                     v-model="item.permalink"
                                     maxlength="100"
                                     expanded></b-input>

                            <p class="control">
                                <b-button tag="a"
                                          target="_blank"
                                          :href="item.link_prefix+item.permalink"
                                          icon-left="external-link-alt">
                                </b-button>
                            </p>
                        </b-field>

                        <b-field labelPosition='on-border'
                                 label="Author">
                            <AutoCompleteUsers v-if="item.author_user"
                                               @onSelect="setAuthor"
                            :selected_value="item.author_user.name">
                            </AutoCompleteUsers>

                            <AutoCompleteUsers v-else @onSelect="setAuthor">
                            </AutoCompleteUsers>

                        </b-field>

                        <ContentFields :groups="item.content_form_groups"></ContentFields>
                        <TemplateFields :groups="item.template_form_groups"></TemplateFields>
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

                                        <!--<b-dropdown-item aria-role="listitem"
                                                         @click="setLocalAction('save-and-clone')">
                                            <b-icon icon="copy"></b-icon>
                                            Save & Clone
                                        </b-dropdown-item>

                                        <b-dropdown-item aria-role="listitem"
                                                         @click="setLocalAction('trash')">
                                            <b-icon icon="trash"></b-icon>
                                            Trash
                                        </b-dropdown-item>-->

                                    </b-dropdown>


                                </p>

                                <p class="control">
                                    <b-button type="is-light"
                                              @click="resetActiveItem()"
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

                        <b-field label="Status"
                                 v-if="assets.content_type.content_statuses
                                 && assets.content_type.content_statuses.length>0"
                                 :label-position="labelPosition">
                            <b-select v-model="item.status">
                                <option value="">Select a Status</option>
                                <option v-for="(status, index) in assets.content_type.content_statuses"
                                        :value="status"
                                >{{status}}</option>
                            </b-select>
                        </b-field>

                        <b-field label="Themes"
                                 :label-position="labelPosition">

                            <b-select v-model="item.vh_theme_id" @input="setActiveTheme">
                                <option value="">Select a Theme</option>
                                <option v-for="(theme, index) in assets.themes"
                                        :value="theme.id"
                                >{{theme.name}}</option>
                            </b-select>
                            <b-tooltip label="Sync Templates" type="is-dark">
                            <p class="control">

                                    <b-button type="is-light"
                                              @click="syncSeeds"
                                              :loading="theme_sync_loader"
                                              icon-left="sync-alt">
                                    </b-button>

                            </p>
                            </b-tooltip>

                        </b-field>



                        <b-field label="Templates"
                                 :label-position="labelPosition">


                            <b-select v-if="page && page.active_theme" v-model="item.vh_theme_template_id" @input="setActiveTemplate">
                                <option value="">Select a Template</option>
                                <option v-if="page.active_theme.templates"
                                        v-for="(template, index) in page.active_theme.templates"
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
