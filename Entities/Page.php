<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use WebReinvent\VaahCms\Entities\Theme;
use WebReinvent\VaahCms\Entities\ThemeTemplate;

class Page extends Model
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
        'content',
        'attr_id', 'attr_class', 'page_template',
        'status', 'visibility', 'meta',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    public function setSlugAttribute( $value ) {
        $this->attributes['slug'] = str_slug( $value );
    }
    //-------------------------------------------------
    public function scopeSlug( $query, $slug ) {
        return $query->where( 'slug', $slug );
    }
    //-------------------------------------------------
    public function formGroups()
    {
        return $this->morphMany('VaahCms\Modules\Cms\Entities\FormGroup', 'groupable');
    }
    //-------------------------------------------------
    public function formFields()
    {
        return $this->morphMany('VaahCms\Modules\Cms\Entities\FormField', 'fieldable');
    }
    //-------------------------------------------------

    public static function getAssetsList()
    {

        $pages = Page::all();

        $result = [];
        $i = 0;
        foreach ($pages as $page)
        {

            $result[$i]['label'] = $page->name;
            $result[$i]['code'] = $page->id;

        }

        return $result;

    }

    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
