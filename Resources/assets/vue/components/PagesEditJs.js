import pagination from 'laravel-vue-pagination';

//https://github.com/sagalbot/vue-select
import vSelect from 'vue-select'

//https://github.com/myokyawhtun/label-edit
import LabelEdit from 'label-edit'

export default {

    props: ['urls', 'id'],
    components:{
        'pagination': pagination,
        'v-select': vSelect,
        'label-edit': LabelEdit,
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
            page_template: {
                code: 'default',
                label: 'Default',
            },
            list: null,
            page_reload_required: null,
            stats: null,
            custom_fields: null,
            page_data:{
                title: null,
                slug: null,
                slug_edit: null,
                name: null,
                permalink: this.urls.base,
                custom_fields: null
            }
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

            //this.$helpers.console(this.assets, 'from app->');

            this.getPageDetails();

        },
        //---------------------------------------------------------------------
        getPageDetails: function () {
            var url = this.urls.current+"/page/"+this.id;
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
        getCustomFields: function () {

            if(!this.page_template)
            {
                return false;
            }

            var url = this.urls.current+"/custom/fields";
            var params = {
                page_template_slug: this.page_template.code
            };

            this.$helpers.console(params, 'params-->');

            this.$helpers.ajax(url, params, this.getCustomFieldsAfter);
        },
        //---------------------------------------------------------------------
        getCustomFieldsAfter: function (data) {

            this.$helpers.console(data, 'custom fields');

            this.page_data.custom_fields = data.list;

            this.$helpers.stopNprogress();
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

            this.page_data.slug = this.$helpers.strToSlug(this.page_data.slug);
            this.page_data.slug_edit = null;

        },

        //---------------------------------------------------------------------
        storeDraft: function (e) {
            if(e)
            {
                e.preventDefault();
            }

            var url = this.urls.current+"/assets";
            var params = this.page_data;

            this.$helpers.console(this.page_data, 'page_data');

            //this.$helpers.ajax(url, params, this.fnAfter);

        },
        //---------------------------------------------------------------------
        fnAfter: function (data) {

            this.$helpers.stopNprogress();
        },
        //---------------------------------------------------------------------
    }
}