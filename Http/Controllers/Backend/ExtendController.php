<?php  namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\ContentType;
use WebReinvent\VaahCms\Entities\TaxonomyType;

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
    public static function getCmsContentRelations()
    {
        $arr = [
            [
                "name" => "Taxonomy",
                "namespace" => "WebReinvent\\VaahCms\\Entities\\Taxonomy",
                "options" => TaxonomyType::getListInTreeFormat(),
                "filter_by" => 'vh_taxonomy_type_id',
                "display_column" => 'name',
                "has_children" => true
            ],
            [
                "name" => "Role",
                "namespace" => "WebReinvent\\VaahCms\\Entities\\Role",
                "display_column" => 'slug',
                "filters" => [
                    [
                        "query" => 'where',
                        "column" => 'id',
                        "condition" => '=',
                        "value" => '1',
                    ],
                ],

            ]
        ];


        return $arr;

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


        /*$list[0] = [
            'link' => self::$link."content-types/",
            'icon' => 'file-alt',
            'label'=> 'CMS'
        ];*/

        $list[0] = [
            'link' => '#',
            'icon'=> 'file-alt',
            'label'=> 'CMS',
            'child' => [
                [
                    'link' => self::$link."content-types/",
                    'icon' => 'paste',
                    'label'=> 'Content Types'
                ],
                [
                    'link' => self::$link."menus/",
                    'icon' => 'bars',
                    'label'=> 'Menus'
                ],
                [
                    'link' => self::$link."blocks/",
                    'icon' => 'th-large',
                    'label'=> 'Blocks'
                ],
                [
                    'link' => '#',
                    'icon'=> 'file',
                    'label'=> 'Content',
                    'child' => []
                ]
            ]
        ];

        $content_types = ContentType::isPublished()->get();

        if($content_types->count() > 0)
        {
            foreach ($content_types as $content_type)
            {
                $list[0]['child'][3]['child'][] =  [
                    'link' => self::$link."contents/".$content_type->slug."/list",
                    'label'=> $content_type->name
                ];
            }
        }


        $response['status'] = 'success';
        $response['data'] = $list;

        return $response;
    }
    //----------------------------------------------------------

}
