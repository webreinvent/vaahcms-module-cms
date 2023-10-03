import {watch} from 'vue'
import {acceptHMRUpdate, defineStore} from 'pinia'
import qs from 'qs'
import {vaah} from '../vaahvue/pinia/vaah'

let model_namespace = 'VaahCms\Modules\Cms\\Models\\Menu';


let base_url = document.getElementsByTagName('base')[0].getAttribute("href");
let ajax_url = base_url + "/cms/menus";

let empty_states = {
    query: {
        vh_theme_id: null,
        vh_theme_location_id: null,
        vh_menu_id: null,
        filter: {
            q: null,
            is_active: null,
            trashed: null,
            sort: null,
        },
    },
    action: {
        type: null,
        items: [],
    }
};

export const useMenuStore = defineStore({
    id: 'menus',
    state: () => ({
        base_url: base_url,
        ajax_url: ajax_url,
        model: model_namespace,
        assets_is_fetching: true,
        app: null,
        active_menu: null,
        title: null,
        content_list: [],
        filtered_content_list: [],
        active_theme: null,
        active_location: null,
        assets: null,
        rows_per_page: [10,20,30,50,100,500],
        list: null,
        item: null,
        new_item: {
            name:null
        },
        fillable:null,
        active_menu_items:null,
        empty_query:empty_states.query,
        empty_action:empty_states.action,
        query: vaah().clone(empty_states.query),
        action: vaah().clone(empty_states.action),
        search: {
            delay_time: 600, // time delay in milliseconds
            delay_timer: 0 // time delay in milliseconds
        },
        route: null,
        view: 'large',
        show_filters: false,
        list_view_width: 12,
        form: {
            type: 'Create',
            action: null,
            is_button_loading: null
        },
        is_list_loading: null,
        count_filters: 0,
        list_selected_menu: [],
        list_bulk_menu: [],
        item_menu_list: [],
        item_menu_state: null,
        content_search: null,
        form_menu_list: [],
        menu_types:[
            {
                name: 'Internal Link',
                uri: "",
                type: 'internal-link',
            },
            {
                name: 'External Link',
                uri: "",
                type: 'external-link',
            }
        ],
        menu_settings:true,
    }),
    getters: {

    },
    actions: {
        //---------------------------------------------------------------------
        async onLoad(route)
        {
            /**
             * Set initial routes
             */
            this.route = route;

            /**
             * Update with view and list css column number
             */
            this.setViewAndWidth(route.name);

            /**
             * Update query state with the query parameters of url
             */
            this.updateQueryFromUrl(route);

        },
        //---------------------------------------------------------------------
        setViewAndWidth(route_name)
        {
            switch(route_name)
            {
                case 'menus.index':
                    this.view = 'large';
                    this.list_view_width = 12;
                    break;
                default:
                    this.view = 'small';
                    this.list_view_width = 6;
                    break
            }
        },
        //---------------------------------------------------------------------
        async updateQueryFromUrl(route)
        {
            if(route.query)
            {
                if(Object.keys(route.query).length > 0)
                {
                    for(let key in route.query)
                    {
                        this.query[key] = route.query[key];

                        if(key === 'vh_theme_id' || key === 'vh_theme_location_id'
                            || key === 'vh_menu_id'){
                            this.query[key] = parseInt(route.query[key]);
                        }
                    }
                    this.countFilters(route.query);
                }
            }
        },
        //---------------------------------------------------------------------
        watchRoutes(route)
        {
            //watch routes
            watch(route.params, (newVal,oldVal) =>
                {

                    console.log(newVal , oldVal);
                    /*this.route = newVal;
                    if(newVal.params.id != oldVal.params.id){
                        this.getItem(newVal.params.id);
                    }
                    this.setViewAndWidth(newVal.name);*/
                }, { deep: true }
            )
        },
        //---------------------------------------------------------------------
        watchStates()
        {
            watch(this.query.filter, (newVal,oldVal) =>
                {
                    this.delayedSearch();
                },{deep: true}
            )
        },
        //---------------------------------------------------------------------
        watchItem()
        {
            if(this.item){
                    watch(() => this.item.name, (newVal,oldVal) =>
                        {
                            if(newVal && newVal !== "")
                            {
                                this.item.name = vaah().capitalising(newVal);
                                this.item.slug = vaah().strToSlug(newVal);
                            }
                        },{deep: true}
                    )
                }
        },
        //---------------------------------------------------------------------
        async getAssets() {

            if(this.assets_is_fetching === true){
                this.assets_is_fetching = false;

                await vaah().ajax(
                    this.ajax_url+'/assets',
                    this.afterGetAssets,
                );
            }
        },
        //---------------------------------------------------------------------
        afterGetAssets(data, res)
        {
            if(data)
            {
                this.assets = data;



                if(this.route.params && !this.route.params.id){
                    this.item = vaah().clone(data.empty_item);
                }

                this.setActiveItems();
            }
        },
        //---------------------------------------------------------------------
        setActiveItems()
        {
            if(this.query.vh_theme_id){
                this.active_theme = vaah().findInArrayByKey(this.assets.themes,
                    'id', this.query.vh_theme_id);
            }

            if(this.query.vh_theme_location_id){

                this.active_location = vaah().findInArrayByKey(this.active_theme.locations,
                    'id', this.query.vh_theme_location_id);

            }

            this.setActiveMenu();

            this.getContentList();
        },
        //---------------------------------------------------------------------
        async getList() {
            let options = {
                query: vaah().clone(this.query)
            };
            await vaah().ajax(
                this.ajax_url,
                this.afterGetList,
                options
            );
        },
        //---------------------------------------------------------------------
        afterGetList: function (data, res)
        {
            if(data)
            {
                this.list = data;
            }
        },
        //---------------------------------------------------------------------

        async getItem(id) {
            if(id){
                await vaah().ajax(
                    ajax_url+'/'+id,
                    this.getItemAfter
                );
            }
        },
        //---------------------------------------------------------------------
        async getItemAfter(data, res)
        {
            if(data)
            {
                this.item = data;
                this.title = this.item.name;
            }else{
                this.$router.push({name: 'menus.index',query:this.query});
            }
            await this.getItemMenu();
            await this.getFormMenu();

        },
        //---------------------------------------------------------------------
        isListActionValid()
        {

            if(!this.action.type)
            {
                vaah().toastErrors(['Select an action type']);
                return false;
            }

            if(this.action.items.length < 1)
            {
                vaah().toastErrors(['Select records']);
                return false;
            }

            return true;
        },
        //---------------------------------------------------------------------
        async updateList(type = null){

            if(!type && this.action.type)
            {
                type = this.action.type;
            } else{
                this.action.type = type;
            }

            if(!this.isListActionValid())
            {
                return false;
            }


            let method = 'PUT';

            switch (type)
            {
                case 'delete':
                    method = 'DELETE';
                    break;
            }

            let options = {
                params: this.action,
                method: method,
                show_success: false
            };
            await vaah().ajax(
                this.ajax_url,
                this.updateListAfter,
                options
            );
        },
        //---------------------------------------------------------------------
        async updateListAfter(data, res) {
            if(data)
            {
                this.action = vaah().clone(this.empty_action);
                await this.getList();
            }
        },
        //---------------------------------------------------------------------
        async listAction(type = null){

            if(!type && this.action.type)
            {
                type = this.action.type;
            } else{
                this.action.type = type;
            }

            let url = this.ajax_url+'/action/'+type
            let method = 'PUT';

            switch (type)
            {
                case 'delete':
                    url = this.ajax_url
                    method = 'DELETE';
                    break;
                case 'delete-all':
                    method = 'DELETE';
                    break;
            }

            let options = {
                params: this.action,
                method: method,
                show_success: false
            };
            await vaah().ajax(
                url,
                this.updateListAfter,
                options
            );
        },
        //---------------------------------------------------------------------
        async itemAction(type, item=null){
            if(!item)
            {
                item = this.item;
            }

            this.form.action = type;

            let ajax_url = this.ajax_url;

            let options = {
                method: 'post',
            };

            /**
             * Learn more about http request methods at
             * https://www.youtube.com/watch?v=tkfVQK6UxDI
             */
            switch (type)
            {
                /**
                 * Create a record, hence method is `POST`
                 * https://docs.vaah.dev/guide/laravel.html#create-one-or-many-records
                 */
                case 'create-and-new':
                case 'create-and-close':
                case 'create-and-clone':
                    options.method = 'POST';
                    options.params = item;
                    break;

                /**
                 * Update a record with many columns, hence method is `PUT`
                 * https://docs.vaah.dev/guide/laravel.html#update-a-record-update-soft-delete-status-change-etc
                 */
                case 'save':
                case 'save-and-close':
                case 'save-and-clone':
                    options.method = 'PUT';
                    options.params = item;
                    ajax_url += '/'+item.id
                    break;
                /**
                 * Delete a record, hence method is `DELETE`
                 * and no need to send entire `item` object
                 * https://docs.vaah.dev/guide/laravel.html#delete-a-record-hard-deleted
                 */
                case 'delete':
                    options.method = 'DELETE';
                    ajax_url += '/'+item.id
                    break;
                /**
                 * Update a record's one column or very few columns,
                 * hence the method is `PATCH`
                 * https://docs.vaah.dev/guide/laravel.html#update-a-record-update-soft-delete-status-change-etc
                 */
                default:
                    options.method = 'PATCH';
                    ajax_url += '/'+item.id+'/action/'+type;
                    break;
            }

            await vaah().ajax(
                ajax_url,
                this.itemActionAfter,
                options
            );
        },
        //---------------------------------------------------------------------
        async itemActionAfter(data, res)
        {
            if(data)
            {
                this.item = data;
                await this.getList();
                await this.formActionAfter();
                this.getItemMenu();
            }
        },
        //---------------------------------------------------------------------
        async formActionAfter ()
        {
            switch (this.form.action)
            {
                case 'create-and-new':
                case 'save-and-new':
                    this.setActiveItemAsEmpty();
                    break;
                case 'create-and-close':
                case 'save-and-close':
                    this.setActiveItemAsEmpty();
                    this.$router.push({name: 'menus.index',query:this.query});
                    break;
                case 'save-and-clone':
                    this.item.id = null;
                    break;
                case 'trash':
                    this.item = null;
                    break;
                case 'delete':
                    this.item = null;
                    this.toList();
                    break;
            }
        },
        //---------------------------------------------------------------------
        async toggleIsActive(item)
        {
            if(item.is_active)
            {
                await this.itemAction('activate', item);
            } else{
                await this.itemAction('deactivate', item);
            }
        },
        //---------------------------------------------------------------------
        async paginate(event) {
            this.query.page = event.page+1;
            await this.getList();
        },
        //---------------------------------------------------------------------
        async reload()
        {
            await this.getAssets();
            await this.getList();
        },
        //---------------------------------------------------------------------
        async getFaker () {
            let params = {
                model_namespace: this.model,
                except: this.assets.fillable.except,
            };

            let url = this.base_url+'/faker';

            let options = {
                params: params,
                method: 'post',
            };

            await vaah().ajax(
                url,
                this.getFakerAfter,
                options
            );
        },
        //---------------------------------------------------------------------
        getFakerAfter: function (data, res) {
            if(data)
            {
                let self = this;
                Object.keys(data.fill).forEach(function(key) {
                    self.item[key] = data.fill[key];
                });
            }
        },

        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        onItemSelection(items)
        {
            this.action.items = items;
        },
        //---------------------------------------------------------------------
        setActiveItemAsEmpty()
        {
            this.item = vaah().clone(this.assets.empty_item);
        },
        //---------------------------------------------------------------------
        confirmDelete()
        {
            if(this.action.items.length < 1)
            {
                vaah().toastErrors(['Select a record']);
                return false;
            }
            this.action.type = 'delete';
            vaah().confirmDialogDelete(this.listAction);
        },
        //---------------------------------------------------------------------
        confirmDeleteAll()
        {
            this.action.type = 'delete-all';
            vaah().confirmDialogDelete(this.listAction);
        },
        //---------------------------------------------------------------------
        async delayedSearch()
        {
            let self = this;
            this.query.page = 1;
            this.action.items = [];
            clearTimeout(this.search.delay_timer);
            this.search.delay_timer = setTimeout(async function() {
                await self.updateUrlQueryString(self.query);
                await self.getList();
            }, this.search.delay_time);
        },
        //---------------------------------------------------------------------
        async updateUrlQueryString(query)
        {
            //remove reactivity from source object
            query = vaah().clone(query)

            //create query string
            let query_string = qs.stringify(query, {
                skipNulls: true,
            });
            let query_object = qs.parse(query_string);

            if(query_object.filter){
                query_object.filter = vaah().cleanObject(query_object.filter);
            }

            //reset url query string
            await this.$router.replace({query: null});

            //replace url query string
            await this.$router.replace({query: query_object});

            //update applied filters
            this.countFilters(query_object);

        },
        //---------------------------------------------------------------------
        countFilters: function (query)
        {
            this.count_filters = 0;
            if(query && query.filter)
            {
                let filter = vaah().cleanObject(query.filter);
                this.count_filters = Object.keys(filter).length;
            }
        },
        //---------------------------------------------------------------------
        async clearSearch()
        {
            this.query.filter.q = null;
            await this.updateUrlQueryString(this.query);
            await this.getList();
        },
        //---------------------------------------------------------------------
        async resetQuery()
        {
            //reset query strings
            await this.resetQueryString();

            //reload page list
            await this.getList();
        },
        //---------------------------------------------------------------------
        async resetQueryString()
        {
            for(let key in this.query.filter)
            {
                this.query.filter[key] = null;
            }
            await this.updateUrlQueryString(this.query);
        },
        //---------------------------------------------------------------------
        closeForm()
        {
            this.$router.push({name: 'menus.index',query:this.query})
        },
        //---------------------------------------------------------------------
        toList()
        {
            this.item = vaah().clone(this.assets.empty_item);
            this.$router.push({name: 'menus.index',query:this.query})
        },
        //---------------------------------------------------------------------
        toForm()
        {
            this.item = vaah().clone(this.assets.empty_item);
            this.getFormMenu();
            this.$router.push({name: 'menus.form',query:this.query})
        },
        //---------------------------------------------------------------------
        toView(item)
        {
            this.item = vaah().clone(item);
            this.$router.push({name: 'menus.view', params:{id:item.id},query:this.query})
        },
        //---------------------------------------------------------------------
        toEdit(item)
        {
            this.item = item;
            this.$router.push({name: 'menus.form', params:{id:item.id},query:this.query})
        },
        //---------------------------------------------------------------------
        isViewLarge()
        {
            return this.view === 'large';
        },
        //---------------------------------------------------------------------
        getIdWidth()
        {
            let width = 50;

            if(this.list && this.list.total)
            {
                let chrs = this.list.total.toString();
                chrs = chrs.length;
                width = chrs*40;
            }

            return width+'px';
        },
        //---------------------------------------------------------------------
        getActionWidth()
        {
            let width = 100;
            if(!this.isViewLarge())
            {
                width = 80;
            }
            return width+'px';
        },
        //---------------------------------------------------------------------
        getActionLabel()
        {
            let text = null;
            if(this.isViewLarge())
            {
                text = 'Actions';
            }

            return text;
        },
        //---------------------------------------------------------------------
        async getListSelectedMenu()
        {
            this.list_selected_menu = [
                {
                    label: 'Activate',
                    command: async () => {
                        await this.updateList('activate')
                    }
                },
                {
                    label: 'Deactivate',
                    command: async () => {
                        await this.updateList('deactivate')
                    }
                },
                {
                    separator: true
                },
                {
                    label: 'Trash',
                    icon: 'pi pi-times',
                    command: async () => {
                        await this.updateList('trash')
                    }
                },
                {
                    label: 'Restore',
                    icon: 'pi pi-replay',
                    command: async () => {
                        await this.updateList('restore')
                    }
                },
                {
                    label: 'Delete',
                    icon: 'pi pi-trash',
                    command: () => {
                        this.confirmDelete()
                    }
                },
            ]

        },
        //---------------------------------------------------------------------
        getListBulkMenu()
        {
            this.list_bulk_menu = [
                {
                    label: 'Mark all as active',
                    command: async () => {
                        await this.listAction('activate-all')
                    }
                },
                {
                    label: 'Mark all as inactive',
                    command: async () => {
                        await this.listAction('deactivate-all')
                    }
                },
                {
                    separator: true
                },
                {
                    label: 'Trash All',
                    icon: 'pi pi-times',
                    command: async () => {
                        await this.listAction('trash-all')
                    }
                },
                {
                    label: 'Restore All',
                    icon: 'pi pi-replay',
                    command: async () => {
                        await this.listAction('restore-all')
                    }
                },
                {
                    label: 'Delete All',
                    icon: 'pi pi-trash',
                    command: async () => {
                        this.confirmDeleteAll();
                    }
                },
            ];
        },
        //---------------------------------------------------------------------
        getItemMenu()
        {
            let item_menu = [];

            if(this.item && this.item.deleted_at)
            {

                item_menu.push({
                    label: 'Restore',
                    icon: 'pi pi-refresh',
                    command: () => {
                        this.itemAction('restore');
                    }
                });
            }

            if(this.item && this.item.id && !this.item.deleted_at)
            {
                item_menu.push({
                    label: 'Trash',
                    icon: 'pi pi-times',
                    command: () => {
                        this.itemAction('trash');
                    }
                });
            }

            item_menu.push({
                label: 'Delete',
                icon: 'pi pi-trash',
                command: () => {
                    this.confirmDeleteItem('delete');
                }
            });

            this.item_menu_list = item_menu;
        },
        //---------------------------------------------------------------------
        confirmDeleteItem()
        {
            this.form.type = 'delete';
            vaah().confirmDialogDelete(this.confirmDeleteItemAfter);
        },
        //---------------------------------------------------------------------
        confirmDeleteItemAfter()
        {
            this.itemAction('delete', this.item);
        },
        //---------------------------------------------------------------------
        async getFormMenu()
        {
            let form_menu = [];

            if(this.item && this.item.id)
            {
                form_menu = [
                    {
                        label: 'Save & Close',
                        icon: 'pi pi-check',
                        command: () => {

                            this.itemAction('save-and-close');
                        }
                    },
                    {
                        label: 'Save & Clone',
                        icon: 'pi pi-copy',
                        command: () => {

                            this.itemAction('save-and-clone');

                        }
                    },
                    {
                        label: 'Trash',
                        icon: 'pi pi-times',
                        command: () => {
                            this.itemAction('trash');
                        }
                    },
                    {
                        label: 'Delete',
                        icon: 'pi pi-trash',
                        command: () => {
                            this.confirmDeleteItem('delete');
                        }
                    },
                ];

            } else{
                form_menu = [
                    {
                        label: 'Create & Close',
                        icon: 'pi pi-check',
                        command: () => {
                            this.itemAction('create-and-close');
                        }
                    },
                    {
                        label: 'Create & Clone',
                        icon: 'pi pi-copy',
                        command: () => {

                            this.itemAction('create-and-clone');

                        }
                    },
                    {
                        label: 'Reset',
                        icon: 'pi pi-refresh',
                        command: () => {
                            this.setActiveItemAsEmpty();
                        }
                    }
                ];
            }

            form_menu.push({
                label: 'Fill',
                icon: 'pi pi-pencil',
                command: () => {
                    this.getFaker();
                }
            },)

            this.form_menu_list = form_menu;

        },
        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        setActiveTheme: function () {

            this.active_menu = null;

            this.query.vh_theme_location_id = null;
            this.query.vh_menu_id = null;

            this.active_theme = null;
            this.active_location = null;

            if(this.query.vh_theme_id){
                this.active_theme = vaah().findInArrayByKey(this.assets.themes,
                    'id', this.query.vh_theme_id);
            }

            this.updateUrlQueryString(this.query);


        },


        //---------------------------------------------------------------------
        copyLocationCode: function ()
        {
            let code = "";

            let location = vaah().findInArrayByKey(this.active_theme.locations,
                'id', this.query.vh_theme_location_id);

            if(location){
                code = "{!! vh_location('"+location.slug+"', true) !!}";
            }

            vaah().copy(code);
        },

        //---------------------------------------------------------------------
        setActiveLocation: function () {

            this.query.vh_menu_id = null;

            this.active_location = null;
            this.active_menu = null;

            if(this.query.vh_theme_location_id){

                this.active_location = vaah().findInArrayByKey(this.active_theme.locations,
                    'id', this.query.vh_theme_location_id);

            }

            this.updateUrlQueryString(this.query);

        },

        //---------------------------------------------------------------------
        setActiveMenu: function () {

            this.updateUrlQueryString(this.query);

            this.active_menu =  null;

            if(this.query.vh_menu_id){
                this.active_menu = vaah().findInArrayByKey(this.active_location.menus,
                    'id', this.query.vh_menu_id);

                this.getItem(this.active_menu.id);
                this.getMenuItems();
            }

        },


        //---------------------------------------------------------------------
        getMenuItems: function () {

            let options = {

            };

            vaah().ajax(
                this.ajax_url+'/item/'+this.active_menu.id,
                this.getMenuItemsAfter,
                options
            );

        },
        //---------------------------------------------------------------------
        getMenuItemsAfter: function (data, res) {

            if(data){
                this.active_menu_items = data;

                this.$router.push({name: 'menus.view', params:{id:this.active_menu.id},query:this.query})
            }

        },


        //---------------------------------------------------------------------
        createItem: function () {

            this.new_item.vh_theme_location_id = this.active_location.id;

            let options = {
                params:this.new_item,
                method:'post'
            };

            vaah().ajax(
                this.ajax_url,
                this.createItemAfter,
                options
            );

        },
        //---------------------------------------------------------------------
        createItemAfter: function (data, res) {

            if(data){
                this.new_item.name = null;
                this.item = data.item;
                this.query.vh_menu_id = data.item.id;

                this.assets = data.assets.data;
                this.setActiveItems();
            }

        },



        //---------------------------------------------------------------------
        storeItem: function () {

            let params = this.item;
            params.items = this.active_menu_items;

            let options = {
                params:params,
                method:'put'
            };

            vaah().ajax(
                this.ajax_url+'/item/'+this.active_menu.id+'/store',
                this.storeItemAfter,
                options
            );

        },
        //---------------------------------------------------------------------
        storeItemAfter: function (data, res) {

            if(data){
                this.getItem(data.menu.id);
                this.getMenuItems();

                this.assets_is_fetching = true;
                this.getAssets();
            }

        },


        //---------------------------------------------------------------------
        getContentList: function () {


            let options = {

            };

            vaah().ajax(
                this.ajax_url+'/content/list',
                this.getContentListAfter,
                options
            );

        },
        //---------------------------------------------------------------------
        getContentListAfter: function (data, res) {
            if(data){

                this.content_list = data;
                this.filtered_content_list = data;

            }

        },

        //---------------------------------------------------------------------
        cloneField: function({ id, name })
        {
            let item = {
                type: 'content',
                vh_content_id: id,
                name: name,
                content: {
                    name: name
                },
                vh_menu_id: this.active_menu.id,
                attr_id: null,
                attr_class: null,
                attr_target_blank: null,
                child: [],
            };



            return item;
        },

        //---------------------------------------------------------------------
        customCloneField: function({ id, name, type })
        {
            let item = {
                type: type,
                name: name,
                uri: "",
                attr_id: null,
                attr_class: null,
                attr_target_blank: null,
                vh_menu_id: this.active_menu.id,
                child: [],
            };

            return item;
        },
        //---------------------------------------------------------------------
        searchContent(){

            if (this.content_search.trim().length == 0) {
                console.log(this.content_search.trim().length);
                this.filtered_content_list = this.content_list;
            }
            else {
                this.filtered_content_list = this.content_list.filter((list) => {
                    return list.name.toLowerCase().match(this.content_search.toLowerCase());
                });
            }
        },
        //---------------------------------------------------------------------
        deleteItem () {

            let options = {
                method:'post',
                params:{
                    inputs:[this.item.id]
                }
            };

            vaah().ajax(
                this.ajax_url+'/actions/bulk-delete',
                this.deleteItemAfter,
                options
            );
        },
        //---------------------------------------------------------------------
        deleteItemAfter (data, res) {
            if(data){
                this.query.vh_menu_id = null;
                this.toList();

            }

        },
        //---------------------------------------------------------------------
        setAsHomePage (id) {

            let options = {
                method:'post',
                params:{
                    inputs:id
                }
            };

            vaah().ajax(
                this.ajax_url+'/actions/set-as-home-page',
                this.setAsHomePageAfter,
                options
            );
        },
        //---------------------------------------------------------------------
        setAsHomePageAfter (data, res) {
            if(data){
                this.getMenuItems();

            }

        },
        //---------------------------------------------------------------------
        removeAt(item,idx) {
            item.splice(idx, 1);
        },
    }
});



// Pinia hot reload
if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useMenuStore, import.meta.hot))
}
