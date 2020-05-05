let routes=[];
let routes_list=[];




//----------Middleware
import GetBackendAssets from './middleware/GetBackendAssets'
//----------Middleware


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

import Backend from './../layouts/Backend'
import Index from './../pages/dashboard/Index'

routes_list =     {
    path: '/',
    component: Backend,
    props: true,
    meta: {
        middleware: [
            GetBackendAssets
        ]
    },
    children: [
        {
            path: '/',
            name: 'cms.index',
            component: Index,
            props: true,
            meta: {
                middleware: [
                    GetBackendAssets
                ]
            },
        },

    ]
};

routes.push(routes_list);



export default routes;
