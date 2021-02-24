import {VaahHelper as Vaah} from "../../vaahvue/helpers/VaahHelper";

//---------Variables
let base_url = document.getElementsByTagName('base')[0].getAttribute("href");
let debug = document.getElementById('debug').getAttribute('content');
//---------/Variables

let json_url = base_url+"/backend/cms/json";
let ajax_url = base_url+"/backend/cms/blocks";

export default {
    namespaced: true,
    state: {
        debug: debug,
        base_url: base_url,
        ajax_url: ajax_url,
        json_url: json_url,
        assets: null,
        active_theme: {
            'locations': []
        },
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
            location: null,
            sort_by: null,
            sort_order: 'desc',
        },
        bulk_action:{
            selected_items: [],
            data: {},
            action: null,
        },
        new_item:{
            name: null,
            slug: null,
            vh_theme_id: "",
            vh_theme_location_id: "",
            content: "",
            is_published: null,
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
            let update;

            if(payload && payload.name && payload.name == 'blocks.list')
            {
                list_view = 'large';

                update = {
                    key: 'active_item',
                    value: null
                };

                commit('updateState', update);

            }

            if(payload.name == 'blocks.create' || payload.name == 'blocks.view' || payload.name == 'blocks.edit')
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
        is_logged_in(state) {return state.is_logged_in;},
    }

}
