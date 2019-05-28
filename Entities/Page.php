<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        'vh_theme_template_id', 'vh_cms_page_id', 'name', 'title', 'slug',
        'content',
        'attr_id', 'attr_class',
        'status', 'visibility', 'meta', 'published_at',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    protected $appends  = [
        'label', 'code'
    ];

    //-------------------------------------------------
    public function getLabelAttribute() {

        $list = page_statuses();

        if(isset($list[$this->status]))
        {
            return $list[$this->status];
        }

        return null;
    }
    //-------------------------------------------------
    public function getCodeAttribute() {
        return $this->status;
    }
    //-------------------------------------------------
    public function setSlugAttribute( $value ) {
        $this->attributes['slug'] = str_slug( $value );
    }
    //-------------------------------------------------
    public function scopeSlug( $query, $slug ) {
        return $query->where( 'slug', $slug );
    }
    //-------------------------------------------------
    public function scopeStatus( $query, $status ) {
        return $query->where( 'status', $status );
    }
    //-------------------------------------------------
    public function formGroups()
    {
        return $this->morphMany(FormGroup::class, 'groupable');
    }
    //-------------------------------------------------
    public function contents()
    {
        return $this->morphMany(Content::class, 'contentable');
    }
    //-------------------------------------------------
    public function template()
    {
        return $this->belongsTo(ThemeTemplate::class,
            'vh_theme_template_id', 'id'
        );
    }
    //-------------------------------------------------

    public static function getAssetsList()
    {

        $pages = Page::all();

        $result = [];
        $i = 0;
        foreach ($pages as $page)
        {

            $result[$i]['name'] = $page->name;
            $result[$i]['id'] = $page->id;

        }

        return $result;

    }

    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
