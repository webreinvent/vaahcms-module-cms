<?php namespace VaahCms\Modules\Cms\Entities;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use WebReinvent\VaahCms\Entities\Theme;
use WebReinvent\VaahCms\Entities\ThemeLocation;
use WebReinvent\VaahCms\Entities\User;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;

class Block extends Model {

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_cms_blocks';
    //-------------------------------------------------
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------
    protected $fillable = [
        'uuid',
        'vh_theme_id',
        'vh_theme_location_id',
        'name',
        'slug',
        'excerpt',
        'content',
        'sort',
        'is_published',
        'meta',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    //-------------------------------------------------
    protected $appends  = [
    ];

    //-------------------------------------------------



    //-------------------------------------------------
    protected function serializeDate(DateTimeInterface $date)
    {
        $date_time_format = config('settings.global.datetime_format');

        return $date->format($date_time_format);

    }
    //-------------------------------------------------
    public function setNameAttribute($value)
    {
        if($value)
        {
            $this->attributes['name'] = ucwords($value);
        } else{
            $this->attributes['name'] = null;
        }

    }
    //-------------------------------------------------
    public function getNameAttribute($value)
    {
        if($value)
        {
            return ucwords($value);
        }
        return null;
    }
    //-------------------------------------------------
    public function setContentAttribute($value)
    {
        if($value)
        {
            $this->attributes['content'] = vh_translate_dynamic_strings($value,['has_replace_string' => true]);
        } else{
            $this->attributes['content'] = null;
        }

    }
    //-------------------------------------------------
    public function getContentAttribute($value)
    {
        if($value)
        {
            return vh_translate_dynamic_strings($value);
        }

        return null;

    }
    //-------------------------------------------------
    public function setMetaAttribute($value)
    {
        if($value)
        {
            $this->attributes['meta'] = json_encode($value);
        } else{
            $this->attributes['meta'] = null;
        }
    }
    //-------------------------------------------------
    public function getMetaAttribute($value)
    {
        if($value)
        {
            return json_decode($value);
        }
        return null;
    }
    //-------------------------------------------------
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()
            ->getColumnListing($this->getTable());
    }
    //-------------------------------------------------
    public function scopeExclude($query, $columns)
    {
        return $query->select( array_diff( $this->getTableColumns(),$columns) );
    }
    //-------------------------------------------------
    public function scopeIsPublished($query)
    {
        return $query->where( 'is_published', 1 );
    }
    //-------------------------------------------------
    public function createdByUser()
    {
        return $this->belongsTo(User::class,
            'created_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }

    //-------------------------------------------------
    public function updatedByUser()
    {
        return $this->belongsTo(User::class,
            'updated_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }
    //-------------------------------------------------
    public function deletedByUser()
    {
        return $this->belongsTo(User::class,
            'deleted_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }
    //-------------------------------------------------
    public function theme()
    {
        return $this->belongsTo(Theme::class,
            'vh_theme_id', 'id'
        );
    }
    //-------------------------------------------------
    public function themeLocation()
    {
        return $this->belongsTo(ThemeLocation::class,
            'vh_theme_location_id', 'id'
        );
    }
    //-------------------------------------------------
    public static function postCreate($request)
    {

        $validation = static::validation($request);
        if(isset($validation['status']) && $validation['status'] == 'failed')
        {
            return $validation;
        }

        // check if sort number exist
        $sort_number_exist = static::where('vh_theme_id',$request['vh_theme_id'])
            ->where('vh_theme_location_id',$request['vh_theme_location_id'])
            ->where('sort',$request['sort'])
            ->first();

        if($sort_number_exist)
        {
            $response['status'] = 'failed';
            $response['errors'][] = "Select different sort number.";
            return $response;
        }

        $item = new static();
        $item->fill($request->all());
        $item->save();

        $response['status'] = 'success';
        $response['data']['item'] =$item;
        $response['messages'][] = 'Saved';

        return $response;

    }
    //-------------------------------------------------
    public static function getList($request)
    {
        if($request->has('sort_by') && $request['sort_by'])
        {
            $list = static::orderBy($request['sort_by'], $request['sort_order']);
        }else{
            $list = static::orderBy('id', $request['sort_order']);
        }

        $list->with([ 'themeLocation','theme']);

        if($request->has('trashed') && $request['trashed'] == 'true')
        {

            $list->withTrashed();
        }

        if($request->has('from') && $request->from
            && $request->has('to') && $request->to)
        {
            $list->whereBetween('updated_at',[$request->from." 00:00:00",$request->to." 23:59:59"]);
        }

        if($request->has('filter') &&  $request['filter'])
        {
            if($request['filter'] == '1')
            {
                $list->where('is_published',$request['filter']);
            }elseif($request['filter'] == '10'){
                $list->whereNull('is_published')->orWhere('is_published',0);
            }else{
                $list->with(['themeLocation'])
                    ->whereHas('themeLocation', function ($q) use ($request){
                        $q->where('slug', $request['filter']);
                    });

            }
        }

        if($request->has('q') && $request->q)
        {
            $search_array = explode(" ",$request->q);

            foreach ($search_array as $item){
                $list->where(function ($q) use ($item){
                    $q->where('name', 'LIKE', '%'.$item.'%')
                        ->orWhere('id', 'LIKE', $item.'%')
                        ->orWhere('slug', 'LIKE', '%'.$item.'%');
                });
            }
        }


        $data['list'] = $list->paginate(config('vaahcms.per_page'));

        $response['status'] = 'success';
        $response['data'] = $data;

        return $response;


    }
    //-------------------------------------------------
    public static function validation($request)
    {
        $rules = array(
            'name' => 'required|unique:vh_cms_blocks|max:60',
            'slug' => 'required|unique:vh_cms_blocks|max:60',
            'content' => 'required',
            'vh_theme_id' => 'required',
            'vh_theme_location_id' => 'required',
            'sort' => 'required|numeric|min:0|regex:/^\d{1,13}?$/',
        );
        $messages = array(
            'vh_theme_id.required' => 'Select a theme.',
            'vh_theme_location_id.required' => 'Select a theme location.',
        );

        $validator = \Validator::make( $request->all(), $rules, $messages);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

        $response['status'] = 'success';

        return $response;

    }
    //-------------------------------------------------
    public static function storeValidation($request)
    {
        $rules = array(
            'name' => 'required|max:60',
            'slug' => 'required|max:60',
            'content' => 'required',
            'vh_theme_id' => 'required',
            'vh_theme_location_id' => 'required',
            'sort' => 'required|numeric|min:0|regex:/^\d{1,13}?$/',
        );
        $messages = array(
            'vh_theme_id.required' => 'Select a theme.',
            'vh_theme_location_id.required' => 'Select a theme location.',
        );

        $validator = \Validator::make( $request->all(), $rules, $messages);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

        $response['status'] = 'success';

        return $response;

    }
    //-------------------------------------------------

    //-------------------------------------------------
    public static function getItem($id)
    {

        $item = static::where('id', $id)
            ->with([
                'theme', 'themeLocation',
                'createdByUser', 'updatedByUser',
                'deletedByUser',
            ])
            ->withTrashed()
            ->first();

        $response['status'] = 'success';
        $response['data'] = $item;

        return $response;

    }
    //-------------------------------------------------
    public static function postStore($request,$id)
    {

        $validation = static::storeValidation($request);
        if(isset($validation['status']) && $validation['status'] == 'failed')
        {
            return $validation;
        }


        // check if name exist
        $name_exist = static::where('id','!=',$request['id'])
            ->where('name',$request['name'])
            ->first();

        if($name_exist)
        {
            $response['status'] = 'failed';
            $response['errors'][] = "This name is already exist.";
            return $response;
        }


        // check if slug exist
        $slug_exist = static::where('id','!=',$request['id'])
            ->where('slug',$request['slug'])
            ->first();

        if($slug_exist)
        {
            $response['status'] = 'failed';
            $response['errors'][] = "This slug is already exist.";
            return $response;
        }


        // check if sort number exist
        $sort_number_exist = static::where('id','!=',$request['id'])
            ->where('vh_theme_id',$request['vh_theme_id'])
            ->where('vh_theme_location_id',$request['vh_theme_location_id'])
            ->where('sort',$request['sort'])
            ->first();

        if($sort_number_exist)
        {
            $response['status'] = 'failed';
            $response['errors'][] = "Select different sort number.";
            return $response;
        }

        $update = static::where('id',$id)->withTrashed()->first();

        $update->fill($request->all());
        $update->save();


        $response['status'] = 'success';
        $response['data'] = [];
        $response['messages'][] = 'Data updated.';

        return $response;

    }
    //-------------------------------------------------
    public static function bulkStatusChange($request)
    {
        if(!$request->has('inputs'))
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Select IDs';
            return $response;
        }

        if(!$request->has('data'))
        {
            $response['status'] = 'failed';
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
                $role->is_published = $request['data']['status'];
            }else{
                if($role->is_published == 1){
                    $role->is_published = 0;
                }else{
                    $role->is_published = 1;
                }
            }
            $role->save();
        }

        $response['status'] = 'success';
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;


    }
    //-------------------------------------------------
    public static function bulkTrash($request)
    {

        if(!$request->has('inputs'))
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Select IDs';
            return $response;
        }


        foreach($request->inputs as $id)
        {
            $item = static::withTrashed()->where('id', $id)->first();
            if($item)
            {
                $item->is_published = 0;
                $item->save();
                $item->delete();
            }
        }

        $response['status'] = 'success';
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;


    }
    //-------------------------------------------------
    public static function bulkRestore($request)
    {


        if(!$request->has('inputs'))
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Select IDs';
            return $response;
        }

        if(!$request->has('data'))
        {
            $response['status'] = 'failed';
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

        $response['status'] = 'success';
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;

    }
    //-------------------------------------------------
    public static function bulkDelete($request)
    {

        if(!$request->has('inputs'))
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Select IDs';
            return $response;
        }


        foreach($request->inputs as $id)
        {
            $item = static::where('id', $id)->withTrashed()->first();
            if($item)
            {
                $item->forceDelete();
            }
        }

        $response['status'] = 'success';
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;
    }

    //---------------------------------------------------------------------------
    public static function getBlock($block_slug)
    {

        if(!$block_slug){
            return false;
        }


        $block = self::where('is_published',1)
            ->where('slug',$block_slug)
            ->where('vh_theme_id',vh_get_theme_id())
            ->first();

        if(!$block)
        {
            return false;
        }

        return vh_translate_dynamic_strings($block->content);
    }

    //---------------------------------------------------------------------------
    public static function getBlocksByLocation($location)
    {


        if(!$location)
        {
            return false;
        }

        $blocks = self::where('vh_theme_location_id', $location->id)
            ->where('is_published',1)
            ->orderBy('sort','asc')
            ->get();

        if(count($blocks) < 1)
        {
            return false;
        }

        $data = "";

        foreach ($blocks as $block){
            $data .= vh_translate_dynamic_strings($block->content);
        }


        return $data;
    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------

}
