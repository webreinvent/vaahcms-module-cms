
import GlobalComponents from '../../vaahvue/helpers/GlobalComponents'
import ContentFieldAll from '../../vaahvue/reusable/content-fields/All'

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

            //this.addFieldsToNewItem();
        },
        //---------------------------------------------------------------------
        addFieldsToNewItem: function()
        {
            let group;
            let field_key;
            let field;

            for(let key in this.assets.groups)
            {
                group = this.assets.groups[key];

                for(field_key in group.fields)
                {
                    field = group.fields[field_key];

                    field = {
                        vh_cms_group_id: group.id,
                        vh_cms_group_sort: group.sort,
                        vh_cms_group_field_id: field.id,
                        vh_cms_group_field_sort: field.sort,
                        content: null,
                        meta: null,
                        group: group,
                        field: field,

                    };

                    console.log('--->', field);

                    this.new_item.fields.push(field);

                }




            }

            console.log('--->', this.new_item.fields);

        },
        //---------------------------------------------------------------------
        create: function () {
            //this.$Progress.start();
            let params = this.new_item;

            params.groups = this.assets.groups;

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
                    this.saveAndClose()
                }else{
                    //this.$router.push({name: 'content.types.list'});
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
            this.$router.push({name:'perm.list'});
        },
        //---------------------------------------------------------------------
    }
}
