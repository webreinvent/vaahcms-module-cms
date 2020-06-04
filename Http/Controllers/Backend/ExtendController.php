<?php  namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\ContentType;

class ExtendController extends Controller
{
    public static $link;

    //----------------------------------------------------------
    public function __construct()
    {
        $base_url = route('vh.backend.cms')."#/";
        $link = $base_url;
        self::$link = $link;
    }
    //----------------------------------------------------------
    public static function topLeftMenu()
    {
        $links = [

        ];

        $response['status'] = 'success';
        $response['data'] = $links;

        return $response;

    }

    //----------------------------------------------------------
    public static function topRightUserMenu()
    {
        $links = [];

        $response['status'] = 'success';
        $response['data'] = $links;

        return $response;
    }

    //----------------------------------------------------------
    public static function sidebarMenu()
    {


        $list[0] = [
            'link' => self::$link."content-types/",
            'icon' => 'file-alt',
            'label'=> 'CMS'
        ];

        /*$list[1]['child'][] =  [
            'link' => self::$link."content-types/",
            'label'=> 'Types'
        ];

        $content_types = ContentType::isPublished()->get();

        if($content_types->count() > 0)
        {
            foreach ($content_types as $content_type)
            {
                $list[1]['child'][] =  [
                    'link' => self::$link."contents/".$content_type->slug."/list",
                    'label'=> $content_type->name
                ];
            }
        }*/


        $response['status'] = 'success';
        $response['data'] = $list;

        return $response;
    }
    //----------------------------------------------------------

}
