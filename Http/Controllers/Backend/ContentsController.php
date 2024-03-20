<?php namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Models\Content;
use VaahCms\Modules\Cms\Models\ContentType;
use WebReinvent\VaahCms\Models\Taxonomy;
use WebReinvent\VaahCms\Models\Theme;
use WebReinvent\VaahCms\Models\User;

class ContentsController extends Controller
{


    //----------------------------------------------------------
    public function __construct()
    {

    }

    //----------------------------------------------------------

    public function getAssets(Request $request, $content_slug)
    {

        //$data['fields'] = FieldType::select('id', 'name', 'slug')->get();

        $data['currency_codes'] = vh_get_currency_list();
        $data['themes'] = Theme::getActiveThemesWithTemplates();
        $data['rows'] = config('vaahcms.per_page');

        $default_theme_template = Theme::getDefaultThemesAndTemplateWithRelations($content_slug);

        $data['default_theme'] = $default_theme_template['theme'];
        $data['default_template'] = $default_theme_template['template'];
        $data['bulk_actions'] = vh_general_bulk_actions();
        $data['media_upload_url'] = route('backend.cms.media.upload');

        $data['non_repeatable_fields'] = Content::getNonRepeatableFields();

        $data['content_type'] = $request->content_type;

        $form_groups = ContentType::getItemWithRelations($request->content_type->id);

        if(isset($form_groups['success']) && $form_groups['success'])
        {

            $arr_group = [];
//            var_dump($form_groups['data']->groups);die();
            foreach ($form_groups['data']->groups as $group){
                $arr_group[] = [$group];
            }
            $data['content_type']['form_groups'] = $arr_group;

        }

        $data['fillable']['except'] = [
            'uuid',
            'created_by',
            'updated_by',
            'deleted_by',
        ];
        $model = new Content();
        $fillable = $model->getFillable();
        $data['fillable']['columns'] = array_diff(
            $fillable, $data['fillable']['except']
        );

        foreach ($fillable as $column)
        {
            $data['empty_item'][$column] = null;
        }

        $data['empty_item']['content_groups'] = [];
        $data['empty_item']['template_groups'] = [];

        $response['success'] = true;
        $response['data'] = $data;

        return response()->json($response);
    }

    //----------------------------------------------------------
    public function getUsers(Request $request,$content_slug)
    {
        try{
            return User::all();
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function getList(Request $request,$content_slug)
    {
        try{
            return Content::getList($request);
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function updateList(Request $request,$content_slug)
    {
        try{
            return Content::updateList($request);
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function listAction(Request $request, $type)
    {


        try{
            return Content::listAction($request, $type);
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function deleteList(Request $request,$content_slug)
    {
        try{
            return Content::deleteList($request);
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function createItem(Request $request,$content_slug)
    {

        try{
            return Content::postCreate($request);
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function getItem(Request $request,$content_slug, $id)
    {
        try{
            return Content::getItem($id);
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function updateItem(Request $request,$content_slug,$id)
    {
        try{
            return Content::updateItem($request,$id);
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function deleteItem(Request $request,$content_slug,$id)
    {
        try{
            return Content::deleteItem($request,$id);
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function itemAction(Request $request,$content_type_slug,$id,$action)
    {
        try{
            return Content::itemAction($request,$id,$action);
        }catch (\Exception $e){
            $response = [];
            $response['success'] = false;
            if(env('APP_DEBUG')){
                $response['errors'][] = $e->getMessage();
                $response['hint'] = $e->getTrace();
            } else{
                $response['errors'][] = 'Something went wrong.';
                return $response;
            }
        }
    }
    //----------------------------------------------------------
    public function getRelationsInTree(Request $request)
    {
        $input = $request->all();

        $list = [];

        $relation =  vh_content_relations_by_name($input['type']);

        if(!isset($relation['display_column']) || !$relation['display_column']){
            $relation['display_column'] = 'name';
        }

        $url = null;

        if($relation && isset($relation['namespace'])){

            $relation['filter_id'] = $input['filter_id'];

            $list = Content::getListByVariables($relation);

            if(isset($relation['add_url']) && $relation['add_url']){
                $url = $relation['add_url'];
            }
        }


        $response['success'] = true;
        $response['data']['list'] = $list;
        $response['data']['display_column'] = $relation['display_column'];
        $response['data']['add_url'] = $url;

        return $response;
    }
    //----------------------------------------------------------


}
