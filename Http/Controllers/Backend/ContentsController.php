<?php namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentType;
use VaahCms\Modules\Cms\Entities\Field;

class ContentsController extends Controller
{

    public $theme;

    //----------------------------------------------------------
    public function __construct()
    {
        $this->theme = vh_get_backend_theme();
    }

    //----------------------------------------------------------

    public function getAssets(Request $request)
    {

        $data['fields'] = Field::select('id', 'name', 'slug')->get();
        $data['content_type'] = $request->content_type;

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);
    }
    //----------------------------------------------------------
    public function postCreate(Request $request)
    {
        $response = Content::postCreate($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getList(Request $request)
    {
        $response = Content::getList($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getItem(Request $request, $id)
    {

        $response = Content::getItem($id);
        return response()->json($response);

    }

    //----------------------------------------------------------
    public function postStore(Request $request,$id)
    {
        $response = Content::postStore($request,$id);
        return response()->json($response);
    }

    //----------------------------------------------------------
    //----------------------------------------------------------
    public function postActions(Request $request, $action)
    {
        $rules = array(
            'inputs' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return response()->json($response);
        }

        $response = [];

        $response['status'] = 'success';

        $inputs = $request->all();

        switch ($action)
        {

            //------------------------------------
            case 'bulk-change-status':
                $response = Content::bulkStatusChange($request);
                break;
            //------------------------------------
            case 'bulk-trash':

                $response = Content::bulkTrash($request);

                break;
            //------------------------------------
            case 'bulk-restore':

                $response = Content::bulkRestore($request);

                break;

            //------------------------------------
            case 'bulk-delete':

                $response = Content::bulkDelete($request);

                break;

            //------------------------------------
        }

        return response()->json($response);

    }

    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------


}
