<?php

namespace VaahCms\Modules\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Page;
use WebReinvent\VaahCms\Entities\ThemeTemplate;

class PageController extends Controller
{



    //----------------------------------------------------------
    public function __construct()
    {

    }

    //----------------------------------------------------------
    public function index()
    {
        return view('cms::admin.pages.pages');
    }
    //----------------------------------------------------------
    public function assets(Request $request)
    {
        $data = [];

        $data['page_templates'] = ThemeTemplate::getAssetsList();
        $data['page_statuses'] = page_statuses();
        $data['page_visibilities'] = page_visibilities();
        $data['pages_list'] = Page::getAssetsList();

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function getCustomFields(Request $request)
    {
        $rules = array(
            'page_template_slug' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return response()->json($response);
        }

        $data = [];

        $template = ThemeTemplate::theme(vh_get_theme_id())->slug($request->page_template_slug)->first();

        //sync all the fields types
        view(vh_get_theme_slug()."::page-templates.".$template->slug)->render();

        $template = ThemeTemplate::find($template->id);

        $response['status'] = 'success';
        $response['data']['list'] = $template->formFields;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function storePage(Request $request)
    {
        $rules = array(
            'title' => 'required',
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

        $response['status'] = 'failed';
        $response['errors'][] = 'error';

        $response['status'] = 'success';
        $response['messages'][] = 'Saved';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------



}
