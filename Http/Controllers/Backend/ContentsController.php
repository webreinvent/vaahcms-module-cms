<?php namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentType;
use WebReinvent\VaahCms\Entities\Taxonomy;
use WebReinvent\VaahCms\Entities\Theme;
use WebReinvent\VaahCms\Entities\User;

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

        //$data['fields'] = FieldType::select('id', 'name', 'slug')->get();

        $data['currency_codes'] = vh_get_currency_list();
        $data['themes'] = Theme::getActiveThemes();

        $default_theme_template = Theme::getDefaultThemesAndTemplateWithRelations($content_slug);

        $data['default_theme'] = $default_theme_template['theme'];
        $data['default_template'] = $default_theme_template['template'];
        $data['bulk_actions'] = vh_general_bulk_actions();
        $data['media_upload_url'] = route('backend.cms.media.upload');

        $data['non_repeatable_fields'] = Content::getNonRepeatableFields();

        $data['content_type'] = $request->content_type;
        $form_groups = ContentType::getItemWithRelations($request->content_type->id);

        if($form_groups['status'] == 'success')
        {

            $arr_group = [];

            foreach ($form_groups['data']->groups as $group){
                $arr_group[] = [$group];
            }
            $data['content_type']['form_groups'] = $arr_group;
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
            case 'remove-group':

                $response = Content::removeGroup($request);

                break;

            //------------------------------------
        }

        return response()->json($response);

    }

    //----------------------------------------------------------
    public function getTemplateGroups(Request $request, $content_slug, $id)
    {
        $rules = array(
            'vh_theme_template_id' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return response()->json($response);
        }

        $data = [];

        $content = Content::find($id);

        $content->vh_theme_template_id = $request->vh_theme_template_id;
        $content->save();

        $groups = Content::getFormGroups($content, 'template');

        $response['status'] = 'success';
        $response['data'] = $groups;

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function syncSeeds(Request $request)
    {

        $theme = Theme::find($request->theme_id);

        $response = Theme::activateItem($theme->slug);

        $response['messages'] = [];

        $response['messages'][] = "Theme is synced";

        return response()->json($response);

    }
    //----------------------------------------------------------
    public function getUserById(Request $request,$id)
    {
        return User::find($id);
    }
    //----------------------------------------------------------
    public function getRelationsInTree(Request $request)
    {
        $input = $request->all();

        $list = [];

        $display_column = 'name';
        $url = null;

        $relation =  vh_content_relations_by_name($input['type']);

        if($relation && isset($relation['namespace'])){

            $relation['filter_id'] = $input['filter_id'];

            $list = Content::getListByVariables($relation);

            if(isset($relation['display_column']) && $relation['display_column']){
                $display_column = $relation['display_column'];
            }

            if(isset($relation['add_url']) && $relation['add_url']){
                $url = $relation['add_url'];
            }
        }


        $response['status'] = 'success';
        $response['data']['list'] = $list;
        $response['data']['display_column'] = $display_column;
        $response['data']['add_url'] = $url;

        return $response;
    }
    //----------------------------------------------------------


}
