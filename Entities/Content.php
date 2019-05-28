<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'vh_cms_contents';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------

    protected $fillable = [
        'contentable_type', 'contentable_id', 'vh_cms_form_group_id',
        'vh_cms_form_field_id', 'content', 'meta',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    public function getContentAttribute($value) {

        if(is_null($value))
        {
            return "";
        }

        return $value;
    }
    //-------------------------------------------------
    public function contentable()
    {
        return $this->morphTo();
    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
