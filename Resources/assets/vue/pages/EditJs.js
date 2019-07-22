import pagination from 'laravel-vue-pagination';
import TableLoader from './../reusable/TableLoader';

import vhSelect from 'vaah-vue-select'


export default {

    props: [ 'id'],
    computed:{
        ajax_url(){
            let ajax_url = this.$store.state.urls.pages;
            return ajax_url;
        },
        urls()
        {
            return this.$store.state.urls;
        }
    },
    components:{
        't-loader': TableLoader,
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

            this.page_data.slug = this.$vaahcms.strToSlug(newVal);
            this.page_data.parmalink = this.urls.base+"/"+this.page_data.slug;

        },
        'page_data.slug': function(newVal, oldVal){

            this.page_data.slug = this.$vaahcms.strToSlug(newVal);
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

            var url = this.ajax_url+"/assets";
            var params = {};
            this.$vaahcms.ajax(url, params, this.getAssetsAfter);
        },
        //---------------------------------------------------------------------
        getAssetsAfter: function (data) {

            this.assets = data;
            //this.page_template = data.page_template_default;

            //this.$vaahcms.console(this.assets, 'from app->');

            this.getPageDetails();

        },
        //---------------------------------------------------------------------
        getPageDetails: function () {
            var url = this.ajax_url+"/"+this.id;
            var params = {};
            this.$vaahcms.ajax(url, params, this.getPageDetailsAfter);
        },
        //---------------------------------------------------------------------
        getPageDetailsAfter: function (data) {

            this.$vaahcms.console(data, '-->');

            this.page_data = data;

            this.$vaahcms.stopNprogress();
        },
        //---------------------------------------------------------------------
        getEditPageCustomFields: function () {

            if(!this.page_template)
            {
                return false;
            }

            var url = this.ajax_url+"/"+this.id+"/custom/fields/"+this.page_data.vh_theme_template_id;
            var params = {};

            this.$vaahcms.console(params, 'params-->');

            this.$vaahcms.ajax(url, params, this.getEditPageCustomFieldsAfter);
        },
        //---------------------------------------------------------------------
        getEditPageCustomFieldsAfter: function (data) {

            this.$vaahcms.console(data, 'custom fields');

            this.page_data = data;

            this.$vaahcms.stopNprogress();
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

            this.page_data.slug = this.$vaahcms.strToSlug(this.page_data.slug);
            this.page_data.slug_edit = null;

        },

        //---------------------------------------------------------------------
        storePage: function (e) {
            if(e)
            {
                e.preventDefault();
            }

            var url = this.ajax_url+"/store";
            var params = this.page_data;

            this.$vaahcms.console(this.page_data, 'page_data');

            this.$vaahcms.ajax(url, params, this.storePageAfter);

        },
        //---------------------------------------------------------------------
        storePageAfter: function (data) {

            this.$vaahcms.console(data, '--->');

            this.$vaahcms.stopNprogress();
        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}