<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VhCmsFormGroup extends Model
{
    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'vh_cms_form_groups';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------

    protected $fillable = [
        'name', 'slug', 'excerpt',
        'is_repeatable',

        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
