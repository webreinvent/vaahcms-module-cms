let routes= [];
let routes_list= [];

import List from '../pages/blocks/List.vue'
import Form from '../pages/blocks/Form.vue'
import Item from '../pages/blocks/Item.vue'

routes_list = {

    path: '/blocks',
    name: 'blocks.index',
    component: List,
    props: true,
    children:[
        {
            path: 'form/:id?',
            name: 'blocks.form',
            component: Form,
            props: true,
        },
        {
            path: 'view/:id?',
            name: 'blocks.view',
            component: Item,
            props: true,
        }
    ]
};

routes.push(routes_list);

export default routes;

