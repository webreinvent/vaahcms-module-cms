<?php namespace VaahCms\Modules\Cms\Entities;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use WebReinvent\VaahCms\Entities\TaxonomyType;
use WebReinvent\VaahCms\Entities\User;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;

class FormGroup extends Model {

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_cms_form_groups';
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
        'parent_id',
        'uuid',
        'sort',
        'groupable_id',
        'groupable_type',
        'name',
        'slug',
        'excerpt',
        'is_repeatable',
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
    public function groupable()
    {
        return $this->morphTo();
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
    //-------------------------------------------------
    public function fields()
    {
        return $this->hasMany(FormField::class,
            'vh_cms_form_group_id', 'id'
        )->orderBy('sort', 'asc');
    }
    //-------------------------------------------------
    public function contentFields()
    {
        return $this->hasMany(ContentFormField::class,
            'vh_cms_form_group_id', 'id'
        );
    }
    //-------------------------------------------------
    public static function deleteItem($id)
    {

        //delete content fields
        ContentFormField::where('vh_cms_form_group_id', $id)->forceDelete();

        //delete group fields
        FormField::where('vh_cms_form_group_id', $id)->forceDelete();

        //delete group
        static::where('id', $id)->forceDelete();

    }
    //-------------------------------------------------
    public static function deleteItems($ids_array){

        foreach ($ids_array as $id)
        {
            static::deleteItem($id);
        }

    }
    //-------------------------------------------------
    public static function syncWithFormFields(FormGroup $group, $fields_array)
    {

        if(count($fields_array) < 1)
        {
            return false;
        }


        //delete form group fields which are just removed
        $stored_group_fields = FormField::where('vh_cms_form_group_id', $group->id)
            ->get()
            ->pluck('slug')
            ->toArray();

        $input_group_field_slugs = collect($fields_array)->pluck('slug')->toArray();
        $fields_to_delete_slugs = array_diff($stored_group_fields, $input_group_field_slugs);


        if(count($fields_to_delete_slugs) > 0)
        {
            $fields_to_delete = FormField::where('vh_cms_form_group_id', $group->id)
                ->whereIn('slug', $fields_to_delete_slugs)
                ->get()
                ->pluck('id')
                ->toArray();

            if(count($fields_to_delete) > 0)
            {
                FormField::deleteItems($fields_to_delete);
            }
        }

        if(count($fields_array) > 0 )
        {
            foreach ($fields_array as $f_index => $field)
            {

                if(!isset($field['slug']) || !$field['slug']){
                    $field['slug'] = Str::slug($field['name']);
                }


                $stored_field = FormField::where('vh_cms_form_group_id', $group->id)
                    ->where('slug', $field['slug'])
                    ->first();



                if(isset($field['type']) && isset($field['type']['slug']) )
                {
                    $type = FieldType::where('slug', $field['type']['slug'])->first();
                    if($type)
                    {
                        $field['vh_cms_field_type_id'] = $type->id;

                        if(isset($field['meta'])){
                            $field['meta'] = array_merge((array) $type['meta'],$field['meta']);
                        }else{
                            $field['meta'] = (array) $type['meta'];
                        }

                    }

                    if($field['type']['slug'] == 'relation'){

                        if($stored_field){

                            if($stored_field->meta && isset($stored_field->meta->type)
                                && $field['meta']['type'] != $stored_field->meta->type){
                                $content_form_fields = ContentFormField::with(['contentFormRelations'])
                                    ->where('vh_cms_form_field_id', $field['id'])->get();


                                foreach($content_form_fields as $form_field){
                                    $form_field->contentFormRelations()->forceDelete();
                                    $form_field->content = null;
                                    $form_field->save();
                                }
                            }

                        }

                    }

                    unset($field['type']);
                }

                if(!$stored_field)
                {
                    $stored_field = new FormField();
                }

                $stored_field->fill($field);
                $stored_field->sort = $f_index;
                $stored_field->slug = Str::slug($field['name']);
                $stored_field->vh_cms_form_group_id = $group->id;
                $stored_field->save();

            }
        }


    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------

}
