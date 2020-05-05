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

/*
|--------------------------------------------------------------------------
| Content Types Routes
|--------------------------------------------------------------------------
*/


import ContentTypeList from "./../pages/content-types/List";
import ContentTypeCreate from "./../pages/content-types/Create";
import ContentTypeView from "./../pages/content-types/View";
import ContentTypeEdit from "./../pages/content-types/Edit";

routes_list =     {
    path: '/content-types',
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
            name: 'content.types.list',
            component: ContentTypeList,
            props: true,
            meta: {
                middleware: [
                    GetBackendAssets
                ]
            },
            children: [
                {
                    path: 'create',
                    name: 'content.types.create',
                    component: ContentTypeCreate,
                    props: true,
                    meta: {
                        middleware: [
                            GetBackendAssets
                        ]
                    },
                },
                {
                    path: 'view/:id',
                    name: 'content.types.view',
                    component: ContentTypeView,
                    props: true,
                    meta: {
                        middleware: [
                            GetBackendAssets
                        ]
                    },
                },
                {
                    path: 'edit/:id',
                    name: 'content.types.edit',
                    component: ContentTypeEdit,
                    props: true,
                    meta: {
                        middleware: [
                            GetBackendAssets
                        ]
                    },
                }

            ]
        }

    ]
};

routes.push(routes_list);



export default routes;
