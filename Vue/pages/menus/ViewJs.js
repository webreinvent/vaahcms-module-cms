import GlobalComponents from '../../vaahvue/helpers/GlobalComponents';
import draggable from 'vuedraggable';
import NestedDraggable from "./partials/NestedDraggable";

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
        draggable,
        NestedDraggable,

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
        }
    },
    mounted() {
        //----------------------------------------------------
        this.onLoad();
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

            if(!this.page.filters.vh_theme_location_id)
            {
                this.$router.push({name: 'menus.list'});
            }

            this.getAssets();
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
            this.getContentList();
        },
        //---------------------------------------------------------------------
        delayedSearch: function()
        {
            let self = this;
            clearTimeout(this.search_delay);
            this.search_delay = setTimeout(function() {
                self.getContentList();
            }, this.search_delay_time);
            this.update('query_string', this.query_string);

        },
        //---------------------------------------------------------------------
        getContentList: function () {
            this.$Progress.start();
            let params = this.page.query_string;
            let url = this.ajax_url+'/content/list';
            this.$vaah.ajax(url, params, this.getContentAfter);
        },
        //---------------------------------------------------------------------
        getContentAfter: function (data, res) {
            this.$Progress.finish();
            if(data){
                this.update('content_list', data.list);

                console.log('--->', data.list);
            }
        },

        //---------------------------------------------------------------------
        cloneField: function({ id, name })
        {
            let item = {
                vh_content_id: id,
                name: name,
                content: {
                    name: name
                },
                vh_menu_id: this.page.active_menu.id,
                child: [],
            };



            return item;
        },
        //---------------------------------------------------------------------
        store: function () {
            this.$Progress.start();
            let params = this.page.active_menu;
            params.items = this.page.active_menu_items;

            let url = this.ajax_url+'/item/'+this.page.active_menu.id+'/store';
            this.$vaah.ajax(url, params, this.storeAfter);
        },
        //---------------------------------------------------------------------
        storeAfter: function (data, res) {
            this.$Progress.finish();
            if(data){
                this.getAssets();
                this.page.filters.vh_menu_id = data.menu.id;
                this.update('filters', this.page.filters);
                this.update('active_menu', data.menu);
                this.update('active_menu_items', data.menu_items);

                console.log('--->data.menu_items', data.menu_items);

            }

        },
        //---------------------------------------------------------------------
    }
}
