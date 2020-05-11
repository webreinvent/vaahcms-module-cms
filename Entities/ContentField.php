<?php namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use WebReinvent\VaahCms\Entities\User;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;

class ContentField extends Model {

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_cms_content_fields';
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
        'vh_cms_content_id',
        'vh_cms_group_id',
        'vh_cms_group_field_id',
        'content',
        'meta',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    //-------------------------------------------------
    public function setContentAttribute($value)
    {
        if(is_array($value) || is_object($value))
        {
            $this->attributes['content'] = json_encode($value);
        }

        $this->attributes['content'] = $value;

    }
    //-------------------------------------------------
    public function getContentAttribute($value)
    {

        if(!$value)
        {
            return null;
        }

        if($value)
        {

            if(vh_is_json($value))
            {
                return json_decode($value);
            }


            $slug = $this->field->type->slug;

            if($slug == 'image' || $slug == 'media')
            {
                $value = asset($value);
            }

            return $value;
        }


        return null;
    }
    //-------------------------------------------------
    public function setMetaAttribute($value)
    {
        if($value)
        {
            $this->attributes['meta'] = json_encode($value);
        }
        $this->attributes['meta'] = null;
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

    //-------------------------------------------------
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
    public function group()
    {
        return $this->belongsTo(Group::class,
            'vh_cms_group_id', 'id'
        );
    }
    //-------------------------------------------------
    public function field()
    {
        return $this->belongsTo(GroupField::class,
            'vh_cms_group_field_id', 'id'
        );
    }
    //-------------------------------------------------

}
