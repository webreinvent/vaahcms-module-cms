<?php  namespace VaahCms\Modules\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentFormField;
use VaahCms\Modules\Cms\Entities\ContentTypeBase;
use WebReinvent\VaahCms\Models\ThemeLocation;

class MenusController extends Controller
{

    public function __construct()
    {
    }

    //----------------------------------------------------------
    //----------------------------------------------------------

    public static function getList(Request $request, $menu_slug)
    {

        return ThemeLocation::getLocationData($menu_slug);

    }

}
