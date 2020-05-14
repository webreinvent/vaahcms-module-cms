<?php namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentType;
use VaahCms\Modules\Cms\Entities\Field;
use WebReinvent\VaahCms\Entities\Theme;

class ContentsController extends Controller
{

    public $theme;

    //----------------------------------------------------------
    public function __construct()
    {
        $this->theme = vh_get_backend_theme();
    }

    //----------------------------------------------------------

    public function getAssets(Request $request, $content_slug)
    {

        $data['fields'] = Field::select('id', 'name', 'slug')->get();
        $data['content_type'] = $request->content_type;
        $data['currency_codes'] = vh_get_currency_list();
        $data['themes'] = Theme::getActiveThemes();

        $default_theme_template = Theme::getDefaultThemesAndTemplateWithRelations($content_slug);
        $data['default_theme'] = $default_theme_template['theme'];
        $data['default_template'] = $default_theme_template['template'];


        $groups = ContentType::getItemWithRelations($request->content_type->id);

        if($groups['status'] == 'success')
        {
            $data['groups'] = $groups['data']->groups;
        }

        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);
    }
    //----------------------------------------------------------
    public function postCreate(Request $request, $content_slug)
    {
        $response = Content::postCreate($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getList(Request $request, $content_slug)
    {
        $response = Content::getList($request);
        return response()->json($response);
    }
    //----------------------------------------------------------
    public function getItem(Request $request, $content_slug, $id)
    {
        $response = Content::getItem($id);
        return response()->json($response);
    }

    //----------------------------------------------------------
    public function postStore(Request $request, $content_slug, $id)
    {
        $response = Content::postStore($request,$id);
        return response()->json($response);
    }

    //----------------------------------------------------------
    //----------------------------------------------------------
    public function postActions(Request $request, $content_slug, $action)
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
