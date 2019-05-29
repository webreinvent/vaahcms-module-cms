<?php

namespace VaahCms\Modules\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\Page;
use WebReinvent\VaahCms\Entities\ThemeLocation;
use WebReinvent\VaahCms\Entities\ThemeTemplate;

class MenuController extends Controller
{



    //----------------------------------------------------------
    public function __construct()
    {

    }

    //----------------------------------------------------------
    public function index()
    {
        return view('cms::admin.pages.menus');
    }
    //----------------------------------------------------------
    public function assets(Request $request)
    {
        $data = [];

        $data['theme_menu_locations'] = ThemeLocation::getMenuLocationsForAssets();

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function getList(Request $request)
    {

        $data = [];


        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------



}
