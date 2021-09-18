<?php namespace VaahCms\Modules\Cms\Entities;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use WebReinvent\VaahCms\Entities\Taxonomy;
use WebReinvent\VaahCms\Entities\Theme;
use WebReinvent\VaahCms\Entities\ThemeTemplate;
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
        'permalink',
        'author',
        'is_published_at',
        'status',
        'total_comments',
        'meta',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    //-------------------------------------------------
    protected $appends  = [
        'link_prefix', 'link'
    ];

    //-------------------------------------------------



    //-------------------------------------------------
    protected function serializeDate(DateTimeInterface $date)
    {
        $date_time_format = config('settings.global.datetime_format');

        return $date->format($date_time_format);

    }
    //-------------------------------------------------

    //-------------------------------------------------
    public function setSlugAttribute($value)
    {
        if($value)
        {
            $this->attributes['slug'] = Str::slug($value);
        } else{
            $this->attributes['slug'] = null;
        }
    }
    //-------------------------------------------------
    public function setPermalinkAttribute($value)
    {
        if($value)
        {
            $this->attributes['permalink'] = Str::slug($value);
        } else{
            $this->attributes['permalink'] = null;
        }
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
    public function getLinkPrefixAttribute()
    {
        if(!$this->permalink)
        {
            return null;
        }

        $link = url('/').'/';

        $type = $this->contentType;

        if($type && $type->slug != 'pages')
        {
            $link .= $type->slug."/";
        }

        return $link;
    }
    //-------------------------------------------------
    public function getLinkAttribute()
    {
        if(!$this->permalink)
        {
            return null;
        }

        $perfix = $this->getLinkPrefixAttribute();

        $link = $perfix.$this->permalink;

        return $link;
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
    public function authorUser()
    {
        return $this->belongsTo(User::class,
            'author', 'id'
        )->select(
            'id', 'uuid', 'first_name', 'last_name', 'email',
            'username', 'display_name', 'title', 'designation', 'bio', 'website','meta','avatar_url'
        );
    }
    //-------------------------------------------------
    public function contentType()
    {
        return $this->belongsTo(ContentType::class,
            'vh_cms_content_type_id', 'id'
        );
    }
    //-------------------------------------------------
    public function theme()
    {
        return $this->belongsTo(Theme::class,
            'vh_theme_id', 'id'
        );
    }
    //-------------------------------------------------
    public function template()
    {
        return $this->belongsTo(ThemeTemplate::class,
            'vh_theme_template_id', 'id'
        );
    }
    //-------------------------------------------------
    public function fields()
    {
        return $this->hasMany(ContentFormField::class,
            'vh_cms_content_id', 'id'
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

        $name_exist = static::where('vh_cms_content_type_id',$request->content_type->id)
            ->where('name',$request['name'])->first();

        if($name_exist)
        {
            $response['status'] = 'failed';
            $response['errors'][] = "This name is already exist.";
            return $response;
        }

        $inputs = $request->all();

        $item = new static();

        $fillable['name'] = $inputs['name'];
        $fillable['slug'] = $inputs['name'];
        $fillable['permalink'] = $inputs['permalink'];
        $fillable['vh_cms_content_type_id'] = $request->content_type->id;
        $fillable['vh_theme_id'] = $request->vh_theme_id;
        $fillable['vh_theme_template_id'] = $request->vh_theme_template_id;
        $fillable['is_published_at'] = \Carbon::now();
        if(!$request->has('status'))
        {
            $fillable['status'] = 'published';
        } else{
            $fillable['status'] = $request->status;
        }

        $item->fill($fillable);
        $item->save();



        foreach ($inputs['content_groups'] as $arr_group)
        {

            foreach ($arr_group as $key => $group) {


                foreach ($group['fields'] as $field) {
                    $content_field = [];
                    $content_field['vh_cms_content_id'] = $item->id;
                    $content_field['vh_cms_form_group_id'] = $group['id'];
                    $content_field['vh_cms_form_field_id'] = $field['id'];
                    $content_field['vh_cms_form_group_index'] = $key;

                    if (isset($field['content'])) {

                        $content_field['content'] = $field['content'];
                        $store_field = new ContentFormField();
                        $store_field->fill($content_field);
                        $store_field->save();


                        if($field['type']['slug'] == 'relation'){

                            $relation =  vh_content_relations_by_name($field['meta']['type']);

                            if($relation && isset($relation['namespace']) && $relation['namespace']){
                                foreach ($field['content'] as $id){
                                    $data = [
                                        'relatable_id' => $id,
                                        'relatable_type' => $relation['namespace']
                                    ];

                                    $store_field->contentFormRelations()->updateOrCreate($data);
                                }
                            }

                        }

                    }

                }
            }

        }

        foreach ($inputs['template_groups'] as $arr_group)
        {

            foreach ($arr_group as $key => $group) {


                foreach ($group['fields'] as $field) {
                    $content_field = [];
                    $content_field['vh_cms_content_id'] = $item->id;
                    $content_field['vh_cms_form_group_id'] = $group['id'];
                    $content_field['vh_cms_form_field_id'] = $field['id'];
                    $content_field['vh_cms_form_group_index'] = $key;

                    if (isset($field['content'])) {

                        $content_field['content'] = $field['content'];
                        $store_field = new ContentFormField();
                        $store_field->fill($content_field);
                        $store_field->save();

                    }

                }
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

        if($request['sort_by'])
        {
            $list = static::orderBy($request['sort_by'], $request['sort_order']);
        }else{
            $list = static::orderBy('id', $request['sort_order']);
        }

        $list->where('vh_cms_content_type_id', $request->content_type->id);

        if($request['trashed'] == 'true')
        {

            $list->withTrashed();
        }

        if(isset($request->from) && isset($request->to))
        {
            $list->whereBetween('updated_at',[$request->from." 00:00:00",$request->to." 23:59:59"]);
        }

        if($request['filter'])
        {
            $list->where('status',$request['filter']);
        }

        if(isset($request->q))
        {
            $search_array = explode(" ",$request->q);

            foreach ($search_array as $item){
                $list->where(function ($q) use ($item){
                    $q->where('name', 'LIKE', '%'.$item.'%')
                        ->orWhere('id', 'LIKE', $item.'%')
                        ->orWhere('slug', 'LIKE', '%'.$item.'%')
                        ->orWhere('permalink', 'LIKE', '%'.$item.'%');
                });
            }
        }


        $data['list'] = $list->paginate(config('vaahcms.per_page'));


        $status = ContentType::where('id', $request->content_type->id);

        $status_list = $status->select('content_statuses')->first();



        $response['status'] = 'success';
        $response['data'] = $data;
        $response['status'] = $status_list;

        return $response;


    }
    //-------------------------------------------------
    public static function validation($request)
    {
        $rules = array(
            'name' => 'required|max:255',
            'status' => 'required',
            'vh_theme_id' => 'required',
            'vh_theme_template_id' => 'required'
        );

        if($request->has('id'))
        {
            $rules['permalink'] = 'required|unique:vh_cms_contents,permalink,'.$request->id.'|max:100';
        } else {
            $rules['permalink'] = 'required|unique:vh_cms_contents|max:100';
        }

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
            ->with([
                'contentType', 'theme', 'template',
                'authorUser', 'createdByUser', 'updatedByUser',
                'deletedByUser',
                'fields' => function($f){
                    $f->with(['group', 'field']);
                }
            ])
            ->withTrashed()
            ->first();


        $content_form_groups = static::getFormGroups($item, 'content');

        $template_form_groups = static::getFormGroups($item, 'template');

        $response['status'] = 'success';

        $item->content_form_groups = $content_form_groups;
        $item->template_form_groups = $template_form_groups;

        $response['data'] = $item;

        return $response;

    }
    //-------------------------------------------------
    public static function getFormGroups(Content $content, $type, array $fields=null)
    {
        if($type=='content')
        {
            $groups = $content->contentType->groups;
        } else{
            $groups = $content->template->groups;
        }


        $arr_group = [];



        $i = 0;

        foreach ($groups as $group)
        {

            $group_fields = ContentFormField::where('vh_cms_content_id',$content->id)
                ->where('vh_cms_form_group_id',$group->id)
                ->get();


            $group_fields = collect($group_fields)->groupBy('vh_cms_form_group_index');


            if(count($group_fields) === 0 ){
                $group_fields[] = '';
            }

            foreach ($group_fields as $key => $fields){

                $arr_group[$i][$key] = [
                    'id' => $group->id,
                    'name' => $group->name,
                    'slug' => $group->slug,
                    'is_repeatable' => $group->is_repeatable,
                ];

                $y = 0;


                foreach ($group->fields as $field)
                {
                    $arr_group[$i][$key]['fields'][$y] = [
                        'id' => $field->id,
                        'name' => $field->name,
                        'slug' => $field->slug,
                        'vh_cms_form_group_id' => $field->vh_cms_form_group_id,
                        'is_repeatable' => $field->is_repeatable,
                        'is_searchable' => $field->is_searchable,
                        'meta' => $field->meta
                    ];

                    $arr_group[$i][$key]['fields'][$y]['type'] = $field->type;


                    $arr_group[$i][$key]['fields'][$y]['vh_cms_form_field_id'] = null;
                    $arr_group[$i][$key]['fields'][$y]['content'] = null;
                    $arr_group[$i][$key]['fields'][$y]['content_meta'] = null;

                    $field_content = ContentFormField::where(['vh_cms_content_id'=> $content->id,
                        'vh_cms_form_group_id'=> $group->id,
                        'vh_cms_form_field_id'=> $field->id,
                        'vh_cms_form_group_index'=> $key])
                    ->first();

                    if($field->type->slug == 'relation' && $field_content
                        && isset($field_content->contentFormRelations)
                        && count($field_content->contentFormRelations) > 0){

                        $field_content->content = $field_content['contentFormRelations']
                            ->pluck('relatable_id');

                    }




                    if($field_content)
                    {

                        $arr_group[$i][$key]['fields'][$y]['vh_cms_form_field_id'] = $field_content->id;

                        if(!$field->is_repeatable
                            && (is_array($field_content->content)
                                || is_object($field_content->content))
                            && $field->type->slug != 'seo-meta-tags'
                            && count($field_content->content) <= 1) {

                            $content_val = null;

                            if (count($field_content->content) == 1) {
                                $content_val = $field_content->content[0];
                            }

                        }elseif($field->is_repeatable
                            && is_string($field_content->content) ){
                            $content_val = [$field_content->content];
                        }else{
                            $content_val = $field_content->content;
                        }

                        $content_val = ContentFormField::getContentAsset($content_val, $field->type->slug);

                        $arr_group[$i][$key]['fields'][$y]['content'] = $content_val;

                        $arr_group[$i][$key]['fields'][$y]['content_meta'] = $field_content->meta;
                    }

                    $y++;

                }

            }

            $i++;

        }

        return $arr_group;
    }
    //-------------------------------------------------
    public static function getFormGroupsTest(Content $content,
                                             $type,
                                             $group_fields,
                                             $filter = null)
    {

        if($type=='content')
        {
            $groups = $content->contentType->groups;

        } else{
            $groups = $content->template->groups;
        }

        $arr_group = [];

        $fields_list = collect($content->fields);


        $i = 0;

        foreach ($groups as $group)
        {

            if((isset($filter['include_groups']) && count($filter['include_groups']) >  0
                    && !in_array($group['slug'], $filter['include_groups']))
                || (isset($filter['exclude_groups']) && count($filter['exclude_groups']) > 0
                    && in_array($group['slug'], $filter['exclude_groups']))){


                continue;

            }

            $group_fields = $group_fields->where('vh_cms_content_id',$content->id)
                ->where('vh_cms_form_group_id',$group->id)
                ->groupBy('vh_cms_form_group_index');


            if(count($group_fields) === 0 ){
                $group_fields[] = '';
            }

            foreach ($group_fields as $key => $fields){


                $arr_group[$i][$key] = [
                    'id' => $group->id,
                    'name' => $group->name,
                    'slug' => $group->slug,
                    'is_repeatable' => $group->is_repeatable,
                ];

                $y = 0;

                foreach ($group['fields'] as $field)
                {

                    $arr_group[$i][$key]['fields'][$y] = [
                        'id' => $field->id,
                        'name' => $field->name,
                        'slug' => $field->slug,
                        'vh_cms_form_group_id' => $field->vh_cms_form_group_id,
                        'is_repeatable' => $field->is_repeatable,
                        'is_searchable' => $field->is_searchable,
                        'meta' => $field->meta
                    ];

                    $arr_group[$i][$key]['fields'][$y]['type'] = $field->type;


                    $arr_group[$i][$key]['fields'][$y]['vh_cms_form_field_id'] = null;
                    $arr_group[$i][$key]['fields'][$y]['content'] = null;
                    $arr_group[$i][$key]['fields'][$y]['content_meta'] = null;

                    $field_content = $fields_list->where('vh_cms_form_group_id', $group->id)
                        ->where('vh_cms_form_field_id', $field->id)
                        ->where('vh_cms_form_group_index', $key)->first();


                    if($field->type->slug == 'relation' && $field_content
                        && isset($field_content->contentFormRelations)
                        && count($field_content->contentFormRelations) > 0){

                        $field_content->content = $field_content['contentFormRelations']
                            ->pluck('relatable');
                    }


                    if($field_content)
                    {

                        $arr_group[$i][$key]['fields'][$y]['vh_cms_form_field_id'] = $field_content->id;

                        if(!$field->is_repeatable
                            && (is_array($field_content->content)
                                || is_object($field_content->content))
                            && $field->type->slug != 'seo-meta-tags'
                            && count($field_content->content) <= 1) {

                            $content_val = null;

                            if (count($field_content->content) == 1) {
                                $content_val = $field_content->content[0];
                            }

                        }elseif($field->is_repeatable
                            && is_string($field_content->content) ){
                            $content_val = [$field_content->content];
                        }else{
                            $content_val = $field_content->content;
                        }

                        $content_val = ContentFormField::getContentAsset($content_val, $field->type->slug);


                        $arr_group[$i][$key]['fields'][$y]['content'] = $content_val;

                        $arr_group[$i][$key]['fields'][$y]['content_meta'] = $field_content->meta;

                    }

                    $y++;

                }

            }

            $i++;

        }

        return $arr_group;
    }
    //-------------------------------------------------
    public static function postStore($request,$id)
    {

        $validation = static::validation($request);
        if(isset($validation['status']) && $validation['status'] == 'failed')
        {
            return $validation;
        }

        $name_exist = static::where('id','!=',$request['id'])
            ->where('vh_cms_content_type_id',$request['vh_cms_content_type_id'])
            ->where('name',$request['name'])->first();

        if($name_exist)
        {
            $response['status'] = 'failed';
            $response['errors'][] = "This name is already exist.";
            return $response;
        }

        $inputs = $request->all();

        $item = static::where('id',$id)->withTrashed()->first();

        $item->fill($inputs);
        $item->slug = Str::slug($inputs['name']);
        $item->save();


        static::storeFormGroups($item, $inputs['content_form_groups']);
        static::storeFormGroups($item, $inputs['template_form_groups']);


        $response['status'] = 'success';
        $response['data'] = [];
        $response['messages'][] = 'Data updated.';

        return $response;

    }
    //-------------------------------------------------
    public static function storeFormGroups(Content $content, $groups)
    {

        $i = 0;

        foreach ($groups as $arr_groups)
        {

            foreach ($arr_groups as $key =>  $group){

                $groups[$i] = $group;

                $y = 0;
                foreach ($group['fields'] as $field)
                {
                    $stored_field = null;
                    if(
                        isset($field['vh_cms_form_group_id'])
                        && isset($field['id'])
                    )
                    {

                        $stored_field = ContentFormField::where('vh_cms_form_group_id', $field['vh_cms_form_group_id'])
                            ->where('vh_cms_form_field_id', $field['id'])
                            ->where('vh_cms_content_id', $content->id)
                            ->where('vh_cms_form_group_index', $key)
                            ->first();

                    }

                    if(!$stored_field)
                    {
                        $stored_field = new ContentFormField();
                        $stored_field->vh_cms_content_id = $content->id;
                        $stored_field->vh_cms_form_group_id = $group['id'];
                        $stored_field->vh_cms_form_field_id = $field['id'];
                        $stored_field->vh_cms_form_group_index = $key;

                        $stored_field->save();
                    }

                    if(is_array($field['content']) || is_object($field['content'])){
                        $field['content'] = json_decode(
                            vh_translate_dynamic_strings(
                                json_encode($field['content'])
                            )
                        );
                    }else{
                        $field['content'] = vh_translate_dynamic_strings(
                            $field['content']
                        );
                    }

                    if($field['type']['slug'] == 'user' && $field['content']){

                        $user = $user_id = User::where('email',$field['content'])->first();

                        if($user)
                        {
                            $stored_field->content = $user->id;
                        }

                    }elseif($field['type']['slug'] == 'relation'){

                        $type_name = $content->contentType->slug.'-'.$field['slug'];

                        if(is_string($field['content']) && !is_numeric($field['content'])){
                            $item = Taxonomy::getFirstOrCreate($type_name,$field['content']);

                            $field['content'] = $item->id;
                        }

                        $related_item = ContentFormField::where('id',$stored_field->id)->first();

                        $field['meta'] = (array) $field['meta'];

                        if($field['content'] && isset($field['meta']['type'])){

                            if(!is_array($field['content']) && !is_object($field['content'])){
                                $field['content'] = [$field['content']];
                            }

                            $relation =  vh_content_relations_by_name($field['meta']['type']);

                            if($relation && isset($relation['namespace']) && $relation['namespace']){
                                foreach ($field['content'] as $id){
                                    $data = [
                                        'relatable_id' => $id,
                                        'relatable_type' => $relation['namespace']
                                    ];

                                    $related_item->contentFormRelations()->updateOrCreate($data);
                                }
                            }
                        }


                        $relatable_ids = ContentFormRelation::where('vh_cms_content_form_field_id',$related_item->id)
                            ->pluck('relatable_id')->toArray();

                        if(!$field['content']){
                            $row_to_delete_ids = array_diff($relatable_ids, []);
                        }else{
                            $row_to_delete_ids = array_diff($relatable_ids, $field['content']);
                        }

                        if(count($row_to_delete_ids) > 0)
                        {
                           ContentFormRelation::where('vh_cms_content_form_field_id', $related_item->id)
                                ->whereIn('relatable_id', $row_to_delete_ids)
                                ->forceDelete();

                        }

                        $stored_field->content = $field['content'];

                    }else{
                        $stored_field->content = $field['content'];
                    }

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
            }



            $i++;
        }

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
                $role->status = $request['data'];
            }else{
                if($role->status == 1){
                    $role->status = 0;
                }else{
                    $role->status = 1;
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

                $item->forceDelete();

            }
        }

        $response['status'] = 'success';
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;
    }
    //-------------------------------------------------
    public static function removeGroup($request)
    {

        $inputs = $request->inputs;

        ContentFormField::where([
            'vh_cms_content_id' => $inputs['content_id'],
            'vh_cms_form_group_id' => $inputs['group_id'],
            'vh_cms_form_group_index' => $inputs['index']])->forceDelete();


        $response['status'] = 'success';
        $response['data'] = [];

        return $response;
    }
    //-------------------------------------------------
    public static function getContents($content_type_slug, $args)
    {
        $content_type = ContentType::where('slug', $content_type_slug)->first();

        if(!$content_type)
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content Type not found.';
            return $response;
        }

        $order = 'desc';
        $order_by = 'id';

        if(isset($args['order'])
            && $args['order']){
            $order = $args['order'];
        }

        if(isset($args['order_by'])
            && $args['order_by']){
            $order_by = $args['order_by'];
        }

        $contents = Content::with(['fields' => function($t){
            $t->with(['contentFormRelations' => function($c){
                $c->with(['relatable']);
            }]);
        },'contentType' => function($q){
            $q->with(['groups' => function($g){
                $g->with(['fields' => function($f){
                    $f->with(['type']);

                }]);

            }]);

        }])->where('vh_cms_content_type_id', $content_type->id);


        if(isset($args['q'])
            && $args['q']){

            $contents->where(function ($q) use ($args){
                $q->where('name', 'LIKE', '%'.$args['q'].'%')
                    ->orWhere('slug', 'LIKE', '%'.$args['q'].'%')
                    ->orWhere('permalink', 'LIKE', '%'.$args['q'].'%');

                $q->orWhereHas('fields',function ($p) use ($args){
                    $p->where('content', 'LIKE', '%'.$args['q'].'%');
                    $p->whereHas('field', function ($f) {
                        $f->where('is_searchable' , 1);
                    });
                });

            });
        }

        $contents->orderBy($order_by,$order);

        if(isset($args['per_page'])
            && $args['per_page']
            && is_numeric($args['per_page'])){
            $contents = $contents->paginate($args['per_page']);
        }else{
            $contents = $contents->paginate(config('vaahcms.per_page'));
        }

        if(!$contents)
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content not found.';
            return $response;
        }

        $arr_include_groups = array();
        $arr_exclude_groups = array();

        if(isset($args['include_groups'])){
            if(is_string($args['include_groups'])){
                $arr_include_groups = explode(",",$args['include_groups']);
            }else{
                $arr_include_groups = $args['include_groups'];
            }

        }

        if(isset($args['exclude_groups'])){
            if(is_string($args['exclude_groups'])){
                $arr_exclude_groups = explode(",",$args['exclude_groups']);
            }else{
                $arr_exclude_groups = $args['exclude_groups'];
            }
        }

        $filter = [
            'include_groups' => $arr_include_groups,
            'exclude_groups' => $arr_exclude_groups
            ];

        $content_ids = $contents->pluck('id')->toArray();

        $group_fields = ContentFormField::whereIn('vh_cms_content_id',$content_ids)
            ->get();

        $group_fields = collect($group_fields);

        foreach ($contents as $key => $content){

            $contents[$key]['content_form_groups'] = static::getFormGroupsTest($content,
                'content',$group_fields,$filter);

            $arr_template = array();

            $contents[$key]['template_form_groups'] = $arr_template;

        }

        $response['status']                 = 'success';
        $response['data']['list']           = $contents;
        return $response;

    }
    //-------------------------------------------------
    public static function getListOfContents($content_type_slug)
    {

        $content_type = ContentType::where('slug', $content_type_slug)->first();

        if(!$content_type)
        {
            $response['status']     = 'failed';
            $response['errors']     = 'Content Type not found.';
            return $response;
        }

        $contents = Content::where('vh_cms_content_type_id', $content_type->id)
            ->orderBy('id','desc')->pluck('name');

        return $contents;

    }
    //-------------------------------------------------
    public static function getContent($id, $args, $output)
    {
        $content = static::find($id);

        $response = null;

        $content_groups = static::getFormGroups($content, 'content');

        switch ($output){

            case 'html':
                $response = static::getFormGroupsHtml($content_groups, 'get-the-content');
                break;

            default:
                $response = $content_groups;
                break;
        }


        return $response;
    }
    //-------------------------------------------------
    public static function getTheContent($id, $args)
    {
        return static::getContent($id, $args, 'html');
    }
    //-------------------------------------------------
    public static function getFormGroupsHtml($groups, $custom_class=null, $view=null)
    {
        $html = "";

        if(!$custom_class)
        {
            $custom_class = 'get-the-content';
        }

        if(!$view)
        {
            $view = 'cms::frontend.templates.contents.get-the-content';
        }

        $html = \View::make($view)->with('groups', $groups)
            ->with('custom_class', $custom_class)
            ->render();

        return $html;
    }
    //-------------------------------------------------
    public static function getContentField($item, $group_slug, $field_slug)
    {

        $group = FormGroup::where('slug', $group_slug)->whereHasMorph('groupable',
            [ContentType::class], function ($c) use ($item){
                //$c->where('id', $item->vh_cms_content_id);
        })->first();

        echo "<pre>";
        print_r($group->toArray());
        echo "</pre>";

    }
    //-------------------------------------------------
    public static function getNonRepeatableFields()
    {

        return ['seo-meta-tags','list',
            'image-group','facebook-card','twitter-card',
            'json','address','tags','select','relation'];

    }
    //-------------------------------------------------
    public static function getListByVariables($var)
    {

        $list = $var['namespace']::orderBy('created_at', 'DESC');

        if(isset($var['has_children']) && $var['has_children']){
            $list->with(['children']);
        }

        if(isset($var['filters'])){

            foreach ($var['filters'] as $filter){

                $query = $filter['query'];
                $column = $filter['column'];

                $list->$query(
                    $column,
                    $filter['condition']?$filter['condition']:"=",
                    $filter['value']?$filter['value']:null
                );
            }
        }

        if(isset($var['filter_by']) && $var['filter_by'] &&
            isset($var['filter_id']) && $var['filter_id']){
            $list->where($var['filter_by'],$var['filter_id']);
        }

        $list = $list->get();

        return $list;

    }
    //-------------------------------------------------

    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------

}
