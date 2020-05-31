<?php namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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

        //delete form group fields which are just removed
        $stored_group_fields = FormField::where('vh_cms_form_group_id', $group->id)
            ->get()
            ->pluck('id')
            ->toArray();

        $input_group_fields = collect($fields_array)->pluck('id')->toArray();
        $fields_to_delete = array_diff($stored_group_fields, $input_group_fields);

        if(count($fields_to_delete) > 0)
        {
            FormField::deleteItems($fields_to_delete);
        }


        if(count($fields_array) > 0 )
        {
            foreach ($fields_array as $f_index => $field)
            {
                if(isset($field['id']))
                {
                    $stored_field = FormField::find($field['id']);
                } else{
                    $stored_field = new FormField();
                }

                if(isset($field['type']) && isset($field['type']['slug']) )
                {
                    $type = FieldType::where('slug', $field['type']['slug'])->first();
                    if($type)
                    {
                        $field['vh_cms_field_type_id'] = $type->id;
                    }

                    unset($field['type']);
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
