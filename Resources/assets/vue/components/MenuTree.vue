<template>
    <div>
        <ul class="tree" v-if="menu_item">
            <li class="">

                <div class="d-flex flex-row mg-b-3 ">

                    <div class="pd-l-10 pd-t-5 bg-gray-200 flex-grow-1">
                        {{menu_item.name}}
                    </div>

                    <div class="bg-gray-300 mg-l-auto btn-group btn-group-sm">


                        <button class="btn btn-xs btn-icon"
                        v-on:click="addSubMenu(menu_item)">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-xs btn-icon"
                        v-on:click="editMenu(menu_item)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-xs btn-icon"
                        v-on:click="deleteItem(menu_item)">
                            <i class="fas fa-trash"></i>
                        </button>

                    </div>


                </div>


                <menutree
                        v-if="menu_item.childrens"
                        v-for="(item, index) in menu_item.childrens"
                        @deleteItem="deleteItem"
                        @addSubMenu="addSubMenu"
                        @editMenu="editMenu"
                        :menu_item="item"
                        :key="index"
                >
                </menutree>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: [ 'menu_item' ],
        name: 'menutree',
        data()
        {
            let obj = {
                assets: null,
                q: null,
                page: 1,
                list: null,
            };

            return obj;
        },
        watch: {


        },
        mounted() {

            //---------------------------------------------------------------------
            //this.getAssets();
            //---------------------------------------------------------------------
            //---------------------------------------------------------------------
            //---------------------------------------------------------------------
            //---------------------------------------------------------------------
        },
        methods: {
            //---------------------------------------------------------------------
            deleteItem: function (menu_item) {

                this.$helpers.console('testing menu tree');

                this.$emit('deleteItem', menu_item)
            },
            //---------------------------------------------------------------------
            addSubMenu: function (menu_item) {

                this.$helpers.console('testing menu tree');

                this.$emit('addSubMenu', menu_item)
            },
            //---------------------------------------------------------------------
            editMenu: function (menu_item) {

                this.$helpers.console(menu_item, 'menu tree-->');

                this.$emit('editMenu', menu_item)
            },
            //---------------------------------------------------------------------
        }
    }
</script>