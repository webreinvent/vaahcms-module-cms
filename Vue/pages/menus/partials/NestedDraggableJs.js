import draggable from "vuedraggable";

let namespace = 'menus';

export default {
    name: "nested-draggable",
    props: {
        items: {
            required: true,
            type: Array
        }
    },
    computed:{
        root() {return this.$store.getters['root/state']},
        page() {return this.$store.getters[namespace+'/state']},
        assets() {return this.$store.getters[namespace+'/state'].assets},
        ajax_url() {return this.$store.getters[namespace+'/state'].ajax_url},
        query_string() {return this.$store.getters[namespace+'/state'].query_string},
    },
    data()
    {
        return {
            namespace: namespace,
            labelPosition: 'on-border',
        }
    },
    components: {
        draggable
    },

    methods: {
        //--------------------------------------------------------
        update: function(name, value)
        {
            let update = {
                state_name: name,
                state_value: value,
                namespace: this.namespace,
            };
            this.$vaah.updateState(update);
        },
        //--------------------------------------------------------
        toggleMenuItemSettings: function (e) {
            console.log('--->clicked');
            let el = e.target;

            let target = $(el).closest('.dropzone-field').find('.menu-item-settings');

            $(target).toggle();

            console.log('--->', target);
        },
        //--------------------------------------------------------
        removeMenuItem: function (item) {
            this.$vaah.removeFromArray(this.items, item);
        },
        //--------------------------------------------------------
        async getAssets() {
            await this.$store.dispatch(this.namespace+'/getAssets');
        },
        //--------------------------------------------------------
        setAsHomePage: function (menu_item) {
            this.$Progress.start();

            let params = {
                inputs: menu_item.id
            };
            let url = this.ajax_url+'/actions/set-as-home-page';
            this.$vaah.ajax(url, params, this.setAsHomePageAfter);
        },
        //---------------------------------------------------------------------
        setAsHomePageAfter: function (data, res) {
            this.$Progress.finish();
            if(data){
                this.getAssets();
                this.getMenuItems();
            }
        },
        //--------------------------------------------------------
        getMenuItems: function () {
            this.$Progress.start();
            let params = {};
            let url = this.ajax_url+'/item/'+this.page.active_menu.id+'/items';
            this.$vaah.ajax(url, params, this.getMenuItemsAfter);
        },
        //---------------------------------------------------------------------
        getMenuItemsAfter: function (data, res) {
            this.$Progress.finish();
            if(data){
                this.update('active_menu_items', data.items);
                this.$router.push({name: 'menus.view', params:{id:this.page.active_menu.id}})
            }

        },
        //--------------------------------------------------------
        //--------------------------------------------------------
    }
};
