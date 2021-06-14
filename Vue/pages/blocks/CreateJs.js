import GlobalComponents from '../../vaahvue/helpers/GlobalComponents'
import ContentFieldAll from '../../vaahvue/reusable/content-fields/All'
import draggable from 'vuedraggable';
import { codemirror } from 'vue-codemirror'
import copy from "copy-to-clipboard";

// language
import 'codemirror/mode/xml/xml.js'
// theme css
import 'codemirror/lib/codemirror.css'
import 'codemirror/theme/monokai.css'
// require active-line.js
import'codemirror/addon/selection/active-line.js'
// autoCloseTags
import'codemirror/addon/edit/closetag.js'

let namespace = 'blocks';

export default {
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
        draggable,
        codemirror
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
            cm_options: {
                tabSize: 4,
                styleActiveLine: true,
                lineNumbers: true,
                lineWrapping: true,
                autoCloseTags: true,
                line: true,
                mode: 'text/html',
                theme: 'monokai'
                // more CodeMirror options...
            }
        }
    },
    watch: {
        $route(to, from) {
            this.updateView()
        },

        'new_item.name': {
            deep: true,
            handler(new_val, old_val) {

                if(new_val)
                {
                    this.new_item.slug = this.$vaah.strToSlug(new_val);
                    this.updateNewItem();
                }

            }
        },

        'new_item.plural': {
            deep: true,
            handler(new_val, old_val) {

                if(new_val)
                {
                    this.new_item.plural_slug = this.$vaah.strToSlug(new_val);
                    this.updateNewItem();
                }

            }
        },

        'new_item.singular': {
            deep: true,
            handler(new_val, old_val) {

                if(new_val)
                {
                    this.new_item.singular_slug = this.$vaah.strToSlug(new_val);
                    this.updateNewItem();
                }

            }
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
        updateView: function()
        {
            this.$store.dispatch(this.namespace+'/updateView', this.$route);
        },
        //---------------------------------------------------------------------
        onLoad: function()
        {
            this.is_content_loading = true;
            this.updateView();
            this.getAssets();
        },
        //---------------------------------------------------------------------
        async reloadRootAssets() {
            await this.$store.dispatch('root/reloadAssets');
        },
        //---------------------------------------------------------------------
        async getAssets() {
            await this.$store.dispatch(namespace+'/getAssets');
        },

        //---------------------------------------------------------------------
        addStatus: function()
        {
            this.new_item.content_statuses.push(this.new_status);
            this.new_status = null;
            this.update('new_item', this.new_item);
        },
        //---------------------------------------------------------------------
        toggleEditStatus: function(status_index)
        {
            this.edit_status_index = status_index;
            if(this.disable_status_editing)
            {
                this.disable_status_editing = false;
            } else
            {
                this.disable_status_editing = true;
            }
        },
        //---------------------------------------------------------------------
        create: function () {
            this.$Progress.start();
            let params = this.new_item;

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

                if(this.local_action === 'save-and-close')
                {
                    this.saveAndClose();
                }else{
                    //this.$router.push({name: 'blocks.list'});
                    this.saveAndNew();

                    // this.$root.$emit('eReloadItem');
                }

                this.reloadRootAssets();

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
            this.$router.push({name:'blocks.list'});
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
                name: null,
                slug: null,
                vh_theme_id: "",
                vh_theme_location_id: "",
                content: "",
                is_published: null,
            };
            return new_item;
        },
    //---------------------------------------------------------------------
        setActiveTheme: function () {

            this.new_item.vh_theme_location_id = '';
            this.update('new_item', this.new_item);

            this.page.active_theme = {
                'locations':[]
            };

            this.update('active_theme', this.page.active_theme);

            if(this.new_item.vh_theme_id){
                let theme = this.$vaah.findInArrayByKey(this.assets.themes,
                    'id', this.new_item.vh_theme_id);

                this.update('active_theme', theme);
            }


        },

        //---------------------------------------------------------------------
        copyCode: function (item,id)
        {
            let code = "";

            let location = this.$vaah.findInArrayByKey(item, 'id', id);

            if(location){
                code = "{!! vh_location_blocks('"+location.slug+"') !!}";
            }

            copy(code);

            this.$buefy.toast.open({
                message: 'Copied!',
                type: 'is-success'
            });
        },
        //---------------------------------------------------------------------
    }
}
