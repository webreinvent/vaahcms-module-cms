let routes= [];
let routes_list= [];

import List from '../pages/contenttypes/List.vue'
import Form from '../pages/contenttypes/Form.vue'
import Item from '../pages/contenttypes/Item.vue'
import ContentStructure from '../pages/contenttypes/ContentStructure.vue'

routes_list = {

    path: '/content-types',
    name: 'contenttypes.index',
    component: List,
    props: true,
    children:[
        {
            path: 'form/:id?',
            name: 'contenttypes.form',
            component: Form,
            props: true,
        },
        {
            path: 'view/:id?',
            name: 'contenttypes.view',
            component: Item,
            props: true,
        },
        {
            path: 'content-structure/:id?',
            name: 'contenttypes.contentstructure',
            component: ContentStructure,
            props: true,
        }
    ]
};

routes.push(routes_list);

export default routes;

