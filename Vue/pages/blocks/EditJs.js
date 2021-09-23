import  GlobalComponents from '../../vaahvue/helpers/GlobalComponents'
import ContentFieldAll from '../../vaahvue/reusable/content-fields/All'
import draggable from 'vuedraggable';
import { codemirror } from 'vue-codemirror'


// language
import 'codemirror/mode/xml/xml.js'
// theme css
import 'codemirror/lib/codemirror.css'
import 'codemirror/theme/monokai.css'
// require active-line.js
import'codemirror/addon/selection/active-line.js'
// autoCloseTags
import'codemirror/addon/edit/closetag.js'
import copy from "copy-to-clipboard";

let namespace = 'blocks';

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
            edit_status_index: null,
            status: null,
            disable_status_editing: true,
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

        'item.name': {
            deep: true,
            handler(new_val, old_val) {

                if(new_val)
                {
                    this.item.slug = this.$vaah.strToSlug(new_val);
                    this.updateNewItem();
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
        updateView: function()
        {
            this.$store.dispatch(this.namespace+'/updateView', this.$route);
        },
        //---------------------------------------------------------------------
        updateNewItem: function()
        {
            let update = {
                state_name: 'item',
                state_value: this.item,
                namespace: this.namespace,
            };
            this.$vaah.updateState(update);
        },
        //---------------------------------------------------------------------
        onLoad: function()
        {
            this.is_content_loading = true;

            this.updateView();
            this.getAssets();
            this.getItem();
        },
        //---------------------------------------------------------------------
        async getAssets() {
            await this.$store.dispatch(namespace+'/getAssets');
        },
        //---------------------------------------------------------------------
        getItem: function () {
            this.$Progress.start();
            this.params = {};
            let url = this.ajax_url+'/item/'+this.$route.params.id;
            this.$vaah.ajaxGet(url, this.params, this.getItemAfter);
        },
        //---------------------------------------------------------------------
        getItemAfter: function (data, res) {
            this.$Progress.finish();
            this.is_content_loading = false;

            if(data)
            {
                this.title = data.name;
                this.update('active_item', data);
                this.setActiveTheme(false);

            } else
            {
                //if item does not exist or delete then redirect to list
                this.update('active_item', null);
                this.$router.push({name: 'blocks.list'});
            }
        },
        //---------------------------------------------------------------------
        store: function () {
            this.$Progress.start();

            let params =  this.item;

            let url = this.ajax_url+'/store/'+this.item.id;
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
                    this.$router.push({name: 'blocks.view', params:{id:this.id}});
                    this.$root.$emit('eReloadItem');
                }

                this.reloadRootAssets();

            }

        },
        //---------------------------------------------------------------------
        async reloadRootAssets() {
            await this.$store.dispatch('root/reloadAssets');
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

        addStatus: function()
        {
            this.item.content_statuses.push(this.status);
            this.status = null;
            this.update('item', this.item);
        },
        //---------------------------------------------------------------------
        setActiveTheme: function (set_null = true) {

            if(set_null){
                this.item.vh_theme_location_id = '';
            }

            this.update('item', this.item);

            this.page.active_theme = {
                'locations':[]
            };

            this.update('active_theme', this.page.active_theme);

            if(this.item.vh_theme_id && this.assets && this.assets.themes){
                let theme = this.$vaah.findInArrayByKey(this.assets.themes,
                    'id', this.item.vh_theme_id);

                this.update('active_theme', theme);
            }


        },
        //---------------------------------------------------------------------
        resetActiveItem: function () {
            this.update('active_item', null);
            this.$router.push({name:'blocks.list'});
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
    }
}
