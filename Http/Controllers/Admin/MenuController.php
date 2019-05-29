<?php

namespace VaahCms\Modules\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\Page;
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

        $data['page_templates'] = ThemeTemplate::getAssetsList();
        $data['page_template_default'] = ThemeTemplate::getDefaultPageTemplate();
        $data['page_statuses'] = page_statuses();
        $data['page_visibilities'] = page_visibilities();
        $data['pages_list'] = Page::getAssetsList();

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
