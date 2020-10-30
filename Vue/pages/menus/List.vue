<script src="./ListJs.js"></script>
<template>
    <div class="form-page-v1-layout">

        <div class="container" v-if="assets">

            <div class="columns">

                <div class="column is-3">
                    <div class="card">


                        <!--header-->
                        <header class="card-header">

                            <div class="card-header-title">
                                <span>Choose Menu</span>
                            </div>


                        </header>
                        <!--/header-->

                        <!--content-->
                        <div class="card-content" >

                            <b-field label="Themes"
                                     :label-position="labelPosition">
                                <b-select v-model="page.filters.vh_theme_id"
                                          expanded
                                          @input="setActiveTheme">
                                    <option value="">Select a Theme</option>
                                    <option v-if="assets.themes"
                                            v-for="(theme, index) in assets.themes"
                                            :value="theme.id"
                                    >{{theme.name}}</option>
                                </b-select>
                            </b-field>

                            <b-field label="Locations"
                                     v-if="page.active_theme"
                                     :label-position="labelPosition">
                                <b-select v-model="page.filters.vh_theme_location_id"
                                          expanded
                                          @input="setActiveLocation">
                                    <option value="">Select a Location</option>
                                    <option v-for="(location, index) in page.active_theme.locations"
                                            :value="location.id"
                                    >{{location.name}}</option>
                                </b-select>
                            </b-field>

                            <b-field label="Menus"
                                     v-if="page.filters.vh_theme_id && page.active_location && page.active_location.menus.length > 0"
                                     :label-position="labelPosition">
                                <b-select v-model="page.filters.vh_menu_id"
                                          expanded
                                          @input="setActiveMenu">
                                    <option value="">Select a Menu</option>
                                    <option v-for="(menu, index) in page.active_location.menus"
                                            :value="menu.id"
                                    >{{menu.name}}</option>
                                </b-select>
                            </b-field>

                            <b-field label="Create New Menu"
                                     v-if="page.active_theme && page.active_location"
                                     :label-position="labelPosition">
                                <b-input v-model="page.new_item.name"
                                         expanded>
                                </b-input>
                                <p class="control">
                                    <b-button @click="create">Create</b-button>
                                </p>
                            </b-field>

                        </div>
                        <!--/content-->
                </div>
                </div>
                <div class="column ">

                    <router-view></router-view>

                </div>

            </div>


        </div>

    </div>
</template>


