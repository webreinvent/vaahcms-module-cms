<template>
    <draggable class="dropzone-nested" tag="ul" :list="items" :group="{ name: 'menu_items' }">
        <li v-for="el in items" :key="el.id">
            <div class="dropzone-field">

                <b-field class="is-marginless" >

                    <p class="control drag">
                        <span class="button is-static">:::</span>
                    </p>

                    <p class="control" v-if="el.content">
                        <span class="button dropzone-field-label is-static">{{el.content.name}}</span>
                    </p>

                    <b-input v-model="el.name" expanded placeholder="Menu Name"></b-input>

                    <p  class="control">
                        <b-tooltip label="Settings" type="is-dark">
                            <b-button type="is-light"
                                      @click="toggleMenuItemSettings($event)"
                                      icon-left="cog">
                            </b-button>
                        </b-tooltip>
                    </p>

                    <p class="control">
                        <b-tooltip label="Delete" type="is-dark">
                            <b-button type="is-light"
                                      @click="removeMenuItem(el)"
                                      icon-left="trash">
                            </b-button>
                        </b-tooltip>
                    </p>

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
<script>
    import draggable from "vuedraggable";


    export default {
        name: "nested-draggable",
        props: {
            items: {
                required: true,
                type: Array
            }
        },
        data()
        {
            return {
                labelPosition: 'on-border',
            }
        },
        components: {
            draggable
        },

        methods: {
            //--------------------------------------------------------
            toggleMenuItemSettings: function (e) {

                console.log('--->clicked');

                let el = e.target;



                let target = $(el).closest('.dropzone-field').find('.menu-item-settings');

                $(target).toggle();

                console.log('--->', target);



            },
            //--------------------------------------------------------
            removeMenuItem: function (item) {
                this.$vaah.removeFromArray(this.items, item);
            }
            //--------------------------------------------------------
            //--------------------------------------------------------
            //--------------------------------------------------------
        }
    };
</script>
