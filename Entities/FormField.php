<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormField extends Model
{
    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'vh_cms_form_fields';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------

    protected $fillable = [
        'fieldable_id', 'fieldable_type',
        'vh_cms_form_group_id', 'name', 'slug', 'excerpt',
        'type', 'order', 'is_repeatable',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    public function fieldable()
    {
        return $this->morphTo();
    }
    //-------------------------------------------------
    public function content($table_id)
    {
        return $this->hasMany('VaahCms\Modules\Cms\Entities\Content',
            'vh_form_field_id', 'id')
            ->where('vh_contents.table_id', $table_id);
    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
