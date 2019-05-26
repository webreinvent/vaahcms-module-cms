<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VhCmsPage extends Model
{
    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'vh_cms_pages';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------
    protected $fillable = [
        'vh_cms_page_id', 'name', 'title', 'slug',
        'attr_id', 'attr_class', 'layout',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
