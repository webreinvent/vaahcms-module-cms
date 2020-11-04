<?php namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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
        'is_published_at',
        'status',
        'total_comments',
        'meta',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    //-------------------------------------------------

    //-------------------------------------------------

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



        $inputs = $request->all();


        $item = new static();

        $fillable['name'] = $inputs['name'];
        $fillable['slug'] = Str::slug($inputs['name']);
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


        foreach ($inputs['content_groups'] as $group)
        {

            foreach ($group['fields'] as $field)
            {
                $content_field = [];
                $content_field['vh_cms_content_id'] = $item->id;
                $content_field['vh_cms_form_group_id'] = $group['id'];
                $content_field['vh_cms_form_field_id'] = $field['id'];

                if(isset($field['content']))
                {
                    $content_field['content'] = $field['content'];
                    $store_field = new ContentFormField();
                    $store_field->fill($content_field);
                    $store_field->save();
                }

            }

        }

        foreach ($inputs['template_groups'] as $group)
        {

            foreach ($group['fields'] as $field)
            {
                $content_field = [];
                $content_field['vh_cms_content_id'] = $item->id;
                $content_field['vh_cms_form_group_id'] = $group['id'];
                $content_field['vh_cms_form_field_id'] = $field['id'];

                if(isset($field['content']))
                {
                    $content_field['content'] = $field['content'];
                    $store_field = new ContentFormField();
                    $store_field->fill($content_field);
                    $store_field->save();
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

            $list->where(function ($q) use ($request){
                $q->where('name', 'LIKE', '%'.$request->q.'%')
                    ->orWhere('slug', 'LIKE', '%'.$request->q.'%');
            });
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
            'name' => 'required',
            'status' => 'required',
            'vh_theme_id' => 'required',
            'vh_theme_template_id' => 'required'
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

        $content_form_groups = static::getFormGroups($item, 'content');
        $template_form_groups = static::getFormGroups($item, 'template');

        $response['status'] = 'success';
        $response['data'] = $item;
        $response['data']['content_form_groups'] = $content_form_groups;
        $response['data']['template_form_groups'] = $template_form_groups;

        return $response;

    }
    //-------------------------------------------------
    public static function getFormGroups(Content $content, $type, array $fields=null)
    {
        $groups = [];

        if($type=='content')
        {
            $groups = $content->contentType->groups;
        } else{
            $groups = $content->template->groups;
        }


        $i = 0;
        foreach ($groups as $group)
        {

            $groups[$i] = $group;

            $y = 0;
            foreach ($group->fields as $field)
            {
                $groups[$i]['fields'][$y] = $field;
                $groups[$i]['fields'][$y]['type'] = $field->type;


                $groups[$i]['fields'][$y]['vh_cms_form_field_id'] = null;
                $groups[$i]['fields'][$y]['content'] = null;
                $groups[$i]['fields'][$y]['content_meta'] = null;



                $field_content = ContentFormField::where('vh_cms_content_id', $content->id);
                $field_content->where('vh_cms_form_group_id', $group->id);
                $field_content->where('vh_cms_form_field_id', $field->id);
                $field_content = $field_content->first();


                if($field_content)
                {
                    $groups[$i]['fields'][$y]['vh_cms_form_field_id'] = $field_content->id;
                    $groups[$i]['fields'][$y]['content'] = $field_content->content;
                    $groups[$i]['fields'][$y]['content_meta'] = $field_content->meta;
                }


                $y++;
            }

            $i++;
        }

        return $groups;
    }
    //-------------------------------------------------
    //-------------------------------------------------
    public static function postStore($request,$id)
    {

        $validation = static::validation($request);
        if(isset($validation['status']) && $validation['status'] == 'failed')
        {
            return $validation;
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
        foreach ($groups as $group)
        {

            $groups[$i] = $group;

            $y = 0;
            foreach ($group['fields'] as $field)
            {
                $stored_field = null;
                if(isset($field['vh_cms_form_field_id']) && !empty($field['vh_cms_form_field_id']))
                {
                    $stored_field = ContentFormField::find($field['vh_cms_form_field_id']);
                }

                if(!$stored_field)
                {
                    $stored_field = new ContentFormField();
                    $stored_field->vh_cms_content_id = $content->id;
                    $stored_field->vh_cms_form_group_id = $group['id'];
                    $stored_field->vh_cms_form_field_id = $field['id'];
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
    public static function getContents($content_type_slug, $args)
    {

        $content_type = ContentType::where('slug', $content_type_slug)->first();



        $contents = static::where('vh_cms_content_type_id', $content_type->id);

        /*if($args['content_groups']) {

            $contents->whereHas('fields.group', function ($f) use ($args) {

                $group_slugs = array_keys($args['content_groups']);
                $f->whereIn('slug', $group_slugs);
            });

        }

        $contents->with(['fields.group.groupable']);*/

        /*if($args['content_groups'])
        {
            foreach($args['content_groups'] as $group)
            {
                $contents->whereHas('fields',  function ($g) use ($group){
                    $g->where('slug', $group['slug']);
                });
            }
            $contents->with('groups');
        }*/


        $contents = $contents->paginate(1);


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
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------

}
