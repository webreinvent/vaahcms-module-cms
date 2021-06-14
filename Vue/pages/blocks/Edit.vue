<script src="./EditJs.js"></script>
<template>

    <div class="column" v-if="page.assets">

        <div class="card" v-if="is_content_loading">
            <Loader/>
        </div>

        <div class="columns" v-else-if="item">
            <div class="column">

                <div class="card has-margin-bottom-15">
                    <div class="card-header">

                        <div class="card-header-title">
                            <span>Content</span>
                        </div>

                    </div>

                    <div class="card-content">

                        <ContentFieldAll
                                        field_slug="editor"
                                         :labelPosition="labelPosition"
                                         v-model="item.content"
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
                            <span>{{$vaah.limitString(title, 15)}}</span>
                        </div>


                        <div class="card-header-buttons">

                            <div class="field has-addons is-pulled-right">
                                <p class="control">
                                    <b-button @click="$vaah.copy(item.id)"  type="is-light">
                                        <small><b>#{{item.id}}</b></small>
                                    </b-button>
                                </p>

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
                        <div class="block">

                            <b-field label="Name" :label-position="labelPosition">
                                <b-input v-model="item.name"></b-input>
                            </b-field>

                            <b-field label="Slug" :label-position="labelPosition">
                                <b-input v-model="item.slug"></b-input>
                            </b-field>

                            <b-field label="Themes"
                                     :label-position="labelPosition">

                                <b-select v-model="item.vh_theme_id" @input="setActiveTheme">
                                    <option value="">Select a Theme</option>
                                    <option v-for="(theme, index) in assets.themes"
                                            :value="theme.id"
                                    >{{theme.name}}</option>
                                </b-select>


                            </b-field>

                            <b-field label="Locations"
                                     v-if="page.active_theme"
                                     :label-position="labelPosition">
                                <b-select v-model="item.vh_theme_location_id"
                                          expanded>
                                    <option value="">Select a Location</option>
                                    <option v-for="(location, index) in page.active_theme.locations"
                                            :value="location.id"
                                            v-if="location.type === 'block'"
                                    >{{location.name}}</option>
                                </b-select>
                                <b-tooltip :active="!!item.vh_theme_location_id"
                                           label="Copy Code" type="is-dark">
                                    <p class="control">
                                        <b-button icon-left="copy"
                                                  :disabled="!item.vh_theme_location_id"
                                                  class="button"
                                                  @click="copyCode(
                                          page.active_theme.locations,
                                          item.vh_theme_location_id
                                          )">
                                        </b-button>
                                    </p>
                                </b-tooltip>
                            </b-field>

                            <b-field label="Sort" :label-position="labelPosition">
                                <b-input type="number" v-model="item.sort" ></b-input>
                            </b-field>

                            <b-field label="Is Published" :label-position="labelPosition">
                                <b-radio-button v-model="item.is_published"
                                                :native-value=1>
                                    <span>Yes</span>
                                </b-radio-button>

                                <b-radio-button type="is-danger"
                                                v-model="item.is_published"
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


