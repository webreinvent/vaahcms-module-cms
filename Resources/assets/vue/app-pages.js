
window.Vue = require('vue');


//---------Package imports
import VueRouter from 'vue-router';
import VueResource from 'vue-resource';
import moment from 'moment'
import VueHelpers from './helpers/VueHelpers';
//---------/Package imports

//---------Configs
Vue.config.delimiters = ['@{{', '}}'];
//Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name=csrf-token]').attr('content');
Vue.config.async = false;
//---------Configs

//---------Helpers
Vue.prototype.moment = moment;
Vue.use(VueResource);
Vue.use(VueRouter);
Vue.use(VueHelpers);
//---------/Helpers

//---------Comp Imports
import PagesList from './components/PagesList';
import PagesAdd from './components/PagesAdd';

//---------/Comp Imports

//---------Routes
const router = new VueRouter({
    base: '/',
    linkActiveClass: "active",
    routes: [
        {   path: '/',
            props: {assets: true},
            component: PagesList
        },
        {   path: '/add',
            component: PagesAdd
        },
        { path: '*', redirect: '/' }
    ]
});
//---------/Routes

//---------Variables
var base_url = $('base').attr('href');
var current_url = $('#current_url').attr('content');
var debug = $('#debug').attr('content');
//---------/Variables

const app = new Vue({
    el: '#vh-app-pages',
    components:{

    },
    router,
    data: {
        assets: null,
        debug: debug,
        urls: {
            base: base_url,
            current: current_url,
        }
    },

    mounted() {

    },
    methods:{

        //-----------------------------------------------------------

        //-----------------------------------------------------------

        //-----------------------------------------------------------
        //-----------------------------------------------------------
    }
});