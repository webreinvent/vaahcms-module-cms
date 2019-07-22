//---------Variables
var base_url = $('base').attr('href');
var current_url = $('#current_url').attr('content');
var debug = $('#debug').attr('content');
//---------/Variables

let urls = {
    base: base_url,
    current: current_url,
};

import Dashboard from "./dashboard/Dashboard";

const routes= [
    {   path: '/',
        component: Dashboard,
        props: true
    },
    { path: '*', redirect: '/' }
];


//----------pages
import PagesApp from "./pages/App";
import PagesList from "./pages/List";
import PagesCreate from "./pages/Create";
import PagesEdit from "./pages/Edit";

const routes_pages =     {
    path: '/pages',
    component: PagesApp,
    props: true,
    children: [
        {
            path: '/',
            component: PagesList,
            props: true,
        },
        {
            path: 'create',
            component: PagesCreate,
            props: true,
        },
        {
            path: 'edit/:id',
            component: PagesEdit,
            props: true
        }
    ]
};

routes.push(routes_pages);
//----------/pages




export default routes;