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

        $input = $request->all();

        $order = 'desc';
        $order_by = 'id';

        if(isset($input['order'])
            && $input['order']){
            $order = $input['order'];
        }

        if(isset($input['order_by'])
            && $input['order_by']){
            $order_by = $input['order_by'];
        }

        $content_types = ContentType::orderBy($order_by,$order);

        if(isset($input['q'])
            && $input['q']){

            $content_types->where(function ($q) use ($input){
                $q->where('name', 'LIKE', '%'.$input['q'].'%')
                    ->orWhere('slug', 'LIKE', '%'.$input['q'].'%')
                    ->orWhere('singular_slug', 'LIKE', '%'.$input['q'].'%')
                    ->orWhere('plural_slug', 'LIKE', '%'.$input['q'].'%');
            });

        }

        if(isset($input['filters'])
            && is_array($input['filters']) && count($input['filters']) > 0){

                $content_types->where(function ($q) use ($input){
                    foreach ($input['filters'] as $column => $value) {
                        $q->where($column, $value);
                    }
                });

        }

        if(isset($input['per_page'])
            && $input['per_page']
            && is_numeric($input['per_page'])){
            $content_types = $content_types->paginate($input['per_page']);
        }else{
            $content_types = $content_types->paginate(config('vaahcms.per_page'));
        }

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

    public static function getContentTypeItem(Request $request,$column, $value)
    {

        $content_type = ContentType::where($column, $value)->first();

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
