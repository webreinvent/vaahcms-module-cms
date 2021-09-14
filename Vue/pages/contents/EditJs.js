let slugify = require('slugify');
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
        active_item() {return this.$store.getters[namespace+'/state'].active_item},
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
            is_reload_btn_loading: false,
            is_btn_loading: null,
            labelPosition: 'on-border',
            params: {},
            local_action: null,
            title: null,
            groups: null,
            theme_sync_loader: false,
            item: null,
        }
    },
    watch: {
        $route(to, from) {
            this.updateStore();
            this.getItem();
        },
        'item.permalink': {
            deep: true,
            handler(new_val, old_val) {
                if(new_val)
                {
                    new_val.trim();
                    this.item.permalink = slugify(new_val);
                    this.updateItem();
                }
            }
        },
    },
    mounted() {
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
        updateItem: function()
        {
            let update = {
                state_name: 'active_item',
                state_value: this.item,
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
            this.getItem();
        },
        //---------------------------------------------------------------------
        async getAssets() {
            await this.$store.dispatch(namespace+'/getAssets');
        },
        //---------------------------------------------------------------------
        async reloadAssets() {
            await this.$store.dispatch(namespace+'/reloadAssets');
        },
        //---------------------------------------------------------------------
        getItem: function () {
            this.$Progress.start();
            this.params = {};
            if(this.$route.params.id){
                let url = this.ajax_url+'/item/'+this.$route.params.id;
                this.$vaah.ajax(url, this.params, this.getItemAfter);
            }

        },
        //---------------------------------------------------------------------
        getItemAfter: function (data, res) {
            this.$Progress.finish();
            this.is_content_loading = false;
            this.is_reload_btn_loading = false;

            if(data)
            {
                this.item = data;
                this.update('active_item', data);

            } else
            {
                //if item does not exist or delete then redirect to list
                this.update('active_item', null);
                this.$router.push({name: 'contents.list', params:{slug: this.page.content_slug}});
            }
        },
        //---------------------------------------------------------------------
        store: function () {
            this.$Progress.start();
            this.is_btn_loading = true;

           this.updateItem();

            console.log('--->', this.item.permalink);

            let params = this.item;
            let url = this.ajax_url+'/item/'+this.item.id+'/store';
            this.$vaah.ajax(url, params, this.storeAfter);
        },
        //---------------------------------------------------------------------
        storeAfter: function (data, res) {

            this.$Progress.finish();

            if(data)
            {
                this.$emit('eReloadList');

                if(this.local_action === 'save-and-close')
                {
                    this.saveAndClose();
                }else if(this.local_action === 'save-and-new'){
                    this.saveAndNew();
                }

            }
            this.is_btn_loading = false;

        },
        //---------------------------------------------------------------------
        setLocalAction: function (action) {
            this.local_action = action;
            this.store();
        },
        //---------------------------------------------------------------------
        setAuthor: function (user)
        {
            this.item.author = user.id;
            this.updateItem();
        },
        //---------------------------------------------------------------------
        saveAndClose: function () {
            this.update('active_item', null);
            this.$router.push({name:'contents.list'});
        },
        //---------------------------------------------------------------------
        saveAndNew: function () {
            this.update('active_item', null);
            this.$router.push({name:'contents.create'});
        },
        //---------------------------------------------------------------------
        expandAll: function () {

            $('.collapse-content').each(function (index, item) {
                $(item).slideDown();
            });

        },
        //---------------------------------------------------------------------
        resetActiveItem: function () {
            this.update('active_item', null);
            this.$router.push({name:'contents.list'});
        },
        //---------------------------------------------------------------------
        collapseAll: function () {
            $('.collapse-content').each(function (index, item) {
                $(item).slideUp();
            });
        },
        //---------------------------------------------------------------------
        setActiveTheme: function () {
            let theme = this.$vaah.findInArrayByKey(this.assets.themes, 'id', this.item.vh_theme_id);
            this.update('active_theme', theme);
        },

        //---------------------------------------------------------------------
        setActiveTemplate: function () {
            this.$Progress.start();

            let params = this.item;
            console.log('--->', params);

            let url = this.ajax_url+'/item/'+this.item.id+'/groups/template';
            this.$vaah.ajax(url, params, this.setActiveTemplateAfter);
        },
        //---------------------------------------------------------------------
        setActiveTemplateAfter: function (data, res) {

            this.$Progress.finish();

            if(data)
            {
                this.item.template_form_groups = data;
                this.update('active_item', this.item);

            }

        },
        //---------------------------------------------------------------------
        syncSeeds: function () {
            this.$Progress.start();
            this.theme_sync_loader = true;
            let params = {
                theme_id: this.item.vh_theme_id
            };
            let url = this.ajax_url+'/sync/seeds';
            this.$vaah.ajax(url, params, this.syncSeedsAfter);
        },
        //---------------------------------------------------------------------
        syncSeedsAfter: function (data, res) {
            this.$Progress.finish();
            this.theme_sync_loader = false;
            this.reloadAssets();
            this.setActiveTheme();

        },
        //---------------------------------------------------------------------
        reload: function () {
            this.is_reload_btn_loading = true;
            this.getItem();

        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}
