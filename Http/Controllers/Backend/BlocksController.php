<?php namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Block;
use VaahCms\Modules\Cms\Entities\ContentType;
use VaahCms\Modules\Cms\Entities\FieldType;
use WebReinvent\VaahCms\Entities\Theme;

class BlocksController extends Controller
{

    public $theme;

    //----------------------------------------------------------
    public function __construct()
    {
        $this->theme = vh_get_backend_theme();
    }

    public function getAssets(Request $request)
    {

        $data['replace_strings'] = vh_action('getPublicUrls', null, 'array');

        $data['field_types'] = FieldType::select('id', 'name', 'slug', 'meta')
            ->get();

        $data['bulk_actions'] = vh_general_bulk_actions();
        $data['themes'] = Theme::getActiveThemesWithBlockLocations();


        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);
    }
    //----------------------------------------------------------
    public function postCreate(Request $request)
    {
        $response = Block::postCreate($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getList(Request $request)
    {
        $response = Block::getList($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getItem(Request $request, $id)
    {

        $response = Block::getItem($id);
        return response()->json($response);

    }
    //----------------------------------------------------------
    public function postStore(Request $request,$id)
    {
        $response = Block::postStore($request,$id);
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
                $response = Block::bulkStatusChange($request);
                break;
            //------------------------------------
            case 'bulk-trash':

                $response = Block::bulkTrash($request);

                break;
            //------------------------------------
            case 'bulk-restore':

                $response = Block::bulkRestore($request);

                break;

            //------------------------------------
            case 'bulk-delete':

                $response = Block::bulkDelete($request);

                break;

            //------------------------------------
        }

        return response()->json($response);

    }

    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------


}
