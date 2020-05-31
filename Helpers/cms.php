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
function get_content_form_groups($id, $type=null, array $args = null, $output=null)
{



}
//-----------------------------------------------------------------------------------
function get_content_form_group($id, $type='content', array $args = null)
{

}
//-----------------------------------------------------------------------------------
function get_content_form_fields($id, $type=null, array $args = null)
{



}
//-----------------------------------------------------------------------------------
function get_content_form_field($id, $type='content', array $args = null)
{

}
//-----------------------------------------------------------------------------------
function get_template_form_groups($id, $type=null, array $args = null)
{



}
//-----------------------------------------------------------------------------------
function get_template_form_group($id, $type='content', array $args = null)
{

}
//-----------------------------------------------------------------------------------
function get_template_form_fields($id, $type=null, array $args = null)
{



}
//-----------------------------------------------------------------------------------
function get_template_form_field($id, $type='content', array $args = null)
{

}
//-----------------------------------------------------------------------------------

