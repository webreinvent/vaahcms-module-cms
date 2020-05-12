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
        },
        bulk_action:{
            selected_items: [],
            data: {},
            action: null,
        },
        new_item:{
            parent_id: null,
            vh_cms_content_type_id: null,
            vh_theme_id: null,
            vh_theme_template_id: null,
            name: null,
            slug: null,
            is_published_at: null,
            status: null,
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

                console.log('--->assets url', url);

                let params = {};
                let data = await Vaah.ajaxGet(url, params);
                payload = {
                    key: 'assets',
                    value: data.data.data
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

            }

            if(payload.name == 'contents.edit')
            {
                list_view = 'small';
            };

            if(payload.name == 'contents.create')
            {
                list_view = 'small';
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
    },
    //=========================================================================
    getters:{
        state(state) {return state;},
        assets(state) {return state.assets;},
        permissions(state) {return state.permissions;},
        is_logged_in(state) {return state.is_logged_in;},
    }

}
