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
function get_field(Content $content, $field_slug, $group_slug='default', $type='content', $return_html=true )
{
    if($type=='content')
    {
        return get_content_field($content, $field_slug, $group_slug, $return_html);
    } else {
        return get_template_field($content, $field_slug, $group_slug, $return_html);
    }
}
//-----------------------------------------------------------------------------------
function get_content_field(Content $content, $field_slug, $group_slug='default', $return_html=true)
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

    if(!$return_html){
        return $value;
    }

    if($field_slug=='seo-meta-tags')
    {
        $value = '<title>'.$field->content->seo_title->content.'</title>'."\n";
        $value .= '<meta name="description" content="'.$field->content->seo_description->content.'">'."\n";
        $value .= '<meta name="keywords" content="'.$field->content->seo_keywords->content.'">'."\n";
    }

    if($field_slug=='list')
    {

        $value = '<ul>'."\n";

        foreach ($field->content as $item){
            $value .= '<li>'.$item.'</li>'."\n";
        }
        $value .= '</ul>';
    }

    if($field_slug=='t-crad')
    {

        $value = '<meta name="twitter:card" content="summary" />'."\n";
        $value .= '<meta name="twitter:site" content="'.$field->content->twitter_site->content.'">'."\n";
        $value .= '<meta name="twitter:title" content="'.$field->content->twitter_title->content.'">'."\n";
        $value .= '<meta name="twitter:description" content="'.$field->content->twitter_description->content.'">'."\n";
        $value .= '<meta name="twitter:image" content="'.$field->content->twitter_imaage->content.'">'."\n";
    }

    if($field_slug=='f-card')
    {

        $value = '<meta name="og:title" content="'.$field->content->og_title->content.'">'."\n";
        $value .= '<meta name="og:description" content="'.$field->content->og_description->content.'">'."\n";
        $value .= '<meta name="og:image" content="'.$field->content->og_image->content.'">'."\n";
    }

    if($field_slug=='address')
    {
        $value = '<address>'."\n";

        $value .= $field->content->address_line_1->content.', '.$field->content->address_line_2->content."</br>";
        $value .= $field->content->city->content.', '.$field->content->state->content."</br>";
        $value .= $field->content->landmark->content."</br>";
        $value .= $field->content->country->content.', '.$field->content->zip_code->content;

        $value .= '</address>';
    }

    return $value;

}


//-----------------------------------------------------------------------------------
function get_template_field(Content $content, $field_slug, $group_slug='default', $return_html=true)
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

    $value = $field->content;

    if(!$return_html){
        return $value;
    }

    if($field_slug=='seo-meta-tags')
    {
        $value = '<title>'.$field->content->seo_title->content.'</title>'."\n";
        $value .= '<meta name="description" content="'.$field->content->seo_description->content.'">'."\n";
        $value .= '<meta name="keywords" content="'.$field->content->seo_keywords->content.'">'."\n";
    }

    if($field_slug=='list')
    {

        $value = '<ul>'."\n";

        foreach ($field->content as $item){
            $value .= '<li>'.$item.'</li>'."\n";
        }
        $value .= '</ul>';
    }

    if($field_slug=='t-crad')
    {

        $value = '<meta name="twitter:card" content="summary" />'."\n";
        $value .= '<meta name="twitter:site" content="'.$field->content->twitter_site->content.'">'."\n";
        $value .= '<meta name="twitter:title" content="'.$field->content->twitter_title->content.'">'."\n";
        $value .= '<meta name="twitter:description" content="'.$field->content->twitter_description->content.'">'."\n";
        $value .= '<meta name="twitter:image" content="'.$field->content->twitter_imaage->content.'">'."\n";
    }

    if($field_slug=='f-card')
    {

        $value = '<meta name="og:title" content="'.$field->content->og_title->content.'">'."\n";
        $value .= '<meta name="og:description" content="'.$field->content->og_description->content.'">'."\n";
        $value .= '<meta name="og:image" content="'.$field->content->og_image->content.'">'."\n";
    }

    if($field_slug=='address')
    {
        $value = '<address>'."\n";

        $value .= $field->content->address_line_1->content.', '.$field->content->address_line_2->content."</br>";
        $value .= $field->content->city->content.', '.$field->content->state->content."</br>";
        $value .= $field->content->landmark->content."</br>";
        $value .= $field->content->country->content.', '.$field->content->zip_code->content;

        $value .= '</address>';
    }

    return $value;
}
//-----------------------------------------------------------------------------------

