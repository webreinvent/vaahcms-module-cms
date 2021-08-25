<?php  namespace VaahCms\Modules\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
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

        $contents = Content::where('vh_cms_content_type_id', $content_type->id)
            ->orderBy('id','desc');


        if($request->has('q')
            && $request->q){

            $contents->where(function ($q) use ($request){
                $q->where('name', 'LIKE', '%'.$request->q.'%')
                    ->orWhere('slug', 'LIKE', '%'.$request->q.'%')
                    ->orWhere('permalink', 'LIKE', '%'.$request->q.'%');

                $q->orWhereHas('fields',function ($p) use ($request){
                    $p->where('content', 'LIKE', '%'.$request->q.'%');
                    $p->whereHas('field', function ($f) {
                        $f->where('is_searchable' , 1);
                    });
                });

            });
        }

        if($request->has('per_page')
            && $request->per_page
            && is_numeric($request->per_page)){
            $contents = $contents->paginate($request->per_page);
        }else{
            $contents = $contents->paginate('5');
        }

        if(!$contents)
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content not found.';
            return $response;
        }

        $arr_include_groups = array();
        $arr_exclude_groups = array();

        if($request->has('include_groups')){
            $arr_include_groups = explode(",",$request->include_groups);
        }

        if($request->has('exclude_groups')){
            $arr_exclude_groups = explode(",",$request->exclude_groups);
        }

        foreach ($contents as $key => $content){

            $content_data = Content::getItem($content->id);

            if($content_data['status'] != 'success')
            {
                $response['status']     = 'failed';
                $response['errors']     = 'Content Data not found.';
                return $response;
            }

            $arr_content = array();

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

            $contents[$key]['content_fields'] = $arr_content;

            $arr_template = array();

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

            $contents[$key]['template_fields'] = $arr_template;
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
