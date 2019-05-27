<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormGroup extends Model
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
        'groupable_type', 'groupable_id',
        'name', 'slug', 'excerpt',
        'is_repeatable',
        'is_auto_generated',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    public function groupable()
    {
        return $this->morphTo();
    }
    //-------------------------------------------------
    public function fields()
    {
        return $this->hasMany('VaahCms\Modules\Cms\Entities\FormField',
            'vh_cms_form_group_id', 'id');
    }
    //-------------------------------------------------
    //-------------------------------------------------
}
