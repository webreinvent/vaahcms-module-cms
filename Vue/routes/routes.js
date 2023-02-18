let routes= [];

import dashboard from "./vue-routes-dashboard";
import blocks from "./vue-routes-blocks";

routes = routes.concat(dashboard);
routes = routes.concat(blocks);

export default routes;
