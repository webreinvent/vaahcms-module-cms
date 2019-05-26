<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VhCmsMenuItem extends Model
{
    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'vh_cms_menu_items';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------

    protected $fillable = [
        'name', 'slug', 'title',
        'attr_id', 'attr_class', 'vh_menu_id',
        'order', 'uri', 'is_active', 'vh_permission_slug',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
