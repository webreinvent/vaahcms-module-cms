<?php  namespace VaahCms\Modules\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentType;

class ContentController extends Controller
{

    public function __construct()
    {
    }



    public static function getContentItem(Request $request, $singular, $content)
    {

        $content_type = ContentType::where('singular_slug', $singular)->first();

        if(!$content_type)
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content Type not found.';
            return $response;
        }

        $content = Content::where('slug', $content)
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
        $arr_groups = array();

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
                $arr_groups['content'][$group[0]['slug']] = get_the_group(
                    $content_data['data'],
                    $group[0]['slug']
                );
            }

        }

        foreach ($content_data['data']['template_form_groups'] as $group){

            if((count($arr_include_groups) ==  0 
                    || in_array($group[0]['slug'], $arr_include_groups))
                && (count($arr_exclude_groups) == 0
                    || !in_array($group[0]['slug'], $arr_exclude_groups))){
                $arr_groups['template'][$group[0]['slug']] = get_the_group(
                    $content_data['data'],
                    $group[0]['slug'],
                    'template'
                );
            }

        }

        $response['status']     = 'success';
        $response['data']       = $arr_groups;
        return $response;
    }

}
