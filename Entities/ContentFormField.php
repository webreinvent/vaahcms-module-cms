<?php namespace VaahCms\Modules\Cms\Entities;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use WebReinvent\VaahCms\Entities\Taxonomy;
use WebReinvent\VaahCms\Entities\User;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;

class ContentFormField extends Model {

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_cms_content_form_fields';
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
        'vh_cms_form_group_id',
        'vh_cms_form_field_id',
        'vh_cms_form_group_index',
        'content',
        'meta',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    //-------------------------------------------------



    //-------------------------------------------------
    protected function serializeDate(DateTimeInterface $date)
    {
        $date_time_format = config('settings.global.datetime_format');

        return $date->format($date_time_format);

    }

    //-------------------------------------------------
    public function setContentAttribute($value)
    {
        if(is_array($value) || is_object($value))
        {
            $this->attributes['content'] = json_encode($value);
        } else{
            $this->attributes['content'] = $value;
        }
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
        return $this->belongsTo(FormGroup::class,
            'vh_cms_form_group_id', 'id'
        );
    }
    //-------------------------------------------------
    public function field()
    {
        return $this->belongsTo(FormField::class,
            'vh_cms_form_field_id', 'id'
        );
    }
    //-------------------------------------------------
    public function content()
    {
        return $this->belongsTo(Content::class,
            'vh_cms_content_id', 'id'
        );
    }
    //-------------------------------------------------
    public static function getContentAsset($content,$type)
    {
        $value = $content;

        if($content && $type && ($type == 'image' || $type == 'media'))
        {
            $value = asset($content);
        }

        return $value;
    }
    //-------------------------------------------------
    public function taxonomies()
    {
        return $this->morphedByMany(Taxonomy::class,
            'relatable',
            'vh_cms_content_form_relations',
            'vh_cms_content_form_field_id');
    }
    //-------------------------------------------------
    public function contentFormRelations()
    {
        return $this->hasMany(ContentFormRelation::class,
            'vh_cms_content_form_field_id', 'id');
    }

}
