<?php

namespace VaahCms\Modules\Cms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nestable\NestableTrait;

class MenuItem extends Model
{
    use NestableTrait;

    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'vh_cms_menu_items';
    //-------------------------------------------------
    protected $parent = 'parent_id';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------

    protected $fillable = [
        'name', 'slug', 'title', 'parent_id', 'depth', 'sort',
        'attr_id', 'attr_class', 'vh_menu_id', 'vh_page_id',
        'order', 'uri', 'is_active', 'vh_permission_slug',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    public function submenus(){
        return $this->hasMany(MenuItem::class, 'parent_id');
    }
    //-------------------------------------------------
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }
    //-------------------------------------------------
    public function childrens()
    {
        return $this->children()->with('childrens');
    }
    //-------------------------------------------------
    public function page()
    {
        return $this->belongsTo(Page::class, 'vh_page_id');
    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    public static function getNewDepth($menu_id)
    {

        $menu = MenuItem::where('id', $menu_id)->first();

        if($menu->depth)
        {
           return $menu->depth+1;
        } else
        {
            return 1;
        }


    }
    //-------------------------------------------------
    public static function hasParent($menu_id)
    {
        $menu = MenuItem::where('id', $menu_id)->first();

        if($menu)
        {
            if(!is_null($menu->parent_id))
            {
                return $menu->parent_id;
            } else
            {
                return false;
            }
        }

        return false;
    }

    //-------------------------------------------------
    public static function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = MenuItem::buildTree($elements, $element['id']);

                if ($children) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }
    //-------------------------------------------------

    //-------------------------------------------------
    public static function recursiveDelete($id){

        $parent = MenuItem::findOrFail($id);

        $array_of_ids = MenuItem::getChildrenIds($parent);

        array_push($array_of_ids, $id);

        MenuItem::destroy($array_of_ids);

    }

    //-------------------------------------------------

    public static function getChildrenIds($category){
        $ids = [];
        foreach ($category->childrens as $cat) {
            $ids[] = $cat->id;
            $ids = array_merge($ids, MenuItem::getChildrenIds($cat));
        }
        return $ids;

    }
    //-------------------------------------------------

    //-------------------------------------------------
}
