let routes= [];

import dashboard from "./vue-routes-dashboard";
import blocks from "./vue-routes-blocks";
import menus from "./vue-routes-menus";
import contenttypes from "./vue-routes-contenttypes";

routes = routes.concat(dashboard);
routes = routes.concat(blocks);
routes = routes.concat(menus);
routes = routes.concat(contenttypes);

export default routes;
