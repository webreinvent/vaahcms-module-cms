<?php namespace VaahCms\Modules\Cms\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use VaahCms\Modules\Cms\Entities\Content;
use VaahCms\Modules\Cms\Entities\ContentType;
use VaahCms\Modules\Cms\Entities\FieldType;

class ContentTypesController extends Controller
{


    //----------------------------------------------------------
    public function __construct()
    {
    }


    //----------------------------------------------------------
    //----------------------------------------------------------

    public static function getContentTypeList(Request $request)
    {

        $content_types = ContentType::paginate(5);

        foreach ($content_types as $content_type){

            $arr = array();

            foreach ($content_type->groups as $group){
                foreach ($group->fields as $key => $field){

                    $arr[$group->slug]['is_repeatable'] = (boolean) $group->is_repeatable;

                    $arr[$group->slug]['fields'][$field->slug] = [

                        'type' => $field->type->slug,
                        'is_repeatable' => (boolean) $field->is_repeatable,
                        'meta' => $field->meta

                    ];
                }
            }

            unset($content_type['groups']);

            $content_type['groups'] = $arr;
        }


        $response['status']     = 'success';
        $response['data']       = $content_types;
        return $response;
    }


    //----------------------------------------------------------
    //----------------------------------------------------------

    public static function getContentTypeItem(Request $request, $slug)
    {

        $content_type = ContentType::where('slug', $slug)->first();

        $arr = array();

        foreach ($content_type->groups as $group){
            foreach ($group->fields as $key => $field){

                $arr[$group->slug]['is_repeatable'] = (boolean) $group->is_repeatable;

                $arr[$group->slug]['fields'][$field->slug] = [

                    'type' => $field->type->slug,
                    'is_repeatable' => (boolean) $field->is_repeatable,
                    'meta' => $field->meta

                ];
            }
        }

        unset($content_type['groups']);

        $content_type['groups'] = $arr;

        $response['status']     = 'success';
        $response['data']       = $content_type;
        return $response;
    }

    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------


}
