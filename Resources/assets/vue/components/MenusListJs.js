import pagination from 'laravel-vue-pagination';
import vhSelect from 'vaah-vue-select'

export default {

    props: ['urls'],
    components:{
        'pagination': pagination,
        'vh-select': vhSelect,
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

            this.getAssets();
        },
        //---------------------------------------------------------------------

        getList: function (page) {


            var url = this.urls.current+"/list";

            if(!page)
            {
                page = this.page;
            }

            if(this.page)
            {
                url = url+"?page="+page;
            }

            url = url+"&status="+this.filters.status;

            if(this.filters.q)
            {
                url = url+"&q="+this.filters.q;
            }

            var params = {};
            this.$helpers.ajax(url, params, this.getListAfter);

        },
        //---------------------------------------------------------------------
        getListAfter: function (data) {

            this.list = data.list;
            this.stats = data.stats;
            this.page = data.list.current_page;

            this.$helpers.console(this.list);

            this.$helpers.stopNprogress();

        },

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