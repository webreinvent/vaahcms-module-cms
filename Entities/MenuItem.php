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
    public static function getNewSort($menu_id)
    {

        $menu = MenuItem::where('id', $menu_id)->first();


        //find menu on the same label
        $count_menus = MenuItem::where('parent_id', $menu->parent_id)
            ->where('depth', $menu->depth)
            ->count();

        if($menu->depth)
        {
            return $count_menus+1;
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
    public static function getTreeArray($menu_id)
    {
        $result = [];
        $i = 0;

        $list = MenuItem::where('vh_menu_id', $menu_id)

            ->get()->toArray();


        $tree = MenuItem::buildTree($list);





        $result = reset_child($tree);


        return $result;
    }
    //-------------------------------------------------
}
