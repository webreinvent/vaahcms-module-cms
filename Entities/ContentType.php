<?php namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    //-------------------------------------------------

}
