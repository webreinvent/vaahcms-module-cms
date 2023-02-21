let routes= [];
let routes_list= [];

import List from '../pages/menus/List.vue'
import Form from '../pages/menus/Form.vue'
import Item from '../pages/menus/Item.vue'

routes_list = {

    path: '/menus',
    name: 'menus.index',
    component: List,
    props: true,
    children:[
        {
            path: 'form/:id?',
            name: 'menus.form',
            component: Form,
            props: true,
        },
        {
            path: 'view/:id?',
            name: 'menus.view',
            component: Item,
            props: true,
        }
    ]
};

routes.push(routes_list);

export default routes;

