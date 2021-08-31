<?php  namespace VaahCms\Modules\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentFormField;
use VaahCms\Modules\Cms\Entities\ContentType;

class ContentsController extends Controller
{

    public function __construct()
    {
    }

    //----------------------------------------------------------
    //----------------------------------------------------------

    public static function getContentList(Request $request, $plural_slug)
    {

        $content_type = ContentType::where('plural_slug', $plural_slug)->first();

        if(!$content_type)
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content Type not found.';
            return $response;
        }

        $order = 'desc';
        $order_by = 'id';

        $input = $request->all();

        if(isset($input['order'])
            && $input['order']){
            $order = $input['order'];
        }

        if(isset($input['order_by'])
            && $input['order_by']){
            $order_by = $input['order_by'];
        }

        $contents = Content::with(['fields','contentType' => function($q){
            $q->with(['groups' => function($g){
                $g->with(['fields' => function($f){
                    $f->with(['type']);

                }]);

            }]);

        },'template' => function($q){
            $q->with(['groups' => function($g){
                $g->with(['fields' => function($f){
                    $f->with(['type']);

                }]);

            }]);

        }])->where('vh_cms_content_type_id', $content_type->id);


        if(isset($input['q'])
            && $input['q']){

            $contents->where(function ($q) use ($input){
                $q->where('name', 'LIKE', '%'.$input['q'].'%')
                    ->orWhere('slug', 'LIKE', '%'.$input['q'].'%')
                    ->orWhere('permalink', 'LIKE', '%'.$input['q'].'%');

                $q->orWhereHas('fields',function ($p) use ($input){
                    $p->where('content', 'LIKE', '%'.$input['q'].'%');
                    $p->whereHas('field', function ($f) {
                        $f->where('is_searchable' , 1);
                    });
                });

            });
        }

        $contents->orderBy($order_by,$order);

        if(isset($input['per_page'])
            && $input['per_page']
            && is_numeric($input['per_page'])){
            $contents = $contents->paginate($input['per_page']);
        }else{
            $contents = $contents->paginate(config('vaahcms.per_page'));
        }

        if(!$contents)
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content not found.';
            return $response;
        }

        $arr_include_groups = array();
        $arr_exclude_groups = array();

        if(isset($input['include_groups'])){
            if(is_string($input['include_groups'])){
                $arr_include_groups = explode(",",$input['include_groups']);
            }else{
                $arr_include_groups = $input['include_groups'];
            }

        }

        if(isset($input['exclude_groups'])){
            if(is_string($input['exclude_groups'])){
                $arr_exclude_groups = explode(",",$input['exclude_groups']);
            }else{
                $arr_exclude_groups = $input['exclude_groups'];
            }
        }

        $filter = [
            'include_groups' => $arr_include_groups,
            'exclude_groups' => $arr_exclude_groups
        ];

        $content_ids = $contents->pluck('id')->toArray();

        $group_fields = ContentFormField::whereIn('vh_cms_content_id',$content_ids)
            ->get();

        $group_fields = collect($group_fields);

        foreach ($contents as $key => $content){

            $contents[$key]['content_form_groups'] = Content::getFormGroupsTest($content, 'content',$group_fields,$filter);

            $contents[$key]['template_form_groups'] = Content::getFormGroupsTest($content, 'template',$group_fields,$filter);

            $group = get_the_content($contents[$key]);

            $contents[$key]['content_form_groups'] = isset($group['content_form_groups'])?$group['content_form_groups']:null;

            $contents[$key]['template_form_groups'] = isset($group['template_form_groups'])?$group['template_form_groups']:null;;

            unset($contents[$key]['template']);
            unset($contents[$key]['fields']);
            unset($contents[$key]['contentType']);

        }

        $response['status']     = 'success';
        $response['data']       = $contents;
        return $response;
    }


    //----------------------------------------------------------
    //----------------------------------------------------------

    public static function getContentItem(Request $request, $singular_slug, $content_slug)
    {

        $content_type = ContentType::where('singular_slug', $singular_slug)->first();

        if(!$content_type)
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content Type not found.';
            return $response;
        }

        $content = Content::where('slug', $content_slug)
            ->where('vh_cms_content_type_id', $content_type->id)
            ->first();

        if(!$content)
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content not found.';
            return $response;
        }

        $content_data = Content::getItem($content->id);

        if($content_data['status'] != 'success')
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content Data not found.';
            return $response;
        }

        $arr_include_groups = array();
        $arr_exclude_groups = array();
        $arr_content = array();
        $arr_template = array();

        if($request->has('include_groups')){
            $arr_include_groups = explode(",",$request->include_groups);
        }

        if($request->has('exclude_groups')){
            $arr_exclude_groups = explode(",",$request->exclude_groups);
        }

        foreach ($content_data['data']['content_form_groups'] as $group){

            if((count($arr_include_groups) ==  0
                    || in_array($group[0]['slug'], $arr_include_groups))
                && (count($arr_exclude_groups) == 0
                    || !in_array($group[0]['slug'], $arr_exclude_groups))){

                $arr_content[$group[0]['slug']] = get_the_group(
                    $content_data['data'],
                    $group[0]['slug']
                );

            }

        }

        $content['content_fields'] = $arr_content;

        foreach ($content_data['data']['template_form_groups'] as $group){

            if((count($arr_include_groups) ==  0
                    || in_array($group[0]['slug'], $arr_include_groups))
                && (count($arr_exclude_groups) == 0
                    || !in_array($group[0]['slug'], $arr_exclude_groups))){

                $arr_template[$group[0]['slug']] = get_the_group(
                    $content_data['data'],
                    $group[0]['slug'],
                    'template'
                );

            }

        }

        $content['template_fields'] = $arr_template;


        $response['status']     = 'success';
        $response['data']       = $content;
        return $response;
    }

}
