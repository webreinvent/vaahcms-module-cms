import {VaahHelper as Vaah} from "../../vaahvue/helpers/VaahHelper";

//---------Variables
let base_url = document.getElementsByTagName('base')[0].getAttribute("href");
let debug = document.getElementById('debug').getAttribute('content');
//---------/Variables

let json_url = base_url+"/backend/cms/json";

export default {
    namespaced: true,
    state: {
        debug: debug,
        content_slug: null,
        base_url: base_url,
        ajax_url: null,
        json_url: json_url,
        assets: null,
        assets_is_fetching: null,
        assets_reload: false,
        list: null,
        selected_id: null,
        status_list: null,
        list_is_empty: false,
        is_list_loading: false,
        list_view: true,
        active_item: null,
        is_item_loading: false,
        show_filters: false,
        query_string: {
            page: 1,
            q: null,
            trashed: null,
            filter: null,
            sort_by: null,
            sort_order: 'desc',
        },
        bulk_action:{
            selected_items: [],
            data: '',
            action: null,
        },
        active_theme: null,
        active_theme_templates: null,
        active_template_groups: null,
        active_template: null,
        new_item:{
            parent_id: null,
            vh_cms_content_type_id: null,
            vh_theme_id: '',
            vh_theme_template_id: '',
            name: null,
            slug: null,
            is_published_at: null,
            status: "",
            total_comments: null,
            meta: null,
            fields: [
            ],
        },

    },
    //=========================================================================
    mutations:{
        updateState: function (state, payload) {
            state[payload.key] = payload.value;
        },
        //-----------------------------------------------------------------
    },
    //=========================================================================
    actions:{
        //-----------------------------------------------------------------
        async getAssets({ state, commit, dispatch, getters }) {

            if(!state.assets_is_fetching || !state.assets || state.assets_reload == true)
            {
                let payload = {
                    key: 'assets_is_fetching',
                    value: true
                };
                commit('updateState', payload);

                let url = state.ajax_url+'/assets';

                let params = {};
                let data = await Vaah.ajaxGet(url, params);
                let assets = data.data.data;
                payload = {
                    key: 'assets',
                    value: assets
                };

                commit('updateState', payload);


                payload = {
                    key: 'active_theme',
                    value: assets.default_theme
                };

                commit('updateState', payload);

                payload = {
                    key: 'active_template',
                    value: assets.default_template
                };

                commit('updateState', payload);

                if(assets.default_template.groups)
                {
                    payload = {
                        key: 'active_template_groups',
                        value: assets.default_template.groups
                    };
                    commit('updateState', payload);
                }


                state.new_item.vh_theme_id = assets.default_theme.id;
                state.new_item.vh_theme_template_id = assets.default_template.id;

                payload = {
                    key: 'new_item',
                    value: state.new_item
                };

                commit('updateState', payload);


                payload = {
                    key: 'assets_reload',
                    value: false
                };
                commit('updateState', payload);

            }

        },
        //-----------------------------------------------------------------
        updateStore({ state, commit, dispatch, getters }, payload) {
            let list_view;
            let update;


            if(payload && payload.name && payload.name == 'contents.list')
            {
                list_view = 'large';

                update = {
                    key: 'assets_reload',
                    value: true
                };

                commit('updateState', update);

                update = {
                    key: 'active_item',
                    value: null
                };

                commit('updateState', update);

            }

            if(payload.name == 'contents.edit')
            {
                list_view = 'small';
            };

            if(payload.name == 'contents.create')
            {
                list_view = 'small';
            };

            if(payload.name == 'contents.view')
            {
                list_view = 'medium';
            };

            update = {
                key: 'list_view',
                value: list_view
            };

            commit('updateState', update);


            //update ajax url
            let ajax = state.base_url+"/backend/cms/contents/"+payload.params.slug;
            update = {
                key: 'ajax_url',
                value: ajax
            };

            commit('updateState', update);

            update = {
                key: 'content_slug',
                value: payload.params.slug
            };

            commit('updateState', update);

        },
        //-----------------------------------------------------------------
        reloadAssets: function ({ state, commit, dispatch, getters }) {
            let payload = {
                key: 'assets_reload',
                value: true
            };
            commit('updateState', payload);
            dispatch('getAssets');
        },
        //-----------------------------------------------------------------
    },
    //=========================================================================
    getters:{
        state(state) {return state;},
        assets(state) {return state.assets;},
        permissions(state) {return state.permissions;},
        is_logged_in(state) {return state.is_logged_in;},
    }

}
