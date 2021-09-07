<?php namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentType;
use VaahCms\Modules\Cms\Entities\FieldType;
use WebReinvent\VaahCms\Entities\TaxonomyType;

class ContentTypesController extends Controller
{

    public $theme;

    //----------------------------------------------------------
    public function __construct()
    {
        $this->theme = vh_get_backend_theme();
    }

    public function getAssets(Request $request)
    {

        $data['field_types'] = FieldType::select('id', 'name', 'slug', 'meta')
            ->get();

        $data['non_repeatable_fields'] = Content::getNonRepeatableFields();

        $data['bulk_actions'] = vh_general_bulk_actions();

        $data['content_relations'] = vh_content_relations();

        $data['taxonomy_types'] = TaxonomyType::whereNotNull('is_active')
            ->whereNull('parent_id')->with(['children'])
            ->select('id', 'name', 'slug')->get();

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);
    }
    //----------------------------------------------------------
    public function postCreate(Request $request)
    {
        $response = ContentType::postCreate($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getList(Request $request)
    {
        $response = ContentType::getList($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getItem(Request $request, $id)
    {

        $response = ContentType::getItem($id);
        return response()->json($response);

    }
    //----------------------------------------------------------
    public function getItemRelations(Request $request, $id)
    {

        $response = ContentType::getItemWithRelations($id);
        return response()->json($response);

    }
    //----------------------------------------------------------
    public function postStoreGroups(Request $request, $id)
    {
        $response = ContentType::postStoreGroups($request, $id);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function postStore(Request $request,$id)
    {
        $response = ContentType::postStore($request,$id);
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
                $response = ContentType::bulkStatusChange($request);
                break;
            //------------------------------------
            case 'bulk-trash':

                $response = ContentType::bulkTrash($request);

                break;
            //------------------------------------
            case 'bulk-restore':

                $response = ContentType::bulkRestore($request);

                break;

            //------------------------------------
            case 'bulk-delete':

                $response = ContentType::bulkDelete($request);

                break;

            //------------------------------------
        }

        return response()->json($response);

    }

    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------


}
