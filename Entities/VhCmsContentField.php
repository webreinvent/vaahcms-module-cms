<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VhCmsContentField extends Model
{
    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'vh_cms_content_fields';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------

    protected $fillable = [
        'vh_page_id', 'vh_form_group_id', 'vh_form_group_order',
        'vh_form_field_id', 'vh_form_field_order', 'vh_form_field_type',
        'content', 'meta',

        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
