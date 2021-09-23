
import GlobalComponents from '../../vaahvue/helpers/GlobalComponents'
import ContentFieldAll from '../../vaahvue/reusable/content-fields/All'
import AutoCompleteUsers from '../../vaahvue/reusable/AutoCompleteUsers'

import ContentFields from './partials/ContentFields'
import TemplateFields from './partials/TemplateFields'
import CustomFields from './partials/CustomFields'

let namespace = 'contents';

export default {
    props: ['id'],
    computed:{
        root() {return this.$store.getters['root/state']},
        page() {return this.$store.getters[namespace+'/state']},
        assets() {return this.$store.getters[namespace+'/state'].assets},
        ajax_url() {return this.$store.getters[namespace+'/state'].ajax_url},
        new_item() {return this.$store.getters[namespace+'/state'].new_item},
    },
    components:{
        ...GlobalComponents,
        ContentFieldAll,
        AutoCompleteUsers,
        ContentFields,
        TemplateFields,
        CustomFields,
    },
    data()
    {
        return {
            namespace: namespace,
            is_content_loading: false,
            is_btn_loading: null,
            labelPosition: 'on-border',
            params: {},
            local_action: null,
            title: null,
            new_status: null,
            disable_status_editing: true,
            edit_status_index: null,
        }
    },
    watch: {
        $route(to, from) {
            this.updateStore()
        },


    },
    mounted() {
        //----------------------------------------------------

        //----------------------------------------------------
        this.onLoad();
        //----------------------------------------------------

        //----------------------------------------------------
    },
    methods: {
        //---------------------------------------------------------------------
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
        updateNewItem: function()
        {
            let update = {
                state_name: 'new_item',
                state_value: this.new_item,
                namespace: this.namespace,
            };
            this.$vaah.updateState(update);
        },
        //---------------------------------------------------------------------
        updateStore: function()
        {
            this.$store.dispatch(this.namespace+'/updateStore', this.$route);
        },
        //---------------------------------------------------------------------
        onLoad: function()
        {
            this.is_content_loading = true;
            this.updateStore();
            this.getAssets();
        },
        //---------------------------------------------------------------------
        async getAssets() {
            await this.$store.dispatch(this.namespace+'/getAssets');
        },
        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        create: function () {
            //this.$Progress.start();
            let params = this.new_item;

            params.content_groups = this.assets.content_type.form_groups;
            params.template_groups = this.page.active_template_groups;

            console.log('--->', params);

            let url = this.ajax_url+'/create';
            this.$vaah.ajax(url, params, this.createAfter);
        },
        //---------------------------------------------------------------------
        createAfter: function (data, res) {

            this.$Progress.finish();

            if(data)
            {
                this.$emit('eReloadList');

                this.$store.dispatch(this.namespace+'/reloadAssets');

                this.saveAndNew();

            }

        },
        //---------------------------------------------------------------------
        setLocalAction: function (action) {
            this.local_action = action;
            this.create();
        },//---------------------------------------------------------------------
        setAuthor: function (user)
        {
            this.new_item.author = user.id;
            this.updateNewItem();
        },
        //---------------------------------------------------------------------
        saveAndNew: function () {
            this.update('active_item', null);
            this.resetNewItem();
        },
        //---------------------------------------------------------------------
        resetNewItem: function()
        {
            let new_item = this.getNewItem();
            this.update('new_item', new_item);
        },
        //---------------------------------------------------------------------
        getNewItem: function()
        {
            let new_item = {
                parent_id: null,
                vh_cms_content_type_id: null,
                vh_theme_id: this.page.assets.default_theme.id,
                vh_theme_template_id: this.page.assets.default_template?this.page.assets.default_template.id:null,
                name: null,
                slug: null,
                is_published_at: null,
                status: "",
                total_comments: null,
                meta: null,
                fields: [
                ],
            };
            return new_item;
        },
        //---------------------------------------------------------------------
        expandAll: function () {

            $('.collapse-content').each(function (index, item) {
                $(item).slideDown();
            });

        },
        //---------------------------------------------------------------------
        collapseAll: function () {
            $('.collapse-content').each(function (index, item) {
                $(item).slideUp();
            });
        },
        //---------------------------------------------------------------------
        setActiveTheme: function () {
            let theme = this.$vaah.findInArrayByKey(this.assets.themes,
                'id', this.new_item.vh_theme_id);
            this.update('active_theme', theme);
        },
        //---------------------------------------------------------------------
        setActiveTemplate: function () {
            let template = this.$vaah.findInArrayByKey(this.page.active_theme.templates,
                'id', this.new_item.vh_theme_template_id);
            this.update('active_template', template);

            let groups = [];

            $(template.groups).each(function (index, item) {

                groups[index] = [item];
            });

            console.log('--->', groups);

            this.update('active_template_groups', groups);
        }
        //---------------------------------------------------------------------
    }
}
