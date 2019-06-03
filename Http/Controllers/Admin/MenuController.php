<?php

namespace VaahCms\Modules\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\Menu;
use VaahCms\Modules\Cms\Entities\MenuItem;
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
        $data['pages'] = Page::getAssetsList();

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function store(Request $request)
    {
        $rules = array(
            'vh_theme_location_id' => 'required',
            'name' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return response()->json($response);
        }

        $data = [];

        $inputs = $request->all();

        if($request->has('id'))
        {
            $menu = Menu::find($inputs['id']);
        } else
        {
            $menu = new Menu();
        }

        $inputs['slug'] = str_slug($inputs['name']);

        $menu->fill($inputs);
        $menu->save();

        $response['status'] = 'success';
        $response['messages'][] = 'Menu Saved';
        $response['data'] = $menu;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function getLocationMenus(Request $request, $location_id)
    {

        $list = Menu::where('vh_theme_location_id', $location_id)->get();

        $response['status'] = 'success';
        $response['data'] = $list;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function getMenuItems(Request $request, $menu_id)
    {

        $data['assets'] = MenuItem::where('vh_menu_id', $menu_id)
            ->get()
            ->toArray();

        $data['list'] = MenuItem::with('childrens')
            ->where('vh_menu_id', $menu_id)
            ->whereNull('parent_id')->get();

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function storeMenuItems(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'vh_menu_id' => 'required',
            'vh_page_id' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return response()->json($response);
        }

        $data = [];

        $inputs = $request->all();

        $inputs['depth'] = 1;


        if(isset($inputs['parent_id']))
        {
            $inputs['depth'] = MenuItem::getNewDepth($inputs['parent_id']);
        }


        if(isset($inputs['id']) && !is_null($inputs['id']) )
        {
            $menu_item = MenuItem::find($inputs['id']);
        } else
        {
            $menu_item = new MenuItem();
        }

        $inputs['slug'] = str_slug($inputs['name']);

        $menu_item->fill($inputs);

        $menu_item->save();




        $response['status'] = 'success';
        $response['messages'][] = 'Saved';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function deleteMenuItem(Request $request, $menu_item_id)
    {
        $data = [];

        MenuItem::recursiveDelete($menu_item_id);

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------
    //----------------------------------------------------------



}
