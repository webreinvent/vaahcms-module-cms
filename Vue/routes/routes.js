let routes= [];

import dashboard from "./vue-routes-dashboard";
import blocks from "./vue-routes-blocks";
import menus from "./vue-routes-menus";
import contents from "./vue-routes-contents";

routes = routes.concat(dashboard);
routes = routes.concat(blocks);
routes = routes.concat(menus);
routes = routes.concat(contents);

export default routes;
