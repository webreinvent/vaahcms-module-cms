import pagination from 'laravel-vue-pagination';
import TableLoader from './../reusable/TableLoader';

//https://github.com/sagalbot/vue-select
import vSelect from 'vue-select'

//https://github.com/myokyawhtun/label-edit


import vhSelect from 'vaah-vue-select'

export default {

    props: [],
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
        'v-select': vSelect,
        'vh-select': vhSelect,
    },
    data()
    {
        let obj = {
            assets: null,
            q: null,
            page: 1,
            page_template: {},
            list: null,
            page_reload_required: null,
            stats: null,
            custom_fields: null,
            page_data:{
                title: null,
                slug: null,
                slug_edit: null,
                name: null,
                permalink: null,
                custom_fields: null,
                vh_theme_template_id: null,
                status: 'draft',
                visibility: 'public',
            }
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

            console.log(this.urls);

            var url = this.ajax_url+"/assets";
            var params = {};
            this.$vaahcms.ajax(url, params, this.getAssetsAfter);
        },
        //---------------------------------------------------------------------
        getAssetsAfter: function (data) {

            this.assets = data;
            this.page_data.vh_theme_template_id = data.page_template_default.id;
            this.page_data.custom_fields = data.page_custom_fields;

            this.$vaahcms.stopNprogress();

            //this.getCustomFields();

        },
        //---------------------------------------------------------------------
        getCustomFields: function () {

            this.$vaahcms.console(this.page_data, 'line 105');

            if(!this.page_data.vh_theme_template_id)
            {
                return false;
            }

            var url = this.ajax_url+"/custom/fields/"+this.page_data.vh_theme_template_id;
            var params = {};

            this.$vaahcms.console(params, 'line 115');

            this.$vaahcms.ajax(url, params, this.getCustomFieldsAfter);
        },
        //---------------------------------------------------------------------
        getCustomFieldsAfter: function (data) {

            this.page_data.custom_fields = null;

            this.$vaahcms.console(data, 'line 124');

            this.page_data.custom_fields = data.list;

            this.$vaahcms.stopNprogress();
        },

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
        storeDraft: function (e) {
            if(e)
            {
                e.preventDefault();
            }

            var url = this.ajax_url+"/store";
            var params = this.page_data;

            this.$vaahcms.console(this.page_data, 'page_data');

            this.$vaahcms.ajax(url, params, this.storeDraftAfter);

        },
        //---------------------------------------------------------------------
        storeDraftAfter: function (data) {

            this.$vaahcms.console(data, 'stored data --->');

            let id = data.id;

            this.$router.push({ path: `/pages/edit/${id}`});

        },
        //---------------------------------------------------------------------
    }
}