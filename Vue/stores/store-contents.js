import {watch} from 'vue'
import {acceptHMRUpdate, defineStore} from 'pinia'
import qs from 'qs'
import {vaah} from '../vaahvue/pinia/vaah'

let model_namespace = 'VaahCms\Modules\Cms\\Models\\Content';

let base_url = document.getElementsByTagName('base')[0].getAttribute("href");
let ajax_url = base_url + "/cms/contents";

let empty_states = {
    query: {
        page: null,
        rows: null,
        filter: {
            q: null,
            status: null,
            trashed: null,
            sort: null,
        },
    },
    action: {
        type: null,
        items: [],
    }
};

export const useContentStore = defineStore({
    id: 'contents',
    state: () => ({
        base_url: base_url,
        ajax_url: ajax_url,
        model: model_namespace,
        assets_is_fetching: true,
        app: null,
        active_template_groups: null,
        active_template: null,
        assets: null,
        rows_per_page: [10,20,30,50,100,500],
        list: null,
        item: null,
        fillable:null,
        empty_query:empty_states.query,
        empty_action:empty_states.action,
        query: vaah().clone(empty_states.query),
        action: vaah().clone(empty_states.action),
        search: {
            delay_time: 600, // time delay in milliseconds
            delay_timer: 0 // time delay in milliseconds
        },
        route: null,
        watch_stopper: null,
        route_prefix: 'contents.',
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
        form_menu_list: [],
        users:null,
        user_list:null,
        active_theme:null,
        active_index:[0],
        field: {
            is_simple:true,
            type:{
                name:'address',
                slug:'address',
            },
            name:'Abinash',
            meta:{
                closing_tag: "</p>",
                container_closing_tag: "",
                container_opening_tag: "",
                is_hidden: false,
                opening_ta: "<p class='text'>",
            },
            content:'ashjgdjhasa',
        },
        selected_user_id:null,
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

            this.ajax_url = this.base_url+'/cms/contents/'+this.route.params.slug

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
                case 'contents.index':
                    this.view = 'large';
                    this.list_view_width = 12;
                    break;
                case 'contents.view':
                    this.view = 'medium';
                    this.list_view_width = 6;
                    break;
                default:
                    this.view = 'small';
                    this.list_view_width = 3;
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
                        this.query[key] = route.query[key]
                    }
                    this.countFilters(route.query);
                }
            }
        },
        //---------------------------------------------------------------------
        watchRoutes(route)
        {
            //watch routes
            this.watch_stopper = watch(route, (newVal,oldVal) =>
                {

                    if(this.watch_stopper && !newVal.name.includes(this.route_prefix)){
                        this.watch_stopper();

                        return false;
                    }

                    this.route = newVal;

                    this.ajax_url = this.base_url+'/cms/contents/'+this.route.params.slug

                    if(newVal.params.slug){
                        this.getList();
                    }
                    if(newVal.params.id){
                        this.getItem(newVal.params.id);
                    }

                    this.setViewAndWidth(newVal.name);

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
                if(data.rows)
                {
                    this.query.rows = data.rows;
                }

                if(this.route.params && !this.route.params.id){
                    this.item = vaah().clone(data.empty_item);
                    this.item.content_form_groups=this.assets.content_type.form_groups;
                }

                this.contentsStatusOptions();

                // this.active_theme = this.assets.default_theme;

                // this.getUser();

            }
        },
        //---------------------------------------------------------------------
        async getList() {
            document.title = this.toLabel(this.route.params.slug)+' | Contents - CMS';
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
                    this.ajax_url+'/'+id,
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
               this.setActiveTheme();
               this.selected_user_id=data.author_user;
            }else{
                this.$router.push({name: 'contents.index'});
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

                    item.content_groups = this.assets.content_type.form_groups;
                    item.template_groups = this.active_template_groups;

                    options.method = 'POST';
                    options.params = {
                        'content_form_groups': this.assets.content_type.form_groups,
                        'template_form_groups': this.active_template_groups
                    };
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

                    /*options.params.content_form_groups = this.assets.content_type.form_groups;
                    options.params.template_form_groups = [];*/
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
            console.log( type)
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
                    this.$router.push({name: 'contents.index'});
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
            if(item.status)
            {
                await this.itemAction('draft', item);
            } else{
                await this.itemAction('published', item);
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
            if(this.assets.content_type &&this.assets.content_type.form_groups){
                this.item.content_form_groups=this.assets.content_type.form_groups;
            }
            this.selected_user_id= null;


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
            this.$router.push({name: 'contents.index'})
        },
        //---------------------------------------------------------------------
        toList()
        {
            this.item = vaah().clone(this.assets.empty_item);
            this.$router.push({name: 'contents.index'})
        },
        //---------------------------------------------------------------------
        toForm()
        {

            this.item = vaah().clone(this.assets.empty_item);
            if(this.assets.content_type &&this.assets.content_type.form_groups){
                this.item.content_form_groups=this.assets.content_type.form_groups;
            }

            this.item.template_form_groups=[];

            this.getFormMenu();
            this.$router.push({name: 'contents.form'})
        },
        //---------------------------------------------------------------------
        toExternalLink(item)
        {
            window.open(item.link_prefix+item.permalink,
                '_blank');
        },
        //---------------------------------------------------------------------
        toView(item)
        {
            this.item = vaah().clone(item);
            this.$router.push({name: 'contents.view', params:{id:item.id}})
        },
        //---------------------------------------------------------------------
        toEdit(item)
        {
            this.item = item;
            this.$router.push({name: 'contents.form', params:{id:item.id}})
            this.setActiveTheme();
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
                    label: 'Publish',
                    command: async () => {
                        await this.updateList('activate')
                    }
                },
                {
                    label: 'Draft',
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
                    label: 'Mark all as published',
                    command: async () => {
                        await this.listAction('activate-all')
                    }
                },
                {
                    label: 'Mark all as draft',
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
        async getUser(){
            console.log(this.base_url);
            await vaah().ajax(
                this.ajax_url+'/users/',
                this.afterGetUser,
            );
        },
        //---------------------------------------------------------------------
        afterGetUser(data, res){
            if(res){
                this.users = res.data;
            }
        },
        //---------------------------------------------------------------------
        async searchUser(event){
            const self = this;
            clearTimeout(this.search.delay_timer);
            this.search.delay_timer = setTimeout(async function() {
                await vaah().ajax(
                    self.base_url+'/json/users/'+event.query,
                    self.afterSearchUser,
                );
            }, this.search.delay_time);
        },
        //---------------------------------------------------------------------
        async afterSearchUser(data, res){
            if(res.data){
                this.user_list = res.data;
            }
        },
        //---------------------------------------------------------------------
        expandAll() {
            this.active_index = [0,1];
        },
        collapseAll() {
            this.active_index = [];
        },
        //---------------------------------------------------------------------
        setActiveTheme () {
            let theme = vaah().findInArrayByKey(this.assets.themes,
                'id', this.item.vh_theme_id);
            this.active_theme = theme;
        },
        //---------------------------------------------------------------------
        copyCode: function (group, field,group_index = 0,field_index = null,type = 'content')
        {

            let code = "";

            if(field_index == null){
                if(group_index === 0){
                    code = "{!! get_field($data, '"+field.slug+"', '"+group.slug+"','"+type+"') !!}";
                }else{
                    code = "{!! get_field($data, '"+field.slug+"', '"+group.slug+"','"+type+"' , "+group_index+") !!}";
                }

            }else{
                code = "{!! get_field($data, '"+field.slug+"', '"+group.slug+"','"+type+"' , "+group_index+", "+field_index+") !!}";
            }

            vaah().copy(code);
        },
        //---------------------------------------------------------------------
        copyGroupCode (group,group_index = null,type = 'content')
        {
            let code = "";

            if(group_index == null){
                code = "{!! get_group($data ,'"+group.slug+"','"+type+"' ) !!}";

            }else{
                code = "{!! get_group($data ,'"+group.slug+"' ,'"+type+"' ,"+group_index+" ) !!}";

            }
            vaah().copy(code);
        },
        //---------------------------------------------------------------------
        toLabel (text)
        {
            return vaah().toLabel(text)
        },
        //---------------------------------------------------------------------
        contentsStatusOptions() {
            const result = [];
            if(this.assets && this.assets.content_type && this.assets.content_type.content_statuses){
                let content_statuses = this.assets.content_type.content_statuses;

                if(!Array.isArray(content_statuses)){
                     content_statuses = JSON.parse(content_statuses);
                }
                for (let i = 0; i < content_statuses.length; i++) {
                    const name = content_statuses[i];
                    const slug = name.toLowerCase().replace(/ /g, '-');
                    result.push({ name, slug });
                }
            }
            this.assets.content_type.content_statuses = result;
        },
        //---------------------------------------------------------------------
        setUserId(){
            if (this.selected_user_id && this.selected_user_id.id) {
                const user_id = this.selected_user_id.id;
                this.item.author = user_id;
            }else{
                this.item.author= null;
            }
        },
        //---------------------------------------------------------------------
        addField: function (field)
        {
            if(!field.content || typeof field.content === 'string'){
                let content = field.content;

                field.content = [
                    content,
                    null
                ]
            }else{
                field.content.push(null);
            }

        },
        //---------------------------------------------------------------------
        addGroup: function (arr_groups,group)
        {

            let temp_group = JSON.parse(JSON.stringify(group));


            temp_group.fields.forEach( function(  field) {

                if(field.type.slug !== "seo-meta-tags"){
                    field.content = null;
                    if(field.is_repeatable) field.content = [''];
                    field.vh_cms_form_group_index = arr_groups.length;
                    field.vh_cms_form_field_id  = null;
                }
            });

            arr_groups.push(temp_group);


        },
        //---------------------------------------------------------------------
        removeField: function (field,index)
        {
            if(field.content !== 'string'){

                if(field.content.length === 2 && field.is_repeatable != 1){
                    let val = field.content[0];
                    field.content = null;
                    field.content = val;
                }else{
                    field.content.splice(index, 1);
                }

            }

        },
        //---------------------------------------------------------------------
        removeGroup: function (arr_groups,group,index)
        {

            arr_groups.splice(index, 1);

            if(group.fields[0].vh_cms_form_field_id){
                this.$Progress.start();
                let url = this.ajax_url+'/actions/remove-group';
                let params = {
                    inputs: {
                        index: index,
                        group_id: group.fields[0].vh_cms_form_group_id,
                        content_id: this.$route.params.id
                    },
                };
                this.$vaah.ajax(url, params, this.removeGroupAfter);
            }

        },

        //---------------------------------------------------------------------
        setActiveTemplate: function () {
            this.active_template = vaah().findInArrayByKey(this.active_theme.templates,
                'id', this.item.vh_theme_template_id);

            let groups = [];

            this.active_template.groups.forEach(function ( item,index) {

                groups[index] = [item];
            });

            this.active_template_groups = groups;

            this.item.template_form_groups=this.active_template_groups;

        }
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
});



// Pinia hot reload
if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useContentStore, import.meta.hot))
}
