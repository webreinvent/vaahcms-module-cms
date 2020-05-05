import Vue from 'vue'
import VueRouter from 'vue-router'
import {store} from './../store/store';

Vue.use(VueRouter);

import middlewarePipeline from './middleware/middlewarePipeline'


let allRoutes = [];

import routes from './routes';

//allRoutes = allRoutes.concat(routes);
allRoutes = routes;


const router = new VueRouter({
    base: '/',
    //mode: 'history',
    linkActiveClass: "",
    routes: allRoutes
});

//----PROTECT VUE ROUTES WITH MIDDLEWARE

//----END OF PROTECT VUE ROUTES WITH MIDDLEWARE




export default router;
