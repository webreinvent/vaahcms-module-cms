<?php

namespace VaahCms\Modules\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

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


        return view("btfourpointthree::page-templates.default");

        $data = [];

        $data['page_templates'] = vh_get_page_templates_path();

        foreach ($data['page_templates'] as $template)
        {

            $content = file_get_contents($template);

            echo "<pre>";
            print_r($content);
            echo "</pre>";
            die("<hr/>line number=123");

        }


        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function syncPageTemplateFields(Request $request)
    {
        $rules = array(
            'template_name' => 'required',
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
