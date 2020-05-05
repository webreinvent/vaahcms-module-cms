import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import root from './modules/root';
import content_types from './modules/content_types';

export const store = new Vuex.Store({
    modules: {
        root: root,
        content_types: content_types,
    }
});
