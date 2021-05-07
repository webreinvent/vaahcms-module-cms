<template>
    <draggable class="dropzone-nested" tag="ul" :list="items" :group="{ name: 'menu_items' }">
        <li v-for="el in items" :key="el.id">
            <div class="dropzone-field">

                <b-field class="is-marginless" >

                    <p class="control drag">
                        <span class="button is-static">:::</span>
                    </p>

                    <p class="control" v-if="el.content">
                        <span class="button dropzone-field-label is-static">{{$vaah.limitString(el.content.name, 15)}}</span>
                    </p>

                    <b-input v-model="el.name" expanded placeholder="Menu Name"></b-input>

                    <b-tooltip label="Set as Home Page" type="is-dark">
                        <p  class="control">
                            <b-button v-if="el.is_home" type="is-success"
                                      @click="setAsHomePage(el)"
                                      icon-left="house-user">
                            </b-button>

                            <b-button v-else type="is-light"
                                      @click="setAsHomePage(el)"
                                      icon-left="house-user">
                            </b-button>
                        </p>
                    </b-tooltip>

                    <b-tooltip label="Settings" type="is-dark">
                        <p  class="control">
                            <b-button type="is-light"
                                      @click="toggleMenuItemSettings($event)"
                                      icon-left="cog">
                            </b-button>
                        </p>
                    </b-tooltip>

                    <b-tooltip label="Delete" type="is-dark">
                        <p class="control">
                            <b-button type="is-light"
                                      @click="removeMenuItem(el)"
                                      icon-left="trash">
                            </b-button>
                        </p>
                    </b-tooltip>

                </b-field>


                <div class="menu-item-settings has-padding-top-15 has-padding-bottom-15 hide">

                    <b-field label=" " :label-position="labelPosition">
                        <b-checkbox v-model="el.attr_target_blank">Open in new window</b-checkbox>
                    </b-field>

                    <b-field v-if="el.type == 'internal-link'" label="URL"
                             :label-position="labelPosition">
                        <b-input v-model="el.uri"
                                 placeholder="/about-us"
                                 expanded>
                        </b-input>
                    </b-field>

                    <b-field v-if="el.type == 'external-link'" label="URL"
                             :label-position="labelPosition">
                        <b-input v-model="el.uri"
                                 placeholder="https://www.google.com"
                                 expanded>
                        </b-input>
                    </b-field>

                    <b-field label="Menu Item ID"
                             :label-position="labelPosition">
                        <b-input v-model="el.attr_id"
                                 expanded>
                        </b-input>
                    </b-field>

                    <b-field label="Menu Item Class"
                             :label-position="labelPosition">
                        <b-input v-model="el.attr_class"
                                 expanded>
                        </b-input>
                    </b-field>

                </div>


            </div>

            <nested-draggable v-if="el.child" :items="el.child" />
        </li>
    </draggable>
</template>
<script src="./NestedDraggableJs.js"></script>
