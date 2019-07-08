import pagination from 'laravel-vue-pagination';

//https://github.com/sagalbot/vue-select
import vSelect from 'vue-select'

//https://github.com/myokyawhtun/label-edit
import LabelEdit from 'label-edit'

import vhSelect from 'vaah-vue-select'

export default {

    props: ['urls'],
    components:{
        'pagination': pagination,
        'v-select': vSelect,
        'label-edit': LabelEdit,
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
                permalink: this.urls.base,
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

            console.log(this.urls);

            var url = this.urls.current+"/assets";
            var params = {};
            this.$helpers.ajax(url, params, this.getAssetsAfter);
        },
        //---------------------------------------------------------------------
        getAssetsAfter: function (data) {

            this.assets = data;
            this.page_data.vh_theme_template_id = data.page_template_default.id;
            this.page_data.custom_fields = data.page_custom_fields;

            this.$helpers.stopNprogress();

            //this.getCustomFields();

        },
        //---------------------------------------------------------------------
        getCustomFields: function () {

            this.$helpers.console(this.page_data, 'line 105');

            if(!this.page_data.vh_theme_template_id)
            {
                return false;
            }

            var url = this.urls.current+"/custom/fields/"+this.page_data.vh_theme_template_id;
            var params = {};

            this.$helpers.console(params, 'line 115');

            this.$helpers.ajax(url, params, this.getCustomFieldsAfter);
        },
        //---------------------------------------------------------------------
        getCustomFieldsAfter: function (data) {

            this.page_data.custom_fields = null;

            this.$helpers.console(data, 'line 124');

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

            var url = this.urls.current+"/store";
            var params = this.page_data;

            this.$helpers.console(this.page_data, 'page_data');

            this.$helpers.ajax(url, params, this.storeDraftAfter);

        },
        //---------------------------------------------------------------------
        storeDraftAfter: function (data) {

            this.$helpers.console(data, 'stored data --->');

            let id = data.id;

            this.$router.push({ path: `/edit/${id}`});

        },
        //---------------------------------------------------------------------
    }
}