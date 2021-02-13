<?php

/*
|--------------------------------------------------------------------------
| Naming conventions
|--------------------------------------------------------------------------
|
| <prefix>_<types> (plural): will return list of the items with pagination
| <prefix>_<types>_all (plural): will return list of all items
| <prefix>_<types>_count (plural): will return count of items
| <prefix>_<type>  (singular): will return one single record
| $args = ['select' => '', 'where' => [], ]
|
*/



//-----------------------------------------------------------------------------------
use VaahCms\Modules\Cms\Entities\Content;

function get_content_types(array $args = null)
{




}
//-----------------------------------------------------------------------------------
function get_content_type($id, array $args = null)
{


}
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
function get_contents($content_type_slug='pages', array $args = null)
{

    $output = \VaahCms\Modules\Cms\Entities\Content::getContents($content_type_slug, $args);

    return $output;

}
//-----------------------------------------------------------------------------------
function get_content($id, array $args = null, $output=null)
{



}
//-----------------------------------------------------------------------------------
function get_the_content($id, array $args = null, $output='html')
{
    $output = \VaahCms\Modules\Cms\Entities\Content::getTheContent($id, $args, $output);
    return $output;
}
//-----------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------
function get_field(Content $content, $field_slug, $group_slug='default', $type='content' )
{
    if($type=='content')
    {
        return get_content_field($content, $field_slug, $group_slug);
    } else {
        return get_template_field($content, $field_slug, $group_slug);
    }
}
//-----------------------------------------------------------------------------------
function get_content_field(Content $content, $field_slug, $group_slug='default')
{

    if(!isset($content->content_form_groups)
    || $content->content_form_groups->count() < 1
    )
    {
        return null;
    }

    $group = $content->content_form_groups->where('slug', $group_slug)->first();

    if(!$group)
    {
        return null;
    }


    $field = $group->fields->where('slug', $field_slug)->first();

    if(!$field)
    {
        return null;
    }

    $value = $field->content;

    if($field_slug=='seo-meta-tags')
    {
        $value = '<title>'.$field->content->seo_title->content.'</title>'."\n";
        $value .= '<meta name="description" content="'.$field->content->seo_description->content.'">'."\n";
        $value .= '<meta name="keywords" content="'.$field->content->seo_keywords->content.'">'."\n";
    }

    if(is_object($value) || is_array($value)){
        return json_encode($value);
    }

    return $value;

}


//-----------------------------------------------------------------------------------
function get_template_field(Content $content, $field_slug, $group_slug='default')
{

    if(!isset($content->template_form_groups)
        || $content->template_form_groups->count() < 1
    )
    {
        return null;
    }

    $group = $content->template_form_groups->where('slug', $group_slug)->first();

    if(!$group)
    {
        return null;
    }


    $field = $group->fields->where('slug', $field_slug)->first();

    if(!$field)
    {
        return null;
    }

    return $field->content;
}
//-----------------------------------------------------------------------------------

