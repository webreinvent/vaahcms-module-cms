let routes= [];
let routes_list= [];

import List from '../pages/contents/List.vue'
import Form from '../pages/contents/Form.vue'
import Item from '../pages/contents/Item.vue'

routes_list = {

    path: '/contents/:slug/list',
    name: 'contents.index',
    component: List,
    props: true,
    children:[
        {
            path: 'form/:id?',
            name: 'contents.form',
            component: Form,
            props: true,
        },
        {
            path: 'view/:id?',
            name: 'contents.view',
            component: Item,
            props: true,
        }
    ]
};

routes.push(routes_list);

export default routes;

