<?php namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;

class GroupField extends Model {

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_cms_group_fields';
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
        'vh_cms_group_id',
        'vh_cms_field_id',
        'sort',
        'name',
        'slug',
        'excerpt',
        'is_searchable',
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

}
