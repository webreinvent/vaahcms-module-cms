<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'vh_cms_menus';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------

    protected $fillable = [
        'name', 'slug', 'attr_id',
        'attr_class', 'vh_theme_location_id', 'vh_permission_slug',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
