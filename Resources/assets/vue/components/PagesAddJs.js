import pagination from 'laravel-vue-pagination';

export default {

    props: ['urls'],
    components:{
        'pagination': pagination,
    },
    data()
    {
        let obj = {
            assets: null,
            q: null,
            page: 1,
            list: null,
            page_reload_required: null,
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
        //this.getAssets();
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

        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}