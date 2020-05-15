<?php namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use WebReinvent\VaahCms\Entities\User;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;

class Content extends Model {

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_cms_contents';
    //-------------------------------------------------
    protected $dates = [
        'is_published_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------
    protected $fillable = [
        'uuid',
        'parent_id',
        'vh_cms_content_type_id',
        'vh_theme_id',
        'vh_theme_template_id',
        'name',
        'slug',
        'is_published_at',
        'status',
        'total_comments',
        'meta',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

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
    public function contentType()
    {
        return $this->belongsTo(ContentType::class,
            'vh_cms_content_type_id', 'id'
        );
    }
    //-------------------------------------------------
    public function fields()
    {
        return $this->hasMany(ContentField::class,
            'vh_cms_content_id', 'id'
        );
    }
    //-------------------------------------------------
    public static function postCreate($request)
    {

        /*$validation = static::validation($request);
        if(isset($validation['status']) && $validation['status'] == 'failed')
        {
            return $validation;
        }*/



        $inputs = $request->all();

        $item = new static();

        $fillable['name'] = $inputs['name'];
        $fillable['name'] = Str::slug($inputs['name']);
        $fillable['vh_cms_content_type_id'] = $request->content_type->id;
        $fillable['vh_theme_id'] = $request->vh_theme_id;
        $fillable['vh_theme_template_id'] = $request->vh_theme_template_id;
        $fillable['is_published_at'] = \Carbon::now();
        $fillable['status'] = 'published';

        $item->fill($fillable);
        $item->save();


        foreach ($inputs['groups'] as $group)
        {

            foreach ($group['fields'] as $field)
            {
                $content_field = [];
                $content_field['vh_cms_content_id'] = $item->id;
                $content_field['vh_cms_group_id'] = $group['id'];
                $content_field['vh_cms_group_field_id'] = $field['id'];
                $content_field['content'] = $field['content'];

                $store_field = new ContentField();
                $store_field->fill($content_field);
                $store_field->save();

            }

        }

        foreach ($inputs['template_groups'] as $group)
        {

            foreach ($group['fields'] as $field)
            {
                $content_field = [];
                $content_field['vh_cms_content_id'] = $item->id;
                $content_field['vh_template_id'] = $field['vh_template_id'];
                $content_field['vh_template_field_id'] = $field['id'];
                $content_field['content'] = $field['content'];

                $store_field = new ContentField();
                $store_field->fill($content_field);
                $store_field->save();

            }

        }

        $response['status'] = 'success';
        $response['data']['item'] =$item;
        $response['messages'][] = 'Saved';

        return $response;

    }
    //-------------------------------------------------
    public static function getList($request)
    {

        $list = static::orderBy('id', 'desc');

        $list->where('vh_cms_content_type_id', $request->content_type->id);

        if($request['trashed'] == 'true')
        {

            $list->withTrashed();
        }

        if(isset($request->from) && isset($request->to))
        {
            $list->whereBetween('updated_at',[$request->from." 00:00:00",$request->to." 23:59:59"]);
        }

        if($request['filter'] && $request['filter'] == '1')
        {

            $list->where('is_active',$request['filter']);
        }elseif($request['filter'] == '10'){

            $list->whereNull('is_active')->orWhere('is_active',0);
        }

        if(isset($request->q))
        {

            $list->where(function ($q) use ($request){
                $q->where('name', 'LIKE', '%'.$request->q.'%')
                    ->orWhere('slug', 'LIKE', '%'.$request->q.'%');
            });
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
            'name' => 'required',
            'slug' => 'required|unique:vh_cms_content_types',
            'plural' => 'required',
            'plural_slug' => 'required|unique:vh_cms_content_types',
            'singular' => 'required',
            'singular_slug' => 'required|unique:vh_cms_content_types',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

        $data = [];

        $response['status'] = 'success';

        return $response;

    }
    //-------------------------------------------------
    public static function getItem($id)
    {

        $item = static::where('id', $id)
            ->with(['createdByUser', 'updatedByUser', 'deletedByUser'])
            ->with(['fields' => function($f){
                $f->with(['group', 'field']);
            }])
            ->withTrashed()
            ->first();

        $groups = [];

        $content_type = $item->contentType;


        $i = 0;
        foreach ($content_type->groups as $group)
        {

            $groups[$i] = $group;

            $y = 0;
            foreach ($group->fields as $field)
            {
                $groups[$i]['fields'][$y] = $field;
                $groups[$i]['fields'][$y]['type'] = $field->type;


                $groups[$i]['fields'][$y]['vh_cms_content_field_id'] = null;
                $groups[$i]['fields'][$y]['content'] = null;
                $groups[$i]['fields'][$y]['meta'] = null;



                $content = ContentField::where('vh_cms_content_id', $item->id);
                $content->where('vh_cms_group_id', $group->id);
                $content->where('vh_cms_group_field_id', $field->id);
                $content = $content->first();


                if($content)
                {
                    $groups[$i]['fields'][$y]['vh_cms_content_field_id'] = $content->id;
                    $groups[$i]['fields'][$y]['content'] = $content->content;
                    $groups[$i]['fields'][$y]['meta'] = $content->meta;
                }


                $y++;
            }

            $i++;
        }

        $response['status'] = 'success';
        $response['data']['item'] = $item;
        $response['data']['groups'] = $groups;

        return $response;

    }
    //-------------------------------------------------
    public static function postStore($request,$id)
    {

        $inputs = $request->all();

        $item = static::where('id',$id)->withTrashed()->first();

        $item->fill($inputs['item']);
        $item->slug = Str::slug($inputs['item']['name']);
        $item->save();


        $i = 0;
        foreach ($inputs['groups'] as $group)
        {

            $groups[$i] = $group;

            $y = 0;
            foreach ($group['fields'] as $field)
            {
                $stored_field = null;
                if(isset($field['vh_cms_content_field_id']) && !empty($field['vh_cms_content_field_id']))
                {
                    $stored_field = ContentField::find($field['vh_cms_content_field_id']);
                }

                if(!$stored_field)
                {
                    $stored_field = new ContentField();
                    $stored_field->vh_cms_content_id = $item->id;
                    $stored_field->vh_cms_group_id = $group['id'];
                    $stored_field->vh_cms_group_field_id = $field['id'];
                }

                if(is_array($field['content']) || is_object($field['content']))
                {
                    $field['content'] = json_encode($field['content']);
                }

                $stored_field->content = $field['content'];
                $stored_field->meta = $field['meta'];
                try{
                    $stored_field->save();
                }catch(\Exception $e)
                {
                    $response['status'] = 'failed';
                    $response['inputs'] = $field;
                    $response['errors'][] = $e->getMessage();
                   return $response;
                }


                $y++;
            }

            $i++;
        }


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
                $item->is_active = 0;
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

        if(!\Auth::user()->hasPermission('can-update-roles') ||
            !\Auth::user()->hasPermission('can-delete-roles'))
        {
            $response['status'] = 'failed';
            $response['errors'][] = trans("vaahcms::messages.permission_denied");

            return $response;
        }

        if(!\Auth::user()->hasPermission('can-update-roles'))
        {
            $response['status'] = 'failed';
            $response['errors'][] = trans("vaahcms::messages.permission_denied");

            return $response;
        }

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
            $item = static::where('id', $id)->withTrashed()->first();
            if($item)
            {

                $item->permissions()->detach();

                $item->users()->detach();

                $item->forceDelete();

            }
        }

        $response['status'] = 'success';
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;
    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------

}
