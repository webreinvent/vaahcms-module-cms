let routes= [];
let routes_list= [];

import List from '../pages/menus/List.vue'
import Item from '../pages/menus/Item.vue'

routes_list = {

    path: '/menus',
    name: 'menus.index',
    component: List,
    props: true,
    children:[
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

