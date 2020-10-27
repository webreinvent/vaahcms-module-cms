<?php namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use WebReinvent\VaahCms\Entities\User;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;

class ContentType extends Model {

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_cms_content_types';
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
        'name',
        'slug',
        'plural',
        'plural_slug',
        'singular',
        'singular_slug',
        'excerpt',
        'is_published',
        'is_commentable',
        'content_statuses',
        'total_records',
        'published_records',
        'total_comments',
        'meta',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    //-------------------------------------------------
    protected $appends  = [
    ];
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

    //-------------------------------------------------
    public function setContentStatusesAttribute($value)
    {
        if($value)
        {
            $this->attributes['content_statuses'] = json_encode($value);
        } else{
            $this->attributes['content_statuses'] = null;
        }
    }
    //-------------------------------------------------
    public function getContentStatusesAttribute($value)
    {
        if($value)
        {
            return json_decode($value);
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
    public function contents()
    {
        return $this->hasMany(Content::class,
            'vh_cms_content_type_id', 'id');
    }
    //-------------------------------------------------
    public function groups()
    {
        return $this->morphMany(FormGroup::class, 'groupable')
            ->orderBy('sort', 'asc');
    }
    //-------------------------------------------------
    public static function postCreate($request)
    {

        $validation = static::validation($request);
        if(isset($validation['status']) && $validation['status'] == 'failed')
        {
            return $validation;
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

        $list = static::orderBy('id', 'desc');

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

            $list->where('is_published',$request['filter']);
        }elseif($request['filter'] == '10'){

            $list->whereNull('is_published')->orWhere('is_published',0);
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

    //-------------------------------------------------
    public static function getItem($id)
    {

        $item = static::where('id', $id)->with(['createdByUser', 'updatedByUser', 'deletedByUser'])
            ->withTrashed()
            ->first();

        $response['status'] = 'success';
        $response['data'] = $item;

        return $response;

    }
    //-------------------------------------------------
    public static function getItemWithRelations($id)
    {

        $item = static::where('id', $id)
            ->with(['createdByUser', 'updatedByUser',
                'deletedByUser',
                'groups'=>function($g){
                $g->orderBy('sort', 'asc')->with(['fields' => function($f){
                    $f->orderBy('sort', 'asc')->with(['type']);
                }]);
                }])
            ->withTrashed()
            ->first();

        $response['status'] = 'success';
        $response['data'] = $item;

        return $response;

    }
    //-------------------------------------------------
    public static function syncWithFormGroups(ContentType $content_type, $groups_array)
    {


        $stored_groups = $content_type->groups()->get()->pluck('id')->toArray();

        $input_groups = collect($groups_array)->pluck('id')->toArray();
        $groups_to_delete = array_diff($stored_groups, $input_groups);


        if(count($groups_to_delete) > 0)
        {
            FormGroup::deleteItems($groups_to_delete);
        }



        foreach($groups_array as $g_index => $group)
        {

            $group['sort'] = $g_index;
            $group['slug'] = Str::slug($group['name']);

            $stored_group = $content_type->groups()->where('slug', $group['slug'])->first();

            $group_fillable = $group;
            unset($group_fillable['fields']);


            if($stored_group)
            {
                $stored_group->fill($group_fillable);
                $stored_group =$content_type->groups()->save($stored_group);
            } else{
                $stored_group = $content_type->groups()->create($group_fillable);
            }


            FormGroup::syncWithFormFields($stored_group, $group['fields']);

        }


    }
    //-------------------------------------------------
    public static function postStoreGroups($request,$id)
    {

        $rules = array(
            '*.fields' => 'array',
            '*.fields.*.name' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }


        $content_type = static::find($id);

        //find delete groups
        static::syncWithFormGroups($content_type, $request->all());


        $response = [];

        $response['status'] = 'success';
        $response['data'][] = '';
        $response['messages'][] = 'Action was successful';
        if(env('APP_DEBUG'))
        {
            $response['hint'][] = '';
        }
        return $response;


    }
    //-------------------------------------------------
    public static function postStore($request,$id)
    {



        $input = $request->item;


        $validation = static::validation($input);
        if(isset($validation['status']) && $validation['status'] == 'failed')
        {
            return $validation;
        }

        // check if name exist
        $user = static::where('id','!=',$input['id'])->where('name',$input['name'])->first();

        if($user)
        {
            $response['status'] = 'failed';
            $response['errors'][] = "This name is already exist.";
            return $response;
        }


        // check if slug exist
        $user = static::where('id','!=',$input['id'])->where('slug',$input['slug'])->first();

        if($user)
        {
            $response['status'] = 'failed';
            $response['errors'][] = "This slug is already exist.";
            return $response;
        }

        $update = static::where('id',$id)->withTrashed()->first();

        $update->name = $input['name'];
        $update->slug = Str::slug($input['slug']);
        $update->details = $input['details'];
        $update->is_active = $input['is_active'];

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
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------

}
