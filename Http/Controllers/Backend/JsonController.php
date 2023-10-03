<?php  namespace VaahCms\Modules\Cms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Models\ContentTypeBase;

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
            "items" => [
                [
                    "label"=>'Content Types',
                    "url"=> self::$link."content-types",
                ],

                [
                    "label"=>'Menus',
                    "url"=> self::$link."menus",
                ],

                [
                    "label"=>'Blocks',
                    "url"=> self::$link."blocks",
                ],

            ],

        ];


        $contents = ContentTypeBase::isPublished()->get();

        if($contents)
        {
            $data['aside_menu'][1]['label'] = "Contents";
            $data['aside_menu'][1]['child'] = [];

            foreach ($contents as $content)
            {
                $data['aside_menu'][1]['items'][] = [
                    'label' => $content->name,
                    'url' => self::$link."contents/".$content->slug."/list",
                ];
            }
        }




        $response['success'] = true;
        $response['data'] = $data;

        return response()->json($response);


    }
    //----------------------------------------------------------
    //----------------------------------------------------------

}
