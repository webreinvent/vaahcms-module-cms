<?php  namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\ContentTypeBase;
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

        $list = [
            [
                "name" => "Taxonomy",
                "namespace" => "WebReinvent\\VaahCms\\Entities\\Taxonomy",
                "options" => TaxonomyType::getListInTreeFormat(),
                "filter_by" => 'vh_taxonomy_type_id',
                "add_url" => route('vh.backend')."#/vaah/manage/taxonomies/create",
                "has_children" => true
            ],
            [
                "name" => "Role",
                "namespace" => "WebReinvent\\VaahCms\\Entities\\Role",
                "display_column" => 'name',
                "filters" => [
                    [
                        "query" => 'where',
                        "column" => 'is_active',
                        "condition" => '=',
                        "value" => 1,
                    ],
                ],

            ]
        ];

        $response['status'] = 'success';
        $response['data'] = $list;

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
            'icon'=> 'pi pi-copy',
            'label'=> 'CMS',
            'items' => [
                [
                    'url' => self::$link."content-types/",
                    'icon' => 'pi pi-book ',
                    'label'=> 'Content Types'
                ],
                [
                    'url' => self::$link."menus/",
                    'icon' => 'pi pi-bars',
                    'label'=> 'Menus'
                ],
                [
                    'url' => self::$link."blocks/",
                    'icon' => 'pi pi-th-large',
                    'label'=> 'Blocks'
                ],
                [
                    'icon'=> 'pi pi-file',
                    'label'=> 'Content',
                    'items' => []
                ],
            ]
        ];

        $content_types = ContentTypeBase::isPublished()->get();

        if($content_types->count() > 0)
        {
            foreach ($content_types as $content_type)
            {
                $list[0]['items'][3]['items'][] =  [
                    'url' => self::$link."contents/".$content_type->slug."/list",
                    'label'=> $content_type->name
                ];
            }
        }


        $response['success'] = true;
        $response['data'] = $list;

        return $response;
    }
    //----------------------------------------------------------
    public function getDashboardItems()
    {

        $data = array();

        $data['next_steps'] = [
            [
                'name' => 'View Pages',
                'icon' => 'eye',
                'link' => self::$link."contents/pages/list"
            ],
            [
                'name' => 'Add Pages',
                'icon' => 'plus',
                'link' => self::$link."contents/pages/list/create"
            ],
            [
                'name' => 'Add a Content Type',
                'icon' => 'edit',
                'link' => self::$link."content-types/create"
            ]
        ];


        $data['actions'] = [
            [
                'name' => 'Manage Menus',
                'icon' => 'bars',
                'link' => self::$link."menus/"
            ],
            [
                'name' => 'Manage Blocks',
                'icon' => 'th-large',
                'link' => self::$link."blocks/"
            ],
            [
                'name' => 'Learn more about CMS',
                'icon' => 'graduation-cap',
                'open_in_new_tab' => true,
                'link' => "https://docs.vaah.dev/vaahcms/cms/introduction.html"
            ]
        ];

        $response['status'] = 'success';
        $response['data'] = $data;

        return $response;
    }
    //----------------------------------------------------------

}
