import pagination from 'laravel-vue-pagination';
import TableLoader from './../reusable/TableLoader';

export default {

    props: ['urls'],
    computed:{
        ajax_url(){
            let ajax_url = this.$store.state.urls.pages;
            return ajax_url;
        }
    },
    components:{
        't-loader': TableLoader,
        'pagination': pagination,
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

            var url = this.ajax_url+"/assets";
            var params = {};
            this.$vaahcms.ajax(url, params, this.getAssetsAfter);
        },
        //---------------------------------------------------------------------
        getAssetsAfter: function (data) {

            this.assets = data;

            this.$vaahcms.console(this.assets, 'from app->');

            this.getList();

        },
        //---------------------------------------------------------------------

        getList: function (page) {


            var url = this.ajax_url+"/list";

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
            this.$vaahcms.ajax(url, params, this.getListAfter);

        },
        //---------------------------------------------------------------------
        getListAfter: function (data) {

            this.list = data.list;
            this.stats = data.stats;
            this.page = data.list.current_page;

            this.$vaahcms.console(this.list);

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