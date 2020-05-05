<?php namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;

class Group extends Model {

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_cms_groups';
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
        'vh_cms_content_type_id',
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

}
