import copy from "copy-to-clipboard";

let namespace = 'blocks';
export default {
    computed: {
        root() {return this.$store.getters['root/state']},
        page() {return this.$store.getters[namespace+'/state']},
        ajax_url() {return this.$store.getters[namespace+'/state'].ajax_url},
        query_string() {return this.$store.getters[namespace+'/state'].query_string},
    },
    components:{

    },

    data()
    {
        let obj = {
            namespace: namespace,
            icon_copy: "<b-icon icon='envelope' size='is-small'></b-icon>"
        };

        return obj;
    },
    created() {
    },
    mounted(){

    },

    watch: {

    },
    methods: {
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

        //---------------------------------------------------------------------
        setRowClass: function(row, index)
        {

            if(this.page.active_item && row.id == this.page.active_item.id)
            {
                return 'is-selected';
            }

            if(row.deleted_at != null)
            {
                return 'is-danger';
            }

        },
        //---------------------------------------------------------------------
        setActiveItem: function (item) {
            this.update('active_item', item);
            this.$router.push({name: 'blocks.view', params:{id:item.id}})
        },
        //---------------------------------------------------------------------
        changeStatus: function (id) {
            this.$Progress.start();
            let url = this.ajax_url+'/actions/bulk-change-status';
            let params = {
                inputs: [id],
                data: null
            };
            this.$vaah.ajax(url, params, this.changeStatusAfter);
        },
        //---------------------------------------------------------------------
        changeStatusAfter: function (data,res) {
            this.$emit('eReloadList');
            this.reloadRootAssets();
            this.update('is_list_loading', false);

        },
        //---------------------------------------------------------------------
        async reloadRootAssets() {
            await this.$store.dispatch('root/reloadAssets');
        },
        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        copiedData: function (data) {

            this.$vaah.toastSuccess(['copied']);

            // alertify.success('copied');

            this.$vaah.console(data, 'copied data');

        },
        //---------------------------------------------------------------------
        copyCode: function (item,has_location = false)
        {
            let code = "";



            if(has_location){
                if(item && item.theme_location && item.theme_location.slug){
                    code = "{!! vh_location_blocks('"+item.theme_location.slug+"') !!}";
                }
            }else{
                if(item && item.slug){
                    code = "{!! vh_block('"+item.slug+"') !!}";
                }
            }



            copy(code);

            this.$buefy.toast.open({
                message: 'Copied!',
                type: 'is-success'
            });
        },
        //---------------------------------------------------------------------
        toEdit: function (item) {
            this.update('active_item', item);
            this.$router.push({name:'blocks.edit', params:{id:item.id}});

        }
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}
