import pagination from 'laravel-vue-pagination';


import vhSelect from 'vaah-vue-select'


export default {

    props: ['urls', 'id'],
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
            page_status: 'draft',
            page_visibility: 'public',
            page_parent: null,
            page_template: {},
            list: null,
            page_reload_required: null,
            stats: null,
            custom_fields: null,
            page_data:null
        };

        return obj;
    },
    watch: {

        'page_data.title': function(newVal, oldVal){
            this.$helpers.console(newVal, 'newVal');
            this.$helpers.console(oldVal, 'oldVal');

            this.page_data.slug = this.$helpers.strToSlug(newVal);
            this.page_data.parmalink = this.urls.base+"/"+this.page_data.slug;

        },
        'page_data.slug': function(newVal, oldVal){

            this.page_data.slug = this.$helpers.strToSlug(newVal);
            this.page_data.parmalink = this.urls.base+"/"+this.page_data.slug;

        },
        'page_data.vh_theme_template_id': function (newVal, oldVal) {
            this.getEditPageCustomFields();
        }

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

            var url = this.urls.current+"/assets";
            var params = {};
            this.$helpers.ajax(url, params, this.getAssetsAfter);
        },
        //---------------------------------------------------------------------
        getAssetsAfter: function (data) {

            this.assets = data;
            //this.page_template = data.page_template_default;

            //this.$helpers.console(this.assets, 'from app->');

            this.getPageDetails();

        },
        //---------------------------------------------------------------------
        getPageDetails: function () {
            var url = this.urls.current+"/"+this.id;
            var params = {};
            this.$helpers.ajax(url, params, this.getPageDetailsAfter);
        },
        //---------------------------------------------------------------------
        getPageDetailsAfter: function (data) {

            this.$helpers.console(data, '-->');

            this.page_data = data;

            this.$helpers.stopNprogress();
        },
        //---------------------------------------------------------------------
        getEditPageCustomFields: function () {

            if(!this.page_template)
            {
                return false;
            }

            var url = this.urls.current+"/"+this.id+"/custom/fields/"+this.page_data.vh_theme_template_id;
            var params = {};

            this.$helpers.console(params, 'params-->');

            this.$helpers.ajax(url, params, this.getEditPageCustomFieldsAfter);
        },
        //---------------------------------------------------------------------
        getEditPageCustomFieldsAfter: function (data) {

            this.$helpers.console(data, 'custom fields');

            this.page_data = data;

            this.$helpers.stopNprogress();
        },

        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        slugUpdated: function (text) {

            this.page_data.slug = text;
        },

        //---------------------------------------------------------------------
        setSlugEdit: function (value) {

            this.page_data.slug_edit = value;
        },
        //---------------------------------------------------------------------
        updatePageSlug: function () {

            this.page_data.slug = this.$helpers.strToSlug(this.page_data.slug);
            this.page_data.slug_edit = null;

        },

        //---------------------------------------------------------------------
        storePage: function (e) {
            if(e)
            {
                e.preventDefault();
            }

            var url = this.urls.current+"/store";
            var params = this.page_data;

            this.$helpers.console(this.page_data, 'page_data');

            this.$helpers.ajax(url, params, this.storePageAfter);

        },
        //---------------------------------------------------------------------
        storePageAfter: function (data) {

            this.$helpers.console(data, '--->');

            this.$helpers.stopNprogress();
        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}