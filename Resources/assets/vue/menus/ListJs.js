import pagination from 'laravel-vue-pagination';
import vhSelect from 'vaah-vue-select'
import menutree from './MenuTree'
import TableLoader from './../reusable/TableLoader';


export default {

    props: [],
    computed:{
        ajax_url(){
            let ajax_url = this.$store.state.urls.menus;
            return ajax_url;
        }
    },
    components:{
        'pagination': pagination,
        'vh-select': vhSelect,
        'menutree': menutree,
        't-loader': TableLoader,
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
                parent_id: null,
                name: null
            },
            active_location_id:null,
            menus_list:null,
            active_menu_id:null,
            menu_items:null,
            new_menu_item: {
                id: null,
                name: null,
                vh_page_id: null,
                parent_id: null,
            },
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

            var url = this.ajax_url+"/assets";
            var params = {};
            this.$vaahcms.ajax(url, params, this.getAssetsAfter);
        },
        //---------------------------------------------------------------------
        getAssetsAfter: function (data) {

            this.assets = data;

            this.$vaahcms.stopNprogress();

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

            var url = this.ajax_url+"/store";
            var params = this.new_menu;
            this.$vaahcms.ajax(url, params, this.storeMenuAfter);
        },
        //---------------------------------------------------------------------
        storeMenuAfter: function (data) {

            this.active_menu = data;
            $("#ModalAddMenu").modal('hide');

            this.new_menu = {
                vh_theme_location_id: null,
                name: null
            };

            this.getLocationMenus();

        },
        //---------------------------------------------------------------------
        getLocationMenus: function () {

            if(this.active_location_id == "")
            {
                return false;
            }

            var url = this.ajax_url+"/location/menus/"+this.active_location_id;
            var params = {};
            this.$vaahcms.ajax(url, params, this.getMenusAfter);
        },
        //---------------------------------------------------------------------
        getMenusAfter: function (data) {

            this.menus_list = null;

            this.menus_list = data;

            this.$vaahcms.stopNprogress();

        },
        //---------------------------------------------------------------------
        getMenuItems: function () {

            if(this.active_menu_id == "")
            {
                return false;
            }

            var url = this.ajax_url+"/items/"+this.active_menu_id;
            var params = {};
            this.$vaahcms.ajax(url, params, this.getMenuItemsAfter);
        },
        //---------------------------------------------------------------------
        getMenuItemsAfter: function (data) {

            this.menu_items = null;
            this.assets.menu_items = null;

            this.assets.menu_items = data.assets;
            this.menu_items = data.list;


            this.$vaahcms.stopNprogress();

        },
        //---------------------------------------------------------------------
        actions: function (e, action, inputs, data) {
            if(e)
            {
                e.preventDefault();
            }

            var url = this.ajax_url+"/actions";
            var params = {
                action: action,
                inputs: inputs,
                data: data,
            };

            this.$vaahcms.ajax(url, params, this.actionsAfter);
        },
        //---------------------------------------------------------------------
        actionsAfter: function (data) {
            this.getMenuItems();
        },
        //---------------------------------------------------------------------

        makeItHome: function (menu_item) {
            var inputs = {id: menu_item.id};
            var data = {is_home:true};
            this.actions(false, 'make_it_home', inputs, data)
        },
        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        addRootMenu: function () {
            this.new_menu_item.parent_id = null;
            $("#ModalAddMenuItem").modal('show');
        },
        //---------------------------------------------------------------------
        addSubMenu: function(menu_item)
        {
            this.new_menu_item.parent_id = menu_item.id;
            $("#ModalAddMenuItem").modal('show');

        },
        //---------------------------------------------------------------------
        editMenu: function(menu_item)
        {
            this.$vaahcms.console(menu_item, 'menu_item');


            this.new_menu_item.parent_id = menu_item.parent_id;
            this.new_menu_item.vh_page_id = menu_item.vh_page_id;
            this.new_menu_item.name = menu_item.name;
            this.new_menu_item.id = menu_item.id;

            //this.$vaahcms.console(this.new_menu_item, 'edit menu');

            $("#ModalAddMenuItem").modal('show');
        },
        //---------------------------------------------------------------------
        storeMenuItem: function () {
            var url = this.ajax_url+"/items/"+this.active_menu_id+"/store";
            var params = this.new_menu_item;
            params.vh_menu_id = this.active_menu_id;

            this.$vaahcms.console(params, 'new page');

            this.$vaahcms.ajax(url, params, this.storeMenuItemAfter);
        },
        //---------------------------------------------------------------------
        storeMenuItemAfter: function (data) {

            $("#ModalAddMenuItem").modal('hide');
            this.getMenuItems();

        },

        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        deleteItem: function (menu_item) {
            this.$vaahcms.console('testing menu list');
            var url = this.ajax_url+"/items/"+menu_item.id+"/delete";
            var params = {};
            this.$vaahcms.ajax(url, params, this.deleteItemAfter);
        },
        //---------------------------------------------------------------------
        deleteItemAfter: function (data) {
            this.getMenuItems();
        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}