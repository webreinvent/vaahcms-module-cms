import GlobalComponents from '../../vaahvue/helpers/GlobalComponents'
import ContentFieldAll from '../../vaahvue/reusable/content-fields/All'

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
        item() {return this.$store.getters[namespace+'/state'].active_item},
    },
    components:{
        ...GlobalComponents,
        ContentFieldAll,
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
            groups: null,
            theme_sync_loader: false
        }
    },
    watch: {
        $route(to, from) {
            this.updateStore();
            this.getItem();
        }
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
            let url = this.ajax_url+'/item/'+this.$route.params.id;
            this.$vaah.ajax(url, this.params, this.getItemAfter);
        },
        //---------------------------------------------------------------------
        getItemAfter: function (data, res) {
            this.$Progress.finish();
            this.is_content_loading = false;

            if(data)
            {
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

            let params = this.item;
            console.log('--->', params);

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
                    this.saveAndClose()
                }else{
                    //this.$router.push({name: 'perm.view', params:{id:this.id}});
                    this.$root.$emit('eReloadItem');
                }

            }

        },
        //---------------------------------------------------------------------
        setLocalAction: function (action) {
            this.local_action = action;
            this.store();
        },
        //---------------------------------------------------------------------
        saveAndClose: function () {
            this.update('active_item', null);
            this.$router.push({name:'contents.list'});
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
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}
