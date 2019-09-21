<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;


    //-------------------------------------------------
    protected $table = 'vh_cms_posts';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------

    protected $fillable = [
        'title', 'slug', 'details',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------

    //-------------------------------------------------
    //-------------------------------------------------
    public function setSlugAttribute()
    {
        $this->attributes['slug'] = Str::slug($this->title);
    }
    //-------------------------------------------------

    //-------------------------------------------------
}
