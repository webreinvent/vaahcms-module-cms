<?php

namespace VaahCms\Modules\Cms\Entities;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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


    //-------------------------------------------------

    protected $fillable = [
        'type', 'name', 'slug', 'title', 'parent_id', 'depth', 'sort',
        'attr_id', 'attr_class', 'attr_target_blank', 'vh_menu_id',
        'vh_content_id',
        'order', 'is_home', 'uri', 'is_active', 'vh_permission_slug',
        'meta',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------



    //-------------------------------------------------
    protected function serializeDate(DateTimeInterface $date)
    {
        $date_time_format = config('settings.global.datetime_format');

        return $date->format($date_time_format);

    }
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
    public function content()
    {
        return $this->belongsTo(Content::class, 'vh_content_id');
    }
    //-------------------------------------------------
    //-------------------------------------------------
    public function getAttrTargetBlankAttribute($value)
    {
        if($value === 1)
        {
            return true;
        }
        return false;
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
    public static function deleteItem($id)
    {
        //delete group
        static::where('id', $id)->forceDelete();
    }
    //-------------------------------------------------
    public static function deleteItems($ids_array){

        foreach ($ids_array as $id)
        {
            static::deleteItem($id);
        }

    }

    //-------------------------------------------------
    public static function storeItems($menu_id, $parent_id, $items)
    {

        if(count($items)>0)
        {
            static::syncChildItems($menu_id, $parent_id, $items);

            foreach ($items as $index => $item)
            {

                $stored_item = null;

                if(empty($item))
                {
                    continue;
                }

                $item['sort'] = $index;
                $item['parent_id'] = $parent_id;
                $item['vh_menu_id'] = $menu_id;
                $item['slug'] = Str::slug($item['name']);

                if(isset($item['id']) && !empty($item['id']))
                {
                    $stored_item = MenuItem::find($item['id']);

                }

                if(!isset($stored_item) || !$stored_item){
                    $stored_item = new MenuItem();
                }

                if(isset($item['content']) && isset($item['content']['id'])
                    && $item['content']['id']){
                    $item['vh_content_id'] = $item['content']['id'];
                }

                $stored_item->fill($item);
                $stored_item->save();

                if(isset($item['child']) && count($item['child']) > 0)
                {
                    static::storeItems($menu_id, $stored_item->id, $item['child']);
                }


            }
        }

    }
    //-------------------------------------------------
    public static function syncChildItems($menu_id, $parent_id, $items)
    {
        $existing_items = static::where('vh_menu_id', $menu_id);
        if($parent_id)
        {
            $existing_items->where('parent_id', $parent_id);
        }

        $existing_items = $existing_items->get()->pluck('id')->toArray();

        $input_items = collect($items)->pluck('id')->toArray();

        $items_to_delete = array_diff($existing_items, $input_items);

        if(count($items_to_delete) > 0)
        {
            MenuItem::deleteItems($items_to_delete);
        }
    }
    //-------------------------------------------------
    public static function getHomePage()
    {
        $menu_item = MenuItem::where('is_home', 1);
        $menu_item->with(['content'=>function($c){
            $c->with(['theme'=>function($theme){
                $theme->select('id', 'name', 'slug', 'is_default', 'is_active');
            }]);
            $c->with(['template']);
        }]);
        $menu_item = $menu_item->first();

        if(!$menu_item || !$menu_item->content)
        {
            return false;
        }

        $content_form_groups = Content::getFormGroups($menu_item->content, 'content');
        $template_form_groups = Content::getFormGroups($menu_item->content, 'template');



        $menu_item->content->content_form_groups = $content_form_groups;
        $menu_item->content->template_form_groups = $template_form_groups;


        return $menu_item;

    }
    //-------------------------------------------------
}
