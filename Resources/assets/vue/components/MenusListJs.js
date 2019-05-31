import pagination from 'laravel-vue-pagination';
import vhSelect from 'vaah-vue-select'
import tree from './reusable/tree'

export default {

    props: ['urls'],
    components:{
        'pagination': pagination,
        'vh-select': vhSelect,
        'tree': tree,
    },
    data()
    {

        let obj = {
            assets: null,
            q: null,
            page: 1,
            list: null,
            stats: null,
            active_tab: 'all',
            active_item: null,
            active_el: null,
            filters: {
                q: null,
                status: 'all',
            },
            new_menu: {
                vh_theme_location_id: null,
                name: null
            },
            active_location_id:null,
            menus_list:null,
            active_menu_id:null,
            menu_items:null,
            new_menu_item: {
                name: null,
                vh_cms_menu_item_id: null,
            },

            tree: {
                name: 'root',
                childerns: [
                    {
                        name: 'item1',
                        childerns: [
                            {
                                name: 'item1.1'
                            },
                            {
                                name: 'item1.2',
                                childerns: [
                                    {
                                        name: 'item1.2.1'
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        name: 'item2'
                    }
                ]
            }


        };

        return obj;
    },
    watch: {



    },
    mounted() {

        //---------------------------------------------------------------------
        this.getAssets();
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------

    },
    methods: {
        //---------------------------------------------------------------------
        getAssets: function (e) {
            if(e)
            {
                e.preventDefault();
            }

            console.log(this.urls);

            var url = this.urls.current+"/assets";
            var params = {};
            this.$helpers.ajax(url, params, this.getAssetsAfter);
        },
        //---------------------------------------------------------------------
        getAssetsAfter: function (data) {

            this.assets = data;

            this.$helpers.console(this.assets, 'from app->');

            this.$helpers.stopNprogress();

        },
        //---------------------------------------------------------------------
        showModalMenuAdd: function()
        {

            $("#ModalAddMenu").modal('show');

        },
        //---------------------------------------------------------------------
        storeMenu: function (e) {
            if(e)
            {
                e.preventDefault();
            }

            var url = this.urls.current+"/store";
            var params = this.new_menu;
            this.$helpers.ajax(url, params, this.storeMenuAfter);
        },
        //---------------------------------------------------------------------
        storeMenuAfter: function (data) {

            this.active_menu = data;
            $("#ModalAddMenu").modal('hide');

            this.new_menu = {
                vh_theme_location_id: null,
                name: null
            };

            this.$helpers.stopNprogress();

        },
        //---------------------------------------------------------------------
        getLocationMenus: function () {

            if(this.active_location_id == "")
            {
                return false;
            }

            var url = this.urls.current+"/location/menus/"+this.active_location_id;
            var params = {};
            this.$helpers.ajax(url, params, this.getMenusAfter);
        },
        //---------------------------------------------------------------------
        getMenusAfter: function (data) {

            this.menus_list = data;

            this.$helpers.console(this.menus_list);

            this.$helpers.stopNprogress();

        },
        //---------------------------------------------------------------------
        getMenuItems: function () {

            if(this.active_menu_id == "")
            {
                return false;
            }

            var url = this.urls.current+"/menus/items/"+this.active_menu_id;
            var params = {};
            this.$helpers.ajax(url, params, this.getMenuItemsAfter);
        },
        //---------------------------------------------------------------------
        getMenuItemsAfter: function (data) {

            this.menu_items = null;
            this.assets.menu_items = null;

            this.assets.menu_items = data.assets;
            this.menu_items = data.list;

            this.$helpers.console(this.menu_items, 'this.menu_items');

            this.$helpers.stopNprogress();

        },
        //---------------------------------------------------------------------
        showModalMenuItemAdd: function () {
            $("#ModalAddMenuItem").modal('show');
        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        storeMenuItem: function () {
            var url = this.urls.current+"/menus/items/"+this.active_menu_id+"/store";
            var params = this.new_menu_item;
            params.vh_menu_id = this.active_menu_id;
            this.$helpers.ajax(url, params, this.storeMenuItemAfter);
        },
        //---------------------------------------------------------------------
        storeMenuItemAfter: function (data) {

            $("#ModalAddMenuItem").modal('hide');

            this.getMenuItems();
        },

        //---------------------------------------------------------------------


        //---------------------------------------------------------------------
        actions: function (e, action, inputs, data) {
            if(e)
            {
                e.preventDefault();
            }

            var url = this.urls.current+"/actions";
            var params = {
                action: action,
                inputs: inputs,
                data: data,
            };

            this.$helpers.ajax(url, params, this.actionsAfter);
        },
        //---------------------------------------------------------------------
        actionsAfter: function (data) {
            this.getList();
            this.page_reload_required = 1;
        },
        //---------------------------------------------------------------------
        setFilterStatus: function (e, status) {
            if(e)
            {
                e.preventDefault();
            }

            this.filters.status = status;

            this.getList();
        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}