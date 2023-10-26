<?php

namespace VaahCms\Modules\Cms\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use VaahCms\Modules\Cms\Models\MenuItem;
use VaahCms\Modules\Cms\Http\Controllers\Backend\MenusController;


class MenuBase extends Model
{
    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'vh_cms_menus';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';

    //-------------------------------------------------

    //-------------------------------------------------

    protected $fillable = [
        'name', 'slug', 'attr_id',
        'attr_class', 'vh_theme_location_id', 'vh_permission_slug',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------



    //-------------------------------------------------
    protected function serializeDate(DateTimeInterface $date)
    {
        $date_time_format = config('settings.global.datetime_format');

        return $date->format($date_time_format);

    }
    //-------------------------------------------------
    public function items()
    {
        return $this->hasMany(MenuItem::class,
            'vh_menu_id', 'id');
    }
    //-------------------------------------------------
    public static function postCreate($request)
    {

        $rules = array(
            'name' => 'required|max:255',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['success'] = false;
            $response['errors'] = $errors;
            return $response;
        }

        $user = static::where('vh_theme_location_id',$request['vh_theme_location_id'])->where('name', $request['name'])
            ->first();

        if($user)
        {
            $response['success'] = false;
            $response['errors'][] = "This name is already exist.";
            return $response;
        }


        // check if slug exist
        $user = static::where('vh_theme_location_id',$request['vh_theme_location_id'])->where('slug',$request['slug'])->first();

        if($user)
        {
            $response['success'] = false;
            $response['errors'][] = "This slug is already exist.";
            return $response;
        }



        $item = new static();
        $item->fill($request->all());
        $item->slug = Str::slug($request->name);
        $item->save();


        $item = static::getItem($item->id);

        $menu = new MenusController();

        $response['success'] = true;
        $response['data']['item'] =$item['data'];
        $response['data']['assets'] = $menu->getAssets($request);
        $response['messages'][] = 'Saved';

        return $response;

    }
    //-------------------------------------------------
    public static function getList($request)
    {

        $list = static::orderBy('id', 'desc');

        $data['list'] = $list->get();

        $response['success'] = true;
        $response['data'] = $data;

        return $response;


    }
    //-------------------------------------------------
    public static function getItem($id)
    {

        $item = static::where('id', $id)
            ->withTrashed()
            ->first();

        $response['success'] = true;
        $response['data'] = $item;

        return $response;

    }
    //-------------------------------------------------
    public static function postStore($request,$id)
    {

        $input = $request->all();


        $rules = array(
            'id' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['success'] = false;
            $response['errors'] = $errors;
            return response()->json($response);
        }

        // check if name exist
        $user = static::where('id','!=',$input['id'])->where('vh_theme_location_id',$input['vh_theme_location_id'])->where('name', $input['name'])
            ->first();

        if($user)
        {
            $response['success'] = false;
            $response['errors'][] = "This name is already exist.";
            return $response;
        }


        // check if slug exist
        $user = static::where('id','!=',$input['id'])->where('vh_theme_location_id',$input['vh_theme_location_id'])->where('slug',$input['slug'])->first();

        if($user)
        {
            $response['success'] = false;
            $response['errors'][] = "This slug is already exist.";
            return $response;
        }

        $menu = static::where('id',$id)->withTrashed()->first();

        $menu->fill($request->all());
        $menu->slug = Str::slug($input['slug']);
        $menu->save();

        if($request->has('items') && count($request->items) > 0)
        {
            MenuItem::storeItems($menu->id, null, $request->items);
        }

        $menu = static::getItem($menu->id);
        $menu_items = MenuBase::getMenuItems($id);

        $menu_items = $menu_items['data'];

        $response['success'] = true;
        $response['data']['menu'] =$menu['data'];
        $response['data']['menu_items'] =$menu_items;
        $response['messages'][] = 'Saved';


        return $response;

    }
    //-------------------------------------------------
    public static function bulkStatusChange($request)
    {
        if(!$request->has('inputs'))
        {
            $response['success'] = false;
            $response['errors'][] = 'Select IDs';
            return $response;
        }

        if(!$request->has('data'))
        {
            $response['success'] = false;
            $response['errors'][] = 'Select Status';
            return $response;
        }

        foreach($request->inputs as $id)
        {
            $role = static::where('id',$id)->withTrashed()->first();

            if($role->deleted_at){
                continue ;
            }

            if($request['data']){
                $role->is_active = $request['data']['status'];
            }else{
                if($role->is_active == 1){
                    $role->is_active = 0;
                }else{
                    $role->is_active = 1;
                }
            }
            $role->save();
        }

        $response['success'] = true;
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;


    }
    //-------------------------------------------------
    public static function bulkTrash($request)
    {

        if(!$request->has('inputs'))
        {
            $response['success'] = false;
            $response['errors'][] = 'Select IDs';
            return $response;
        }


        foreach($request->inputs as $id)
        {
            $item = static::withTrashed()->where('id', $id)->first();
            if($item)
            {
                $item->is_active = 0;
                $item->save();
                $item->delete();
            }
        }

        $response['success'] = true;
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;


    }
    //-------------------------------------------------
    public static function bulkRestore($request)
    {


        if(!$request->has('inputs'))
        {
            $response['success'] = false;
            $response['errors'][] = 'Select IDs';
            return $response;
        }

        if(!$request->has('data'))
        {
            $response['success'] = false;
            $response['errors'][] = 'Select Status';
            return $response;
        }

        foreach($request->inputs as $id)
        {
            $item = static::withTrashed()->where('id', $id)->first();
            if(isset($item) && isset($item->deleted_at))
            {
                $item->restore();
            }
        }

        $response['success'] = true;
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;

    }
    //-------------------------------------------------
    public static function bulkDelete($request)
    {



        if(!$request->has('inputs'))
        {
            $response['success'] = false;
            $response['errors'][] = 'Select IDs';
            return $response;
        }



        foreach($request->inputs as $id)
        {
            $menu = static::where('id', $id)->withTrashed()->first();
            if($menu)
            {

                $menu->items()->forceDelete();

                $menu->forceDelete();

            }
        }

        $menu = new MenusController();



        $response['success'] = true;
        $response['data']['assets'] = $menu->getAssets($request);
        $response['messages'][] = 'Action was successful';

        return $response;
    }
    //-------------------------------------------------
    public static function getMenuItems($id)
    {

        $items = MenuItem::where('vh_menu_id', $id)
            ->with(['content' => function($c){
                $c->select('id', 'name', 'slug');
            }])
            ->orderBy('sort')
            ->get()->toArray();


        $body = [
            'id','parent_id', 'type', 'name', 'slug',
            'title', 'attr_id', 'attr_class', 'attr_target_blank',
            'meta', 'uri',
            'sort', 'is_home', 'is_active', 'vh_permission_slug',
            'content'
        ];

        \Config::set('nestable.body', $body);

        $response['success'] = true;
        $response['data'] = \Nestable::make($items)
            ->renderAsArray();

        return $response;

    }
    //-------------------------------------------------
    public static function setAsHomePage($request)
    {
        MenuItem::where('is_home', '!=', null)->update(['is_home' => null]);

        $menu_item = MenuItem::find($request->inputs);
        $menu_item->is_home = 1;
        $menu_item->save();

        $response['success'] = true;
        $response['data'] = [];

        return $response;
    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
