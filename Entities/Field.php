<?php namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;

class Field extends Model {

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_cms_fields';
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
        'name',
        'slug',
        'excerpt',
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
