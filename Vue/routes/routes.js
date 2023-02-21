let routes= [];

import dashboard from "./vue-routes-dashboard";
import blocks from "./vue-routes-blocks";
import menus from "./vue-routes-menus";

routes = routes.concat(dashboard);
routes = routes.concat(blocks);
routes = routes.concat(menus);

export default routes;
