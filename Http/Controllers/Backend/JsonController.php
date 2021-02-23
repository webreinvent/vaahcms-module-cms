<?php  namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\ContentType;

class JsonController extends Controller
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
    public function getAssets(Request $request)
    {

        if(\Auth::check())
        {
            $data['auth_user'] = [
                'name' => \Auth::user()->name,
                'email' => \Auth::user()->email,
            ];
        }

        $data['urls'] = [
            'base' => url('/')
        ];

        $data['permissions'] = \Auth::user()->permissions(true);


        $data['aside_menu'][0] = [
            "label" => "Manage",
            "child" => [
                [
                    "label"=>'Content Types',
                    "icon"=>'cog',
                    "link"=> self::$link."content-types",
                    'path' => "/content-types",
                ],

                [
                    "label"=>'Menus',
                    "icon"=>'link',
                    "link"=> self::$link."menus",
                    'path' => "/menus",
                ],

                [
                    "label"=>'Blocks',
                    "icon"=>'cubes',
                    "link"=> self::$link."blocks",
                    'path' => "/blocks",
                ],

            ],

        ];


        $contents = ContentType::isPublished()->get();

        if($contents)
        {
            $data['aside_menu'][1]['label'] = "Contents";
            $data['aside_menu'][1]['child'] = [];

            foreach ($contents as $content)
            {
                $data['aside_menu'][1]['child'][] = [
                    'label' => $content->name,
                    'icon' => 'list',
                    'link' => self::$link."contents/".$content->slug."/list",
                    'path' => "/contents/".$content->slug."/list",
                ];
            }
        }




        $response['status'] = 'success';
        $response['data'] = $data;

        return response()->json($response);


    }
    //----------------------------------------------------------
    //----------------------------------------------------------

}
