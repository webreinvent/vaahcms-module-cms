import GlobalComponents from '../../vaahvue/helpers/GlobalComponents';
import copy from "copy-to-clipboard";


let namespace = 'menus';

export default {
    computed:{
        root() {return this.$store.getters['root/state']},
        page() {return this.$store.getters[namespace+'/state']},
        assets() {return this.$store.getters[namespace+'/state'].assets},
        ajax_url() {return this.$store.getters[namespace+'/state'].ajax_url},
        query_string() {return this.$store.getters[namespace+'/state'].query_string},
    },
    components:{
        ...GlobalComponents,


    },
    data()
    {
        return {
            namespace: namespace,
            is_content_loading: false,
            is_btn_loading: false,
            labelPosition: 'on-border',
            selected_date: null,
            search_delay: null,
            search_delay_time: 800,
            ids: [],
            moduleSection: null,
        }
    },
    watch: {
        $route(to, from) {
            this.updateQueryString();

            //----------------------------------------------------
            this.getMenuList();
        }
    },
    mounted() {
        //----------------------------------------------------
        this.onLoad();
        this.updateQueryString();

        //----------------------------------------------------
        this.getMenuList();
        //----------------------------------------------------
        //----------------------------------------------------
        //----------------------------------------------------
        //----------------------------------------------------
        //----------------------------------------------------
    },
    methods: {
        //---------------------------------------------------------------------
        update: function(name, value)
        {
            let update = {
                state_name: name,
                state_value: value,
                namespace: this.namespace,
            };
            this.$vaah.updateState(update);
        },
        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        onLoad: function()
        {
            this.getAssets();
        },
        //---------------------------------------------------------------------
        toCreate: function()
        {
            this.update('active_item', null);
            this.$router.push({name:'content.types.create'});
        },
        //---------------------------------------------------------------------
        updateQueryString: function()
        {
            let query = this.$vaah.removeEmpty(this.$route.query);
            if(Object.keys(query).length)
            {
                for(let key in query)
                {
                    this.query_string[key] = query[key];
                }
            }
            this.update('query_string', this.query_string);
            this.$vaah.updateCurrentURL(this.query_string, this.$router);
        },
        //---------------------------------------------------------------------
        async getAssets() {
            await this.$store.dispatch(this.namespace+'/getAssets');
        },
        //---------------------------------------------------------------------
        setActiveTheme: function () {

            this.update('active_menu', null);


            this.query_string.vh_theme_location_id = '';
            this.query_string.vh_menu_id = '';
            this.update('query_string', this.query_string);

            this.update('active_theme', null);
            this.update('active_location', null);


            if(this.page.query_string.vh_theme_id){
                let theme = this.$vaah.findInArrayByKey(this.assets.themes,
                    'id', this.page.query_string.vh_theme_id);

                this.update('active_theme', theme);
            }

            this.$vaah.updateCurrentURL(this.query_string, this.$router);


        },
        //---------------------------------------------------------------------
        setActiveLocation: function () {


            this.query_string.vh_menu_id = '';
            this.update('query_string', this.query_string);

            this.update('active_location', null);

            this.update('active_menu', null);

            if(this.page.query_string.vh_theme_location_id){

                let item = this.$vaah.findInArrayByKey(this.page.active_theme.locations,
                    'id', this.page.query_string.vh_theme_location_id);

                this.update('active_location', item);
            }

            this.$vaah.updateCurrentURL(this.query_string, this.$router);






        },
        //---------------------------------------------------------------------
        setActiveMenu: function () {

            this.$vaah.updateCurrentURL(this.query_string, this.$router);

            this.update('active_menu', null);

            if(this.page.query_string.vh_menu_id){
                let item = this.$vaah.findInArrayByKey(this.page.active_location.menus,
                    'id', this.page.query_string.vh_menu_id);

                this.update('active_menu', item);

                this.getMenuItems();
            }

        },
        //---------------------------------------------------------------------
        create: function () {
            this.$Progress.start();
            let params = this.page.new_item;
            params.vh_theme_location_id = this.page.active_location.id;

            let url = this.ajax_url+'/create';
            this.$vaah.ajax(url, params, this.createAfter);
        },
        //---------------------------------------------------------------------
        createAfter: function (data, res) {

            if(data){

                this.getMenuList();

                this.page.query_string.vh_menu_id = data.item.id;
                this.update('query_string', this.page.query_string);

                this.update('active_menu', data.item);
                this.update('active_menu_items', []);

                this.page.new_item.name = null;

                this.update('new_item', this.page.new_item);

                this.getMenuItems();
            }


            this.$Progress.finish();

        },
        //---------------------------------------------------------------------
        getMenuItems: function () {
            this.$Progress.start();
            let params = {};
            let url = this.ajax_url+'/item/'+this.page.active_menu.id+'/items';
            this.$vaah.ajax(url, params, this.getMenuItemsAfter);
        },
        //---------------------------------------------------------------------
        getMenuItemsAfter: function (data, res) {
            this.$Progress.finish();
            if(data){
                this.update('active_menu_items', data.items);

                console.log('chek',this.page.active_menu.id);
                this.$router.push({name: 'menus.view', params:{id:this.page.active_menu.id}})
            }

        },
        //---------------------------------------------------------------------
        deleteItem: function () {
            this.$Progress.start();
            let params = {
                inputs: [
                    this.page.active_menu.id
                ]
            };
            let url = this.ajax_url+'/actions/bulk-delete';
            this.$vaah.ajax(url, params, this.deleteItemAfter);
        },
        //---------------------------------------------------------------------
        deleteItemAfter: function (data, res) {
            this.$Progress.finish();
            if(data){
                this.getAssets();
                this.$router.push({name: 'menus.list'});
            }

        },
        //---------------------------------------------------------------------
        getMenuList: function () {
            let params = {};
            let url = this.ajax_url+'/assets';
            this.$vaah.ajaxGet( url, params, this.getMenuListAfter);
        },
        //---------------------------------------------------------------------
        getMenuListAfter: function (data, res) {
            if(data && data.themes){

                this.update('assets', data);

                let theme = this.$vaah.findInArrayByKey(data.themes,
                    'id', this.page.query_string.vh_theme_id);

                this.update('active_theme', theme);


                if(theme){
                    let item = this.$vaah.findInArrayByKey(theme.locations,
                        'id', this.page.query_string.vh_theme_location_id);

                    this.update('active_location', item);
                }




            }

        },

        //---------------------------------------------------------------------
        copyCode: function (item,id)
        {
            let code = "";

            let location = this.$vaah.findInArrayByKey(item, 'id', id);

            if(location){
                code = "{!! vh_location('"+location.slug+"', true) !!}";
            }

            copy(code);

            this.$buefy.toast.open({
                message: 'Copied!',
                type: 'is-success'
            });
        },
        //---------------------------------------------------------------------
    }
}
