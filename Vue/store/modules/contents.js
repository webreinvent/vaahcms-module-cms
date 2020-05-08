import {VaahHelper as Vaah} from "../../vaahvue/helpers/VaahHelper";

//---------Variables
let base_url = document.getElementsByTagName('base')[0].getAttribute("href");
let debug = document.getElementById('debug').getAttribute('content');
//---------/Variables

let current_url = window.location;

let hash_url = current_url.hash;

hash_url = hash_url.split('?');
hash_url = hash_url[0];
hash_url = hash_url.replace("#", '/');
hash_url = hash_url.split('/');

console.log('--->hash_url', hash_url);

let content_slug = hash_url[3];

console.log('--->content_slug', content_slug);

let json_url = base_url+"/backend/cms/json";
let ajax_url = base_url+"/backend/cms/contents/"+content_slug;

export default {
    namespaced: true,
    state: {
        debug: debug,
        content_slug: content_slug,
        base_url: base_url,
        ajax_url: ajax_url,
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

            if(!state.assets_is_fetching || !state.assets)
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
            }

        },
        //-----------------------------------------------------------------
        updateView({ state, commit, dispatch, getters }, payload) {
            let list_view;

            if(payload && payload.name && payload.name == 'contents.list')
            {
                list_view = 'large';
            }

            if(payload.name == 'contents.edit')
            {
                list_view = 'small';
            };

            if(payload.name == 'contents.create')
            {
                list_view = 'small';
            };

            let view = {
                key: 'list_view',
                value: list_view
            };

            commit('updateState', view);

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
